<?= view(theme_view('layouts/header')) ?>

<?php
$list = $testimonials ?? [];
$cleanText = static fn (?string $s): string => strtr(trim((string) $s), ['Â·' => '·', 'â€”' => '—', 'â€“' => '–']);
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
    'kicker'  => 'Kepercayaan Komunitas',
    'heading' => 'Testimoni Alumni, Siswa & Orang Tua',
    'lead'    => 'Pengalaman nyata warga sekolah mengenai kualitas pendampingan, pembinaan karakter, dan pembelajaran.',
    'crumbs'  => [['label' => 'Testimoni']],
]) ?>

<section class="sec sec-white">
  <div class="wrap">
    <?php if (! empty($list)): ?>
      <div class="grid-3">
        <?php foreach ($list as $item): ?>
          <?php $photo = $resolveMedia($item['photo'] ?? ''); ?>
          <article class="quote-card">
            <?= lucide_icon('quote', 'quote-mark', 20) ?>
            <p class="quote-body">"<?= esc($cleanText($item['quote'] ?? '')) ?>"</p>
            <div class="quote-author">
              <div class="quote-avatar">
                <?php if ($photo !== ''): ?>
                  <img src="<?= esc($photo) ?>" alt="<?= esc($cleanText($item['name'] ?? '')) ?>" loading="lazy">
                <?php else: ?>
                  <span><?= esc(mb_substr((string) ($item['name'] ?? 'A'), 0, 2)) ?></span>
                <?php endif; ?>
              </div>
              <div>
                <div class="quote-name"><?= esc($cleanText($item['name'] ?? '')) ?></div>
                <?php if (! empty($item['role'])): ?>
                  <div class="quote-role"><?= esc($cleanText($item['role'])) ?></div>
                <?php endif; ?>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="empty-state">
        <?= lucide_icon('message-circle', '', 32) ?>
        <p>Belum ada testimoni yang dipublikasikan.</p>
      </div>
    <?php endif; ?>
  </div>
</section>

<?= view(theme_view('layouts/footer')) ?>
