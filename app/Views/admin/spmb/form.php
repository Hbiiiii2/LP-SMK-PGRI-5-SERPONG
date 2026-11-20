<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<?php
$item        = $item ?? [];
$isEdit      = ! empty($item['id']);
$programOpts = $program_options ?? [];
$oldChoice1  = old('program_choice_1', $item['program_choice_1'] ?? '');
$oldChoice2  = old('program_choice_2', $item['program_choice_2'] ?? '');
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1"><?= $isEdit ? 'Edit Data Pendaftar SPMB' : 'Tambah Pendaftar SPMB' ?></h4>
        <p class="text-body-tertiary small mb-0">
            <?= $isEdit ? 'Perbarui data calon siswa, wali murid, dan berkas persyaratan (' . esc($item['registration_no'] ?? '') . ')' : 'Input pendaftaran calon siswa baru secara langsung oleh panitia' ?>
        </p>
    </div>
    <a href="<?= $isEdit ? '/admin/spmb/' . (int) $item['id'] : '/admin/spmb' ?>" class="btn btn-outline-secondary btn-sm">
        <?= lucide_icon('arrow-left', 'me-1', 15) ?>Kembali
    </a>
</div>

<form action="/admin/spmb<?= $isEdit ? '/update/' . (int) $item['id'] : '' ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-header bg-transparent border-bottom-0 py-3">
                    <h6 class="mb-0"><?= lucide_icon('user', 'me-2 text-primary', 16) ?>Data Calon Siswa &amp; Jurusan</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-medium">Pilihan Jurusan Utama <span class="text-danger">*</span></label>
                            <select name="program_choice_1" class="form-select" required>
                                <option value="">-- Pilih Jurusan --</option>
                                <?php foreach ($programOpts as $opt): ?>
                                    <option value="<?= esc($opt) ?>" <?= $oldChoice1 === $opt ? 'selected' : '' ?>><?= esc($opt) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-medium">Pilihan Jurusan Kedua</label>
                            <select name="program_choice_2" class="form-select">
                                <option value="">-- Opsional --</option>
                                <?php foreach ($programOpts as $opt): ?>
                                    <option value="<?= esc($opt) ?>" <?= $oldChoice2 === $opt ? 'selected' : '' ?>><?= esc($opt) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label small fw-medium">Nama Lengkap Calon Siswa <span class="text-danger">*</span></label>
                            <input type="text" name="full_name" class="form-control" value="<?= esc(old('full_name', $item['full_name'] ?? '')) ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-medium">Jenis Kelamin</label>
                            <?php $g = old('gender', $item['gender'] ?? 'L'); ?>
                            <select name="gender" class="form-select">
                                <option value="L" <?= $g === 'L' ? 'selected' : '' ?>>Laki-laki</option>
                                <option value="P" <?= $g === 'P' ? 'selected' : '' ?>>Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-medium">NISN</label>
                            <input type="text" name="nisn" class="form-control" value="<?= esc(old('nisn', $item['nisn'] ?? '')) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-medium">NIK</label>
                            <input type="text" name="nik" class="form-control" value="<?= esc(old('nik', $item['nik'] ?? '')) ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-medium">Tempat Lahir</label>
                            <input type="text" name="birth_place" class="form-control" value="<?= esc(old('birth_place', $item['birth_place'] ?? '')) ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-medium">Tanggal Lahir</label>
                            <input type="date" name="birth_date" class="form-control" value="<?= esc(old('birth_date', $item['birth_date'] ?? '')) ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-medium">Agama</label>
                            <input type="text" name="religion" class="form-control" value="<?= esc(old('religion', $item['religion'] ?? 'Islam')) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-medium">Asal Sekolah (SMP/MTs)</label>
                            <input type="text" name="origin_school" class="form-control" value="<?= esc(old('origin_school', $item['origin_school'] ?? '')) ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-medium">Tahun Lulus</label>
                            <input type="text" name="graduation_year" class="form-control" value="<?= esc(old('graduation_year', $item['graduation_year'] ?? date('Y'))) ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-medium">No. HP Siswa</label>
                            <input type="text" name="student_phone" class="form-control" value="<?= esc(old('student_phone', $item['student_phone'] ?? '')) ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-medium">Alamat Lengkap</label>
                            <textarea name="address" class="form-control" rows="2"><?= esc(old('address', $item['address'] ?? '')) ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-transparent border-bottom-0 py-3">
                    <h6 class="mb-0"><?= lucide_icon('users', 'me-2 text-primary', 16) ?>Data Orang Tua / Wali Murid</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-medium">Nama Orang Tua / Wali <span class="text-danger">*</span></label>
                            <input type="text" name="parent_name" class="form-control" value="<?= esc(old('parent_name', $item['parent_name'] ?? '')) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-medium">Hubungan</label>
                            <input type="text" name="parent_relationship" class="form-control" value="<?= esc(old('parent_relationship', $item['parent_relationship'] ?? 'Ayah')) ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-medium">No. WhatsApp Wali <span class="text-danger">*</span></label>
                            <input type="text" name="parent_phone" class="form-control" value="<?= esc(old('parent_phone', $item['parent_phone'] ?? '')) ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-medium">Email Orang Tua</label>
                            <input type="email" name="parent_email" class="form-control" value="<?= esc(old('parent_email', $item['parent_email'] ?? '')) ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-medium">Pekerjaan</label>
                            <input type="text" name="parent_occupation" class="form-control" value="<?= esc(old('parent_occupation', $item['parent_occupation'] ?? '')) ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-header bg-transparent border-bottom-0 py-3">
                    <h6 class="mb-0"><?= lucide_icon('folder', 'me-2 text-primary', 16) ?>Berkas Persyaratan</h6>
                </div>
                <div class="card-body">
                    <?php foreach (\App\Models\SpmbModel::DOC_FIELDS as $field => $label): ?>
                        <?php $curFile = trim((string) ($item[$field] ?? '')); ?>
                        <div class="mb-3 pb-2 border-bottom">
                            <label class="form-label small fw-medium mb-1"><?= esc($label) ?></label>
                            <input type="file" name="<?= esc($field) ?>" class="form-control form-control-sm" accept=".pdf,.jpg,.jpeg,.png,.webp">
                            <?php if ($curFile !== ''): ?>
                                <div class="d-flex justify-content-between align-items-center mt-1">
                                    <a href="<?= esc(base_url('uploads/spmb/' . $curFile)) ?>" target="_blank" class="small text-decoration-none">Lihat file saat ini</a>
                                    <div class="form-check mb-0">
                                        <input class="form-check-input" type="checkbox" name="remove_<?= esc($field) ?>" value="1" id="rm_<?= esc($field) ?>">
                                        <label class="form-check-label small text-danger" for="rm_<?= esc($field) ?>">Hapus</label>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>

                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input" type="checkbox" name="docs_complete" value="1" id="formDocsComplete" <?= ! empty(old('docs_complete', $item['docs_complete'] ?? 0)) ? 'checked' : '' ?>>
                        <label class="form-check-label small fw-medium" for="formDocsComplete">Tandai Berkas Lengkap</label>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-header bg-transparent border-bottom-0 py-3">
                    <h6 class="mb-0"><?= lucide_icon('settings', 'me-2 text-primary', 16) ?>Status Verifikasi</h6>
                </div>
                <div class="card-body">
                    <?php $st = old('status', $item['status'] ?? 'pending'); ?>
                    <div class="mb-3">
                        <label class="form-label small fw-medium">Status Pendaftaran</label>
                        <select name="status" class="form-select form-select-sm">
                            <option value="pending" <?= $st === 'pending' ? 'selected' : '' ?>>Menunggu Verifikasi</option>
                            <option value="verified" <?= $st === 'verified' ? 'selected' : '' ?>>Berkas Lengkap &amp; Terverifikasi</option>
                            <option value="accepted" <?= $st === 'accepted' ? 'selected' : '' ?>>Diterima</option>
                            <option value="revision" <?= $st === 'revision' ? 'selected' : '' ?>>Perlu Perbaikan Berkas</option>
                            <option value="rejected" <?= $st === 'rejected' ? 'selected' : '' ?>>Tidak Diterima</option>
                        </select>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-medium">Catatan Panitia</label>
                        <textarea name="admin_notes" class="form-control form-control-sm" rows="3"><?= esc(old('admin_notes', $item['admin_notes'] ?? '')) ?></textarea>
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary"><?= lucide_icon('save', 'me-1', 16) ?><?= $isEdit ? 'Simpan Perubahan' : 'Simpan Pendaftar' ?></button>
                <a href="/admin/spmb" class="btn btn-outline-secondary"><?= lucide_icon('x', 'me-1', 16) ?>Batal</a>
            </div>
        </div>
    </div>
</form>
<?= $this->endSection() ?>
