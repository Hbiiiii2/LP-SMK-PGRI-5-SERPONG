<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Komentar</h4>
        <p class="text-body-tertiary small mb-0">Kelola komentar pengunjung pada artikel berita</p>
    </div>
</div>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <div class="btn-group btn-group-sm" role="group" aria-label="Filter status komentar">
        <a href="/admin/comments" class="btn btn-outline-secondary <?= $status === '' ? 'active' : '' ?>">Semua (<?= (int) $counts['all'] ?>)</a>
        <a href="/admin/comments?status=pending" class="btn btn-outline-secondary <?= $status === 'pending' ? 'active' : '' ?>">Menunggu (<?= (int) $counts['pending'] ?>)</a>
        <a href="/admin/comments?status=approved" class="btn btn-outline-secondary <?= $status === 'approved' ? 'active' : '' ?>">Disetujui (<?= (int) $counts['approved'] ?>)</a>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-sm btn-outline-success" id="bulkApproveBtn"
                data-coreui-toggle="modal" data-coreui-target="#bulkApproveModal" disabled>
            <?= lucide_icon('check', 'me-1', 14) ?>Setujui terpilih
        </button>
        <button type="button" class="btn btn-sm btn-outline-danger" id="bulkDeleteBtn"
                data-coreui-toggle="modal" data-coreui-target="#bulkDeleteModal" disabled>
            <?= lucide_icon('trash-2', 'me-1', 14) ?>Hapus terpilih
        </button>
    </div>
</div>

<div class="card shadow-sm border-0 rounded-3">
    <div class="table-responsive">
        <table class="table table-crud table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th style="width:40px"><input class="form-check-input" type="checkbox" id="checkAll" title="Pilih semua"></th>
                    <th style="width:60px">#</th>
                    <th>Berita</th>
                    <th>Komentator</th>
                    <th>Komentar</th>
                    <th style="width:110px">Tanggal</th>
                    <th style="width:100px">Status</th>
                    <th style="width:150px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $i => $item): ?>
                <tr class="<?= empty($item['is_approved']) ? 'table-warning' : '' ?>">
                    <td><input class="form-check-input msg-check" type="checkbox" name="ids[]" value="<?= (int) $item['id'] ?>" form="bulkForm" aria-label="Pilih komentar dari <?= esc($item['name']) ?>"></td>
                    <td><span class="badge bg-body-tertiary text-body-tertiary fw-normal"><?= $i + 1 + (($pager->getCurrentPage() - 1) * 10) ?></span></td>
                    <td class="text-body-secondary">
                        <?php if (!empty($item['news_title'])): ?>
                            <a href="<?= base_url('news/' . $item['news_slug']) ?>" target="_blank" rel="noopener" title="<?= esc($item['news_title']) ?>">
                                <?= esc(mb_strimwidth($item['news_title'], 0, 40, '...')) ?>
                            </a>
                        <?php else: ?>
                            #<?= (int) ($item['news_id'] ?? 0) ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="fw-medium"><?= esc($item['name'] ?? '-') ?></div>
                        <?php if (!empty($item['email'])): ?>
                            <div class="small text-body-tertiary"><?= esc($item['email']) ?></div>
                        <?php endif; ?>
                    </td>
                    <td class="text-body-secondary small"><?= esc(mb_strimwidth((string) ($item['comment'] ?? ''), 0, 60, '...')) ?></td>
                    <td class="text-body-secondary small"><?= isset($item['created_at']) ? date('d M Y H:i', strtotime($item['created_at'])) : '-' ?></td>
                    <td>
                        <?php if (!empty($item['is_approved'])): ?>
                            <span class="badge bg-success">Disetujui</span>
                        <?php else: ?>
                            <span class="badge bg-warning">Menunggu</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <?php if (empty($item['is_approved'])): ?>
                                <form action="/admin/comments/approve/<?= (int) $item['id'] ?>" method="post" class="d-inline">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="status" value="<?= esc($status) ?>">
                                    <button class="btn btn-sm btn-outline-success border-0" title="Setujui">
                                        <?= lucide_icon('check', '', 14) ?>
                                    </button>
                                </form>
                                <form action="/admin/comments/reject/<?= (int) $item['id'] ?>" method="post" class="d-inline" onsubmit="return confirm('Tolak komentar ini? Komentar akan dihapus.')">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="status" value="<?= esc($status) ?>">
                                    <button class="btn btn-sm btn-outline-warning border-0" title="Tolak">
                                        <?= lucide_icon('x', '', 14) ?>
                                    </button>
                                </form>
                            <?php endif; ?>
                            <form action="/admin/comments/delete/<?= (int) $item['id'] ?>" method="post" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus komentar ini?')">
                                <?= csrf_field() ?>
                                <input type="hidden" name="status" value="<?= esc($status) ?>">
                                <button class="btn btn-sm btn-outline-danger border-0" title="Hapus">
                                    <?= lucide_icon('trash-2', '', 14) ?>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($items)): ?>
                <tr><td colspan="8" class="text-center py-5 text-body-tertiary">Tidak ada komentar ditemukan.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if ($pager->links()): ?>
    <div class="d-flex justify-content-center py-3 border-top">
        <?= $pager->links() ?>
    </div>
    <?php endif; ?>
</div>

<?php // Form aksi massal diletakkan di luar tabel agar tidak ada <form> bersarang;
      // checkbox baris mengacu ke form ini lewat atribut form="bulkForm", dan
      // tombol submit di modal membawa name="action" (approve|delete). ?>
<form id="bulkForm" action="/admin/comments/bulk" method="post" class="d-none">
    <?= csrf_field() ?>
    <input type="hidden" name="status" value="<?= esc($status) ?>">
</form>

<div class="modal fade" id="bulkApproveModal" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title">Konfirmasi Setujui</h6>
                <button type="button" class="btn-close" data-coreui-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Setujui <strong id="bulkApproveCount">0</strong> komentar terpilih? Komentar akan tampil di halaman berita.
            </div>
            <div class="modal-footer border-0 pt-0">
                <button class="btn btn-sm btn-secondary" data-coreui-dismiss="modal">Batal</button>
                <button type="submit" form="bulkForm" name="action" value="approve" class="btn btn-sm btn-success">Setujui</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="bulkDeleteModal" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title">Konfirmasi Hapus</h6>
                <button type="button" class="btn-close" data-coreui-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Hapus <strong id="bulkDeleteCount">0</strong> komentar terpilih? Tindakan ini tidak bisa dibatalkan.
            </div>
            <div class="modal-footer border-0 pt-0">
                <button class="btn btn-sm btn-secondary" data-coreui-dismiss="modal">Batal</button>
                <button type="submit" form="bulkForm" name="action" value="delete" class="btn btn-sm btn-danger">Hapus</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const checkAll   = document.getElementById('checkAll');
    const boxes      = Array.from(document.querySelectorAll('.msg-check'));
    const approveBtn = document.getElementById('bulkApproveBtn');
    const deleteBtn  = document.getElementById('bulkDeleteBtn');
    const approveCnt = document.getElementById('bulkApproveCount');
    const deleteCnt  = document.getElementById('bulkDeleteCount');

    if (!boxes.length) {
        return;
    }

    function refresh() {
        const selected = boxes.filter(function (box) { return box.checked; }).length;
        if (approveCnt) approveCnt.textContent = selected;
        if (deleteCnt) deleteCnt.textContent = selected;
        if (approveBtn) approveBtn.disabled = selected === 0;
        if (deleteBtn) deleteBtn.disabled = selected === 0;
        if (checkAll) checkAll.checked = selected === boxes.length;
    }

    if (checkAll) {
        checkAll.addEventListener('change', function () {
            boxes.forEach(function (box) { box.checked = checkAll.checked; });
            refresh();
        });
    }

    boxes.forEach(function (box) { box.addEventListener('change', refresh); });
    refresh();
});
</script>
<?= $this->endSection() ?>
