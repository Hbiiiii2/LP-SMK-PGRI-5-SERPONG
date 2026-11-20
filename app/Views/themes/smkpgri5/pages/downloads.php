<?= view(theme_view('layouts/header')) ?>

<?php
$banner = function_exists('theme_banner') ? theme_banner('downloads') : [];

// Normalize controller output: Downloads::index() passes grouped $categories; fallback supports flat $downloads
$grouped = [];
if (! empty($categories) && is_array($categories)) {
    $grouped = $categories;
} elseif (! empty($downloads) && is_array($downloads)) {
    foreach ($downloads as $doc) {
        $catName = trim((string) ($doc['category'] ?? 'Umum')) ?: 'Umum';
        $grouped[$catName][] = $doc;
    }
}

$totalDocs = 0;
foreach ($grouped as $items) {
    if (is_array($items)) {
        $totalDocs += count($items);
    }
}
?>

<?= view(theme_view('layouts/page_hero'), [
    'kicker'  => trim((string) ($banner['badge'] ?? '')) ?: 'Pusat Dokumen',
    'heading' => trim((string) ($banner['title'] ?? '')) ?: 'Pusat Unduhan Resmi',
    'lead'    => trim((string) ($banner['subtitle'] ?? '')) ?: 'Unduh brosur SPMB, formulir pendaftaran, kalender akademik, dan dokumen resmi sekolah.',
    'crumbs'  => [['label' => 'Unduhan']],
]) ?>

<section class="sec sec-white">
  <div class="wrap">
    <?php if ($totalDocs > 0): ?>
      <div class="news-toolbar">
        <div class="form-help tabular-nums" id="dlCountLabel">Menampilkan <?= esc((string) $totalDocs) ?> dokumen tersedia.</div>
        <div class="search-form">
          <label class="sr-only" for="dlSearch">Filter dokumen</label>
          <input class="form-control" type="search" id="dlSearch" placeholder="Cari nama atau kategori dokumen...">
        </div>
      </div>

      <div id="dlList">
        <?php foreach ($grouped as $catName => $docs): ?>
          <?php if (empty($docs) || ! is_array($docs)) continue; ?>
          <div class="dl-category-block">
            <div class="dl-category-title">
              <span><?= esc((string) $catName !== '' ? (string) $catName : 'Dokumen Sekolah') ?></span>
              <span class="badge badge-blue tabular-nums"><?= esc((string) count($docs)) ?> berkas</span>
            </div>
            <div class="dl-ledger">
              <?php foreach ($docs as $doc): ?>
                <?php
                $name = trim((string) ($doc['title'] ?? 'Berkas Sekolah'));
                $desc = trim((string) ($doc['description'] ?? ''));
                $size = trim((string) ($doc['file_size'] ?? ''));
                $url  = trim((string) ($doc['url'] ?? '#'));
                if ($url !== '' && ! str_starts_with($url, 'http') && ! str_starts_with($url, '#')) {
                    $url = base_url(ltrim($url, '/'));
                }
                $ext = 'PDF';
                if (preg_match('/\.([a-z0-9]{2,5})(?:\?|#|$)/i', $url, $extMatch)) {
                    $ext = strtoupper($extMatch[1]);
                }
                ?>
                <div class="dl-row dl-item" data-search="<?= esc($name . ' ' . $catName . ' ' . $desc) ?>">
                  <div class="dl-row-main">
                    <span class="dl-ext"><?= esc($ext) ?></span>
                    <div>
                      <h2 class="dl-title"><?= esc($name) ?></h2>
                      <?php if ($desc !== ''): ?>
                        <div class="dl-meta"><?= esc($desc) ?></div>
                      <?php endif; ?>
                    </div>
                  </div>
                  <div class="dl-row-actions">
                    <?php if ($size !== ''): ?>
                      <span class="dl-size"><?= esc($size) ?></span>
                    <?php endif; ?>
                    <a class="btn btn-secondary btn-sm" href="<?= esc($url) ?>" target="_blank" rel="noopener noreferrer">
                      <?= lucide_icon('download', '', 15) ?>
                      <span>Unduh</span>
                    </a>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <div class="empty-state hidden" id="dlEmpty">
        <?= lucide_icon('folder', '', 32) ?>
        <p>Tidak ditemukan dokumen yang cocok dengan kata kunci tersebut.</p>
      </div>
    <?php else: ?>
      <div class="empty-state">
        <?= lucide_icon('folder', '', 32) ?>
        <p>Belum ada dokumen yang tersedia untuk diunduh.</p>
      </div>
    <?php endif; ?>
  </div>
</section>

<?= view(theme_view('layouts/footer')) ?>
