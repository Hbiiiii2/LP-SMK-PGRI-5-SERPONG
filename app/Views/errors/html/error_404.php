<?php
helper(['theme', 'menu', 'url', 'lucide']);

$settings = [];
try {
    $rows = (new \App\Models\SettingModel())->findAll();
    foreach ($rows as $row) {
        $settings[$row['key']] = $row['value'];
    }
} catch (\Throwable $e) {
    $settings = [];
}

$theme       = function_exists('active_theme') ? active_theme() : 'smkpgri5';
$hasThemeTpl = is_file(FCPATH . 'themes/' . $theme . '/css/tokens.css')
    && is_file(APPPATH . 'Views/themes/' . $theme . '/layouts/header.php')
    && is_file(APPPATH . 'Views/themes/' . $theme . '/layouts/footer.php');

$siteName = trim((string) ($settings['site_name'] ?? 'SMK PGRI 5 Serpong')) ?: 'SMK PGRI 5 Serpong';
$logoUrl  = is_file(FCPATH . 'themes/' . $theme . '/img/logo-smk-pgri-5-serpong.png')
    ? theme_asset('img/logo-smk-pgri-5-serpong.png')
    : base_url('themes/smkpgri5/img/logo-smk-pgri-5-serpong.png');

$shortcuts = [
    ['title' => 'Beranda', 'desc' => 'Ringkasan profil sekolah, berita terkini, dan informasi SPMB.', 'url' => base_url('/'), 'icon' => 'home'],
    ['title' => 'Profil Sekolah', 'desc' => 'Sejarah, visi-misi, identitas resmi, dan sambutan pimpinan.', 'url' => base_url('profile'), 'icon' => 'school'],
    ['title' => 'Program Keahlian', 'desc' => 'Kompetensi keahlian, kurikulum, dan fasilitas belajar.', 'url' => base_url('program'), 'icon' => 'graduation-cap'],
    ['title' => 'Berita & Pengumuman', 'desc' => 'Liputan kegiatan terbaru dan pengumuman resmi sekolah.', 'url' => base_url('news'), 'icon' => 'newspaper'],
    ['title' => 'Pusat Unduhan', 'desc' => 'Dokumen resmi, brosur SPMB, dan formulir pendaftaran.', 'url' => base_url('downloads'), 'icon' => 'download'],
    ['title' => 'Kontak & Lokasi', 'desc' => 'Alamat kampus, jam layanan, dan formulir pesan resmi.', 'url' => base_url('contact'), 'icon' => 'mail'],
];
?>
<?php if ($hasThemeTpl): ?>
<?= view(theme_view('layouts/header'), [
    'title'            => '404 — Halaman Tidak Ditemukan',
    'meta_description' => 'Halaman yang Anda cari tidak ditemukan di situs resmi ' . $siteName . '.',
    'settings'         => $settings,
]) ?>

<section class="page-hero">
  <div class="wrap">
    <nav class="breadcrumbs" aria-label="Breadcrumb">
      <a href="<?= esc(base_url('/')) ?>">Beranda</a>
      <span class="crumb-sep" aria-hidden="true">/</span>
      <span aria-current="page">404</span>
    </nav>
    <div class="err-code">
      <span class="badge badge-red"><span class="badge-dot" aria-hidden="true"></span><span>Kesalahan 404</span></span>
    </div>
    <h1 class="page-hero-title">Halaman tidak ditemukan.</h1>
    <p class="page-hero-lead">Alamat URL mungkin salah ketik, halaman telah dipindahkan, atau tautan sudah diperbarui.</p>
    <div class="hero-cta" style="margin-top: var(--space-5); margin-bottom: 0;">
      <a class="btn btn-primary" href="<?= esc(base_url('/')) ?>">
        <?= lucide_icon('home', '', 16) ?>
        <span>Kembali ke Beranda</span>
      </a>
      <a class="btn btn-secondary" href="<?= esc(base_url('faq')) ?>">
        <?= lucide_icon('help-circle', '', 16) ?>
        <span>Lihat FAQ</span>
      </a>
    </div>
  </div>
</section>

<section class="sec sec-white">
  <div class="wrap">
    <div class="sec-head">
      <div class="sec-head-text">
        <div class="sec-index">Direktori Tautan</div>
        <h2 class="sec-title">Halaman Resmi <?= esc($siteName) ?></h2>
      </div>
    </div>
    <div class="programs-matrix">
      <?php foreach ($shortcuts as $idx => $sc): ?>
        <a class="prog-matrix-card" href="<?= esc($sc['url']) ?>">
          <div class="prog-matrix-top">
            <span class="prog-icon-inline"><?= lucide_icon($sc['icon'], '', 19) ?></span>
            <span class="prog-code tabular-nums"><?= esc(str_pad((string) ($idx + 1), 2, '0', STR_PAD_LEFT)) ?></span>
          </div>
          <h3 class="info-card-title"><?= esc($sc['title']) ?></h3>
          <p class="info-card-desc" style="margin-top: 0.4rem; margin-bottom: 0;"><?= esc($sc['desc']) ?></p>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?= view(theme_view('layouts/footer'), ['settings' => $settings]) ?>
<?php else: ?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>404 — Halaman Tidak Ditemukan | <?= esc($siteName) ?></title>
<meta name="robots" content="noindex">
<meta name="theme-color" content="#1b61c9">
<link rel="icon" type="image/png" href="<?= esc($logoUrl) ?>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= esc(base_url('themes/smkpgri5/css/tokens.css')) ?>">
<link rel="stylesheet" href="<?= esc(base_url('themes/smkpgri5/css/style.min.css')) ?>">
</head>
<body>
<header class="masthead">
  <div class="wrap masthead-inner">
    <a class="brand" href="<?= esc(base_url('/')) ?>">
      <img class="brand-logo" src="<?= esc($logoUrl) ?>" alt="Logo <?= esc($siteName) ?>" width="44" height="44">
      <span class="brand-text">
        <span class="brand-name"><?= esc($siteName) ?></span>
      </span>
    </a>
    <a class="btn btn-primary btn-sm" href="<?= esc(base_url('/')) ?>">Beranda</a>
  </div>
</header>
<main class="sec sec-white">
  <div class="wrap-narrow">
    <div class="card">
      <div class="err-code"><span class="badge badge-red">404 Not Found</span></div>
      <h1>Halaman tidak ditemukan.</h1>
      <p class="sec-desc" style="margin-bottom: var(--space-5);">Alamat yang Anda tuju tidak tersedia di sistem.</p>
      <a class="btn btn-primary" href="<?= esc(base_url('/')) ?>">Kembali ke Beranda</a>
    </div>
  </div>
</main>
</body>
</html>
<?php endif; ?>
