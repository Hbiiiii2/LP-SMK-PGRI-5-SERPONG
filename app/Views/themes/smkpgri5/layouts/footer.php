<?php
$settings       = $settings ?? [];
$cleanText      = static fn (?string $s): string => strtr(trim((string) $s), ['Â·' => '·', 'â€”' => '—', 'â€“' => '–']);

$siteName       = $cleanText($site_name ?? ($settings['site_name'] ?? 'SMK PGRI 5 Serpong')) ?: 'SMK PGRI 5 Serpong';
$siteTagline    = $cleanText($site_tagline ?? ($settings['site_tagline'] ?? 'Unggul dalam Prestasi, Luhur dalam Budi Pekerti'));
$logoUrl        = theme_asset('img/logo-smk-pgri-5-serpong.png');
$footerDesc     = $cleanText($footer_description ?? ($settings['footer_description'] ?? ($site_description ?? '')));
if ($footerDesc === '') {
    $footerDesc = 'Lembaga pendidikan resmi yang berkomitmen mencetak lulusan berkarakter, kompeten, dan siap bersaing.';
}
$contactAddress = $cleanText($contact_address ?? ($settings['contact_address'] ?? 'Serpong, Kota Tangerang Selatan'));
$contactPhone   = $cleanText($contact_phone ?? ($settings['contact_phone'] ?? ''));
$contactEmail   = $cleanText($contact_email ?? ($settings['contact_email'] ?? ''));
$contactHours   = $cleanText($contact_hours ?? ($settings['contact_hours'] ?? 'Senin – Jumat, 07.00 – 15.30 WIB'));

$footerNavLinks = function_exists('theme_setting_links') ? theme_setting_links('footer_links') : [];
if (empty($footerNavLinks)) {
    $footerNavLinks = [
        ['label' => 'Beranda', 'url' => '/'],
        ['label' => 'Profil Sekolah', 'url' => '/profile'],
        ['label' => 'Program Keahlian', 'url' => '/program'],
        ['label' => 'Berita & Pengumuman', 'url' => '/news'],
        ['label' => 'Prestasi Siswa', 'url' => '/achievements'],
    ];
}

$footerServiceLinks = function_exists('theme_setting_links') ? theme_setting_links('footer_services') : [];
if (empty($footerServiceLinks)) {
    $footerServiceLinks = [
        ['label' => 'Tenaga Pendidik', 'url' => '/teachers'],
        ['label' => 'Agenda Sekolah', 'url' => '/agenda'],
        ['label' => 'Ekstrakurikuler', 'url' => '/extracurriculars'],
        ['label' => 'Galeri Kegiatan', 'url' => '/gallery'],
        ['label' => 'Pusat Unduhan', 'url' => '/downloads'],
        ['label' => 'Tanya Jawab (FAQ)', 'url' => '/faq'],
    ];
}

$socialMap = [
    'social_instagram' => ['label' => 'Instagram', 'icon' => 'instagram', 'val' => $social_instagram ?? ($settings['social_instagram'] ?? '')],
    'social_facebook'  => ['label' => 'Facebook', 'icon' => 'facebook', 'val' => $social_facebook ?? ($settings['social_facebook'] ?? '')],
    'social_youtube'   => ['label' => 'YouTube', 'icon' => 'youtube', 'val' => $social_youtube ?? ($settings['social_youtube'] ?? '')],
    'social_tiktok'    => ['label' => 'TikTok', 'icon' => 'video', 'val' => $social_tiktok ?? ($settings['social_tiktok'] ?? '')],
];

$jsAsset = is_file(FCPATH . 'themes/' . active_theme() . '/js/script.min.js')
    ? 'js/script.min.js'
    : 'js/script.js';
$jsVer = @filemtime(FCPATH . 'themes/' . active_theme() . '/' . $jsAsset) ?: '1';
?>
</main>

<footer class="site-footer">
  <div class="wrap foot-main">
    <div>
      <div class="foot-brand">
        <div class="foot-logo-badge">
          <img src="<?= esc($logoUrl) ?>" alt="Logo Resmi <?= esc($siteName) ?>" width="46" height="46" loading="lazy" decoding="async">
        </div>
        <div>
          <div class="foot-brand-name"><?= esc($siteName) ?></div>
          <?php if ($siteTagline !== ''): ?>
            <div class="foot-brand-tag"><?= esc($siteTagline) ?></div>
          <?php endif; ?>
        </div>
      </div>
      <p class="foot-desc"><?= esc($footerDesc) ?></p>
      <div class="foot-social">
        <?php foreach ($socialMap as $meta): ?>
          <?php $url = trim((string) ($meta['val'] ?? '')); ?>
          <?php if ($url !== ''): ?>
            <a class="social-btn" href="<?= esc($url) ?>" target="_blank" rel="noopener noreferrer" aria-label="<?= esc($meta['label']) ?> <?= esc($siteName) ?>">
              <?= lucide_icon($meta['icon'], '', 16) ?>
            </a>
          <?php endif; ?>
        <?php endforeach; ?>
      </div>
    </div>

    <div>
      <div class="foot-col-title">Navigasi Utama</div>
      <ul class="foot-links">
        <?php foreach ($footerNavLinks as $link): ?>
          <?php
          $rawUrl = (string) ($link['url'] ?? '/');
          $href = function_exists('theme_nav_url') ? theme_nav_url($rawUrl) : (str_starts_with($rawUrl, 'http') ? $rawUrl : base_url(ltrim($rawUrl, '/')));
          ?>
          <li><a href="<?= esc($href) ?>"><?= esc($link['label'] ?? '') ?></a></li>
        <?php endforeach; ?>
      </ul>
    </div>

    <div>
      <div class="foot-col-title">Layanan &amp; Direktori</div>
      <ul class="foot-links">
        <?php foreach ($footerServiceLinks as $link): ?>
          <?php
          $rawUrl = (string) ($link['url'] ?? '/');
          $href = function_exists('theme_nav_url') ? theme_nav_url($rawUrl) : (str_starts_with($rawUrl, 'http') ? $rawUrl : base_url(ltrim($rawUrl, '/')));
          ?>
          <li><a href="<?= esc($href) ?>"><?= esc($link['label'] ?? '') ?></a></li>
        <?php endforeach; ?>
      </ul>
    </div>

    <div>
      <div class="foot-col-title">Informasi Kontak</div>
      <div class="foot-contact-list">
        <?php if ($contactAddress !== ''): ?>
          <div class="foot-contact-item">
            <?= lucide_icon('map-pin', '', 15) ?>
            <span><?= esc($contactAddress) ?></span>
          </div>
        <?php endif; ?>
        <?php if ($contactPhone !== ''): ?>
          <div class="foot-contact-item">
            <?= lucide_icon('phone', '', 15) ?>
            <a class="tabular-nums" href="tel:<?= esc(preg_replace('/[^0-9+]/', '', $contactPhone)) ?>"><?= esc($contactPhone) ?></a>
          </div>
        <?php endif; ?>
        <?php if ($contactEmail !== ''): ?>
          <div class="foot-contact-item">
            <?= lucide_icon('mail', '', 15) ?>
            <a href="mailto:<?= esc($contactEmail) ?>"><?= esc($contactEmail) ?></a>
          </div>
        <?php endif; ?>
        <?php if ($contactHours !== ''): ?>
          <div class="foot-contact-item">
            <?= lucide_icon('clock', '', 15) ?>
            <span><?= esc($contactHours) ?></span>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <div class="foot-bottom">
    <div class="wrap foot-bottom-inner">
      <div class="foot-bottom-copy">
        <span>&copy; <span id="year"><?= esc(date('Y')) ?></span> <?= esc($siteName) ?>. Seluruh hak cipta dilindungi.</span>
        <span class="foot-credit">Powered by <a href="https://aybisa-workspace.my.id/" target="_blank" rel="noopener noreferrer">Aybisa Studio</a></span>
      </div>
      <button type="button" class="totop" id="totop" aria-label="Kembali ke atas halaman">
        <?= lucide_icon('arrow-up', '', 16) ?>
      </button>
    </div>
  </div>
</footer>

<div class="lightbox" id="lightbox" role="dialog" aria-modal="true" aria-labelledby="lightboxCap" aria-hidden="true">
  <div class="lightbox-inner">
    <button type="button" class="lightbox-close" id="lightboxClose" aria-label="Tutup pratinjau gambar">
      <?= lucide_icon('x', '', 18) ?>
    </button>
    <img class="lightbox-img" id="lightboxImg" src="" alt="Pratinjau galeri">
    <p class="lightbox-cap" id="lightboxCap"></p>
  </div>
</div>

<div class="toast" id="toast" role="status" aria-live="polite">
  <?= lucide_icon('info', '', 16) ?>
  <span id="toastMsg">Pesan terkirim.</span>
</div>

<script defer src="<?= esc(theme_asset($jsAsset)) ?>?v=<?= esc((string) $jsVer) ?>"></script>
</body>
</html>
