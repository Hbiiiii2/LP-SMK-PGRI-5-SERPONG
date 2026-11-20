<?= $this->include(theme_view('layouts/header')) ?>
<?php
$heroStats = is_array($hero_stats ?? null) ? $hero_stats : [];
$counterItems = is_array($counter_stats ?? null) ? $counter_stats : [];
if (empty($counterItems)) {
    $counterItems = [
        ['number' => '40', 'suffix' => '+', 'label' => 'Rombongan Belajar'],
        ['number' => '25', 'suffix' => '+', 'label' => 'Tahun Berdiri'],
        ['number' => '24', 'suffix' => '+', 'label' => 'Program Studi'],
        ['number' => '3000', 'suffix' => '+', 'label' => 'Alumni'],
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
$featNews = $previewNews[0] ?? null;
$listNews = array_slice($previewNews, 1, 3);
$quote = ($testimonials ?? [])[0] ?? null;
$highlights = $aboutData['highlights'] ?? [];
$previewHighlights = array_slice(is_array($highlights) ? $highlights : [], 0, 6);
// Petakan anchor/slug lama ke URL English baru (tahan terhadap isi DB lama)
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
?>
<main id="konten">

<!-- ============ HERO ============ -->
<section class="hero hero-plain" id="beranda">
  <div class="wrap hero-grid">
    <div class="hero-copy">
      <span class="kicker"><?= esc($hero_badge ?: ($site_tagline ?: 'Profil Sekolah')) ?></span>
      <h1><?= ($hero_title ?? '') !== '' ? esc($hero_title) : 'Tumbuh berilmu,<br>tegak <em>berkarakter.</em>' ?></h1>
      <p class="hero-lead"><?= esc($hero_subtitle ?: 'Informasi profil, program, prestasi, dan layanan ' . $site_name) ?></p>
      <div class="hero-cta">
        <?php if (!empty($hero_btn_primary_text)): ?>
        <a class="btn btn-navy" href="<?= esc($mapUrl($hero_btn_primary_url ?: '#spmb')) ?>"><?= esc($hero_btn_primary_text) ?> <svg><use href="#i-arrow"/></svg></a>
        <?php else: ?>
        <a class="btn btn-navy" href="<?= base_url('#spmb') ?>">Daftar SPMB <svg><use href="#i-arrow"/></svg></a>
        <?php endif; ?>
        <?php if (!empty($hero_btn_secondary_text)): ?>
        <a class="btn btn-ghost" href="<?= esc($mapUrl($hero_btn_secondary_url ?: 'profile')) ?>"><?= esc($hero_btn_secondary_text) ?></a>
        <?php else: ?>
        <a class="btn btn-ghost" href="<?= base_url('profile') ?>">Jelajahi Sekolah</a>
        <?php endif; ?>
      </div>
    </div>
    <?php $schoolImg = u_img($aboutData['image'] ?? '', ''); ?>
    <?php if ($schoolImg): ?>
    <aside class="hero-side rv" aria-label="Tentang <?= esc($site_name) ?>">
      <figure class="frame">
        <div class="frame-inner">
          <img src="<?= esc($schoolImg) ?>" alt="Foto <?= esc($site_name) ?>" loading="eager">
        </div>
        <figcaption class="frame-cap"><span><?= esc($aboutData['content_title'] ?? $site_name) ?></span><span class="no">FIG. 01</span></figcaption>
      </figure>
      <?php if (!empty($aboutData['accreditation'])): ?>
      <div class="hero-accred"><span class="ha-badge">Akreditasi <?= esc($aboutData['accreditation']) ?></span><span class="ha-lbl"><?= esc($aboutData['accreditation_label'] ?? '') ?></span></div>
      <?php endif; ?>
    </aside>
    <?php elseif (!empty($heroStats)): ?>
    <aside class="hero-side rv" aria-label="Data sekolah">
      <div class="hero-statcard">
        <div class="hsc-h">Data Sekolah</div>
        <?php foreach (array_slice($heroStats, 0, 3) as $hs): ?>
        <div class="hsc-row"><div class="hsc-num"><?= esc($hs['value'] ?? $hs['number'] ?? '0') ?></div><div class="hsc-lbl"><?= esc($hs['label'] ?? '') ?></div></div>
        <?php endforeach; ?>
      </div>
    </aside>
    <?php endif; ?>
  </div>
</section>

<!-- ============ TICKER ============ -->
<?php
// Ticker full dinamis dari modul CMS (tanpa teks hardcoded)
$tickerItems = [];
// 1. Pengumuman terbaru (kategori mengandung "pengumuman"), fallback berita terbaru
$announce = null;
foreach (($latest_news ?? []) as $ln) {
    if (stripos($ln['category'] ?? '', 'pengumuman') !== false) { $announce = $ln; break; }
}
$announce = $announce ?? ($latest_news[0] ?? null);
if (!empty($announce['title'])) $tickerItems[] = $announce['title'];
// 2-3. Berita unggulan (hindari duplikat dengan item 1)
foreach (array_slice($previewNews, 0, 2) as $tn) {
    if (!empty($tn['title']) && !in_array($tn['title'], $tickerItems, true)) $tickerItems[] = $tn['title'];
}
// 4-5. Agenda terdekat
foreach (array_slice($previewEvents, 0, 2) as $te) {
    if (!empty($te['title']) && !in_array($te['title'], $tickerItems, true)) $tickerItems[] = $te['title'];
}
// 6. Prestasi terbaru
$ach0 = ($achievements ?? [])[0] ?? null;
if (!empty($ach0['achievement'])) {
    $achT = 'Prestasi: ' . $ach0['achievement'] . (!empty($ach0['student_name']) ? ' — ' . $ach0['student_name'] : '');
    if (!in_array($achT, $tickerItems, true)) $tickerItems[] = $achT;
}
// Fallback dari settings agar ticker tidak pernah kosong
if (empty($tickerItems)) $tickerItems[] = $site_tagline ?: $site_name;
?>
<div class="ticker" aria-label="Pengumuman berjalan">
  <div class="ticker-track" id="tickerTrack">
    <?php foreach ($tickerItems as $tt): ?><span><i>◆</i> <?= esc($tt) ?></span><?php endforeach; ?>
  </div>
</div>

<!-- ============ STATS ============ -->
<section class="stats" aria-label="Data sekolah">
  <div class="wrap">
    <?php foreach (array_slice($counterItems, 0, 4) as $i => $c): ?>
    <div class="stat rv"><div class="tag">S.0<?= $i + 1 ?> — <?= esc($c['label'] ?? 'Data') ?></div><div class="val"><?= esc($c['number'] ?? '0') ?><span><?= esc($c['suffix'] ?? '+') ?></span></div><div class="dsc"><?= esc($c['label'] ?? '') ?></div></div>
    <?php endforeach; ?>
  </div>
</section>

<!-- ============ SAMBUTAN RINGKAS ============ -->
<section class="sec" id="profil">
  <div class="wrap">
    <div class="sec-head rv"><span class="idx">01</span><span class="lbl">Profil Sekolah</span><span class="rule"></span><a class="more" href="<?= base_url('profile') ?>">Profil lengkap →</a></div>
    <h2 class="sec-title rv">Sambutan Kepala Sekolah</h2>
    <p class="sec-desc rv"><?= esc($aboutData['content_title'] ?? 'Mengenal lebih dekat ' . $site_name) ?></p>
    <div class="sambut-grid">
      <div class="plate rv">
        <?php if (!empty($principalData['photo'])): ?>
        <div class="plate-photo"><img src="<?= esc(u_img($principalData['photo'])) ?>" alt="<?= esc($principalData['name'] ?? 'Kepala Sekolah') ?>" loading="lazy"></div>
        <?php else: ?>
        <svg class="plate-pattern" viewBox="0 0 300 300" preserveAspectRatio="xMidYMid slice" aria-hidden="true"><g stroke="#fff" stroke-width="1" fill="none"><circle cx="150" cy="120" r="90"/><circle cx="150" cy="120" r="66"/><circle cx="150" cy="120" r="42"/></g></svg>
        <div class="plate-mono"><?= esc(mb_substr($principalData['name'] ?? $site_name, 0, 2, 'UTF-8')) ?></div>
        <?php endif; ?>
        <div class="plate-name"><strong><?= esc($principalData['name'] ?? 'Kepala Sekolah') ?></strong><span><?= esc(strtoupper($principalData['role'] ?? $principalData['role_title'] ?? 'KEPALA SEKOLAH')) ?></span></div>
      </div>
      <div class="sambut-body rv">
        <?php $sambut = $principalData['quote'] ?? $principalData['sambutan'] ?? $principalData['welcome_message'] ?? ''; ?>
        <?php if ($sambut): ?><p><?= esc($sambut) ?></p><?php endif; ?>
        <?php if (!empty($aboutData['visi'])): ?><p><strong>Visi:</strong> <?= esc($aboutData['visi']) ?></p><?php endif; ?>
        <div class="sign">
          <span class="sign-name"><?= esc($principalData['name'] ?? $site_name) ?></span>
          <span class="sign-role"><?= esc($principalData['role'] ?? 'Kepala Sekolah') ?></span>
        </div>
        <div style="margin-top:24px"><a class="btn btn-navy" href="<?= base_url('profile') ?>">Sejarah &amp; Visi Misi <svg><use href="#i-arrow"/></svg></a></div>
      </div>
    </div>
  </div>
</section>

<!-- ============ PROGRAM PREVIEW ============ -->
<?php if (!empty($previewPrograms)): ?>
<section class="sec tint" id="akademik">
  <div class="wrap">
    <div class="sec-head rv"><span class="idx">02</span><span class="lbl">Program</span><span class="rule"></span><a class="more" href="<?= base_url('program') ?>">Semua program →</a></div>
    <h2 class="sec-title rv">Program unggulan kami.</h2>
    <p class="sec-desc rv">Pilihan peminatan dan pembinaan di <?= esc($site_name) ?>.</p>
    <div class="prog rv">
      <?php foreach ($previewPrograms as $i => $p): ?>
      <a class="prog-row" href="<?= program_link($p, 'program') ?>">
        <span class="p-num">/ 0<?= $i + 1 ?></span>
        <span><span class="p-title"><?= esc($p['title']) ?></span>
        <span class="p-desc" style="display:block"><?= esc(mb_strimwidth($p['description'] ?? '', 0, 140, '…')) ?></span></span>
        <span class="p-tags" style="justify-content:flex-end"><span>Lihat</span></span>
        <span class="p-arrow"><svg><use href="#i-arrow"/></svg></span>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ============ KEUNGGULAN RINGKAS ============ -->
<?php if (!empty($previewHighlights)): ?>
<section class="sec" id="fasilitas">
  <div class="wrap">
    <div class="sec-head rv"><span class="idx">03</span><span class="lbl">Keunggulan</span><span class="rule"></span></div>
    <h2 class="sec-title rv">Lingkungan yang membuat anak betah belajar.</h2>
    <div class="fas-grid rv">
      <?php foreach ($previewHighlights as $i => $hl): $t = is_array($hl) ? ($hl['text'] ?? '') : $hl; ?>
      <div class="fas"><span class="f-no">F.0<?= $i + 1 ?></span><svg><use href="#i-check"/></svg><h3><?= esc($t) ?></h3></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ============ AGENDA PREVIEW ============ -->
<?php if (!empty($previewEvents)): ?>
<section class="sec tint" id="agenda">
  <div class="wrap">
    <div class="sec-head rv"><span class="idx">04</span><span class="lbl">Agenda</span><span class="rule"></span><a class="more" href="<?= base_url('agenda') ?>">Kalender penuh →</a></div>
    <h2 class="sec-title rv">Agenda terdekat</h2>
    <p class="sec-desc rv">Jadwal resmi sekolah.</p>
    <div class="agenda rv" id="agendaList">
      <?php foreach ($previewEvents as $ev): $ts = strtotime($ev['event_date'] ?? ''); ?>
      <div class="ag-row" data-cat="akademik">
        <div class="ag-date"><div class="d"><?= $ts ? date('d', $ts) : '–' ?></div><div class="m"><?= $ts ? date("M 'y", $ts) : '' ?></div></div>
        <div class="ag-info"><span class="cat">Agenda</span><h3><?= esc($ev['title']) ?></h3><p><?= esc(mb_strimwidth($ev['description'] ?? '', 0, 110, '…')) ?></p></div>
        <span class="ag-loc"><svg><use href="#i-pin"/></svg><?= esc($ev['location'] ?: ($ev['event_time'] ?: 'Sekolah')) ?></span>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ============ BERITA PREVIEW ============ -->
<?php if (!empty($previewNews)): ?>
<section class="sec" id="berita">
  <div class="wrap">
    <div class="sec-head rv"><span class="idx">05</span><span class="lbl">Berita</span><span class="rule"></span><a class="more" href="<?= base_url('news') ?>">Semua berita →</a></div>
    <h2 class="sec-title rv">Kabar terbaru dari kampus.</h2>
    <div class="news-grid">
      <?php if ($featNews): $fi = u_img($featNews['image'] ?? '', ''); $fcatH = trim(explode(',', $featNews['category'] ?? 'Berita')[0]); ?>
      <a class="feat rv" href="<?= base_url('news/' . esc($featNews['slug'])) ?>">
        <?php if ($fi): ?><div class="feat-media"><img src="<?= esc($fi) ?>" alt="<?= esc($featNews['title']) ?>" loading="lazy"></div>
        <?php else: ?><div class="feat-media feat-ph" aria-hidden="true"><span class="feat-ph-letter"><?= esc(mb_strtoupper(mb_substr(trim($featNews['title']), 0, 1, 'UTF-8'), 'UTF-8')) ?></span><span class="feat-ph-cat"><?= esc(strtoupper($fcatH)) ?></span></div><?php endif; ?>
        <div class="feat-body">
          <div class="catline"><span class="cat"><?= esc($fcatH) ?></span><span class="dt">— <?= date('j M Y', strtotime($featNews['published_at'] ?? $featNews['created_at'])) ?></span></div>
          <h3 class="feat-title"><?= esc($featNews['title']) ?></h3>
          <p class="feat-ex"><?= esc($featNews['excerpt'] ?? mb_strimwidth(strip_tags($featNews['content'] ?? ''), 0, 160, '…')) ?></p>
        </div>
      </a>
      <?php endif; ?>
      <div class="news-list rv">
        <?php foreach ($listNews as $n): $ni = u_img($n['image'] ?? '', ''); ?>
        <a class="nl-item" href="<?= base_url('news/' . esc($n['slug'])) ?>">
          <?php if ($ni): ?><img class="nl-thumb" src="<?= esc($ni) ?>" alt="<?= esc($n['title']) ?>" loading="lazy">
          <?php else: ?><span class="nl-thumb nl-ph" aria-hidden="true"><?= esc(mb_strtoupper(mb_substr(trim($n['title']), 0, 1, 'UTF-8'), 'UTF-8')) ?></span><?php endif; ?>
          <span class="nl-text"><span class="catline"><span class="cat"><?= esc(explode(',', $n['category'] ?? 'Berita')[0]) ?></span><span class="dt">— <?= date('j M Y', strtotime($n['published_at'] ?? $n['created_at'])) ?></span></span><span class="nl-title"><?= esc($n['title']) ?></span></span>
        </a>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ============ QUOTE ============ -->
<section class="quote">
  <div class="wrap rv">
    <span class="q-mark">“</span>
    <?php if ($quote): ?>
    <blockquote><?= esc($quote['quote']) ?></blockquote>
    <cite><?php if (!empty($quote['photo'])): ?><img class="quote-ava" src="<?= esc($quote['photo']) ?>" alt="" loading="lazy"><?php endif; ?><strong><?= esc($quote['name']) ?></strong> · <?= esc($quote['role'] ?? 'Testimoni') ?></cite>
    <?php else: ?>
    <blockquote>Pendidikan terbaik lahir dari keteladanan, kedisiplinan, dan kasih sayang.</blockquote>
    <cite><strong><?= esc($site_name) ?></strong></cite>
    <?php endif; ?>
  </div>
</section>

<!-- ============ PPDB ============ -->
<section class="ppdb" id="spmb">
  <?php $spmbSteps = (!empty($spmb_steps) && is_array($spmb_steps)) ? array_values($spmb_steps) : default_spmb_steps(); ?>
  <div class="wrap">
    <div class="rv">
      <span class="kicker">SPMB — Seleksi Penerimaan Murid Baru</span>
      <h2><?= count($spmbSteps) ?> Langkah Mudah <em>Mendaftar.</em></h2>
      <p class="lead">Proses pendaftaran bertahap dengan progress bar yang memandu calon siswa hingga cetak formulir PDF.</p>
      <div class="ppdb-cta">
        <a class="btn btn-red" href="<?= base_url('contact') ?>">Daftar Sekarang <svg><use href="#i-arrow"/></svg></a>
        <a class="btn btn-outline-w" href="<?= base_url('contact') ?>">Jadwal Open House</a>
      </div>
      <p class="ppdb-note"><i></i><?= esc($contact_phone ?: $contact_email) ?></p>
    </div>
    <div>
    <div class="sp-flow rv" aria-label="Alur pendaftaran <?= count($spmbSteps) ?> langkah">
      <div class="sp-track" aria-hidden="true"><?php for ($i = 0; $i < count($spmbSteps); $i++): ?><span></span><?php endfor; ?></div>
      <div class="sp-cap"><span>Langkah 1–<?= count($spmbSteps) ?></span><span>Final: cetak formulir PDF</span></div>
    </div>
    <ol class="steps rv">
      <?php foreach ($spmbSteps as $i => $st): ?>
      <li data-n="<?= str_pad($i + 1, 2, '0', STR_PAD_LEFT) ?>"><h3><?= esc($st['title']) ?></h3><p><?= esc($st['desc']) ?></p></li>
      <?php endforeach; ?>
    </ol>
    </div>
  </div>
</section>

<!-- ============ KONTAK RINGKAS ============ -->
<section class="sec" id="kontak">
  <div class="wrap">
    <div class="sec-head rv"><span class="idx">06</span><span class="lbl">Kontak</span><span class="rule"></span><a class="more" href="<?= base_url('contact') ?>">Halaman kontak →</a></div>
    <h2 class="sec-title rv">Datang, lihat, dan bertanya langsung.</h2>
    <div class="kontak-grid">
      <div class="rv">
        <div class="k-item"><svg><use href="#i-pin"/></svg><div><h3>Alamat</h3><p><?= esc($contact_address ?: 'Lihat halaman kontak') ?></p></div></div>
        <div class="k-item"><svg><use href="#i-phone"/></svg><div><h3>Telepon</h3><p><?= esc($contact_phone ?: '-') ?></p></div></div>
        <div class="k-item"><svg><use href="#i-mail"/></svg><div><h3>Surel</h3><p><?= esc($contact_email ?: '-') ?></p></div></div>
        <div class="k-item"><svg><use href="#i-clock"/></svg><div><h3>Jam layanan</h3><p><?= esc($contact_hours ?: '-') ?></p></div></div>
      </div>
      <div class="form rv">
        <h3>Butuh informasi cepat?</h3>
        <p>Kirim pesan melalui halaman kontak — balasan maks. 1×24 jam pada hari kerja.</p>
        <a class="btn btn-navy" href="<?= base_url('contact') ?>" style="width:100%;justify-content:center">Buka Halaman Kontak <svg><use href="#i-arrow"/></svg></a>
      </div>
    </div>
  </div>
</section>

</main>

<?= $this->include(theme_view('layouts/footer')) ?>
