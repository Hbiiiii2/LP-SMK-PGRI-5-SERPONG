<?= view(theme_view('layouts/header')) ?>

<?php
$settings    = $settings ?? [];
$cleanText   = static fn (?string $s): string => strtr(trim((string) $s), ['Â·' => '·', 'â€”' => '—', 'â€“' => '–']);

$siteName    = $cleanText($site_name ?? ($settings['site_name'] ?? 'SMK PGRI 5 Serpong')) ?: 'SMK PGRI 5 Serpong';
$siteDesc    = $cleanText($site_description ?? ($settings['site_description'] ?? 'Lembaga pendidikan kejuruan yang berkomitmen mengembangkan kompetensi, karakter, dan kesiapan kerja peserta didik.'));
$heroBadge   = $cleanText($hero_badge ?? ($settings['hero_badge'] ?? 'SPMB Dibuka · Sekolah Resmi')) ?: 'SPMB Dibuka · Sekolah Resmi';
$heroTitle   = $cleanText($hero_title ?? ($settings['hero_title'] ?? $siteName)) ?: $siteName;
$heroSub     = $cleanText($hero_subtitle ?? ($settings['hero_subtitle'] ?? $siteDesc)) ?: $siteDesc;
$btnPrimText = $cleanText($hero_btn_primary_text ?? ($settings['hero_btn_primary_text'] ?? 'Daftar SPMB')) ?: 'Daftar SPMB';
$btnPrimUrl  = trim((string) ($hero_btn_primary_url ?? ($settings['hero_btn_primary_url'] ?? '#spmb'))) ?: '#spmb';
$btnSecText  = $cleanText($hero_btn_secondary_text ?? ($settings['hero_btn_secondary_text'] ?? 'Profil Sekolah')) ?: 'Profil Sekolah';
$btnSecUrl   = trim((string) ($hero_btn_secondary_url ?? ($settings['hero_btn_secondary_url'] ?? 'profile'))) ?: 'profile';

$contactAddr  = $cleanText($contact_address ?? ($settings['contact_address'] ?? ''));
$contactPhone = $cleanText($contact_phone ?? ($settings['contact_phone'] ?? ''));
$contactEmail = $cleanText($contact_email ?? ($settings['contact_email'] ?? ''));
$contactHours = $cleanText($contact_hours ?? ($settings['contact_hours'] ?? ''));
$spmbUrl      = trim((string) ($spmb_url ?? ($settings['spmb_url'] ?? '')));

$toHref = static function (string $raw): string {
    if ($raw === '' || $raw === '#') {
        return '#spmb';
    }
    if (str_starts_with($raw, '#') || str_starts_with($raw, 'http')) {
        return $raw;
    }
    return base_url(ltrim($raw, '/'));
};

$resolveMedia = static function (?string $raw): string {
    $v = trim((string) $raw);
    if ($v === '') {
        return '';
    }
    if (preg_match('#^https?://localhost(?::\d+)?/(.*)$#i', $v, $m)) {
        return base_url(ltrim($m[1], '/'));
    }
    if (str_starts_with($v, 'http://') || str_starts_with($v, 'https://') || str_starts_with($v, '/') || str_starts_with($v, 'data:')) {
        return $v;
    }
    return base_url(ltrim($v, '/'));
};

$aboutData     = is_array($about ?? null) ? $about : (json_decode((string) ($settings['about'] ?? '{}'), true) ?: []);
$principalData = is_array($principal ?? null) ? $principal : (json_decode((string) ($settings['principal'] ?? '{}'), true) ?: []);
$heroStats     = is_array($hero_stats ?? null) ? $hero_stats : (json_decode((string) ($settings['hero_stats'] ?? '[]'), true) ?: []);
$counterStats  = is_array($counter_stats ?? null) ? $counter_stats : (json_decode((string) ($settings['counter_stats'] ?? '[]'), true) ?: []);
$spmbSteps     = is_array($spmb_steps ?? null) ? $spmb_steps : (function_exists('resolve_spmb_steps') ? resolve_spmb_steps($settings['spmb_steps'] ?? null) : []);

$logoFallback  = theme_asset('img/logo-smk-pgri-5-serpong.png');
$aboutImageRaw = trim((string) ($aboutData['image'] ?? ''));
$aboutImage    = $aboutImageRaw !== '' ? $resolveMedia($aboutImageRaw) : $logoFallback;
$accredGrade   = $cleanText($aboutData['accreditation'] ?? '');
$accredLabel   = $cleanText($aboutData['accreditation_label'] ?? 'Institusi Pendidikan Resmi') ?: 'Institusi Pendidikan Resmi';

// Merge & deduplicate recent and featured news so the editorial split stays rich
$mergedNews = [];
$seenIds    = [];
foreach (array_merge($latest_news ?? [], $news ?? []) as $nItem) {
    $nid = $nItem['id'] ?? ($nItem['slug'] ?? uniqid());
    if (! isset($seenIds[$nid])) {
        $seenIds[$nid] = true;
        $mergedNews[] = $nItem;
    }
}

$leadNotice    = $mergedNews[0] ?? null;
$programList   = array_slice($programs ?? [], 0, 6);
$achList       = array_slice($achievements ?? [], 0, 5);
$leadStory     = $mergedNews[0] ?? null;
$indexStories  = array_slice($mergedNews, 1, 4);
$eventList     = array_slice($events ?? [], 0, 4);
$testiList     = array_slice($testimonials ?? [], 0, 2);
$galleryList   = array_slice($galleries ?? [], 0, 6);
$highlights    = is_array($aboutData['highlights'] ?? null) ? array_slice($aboutData['highlights'], 0, 4) : [];

$monthNames = [
    1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun',
    7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des',
];
?>

<!-- 1. Editorial Split Hero & Institutional Data Ledger -->
<section class="hero">
  <div class="wrap hero-grid">
    <div>
      <div class="hero-badges">
        <span class="badge badge-blue">
          <span class="badge-dot" aria-hidden="true"></span>
          <span><?= esc($heroBadge) ?></span>
        </span>
        <?php if ($accredGrade !== '' && stripos($heroBadge, 'akreditasi') === false): ?>
          <span class="badge badge-amber">
            <?= lucide_icon('award', '', 13) ?>
            <span>Akreditasi <?= esc($accredGrade) ?></span>
          </span>
        <?php endif; ?>
      </div>

      <h1 class="hero-title"><?= esc($heroTitle) ?></h1>
      <p class="hero-lead"><?= esc($heroSub) ?></p>

      <div class="hero-cta">
        <a class="btn btn-primary btn-lg" href="<?= esc($toHref($btnPrimUrl)) ?>">
          <span><?= esc($btnPrimText) ?></span>
          <?= lucide_icon('arrow-right', '', 17) ?>
        </a>
        <a class="btn btn-secondary btn-lg" href="<?= esc($toHref($btnSecUrl)) ?>">
          <?= lucide_icon('school', '', 17) ?>
          <span><?= esc($btnSecText) ?></span>
        </a>
      </div>

      <?php if (! empty($leadNotice)): ?>
        <a class="hero-notice" href="<?= esc(base_url('news/' . ($leadNotice['slug'] ?? ''))) ?>">
          <span class="badge badge-red">
            <span class="badge-dot" aria-hidden="true"></span>
            <span>Terbaru</span>
          </span>
          <span class="hero-notice-text"><?= esc($leadNotice['title'] ?? '') ?></span>
          <?= lucide_icon('chevron-right', '', 15) ?>
        </a>
      <?php endif; ?>
    </div>

    <div class="hero-card">
      <div class="hero-emblem-box">
        <img class="<?= $aboutImage === $logoFallback || str_contains($aboutImage, 'about') ? 'hero-emblem-img' : 'hero-photo-img' ?>"
             src="<?= esc($aboutImage) ?>"
             alt="Identitas Resmi <?= esc($siteName) ?>">
        <div class="hero-emblem-caption">
          <div class="hero-emblem-title"><?= esc($siteName) ?></div>
          <div class="hero-emblem-sub"><?= esc($accredLabel) ?></div>
        </div>
      </div>

      <?php if (! empty($heroStats)): ?>
        <div class="hero-stats">
          <?php foreach (array_slice($heroStats, 0, 3) as $stat): ?>
            <div class="hero-stat">
              <span class="hero-stat-num tabular-nums"><?= esc($stat['value'] ?? '0') ?></span>
              <span class="hero-stat-lbl"><?= esc($stat['label'] ?? '') ?></span>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <?php if ($contactHours !== ''): ?>
        <div class="hero-ledger-row">
          <span class="hero-ledger-key">Layanan</span>
          <span class="hero-ledger-val"><?= esc($contactHours) ?></span>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- 2. Ticker Info Band -->
<?php if (! empty($mergedNews) || ! empty($eventList)): ?>
  <div class="ticker" aria-label="Informasi terkini sekolah">
    <div class="wrap ticker-inner">
      <span class="badge badge-red ticker-label">
        <span class="badge-dot" aria-hidden="true"></span>
        <span>Info Terkini</span>
      </span>
      <div class="ticker-viewport">
        <div class="ticker-track" id="tickerTrack">
          <?php foreach ($mergedNews as $item): ?>
            <a class="ticker-item" href="<?= esc(base_url('news/' . ($item['slug'] ?? ''))) ?>">
              <span class="ticker-dot" aria-hidden="true"></span>
              <span><?= esc($item['title'] ?? '') ?></span>
            </a>
          <?php endforeach; ?>
          <?php foreach ($eventList as $ev): ?>
            <a class="ticker-item" href="<?= esc(base_url('agenda')) ?>">
              <span class="ticker-dot" aria-hidden="true"></span>
              <span>Agenda: <?= esc($ev['title'] ?? '') ?></span>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>

<!-- 3. Asymmetric Principal Editorial & Numbered Value Ledger -->
<section class="sec sec-white" id="profil">
  <div class="wrap">
    <div class="sec-head">
      <div class="sec-head-text">
        <div class="sec-index">01 / Profil &amp; Pimpinan</div>
        <h2 class="sec-title">Sambutan &amp; Karakter Sekolah</h2>
        <p class="sec-desc">Komitmen kami menghadirkan pendidikan yang disiplin, relevan, dan berlandaskan nilai integritas.</p>
      </div>
      <div class="sec-actions">
        <a class="btn btn-outline-primary btn-sm" href="<?= esc(base_url('profile')) ?>">Profil Lengkap</a>
      </div>
    </div>

    <div class="profile-grid">
      <article class="card principal-card">
        <div class="principal-portrait">
          <?php $pPhoto = $resolveMedia($principalData['photo'] ?? ''); ?>
          <?php if ($pPhoto !== ''): ?>
            <img src="<?= esc($pPhoto) ?>" alt="<?= esc($principalData['name'] ?? 'Kepala Sekolah') ?>" loading="lazy">
          <?php else: ?>
            <span><?= esc(mb_substr((string) ($principalData['name'] ?? 'KS'), 0, 2)) ?></span>
          <?php endif; ?>
        </div>
        <div class="principal-content">
          <span class="principal-role"><?= esc($principalData['role_title'] ?? ($principalData['role'] ?? 'Kepala Sekolah')) ?></span>
          <h3 class="principal-name"><?= esc($principalData['name'] ?? 'Kepala Sekolah') ?></h3>
          <?php if (! empty($principalData['education'])): ?>
            <div class="principal-edu"><?= esc($principalData['education']) ?></div>
          <?php endif; ?>
          <blockquote class="principal-quote">
            <?= esc($cleanText($principalData['quote'] ?? ($principalData['welcome_message'] ?? $siteDesc))) ?>
          </blockquote>
        </div>
      </article>

      <div class="pillar-list">
        <?php if (! empty($highlights)): ?>
          <?php foreach ($highlights as $idx => $hl): ?>
            <div class="pillar-item">
              <span class="pillar-num tabular-nums"><?= esc(str_pad((string) ($idx + 1), 2, '0', STR_PAD_LEFT)) ?></span>
              <div>
                <h3 class="pillar-title"><?= esc(is_string($hl) ? $cleanText($hl) : $cleanText($hl['title'] ?? '')) ?></h3>
                <?php if (is_array($hl) && ! empty($hl['desc'])): ?>
                  <p class="pillar-desc"><?= esc($cleanText($hl['desc'])) ?></p>
                <?php endif; ?>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="pillar-item">
            <span class="pillar-num tabular-nums">01</span>
            <div>
              <h3 class="pillar-title">Pembelajaran Berbasis Kompetensi</h3>
              <p class="pillar-desc">Kurikulum adaptif yang menyeimbangkan penguasaan teori, praktik laboratorium, dan kesiapan karier.</p>
            </div>
          </div>
          <div class="pillar-item">
            <span class="pillar-num tabular-nums">02</span>
            <div>
              <h3 class="pillar-title">Pembinaan Karakter &amp; Kedisiplinan</h3>
              <p class="pillar-desc">Menanamkan budaya kejujuran, tanggung jawab, dan etika profesional sejak hari pertama belajar.</p>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<!-- 4. Architectural Horizontal Metrics Bar -->
<?php if (! empty($counterStats)): ?>
  <section class="sec sec-subtle sec-compact" aria-label="Statistik Pendidikan">
    <div class="wrap">
      <div class="stats-strip">
        <?php foreach (array_slice($counterStats, 0, 4) as $cStat): ?>
          <div class="stats-strip-item">
            <div class="stats-strip-head">
              <div class="stat-val tabular-nums"><?= esc((string) ($cStat['number'] ?? '0') . (string) ($cStat['suffix'] ?? '')) ?></div>
              <?= lucide_icon((string) ($cStat['icon'] ?? 'bar-chart-2'), 'stats-strip-icon', 20) ?>
            </div>
            <div class="stat-label"><?= esc($cStat['label'] ?? '') ?></div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
<?php endif; ?>

<!-- 5. Academic Programs — Architectural 2-Column Matrix -->
<section class="sec sec-white" id="program">
  <div class="wrap">
    <div class="sec-head">
      <div class="sec-head-text">
        <div class="sec-index">02 / Akademik &amp; Layanan</div>
        <h2 class="sec-title">Program Unggulan Akademik</h2>
        <p class="sec-desc">Layanan pembelajaran terstruktur dan pembinaan kompetensi untuk membekali lulusan siap berkarya.</p>
      </div>
      <div class="sec-actions">
        <a class="btn btn-secondary btn-sm" href="<?= esc(base_url('program')) ?>">Semua Program</a>
      </div>
    </div>

    <?php if (! empty($programList)): ?>
      <div class="programs-matrix">
        <?php foreach ($programList as $pIdx => $prog): ?>
          <?= view(theme_view('layouts/program_card'), ['item' => $prog, 'index' => $pIdx]) ?>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="empty-state">
        <?= lucide_icon('book-open', '', 32) ?>
        <p>Data program akademik belum tersedia.</p>
      </div>
    <?php endif; ?>
  </div>
</section>

<!-- 6. News & Announcements — Featured Lead + Editorial Index Ledger -->
<section class="sec sec-subtle" id="berita">
  <div class="wrap">
    <div class="sec-head">
      <div class="sec-head-text">
        <div class="sec-index">03 / Publikasi Resmi</div>
        <h2 class="sec-title">Berita &amp; Pengumuman Terbaru</h2>
        <p class="sec-desc">Kabar resmi kegiatan akademik, prestasi warga sekolah, dan informasi penting dari <?= esc($siteName) ?>.</p>
      </div>
      <div class="sec-actions">
        <a class="btn btn-outline-primary btn-sm" href="<?= esc(base_url('news')) ?>">Arsip Berita</a>
      </div>
    </div>

    <?php if (! empty($leadStory)): ?>
      <?php
      $leadCat      = trim(explode(',', (string) ($leadStory['category'] ?? 'Berita'))[0] ?? 'Berita');
      $leadUrgent   = str_contains(mb_strtolower($leadCat), 'pengumuman') || str_contains(mb_strtolower($leadCat), 'spmb');
      $leadDateRaw  = (string) ($leadStory['published_at'] ?? ($leadStory['created_at'] ?? ''));
      $leadDateFmt  = $leadDateRaw !== '' ? date('d M Y', strtotime($leadDateRaw)) : '';
      $leadImg      = $resolveMedia($leadStory['image'] ?? '');
      $leadUrl      = base_url('news/' . ($leadStory['slug'] ?? ''));
      $leadExcerpt  = trim((string) ($leadStory['excerpt'] ?? ''));
      if ($leadExcerpt === '' && ! empty($leadStory['content'])) {
          $plain = trim(strip_tags((string) $leadStory['content']));
          $leadExcerpt = mb_strlen($plain) > 190 ? mb_substr($plain, 0, 187) . '...' : $plain;
      }
      ?>
      <div class="news-editorial">
        <article class="news-lead-card">
          <a class="news-lead-media" href="<?= esc($leadUrl) ?>">
            <?php if ($leadImg !== ''): ?>
              <img src="<?= esc($leadImg) ?>" alt="<?= esc($leadStory['title'] ?? 'Berita utama') ?>" loading="lazy">
            <?php else: ?>
              <div class="news-thumb-fallback">
                <?= lucide_icon('newspaper', '', 36) ?>
                <span><?= esc($leadCat) ?></span>
              </div>
            <?php endif; ?>
          </a>
          <div class="news-lead-body">
            <div class="news-meta">
              <span class="badge <?= $leadUrgent ? 'badge-red' : 'badge-blue' ?>">
                <?php if ($leadUrgent): ?><span class="badge-dot" aria-hidden="true"></span><?php endif; ?>
                <?= esc($leadCat) ?>
              </span>
              <?php if ($leadDateFmt !== ''): ?>
                <time class="tabular-nums" datetime="<?= esc($leadDateRaw) ?>"><?= esc($leadDateFmt) ?></time>
              <?php endif; ?>
            </div>
            <h3 class="news-lead-title">
              <a href="<?= esc($leadUrl) ?>"><?= esc($leadStory['title'] ?? '') ?></a>
            </h3>
            <?php if ($leadExcerpt !== ''): ?>
              <p class="news-excerpt"><?= esc($leadExcerpt) ?></p>
            <?php endif; ?>
            <div class="info-card-foot">
              <a class="text-link is-blue" href="<?= esc($leadUrl) ?>">
                <span>Baca Artikel Utama</span>
                <?= lucide_icon('arrow-right', '', 15) ?>
              </a>
            </div>
          </div>
        </article>

        <aside class="news-index-ledger" aria-label="Daftar kabar lainnya">
          <div class="news-index-head">
            <span>Indeks Berita &amp; Agenda</span>
            <span class="tabular-nums"><?= esc((string) count($mergedNews)) ?> Rilis</span>
          </div>
          <?php if (! empty($indexStories)): ?>
            <?php foreach ($indexStories as $story): ?>
              <?php
              $sCat     = trim(explode(',', (string) ($story['category'] ?? 'Berita'))[0] ?? 'Berita');
              $sUrgent  = str_contains(mb_strtolower($sCat), 'pengumuman') || str_contains(mb_strtolower($sCat), 'spmb');
              $sDateRaw = (string) ($story['published_at'] ?? ($story['created_at'] ?? ''));
              $sDateFmt = $sDateRaw !== '' ? date('d M Y', strtotime($sDateRaw)) : '';
              ?>
              <a class="news-index-item" href="<?= esc(base_url('news/' . ($story['slug'] ?? ''))) ?>">
                <div class="news-meta" style="margin-bottom: 0;">
                  <span class="badge <?= $sUrgent ? 'badge-red' : 'badge-blue' ?>"><?= esc($sCat) ?></span>
                  <?php if ($sDateFmt !== ''): ?>
                    <time class="tabular-nums" datetime="<?= esc($sDateRaw) ?>"><?= esc($sDateFmt) ?></time>
                  <?php endif; ?>
                </div>
                <span class="news-index-title"><?= esc($story['title'] ?? '') ?></span>
              </a>
            <?php endforeach; ?>
          <?php endif; ?>
          <?php foreach (array_slice($eventList, 0, 2) as $evItem): ?>
            <a class="news-index-item" href="<?= esc(base_url('agenda')) ?>">
              <div class="news-meta" style="margin-bottom: 0;">
                <span class="badge badge-amber">Agenda</span>
                <?php if (! empty($evItem['event_date'])): ?>
                  <time class="tabular-nums"><?= esc(date('d M Y', strtotime((string) $evItem['event_date']))) ?></time>
                <?php endif; ?>
              </div>
              <span class="news-index-title"><?= esc($evItem['title'] ?? '') ?></span>
            </a>
          <?php endforeach; ?>
        </aside>
      </div>
    <?php else: ?>
      <div class="empty-state">
        <?= lucide_icon('newspaper', '', 32) ?>
        <p>Belum ada berita terbaru yang dipublikasikan.</p>
      </div>
    <?php endif; ?>
  </div>
</section>

<!-- 7. Student Achievements — Structured Tabular Ledger -->
<section class="sec sec-white" id="prestasi">
  <div class="wrap">
    <div class="sec-head">
      <div class="sec-head-text">
        <div class="sec-index">04 / Rekam Jejak Prestasi</div>
        <h2 class="sec-title">Prestasi &amp; Penghargaan Siswa</h2>
        <p class="sec-desc">Capaian akademik maupun non-akademik yang diraih siswa pada tingkat kota, provinsi, hingga nasional.</p>
      </div>
      <div class="sec-actions">
        <a class="btn btn-outline-primary btn-sm" href="<?= esc(base_url('achievements')) ?>">Lihat Semua Prestasi</a>
      </div>
    </div>

    <?php if (! empty($achList)): ?>
      <div class="ach-ledger">
        <div class="ach-ledger-head" aria-hidden="true">
          <span>Tahun</span>
          <span>Peringkat</span>
          <span>Kejuaraan / Bidang</span>
          <span>Penerima</span>
          <span>Tingkat</span>
        </div>
        <?php foreach ($achList as $ach): ?>
          <?= view(theme_view('layouts/ach_card'), ['item' => $ach, 'as_row' => true]) ?>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="empty-state">
        <?= lucide_icon('trophy', '', 32) ?>
        <p>Belum ada data prestasi yang ditampilkan.</p>
      </div>
    <?php endif; ?>
  </div>
</section>

<!-- 8. Agenda & Testimonials Split -->
<section class="sec sec-subtle" id="agenda">
  <div class="wrap split-grid">
    <div>
      <div class="sec-head" style="margin-bottom: var(--space-5);">
        <div class="sec-head-text">
          <div class="sec-index">05 / Kalender Kegiatan</div>
          <h2 class="sec-title">Agenda Sekolah</h2>
        </div>
        <div class="sec-actions">
          <a class="text-link is-blue" href="<?= esc(base_url('agenda')) ?>">Kalender Lengkap <?= lucide_icon('arrow-right', '', 15) ?></a>
        </div>
      </div>

      <?php if (! empty($eventList)): ?>
        <div class="event-list">
          <?php foreach ($eventList as $ev): ?>
            <?php
            $ts    = strtotime((string) ($ev['event_date'] ?? 'now'));
            $day   = $ts ? date('d', $ts) : '--';
            $month = $ts ? ($monthNames[(int) date('n', $ts)] ?? date('M', $ts)) : 'BLN';
            ?>
            <article class="event-row">
              <div class="event-date" aria-label="<?= esc($ev['event_date'] ?? '') ?>">
                <span class="event-day tabular-nums"><?= esc($day) ?></span>
                <span class="event-month"><?= esc($month) ?></span>
              </div>
              <div class="event-info">
                <h3 class="event-title"><?= esc($ev['title'] ?? 'Kegiatan') ?></h3>
                <div class="event-sub">
                  <?php if (! empty($ev['event_time'])): ?>
                    <span><?= lucide_icon('clock', '', 14) ?> <?= esc($ev['event_time']) ?></span>
                  <?php endif; ?>
                  <?php if (! empty($ev['location'])): ?>
                    <span><?= lucide_icon('map-pin', '', 14) ?> <?= esc($ev['location']) ?></span>
                  <?php endif; ?>
                </div>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="empty-state">
          <p>Belum ada agenda terdekat.</p>
        </div>
      <?php endif; ?>
    </div>

    <div>
      <div class="sec-head" style="margin-bottom: var(--space-5);">
        <div class="sec-head-text">
          <div class="sec-index">06 / Suara Komunitas</div>
          <h2 class="sec-title">Testimoni</h2>
        </div>
        <div class="sec-actions">
          <a class="text-link is-blue" href="<?= esc(base_url('testimonials')) ?>">Semua Testimoni <?= lucide_icon('arrow-right', '', 15) ?></a>
        </div>
      </div>

      <?php if (! empty($testiList)): ?>
        <div class="event-list">
          <?php foreach ($testiList as $t): ?>
            <?php $tPhoto = $resolveMedia($t['photo'] ?? ''); ?>
            <article class="quote-card" style="background-color: var(--theme_color_surface_canvas);">
              <?= lucide_icon('quote', 'quote-mark', 18) ?>
              <p class="quote-body">"<?= esc($cleanText($t['quote'] ?? '')) ?>"</p>
              <div class="quote-author">
                <div class="quote-avatar">
                  <?php if ($tPhoto !== ''): ?>
                    <img src="<?= esc($tPhoto) ?>" alt="<?= esc($t['name'] ?? '') ?>" loading="lazy">
                  <?php else: ?>
                    <span><?= esc(mb_substr((string) ($t['name'] ?? 'A'), 0, 2)) ?></span>
                  <?php endif; ?>
                </div>
                <div>
                  <div class="quote-name"><?= esc($cleanText($t['name'] ?? '')) ?></div>
                  <?php if (! empty($t['role'])): ?>
                    <div class="quote-role"><?= esc($cleanText($t['role'])) ?></div>
                  <?php endif; ?>
                </div>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- 9. Gallery Preview -->
<?php if (! empty($galleryList)): ?>
  <section class="sec sec-white" id="galeri">
    <div class="wrap">
      <div class="sec-head">
        <div class="sec-head-text">
          <div class="sec-index">07 / Dokumentasi Visual</div>
          <h2 class="sec-title">Galeri Kegiatan Sekolah</h2>
          <p class="sec-desc">Potret aktivitas pembelajaran, kegiatan kesiswaan, dan fasilitas di <?= esc($siteName) ?>.</p>
        </div>
        <div class="sec-actions">
          <a class="btn btn-secondary btn-sm" href="<?= esc(base_url('gallery')) ?>">Buka Galeri Penuh</a>
        </div>
      </div>

      <div class="grid-3">
        <?php foreach ($galleryList as $gal): ?>
          <?php
          $gImg     = $resolveMedia($gal['image'] ?? '');
          $gCaption = $cleanText($gal['caption'] ?? 'Dokumentasi kegiatan') ?: 'Dokumentasi kegiatan';
          $gCat     = $cleanText($gal['category'] ?? 'Umum') ?: 'Umum';
          ?>
          <button type="button"
                  class="gallery-card"
                  data-lightbox-src="<?= esc($gImg) ?>"
                  data-lightbox-title="<?= esc($gCaption) ?>">
            <span class="gallery-media">
              <img src="<?= esc($gImg) ?>" alt="<?= esc($gCaption) ?>" loading="lazy">
            </span>
            <span class="gallery-caption">
              <span class="gallery-title"><?= esc($gCaption) ?></span>
              <span class="badge badge-blue"><?= esc($gCat) ?></span>
            </span>
          </button>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
<?php endif; ?>

<!-- 10. SPMB Pathway -->
<?php if (! empty($spmbSteps)): ?>
  <section class="sec sec-blue-soft" id="spmb">
    <div class="wrap">
      <div class="sec-head">
        <div class="sec-head-text">
          <div class="sec-index">08 / Penerimaan Siswa Baru</div>
          <h2 class="sec-title">Alur Pendaftaran SPMB</h2>
          <p class="sec-desc">Tahapan pendaftaran calon peserta didik baru secara terstruktur, jelas, dan transparan.</p>
        </div>
        <div class="sec-actions">
          <a class="btn btn-white btn-sm" href="<?= esc(base_url('spmb#cek-status')) ?>">Cek Status Pendaftaran</a>
          <a class="btn btn-white btn-sm" href="<?= esc(base_url('downloads')) ?>">Unduh Panduan</a>
        </div>
      </div>

      <ol class="spmb-roadmap">
        <?php foreach ($spmbSteps as $idx => $step): ?>
          <li class="spmb-roadmap-item">
            <span class="spmb-roadmap-num tabular-nums"><?= esc(str_pad((string) ($idx + 1), 2, '0', STR_PAD_LEFT)) ?></span>
            <div>
              <h3 class="step-title"><?= esc($cleanText($step['title'] ?? '')) ?></h3>
              <?php if (! empty($step['desc'])): ?>
                <p class="step-desc"><?= esc($cleanText($step['desc'])) ?></p>
              <?php endif; ?>
            </div>
          </li>
        <?php endforeach; ?>
      </ol>

      <div class="spmb-cta-box">
        <div>
          <h3>Daftar SPMB Online &amp; Unggah Berkas Langsung</h3>
          <p>Isi data calon peserta didik, unggah berkas persyaratan, dan terima konfirmasi verifikasi via WhatsApp panitia <?= esc($siteName) ?>.</p>
        </div>
        <div class="sec-actions">
          <a class="btn btn-primary" href="<?= esc(base_url('spmb')) ?>">
            <span>Daftar SPMB Online Sekarang</span>
            <?= lucide_icon('arrow-right', '', 15) ?>
          </a>
          <a class="btn btn-secondary" href="<?= esc(base_url('spmb#cek-status')) ?>">Cek Status Pendaftaran</a>
          <?php if ($spmbUrl !== '' && $spmbUrl !== '#'): ?>
            <a class="btn btn-ghost" href="<?= esc($spmbUrl) ?>" target="_blank" rel="noopener noreferrer">
              <span>Portal Eksternal</span>
              <?= lucide_icon('external-link', '', 15) ?>
            </a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </section>
<?php endif; ?>

<!-- 11. Quick Contact Section -->
<section class="sec sec-white" id="kontak">
  <div class="wrap">
    <div class="sec-head">
      <div class="sec-head-text">
        <div class="sec-index">09 / Layanan Informasi</div>
        <h2 class="sec-title">Hubungi <?= esc($siteName) ?></h2>
        <p class="sec-desc">Kirim pertanyaan Anda langsung atau kunjungi sekolah pada jam pelayanan resmi.</p>
      </div>
      <div class="sec-actions">
        <a class="btn btn-ghost btn-sm" href="<?= esc(base_url('contact')) ?>">Halaman Kontak &amp; Peta</a>
      </div>
    </div>

    <div class="contact-grid">
      <dl class="contact-dl">
        <?php if ($contactAddr !== ''): ?>
          <div class="contact-dl-item">
            <dt class="contact-dl-term"><?= lucide_icon('map-pin', '', 15) ?><span>Alamat Sekolah</span></dt>
            <dd class="contact-dl-val"><?= esc($contactAddr) ?></dd>
          </div>
        <?php endif; ?>
        <?php if ($contactPhone !== ''): ?>
          <div class="contact-dl-item">
            <dt class="contact-dl-term"><?= lucide_icon('phone', '', 15) ?><span>Telepon</span></dt>
            <dd class="contact-dl-val"><a class="text-link is-blue tabular-nums" href="tel:<?= esc(preg_replace('/[^0-9+]/', '', $contactPhone)) ?>"><?= esc($contactPhone) ?></a></dd>
          </div>
        <?php endif; ?>
        <?php if ($contactEmail !== ''): ?>
          <div class="contact-dl-item">
            <dt class="contact-dl-term"><?= lucide_icon('mail', '', 15) ?><span>Email Resmi</span></dt>
            <dd class="contact-dl-val"><a class="text-link is-blue" href="mailto:<?= esc($contactEmail) ?>"><?= esc($contactEmail) ?></a></dd>
          </div>
        <?php endif; ?>
        <?php if ($contactHours !== ''): ?>
          <div class="contact-dl-item">
            <dt class="contact-dl-term"><?= lucide_icon('clock', '', 15) ?><span>Jam Operasional</span></dt>
            <dd class="contact-dl-val"><?= esc($contactHours) ?></dd>
          </div>
        <?php endif; ?>
      </dl>

      <div class="card">
        <h3 class="sec-title" style="font-size: 1.25rem; margin-bottom: var(--space-4);">Kirim Pesan</h3>
        <div id="contactFeedback" class="alert hidden" role="status"></div>
        <form id="contactForm" action="<?= esc(base_url('contact/send')) ?>" method="post">
          <?= csrf_field() ?>
          <div class="hp-field" aria-hidden="true">
            <label for="homeWebsiteHp">Website</label>
            <input type="text" id="homeWebsiteHp" name="website" tabindex="-1" autocomplete="off">
          </div>
          <div class="form-group">
            <label class="form-label" for="homeName">Nama Lengkap</label>
            <input class="form-control" type="text" id="homeName" name="name" required placeholder="Nama Anda">
          </div>
          <div class="form-group">
            <label class="form-label" for="homeEmail">Alamat Email</label>
            <input class="form-control" type="email" id="homeEmail" name="email" required placeholder="email@contoh.com">
          </div>
          <div class="form-group">
            <label class="form-label" for="homeSubject">Subjek</label>
            <input class="form-control" type="text" id="homeSubject" name="subject" required placeholder="Informasi Pendaftaran / Umum">
          </div>
          <div class="form-group">
            <label class="form-label" for="homeMsg">Pesan</label>
            <textarea class="form-control" id="homeMsg" name="message" required minlength="10" maxlength="5000" placeholder="Tuliskan pertanyaan atau pesan Anda..."></textarea>
          </div>
          <button type="submit" class="btn btn-primary">
            <?= lucide_icon('send', '', 16) ?>
            <span>Kirim Pesan</span>
          </button>
        </form>
      </div>
    </div>
  </div>
</section>

<?= view(theme_view('layouts/footer')) ?>
