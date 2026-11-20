<?= view(theme_view('layouts/header')) ?>

<?php
$settings       = $settings ?? [];
$cleanText      = static fn (?string $s): string => strtr(trim((string) $s), ['Â·' => '·', 'â€”' => '—', 'â€“' => '–']);

$siteName       = $cleanText($site_name ?? ($settings['site_name'] ?? 'SMK PGRI 5 Serpong')) ?: 'SMK PGRI 5 Serpong';
$contactAddr    = $cleanText($contact_address ?? ($settings['contact_address'] ?? ''));
$contactPhone   = $cleanText($contact_phone ?? ($settings['contact_phone'] ?? ''));
$contactEmail   = $cleanText($contact_email ?? ($settings['contact_email'] ?? ''));
$contactHours   = $cleanText($contact_hours ?? ($settings['contact_hours'] ?? ''));
$banner         = function_exists('theme_banner') ? theme_banner('contact') : [];
$flashOk        = session()->getFlashdata('success');
$flashErr       = session()->getFlashdata('error');
$flashErrs      = session()->getFlashdata('errors');
$mapEmbed       = trim((string) ($contact_map_embed ?? ($settings['contact_map_embed'] ?? ($settings['google_maps_embed'] ?? ''))));
?>

<?= view(theme_view('layouts/page_hero'), [
    'kicker'  => trim((string) ($banner['badge'] ?? '')) ?: 'Hubungi Kami',
    'heading' => trim((string) ($banner['title'] ?? '')) ?: ('Kontak & Lokasi ' . $siteName),
    'lead'    => trim((string) ($banner['subtitle'] ?? '')) ?: 'Layanan informasi resmi, alamat kampus, serta formulir pesan langsung ke pihak sekolah.',
    'crumbs'  => [['label' => 'Kontak']],
]) ?>

<section class="sec sec-white">
  <div class="wrap">
    <div class="contact-grid">
      <dl class="contact-dl">
        <?php if ($contactAddr !== ''): ?>
          <div class="contact-dl-item">
            <dt class="contact-dl-term"><?= lucide_icon('map-pin', '', 15) ?><span>Alamat Kampus</span></dt>
            <dd class="contact-dl-val"><?= esc($contactAddr) ?></dd>
          </div>
        <?php endif; ?>

        <?php if ($contactPhone !== ''): ?>
          <div class="contact-dl-item">
            <dt class="contact-dl-term"><?= lucide_icon('phone', '', 15) ?><span>Telepon</span></dt>
            <dd class="contact-dl-val">
              <a class="text-link is-blue tabular-nums" href="tel:<?= esc(preg_replace('/[^0-9+]/', '', $contactPhone)) ?>">
                <?= esc($contactPhone) ?>
              </a>
            </dd>
          </div>
        <?php endif; ?>

        <?php if ($contactEmail !== ''): ?>
          <div class="contact-dl-item">
            <dt class="contact-dl-term"><?= lucide_icon('mail', '', 15) ?><span>Email Resmi</span></dt>
            <dd class="contact-dl-val">
              <a class="text-link is-blue" href="mailto:<?= esc($contactEmail) ?>">
                <?= esc($contactEmail) ?>
              </a>
            </dd>
          </div>
        <?php endif; ?>

        <?php if ($contactHours !== ''): ?>
          <div class="contact-dl-item">
            <dt class="contact-dl-term"><?= lucide_icon('clock', '', 15) ?><span>Jam Operasional Layanan</span></dt>
            <dd class="contact-dl-val"><?= esc($contactHours) ?></dd>
          </div>
        <?php endif; ?>
      </dl>

      <div class="card">
        <div class="sec-index">Layanan Pesan</div>
        <h2 class="sec-title" style="font-size: 1.375rem; margin-bottom: var(--space-2);">Formulir Pesan Resmi</h2>
        <p class="sec-desc" style="margin-top: 0; margin-bottom: var(--space-5);">Sampaikan pertanyaan seputar SPMB, akademik, kerja sama industri, atau keperluan administrasi lainnya.</p>

        <?php if ($flashOk): ?>
          <div class="alert alert-success" role="status"><?= esc($flashOk) ?></div>
        <?php endif; ?>
        <?php if ($flashErr): ?>
          <div class="alert alert-error" role="alert"><?= esc($flashErr) ?></div>
        <?php endif; ?>
        <?php if (! empty($flashErrs) && is_array($flashErrs)): ?>
          <div class="alert alert-error" role="alert">
            <ul style="margin: 0; padding-left: 1.25rem;">
              <?php foreach ($flashErrs as $errItem): ?>
                <li><?= esc($errItem) ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>
        <div id="contactFeedback" class="alert hidden" role="status"></div>

        <form id="contactForm" action="<?= esc(base_url('contact/send')) ?>" method="post">
          <?= csrf_field() ?>
          <div class="hp-field" aria-hidden="true">
            <label for="contactHp">Website</label>
            <input type="text" id="contactHp" name="website" tabindex="-1" autocomplete="off">
          </div>

          <div class="form-group">
            <label class="form-label" for="contactName">Nama Lengkap</label>
            <input class="form-control" type="text" id="contactName" name="name" required value="<?= esc(old('name')) ?>" placeholder="Masukkan nama lengkap">
          </div>

          <div class="form-group">
            <label class="form-label" for="contactEmail">Alamat Email</label>
            <input class="form-control" type="email" id="contactEmail" name="email" required value="<?= esc(old('email')) ?>" placeholder="nama@email.com">
          </div>

          <div class="form-group">
            <label class="form-label" for="contactSubject">Subjek Pesan</label>
            <input class="form-control" type="text" id="contactSubject" name="subject" required value="<?= esc(old('subject')) ?>" placeholder="Contoh: Informasi Pendaftaran Siswa Baru">
          </div>

          <div class="form-group">
            <label class="form-label" for="contactMessage">Isi Pesan</label>
            <textarea class="form-control" id="contactMessage" name="message" required minlength="10" maxlength="5000" placeholder="Tuliskan pesan Anda secara jelas..."><?= esc(old('message')) ?></textarea>
            <span class="form-help">Minimal 10 karakter.</span>
          </div>

          <button type="submit" class="btn btn-primary">
            <?= lucide_icon('send', '', 16) ?>
            <span>Kirim Pesan</span>
          </button>
        </form>
      </div>
    </div>

    <?php if ($mapEmbed !== '' && (str_starts_with($mapEmbed, 'https://') || str_starts_with($mapEmbed, '<iframe'))): ?>
      <div class="map-frame">
        <?php if (str_starts_with($mapEmbed, '<iframe')): ?>
          <?= $mapEmbed ?>
        <?php else: ?>
          <iframe src="<?= esc($mapEmbed) ?>" title="Peta Lokasi <?= esc($siteName) ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </div>
</section>

<?= view(theme_view('layouts/footer')) ?>
