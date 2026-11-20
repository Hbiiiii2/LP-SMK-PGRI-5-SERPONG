<?= view(theme_view('layouts/header')) ?>

<?php
$list   = $achievements ?? [];
$levels = [];
foreach ($list as $item) {
    $lvl = trim((string) ($item['level'] ?? ''));
    if ($lvl !== '' && ! in_array($lvl, $levels, true)) {
        $levels[] = $lvl;
    }
}
?>

<?= view(theme_view('layouts/page_hero'), [
    'kicker'  => 'Rekam Jejak Prestasi',
    'heading' => 'Prestasi & Penghargaan Siswa',
    'lead'    => 'Dokumentasi raihan kejuaraan akademik, kompetensi, olahraga, dan seni peserta didik.',
    'crumbs'  => [['label' => 'Prestasi']],
]) ?>

<section class="sec sec-white">
  <div class="wrap">
    <?php if (! empty($levels)): ?>
      <div class="news-toolbar">
        <div class="filter-tabs" data-filter-group="achievements" role="group" aria-label="Filter tingkat prestasi">
          <button type="button" class="filter-btn active" data-filter="semua" aria-pressed="true">Semua Tingkat</button>
          <?php foreach ($levels as $lvl): ?>
            <button type="button" class="filter-btn" data-filter="<?= esc(mb_strtolower($lvl)) ?>" aria-pressed="false"><?= esc($lvl) ?></button>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>

    <?php if (! empty($list)): ?>
      <div class="ach-ledger" data-filter-list="achievements">
        <div class="ach-ledger-head" aria-hidden="true">
          <span>Tahun</span>
          <span>Peringkat</span>
          <span>Kejuaraan / Bidang</span>
          <span>Penerima</span>
          <span>Tingkat</span>
        </div>
        <?php foreach ($list as $ach): ?>
          <?= view(theme_view('layouts/ach_card'), ['item' => $ach, 'as_row' => true]) ?>
        <?php endforeach; ?>
      </div>
      <div class="empty-state hidden" data-filter-empty="achievements" style="margin-top: var(--space-5);">
        <?= lucide_icon('trophy', '', 32) ?>
        <p>Tidak ada prestasi untuk filter tingkat ini.</p>
      </div>
    <?php else: ?>
      <div class="empty-state">
        <?= lucide_icon('trophy', '', 32) ?>
        <p>Belum ada data prestasi yang dipublikasikan.</p>
      </div>
    <?php endif; ?>
  </div>
</section>

<?= view(theme_view('layouts/footer')) ?>
