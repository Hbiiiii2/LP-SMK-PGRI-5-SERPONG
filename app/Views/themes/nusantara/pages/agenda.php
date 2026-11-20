<?= $this->include(theme_view('layouts/header')) ?>
<main id="konten">

<section class="pagehero">
  <div class="wrap">
    <div class="crumbs"><a href="<?= base_url('/') ?>">Beranda</a><i>/</i><span>Agenda</span></div>
    <h1>Kalender <em>kegiatan.</em></h1>
    <p class="lead">Jadwal resmi <?= esc($site_name) ?>. <?= count($events ?? []) ?> agenda terdekat.</p>
  </div>
</section>

<section class="sec">
  <div class="wrap">
    <?php if (!empty($events)): ?>
    <div class="agenda rv">
      <?php foreach ($events as $ev): $ts = strtotime($ev['event_date'] ?? ''); ?>
      <div class="ag-row" data-cat="akademik">
        <div class="ag-date"><div class="d"><?= $ts ? date('d', $ts) : '–' ?></div><div class="m"><?= $ts ? date("M 'y", $ts) : '' ?></div></div>
        <div class="ag-info"><span class="cat">Agenda<?= !empty($ev['event_time']) ? ' · ' . esc($ev['event_time']) : '' ?></span><h3><?= esc($ev['title']) ?></h3><p><?= esc($ev['description'] ?? '') ?></p></div>
        <span class="ag-loc"><svg><use href="#i-pin"/></svg><?= esc($ev['location'] ?: 'Sekolah') ?></span>
      </div>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <p style="color:var(--muted)">Belum ada agenda mendatang. Tambahkan melalui Admin → Events.</p>
    <?php endif; ?>
  </div>
</section>

</main>
<?= $this->include(theme_view('layouts/footer')) ?>
