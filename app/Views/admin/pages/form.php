<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1"><?= $item ? 'Edit Halaman' : 'Tambah Halaman' ?></h4>
        <p class="text-body-tertiary small mb-0"><?= $item ? 'Perbarui konten halaman statis' : 'Buat halaman statis baru' ?></p>
    </div>
    <a href="/admin/pages" class="btn btn-outline-secondary btn-sm">
        <?= lucide_icon('arrow-left', 'me-1', 15) ?>Kembali
    </a>
</div>

<form action="/admin/pages<?= $item ? '/update/' . $item['id'] : '' ?>" method="post">
    <?= csrf_field() ?>
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-header bg-transparent border-bottom-0 py-3">
                    <h6 class="mb-0"><?= lucide_icon('file-text', 'me-2 text-primary', 16) ?>Konten Halaman</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label small fw-medium">Judul <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-body-tertiary border-end-0"><?= lucide_icon('type', 'text-body-tertiary', 16) ?></span>
                            <input type="text" name="title" id="page-title" class="form-control border-start-0 ps-0" value="<?= esc($item['title'] ?? '') ?>" placeholder="Contoh: Visi dan Misi" maxlength="255" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-medium">Slug (alamat URL)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-body-tertiary border-end-0 text-body-tertiary">/</span>
                            <input type="text" name="slug" id="page-slug" class="form-control border-start-0 ps-0" value="<?= esc($item['slug'] ?? '') ?>" placeholder="otomatis-dari-judul" maxlength="255">
                        </div>
                        <div class="form-text small">Biarkan kosong untuk otomatis dari judul. Mengubah slug mengubah alamat halaman (tautan lama berhenti berlaku).</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-medium">Konten</label>
                        <div class="quill-editor" style="min-height:300px"><?= $item['content'] ?? '' ?></div>
                        <textarea name="content" class="d-none"><?= $item['content'] ?? '' ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-medium">Ringkasan</label>
                        <textarea name="excerpt" class="form-control" rows="3" placeholder="Ringkasan singkat halaman..."><?= esc($item['excerpt'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-header bg-transparent border-bottom-0 py-3">
                    <h6 class="mb-0"><?= lucide_icon('search', 'me-2 text-primary', 16) ?>SEO</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label small fw-medium">Meta Title</label>
                        <input type="text" name="meta_title" class="form-control" value="<?= esc($item['meta_title'] ?? '') ?>" placeholder="Biarkan kosong untuk menggunakan judul halaman" maxlength="255">
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-medium">Meta Description</label>
                        <textarea name="meta_description" class="form-control" rows="2" placeholder="Deskripsi untuk SEO..." maxlength="255"><?= esc($item['meta_description'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <?php
            $pageImage    = trim((string) ($item['image'] ?? ''));
            $pageImageSrc = $pageImage === ''
                ? ''
                : ((preg_match('#^https?://#', $pageImage) || str_starts_with($pageImage, '/')) ? $pageImage : base_url($pageImage));
            ?>
            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-header bg-transparent border-bottom-0 py-3">
                    <h6 class="mb-0"><?= lucide_icon('image', 'me-2 text-primary', 16) ?>Gambar Halaman</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label small fw-medium">URL Gambar</label>
                        <input type="text" name="image" class="form-control" value="<?= esc($pageImage) ?>" placeholder="https://..." maxlength="255">
                        <div class="form-text small">Dipakai sebagai gambar sampul halaman dan pratinjau berbagi (OG). Isi URL gambar.</div>
                    </div>
                    <?php if ($pageImageSrc !== ''): ?>
                    <img src="<?= esc($pageImageSrc) ?>" alt="Pratinjau gambar halaman" class="rounded border w-100" style="max-height:160px;object-fit:cover">
                    <?php endif; ?>
                </div>
            </div>

            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-header bg-transparent border-bottom-0 py-3">
                    <h6 class="mb-0"><?= lucide_icon('settings', 'me-2 text-primary', 16) ?>Status</h6>
                </div>
                <div class="card-body">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="status" value="published" id="status" <?= (isset($item['status']) && $item['status'] === 'published') ? 'checked' : '' ?>>
                        <label class="form-check-label small fw-medium" for="status">Published</label>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="add_to_menu" value="1" id="add_to_menu" <?= (!isset($item) || empty($item)) ? 'checked' : (!empty($menuItem) ? 'checked' : '') ?>>
                        <label class="form-check-label small fw-medium" for="add_to_menu">Tambah ke menu navigasi</label>
                        <?php if (!empty($menuItem)): ?>
                        <div class="form-text small text-body-tertiary">Judul & URL menu otomatis tersinkronisasi dengan halaman ini. Matikan untuk melepas tautan halaman ini dari menu.</div>
                        <?php else: ?>
                        <div class="form-text small text-body-tertiary">Centang untuk menambahkan tautan halaman ini ke menu navigasi.</div>
                        <?php endif; ?>
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
                <button type="submit" class="btn btn-primary px-4"><?= lucide_icon('save', 'me-1', 16) ?><?= $item ? 'Perbarui Halaman' : 'Simpan Halaman' ?></button>
                <a href="/admin/pages" class="btn btn-outline-secondary px-4"><?= lucide_icon('x', 'me-1', 16) ?>Batal</a>
            </div>
        </div>
    </div>
</form>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Slug otomatis mengikuti judul selama belum disentuh (halaman baru).
    const titleInput = document.getElementById('page-title');
    const slugInput  = document.getElementById('page-slug');
    if (titleInput && slugInput) {
        let slugTouched = slugInput.value.trim() !== '';
        slugInput.addEventListener('input', function() { slugTouched = true; });
        titleInput.addEventListener('input', function() {
            if (slugTouched) return;
            slugInput.value = titleInput.value
                .toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .trim()
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');
        });
    }

    const editorEl = document.querySelector('.quill-editor');
    const textarea = document.querySelector('textarea[name="content"]');
    if (!editorEl || !textarea) return;

    const quill = new Quill(editorEl, {
        theme: 'snow',
        modules: {
            toolbar: [
                [{ header: [1,2,3,false] }],
                ['bold','italic','underline','strike'],
                [{ list: 'ordered' }, { list: 'bullet' }],
                ['blockquote', 'code-block'],
                [{ align: [] }],
                ['link', 'image'],
                ['clean']
            ]
        }
    });

    quill.root.innerHTML = textarea.value;

    // Pakai induk form dari textarea (bukan querySelector('form') —
    // form logout di header kini muncul lebih dulu di DOM).
    textarea.closest('form').addEventListener('submit', function() {
        textarea.value = quill.root.innerHTML;
    });
});
</script>
<?= $this->endSection() ?>
