<?= view(theme_view('layouts/header')) ?>

<?php
$list       = $galleries ?? [];
$catList    = [];
foreach ($list as $item) {
    $cat = trim((string) ($item['category'] ?? ''));
    if ($cat !== '' && ! in_array($cat, $catList, true)) {
        $catList[] = $cat;
    }
}

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
    'kicker'  => 'Dokumentasi Visual',
    'heading' => 'Galeri Kegiatan Sekolah',
    'lead'    => 'Koleksi foto kegiatan pembelajaran, praktik, acara sekolah, dan fasilitas belajar.',
    'crumbs'  => [['label' => 'Galeri']],
]) ?>

<section class="sec sec-white">
  <div class="wrap">
    <?php if (! empty($catList)): ?>
      <div class="news-toolbar">
        <div class="filter-tabs" data-filter-group="gallery" role="group" aria-label="Filter kategori galeri">
          <button type="button" class="filter-btn active" data-filter="semua" aria-pressed="true">Semua Kategori</button>
          <?php foreach ($catList as $cat): ?>
            <button type="button" class="filter-btn" data-filter="<?= esc(mb_strtolower($cat)) ?>" aria-pressed="false"><?= esc($cat) ?></button>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>

    <?php if (! empty($list)): ?>
      <div class="grid-3" data-filter-list="gallery">
        <?php foreach ($list as $gal): ?>
          <?php
          $img     = $resolveMedia($gal['image'] ?? '');
          $caption = trim((string) ($gal['caption'] ?? 'Dokumentasi sekolah'));
          $cat     = trim((string) ($gal['category'] ?? 'Umum'));
          ?>
          <button type="button"
                  class="gallery-card"
                  data-filter-value="<?= esc(mb_strtolower($cat)) ?>"
                  data-lightbox-src="<?= esc($img) ?>"
                  data-lightbox-title="<?= esc($caption) ?>">
            <span class="gallery-media">
              <img src="<?= esc($img) ?>" alt="<?= esc($caption) ?>" loading="lazy">
            </span>
            <span class="gallery-caption">
              <span class="gallery-title"><?= esc($caption) ?></span>
              <span class="badge badge-blue"><?= esc($cat) ?></span>
            </span>
          </button>
        <?php endforeach; ?>
      </div>
      <div class="empty-state hidden" data-filter-empty="gallery" style="margin-top: var(--space-5);">
        <?= lucide_icon('images', '', 32) ?>
        <p>Tidak ada foto pada kategori yang dipilih.</p>
      </div>
    <?php else: ?>
      <div class="empty-state">
        <?= lucide_icon('images', '', 32) ?>
        <p>Belum ada dokumentasi galeri yang tersedia.</p>
      </div>
    <?php endif; ?>
  </div>
</section>

<?= view(theme_view('layouts/footer')) ?>
