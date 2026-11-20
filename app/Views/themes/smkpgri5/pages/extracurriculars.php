<?= view(theme_view('layouts/header')) ?>

<?php $list = $extracurriculars ?? []; ?>

<?= view(theme_view('layouts/page_hero'), [
    'kicker'  => 'Pengembangan Diri',
    'heading' => 'Ekstrakurikuler Siswa',
    'lead'    => 'Wadah penyaluran minat, bakat, kepemimpinan, serta kedisiplinan siswa di luar jam pelajaran inti.',
    'crumbs'  => [['label' => 'Ekstrakurikuler']],
]) ?>

<section class="sec sec-white">
  <div class="wrap">
    <?php if (! empty($list)): ?>
      <div class="programs-matrix">
        <?php foreach ($list as $idx => $eks): ?>
          <article class="prog-matrix-card">
            <div class="prog-matrix-top">
              <span class="prog-icon-inline">
                <?= lucide_icon((string) ($eks['icon'] ?? 'star'), '', 20) ?>
              </span>
              <span class="prog-code tabular-nums">EKS-<?= esc(str_pad((string) ($idx + 1), 2, '0', STR_PAD_LEFT)) ?></span>
            </div>
            <h2 class="info-card-title"><?= esc($eks['title'] ?? '') ?></h2>
            <?php if (! empty($eks['description'])): ?>
              <p class="info-card-desc" style="margin-top: 0.5rem; margin-bottom: 0;"><?= esc($eks['description']) ?></p>
            <?php endif; ?>
          </article>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="empty-state">
        <?= lucide_icon('trophy', '', 32) ?>
        <p>Belum ada data ekstrakurikuler yang tersedia.</p>
      </div>
    <?php endif; ?>
  </div>
</section>

<?= view(theme_view('layouts/footer')) ?>
