<?= $this->include(theme_view('layouts/header')) ?>
<?php
$heroStats = is_array($hero_stats ?? null) ? $hero_stats : [];
$counterItems = is_array($counter_stats ?? null) ? $counter_stats : [];
if (empty($counterItems)) {
    $counterItems = [
        ['number' => '1240', 'suffix' => '', 'label' => 'Siswa aktif'],
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
$previewNews = array_slice($news ?? [], 0, 3);
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
$__pRole = $principalData['role'] ?? $principalData['role_title'] ?? 'Kepala Sekolah';
$__pQuote = $principalData['quote'] ?? $principalData['sambutan'] ?? $principalData['welcome_message'] ?? '';
$__pInitials = '';
foreach (preg_split('/\s+/', trim($__pName)) as $__w) {
    if ($__w !== '' && str_starts_with($__w, '(') === false) $__pInitials .= mb_strtoupper(mb_substr($__w, 0, 1, 'UTF-8'));
    if (mb_strlen($__pInitials, 'UTF-8') >= 2) break;
}
$__pInitials = $__pInitials !== '' ? $__pInitials : 'KS';
?>
<main id="konten">

<!-- ===== HERO : tajuk raksasa + kartu pendaftaran menempel ===== -->
<section class="g-hero" id="beranda">
<div class="wrap">
<div class="g-meta rv"><span>Beranda — Tahun ajaran 2026/2027</span><span><?= esc($site_tagline ?: 'Kurikulum Merdeka') ?></span></div>
<h1 class="g-h1 rv"><?= ($hero_title ?? '') !== '' ? esc($hero_title) : 'Sekolah yang mengenal<br>setiap anak dengan <em>nama.</em>' ?></h1>
<div class="g-hero-grid">
<div class="rv">
<p class="g-lead"><?= esc($hero_subtitle ?: 'Informasi profil, program, prestasi, dan layanan ' . $site_name) ?></p>
<div class="g-hero-cta">
<?php if (!empty($hero_btn_primary_text)): ?>
<a class="g-cta g-cta-green" href="<?= esc($mapUrl($hero_btn_primary_url ?: '#spmb')) ?>"><?= esc($hero_btn_primary_text) ?><svg><use href="#i-arrow"/></svg></a>
<?php else: ?>
<a class="g-cta g-cta-green" href="<?= base_url('#spmb') ?>">Daftar SPMB<svg><use href="#i-arrow"/></svg></a>
<?php endif; ?>
<?php if (!empty($hero_btn_secondary_text)): ?>
<a class="g-ghost g-ghost-lg" href="<?= esc($mapUrl($hero_btn_secondary_url ?: 'profile')) ?>"><?= esc($hero_btn_secondary_text) ?></a>
<?php else: ?>
<a class="g-ghost g-ghost-lg" href="<?= base_url('profile') ?>">Kenali sekolah</a>
<?php endif; ?>
</div>
<ul class="g-assure">
<li><i></i>Akreditasi <?= esc($aboutData['accreditation'] ?? 'A') ?></li>
<li><i></i>Kurikulum Merdeka</li>
<li><i></i>Open house Sabtu pertama</li>
</ul>
</div>
<aside class="g-apply rv" aria-label="Ringkasan penerimaan">
<div class="g-apply-head"><span>Penerimaan 2026/2027</span></div>
<dl class="g-apply-rows">
<div><dt>Gelombang 1</dt><dd>s.d. 31 Jan 2027</dd></div>
<div><dt>Open house</dt><dd>Sabtu, 7 Feb · 08.00</dd></div>
<div><dt>Kontak</dt><dd><?= esc($contact_phone ?: $contact_email ?: '-') ?></dd></div>
</dl>
<p class="g-apply-note">Gelombang 1 — potongan 50% biaya pengembangan.</p>
<a class="g-cta g-cta-green g-cta-block" href="<?= base_url('#spmb') ?>">Amankan kursi<svg><use href="#i-arrow"/></svg></a>
</aside>
</div>
<figure class="g-fig rv">
<svg viewBox="0 0 1120 380" role="img" aria-label="Ilustrasi kampus <?= esc($site_name) ?>">
<rect width="1120" height="380" rx="0" fill="#0E2A1D"/>
<g fill="#143B28"><ellipse cx="140" cy="330" rx="220" ry="60"/><ellipse cx="980" cy="330" rx="240" ry="60"/></g>
<g fill="#1B4A30"><rect x="380" y="130" width="360" height="200" rx="10"/><rect x="410" y="100" width="300" height="34" rx="6"/></g>
<path d="M410 100 L560 40 L710 100" fill="none" stroke="#FACC15" stroke-width="6" stroke-linejoin="round"/>
<rect x="556" y="18" width="7" height="28" fill="#FACC15"/><path d="M563 18 L585 25 L563 32 Z" fill="#FACC15"/>
<g fill="#DFF0E1"><rect x="404" y="152" width="44" height="56" rx="4"/><rect x="460" y="152" width="44" height="56" rx="4"/><rect x="516" y="152" width="44" height="56" rx="4"/><rect x="572" y="152" width="44" height="56" rx="4"/><rect x="628" y="152" width="44" height="56" rx="4"/><rect x="404" y="220" width="44" height="56" rx="4"/><rect x="460" y="220" width="44" height="56" rx="4"/><rect x="572" y="220" width="44" height="56" rx="4"/><rect x="628" y="220" width="44" height="56" rx="4"/></g>
<rect x="534" y="238" width="52" height="92" rx="4" fill="#FACC15"/>
<g fill="#0E2A1D"><circle cx="180" cy="260" r="16"/><rect x="168" y="278" width="24" height="52" rx="10"/><circle cx="940" cy="262" r="16"/><rect x="928" y="280" width="24" height="50" rx="10"/></g>
<g fill="#fff" opacity=".85"><ellipse cx="200" cy="70" rx="60" ry="16"/><ellipse cx="900" cy="56" rx="70" ry="18"/></g>
<circle cx="985" cy="95" r="26" fill="#FACC15"/>
</svg>
<figcaption><span><svg><use href="#i-pin"/></svg><?= esc($contact_address ?: 'Kampus utama') ?></span><span class="g-fig-live"><i></i>Tahun ajaran 2026/2027</span></figcaption>
</figure>
</div>
</section>

<!-- ===== TICKER : garis berjalan, terang ===== -->
<?php
$tickerItems = [];
$announce = null;
foreach (($latest_news ?? []) as $ln) {
    if (stripos($ln['category'] ?? '', 'pengumuman') !== false) { $announce = $ln; break; }
}
$announce = $announce ?? ($latest_news[0] ?? null);
if (!empty($announce['title'])) $tickerItems[] = $announce['title'];
foreach (array_slice($news ?? [], 0, 2) as $tn) {
    if (!empty($tn['title']) && !in_array($tn['title'], $tickerItems, true)) $tickerItems[] = $tn['title'];
}
foreach (array_slice($previewEvents, 0, 2) as $te) {
    if (!empty($te['title']) && !in_array($te['title'], $tickerItems, true)) $tickerItems[] = $te['title'];
}
if (empty($tickerItems)) $tickerItems[] = $site_tagline ?: $site_name;
?>
<div class="g-ticker" aria-label="Pengumuman berjalan">
<div class="wrap g-ticker-in">
<span class="g-ticker-lbl">Info</span>
<div class="g-ticker-view"><div class="ticker-track" id="tickerTrack">
<?php foreach ($tickerItems as $tt): ?><span><i></i><?= esc($tt) ?></span><?php endforeach; ?>
</div></div>
</div>
</div>

<!-- ===== ANGKA : empat statistik bergaris atas ===== -->
<section class="g-sec" aria-label="Data sekolah">
<div class="wrap g-nums">
<?php foreach (array_slice($counterItems, 0, 4) as $c): ?>
<div class="rv"><p class="g-num-k"><?= esc($c['label'] ?? 'Data') ?></p><p class="g-num-v"><?= esc($c['number'] ?? '0') ?><?php if (!empty($c['suffix'])): ?><small><?= esc($c['suffix']) ?></small><?php endif; ?></p><p class="g-num-d"><?= esc($c['label'] ?? '') ?></p></div>
<?php endforeach; ?>
</div>
</section>

<!-- ===== SAMBUTAN : kartu kepsek menempel + surat ===== -->
<section class="g-sec g-paper" id="profil">
<div class="wrap">
<div class="g-shead rv"><p class="g-kick">Profil sekolah</p><a class="g-link" href="<?= base_url('profile') ?>">Sejarah lengkap</a></div>
<h2 class="g-h2 rv">Sambutan Kepala Sekolah</h2>
<p class="g-sub rv"><?= esc($aboutData['content_title'] ?? 'Disiplin yang hangat, target yang jelas, pendampingan yang sabar.') ?></p>
<div class="g-sambut">
<aside class="g-person rv">
<?php if (!empty($principalData['photo'])): ?>
<span class="g-avatar" style="padding:0;overflow:hidden"><img src="<?= esc(u_img($principalData['photo'])) ?>" alt="<?= esc($__pName) ?>" loading="lazy" style="width:100%;height:100%;object-fit:cover"></span>
<?php else: ?>
<span class="g-avatar"><?= esc($__pInitials) ?></span>
<?php endif; ?>
<?php if ($__pQuote): ?><p class="g-quote">“<?= esc($__pQuote) ?>”</p><?php endif; ?>
<p class="g-person-name"><?= esc($__pName) ?></p>
<p class="g-person-nip"><?= esc($__pRole) ?></p>
</aside>
<div class="g-letter rv">
<?php if (!empty($aboutData['content_1'])): ?><p><?= esc($aboutData['content_1']) ?></p><?php endif; ?>
<?php if (!empty($aboutData['content_2'])): ?><p><?= esc($aboutData['content_2']) ?></p><?php endif; ?>
<?php if (empty($aboutData['content_1']) && empty($aboutData['content_2'])): ?>
<p>Prestasi dibangun berdampingan dengan karakter: apel pagi yang tertib, guru yang hafal nama setiap siswa, dan target belajar yang transparan bagi orang tua.</p>
<?php endif; ?>
<?php if (!empty($aboutData['visi'])): ?><p><strong>Visi:</strong> <?= esc($aboutData['visi']) ?></p><?php endif; ?>
<p class="g-sign"><?= esc($__pName) ?> <span><?= esc($__pRole) ?></span></p>
<div style="margin-top:18px"><a class="g-cta g-cta-green" href="<?= base_url('profile') ?>">Sejarah &amp; Visi Misi<svg><use href="#i-arrow"/></svg></a></div>
</div>
</div>
</div>
</section>

<!-- ===== PROGRAM : daftar baris, bukan kartu ===== -->
<?php if (!empty($previewPrograms)): ?>
<section class="g-sec" id="program">
<div class="wrap">
<div class="g-shead rv"><p class="g-kick">Akademik</p><a class="g-link" href="<?= base_url('program') ?>">Semua program</a></div>
<h2 class="g-h2 rv">Tiga peminatan, satu standar.</h2>
<p class="g-sub rv">Pilihan peminatan dan pembinaan di <?= esc($site_name) ?>.</p>
<div class="g-prog rv">
<?php foreach ($previewPrograms as $i => $p): ?>
<a class="g-prow" href="<?= program_link($p, 'program') ?>"><span class="g-prow-n">0<?= $i + 1 ?></span><span class="g-prow-tx"><span class="g-prow-t"><?= esc($p['title']) ?></span><span class="g-prow-d"><?= esc(mb_strimwidth($p['description'] ?? '', 0, 120, '…')) ?></span></span><span class="g-prow-go"><span>Lihat</span><svg><use href="#i-arrow"/></svg></span></a>
<?php endforeach; ?>
</div>
</div>
</section>
<?php endif; ?>

<!-- ===== FASILITAS : bento 6 sel ===== -->
<?php if (!empty($previewHighlights)): ?>
<section class="g-sec g-paper" id="fasilitas">
<div class="wrap">
<div class="g-shead rv"><p class="g-kick">Fasilitas</p></div>
<h2 class="g-h2 rv">Lingkungan yang membuat anak betah belajar.</h2>
<div class="g-bento">
<?php $bentoArt = ['lib', 'lab', 'sport', 'bk', 'aula', 'uks']; ?>
<?php foreach ($previewHighlights as $i => $hl): $t = is_array($hl) ? ($hl['text'] ?? '') : $hl; ?>
<div class="g-f rv"><span class="g-f-art" data-a="<?= $bentoArt[$i % 6] ?>"></span><h3><?= esc(mb_strimwidth($t, 0, 45, '…')) ?></h3><p><?= esc($t) ?></p></div>
<?php endforeach; ?>
</div>
</div>
</section>
<?php endif; ?>

<!-- ===== AGENDA : tanggal teks + pil lokasi ===== -->
<?php if (!empty($previewEvents)): ?>
<section class="g-sec" id="agenda">
<div class="wrap">
<div class="g-shead rv"><p class="g-kick">Agenda</p><a class="g-link" href="<?= base_url('agenda') ?>">Kalender penuh</a></div>
<h2 class="g-h2 rv">Agenda terdekat</h2>
<p class="g-sub rv">Jadwal resmi <?= esc($site_name) ?>.</p>
<div class="g-agenda rv">
<?php foreach ($previewEvents as $ev): $ts = strtotime($ev['event_date'] ?? ''); ?>
<div class="g-arow" data-cat="akademik"><div class="g-adate"><b><?= $ts ? date('d', $ts) : '–' ?></b><span><?= $ts ? date("M 'y", $ts) : '' ?></span></div><div><p class="g-acat">Agenda<?= !empty($ev['event_time']) ? ' · ' . esc($ev['event_time']) : '' ?></p><h3><?= esc($ev['title']) ?></h3><p><?= esc(mb_strimwidth($ev['description'] ?? '', 0, 90, '…')) ?></p></div><span class="g-aloc"><svg><use href="#i-pin"/></svg><?= esc($ev['location'] ?: 'Sekolah') ?></span></div>
<?php endforeach; ?>
</div>
</div>
</section>
<?php endif; ?>

<!-- ===== BERITA : tiga kartu sejajar ===== -->
<?php if (!empty($previewNews)): ?>
<section class="g-sec g-paper" id="berita">
<div class="wrap">
<div class="g-shead rv"><p class="g-kick">Berita &amp; prestasi</p><a class="g-link" href="<?= base_url('news') ?>">Semua berita</a></div>
<h2 class="g-h2 rv">Kabar terbaru dari kampus.</h2>
<div class="g-cards">
<?php $cardArt = ['robot', 'wisuda', 'lib']; ?>
<?php foreach ($previewNews as $i => $n): $ni = u_img($n['image'] ?? '', ''); ?>
<a class="g-card rv" href="<?= base_url('news/' . esc($n['slug'])) ?>">
<?php if ($ni): ?><span class="g-card-art" style="padding:0;overflow:hidden"><img src="<?= esc($ni) ?>" alt="<?= esc($n['title']) ?>" loading="lazy" style="width:100%;height:100%;object-fit:cover"></span>
<?php else: ?><span class="g-card-art" data-c="<?= $cardArt[$i % 3] ?>"></span><?php endif; ?>
<span class="g-card-bd"><span class="g-card-meta"><b><?= esc(explode(',', $n['category'] ?? 'Berita')[0]) ?></b> · <?= date('j M Y', strtotime($n['published_at'] ?? $n['created_at'])) ?></span><span class="g-card-t"><?= esc($n['title']) ?></span><span class="g-card-x"><?= esc($n['excerpt'] ?? mb_strimwidth(strip_tags($n['content'] ?? ''), 0, 90, '…')) ?></span></span></a>
<?php endforeach; ?>
</div>
</div>
</section>
<?php endif; ?>

<!-- ===== SUARA ALUMNI : satu kutipan tengah ===== -->
<section class="g-sec g-voice">
<div class="wrap rv">
<p class="g-kick">Testimoni</p>
<?php if ($quote): ?>
<blockquote>“<?= esc($quote['quote']) ?>”</blockquote>
<p class="g-voice-who"><span class="g-mini-av"><?php if (!empty($quote['photo'])): ?><img src="<?= esc($quote['photo']) ?>" alt="" loading="lazy"><?php else: ?><?= esc(mb_strtoupper(mb_substr(trim($quote['name']), 0, 2, 'UTF-8'), 'UTF-8')) ?><?php endif; ?></span><span><strong><?= esc($quote['name']) ?></strong> · <?= esc($quote['role'] ?? 'Testimoni') ?></span></p>
<?php else: ?>
<blockquote>“Pendidikan terbaik lahir dari keteladanan, kedisiplinan, dan kasih sayang.”</blockquote>
<p class="g-voice-who"><span class="g-mini-av"><?= esc(mb_strtoupper(mb_substr(trim($site_name), 0, 2, 'UTF-8'), 'UTF-8')) ?></span><span><strong><?= esc($site_name) ?></strong></span></p>
<?php endif; ?>
<div style="margin-top:18px"><a class="g-link" href="<?= base_url('testimonials') ?>">Semua testimoni →</a></div>
</div>
</section>

<!-- ===== SPMB : 10 langkah + progress ===== -->
<section class="g-sec g-paper" id="spmb">
<?php $spmbSteps = (!empty($spmb_steps) && is_array($spmb_steps)) ? array_values($spmb_steps) : default_spmb_steps(); ?>
<div class="wrap">
<div class="g-shead rv"><p class="g-kick"><?= esc($hero_badge ?: 'SPMB') ?></p></div>
<h2 class="g-h2 rv"><?= count($spmbSteps) ?> Langkah Mudah Mendaftar.</h2>
<p class="g-sub rv">Proses pendaftaran bertahap dengan progress bar yang memandu calon siswa hingga cetak formulir PDF.</p>
<div class="g-ppdb">
<div>
<div class="sp-flow rv" aria-label="Alur pendaftaran <?= count($spmbSteps) ?> langkah">
<div class="sp-track" aria-hidden="true"><?php for ($i = 0; $i < count($spmbSteps); $i++): ?><span></span><?php endfor; ?></div>
<div class="sp-cap"><span>Langkah 1–<?= count($spmbSteps) ?></span><span>Final: cetak formulir PDF</span></div>
</div>
<ol class="g-steps rv">
<?php foreach ($spmbSteps as $i => $st): ?>
<li><span><?= str_pad($i + 1, 2, '0', STR_PAD_LEFT) ?></span><div><h3><?= esc($st['title']) ?></h3><p><?= esc($st['desc']) ?></p></div></li>
<?php endforeach; ?>
</ol>
</div>
<div class="g-ppdb-side rv">
<p class="g-ppdb-dl">Kontak <strong><?= esc($contact_phone ?: $contact_email ?: $site_name) ?></strong></p>
<ul class="g-check">
<li><svg><use href="#i-check"/></svg>Kuota terbatas tiap gelombang</li>
<li><svg><use href="#i-check"/></svg>Prioritas kelas unggulan</li>
<li><svg><use href="#i-check"/></svg>Konseling peminatan gratis</li>
</ul>
<a class="g-cta g-cta-green g-cta-block" href="<?= base_url('contact') ?>">Daftar sekarang<svg><use href="#i-arrow"/></svg></a>
<a class="g-ghost g-ghost-lg g-ghost-block" href="<?= base_url('contact') ?>">Jadwal open house</a>
</div>
</div>
</div>
</section>

<!-- ===== KONTAK : info ringkas + formulir terang ===== -->
<section class="g-sec" id="kontak">
<div class="wrap">
<div class="g-shead rv"><p class="g-kick">Kontak</p></div>
<h2 class="g-h2 rv">Datang, lihat, bertanya langsung.</h2>
<div class="g-kontak">
<div class="rv">
<ul class="g-clist">
<li><div><h3>Alamat</h3><p><?= esc($contact_address ?: 'Lihat halaman kontak') ?></p></div></li>
<li><div><h3>Telepon / WA</h3><p><?= esc($contact_phone ?: '-') ?></p></div></li>
<li><div><h3>Surel</h3><p><?= esc($contact_email ?: '-') ?></p></div></li>
<li><div><h3>Jam layanan</h3><p><?= esc($contact_hours ?: '-') ?></p></div></li>
</ul>
</div>
<div class="g-form rv">
<h3>Butuh informasi cepat?</h3>
<p>Kirim pesan melalui halaman kontak — balasan maks. 1×24 jam hari kerja.</p>
<a class="g-cta g-cta-green g-cta-block" href="<?= base_url('contact') ?>">Buka Halaman Kontak<svg><use href="#i-arrow"/></svg></a>
</div>
</div>
</div>
</section>

</main>

<?= $this->include(theme_view('layouts/footer')) ?>
