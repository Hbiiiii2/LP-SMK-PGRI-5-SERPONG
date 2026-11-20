<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<?php
$item        = $item ?? [];
$id          = (int) ($item['id'] ?? 0);
$spmbModel   = $spmb_model ?? new \App\Models\SpmbModel();
$meta        = $spmbModel->statusMeta((string) ($item['status'] ?? 'pending'));
$docsOk      = $spmbModel->isDocsComplete($item);
$uploadedCnt = $spmbModel->countUploadedDocs($item);
$siteName    = (string) ($site_name ?? 'SMK PGRI 5 Serpong');
$waUrl       = (string) ($wa_url ?? '');
$defaultWa   = (string) ($default_wa_msg ?? '');

$templateVerified = $spmbModel->buildWhatsAppMessage(array_merge($item, [
    'status'        => 'verified',
    'docs_complete' => 1,
]), $siteName);
$templateAccepted = $spmbModel->buildWhatsAppMessage(array_merge($item, [
    'status'        => 'accepted',
    'docs_complete' => 1,
]), $siteName);
$templateRevision = $spmbModel->buildWhatsAppMessage(array_merge($item, [
    'status'        => 'revision',
    'docs_complete' => 0,
]), $siteName);
?>

<div class="d-flex justify-content-between align-items-start align-items-sm-center flex-wrap gap-3 mb-4">
    <div>
        <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
            <h4 class="fw-bold mb-0"><?= esc($item['full_name'] ?? 'Detail Pendaftar') ?></h4>
            <span class="badge bg-body-secondary text-body font-monospace"><?= esc($item['registration_no'] ?? '-') ?></span>
            <span class="badge <?= esc($meta['badge_admin']) ?>"><?= esc($meta['label']) ?></span>
        </div>
        <p class="text-body-secondary small mb-0">
            Didaftarkan pada <?= ! empty($item['created_at']) ? date('d M Y, H:i', strtotime((string) $item['created_at'])) : '-' ?> WIB
            <?php if (! empty($item['wa_notified_at'])): ?>
                &middot; <span class="text-success fw-medium">Terakhir kirim WA: <?= date('d M Y H:i', strtotime((string) $item['wa_notified_at'])) ?></span>
            <?php endif; ?>
        </p>
    </div>

    <div class="d-flex flex-wrap gap-2">
        <?php if ($waUrl !== ''): ?>
            <a href="/admin/spmb/send-wa/<?= $id ?><?= ($item['status'] ?? 'pending') === 'pending' ? '?auto_verify=1' : '' ?>"
               target="_blank" rel="noopener noreferrer"
               class="btn btn-success btn-sm d-inline-flex align-items-center gap-1">
                <?= lucide_icon('message-circle', '', 15) ?>
                <span>Kirim Info Lengkap via WhatsApp Wali</span>
            </a>
        <?php endif; ?>
        <a href="/admin/spmb/edit/<?= $id ?>" class="btn btn-outline-primary btn-sm d-inline-flex align-items-center gap-1">
            <?= lucide_icon('pencil', '', 14) ?><span>Edit Data</span>
        </a>
        <a href="/admin/spmb" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1">
            <?= lucide_icon('arrow-left', '', 14) ?><span>Kembali</span>
        </a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <!-- Student & Program Data -->
        <div class="card shadow-sm border-0 rounded-3 mb-4">
            <div class="card-header bg-transparent py-3">
                <h6 class="fw-semibold mb-0 d-inline-flex align-items-center gap-2">
                    <?= lucide_icon('user', 'text-primary', 17) ?>
                    <span>Biodata Calon Siswa &amp; Pilihan Jurusan</span>
                </h6>
            </div>
            <div class="card-body">
                <div class="row g-3 small">
                    <div class="col-sm-6">
                        <div class="text-body-tertiary">Pilihan Jurusan Utama</div>
                        <div class="fw-semibold text-primary"><?= esc($item['program_choice_1'] ?? '-') ?></div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-body-tertiary">Pilihan Alternatif</div>
                        <div class="fw-medium"><?= esc($item['program_choice_2'] ?: '-') ?></div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-body-tertiary">NISN</div>
                        <div class="fw-medium font-monospace"><?= esc($item['nisn'] ?: '-') ?></div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-body-tertiary">NIK</div>
                        <div class="fw-medium font-monospace"><?= esc($item['nik'] ?: '-') ?></div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-body-tertiary">Jenis Kelamin</div>
                        <div class="fw-medium"><?= ($item['gender'] ?? 'L') === 'P' ? 'Perempuan' : 'Laki-laki' ?></div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-body-tertiary">Tempat, Tanggal Lahir</div>
                        <div class="fw-medium">
                            <?= esc($item['birth_place'] ?: '-') ?><?= ! empty($item['birth_date']) ? ', ' . date('d M Y', strtotime((string) $item['birth_date'])) : '' ?>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-body-tertiary">Asal Sekolah (SMP/MTs)</div>
                        <div class="fw-medium"><?= esc($item['origin_school'] ?: '-') ?></div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-body-tertiary">Tahun Lulus / Agama</div>
                        <div class="fw-medium"><?= esc(($item['graduation_year'] ?: '-') . ' / ' . ($item['religion'] ?: '-')) ?></div>
                    </div>
                    <div class="col-12">
                        <div class="text-body-tertiary">Alamat Lengkap</div>
                        <div class="fw-medium"><?= esc($item['address'] ?: '-') ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Parent / Guardian Data -->
        <div class="card shadow-sm border-0 rounded-3 mb-4">
            <div class="card-header bg-transparent py-3">
                <h6 class="fw-semibold mb-0 d-inline-flex align-items-center gap-2">
                    <?= lucide_icon('users', 'text-primary', 17) ?>
                    <span>Data Orang Tua / Wali Murid</span>
                </h6>
            </div>
            <div class="card-body">
                <div class="row g-3 small">
                    <div class="col-sm-6">
                        <div class="text-body-tertiary">Nama Wali Murid</div>
                        <div class="fw-semibold"><?= esc($item['parent_name'] ?? '-') ?></div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-body-tertiary">Hubungan</div>
                        <div class="fw-medium"><?= esc($item['parent_relationship'] ?? 'Orang Tua') ?></div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-body-tertiary">No. WhatsApp Wali</div>
                        <div class="fw-semibold font-monospace text-success"><?= esc($item['parent_phone'] ?? '-') ?></div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-body-tertiary">Email &amp; Pekerjaan</div>
                        <div class="fw-medium"><?= esc(($item['parent_email'] ?: '-') . ' · ' . ($item['parent_occupation'] ?: '-')) ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Documents Uploaded -->
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center py-3">
                <h6 class="fw-semibold mb-0 d-inline-flex align-items-center gap-2">
                    <?= lucide_icon('folder', 'text-primary', 17) ?>
                    <span>Berkas Persyaratan Calon Siswa</span>
                </h6>
                <span class="badge <?= $docsOk ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning-emphasis' ?>">
                    <?= $docsOk ? 'Berkas Lengkap' : $uploadedCnt . '/4 Berkas Terunggah' ?>
                </span>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <?php foreach (\App\Models\SpmbModel::DOC_FIELDS as $field => $label): ?>
                        <?php
                        $fileName = trim((string) ($item[$field] ?? ''));
                        $fileUrl  = $fileName !== '' ? base_url('uploads/spmb/' . $fileName) : '';
                        $ext      = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                        $isImg    = in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true);
                        ?>
                        <div class="col-sm-6">
                            <div class="border rounded-3 p-3 h-100 bg-body-tertiary d-flex flex-column justify-content-between gap-2">
                                <div class="d-flex justify-content-between align-items-start gap-2">
                                    <span class="small fw-semibold"><?= esc($label) ?></span>
                                    <?php if ($fileName !== ''): ?>
                                        <span class="badge bg-success-subtle text-success">Terunggah</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary-subtle text-secondary">Belum Ada</span>
                                    <?php endif; ?>
                                </div>

                                <?php if ($fileName !== ''): ?>
                                    <?php if ($isImg): ?>
                                        <a href="<?= esc($fileUrl) ?>" target="_blank" rel="noopener noreferrer" class="d-block">
                                            <img src="<?= esc($fileUrl) ?>" alt="<?= esc($label) ?>" class="rounded border w-100" style="height:110px;object-fit:cover">
                                        </a>
                                    <?php else: ?>
                                        <div class="rounded border bg-white p-3 text-center text-body-secondary small">
                                            <?= lucide_icon('file-text', 'mb-1 text-primary', 22) ?>
                                            <div>Dokumen PDF</div>
                                        </div>
                                    <?php endif; ?>
                                    <a href="<?= esc($fileUrl) ?>" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-primary w-100">
                                        <?= lucide_icon('external-link', 'me-1', 13) ?>Lihat / Unduh Berkas
                                    </a>
                                <?php else: ?>
                                    <div class="text-body-tertiary small py-2">
                                        Belum diunggah secara daring (edit pendaftar jika ingin mengunggah manual).
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Verification & Auto WhatsApp Card -->
    <div class="col-lg-5">
        <div class="card shadow-sm border-0 rounded-3 sticky-top" style="top: 76px;">
            <div class="card-header bg-transparent py-3">
                <h6 class="fw-semibold mb-0 d-inline-flex align-items-center gap-2">
                    <?= lucide_icon('message-circle', 'text-success', 17) ?>
                    <span>Verifikasi Panitia &amp; Kirim WhatsApp</span>
                </h6>
            </div>
            <div class="card-body">
                <form action="/admin/spmb/verify/<?= $id ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="docsCompleteSwitch" name="docs_complete" value="1" <?= $docsOk ? 'checked' : '' ?>>
                        <label class="form-check-label small fw-semibold" for="docsCompleteSwitch">Persyaratan Berkas Lengkap</label>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold" for="verifyStatus">Status Pendaftaran</label>
                        <select class="form-select form-select-sm" id="verifyStatus" name="status">
                            <?php
                            $statusOptions = [
                                'pending'  => 'Menunggu Verifikasi',
                                'verified' => 'Berkas Lengkap & Terverifikasi',
                                'accepted' => 'Diterima (Lulus Seleksi)',
                                'revision' => 'Perlu Perbaikan Berkas',
                                'rejected' => 'Tidak Diterima',
                            ];
                            $curStatus = (string) ($item['status'] ?? 'pending');
                            ?>
                            <?php foreach ($statusOptions as $sKey => $sLabel): ?>
                                <option value="<?= esc($sKey) ?>" <?= $curStatus === $sKey ? 'selected' : '' ?>><?= esc($sLabel) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold" for="adminNotes">Catatan Panitia / Jadwal Daftar Ulang</label>
                        <textarea class="form-control form-control-sm" id="adminNotes" name="admin_notes" rows="3" placeholder="Misal: Jadwal daftar ulang Senin pukul 08.00 WIB dengan membawa berkas asli..."><?= esc($item['admin_notes'] ?? '') ?></textarea>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-1 mb-1">
                            <label class="form-label small fw-semibold mb-0" for="waMessagePreview">Pratinjau Pesan WhatsApp Otomatis</label>
                        </div>
                        <div class="d-flex flex-wrap gap-1 mb-2">
                            <button type="button" class="btn btn-light border btn-sm py-0 px-2 js-wa-tpl" data-tpl="verified">Berkas Lengkap</button>
                            <button type="button" class="btn btn-light border btn-sm py-0 px-2 js-wa-tpl" data-tpl="accepted">Diterima</button>
                            <button type="button" class="btn btn-light border btn-sm py-0 px-2 js-wa-tpl" data-tpl="revision">Perbaikan Berkas</button>
                        </div>
                        <textarea class="form-control form-control-sm font-monospace" id="waMessagePreview" name="wa_message" rows="9" style="font-size:0.78rem;line-height:1.45"><?= esc($defaultWa) ?></textarea>
                        <div class="form-text small">Pesan otomatis terisi dengan nomor daftar, nama siswa, jurusan, dan status berkas. Anda boleh menyuntingnya sebelum dikirim.</div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" name="send_wa" value="1" formtarget="_blank" class="btn btn-success d-inline-flex align-items-center justify-content-center gap-2">
                            <?= lucide_icon('message-circle', '', 16) ?>
                            <span>Simpan &amp; Kirim ke WhatsApp Wali Murid</span>
                        </button>
                        <button type="submit" name="send_wa" value="0" class="btn btn-primary d-inline-flex align-items-center justify-content-center gap-2">
                            <?= lucide_icon('save', '', 15) ?>
                            <span>Simpan Status Verifikasi Saja</span>
                        </button>
                    </div>
                </form>

                <hr class="my-3">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="small text-body-secondary">Butuh menghapus data ini?</span>
                    <button type="button" class="btn btn-outline-danger btn-sm" data-coreui-toggle="modal" data-coreui-target="#deleteApplicantModal">
                        <?= lucide_icon('trash-2', 'me-1', 13) ?>Hapus Pendaftar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteApplicantModal" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title">Konfirmasi Hapus</h6>
                <button type="button" class="btn-close" data-coreui-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Yakin menghapus pendaftar <strong><?= esc($item['full_name'] ?? '') ?></strong>?
            </div>
            <div class="modal-footer border-0 pt-0">
                <button class="btn btn-sm btn-secondary" data-coreui-dismiss="modal">Batal</button>
                <form action="/admin/spmb/delete/<?= $id ?>" method="post" class="d-inline">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const WA_TEMPLATES = <?= json_encode([
        'verified' => $templateVerified,
        'accepted' => $templateAccepted,
        'revision' => $templateRevision,
    ], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) ?>;

    const waTextarea = document.getElementById('waMessagePreview');
    const statusSelect = document.getElementById('verifyStatus');
    const docsSwitch = document.getElementById('docsCompleteSwitch');

    document.querySelectorAll('.js-wa-tpl').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const key = this.getAttribute('data-tpl');
            if (waTextarea && WA_TEMPLATES[key]) {
                waTextarea.value = WA_TEMPLATES[key];
            }
            if (statusSelect && key) {
                statusSelect.value = key;
            }
            if (docsSwitch) {
                docsSwitch.checked = (key === 'verified' || key === 'accepted');
            }
        });
    });
});
</script>
<?= $this->endSection() ?>
