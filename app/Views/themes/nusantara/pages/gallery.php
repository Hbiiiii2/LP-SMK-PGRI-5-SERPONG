<?= $this->include(theme_view('layouts/header')) ?>
<?php
function g_url($v) {
    if (empty($v)) return '';
    if (preg_match('#^https?://#', $v) || str_starts_with($v, '/') || str_starts_with($v, 'data:')) return $v;
    return base_url(ltrim($v, '/'));
}
$galleryCategory = $category ?? null;
?>
<main id="konten">

<section class="pagehero">
  <div class="wrap">
    <div class="crumbs"><a href="<?= base_url('/') ?>">Beranda</a><i>/</i><span>Galeri</span></div>
    <h1>Suasana <em>kampus.</em></h1>
    <p class="lead"><?= count($galleries ?? []) ?> foto kegiatan<?= $galleryCategory !== null ? ' kategori ' . esc($galleryCategory) : '' ?>. Klik gambar untuk melihat lebih besar.</p>
  </div>
</section>

<section class="sec">
  <div class="wrap">
    <?php if (!empty($categories)): ?>
    <div class="tabs rv" role="tablist" aria-label="Saring kategori galeri">
      <a class="tab<?= $galleryCategory === null ? ' on' : '' ?>" href="<?= base_url('gallery') ?>"<?= $galleryCategory === null ? ' aria-current="page"' : '' ?>>Semua</a>
      <?php foreach ($categories as $cat): $cn = trim((string) ($cat['name'] ?? '')); if ($cn === '') continue; ?>
      <a class="tab<?= $galleryCategory === $cn ? ' on' : '' ?>" href="<?= base_url('gallery?category=' . urlencode($cn)) ?>"<?= $galleryCategory === $cn ? ' aria-current="page"' : '' ?>><?= esc($cn) ?></a>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if (!empty($galleries)): ?>
    <div class="g-grid rv">
      <?php foreach ($galleries as $i => $g): $src = g_url($g['image'] ?? ''); if (!$src) continue; $gcat = trim((string) ($g['category'] ?? '')); ?>
      <figure class="g-item"><div class="g-art"><img src="<?= esc($src) ?>" alt="<?= esc(($g['caption'] ?? '') ?: 'Galeri ' . ($i + 1)) ?>" loading="lazy"></div><figcaption class="g-cap"><span><?= esc(($g['caption'] ?? '') ?: 'Dokumentasi kegiatan') ?><?php if ($gcat !== ''): ?> · <span style="display:inline-block;padding:1px 7px;border:1px solid var(--line);border-radius:999px;font-size:10.5px;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.03em"><?= esc($gcat) ?></span><?php endif; ?></span><span class="no"><?php if (!empty($g['is_featured'])): ?>★ Unggulan · <?php endif; ?>G.<?= str_pad($i + 1, 2, '0', STR_PAD_LEFT) ?></span></figcaption></figure>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <p style="color:var(--muted)"><?= $galleryCategory !== null ? 'Belum ada foto pada kategori ini.' : 'Belum ada foto galeri. Tambahkan melalui Admin → Galeri.' ?></p>
    <?php endif; ?>
  </div>
</section>

</main>
<?= $this->include(theme_view('layouts/footer')) ?>
