<?php
$settings       = $settings ?? [];
$siteName       = trim((string) ($site_name ?? ($settings['site_name'] ?? 'SMK PGRI 5 Serpong'))) ?: 'SMK PGRI 5 Serpong';
$siteTagline    = trim((string) ($site_tagline ?? ($settings['site_tagline'] ?? 'Unggul dalam Prestasi, Luhur dalam Budi Pekerti')));
$rawTitle       = trim((string) ($page_title ?? ($title ?? '')));
$pageTitle      = $rawTitle !== ''
    ? (stripos($rawTitle, $siteName) !== false ? $rawTitle : $rawTitle . ' — ' . $siteName)
    : $siteName . ($siteTagline !== '' ? ' — ' . $siteTagline : '');
$metaDesc       = trim((string) ($meta_description ?? ($site_description ?? ($settings['meta_description'] ?? $siteTagline))));
$logoUrl        = theme_asset('img/logo-smk-pgri-5-serpong.png');
$ogImage        = $og_image ?? (function_exists('theme_og') ? theme_og() : theme_asset('img/og-cover.png'));
$ogType         = $og_type ?? 'website';
$currentPath    = trim((string) uri_string(), '/');

$isActive = static function (string $path) use ($currentPath): bool {
    $clean = trim($path, '/');
    if ($clean === '') {
        return $currentPath === '';
    }
    return $currentPath === $clean || str_starts_with($currentPath, $clean . '/');
};

$primaryNav = [
    ['label' => 'Beranda', 'url' => '/', 'active' => $isActive('')],
    ['label' => 'Profil', 'url' => '/profile', 'active' => $isActive('profile')],
    ['label' => 'Program Keahlian', 'url' => '/program', 'active' => $isActive('program')],
    ['label' => 'Berita', 'url' => '/news', 'active' => $isActive('news')],
    ['label' => 'Prestasi', 'url' => '/achievements', 'active' => $isActive('achievements')],
    ['label' => 'Kontak', 'url' => '/contact', 'active' => $isActive('contact')],
];

$moreNav = [
    ['label' => 'Tenaga Pendidik', 'url' => '/teachers', 'active' => $isActive('teachers')],
    ['label' => 'Ekstrakurikuler', 'url' => '/extracurriculars', 'active' => $isActive('extracurriculars')],
    ['label' => 'Agenda Sekolah', 'url' => '/agenda', 'active' => $isActive('agenda')],
    ['label' => 'Galeri Kegiatan', 'url' => '/gallery', 'active' => $isActive('gallery')],
    ['label' => 'Testimoni Alumni', 'url' => '/testimonials', 'active' => $isActive('testimonials')],
    ['label' => 'Pusat Unduhan', 'url' => '/downloads', 'active' => $isActive('downloads')],
    ['label' => 'Tanya Jawab (FAQ)', 'url' => '/faq', 'active' => $isActive('faq')],
];

$extraNavItems = function_exists('get_extra_nav_items') ? get_extra_nav_items() : [];
foreach ($extraNavItems as $extraItem) {
    $moreNav[] = [
        'label'  => (string) ($extraItem['title'] ?? ''),
        'url'    => (string) ($extraItem['url'] ?? '/'),
        'target' => (string) ($extraItem['target'] ?? '_self'),
        'active' => (bool) ($extraItem['active'] ?? false),
    ];
}

$moreActive = false;
foreach ($moreNav as $item) {
    if (! empty($item['active'])) {
        $moreActive = true;
        break;
    }
}

$contactPhone   = trim((string) ($contact_phone ?? ($settings['contact_phone'] ?? '')));
$contactEmail   = trim((string) ($contact_email ?? ($settings['contact_email'] ?? '')));
$contactAddress = trim((string) ($contact_address ?? ($settings['contact_address'] ?? 'Serpong, Tangerang Selatan')));
$spmbLabelRaw   = trim((string) ($settings['spmb_badge_text'] ?? 'Info SPMB'));
$spmbLabel      = strtr($spmbLabelRaw !== '' ? $spmbLabelRaw : 'Info SPMB', ['Â·' => '·', 'â€”' => '—', 'â€“' => '–']);

$cssAsset = is_file(FCPATH . 'themes/' . active_theme() . '/css/style.min.css')
    ? 'css/style.min.css'
    : 'css/style.css';
$tokensVer = @filemtime(FCPATH . 'themes/' . active_theme() . '/css/tokens.css') ?: '1';
$styleVer  = @filemtime(FCPATH . 'themes/' . active_theme() . '/' . $cssAsset) ?: '1';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= esc($pageTitle) ?></title>
<meta name="description" content="<?= esc($metaDesc) ?>">
<meta name="theme-color" content="#1b61c9">
<link rel="canonical" href="<?= esc(current_url()) ?>">
<link rel="icon" type="image/png" href="<?= esc($logoUrl) ?>">
<link rel="apple-touch-icon" href="<?= esc($logoUrl) ?>">
<meta property="og:type" content="<?= esc($ogType) ?>">
<meta property="og:title" content="<?= esc($pageTitle) ?>">
<meta property="og:description" content="<?= esc($metaDesc) ?>">
<meta property="og:url" content="<?= esc(current_url()) ?>">
<meta property="og:image" content="<?= esc($ogImage) ?>">
<meta name="twitter:card" content="summary_large_image">
<style>
#sitePreloader{position:fixed;inset:0;z-index:99999;background:linear-gradient(180deg,#ffffff 0%,#f8fafc 100%);display:flex;flex-direction:column;align-items:center;justify-content:center;gap:18px;padding:24px;text-align:center;transition:opacity .38s ease,visibility .38s ease}
#sitePreloader.is-done{opacity:0;visibility:hidden;pointer-events:none}
#sitePreloader.is-skip{display:none!important}
.sp-ring-wrap{position:relative;width:76px;height:76px;display:flex;align-items:center;justify-content:center}
.sp-ring{position:absolute;inset:0;border-radius:50%;border:3px solid #e0e7ff;border-top-color:#1b61c9;animation:spSpin .9s linear infinite}
.sp-crest{width:46px;height:46px;border-radius:12px;background:#eff6ff;color:#1b61c9;display:flex;align-items:center;justify-content:center;font-family:system-ui,-apple-system,sans-serif;font-weight:800;font-size:15px;letter-spacing:-.02em;box-shadow:0 4px 14px rgba(27,97,201,.14)}
.sp-title{margin:0;font-family:system-ui,-apple-system,sans-serif;font-size:17px;font-weight:700;color:#181d26;letter-spacing:-.01em}
.sp-sub{margin:-10px 0 0;font-family:system-ui,-apple-system,sans-serif;font-size:13px;color:#64748b}
.sp-bar{width:180px;height:4px;border-radius:999px;background:#e2e8f0;overflow:hidden;position:relative}
.sp-bar-fill{position:absolute;inset:0 auto 0 0;width:45%;border-radius:999px;background:linear-gradient(90deg,#1b61c9,#2d7ff9);animation:spSlide 1.2s ease-in-out infinite}
#navProgressBar{position:fixed;top:0;left:0;height:3px;width:0;z-index:99998;background:linear-gradient(90deg,#1b61c9,#2d7ff9);box-shadow:0 0 8px rgba(27,97,201,.45);opacity:0;pointer-events:none;transition:width .35s ease,opacity .25s ease}
#navProgressBar.is-active{opacity:1;width:78%}
.img-skel-host{position:relative;background:linear-gradient(90deg,#f1f5f9 25%,#e2e8f0 50%,#f1f5f9 75%);background-size:200% 100%;animation:spShimmer 1.35s ease-in-out infinite}
img.is-img-loading{opacity:0;background:linear-gradient(90deg,#f1f5f9 25%,#e2e8f0 50%,#f1f5f9 75%);background-size:200% 100%;animation:spShimmer 1.35s ease-in-out infinite}
img.is-img-loaded{opacity:1;transition:opacity .35s ease}
@keyframes spSpin{to{transform:rotate(360deg)}}
@keyframes spSlide{0%{transform:translateX(-110%)}50%{transform:translateX(85%)}100%{transform:translateX(260%)}}
@keyframes spShimmer{0%{background-position:200% 0}100%{background-position:-200% 0}}
@media (prefers-reduced-motion:reduce){.sp-ring,.sp-bar-fill,.img-skel-host,img.is-img-loading{animation:none!important}}
</style>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Inter:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= esc(theme_asset('css/tokens.css')) ?>?v=<?= esc((string) $tokensVer) ?>">
<link rel="stylesheet" href="<?= esc(theme_asset($cssAsset)) ?>?v=<?= esc((string) $styleVer) ?>">
<?php if (! empty($ldjson)): ?>
<?= $ldjson . "\n" ?>
<?php else: ?>
<script type="application/ld+json">
<?= json_encode([
    '@context'    => 'https://schema.org',
    '@type'       => 'EducationalOrganization',
    'name'        => $siteName,
    'description' => $siteTagline,
    'url'         => base_url('/'),
    'logo'        => $logoUrl,
    'address'     => [
        '@type'          => 'PostalAddress',
        'streetAddress'  => $contactAddress,
        'addressCountry' => 'ID',
    ],
    'email'       => $contactEmail !== '' ? $contactEmail : null,
    'telephone'   => $contactPhone !== '' ? $contactPhone : null,
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>
</script>
<?php endif; ?>
</head>
<body<?= ! empty($data_page) ? ' data-page="' . esc($data_page) . '"' : '' ?>>
<div id="navProgressBar" aria-hidden="true"></div>
<div id="sitePreloader" role="status" aria-live="polite" aria-label="Memuat halaman <?= esc($siteName) ?>">
  <div class="sp-ring-wrap" aria-hidden="true">
    <div class="sp-ring"></div>
    <div class="sp-crest">PGRI 5</div>
  </div>
  <p class="sp-title"><?= esc($siteName) ?></p>
  <p class="sp-sub">Memuat halaman, mohon tunggu...</p>
  <div class="sp-bar" aria-hidden="true"><div class="sp-bar-fill"></div></div>
</div>
<script>
(function(){
  var el=document.getElementById('sitePreloader');
  if(!el)return;
  var key='smkpgri5_preloader_seen';
  var seen=false;
  try{seen=sessionStorage.getItem(key)==='1';}catch(e){}
  if(seen){el.classList.add('is-skip');return;}
  try{sessionStorage.setItem(key,'1');}catch(e){}
  var start=Date.now(),closed=false;
  function hide(){
    if(closed)return;
    closed=true;
    var wait=Math.max(0,420-(Date.now()-start));
    setTimeout(function(){
      el.classList.add('is-done');
      setTimeout(function(){if(el&&el.parentNode)el.parentNode.removeChild(el);},420);
    },wait);
  }
  if(document.readyState==='complete'){hide();}
  else{
    window.addEventListener('load',hide,{once:true});
    setTimeout(hide,3200);
  }
})();
</script>

<a class="skip-link" href="#konten">Lewati ke konten utama</a>

<div class="util">
  <div class="wrap util-inner">
    <div class="util-left">
      <span class="util-meta">
        <?= lucide_icon('school', '', 14) ?>
        <span><?= esc($siteName) ?></span>
      </span>
      <?php if ($contactEmail !== ''): ?>
        <span class="util-sep" aria-hidden="true">/</span>
        <a class="util-meta util-link" href="mailto:<?= esc($contactEmail) ?>">
          <?= lucide_icon('mail', '', 13) ?>
          <span><?= esc($contactEmail) ?></span>
        </a>
      <?php endif; ?>
      <?php if ($contactPhone !== ''): ?>
        <span class="util-sep" aria-hidden="true">/</span>
        <a class="util-meta util-link tabular-nums" href="tel:<?= esc(preg_replace('/[^0-9+]/', '', $contactPhone)) ?>">
          <?= lucide_icon('phone', '', 13) ?>
          <span><?= esc($contactPhone) ?></span>
        </a>
      <?php endif; ?>
    </div>
    <div class="util-right">
      <a class="util-link" href="<?= esc(base_url('downloads')) ?>">Unduhan</a>
      <span class="util-sep" aria-hidden="true">/</span>
      <a class="util-link" href="<?= esc(base_url('faq')) ?>">FAQ</a>
      <a class="spmb-flag" href="<?= esc(base_url('spmb')) ?>">
        <span class="spmb-pulse" aria-hidden="true"></span>
        <span><?= esc($spmbLabel) ?></span>
      </a>
    </div>
  </div>
</div>

<header class="masthead" id="masthead">
  <div class="wrap masthead-inner">
    <a class="brand" href="<?= esc(base_url('/')) ?>" aria-label="Beranda <?= esc($siteName) ?>">
      <img class="brand-logo" src="<?= esc($logoUrl) ?>" alt="Logo <?= esc($siteName) ?>" width="44" height="44">
      <span class="brand-text">
        <span class="brand-name"><?= esc($siteName) ?></span>
        <?php if ($siteTagline !== ''): ?>
          <span class="brand-sub"><?= esc($siteTagline) ?></span>
        <?php endif; ?>
      </span>
    </a>

    <nav class="nav" aria-label="Navigasi utama">
      <?php foreach ($primaryNav as $item): ?>
        <a class="nav-link<?= $item['active'] ? ' active' : '' ?>" href="<?= esc(base_url(ltrim($item['url'], '/'))) ?>"<?= $item['active'] ? ' aria-current="page"' : '' ?>>
          <?= esc($item['label']) ?>
        </a>
      <?php endforeach; ?>

      <div class="nav-dropdown" id="navMoreWrap">
        <button type="button" class="nav-link nav-drop-btn<?= $moreActive ? ' active' : '' ?>" id="navMoreBtn" aria-expanded="false" aria-haspopup="true">
          <span>Lainnya</span>
          <?= lucide_icon('chevron-down', '', 14) ?>
        </button>
        <div class="nav-drop-menu" role="menu">
          <?php foreach ($moreNav as $sub): ?>
            <?php
            $href = str_starts_with($sub['url'], 'http')
                ? $sub['url']
                : base_url(ltrim($sub['url'], '/'));
            $target = $sub['target'] ?? '_self';
            ?>
            <a class="nav-drop-item<?= ! empty($sub['active']) ? ' active' : '' ?>"
               href="<?= esc($href) ?>"
               target="<?= esc($target) ?>"
               <?= $target === '_blank' ? 'rel="noopener noreferrer"' : '' ?>
               role="menuitem">
              <span><?= esc($sub['label']) ?></span>
              <?= lucide_icon('arrow-right', '', 14) ?>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    </nav>

    <div class="head-actions">
      <a class="btn btn-primary btn-sm head-cta" href="<?= esc(base_url('spmb')) ?>">
        <?= lucide_icon('graduation-cap', '', 15) ?>
        <span>Daftar SPMB</span>
      </a>
      <button type="button" class="menu-btn" id="menuBtn" aria-controls="menuOverlay" aria-expanded="false" aria-label="Buka direktori menu">
        <?= lucide_icon('menu', '', 17) ?>
        <span class="menu-btn-label">Menu</span>
        <span class="menu-btn-desktop-label">Direktori</span>
      </button>
    </div>
  </div>
</header>

<div class="dir-overlay" id="menuOverlay" role="dialog" aria-modal="true" aria-labelledby="dirTitle" aria-hidden="true">
  <div class="dir-panel">
    <div class="dir-head">
      <div class="dir-brand">
        <img src="<?= esc($logoUrl) ?>" alt="Logo <?= esc($siteName) ?>" width="38" height="38">
        <div>
          <div class="dir-brand-title" id="dirTitle">Direktori <?= esc($siteName) ?></div>
          <div class="dir-brand-sub">Akses cepat seluruh halaman resmi sekolah</div>
        </div>
      </div>
      <button type="button" class="dir-close" id="closeOverlay" aria-label="Tutup direktori menu">
        <?= lucide_icon('x', '', 18) ?>
      </button>
    </div>

    <div class="dir-body">
      <div>
        <div class="dir-group-title">01 / Profil &amp; Akademik</div>
        <div class="dir-links">
          <a class="dir-link<?= $isActive('') ? ' active' : '' ?>" href="<?= esc(base_url('/')) ?>">Beranda <?= lucide_icon('chevron-right', '', 15) ?></a>
          <a class="dir-link<?= $isActive('profile') ? ' active' : '' ?>" href="<?= esc(base_url('profile')) ?>">Profil Sekolah <?= lucide_icon('chevron-right', '', 15) ?></a>
          <a class="dir-link<?= $isActive('program') ? ' active' : '' ?>" href="<?= esc(base_url('program')) ?>">Program Keahlian <?= lucide_icon('chevron-right', '', 15) ?></a>
          <a class="dir-link<?= $isActive('teachers') ? ' active' : '' ?>" href="<?= esc(base_url('teachers')) ?>">Tenaga Pendidik <?= lucide_icon('chevron-right', '', 15) ?></a>
          <a class="dir-link<?= $isActive('agenda') ? ' active' : '' ?>" href="<?= esc(base_url('agenda')) ?>">Agenda Sekolah <?= lucide_icon('chevron-right', '', 15) ?></a>
        </div>
      </div>

      <div>
        <div class="dir-group-title">02 / Kesiswaan &amp; Galeri</div>
        <div class="dir-links">
          <a class="dir-link<?= $isActive('achievements') ? ' active' : '' ?>" href="<?= esc(base_url('achievements')) ?>">Prestasi Siswa <?= lucide_icon('chevron-right', '', 15) ?></a>
          <a class="dir-link<?= $isActive('extracurriculars') ? ' active' : '' ?>" href="<?= esc(base_url('extracurriculars')) ?>">Ekstrakurikuler <?= lucide_icon('chevron-right', '', 15) ?></a>
          <a class="dir-link<?= $isActive('gallery') ? ' active' : '' ?>" href="<?= esc(base_url('gallery')) ?>">Galeri Kegiatan <?= lucide_icon('chevron-right', '', 15) ?></a>
          <a class="dir-link<?= $isActive('testimonials') ? ' active' : '' ?>" href="<?= esc(base_url('testimonials')) ?>">Testimoni Alumni <?= lucide_icon('chevron-right', '', 15) ?></a>
        </div>
      </div>

      <div>
        <div class="dir-group-title">03 / Layanan &amp; Informasi</div>
        <div class="dir-links">
          <a class="dir-link<?= $isActive('spmb') ? ' active' : '' ?>" href="<?= esc(base_url('spmb')) ?>">Pendaftaran SPMB Online <?= lucide_icon('chevron-right', '', 15) ?></a>
          <a class="dir-link<?= $isActive('news') ? ' active' : '' ?>" href="<?= esc(base_url('news')) ?>">Berita &amp; Pengumuman <?= lucide_icon('chevron-right', '', 15) ?></a>
          <a class="dir-link<?= $isActive('downloads') ? ' active' : '' ?>" href="<?= esc(base_url('downloads')) ?>">Pusat Unduhan <?= lucide_icon('chevron-right', '', 15) ?></a>
          <a class="dir-link<?= $isActive('faq') ? ' active' : '' ?>" href="<?= esc(base_url('faq')) ?>">Tanya Jawab (FAQ) <?= lucide_icon('chevron-right', '', 15) ?></a>
          <a class="dir-link<?= $isActive('contact') ? ' active' : '' ?>" href="<?= esc(base_url('contact')) ?>">Kontak &amp; Lokasi <?= lucide_icon('chevron-right', '', 15) ?></a>
          <?php foreach ($extraNavItems as $extra): ?>
            <a class="dir-link<?= ! empty($extra['active']) ? ' active' : '' ?>"
               href="<?= esc($extra['url']) ?>"
               target="<?= esc($extra['target'] ?? '_self') ?>"
               <?= ($extra['target'] ?? '_self') === '_blank' ? 'rel="noopener noreferrer"' : '' ?>>
              <?= esc($extra['title']) ?> <?= lucide_icon('external-link', '', 14) ?>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <div class="dir-foot">
      <div><span class="badge badge-blue"><span class="badge-dot" aria-hidden="true"></span> <?= esc($spmbLabel) ?></span></div>
      <div class="sec-actions">
        <a class="btn btn-primary btn-sm" href="<?= esc(base_url('spmb')) ?>">Daftar SPMB Online</a>
        <a class="btn btn-outline-secondary btn-sm" href="<?= esc(base_url('contact')) ?>">Hubungi Sekolah</a>
      </div>
    </div>
  </div>
</div>

<main id="konten">
