<?= $this->include(theme_view('layouts/header')) ?>
<?php
function t_initials($name) {
    $p = preg_split('/\s+/', trim($name));
    $a = mb_substr($p[0] ?? '?', 0, 1, 'UTF-8');
    $b = isset($p[1]) ? mb_substr($p[1], 0, 1, 'UTF-8') : '.';
    return mb_strtoupper($a . $b, 'UTF-8');
}
function t_photo($t) {
    if (!empty($t['photo'])) {
        if (preg_match('#^https?://#', $t['photo']) || str_starts_with($t['photo'], '/')) return $t['photo'];
        return base_url('uploads/teachers/' . ltrim($t['photo'], '/'));
    }
    return '';
}
?>
<main id="konten">

<section class="pagehero">
  <div class="wrap">
    <div class="crumbs"><a href="<?= base_url('/') ?>">Beranda</a><i>/</i><span>Pengajar</span></div>
    <h1>Guru yang hafal <em>nama anak.</em></h1>
    <p class="lead"><?= count($teachers ?? []) ?> guru dan tenaga kependidikan <?= esc($site_name) ?>.</p>
  </div>
</section>

<section class="sec">
  <div class="wrap">
    <div class="sec-head rv"><span class="idx">A</span><span class="lbl">Direktori</span><span class="rule"></span></div>
    <?php if (!empty($teachers)): ?>
    <div class="t-grid rv">
      <?php foreach ($teachers as $i => $t): $ph = t_photo($t); ?>
      <div class="t-card">
        <div class="t-ava<?= $i % 2 ? ' b' : '' ?>">
          <?php if ($ph): ?><img src="<?= esc($ph) ?>" alt="<?= esc($t['name']) ?>" loading="lazy"><?php else: ?><?= esc(t_initials($t['name'])) ?><i>.</i><?php endif; ?>
        </div>
        <div class="t-info"><h3><?= esc($t['name']) ?></h3><div class="t-mapel"><?= esc($t['role'] ?? 'Guru') ?></div><p><?= esc($t['education'] ?: ($t['experience'] ?: 'Tenaga pendidik')) ?></p></div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <p style="color:var(--muted)">Belum ada data pengajar. Tambahkan melalui Admin → Teachers.</p>
    <?php endif; ?>
    <div class="cta-mini rv">
      <div><h2>Ingin bertemu <em>guru kami?</em></h2><p>Datang ke open house — berbicaralah langsung, tanpa perantara.</p></div>
      <a class="btn btn-white" href="<?= base_url('contact') ?>">Jadwal Kunjungan <svg><use href="#i-arrow"/></svg></a>
    </div>
  </div>
</section>

</main>
<?= $this->include(theme_view('layouts/footer')) ?>
