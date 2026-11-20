<?= $this->include(theme_view('layouts/header')) ?>
<main id="konten">

<section class="pagehero">
  <div class="wrap">
    <div class="crumbs"><a href="<?= base_url('/') ?>">Beranda</a><i>/</i><span>FAQ</span></div>
    <h1>Yang sering <em>ditanyakan.</em></h1>
    <p class="lead">Tidak menemukan jawaban? Hubungi kami melalui halaman <a href="<?= base_url('contact') ?>">Kontak</a>.</p>
  </div>
</section>

<section class="sec">
  <div class="wrap">
    <?php if (!empty($faq)): ?>
    <div class="faq rv">
      <?php $prevCat = null; foreach ($faq as $f): $cat = trim((string) ($f['category'] ?? '')) !== '' ? $f['category'] : 'Umum'; ?>
      <?php if ($cat !== $prevCat): $prevCat = $cat; ?>
      <div class="faq-cat"><?= esc($cat) ?></div>
      <?php endif; ?>
      <div class="faq-item">
        <button class="faq-q" aria-expanded="false"><?= esc($f['question']) ?><span class="fx"></span></button>
        <div class="faq-a"><p><?= nl2br(esc($f['answer'])) ?></p></div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <p style="color:var(--muted)">Belum ada FAQ. Tambahkan melalui Admin → FAQ.</p>
    <?php endif; ?>
    <div class="cta-mini rv">
      <div><h2>Masih ada yang <em>mengganjal?</em></h2><p>Kami siap membantu pada jam layanan.</p></div>
      <a class="btn btn-white" href="<?= base_url('contact') ?>">Hubungi Kami <svg><use href="#i-arrow"/></svg></a>
    </div>
  </div>
</section>

</main>
<?= $this->include(theme_view('layouts/footer')) ?>
