<!-- ============ FOOTER ============ -->
<footer>
  <div class="wrap foot-grid">
    <div>
      <svg width="46" height="46" viewBox="0 0 64 64" aria-hidden="true">
        <path d="M32 3 L55 11 V31 C55 45 45 55 32 60 C19 55 9 45 9 31 V11 Z" fill="#fff"/>
        <path d="M32 8 L50 14.5 V31 C50 42 42 50.5 32 54.5 C22 50.5 14 42 14 31 V14.5 Z" fill="none" stroke="#17356B" stroke-width="2"/>
        <path d="M22 40 L32 28 L42 40" fill="none" stroke="#17356B" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"/>
        <circle cx="32" cy="22" r="5.5" fill="#D22630"/>
      </svg>
      <div class="foot-brand"><?= esc($site_name) ?></div>
      <p class="foot-desc"><?= esc($footer_description ?? $site_description ?? $site_tagline) ?></p>
    </div>
    <div>
      <?php $__svc = theme_setting_links('footer_services'); ?>
      <div class="foot-h"><?= $__svc === [] ? 'Jelajah' : 'Program' ?></div>
      <ul class="foot-links">
        <?php if ($__svc !== []): ?>
        <?php foreach ($__svc as $__l): ?><li><a href="<?= esc(theme_nav_url($__l['url'])) ?>"><?= esc($__l['label']) ?></a></li><?php endforeach; ?>
        <?php else: ?>
        <li><a href="<?= base_url('profile') ?>">Profil sekolah</a></li>
        <li><a href="<?= base_url('program') ?>">Program unggulan</a></li>
        <li><a href="<?= base_url('teachers') ?>">Pengajar</a></li>
        <li><a href="<?= base_url('achievements') ?>">Prestasi</a></li>
        <li><a href="<?= base_url('extracurriculars') ?>">Ekstrakurikuler</a></li>
        <li><a href="<?= base_url('gallery') ?>">Galeri</a></li>
        <?php endif; ?>
      </ul>
    </div>
    <div>
      <?php $__lnk = theme_setting_links('footer_links'); ?>
      <div class="foot-h">Layanan</div>
      <ul class="foot-links">
        <?php if ($__lnk !== []): ?>
        <?php foreach ($__lnk as $__l): ?><li><a href="<?= esc(theme_nav_url($__l['url'])) ?>"><?= esc($__l['label']) ?></a></li><?php endforeach; ?>
        <?php else: ?>
        <li><a href="<?= base_url('#spmb') ?>">SPMB</a></li>
        <li><a href="<?= base_url('agenda') ?>">Agenda</a></li>
        <li><a href="<?= base_url('news') ?>">Berita</a></li>
        <li><a href="<?= base_url('faq') ?>">FAQ</a></li>
        <li><a href="<?= base_url('downloads') ?>">Download file</a></li>
        <li><a href="<?= base_url('contact') ?>">Kontak</a></li>
        <?php endif; ?>
      </ul>
    </div>
    <div>
      <div class="foot-h">Kontak</div>
      <ul class="foot-links">
        <?php if (!empty($contact_address)): ?><li><?= esc($contact_address) ?></li><?php endif; ?>
        <?php if (!empty($contact_phone)): ?><li><a href="tel:<?= esc(preg_replace('/[^+\d]/', '', $contact_phone)) ?>"><?= esc($contact_phone) ?></a></li><?php endif; ?>
        <?php if (!empty($contact_email)): ?><li><a href="mailto:<?= esc($contact_email) ?>"><?= esc($contact_email) ?></a></li><?php endif; ?>
        <?php if (!empty($contact_hours)): ?><li><?= esc($contact_hours) ?></li><?php endif; ?>
      </ul>
    </div>
  </div>
  <div class="foot-bottom">
    <div class="wrap">
      <?php
      $fc = trim($footer_copyright ?? '');
      // Hindari duplikat "© TAHUN Nama" karena template sudah mencetaknya sendiri
      $fc = preg_replace('/^©\s*\d{4}\s+.*?(—|–|-)\s*/u', '', $fc);
      ?>
      <span>© <span id="year">2026</span> <?= esc(strtoupper($site_name)) ?><?php if ($fc !== ''): ?> · <?= esc($fc) ?><?php endif; ?></span>
      <button class="totop" id="totop" aria-label="Kembali ke atas"><svg><use href="#i-up"/></svg></button>
    </div>
  </div>
</footer>

<div class="lightbox" id="lightbox" aria-hidden="true" role="dialog" aria-label="Pratinjau gambar">
  <div class="lb-box">
    <button class="lb-x" data-lb-close><svg><use href="#i-close"/></svg>Tutup</button>
    <div class="g-art" id="lbFig"></div>
    <div class="lb-cap"><span id="lbCap"></span><span>GALERI</span></div>
  </div>
</div>

<div class="toast" id="toast" role="status"><svg><use href="#i-check"/></svg><span id="toastMsg">Pesan terkirim.</span></div>

<script defer src="<?= theme_asset('js/script.min.js') ?>"></script>
<script>
// Refresh CSRF token on cached pages — reads token from cookie
(function(){
  var fields = document.querySelectorAll('[name="csrf_test_name"]');
  if (!fields.length) return;
  var match = document.cookie.match(/(?:^|;\s*)csrf_cookie_name=([^;]*)/);
  if (match) {
    var token = decodeURIComponent(match[1]);
    for (var i = 0; i < fields.length; i++) fields[i].value = token;
  }
})();
// Flash messages from session -> toast
(function(){
  <?php $flashOk = session()->getFlashdata('success'); $flashErr = session()->getFlashdata('error'); ?>
  <?php if ($flashOk || $flashErr): ?>
  document.addEventListener('DOMContentLoaded', function(){
    var t = document.getElementById('toast'), m = document.getElementById('toastMsg');
    if (!t || !m) return;
    m.textContent = <?= json_encode($flashOk ?: $flashErr) ?>;
    t.classList.add('show');
    setTimeout(function(){ t.classList.remove('show'); }, 3600);
  });
  <?php endif; ?>
})();
</script>
</body>
</html>
