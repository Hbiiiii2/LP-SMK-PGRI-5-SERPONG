<?= view(theme_view('layouts/header')) ?>

<?php
$banner   = function_exists('theme_banner') ? theme_banner('pages') : [];
$doc      = $page ?? [];
$docTitle = (string) ($doc['title'] ?? ($banner['title'] ?? 'Halaman Sekolah'));
$lead     = trim((string) ($doc['excerpt'] ?? ($banner['subtitle'] ?? '')));

$rawImg   = trim((string) ($doc['image'] ?? ''));
$img      = '';
if ($rawImg !== '') {
    if (preg_match('#^https?://localhost(?::\d+)?/(.*)$#i', $rawImg, $m)) {
        $img = base_url(ltrim($m[1], '/'));
    } elseif (str_starts_with($rawImg, 'http://') || str_starts_with($rawImg, 'https://') || str_starts_with($rawImg, '/') || str_starts_with($rawImg, 'data:')) {
        $img = $rawImg;
    } else {
        $img = base_url(ltrim($rawImg, '/'));
    }
}
?>

<?= view(theme_view('layouts/page_hero'), [
    'kicker'  => trim((string) ($banner['badge'] ?? '')) ?: 'Informasi Resmi',
    'heading' => $docTitle,
    'lead'    => $lead,
    'crumbs'  => [['label' => $docTitle]],
]) ?>

<section class="sec sec-white">
  <div class="wrap-narrow">
    <article class="article-main">
      <?php if ($img !== ''): ?>
        <figure class="article-cover">
          <img src="<?= esc($img) ?>" alt="<?= esc($docTitle) ?>">
        </figure>
      <?php endif; ?>

      <div class="prose">
        <?= $doc['content'] ?? '<p>Konten halaman belum tersedia.</p>' ?>
      </div>
    </article>
  </div>
</section>

<?= view(theme_view('layouts/footer')) ?>
