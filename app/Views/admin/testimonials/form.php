<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1"><?= $item ? 'Edit Testimoni' : 'Tambah Testimoni' ?></h4>
        <p class="text-body-tertiary small mb-0"><?= $item ? 'Perbarui testimoni ini' : 'Tambah testimoni baru dari siswa atau orang tua' ?></p>
    </div>
    <a href="/admin/testimonials" class="btn btn-outline-secondary btn-sm">
        <?= lucide_icon('arrow-left', 'me-1', 15) ?>Kembali
    </a>
</div>

<form action="/admin/testimonials<?= $item ? '/update/' . $item['id'] : '' ?>" method="post">
    <?= csrf_field() ?>
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-header bg-transparent border-bottom-0 py-3">
                    <h6 class="mb-0"><?= lucide_icon('quote', 'me-2 text-primary', 16) ?>Konten Testimoni</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label small fw-medium">Nama <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-body-tertiary border-end-0"><?= lucide_icon('user', 'text-body-tertiary', 16) ?></span>
                            <input type="text" name="name" class="form-control border-start-0 ps-0" value="<?= esc(old('name', $item['name'] ?? '')) ?>" placeholder="e.g. Siti Nurhaliza" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-medium">Peran / Jabatan</label>
                        <div class="input-group">
                            <span class="input-group-text bg-body-tertiary border-end-0"><?= lucide_icon('tag', 'text-body-tertiary', 16) ?></span>
                            <input type="text" name="role" class="form-control border-start-0 ps-0" value="<?= esc(old('role', $item['role'] ?? '')) ?>" placeholder="e.g. Orang Tua Siswa / Siswi Kelas 9">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-medium">Testimoni <span class="text-danger">*</span></label>
                        <textarea name="quote" class="form-control" rows="5" placeholder="What did they say about the school? Write their testimonial here..." required><?= esc(old('quote', $item['quote'] ?? '')) ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-header bg-transparent border-bottom-0 py-3">
                    <h6 class="mb-0"><?= lucide_icon('image', 'me-2 text-primary', 16) ?>Foto</h6>
                </div>
                <div class="card-body text-center">
                    <?php $photoValue = (string) old('photo', $item['photo'] ?? ''); ?>
                    <div class="mb-3">
                        <?php if ($photoValue !== ''): ?>
                            <img src="<?= esc($photoValue) ?>" alt="preview" class="rounded-circle shadow-sm" style="width:100px;height:100px;object-fit:cover">
                        <?php else: ?>
                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-body-tertiary" style="width:100px;height:100px">
                                <?= lucide_icon('user', 'text-body-tertiary opacity-50', 40) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <label class="form-label small fw-medium">URL Foto</label>
                    <input type="text" name="photo" class="form-control form-control-sm" value="<?= esc($photoValue) ?>" placeholder="https://...">
                </div>
            </div>

            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-header bg-transparent border-bottom-0 py-3">
                    <h6 class="mb-0"><?= lucide_icon('settings', 'me-2 text-primary', 16) ?>Tampilan</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label small fw-medium">Urutan</label>
                        <input type="number" name="sort_order" class="form-control" value="<?= esc(old('sort_order', $item['sort_order'] ?? 0)) ?>" min="0">
                        <div class="form-text small">Angka kecil tampil lebih dulu</div>
                    </div>
                    <div class="form-check form-switch">
                        <?php $isShowOn = old('show') === null
                            ? (!isset($item['show']) || (bool) $item['show'])
                            : (old('show') === '1'); ?>
                        <input type="hidden" name="show" value="0">
                        <input class="form-check-input" type="checkbox" name="show" value="1" id="show" <?= $isShowOn ? 'checked' : '' ?>>
                        <label class="form-check-label small fw-medium" for="show">Tampil di website</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4"><?= lucide_icon('save', 'me-1', 16) ?><?= $item ? 'Perbarui Testimoni' : 'Simpan Testimoni' ?></button>
                <a href="/admin/testimonials" class="btn btn-outline-secondary px-4"><?= lucide_icon('x', 'me-1', 16) ?>Batal</a>
            </div>
        </div>
    </div>
</form>
<?= $this->endSection() ?>