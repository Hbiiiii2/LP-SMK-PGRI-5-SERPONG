<?php

namespace App\Controllers\Admin;

use App\Models\ProgramModel;
use App\Models\SpmbModel;
use CodeIgniter\HTTP\Files\UploadedFile;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;

class Spmb extends BaseController
{
    protected SpmbModel $spmbModel;

    public function initController($request, $response, $logger): void
    {
        parent::initController($request, $response, $logger);
        $this->spmbModel = new SpmbModel();
    }

    /**
     * Invalidate cached dashboard counters and recent feeds after SPMB changes.
     */
    private function clearDashboardCache(): void
    {
        cache()->delete('admin_dashboard_stats');
        cache()->delete('admin_dashboard_recent');
    }

    /**
     * Return available major options from active programs (plus vocational fallbacks).
     *
     * @return list<string>
     */
    private function resolveProgramOptions(): array
    {
        $programs = new ProgramModel()->getOrdered(50);
        $titles   = [];
        foreach ($programs as $p) {
            $t = trim((string) ($p['title'] ?? ''));
            if ($t !== '') {
                $titles[] = $t;
            }
        }

        return $titles !== [] ? $titles : [
            'TEKNIK KOMPUTER DAN JARINGAN',
            'Rekayasa Perangkat Lunak',
            'Akuntansi dan Keuangan Lembaga (AKL)',
            'Manajemen Perkantoran dan Layanan Bisnis (MPLB)',
        ];
    }

    /**
     * Paginated list of SPMB applicants with filters and one-click WA status actions.
     */
    public function index(): string
    {
        $status  = trim((string) ($this->request->getGet('status') ?? ''));
        $program = trim((string) ($this->request->getGet('program') ?? ''));
        $q       = trim((string) ($this->request->getGet('q') ?? ''));

        $allowedStatuses = ['pending', 'verified', 'accepted', 'revision', 'rejected'];
        if (! in_array($status, $allowedStatuses, true)) {
            $status = '';
        }

        if ($status !== '') {
            $this->spmbModel->where('status', $status);
        }

        if ($program !== '') {
            $this->spmbModel->groupStart()
                ->where('program_choice_1', $program)
                ->orWhere('program_choice_2', $program)
                ->groupEnd();
        }

        if ($q !== '') {
            $this->spmbModel->groupStart()
                ->like('registration_no', $q)
                ->orLike('full_name', $q)
                ->orLike('nisn', $q)
                ->orLike('origin_school', $q)
                ->orLike('parent_name', $q)
                ->orLike('parent_phone', $q)
                ->groupEnd();
        }

        $this->data['title']           = 'Manajemen SPMB Online';
        $this->data['items']           = $this->spmbModel->orderBy('created_at', 'DESC')->orderBy('id', 'DESC')->paginate(15);
        $this->data['pager']           = $this->spmbModel->pager;
        $this->data['stats']           = $this->spmbModel->getStats();
        $this->data['program_options'] = $this->resolveProgramOptions();
        $this->data['filter_status']   = $status;
        $this->data['filter_program']  = $program;
        $this->data['filter_q']        = $q;
        $this->data['spmb_model']      = $this->spmbModel;

        return view('admin/spmb/index', $this->data);
    }

    /**
     * Show detailed applicant view with uploaded documents, verification controls, and WA sender.
     */
    public function show(int $id): string|RedirectResponse
    {
        $item = $this->spmbModel->find($id);
        if (! $item) {
            return redirect()->to('/admin/spmb')->with('error', 'Data pendaftar SPMB tidak ditemukan.');
        }

        if (empty($item['is_read'])) {
            $this->spmbModel->update($id, ['is_read' => 1]);
            $item['is_read'] = 1;
            $this->clearDashboardCache();
        }

        $siteName = (string) ($this->data['site_name'] ?? 'SMK PGRI 5 Serpong');

        $this->data['title']          = 'Detail Pendaftar ' . ($item['registration_no'] ?? '');
        $this->data['item']           = $item;
        $this->data['spmb_model']     = $this->spmbModel;
        $this->data['default_wa_msg'] = $this->spmbModel->buildWhatsAppMessage($item, $siteName);
        $this->data['wa_url']         = $this->spmbModel->buildWhatsAppUrl($item, $siteName);

        return view('admin/spmb/show', $this->data);
    }

    /**
     * Update applicant verification status & document completion, optionally redirecting directly to WhatsApp.
     */
    public function verify(int $id): RedirectResponse
    {
        $item = $this->spmbModel->find($id);
        if (! $item) {
            return redirect()->to('/admin/spmb')->with('error', 'Data pendaftar SPMB tidak ditemukan.');
        }

        $allowedStatuses = ['pending', 'verified', 'accepted', 'revision', 'rejected'];
        $status          = (string) ($this->request->getPost('status') ?? 'pending');
        if (! in_array($status, $allowedStatuses, true)) {
            $status = 'pending';
        }

        $docsComplete = $this->request->getPost('docs_complete') ? 1 : 0;
        $adminNotes   = trim((string) ($this->request->getPost('admin_notes') ?? ''));
        $sendWa       = $this->request->getPost('send_wa') === '1';

        $updateData = [
            'status'        => $status,
            'docs_complete' => $docsComplete,
            'admin_notes'   => $adminNotes !== '' ? $adminNotes : null,
            'is_read'       => 1,
        ];

        if ($sendWa) {
            $updateData['wa_notified_at'] = date('Y-m-d H:i:s');
        }

        $this->spmbModel->update($id, $updateData);
        $this->clearDashboardCache();

        if ($sendWa) {
            $fresh    = $this->spmbModel->find($id) ?: array_merge($item, $updateData);
            $siteName = (string) ($this->data['site_name'] ?? 'SMK PGRI 5 Serpong');
            $customWa = trim((string) ($this->request->getPost('wa_message') ?? ''));
            $waUrl    = $this->spmbModel->buildWhatsAppUrl($fresh, $siteName, $customWa !== '' ? $customWa : null);

            if ($waUrl !== '') {
                return redirect()->to($waUrl);
            }

            return redirect()->to('/admin/spmb/' . $id)->with('error', 'Nomor WhatsApp orang tua/wali tidak valid atau kosong.');
        }

        return redirect()->to('/admin/spmb/' . $id)->with('success', 'Status verifikasi pendaftaran berhasil diperbarui.');
    }

    /**
     * One-click WhatsApp notification launcher for an applicant's parent/guardian.
     */
    public function sendWa(int $id): RedirectResponse
    {
        $item = $this->spmbModel->find($id);
        if (! $item) {
            return redirect()->to('/admin/spmb')->with('error', 'Data pendaftar SPMB tidak ditemukan.');
        }

        $updateData = [
            'wa_notified_at' => date('Y-m-d H:i:s'),
            'is_read'        => 1,
        ];

        if ($this->request->getGet('auto_verify') === '1') {
            if (($item['status'] ?? 'pending') === 'pending') {
                $updateData['status'] = 'verified';
            }
            $updateData['docs_complete'] = 1;
        }

        $this->spmbModel->update($id, $updateData);
        $this->clearDashboardCache();

        $fresh    = $this->spmbModel->find($id) ?: array_merge($item, $updateData);
        $siteName = (string) ($this->data['site_name'] ?? 'SMK PGRI 5 Serpong');
        $waUrl    = $this->spmbModel->buildWhatsAppUrl($fresh, $siteName);

        if ($waUrl === '') {
            return redirect()->back()->with('error', 'Nomor WhatsApp orang tua/wali belum diisi atau formatnya tidak valid.');
        }

        return redirect()->to($waUrl);
    }

    /**
     * Display form to manually add a new SPMB applicant from Admin.
     */
    public function new(): string
    {
        $this->data['title']           = 'Tambah Pendaftar SPMB';
        $this->data['item']            = [];
        $this->data['program_options'] = $this->resolveProgramOptions();
        $this->data['spmb_model']      = $this->spmbModel;

        return view('admin/spmb/form', $this->data);
    }

    /**
     * Create a new SPMB applicant from Admin.
     */
    public function create(): RedirectResponse
    {
        [$data, $errors] = $this->collectPayload(null);
        if (! empty($errors)) {
            return redirect()->back()->withInput()->with('errors', $errors)->with('error', implode(' ', $errors));
        }

        $data['registration_no'] = $this->spmbModel->generateRegistrationNo();
        $data['is_read']         = 1;

        if (! $this->spmbModel->insert($data)) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data pendaftar SPMB.');
        }

        $newId = (int) $this->spmbModel->getInsertID();
        $this->clearDashboardCache();

        return redirect()->to('/admin/spmb/' . $newId)->with('success', 'Pendaftar SPMB berhasil ditambahkan (' . $data['registration_no'] . ').');
    }

    /**
     * Display form to edit an existing SPMB applicant.
     */
    public function edit(int $id): string|RedirectResponse
    {
        $item = $this->spmbModel->find($id);
        if (! $item) {
            return redirect()->to('/admin/spmb')->with('error', 'Data pendaftar SPMB tidak ditemukan.');
        }

        $this->data['title']           = 'Edit Pendaftar ' . ($item['registration_no'] ?? '');
        $this->data['item']            = $item;
        $this->data['program_options'] = $this->resolveProgramOptions();
        $this->data['spmb_model']      = $this->spmbModel;

        return view('admin/spmb/form', $this->data);
    }

    /**
     * Update an existing SPMB applicant record and optionally upload/remove documents.
     */
    public function update(int $id): RedirectResponse
    {
        $existing = $this->spmbModel->find($id);
        if (! $existing) {
            return redirect()->to('/admin/spmb')->with('error', 'Data pendaftar SPMB tidak ditemukan.');
        }

        [$data, $errors] = $this->collectPayload($existing);
        if (! empty($errors)) {
            return redirect()->back()->withInput()->with('errors', $errors)->with('error', implode(' ', $errors));
        }

        if (! $this->spmbModel->update($id, $data)) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui data pendaftar SPMB.');
        }

        $this->clearDashboardCache();

        return redirect()->to('/admin/spmb/' . $id)->with('success', 'Data pendaftar SPMB berhasil diperbarui.');
    }

    /**
     * Delete an SPMB applicant record and its uploaded documents.
     */
    public function delete(int $id): RedirectResponse
    {
        $item = $this->spmbModel->find($id);
        if (! $item) {
            return redirect()->to('/admin/spmb')->with('error', 'Data pendaftar SPMB tidak ditemukan.');
        }

        foreach (array_keys(SpmbModel::DOC_FIELDS) as $field) {
            $this->deleteStoredDoc($item[$field] ?? null);
        }

        $this->spmbModel->delete($id);
        $this->clearDashboardCache();

        return redirect()->to('/admin/spmb')->with('success', 'Data pendaftar ' . ($item['registration_no'] ?? '') . ' berhasil dihapus.');
    }

    /**
     * Export SPMB applicants to CSV.
     */
    public function exportCsv(): ResponseInterface
    {
        $rows = $this->spmbModel->orderBy('created_at', 'DESC')->orderBy('id', 'DESC')->findAll();

        $fp = fopen('php://temp', 'r+');
        fwrite($fp, "\xEF\xBB\xBF");

        fputcsv($fp, [
            'No. Pendaftaran',
            'Tanggal Daftar',
            'Nama Siswa',
            'NISN',
            'NIK',
            'L/P',
            'Tempat, Tanggal Lahir',
            'Asal Sekolah',
            'Pilihan Jurusan 1',
            'Pilihan Jurusan 2',
            'Nama Wali',
            'Hubungan',
            'No. WA Wali',
            'Kelengkapan Berkas',
            'Status',
            'Terakhir Kirim WA',
        ]);

        foreach ($rows as $r) {
            $meta        = $this->spmbModel->statusMeta((string) ($r['status'] ?? 'pending'));
            $docsOk      = $this->spmbModel->isDocsComplete($r);
            $uploadedCnt = $this->spmbModel->countUploadedDocs($r);
            $ttl         = trim((string) ($r['birth_place'] ?? '')) . (! empty($r['birth_date']) ? ', ' . $r['birth_date'] : '');

            fputcsv($fp, [
                $r['registration_no'] ?? '',
                $r['created_at'] ?? '',
                $r['full_name'] ?? '',
                $r['nisn'] ?? '',
                $r['nik'] ?? '',
                $r['gender'] ?? '',
                $ttl,
                $r['origin_school'] ?? '',
                $r['program_choice_1'] ?? '',
                $r['program_choice_2'] ?? '',
                $r['parent_name'] ?? '',
                $r['parent_relationship'] ?? '',
                $r['parent_phone'] ?? '',
                ($docsOk ? 'Lengkap' : 'Belum Lengkap') . ' (' . $uploadedCnt . '/4)',
                $meta['label'],
                $r['wa_notified_at'] ?? '-',
            ]);
        }

        rewind($fp);
        $csv = stream_get_contents($fp);
        fclose($fp);

        return $this->response
            ->setHeader('Content-Type', 'text/csv; charset=UTF-8')
            ->setHeader('Content-Disposition', 'attachment; filename="spmb-pendaftar-' . date('Ymd') . '.csv"')
            ->setBody((string) $csv);
    }

    /**
     * Collect and validate admin POST payload + optional document uploads/removals.
     *
     * @return array{0: array, 1: list<string>}
     */
    private function collectPayload(?array $existing): array
    {
        $fullName   = trim((string) ($this->request->getPost('full_name') ?? ''));
        $program1   = trim((string) ($this->request->getPost('program_choice_1') ?? ''));
        $parentName = trim((string) ($this->request->getPost('parent_name') ?? ''));
        $parentWa   = trim((string) ($this->request->getPost('parent_phone') ?? ''));

        $errors = [];
        if (mb_strlen($fullName) < 3) {
            $errors[] = 'Nama lengkap calon siswa wajib diisi minimal 3 karakter.';
        }
        if ($program1 === '') {
            $errors[] = 'Pilihan jurusan utama wajib dipilih.';
        }
        if (mb_strlen($parentName) < 3) {
            $errors[] = 'Nama orang tua / wali wajib diisi minimal 3 karakter.';
        }
        if (mb_strlen($parentWa) < 9) {
            $errors[] = 'Nomor WhatsApp orang tua / wali wajib diisi.';
        }

        if (! empty($errors)) {
            return [[], $errors];
        }

        $allowedStatuses = ['pending', 'verified', 'accepted', 'revision', 'rejected'];
        $status          = (string) ($this->request->getPost('status') ?? ($existing['status'] ?? 'pending'));
        if (! in_array($status, $allowedStatuses, true)) {
            $status = 'pending';
        }

        $docValues = [];
        $newlyMoved = [];
        $toDeleteAfter = [];

        foreach (array_keys(SpmbModel::DOC_FIELDS) as $field) {
            $current = $existing[$field] ?? null;
            if ($this->request->getPost('remove_' . $field) === '1' && ! empty($current)) {
                $toDeleteAfter[] = $current;
                $current = null;
            }

            $file = $this->request->getFile($field);
            if ($file && $file->getError() !== UPLOAD_ERR_NO_FILE) {
                $saved = $this->moveDocFile($file, SpmbModel::DOC_FIELDS[$field], $errors);
                if ($saved !== null) {
                    if (! empty($current)) {
                        $toDeleteAfter[] = $current;
                    }
                    $current = $saved;
                    $newlyMoved[] = $saved;
                }
            }

            $docValues[$field] = $current;
        }

        if (! empty($errors)) {
            foreach ($newlyMoved as $mFile) {
                $this->deleteStoredDoc($mFile);
            }
            return [[], $errors];
        }

        foreach ($toDeleteAfter as $oldFile) {
            $this->deleteStoredDoc($oldFile);
        }

        $uploadedCount = count(array_filter($docValues, static fn($v) => ! empty($v)));
        $docsComplete  = $this->request->getPost('docs_complete') ? 1 : ($uploadedCount === 4 ? 1 : 0);

        $data = array_merge([
            'full_name'           => $fullName,
            'nisn'                => trim((string) ($this->request->getPost('nisn') ?? '')) ?: null,
            'nik'                 => trim((string) ($this->request->getPost('nik') ?? '')) ?: null,
            'gender'              => $this->request->getPost('gender') === 'P' ? 'P' : 'L',
            'birth_place'         => trim((string) ($this->request->getPost('birth_place') ?? '')) ?: null,
            'birth_date'          => trim((string) ($this->request->getPost('birth_date') ?? '')) ?: null,
            'religion'            => trim((string) ($this->request->getPost('religion') ?? '')) ?: null,
            'origin_school'       => trim((string) ($this->request->getPost('origin_school') ?? '')) ?: null,
            'graduation_year'     => trim((string) ($this->request->getPost('graduation_year') ?? '')) ?: null,
            'student_phone'       => trim((string) ($this->request->getPost('student_phone') ?? '')) ?: null,
            'address'             => trim((string) ($this->request->getPost('address') ?? '')) ?: null,
            'program_choice_1'    => $program1,
            'program_choice_2'    => trim((string) ($this->request->getPost('program_choice_2') ?? '')) ?: null,
            'parent_name'         => $parentName,
            'parent_relationship' => trim((string) ($this->request->getPost('parent_relationship') ?? 'Orang Tua')) ?: 'Orang Tua',
            'parent_phone'        => $parentWa,
            'parent_email'        => trim((string) ($this->request->getPost('parent_email') ?? '')) ?: null,
            'parent_occupation'   => trim((string) ($this->request->getPost('parent_occupation') ?? '')) ?: null,
            'docs_complete'       => $docsComplete,
            'status'              => $status,
            'admin_notes'         => trim((string) ($this->request->getPost('admin_notes') ?? '')) ?: null,
        ], $docValues);

        return [$data, []];
    }

    /**
     * Validate and store an uploaded SPMB file in public/uploads/spmb.
     *
     * @param list<string> $errors
     */
    private function moveDocFile(UploadedFile $file, string $label, array &$errors): ?string
    {
        if (! $file->isValid()) {
            $errors[] = 'Gagal mengunggah berkas ' . $label . '.';
            return null;
        }

        if ($file->getSize() > 5 * 1024 * 1024) {
            $errors[] = 'Ukuran berkas ' . $label . ' melebihi batas 5 MB.';
            return null;
        }

        $mime    = strtolower((string) $file->getMimeType());
        $allowed = [
            'image/jpeg'      => 'jpg',
            'image/pjpeg'     => 'jpg',
            'image/png'       => 'png',
            'image/webp'      => 'webp',
            'application/pdf' => 'pdf',
        ];

        if (! isset($allowed[$mime])) {
            $errors[] = 'Format berkas ' . $label . ' harus PDF, JPG, PNG, atau WebP.';
            return null;
        }

        $dir = FCPATH . 'uploads/spmb';
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $safe = time() . '_' . bin2hex(random_bytes(8)) . '.' . $allowed[$mime];
        $file->move($dir, $safe);

        return $safe;
    }

    private function deleteStoredDoc(?string $filename): void
    {
        $clean = trim((string) $filename);
        if ($clean === '') {
            return;
        }

        $path = FCPATH . 'uploads/spmb/' . basename($clean);
        if (is_file($path)) {
            @unlink($path);
        }
    }
}
