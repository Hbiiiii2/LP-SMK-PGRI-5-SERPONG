<?= $this->include(theme_view('layouts/header')) ?>
<main id="konten">

<section class="pagehero">
  <div class="wrap">
    <div class="crumbs"><a href="<?= base_url('/') ?>">Beranda</a><i>/</i><span>Ekstrakurikuler</span></div>
    <h1>Pilih <em>arenamu.</em></h1>
    <p class="lead"><?= count($extracurriculars ?? []) ?> kegiatan siswa aktif — lengkap dengan pembina dan jadwal.</p>
  </div>
</section>

<section class="sec">
  <div class="wrap">
    <?php if (!empty($extracurriculars)): ?>
    <div class="agenda rv">
      <?php foreach ($extracurriculars as $i => $ex): ?>
      <div class="ag-row" data-cat="semua">
        <span class="day<?= $i === 0 ? ' red' : '' ?>">E.<?= str_pad($i + 1, 2, '0', STR_PAD_LEFT) ?></span>
        <div class="ag-info"><span class="cat"><i class="<?= esc(($ex['icon'] ?? '') ?: 'fas fa-circle') ?>" style="color:<?= esc(($ex['icon_color'] ?? '') ?: '#14b8a6') ?>;margin-right:.4rem"></i>Ekstrakurikuler</span><h3><?= esc($ex['title']) ?></h3><p><?= esc($ex['description'] ?? '') ?></p></div>
        <span class="ag-loc"><svg><use href="#i-pin"/></svg>Sekolah</span>
      </div>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <p style="color:var(--muted)">Belum ada data ekstrakurikuler. Tambahkan melalui Admin → Extracurriculars.</p>
    <?php endif; ?>
    <div class="cta-mini rv">
      <div><h2>Lihat buktinya <em>di papan prestasi.</em></h2><p>Prestasi lahir dari pembinaan rutin kegiatan di atas.</p></div>
      <a class="btn btn-red" href="<?= base_url('achievements') ?>">Lihat Prestasi <svg><use href="#i-arrow"/></svg></a>
    </div>
  </div>
</section>

</main>
<?= $this->include(theme_view('layouts/footer')) ?>
