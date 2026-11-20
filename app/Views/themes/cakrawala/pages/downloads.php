<?= $this->include(theme_view('layouts/header')) ?>
<main id="konten">

<?php $__b = theme_banner('downloads'); ?>
<section class="pagehero">
  <div class="wrap">
    <div class="crumbs"><a href="<?= base_url('/') ?>">Beranda</a><i>/</i><span>Download File</span></div>
    <?php if ($__b['badge'] !== ''): ?><span class="hero-badge"><?= esc($__b['badge']) ?></span><?php endif; ?>
    <h1><?= $__b['title'] !== '' ? esc($__b['title']) : 'Dokumen <em>resmi.</em>' ?></h1>
    <p class="lead"><?= $__b['subtitle'] !== '' ? esc($__b['subtitle']) : 'Brosur, formulir, dan peraturan — selalu versi terbaru. Ketik kata kunci untuk mencari.' ?></p>
  </div>
</section>

<section class="sec">
  <div class="wrap">
    <div class="dl-tools rv">
      <label class="dl-search"><svg><use href="#i-search"/></svg><input type="search" id="dlSearch" placeholder="Cari berkas… cth. brosur, kalender, tata tertib" aria-label="Cari berkas"></label>
      <span class="dl-count" id="dlCount"><?= array_sum(array_map('count', $categories ?? [])) ?> berkas ditemukan</span>
    </div>
    <?php if (!empty($categories)): ?>
    <div class="rv">
      <?php foreach ($categories as $cat => $items): ?>
      <div class="dl-cat" style="font-family:var(--mono,monospace);font-size:12px;letter-spacing:.18em;text-transform:uppercase;color:var(--muted);margin:22px 0 10px"><?= esc($cat ?: 'Umum') ?> — <?= count($items) ?> berkas</div>
      <?php foreach ($items as $item): ?>
      <?php
      $ext = 'PDF';
      $url = $item['url'] ?? '#';
      if (preg_match('/\.([a-z0-9]{2,5})(?:\?|#|$)/i', $url, $m)) $ext = strtoupper($m[1]);
      $docCls = in_array($ext, ['DOC', 'DOCX', 'XLS', 'XLSX', 'PPT', 'PPTX'], true) ? ' doc' : '';
      ?>
      <div class="dl-row"><span class="ftype<?= $docCls ?>"><?= esc($ext) ?><small><?= esc($item['file_size'] ?? '') ?></small></span><span><h3><?= esc($item['title']) ?></h3><div class="dl-meta"><?= esc(strtoupper($cat)) ?><?php if (!empty($item['description'])): ?> · <?= esc($item['description']) ?><?php endif; ?></div></span><a class="btn btn-blue sm dl-btn" href="<?= esc($url) ?>" target="_blank" rel="noopener">Unduh</a></div>
      <?php endforeach; ?>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <p style="color:var(--muted)">Belum ada berkas download tersedia.</p>
    <?php endif; ?>
  </div>
</section>

</main>
<?= $this->include(theme_view('layouts/footer')) ?>
