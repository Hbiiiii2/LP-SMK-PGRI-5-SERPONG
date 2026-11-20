<?php
helper('theme');
$item     = $item ?? [];
$rawIcon  = trim((string) ($item['icon'] ?? 'graduation-cap')) ?: 'graduation-cap';
$title    = trim((string) ($item['title'] ?? 'Program Keahlian'));
$desc     = trim((string) ($item['description'] ?? ''));
$codeIdx  = $index ?? null;
$linkText = trim((string) ($item['link_text'] ?? 'Pelajari Detail')) ?: 'Pelajari Detail';
$linkUrl  = function_exists('program_link')
    ? program_link($item, 'program')
    : base_url('program');

$payload       = function_exists('program_detail_payload') ? program_detail_payload($item) : ['learning_points' => [], 'image_url' => ''];
$imgUrl        = (string) ($payload['image_url'] ?? '');
$learningItems = array_slice($payload['learning_points'] ?? [], 0, 3);
?>
<article class="prog-matrix-card">
  <div class="prog-matrix-top">
    <?php if ($imgUrl !== ''): ?>
      <span class="prog-media-thumb">
        <img src="<?= esc($imgUrl) ?>" alt="<?= esc($title) ?>" loading="lazy">
      </span>
    <?php else: ?>
      <span class="prog-icon-inline"><?= lucide_icon($rawIcon, '', 20) ?></span>
    <?php endif; ?>
    <?php if ($codeIdx !== null): ?>
      <span class="prog-code tabular-nums">PRG-<?= esc(str_pad((string) ($codeIdx + 1), 2, '0', STR_PAD_LEFT)) ?></span>
    <?php endif; ?>
  </div>
  <h3 class="info-card-title">
    <a class="prog-title-link" href="<?= esc($linkUrl) ?>"><?= esc($title) ?></a>
  </h3>
  <?php if ($desc !== ''): ?>
    <p class="info-card-desc" style="margin-top: 0.45rem;"><?= esc($desc) ?></p>
  <?php endif; ?>
  <?php if (! empty($learningItems)): ?>
    <div class="prog-learn-preview">
      <div class="prog-learn-label">Fokus Dipelajari</div>
      <ul class="prog-learn-mini">
        <?php foreach ($learningItems as $point): ?>
          <li>
            <?= lucide_icon('check', '', 13) ?>
            <span><?= esc($point) ?></span>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>
  <div class="info-card-foot">
    <a class="text-link is-blue" href="<?= esc($linkUrl) ?>">
      <span><?= esc($linkText) ?></span>
      <?= lucide_icon('arrow-right', '', 15) ?>
    </a>
  </div>
</article>
