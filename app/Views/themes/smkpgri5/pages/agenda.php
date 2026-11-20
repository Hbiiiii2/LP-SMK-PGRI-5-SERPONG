<?= view(theme_view('layouts/header')) ?>

<?php
$list = $events ?? [];
$monthNames = [
    1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun',
    7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des',
];
$statusLabels = [
    'upcoming'  => ['label' => 'Akan Datang', 'class' => 'badge-green'],
    'ongoing'   => ['label' => 'Berlangsung', 'class' => 'badge-blue'],
    'completed' => ['label' => 'Selesai', 'class' => ''],
    'cancelled' => ['label' => 'Dibatalkan', 'class' => 'badge-red'],
];
?>

<?= view(theme_view('layouts/page_hero'), [
    'kicker'  => 'Kalender Resmi',
    'heading' => 'Agenda & Kegiatan Sekolah',
    'lead'    => 'Jadwal pelaksanaan kegiatan akademik, asesmen, penerimaan siswa, dan agenda besar sekolah.',
    'crumbs'  => [['label' => 'Agenda']],
]) ?>

<section class="sec sec-white">
  <div class="wrap-narrow">
    <?php if (! empty($list)): ?>
      <div class="event-list">
        <?php foreach ($list as $ev): ?>
          <?php
          $ts     = strtotime((string) ($ev['event_date'] ?? 'now'));
          $day    = $ts ? date('d', $ts) : '--';
          $month  = $ts ? ($monthNames[(int) date('n', $ts)] ?? date('M', $ts)) : 'BLN';
          $stKey  = strtolower((string) ($ev['status'] ?? 'upcoming'));
          $stMeta = $statusLabels[$stKey] ?? ['label' => 'Terjadwal', 'class' => 'badge-blue'];
          ?>
          <article class="event-row">
            <div class="event-date">
              <span class="event-day tabular-nums"><?= esc($day) ?></span>
              <span class="event-month"><?= esc($month) ?></span>
            </div>
            <div class="event-info">
              <div class="sec-actions" style="justify-content: space-between; margin-bottom: 0.35rem;">
                <h2 class="event-title" style="margin-bottom: 0;"><?= esc($ev['title'] ?? '') ?></h2>
                <span class="badge <?= esc($stMeta['class']) ?>"><?= esc($stMeta['label']) ?></span>
              </div>
              <?php if (! empty($ev['description'])): ?>
                <p class="pillar-desc" style="margin-bottom: var(--space-2);"><?= esc($ev['description']) ?></p>
              <?php endif; ?>
              <div class="event-sub tabular-nums">
                <?php if (! empty($ev['event_date'])): ?>
                  <span><?= lucide_icon('calendar', '', 14) ?> <?= esc(date('d M Y', $ts ?: time())) ?></span>
                <?php endif; ?>
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
        <?= lucide_icon('calendar', '', 32) ?>
        <p>Belum ada agenda kegiatan yang terjadwal.</p>
      </div>
    <?php endif; ?>
  </div>
</section>

<?= view(theme_view('layouts/footer')) ?>
