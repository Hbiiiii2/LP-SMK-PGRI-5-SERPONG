<?= $this->include(theme_view('layouts/header')) ?>
<?php
function pg_img($v) {
    if (empty($v)) return '';
    if (preg_match('#^https?://#', $v) || str_starts_with($v, '/') || str_starts_with($v, 'data:')) return $v;
    return base_url(ltrim($v, '/'));
}
$cover = pg_img($page['image'] ?? '');
?>
<main id="konten">

<section class="pagehero">
  <div class="wrap">
    <div class="crumbs"><a href="<?= base_url('/') ?>">Beranda</a><i>/</i><span><?= esc(mb_strimwidth($page['title'], 0, 30, '…')) ?></span></div>
    <?php $__b = theme_banner('pages'); ?>
    <?php if ($__b['badge'] !== ''): ?><span class="hero-badge"><?= esc($__b['badge']) ?></span><?php endif; ?>
    <h1><?= esc($page['title']) ?></h1>
    <?php if (!empty($page['excerpt'])): ?><p class="lead"><?= esc($page['excerpt']) ?></p><?php endif; ?>
  </div>
</section>

<article class="sec artikel">
  <div class="wrap artikel-wrap">
    <div class="byline rv">
      <span class="by-ava"><?= esc(mb_strtoupper(mb_substr($site_name, 0, 1, 'UTF-8'))) ?></span>
      <span><strong><?= esc($site_name) ?></strong><br><span class="by-sub"><?= date('j F Y', strtotime($page['created_at'] ?? $page['updated_at'] ?? 'now')) ?></span></span>
    </div>

    <?php if ($cover): ?>
    <figure class="art-fig rv">
      <img src="<?= esc($cover) ?>" alt="<?= esc($page['title']) ?>" loading="lazy">
      <figcaption><span><?= esc($page['title']) ?></span><span class="no">FIG. 01</span></figcaption>
    </figure>
    <?php endif; ?>

    <div class="art-body rv">
      <?= $page['content'] ?>
    </div>

    <div class="share rv">
      <span class="share-lbl">Bagikan halaman</span>
      <span class="share-btns">
        <button class="sbtn" id="copyLink"><svg><use href="#i-link"/></svg>Salin tautan</button>
        <a class="sbtn" href="https://wa.me/?text=<?= urlencode($page['title'] . ' ' . current_url()) ?>" target="_blank" rel="noopener">WhatsApp</a>
        <a class="sbtn" href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(current_url()) ?>" target="_blank" rel="noopener">Facebook</a>
      </span>
    </div>

    <?php if (isset($all_pages) && count($all_pages) > 1): ?>
    <div class="rel">
      <div class="s-head"><span class="s-chip">◆</span><span class="s-kick">Halaman lainnya</span><span class="s-rule"></span></div>
      <div class="news-side" style="max-width:820px">
        <?php foreach ($all_pages as $p): ?>
        <?php if ($p['id'] === $page['id']) continue; ?>
        <a class="nl-item" href="<?= base_url(esc($p['slug'])) ?>">
          <span><span class="nl-title" style="display:block"><?= esc($p['title']) ?></span></span>
        </a>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>

  </div>
</article>

</main>
<?= $this->include(theme_view('layouts/footer')) ?>
<script>
(function(){
  var cp=document.getElementById('copyLink');
  if(cp) cp.addEventListener('click',function(){
    var url=location.href;
    if(navigator.clipboard&&navigator.clipboard.writeText){ navigator.clipboard.writeText(url); }
    var t=document.getElementById('toast'),m=document.getElementById('toastMsg');
    if(t&&m){ m.textContent='Tautan halaman disalin.'; t.classList.add('show'); setTimeout(function(){t.classList.remove('show');},3200); }
  });
})();
</script>
