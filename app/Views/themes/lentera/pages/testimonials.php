<?= $this->include(theme_view('layouts/header')) ?>
<main id="konten">

<section class="pagehero">
  <div class="wrap">
    <div class="crumbs"><a href="<?= base_url('/') ?>">Beranda</a><i>/</i><span>Testimoni</span></div>
    <h1>Kata mereka yang <em>pernah di sini.</em></h1>
    <p class="lead">Cerita alumni, orang tua, dan siswa tentang <?= esc($site_name) ?>.</p>
  </div>
</section>

<section class="sec">
  <div class="wrap">
    <?php if (!empty($testimonials)): ?>
    <div class="rv">
      <?php foreach ($testimonials as $t): ?>
      <div class="t-big"><?php if (!empty($t['photo'])): ?><span class="t-ava"><img src="<?= esc($t['photo']) ?>" alt="<?= esc($t['name']) ?>" loading="lazy"></span><?php else: ?><span class="q">“</span><?php endif; ?><div><blockquote><?= esc($t['quote']) ?></blockquote><cite><strong><?= esc($t['name']) ?></strong><?php if (!empty($t['role'])): ?> · <?= esc($t['role']) ?><?php endif; ?></cite></div></div>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <p style="color:var(--muted)">Belum ada testimoni. Tambahkan melalui Admin → Testimonials.</p>
    <?php endif; ?>
    <div class="cta-mini rv">
      <div><h2>Jadilah cerita <em>berikutnya.</em></h2><p>Pendaftaran peserta didik baru telah dibuka.</p></div>
      <a class="btn btn-sun" href="<?= base_url('#spmb') ?>">Daftar SPMB <svg><use href="#i-arrow"/></svg></a>
    </div>
  </div>
</section>

</main>
<?= $this->include(theme_view('layouts/footer')) ?>
