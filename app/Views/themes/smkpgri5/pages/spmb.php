<?= view(theme_view('layouts/header')) ?>

<?php
helper('theme');
$settings        = $settings ?? [];
$siteName        = trim((string) ($site_name ?? ($settings['site_name'] ?? 'SMK PGRI 5 Serpong'))) ?: 'SMK PGRI 5 Serpong';
$programOptions  = $program_options ?? [];
$selectedProgram = (string) ($selected_program ?? '');
$lookupResult    = $lookup_result ?? null;
$lookupChecked   = ! empty($lookup_checked);
$kodeQuery       = (string) ($kode_query ?? '');
$spmbSteps       = $spmb_steps ?? [];
$spmbModel       = $spmb_model ?? new \App\Models\SpmbModel();
$errors          = session()->getFlashdata('errors');
$spmbSuccess     = (string) (session()->getFlashdata('spmb_success') ?? '');
$oldChoice1      = old('program_choice_1', $selectedProgram);
$oldChoice2      = old('program_choice_2', '');
?>

<?= view(theme_view('layouts/page_hero'), [
    'kicker'  => 'Penerimaan Peserta Didik Baru',
    'heading' => 'Pendaftaran SPMB Online',
    'lead'    => 'Orang tua / wali murid dapat mengisi biodata calon siswa dan mengunggah berkas persyaratan secara daring di ' . $siteName . '.',
    'crumbs'  => [['label' => 'SPMB Online']],
]) ?>

<?php if ($spmbSuccess !== '' || $lookupResult !== null): ?>
<section class="sec sec-subtle sec-compact" id="bukti-pendaftaran">
  <div class="wrap">
    <?php if ($spmbSuccess !== ''): ?>
      <div class="alert alert-success" role="status">
        <strong><?= lucide_icon('check-circle', '', 16) ?> Berhasil Terdaftar:</strong>
        <?= esc($spmbSuccess) ?>
      </div>
    <?php endif; ?>

    <?php if ($lookupResult !== null): ?>
      <?php
      $meta       = $spmbModel->statusMeta((string) ($lookupResult['status'] ?? 'pending'));
      $docsOk     = $spmbModel->isDocsComplete($lookupResult);
      $docCount   = $spmbModel->countUploadedDocs($lookupResult);
      $docLabels  = \App\Models\SpmbModel::DOC_FIELDS;
      ?>
      <article class="card spmb-receipt-card">
        <div class="spmb-receipt-head">
          <div>
            <div class="sec-index">Bukti &amp; Status Pendaftaran</div>
            <h2 class="spmb-receipt-no tabular-nums"><?= esc($lookupResult['registration_no'] ?? '-') ?></h2>
            <p class="form-help" style="margin: 0.25rem 0 0;">
              Didaftarkan pada <?= ! empty($lookupResult['created_at']) ? esc(date('d M Y, H:i', strtotime((string) $lookupResult['created_at']))) : '-' ?> WIB
            </p>
          </div>
          <div class="spmb-receipt-badges">
            <span class="badge <?= esc($meta['badge_public']) ?>">
              <span class="badge-dot" aria-hidden="true"></span>
              <?= esc($meta['label']) ?>
            </span>
            <span class="badge <?= $docsOk ? 'badge-green' : 'badge-amber' ?>">
              <?= lucide_icon($docsOk ? 'check-circle' : 'clock', '', 13) ?>
              <span><?= $docsOk ? 'Berkas Lengkap' : $docCount . '/4 Berkas Terunggah' ?></span>
            </span>
            <button type="button" class="btn btn-secondary btn-sm no-print" onclick="window.print()">
              <?= lucide_icon('file-text', '', 14) ?>
              <span>Cetak Bukti</span>
            </button>
          </div>
        </div>

        <div class="spmb-receipt-grid">
          <dl class="spmb-receipt-dl">
            <div><dt>Nama Calon Siswa</dt><dd><?= esc($lookupResult['full_name'] ?? '-') ?></dd></div>
            <div><dt>NISN / NIK</dt><dd class="tabular-nums"><?= esc(($lookupResult['nisn'] ?: '-') . ' / ' . ($lookupResult['nik'] ?: '-')) ?></dd></div>
            <div><dt>Asal Sekolah</dt><dd><?= esc($lookupResult['origin_school'] ?? '-') ?></dd></div>
            <div><dt>Pilihan Jurusan 1</dt><dd><strong><?= esc($lookupResult['program_choice_1'] ?? '-') ?></strong></dd></div>
            <?php if (! empty($lookupResult['program_choice_2'])): ?>
              <div><dt>Pilihan Jurusan 2</dt><dd><?= esc($lookupResult['program_choice_2']) ?></dd></div>
            <?php endif; ?>
            <div><dt>Orang Tua / Wali</dt><dd><?= esc($lookupResult['parent_name'] ?? '-') ?> (<?= esc($lookupResult['parent_relationship'] ?? 'Orang Tua') ?>)</dd></div>
            <div><dt>No. WhatsApp Wali</dt><dd class="tabular-nums"><?= esc($lookupResult['parent_phone'] ?? '-') ?></dd></div>
          </dl>

          <div class="spmb-receipt-docs">
            <div class="prog-block-label" style="margin-bottom: var(--space-3);">
              <?= lucide_icon('folder', '', 15) ?>
              <span>Status Berkas Persyaratan</span>
            </div>
            <ul class="spmb-check-list">
              <?php foreach ($docLabels as $fKey => $fLabel): ?>
                <?php $hasFile = ! empty($lookupResult[$fKey]); ?>
                <li class="<?= $hasFile ? 'is-ok' : 'is-wait' ?>">
                  <?= lucide_icon($hasFile ? 'check-circle' : 'clock', '', 15) ?>
                  <span><?= esc($fLabel) ?></span>
                  <span class="spmb-doc-state"><?= $hasFile ? 'Sudah Diunggah' : ($docsOk ? 'Terverifikasi Panitia' : 'Belum Diunggah') ?></span>
                </li>
              <?php endforeach; ?>
            </ul>

            <?php if (! empty($lookupResult['admin_notes'])): ?>
              <div class="spmb-admin-note">
                <strong>Catatan Panitia:</strong>
                <p><?= nl2br(esc($lookupResult['admin_notes'])) ?></p>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </article>
    <?php endif; ?>
  </div>
</section>
<?php endif; ?>

<section class="sec sec-white">
  <div class="wrap">
    <div class="spmb-portal-grid">
      <div class="spmb-form-main">
        <div class="sec-head" style="margin-bottom: var(--space-5);">
          <div class="sec-head-text">
            <div class="sec-index">Formulir Pendaftaran Daring</div>
            <h2 class="sec-title" style="font-size: clamp(1.375rem, 2.5vw, 1.85rem);">Isi Biodata Calon Siswa &amp; Unggah Berkas</h2>
            <p class="sec-desc">Pastikan nomor WhatsApp orang tua / wali aktif agar informasi verifikasi dan kelulusan dapat langsung dikirim oleh panitia.</p>
          </div>
        </div>

        <?php if (! empty($errors) && is_array($errors)): ?>
          <div class="alert alert-error" role="alert">
            <strong>Mohon periksa kembali isian formulir:</strong>
            <ul style="margin: 0.45rem 0 0 1.2rem; padding: 0;">
              <?php foreach ($errors as $err): ?>
                <li><?= esc($err) ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>

        <form action="<?= esc(base_url('spmb/submit')) ?>" method="post" enctype="multipart/form-data" class="spmb-form-stack">
          <?= csrf_field() ?>
          <div class="hp-field" aria-hidden="true">
            <label for="spmbWebsiteHp">Website</label>
            <input type="text" id="spmbWebsiteHp" name="website" tabindex="-1" autocomplete="off">
          </div>

          <!-- 01. Program Choice -->
          <div class="card spmb-form-section">
            <div class="spmb-section-head">
              <span class="prog-code tabular-nums">01</span>
              <div>
                <h3>Pilihan Program Keahlian / Jurusan</h3>
                <p>Pilih program keahlian utama yang diminati calon peserta didik.</p>
              </div>
            </div>

            <div class="spmb-field-grid">
              <div class="form-group">
                <label class="form-label" for="program_choice_1">Pilihan Jurusan Utama <span style="color:var(--color-error)">*</span></label>
                <select class="form-control" id="program_choice_1" name="program_choice_1" required>
                  <option value="">-- Pilih Program Keahlian --</option>
                  <?php foreach ($programOptions as $opt): ?>
                    <option value="<?= esc($opt) ?>" <?= $oldChoice1 === $opt ? 'selected' : '' ?>><?= esc($opt) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="form-group">
                <label class="form-label" for="program_choice_2">Pilihan Alternatif (Opsional)</label>
                <select class="form-control" id="program_choice_2" name="program_choice_2">
                  <option value="">-- Tidak Ada / Opsional --</option>
                  <?php foreach ($programOptions as $opt): ?>
                    <option value="<?= esc($opt) ?>" <?= $oldChoice2 === $opt ? 'selected' : '' ?>><?= esc($opt) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>

          <!-- 02. Student Data -->
          <div class="card spmb-form-section">
            <div class="spmb-section-head">
              <span class="prog-code tabular-nums">02</span>
              <div>
                <h3>Biodata Calon Peserta Didik</h3>
                <p>Sesuai dengan Kartu Keluarga (KK) dan Ijazah/Rapor jenjang sebelumnya.</p>
              </div>
            </div>

            <div class="form-group">
              <label class="form-label" for="full_name">Nama Lengkap Calon Siswa <span style="color:var(--color-error)">*</span></label>
              <input class="form-control" type="text" id="full_name" name="full_name" value="<?= esc(old('full_name')) ?>" required placeholder="Nama lengkap sesuai akta / ijazah">
            </div>

            <div class="spmb-field-grid">
              <div class="form-group">
                <label class="form-label" for="nisn">NISN (Nomor Induk Siswa Nasional)</label>
                <input class="form-control tabular-nums" type="text" id="nisn" name="nisn" value="<?= esc(old('nisn')) ?>" placeholder="10 digit NISN" maxlength="30">
              </div>
              <div class="form-group">
                <label class="form-label" for="nik">NIK (Sesuai Kartu Keluarga)</label>
                <input class="form-control tabular-nums" type="text" id="nik" name="nik" value="<?= esc(old('nik')) ?>" placeholder="16 digit NIK" maxlength="30">
              </div>
            </div>

            <div class="spmb-field-grid spmb-field-grid-3">
              <div class="form-group">
                <label class="form-label" for="gender">Jenis Kelamin <span style="color:var(--color-error)">*</span></label>
                <select class="form-control" id="gender" name="gender" required>
                  <option value="L" <?= old('gender', 'L') === 'L' ? 'selected' : '' ?>>Laki-laki</option>
                  <option value="P" <?= old('gender') === 'P' ? 'selected' : '' ?>>Perempuan</option>
                </select>
              </div>
              <div class="form-group">
                <label class="form-label" for="birth_place">Tempat Lahir <span style="color:var(--color-error)">*</span></label>
                <input class="form-control" type="text" id="birth_place" name="birth_place" value="<?= esc(old('birth_place')) ?>" required placeholder="Kota / Kabupaten">
              </div>
              <div class="form-group">
                <label class="form-label" for="birth_date">Tanggal Lahir <span style="color:var(--color-error)">*</span></label>
                <input class="form-control" type="date" id="birth_date" name="birth_date" value="<?= esc(old('birth_date')) ?>" required>
              </div>
            </div>

            <div class="spmb-field-grid spmb-field-grid-3">
              <div class="form-group">
                <label class="form-label" for="religion">Agama</label>
                <select class="form-control" id="religion" name="religion">
                  <?php $oldRel = old('religion', 'Islam'); ?>
                  <?php foreach (['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'] as $rel): ?>
                    <option value="<?= esc($rel) ?>" <?= $oldRel === $rel ? 'selected' : '' ?>><?= esc($rel) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="form-group">
                <label class="form-label" for="origin_school">Asal Sekolah (SMP/MTs) <span style="color:var(--color-error)">*</span></label>
                <input class="form-control" type="text" id="origin_school" name="origin_school" value="<?= esc(old('origin_school')) ?>" required placeholder="Contoh: SMP Negeri 1 Serpong">
              </div>
              <div class="form-group">
                <label class="form-label" for="graduation_year">Tahun Lulus</label>
                <input class="form-control tabular-nums" type="text" id="graduation_year" name="graduation_year" value="<?= esc(old('graduation_year', date('Y'))) ?>" placeholder="<?= esc(date('Y')) ?>" maxlength="10">
              </div>
            </div>

            <div class="spmb-field-grid">
              <div class="form-group">
                <label class="form-label" for="student_phone">No. HP / WhatsApp Siswa (Opsional)</label>
                <input class="form-control tabular-nums" type="tel" id="student_phone" name="student_phone" value="<?= esc(old('student_phone')) ?>" placeholder="08xxxxxxxxxx">
              </div>
              <div class="form-group" style="margin-bottom:0"></div>
            </div>

            <div class="form-group" style="margin-bottom:0">
              <label class="form-label" for="address">Alamat Lengkap Tempat Tinggal <span style="color:var(--color-error)">*</span></label>
              <textarea class="form-control" id="address" name="address" rows="3" style="min-height:92px" required placeholder="Jalan, RT/RW, Kelurahan, Kecamatan, Kota/Kabupaten"><?= esc(old('address')) ?></textarea>
            </div>
          </div>

          <!-- 03. Parent / Guardian -->
          <div class="card spmb-form-section">
            <div class="spmb-section-head">
              <span class="prog-code tabular-nums">03</span>
              <div>
                <h3>Data Orang Tua / Wali Murid</h3>
                <p>Nomor WhatsApp wali murid akan digunakan panitia untuk mengirim pemberitahuan resmi.</p>
              </div>
            </div>

            <div class="spmb-field-grid">
              <div class="form-group">
                <label class="form-label" for="parent_name">Nama Orang Tua / Wali <span style="color:var(--color-error)">*</span></label>
                <input class="form-control" type="text" id="parent_name" name="parent_name" value="<?= esc(old('parent_name')) ?>" required placeholder="Nama lengkap ayah/ibu/wali">
              </div>
              <div class="form-group">
                <label class="form-label" for="parent_relationship">Hubungan Keluarga</label>
                <?php $oldRelship = old('parent_relationship', 'Ayah'); ?>
                <select class="form-control" id="parent_relationship" name="parent_relationship">
                  <?php foreach (['Ayah', 'Ibu', 'Wali'] as $rs): ?>
                    <option value="<?= esc($rs) ?>" <?= $oldRelship === $rs ? 'selected' : '' ?>><?= esc($rs) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

            <div class="spmb-field-grid spmb-field-grid-3">
              <div class="form-group">
                <label class="form-label" for="parent_phone">No. WhatsApp Aktif Wali <span style="color:var(--color-error)">*</span></label>
                <input class="form-control tabular-nums" type="tel" id="parent_phone" name="parent_phone" value="<?= esc(old('parent_phone')) ?>" required placeholder="0812xxxxxxxx">
                <span class="form-help">Notifikasi status &amp; jadwal dikirim ke nomor ini.</span>
              </div>
              <div class="form-group">
                <label class="form-label" for="parent_email">Email Orang Tua (Opsional)</label>
                <input class="form-control" type="email" id="parent_email" name="parent_email" value="<?= esc(old('parent_email')) ?>" placeholder="nama@email.com">
              </div>
              <div class="form-group">
                <label class="form-label" for="parent_occupation">Pekerjaan Orang Tua</label>
                <input class="form-control" type="text" id="parent_occupation" name="parent_occupation" value="<?= esc(old('parent_occupation')) ?>" placeholder="Wiraswasta / Karyawan / PNS">
              </div>
            </div>
          </div>

          <!-- 04. Required Documents -->
          <div class="card spmb-form-section">
            <div class="spmb-section-head">
              <span class="prog-code tabular-nums">04</span>
              <div>
                <h3>Unggah Berkas Persyaratan</h3>
                <p>Format PDF, JPG, PNG, atau WebP (maksimal 5 MB per berkas). Boleh dilengkapi bertahap atau langsung seluruhnya.</p>
              </div>
            </div>

            <div class="spmb-doc-grid">
              <div class="spmb-doc-slot">
                <label class="form-label" for="doc_kk">1. Kartu Keluarga (KK)</label>
                <input class="form-control" type="file" id="doc_kk" name="doc_kk" accept=".pdf,.jpg,.jpeg,.png,.webp">
                <span class="form-help">Scan / foto KK asli yang terbaca jelas.</span>
              </div>
              <div class="spmb-doc-slot">
                <label class="form-label" for="doc_akta">2. Akta Kelahiran</label>
                <input class="form-control" type="file" id="doc_akta" name="doc_akta" accept=".pdf,.jpg,.jpeg,.png,.webp">
                <span class="form-help">Scan / foto Akta Kelahiran calon siswa.</span>
              </div>
              <div class="spmb-doc-slot">
                <label class="form-label" for="doc_ijazah">3. Ijazah / SKL / Rapor SMP</label>
                <input class="form-control" type="file" id="doc_ijazah" name="doc_ijazah" accept=".pdf,.jpg,.jpeg,.png,.webp">
                <span class="form-help">Ijazah SMP, Surat Keterangan Lulus, atau Rapor semester terakhir.</span>
              </div>
              <div class="spmb-doc-slot">
                <label class="form-label" for="doc_foto">4. Pas Foto Siswa 3x4</label>
                <input class="form-control" type="file" id="doc_foto" name="doc_foto" accept=".jpg,.jpeg,.png,.webp">
                <span class="form-help">Pas foto terbaru berlatar merah/biru yang rapi.</span>
              </div>
            </div>
          </div>

          <div class="spmb-submit-bar">
            <button type="submit" class="btn btn-primary">
              <?= lucide_icon('send', '', 16) ?>
              <span>Kirim Pendaftaran SPMB Online</span>
            </button>
            <span class="form-help">Dengan menekan tombol kirim, data calon peserta didik akan langsung masuk ke sistem panitia SPMB <?= esc($siteName) ?>.</span>
          </div>
        </form>
      </div>

      <aside class="spmb-portal-aside">
        <!-- Lookup Box -->
        <div class="card" id="cek-status">
          <div class="prog-block-label" style="margin-bottom: var(--space-2);">
            <?= lucide_icon('search', '', 15) ?>
            <span>Cek Status Pendaftaran</span>
          </div>
          <p class="form-help" style="margin-bottom: var(--space-3);">Sudah mendaftar? Masukkan Nomor Pendaftaran untuk memeriksa kelengkapan berkas dan status verifikasi panitia.</p>
          <form action="<?= esc(base_url('spmb')) ?>#bukti-pendaftaran" method="get">
            <div class="form-group" style="margin-bottom: var(--space-3);">
              <label class="form-label" for="kodeInput">Nomor Pendaftaran</label>
              <input class="form-control tabular-nums" type="text" id="kodeInput" name="kode" value="<?= esc($kodeQuery) ?>" placeholder="Contoh: SPMB-<?= esc(date('Y')) ?>-0001" required>
            </div>
            <button type="submit" class="btn btn-secondary" style="width: 100%; justify-content: center;">
              <?= lucide_icon('search', '', 15) ?>
              <span>Periksa Status</span>
            </button>
          </form>

          <?php if ($lookupChecked && $lookupResult === null): ?>
            <div class="alert alert-error" style="margin-top: var(--space-3); margin-bottom: 0;">
              Nomor pendaftaran <strong><?= esc($kodeQuery) ?></strong> tidak ditemukan. Periksa kembali penulisan kode.
            </div>
          <?php endif; ?>
        </div>

        <!-- Document Checklist -->
        <div class="card">
          <div class="prog-block-label" style="margin-bottom: var(--space-3);">
            <?= lucide_icon('file-text', '', 15) ?>
            <span>Checklist Persyaratan</span>
          </div>
          <ul class="spmb-check-list">
            <li class="is-info"><?= lucide_icon('check', '', 14) ?><span>Scan / Foto Kartu Keluarga (KK)</span></li>
            <li class="is-info"><?= lucide_icon('check', '', 14) ?><span>Scan / Foto Akta Kelahiran</span></li>
            <li class="is-info"><?= lucide_icon('check', '', 14) ?><span>Ijazah SMP / SKL / Rapor Terakhir</span></li>
            <li class="is-info"><?= lucide_icon('check', '', 14) ?><span>Pas Foto Berwarna Terbaru</span></li>
            <li class="is-info"><?= lucide_icon('check', '', 14) ?><span>Nomor WhatsApp Aktif Orang Tua/Wali</span></li>
          </ul>
        </div>

        <!-- Verification & WhatsApp Info -->
        <div class="card" style="background-color: var(--theme_color_surface_accent_soft); border-color: var(--color-primary-200);">
          <div class="prog-block-label" style="margin-bottom: var(--space-2);">
            <?= lucide_icon('message-circle', '', 15) ?>
            <span>Notifikasi Resmi WhatsApp</span>
          </div>
          <p class="step-desc" style="margin: 0; color: var(--theme_color_text_secondary);">
            Setelah formulir dikirim dan persyaratan lengkap, panitia <?= esc($siteName) ?> akan memverifikasi data dan mengirimkan konfirmasi langsung ke WhatsApp orang tua / wali murid.
          </p>
        </div>

        <?php if (! empty($spmbSteps)): ?>
          <div class="card">
            <div class="prog-block-label" style="margin-bottom: var(--space-3);">
              <?= lucide_icon('list-ordered', '', 15) ?>
              <span>Alur Penerimaan</span>
            </div>
            <ol class="spmb-mini-steps">
              <?php foreach ($spmbSteps as $step): ?>
                <li><?= esc($step['title'] ?? '') ?></li>
              <?php endforeach; ?>
            </ol>
          </div>
        <?php endif; ?>
      </aside>
    </div>
  </div>
</section>

<?= view(theme_view('layouts/footer')) ?>
