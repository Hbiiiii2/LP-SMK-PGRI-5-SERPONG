<?= view(theme_view('layouts/header')) ?>

<?php
$banner          = function_exists('theme_banner') ? theme_banner('news') : [];
$list            = $news ?? [];
$cats            = $categories ?? [];
$activeCategory  = trim((string) ($category ?? ($current_category ?? '')));
$searchTerm      = trim((string) ($search ?? ($search_query ?? ($keyword ?? ($q ?? '')))));
$activeMonth     = trim((string) ($month ?? ''));

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

$showFeatured = ! empty($list) && $searchTerm === '' && count($list) > 1;
$featuredPost = $showFeatured ? $list[0] : null;
$gridList     = $showFeatured ? array_slice($list, 1) : $list;
?>

<?= view(theme_view('layouts/page_hero'), [
    'kicker'  => trim((string) ($banner['badge'] ?? '')) ?: 'Publikasi Resmi',
    'heading' => trim((string) ($banner['title'] ?? '')) ?: 'Berita & Pengumuman Sekolah',
    'lead'    => trim((string) ($banner['subtitle'] ?? '')) ?: 'Kabar akademik, liputan kegiatan siswa, dan pengumuman resmi terbaru.',
    'crumbs'  => [['label' => 'Berita']],
]) ?>

<section class="sec sec-white">
  <div class="wrap">
    <div class="news-toolbar">
      <div class="filter-tabs" role="navigation" aria-label="Kategori berita">
        <a class="filter-btn<?= $activeCategory === '' && $searchTerm === '' && $activeMonth === '' ? ' active' : '' ?>" href="<?= esc(base_url('news')) ?>">Semua</a>
        <?php foreach ($cats as $catRow): ?>
          <?php
          if (is_array($catRow)) {
              $catLabel = trim((string) ($catRow['name'] ?? ($catRow['category'] ?? '')));
              $catSlug  = trim((string) ($catRow['slug'] ?? $catLabel));
              $catCount = (int) ($catRow['count'] ?? 0);
          } else {
              $catLabel = trim((string) $catRow);
              $catSlug  = $catLabel;
              $catCount = 0;
          }
          if ($catLabel === '') {
              continue;
          }
          $isCatActive = strcasecmp($activeCategory, $catSlug) === 0 || strcasecmp($activeCategory, $catLabel) === 0;
          ?>
          <a class="filter-btn<?= $isCatActive ? ' active' : '' ?>" href="<?= esc(base_url('news?category=' . urlencode($catSlug))) ?>">
            <span><?= esc($catLabel) ?></span>
            <?php if ($catCount > 0): ?>
              <span class="tabular-nums" style="opacity: 0.72; font-size: 0.75em;">(<?= esc((string) $catCount) ?>)</span>
            <?php endif; ?>
          </a>
        <?php endforeach; ?>
      </div>

      <form class="search-form" action="<?= esc(base_url('news')) ?>" method="get" role="search">
        <label class="sr-only" for="newsSearchInput">Cari berita</label>
        <input class="form-control" type="search" id="newsSearchInput" name="search" value="<?= esc($searchTerm) ?>" placeholder="Cari judul atau topik...">
        <button type="submit" class="btn btn-primary btn-sm">
          <?= lucide_icon('search', '', 15) ?>
          <span>Cari</span>
        </button>
      </form>
    </div>

    <?php if ($searchTerm !== '' || $activeCategory !== '' || $activeMonth !== ''): ?>
      <div class="alert alert-success" style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: var(--space-3); margin-bottom: var(--space-6);">
        <span>
          <?php if ($searchTerm !== ''): ?>
            Menampilkan hasil pencarian untuk: <strong><?= esc($searchTerm) ?></strong>
          <?php elseif ($activeCategory !== ''): ?>
            Kategori terpilih: <strong><?= esc($activeCategory) ?></strong>
          <?php else: ?>
            Arsip bulan: <strong><?= esc($activeMonth) ?></strong>
          <?php endif; ?>
        </span>
        <a class="text-link is-blue" href="<?= esc(base_url('news')) ?>">Reset filter <?= lucide_icon('x', '', 14) ?></a>
      </div>
    <?php endif; ?>

    <?php if (! empty($featuredPost)): ?>
      <?php
      $fUrl     = base_url('news/' . ($featuredPost['slug'] ?? ''));
      $fImg     = $resolveMedia($featuredPost['image'] ?? '');
      $fCat     = trim(explode(',', (string) ($featuredPost['category'] ?? 'Berita'))[0] ?? 'Berita');
      $fUrgent  = str_contains(mb_strtolower($fCat), 'pengumuman') || str_contains(mb_strtolower($fCat), 'spmb');
      $fDateRaw = (string) ($featuredPost['published_at'] ?? ($featuredPost['created_at'] ?? ''));
      $fDateFmt = $fDateRaw !== '' ? date('d M Y', strtotime($fDateRaw)) : '';
      $fExcerpt = trim((string) ($featuredPost['excerpt'] ?? ''));
      if ($fExcerpt === '' && ! empty($featuredPost['content'])) {
          $plain = trim(strip_tags((string) $featuredPost['content']));
          $fExcerpt = mb_strlen($plain) > 200 ? mb_substr($plain, 0, 197) . '...' : $plain;
      }
      ?>
      <article class="card" style="margin-bottom: var(--space-6); padding: 0; overflow: hidden; border-radius: var(--radius-xl);">
        <div class="split-grid" style="gap: 0; align-items: stretch;">
          <a href="<?= esc($fUrl) ?>" style="display: block; min-height: 240px; background-color: var(--theme_color_surface_subtle); border-bottom: 1px solid var(--theme_color_border_default);">
            <?php if ($fImg !== ''): ?>
              <img src="<?= esc($fImg) ?>" alt="<?= esc($featuredPost['title'] ?? '') ?>" style="width: 100%; height: 100%; max-height: 340px; object-fit: cover;">
            <?php else: ?>
              <div class="news-thumb-fallback" style="min-height: 240px;">
                <?= lucide_icon('newspaper', '', 34) ?>
                <span><?= esc($fCat) ?></span>
              </div>
            <?php endif; ?>
          </a>
          <div style="padding: var(--space-6); display: flex; flex-direction: column;">
            <div class="news-meta">
              <span class="badge <?= $fUrgent ? 'badge-red' : 'badge-blue' ?>"><?= esc($fCat) ?> · Utama</span>
              <?php if ($fDateFmt !== ''): ?>
                <time class="tabular-nums" datetime="<?= esc($fDateRaw) ?>"><?= esc($fDateFmt) ?></time>
              <?php endif; ?>
            </div>
            <h2 class="news-lead-title">
              <a href="<?= esc($fUrl) ?>"><?= esc($featuredPost['title'] ?? '') ?></a>
            </h2>
            <?php if ($fExcerpt !== ''): ?>
              <p class="news-excerpt"><?= esc($fExcerpt) ?></p>
            <?php endif; ?>
            <div class="info-card-foot">
              <a class="text-link is-blue" href="<?= esc($fUrl) ?>">
                <span>Baca Selengkapnya</span>
                <?= lucide_icon('arrow-right', '', 15) ?>
              </a>
            </div>
          </div>
        </div>
      </article>
    <?php endif; ?>

    <?php if (! empty($gridList)): ?>
      <div class="grid-3">
        <?php foreach ($gridList as $article): ?>
          <?= view(theme_view('layouts/news_card'), ['article' => $article]) ?>
        <?php endforeach; ?>
      </div>
    <?php elseif (empty($featuredPost)): ?>
      <div class="empty-state">
        <?= lucide_icon('newspaper', '', 32) ?>
        <p>Belum ada artikel berita yang sesuai dengan filter atau pencarian Anda.</p>
        <a class="btn btn-outline-primary btn-sm" href="<?= esc(base_url('news')) ?>">Lihat Semua Berita</a>
      </div>
    <?php endif; ?>

    <?php if (isset($pager) && is_object($pager) && method_exists($pager, 'links')): ?>
      <?= $pager->links('default', 'default_full') ?>
    <?php endif; ?>
  </div>
</section>

<?= view(theme_view('layouts/footer')) ?>
