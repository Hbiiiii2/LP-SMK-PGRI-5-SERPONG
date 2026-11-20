<?= $this->include(theme_view('layouts/header')) ?>
<?php
function u_img($v, $fallback = '') {
    if (empty($v)) return $fallback;
    if (preg_match('#^https?://#', $v) || str_starts_with($v, '/') || str_starts_with($v, 'data:')) return $v;
    return base_url(ltrim($v, '/'));
}
$aboutData = is_array($about ?? null) ? $about : [];
$highlights = $aboutData['highlights'] ?? [];
?>
<main id="konten">

<section class="pagehero pagehero-full">
  <div class="wrap">
    <div class="crumbs"><a href="<?= base_url('/') ?>">Beranda</a><i>/</i><span>Profil</span></div>
    <h1><?= ($aboutData['content_title'] ?? '') !== '' ? esc($aboutData['content_title']) : 'Tiga dekade <em>mendidik.</em>' ?></h1>
    <p class="lead"><?= esc($aboutData['content_1'] ?? $site_description) ?></p>
  </div>
</section>

<section class="sec">
  <div class="wrap">
    <div class="sec-head rv"><span class="idx">A</span><span class="lbl">Tentang Kami</span><span class="rule"></span></div>
    <div class="sambut-grid">
      <div class="plate rv">
        <?php $abImg = u_img($aboutData['image'] ?? '', ''); ?>
        <?php if ($abImg): ?>
        <div class="plate-photo"><img src="<?= esc($abImg) ?>" alt="Gedung <?= esc($site_name) ?>" loading="lazy"></div>
        <?php else: ?>
        <svg class="plate-pattern" viewBox="0 0 300 300" preserveAspectRatio="xMidYMid slice" aria-hidden="true"><g stroke="#fff" stroke-width="1" fill="none"><circle cx="150" cy="120" r="90"/><circle cx="150" cy="120" r="66"/><circle cx="150" cy="120" r="42"/></g></svg>
        <div class="plate-mono"><?= esc(mb_substr($site_name, 0, 2, 'UTF-8')) ?></div>
        <?php endif; ?>
        <div class="plate-name"><strong><?= esc($site_name) ?></strong><span><?= esc(strtoupper($site_tagline ?: 'PROFIL SEKOLAH')) ?><?php if (!empty($aboutData['accreditation'])): ?> · AKREDITASI <?= esc($aboutData['accreditation']) ?><?php endif; ?></span></div>
      </div>
      <div class="sambut-body rv">
        <?php if (!empty($aboutData['content_2'])): ?><p><?= esc($aboutData['content_2']) ?></p><?php endif; ?>
        <?php if (!empty($aboutData['accreditation'])): ?><p><strong>Akreditasi <?= esc($aboutData['accreditation']) ?></strong> — <?= esc($aboutData['accreditation_label'] ?? 'Standar Nasional') ?></p><?php endif; ?>
      </div>
    </div>
  </div>
</section>

<section class="sec tint">
  <div class="wrap">
    <div class="sec-head rv"><span class="idx">B</span><span class="lbl">Visi &amp; Misi</span><span class="rule"></span></div>
    <div class="cols2">
      <div class="visi-box rv">
        <div class="vh">Visi Sekolah</div>
        <p>“<?= esc($aboutData['visi'] ?? 'Menjadi sekolah unggul yang melahirkan lulusan berilmu dan berkarakter.') ?>”</p>
      </div>
      <div class="rv">
        <div class="vh" style="font-family:var(--mono);font-size:11px;letter-spacing:.24em;text-transform:uppercase;color:var(--red-dark);margin-bottom:16px">Misi Sekolah</div>
        <?php if (!empty($aboutData['misi'])): ?><p style="font-size:16px;color:#33405a"><?= esc($aboutData['misi']) ?></p><?php else: ?><p style="color:var(--muted)">Misi sekolah dikelola melalui halaman admin (Settings → About).</p><?php endif; ?>
      </div>
    </div>
  </div>
</section>

<?php if (!empty($highlights)): ?>
<section class="sec">
  <div class="wrap">
    <div class="sec-head rv"><span class="idx">C</span><span class="lbl">Budaya Sekolah</span><span class="rule"></span></div>
    <h2 class="sec-title rv">Kebiasaan yang terlihat setiap hari.</h2>
    <div class="fas-grid rv">
      <?php foreach ($highlights as $i => $hl): $t = is_array($hl) ? ($hl['text'] ?? '') : $hl; ?>
      <div class="fas"><span class="f-no">B.0<?= $i + 1 ?></span><h3><?= esc($t) ?></h3></div>
      <?php endforeach; ?>
    </div>
    <div class="cta-mini rv">
      <div><h2>Ingin melihat langsung <em>budayanya?</em></h2><p>Hubungi kami untuk jadwal kunjungan dan open house.</p></div>
      <a class="btn btn-red" href="<?= base_url('contact') ?>">Hubungi Kami <svg><use href="#i-arrow"/></svg></a>
    </div>
  </div>
</section>
<?php endif; ?>

</main>
<?= $this->include(theme_view('layouts/footer')) ?>
