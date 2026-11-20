<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<style>
.card-header[data-bs-toggle="collapse"]{cursor:pointer}
.card-header[data-bs-toggle="collapse"] .collapse-icon{transition:transform .2s ease}
.card-header[data-bs-toggle="collapse"].collapsed .collapse-icon{transform:rotate(-90deg)}

</style>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Pengaturan</h4>
        <p class="text-body-tertiary small mb-0">Kelola konfigurasi website sekolah</p>
    </div>
    <span class="badge bg-primary-subtle text-primary px-3 py-2"><?= count($settings ?? []) ?> pengaturan</span>
</div>

<form action="/admin/settings/save" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-header bg-transparent border-bottom-0 py-3 d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#collapseIdentitas" role="button">
                    <h6 class="mb-0"><?= lucide_icon('globe', 'me-2 text-primary', 16) ?>Identitas Situs</h6>
                    <?= lucide_icon('chevron-down', 'text-body-tertiary collapse-icon', 14) ?>
                </div>
                <div class="collapse" id="collapseIdentitas">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label small fw-medium">Nama Situs</label>
                            <div class="input-group">
                                <span class="input-group-text bg-body-tertiary border-end-0"><?= lucide_icon('user-pen', 'text-body-tertiary', 16) ?></span>
                                <input type="text" name="site_name" class="form-control border-start-0 ps-0" value="<?= esc($settings['site_name'] ?? '') ?>" placeholder="Mis. SMP Negeri 1 Jakarta">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-medium">Teks Logo</label>
                            <div class="input-group">
                                <span class="input-group-text bg-body-tertiary border-end-0"><?= lucide_icon('type', 'text-body-tertiary', 16) ?></span>
                                <input type="text" name="site_logo_text" class="form-control border-start-0 ps-0" value="<?= esc($settings['site_logo_text'] ?? '') ?>" placeholder="Mis. SMPN 1 JKT">
                            </div>
                            <div class="form-text small">Teks pendek yang ditampilkan di area navbar/brand</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-medium">Ikon Logo (Font Awesome)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-body-tertiary border-end-0"><?= lucide_icon('sparkles', 'text-body-tertiary', 16) ?></span>
                                <input type="text" name="site_logo_icon" class="form-control border-start-0 ps-0" value="<?= esc($settings['site_logo_icon'] ?? '') ?>" placeholder="Mis. school">
                            </div>
                            <div class="form-text small text-body-secondary">Nama ikon Lucide atau class FA legacy (mis. <code>school</code>, <code>graduation-cap</code>).</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-medium">Tagline</label>
                            <input type="text" name="site_tagline" class="form-control" value="<?= esc($settings['site_tagline'] ?? '') ?>" placeholder="Mis. Unggul dalam Prestasi, Berkarakter Mulia">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-medium">Deskripsi</label>
                            <textarea name="site_description" class="form-control" rows="3" placeholder="Deskripsi singkat tentang sekolah untuk SEO dan meta tag..."><?= esc($settings['site_description'] ?? '') ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-header bg-transparent border-bottom-0 py-3 d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#collapseKontak" role="button">
                    <h6 class="mb-0"><?= lucide_icon('phone', 'me-2 text-primary', 16) ?>Informasi Kontak</h6>
                    <?= lucide_icon('chevron-down', 'text-body-tertiary collapse-icon', 14) ?>
                </div>
                <div class="collapse" id="collapseKontak">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label small fw-medium">Telepon</label>
                            <div class="input-group">
                                <span class="input-group-text bg-body-tertiary border-end-0"><?= lucide_icon('phone', 'text-body-tertiary', 16) ?></span>
                                <input type="text" name="contact_phone" class="form-control border-start-0 ps-0" value="<?= esc($settings['contact_phone'] ?? '') ?>" placeholder="Mis. (021) 12345678">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-medium">Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-body-tertiary border-end-0"><?= lucide_icon('mail', 'text-body-tertiary', 16) ?></span>
                                <input type="email" name="contact_email" class="form-control border-start-0 ps-0" value="<?= esc($settings['contact_email'] ?? '') ?>" placeholder="Mis. info@sekolah.sch.id">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-medium">Alamat</label>
                            <textarea name="contact_address" class="form-control" rows="2" placeholder="Mis. Jl. Merdeka No. 123, Jakarta Pusat"><?= esc($settings['contact_address'] ?? '') ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-medium">Jam Operasional</label>
                            <div class="input-group">
                                <span class="input-group-text bg-body-tertiary border-end-0"><?= lucide_icon('clock', 'text-body-tertiary', 16) ?></span>
                                <input type="text" name="contact_hours" class="form-control border-start-0 ps-0" value="<?= esc($settings['contact_hours'] ?? '') ?>" placeholder="Mis. Sen-Jum 07:00-16:00">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-header bg-transparent border-bottom-0 py-3 d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#collapseSosial" role="button">
                    <h6 class="mb-0"><?= lucide_icon('share-2', 'me-2 text-primary', 16) ?>Media Sosial</h6>
                    <?= lucide_icon('chevron-down', 'text-body-tertiary collapse-icon', 14) ?>
                </div>
                <div class="collapse" id="collapseSosial">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-medium">Facebook</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-body-tertiary border-end-0"><?= lucide_icon('facebook', 'text-primary', 16) ?></span>
                                    <input type="text" name="social_facebook" class="form-control border-start-0 ps-0" value="<?= esc($settings['social_facebook'] ?? '') ?>" placeholder="https://facebook.com/...">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-medium">Instagram</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-body-tertiary border-end-0"><?= lucide_icon('instagram', 'text-danger', 16) ?></span>
                                    <input type="text" name="social_instagram" class="form-control border-start-0 ps-0" value="<?= esc($settings['social_instagram'] ?? '') ?>" placeholder="https://instagram.com/...">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-medium">YouTube</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-body-tertiary border-end-0"><?= lucide_icon('youtube', 'text-danger', 16) ?></span>
                                    <input type="text" name="social_youtube" class="form-control border-start-0 ps-0" value="<?= esc($settings['social_youtube'] ?? '') ?>" placeholder="https://youtube.com/@...">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-medium">TikTok</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-body-tertiary border-end-0"><?= lucide_icon('video', 'text-dark', 16) ?></span>
                                    <input type="text" name="social_tiktok" class="form-control border-start-0 ps-0" value="<?= esc($settings['social_tiktok'] ?? '') ?>" placeholder="https://tiktok.com/@...">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-header bg-transparent border-bottom-0 py-3 d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#collapseHeroStats" role="button">
                    <h6 class="mb-0"><?= lucide_icon('bar-chart-2', 'me-2 text-primary', 16) ?>Statistik Hero</h6>
                    <?= lucide_icon('chevron-down', 'text-body-tertiary collapse-icon', 14) ?>
                </div>
                <div class="collapse" id="collapseHeroStats">
                    <div class="card-body">
                        <p class="small text-body-tertiary mb-3">Angka statistik di hero section — icon cukup nama class Font Awesome tanpa <code>fa-</code></p>
                        <?php $heroStats = !empty($settings['hero_stats']) ? json_decode($settings['hero_stats'], true) : []; ?>
                        <?php for ($i = 0; $i < 3; $i++): ?>
                        <?php $item = $heroStats[$i] ?? ['icon' => '', 'value' => '', 'label' => '']; ?>
                        <div class="row g-2 mb-3 align-items-end border rounded-3 p-3 bg-body-tertiary">
                            <div class="col-md-4">
                                <label class="form-label small fw-medium">Ikon</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-body-tertiary border-end-0"><?= lucide_icon('sparkles', 'text-body-tertiary', 16) ?></span>
                                    <input type="text" name="hero_stats[<?= $i ?>][icon]" class="form-control border-start-0 ps-0" value="<?= esc($item['icon']) ?>" placeholder="user-graduate">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-medium">Nilai</label>
                                <input type="text" name="hero_stats[<?= $i ?>][value]" class="form-control form-control-sm" value="<?= esc($item['value']) ?>" placeholder="1200+">
                            </div>
                            <div class="col-md-5">
                                <label class="form-label small fw-medium">Label</label>
                                <input type="text" name="hero_stats[<?= $i ?>][label]" class="form-control form-control-sm" value="<?= esc($item['label']) ?>" placeholder="Siswa">
                            </div>
                        </div>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-header bg-transparent border-bottom-0 py-3 d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#collapseTema" role="button">
                    <h6 class="mb-0"><?= lucide_icon('palette', 'me-2 text-primary', 16) ?>Tema Tampilan</h6>
                    <?= lucide_icon('chevron-down', 'text-body-tertiary collapse-icon', 14) ?>
                </div>
                <div class="collapse" id="collapseTema">
                    <div class="card-body">
                        <p class="small text-body-tertiary mb-3">Pilih tema tampilan website depan. Mengganti tema otomatis menghapus cache.</p>
                            <?php $themeList = theme_list(); $activeThemeName = $settings['active_theme'] ?? active_theme(); ?>
                            <?php if (empty($themeList)): ?>
                            <div class="alert alert-warning">Tidak ada theme ditemukan di <code>app/Views/themes/</code>.</div>
                            <?php else: ?>
                            <div class="row g-3" id="themePicker">
                            <?php foreach ($themeList as $t): ?>
                            <div class="col-md-4">
                                <label class="card h-100 theme-card <?= $activeThemeName === $t['name'] ? 'selected' : '' ?>" style="cursor:pointer" data-theme-name="<?= esc($t['name']) ?>">
                                    <input type="radio" name="active_theme" value="<?= esc($t['name']) ?>" class="theme-radio" <?= $activeThemeName === $t['name'] ? 'checked' : '' ?>>
                                    <span class="theme-check" aria-hidden="true"><?= lucide_icon('check', '', 13) ?></span>
                                    <?php if ($t['screenshot']): ?>
                                    <img src="<?= esc($t['screenshot']) ?>" class="card-img-top" alt="<?= esc($t['title']) ?>" loading="lazy">
                                    <?php else: ?>
                                    <div class="card-img-top d-flex align-items-center justify-content-center bg-body-tertiary" style="height:120px"><?= lucide_icon('image', 'text-body-tertiary', 28) ?></div>
                                    <?php endif; ?>
                                    <div class="card-body py-2">
                                        <div class="fw-semibold"><?= esc($t['title']) ?><?php if ($t['version'] !== ''): ?> <small class="text-body-tertiary">v<?= esc($t['version']) ?></small><?php endif; ?></div>
                                        <?php if ($t['description'] !== ''): ?><div class="small text-body-tertiary"><?= esc($t['description']) ?></div><?php endif; ?>
                                    </div>
                                </label>
                            </div>
                            <?php endforeach; ?>
                            </div>
                            <?php endif; ?>
                        <p class="form-text small mt-3 mb-0">Menambah theme baru: buat folder di <code>app/Views/themes/&lt;nama&gt;</code> + <code>public/themes/&lt;nama&gt;</code> berikut <code>theme.json</code>, lalu jalankan <code>php minify.php</code>.</p>
                    </div>
                </div>
            </div>
            <style>
            /* Kartu pemilih theme: radio disembunyikan visual tapi tetap bisa diklik via label
               (jangan pakai display:none — klik label diabaikan sebagian browser) */
            .theme-card { position: relative; transition: border-color .15s, box-shadow .15s; }
            .theme-card .theme-radio { position: absolute; opacity: 0; pointer-events: none; }
            .theme-card:hover { border-color: var(--color-primary, #087F3E) !important; }
            .theme-card.selected { border-color: var(--color-primary, #087F3E) !important; box-shadow: 0 0 0 2px var(--color-primary, #087F3E); }
            .theme-card .theme-check {
                position: absolute; top: 8px; right: 8px; width: 26px; height: 26px;
                display: none; align-items: center; justify-content: center;
                border-radius: 50%; background: var(--color-primary, #087F3E); color: #fff; font-size: 12px;
            }
            .theme-card.selected .theme-check { display: inline-flex; }
            .theme-card .theme-radio:focus-visible + .theme-check { display: inline-flex; outline: 2px solid #fff; outline-offset: 2px; }
            </style>

            <?php $principalData = !empty($settings['principal']) ? json_decode($settings['principal'], true) : []; ?>
            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-header bg-transparent border-bottom-0 py-3 d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#collapseKepsek" role="button">
                    <h6 class="mb-0"><?= lucide_icon('user-check', 'me-2 text-primary', 16) ?>Kepala Sekolah</h6>
                    <?= lucide_icon('chevron-down', 'text-body-tertiary collapse-icon', 14) ?>
                </div>
                <div class="collapse" id="collapseKepsek">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label small fw-medium">Foto</label>
                            <div class="mb-2">
                                <img src="<?= esc($principalData['photo'] ?? '/images/default-principal.jpg') ?>" alt="preview" style="max-height:120px;width:auto" class="rounded border">
                            </div>
                            <div class="input-group">
                                <span class="input-group-text bg-body-tertiary border-end-0"><?= lucide_icon('image', 'text-body-tertiary', 16) ?></span>
                                <input type="file" name="principal_photo" class="form-control border-start-0 ps-0" accept="image/jpeg,image/png,image/webp">
                            </div>
                            <input type="hidden" name="principal[current_image]" value="<?= esc($principalData['photo'] ?? '') ?>">
                            <div class="form-text small">Upload foto kepala sekolah. Maks <?= esc($uploadLimit ?? '5 MB') ?>, JPG/PNG/WebP. Kosongkan jika tidak diubah.</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-medium">Nama <span class="text-danger">*</span></label>
                            <input type="text" name="principal[name]" class="form-control" value="<?= esc($principalData['name'] ?? '') ?>" placeholder="Mis. Drs. H. Ahmad Fauzi, M.Pd.">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-medium">Jabatan</label>
                            <input type="text" name="principal[role]" class="form-control" value="<?= esc($principalData['role'] ?? '') ?>" placeholder="Mis. Kepala Sekolah">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-medium">Sambutan / Kutipan</label>
                            <textarea name="principal[quote]" class="form-control" rows="3" placeholder="Sambutan atau kutipan dari kepala sekolah..."><?= esc($principalData['quote'] ?? '') ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-header bg-transparent border-bottom-0 py-3 d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#collapseHeroSection" role="button">
                    <h6 class="mb-0"><?= lucide_icon('star', 'me-2 text-primary', 16) ?>Bagian Hero</h6>
                    <?= lucide_icon('chevron-down', 'text-body-tertiary collapse-icon', 14) ?>
                </div>
                <div class="collapse" id="collapseHeroSection">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label small fw-medium">Lencana</label>
                            <div class="input-group">
                                <span class="input-group-text bg-body-tertiary border-end-0"><?= lucide_icon('tag', 'text-body-tertiary', 16) ?></span>
                                <input type="text" name="hero_badge" class="form-control border-start-0 ps-0" value="<?= esc($settings['hero_badge'] ?? '') ?>" placeholder="Mis. Terakreditasi A">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-medium">Judul</label>
                            <input type="text" name="hero_title" class="form-control" value="<?= esc($settings['hero_title'] ?? '') ?>" placeholder="Mis. Selamat Datang di Sekolah Kami">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-medium">Subjudul</label>
                            <textarea name="hero_subtitle" class="form-control" rows="2" placeholder="Mis. Mencerdaskan kehidupan bangsa melalui pendidikan berkualitas..."><?= esc($settings['hero_subtitle'] ?? '') ?></textarea>
                        </div>
                        <hr class="my-3">
                        <h6 class="small fw-semibold text-body-tertiary mb-2"><?= lucide_icon('link-2', 'me-1', 14) ?>Tombol Hero</h6>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-medium">Teks Tombol Utama</label>
                                <input type="text" name="hero_btn_primary_text" class="form-control" value="<?= esc($settings['hero_btn_primary_text'] ?? '') ?>" placeholder="Mis. Informasi Pendaftaran">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-medium">URL Tombol Utama</label>
                                <input type="text" name="hero_btn_primary_url" class="form-control" value="<?= esc($settings['hero_btn_primary_url'] ?? '') ?>" placeholder="Mis. /spmb">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-medium">Teks Tombol Sekunder</label>
                                <input type="text" name="hero_btn_secondary_text" class="form-control" value="<?= esc($settings['hero_btn_secondary_text'] ?? '') ?>" placeholder="Mis. Tentang Kami">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-medium">URL Tombol Sekunder</label>
                                <input type="text" name="hero_btn_secondary_url" class="form-control" value="<?= esc($settings['hero_btn_secondary_url'] ?? '') ?>" placeholder="Mis. /tentang">
                            </div>
                        </div>
                        <div class="border rounded-3 bg-body-tertiary p-3">
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <span class="badge bg-primary-subtle text-primary fs-6"><?= esc($settings['hero_badge'] ?? 'Lencana') ?></span>
                                <span class="small text-body-tertiary"><?= lucide_icon('phone', 'me-1', 14) ?>Pratinjau</span>
                            </div>
                            <h5 class="mb-1"><?= esc($settings['hero_title'] ?? 'Judul Hero') ?></h5>
                            <p class="small text-body-tertiary mb-2"><?= esc($settings['hero_subtitle'] ?? 'Subtitle hero akan muncul di sini...') ?></p>
                            <div class="d-flex gap-2">
                                <span class="btn btn-primary btn-sm disabled"><?= esc($settings['hero_btn_primary_text'] ?? 'Utama') ?></span>
                                <span class="btn btn-outline-secondary btn-sm disabled"><?= esc($settings['hero_btn_secondary_text'] ?? 'Sekunder') ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-header bg-transparent border-bottom-0 py-3 d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#collapseSpmb" role="button">
                    <h6 class="mb-0"><?= lucide_icon('graduation-cap', 'me-2 text-primary', 16) ?>SPMB</h6>
                    <?= lucide_icon('chevron-down', 'text-body-tertiary collapse-icon', 14) ?>
                </div>
                <div class="collapse" id="collapseSpmb">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label small fw-medium">URL SPMB</label>
                            <div class="input-group">
                                <span class="input-group-text bg-body-tertiary border-end-0"><?= lucide_icon('external-link', 'text-body-tertiary', 16) ?></span>
                                <input type="text" name="spmb_url" class="form-control border-start-0 ps-0" value="<?= esc($settings['spmb_url'] ?? '') ?>" placeholder="Mis. https://spmb.sekolah.sch.id">
                            </div>
                            <div class="form-text small">Tautan ke portal pendaftaran SPMB</div>
                        </div>
                        <?php if (!empty($settings['spmb_url'])): ?>
                        <a href="<?= esc($settings['spmb_url']) ?>" target="_blank" class="btn btn-success btn-sm">
                            <?= lucide_icon('external-link', 'me-1', 14) ?>Uji Tautan
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <?php $spmbRows = resolve_spmb_steps($settings['spmb_steps'] ?? null); ?>
            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-header bg-transparent border-bottom-0 py-3 d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#collapseSpmbSteps" role="button">
                    <h6 class="mb-0"><?= lucide_icon('list-ordered', 'me-2 text-primary', 16) ?>Tahapan SPMB</h6>
                    <?= lucide_icon('chevron-down', 'text-body-tertiary collapse-icon', 14) ?>
                </div>
                <div class="collapse" id="collapseSpmbSteps">
                    <div class="card-body">
                        <p class="small text-body-tertiary mb-2">Daftar langkah pendaftaran di section SPMB beranda. Tiap jenjang bisa berbeda — gunakan tombol preset untuk menyesuaikan cepat (mis. Nilai Rapor SD tematik vs SMP/SMA per mata pelajaran), lalu ubah seperlunya.</p>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <span class="small text-body-tertiary align-self-center">Sesuaikan cepat:</span>
                            <?php foreach (array_keys(spmb_step_presets()) as $__jenjang): ?>
                            <button type="button" class="btn btn-outline-secondary btn-sm spmb-preset" data-preset="<?= esc($__jenjang) ?>">Preset <?= esc($__jenjang) ?></button>
                            <?php endforeach; ?>
                        </div>
                        <div id="spmbStepsList">
                            <?php foreach ($spmbRows as $idx => $st): ?>
                            <div class="row g-2 mb-2 align-items-center spmb-step-item">
                                <div class="col-auto"><span class="badge bg-body-tertiary text-body-tertiary spmb-step-num"><?= $idx + 1 ?></span></div>
                                <div class="col-md-3">
                                    <input type="text" name="spmb_steps[<?= $idx ?>][title]" class="form-control form-control-sm" value="<?= esc($st['title'] ?? '') ?>" placeholder="Judul langkah" maxlength="60">
                                </div>
                                <div class="col">
                                    <input type="text" name="spmb_steps[<?= $idx ?>][desc]" class="form-control form-control-sm" value="<?= esc($st['desc'] ?? '') ?>" placeholder="Keterangan singkat" maxlength="120">
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="btn btn-outline-danger btn-sm remove-spmb-step" title="Hapus" style="width:32px;height:32px"><?= lucide_icon('x', '', 14) ?></button>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm mt-1" id="addSpmbStep"><?= lucide_icon('plus', 'me-1', 14) ?>Tambah Langkah</button>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-header bg-transparent border-bottom-0 py-3 d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#collapseCounter" role="button">
                    <h6 class="mb-0"><?= lucide_icon('bar-chart-2', 'me-2 text-primary', 16) ?>Statistik Counter</h6>
                    <?= lucide_icon('chevron-down', 'text-body-tertiary collapse-icon', 14) ?>
                </div>
                <div class="collapse" id="collapseCounter">
                    <div class="card-body">
                        <p class="small text-body-tertiary mb-3">Angka statistik di section counter (animasi naik) — icon cukup nama class Font Awesome tanpa <code>fa-</code></p>
                        <?php $counterStats = !empty($settings['counter_stats']) ? json_decode($settings['counter_stats'], true) : []; ?>
                        <?php for ($i = 0; $i < 4; $i++): ?>
                        <?php $item = $counterStats[$i] ?? ['icon' => '', 'number' => '', 'suffix' => '+', 'label' => '']; ?>
                        <div class="row g-2 mb-3 align-items-end border rounded-3 p-3 bg-body-tertiary">
                            <div class="col-md-3">
                                <label class="form-label small fw-medium">Ikon</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-body-tertiary border-end-0"><?= lucide_icon('sparkles', 'text-body-tertiary', 16) ?></span>
                                    <input type="text" name="counter_stats[<?= $i ?>][icon]" class="form-control border-start-0 ps-0" value="<?= esc($item['icon']) ?>" placeholder="layer-group">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-medium">Angka</label>
                                <input type="number" name="counter_stats[<?= $i ?>][number]" class="form-control form-control-sm" value="<?= esc($item['number']) ?>" placeholder="40">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-medium">Akhiran</label>
                                <input type="text" name="counter_stats[<?= $i ?>][suffix]" class="form-control form-control-sm" value="<?= esc($item['suffix']) ?>" placeholder="+">
                            </div>
                            <div class="col-md-5">
                                <label class="form-label small fw-medium">Label</label>
                                <input type="text" name="counter_stats[<?= $i ?>][label]" class="form-control form-control-sm" value="<?= esc($item['label']) ?>" placeholder="Rombongan Belajar">
                            </div>
                        </div>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>

            <?php $banners = !empty($settings['page_banners']) ? json_decode($settings['page_banners'], true) : []; ?>
            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-header bg-transparent border-bottom-0 py-3 d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#collapseBanners" role="button">
                    <h6 class="mb-0"><?= lucide_icon('image', 'me-2 text-primary', 16) ?>Banner Halaman</h6>
                    <?= lucide_icon('chevron-down', 'text-body-tertiary collapse-icon', 14) ?>
                </div>
                <div class="collapse" id="collapseBanners">
                    <div class="card-body">
                        <p class="small text-body-tertiary mb-3">Atur badge, judul, dan subtitle banner di setiap halaman. Untuk <strong>Detail Berita</strong> dan <strong>Halaman Statis</strong> hanya <em>lencana</em> yang tampil (judul mengikuti isi halaman). Kosongkan untuk memakai teks bawaan tema.</p>
                        <?php $pages = ['news' => 'Berita', 'single_post' => 'Detail Berita', 'pages' => 'Halaman Statis', 'contact' => 'Kontak', 'downloads' => 'Download Center']; ?>
                        <?php foreach ($pages as $key => $label): ?>
                        <?php $b = $banners[$key] ?? ['badge' => '', 'title' => '', 'subtitle' => '']; ?>
                        <div class="border rounded-3 p-3 bg-body-tertiary mb-3">
                            <h6 class="small fw-semibold mb-2"><?= esc($label) ?></h6>
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <label class="form-label small fw-medium">Lencana</label>
                                    <input type="text" name="page_banners[<?= $key ?>][badge]" class="form-control form-control-sm" value="<?= esc($b['badge']) ?>" placeholder="Mis. Berita Terkini">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-medium">Judul</label>
                                    <input type="text" name="page_banners[<?= $key ?>][title]" class="form-control form-control-sm" value="<?= esc($b['title']) ?>" placeholder="Mis. Baca Berita">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-medium">Subtitle</label>
                                    <input type="text" name="page_banners[<?= $key ?>][subtitle]" class="form-control form-control-sm" value="<?= esc($b['subtitle']) ?>" placeholder="Informasi terbaru...">
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <?php $aboutData = !empty($settings['about']) ? json_decode($settings['about'], true) : []; ?>
            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-header bg-transparent border-bottom-0 py-3 d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#collapseAbout" role="button">
                    <h6 class="mb-0"><?= lucide_icon('school', 'me-2 text-primary', 16) ?>Tentang Kami / Profil Sekolah</h6>
                    <?= lucide_icon('chevron-down', 'text-body-tertiary collapse-icon', 14) ?>
                </div>
                <div class="collapse" id="collapseAbout">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label small fw-medium">Gambar</label>
                            <?php if (!empty($aboutData['image'])): ?>
                            <div class="mb-2">
                                <img src="<?= esc($aboutData['image']) ?>" alt="preview" style="max-height:100px;width:auto" class="rounded border">
                            </div>
                            <?php endif; ?>
                            <div class="input-group">
                                <span class="input-group-text bg-body-tertiary border-end-0"><?= lucide_icon('image', 'text-body-tertiary', 16) ?></span>
                                <input type="file" name="about_image" class="form-control border-start-0 ps-0" accept="image/jpeg,image/png,image/webp">
                            </div>
                            <input type="hidden" name="about[current_image]" value="<?= esc($aboutData['image'] ?? '') ?>">
                            <div class="form-text small">Upload gambar (akan diubah ke lebar 1200px). Maks <?= esc($uploadLimit ?? '5 MB') ?>, JPG/PNG/WebP. Kosongkan jika tidak diubah.</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-medium">Judul Konten</label>
                            <input type="text" name="about[content_title]" class="form-control" value="<?= esc($aboutData['content_title'] ?? '') ?>" placeholder="Mis. Mewujudkan Pendidikan Berkualitas untuk Masa Depan">
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-medium">Visi</label>
                                <textarea name="about[visi]" class="form-control" rows="3" placeholder="Visi sekolah..."><?= esc($aboutData['visi'] ?? '') ?></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-medium">Misi</label>
                                <textarea name="about[misi]" class="form-control" rows="3" placeholder="Misi sekolah..."><?= esc($aboutData['misi'] ?? '') ?></textarea>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-medium">Paragraf 1</label>
                            <textarea name="about[content_1]" class="form-control" rows="3" placeholder="Paragraf pertama..."><?= esc($aboutData['content_1'] ?? '') ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-medium">Paragraf 2</label>
                            <textarea name="about[content_2]" class="form-control" rows="3" placeholder="Paragraf kedua (opsional)..."><?= esc($aboutData['content_2'] ?? '') ?></textarea>
                        </div>
                        <hr class="my-3">
                        <h6 class="small fw-semibold text-body-tertiary mb-2"><?= lucide_icon('award', 'me-1', 14) ?>Akreditasi</h6>
                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label small fw-medium">Nilai</label>
                                <input type="text" name="about[accreditation]" class="form-control" value="<?= esc($aboutData['accreditation'] ?? '') ?>" placeholder="Mis. A">
                            </div>
                            <div class="col-md-8">
                                <label class="form-label small fw-medium">Label</label>
                                <input type="text" name="about[accreditation_label]" class="form-control" value="<?= esc($aboutData['accreditation_label'] ?? '') ?>" placeholder="Mis. Standar Nasional">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-medium">Sorotan <span class="text-body-tertiary fw-normal">(satu per baris)</span></label>
                            <textarea name="about[highlights_text]" class="form-control" rows="4" placeholder="Akreditasi A&#10;Kurikulum Merdeka&#10;Lulusan 100% Terserap"><?php
                                $hlItems = $aboutData['highlights'] ?? [];
                                if (is_array($hlItems)) {
                                    $lines = [];
                                    foreach ($hlItems as $hl) {
                                        $lines[] = is_array($hl) ? ($hl['text'] ?? '') : $hl;
                                    }
                                    echo implode("\n", $lines);
                                }
                            ?></textarea>
                            <div class="form-text small">Setiap baris menjadi item centang di bagian tentang</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-header bg-transparent border-bottom-0 py-3 d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#collapseFooter" role="button">
                    <h6 class="mb-0"><?= lucide_icon('info', 'me-2 text-primary', 16) ?>Footer</h6>
                    <?= lucide_icon('chevron-down', 'text-body-tertiary collapse-icon', 14) ?>
                </div>
                <div class="collapse" id="collapseFooter">
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label small fw-medium">Deskripsi</label>
                            <textarea name="footer_description" class="form-control" rows="2" placeholder="Tentang sekolah, visi, atau pengantar singkat untuk footer..."><?= esc($settings['footer_description'] ?? '') ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-medium">Teks Copyright</label>
                            <div class="input-group">
                                <span class="input-group-text bg-body-tertiary border-end-0"><?= lucide_icon('copyright', 'text-body-tertiary', 16) ?></span>
                                <input type="text" name="footer_copyright" class="form-control border-start-0 ps-0" value="<?= esc($settings['footer_copyright'] ?? '') ?>" placeholder="Mis. 2026 SMP Negeri 1 Jakarta. Hak Cipta Dilindungi.">
                            </div>
                        </div>
                        <hr class="my-3">
                        <?php $footerServices = !empty($settings['footer_services']) ? json_decode($settings['footer_services'], true) : []; ?>
                        <div class="mb-3">
                            <label class="form-label small fw-medium">Program</label>
                            <div class="form-text small text-body-tertiary mb-2">Tautan kolom "Program" di footer (maks. 10 item)</div>
                            <div id="footerServicesList">
                                <?php if (!empty($footerServices)): ?>
                                <?php foreach ($footerServices as $idx => $svc): ?>
                                <div class="row g-2 mb-2 align-items-center footer-service-item">
                                    <div class="col">
                                        <input type="text" name="footer_services[<?= $idx ?>][label]" class="form-control form-control-sm" value="<?= esc($svc['label'] ?? '') ?>" placeholder="Label (mis. Sains & Teknologi)" maxlength="50">
                                    </div>
                                    <div class="col">
                                        <input type="text" name="footer_services[<?= $idx ?>][url]" class="form-control form-control-sm" value="<?= esc($svc['url'] ?? '') ?>" placeholder="URL (mis. #programs)">
                                    </div>
                                    <div class="col-auto">
                                        <button type="button" class="btn btn-outline-danger btn-sm remove-footer-service" title="Hapus" style="width:32px;height:32px"><?= lucide_icon('x', '', 14) ?></button>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm mt-1 add-footer-service" data-max="10"><?= lucide_icon('plus', 'me-1', 14) ?>Tambah Program</button>
                        </div>
                        <hr class="my-3">
                        <?php $footerLinks = !empty($settings['footer_links']) ? json_decode($settings['footer_links'], true) : []; ?>
                        <div class="mb-0">
                            <label class="form-label small fw-medium">Layanan</label>
                            <div class="form-text small text-body-tertiary mb-2">Tautan kolom "Layanan" di footer (maks. 10 item)</div>
                            <div id="footerLinksList">
                                <?php if (!empty($footerLinks)): ?>
                                <?php foreach ($footerLinks as $idx => $lnk): ?>
                                <div class="row g-2 mb-2 align-items-center footer-link-item">
                                    <div class="col">
                                        <input type="text" name="footer_links[<?= $idx ?>][label]" class="form-control form-control-sm" value="<?= esc($lnk['label'] ?? '') ?>" placeholder="Label (mis. SPMB Online)" maxlength="50">
                                    </div>
                                    <div class="col">
                                        <input type="text" name="footer_links[<?= $idx ?>][url]" class="form-control form-control-sm" value="<?= esc($lnk['url'] ?? '') ?>" placeholder="URL (mis. https://spmb.sekolah.sch.id)">
                                    </div>
                                    <div class="col-auto">
                                        <button type="button" class="btn btn-outline-danger btn-sm remove-footer-link" title="Hapus" style="width:32px;height:32px"><?= lucide_icon('x', '', 14) ?></button>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm mt-1 add-footer-link" data-max="10"><?= lucide_icon('plus', 'me-1', 14) ?>Tambah Layanan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex gap-2 pt-4 border-top mt-4">
        <button type="submit" class="btn btn-primary px-4"><?= lucide_icon('save', 'me-1', 16) ?>Simpan Pengaturan</button>
        <a href="/admin/dashboard" class="btn btn-outline-secondary px-4"><?= lucide_icon('x', 'me-1', 16) ?>Batal</a>
    </div>
</form>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Pemilih theme: pindahkan highlight ke kartu yang diklik (radio di dalam label
// ikut ter-check otomatis). Tanpa ini pilihan terlihat tidak berubah.
document.querySelectorAll('#themePicker .theme-card').forEach(function(card) {
    card.addEventListener('click', function() {
        var radio = card.querySelector('.theme-radio');
        if (radio) radio.checked = true;
        document.querySelectorAll('#themePicker .theme-card').forEach(function(c) {
            c.classList.remove('selected');
        });
        card.classList.add('selected');
    });
});

document.querySelectorAll('.card-header[data-bs-toggle="collapse"]').forEach(function(header) {
    header.addEventListener('click', function() {
        var target = document.querySelector(this.getAttribute('data-bs-target'));
        if (!target) return;
        // Pakai API Collapse bawaan bila ada (CoreUI/Bootstrap), agar tidak
        // double-toggle dengan handler manual di bawah.
        var CollapseApi = (window.coreui && window.coreui.Collapse) || (window.bootstrap && window.bootstrap.Collapse);
        if (CollapseApi) {
            var inst = CollapseApi.getOrCreateInstance(target);
            inst.toggle();
            return;
        }
        var expanded = target.classList.contains('show');
        target.classList.toggle('show');
        this.classList.toggle('collapsed');
        this.setAttribute('aria-expanded', expanded ? 'false' : 'true');
    });
});

function addFooterItem(listId, itemClass, namePrefix) {
    var list = document.getElementById(listId);
    var count = list.querySelectorAll('.' + itemClass).length;
    var max = 10;
    if (count >= max) return;
    var html = '<div class="row g-2 mb-2 align-items-center ' + itemClass + '">'
        + '<div class="col"><input type="text" name="' + namePrefix + '[' + count + '][label]" class="form-control form-control-sm" placeholder="Label" maxlength="50"></div>'
        + '<div class="col"><input type="text" name="' + namePrefix + '[' + count + '][url]" class="form-control form-control-sm" placeholder="URL"></div>'
        + '<div class="col-auto"><button type="button" class="btn btn-outline-danger btn-sm remove-' + itemClass + '" title="Hapus" style="width:32px;height:32px"><?= lucide_icon('x', '', 14) ?></button></div>'
        + '</div>';
    list.insertAdjacentHTML('beforeend', html);
}

function bindRemoveBtns(itemClass, listId, namePrefix) {
    document.addEventListener('click', function(e) {
        var btn = e.target.closest('.remove-' + itemClass);
        if (!btn) return;
        btn.closest('.' + itemClass).remove();
        var list = document.getElementById(listId);
        var items = list.querySelectorAll('.' + itemClass);
        items.forEach(function(row, i) {
            row.querySelector('[name]').name = namePrefix + '[' + i + '][label]';
            row.querySelectorAll('input')[1].name = namePrefix + '[' + i + '][url]';
        });
    });
}

document.querySelector('.add-footer-service').addEventListener('click', function() {
    addFooterItem('footerServicesList', 'footer-service-item', 'footer_services');
});
document.querySelector('.add-footer-link').addEventListener('click', function() {
    addFooterItem('footerLinksList', 'footer-link-item', 'footer_links');
});
bindRemoveBtns('footer-service-item', 'footerServicesList', 'footer_services');
bindRemoveBtns('footer-link-item', 'footerLinksList', 'footer_links');

// Tahapan SPMB: tambah/hapus baris + penomoran ulang + preset per jenjang
function renumberSpmbSteps() {
    var list = document.getElementById('spmbStepsList');
    if (!list) return;
    list.querySelectorAll('.spmb-step-item').forEach(function(row, i) {
        var num = row.querySelector('.spmb-step-num');
        if (num) num.textContent = i + 1;
        var inputs = row.querySelectorAll('input');
        if (inputs[0]) inputs[0].name = 'spmb_steps[' + i + '][title]';
        if (inputs[1]) inputs[1].name = 'spmb_steps[' + i + '][desc]';
    });
}
var addSpmbBtn = document.getElementById('addSpmbStep');
if (addSpmbBtn) addSpmbBtn.addEventListener('click', function() {
    var list = document.getElementById('spmbStepsList');
    if (!list) return;
    if (list.querySelectorAll('.spmb-step-item').length >= 12) return;
    var html = '<div class="row g-2 mb-2 align-items-center spmb-step-item">'
        + '<div class="col-auto"><span class="badge bg-body-tertiary text-body-tertiary spmb-step-num"></span></div>'
        + '<div class="col-md-3"><input type="text" name="spmb_steps[0][title]" class="form-control form-control-sm" placeholder="Judul langkah" maxlength="60"></div>'
        + '<div class="col"><input type="text" name="spmb_steps[0][desc]" class="form-control form-control-sm" placeholder="Keterangan singkat" maxlength="120"></div>'
        + '<div class="col-auto"><button type="button" class="btn btn-outline-danger btn-sm remove-spmb-step" title="Hapus" style="width:32px;height:32px"><?= lucide_icon('x', '', 14) ?></button></div>'
        + '</div>';
    list.insertAdjacentHTML('beforeend', html);
    renumberSpmbSteps();
});
document.addEventListener('click', function(e) {
    var btn = e.target.closest('.remove-spmb-step');
    if (!btn) return;
    btn.closest('.spmb-step-item').remove();
    renumberSpmbSteps();
});
var spmbPresets = <?= json_encode(spmb_step_presets(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>;
document.querySelectorAll('.spmb-preset').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var rows = spmbPresets[btn.getAttribute('data-preset')] || [];
        var list = document.getElementById('spmbStepsList');
        if (!list || !rows.length) return;
        if (!confirm('Ganti daftar tahapan dengan preset ' + btn.getAttribute('data-preset') + '? Isian saat ini akan tertimpa.')) return;
        list.innerHTML = '';
        rows.forEach(function(r) {
            var div = document.createElement('div');
            div.className = 'row g-2 mb-2 align-items-center spmb-step-item';
            div.innerHTML = '<div class="col-auto"><span class="badge bg-body-tertiary text-body-tertiary spmb-step-num"></span></div>'
                + '<div class="col-md-3"><input type="text" class="form-control form-control-sm" placeholder="Judul langkah" maxlength="60"></div>'
                + '<div class="col"><input type="text" class="form-control form-control-sm" placeholder="Keterangan singkat" maxlength="120"></div>'
                + '<div class="col-auto"><button type="button" class="btn btn-outline-danger btn-sm remove-spmb-step" title="Hapus" style="width:32px;height:32px"><?= lucide_icon('x', '', 14) ?></button></div>';
            var inputs = div.querySelectorAll('input');
            inputs[0].value = r.title || '';
            inputs[1].value = r.desc || '';
            list.appendChild(div);
        });
        renumberSpmbSteps();
    });
});
</script>
<?= $this->endSection() ?>
