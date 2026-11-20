<?= view(theme_view('layouts/header')) ?>

<?php
$settings = $settings ?? [];
$siteName = trim((string) ($site_name ?? ($settings['site_name'] ?? 'SMK PGRI 5 Serpong'))) ?: 'SMK PGRI 5 Serpong';
$list     = $teachers ?? [];

$initials = static function (string $name): string {
    $parts = preg_split('/\s+/', trim($name)) ?: [];
    $letters = '';
    foreach (array_slice($parts, 0, 2) as $p) {
        $letters .= mb_strtoupper(mb_substr($p, 0, 1));
    }
    return $letters !== '' ? $letters : 'GR';
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
?>

<?= view(theme_view('layouts/page_hero'), [
    'kicker'  => 'Pendidik Profesional',
    'heading' => 'Tenaga Pendidik & Kependidikan',
    'lead'    => 'Guru dan staf kependidikan berdedikasi yang membimbing siswa ' . $siteName . ' dalam akademik maupun karakter.',
    'crumbs'  => [['label' => 'Tenaga Pendidik']],
]) ?>

<section class="sec sec-white">
  <div class="wrap">
    <?php if (! empty($list)): ?>
      <div class="grid-3">
        <?php foreach ($list as $teacher): ?>
          <?php $photo = $resolveMedia($teacher['photo'] ?? ''); ?>
          <article class="teacher-card">
            <div class="teacher-media">
              <?php if ($photo !== ''): ?>
                <img src="<?= esc($photo) ?>" alt="<?= esc($teacher['name'] ?? 'Guru') ?>" loading="lazy">
              <?php else: ?>
                <div class="teacher-initials" aria-hidden="true"><?= esc($initials((string) ($teacher['name'] ?? ''))) ?></div>
              <?php endif; ?>
            </div>
            <div class="teacher-body">
              <h2 class="info-card-title"><?= esc($teacher['name'] ?? '') ?></h2>
              <?php if (! empty($teacher['role'])): ?>
                <div><span class="badge badge-blue"><?= esc($teacher['role']) ?></span></div>
              <?php endif; ?>
              <?php if (! empty($teacher['education'])): ?>
                <p class="teacher-subject"><?= lucide_icon('graduation-cap', '', 15) ?> <?= esc($teacher['education']) ?></p>
              <?php endif; ?>
              <?php if (! empty($teacher['experience'])): ?>
                <p class="teacher-subject"><?= lucide_icon('clock', '', 15) ?> <?= esc($teacher['experience']) ?></p>
              <?php endif; ?>
              <?php if (! empty($teacher['social_linkedin']) || ! empty($teacher['social_instagram']) || ! empty($teacher['social_facebook'])): ?>
                <div class="sec-actions" style="margin-top: auto; padding-top: var(--space-3); border-top: 1px solid var(--theme_color_border_default);">
                  <?php if (! empty($teacher['social_linkedin'])): ?>
                    <a class="badge badge-blue" href="<?= esc($teacher['social_linkedin']) ?>" target="_blank" rel="noopener noreferrer"><?= lucide_icon('linkedin', '', 13) ?> LinkedIn</a>
                  <?php endif; ?>
                  <?php if (! empty($teacher['social_instagram'])): ?>
                    <a class="badge badge-blue" href="<?= esc($teacher['social_instagram']) ?>" target="_blank" rel="noopener noreferrer"><?= lucide_icon('instagram', '', 13) ?> Instagram</a>
                  <?php endif; ?>
                  <?php if (! empty($teacher['social_facebook'])): ?>
                    <a class="badge" href="<?= esc($teacher['social_facebook']) ?>" target="_blank" rel="noopener noreferrer"><?= lucide_icon('facebook', '', 13) ?> Facebook</a>
                  <?php endif; ?>
                </div>
              <?php endif; ?>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="empty-state">
        <?= lucide_icon('users', '', 32) ?>
        <p>Belum ada data tenaga pendidik yang ditampilkan.</p>
      </div>
    <?php endif; ?>
  </div>
</section>

<?= view(theme_view('layouts/footer')) ?>
