<?= $this->include(theme_view('layouts/header')) ?>
<main id="konten">

<section class="pagehero">
  <div class="wrap">
    <div class="crumbs"><a href="<?= base_url('/') ?>">Beranda</a><i>/</i><span>Program Unggulan</span></div>
    <h1>Program <em>unggulan.</em></h1>
    <p class="lead">Peminatan dan pembinaan di <?= esc($site_name) ?>. Dikelola dinamis melalui admin.</p>
  </div>
</section>

<section class="sec">
  <div class="wrap">
    <div class="sec-head rv"><span class="idx">A</span><span class="lbl">Peminatan</span><span class="rule"></span></div>
    <?php if (!empty($programs)): ?>
    <div class="prog rv">
      <?php foreach ($programs as $i => $p): ?>
      <a class="prog-row" href="<?= program_link($p) ?>">
        <span class="p-num">/ 0<?= $i + 1 ?></span>
        <span><span class="p-title"><?= esc($p['title']) ?></span>
        <span class="p-desc" style="display:block"><?= esc($p['description'] ?? '') ?></span>
        <?php if (!empty($p['link_text'])): ?><span class="p-tags"><span><?= esc($p['link_text']) ?></span></span><?php endif; ?></span>
        <span class="p-tags" style="justify-content:flex-end"><span>SPMB</span></span>
        <span class="p-arrow"><svg><use href="#i-arrow"/></svg></span>
      </a>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <p style="color:var(--muted)">Belum ada program. Tambahkan melalui Admin → Programs.</p>
    <?php endif; ?>
  </div>
</section>

<section class="sec tint">
  <div class="wrap">
    <div class="sec-head rv"><span class="idx">B</span><span class="lbl">Ritme Sepekan</span><span class="rule"></span></div>
    <h2 class="sec-title rv">Sepekan di <?= esc($site_name) ?>.</h2>
    <div class="rv" style="margin-top:36px">
      <div class="pr-row"><span><h4>Senin – Jumat · Sesuai jadwal</h4><p>KBM reguler + pembiasaan pagi</p></span><span class="day">KBM</span></div>
      <div class="pr-row"><span><h4>Sore hari · Terjadwal</h4><p>Bimbingan dan pembinaan prestasi</p></span><span class="day">Bimbel</span></div>
      <div class="pr-row"><span><h4>Jumat · Siang</h4><p>Ekstrakurikuler dan kerja bakti</p></span><span class="day">Ekskul</span></div>
      <div class="pr-row"><span><h4>Sabtu · Pagi</h4><p>Open house dan kegiatan terbuka</p></span><span class="day red">Terbuka</span></div>
    </div>
    <div class="cta-mini rv">
      <div><h2>Kuota <em>terbatas.</em></h2><p>Hubungi panitia SPMB untuk informasi daya tampung tahun ini.</p></div>
      <a class="btn btn-red" href="<?= base_url('#spmb') ?>">Daftar SPMB <svg><use href="#i-arrow"/></svg></a>
    </div>
  </div>
</section>

</main>
<?= $this->include(theme_view('layouts/footer')) ?>
