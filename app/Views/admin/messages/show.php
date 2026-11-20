<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1"><?= esc($item['subject'] ?? '(Tanpa Subjek)') ?></h4>
        <p class="text-body-tertiary small mb-0">Detail pesan dari <?= esc($item['name']) ?></p>
    </div>
    <a href="/admin/messages" class="btn btn-outline-secondary btn-sm">
        <?= lucide_icon('arrow-left', 'me-1', 14) ?>Kembali
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-body p-4">
                <h6 class="small fw-semibold text-body-tertiary text-uppercase mb-3">
                    <?= lucide_icon('message-circle', 'me-1', 15) ?>Pesan
                </h6>
                <div class="p-3 bg-body-tertiary rounded-3" style="white-space:pre-wrap;line-height:1.7"><?= esc($item['message'] ?? '-') ?></div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm border-0 rounded-3 mb-3">
            <div class="card-header bg-transparent border-bottom-0 py-3">
                <h6 class="mb-0"><?= lucide_icon('info', 'me-2 text-primary', 16) ?>Informasi Pengirim</h6>
            </div>
            <div class="card-body pt-0">
                <dl class="mb-0">
                    <dt class="small fw-semibold text-body-tertiary mb-1">Nama</dt>
                    <dd class="mb-3"><?= esc($item['name']) ?></dd>
                    <dt class="small fw-semibold text-body-tertiary mb-1">Email</dt>
                    <dd class="mb-3">
                        <a href="mailto:<?= esc($item['email']) ?>"><?= esc($item['email']) ?></a>
                    </dd>
                    <dt class="small fw-semibold text-body-tertiary mb-1">Subjek</dt>
                    <dd class="mb-3"><?= esc($item['subject'] ?? '-') ?></dd>
                    <dt class="small fw-semibold text-body-tertiary mb-1">Diterima</dt>
                    <dd class="mb-3"><?= isset($item['created_at']) ? date('d M Y H:i', strtotime($item['created_at'])) : '-' ?></dd>
                    <dt class="small fw-semibold text-body-tertiary mb-1">Status</dt>
                    <dd>
                        <?php if (!empty($item['is_read'])): ?>
                            <span class="badge bg-secondary-subtle text-secondary px-3 py-2"><?= lucide_icon('check-circle', 'me-1', 13) ?>Sudah dibaca</span>
                        <?php else: ?>
                            <span class="badge bg-success-subtle text-success px-3 py-2"><?= lucide_icon('mail', 'me-1', 13) ?>Baru</span>
                        <?php endif; ?>
                    </dd>
                </dl>
            </div>
        </div>

        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-body">
                <div class="d-flex gap-2">
                    <a href="/admin/messages" class="btn btn-outline-secondary btn-sm flex-grow-1">
                        <?= lucide_icon('arrow-left', 'me-1', 14) ?>Kembali
                    </a>
                    <button type="button" class="btn btn-outline-danger btn-sm flex-grow-1" data-coreui-toggle="modal" data-coreui-target="#deleteMessageModal">
                        <?= lucide_icon('trash-2', 'me-1', 14) ?>Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal konfirmasi hapus (tanpa interpolasi ke JS inline) -->
<div class="modal fade" id="deleteMessageModal" tabindex="-1">
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
                <form action="/admin/messages/delete/<?= (int) $item['id'] ?>" method="post" class="d-inline">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
