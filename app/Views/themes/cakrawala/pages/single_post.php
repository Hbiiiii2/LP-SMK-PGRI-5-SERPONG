<?= $this->include(theme_view('layouts/header')) ?>
<?php
$article = $post ?? ($news_item ?? []);
function p_img($v) {
    if (empty($v)) return '';
    if (preg_match('#^https?://#', $v) || str_starts_with($v, '/') || str_starts_with($v, 'data:')) return $v;
    return base_url(ltrim($v, '/'));
}
$cover = p_img($article['image'] ?? '');
$artTags = [];
if (!empty($article['tags'])) {
    $t = json_decode($article['tags'], true);
    if (is_array($t)) $artTags = $t;
}
function rp_initial($title) {
    $t = trim((string) $title);
    return $t !== '' ? mb_strtoupper(mb_substr($t, 0, 1, 'UTF-8'), 'UTF-8') : 'B';
}
$catFirst = trim(explode(',', $article['category'] ?? 'Berita')[0]);
$initials = function ($n) {
    $p = preg_split('/\s+/', trim($n));
    return mb_strtoupper(mb_substr($p[0] ?? '?', 0, 1, 'UTF-8') . (isset($p[1]) ? mb_substr($p[1], 0, 1, 'UTF-8') : '.'), 'UTF-8');
};
?>
<div class="progress" id="progress" aria-hidden="true"></div>
<main id="konten">

<article class="sec artikel">
  <div class="wrap artikel-wrap">
    <div class="crumbs rv"><a href="<?= base_url('/') ?>">Beranda</a><i>/</i><a href="<?= base_url('news') ?>">Berita</a><i>/</i><span><?= esc(mb_strimwidth($article['title'] ?? '', 0, 40, '…')) ?></span></div>
    <?php $__b = theme_banner('single_post'); ?>
    <?php if ($__b['badge'] !== ''): ?><div class="rv"><span class="hero-badge"><?= esc($__b['badge']) ?></span></div><?php endif; ?>
    <div class="catline rv"><span class="cat"><?= esc($catFirst) ?></span><span class="dt"><?= date('j M Y', strtotime($article['published_at'] ?? $article['created_at'] ?? 'now')) ?><?php if (!empty($article['views'] ?? $article['view_count'] ?? '')): ?> · <?= number_format($article['views'] ?? $article['view_count']) ?> dibaca<?php endif; ?></span></div>
    <h1 class="rv"><?= esc($article['title'] ?? '') ?></h1>
    <div class="byline rv">
      <span class="by-ava"><?= esc($initials($article['author'] ?? 'A')) ?></span>
      <span><strong><?= esc($article['author'] ?? 'Admin') ?></strong><br><span class="by-sub"><?= date('j F Y', strtotime($article['published_at'] ?? $article['created_at'] ?? 'now')) ?> · Redaksi <?= esc($site_name) ?></span></span>
    </div>

    <?php if ($cover): ?>
    <figure class="art-fig rv">
      <img src="<?= esc($cover) ?>" alt="<?= esc($article['title'] ?? '') ?>" loading="lazy">
      <figcaption><span><?= esc($article['title'] ?? '') ?></span><span class="no">FIG. 01</span></figcaption>
    </figure>
    <?php endif; ?>

    <div class="art-body rv">
      <?= $article['content'] ?? '' ?>
    </div>

    <?php if (!empty($artTags)): ?>
    <div class="art-tags rv"><span>TAG:</span><?php foreach ($artTags as $tg): ?><a href="<?= base_url('news?search=' . urlencode($tg)) ?>"><?= esc($tg) ?></a><?php endforeach; ?></div>
    <?php endif; ?>

    <div class="share rv">
      <span class="share-lbl">Bagikan artikel</span>
      <span class="share-btns">
        <button class="sbtn" id="copyLink"><svg><use href="#i-link"/></svg>Salin tautan</button>
        <a class="sbtn" href="https://wa.me/?text=<?= urlencode(($article['title'] ?? '') . ' ' . current_url()) ?>" target="_blank" rel="noopener">WhatsApp</a>
        <a class="sbtn" href="https://twitter.com/intent/tweet?text=<?= urlencode($article['title'] ?? '') ?>&url=<?= urlencode(current_url()) ?>" target="_blank" rel="noopener">X</a>
        <a class="sbtn" href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(current_url()) ?>" target="_blank" rel="noopener">Facebook</a>
      </span>
    </div>

    <div class="author-box rv">
      <span class="by-ava big"><?= esc($initials($article['author'] ?? 'A')) ?></span>
      <div><div class="ab-lbl">Ditulis oleh</div><strong><?= esc($article['author'] ?? 'Admin') ?></strong><p>Redaksi <?= esc($site_name) ?>.</p></div>
    </div>

    <?php if (!empty($prev_post) || !empty($next_post)): ?>
    <nav class="prevnext rv" aria-label="Artikel sebelum dan sesudah">
      <?php if (!empty($prev_post)): ?><a href="<?= base_url('news/' . esc($prev_post['slug'])) ?>"><span class="pn-lbl">← Sebelumnya</span><span class="pn-t"><?= esc($prev_post['title']) ?></span></a>
      <?php else: ?><span></span><?php endif; ?>
      <?php if (!empty($next_post)): ?><a href="<?= base_url('news/' . esc($next_post['slug'])) ?>"><span class="pn-lbl">Berikutnya →</span><span class="pn-t"><?= esc($next_post['title']) ?></span></a><?php endif; ?>
    </nav>
    <?php endif; ?>

    <!-- ============ KOMENTAR (backend CMS) ============ -->
    <section class="komen" id="komentar" aria-label="Komentar pembaca">
      <div class="s-head rv"><span class="s-chip sun">◆</span><span class="s-kick"><?= count($comments ?? []) ?> Komentar</span><span class="s-rule"></span></div>

      <?php $errors = session()->getFlashdata('errors'); ?>
      <?php if ($errors): ?>
      <ul class="rv" style="list-style:none;padding:14px 18px;border:1px solid #f0b6bb;background:#fdf0f1;border-radius:8px;color:#a8141f"><?php foreach ((array) $errors as $e): ?><li><?= esc($e) ?></li><?php endforeach; ?></ul>
      <?php endif; ?>

      <?php if (!empty($comments)): ?>
      <ol class="c-list">
        <?php foreach ($comments as $cm): ?>
        <li class="c-item">
          <span class="c-ava<?= (crc32($cm['name']) % 2) ? ' alt' : '' ?>"><?= esc(mb_strtoupper(mb_substr($cm['name'], 0, 1, 'UTF-8') . '.', 'UTF-8')) ?></span>
          <div class="c-main">
            <div class="c-head"><strong><?= esc($cm['name']) ?></strong><span><?= date('j M Y', strtotime($cm['created_at'])) ?></span></div>
            <p class="c-text"><?= esc($cm['comment'] ?? $cm['message'] ?? '') ?></p>
            <div class="c-act"><button type="button" class="c-reply" data-comment-id="<?= (int) $cm['id'] ?>" data-to="<?= esc($cm['name']) ?>">Balas</button></div>
          </div>
        </li>
        <?php endforeach; ?>
      </ol>
      <?php endif; ?>

      <form class="c-form" id="cForm" action="<?= base_url('news/' . esc($article['slug']) . '/comment') ?>" method="post" novalidate>
        <?= csrf_field() ?>
        <div style="position:absolute;left:-9999px" aria-hidden="true"><input type="text" name="website" tabindex="-1" autocomplete="off"></div>
        <input type="hidden" name="parent_id" id="comment_parent_id" value="">
        <h3>Tinggalkan komentar</h3>
        <p id="cReplyInfo" hidden style="margin:0 0 .75rem">Membalas <strong id="cReplyName"></strong> · <button type="button" id="cReplyCancel" style="background:none;border:0;padding:0;color:inherit;text-decoration:underline;cursor:pointer">Batal</button></p>
        <p class="c-note">Email tidak dipublikasikan. Komentar tampil setelah disetujui admin.</p>
        <div class="f-row">
          <div class="f-group"><label for="cNama">Nama lengkap</label><input id="cNama" name="name" type="text" placeholder="cth. Budi Santoso" maxlength="100" required value="<?= esc(old('name')) ?>"></div>
          <div class="f-group"><label for="cMail">Email</label><input id="cMail" name="email" type="email" placeholder="cth. budi@email.com" maxlength="100" required value="<?= esc(old('email')) ?>"></div>
        </div>
        <div class="f-group"><label for="cIsi">Komentar <span id="cLeft">0/2000</span></label><textarea id="cIsi" name="message" placeholder="Tulis dukungan atau pertanyaan Anda…" maxlength="2000" required><?= esc(old('message')) ?></textarea></div>
        <button class="btn btn-blue" type="submit">Kirim Komentar <svg><use href="#i-arrow"/></svg></button>
      </form>
    </section>

    <?php if (!empty($related)): ?>
    <div class="rel">
      <div class="s-head rv"><span class="s-chip sun">◆</span><span class="s-kick">Baca juga</span><span class="s-rule"></span><a class="s-more" href="<?= base_url('news') ?>">Semua berita →</a></div>
      <div class="news-side" style="max-width:820px">
        <?php foreach (array_slice($related, 0, 3) as $rp): $ri = p_img($rp['image'] ?? ''); $rcat = trim(explode(',', $rp['category'] ?? 'Berita')[0]); ?>
        <a class="nl-item" href="<?= base_url('news/' . esc($rp['slug'])) ?>">
          <?php if ($ri): ?>
          <span class="nl-thumb"><img src="<?= esc($ri) ?>" alt="<?= esc($rp['title']) ?>" loading="lazy" style="width:100%;height:100%;object-fit:cover"></span>
          <?php else: ?>
          <span class="nl-thumb" aria-hidden="true" style="display:flex;align-items:center;justify-content:center;background:#E4ECFA;color:#0C2F73;font-weight:800;font-size:30px"><?= esc(rp_initial($rp['title'])) ?></span>
          <?php endif; ?>
          <span><span class="catline"><span class="cat"><?= esc($rcat) ?></span><span class="dt"><?= date('j M Y', strtotime($rp['published_at'] ?? $rp['created_at'])) ?></span></span><span class="nl-title"><?= esc($rp['title']) ?></span></span>
        </a>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>

  </div>
</article>

</main>
<?= $this->include(theme_view('layouts/footer')) ?>
<script>
/* progres baca + salin tautan (khusus detail berita) */
(function(){
  var bar=document.getElementById('progress');
  function prog(){
    if(!bar) return;
    var h=document.documentElement,max=h.scrollHeight-h.clientHeight;
    bar.style.width=(max>0?(h.scrollTop/max)*100:0)+'%';
  }
  window.addEventListener('scroll',prog,{passive:true}); prog();
  var cp=document.getElementById('copyLink');
  if(cp) cp.addEventListener('click',function(){
    var url=location.href;
    function done(){
      var t=document.getElementById('toast'),m=document.getElementById('toastMsg');
      if(t&&m){ m.textContent='Tautan artikel disalin.'; t.classList.add('show'); setTimeout(function(){t.classList.remove('show');},3200); }
    }
    if(navigator.clipboard&&navigator.clipboard.writeText){ navigator.clipboard.writeText(url).then(done,done); }
    else{ var ta=document.createElement('textarea'); ta.value=url; document.body.appendChild(ta); ta.select(); try{document.execCommand('copy');}catch(e){} document.body.removeChild(ta); done(); }
  });
  /* balas komentar: isi parent_id + tampilkan penanda balasan */
  var cForm=document.getElementById('cForm'),
      cPid=document.getElementById('comment_parent_id'),
      cIsi=document.getElementById('cIsi'),
      cLeft=document.getElementById('cLeft'),
      cInfo=document.getElementById('cReplyInfo'),
      cName=document.getElementById('cReplyName'),
      cCancel=document.getElementById('cReplyCancel');
  document.querySelectorAll('.c-reply').forEach(function(btn){
    btn.addEventListener('click',function(){
      var item=btn.closest('.c-item');
      if(cPid) cPid.value=(item&&item.dataset.commentId)?item.dataset.commentId:'';
      if(cName) cName.textContent=btn.dataset.to||'';
      if(cInfo) cInfo.hidden=false;
      if(cIsi) cIsi.focus();
      if(cForm) cForm.scrollIntoView({behavior:'smooth',block:'center'});
    });
  });
  if(cCancel&&cInfo&&cPid) cCancel.addEventListener('click',function(){ cPid.value=''; cInfo.hidden=true; });
  /* penghitung karakter (maks 2000, sama dengan aturan server) */
  if(cIsi&&cLeft){
    var cMax=parseInt(cIsi.getAttribute('maxlength'),10)||2000;
    var cCount=function(){ cLeft.textContent=cIsi.value.length+'/'+cMax; };
    cIsi.addEventListener('input',cCount); cCount();
  }
})();
</script>
