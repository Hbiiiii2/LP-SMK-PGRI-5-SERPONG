<!-- ===== FOOTER : pinus gelap, buletin pil ===== -->
<footer class="g-foot">
<div class="wrap">
<div class="g-foot-top">
<div>
<p class="g-foot-brand"><?= esc($site_name) ?></p>
<p class="g-foot-desc"><?= esc($footer_description ?? $site_description ?? $site_tagline) ?></p>
</div>
</div>
<div class="g-foot-grid">
<div><p class="g-foot-h">Jelajah</p><ul><li><a href="<?= base_url('profile') ?>">Profil sekolah</a></li><li><a href="<?= base_url('program') ?>">Program</a></li><li><a href="<?= base_url('teachers') ?>">Pengajar</a></li><li><a href="<?= base_url('achievements') ?>">Prestasi</a></li></ul></div>
<div><?php $__svc = theme_setting_links('footer_services'); ?><p class="g-foot-h"><?= $__svc === [] ? 'Kesiswaan' : 'Program' ?></p><ul><?php if ($__svc !== []): ?><?php foreach ($__svc as $__l): ?><li><a href="<?= esc(theme_nav_url($__l['url'])) ?>"><?= esc($__l['label']) ?></a></li><?php endforeach; ?><?php else: ?><li><a href="<?= base_url('extracurriculars') ?>">Ekstrakurikuler</a></li><li><a href="<?= base_url('gallery') ?>">Galeri</a></li><li><a href="<?= base_url('testimonials') ?>">Testimoni</a></li><li><a href="<?= base_url('agenda') ?>">Agenda</a></li><?php endif; ?></ul></div>
<div><?php $__lnk = theme_setting_links('footer_links'); ?><p class="g-foot-h">Layanan</p><ul><?php if ($__lnk !== []): ?><?php foreach ($__lnk as $__l): ?><li><a href="<?= esc(theme_nav_url($__l['url'])) ?>"><?= esc($__l['label']) ?></a></li><?php endforeach; ?><?php else: ?><li><a href="<?= base_url('#spmb') ?>">SPMB</a></li><li><a href="<?= base_url('news') ?>">Berita</a></li><li><a href="<?= base_url('faq') ?>">FAQ</a></li><li><a href="<?= base_url('downloads') ?>">Unduhan</a></li><?php endif; ?></ul></div>
<div><p class="g-foot-h">Kontak</p><ul><li><?php if (!empty($contact_phone)): ?><a href="tel:<?= esc(preg_replace('/[^+\d]/', '', $contact_phone)) ?>"><?= esc($contact_phone) ?></a><?php else: ?><a href="<?= base_url('contact') ?>">Kontak</a><?php endif; ?></li><li><?php if (!empty($contact_email)): ?><a href="mailto:<?= esc($contact_email) ?>"><?= esc($contact_email) ?></a><?php endif; ?></li><li><a href="<?= base_url('contact') ?>"><?= esc($contact_address ?: 'Hubungi kami') ?></a></li><li><span class="g-foot-hours"><?= esc($contact_hours ?? '') ?></span></li></ul></div>
</div>
</div>
<div class="g-foot-base"><div class="wrap">
<?php
$fc = trim($footer_copyright ?? '');
$fc = preg_replace('/^©\s*\d{4}\s+.*?(—|–|-)\s*/u', '', $fc);
?>
<span>© <span id="year">2026</span> <?= esc(strtoupper($site_name)) ?><?php if ($fc !== ''): ?> · <?= esc($fc) ?><?php endif; ?></span>
<button class="g-top-btn" id="totop" aria-label="Kembali ke atas"><svg><use href="#i-up"/></svg></button>
</div></div>
</footer>

<div class="lightbox" id="lightbox" aria-hidden="true" role="dialog" aria-label="Pratinjau gambar">
<div class="lb-box">
<button class="lb-x" data-lb-close><svg><use href="#i-close"/></svg>Tutup</button>
<div class="g-art" id="lbFig"></div>
<div class="lb-cap"><span id="lbCap"></span><span>GALERI</span></div>
</div>
</div>

<div class="toast g-toast" id="toast" role="status"><svg><use href="#i-check"/></svg><span id="toastMsg">Pesan terkirim.</span></div>

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
