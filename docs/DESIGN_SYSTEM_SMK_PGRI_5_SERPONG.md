# Design System & Brand Identity — SMK PGRI 5 Serpong (`smkpgri5` v2.0.0)

Sistem desain antarmuka publik CMS Sekolah **SMK PGRI 5 Serpong** versi 2.0.0 menggunakan bahasa visual **Design System Inspired by Airtable** (`od://design-systems/airtable/DESIGN.md`) yang disesuaikan untuk institusi pendidikan kejuruan: terang, terstruktur, presisi editorial, berbatas garis halus (*hairline borders*), dan bebas elemen dekoratif generik.

---

## 1. Sumber Desain (*Single Source of Truth*)

| Berkas | Peran |
| :--- | :--- |
| [tokens.css](file:///c:/laragon/www/cms-sekolahku-v3.2.0-latest/public/themes/smkpgri5/css/tokens.css) | Token warna, tipografi, spasi, radius, dan elevasi (`--theme_*`, skala warna, serta alias kompatibilitas `--color-*` untuk admin). |
| [style.css](file:///c:/laragon/www/cms-sekolahku-v3.2.0-latest/public/themes/smkpgri5/css/style.css) | Stylesheet utama komponen publik, tata letak editorial, ledger, matriks program, dan responsivitas *mobile-first*. |
| [style.min.css](file:///c:/laragon/www/cms-sekolahku-v3.2.0-latest/public/themes/smkpgri5/css/style.min.css) | Hasil minifikasi CSS produksi via `MatthiasMullie\Minify\CSS`. |
| [script.js](file:///c:/laragon/www/cms-sekolahku-v3.2.0-latest/public/themes/smkpgri5/js/script.js) | Interaktivitas publik (navigasi mobile, dropdown aksesibel, pencarian unduhan, formulir kontak AJAX, lightbox, toast). |
| [theme.json](file:///c:/laragon/www/cms-sekolahku-v3.2.0-latest/app/Views/themes/smkpgri5/theme.json) | Metadata tema aktif `smkpgri5`. |

---

## 2. Sistem Warna (*Color Tokens*)

### A. Warna Utama (*Airtable Blue Identity*)
- `--theme_color_primary_default`: `#1b61c9` (Royal Blue — tombol primer, tautan aktif, aksen interaksi terfokus)
- `--theme_color_primary_emphasis`: `#254fad` (Emphasis Blue — state hover tombol & tautan aktif)
- `--theme_color_primary_deep`: `#0f306a` (Deep Cobalt — penekanan tinggi dan kedalaman bayangan biru)
- `--theme_color_primary_soft`: `#eff6ff` & `--theme_color_primary_tint`: `#f0f6ff` (Latar lembut terarah untuk sorotan ringan)
- Skala lengkap: `--color-primary-950` (`#0a1936`) hingga `--color-primary-50` (`#eff6ff`) dan aksen pendukung `--color-accent-700`–`50` serta `--color-warm-600`–`50`.

### B. Permukaan, Tinta & Batas (*Surface & Ink Tokens*)
- `--theme_color_surface_canvas`: `#ffffff` (Kanvas utama & kartu bersih)
- `--theme_color_surface_subtle`: `#f8fafc` (Latar seksi berirama halus & halaman arsip)
- `--theme_color_surface_muted`: `#f1f5f9` (Permukaan kontras lembut)
- `--theme_color_border_default`: `#e0e2e6` (Garis struktural *hairline*)
- `--theme_color_border_strong`: `#cbd5e1` (Batas kontrol formulir & tabel)
- `--theme_color_text_default`: `#181d26` (Tinta Deep Navy untuk judul & teks utama)
- `--theme_color_text_secondary`: `#333333` (Teks isi sekunder)
- `--theme_color_text_weak`: `rgba(4, 14, 32, 0.69)` (Teks keterangan & metadata)
- `--color-neutral-950`: `#181d26` (Latar *footer* institusional gelap dengan teks `--color-on-dark-muted` / `--color-on-dark-subtle`)

### C. Warna Semantik (*Semantic Tokens*)
- **Success**: `--color-success` (`#006400`), `--color-success-bg` (`#ecfdf5`), `--color-success-border` (`#a7f3d0`)
- **Warning**: `--color-warning` (`#b45309`), `--color-warning-bg` (`#fffbeb`), `--color-warning-border` (`#fde68a`)
- **Error**: `--color-error` (`#c81e1e`), `--color-error-bg` (`#fef2f2`), `--color-error-border` (`#fecaca`)
- **Info**: `--color-info` (`#1b61c9`), `--color-info-bg` (`#eff6ff`), `--color-info-border` (`#bfdbfe`)

### D. Kompatibilitas Panel Admin (`admin-theme.css`)
`app/Views/admin/layout.php` memuat `tokens.css` sebelum `public/assets/css/admin-theme.css`. Oleh karena itu, alias warisan `--color-primary`, `--color-primary-dark`, `--color-primary-light`, `--color-primary-border`, `--color-secondary*`, `--color-accent-red*`, `--color-accent-yellow*`, `--color-text-*`, `--color-background`, `--color-surface`, dan `--color-border*` tetap dipertahankan di `tokens.css`.

---

## 3. Tipografi (*Typography System*)

- `--font-sans`: `"Plus Jakarta Sans", "Inter", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif`
- `--font-mono`: `"IBM Plex Mono", ui-monospace, SFMono-Regular, Menlo, monospace` (digunakan untuk *kicker*, kode program/indeks `01..06`, metadata tanggal, dan statistik numerik)
- Skala ukuran: `--text-xs` (`0.75rem`), `--text-sm` (`0.875rem`), `--text-base` (`1rem`), `--text-lg` (`1.125rem`), `--text-xl` (`1.25rem`), `--text-2xl` (`1.5rem`), `--text-3xl` (`2rem`), `--text-4xl` (`2.75rem`)
- Spasi huruf (*tracking*): `--tracking-tight` (`-0.025em`), `--tracking-snug` (`-0.015em`), `--tracking-body` (`0.08px`), `--tracking-ui` (`0.12px`), `--tracking-mono` (`0.06em`)
- *Line-height*: `--leading-tight` (`1.14`), `--leading-snug` (`1.3`), `--leading-normal` (`1.62`)
- Angka sejajar kolom tabel/ledger memakai `font-variant-numeric: tabular-nums`.

---

## 4. Geometri, Elevasi & Transisi

- **Radius (*Airtable Geometry Scale*)**: `--radius-xs` (`6px`), `--radius-sm` (`8px`), `--radius-md` (`12px` — tombol & input), `--radius-lg` (`16px` — kartu & panel), `--radius-xl` (`24px` — wadah editorial unggulan), `--radius-pill` (`9999px`)
- **Elevasi (*Blue-Tinted Layered Shadows*)**: `--shadow-btn-primary`, `--shadow-btn-secondary`, `--shadow-xs`, `--shadow-sm`, `--shadow-md`, `--shadow-lg`, `--shadow-xl`, `--shadow-header`, dan `--focus-ring` (`0 0 0 3px rgba(27, 97, 201, 0.22)`)
- **Transisi Fungsional**: `--transition-fast` (`150ms cubic-bezier(0.2, 0.8, 0.2, 1)`) dan `--transition-base` (`220ms cubic-bezier(0.2, 0.8, 0.2, 1)`)

---

## 5. Prinsip Komposisi Anti-AI-Slop

1. **Ritme Seksi Bervariasi**: Seksi beranda dan halaman internal memadukan *Split Editorial Hero + Ledger*, *Asymmetric Principal Spotlight + Numbered Pillar Ledger*, *Architectural Metrics Bar*, *2-Column Program Matrix*, *Featured News + Index Ledger*, *Tabular Achievement Ledger*, serta *SPMB Roadmap*.
2. **Permukaan Tenang & Tanpa Gradien Dekoratif**: Pergantian kanvas `#ffffff` dan `#f8fafc` dengan garis pemisah `#e0e2e6` menggantikan blok warna penuh berulang, animasi dekoratif acak, maupun *eyebrow pill spam*.
3. **Konsistensi Ikonografi**: Seluruh ikon antarmuka publik merujuk ke sprite Lucide SVG lokal melalui helper `lucide_icon()` di `app/Helpers/lucide_helper.php`.
4. **Ketahanan Responsif**: Tata letak menggunakan grid *mobile-first* dengan `minmax(0, 1fr)` dan pembatas horizontal aman agar bebas *horizontal overflow* di desktop, tablet, dan mobile.

---

## 6. Aturan Penggunaan Logo Resmi

- Logo resmi sekolah (`public/media_library/images/smk-pgri-5-logo.png`, `public/themes/smkpgri5/img/logo-smk-pgri-5-serpong.png`, serta `public/uploads/about/...`) digunakan tanpa modifikasi warna, tanpa pemotongan (*crop*) melingkar, dan mempertahankan rasio asli.
- Selalu gunakan `object-fit: contain` dan wadah netral putih (`#ffffff`), termasuk pada `.foot-logo-badge` di *footer* gelap `#181d26`, header publik, dan panel Admin.
