<?= $this->include(theme_view('layouts/header')) ?>
<?php
function u_img($v, $fallback = '') {
    if (empty($v)) return $fallback;
    if (preg_match('#^https?://#', $v) || str_starts_with($v, '/') || str_starts_with($v, 'data:')) return $v;
    return base_url(ltrim($v, '/'));
}
$aboutData = is_array($about ?? null) ? $about : [];
$highlights = $aboutData['highlights'] ?? [];
$abImg = u_img($aboutData['image'] ?? '', '');
?>
<main id="konten">

<section class="pagehero">
  <div class="wrap">
    <div class="crumbs"><a href="<?= base_url('/') ?>">Beranda</a><i>/</i><span>Profil</span></div>
    <h1><?= ($aboutData['content_title'] ?? '') !== '' ? esc($aboutData['content_title']) : 'Tiga dekade <em>mendidik.</em>' ?></h1>
    <p class="lead"><?= esc($aboutData['content_1'] ?? $site_description) ?></p>
    <div class="hero-facts"><span>AKREDITASI <b><?= esc($aboutData['accreditation'] ?? 'A') ?></b></span><span>KURIKULUM <b>MERDEKA</b></span></div>
  </div>
</section>

<section class="sec">
  <div class="wrap">
    <div class="s-head rv"><span class="s-chip">A</span><span class="s-kick">Tentang Kami</span><span class="s-rule"></span></div>
    <h2 class="s-title rv"><?= esc($site_name) ?> <em>sekilas.</em></h2>
    <?php if ($abImg): ?>
    <figure class="art-fig rv">
      <img src="<?= esc($abImg) ?>" alt="Gedung <?= esc($site_name) ?>" loading="lazy">
      <figcaption><span>Kampus <?= esc($site_name) ?></span><span class="no">FIG. 01</span></figcaption>
    </figure>
    <?php endif; ?>
    <div class="rv" style="max-width:820px">
      <?php if (!empty($aboutData['content_2'])): ?><p style="font-size:17px;margin-bottom:16px"><?= esc($aboutData['content_2']) ?></p><?php endif; ?>
      <?php if (!empty($aboutData['accreditation'])): ?><p><strong>Akreditasi <?= esc($aboutData['accreditation']) ?></strong> — <?= esc($aboutData['accreditation_label'] ?? 'Standar Nasional') ?></p><?php endif; ?>
    </div>
  </div>
</section>

<section class="sec mist">
  <div class="wrap">
    <div class="s-head rv"><span class="s-chip sun">B</span><span class="s-kick">Visi &amp; Misi</span><span class="s-rule"></span></div>
    <div class="cols2">
      <div class="visi-box rv">
        <div class="vh">Visi Sekolah</div>
        <p>“<?= esc($aboutData['visi'] ?? 'Menjadi sekolah unggul yang melahirkan lulusan berilmu dan berkarakter.') ?>”</p>
      </div>
      <div class="visi-box rv">
        <div class="vh">Misi Sekolah</div>
        <?php if (!empty($aboutData['misi'])): ?><p><?= esc($aboutData['misi']) ?></p><?php else: ?><p style="color:var(--muted)">Misi sekolah dikelola melalui halaman admin (Settings → About).</p><?php endif; ?>
      </div>
    </div>
  </div>
</section>

<?php if (!empty($highlights)): ?>
<section class="sec">
  <div class="wrap">
    <div class="s-head rv"><span class="s-chip">C</span><span class="s-kick">Budaya Sekolah</span><span class="s-rule"></span></div>
    <h2 class="s-title rv">Kebiasaan yang terlihat setiap hari.</h2>
    <div class="fas-grid rv">
      <?php foreach ($highlights as $i => $hl): $t = is_array($hl) ? ($hl['text'] ?? '') : $hl; ?>
      <div class="fas"><div class="fas-top"><span class="fas-no">B.0<?= $i + 1 ?></span></div><h3><?= esc(mb_strimwidth($t, 0, 60, '…')) ?></h3><p><?= esc($t) ?></p></div>
      <?php endforeach; ?>
    </div>
    <div class="cta-mini rv">
      <div><h2>Ingin melihat langsung <em>budayanya?</em></h2><p>Hubungi kami untuk jadwal kunjungan dan open house.</p></div>
      <a class="btn btn-blue" href="<?= base_url('contact') ?>">Hubungi Kami <svg><use href="#i-arrow"/></svg></a>
    </div>
  </div>
</section>
<?php endif; ?>

</main>
<?= $this->include(theme_view('layouts/footer')) ?>
