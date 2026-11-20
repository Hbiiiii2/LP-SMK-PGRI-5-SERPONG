<?= view(theme_view('layouts/header')) ?>

<?php
$settings      = $settings ?? [];
$cleanText     = static fn (?string $s): string => strtr(trim((string) $s), ['Â·' => '·', 'â€”' => '—', 'â€“' => '–']);

$siteName      = $cleanText($site_name ?? ($settings['site_name'] ?? 'SMK PGRI 5 Serpong')) ?: 'SMK PGRI 5 Serpong';
$siteDesc      = $cleanText($site_description ?? ($settings['site_description'] ?? 'Mengenal lebih dekat sejarah, identitas resmi, visi, misi, dan sambutan pimpinan sekolah.'));
$aboutData     = is_array($about ?? null) ? $about : (json_decode((string) ($settings['about'] ?? '{}'), true) ?: []);
$principalData = is_array($principal ?? null) ? $principal : (json_decode((string) ($settings['principal'] ?? '{}'), true) ?: []);

$resolveMedia  = static function (?string $raw): string {
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

$logoUrl       = theme_asset('img/logo-smk-pgri-5-serpong.png');
$aboutImgRaw   = trim((string) ($aboutData['image'] ?? ''));
$aboutImage    = $aboutImgRaw !== '' ? $resolveMedia($aboutImgRaw) : $logoUrl;
$highlights    = is_array($aboutData['highlights'] ?? null) ? $aboutData['highlights'] : [];
$misiRaw       = $cleanText($aboutData['misi'] ?? '');
$misiItems     = $misiRaw !== '' ? array_values(array_filter(array_map('trim', preg_split('/[;\n]+/', $misiRaw) ?: []))) : [];
?>

<?= view(theme_view('layouts/page_hero'), [
    'kicker'  => 'Profil Institusi',
    'heading' => 'Profil ' . $siteName,
    'lead'    => $siteDesc,
    'crumbs'  => [['label' => 'Profil Sekolah']],
]) ?>

<section class="sec sec-white">
  <div class="wrap profile-grid">
    <div class="hero-card">
      <div class="hero-emblem-box">
        <img class="<?= $aboutImage === $logoUrl || str_contains($aboutImage, 'about') ? 'hero-emblem-img' : 'hero-photo-img' ?>"
             src="<?= esc($aboutImage) ?>"
             alt="Lambang Resmi <?= esc($siteName) ?>">
        <div class="hero-emblem-caption">
          <div class="hero-emblem-title"><?= esc($siteName) ?></div>
          <?php if (! empty($aboutData['accreditation_label'])): ?>
            <div class="hero-emblem-sub"><?= esc($cleanText($aboutData['accreditation_label'])) ?></div>
          <?php endif; ?>
        </div>
      </div>
      <?php if (! empty($aboutData['accreditation'])): ?>
        <div class="hero-ledger-row">
          <span class="hero-ledger-key">Akreditasi</span>
          <span class="badge badge-amber">
            <?= lucide_icon('award', '', 13) ?>
            <span>Peringkat <?= esc($aboutData['accreditation']) ?></span>
          </span>
        </div>
      <?php endif; ?>
      <div class="hero-ledger-row">
        <span class="hero-ledger-key">Akademik</span>
        <a class="text-link is-blue" href="<?= esc(base_url('program')) ?>">Lihat Program Keahlian <?= lucide_icon('arrow-right', '', 14) ?></a>
      </div>
    </div>

    <div class="card">
      <div class="sec-index">Sejarah &amp; Identitas</div>
      <h2 class="sec-title" style="margin-bottom: var(--space-4);"><?= esc($cleanText($aboutData['content_title'] ?? ('Tentang ' . $siteName))) ?></h2>
      <?php if (! empty($aboutData['content_1'])): ?>
        <p class="prose" style="margin-bottom: var(--space-4);"><?= esc($cleanText($aboutData['content_1'])) ?></p>
      <?php endif; ?>
      <?php if (! empty($aboutData['content_2'])): ?>
        <p class="sec-desc" style="margin-top: 0;"><?= esc($cleanText($aboutData['content_2'])) ?></p>
      <?php endif; ?>
      <?php if (empty($aboutData['content_1']) && $siteDesc !== ''): ?>
        <p class="prose"><?= esc($siteDesc) ?></p>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php if (! empty($aboutData['visi']) || ! empty($misiItems)): ?>
  <section class="sec sec-subtle">
    <div class="wrap">
      <div class="sec-head">
        <div class="sec-head-text">
          <div class="sec-index">Arah Pendidikan</div>
          <h2 class="sec-title">Visi &amp; Misi Sekolah</h2>
        </div>
      </div>

      <div class="visi-grid">
        <?php if (! empty($aboutData['visi'])): ?>
          <article class="card">
            <div class="prog-head">
              <span class="prog-icon-inline"><?= lucide_icon('compass', '', 20) ?></span>
              <h3 class="sec-title" style="font-size: 1.25rem;">Visi Institusi</h3>
            </div>
            <p class="principal-quote" style="margin-top: var(--space-3);"><?= esc($cleanText($aboutData['visi'])) ?></p>
          </article>
        <?php endif; ?>

        <?php if (! empty($misiItems)): ?>
          <article class="card">
            <div class="prog-head">
              <span class="prog-icon-inline"><?= lucide_icon('target', '', 20) ?></span>
              <h3 class="sec-title" style="font-size: 1.25rem;">Misi Pendidikan</h3>
            </div>
            <ol class="misi-list" style="margin-top: var(--space-3);">
              <?php foreach ($misiItems as $misi): ?>
                <li><?= esc($misi) ?></li>
              <?php endforeach; ?>
            </ol>
          </article>
        <?php endif; ?>
      </div>
    </div>
  </section>
<?php endif; ?>

<?php if (! empty($principalData)): ?>
  <section class="sec sec-white">
    <div class="wrap">
      <div class="sec-head" style="margin-bottom: var(--space-5);">
        <div class="sec-head-text">
          <div class="sec-index">Pimpinan Sekolah</div>
          <h2 class="sec-title">Sambutan Kepala Sekolah</h2>
        </div>
      </div>
      <article class="card principal-card is-full">
        <div class="principal-portrait">
          <?php $pPhoto = $resolveMedia($principalData['photo'] ?? ''); ?>
          <?php if ($pPhoto !== ''): ?>
            <img src="<?= esc($pPhoto) ?>" alt="<?= esc($principalData['name'] ?? 'Kepala Sekolah') ?>" loading="lazy">
          <?php else: ?>
            <span><?= esc(mb_substr((string) ($principalData['name'] ?? 'KS'), 0, 2)) ?></span>
          <?php endif; ?>
        </div>
        <div class="principal-content">
          <span class="principal-role"><?= esc($principalData['role_title'] ?? ($principalData['role'] ?? 'Kepala Sekolah')) ?></span>
          <h3 class="principal-name"><?= esc($principalData['name'] ?? 'Kepala Sekolah') ?></h3>
          <?php if (! empty($principalData['education'])): ?>
            <div class="principal-edu"><?= esc($principalData['education']) ?></div>
          <?php endif; ?>
          <div class="prose" style="margin-top: var(--space-3);">
            <?php if (! empty($principalData['quote'])): ?>
              <blockquote style="margin-top: 0;"><?= esc($cleanText($principalData['quote'])) ?></blockquote>
            <?php endif; ?>
            <?php if (! empty($principalData['welcome_message'])): ?>
              <p><?= nl2br(esc($cleanText($principalData['welcome_message']))) ?></p>
            <?php elseif (! empty($principalData['sambutan'])): ?>
              <p><?= nl2br(esc($cleanText($principalData['sambutan']))) ?></p>
            <?php endif; ?>
          </div>
        </div>
      </article>
    </div>
  </section>
<?php endif; ?>

<?php if (! empty($highlights)): ?>
  <section class="sec sec-blue-soft">
    <div class="wrap">
      <div class="sec-head">
        <div class="sec-head-text">
          <div class="sec-index">Keunggulan Institusi</div>
          <h2 class="sec-title">Pilar Mutu Sekolah</h2>
        </div>
      </div>
      <div class="pillar-list">
        <?php foreach ($highlights as $idx => $hl): ?>
          <div class="pillar-item">
            <span class="pillar-num tabular-nums"><?= esc(str_pad((string) ($idx + 1), 2, '0', STR_PAD_LEFT)) ?></span>
            <div>
              <h3 class="pillar-title"><?= esc(is_string($hl) ? $cleanText($hl) : $cleanText($hl['title'] ?? '')) ?></h3>
              <?php if (is_array($hl) && ! empty($hl['desc'])): ?>
                <p class="pillar-desc"><?= esc($cleanText($hl['desc'])) ?></p>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
<?php endif; ?>

<?= view(theme_view('layouts/footer')) ?>
