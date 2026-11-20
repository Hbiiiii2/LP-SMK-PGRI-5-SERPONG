<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= esc($page_title ?? $site_name) ?></title>
  <meta name="description" content="<?= esc($meta_description ?? $site_description ?? $site_tagline) ?>">
  <meta name="author" content="<?= esc($site_name) ?>">
  <link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64'%3E%3Crect width='64' height='64' rx='14' fill='%231550B8'/%3E%3Ccircle cx='45' cy='19' r='9' fill='%23FFC60B'/%3E%3Cpath d='M13 43h38M13 50h24' stroke='white' stroke-width='4.5' stroke-linecap='round'/%3E%3C/svg%3E">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Lora:ital,wght@0,500;0,600;1,500;1,600&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="<?= theme_asset('css/style.min.css') ?>">
  <link rel="canonical" href="<?= esc(current_url()) ?>">
  <meta name="theme-color" content="#0C2F73">
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
<symbol id="i-search" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="6.5"/><path d="M16 16l4.5 4.5"/></symbol>
<symbol id="i-link" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 14a5 5 0 0 0 7 0l3-3a5 5 0 0 0-7-7l-1.5 1.5"/><path d="M14 10a5 5 0 0 0-7 0l-3 3a5 5 0 0 0 7 7l1.5-1.5"/></symbol>
<symbol id="i-book" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v15H6.5A2.5 2.5 0 0 0 4 20.5z"/><path d="M4 20.5V5.5M20 18v3H6.5"/><path d="M9 8h7M9 11.5h5"/></symbol>
<symbol id="i-flask" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9.5 3h5M10.5 3v5.2L5.4 17a2.4 2.4 0 0 0 2.1 3.5h9a2.4 2.4 0 0 0 2.1-3.5L13.5 8.2V3"/><path d="M7.8 14.5h8.4"/></symbol>
<symbol id="i-trophy" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M8 4h8v5a4 4 0 0 1-8 0z"/><path d="M8 5H4.5a.5.5 0 0 0-.5.5C4 8 5.5 10 8 10.5M16 5h3.5a.5.5 0 0 1 .5.5C20 8 18.5 10 16 10.5"/><path d="M12 13v4M8.5 20h7M10 17h4"/></symbol>
<symbol id="i-users" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="8" r="3.2"/><path d="M3.5 19a5.5 5.5 0 0 1 11 0"/><path d="M16 5.4a3.2 3.2 0 0 1 0 5.6M17.5 14a5.5 5.5 0 0 1 3 5"/></symbol>
<symbol id="i-globe" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="8.5"/><path d="M3.5 12h17M12 3.5c-4.5 4.7-4.5 12.3 0 17M12 3.5c4.5 4.7 4.5 12.3 0 17"/></symbol>
<symbol id="i-heart" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20s-7.5-4.6-7.5-10A4.3 4.3 0 0 1 12 7a4.3 4.3 0 0 1 7.5 3c0 5.4-7.5 10-7.5 10z"/></symbol>
<symbol id="i-cal" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><rect x="4" y="5" width="16" height="15" rx="2"/><path d="M4 10h16M8 3v4M16 3v4"/></symbol>
<symbol id="i-doc" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M6 3h8l4 4v14H6z"/><path d="M14 3v4h4M9 12h6M9 16h6"/></symbol>
</defs>
</svg>

<div class="util">
<div class="wrap">
<div class="util-left"><span class="npsn">NPSN 20109999</span><span><?= esc(strtoupper($site_tagline ?: 'AKREDITASI A · KURIKULUM MERDEKA')) ?></span></div>
<div class="util-right">
<?php if (!empty($contact_phone)): ?><a class="u-link" href="tel:<?= esc(preg_replace('/[^+\d]/', '', $contact_phone)) ?>"><svg><use href="#i-phone"/></svg><?= esc($contact_phone) ?></a><?php endif; ?>
<?php if (!empty($contact_email)): ?><a class="u-link" href="mailto:<?= esc($contact_email) ?>"><svg><use href="#i-mail"/></svg><?= esc($contact_email) ?></a><?php endif; ?>
<a class="ppdb-pill" href="<?= base_url('#spmb') ?>"><span class="pulse"></span>SPMB Dibuka</a>
</div>
</div>
</div>

<header class="masthead" id="masthead">
<div class="wrap">
<?php
$__words = preg_split('/\s+/', trim($site_name ?? 'SMA Cakrawala Nusantara'));
$__initials = '';
foreach ($__words as $__w) { if ($__w !== '') $__initials .= mb_strtoupper(mb_substr($__w, 0, 1, 'UTF-8')); }
$__initials = mb_substr($__initials, 0, 2, 'UTF-8') ?: 'CN';
?>
<a class="brand" href="<?= base_url('/') ?>" aria-label="<?= esc($site_name) ?> — Beranda">
<span class="brand-mark"><b><?= esc($__initials) ?></b></span>
<span><span class="brand-name"><?= esc(theme_brand_text() !== '' ? theme_brand_text() : $site_name) ?></span><span class="brand-sub"><?= esc($site_tagline ?: 'Profil Sekolah') ?></span></span>
</a>
<nav class="nav" aria-label="Navigasi utama">
<a href="<?= base_url('/') ?>" data-p="home">Beranda</a>
<a href="<?= base_url('profile') ?>" data-p="profile">Profil</a>
<a href="<?= base_url('program') ?>" data-p="program">Program</a>
<a href="<?= base_url('news') ?>" data-p="news">Berita</a>
<a href="<?= base_url('contact') ?>" data-p="contact">Kontak</a>
</nav>
<button class="menu-btn" id="menuBtn"><svg><use href="#i-menu"/></svg>Semua halaman</button>
<a class="btn btn-sun" href="<?= base_url('#spmb') ?>">SPMB <svg><use href="#i-arrow"/></svg></a>
<button class="burger" id="burger" aria-label="Buka menu"><svg><use href="#i-menu"/></svg></button>
</div>
</header>

<?php $extraNav = get_extra_nav_items(); ?>
<div class="overlay" id="menuOverlay" aria-hidden="true" role="dialog" aria-label="Jelajah halaman">
<div class="wrap"><div class="ov-sheet">
<div class="wrap ov-top"><span class="ov-label"><b><?= 13 + count($extraNav) ?></b>Jelajah halaman</span><button class="ov-close" id="menuClose"><svg><use href="#i-close"/></svg>Tutup</button></div>
<nav class="wrap ov-grid" aria-label="Jelajah halaman">
<a href="<?= base_url('/') ?>" data-p="home"><span class="ov-n">01</span><span><span class="ov-t">Beranda</span><span class="ov-d">Ringkasan sekolah</span></span><svg class="ov-a"><use href="#i-arrow"/></svg></a>
<a href="<?= base_url('profile') ?>" data-p="profile"><span class="ov-n">02</span><span><span class="ov-t">Profil</span><span class="ov-d">Sejarah, visi &amp; budaya</span></span><svg class="ov-a"><use href="#i-arrow"/></svg></a>
<a href="<?= base_url('program') ?>" data-p="program"><span class="ov-n">03</span><span><span class="ov-t">Program</span><span class="ov-d">MIPA, IPS, Bahasa</span></span><svg class="ov-a"><use href="#i-arrow"/></svg></a>
<a href="<?= base_url('news') ?>" data-p="news"><span class="ov-n">04</span><span><span class="ov-t">Berita</span><span class="ov-d">Kabar &amp; prestasi</span></span><svg class="ov-a"><use href="#i-arrow"/></svg></a>
<a href="<?= base_url('contact') ?>" data-p="contact"><span class="ov-n">05</span><span><span class="ov-t">Kontak</span><span class="ov-d">Alamat &amp; pesan</span></span><svg class="ov-a"><use href="#i-arrow"/></svg></a>
<a href="<?= base_url('teachers') ?>" data-p="teachers"><span class="ov-n">06</span><span><span class="ov-t">Pengajar</span><span class="ov-d">Guru &amp; tendik</span></span><svg class="ov-a"><use href="#i-arrow"/></svg></a>
<a href="<?= base_url('achievements') ?>" data-p="achievements"><span class="ov-n">07</span><span><span class="ov-t">Prestasi</span><span class="ov-d">Piala &amp; medali</span></span><svg class="ov-a"><use href="#i-arrow"/></svg></a>
<a href="<?= base_url('extracurriculars') ?>" data-p="extracurriculars"><span class="ov-n">08</span><span><span class="ov-t">Ekstrakurikuler</span><span class="ov-d">Kegiatan siswa</span></span><svg class="ov-a"><use href="#i-arrow"/></svg></a>
<a href="<?= base_url('testimonials') ?>" data-p="testimonials"><span class="ov-n">09</span><span><span class="ov-t">Testimoni</span><span class="ov-d">Kata alumni &amp; ortu</span></span><svg class="ov-a"><use href="#i-arrow"/></svg></a>
<a href="<?= base_url('gallery') ?>" data-p="gallery"><span class="ov-n">10</span><span><span class="ov-t">Galeri</span><span class="ov-d">Suasana kampus</span></span><svg class="ov-a"><use href="#i-arrow"/></svg></a>
<a href="<?= base_url('agenda') ?>" data-p="agenda"><span class="ov-n">11</span><span><span class="ov-t">Agenda</span><span class="ov-d">Kalender kegiatan</span></span><svg class="ov-a"><use href="#i-arrow"/></svg></a>
<a href="<?= base_url('faq') ?>" data-p="faq"><span class="ov-n">12</span><span><span class="ov-t">FAQ</span><span class="ov-d">Tanya jawab</span></span><svg class="ov-a"><use href="#i-arrow"/></svg></a>
<a href="<?= base_url('downloads') ?>" data-p="downloads"><span class="ov-n">13</span><span><span class="ov-t">Download</span><span class="ov-d">Brosur &amp; formulir</span></span><svg class="ov-a"><use href="#i-arrow"/></svg></a>
<?php foreach ($extraNav as $i => $extra): ?>
<a href="<?= esc($extra['url']) ?>"<?= $extra['target'] === '_blank' ? ' target="_blank" rel="noopener"' : '' ?><?= $extra['active'] ? ' class="active"' : '' ?>><span class="ov-n"><?= str_pad((string) (14 + $i), 2, '0', STR_PAD_LEFT) ?></span><span><span class="ov-t"><?= esc($extra['title']) ?></span></span><svg class="ov-a"><use href="#i-arrow"/></svg></a>
<?php endforeach; ?>
</nav>
<div class="wrap"><div class="ov-foot"><span>Butuh bantuan? <?= esc($contact_phone ?? '') ?> · <?= esc($contact_email ?? '') ?></span><a class="btn btn-sun sm" href="<?= base_url('#spmb') ?>">SPMB</a></div></div>
</div></div>
</div>
