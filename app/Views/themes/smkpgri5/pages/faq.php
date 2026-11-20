<?= view(theme_view('layouts/header')) ?>

<?php
$list = $faq ?? [];
$cleanText = static fn (?string $s): string => strtr(trim((string) $s), ['Â·' => '·', 'â€”' => '—', 'â€“' => '–']);
?>

<?= view(theme_view('layouts/page_hero'), [
    'kicker'  => 'Pusat Bantuan',
    'heading' => 'Pertanyaan yang Sering Diajukan (FAQ)',
    'lead'    => 'Jawaban ringkas seputar pendaftaran siswa baru, program belajar, kegiatan siswa, dan administrasi sekolah.',
    'crumbs'  => [['label' => 'FAQ']],
]) ?>

<section class="sec sec-white">
  <div class="wrap-narrow">
    <?php if (! empty($list)): ?>
      <div class="faq-list">
        <?php foreach ($list as $idx => $item): ?>
          <?php $isOpen = $idx === 0; ?>
          <article class="faq-item<?= $isOpen ? ' open' : '' ?>">
            <button type="button" class="faq-q" aria-expanded="<?= $isOpen ? 'true' : 'false' ?>">
              <span><?= esc($cleanText($item['question'] ?? '')) ?></span>
              <?= lucide_icon('chevron-down', '', 16) ?>
            </button>
            <div class="faq-a">
              <p style="margin: 0;"><?= nl2br(esc($cleanText($item['answer'] ?? ''))) ?></p>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="empty-state">
        <?= lucide_icon('help-circle', '', 32) ?>
        <p>Belum ada daftar pertanyaan yang tersedia.</p>
      </div>
    <?php endif; ?>

    <div class="spmb-cta-box">
      <div>
        <h3>Pertanyaan Anda Belum Terjawab?</h3>
        <p>Silakan hubungi layanan administrasi dan informasi sekolah melalui formulir kontak.</p>
      </div>
      <a class="btn btn-primary" href="<?= esc(base_url('contact')) ?>">Hubungi Kami</a>
    </div>
  </div>
</section>

<?= view(theme_view('layouts/footer')) ?>
