-- ============================================================
-- UPGRADE.sql — CMS Sekolahku v3.1.2 → v3.2.0
-- Cara pakai: phpMyAdmin → pilih database → menu Import → pilih file ini → Go
-- Aman dijalankan ulang (semua perintah bersyarat, hanya mengubah data lama).
-- ============================================================

-- 1. Menu navigasi: slug Indonesia → English (mengikuti route baru)
UPDATE menu_items SET url = 'profile'          WHERE url = 'profil';
UPDATE menu_items SET url = 'teachers'         WHERE url = 'pengajar';
UPDATE menu_items SET url = 'achievements'     WHERE url = 'prestasi';
UPDATE menu_items SET url = 'extracurriculars' WHERE url = 'ekstrakurikuler';
UPDATE menu_items SET url = 'testimonials'     WHERE url = 'testimoni';
UPDATE menu_items SET url = 'gallery'          WHERE url = 'galeri';

-- 2. Tombol hero: slug Indonesia → English
UPDATE settings SET value = 'profile' WHERE `key` = 'hero_btn_primary_url'   AND value IN ('profil', '#profile');
UPDATE settings SET value = 'contact' WHERE `key` = 'hero_btn_secondary_url' AND value IN ('contact', '#contact');

-- 3. Footer: buang awalan "© TAHUN Nama —" agar tidak dobel
--    ("© 2026 SekolahKu — All rights reserved." → "All rights reserved.")
UPDATE settings
SET value = TRIM(SUBSTRING(value, LOCATE('—', value) + 1))
WHERE `key` = 'footer_copyright' AND value LIKE '©%—%';

-- 4. Theme aktif default (multi-theme). Tidak menimpa pilihan yang sudah ada.
INSERT INTO settings (`key`, `value`) VALUES ('active_theme', 'nusantara')
ON DUPLICATE KEY UPDATE `value` = `value`;

-- 4b. Rename theme "default" → "nusantara" (v3.2.0). Hanya mengubah nilai lama.
UPDATE settings SET value = 'nusantara' WHERE `key` = 'active_theme' AND value = 'default';

-- 5. Achievements: kolom level ENUM → VARCHAR agar semua tingkat tersimpan
--    (ENUM lama menolak nilai form + tidak ada Sekolah/Internasional)
ALTER TABLE achievements MODIFY level VARCHAR(50) NULL DEFAULT NULL;

-- 6. Events: status murni mengikuti tanggal (realtime) — tidak lagi diisi manual.
--    Lewat = completed, hari ini = ongoing, nanti = upcoming.
--    Event yang dibatalkan (cancelled) tidak diubah.
UPDATE events SET status = CASE
  WHEN event_date IS NULL THEN status
  WHEN status = 'cancelled' THEN status
  WHEN event_date < CURDATE() THEN 'completed'
  WHEN event_date = CURDATE() THEN 'ongoing'
  ELSE 'upcoming'
END;

-- 7. Tahapan SPMB bawaan (10 langkah). Tidak menimpa isian yang sudah ada
--    (mis. disesuaikan per jenjang SD/SMP/SMA) — hanya mengisi bila kosong.
INSERT INTO settings (`key`, `value`) VALUES ('spmb_steps', '[{"title":"Identitas Diri","desc":"Nama lengkap, TTL, jenis kelamin"},{"title":"Alamat Tinggal","desc":"Alamat, kelurahan, kecamatan, kota, provinsi"},{"title":"Kontak","desc":"Telepon, email, kontak darurat"},{"title":"Data Ayah & Ibu","desc":"Nama, pekerjaan, pendidikan, penghasilan"},{"title":"Data Wali","desc":"Nama, alamat, hubungan, kontak"},{"title":"Asal Sekolah","desc":"Nama sekolah, alamat, NISN"},{"title":"Pilihan Program","desc":"Pilih program/jurusan yang diminati"},{"title":"Input Nilai Rapor","desc":"Isi nilai sesuai mata pelajaran"},{"title":"Upload Dokumen","desc":"Foto, ijazah, KK, dokumen pendukung"},{"title":"Finalisasi & Cetak PDF","desc":"Periksa kembali lalu cetak formulir"}]')
ON DUPLICATE KEY UPDATE `value` = `value`;

-- 8. Pesan Masuk: indeks untuk daftar admin (urut created_at + id, paginasi).
--    Aman diulang: indeks dibuat hanya bila belum ada.
SET @ix_exists := (
  SELECT COUNT(*) FROM information_schema.statistics
  WHERE table_schema = DATABASE()
    AND table_name = 'contact_messages'
    AND index_name = 'idx_messages_created'
);
SET @sql := IF(@ix_exists = 0,
  'CREATE INDEX idx_messages_created ON contact_messages(created_at, id)',
  'DO 0');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- 9. Komentar Berita: indeks untuk daftar admin (urut created_at + id, paginasi).
--    Aman diulang: indeks dibuat hanya bila belum ada.
SET @ix_exists := (
  SELECT COUNT(*) FROM information_schema.statistics
  WHERE table_schema = DATABASE()
    AND table_name = 'news_comments'
    AND index_name = 'idx_comments_created'
);
SET @sql := IF(@ix_exists = 0,
  'CREATE INDEX idx_comments_created ON news_comments(created_at, id)',
  'DO 0');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- 10. Pengguna: kolom `role` dijadikan NOT NULL (default 'editor').
--     Baris lama yang `role`-nya NULL dinormalkan lebih dulu agar tidak "hilang"
--     dari daftar Pengguna. Aman diulang: nilai default diisi hanya bila NULL.
UPDATE users SET role = 'editor' WHERE role IS NULL;
ALTER TABLE users MODIFY role ENUM('superadmin','admin','editor') NOT NULL DEFAULT 'editor';

-- 11. Pengaturan: buang setting `theme_color` yang sudah tidak dipakai
--     (digantikan pemilih `active_theme` sejak v3.2.0). Aman diulang.
DELETE FROM settings WHERE `key` = 'theme_color';

-- 12. Pengaturan: judul hero kini ditampilkan sebagai teks — tag HTML di
--     dalamnya tidak lagi dijalankan. Nilai bawaan lama yang memuat tag
--     dinormalkan; judul yang sudah diubah sendiri tidak disentuh. Aman diulang.
UPDATE settings SET value = 'Tumbuh Berilmu, Tegak Berkarakter.'
WHERE `key` = 'hero_title' AND value = 'Tumbuh Berilmu, <em>Tegak Berkarakter.</em>';
