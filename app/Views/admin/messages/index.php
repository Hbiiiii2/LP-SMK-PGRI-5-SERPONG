<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Pesan</h4>
        <p class="text-body-tertiary small mb-0">Kelola pesan pengunjung</p>
    </div>
</div>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <div class="btn-group btn-group-sm" role="group" aria-label="Filter status pesan">
        <a href="/admin/messages" class="btn btn-outline-secondary <?= $status === '' ? 'active' : '' ?>">Semua (<?= (int) $counts['all'] ?>)</a>
        <a href="/admin/messages?status=unread" class="btn btn-outline-secondary <?= $status === 'unread' ? 'active' : '' ?>">Belum dibaca (<?= (int) $counts['unread'] ?>)</a>
        <a href="/admin/messages?status=read" class="btn btn-outline-secondary <?= $status === 'read' ? 'active' : '' ?>">Sudah dibaca (<?= (int) $counts['read'] ?>)</a>
    </div>
    <button type="button" class="btn btn-sm btn-outline-danger" id="bulkDeleteBtn"
            data-coreui-toggle="modal" data-coreui-target="#bulkDeleteModal" disabled>
        <?= lucide_icon('trash-2', 'me-1', 14) ?>Hapus terpilih
    </button>
</div>

<div class="card shadow-sm border-0 rounded-3">
    <div class="table-responsive">
        <table class="table table-crud table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th style="width:40px"><input class="form-check-input" type="checkbox" id="checkAll" title="Pilih semua"></th>
                    <th style="width:60px">#</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Subjek</th>
                    <th style="width:120px">Tanggal</th>
                    <th style="width:80px">Status</th>
                    <th style="width:170px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $i => $item): ?>
                <tr class="<?= empty($item['is_read']) ? 'table-info' : '' ?>">
                    <td><input class="form-check-input msg-check" type="checkbox" name="ids[]" value="<?= (int) $item['id'] ?>" form="bulkDeleteForm" aria-label="Pilih pesan dari <?= esc($item['name']) ?>"></td>
                    <td><span class="badge bg-body-tertiary text-body-tertiary fw-normal"><?= $i + 1 + (($pager->getCurrentPage() - 1) * 10) ?></span></td>
                    <td class="fw-medium"><?= esc($item['name']) ?></td>
                    <td class="text-body-secondary small"><?= esc($item['email']) ?></td>
                    <td class="text-body-secondary"><?= esc($item['subject'] ?? '-') ?></td>
                    <td class="text-body-secondary small"><?= isset($item['created_at']) ? date('d M Y H:i', strtotime($item['created_at'])) : '-' ?></td>
                    <td>
                        <?php if (!empty($item['is_read'])): ?>
                            <span class="badge bg-secondary">Dibaca</span>
                        <?php else: ?>
                            <span class="badge bg-success">Baru</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="/admin/messages/<?= (int) $item['id'] ?>" class="btn btn-sm btn-outline-info border-0" title="View">
                                <?= lucide_icon('eye', '', 14) ?>
                            </a>
                            <?php $toggleTitle = empty($item['is_read']) ? 'Tandai sudah dibaca' : 'Tandai belum dibaca'; ?>
                            <form action="/admin/messages/<?= empty($item['is_read']) ? 'read' : 'unread' ?>/<?= (int) $item['id'] ?>" method="post" class="d-inline">
                                <?= csrf_field() ?>
                                <input type="hidden" name="status" value="<?= esc($status) ?>">
                                <button type="submit" class="btn btn-sm btn-outline-secondary border-0" title="<?= $toggleTitle ?>">
                                    <?= lucide_icon(empty($item['is_read']) ? 'mail' : 'check-circle', '', 14) ?>
                                </button>
                            </form>
                            <button class="btn btn-sm btn-outline-danger border-0" title="Delete" data-coreui-toggle="modal" data-coreui-target="#deleteModal<?= (int) $item['id'] ?>">
                                <?= lucide_icon('trash-2', '', 14) ?>
                            </button>
                        </div>
                        <div class="modal fade" id="deleteModal<?= (int) $item['id'] ?>" tabindex="-1">
                            <div class="modal-dialog modal-sm modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header border-0 pb-0">
                                        <h6 class="modal-title">Konfirmasi Hapus</h6>
                                        <button type="button" class="btn-close" data-coreui-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        Yakin ingin menghapus pesan dari "<strong><?= esc($item['name']) ?></strong>"?
                                    </div>
                                    <div class="modal-footer border-0 pt-0">
                                        <button class="btn btn-sm btn-secondary" data-coreui-dismiss="modal">Batal</button>
                                        <form action="/admin/messages/delete/<?= (int) $item['id'] ?>" method="post" class="d-inline"><?= csrf_field() ?><button type="submit" class="btn btn-sm btn-danger">Hapus</button></form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($items)): ?>
                <tr><td colspan="8" class="text-center py-5 text-body-tertiary">Tidak ada pesan ditemukan.</td></tr>
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

<?php // Form hapus massal diletakkan di luar tabel agar tidak ada <form> bersarang;
      // checkbox baris mengacu ke form ini lewat atribut form="bulkDeleteForm". ?>
<form id="bulkDeleteForm" action="/admin/messages/bulk-delete" method="post" class="d-none">
    <?= csrf_field() ?>
    <input type="hidden" name="status" value="<?= esc($status) ?>">
</form>

<div class="modal fade" id="bulkDeleteModal" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title">Konfirmasi Hapus</h6>
                <button type="button" class="btn-close" data-coreui-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Hapus <strong id="bulkDeleteCount">0</strong> pesan terpilih? Tindakan ini tidak bisa dibatalkan.
            </div>
            <div class="modal-footer border-0 pt-0">
                <button class="btn btn-sm btn-secondary" data-coreui-dismiss="modal">Batal</button>
                <button type="submit" form="bulkDeleteForm" class="btn btn-sm btn-danger">Hapus</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const checkAll  = document.getElementById('checkAll');
    const boxes     = Array.from(document.querySelectorAll('.msg-check'));
    const bulkBtn   = document.getElementById('bulkDeleteBtn');
    const bulkCount = document.getElementById('bulkDeleteCount');

    if (!boxes.length) {
        return;
    }

    function refresh() {
        const selected = boxes.filter(function (box) { return box.checked; }).length;
        if (bulkCount) bulkCount.textContent = selected;
        if (bulkBtn) bulkBtn.disabled = selected === 0;
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
