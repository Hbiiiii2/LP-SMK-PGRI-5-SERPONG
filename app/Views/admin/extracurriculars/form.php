<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1"><?= $item ? 'Edit Ekskul' : 'Tambah Ekskul' ?></h4>
        <p class="text-body-tertiary small mb-0"><?= $item ? 'Perbarui detail kegiatan ekstrakurikuler' : 'Daftarkan kegiatan ekstrakurikuler baru' ?></p>
    </div>
    <a href="/admin/extracurriculars" class="btn btn-outline-secondary btn-sm">
        <?= lucide_icon('arrow-left', 'me-1', 15) ?>Kembali
    </a>
</div>

<form action="/admin/extracurriculars<?= $item ? '/update/' . $item['id'] : '' ?>" method="post">
    <?= csrf_field() ?>
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-header bg-transparent border-bottom-0 py-3">
                    <h6 class="mb-0"><?= lucide_icon('trophy', 'me-2 text-primary', 16) ?>Detail Kegiatan</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label small fw-medium">Judul <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-body-tertiary border-end-0"><?= lucide_icon('type', 'text-body-tertiary', 16) ?></span>
                            <input type="text" name="title" class="form-control border-start-0 ps-0" value="<?= esc($item['title'] ?? '') ?>" placeholder="e.g. Paskibra, Pramuka, Futsal" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-medium">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="5" placeholder="Describe the activity, schedule, and benefits for students..."><?= esc($item['description'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-header bg-transparent border-bottom-0 py-3">
                    <h6 class="mb-0"><?= lucide_icon('palette', 'me-2 text-primary', 16) ?>Ikon</h6>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <div class="d-inline-flex align-items-center justify-content-center rounded-3" style="width:80px;height:80px;font-size:2rem;background-color:<?= esc($item['icon_color'] ?? '#0d6efd') ?>20;color:<?= esc($item['icon_color'] ?? '#0d6efd') ?>">
                            <?= lucide_icon((string) ($item['icon'] ?? 'circle'), '', 30) ?>
                        </div>
                    </div>
                    <label class="form-label small fw-medium">Font Awesome Class</label>
                    <input type="text" name="icon" class="form-control form-control-sm mb-3" value="<?= esc($item['icon'] ?? 'circle') ?>" placeholder="music" id="icon-input">
                    <label class="form-label small fw-medium">Icon Color</label>
                    <div class="d-flex gap-2">
                        <input type="color" name="icon_color_hex" class="form-control form-control-color w-auto" value="<?= esc($item['icon_color'] ?? '#0d6efd') ?>" id="color-input">
                        <input type="text" name="icon_color" class="form-control form-control-sm" value="<?= esc($item['icon_color'] ?? '#0d6efd') ?>" placeholder="#0d6efd" id="color-text">
                    </div>
                    <div class="form-text small text-body-secondary">Contoh: <code>music</code>, <code>trophy</code>, <code>compass</code>, <code>flask</code>, <code>laptop</code>. Otomatis dipetakan ke Lucide.</div>
                    <div class="form-text small text-body-tertiary">Ikon dan warnanya tampil di halaman Ekstrakurikuler.</div>
                </div>
            </div>

            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-header bg-transparent border-bottom-0 py-3">
                    <h6 class="mb-0"><?= lucide_icon('settings', 'me-2 text-primary', 16) ?>Tampilan</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label small fw-medium">Urutan</label>
                        <input type="number" name="sort_order" class="form-control" value="<?= $item['sort_order'] ?? 0 ?>" min="0">
                        <div class="form-text small">Angka kecil tampil lebih dulu</div>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="show" value="1" id="show" <?= (!isset($item['show']) || $item['show']) ? 'checked' : '' ?>>
                        <label class="form-check-label small fw-medium" for="show">Tampil di website</label>
                    </div>
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
                <button type="submit" class="btn btn-primary px-4"><?= lucide_icon('save', 'me-1', 16) ?><?= $item ? 'Perbarui Ekskul' : 'Simpan Ekskul' ?></button>
                <a href="/admin/extracurriculars" class="btn btn-outline-secondary px-4"><?= lucide_icon('x', 'me-1', 16) ?>Batal</a>
            </div>
        </div>
    </div>
</form>

<script>
(function() {
    const iconInput = document.getElementById('icon-input');
    const colorInput = document.getElementById('color-input');
    const colorText = document.getElementById('color-text');
    const card = iconInput?.closest('.card-body');
    const previewUse = card?.querySelector('.rounded-3 svg use');
    const previewBox = card?.querySelector('.rounded-3');

    iconInput?.addEventListener('input', function() {
        if (previewUse && typeof window.resolveLucideSymbol === 'function' && window.lucideSpriteUrl) {
            const sym = window.resolveLucideSymbol(this.value || 'circle');
            previewUse.setAttribute('href', window.lucideSpriteUrl + '#lucide-' + sym);
        }
    });
    colorInput?.addEventListener('input', function() {
        if (colorText) colorText.value = this.value;
        if (previewBox) { previewBox.style.backgroundColor = this.value + '20'; previewBox.style.color = this.value; }
    });
    colorText?.addEventListener('input', function() {
        if (colorInput) colorInput.value = this.value;
        if (previewBox) { previewBox.style.backgroundColor = this.value + '20'; previewBox.style.color = this.value; }
    });
})();
</script>
<?= $this->endSection() ?>