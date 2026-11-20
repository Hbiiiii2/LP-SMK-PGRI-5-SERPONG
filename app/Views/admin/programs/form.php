<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<?php
helper('theme');
$isEdit       = !empty($item);
$mediaType    = old('media_type', $item['media_type'] ?? 'icon') === 'image' ? 'image' : 'icon';
$rawImage     = trim((string) old('existing_image', $item['image'] ?? ''));
$previewPhoto = ($rawImage !== '' && function_exists('program_image_url'))
    ? program_image_url(['media_type' => 'image', 'image' => $rawImage])
    : '';
$quickIcons   = ['network', 'laptop-code', 'cpu', 'calculator', 'briefcase', 'palette', 'flask', 'globe', 'graduation-cap'];
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1"><?= $isEdit ? 'Edit Program Keahlian' : 'Tambah Program Keahlian' ?></h4>
        <p class="text-body-tertiary small mb-0"><?= $isEdit ? 'Perbarui detail materi yang dipelajari, prospek lulusan, dan tampilan ikon/foto' : 'Tambahkan jurusan atau program keahlian baru beserta fokus materinya' ?></p>
    </div>
    <a href="/admin/programs" class="btn btn-outline-secondary btn-sm">
        <?= lucide_icon('arrow-left', 'me-1', 15) ?>Kembali
    </a>
</div>

<form action="/admin/programs<?= $isEdit ? '/update/' . (int) $item['id'] : '' ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-header bg-transparent border-bottom-0 py-3">
                    <h6 class="mb-0"><?= lucide_icon('star', 'me-2 text-primary', 16) ?>Detail Program &amp; Kurikulum</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label small fw-medium">Judul Program / Jurusan <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-body-tertiary border-end-0"><?= lucide_icon('type', 'text-body-tertiary', 16) ?></span>
                            <input type="text" name="title" id="program-title" class="form-control border-start-0 ps-0" value="<?= esc(old('title', $item['title'] ?? '')) ?>" placeholder="Contoh: Teknik Komputer dan Jaringan (TKJ)" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-medium">Slug URL</label>
                        <div class="input-group">
                            <span class="input-group-text bg-body-tertiary border-end-0 text-body-tertiary">/program/</span>
                            <input type="text" name="slug" id="program-slug" class="form-control border-start-0 ps-0" value="<?= esc(old('slug', $item['slug'] ?? '')) ?>" placeholder="otomatis-dari-judul" maxlength="220">
                        </div>
                        <div class="form-text small">Digunakan untuk alamat halaman detail program. Kosongkan agar dibuat otomatis dari judul.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-medium">Ringkasan Singkat</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Ringkasan singkat yang tampil di kartu beranda dan daftar jurusan..."><?= esc(old('description', $item['description'] ?? '')) ?></textarea>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
                            <label class="form-label small fw-medium mb-0">Fokus Materi yang Dipelajari (&ldquo;Belajar Apa Saja?&rdquo;)</label>
                            <div class="d-flex flex-wrap gap-1">
                                <span class="text-body-tertiary small align-self-center me-1">Isi Cepat:</span>
                                <button type="button" class="btn btn-outline-primary btn-sm py-0 px-2 js-preset-fill" data-preset="tkj">TKJ</button>
                                <button type="button" class="btn btn-outline-primary btn-sm py-0 px-2 js-preset-fill" data-preset="rpl">RPL / PPLG</button>
                                <button type="button" class="btn btn-outline-primary btn-sm py-0 px-2 js-preset-fill" data-preset="akl">Akuntansi (AKL)</button>
                                <button type="button" class="btn btn-outline-primary btn-sm py-0 px-2 js-preset-fill" data-preset="mplb">Perkantoran (MPLB)</button>
                                <button type="button" class="btn btn-outline-primary btn-sm py-0 px-2 js-preset-fill" data-preset="dkv">DKV / Multimedia</button>
                            </div>
                        </div>
                        <textarea name="learning_points" id="learning-points-input" class="form-control" rows="6" placeholder="Tulis 1 poin materi per baris, misal:&#10;Perakitan dan perbaikan komputer (Hardware & Troubleshooting)&#10;Instalasi jaringan LAN, WAN, Fiber Optik & MikroTik&#10;Administrasi server Linux dan keamanan jaringan"><?= esc(old('learning_points', $item['learning_points'] ?? '')) ?></textarea>
                        <div class="form-text small">1 baris = 1 poin materi pembelajaran. Tampil menonjol untuk orang tua dan calon siswa di halaman detail jurusan.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-medium">Prospek Lulusan &amp; Karier</label>
                        <textarea name="career_prospects" id="career-prospects-input" class="form-control" rows="4" placeholder="Tulis 1 peluang karier per baris, misal:&#10;Network Administrator&#10;IT Support & Infrastructure Engineer&#10;Teknisi Fiber Optik"><?= esc(old('career_prospects', $item['career_prospects'] ?? '')) ?></textarea>
                        <div class="form-text small">1 baris = 1 prospek kerja / studi lanjut lulusan program ini.</div>
                    </div>

                    <div class="mb-0">
                        <label class="form-label small fw-medium">Penjelasan Lengkap &amp; Fasilitas Praktik</label>
                        <div id="program-content-editor" style="min-height:240px"><?= old('content', $item['content'] ?? '') ?></div>
                        <textarea name="content" class="d-none"><?= esc(old('content', $item['content'] ?? '')) ?></textarea>
                        <div class="form-text small">Jelaskan metode praktik laboratorium, sertifikasi industri, atau kegiatan unggulan siswa di jurusan ini.</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-header bg-transparent border-bottom-0 py-3">
                    <h6 class="mb-0"><?= lucide_icon('palette', 'me-2 text-primary', 16) ?>Visual Program (Ikon atau Foto)</h6>
                </div>
                <div class="card-body">
                    <div class="btn-group w-100 mb-3" role="group" aria-label="Pilih tipe visual program">
                        <input type="radio" class="btn-check" name="media_type" id="mediaTypeIcon" value="icon" <?= $mediaType === 'icon' ? 'checked' : '' ?>>
                        <label class="btn btn-outline-primary btn-sm" for="mediaTypeIcon">
                            <?= lucide_icon('sparkles', 'me-1', 14) ?>Gunakan Ikon
                        </label>

                        <input type="radio" class="btn-check" name="media_type" id="mediaTypeImage" value="image" <?= $mediaType === 'image' ? 'checked' : '' ?>>
                        <label class="btn btn-outline-primary btn-sm" for="mediaTypeImage">
                            <?= lucide_icon('image', 'me-1', 14) ?>Gunakan Foto Upload
                        </label>
                    </div>

                    <div id="panel-media-icon" class="text-center<?= $mediaType === 'image' ? ' d-none' : '' ?>">
                        <div class="mb-3">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-3 bg-primary-subtle text-primary" style="width:72px;height:72px" id="icon-preview-box">
                                <?= lucide_icon((string) old('icon', $item['icon'] ?? 'star'), '', 30) ?>
                            </div>
                        </div>
                        <label class="form-label small fw-medium">Nama Ikon (Lucide / FA Legacy)</label>
                        <input type="text" name="icon" class="form-control form-control-sm mb-2" value="<?= esc(old('icon', $item['icon'] ?? 'star')) ?>" placeholder="graduation-cap" id="icon-input">
                        <div class="d-flex flex-wrap justify-content-center gap-1 mb-2">
                            <?php foreach ($quickIcons as $qi): ?>
                                <button type="button" class="btn btn-light border btn-sm py-0 px-2 js-icon-chip" data-icon="<?= esc($qi) ?>" title="<?= esc($qi) ?>"><?= esc($qi) ?></button>
                            <?php endforeach; ?>
                        </div>
                        <div class="form-text small text-body-secondary">Klik contoh di atas atau ketik nama ikon Lucide.</div>
                    </div>

                    <div id="panel-media-image" class="<?= $mediaType === 'icon' ? 'd-none' : '' ?>">
                        <div class="text-center mb-3">
                            <?php if ($previewPhoto !== ''): ?>
                                <img src="<?= esc($previewPhoto) ?>" alt="Pratinjau foto program" class="rounded-3 border shadow-sm object-fit-cover" style="width:100%;max-height:170px" id="program-image-preview">
                                <div class="d-none align-items-center justify-content-center rounded-3 bg-body-tertiary border" style="width:100%;height:140px" id="program-image-placeholder">
                                    <?= lucide_icon('image', 'text-body-tertiary opacity-50', 40) ?>
                                </div>
                            <?php else: ?>
                                <div class="d-inline-flex align-items-center justify-content-center rounded-3 bg-body-tertiary border" style="width:100%;height:140px" id="program-image-placeholder">
                                    <?= lucide_icon('image', 'text-body-tertiary opacity-50', 40) ?>
                                </div>
                                <img src="" alt="Pratinjau foto program" class="rounded-3 border shadow-sm object-fit-cover d-none" style="width:100%;max-height:170px" id="program-image-preview">
                            <?php endif; ?>
                        </div>

                        <div class="mb-2">
                            <label class="form-label small fw-medium">Unggah Foto Program / Lab</label>
                            <input type="file" name="image_file" class="form-control form-control-sm" accept="image/*" id="program-image-input">
                            <div class="form-text small">JPG, PNG, atau WebP (maks 5MB). Otomatis di-resize agar ringan dimuat.</div>
                        </div>

                        <div class="mb-2">
                            <label class="form-label small fw-medium">Atau URL / Path Foto</label>
                            <input type="text" name="existing_image" id="program-existing-image" class="form-control form-control-sm" value="<?= esc($rawImage) ?>" placeholder="uploads/programs/foto.jpg atau https://...">
                        </div>

                        <?php if ($rawImage !== ''): ?>
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" name="remove_image" value="1" id="remove-image-check">
                            <label class="form-check-label small text-danger" for="remove-image-check">Hapus foto saat ini</label>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-header bg-transparent border-bottom-0 py-3">
                    <h6 class="mb-0"><?= lucide_icon('link-2', 'me-2 text-primary', 16) ?>Tautan</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label small fw-medium">URL Tujuan (Opsional)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-body-tertiary border-end-0"><?= lucide_icon('external-link', 'text-body-tertiary', 16) ?></span>
                            <input type="text" name="link_url" class="form-control border-start-0 ps-0" value="<?= esc(old('link_url', $item['link_url'] ?? '')) ?>" placeholder="Kosongkan untuk buka /program/{slug}">
                        </div>
                        <div class="form-text small">Kosongkan agar kartu otomatis membuka halaman detail materi program ini.</div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small fw-medium">Teks Tombol</label>
                        <input type="text" name="link_text" class="form-control" value="<?= esc(old('link_text', $item['link_text'] ?? 'Pelajari Detail')) ?>" placeholder="Contoh: Pelajari Detail">
                    </div>
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
                        <input class="form-check-input" type="checkbox" name="show" value="1" id="show" <?= (!isset($item['show']) || $item['show']) ? 'checked' : '' ?>>
                        <label class="form-check-label small fw-medium" for="show">Tampil di website</label>
                    </div>
                </div>
            </div>

            <?php if ($isEdit): ?>
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
                <button type="submit" class="btn btn-primary px-4"><?= lucide_icon('save', 'me-1', 16) ?><?= $isEdit ? 'Perbarui Program' : 'Simpan Program' ?></button>
                <a href="/admin/programs" class="btn btn-outline-secondary px-4"><?= lucide_icon('x', 'me-1', 16) ?>Batal</a>
            </div>
        </div>
    </div>
</form>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const titleInput = document.getElementById('program-title');
    const slugInput = document.getElementById('program-slug');
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

    const iconInput = document.getElementById('icon-input');
    const iconPreviewBox = document.getElementById('icon-preview-box');
    function updateIconPreview(val) {
        const useEl = iconPreviewBox?.querySelector('svg use');
        if (useEl && typeof window.resolveLucideSymbol === 'function' && window.lucideSpriteUrl) {
            const sym = window.resolveLucideSymbol(val || 'star');
            useEl.setAttribute('href', window.lucideSpriteUrl + '#lucide-' + sym);
        }
    }
    iconInput?.addEventListener('input', function() {
        updateIconPreview(this.value);
    });
    document.querySelectorAll('.js-icon-chip').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const ic = this.getAttribute('data-icon') || 'star';
            if (iconInput) iconInput.value = ic;
            updateIconPreview(ic);
        });
    });

    const radioIcon = document.getElementById('mediaTypeIcon');
    const radioImage = document.getElementById('mediaTypeImage');
    const panelIcon = document.getElementById('panel-media-icon');
    const panelImage = document.getElementById('panel-media-image');
    function syncMediaPanels() {
        const isImg = Boolean(radioImage && radioImage.checked);
        panelIcon?.classList.toggle('d-none', isImg);
        panelImage?.classList.toggle('d-none', !isImg);
    }
    radioIcon?.addEventListener('change', syncMediaPanels);
    radioImage?.addEventListener('change', syncMediaPanels);

    const fileInput = document.getElementById('program-image-input');
    const imgPreview = document.getElementById('program-image-preview');
    const imgPlaceholder = document.getElementById('program-image-placeholder');
    fileInput?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function(ev) {
            if (imgPreview) {
                imgPreview.src = ev.target.result;
                imgPreview.classList.remove('d-none');
            }
            if (imgPlaceholder) {
                imgPlaceholder.classList.add('d-none');
                imgPlaceholder.classList.remove('d-inline-flex');
            }
        };
        reader.readAsDataURL(file);
    });

    const editorEl = document.getElementById('program-content-editor');
    const contentTa = document.querySelector('textarea[name="content"]');
    let quill = null;
    if (editorEl && contentTa && typeof Quill !== 'undefined') {
        quill = new Quill(editorEl, {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ header: [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ list: 'ordered' }, { list: 'bullet' }],
                    ['blockquote'],
                    ['link', 'clean']
                ]
            }
        });
        quill.root.innerHTML = contentTa.value;
        contentTa.closest('form')?.addEventListener('submit', function() {
            const html = quill.root.innerHTML;
            contentTa.value = (html === '<p><br></p>') ? '' : html;
        });
    }

    const PRESETS = {
        tkj: {
            icon: 'network',
            learning: [
                'Perakitan, perawatan, dan troubleshooting komputer (PC & perangkat keras)',
                'Instalasi sistem operasi Windows & Linux Server serta manajemen layanan jaringan',
                'Perancangan topologi LAN, WAN, routing & switching (MikroTik / Cisco)',
                'Instalasi kabel Fiber Optik (FTTH), crimping, dan konfigurasi Wi-Fi enterprise',
                'Keamanan jaringan (Firewall, VPN, manajemen bandwidth) & komputasi awan dasar'
            ],
            careers: [
                'Network Administrator & Teknisi Jaringan',
                'IT Support / Helpdesk & Infrastructure Engineer',
                'Teknisi Fiber Optik & Telekomunikasi',
                'System Administrator Junior',
                'Wirausaha Jasa Servis Komputer & Instalasi Jaringan'
            ]
        },
        rpl: {
            icon: 'laptop-code',
            learning: [
                'Logika algoritma, struktur data, dan pemrograman berorientasi objek',
                'Pengembangan aplikasi web full-stack (HTML, CSS, JavaScript, PHP, database SQL)',
                'Perancangan antarmuka pengguna (UI/UX) dan pembuatan aplikasi mobile dasar',
                'Version control Git, pengujian aplikasi, dan praktik proyek perangkat lunak'
            ],
            careers: [
                'Web Developer Junior (Front-End / Back-End / Full-Stack)',
                'Mobile App & UI/UX Designer',
                'Quality Assurance & Database Operator'
            ]
        },
        akl: {
            icon: 'calculator',
            learning: [
                'Siklus akuntansi perusahaan jasa, dagang, dan manufaktur',
                'Pengolahan angka dengan Excel lanjutan dan aplikasi akuntansi (MYOB / Accurate)',
                'Administrasi perpajakan dan penyusunan laporan keuangan neraca & laba rugi',
                'Pengelolaan kas, perbankan dasar, dan etika profesi akuntansi'
            ],
            careers: [
                'Staf Akuntansi & Pembukuan Perusahaan',
                'Operator Perpajakan & Payroll',
                'Teller / Layanan Nasabah Lembaga Keuangan'
            ]
        },
        mplb: {
            icon: 'briefcase',
            learning: [
                'Korespondensi bisnis Indonesia & Inggris serta surat-menyurat digital',
                'Kearsipan modern, tata kelola dokumen perkantoran, dan penjadwalan pimpinan',
                'Aplikasi perkantoran terpadu dan layanan prima pelanggan (customer care)'
            ],
            careers: [
                'Staf Administrasi Perkantoran & Sekretaris Junior',
                'Front Office / Customer Care Executive',
                'Arsiparis Digital & Staf HRD'
            ]
        },
        dkv: {
            icon: 'palette',
            learning: [
                'Tipografi, teori warna, ilustrasi digital, dan identitas visual brand',
                'Desain grafis vektor & bitmap dengan perangkat lunak industri',
                'Fotografi studio, videografi, tata cahaya, dan penyuntingan video'
            ],
            careers: [
                'Graphic Designer & Brand Visual Specialist',
                'Video Editor & Motion Graphic Artist',
                'Kreator Konten Media Sosial & Desainer Lepas'
            ]
        }
    };

    const learningTa = document.getElementById('learning-points-input');
    const careerTa = document.getElementById('career-prospects-input');
    document.querySelectorAll('.js-preset-fill').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const p = PRESETS[this.getAttribute('data-preset') || ''];
            if (!p) return;
            if (learningTa) learningTa.value = p.learning.join('\n');
            if (careerTa) careerTa.value = p.careers.join('\n');
            if (iconInput && p.icon) {
                iconInput.value = p.icon;
                updateIconPreview(p.icon);
            }
        });
    });
});
</script>
<?= $this->endSection() ?>
