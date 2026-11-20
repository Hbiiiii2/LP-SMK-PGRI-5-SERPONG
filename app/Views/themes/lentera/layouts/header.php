<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= esc($page_title ?? $site_name) ?></title>
  <meta name="description" content="<?= esc($meta_description ?? $site_description ?? $site_tagline) ?>">
  <meta name="author" content="<?= esc($site_name) ?>">
  <link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64'%3E%3Ccircle cx='32' cy='32' r='30' fill='%2315803D'/%3E%3Cpath d='M20 42c4-10 9-15 12-15s8 5 12 15' fill='none' stroke='white' stroke-width='4' stroke-linecap='round'/%3E%3Ccircle cx='44' cy='20' r='7' fill='%23FACC15'/%3E%3C/svg%3E">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Newsreader:ital,opsz,wght@0,6..72,400;0,6..72,500;1,6..72,400;1,6..72,500&family=IBM+Plex+Mono:wght@400;500;700&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="<?= theme_asset('css/style.min.css') ?>">
  <link rel="canonical" href="<?= esc(current_url()) ?>">
  <meta name="theme-color" content="#0E2A1D">
  <meta property="og:site_name" content="<?= esc($site_name) ?>">
  <meta property="og:locale" content="id_ID">
  <meta property="og:type" content="<?= isset($og_type) ? esc($og_type) : 'website' ?>">
  <meta property="og:title" content="<?= esc($page_title ?? $site_name) ?>">
  <meta property="og:description" content="<?= esc($meta_description ?? $site_description ?? $site_tagline) ?>">
  <meta property="og:url" content="<?= esc(current_url()) ?>">
  <meta property="og:image" content="<?= isset($og_image) ? esc($og_image) : theme_og() ?>">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="<?= esc($page_title ?? $site_name) ?>">
  <meta name="twitter:description" content="<?= esc($meta_description ?? $site_description ?? $site_tagline) ?>">
  <meta name="twitter:image" content="<?= isset($og_image) ? esc($og_image) : theme_og() ?>">
  <?php if (($is_404 ?? false)): ?><meta name="robots" content="noindex"><?php endif; ?>
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "HighSchool",
    "name": "<?= esc($site_name) ?>",
    "url": "<?= base_url() ?>",
    "description": "<?= esc($site_description ?? $site_tagline) ?>",
    "logo": "<?= theme_og() ?>",
    "telephone": "<?= esc($contact_phone ?? '') ?>",
    "email": "<?= esc($contact_email ?? '') ?>",
    "address": {
      "@type": "PostalAddress",
      "streetAddress": "<?= esc($contact_address ?? '') ?>",
      "addressCountry": "ID"
    }
  }
  </script>
  <?php if (isset($ldjson)) echo $ldjson; ?>
</head>
<body data-page="<?= esc($data_page ?? '') ?>">

<a class="skip" href="#konten">Lewati ke konten</a>

<svg width="0" height="0" style="position:absolute" aria-hidden="true">
<defs>
<symbol id="i-phone" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2z"/></symbol>
<symbol id="i-mail" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3.5 7l8.5 6 8.5-6"/></symbol>
<symbol id="i-pin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s7-6.1 7-11a7 7 0 1 0-14 0c0 4.9 7 11 7 11z"/><circle cx="12" cy="10" r="2.6"/></symbol>
<symbol id="i-clock" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="8.5"/><path d="M12 7.5V12l3 2"/></symbol>
<symbol id="i-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 12h15M13 6l6 6-6 6"/></symbol>
<symbol id="i-up" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19V5M6 11l6-6 6 6"/></symbol>
<symbol id="i-menu" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 7h16M4 12h16M4 17h10"/></symbol>
<symbol id="i-close" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M6 6l12 12M18 6L6 18"/></symbol>
<symbol id="i-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M4.5 12.5l5 5 10-11"/></symbol>
<symbol id="i-book" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v15H6.5A2.5 2.5 0 0 0 4 20.5z"/><path d="M4 20.5V5.5M20 18v3H6.5"/><path d="M9 8h7M9 11.5h5"/></symbol>
<symbol id="i-flask" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9.5 3h5M10.5 3v5.2L5.4 17a2.4 2.4 0 0 0 2.1 3.5h9a2.4 2.4 0 0 0 2.1-3.5L13.5 8.2V3"/><path d="M7.8 14.5h8.4"/></symbol>
<symbol id="i-trophy" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M8 4h8v5a4 4 0 0 1-8 0z"/><path d="M8 5H4.5a.5.5 0 0 0-.5.5C4 8 5.5 10 8 10.5M16 5h3.5a.5.5 0 0 1 .5.5C20 8 18.5 10 16 10.5"/><path d="M12 13v4M8.5 20h7M10 17h4"/></symbol>
<symbol id="i-users" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="8" r="3.2"/><path d="M3.5 19a5.5 5.5 0 0 1 11 0"/><path d="M16 5.4a3.2 3.2 0 0 1 0 5.6M17.5 14a5.5 5.5 0 0 1 3 5"/></symbol>
<symbol id="i-globe" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="8.5"/><path d="M3.5 12h17M12 3.5c-4.5 4.7-4.5 12.3 0 17M12 3.5c4.5 4.7 4.5 12.3 0 17"/></symbol>
<symbol id="i-heart" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20s-7.5-4.6-7.5-10A4.3 4.3 0 0 1 12 7a4.3 4.3 0 0 1 7.5 3c0 5.4-7.5 10-7.5 10z"/></symbol>
<symbol id="i-cal" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><rect x="4" y="5" width="16" height="15" rx="2"/><path d="M4 10h16M8 3v4M16 3v4"/></symbol>
<symbol id="i-doc" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M6 3h8l4 4v14H6z"/><path d="M14 3v4h4M9 12h6M9 16h6"/></symbol>
<symbol id="i-search" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="6.5"/><path d="M16 16l4.5 4.5"/></symbol>
<symbol id="i-link" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 14a5 5 0 0 0 7 0l3-3a5 5 0 0 0-7-7l-1.5 1.5"/><path d="M14 10a5 5 0 0 0-7 0l-3 3a5 5 0 0 0 7 7l1.5-1.5"/></symbol>
</defs>
</svg>

<!-- ===== BAR ATAS : tipis, gelap, informatif ===== -->
<div class="g-top">
<div class="wrap g-top-in">
<p class="g-top-left"><svg><use href="#i-pin"/></svg><?= esc($contact_address ?: $site_tagline ?: $site_name) ?> <span class="g-top-sep">/</span> <span class="g-top-acc">Akreditasi A</span></p>
<div class="g-top-right">
<?php if (!empty($contact_phone)): ?><a href="tel:<?= esc(preg_replace('/[^+\d]/', '', $contact_phone)) ?>"><svg><use href="#i-phone"/></svg><?= esc($contact_phone) ?></a><?php endif; ?>
<?php if (!empty($contact_email)): ?><a href="mailto:<?= esc($contact_email) ?>"><svg><use href="#i-mail"/></svg><?= esc($contact_email) ?></a><?php endif; ?>
<span class="g-live"><span class="g-live-dot"></span>SPMB Dibuka</span>
</div>
</div>
</div>

<!-- ===== HEADER : lega, emblem lingkaran, aksi pil ===== -->
<header class="g-head" id="masthead">
<div class="wrap g-head-in">
<a class="g-brand" href="<?= base_url('/') ?>" aria-label="<?= esc($site_name) ?> — Beranda">
<span class="g-emblem" aria-hidden="true"><svg viewBox="0 0 48 48"><circle cx="24" cy="24" r="22" fill="#15803D"/><path d="M14 32c3.5-8 7-12 10-12s6.5 4 10 12" fill="none" stroke="#fff" stroke-width="3.2" stroke-linecap="round"/><circle cx="34" cy="14" r="5.5" fill="#FACC15"/></svg></span>
<span class="g-brand-tx"><span class="g-brand-name"><?= esc(theme_brand_text() !== '' ? theme_brand_text() : $site_name) ?></span><span class="g-brand-sub"><?= esc($site_tagline ?: 'Profil Sekolah') ?></span></span>
</a>
<nav class="nav g-nav" aria-label="Navigasi utama">
<a href="<?= base_url('/') ?>" data-p="home">Beranda</a>
<a href="<?= base_url('profile') ?>" data-p="profile">Profil</a>
<a href="<?= base_url('program') ?>" data-p="program">Program</a>
<a href="<?= base_url('news') ?>" data-p="news">Berita</a>
<a href="<?= base_url('contact') ?>" data-p="contact">Kontak</a>
</nav>
<div class="g-head-act">
<button class="g-ghost" id="menuBtn"><svg><use href="#i-menu"/></svg>Jelajah</button>
<a class="g-cta" href="<?= base_url('#spmb') ?>">SPMB<svg><use href="#i-arrow"/></svg></a>
<button class="g-burger" id="burger" aria-label="Buka menu"><svg><use href="#i-menu"/></svg></button>
</div>
</div>
</header>

<?php $extraNav = get_extra_nav_items(); ?>
<!-- ===== MENU JELAJAH : panel belah — info gelap + daftar terang ===== -->
<div class="g-menu" id="menuOverlay" aria-hidden="true" role="dialog" aria-label="Jelajah halaman">
<div class="g-menu-box" role="document">
<aside class="g-menu-side">
<p class="g-menu-kick"><?= esc($site_name) ?></p>
<p class="g-menu-big">Mau ke halaman <em>mana?</em></p>
<p class="g-menu-desc"><?= 13 + count($extraNav) ?> halaman, satu alur yang jelas — dari profil sampai formulir SPMB.</p>
<div class="g-menu-contact"><span><?= esc($contact_phone ?? '') ?></span><span><?= esc($contact_email ?? '') ?></span></div>
<a class="g-cta g-cta-light" href="<?= base_url('#spmb') ?>">SPMB<svg><use href="#i-arrow"/></svg></a>
</aside>
<div class="g-menu-main">
<div class="g-menu-top"><span><?= 13 + count($extraNav) ?> halaman</span><button class="g-menu-x" id="menuClose"><svg><use href="#i-close"/></svg>Tutup</button></div>
<nav class="ov-grid g-menu-list" aria-label="Jelajah halaman">
<a href="<?= base_url('/') ?>" data-p="home"><span class="g-mi">01</span><span><span class="g-mt">Beranda</span><span class="g-md">Ringkasan sekolah</span></span></a>
<a href="<?= base_url('profile') ?>" data-p="profile"><span class="g-mi">02</span><span><span class="g-mt">Profil</span><span class="g-md">Sejarah, visi &amp; budaya</span></span></a>
<a href="<?= base_url('program') ?>" data-p="program"><span class="g-mi">03</span><span><span class="g-mt">Program</span><span class="g-md">MIPA, IPS, Bahasa</span></span></a>
<a href="<?= base_url('news') ?>" data-p="news"><span class="g-mi">04</span><span><span class="g-mt">Berita</span><span class="g-md">Kabar &amp; prestasi</span></span></a>
<a href="<?= base_url('contact') ?>" data-p="contact"><span class="g-mi">05</span><span><span class="g-mt">Kontak</span><span class="g-md">Alamat &amp; pesan</span></span></a>
<a href="<?= base_url('teachers') ?>" data-p="teachers"><span class="g-mi">06</span><span><span class="g-mt">Pengajar</span><span class="g-md">Guru &amp; tendik</span></span></a>
<a href="<?= base_url('achievements') ?>" data-p="achievements"><span class="g-mi">07</span><span><span class="g-mt">Prestasi</span><span class="g-md">Piala &amp; medali</span></span></a>
<a href="<?= base_url('extracurriculars') ?>" data-p="extracurriculars"><span class="g-mi">08</span><span><span class="g-mt">Ekstrakurikuler</span><span class="g-md">Kegiatan siswa</span></span></a>
<a href="<?= base_url('testimonials') ?>" data-p="testimonials"><span class="g-mi">09</span><span><span class="g-mt">Testimoni</span><span class="g-md">Kata alumni &amp; ortu</span></span></a>
<a href="<?= base_url('gallery') ?>" data-p="gallery"><span class="g-mi">10</span><span><span class="g-mt">Galeri</span><span class="g-md">Suasana kampus</span></span></a>
<a href="<?= base_url('agenda') ?>" data-p="agenda"><span class="g-mi">11</span><span><span class="g-mt">Agenda</span><span class="g-md">Kalender kegiatan</span></span></a>
<a href="<?= base_url('faq') ?>" data-p="faq"><span class="g-mi">12</span><span><span class="g-mt">FAQ</span><span class="g-md">Tanya jawab</span></span></a>
<a href="<?= base_url('downloads') ?>" data-p="downloads"><span class="g-mi">13</span><span><span class="g-mt">Unduhan</span><span class="g-md">Brosur &amp; formulir</span></span></a>
<?php foreach ($extraNav as $i => $extra): ?>
<a href="<?= esc($extra['url']) ?>"<?= $extra['target'] === '_blank' ? ' target="_blank" rel="noopener"' : '' ?><?= $extra['active'] ? ' class="active"' : '' ?>><span class="g-mi"><?= str_pad((string) (14 + $i), 2, '0', STR_PAD_LEFT) ?></span><span><span class="g-mt"><?= esc($extra['title']) ?></span></span></a>
<?php endforeach; ?>
</nav>
</div>
</div>
</div>
