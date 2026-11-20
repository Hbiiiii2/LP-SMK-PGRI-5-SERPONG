<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1"><?= $item ? 'Edit Pengguna' : 'Tambah Pengguna' ?></h4>
        <p class="text-body-tertiary small mb-0"><?= $item ? 'Perbarui akun dan izin pengguna' : 'Buat akun pengguna baru' ?></p>
    </div>
    <a href="/admin/users" class="btn btn-outline-secondary btn-sm">
        <?= lucide_icon('arrow-left', 'me-1', 15) ?>Kembali
    </a>
</div>

<form action="/admin/users<?= $item ? '/update/' . $item['id'] : '' ?>" method="post" autocomplete="off">
    <?= csrf_field() ?>
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-header bg-transparent border-bottom-0 py-3">
                    <h6 class="mb-0"><?= lucide_icon('user', 'me-2 text-primary', 16) ?>Informasi Akun</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-medium">Username <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-body-tertiary border-end-0"><?= lucide_icon('at-sign', 'text-body-tertiary', 16) ?></span>
                                <input type="text" name="username" class="form-control border-start-0 ps-0" value="<?= esc(old('username', $item['username'] ?? '')) ?>" placeholder="Mis. ahmadfauzi" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-medium">Email <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-body-tertiary border-end-0"><?= lucide_icon('mail', 'text-body-tertiary', 16) ?></span>
                                <input type="email" name="email" class="form-control border-start-0 ps-0" value="<?= esc(old('email', $item['email'] ?? '')) ?>" placeholder="Mis. ahmad@sekolah.sch.id" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-medium">Nama Lengkap <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-body-tertiary border-end-0"><?= lucide_icon('user', 'text-body-tertiary', 16) ?></span>
                                <input type="text" name="full_name" class="form-control border-start-0 ps-0" value="<?= esc(old('full_name', $item['full_name'] ?? '')) ?>" placeholder="Mis. Ahmad Fauzi" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-medium">Peran</label>
                            <?php $currentRole = old('role', $item['role'] ?? 'admin'); ?>
                            <select name="role" class="form-select">
                                <option value="admin" <?= $currentRole === 'admin' ? 'selected' : '' ?>>Admin</option>
                                <option value="editor" <?= $currentRole === 'editor' ? 'selected' : '' ?>>Editor</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-header bg-transparent border-bottom-0 py-3">
                    <h6 class="mb-0"><?= lucide_icon('lock', 'me-2 text-primary', 16) ?>Kata Sandi</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-medium">
                                Kata Sandi <?= $item ? '<span class="text-body-tertiary fw-normal">(kosongkan jika tidak diubah)</span>' : '<span class="text-danger">*</span>' ?>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-body-tertiary border-end-0"><?= lucide_icon('key', 'text-body-tertiary', 16) ?></span>
                                <input type="password" name="password" class="form-control border-start-0 ps-0" <?= $item ? 'placeholder="••••••••"' : 'required' ?> id="password-input">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-medium">
                                Konfirmasi Kata Sandi <?= $item ? '<span class="text-body-tertiary fw-normal">(ulangi jika diubah)</span>' : '<span class="text-danger">*</span>' ?>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-body-tertiary border-end-0"><?= lucide_icon('key', 'text-body-tertiary', 16) ?></span>
                                <input type="password" name="password_confirm" class="form-control border-start-0 ps-0" <?= $item ? 'placeholder="••••••••"' : 'required' ?> id="password-confirm-input">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="show-password">
                                <label class="form-check-label small fw-medium" for="show-password">Tampilkan sandi</label>
                            </div>
                        </div>
                    </div>
                    <?php if ($item): ?>
                    <div class="form-text small">Kosongkan kata sandi jika tidak ingin diubah</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-header bg-transparent border-bottom-0 py-3">
                    <h6 class="mb-0"><?= lucide_icon('shield-check', 'me-2 text-primary', 16) ?>Status</h6>
                </div>
                <div class="card-body">
                    <div class="form-check form-switch mb-3">
                        <?php $isActiveOn = old('is_active') === null
                            ? (!isset($item['is_active']) || (int) $item['is_active'] === 1)
                            : (old('is_active') === '1'); ?>
                        <input type="hidden" name="is_active" value="0">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" <?= $isActiveOn ? 'checked' : '' ?>>
                        <label class="form-check-label small fw-medium" for="is_active">Akun aktif</label>
                    </div>
                    <?php if ($item): ?>
                    <hr>
                    <div class="small text-body-tertiary">
                        <div class="d-flex justify-content-between mb-1 flex-wrap gap-1">
                            <span>Terakhir login</span>
                            <span class="text-nowrap"><?= isset($item['last_login']) && $item['last_login'] ? date('d M Y H:i', strtotime($item['last_login'])) : 'Tidak Pernah' ?></span>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if ($item): ?>
            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-header bg-transparent border-bottom-0 py-3">
                    <h6 class="mb-0"><?= lucide_icon('clock', 'me-2 text-primary', 16) ?>Waktu</h6>
                </div>
                <div class="card-body small text-body-tertiary">
                    <div class="d-flex justify-content-between mb-1 flex-wrap gap-1">
                        <span>Dibuat</span>
                        <span class="text-nowrap"><?= isset($item['created_at']) ? date('d M Y H:i', strtotime($item['created_at'])) : '-' ?></span>
                    </div>
                    <div class="d-flex justify-content-between flex-wrap gap-1">
                        <span>Diubah</span>
                        <span class="text-nowrap"><?= isset($item['updated_at']) ? date('M d, Y H:i', strtotime($item['updated_at'])) : '-' ?></span>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <div class="col-12">
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4"><?= lucide_icon('save', 'me-1', 16) ?><?= $item ? 'Perbarui Pengguna' : 'Buat Pengguna' ?></button>
                <a href="/admin/users" class="btn btn-outline-secondary px-4"><?= lucide_icon('x', 'me-1', 16) ?>Batal</a>
            </div>
        </div>
    </div>
</form>

<script>
document.getElementById('show-password')?.addEventListener('change', function() {
    const type = this.checked ? 'text' : 'password';
    ['password-input', 'password-confirm-input'].forEach(function(id) {
        const input = document.getElementById(id);
        if (input) input.type = type;
    });
});
</script>
<?= $this->endSection() ?>