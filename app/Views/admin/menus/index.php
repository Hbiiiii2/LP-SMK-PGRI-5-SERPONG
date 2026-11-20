<?= $this->extend('admin/layout') ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Menu Navigasi</h4>
        <p class="text-body-tertiary small mb-0">Atur menu navigasi website — seret baris untuk mengubah urutan dan kedudukan (induk/anak). Lihat <strong>Keterangan</strong> di bawah untuk cakupan <em>Urutan</em>.</p>
    </div>
    <a href="/admin/menus/new" class="btn btn-primary px-3">
        <?= lucide_icon('plus', 'me-1', 16) ?>Tambah Menu
    </a>
</div>

<?php if (!empty($orphanCount)): ?>
<div class="alert alert-warning d-flex align-items-start gap-2 rounded-3" role="alert">
    <?= lucide_icon('alert-triangle', 'mt-1 flex-shrink-0', 16) ?>
    <div class="small">
        <strong><?= (int) $orphanCount ?> item menu tidak tampil di daftar ini.</strong>
        Induk item-item itu saling menunjuk sehingga tidak terjangkau dari Menu Utama (struktur berputar) — biasanya sisa data lama atau hasil impor.
        Perbaiki lewat database, mis. <code>UPDATE menu_items SET parent_id = NULL WHERE id IN (…);</code>
    </div>
</div>
<?php endif; ?>

<div class="card shadow-sm border-0 rounded-3">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-crud table-hover align-middle mb-0" id="menus-table">
                <thead>
                    <tr>
                        <th style="width:40px"><?= lucide_icon('grip-vertical', 'text-body-tertiary', 16) ?></th>
                        <th style="width:60px">#</th>
                        <th>Judul</th>
                        <th style="width:200px">URL</th>
                        <th style="width:80px">Target</th>
                        <th style="width:70px">Tipe</th>
                        <th style="width:90px">Status</th>
                        <th style="width:60px">Urutan</th>
                        <th style="width:110px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $num = 1; ?>
                    <?php foreach ($allItems as $item): ?>
                    <?php
                    $isChild   = !empty($item['parent_id']);
                    $childClass = $isChild ? ' table-row-child' : '';
                    $isSystem  = $item['type'] === 'system';
                    $canDelete = $item['type'] === 'custom';
                    $typeLabels = ['custom' => 'Custom', 'system' => 'System', 'page' => 'Halaman'];
                    $typeBadge  = $typeLabels[$item['type']] ?? $item['type'];
                    $typeColor  = $item['type'] === 'system' ? 'secondary' : ($item['type'] === 'page' ? 'info' : 'light text-body');
                    ?>
                    <tr data-id="<?= $item['id'] ?>" data-parent-id="<?= $item['parent_id'] ?? '' ?>" draggable="true" class="<?= $childClass ?>">
                        <td class="drag-handle" style="cursor:grab"><?= lucide_icon('grip-vertical', 'text-body-tertiary', 16) ?></td>
                        <td><span class="badge bg-body-tertiary text-body-tertiary fw-normal"><?= $num++ ?></span></td>
                        <td class="fw-medium">
                            <?php if ($isChild): ?><span class="child-indent"><?= lucide_icon('corner-up-left', 'me-2 text-body-tertiary', 14) ?></span><?php endif; ?>
                            <?= esc($item['title']) ?>
                            <?php if ($item['type'] === 'page' && !empty($item['page_id']) && isset($pageTitles[$item['page_id']])): ?>
                            <span class="badge bg-info-subtle text-info-emphasis ms-1 fw-normal" style="font-size:0.65rem"><?= esc($pageTitles[$item['page_id']]) ?></span>
                            <?php endif; ?>
                            <?php if ($item['icon']): ?><?= lucide_icon((string) $item['icon'], 'ms-1 text-body-tertiary', 14) ?><?php endif; ?>
                        </td>
                        <td class="small"><code><?= esc($item['url']) ?></code></td>
                        <td class="small"><?= $item['target'] === '_blank' ? lucide_icon('external-link', 'me-1', 13) . 'Blank' : 'Same' ?></td>
                        <td><span class="badge bg-<?= $typeColor ?>"><?= $typeBadge ?></span></td>
                        <td>
                            <?php if ($item['type'] === 'system'): ?>
                                <span class="badge bg-light text-body-tertiary border" title="Menu bawaan tema — selalu tampil, status tidak berlaku">Bawaan</span>
                            <?php elseif ($item['status']): ?>
                                <span class="badge bg-success">Aktif</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Nonaktif</span>
                            <?php endif; ?>
                        </td>
                        <td><span class="badge bg-light text-body-tertiary border"><?= esc($item['sort_order'] ?? 0) ?></span></td>
                        <td>
                            <div class="d-flex gap-1">
                                <?php if ($isSystem): ?>
                                <span class="btn btn-sm btn-outline-secondary border-0 disabled" title="Menu bawaan tidak dapat diedit">
                                    <?= lucide_icon('lock', '', 13) ?>
                                </span>
                                <?php else: ?>
                                <a href="/admin/menus/edit/<?= $item['id'] ?>" class="btn btn-sm btn-outline-warning border-0" title="<?= $canDelete ? 'Edit' : 'Edit (induk &amp; urutan)' ?>">
                                    <?= lucide_icon('pencil', '', 14) ?>
                                </a>
                                <?php if ($canDelete): ?>
                                <button class="btn btn-sm btn-outline-danger border-0" title="Hapus" data-coreui-toggle="modal" data-coreui-target="#deleteModal<?= $item['id'] ?>">
                                    <?= lucide_icon('trash-2', '', 14) ?>
                                </button>
                                <?php endif; ?>
                                <?php endif; ?>
                            </div>
                            <?php if ($canDelete): ?>
                            <div class="modal fade" id="deleteModal<?= $item['id'] ?>" tabindex="-1">
                                <div class="modal-dialog modal-sm modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header border-0 pb-0">
                                            <h6 class="modal-title">Konfirmasi Hapus</h6>
                                            <button type="button" class="btn-close" data-coreui-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            Yakin ingin menghapus "<strong><?= esc($item['title']) ?></strong>"?
                                        </div>
                                        <div class="modal-footer border-0 pt-0">
                                            <button class="btn btn-sm btn-secondary" data-coreui-dismiss="modal">Batal</button>
                                            <form action="/admin/menus/delete/<?= $item['id'] ?>" method="post" class="d-inline"><?= csrf_field() ?><button type="submit" class="btn btn-sm btn-danger">Hapus</button></form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($allItems)): ?>
                    <tr><td colspan="9" class="text-center py-5 text-body-tertiary">Tidak ada menu ditemukan. <a href="/admin/menus/new">Tambah menu</a></td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0 rounded-3 mt-4">
    <div class="card-header bg-transparent py-3">
        <h6 class="mb-0"><?= lucide_icon('info', 'me-2 text-primary', 16) ?>Keterangan</h6>
    </div>
    <div class="card-body small">
        <p class="mb-1">
            <span class="badge bg-secondary">System</span> — menu bawaan tema. Selalu tampil di header dan tidak dapat diedit atau dihapus; kolom Status <em>dan</em> Urutan tidak berpengaruh ke header.
        </p>
        <p class="mb-1">
            <span class="badge bg-info">Halaman</span> — menu otomatis dari halaman statis; judul &amp; URL disinkronkan otomatis. Tampil hanya bila halaman berstatus <em>published</em>.
        </p>
        <p class="mb-1">
            <span class="badge bg-light text-body border">Custom</span> — menu buatan manual yang dapat diedit dan dihapus. Status <strong>Aktif/Nonaktif</strong> berpengaruh ke navigasi depan.
        </p>
        <p class="mb-1">
            <strong>Urutan</strong> (kolom Urutan / seret baris) hanya mengatur urutan <em>menu Custom &amp; Halaman</em> di panel navigasi depan, dan urutan daftar di halaman ini. 13 halaman bawaan tema (Beranda, Profil, Program, Berita, Kontak, Pengajar s/d Download) urutannya tetap mengikuti tema — menggeser baris System tidak mengubah header.
        </p>
        <p class="mb-1">
            <strong>Anak menu (dropdown)</strong> mengubah kedudukan di daftar ini saja. Tema belum menampilkan dropdown, sehingga menu anak tidak muncul di navigasi depan.
        </p>
        <p class="mb-1"><code>Section Key</code> bersifat opsional — hanya dipakai sebagai URL cadangan bila kolom URL dikosongkan.</p>
        <p class="mb-0 text-body-secondary bg-body-tertiary rounded p-2 mt-2">
            <?= lucide_icon('info', 'me-1', 14) ?>Item Custom/Halaman dibaca langsung dari database setiap halaman dimuat (tanpa cache menu), jadi perubahan menu langsung tampil. Halaman berita, kontak, dan unduhan memakai page cache singkat (~1–5 detik); hapus isi folder <code>writable/cache/</code> bila perlu memaksa segar.
        </p>
    </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('styles') ?>
<style>
#menus-table tbody tr.dragging { opacity:.35; }
#menus-table tbody tr.insert-indicator { border-top:2px solid var(--bs-primary); }
#menus-table tbody tr.insert-indicator td { padding:0!important; height:0!important; }
/* Kedudukan anak menu: beri jarak nyata pada kolom Judul, bukan hanya ikon. */
#menus-table tbody tr.table-row-child > td:nth-child(3) { padding-left:2.25rem; }
#menus-table tbody tr.table-row-child > td:nth-child(2) > .badge { opacity:.6; }
#menus-table .child-indent { display:inline-block; }
</style>
<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<meta name="csrf-token" content="<?= csrf_hash() ?>">
<script>
document.addEventListener('DOMContentLoaded', function() {
  const tbody = document.querySelector('#menus-table tbody');
  if (!tbody) return;

  let dragSrc = null;
  let dragChildren = [];
  let insertAfterRow = null; // the row after which the indicator is shown

  function getCsrfToken() {
    var meta = document.querySelector('meta[name="csrf-token"]');
    return meta ? meta.getAttribute('content') : '';
  }

  function findChildren(row) {
    const id = row.dataset.id;
    return Array.from(tbody.querySelectorAll('tr')).filter(function(r) {
      return r.dataset.parentId === id;
    });
  }

  function removeInsertIndicator() {
    tbody.querySelectorAll('.insert-indicator').forEach(function(el) { el.remove(); });
    insertAfterRow = null;
  }

  function showInsertIndicator(targetRow, position) {
    removeInsertIndicator();
    var indicator = document.createElement('tr');
    indicator.className = 'insert-indicator';
    indicator.innerHTML = '<td colspan="9"></td>';
    if (position === 'before') {
      targetRow.before(indicator);
    } else {
      targetRow.after(indicator);
    }
    insertAfterRow = position === 'after' ? targetRow : targetRow.previousElementSibling;
  }

  // --- Drag events ---

  tbody.addEventListener('dragstart', function(e) {
    const tr = e.target.closest('tr');
    if (!tr) return;
    if (tr.classList.contains('insert-indicator')) { e.preventDefault(); return; }

    dragSrc = tr;
    dragSrc.classList.add('dragging');
    dragChildren = findChildren(tr);
    dragChildren.forEach(function(child) { child.classList.add('dragging'); });

    e.dataTransfer.effectAllowed = 'move';
    // Make Firefox happy
    e.dataTransfer.setData('text/plain', tr.dataset.id);
  });

  tbody.addEventListener('dragend', function(e) {
    removeInsertIndicator();
    if (dragSrc) {
      dragSrc.classList.remove('dragging', 'table-active');
      dragChildren.forEach(function(child) { child.classList.remove('dragging', 'table-active'); });
    }
    dragSrc = null;
    dragChildren = [];
  });

  tbody.addEventListener('dragover', function(e) {
    e.preventDefault();
    const tr = e.target.closest('tr');
    if (!tr || !dragSrc || tr === dragSrc || tr.classList.contains('insert-indicator')) return;
    if (dragChildren.includes(tr)) return; // can't drop on own child

    // Determine position based on mouse Y relative to the row
    var rect = tr.getBoundingClientRect();
    var midY = rect.top + rect.height / 2;
    var position = e.clientY < midY ? 'before' : 'after';

    showInsertIndicator(tr, position);
  });

  tbody.addEventListener('dragleave', function(e) {
  });

  // --- Drop ---

  tbody.addEventListener('drop', function(e) {
    e.preventDefault();
    if (!dragSrc) return;

    // Which row was the indicator after?
    var afterRow = insertAfterRow;
    var indicator = tbody.querySelector('.insert-indicator');
    removeInsertIndicator();

    if (!afterRow && indicator) {
      // Dropped before the first row
      afterRow = null;
    } else if (!afterRow) {
      // Fallback: use the target row under cursor
      var tr = e.target.closest('tr');
      if (!tr || tr === dragSrc || dragChildren.includes(tr)) {
        dragSrc.classList.remove('dragging');
        return;
      }
      var rect = tr.getBoundingClientRect();
      var midY = rect.top + rect.height / 2;
      afterRow = e.clientY < midY ? tr.previousElementSibling : tr;
    }

    // Check if afterRow is null (insert at beginning) or a valid row
    // Move the dragged group
    var group = [dragSrc, ...dragChildren];

    // Clean up drag state
    dragSrc.classList.remove('dragging');
    dragChildren.forEach(function(child) { child.classList.remove('dragging'); });

    // If drop position is after dragSrc itself (or within dragged group), no-op
    if (afterRow && group.includes(afterRow)) {
      dragSrc = null;
      dragChildren = [];
      return;
    }

    // Move DOM nodes
    group.forEach(function(row) { row.remove(); });

    // Re-query afterRow since DOM changed
    // If afterRow was removed (it was after dragSrc which was before the drop zone), find its position
    if (afterRow === null) {
      // Insert at the beginning
      tbody.prepend(...group);
    } else {
      // Find afterRow in the current DOM
      var currentAfter = tbody.querySelector('tr[data-id="' + afterRow.dataset.id + '"]');
      if (currentAfter) {
        currentAfter.after(...group);
      } else {
        // Fallback: append to end
        tbody.append(...group);
      }
    }

    // Update parent_id of dragSrc based on position context
    var newParentId = determineNewParentId(dragSrc, tbody);
    dragSrc.dataset.parentId = newParentId;

    // Save
    saveOrder(tbody);

    dragSrc = null;
    dragChildren = [];
  });

  function determineNewParentId(row, tbody) {
    var prev = row.previousElementSibling;
    var next = row.nextElementSibling;

    // Dropped between a parent and its first child → become child of that parent
    if (prev && next && next.dataset.parentId === prev.dataset.id) {
      return prev.dataset.id;
    }

    // Otherwise: become sibling of the row before
    if (prev && !prev.classList.contains('insert-indicator')) {
      return prev.dataset.parentId;
    }
    // Or sibling of the row after
    if (next && !next.classList.contains('insert-indicator')) {
      return next.dataset.parentId;
    }
    // First and only row → top-level
    return '';
  }

  function saveOrder(tbody) {
    var rows = Array.from(tbody.querySelectorAll('tr'));
    var items = rows.map(function(row, idx) {
      return {
        id: row.dataset.id,
        parent_id: row.dataset.parentId || null,
        sort_order: idx
      };
    });

    fetch('/admin/menus/reorder', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': getCsrfToken()
      },
      body: JSON.stringify({items: items})
    })
    .then(function(r) {
      // Respons galat tetap JSON; baca isinya agar pesannya bisa ditampilkan.
      return r.json().catch(function() { return { success: false }; });
    })
    .then(function(data) {
      if (!data || !data.success) {
        alert(((data && data.error) ? data.error : 'Gagal menyimpan urutan menu.')
          + '\n\nUrutan dikembalikan ke kondisi tersimpan.');
        window.location.reload();
        return;
      }

      if (data.csrfHash) {
        syncCsrf(data.csrfHash);
      }
      rows.forEach(function(row, idx) {
        var numBadge = row.querySelector('td:nth-child(2) .badge');
        if (numBadge) numBadge.textContent = idx + 1;
        // Kolom "Urutan" adalah td ke-8 (td ke-7 = Status) — dulu tertukar
        // sehingga badge Status tertimpa angka.
        var sortBadge = row.querySelector('td:nth-child(8) .badge');
        if (sortBadge) sortBadge.textContent = idx;

        // Update visual child indent
        var isChild = row.dataset.parentId && row.dataset.parentId !== '';
        var indentSpan = row.querySelector('td:nth-child(3) .child-indent');
        if (isChild) {
          row.classList.add('table-row-child');
          if (!indentSpan) {
            var titleTd = row.querySelector('td:nth-child(3)');
            var icon = document.createElement('span');
            icon.className = 'child-indent';
            icon.innerHTML = '<?= lucide_icon('corner-up-left', 'me-2 text-body-tertiary', 14) ?>';
            titleTd.prepend(icon);
          }
        } else {
          row.classList.remove('table-row-child');
          if (indentSpan) indentSpan.remove();
        }
      });
    })
    .catch(function() {
      alert('Gagal menyimpan urutan menu. Periksa koneksi lalu coba lagi.');
      window.location.reload();
    });
  }
});
</script>
<?= $this->endSection() ?>
