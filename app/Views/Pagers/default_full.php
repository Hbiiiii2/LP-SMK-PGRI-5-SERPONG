<?php

use CodeIgniter\Pager\PagerRenderer;

/** @var PagerRenderer $pager */
$pager->setSurroundCount(2);
?>

<?php if ($pager->links() !== []): ?>
<nav class="news-pagination" aria-label="Navigasi halaman">
  <?php if ($pager->hasPrevious()): ?>
    <a class="np-btn" href="<?= $pager->getFirst() ?>" aria-label="Halaman pertama">&laquo;</a>
    <a class="np-btn" href="<?= $pager->getPrevious() ?>" aria-label="Halaman sebelumnya">&lsaquo;</a>
  <?php endif ?>

  <?php foreach ($pager->links() as $link): ?>
    <a class="np-btn <?= $link['active'] ? 'active' : '' ?>" href="<?= $link['uri'] ?>"<?= $link['active'] ? ' aria-current="page"' : '' ?>><?= $link['title'] ?></a>
  <?php endforeach ?>

  <?php if ($pager->hasNext()): ?>
    <a class="np-btn" href="<?= $pager->getNext() ?>" aria-label="Halaman selanjutnya">&rsaquo;</a>
    <a class="np-btn" href="<?= $pager->getLast() ?>" aria-label="Halaman terakhir">&raquo;</a>
  <?php endif ?>
</nav>
<?php endif ?>
