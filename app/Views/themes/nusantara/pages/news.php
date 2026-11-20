<?= $this->include(theme_view('layouts/header')) ?>
<?php
function n_img($v) {
    if (empty($v)) return '';
    if (preg_match('#^https?://#', $v) || str_starts_with($v, '/') || str_starts_with($v, 'data:')) return $v;
    return base_url(ltrim($v, '/'));
}
function n_initial($title) {
    $t = trim((string) $title);
    return $t !== '' ? mb_strtoupper(mb_substr($t, 0, 1, 'UTF-8'), 'UTF-8') : 'B';
}
$feat = $news[0] ?? null;
$rest = array_slice($news ?? [], 1);
?>
<main id="konten">

<?php $__b = theme_banner('news'); ?>
<section class="pagehero">
  <div class="wrap">
    <div class="crumbs"><a href="<?= base_url('/') ?>">Beranda</a><i>/</i><span>Berita</span></div>
    <?php if ($__b['badge'] !== ''): ?><span class="hero-badge"><?= esc($__b['badge']) ?></span><?php endif; ?>
    <h1><?= $__b['title'] !== '' ? esc($__b['title']) : 'Kabar <em>kampus.</em>' ?></h1>
    <p class="lead"><?= $__b['subtitle'] !== '' ? esc($__b['subtitle']) : 'Ditulis tim sekolah, dikurasi redaksi.' ?><?= !empty($search) ? ' Hasil pencarian: “' . esc($search) . '”' : '' ?><?= !empty($category) ? ' Kategori: ' . esc($category) : '' ?></p>
  </div>
</section>

<section class="sec">
  <div class="wrap">
    <form class="dl-tools rv" action="<?= base_url('news') ?>" method="get">
      <label class="dl-search" style="flex:1">
        <svg><use href="#i-search"/></svg>
        <input type="search" name="search" placeholder="Cari berita…" value="<?= esc($search ?? '') ?>" aria-label="Cari berita">
      </label>
      <button class="btn btn-navy sm" type="submit">Cari</button>
      <?php if (!empty($search) || !empty($category)): ?><a class="btn btn-ghost sm" href="<?= base_url('news') ?>">Reset</a><?php endif; ?>
    </form>

    <?php if (!empty($categories)): ?>
    <div class="tabs rv" role="tablist" aria-label="Saring kategori">
      <a class="tab<?= empty($category) ? ' on' : '' ?>" href="<?= base_url('news') ?>"<?= empty($category) ? ' aria-current="page"' : '' ?>>Semua</a>
      <?php foreach ($categories as $cat): ?>
      <a class="tab<?= (isset($category) && $category === ($cat['slug'] ?? $cat['name'])) ? ' on' : '' ?>" href="<?= base_url('news?category=' . urlencode($cat['slug'] ?? $cat['name'])) ?>"<?= (isset($category) && $category === ($cat['slug'] ?? $cat['name'])) ? ' aria-current="page"' : '' ?>><?= esc($cat['name'] ?? $cat) ?></a>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if (!empty($news)): ?>
    <div class="news-grid">
      <?php if ($feat): $fi = n_img($feat['image'] ?? ''); $fcat = trim(explode(',', $feat['category'] ?? 'Berita')[0]); ?>
      <a class="feat rv" href="<?= base_url('news/' . esc($feat['slug'])) ?>">
        <?php if ($fi): ?>
        <div class="feat-media"><img src="<?= esc($fi) ?>" alt="<?= esc($feat['title']) ?>" loading="lazy"></div>
        <?php else: ?>
        <div class="feat-media feat-ph" aria-hidden="true"><span class="feat-ph-letter"><?= esc(n_initial($feat['title'])) ?></span><span class="feat-ph-cat"><?= esc(strtoupper($fcat)) ?></span></div>
        <?php endif; ?>
        <div class="feat-body">
          <div class="catline"><span class="cat"><?= esc($fcat) ?></span><span class="dt">— <?= date('j M Y', strtotime($feat['published_at'] ?? $feat['created_at'])) ?></span></div>
          <h3 class="feat-title"><?= esc($feat['title']) ?></h3>
          <p class="feat-ex"><?= esc($feat['excerpt'] ?? mb_strimwidth(strip_tags($feat['content'] ?? ''), 0, 160, '…')) ?></p>
        </div>
      </a>
      <?php endif; ?>
      <div class="news-list rv">
        <?php foreach ($rest as $n): $ni = n_img($n['image'] ?? ''); $ncat = trim(explode(',', $n['category'] ?? 'Berita')[0]); ?>
        <a class="nl-item" href="<?= base_url('news/' . esc($n['slug'])) ?>">
          <?php if ($ni): ?>
          <img class="nl-thumb" src="<?= esc($ni) ?>" alt="<?= esc($n['title']) ?>" loading="lazy">
          <?php else: ?>
          <span class="nl-thumb nl-ph" aria-hidden="true"><?= esc(n_initial($n['title'])) ?></span>
          <?php endif; ?>
          <span class="nl-text"><span class="catline"><span class="cat"><?= esc($ncat) ?></span><span class="dt">— <?= date('j M Y', strtotime($n['published_at'] ?? $n['created_at'])) ?></span></span><span class="nl-title"><?= esc($n['title']) ?></span></span>
        </a>
        <?php endforeach; ?>
        <?php if (empty($rest) && $feat): ?><p class="news-empty">Hanya satu berita pada halaman ini.</p><?php endif; ?>
      </div>
    </div>
    <?php else: ?>
    <div class="news-empty-box rv">
      <p class="news-empty-title">Tidak ada berita ditemukan.</p>
      <p class="news-empty">Coba kata kunci lain atau <a href="<?= base_url('news') ?>">tampilkan semua berita</a>.</p>
    </div>
    <?php endif; ?>

    <?php if (isset($pager) && $pager): ?>
      <?= $pager->links() ?>
    <?php endif; ?>
  </div>
</section>

</main>
<?= $this->include(theme_view('layouts/footer')) ?>
