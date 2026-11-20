<?php
$item        = $item ?? [];
$level       = trim((string) ($item['level'] ?? 'Sekolah'));
$medal       = strtolower(trim((string) ($item['medal'] ?? 'gold')));
$year        = trim((string) ($item['year'] ?? ''));
$title       = trim((string) ($item['achievement'] ?? ($item['title'] ?? 'Prestasi Siswa')));
$studentName = trim((string) ($item['student_name'] ?? ''));
$className   = trim((string) ($item['class_name'] ?? ''));

$medalLabelMap = [
    'gold'   => 'Medali Emas',
    'silver' => 'Medali Perak',
    'bronze' => 'Medali Perunggu',
];
$medalLabel = $medalLabelMap[$medal] ?? 'Penghargaan';
$levelKey   = mb_strtolower($level);
$rowMode    = ! empty($as_row);
?>
<?php if ($rowMode): ?>
<article class="ach-row" data-filter-value="<?= esc($levelKey) ?>">
  <div class="ach-year tabular-nums"><?= esc($year !== '' ? $year : '—') ?></div>
  <div>
    <span class="badge badge-amber">
      <?= lucide_icon('trophy', '', 13) ?>
      <span><?= esc($medalLabel) ?></span>
    </span>
  </div>
  <div>
    <h3 class="ach-title"><?= esc($title) ?></h3>
  </div>
  <div class="ach-meta">
    <?php if ($studentName !== ''): ?>
      <span><?= esc($studentName) ?><?= $className !== '' ? ' · ' . esc($className) : '' ?></span>
    <?php else: ?>
      <span><?= esc($className !== '' ? $className : 'Peserta Didik') ?></span>
    <?php endif; ?>
  </div>
  <div>
    <span class="badge badge-blue"><?= esc($level !== '' ? $level : 'Sekolah') ?></span>
  </div>
</article>
<?php else: ?>
<article class="ach-card" data-filter-value="<?= esc($levelKey) ?>">
  <div class="ach-top">
    <span class="ach-medal">
      <?= lucide_icon('trophy', 'ach-trophy', 15) ?>
      <span><?= esc($medalLabel) ?></span>
      <span aria-hidden="true">&middot;</span>
      <span><?= esc($level !== '' ? $level : 'Sekolah') ?></span>
    </span>
    <?php if ($year !== ''): ?>
      <span class="ach-year tabular-nums"><?= esc($year) ?></span>
    <?php endif; ?>
  </div>

  <h3 class="ach-title"><?= esc($title) ?></h3>

  <?php if ($studentName !== '' || $className !== ''): ?>
    <div class="ach-meta">
      <?php if ($studentName !== ''): ?>
        <span><strong>Penerima:</strong> <?= esc($studentName) ?></span>
      <?php endif; ?>
      <?php if ($className !== ''): ?>
        <span><strong>Kelas / Tim:</strong> <?= esc($className) ?></span>
      <?php endif; ?>
    </div>
  <?php endif; ?>
</article>
<?php endif; ?>
