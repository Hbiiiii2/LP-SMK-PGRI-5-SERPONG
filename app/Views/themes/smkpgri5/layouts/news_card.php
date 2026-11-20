<?php
$article   = $article ?? [];
$slug      = (string) ($article['slug'] ?? '');
$url       = base_url('news/' . $slug);
$category  = trim((string) ($article['category'] ?? 'Berita'));
$catFirst  = trim(explode(',', $category)[0] ?? 'Berita');
$catLower  = mb_strtolower($catFirst);
$isUrgent  = str_contains($catLower, 'pengumuman') || str_contains($catLower, 'penting') || str_contains($catLower, 'spmb');
$badgeCls  = $isUrgent ? 'badge-red' : 'badge-blue';
$rawDate   = (string) ($article['published_at'] ?? ($article['created_at'] ?? ''));
$dateLabel = $rawDate !== '' ? date('d M Y', strtotime($rawDate)) : '';
$excerpt   = trim((string) ($article['excerpt'] ?? ''));
if ($excerpt === '' && ! empty($article['content'])) {
    $plain = trim(strip_tags((string) $article['content']));
    $excerpt = mb_strlen($plain) > 130 ? mb_substr($plain, 0, 127) . '...' : $plain;
}
$rawImg = trim((string) ($article['image'] ?? ''));
$imgSrc = '';
if ($rawImg !== '') {
    if (preg_match('#^https?://localhost(?::\d+)?/(.*)$#i', $rawImg, $m)) {
        $imgSrc = base_url(ltrim($m[1], '/'));
    } elseif (preg_match('#^https?://#i', $rawImg) || str_starts_with($rawImg, '/') || str_starts_with($rawImg, 'data:')) {
        $imgSrc = $rawImg;
    } else {
        $imgSrc = base_url(ltrim($rawImg, '/'));
    }
}
?>
<article class="news-card">
  <a class="news-thumb" href="<?= esc($url) ?>">
    <?php if ($imgSrc !== ''): ?>
      <img src="<?= esc($imgSrc) ?>" alt="<?= esc($article['title'] ?? 'Berita sekolah') ?>" loading="lazy">
    <?php else: ?>
      <div class="news-thumb-fallback">
        <?= lucide_icon('newspaper', '', 26) ?>
        <span><?= esc($catFirst !== '' ? $catFirst : 'Informasi Sekolah') ?></span>
      </div>
    <?php endif; ?>
  </a>
  <div class="news-body">
    <div class="news-meta">
      <span class="badge <?= esc($badgeCls) ?>">
        <?php if ($isUrgent): ?><span class="badge-dot" aria-hidden="true"></span><?php endif; ?>
        <?= esc($catFirst !== '' ? $catFirst : 'Berita') ?>
      </span>
      <?php if ($dateLabel !== ''): ?>
        <time class="tabular-nums" datetime="<?= esc($rawDate) ?>"><?= esc($dateLabel) ?></time>
      <?php endif; ?>
    </div>

    <h3 class="news-title">
      <a href="<?= esc($url) ?>"><?= esc($article['title'] ?? 'Tanpa Judul') ?></a>
    </h3>

    <?php if ($excerpt !== ''): ?>
      <p class="news-excerpt"><?= esc($excerpt) ?></p>
    <?php endif; ?>

    <div class="info-card-foot">
      <a class="text-link is-blue" href="<?= esc($url) ?>">
        <span>Baca Selengkapnya</span>
        <?= lucide_icon('arrow-right', '', 15) ?>
      </a>
    </div>
  </div>
</article>
