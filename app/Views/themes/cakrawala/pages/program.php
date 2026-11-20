<?= $this->include(theme_view('layouts/header')) ?>
<?php $aboutData = is_array($about ?? null) ? $about : []; ?>
<main id="konten">

<section class="pagehero">
  <div class="wrap">
    <div class="crumbs"><a href="<?= base_url('/') ?>">Beranda</a><i>/</i><span>Program Unggulan</span></div>
    <h1>Program <em>unggulan.</em></h1>
    <p class="lead">Peminatan dan pembinaan di <?= esc($site_name) ?>. Dikelola dinamis melalui admin.</p>
    <div class="hero-facts"><span><b><?= count($programs ?? []) ?></b> PEMINATAN</span><span>KURIKULUM <b>MERDEKA</b></span><span>AKREDITASI <b><?= esc($aboutData['accreditation'] ?? 'A') ?></b></span></div>
  </div>
</section>

<section class="sec">
  <div class="wrap">
    <div class="s-head rv"><span class="s-chip">A</span><span class="s-kick">Peminatan</span><span class="s-rule"></span></div>
    <?php if (!empty($programs)): ?>
    <?php $progIcons = ['flask', 'users', 'book']; ?>
    <div class="prog-grid">
      <?php foreach ($programs as $i => $p): ?>
      <div class="prog-card rv"><span class="prog-no">PEMINATAN / 0<?= $i + 1 ?></span><span class="prog-ico<?= $i % 2 === 1 ? ' sun' : '' ?>"><svg><use href="#i-<?= $progIcons[$i % 3] ?>"/></svg></span><h3><?= esc($p['title']) ?></h3><p><?= esc($p['description'] ?? '') ?></p><?php if (!empty($p['link_text'])): ?><div class="prog-tags"><span><?= esc($p['link_text']) ?></span></div><?php endif; ?><div class="prog-foot"><span class="kuota">Kurikulum Merdeka</span><span><a href="<?= program_link($p) ?>" style="text-decoration:none">Daftar SPMB <svg><use href="#i-arrow"/></svg></a></span></div></div>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <p style="color:var(--muted)">Belum ada program. Tambahkan melalui Admin → Programs.</p>
    <?php endif; ?>
  </div>
</section>

<section class="sec mist">
  <div class="wrap">
    <div class="s-head rv"><span class="s-chip">B</span><span class="s-kick">Ritme Sepekan</span><span class="s-rule"></span></div>
    <h2 class="s-title rv">Sepekan di <?= esc($site_name) ?>.</h2>
    <div class="rv" style="margin-top:36px">
      <div class="pr-row"><span><h4>Senin – Jumat · Sesuai jadwal</h4><p>KBM reguler + pembiasaan pagi</p></span><span class="day">KBM</span></div>
      <div class="pr-row"><span><h4>Sore hari · Terjadwal</h4><p>Bimbingan dan pembinaan prestasi</p></span><span class="day">Bimbel</span></div>
      <div class="pr-row"><span><h4>Jumat · Siang</h4><p>Ekstrakurikuler dan kerja bakti</p></span><span class="day">Ekskul</span></div>
      <div class="pr-row"><span><h4>Sabtu · Pagi</h4><p>Open house dan kegiatan terbuka</p></span><span class="day red">Terbuka</span></div>
    </div>
    <div class="cta-mini rv">
      <div><h2>Kuota <em>terbatas.</em></h2><p>Hubungi panitia SPMB untuk informasi daya tampung tahun ini.</p></div>
      <a class="btn btn-sun" href="<?= base_url('#spmb') ?>">Daftar SPMB <svg><use href="#i-arrow"/></svg></a>
    </div>
  </div>
</section>

</main>
<?= $this->include(theme_view('layouts/footer')) ?>
