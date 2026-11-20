<?= $this->include(theme_view('layouts/header')) ?>
<main id="konten">

<section class="pagehero">
  <div class="wrap">
    <div class="crumbs"><a href="<?= base_url('/') ?>">Beranda</a><i>/</i><span>Prestasi</span></div>
    <h1>Papan <em>prestasi.</em></h1>
    <p class="lead"><?= count($achievements ?? []) ?> prestasi tercatat — dari akademik hingga olahraga dan seni.</p>
  </div>
</section>

<section class="sec">
  <div class="wrap">
    <div class="tabs rv" role="tablist" aria-label="Saring prestasi">
      <button class="tab on" data-f="semua">Semua</button>
      <button class="tab" data-f="akademik">Akademik</button>
      <button class="tab" data-f="olahraga">Olahraga</button>
      <button class="tab" data-f="seni">Seni &amp; Bahasa</button>
    </div>
    <?php if (!empty($achievements)): ?>
    <?php
    // Urutan mengikuti kolom "Urutan" (sort_order) dari admin — header tahun
    // mengikuti urutan item apa adanya (tidak diurut ulang per tahun).
    $sections = [];
    foreach ($achievements as $a) {
        $yr = ((int) ($a['year'] ?? 0)) ?: (int) date('Y');
        $last = array_key_last($sections);
        if ($last === null || $sections[$last]['year'] !== $yr) {
            $sections[] = ['year' => $yr, 'items' => []];
            $last = array_key_last($sections);
        }
        $sections[$last]['items'][] = $a;
    }
    function ach_photo($a) {
        if (!empty($a['photo'])) {
            if (preg_match('#^https?://#', $a['photo']) || str_starts_with($a['photo'], '/')) return $a['photo'];
            return base_url('uploads/achievements/' . ltrim($a['photo'], '/'));
        }
        return '';
    }
    function ach_cat($a) {
        $t = strtolower(($a['achievement'] ?? '') . ' ' . ($a['level'] ?? ''));
        if (str_contains($t, 'bola') || str_contains($t, 'futsal') || str_contains($t, 'basket') || str_contains($t, 'atlet') || str_contains($t, 'olahraga') || str_contains($t, 'cup') || str_contains($t, 'popda')) return 'olahraga';
        if (str_contains($t, 'seni') || str_contains($t, 'musik') || str_contains($t, 'tari') || str_contains($t, 'debat') || str_contains($t, 'bahasa') || str_contains($t, 'mtq') || str_contains($t, 'angklung') || str_contains($t, 'teater')) return 'seni';
        return 'akademik';
    }
    function medal_class($medal) {
        $m = strtolower($medal ?? '');
        if (str_contains($m, 'emas') || $m === 'gold' || str_contains($m, 'juara 1') || str_contains($m, 'terbaik')) return 'g';
        if (str_contains($m, 'perak') || $m === 'silver' || str_contains($m, 'juara 2') || str_contains($m, 'finalis')) return 's';
        return 'b';
    }
    ?>
    <?php foreach ($sections as $section): ?>
    <div class="yr-head rv"><span class="y"><?= esc($section['year']) ?></span><span class="c"><?= count($section['items']) ?> prestasi</span></div>
    <div class="rv">
      <?php foreach ($section['items'] as $a): ?>
      <div class="pr-row" data-cat="<?= ach_cat($a) ?>"><span style="display:flex;align-items:center;gap:13px"><?php $ph = ach_photo($a); if ($ph !== ''): ?><img src="<?= esc($ph) ?>" alt="<?= esc($a['student_name']) ?>" loading="lazy" style="width:42px;height:42px;object-fit:cover;border-radius:50%;flex:none"><?php endif; ?><span><h4><?= esc($a['achievement']) ?> — <?= esc($a['student_name']) ?></h4><p><?= esc($a['class_name'] ?? '') ?> · <?= esc($a['level'] ?? '') ?></p></span></span><span class="medal"><i class="<?= medal_class($a['medal'] ?? '') ?>"></i><?= esc(strtoupper($a['medal'] ?: $a['level'] ?: 'PRESTASI')) ?></span></div>
      <?php endforeach; ?>
    </div>
    <?php endforeach; ?>
    <?php else: ?>
    <p style="color:var(--muted)">Belum ada data prestasi. Tambahkan melalui Admin → Achievements.</p>
    <?php endif; ?>
    <div class="cta-mini rv">
      <div><h2>Anak Anda berikutnya <em>di papan ini?</em></h2><p>Pembinaan prestasi dimulai sejak awal tahun ajaran.</p></div>
      <a class="btn btn-red" href="<?= base_url('extracurriculars') ?>">Lihat Ekstrakurikuler <svg><use href="#i-arrow"/></svg></a>
    </div>
  </div>
</section>

</main>
<?= $this->include(theme_view('layouts/footer')) ?>
