<?php

namespace App\Controllers;

use App\Models\ProgramModel;
use App\Models\SettingModel;
use App\Models\SpmbModel;
use CodeIgniter\HTTP\Files\UploadedFile;
use CodeIgniter\HTTP\RedirectResponse;

class Spmb extends BaseController
{
    /**
     * Load shared site settings for the public SPMB portal.
     */
    private function baseData(): array
    {
        $settingKeys = [
            'site_name', 'site_tagline', 'site_description',
            'site_logo_text', 'site_logo_icon',
            'contact_phone', 'contact_email', 'contact_address', 'contact_hours',
            'social_facebook', 'social_instagram', 'social_youtube', 'social_tiktok',
            'footer_description', 'footer_copyright', 'footer_services', 'footer_links',
            'page_banners', 'spmb_steps', 'spmb_badge_text',
        ];

        $settings = new SettingModel()->getMany($settingKeys);
        $siteName = $settings['site_name'] ?? 'SMK PGRI 5 Serpong';

        helper('theme');

        return [
            'settings'           => $settings,
            'site_name'          => $siteName,
            'site_tagline'       => $settings['site_tagline'] ?? '',
            'site_description'   => $settings['site_description'] ?? '',
            'site_logo_text'     => $settings['site_logo_text'] ?? $siteName,
            'site_logo_icon'     => $settings['site_logo_icon'] ?? 'graduation-cap',
            'contact_phone'      => $settings['contact_phone'] ?? '',
            'contact_email'      => $settings['contact_email'] ?? '',
            'contact_address'    => $settings['contact_address'] ?? '',
            'contact_hours'      => $settings['contact_hours'] ?? '',
            'social_facebook'    => $settings['social_facebook'] ?? '',
            'social_instagram'   => $settings['social_instagram'] ?? '',
            'social_youtube'     => $settings['social_youtube'] ?? '',
            'social_tiktok'      => $settings['social_tiktok'] ?? '',
            'footer_description' => $settings['footer_description'] ?? '',
            'footer_copyright'   => $settings['footer_copyright'] ?? '',
            'footer_services'    => $settings['footer_services'] ?? '',
            'footer_links'       => $settings['footer_links'] ?? '',
            'page_banners'       => $settings['page_banners'] ?? null,
            'spmb_steps'         => resolve_spmb_steps($settings['spmb_steps'] ?? null),
            'og_image'           => theme_og(),
        ];
    }

    /**
     * Resolve available major options from the programs table (with vocational fallbacks).
     *
     * @return list<string>
     */
    private function resolveProgramTitles(array $programs): array
    {
        $titles = [];
        foreach ($programs as $prog) {
            $t = trim((string) ($prog['title'] ?? ''));
            if ($t !== '') {
                $titles[] = $t;
            }
        }

        if ($titles === []) {
            return [
                'Teknik Komputer dan Jaringan (TKJ)',
                'Rekayasa Perangkat Lunak (RPL)',
                'Akuntansi dan Keuangan Lembaga (AKL)',
                'Manajemen Perkantoran dan Layanan Bisnis (MPLB)',
            ];
        }

        return $titles;
    }

    /**
     * Display the public Online SPMB registration portal and status lookup.
     */
    public function index(): string
    {
        $data = $this->baseData();

        $programModel = new ProgramModel();
        $programs     = $programModel->getOrdered(50);
        $programNames = $this->resolveProgramTitles($programs);

        $jurusanParam    = trim((string) $this->request->getGet('jurusan'));
        $selectedProgram = '';
        if ($jurusanParam !== '') {
            $needle = mb_strtolower($jurusanParam);
            foreach ($programs as $prog) {
                $pSlug  = mb_strtolower(trim((string) ($prog['slug'] ?? '')));
                $pTitle = trim((string) ($prog['title'] ?? ''));
                if ($needle === $pSlug || $needle === mb_strtolower($pTitle)) {
                    $selectedProgram = $pTitle;
                    break;
                }
            }
            if ($selectedProgram === '' && in_array($jurusanParam, $programNames, true)) {
                $selectedProgram = $jurusanParam;
            }
        }

        $kodeQuery     = trim((string) ($this->request->getGet('kode') ?? $this->request->getGet('cek') ?? ''));
        $lookupResult  = null;
        $lookupChecked = false;
        $spmbModel     = new SpmbModel();

        if ($kodeQuery !== '') {
            $lookupChecked = true;
            $lookupResult  = $spmbModel->findByRegistrationNo($kodeQuery);
        }

        $data['page_title']       = 'SPMB Online — ' . $data['site_name'];
        $data['meta_description'] = 'Formulir pendaftaran SPMB Online, unggah berkas persyaratan, dan cek status verifikasi calon peserta didik baru ' . $data['site_name'];
        $data['data_page']        = 'spmb';
        $data['programs']         = $programs;
        $data['program_options']  = $programNames;
        $data['selected_program'] = $selectedProgram;
        $data['kode_query']       = $kodeQuery;
        $data['lookup_result']    = $lookupResult;
        $data['lookup_checked']   = $lookupChecked;
        $data['spmb_model']       = $spmbModel;

        return view(theme_view('pages/spmb'), $data);
    }

    /**
     * Process an online SPMB registration submission and document uploads.
     */
    public function submit(): RedirectResponse
    {
        // Honeypot check against automated spam
        if (! empty($this->request->getPost('website'))) {
            return redirect()->to('/spmb')->with('spmb_success', 'Pendaftaran Anda telah diterima.');
        }

        $rules = [
            'full_name'        => 'required|min_length[3]|max_length[150]',
            'gender'           => 'required|in_list[L,P]',
            'birth_place'      => 'required|min_length[2]|max_length[100]',
            'birth_date'       => 'required|valid_date[Y-m-d]',
            'origin_school'    => 'required|min_length[3]|max_length[150]',
            'address'          => 'required|min_length[8]|max_length[2000]',
            'program_choice_1' => 'required|min_length[2]|max_length[150]',
            'parent_name'      => 'required|min_length[3]|max_length[150]',
            'parent_phone'     => 'required|min_length[9]|max_length[30]',
        ];

        $messages = [
            'full_name' => [
                'required'   => 'Nama lengkap calon siswa wajib diisi.',
                'min_length' => 'Nama calon siswa minimal 3 karakter.',
            ],
            'gender' => [
                'required' => 'Jenis kelamin wajib dipilih.',
                'in_list'  => 'Jenis kelamin tidak valid.',
            ],
            'birth_place' => [
                'required' => 'Tempat lahir wajib diisi.',
            ],
            'birth_date' => [
                'required'   => 'Tanggal lahir wajib diisi.',
                'valid_date' => 'Format tanggal lahir tidak valid.',
            ],
            'origin_school' => [
                'required' => 'Asal sekolah SMP/MTs wajib diisi.',
            ],
            'address' => [
                'required'   => 'Alamat lengkap tempat tinggal wajib diisi.',
                'min_length' => 'Alamat minimal 8 karakter.',
            ],
            'program_choice_1' => [
                'required' => 'Pilihan program keahlian utama wajib dipilih.',
            ],
            'parent_name' => [
                'required'   => 'Nama orang tua / wali murid wajib diisi.',
                'min_length' => 'Nama orang tua / wali minimal 3 karakter.',
            ],
            'parent_phone' => [
                'required'   => 'Nomor WhatsApp aktif orang tua / wali wajib diisi.',
                'min_length' => 'Nomor WhatsApp minimal 9 digit.',
            ],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Validate and move any uploaded documents
        $docFields    = array_keys(SpmbModel::DOC_FIELDS);
        $uploadedDocs = [];
        $uploadErrors = [];

        foreach ($docFields as $field) {
            $file = $this->request->getFile($field);
            if ($file && $file->getError() !== UPLOAD_ERR_NO_FILE) {
                $stored = $this->moveDocumentFile($file, SpmbModel::DOC_FIELDS[$field], $uploadErrors);
                if ($stored !== null) {
                    $uploadedDocs[$field] = $stored;
                }
            }
        }

        if (! empty($uploadErrors)) {
            foreach ($uploadedDocs as $storedFile) {
                $full = FCPATH . 'uploads/spmb/' . basename($storedFile);
                if (is_file($full)) {
                    @unlink($full);
                }
            }

            return redirect()->back()->withInput()->with('errors', $uploadErrors);
        }

        $spmbModel = new SpmbModel();
        $regNo     = $spmbModel->generateRegistrationNo();

        $uploadedCount = count($uploadedDocs);
        $docsComplete  = $uploadedCount === 4 ? 1 : 0;

        $data = [
            'registration_no'     => $regNo,
            'full_name'           => trim((string) $this->request->getPost('full_name')),
            'nisn'                => trim((string) $this->request->getPost('nisn')) ?: null,
            'nik'                 => trim((string) $this->request->getPost('nik')) ?: null,
            'gender'              => trim((string) $this->request->getPost('gender')) ?: 'L',
            'birth_place'         => trim((string) $this->request->getPost('birth_place')) ?: null,
            'birth_date'          => trim((string) $this->request->getPost('birth_date')) ?: null,
            'religion'            => trim((string) $this->request->getPost('religion')) ?: null,
            'origin_school'       => trim((string) $this->request->getPost('origin_school')) ?: null,
            'graduation_year'     => trim((string) $this->request->getPost('graduation_year')) ?: null,
            'student_phone'       => trim((string) $this->request->getPost('student_phone')) ?: null,
            'address'             => trim((string) $this->request->getPost('address')) ?: null,
            'program_choice_1'    => trim((string) $this->request->getPost('program_choice_1')),
            'program_choice_2'    => trim((string) $this->request->getPost('program_choice_2')) ?: null,
            'parent_name'         => trim((string) $this->request->getPost('parent_name')),
            'parent_relationship' => trim((string) $this->request->getPost('parent_relationship')) ?: 'Orang Tua',
            'parent_phone'        => trim((string) $this->request->getPost('parent_phone')),
            'parent_email'        => trim((string) $this->request->getPost('parent_email')) ?: null,
            'parent_occupation'   => trim((string) $this->request->getPost('parent_occupation')) ?: null,
            'doc_kk'              => $uploadedDocs['doc_kk'] ?? null,
            'doc_akta'            => $uploadedDocs['doc_akta'] ?? null,
            'doc_ijazah'          => $uploadedDocs['doc_ijazah'] ?? null,
            'doc_foto'            => $uploadedDocs['doc_foto'] ?? null,
            'docs_complete'       => $docsComplete,
            'status'              => 'pending',
            'is_read'             => 0,
        ];

        if (! $spmbModel->insert($data)) {
            foreach ($uploadedDocs as $storedFile) {
                $full = FCPATH . 'uploads/spmb/' . basename($storedFile);
                if (is_file($full)) {
                    @unlink($full);
                }
            }

            return redirect()->back()->withInput()->with('errors', ['Gagal menyimpan pendaftaran. Silakan periksa isian Anda dan coba lagi.']);
        }

        cache()->delete('admin_dashboard_stats');
        cache()->delete('admin_dashboard_recent');

        return redirect()
            ->to('/spmb?kode=' . urlencode($regNo) . '#bukti-pendaftaran')
            ->with('spmb_success', 'Pendaftaran SPMB Online berhasil dikirim! Simpan Nomor Pendaftaran Anda (' . $regNo . ') untuk memantau status verifikasi dan notifikasi WhatsApp dari panitia.');
    }

    /**
     * Validate and safely store a single uploaded registration document.
     *
     * @param list<string> $errors
     */
    private function moveDocumentFile(UploadedFile $file, string $label, array &$errors): ?string
    {
        if (! $file->isValid()) {
            $errors[] = 'Gagal mengunggah berkas ' . $label . ': ' . $file->getErrorString();
            return null;
        }

        if ($file->getSize() > 5 * 1024 * 1024) {
            $errors[] = 'Ukuran berkas ' . $label . ' melebihi batas maksimal 5 MB.';
            return null;
        }

        $mime        = strtolower((string) $file->getMimeType());
        $allowedMime = [
            'image/jpeg'      => 'jpg',
            'image/pjpeg'     => 'jpg',
            'image/png'       => 'png',
            'image/webp'      => 'webp',
            'application/pdf' => 'pdf',
        ];

        if (! isset($allowedMime[$mime])) {
            $errors[] = 'Format berkas ' . $label . ' harus berupa PDF, JPG, PNG, atau WebP.';
            return null;
        }

        $uploadDir = FCPATH . 'uploads/spmb';
        if (! is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $safeName = time() . '_' . bin2hex(random_bytes(8)) . '.' . $allowedMime[$mime];
        $file->move($uploadDir, $safeName);

        return $safeName;
    }
}
