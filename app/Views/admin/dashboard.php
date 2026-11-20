<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<?php
$adminDisplayName = $user['fullName'] ?? ($user['full_name'] ?? ($user['username'] ?? 'Admin'));
$unreadCount      = (int) ($total_messages ?? 0);
$pendingCount     = (int) ($total_comments ?? 0);
$spmbTotal        = (int) ($total_spmb ?? 0);
$spmbPending      = (int) ($pending_spmb ?? 0);

$priorityKpis = [
    [
        'label'     => 'Pendaftar SPMB',
        'value'     => $spmbTotal,
        'sub'       => $spmbPending > 0 ? $spmbPending . ' perlu verifikasi' : 'Semua terverifikasi',
        'href'      => '/admin/spmb',
        'icon'      => 'graduation-cap',
        'tone'      => $spmbPending > 0 ? 'is-amber' : 'is-blue',
        'attention' => $spmbPending > 0,
    ],
    [
        'label'     => 'Berita',
        'value'     => (int) ($total_news ?? 0),
        'sub'       => 'Artikel & pengumuman',
        'href'      => '/admin/news',
        'icon'      => 'newspaper',
        'tone'      => '',
        'attention' => false,
    ],
    [
        'label'     => 'Pesan Belum Dibaca',
        'value'     => $unreadCount,
        'sub'       => $unreadCount > 0 ? 'Perlu ditinjau' : 'Semua terbaca',
        'href'      => '/admin/messages',
        'icon'      => 'mail',
        'tone'      => $unreadCount > 0 ? 'is-red' : 'is-blue',
        'attention' => $unreadCount > 0,
    ],
    [
        'label'     => 'Agenda Sekolah',
        'value'     => (int) ($total_events ?? 0),
        'sub'       => 'Jadwal kegiatan',
        'href'      => '/admin/events',
        'icon'      => 'calendar',
        'tone'      => 'is-blue',
        'attention' => false,
    ],
];

$moduleCounts = [
    ['label' => 'SPMB Online', 'value' => $spmbTotal, 'href' => '/admin/spmb'],
    ['label' => 'Guru & Staf', 'value' => (int) ($total_teachers ?? 0), 'href' => '/admin/teachers'],
    ['label' => 'Program',     'value' => (int) ($total_programs ?? 0), 'href' => '/admin/programs'],
    ['label' => 'Ekskul',      'value' => (int) ($total_extracurriculars ?? 0), 'href' => '/admin/extracurriculars'],
    ['label' => 'Prestasi',    'value' => (int) ($total_achievements ?? 0), 'href' => '/admin/achievements'],
    ['label' => 'Galeri',      'value' => (int) ($total_galleries ?? 0), 'href' => '/admin/gallery'],
    ['label' => 'Komentar',    'value' => $pendingCount, 'href' => '/admin/comments'],
    ['label' => 'Pengguna',    'value' => (int) ($total_users ?? 0), 'href' => '/admin/users'],
];

$quickLinks = [
    ['label' => 'Kelola Pendaftar SPMB', 'href' => '/admin/spmb',            'icon' => 'graduation-cap'],
    ['label' => 'Tulis Berita Baru',     'href' => '/admin/news/new',        'icon' => 'newspaper'],
    ['label' => 'Buat Agenda Baru',      'href' => '/admin/events/new',      'icon' => 'calendar'],
    ['label' => 'Upload Foto Galeri',    'href' => '/admin/gallery/new',     'icon' => 'images'],
    ['label' => 'Tambah Data Guru',      'href' => '/admin/teachers/new',    'icon' => 'users'],
    ['label' => 'Pengaturan Situs',      'href' => '/admin/settings',        'icon' => 'settings'],
];
?>

<div class="d-flex justify-content-between align-items-start align-items-sm-center flex-wrap gap-3 mb-4">
  <div>
    <h4 class="fw-bold mb-1">Dashboard</h4>
    <p class="text-body-secondary small mb-0">
      Masuk sebagai <strong><?= esc($adminDisplayName) ?></strong> &middot; Ringkasan konten &amp; layanan situs <?= esc($site_name ?? 'SMK PGRI 5 Serpong') ?>
    </p>
  </div>
  <div class="d-flex flex-wrap gap-2">
    <a href="/admin/spmb" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1">
      <?= lucide_icon('graduation-cap', '', 15) ?>
      <span>Manajemen SPMB</span>
    </a>
    <a href="/admin/news/new" class="btn btn-outline-primary btn-sm d-inline-flex align-items-center gap-1">
      <?= lucide_icon('plus', '', 15) ?>
      <span>Berita Baru</span>
    </a>
    <a href="/admin/events/new" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1">
      <?= lucide_icon('calendar', '', 15) ?>
      <span>Agenda Baru</span>
    </a>
  </div>
</div>

<!-- 4 Key Operational KPIs -->
<div class="row g-3 mb-3">
  <?php foreach ($priorityKpis as $kpi): ?>
  <div class="col-6 col-xl-3">
    <a href="<?= esc($kpi['href']) ?>" class="admin-kpi-card h-100 <?= $kpi['attention'] ? 'is-attention' : '' ?>">
      <div>
        <div class="admin-kpi-value"><?= esc((string) $kpi['value']) ?></div>
        <div class="admin-kpi-label"><?= esc($kpi['label']) ?></div>
        <div class="admin-kpi-sub"><?= esc($kpi['sub']) ?></div>
      </div>
      <span class="admin-kpi-icon <?= esc($kpi['tone']) ?>">
        <?= lucide_icon($kpi['icon'], '', 18) ?>
      </span>
    </a>
  </div>
  <?php endforeach; ?>
</div>

<!-- Compact Directory Module Inventory Bar -->
<div class="card mb-4">
  <div class="card-body py-2 px-3">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
      <?php foreach ($moduleCounts as $mc): ?>
      <a href="<?= esc($mc['href']) ?>" class="text-decoration-none d-inline-flex align-items-baseline gap-2 px-2 py-1 rounded text-body">
        <span class="fw-bold"><?= esc((string) $mc['value']) ?></span>
        <span class="small text-body-secondary"><?= esc($mc['label']) ?></span>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<!-- Manajemen SPMB Online (Pendaftar Terbaru) -->
<?php $dashSpmbModel = new \App\Models\SpmbModel(); ?>
<div class="card mb-4">
  <div class="card-header bg-transparent d-flex flex-wrap justify-content-between align-items-center gap-2 py-3">
    <h6 class="fw-semibold mb-0 d-inline-flex align-items-center gap-2">
      <?= lucide_icon('graduation-cap', 'text-primary', 18) ?>
      <span>Pendaftar SPMB Online Terbaru</span>
      <?php if ($spmbPending > 0): ?>
        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle"><?= $spmbPending ?> Perlu Verifikasi</span>
      <?php endif; ?>
    </h6>
    <div class="d-flex gap-2">
      <a href="/admin/spmb/new" class="btn btn-sm btn-outline-secondary">Tambah Manual</a>
      <a href="/admin/spmb" class="btn btn-sm btn-primary">Kelola Semua SPMB</a>
    </div>
  </div>
  <div class="card-body p-0">
    <?php if (! empty($recent_spmb)): ?>
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead>
            <tr class="small text-body-secondary">
              <th class="ps-3">No. Daftar</th>
              <th>Calon Siswa</th>
              <th>Jurusan</th>
              <th>Berkas</th>
              <th>Status</th>
              <th class="pe-3 text-end">Aksi Cepat</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($recent_spmb as $reg): ?>
              <?php
              $regId       = (int) ($reg['id'] ?? 0);
              $meta        = $dashSpmbModel->statusMeta((string) ($reg['status'] ?? 'pending'));
              $docsOk      = $dashSpmbModel->isDocsComplete($reg);
              $uploadedCnt = $dashSpmbModel->countUploadedDocs($reg);
              $hasWa       = $dashSpmbModel->normalizeWaPhone((string) ($reg['parent_phone'] ?? '')) !== '';
              ?>
              <tr>
                <td class="ps-3 font-monospace small fw-semibold"><?= esc($reg['registration_no'] ?? '-') ?></td>
                <td>
                  <div class="fw-medium small"><?= esc($reg['full_name'] ?? '-') ?></div>
                  <div class="text-body-secondary" style="font-size:.75rem">Wali: <?= esc($reg['parent_name'] ?? '-') ?> (<?= esc($reg['parent_phone'] ?? '-') ?>)</div>
                </td>
                <td class="small"><?= esc($reg['program_choice_1'] ?? '-') ?></td>
                <td>
                  <?php if ($docsOk): ?>
                    <span class="badge bg-success-subtle text-success">Lengkap</span>
                  <?php else: ?>
                    <span class="badge bg-warning-subtle text-warning-emphasis"><?= $uploadedCnt ?>/4 Berkas</span>
                  <?php endif; ?>
                </td>
                <td><span class="badge <?= esc($meta['badge_admin']) ?>"><?= esc($meta['label']) ?></span></td>
                <td class="pe-3 text-end">
                  <div class="d-inline-flex gap-1">
                    <?php if ($hasWa): ?>
                      <a href="/admin/spmb/send-wa/<?= $regId ?><?= ($reg['status'] ?? 'pending') === 'pending' ? '?auto_verify=1' : '' ?>"
                         target="_blank" rel="noopener noreferrer"
                         class="btn btn-sm btn-success d-inline-flex align-items-center gap-1">
                        <?= lucide_icon('message-circle', '', 13) ?><span>WA Wali</span>
                      </a>
                    <?php endif; ?>
                    <a href="/admin/spmb/<?= $regId ?>" class="btn btn-sm btn-outline-primary">Verifikasi</a>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <div class="text-center py-4 text-body-secondary small">Belum ada pendaftar SPMB Online.</div>
    <?php endif; ?>
  </div>
</div>

<!-- Content & Moderation Feeds -->
<div class="row g-4 mb-4">
  <!-- Berita Terbaru -->
  <div class="col-lg-6">
    <div class="card h-100">
      <div class="card-header bg-transparent d-flex justify-content-between align-items-center py-3">
        <h6 class="fw-semibold mb-0 d-inline-flex align-items-center gap-2">
          <?= lucide_icon('newspaper', 'text-primary', 17) ?>
          <span>Berita Terbaru</span>
        </h6>
        <a href="/admin/news" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
      </div>
      <div class="card-body p-0">
        <?php if (! empty($recent_news)): ?>
          <div class="list-group list-group-flush">
          <?php foreach ($recent_news as $item): ?>
          <a href="/admin/news/edit/<?= (int) $item['id'] ?>" class="list-group-item list-group-item-action d-flex align-items-center gap-3 px-3 py-2">
            <?php if (! empty($item['image'])): ?>
              <img src="<?= esc($item['image']) ?>" alt="<?= esc($item['title']) ?>" class="rounded flex-shrink-0" width="46" height="46" style="object-fit:cover">
            <?php else: ?>
              <span class="admin-thumb-placeholder"><?= lucide_icon('newspaper', '', 18) ?></span>
            <?php endif; ?>
            <div class="flex-grow-1 min-width-0">
              <div class="fw-medium small text-truncate"><?= esc($item['title']) ?></div>
              <div class="d-flex align-items-center gap-2 mt-1">
                <span class="badge bg-primary-subtle text-primary"><?= esc($item['category'] ?? 'Berita') ?></span>
                <small class="text-body-secondary d-inline-flex align-items-center gap-1">
                  <?= lucide_icon('clock', '', 13) ?>
                  <span><?= date('d M Y', strtotime($item['created_at'])) ?></span>
                </small>
              </div>
            </div>
            <?= lucide_icon('chevron-right', 'text-body-secondary flex-shrink-0', 16) ?>
          </a>
          <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="text-center py-5 text-body-secondary">
            <?= lucide_icon('newspaper', 'mb-2', 28) ?>
            <div class="small">Belum ada berita yang dipublikasikan.</div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Pesan Masuk -->
  <div class="col-lg-6">
    <div class="card h-100">
      <div class="card-header bg-transparent d-flex justify-content-between align-items-center py-3">
        <h6 class="fw-semibold mb-0 d-inline-flex align-items-center gap-2">
          <?= lucide_icon('mail', 'text-danger', 17) ?>
          <span>Pesan Masuk</span>
        </h6>
        <a href="/admin/messages" class="btn btn-sm btn-outline-secondary">Kelola Pesan</a>
      </div>
      <div class="card-body p-0">
        <?php if (! empty($recent_messages)): ?>
          <div class="list-group list-group-flush">
          <?php foreach ($recent_messages as $msg): ?>
          <a href="/admin/messages/<?= (int) $msg['id'] ?>" class="list-group-item list-group-item-action px-3 py-2">
            <div class="d-flex justify-content-between align-items-start gap-2">
              <div class="flex-grow-1 min-width-0">
                <div class="d-flex align-items-center gap-2">
                  <span class="fw-medium small"><?= esc($msg['name']) ?></span>
                  <?php if (empty($msg['is_read'])): ?>
                  <span class="badge bg-danger">Baru</span>
                  <?php endif; ?>
                </div>
                <div class="text-body-secondary small text-truncate mt-1"><?= esc($msg['message']) ?></div>
              </div>
              <small class="text-body-secondary flex-shrink-0"><?= date('d M', strtotime($msg['created_at'])) ?></small>
            </div>
          </a>
          <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="text-center py-5 text-body-secondary">
            <?= lucide_icon('mail', 'mb-2', 28) ?>
            <div class="small">Belum ada pesan masuk.</div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<!-- Agenda, Komentar Menunggu & Akses Cepat -->
<div class="row g-4">
  <div class="col-lg-4">
    <div class="card h-100">
      <div class="card-header bg-transparent d-flex justify-content-between align-items-center py-3">
        <h6 class="fw-semibold mb-0 d-inline-flex align-items-center gap-2">
          <?= lucide_icon('calendar', 'text-primary', 17) ?>
          <span>Agenda Terdekat</span>
        </h6>
        <a href="/admin/events" class="btn btn-sm btn-outline-secondary">Semua</a>
      </div>
      <div class="card-body p-0">
        <?php if (! empty($upcoming_events)): ?>
          <div class="list-group list-group-flush">
          <?php foreach ($upcoming_events as $ev): ?>
          <a href="/admin/events/edit/<?= (int) $ev['id'] ?>" class="list-group-item list-group-item-action d-flex align-items-center gap-3 px-3 py-2">
            <div class="rounded bg-body-tertiary border d-flex flex-column align-items-center justify-content-center flex-shrink-0" style="width:42px;height:42px">
              <span class="fw-bold small lh-1"><?= date('d', strtotime($ev['event_date'])) ?></span>
              <span class="text-body-secondary" style="font-size:.65rem"><?= date('M', strtotime($ev['event_date'])) ?></span>
            </div>
            <div class="flex-grow-1 min-width-0">
              <div class="fw-medium small text-truncate"><?= esc($ev['title']) ?></div>
              <div class="d-flex flex-wrap gap-2 text-body-secondary mt-1" style="font-size:.75rem">
                <?php if (! empty($ev['location'])): ?>
                <span class="d-inline-flex align-items-center gap-1"><?= lucide_icon('map-pin', '', 12) ?><?= esc($ev['location']) ?></span>
                <?php endif; ?>
                <?php if (! empty($ev['event_time'])): ?>
                <span class="d-inline-flex align-items-center gap-1"><?= lucide_icon('clock', '', 12) ?><?= esc($ev['event_time']) ?></span>
                <?php endif; ?>
              </div>
            </div>
          </a>
          <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="text-center py-5 text-body-secondary">
            <?= lucide_icon('calendar', 'mb-2', 28) ?>
            <div class="small">Tidak ada agenda terjadwal.</div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="card h-100">
      <div class="card-header bg-transparent d-flex justify-content-between align-items-center py-3">
        <h6 class="fw-semibold mb-0 d-inline-flex align-items-center gap-2">
          <?= lucide_icon('message-circle', 'text-warning', 17) ?>
          <span>Komentar Menunggu</span>
        </h6>
        <a href="/admin/comments" class="btn btn-sm btn-outline-secondary">Moderasi</a>
      </div>
      <div class="card-body p-0">
        <?php if (! empty($pending_comments)): ?>
          <div class="list-group list-group-flush">
          <?php foreach ($pending_comments as $c): ?>
          <div class="list-group-item px-3 py-2">
            <div class="d-flex justify-content-between align-items-center gap-2">
              <span class="fw-medium small"><?= esc($c['name']) ?></span>
              <small class="text-body-secondary"><?= date('d M H:i', strtotime($c['created_at'])) ?></small>
            </div>
            <div class="text-body-secondary small text-truncate mt-1"><?= esc($c['comment']) ?></div>
          </div>
          <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="text-center py-5 text-body-secondary">
            <?= lucide_icon('check-circle', 'mb-2', 28) ?>
            <div class="small">Semua komentar sudah dimoderasi.</div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="card h-100">
      <div class="card-header bg-transparent py-3">
        <h6 class="fw-semibold mb-0 d-inline-flex align-items-center gap-2">
          <?= lucide_icon('zap', 'text-primary', 17) ?>
          <span>Pintasan Aksi</span>
        </h6>
      </div>
      <div class="card-body d-flex flex-column gap-2">
        <?php foreach ($quickLinks as $ql): ?>
        <a href="<?= esc($ql['href']) ?>" class="admin-action-link">
          <span class="d-inline-flex align-items-center gap-2">
            <?= lucide_icon($ql['icon'], 'text-primary', 16) ?>
            <span><?= esc($ql['label']) ?></span>
          </span>
          <?= lucide_icon('arrow-right', 'text-body-secondary', 15) ?>
        </a>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
