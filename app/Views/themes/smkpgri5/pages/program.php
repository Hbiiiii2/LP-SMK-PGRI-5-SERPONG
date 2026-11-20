<?= view(theme_view('layouts/header')) ?>

<?php
helper('theme');
$settings   = $settings ?? [];
$siteName   = trim((string) ($site_name ?? ($settings['site_name'] ?? 'SMK PGRI 5 Serpong'))) ?: 'SMK PGRI 5 Serpong';
$list       = $programs ?? [];
$activeReq  = strtolower(trim((string) ($active_program_slug ?? '')));
$activeIdx  = 0;

if ($activeReq !== '' && ! empty($list)) {
    foreach ($list as $i => $p) {
        $pSlug = strtolower(trim((string) ($p['slug'] ?? '')));
        $pId   = (string) ($p['id'] ?? '');
        if ($activeReq === $pSlug || $activeReq === $pId) {
            $activeIdx = $i;
            break;
        }
    }
}
?>

<?= view(theme_view('layouts/page_hero'), [
    'kicker'  => 'Akademik & Kompetensi',
    'heading' => 'Program Keahlian & Layanan Akademik',
    'lead'    => 'Klik salah satu jurusan atau program di bawah untuk melihat fokus materi yang dipelajari, praktik laboratorium, dan prospek lulusan di ' . $siteName . '.',
    'crumbs'  => [['label' => 'Program Keahlian']],
]) ?>

<?php if (! empty($list)): ?>
<section class="sec sec-white" id="fokus-program">
  <div class="wrap">
    <div class="sec-head">
      <div class="sec-head-text">
        <div class="sec-index">01 / Penjelajah Fokus Program</div>
        <h2 class="sec-title">Lihat Apa yang Dipelajari di Setiap Jurusan</h2>
        <p class="sec-desc">Pilih program pada daftar sebelah kiri untuk melihat rincian kompetensi inti, kegiatan praktik, dan peluang karier lulusan.</p>
      </div>
    </div>

    <div class="prog-explorer">
      <div class="prog-explorer-rail" role="tablist" aria-label="Pilih Program Keahlian">
        <?php foreach ($list as $idx => $prog): ?>
          <?php
          $isSelected = $idx === $activeIdx;
          $progSlug   = trim((string) ($prog['slug'] ?? ($prog['id'] ?? '')));
          $panelId    = 'prog-panel-' . (int) ($prog['id'] ?? $idx);
          $rawIcon    = trim((string) ($prog['icon'] ?? 'graduation-cap')) ?: 'graduation-cap';
          $thumbUrl   = function_exists('program_image_url') ? program_image_url($prog) : '';
          ?>
          <button type="button"
                  class="prog-tab<?= $isSelected ? ' active' : '' ?>"
                  role="tab"
                  aria-selected="<?= $isSelected ? 'true' : 'false' ?>"
                  aria-controls="<?= esc($panelId) ?>"
                  data-prog-tab="<?= esc($panelId) ?>"
                  data-prog-slug="<?= esc($progSlug) ?>">
            <?php if ($thumbUrl !== ''): ?>
              <span class="prog-tab-thumb"><img src="<?= esc($thumbUrl) ?>" alt="" loading="lazy"></span>
            <?php else: ?>
              <span class="prog-tab-icon"><?= lucide_icon($rawIcon, '', 17) ?></span>
            <?php endif; ?>
            <span class="prog-tab-body">
              <span class="prog-tab-code tabular-nums">PRG-<?= esc(str_pad((string) ($idx + 1), 2, '0', STR_PAD_LEFT)) ?></span>
              <span class="prog-tab-title"><?= esc($prog['title'] ?? '') ?></span>
            </span>
            <?= lucide_icon('chevron-right', 'prog-tab-arrow', 15) ?>
          </button>
        <?php endforeach; ?>
      </div>

      <div class="prog-explorer-stage" id="progExplorerStage">
        <?php foreach ($list as $idx => $prog): ?>
          <?php
          $isSelected  = $idx === $activeIdx;
          $panelId     = 'prog-panel-' . (int) ($prog['id'] ?? $idx);
          $rawIcon     = trim((string) ($prog['icon'] ?? 'graduation-cap')) ?: 'graduation-cap';
          $payload     = function_exists('program_detail_payload') ? program_detail_payload($prog) : [];
          $imgUrl      = (string) ($payload['image_url'] ?? '');
          $learning    = $payload['learning_points'] ?? [];
          $careers     = $payload['career_prospects'] ?? [];
          $contentHtml = (string) ($payload['content_html'] ?? '');
          $detailHref  = function_exists('program_link') ? program_link($prog, 'program') : base_url('program');
          ?>
          <article class="prog-focus-panel<?= $isSelected ? ' active' : '' ?>"
                   id="<?= esc($panelId) ?>"
                   role="tabpanel"
                   <?= $isSelected ? '' : 'hidden' ?>>
            <div class="prog-focus-head">
              <div class="prog-focus-id">
                <?php if ($imgUrl !== ''): ?>
                  <span class="prog-media-thumb is-lg">
                    <img src="<?= esc($imgUrl) ?>" alt="<?= esc($prog['title'] ?? '') ?>">
                  </span>
                <?php else: ?>
                  <span class="prog-icon-inline is-lg"><?= lucide_icon($rawIcon, '', 24) ?></span>
                <?php endif; ?>
                <div>
                  <div class="prog-code tabular-nums">PRG-<?= esc(str_pad((string) ($idx + 1), 2, '0', STR_PAD_LEFT)) ?></div>
                  <h3 class="prog-focus-title"><?= esc($prog['title'] ?? '') ?></h3>
                </div>
              </div>
              <a class="btn btn-outline-primary btn-sm" href="<?= esc($detailHref) ?>">
                <span>Halaman Khusus</span>
                <?= lucide_icon('arrow-up-right', '', 15) ?>
              </a>
            </div>

            <?php if (! empty($prog['description'])): ?>
              <p class="prog-focus-lead"><?= esc($prog['description']) ?></p>
            <?php endif; ?>

            <?php if ($imgUrl !== ''): ?>
              <figure class="prog-focus-photo">
                <img src="<?= esc($imgUrl) ?>" alt="Visual <?= esc($prog['title'] ?? '') ?>" loading="lazy">
              </figure>
            <?php endif; ?>

            <?php if (! empty($learning)): ?>
              <div class="prog-focus-block">
                <div class="prog-block-label">
                  <?= lucide_icon('book-open', '', 15) ?>
                  <span>Apa Saja yang Dipelajari?</span>
                </div>
                <div class="prog-module-list">
                  <?php foreach ($learning as $mIdx => $point): ?>
                    <div class="prog-module-item">
                      <span class="prog-module-num tabular-nums"><?= esc(str_pad((string) ($mIdx + 1), 2, '0', STR_PAD_LEFT)) ?></span>
                      <span class="prog-module-text"><?= esc($point) ?></span>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>
            <?php endif; ?>

            <?php if ($contentHtml !== ''): ?>
              <div class="prog-focus-block">
                <div class="prog-block-label">
                  <?= lucide_icon('layers', '', 15) ?>
                  <span>Pembelajaran &amp; Praktik</span>
                </div>
                <div class="prog-prose">
                  <?= $contentHtml ?>
                </div>
              </div>
            <?php endif; ?>

            <?php if (! empty($careers)): ?>
              <div class="prog-focus-block">
                <div class="prog-block-label">
                  <?= lucide_icon('briefcase', '', 15) ?>
                  <span>Prospek Lulusan &amp; Karier</span>
                </div>
                <div class="prog-career-list">
                  <?php foreach ($careers as $career): ?>
                    <span class="prog-career-item">
                      <?= lucide_icon('check-circle-2', '', 14) ?>
                      <span><?= esc($career) ?></span>
                    </span>
                  <?php endforeach; ?>
                </div>
              </div>
            <?php endif; ?>

            <div class="prog-focus-foot">
              <a class="btn btn-primary" href="<?= esc($detailHref) ?>">
                <span>Buka Detail Lengkap</span>
                <?= lucide_icon('arrow-right', '', 16) ?>
              </a>
              <a class="btn btn-secondary" href="<?= esc(base_url('spmb?jurusan=' . urlencode((string) ($prog['slug'] ?? ($prog['title'] ?? ''))))) ?>">Daftar Jurusan Ini (SPMB)</a>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>

<section class="sec sec-subtle">
  <div class="wrap">
    <?php if (! empty($list)): ?>
      <div class="sec-head">
        <div class="sec-head-text">
          <div class="sec-index">02 / Daftar Lengkap Jurusan &amp; Program</div>
          <h2 class="sec-title">Semua Program <?= esc($siteName) ?></h2>
        </div>
      </div>

      <div class="programs-matrix">
        <?php foreach ($list as $idx => $prog): ?>
          <?= view(theme_view('layouts/program_card'), ['item' => $prog, 'index' => $idx]) ?>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="empty-state">
        <?= lucide_icon('book-open', '', 32) ?>
        <p>Belum ada data program akademik yang ditampilkan.</p>
      </div>
    <?php endif; ?>

    <div class="spmb-cta-box">
      <div>
        <h3>Butuh Informasi Lebih Lanjut Tentang Program?</h3>
        <p>Pelajari alur pendaftaran SPMB atau konsultasikan pilihan kompetensi bersama tim akademik kami.</p>
      </div>
      <div class="sec-actions">
        <a class="btn btn-primary" href="<?= esc(base_url('spmb')) ?>">Daftar SPMB Online</a>
        <a class="btn btn-secondary" href="<?= esc(base_url('contact')) ?>">Hubungi Sekolah</a>
      </div>
    </div>
  </div>
</section>

<?= view(theme_view('layouts/footer')) ?>
