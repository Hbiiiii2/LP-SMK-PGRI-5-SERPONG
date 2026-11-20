<?php
$kicker  = trim((string) ($kicker ?? ''));
$heading = trim((string) ($heading ?? 'Halaman'));
$lead    = trim((string) ($lead ?? ''));
$crumbs  = $crumbs ?? [['label' => $heading]];
?>
<section class="page-hero">
  <div class="wrap">
    <nav class="breadcrumbs" aria-label="Breadcrumb">
      <a href="<?= esc(base_url('/')) ?>">Beranda</a>
      <?php foreach ($crumbs as $crumb): ?>
        <span class="crumb-sep" aria-hidden="true">/</span>
        <?php if (! empty($crumb['url'])): ?>
          <a href="<?= esc($crumb['url']) ?>"><?= esc($crumb['label'] ?? '') ?></a>
        <?php else: ?>
          <span aria-current="page"><?= esc($crumb['label'] ?? '') ?></span>
        <?php endif; ?>
      <?php endforeach; ?>
    </nav>

    <?php if ($kicker !== ''): ?>
      <div class="page-hero-kicker"><?= esc($kicker) ?></div>
    <?php endif; ?>

    <h1 class="page-hero-title"><?= esc($heading) ?></h1>

    <?php if ($lead !== ''): ?>
      <p class="page-hero-lead"><?= esc($lead) ?></p>
    <?php endif; ?>
  </div>
</section>
