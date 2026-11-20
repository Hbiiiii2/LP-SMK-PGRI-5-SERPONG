<?= $this->include(theme_view('layouts/header')) ?>
<?php
$heroStats = is_array($hero_stats ?? null) ? $hero_stats : [];
$counterItems = is_array($counter_stats ?? null) ? $counter_stats : [];
if (empty($counterItems)) {
    $counterItems = [
        ['number' => '1240', 'suffix' => '+', 'label' => 'Siswa aktif'],
        ['number' => '86', 'suffix' => '', 'label' => 'Pendidik & tendik'],
        ['number' => '47', 'suffix' => '', 'label' => 'Prestasi setahun'],
        ['number' => '96', 'suffix' => '%', 'label' => 'Tembus PTN'],
    ];
}
$principalData = is_array($principal ?? null) ? $principal : [];
$aboutData = is_array($about ?? null) ? $about : [];
function u_img($v, $fallback = '') {
    if (empty($v)) return $fallback;
    if (preg_match('#^https?://#', $v) || str_starts_with($v, '/') || str_starts_with($v, 'data:')) return $v;
    return base_url(ltrim($v, '/'));
}
$previewPrograms = array_slice($programs ?? [], 0, 3);
$previewEvents = array_slice($events ?? [], 0, 6);
$previewNews = array_slice($news ?? [], 0, 4);
$schoolImg = u_img($aboutData['image'] ?? '', '');
$featNews = $previewNews[0] ?? null;
$listNews = array_slice($previewNews, 1, 3);
$quote = ($testimonials ?? [])[0] ?? null;
$highlights = $aboutData['highlights'] ?? [];
$previewHighlights = array_slice(is_array($highlights) ? $highlights : [], 0, 6);
$anchorMap = [
    '#profile' => 'profile', '#programs' => 'program', '#extracurriculars' => 'extracurriculars',
    '#teachers' => 'teachers', '#achievements' => 'achievements', '#testimonials' => 'testimonials',
    '#news' => 'news', '#events' => 'agenda', '#gallery' => 'gallery', '#faq' => 'faq',
    '#downloads' => 'downloads', '#contact' => 'contact', '#ppdb' => '#spmb', '#spmb' => '#spmb',
    '#beranda' => '#beranda', '#profil' => 'profile', '#kontak' => 'contact', '#agenda' => 'agenda',
    '#berita' => 'news', '#galeri' => 'gallery',
    'profil' => 'profile', 'pengajar' => 'teachers', 'prestasi' => 'achievements',
    'ekstrakurikuler' => 'extracurriculars', 'testimoni' => 'testimonials', 'galeri' => 'gallery',
    'kontak' => 'contact', 'beranda' => 'home',
];
$mapUrl = function ($u) use ($anchorMap) {
    $u = trim((string) $u);
    if ($u === '' || $u === '#') return base_url('#spmb');
    if (isset($anchorMap[$u])) {
        $t = $anchorMap[$u];
        return str_starts_with($t, '#') ? base_url($t) : base_url($t);
    }
    if (str_starts_with($u, '#')) return base_url('/' . $u);
    if (preg_match('#^https?://#', $u)) return $u;
    return base_url(ltrim($u, '/'));
};
$__pName = $principalData['name'] ?? 'Kepala Sekolah';
$__pRole = $principalData['role'] ?? $principalData['role_title'] ?? 'KEPALA SEKOLAH';
$__pQuote = $principalData['quote'] ?? $principalData['sambutan'] ?? $principalData['welcome_message'] ?? '';
$__pInitials = '';
foreach (preg_split('/\s+/', trim($__pName)) as $__w) {
    if ($__w !== '' && str_starts_with($__w, '(') === false) $__pInitials .= mb_strtoupper(mb_substr($__w, 0, 1, 'UTF-8'));
    if (mb_strlen($__pInitials, 'UTF-8') >= 2) break;
}
$__pInitials = $__pInitials !== '' ? $__pInitials : 'KS';
?>
<main id="konten">

<!-- ============ HERO ============ -->
<section class="hero" id="beranda">
<div class="wrap hero-grid">
<div>
<span class="eyebrow rv"><span class="tick"><?= esc($hero_badge ?: 'SPMB') ?></span><?= esc($site_tagline ?: $site_name) ?></span>
<h1 class="rv"><?= ($hero_title ?? '') !== '' ? esc($hero_title) : 'Sekolah yang mengenal setiap anak dengan <span class="mark">nama.</span>' ?></h1>
<p class="hero-lead rv"><?= esc($hero_subtitle ?: 'Informasi profil, program, prestasi, dan layanan ' . $site_name) ?></p>
<div class="hero-cta rv">
<?php if (!empty($hero_btn_primary_text)): ?>
<a class="btn btn-blue" href="<?= esc($mapUrl($hero_btn_primary_url ?: '#spmb')) ?>"><?= esc($hero_btn_primary_text) ?> <svg><use href="#i-arrow"/></svg></a>
<?php else: ?>
<a class="btn btn-blue" href="<?= base_url('#spmb') ?>">Daftar SPMB <svg><use href="#i-arrow"/></svg></a>
<?php endif; ?>
<?php if (!empty($hero_btn_secondary_text)): ?>
<a class="btn btn-ghost" href="<?= esc($mapUrl($hero_btn_secondary_url ?: 'profile')) ?>"><?= esc($hero_btn_secondary_text) ?></a>
<?php else: ?>
<a class="btn btn-ghost" href="<?= base_url('profile') ?>">Kenali Sekolah</a>
<?php endif; ?>
</div>
<?php
// Checklist hero diambil dari sorotan Tentang Kami (Admin → Settings → About),
// dilengkapi otomatis dari akreditasi & jam layanan bila sorotan kurang dari 3.
$heroChecks = [];
foreach (array_slice($previewHighlights, 0, 3) as $hl) {
    $t = trim(is_array($hl) ? ($hl['text'] ?? '') : (string) $hl);
    if ($t !== '' && !in_array($t, $heroChecks, true)) $heroChecks[] = $t;
}
foreach (['Akreditasi ' . ($aboutData['accreditation'] ?? ''), $aboutData['accreditation_label'] ?? '', $contact_hours ?? ''] as $fb) {
    if (count($heroChecks) >= 3) break;
    $fb = trim((string) $fb);
    if ($fb !== '' && $fb !== 'Akreditasi ' && !in_array($fb, $heroChecks, true)) $heroChecks[] = $fb;
}
?>
<div class="hero-checks rv">
<?php foreach ($heroChecks as $chk): ?>
<span><svg><use href="#i-check"/></svg><?= esc(mb_strimwidth($chk, 0, 44, '…')) ?></span>
<?php endforeach; ?>
</div>
</div>
<div class="papan rv">
<div class="papan-main">
<?php if ($schoolImg): ?>
<div class="art"><img src="<?= esc($schoolImg) ?>" alt="Foto <?= esc($site_name) ?>" loading="eager" style="width:100%;height:auto;object-fit:cover;aspect-ratio:4/3;display:block"></div>
<?php else: ?>
<div class="art">
<svg viewBox="0 0 560 400" role="img" aria-label="Ilustrasi halaman depan <?= esc($site_name) ?>">
<rect width="560" height="400" fill="#E4ECFA"/>
<circle cx="468" cy="66" r="30" fill="#FFC60B"/>
<circle cx="468" cy="66" r="43" fill="none" stroke="#FFC60B" stroke-width="2" opacity=".5"/>
<g fill="#fff" opacity=".9"><ellipse cx="120" cy="70" rx="42" ry="14"/><ellipse cx="162" cy="60" rx="30" ry="12"/><ellipse cx="300" cy="48" rx="36" ry="12"/></g>
<g stroke="#1550B8" stroke-width="2" fill="none" opacity=".7"><path d="M60 110q7-10 14 0M80 104q7-10 14 0"/></g>
<rect x="0" y="318" width="560" height="82" fill="#D3DFF5"/>
<rect x="0" y="314" width="560" height="4" fill="#0C2F73"/>
<g><rect x="40" y="252" width="10" height="66" fill="#0C2F73"/><circle cx="45" cy="230" r="32" fill="#1550B8"/><circle cx="28" cy="244" r="18" fill="#1550B8" opacity=".6"/></g>
<g><rect x="510" y="258" width="10" height="60" fill="#0C2F73"/><circle cx="515" cy="238" r="28" fill="#1550B8"/><circle cx="531" cy="250" r="15" fill="#1550B8" opacity=".6"/></g>
<g>
<rect x="150" y="130" width="260" height="188" fill="#fff" stroke="#0C2F73" stroke-width="3"/>
<rect x="136" y="114" width="288" height="20" fill="#0C2F73"/>
<path d="M164 114 L280 66 L396 114" fill="#fff" stroke="#0C2F73" stroke-width="3"/>
<rect x="276" y="38" width="7" height="32" fill="#0C2F73"/>
<path d="M283 38 L313 45 L283 53 Z" fill="#FFC60B"/>
<g fill="#E4ECFA" stroke="#1550B8" stroke-width="2.5">
<rect x="168" y="148" width="36" height="46"/><rect x="216" y="148" width="36" height="46"/><rect x="308" y="148" width="36" height="46"/><rect x="356" y="148" width="36" height="46"/>
<rect x="168" y="208" width="36" height="46"/><rect x="216" y="208" width="36" height="46"/><rect x="308" y="208" width="36" height="46"/><rect x="356" y="208" width="36" height="46"/>
</g>
<rect x="256" y="248" width="48" height="70" fill="#1550B8"/>
<rect x="262" y="256" width="15" height="24" fill="#fff" opacity=".9"/><rect x="283" y="256" width="15" height="24" fill="#FFC60B"/>
<rect x="240" y="318" width="80" height="7" fill="#0C2F73"/>
</g>
<g fill="#0C2F73"><circle cx="112" cy="282" r="8"/><rect x="105" y="291" width="14" height="28" rx="5"/><circle cx="452" cy="286" r="8"/><rect x="445" y="295" width="14" height="24" rx="5"/></g>
<rect x="196" y="272" width="52" height="18" fill="#FFC60B"/><rect x="312" y="272" width="52" height="18" fill="#FFC60B"/>
<path d="M60 356h440" stroke="#1550B8" stroke-width="2"/>
</svg>
</div>
<?php endif; ?>
<div class="papan-cap"><span>Halaman depan · <?= esc(mb_strimwidth($contact_address ?: $site_name, 0, 56, '…')) ?></span></div>
</div>
</div>
</div>
<div class="hero-strip"><div class="wrap">
<div class="strip-it"><svg><use href="#i-doc"/></svg><div><b>Akreditasi <?= esc($aboutData['accreditation'] ?? 'A') ?> · <?= esc($aboutData['accreditation_label'] ?? 'Kurikulum Merdeka') ?></b><span>Terdaftar resmi<?= !empty($contact_phone) ? ' · ' . esc($contact_phone) : '' ?></span></div></div>
<div class="strip-it"><svg><use href="#i-cal"/></svg><div><b>Jam layanan</b><span><?= esc($contact_hours ?: 'Lihat halaman kontak untuk jam layanan') ?></span></div></div>
<div class="strip-it"><svg><use href="#i-phone"/></svg><div><b><?= esc($contact_phone ?: $contact_email ?: $site_name) ?></b><span><?= esc($contact_email ?: $contact_address ?: '') ?></span></div></div>
</div></div>
</section>

<!-- ============ TICKER ============ -->
<?php
$tickerItems = [];
$announce = null;
foreach (($latest_news ?? []) as $ln) {
    if (stripos($ln['category'] ?? '', 'pengumuman') !== false) { $announce = $ln; break; }
}
$announce = $announce ?? ($latest_news[0] ?? null);
if (!empty($announce['title'])) $tickerItems[] = $announce['title'];
foreach (array_slice($previewNews, 0, 2) as $tn) {
    if (!empty($tn['title']) && !in_array($tn['title'], $tickerItems, true)) $tickerItems[] = $tn['title'];
}
foreach (array_slice($previewEvents, 0, 2) as $te) {
    if (!empty($te['title']) && !in_array($te['title'], $tickerItems, true)) $tickerItems[] = $te['title'];
}
$ach0 = ($achievements ?? [])[0] ?? null;
if (!empty($ach0['achievement'])) {
    $achT = 'Prestasi: ' . $ach0['achievement'] . (!empty($ach0['student_name']) ? ' — ' . $ach0['student_name'] : '');
    if (!in_array($achT, $tickerItems, true)) $tickerItems[] = $achT;
}
if (empty($tickerItems)) $tickerItems[] = $site_tagline ?: $site_name;
?>
<div class="ticker" aria-label="Pengumuman berjalan">
<div class="ticker-track" id="tickerTrack">
<?php foreach ($tickerItems as $tt): ?><span><i>◆</i> <?= esc($tt) ?></span><?php endforeach; ?>
</div>
</div>

<!-- ============ STATS ============ -->
<div class="stat-band" aria-label="Data sekolah">
<div class="wrap">
<?php $statTags = ['Siswa aktif', 'Pendidik & tendik', 'Prestasi setahun', 'Tembus PTN']; ?>
<?php foreach (array_slice($counterItems, 0, 4) as $i => $c): ?>
<div class="stat-card rv"><div class="tag"><?= esc($c['label'] ?? $statTags[$i] ?? 'Data') ?></div><div class="val"><?= esc($c['number'] ?? '0') ?><small><?= esc($c['suffix'] ?? '') ?></small></div><div class="dsc"><?= esc($c['label'] ?? '') ?></div></div>
<?php endforeach; ?>
</div>
</div>

<!-- ============ SAMBUTAN ============ -->
<section class="sec" id="profil">
<div class="wrap">
<div class="s-head rv"><span class="s-chip">01</span><span class="s-kick">Profil sekolah</span><span class="s-rule"></span><a class="s-more" href="<?= base_url('profile') ?>">Sejarah lengkap →</a></div>
<h2 class="s-title rv">Sambutan <em>Kepala Sekolah</em></h2>
<p class="s-lead rv"><?= esc($aboutData['content_title'] ?? 'Disiplin yang hangat, target yang jelas, dan pendampingan yang sabar.') ?></p>
<div class="sambut-grid">
<div class="kepsek rv">
<?php if (!empty($principalData['photo'])): ?>
<div><img src="<?= esc(u_img($principalData['photo'])) ?>" alt="<?= esc($__pName) ?>" loading="lazy" style="width:100%;border-radius:8px"></div>
<?php else: ?>
<div class="k-inisial"><?= esc($__pInitials) ?></div>
<?php endif; ?>
<?php if ($__pQuote): ?><p class="k-quote">“<?= esc($__pQuote) ?>”</p><?php endif; ?>
<div class="k-name"><strong><?= esc($__pName) ?></strong><span><?= esc(strtoupper($__pRole)) ?></span></div>
</div>
<div class="sambut-body rv">
<?php if (!empty($aboutData['content_1'])): ?><p><?= esc($aboutData['content_1']) ?></p><?php endif; ?>
<?php if (!empty($aboutData['content_2'])): ?><p><?= esc($aboutData['content_2']) ?></p><?php endif; ?>
<?php if (empty($aboutData['content_1']) && empty($aboutData['content_2'])): ?>
<p>Prestasi akademik dibangun berdampingan dengan pembiasaan karakter: apel pagi yang tertib, guru yang hafal nama setiap siswanya, serta target belajar yang transparan bagi orang tua.</p>
<?php endif; ?>
<?php if (!empty($aboutData['visi'])): ?><p><strong>Visi:</strong> <?= esc($aboutData['visi']) ?></p><?php endif; ?>
<div class="sign"><span class="sign-name"><?= esc($__pName) ?></span><span class="sign-role"><?= esc($__pRole) ?></span></div>
<div style="margin-top:20px"><a class="btn btn-blue" href="<?= base_url('profile') ?>">Sejarah &amp; Visi Misi <svg><use href="#i-arrow"/></svg></a></div>
</div>
</div>
</div>
</section>

<!-- ============ PROGRAM ============ -->
<?php if (!empty($previewPrograms)): ?>
<section class="sec mist" id="program">
<div class="wrap">
<div class="s-head rv"><span class="s-chip sun">02</span><span class="s-kick">Akademik</span><span class="s-rule"></span><a class="s-more" href="<?= base_url('program') ?>">Semua program →</a></div>
<h2 class="s-title rv">Tiga peminatan, <em>satu standar yang sama.</em></h2>
<p class="s-lead rv">Pilihan peminatan dan pembinaan di <?= esc($site_name) ?>.</p>
<div class="prog-grid">
<?php $progIcons = ['flask', 'users', 'book']; ?>
<?php foreach ($previewPrograms as $i => $p): ?>
<a class="prog-card rv" href="<?= program_link($p, 'program') ?>"><span class="prog-no">PEMINATAN / 0<?= $i + 1 ?></span><span class="prog-ico<?= $i === 1 ? ' sun' : '' ?>"><svg><use href="#i-<?= $progIcons[$i % 3] ?>"/></svg></span><h3><?= esc($p['title']) ?></h3><p><?= esc(mb_strimwidth($p['description'] ?? '', 0, 140, '…')) ?></p><div class="prog-foot"><span class="kuota">Lihat detail</span><span>Selengkapnya <svg><use href="#i-arrow"/></svg></span></div></a>
<?php endforeach; ?>
</div>
</div>
</section>
<?php endif; ?>

<!-- ============ FASILITAS ============ -->
<?php if (!empty($previewHighlights)): ?>
<section class="sec" id="fasilitas">
<div class="wrap">
<div class="s-head rv"><span class="s-chip">03</span><span class="s-kick">Fasilitas</span><span class="s-rule"></span></div>
<h2 class="s-title rv">Lingkungan yang membuat anak <em>betah belajar.</em></h2>
<div class="fas-grid">
<?php $fasIcons = ['book', 'flask', 'trophy', 'users', 'globe', 'heart']; ?>
<?php foreach ($previewHighlights as $i => $hl): $t = is_array($hl) ? ($hl['text'] ?? '') : $hl; ?>
<div class="fas rv"><div class="fas-top"><span class="fas-no">F.0<?= $i + 1 ?></span><span class="fas-ico"><svg><use href="#i-<?= $fasIcons[$i % 6] ?>"/></svg></span></div><h3><?= esc(mb_strimwidth($t, 0, 50, '…')) ?></h3><p><?= esc($t) ?></p></div>
<?php endforeach; ?>
</div>
</div>
</section>
<?php endif; ?>

<!-- ============ AGENDA ============ -->
<?php if (!empty($previewEvents)): ?>
<section class="sec mist" id="agenda">
<div class="wrap">
<div class="s-head rv"><span class="s-chip sun">04</span><span class="s-kick">Agenda</span><span class="s-rule"></span><a class="s-more" href="<?= base_url('agenda') ?>">Kalender penuh →</a></div>
<h2 class="s-title rv">Agenda <em>terdekat</em></h2>
<p class="s-lead rv">Jadwal resmi <?= esc($site_name) ?>.</p>
<div class="agenda rv">
<?php foreach ($previewEvents as $ev): $ts = strtotime($ev['event_date'] ?? ''); ?>
<div class="ag-row" data-cat="akademik"><div class="ag-date"><div class="d"><?= $ts ? date('d', $ts) : '–' ?></div><div class="m"><?= $ts ? date("M 'y", $ts) : '' ?></div></div><div class="ag-info"><span class="cat">Agenda<?= !empty($ev['event_time']) ? ' · ' . esc($ev['event_time']) : '' ?></span><h3><?= esc($ev['title']) ?></h3><p><?= esc(mb_strimwidth($ev['description'] ?? '', 0, 110, '…')) ?></p></div><span class="ag-loc"><svg><use href="#i-pin"/></svg><?= esc($ev['location'] ?: 'Sekolah') ?></span></div>
<?php endforeach; ?>
</div>
</div>
</section>
<?php endif; ?>

<!-- ============ BERITA ============ -->
<?php if (!empty($previewNews)): ?>
<section class="sec" id="berita">
<div class="wrap">
<div class="s-head rv"><span class="s-chip">05</span><span class="s-kick">Berita &amp; prestasi</span><span class="s-rule"></span><a class="s-more" href="<?= base_url('news') ?>">Semua berita →</a></div>
<h2 class="s-title rv">Kabar terbaru <em>dari kampus.</em></h2>
<div class="news-grid">
<?php if ($featNews): $fi = u_img($featNews['image'] ?? '', ''); $fcatH = trim(explode(',', $featNews['category'] ?? 'Berita')[0]); ?>
<a class="feat rv" href="<?= base_url('news/' . esc($featNews['slug'])) ?>">
<?php if ($fi): ?><div class="art"><img src="<?= esc($fi) ?>" alt="<?= esc($featNews['title']) ?>" loading="lazy" style="width:100%"></div>
<?php else: ?><div class="art" aria-hidden="true" style="display:flex;align-items:center;justify-content:center;background:#0C2F73;color:#fff;font-size:64px;font-weight:800;min-height:220px"><?= esc(mb_strtoupper(mb_substr(trim($featNews['title']), 0, 1, 'UTF-8'), 'UTF-8')) ?></div><?php endif; ?>
<div class="feat-body">
<div class="catline"><span class="cat"><?= esc($fcatH) ?></span><span class="dt"><?= date('j M Y', strtotime($featNews['published_at'] ?? $featNews['created_at'])) ?></span></div>
<h3 class="feat-title"><?= esc($featNews['title']) ?></h3>
<p class="feat-ex"><?= esc($featNews['excerpt'] ?? mb_strimwidth(strip_tags($featNews['content'] ?? ''), 0, 160, '…')) ?></p>
</div>
</a>
<?php endif; ?>
<div class="news-side rv">
<?php foreach ($listNews as $n): $ni = u_img($n['image'] ?? '', ''); ?>
<a class="nl-item" href="<?= base_url('news/' . esc($n['slug'])) ?>">
<?php if ($ni): ?><span class="nl-thumb"><img src="<?= esc($ni) ?>" alt="<?= esc($n['title']) ?>" loading="lazy" style="width:100%;height:100%;object-fit:cover"></span>
<?php else: ?><span class="nl-thumb" aria-hidden="true" style="display:flex;align-items:center;justify-content:center;background:#E4ECFA;color:#0C2F73;font-weight:800;font-size:28px"><?= esc(mb_strtoupper(mb_substr(trim($n['title']), 0, 1, 'UTF-8'), 'UTF-8')) ?></span><?php endif; ?>
<span><span class="catline"><span class="cat"><?= esc(explode(',', $n['category'] ?? 'Berita')[0]) ?></span><span class="dt"><?= date('j M Y', strtotime($n['published_at'] ?? $n['created_at'])) ?></span></span><span class="nl-title"><?= esc($n['title']) ?></span></span></a>
<?php endforeach; ?>
</div>
</div>
</div>
</section>
<?php endif; ?>

<!-- ============ QUOTE ============ -->
<section class="quote">
<div class="wrap rv">
<?php if ($quote): ?>
<blockquote>“<?= esc($quote['quote']) ?>”</blockquote>
<cite><?php if (!empty($quote['photo'])): ?><img class="quote-ava" src="<?= esc($quote['photo']) ?>" alt="" loading="lazy"><?php endif; ?><strong><?= esc($quote['name']) ?></strong> · <?= esc($quote['role'] ?? 'Testimoni') ?></cite>
<?php else: ?>
<blockquote>“Pendidikan terbaik lahir dari keteladanan, kedisiplinan, dan kasih sayang.”</blockquote>
<cite><strong><?= esc($site_name) ?></strong></cite>
<?php endif; ?>
</div>
</section>

<!-- ============ SPMB ============ -->
<section class="ppdb" id="spmb">
<?php $spmbSteps = (!empty($spmb_steps) && is_array($spmb_steps)) ? array_values($spmb_steps) : default_spmb_steps(); ?>
<div class="wrap">
<div class="rv">
<span class="eyebrow"><span class="tick"><?= esc($hero_badge ?: 'SPMB') ?></span>Seleksi Penerimaan Murid Baru</span>
<h2><?= count($spmbSteps) ?> Langkah Mudah <em>Mendaftar.</em></h2>
<p class="lead">Proses pendaftaran bertahap dengan progress bar yang memandu calon siswa hingga cetak formulir PDF.</p>
<div class="ppdb-cta">
<a class="btn btn-sun" href="<?= base_url('contact') ?>">Daftar Sekarang <svg><use href="#i-arrow"/></svg></a>
<a class="btn btn-line-w" href="<?= base_url('contact') ?>">Jadwal Open House</a>
</div>
<p class="ppdb-note"><i></i><?= esc(strtoupper($contact_phone ?: $contact_email ?: 'HUBUNGI KAMI')) ?></p>
</div>
<div>
<div class="sp-flow rv" aria-label="Alur pendaftaran <?= count($spmbSteps) ?> langkah">
<div class="sp-track" aria-hidden="true"><?php for ($i = 0; $i < count($spmbSteps); $i++): ?><span></span><?php endfor; ?></div>
<div class="sp-cap"><span>Langkah 1–<?= count($spmbSteps) ?></span><span>Final: cetak formulir PDF</span></div>
</div>
<ol class="steps rv">
<?php foreach ($spmbSteps as $i => $st): ?>
<li><span class="n"><?= str_pad($i + 1, 2, '0', STR_PAD_LEFT) ?></span><h3><?= esc($st['title']) ?></h3><p><?= esc($st['desc']) ?></p></li>
<?php endforeach; ?>
</ol>
</div>
</div>
</section>

<!-- ============ KONTAK ============ -->
<section class="sec" id="kontak">
<div class="wrap">
<div class="s-head rv"><span class="s-chip">06</span><span class="s-kick">Kontak</span><span class="s-rule"></span><a class="s-more" href="<?= base_url('contact') ?>">Halaman kontak →</a></div>
<h2 class="s-title rv">Datang, lihat, dan <em>bertanya langsung.</em></h2>
<div class="kontak-grid">
<div class="k-card rv">
<div class="k-item"><span class="k-ico"><svg><use href="#i-pin"/></svg></span><div><h3>Alamat</h3><p><?= esc($contact_address ?: 'Lihat halaman kontak') ?></p></div></div>
<div class="k-item"><span class="k-ico sun"><svg><use href="#i-phone"/></svg></span><div><h3>Telepon / WA</h3><p><?= esc($contact_phone ?: '-') ?></p></div></div>
<div class="k-item"><span class="k-ico"><svg><use href="#i-mail"/></svg></span><div><h3>Surel</h3><p><?= esc($contact_email ?: '-') ?></p></div></div>
<div class="k-item"><span class="k-ico sun"><svg><use href="#i-clock"/></svg></span><div><h3>Jam layanan</h3><p><?= esc($contact_hours ?: '-') ?></p></div></div>
</div>
<div class="form rv">
<h3>Butuh informasi cepat?</h3>
<p>Kirim pesan melalui halaman kontak — balasan maks. 1×24 jam pada hari kerja.</p>
<a class="btn btn-sun" href="<?= base_url('contact') ?>" style="width:100%;justify-content:center">Buka Halaman Kontak <svg><use href="#i-arrow"/></svg></a>
</div>
</div>
</div>
</section>

</main>

<?= $this->include(theme_view('layouts/footer')) ?>
