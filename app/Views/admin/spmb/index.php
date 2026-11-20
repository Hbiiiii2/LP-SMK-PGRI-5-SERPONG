<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<?php
$spmbModel     = $spmb_model ?? new \App\Models\SpmbModel();
$stats         = $stats ?? ['all' => 0, 'pending' => 0, 'verified' => 0, 'accepted' => 0, 'revision' => 0, 'rejected' => 0];
$items         = $items ?? [];
$filterStatus  = (string) ($filter_status ?? '');
$filterProgram = (string) ($filter_program ?? '');
$filterQ       = (string) ($filter_q ?? '');
$programOpts   = $program_options ?? [];
$siteName      = (string) ($site_name ?? 'SMK PGRI 5 Serpong');
?>

<div class="d-flex justify-content-between align-items-start align-items-sm-center flex-wrap gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1">Manajemen SPMB Online</h4>
        <p class="text-body-secondary small mb-0">Kelola pendaftar calon peserta didik baru, verifikasi berkas persyaratan, dan kirim informasi langsung ke WhatsApp wali murid.</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a href="/spmb" target="_blank" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1">
            <?= lucide_icon('external-link', '', 14) ?><span>Lihat Formulir Publik</span>
        </a>
        <a href="/admin/spmb/export" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1">
            <?= lucide_icon('download', '', 14) ?><span>Export CSV</span>
        </a>
        <a href="/admin/spmb/new" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1">
            <?= lucide_icon('plus', '', 15) ?><span>Tambah Pendaftar</span>
        </a>
    </div>
</div>

<!-- Top KPI Summary -->
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <a href="/admin/spmb" class="admin-kpi-card h-100">
            <div>
                <div class="admin-kpi-value"><?= (int) $stats['all'] ?></div>
                <div class="admin-kpi-label">Total Pendaftar</div>
                <div class="admin-kpi-sub">Semua calon siswa</div>
            </div>
            <span class="admin-kpi-icon is-blue"><?= lucide_icon('users', '', 18) ?></span>
        </a>
    </div>
    <div class="col-6 col-xl-3">
        <a href="/admin/spmb?status=pending" class="admin-kpi-card h-100 <?= $stats['pending'] > 0 ? 'is-attention' : '' ?>">
            <div>
                <div class="admin-kpi-value"><?= (int) $stats['pending'] ?></div>
                <div class="admin-kpi-label">Menunggu Verifikasi</div>
                <div class="admin-kpi-sub"><?= $stats['pending'] > 0 ? 'Perlu dicek panitia' : 'Semua sudah dicek' ?></div>
            </div>
            <span class="admin-kpi-icon is-amber"><?= lucide_icon('clock', '', 18) ?></span>
        </a>
    </div>
    <div class="col-6 col-xl-3">
        <a href="/admin/spmb?status=verified" class="admin-kpi-card h-100">
            <div>
                <div class="admin-kpi-value"><?= (int) $stats['verified'] ?></div>
                <div class="admin-kpi-label">Berkas Lengkap</div>
                <div class="admin-kpi-sub">Siap kirim info WA / seleksi</div>
            </div>
            <span class="admin-kpi-icon is-blue"><?= lucide_icon('check-circle', '', 18) ?></span>
        </a>
    </div>
    <div class="col-6 col-xl-3">
        <a href="/admin/spmb?status=accepted" class="admin-kpi-card h-100">
            <div>
                <div class="admin-kpi-value"><?= (int) $stats['accepted'] ?></div>
                <div class="admin-kpi-label">Dinyatakan Diterima</div>
                <div class="admin-kpi-sub">Siap daftar ulang</div>
            </div>
            <span class="admin-kpi-icon"><?= lucide_icon('graduation-cap', '', 18) ?></span>
        </a>
    </div>
</div>

<!-- Filter & Search Bar -->
<div class="card shadow-sm border-0 rounded-3 mb-3">
    <div class="card-body py-3">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div class="btn-group btn-group-sm flex-wrap" role="group" aria-label="Filter status pendaftar">
                <a href="/admin/spmb" class="btn btn-outline-secondary <?= $filterStatus === '' ? 'active' : '' ?>">Semua (<?= (int) $stats['all'] ?>)</a>
                <a href="/admin/spmb?status=pending" class="btn btn-outline-secondary <?= $filterStatus === 'pending' ? 'active' : '' ?>">Menunggu (<?= (int) $stats['pending'] ?>)</a>
                <a href="/admin/spmb?status=verified" class="btn btn-outline-secondary <?= $filterStatus === 'verified' ? 'active' : '' ?>">Terverifikasi (<?= (int) $stats['verified'] ?>)</a>
                <a href="/admin/spmb?status=accepted" class="btn btn-outline-secondary <?= $filterStatus === 'accepted' ? 'active' : '' ?>">Diterima (<?= (int) $stats['accepted'] ?>)</a>
                <a href="/admin/spmb?status=revision" class="btn btn-outline-secondary <?= $filterStatus === 'revision' ? 'active' : '' ?>">Perbaikan (<?= (int) $stats['revision'] ?>)</a>
            </div>

            <form action="/admin/spmb" method="get" class="d-flex flex-wrap gap-2 align-items-center">
                <?php if ($filterStatus !== ''): ?>
                    <input type="hidden" name="status" value="<?= esc($filterStatus) ?>">
                <?php endif; ?>
                <select name="program" class="form-select form-select-sm" style="width:auto;max-width:220px">
                    <option value="">Semua Jurusan</option>
                    <?php foreach ($programOpts as $opt): ?>
                        <option value="<?= esc($opt) ?>" <?= $filterProgram === $opt ? 'selected' : '' ?>><?= esc($opt) ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="input-group input-group-sm" style="width:230px">
                    <input type="text" name="q" class="form-control" value="<?= esc($filterQ) ?>" placeholder="Cari kode, nama, sekolah, WA...">
                    <button type="submit" class="btn btn-primary" title="Cari"><?= lucide_icon('search', '', 14) ?></button>
                </div>
                <?php if ($filterStatus !== '' || $filterProgram !== '' || $filterQ !== ''): ?>
                    <a href="/admin/spmb" class="btn btn-sm btn-outline-secondary">Reset</a>
                <?php endif; ?>
            </form>
        </div>
    </div>
</div>

<!-- Applicants Table -->
<div class="card shadow-sm border-0 rounded-3">
    <div class="table-responsive">
        <table class="table table-crud table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>No. Pendaftaran</th>
                    <th>Calon Siswa</th>
                    <th>Pilihan Jurusan</th>
                    <th>Orang Tua / Wali</th>
                    <th>Berkas</th>
                    <th>Status</th>
                    <th style="min-width:260px">Aksi &amp; WhatsApp</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <?php
                    $id          = (int) ($item['id'] ?? 0);
                    $meta        = $spmbModel->statusMeta((string) ($item['status'] ?? 'pending'));
                    $docsOk      = $spmbModel->isDocsComplete($item);
                    $uploadedCnt = $spmbModel->countUploadedDocs($item);
                    $isPending   = ($item['status'] ?? 'pending') === 'pending';
                    $waSent      = ! empty($item['wa_notified_at']);
                    $hasWaPhone  = $spmbModel->normalizeWaPhone((string) ($item['parent_phone'] ?? '')) !== '';
                    ?>
                    <tr class="<?= empty($item['is_read']) ? 'table-info' : '' ?>">
                        <td>
                            <a href="/admin/spmb/<?= $id ?>" class="fw-semibold text-decoration-none font-monospace"><?= esc($item['registration_no'] ?? '-') ?></a>
                            <div class="text-body-tertiary" style="font-size:.75rem">
                                <?= ! empty($item['created_at']) ? date('d M Y H:i', strtotime((string) $item['created_at'])) : '-' ?>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="fw-medium"><?= esc($item['full_name'] ?? '-') ?></span>
                                <?php if (empty($item['is_read'])): ?>
                                    <span class="badge bg-primary">Baru</span>
                                <?php endif; ?>
                            </div>
                            <div class="text-body-secondary small">
                                <?= esc($item['origin_school'] ?: '-') ?>
                                <?php if (! empty($item['nisn'])): ?>
                                    &middot; NISN: <?= esc($item['nisn']) ?>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td>
                            <div class="small fw-medium"><?= esc($item['program_choice_1'] ?? '-') ?></div>
                            <?php if (! empty($item['program_choice_2'])): ?>
                                <div class="text-body-tertiary" style="font-size:.75rem">Alt: <?= esc($item['program_choice_2']) ?></div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="small fw-medium"><?= esc($item['parent_name'] ?? '-') ?> <span class="text-body-tertiary">(<?= esc($item['parent_relationship'] ?? 'Wali') ?>)</span></div>
                            <div class="small text-body-secondary font-monospace"><?= esc($item['parent_phone'] ?? '-') ?></div>
                        </td>
                        <td>
                            <?php if ($docsOk): ?>
                                <span class="badge bg-success-subtle text-success border border-success-subtle">
                                    <?= lucide_icon('check-circle', 'me-1', 12) ?>Lengkap (<?= $uploadedCnt ?>/4)
                                </span>
                            <?php else: ?>
                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">
                                    <?= $uploadedCnt ?>/4 Berkas
                                </span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge <?= esc($meta['badge_admin']) ?>"><?= esc($meta['label']) ?></span>
                            <?php if ($waSent): ?>
                                <div class="text-success mt-1" style="font-size:.72rem">
                                    <?= lucide_icon('check', 'me-1', 11) ?>WA dikirim <?= date('d M H:i', strtotime((string) $item['wa_notified_at'])) ?>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="d-flex flex-wrap gap-1 align-items-center">
                                <?php if ($hasWaPhone): ?>
                                    <?php if ($isPending && $docsOk): ?>
                                        <a href="/admin/spmb/send-wa/<?= $id ?>?auto_verify=1" target="_blank" rel="noopener noreferrer"
                                           class="btn btn-sm btn-success d-inline-flex align-items-center gap-1"
                                           title="Verifikasi otomatis &amp; kirim informasi lengkap ke WhatsApp wali murid">
                                            <?= lucide_icon('message-circle', '', 13) ?><span>Verifikasi &amp; WA</span>
                                        </a>
                                    <?php elseif ($docsOk || in_array($item['status'] ?? '', ['verified', 'accepted'], true)): ?>
                                        <a href="/admin/spmb/send-wa/<?= $id ?>" target="_blank" rel="noopener noreferrer"
                                           class="btn btn-sm btn-success d-inline-flex align-items-center gap-1"
                                           title="Kirim info pendaftaran langsung ke WhatsApp wali murid">
                                            <?= lucide_icon('message-circle', '', 13) ?><span>Kirim WA Wali</span>
                                        </a>
                                    <?php else: ?>
                                        <a href="/admin/spmb/send-wa/<?= $id ?>?auto_verify=1" target="_blank" rel="noopener noreferrer"
                                           class="btn btn-sm btn-outline-success d-inline-flex align-items-center gap-1"
                                           title="Tandai lengkap/terverifikasi &amp; kirim info WhatsApp ke wali murid">
                                            <?= lucide_icon('message-circle', '', 13) ?><span>WA Wali</span>
                                        </a>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <a href="/admin/spmb/<?= $id ?>" class="btn btn-sm btn-outline-primary" title="Detail &amp; Verifikasi Berkas">
                                    <?= lucide_icon('eye', '', 14) ?>
                                </a>
                                <a href="/admin/spmb/edit/<?= $id ?>" class="btn btn-sm btn-outline-secondary" title="Edit Data">
                                    <?= lucide_icon('pencil', '', 14) ?>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger" title="Hapus"
                                        data-coreui-toggle="modal" data-coreui-target="#deleteSpmbModal<?= $id ?>">
                                    <?= lucide_icon('trash-2', '', 14) ?>
                                </button>
                            </div>

                            <div class="modal fade" id="deleteSpmbModal<?= $id ?>" tabindex="-1">
                                <div class="modal-dialog modal-sm modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header border-0 pb-0">
                                            <h6 class="modal-title">Konfirmasi Hapus</h6>
                                            <button type="button" class="btn-close" data-coreui-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            Hapus pendaftar <strong><?= esc($item['full_name'] ?? '') ?></strong> (<?= esc($item['registration_no'] ?? '') ?>)? Berkas unggahan ikut dihapus.
                                        </div>
                                        <div class="modal-footer border-0 pt-0">
                                            <button class="btn btn-sm btn-secondary" data-coreui-dismiss="modal">Batal</button>
                                            <form action="/admin/spmb/delete/<?= $id ?>" method="post" class="d-inline">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($items)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-5 text-body-tertiary">
                            Belum ada pendaftar SPMB yang sesuai dengan filter saat ini.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if (! empty($pager) && $pager->links()): ?>
        <div class="d-flex justify-content-center py-3 border-top">
            <?= $pager->links() ?>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
