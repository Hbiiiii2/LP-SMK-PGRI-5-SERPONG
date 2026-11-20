<?= view(theme_view('layouts/header')) ?>

<?php
helper('theme');
$settings = $settings ?? [];
$siteName = trim((string) ($site_name ?? ($settings['site_name'] ?? 'SMK PGRI 5 Serpong'))) ?: 'SMK PGRI 5 Serpong';
$prog     = $program ?? [];
$allProgs = $programs ?? [];

$progId   = (int) ($prog['id'] ?? 0);
$rawIcon  = trim((string) ($prog['icon'] ?? 'graduation-cap')) ?: 'graduation-cap';
$title    = trim((string) ($prog['title'] ?? 'Program Keahlian'));
$desc     = trim((string) ($prog['description'] ?? ''));

$codeIdx = 1;
foreach ($allProgs as $idx => $row) {
    if ((int) ($row['id'] ?? 0) === $progId) {
        $codeIdx = $idx + 1;
        break;
    }
}
$codeBadge   = 'PRG-' . str_pad((string) $codeIdx, 2, '0', STR_PAD_LEFT);
$payload     = function_exists('program_detail_payload') ? program_detail_payload($prog) : [];
$imgUrl      = (string) ($payload['image_url'] ?? '');
$learning    = $payload['learning_points'] ?? [];
$careers     = $payload['career_prospects'] ?? [];
$contentHtml = (string) ($payload['content_html'] ?? '');
?>

<?= view(theme_view('layouts/page_hero'), [
    'kicker'  => 'Program Keahlian · ' . $codeBadge,
    'heading' => $title,
    'lead'    => $desc !== '' ? $desc : ('Fokus materi pembelajaran, praktik kejuruan, dan prospek lulusan ' . $title . ' di ' . $siteName . '.'),
    'crumbs'  => [
        ['label' => 'Program Keahlian', 'url' => base_url('program')],
        ['label' => $title],
    ],
]) ?>

<section class="sec sec-white">
  <div class="wrap">
    <div class="prog-detail-grid">
      <div class="prog-detail-main">
        <?php if ($imgUrl !== ''): ?>
          <figure class="prog-detail-photo">
            <img src="<?= esc($imgUrl) ?>" alt="Foto kegiatan <?= esc($title) ?>">
          </figure>
        <?php endif; ?>

        <?php if (! empty($learning)): ?>
          <section class="prog-detail-section">
            <div class="sec-head" style="margin-bottom: var(--space-5);">
              <div class="sec-head-text">
                <div class="sec-index">01 / Kurikulum &amp; Kompetensi</div>
                <h2 class="sec-title" style="font-size: clamp(1.375rem, 2.4vw, 1.75rem);">Apa Saja yang Dipelajari?</h2>
                <p class="sec-desc">Materi inti dan keterampilan praktik yang dibina secara bertahap bersama guru kejuruan dan instruktur praktik.</p>
              </div>
            </div>

            <div class="prog-module-list">
              <?php foreach ($learning as $mIdx => $point): ?>
                <div class="prog-module-item">
                  <span class="prog-module-num tabular-nums"><?= esc(str_pad((string) ($mIdx + 1), 2, '0', STR_PAD_LEFT)) ?></span>
                  <span class="prog-module-text"><?= esc($point) ?></span>
                </div>
              <?php endforeach; ?>
            </div>
          </section>
        <?php endif; ?>

        <?php if ($contentHtml !== ''): ?>
          <section class="prog-detail-section">
            <div class="sec-head" style="margin-bottom: var(--space-4);">
              <div class="sec-head-text">
                <div class="sec-index">02 / Pendekatan Belajar</div>
                <h2 class="sec-title" style="font-size: clamp(1.375rem, 2.4vw, 1.75rem);">Gambaran Pembelajaran &amp; Praktik</h2>
              </div>
            </div>
            <div class="prog-prose card" style="padding: var(--space-6);">
              <?= $contentHtml ?>
            </div>
          </section>
        <?php endif; ?>

        <?php if (! empty($careers)): ?>
          <section class="prog-detail-section">
            <div class="sec-head" style="margin-bottom: var(--space-4);">
              <div class="sec-head-text">
                <div class="sec-index">03 / Peluang Masa Depan</div>
                <h2 class="sec-title" style="font-size: clamp(1.375rem, 2.4vw, 1.75rem);">Prospek Lulusan &amp; Karier</h2>
              </div>
            </div>
            <div class="prog-career-grid">
              <?php foreach ($careers as $career): ?>
                <div class="prog-career-card">
                  <span class="prog-career-ico"><?= lucide_icon('briefcase', '', 16) ?></span>
                  <span><?= esc($career) ?></span>
                </div>
              <?php endforeach; ?>
            </div>
          </section>
        <?php endif; ?>
      </div>

      <aside class="prog-detail-aside">
        <div class="card prog-summary-box">
          <div class="prog-matrix-top">
            <?php if ($imgUrl !== ''): ?>
              <span class="prog-media-thumb is-lg">
                <img src="<?= esc($imgUrl) ?>" alt="<?= esc($title) ?>">
              </span>
            <?php else: ?>
              <span class="prog-icon-inline is-lg"><?= lucide_icon($rawIcon, '', 24) ?></span>
            <?php endif; ?>
            <span class="prog-code tabular-nums"><?= esc($codeBadge) ?></span>
          </div>

          <h2 class="prog-summary-title"><?= esc($title) ?></h2>
          <?php if ($desc !== ''): ?>
            <p class="prog-summary-desc"><?= esc($desc) ?></p>
          <?php endif; ?>

          <dl class="prog-summary-meta">
            <div>
              <dt>Fokus Materi</dt>
              <dd class="tabular-nums"><?= count($learning) ?> Topik Inti</dd>
            </div>
            <div>
              <dt>Prospek Lulusan</dt>
              <dd class="tabular-nums"><?= count($careers) ?> Jalur Karier</dd>
            </div>
          </dl>

          <div class="prog-summary-actions">
            <a class="btn btn-primary" href="<?= esc(base_url('spmb?jurusan=' . urlencode((string) ($prog['slug'] ?? $title)))) ?>">
              <span>Daftar SPMB Jurusan Ini</span>
              <?= lucide_icon('arrow-right', '', 16) ?>
            </a>
            <a class="btn btn-secondary" href="<?= esc(base_url('contact')) ?>">
              <?= lucide_icon('message-circle', '', 16) ?>
              <span>Konsultasi Orang Tua</span>
            </a>
            <a class="text-link is-blue" href="<?= esc(base_url('program')) ?>" style="justify-content: center; padding-top: 0.25rem;">
              <?= lucide_icon('arrow-left', '', 15) ?>
              <span>Lihat Semua Program</span>
            </a>
          </div>
        </div>

        <?php if (count($allProgs) > 1): ?>
          <div class="card prog-switcher-box">
            <div class="prog-block-label" style="margin-bottom: var(--space-3);">
              <?= lucide_icon('compass', '', 15) ?>
              <span>Program &amp; Jurusan Lainnya</span>
            </div>
            <div class="prog-switcher-list">
              <?php foreach ($allProgs as $idx => $other): ?>
                <?php
                $isCurrent = ((int) ($other['id'] ?? 0) === $progId);
                $otherIcon = trim((string) ($other['icon'] ?? 'graduation-cap')) ?: 'graduation-cap';
                $otherImg  = function_exists('program_image_url') ? program_image_url($other) : '';
                $otherHref = function_exists('program_link') ? program_link($other, 'program') : base_url('program');
                ?>
                <a class="prog-switcher-link<?= $isCurrent ? ' active' : '' ?>" href="<?= esc($otherHref) ?>">
                  <?php if ($otherImg !== ''): ?>
                    <span class="prog-tab-thumb"><img src="<?= esc($otherImg) ?>" alt="" loading="lazy"></span>
                  <?php else: ?>
                    <span class="prog-tab-icon"><?= lucide_icon($otherIcon, '', 15) ?></span>
                  <?php endif; ?>
                  <span class="prog-switcher-name"><?= esc($other['title'] ?? '') ?></span>
                  <?= lucide_icon('chevron-right', '', 14) ?>
                </a>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endif; ?>
      </aside>
    </div>
  </div>
</section>

<?= view(theme_view('layouts/footer')) ?>
