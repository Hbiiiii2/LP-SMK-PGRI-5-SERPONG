<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1"><?= $item ? 'Edit FAQ' : 'Tambah FAQ' ?></h4>
        <p class="text-body-tertiary small mb-0"><?= $item ? 'Perbarui pertanyaan yang sering diajukan' : 'Tambah pertanyaan baru' ?></p>
    </div>
    <a href="/admin/faq" class="btn btn-outline-secondary btn-sm">
        <?= lucide_icon('arrow-left', 'me-1', 15) ?>Kembali
    </a>
</div>

<form action="/admin/faq<?= $item ? '/update/' . $item['id'] : '' ?>" method="post">
    <?= csrf_field() ?>
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-header bg-transparent border-bottom-0 py-3">
                    <h6 class="mb-0"><?= lucide_icon('help-circle', 'me-2 text-primary', 16) ?>Tanya Jawab</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label small fw-medium">Pertanyaan <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-body-tertiary border-end-0"><?= lucide_icon('help-circle', 'text-body-tertiary', 16) ?></span>
                            <input type="text" name="question" class="form-control border-start-0 ps-0" value="<?= esc($item['question'] ?? '') ?>" placeholder="Contoh: Bagaimana cara mendaftar SPMB?" maxlength="255" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-medium">Jawaban <span class="text-danger">*</span></label>
                        <textarea name="answer" class="form-control" rows="6" placeholder="Tulis jawaban yang jelas dan mudah dipahami..." required><?= esc($item['answer'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-header bg-transparent border-bottom-0 py-3">
                    <h6 class="mb-0"><?= lucide_icon('folder', 'me-2 text-primary', 16) ?>Organisasi</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label small fw-medium">Kategori</label>
                        <input type="text" name="category" class="form-control" value="<?= esc($item['category'] ?? '') ?>" placeholder="Contoh: Pendaftaran, Akademik" list="category-list" maxlength="100">
                        <datalist id="category-list">
                            <?php foreach ($categories ?? [] as $cat): ?>
                            <option value="<?= esc($cat) ?>">
                            <?php endforeach; ?>
                        </datalist>
                        <div class="form-text small">Biarkan kosong untuk masuk kelompok "Umum".</div>
                    </div>
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
                        <span class="text-nowrap"><?= isset($item['updated_at']) ? date('d M Y H:i', strtotime($item['updated_at'])) : '-' ?></span>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <div class="col-12">
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4"><?= lucide_icon('save', 'me-1', 16) ?><?= $item ? 'Perbarui FAQ' : 'Simpan FAQ' ?></button>
                <a href="/admin/faq" class="btn btn-outline-secondary px-4"><?= lucide_icon('x', 'me-1', 16) ?>Batal</a>
            </div>
        </div>
    </div>
</form>
<?= $this->endSection() ?>