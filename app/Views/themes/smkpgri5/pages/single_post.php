<?= view(theme_view('layouts/header')) ?>

<?php
$postData    = $post ?? ($article ?? []);
$postTitle   = (string) ($postData['title'] ?? 'Detail Berita');
$postSlug    = (string) ($postData['slug'] ?? '');
$postCatRaw  = trim((string) ($postData['category'] ?? 'Berita'));
$postCat     = trim(explode(',', $postCatRaw)[0] ?? 'Berita');
$isUrgent    = str_contains(mb_strtolower($postCat), 'pengumuman') || str_contains(mb_strtolower($postCat), 'spmb');
$rawDate     = (string) ($postData['published_at'] ?? ($postData['created_at'] ?? ''));
$dateLabel   = $rawDate !== '' ? date('d F Y', strtotime($rawDate)) : '';
$author      = trim((string) ($postData['author'] ?? 'Redaksi Sekolah'));
$viewCount   = (int) ($postData['view_count'] ?? ($postData['views'] ?? 0));

$resolveMedia = static function (?string $raw): string {
    $v = trim((string) $raw);
    if ($v === '') {
        return '';
    }
    if (preg_match('#^https?://localhost(?::\d+)?/(.*)$#i', $v, $m)) {
        return base_url(ltrim($m[1], '/'));
    }
    if (str_starts_with($v, 'http://') || str_starts_with($v, 'https://') || str_starts_with($v, '/') || str_starts_with($v, 'data:')) {
        return $v;
    }
    return base_url(ltrim($v, '/'));
};

$imageUrl    = $resolveMedia($postData['image'] ?? '');
$relatedList = $related ?? ($related_news ?? ($recent_news ?? []));
$recentList  = $recent_news ?? [];
$commentRows = $comments ?? [];
$cats        = $categories ?? [];
$postTags    = [];
if (! empty($postData['tags'])) {
    $decoded = is_array($postData['tags']) ? $postData['tags'] : json_decode((string) $postData['tags'], true);
    if (is_array($decoded)) {
        $postTags = array_values(array_filter(array_map('trim', $decoded)));
    }
}

$flashOk     = session()->getFlashdata('success');
$flashErr    = session()->getFlashdata('error');
$flashErrs   = session()->getFlashdata('errors');

$topComments = [];
$repliesByParent = [];
foreach ($commentRows as $c) {
    $pid = (int) ($c['parent_id'] ?? 0);
    if ($pid > 0) {
        $repliesByParent[$pid][] = $c;
    } else {
        $topComments[] = $c;
    }
}
?>

<?= view(theme_view('layouts/page_hero'), [
    'kicker'  => $postCat,
    'heading' => $postTitle,
    'lead'    => trim((string) ($postData['excerpt'] ?? '')),
    'crumbs'  => [
        ['label' => 'Berita', 'url' => base_url('news')],
        ['label' => $postTitle],
    ],
]) ?>

<section class="sec sec-white">
  <div class="wrap article-layout">
    <article class="article-main">
      <div class="news-meta" style="margin-bottom: var(--space-5); padding-bottom: var(--space-4); border-bottom: 1px solid var(--theme_color_border_default);">
        <div class="sec-actions">
          <span class="badge <?= $isUrgent ? 'badge-red' : 'badge-blue' ?>"><?= esc($postCat) ?></span>
          <span><?= lucide_icon('user', '', 14) ?> <?= esc($author) ?></span>
          <?php if ($dateLabel !== ''): ?>
            <span class="tabular-nums"><?= lucide_icon('calendar', '', 14) ?> <?= esc($dateLabel) ?></span>
          <?php endif; ?>
          <?php if ($viewCount > 0): ?>
            <span class="tabular-nums"><?= lucide_icon('eye', '', 14) ?> <?= esc(number_format($viewCount)) ?> kali dibaca</span>
          <?php endif; ?>
        </div>
        <button type="button" class="btn btn-ghost btn-sm" data-copy-url="<?= esc(current_url()) ?>">
          <?= lucide_icon('link-2', '', 14) ?>
          <span>Salin Tautan</span>
        </button>
      </div>

      <?php if ($imageUrl !== ''): ?>
        <figure class="article-cover">
          <img src="<?= esc($imageUrl) ?>" alt="<?= esc($postTitle) ?>">
        </figure>
      <?php endif; ?>

      <div class="prose">
        <?= $postData['content'] ?? '<p>Konten artikel tidak tersedia.</p>' ?>
      </div>

      <?php if (! empty($postTags)): ?>
        <div class="filter-tabs" style="margin-top: var(--space-6); padding-top: var(--space-5); border-top: 1px solid var(--theme_color_border_default);">
          <span class="page-hero-kicker" style="margin: 0; align-self: center; margin-right: 0.4rem;">Tag:</span>
          <?php foreach ($postTags as $tg): ?>
            <a class="badge" href="<?= esc(base_url('news?search=' . urlencode($tg))) ?>">#<?= esc($tg) ?></a>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <?php if (! empty($prev_post) || ! empty($next_post)): ?>
        <nav class="prev-next-grid" aria-label="Navigasi artikel sebelum dan sesudah">
          <?php if (! empty($prev_post)): ?>
            <a class="prev-next-link" href="<?= esc(base_url('news/' . ($prev_post['slug'] ?? ''))) ?>">
              <span class="page-hero-kicker" style="margin-bottom: 0;">&larr; Artikel Sebelumnya</span>
              <span class="sidebar-post-title"><?= esc($prev_post['title'] ?? '') ?></span>
            </a>
          <?php else: ?>
            <span></span>
          <?php endif; ?>
          <?php if (! empty($next_post)): ?>
            <a class="prev-next-link" style="text-align: right;" href="<?= esc(base_url('news/' . ($next_post['slug'] ?? ''))) ?>">
              <span class="page-hero-kicker" style="margin-bottom: 0;">Artikel Berikutnya &rarr;</span>
              <span class="sidebar-post-title"><?= esc($next_post['title'] ?? '') ?></span>
            </a>
          <?php endif; ?>
        </nav>
      <?php endif; ?>

      <div class="comments-wrap" id="komentar">
        <div class="sec-index">Diskusi Pembaca</div>
        <h2 class="sec-title" style="font-size: 1.375rem; margin-bottom: var(--space-4);">Komentar &amp; Tanggapan</h2>

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

        <?php if (! empty($topComments)): ?>
          <div class="comment-list">
            <?php foreach ($topComments as $comment): ?>
              <?php
              $cid        = (int) ($comment['id'] ?? 0);
              $cAuthor    = (string) ($comment['name'] ?? 'Pembaca');
              $cBody      = (string) ($comment['comment'] ?? ($comment['message'] ?? ''));
              $cDateRaw   = (string) ($comment['created_at'] ?? '');
              $cDateLabel = $cDateRaw !== '' ? date('d M Y H:i', strtotime($cDateRaw)) : '';
              $childList  = ! empty($comment['replies']) ? $comment['replies'] : ($repliesByParent[$cid] ?? []);
              ?>
              <div class="comment-item">
                <div class="comment-head">
                  <span class="comment-author"><?= esc($cAuthor) ?></span>
                  <div class="sec-actions">
                    <?php if ($cDateLabel !== ''): ?>
                      <span class="comment-date tabular-nums"><?= esc($cDateLabel) ?></span>
                    <?php endif; ?>
                    <?php if ($cid > 0): ?>
                      <button type="button"
                              class="btn btn-ghost btn-sm"
                              data-reply-id="<?= esc((string) $cid) ?>"
                              data-reply-author="<?= esc($cAuthor) ?>">Balas</button>
                    <?php endif; ?>
                  </div>
                </div>
                <p style="margin-bottom: 0; color: var(--theme_color_text_weak); font-size: var(--text-sm);"><?= nl2br(esc($cBody)) ?></p>
              </div>

              <?php foreach ($childList as $reply): ?>
                <?php
                $rDateRaw   = (string) ($reply['created_at'] ?? '');
                $rDateLabel = $rDateRaw !== '' ? date('d M Y H:i', strtotime($rDateRaw)) : '';
                ?>
                <div class="comment-item is-reply">
                  <div class="comment-head">
                    <span class="comment-author"><?= esc($reply['name'] ?? 'Pembaca') ?></span>
                    <?php if ($rDateLabel !== ''): ?>
                      <span class="comment-date tabular-nums"><?= esc($rDateLabel) ?></span>
                    <?php endif; ?>
                  </div>
                  <p style="margin-bottom: 0; color: var(--theme_color_text_weak); font-size: var(--text-sm);"><?= nl2br(esc($reply['comment'] ?? ($reply['message'] ?? ''))) ?></p>
                </div>
              <?php endforeach; ?>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <p class="sec-desc" style="margin-bottom: var(--space-5);">Belum ada komentar. Jadilah yang pertama memberikan tanggapan yang konstruktif.</p>
        <?php endif; ?>

        <div class="reply-banner hidden" id="replyBanner">
          <span>Membalas komentar <strong id="replyToName"></strong></span>
          <button type="button" class="btn btn-ghost btn-sm" id="cancelReplyBtn">Batal Balas</button>
        </div>

        <form id="commentForm" action="<?= esc(base_url('news/' . $postSlug . '/comment')) ?>" method="post">
          <?= csrf_field() ?>
          <input type="hidden" id="commentParentId" name="parent_id" value="">
          <div class="hp-field" aria-hidden="true">
            <label for="commentHp">Website</label>
            <input type="text" id="commentHp" name="website" tabindex="-1" autocomplete="off">
          </div>

          <div class="grid-2">
            <div class="form-group">
              <label class="form-label" for="commentName">Nama Lengkap</label>
              <input class="form-control" type="text" id="commentName" name="name" required value="<?= esc(old('name')) ?>" placeholder="Nama Anda">
            </div>
            <div class="form-group">
              <label class="form-label" for="commentEmail">Alamat Email</label>
              <input class="form-control" type="email" id="commentEmail" name="email" required value="<?= esc(old('email')) ?>" placeholder="email@contoh.com">
            </div>
          </div>

          <div class="form-group">
            <div class="sec-actions" style="justify-content: space-between;">
              <label class="form-label" for="commentMsg">Tulis Komentar</label>
              <span class="form-help tabular-nums" id="commentCounter">0 / 2000</span>
            </div>
            <textarea class="form-control" id="commentMsg" name="message" required maxlength="2000" placeholder="Tuliskan komentar yang sopan dan relevan..."><?= esc(old('message')) ?></textarea>
          </div>

          <button type="submit" class="btn btn-primary">
            <?= lucide_icon('message-circle', '', 16) ?>
            <span>Kirim Komentar</span>
          </button>
        </form>
      </div>
    </article>

    <aside class="sidebar-stack" aria-label="Navigasi berita terkait">
      <div class="card">
        <h3 class="sec-title" style="font-size: 1.0625rem; margin-bottom: var(--space-3);">Cari Berita</h3>
        <form class="search-form" action="<?= esc(base_url('news')) ?>" method="get">
          <label class="sr-only" for="sideNewsSearch">Cari berita</label>
          <input class="form-control" type="search" id="sideNewsSearch" name="search" placeholder="Kata kunci...">
          <button type="submit" class="btn btn-primary btn-sm" aria-label="Cari">
            <?= lucide_icon('search', '', 15) ?>
          </button>
        </form>
      </div>

      <?php $sidebarNews = ! empty($relatedList) ? $relatedList : $recentList; ?>
      <?php if (! empty($sidebarNews)): ?>
        <div class="card">
          <h3 class="sec-title" style="font-size: 1.0625rem;">Berita Lainnya</h3>
          <div class="sidebar-list">
            <?php foreach (array_slice($sidebarNews, 0, 5) as $rel): ?>
              <?php if (($rel['slug'] ?? '') === $postSlug) continue; ?>
              <a class="sidebar-post" href="<?= esc(base_url('news/' . ($rel['slug'] ?? ''))) ?>">
                <span class="sidebar-post-title"><?= esc($rel['title'] ?? '') ?></span>
                <?php if (! empty($rel['published_at']) || ! empty($rel['created_at'])): ?>
                  <span class="sidebar-post-date tabular-nums"><?= esc(date('d M Y', strtotime((string) ($rel['published_at'] ?? $rel['created_at'])))) ?></span>
                <?php endif; ?>
              </a>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endif; ?>

      <?php if (! empty($cats)): ?>
        <div class="card">
          <h3 class="sec-title" style="font-size: 1.0625rem; margin-bottom: var(--space-3);">Kategori</h3>
          <div class="filter-tabs">
            <?php foreach ($cats as $catRow): ?>
              <?php
              if (is_array($catRow)) {
                  $catLabel = trim((string) ($catRow['name'] ?? ($catRow['category'] ?? '')));
                  $catSlug  = trim((string) ($catRow['slug'] ?? $catLabel));
              } else {
                  $catLabel = trim((string) $catRow);
                  $catSlug  = $catLabel;
              }
              if ($catLabel === '') continue;
              ?>
              <a class="filter-btn" style="padding: 0.35rem 0.7rem; font-size: 0.8125rem;" href="<?= esc(base_url('news?category=' . urlencode($catSlug))) ?>"><?= esc($catLabel) ?></a>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endif; ?>
    </aside>
  </div>
</section>

<?= view(theme_view('layouts/footer')) ?>
