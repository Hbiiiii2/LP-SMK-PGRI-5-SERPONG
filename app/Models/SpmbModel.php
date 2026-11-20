<?php

namespace App\Models;

use CodeIgniter\Model;

class SpmbModel extends Model
{
    protected $table            = 'spmb_applications';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $allowedFields = [
        'registration_no',
        'full_name',
        'nisn',
        'nik',
        'gender',
        'birth_place',
        'birth_date',
        'religion',
        'origin_school',
        'graduation_year',
        'student_phone',
        'address',
        'program_choice_1',
        'program_choice_2',
        'parent_name',
        'parent_relationship',
        'parent_phone',
        'parent_email',
        'parent_occupation',
        'doc_kk',
        'doc_akta',
        'doc_ijazah',
        'doc_foto',
        'docs_complete',
        'status',
        'admin_notes',
        'wa_notified_at',
        'is_read',
    ];

    public const DOC_FIELDS = [
        'doc_kk'     => 'Kartu Keluarga (KK)',
        'doc_akta'   => 'Akta Kelahiran',
        'doc_ijazah' => 'Ijazah / SKL / Rapor',
        'doc_foto'   => 'Pas Foto 3x4',
    ];

    /**
     * Generate a unique registration number formatted as SPMB-YYYY-0001.
     */
    public function generateRegistrationNo(): string
    {
        $year   = date('Y');
        $prefix = 'SPMB-' . $year . '-';

        $latest = $this->select('registration_no')
            ->like('registration_no', $prefix, 'after')
            ->orderBy('id', 'DESC')
            ->first();

        $nextSeq = 1;
        if ($latest && preg_match('/^SPMB-\d{4}-(\d+)$/', (string) ($latest['registration_no'] ?? ''), $m)) {
            $nextSeq = ((int) $m[1]) + 1;
        }

        do {
            $candidate = $prefix . str_pad((string) $nextSeq, 4, '0', STR_PAD_LEFT);
            $exists    = $this->where('registration_no', $candidate)->countAllResults() > 0;
            $nextSeq++;
        } while ($exists);

        return $candidate;
    }

    /**
     * Look up an applicant record by exact registration number.
     */
    public function findByRegistrationNo(string $regNo): ?array
    {
        $clean = strtoupper(trim($regNo));
        if ($clean === '') {
            return null;
        }

        $row = $this->where('registration_no', $clean)->first();
        return is_array($row) ? $row : null;
    }

    /**
     * Count how many of the 4 required document files have been uploaded.
     */
    public function countUploadedDocs(array $row): int
    {
        $count = 0;
        foreach (array_keys(self::DOC_FIELDS) as $field) {
            if (! empty(trim((string) ($row[$field] ?? '')))) {
                $count++;
            }
        }
        return $count;
    }

    /**
     * Determine whether applicant documents are complete (marked manually or all 4 uploaded).
     */
    public function isDocsComplete(array $row): bool
    {
        return ! empty($row['docs_complete']) || $this->countUploadedDocs($row) === 4;
    }

    /**
     * Map registration status code to display label and CoreUI / public theme badge classes.
     *
     * @return array{label: string, badge_admin: string, badge_public: string}
     */
    public function statusMeta(string $status): array
    {
        return match ($status) {
            'verified' => [
                'label'        => 'Berkas Lengkap & Terverifikasi',
                'badge_admin'  => 'bg-info-subtle text-info border border-info-subtle',
                'badge_public' => 'badge-blue',
            ],
            'accepted' => [
                'label'        => 'Diterima',
                'badge_admin'  => 'bg-success-subtle text-success border border-success-subtle',
                'badge_public' => 'badge-green',
            ],
            'revision' => [
                'label'        => 'Perlu Perbaikan Berkas',
                'badge_admin'  => 'bg-warning-subtle text-warning-emphasis border border-warning-subtle',
                'badge_public' => 'badge-amber',
            ],
            'rejected' => [
                'label'        => 'Tidak Diterima',
                'badge_admin'  => 'bg-danger-subtle text-danger border border-danger-subtle',
                'badge_public' => 'badge-red',
            ],
            default => [
                'label'        => 'Menunggu Verifikasi',
                'badge_admin'  => 'bg-secondary-subtle text-secondary border border-secondary-subtle',
                'badge_public' => 'badge-slate',
            ],
        };
    }

    /**
     * Normalize Indonesian WhatsApp phone numbers into international digits-only format (62...).
     */
    public function normalizeWaPhone(string $phone): string
    {
        $digits = preg_replace('/[^0-9]/', '', trim($phone)) ?? '';
        if ($digits === '') {
            return '';
        }

        if (str_starts_with($digits, '0')) {
            return '62' . substr($digits, 1);
        }

        if (str_starts_with($digits, '8')) {
            return '62' . $digits;
        }

        return $digits;
    }

    /**
     * Build a structured Indonesian WhatsApp notification message for the parent/guardian.
     */
    public function buildWhatsAppMessage(array $row, string $siteName = 'SMK PGRI 5 Serpong'): string
    {
        $parentName  = trim((string) ($row['parent_name'] ?? 'Bapak/Ibu Orang Tua/Wali'));
        $studentName = trim((string) ($row['full_name'] ?? '-'));
        $regNo       = trim((string) ($row['registration_no'] ?? '-'));
        $origin      = trim((string) ($row['origin_school'] ?? '-'));
        $program1    = trim((string) ($row['program_choice_1'] ?? '-'));
        $program2    = trim((string) ($row['program_choice_2'] ?? ''));
        $status      = (string) ($row['status'] ?? 'pending');
        $meta        = $this->statusMeta($status);
        $docsOk      = $this->isDocsComplete($row);
        $uploadedCnt = $this->countUploadedDocs($row);
        $notes       = trim((string) ($row['admin_notes'] ?? ''));

        $docStatusText = $docsOk
            ? 'LENGKAP & TERVERIFIKASI'
            : ($uploadedCnt > 0
                ? 'MENUNGGU KELENGKAPAN BERKAS (' . $uploadedCnt . '/4 terunggah)'
                : 'MENUNGGU KELENGKAPAN BERKAS');

        $jurusanText = $program1 . ($program2 !== '' ? ' (Pilihan 2: ' . $program2 . ')' : '');

        $lines = [
            'Assalamu\'alaikum Wr. Wb. / Salam Sejahtera,',
            '',
            'Yth. ' . $parentName . ',',
            'Kami dari Panitia SPMB *' . $siteName . '* menyampaikan pembaruan status pendaftaran calon peserta didik baru:',
            '',
            '• No. Pendaftaran : *' . $regNo . '*',
            '• Nama Calon Siswa : *' . $studentName . '*',
            '• Asal Sekolah : ' . $origin,
            '• Program Keahlian : *' . $jurusanText . '*',
            '• Kelengkapan Berkas : *' . $docStatusText . '*',
            '• Status Pendaftaran : *' . $meta['label'] . '*',
        ];

        if ($notes !== '') {
            $lines[] = '';
            $lines[] = '*Catatan Panitia:*';
            $lines[] = $notes;
        }

        if ($status === 'accepted') {
            $lines[] = '';
            $lines[] = 'Selamat! Calon peserta didik dinyatakan *DITERIMA*. Mohon hadir sesuai jadwal daftar ulang dengan membawa berkas asli.';
        } elseif ($status === 'verified' || $docsOk) {
            $lines[] = '';
            $lines[] = 'Terima kasih telah melengkapi data dan persyaratan pendaftaran. Panitia akan menginformasikan jadwal seleksi / daftar ulang berikutnya melalui WhatsApp ini.';
        } elseif ($status === 'revision') {
            $lines[] = '';
            $lines[] = 'Mohon segera melengkapi atau memperbaiki berkas persyaratan sesuai catatan panitia di atas agar proses verifikasi dapat dilanjutkan.';
        } else {
            $lines[] = '';
            $lines[] = 'Pendaftaran telah kami terima dan sedang dalam proses verifikasi oleh Panitia SPMB ' . $siteName . '.';
        }

        $lines[] = '';
        $lines[] = 'Hormat kami,';
        $lines[] = '*Panitia SPMB ' . $siteName . '*';

        return implode("\n", $lines);
    }

    /**
     * Build a clickable https://wa.me/... URL prefilled with the official notification message.
     */
    public function buildWhatsAppUrl(array $row, string $siteName = 'SMK PGRI 5 Serpong', ?string $customMessage = null): string
    {
        $phone = $this->normalizeWaPhone((string) ($row['parent_phone'] ?? ''));
        if ($phone === '') {
            return '';
        }

        $msg = ($customMessage !== null && trim($customMessage) !== '')
            ? trim($customMessage)
            : $this->buildWhatsAppMessage($row, $siteName);

        return 'https://wa.me/' . $phone . '?text=' . rawurlencode($msg);
    }

    /**
     * Aggregate counts per status and unread items for Admin SPMB & Dashboard.
     *
     * @return array<string, int>
     */
    public function getStats(): array
    {
        $rows = $this->select('status, is_read')->findAll();
        $stats = [
            'all'      => count($rows),
            'pending'  => 0,
            'verified' => 0,
            'accepted' => 0,
            'revision' => 0,
            'rejected' => 0,
            'unread'   => 0,
        ];

        foreach ($rows as $r) {
            $st = (string) ($r['status'] ?? 'pending');
            if (isset($stats[$st])) {
                $stats[$st]++;
            }
            if (empty($r['is_read'])) {
                $stats['unread']++;
            }
        }

        return $stats;
    }

    /**
     * Count applicants waiting for verification or not yet read by admin.
     */
    public function getPendingCount(): int
    {
        return $this->groupStart()
            ->where('status', 'pending')
            ->orWhere('is_read', 0)
            ->groupEnd()
            ->countAllResults();
    }

    /**
     * Retrieve recent SPMB applicants ordered newest first.
     */
    public function getRecent(int $limit = 5): array
    {
        return $this->orderBy('created_at', 'DESC')
            ->orderBy('id', 'DESC')
            ->findAll($limit);
    }
}
