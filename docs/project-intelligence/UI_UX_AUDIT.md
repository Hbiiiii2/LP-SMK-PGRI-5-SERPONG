# CMS SekolahKu v3.2.0 — UI/UX Audit & Interface Analysis

> **Document Type:** Interface Evaluation, Design System Inventory & Usability Audit  
> **Target Version:** CMS SekolahKu v3.2.0  
> **Source Files:** `app/Views/admin/`, `app/Views/themes/`, `public/themes/`, `public/css/`  
> **Analysis Date:** 2026-11-12  

---

## 1. Interface Architecture: Dual Ecosystem

The interface architecture consists of two separate, non-overlapping design paradigms:

1. **Administrative Control Console:** Built upon **CoreUI 5.9.1** (a Bootstrap 5 derivative) with strict operational ergonomics, data density, tabular CRUDs, and modal dialogs.
2. **Public Institutional Presentation Engine:** Built upon custom **Vanilla CSS multi-theme stylesheets** (`style.min.css`) with zero CSS utility framework dependencies (no Tailwind, no Bootstrap on public pages), driven by CSS custom properties and semantic markup.

---

## 2. Public Theme Audit & Comparison

The platform packages three distinct themes sharing identical PHP markup structures but utilizing differing design tokens:

| Design Dimension | Theme 1: `nusantara` (Default) | Theme 2: `cakrawala` | Theme 3: `lentera` |
| :--- | :--- | :--- | :--- |
| **Aesthetic Tone** | Classical Indonesian Academy | Modern Metropolitan University | Islamic / Eco-Friendly Institutional |
| **Primary Brand Color** | Deep Navy (`#17356B`) | Royal Blue (`#1550B8` / `#0C2F73`) | Forest Green (`#15803D` / `#0E2A1D`) |
| **Accent / Contrast** | Crimson Red (`#D22630`) | Sun Gold (`#FFC60B`) | Amber Gold (`#FACC15`) |
| **Display Typography** | `Fraunces` (Serif Display) | `Lora` (Academic Serif) | `Newsreader` (Editorial Serif) |
| **Body Typography** | `Archivo` (Clean Sans-Serif) | `Plus Jakarta Sans` (Geometric Sans)| `Inter` (Neutral Sans-Serif) |
| **Monospace / Numbers**| `IBM Plex Mono` | `IBM Plex Mono` | `IBM Plex Mono` |
| **Border Radius Scale** | Sharp to Compact (4px – 8px) | Rounded Modern (8px – 16px) | Organic Rounded (6px – 12px) |
| **Visual Texture** | Editorial borders, rules, tickers | Clean drop shadows, soft tints | Nature-tinted light card fills |

---

## 3. Public Navigation & Information Architecture

### A. Navigation Patterns
1. **Utility Bar (`.util`):**  
   Positioned above the masthead. Displays NPSN / institutional tagline, school phone, email, and a flashing indicator pill for admissions (`.ppdb-flag > .pulse`).
2. **Masthead & Brand (`.masthead`):**  
   Sticky header with scroll-detection shadow (`#masthead.scrolled`). Hosts the SVG logo crest, institutional name, and tagline.
3. **Primary Desktop Navigation:**  
   Renders exactly 5 primary destinations: `Beranda`, `Profil`, `Program`, `Berita`, `Kontak`.
4. **Overlay Index Navigation (`#menuOverlay`):**  
   Clicking the `Menu` button triggers a full-viewport modal overlay presenting all 13 core pages + dynamic admin-created links arranged in a numbered two-column grid (`01 Beranda`, `02 Profil`, ..., `13 Download File`, `14+ Custom`).
5. **Mobile Navigation:**  
   Mobile hamburger button (`#burger`) invokes the same `#menuOverlay` drawer rather than a standard collapse accordion.

---

## 4. Administrative Console (CoreUI 5) Audit

### A. Layout & Navigation Ergonomics
- **Sidebar (`#sidebar`):**  
  Narrow unfoldable dark sidebar (`sidebar-narrow-unfoldable`). State is persisted to browser `window.localStorage.getItem('cmsSidebarState')` ('narrow' vs 'wide').
- **Sidebar Organization:**  
  Grouped cleanly into four conceptual categories:
  - **Konten:** Berita, Guru, Program, Ekskul, Prestasi, Testimoni, Agenda, Galeri, FAQ, Download, Halaman.
  - **Layanan:** Pesan Masuk (with unread counters), Komentar (with pending counters).
  - **Sistem:** Upload Manager, Pengguna (Superadmin only), Pengaturan.
  - **Navigasi:** Menu Builder, Lihat Situs (external blank link).
- **Header:** Sticky white bar with sidebar toggle button, profile link button, and POST-based logout with CSRF protection.

### B. Dashboard Visualization & Information Density
- **Statistics Row:** 11 metric tiles (News, Events, Teachers, Programs, Unread Messages, Pending Comments, Achievements, Extracurriculars, Galleries, FAQs, Users).
- **Recent Feeds:** 4 distinct lists (Recent News, Upcoming Events, Recent Messages, Pending Comments).

### C. Data Tables & Interactive Patterns
- **Table Standard (`.table-crud`):** Clean borders, uppercase tracking on headers, hover state highlights.
- **Drag-and-Drop Reordering:** Sortable table rows with drag handles (`.drag-handle`) trigger AJAX POST requests to `/admin/{resource}/reorder` with automatic page-offset compensation.
- **In-Place Quick Updates:** On `/admin/news`, title, status, category, author, and featured state can be modified via an in-row AJAX modal without loading the full editor.
- **Batch Processing:** Implemented on `/admin/messages` (Bulk Delete) and `/admin/comments` (Bulk Approve & Bulk Delete) with "Select All" master checkboxes.

---

## 5. Critical Usability Issues & Design Inconsistencies Detected

### Issue 1: Lack of Dropdown Navigation on Public Themes
- **Finding:** While `menu_items` supports parent-child relationships (`parent_id`) and the admin interface allows nesting child items under parent menus, the public themes do not have dropdown CSS styles.
- **Evidence:** `app/Helpers/menu_helper.php` (Line 80): *"Child items (dropdowns) are not rendered — the themes have no dropdown CSS."*
- **UX Impact:** If school administrators create submenus (e.g. Profil -> Sejarah, Profil -> Visi Misi), visitors cannot access them from the header navigation.

### Issue 2: Hardcoded Theme Colors in System Error Pages
- **Finding:** The custom 404 error template `app/Views/errors/html/error_404.php` hardcodes the Nusantara Navy `#17356B` palette and Fraunces fonts.
- **UX Impact:** When a school activates the `cakrawala` (blue/gold) or `lentera` (green) theme, encountering a 404 page abruptly exposes them to the dark navy theme colors.

### Issue 3: Disorienting Desktop Overlay Menu
- **Finding:** On desktop screens, clicking `Menu` replaces the entire page with a full-screen dark overlay grid (`#menuOverlay`).
- **UX Impact:** While effective on mobile, full-screen takeover on high-resolution desktop monitors (1920x1080+) causes disorientation and cognitive load compared to traditional mega-menus or dropdowns.

### Issue 4: Inconsistent Breadcrumb Navigation
- **Finding:** Subpages like `/profile`, `/program`, `/teachers`, `/achievements`, and single post views lack structured breadcrumbs (`Beranda > Berita > Judul Artikel`).
- **UX Impact:** Visitors landing from Google Search directly onto deep articles or faculty pages have no clear navigational cue showing their current position within the site hierarchy.

### Issue 5: Quill WYSIWYG Editor Styling Artifacts
- **Finding:** In `/admin/news/form` and `/admin/pages/form`, Quill editor content is styled using the generic `quill.snow.css` CDN theme. It lacks custom typography rules that preview how the HTML will actually look when rendered inside the active frontend theme.
- **UX Impact:** Administrators see unstyled serif/sans content in the editor, but the public page renders with theme-specific font stacks, line heights, and blockquote styles.

### Issue 6: Disconnected Admissions User Journey
- **Finding:** The homepage features "10 Langkah Mudah Mendaftar" with an attractive step-flow progress indicator, but the final step directs the user either to `/contact` or an external URL (`spmb_url`).
- **UX Impact:** Parents expecting an integrated digital admissions application form find that the CMS only offers static step descriptions.
