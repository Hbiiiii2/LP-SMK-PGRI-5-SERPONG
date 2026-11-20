<?= $this->include(theme_view('layouts/header')) ?>
<main id="konten">

<?php $__b = theme_banner('contact'); ?>
<section class="pagehero">
  <div class="wrap">
    <div class="crumbs"><a href="<?= base_url('/') ?>">Beranda</a><i>/</i><span>Kontak</span></div>
    <?php if ($__b['badge'] !== ''): ?><span class="hero-badge"><?= esc($__b['badge']) ?></span><?php endif; ?>
    <h1><?= $__b['title'] !== '' ? esc($__b['title']) : 'Datang, lihat, <em>bertanya.</em>' ?></h1>
    <p class="lead"><?= $__b['subtitle'] !== '' ? esc($__b['subtitle']) : 'Balasan maks. 1×24 jam pada hari kerja. Untuk SPMB, pilih keperluan “Pendaftaran”.' ?></p>
  </div>
</section>

<section class="sec">
  <div class="wrap">
    <?php $errors = session()->getFlashdata('errors'); ?>
    <?php if ($errors): ?>
    <ul class="f-errors rv"><?php foreach ((array) $errors as $e): ?><li><?= esc($e) ?></li><?php endforeach; ?></ul>
    <?php endif; ?>
    <div class="kontak-grid">
      <div class="rv">
        <div class="k-item"><svg><use href="#i-pin"/></svg><div><h3>Alamat</h3><p><?= esc($contact_address ?: '-') ?></p></div></div>
        <div class="k-item"><svg><use href="#i-phone"/></svg><div><h3>Telepon / WA</h3><p><a href="tel:<?= esc(preg_replace('/[^+\d]/', '', $contact_phone ?? '')) ?>"><?= esc($contact_phone ?: '-') ?></a></p></div></div>
        <div class="k-item"><svg><use href="#i-mail"/></svg><div><h3>Surel</h3><p><a href="mailto:<?= esc($contact_email ?? '') ?>"><?= esc($contact_email ?: '-') ?></a></p></div></div>
        <div class="k-item"><svg><use href="#i-clock"/></svg><div><h3>Jam layanan</h3><p><?= esc($contact_hours ?: '-') ?></p></div></div>
      </div>
      <form class="form rv" id="contactForm" action="<?= base_url('contact/send') ?>" method="post" novalidate>
        <?= csrf_field() ?>
        <div style="position:absolute;left:-9999px" aria-hidden="true"><input type="text" name="website" tabindex="-1" autocomplete="off"></div>
        <h3>Kirim pesan</h3>
        <p>Balasan maks. 1×24 jam pada hari kerja.</p>
        <div class="f-row">
          <div class="f-group"><label for="fNama">Nama lengkap</label><input id="fNama" name="name" type="text" placeholder="cth. Budi Santoso" required value="<?= esc(old('name')) ?>"></div>
          <div class="f-group"><label for="fMail">Email</label><input id="fMail" name="email" type="email" placeholder="cth. budi@email.com" required value="<?= esc(old('email')) ?>"></div>
        </div>
        <div class="f-group"><label for="fPerlu">Keperluan</label>
          <select id="fPerlu" name="subject">
            <option value="Pendaftaran (SPMB)"<?= old('subject') === 'Pendaftaran (SPMB)' ? ' selected' : '' ?>>Pendaftaran (SPMB)</option>
            <option value="Jadwal survei / open house"<?= old('subject') === 'Jadwal survei / open house' ? ' selected' : '' ?>>Jadwal survei / open house</option>
            <option value="Mutasi / pindahan"<?= old('subject') === 'Mutasi / pindahan' ? ' selected' : '' ?>>Mutasi / pindahan</option>
            <option value="Kerja sama & lainnya"<?= old('subject') === 'Kerja sama & lainnya' ? ' selected' : '' ?>>Kerja sama &amp; lainnya</option>
          </select>
        </div>
        <div class="f-group"><label for="fPesan">Pesan</label><textarea id="fPesan" name="message" placeholder="Tulis pertanyaan Anda…" maxlength="5000" required><?= esc(old('message')) ?></textarea></div>
        <button class="btn btn-navy" type="submit" style="width:100%;justify-content:center">Kirim Pesan <svg><use href="#i-arrow"/></svg></button>
      </form>
    </div>
  </div>
</section>

</main>
<?= $this->include(theme_view('layouts/footer')) ?>
