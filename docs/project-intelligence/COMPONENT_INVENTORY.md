# CMS SekolahKu v3.2.0 — Component & Design Inventory

> **Document Type:** Reusable Component Catalog & Design Token Inventory  
> **Target Version:** CMS SekolahKu v3.2.0  
> **Source Files:** `public/themes/*/css/style.css`, `app/Views/themes/`, `app/Views/admin/`  
> **Analysis Date:** 2026-11-12  

---

## 1. Design Tokens & Visual Hierarchy

### A. Color Tokens by Theme
```css
/* Theme: Nusantara (Navy/Red) */
--nusantara-primary:   #17356B;  /* Deep Institutional Navy */
--nusantara-accent:    #D22630;  /* Crimson Red */
--nusantara-bg:        #FFFFFF;  /* White */
--nusantara-tint:      #F5F7FA;  /* Soft Ice Grey Tint */
--nusantara-text:      #1F2937;  /* Charcoal Gray */
--nusantara-muted:     #6B7280;  /* Slate Muted */
--nusantara-border:    #E5E7EB;  /* Light Border Gray */

/* Theme: Cakrawala (Blue/Gold) */
--cakrawala-primary:   #1550B8;  /* Royal Blue */
--cakrawala-secondary: #0C2F73;  /* Midnight Blue */
--cakrawala-accent:    #FFC60B;  /* Golden Sun */
--cakrawala-bg:        #FFFFFF;
--cakrawala-tint:      #F0F4FC;

/* Theme: Lentera (Green/Amber) */
--lentera-primary:     #15803D;  /* Forest Emerald */
--lentera-secondary:   #0E2A1D;  /* Dark Spruce */
--lentera-accent:      #FACC15;  /* Warm Amber */
--lentera-bg:          #FFFFFF;
--lentera-tint:        #F2F7F4;
```

### B. Typography Scales
- **Display & Headings:**
  - `Nusantara`: `Fraunces, Georgia, serif` (Weights: 500, 600)
  - `Cakrawala`: `Lora, Georgia, serif` (Weights: 500, 600)
  - `Lentera`: `Newsreader, Georgia, serif` (Weights: 400, 500)
- **Body & Interfaces:**
  - `Nusantara`: `Archivo, system-ui, sans-serif` (Weights: 400, 500, 600, 700)
  - `Cakrawala`: `Plus Jakarta Sans, sans-serif` (Weights: 400, 500, 600, 700, 800)
  - `Lentera`: `Inter, system-ui, sans-serif` (Weights: 400, 500, 600, 700)
- **Data & Numbers:** `IBM Plex Mono, monospace` (Weights: 400, 500, 700)

---

## 2. Reusable Public Frontend Components

### 1. Utility & Alert Bar (`.util`)
- **Structure:** Full-width top bar.
- **Sub-elements:**
  - `.util-left`: Pulsing live dot indicator (`.dot`) + capitalized tagline.
  - `.util-right`: Contact phone link with phone SVG (`#i-phone`), email link with mail SVG (`#i-mail`), and SPMB admissions flag button (`.ppdb-flag` with live CSS pulse animation).

### 2. Masthead & Brand Block (`.masthead`)
- **Structure:** Flexbox container containing brand lockup, desktop navigation, menu button, SPMB CTA, and mobile burger.
- **Scroll Behavior:** Listens to `window.scroll` (> 8px) and toggles `.scrolled` class, adding a subtle elevation drop-shadow.

### 3. Fullscreen Navigation Overlay (`.overlay#menuOverlay`)
- **Structure:** Accessible modal overlay (`role="dialog"`, `aria-hidden="true"`).
- **Sub-elements:**
  - `.ov-top`: Header with total page count indicator and close button (`#menuClose`).
  - `.ov-grid`: Two-column grid layout listing all 13 core pages + dynamic admin links with sequence numbers (`.ov-n`), title (`.ov-t`), subtitle (`.ov-d`), and directional SVG arrow.
  - `.ov-foot`: Footer contact helper with direct SPMB button.

### 4. Hero Showcase Section (`.hero`, `.hero-grid`)
- **Structure:** Asymmetrical 2-column grid layout.
- **Left Column:** Kicker badge (`.kicker`), large H1 headline, lead paragraph (`.hero-lead`), and dual button CTA group (`.hero-cta`).
- **Right Column:** Either an framed campus photo (`.frame > .frame-inner > img`) with accreditation pill (`.hero-accred`), or a data statcard (`.hero-statcard`) displaying key school metrics.

### 5. Infinite Announcement Ticker (`.ticker`)
- **Structure:** Continuous horizontal marquee animation.
- **Content Source:** Dynamically generated from latest announcements, featured news, upcoming agenda items, and newest achievements.
- **Script Handling:** JavaScript clones `track.innerHTML += track.innerHTML` to achieve a seamless, glitch-free loop.

### 6. Metric Counter Grid (`.stats`, `.stat`)
- **Structure:** Responsive 4-column counter block.
- **Sub-elements:**
  - `.tag`: Index identifier (`S.01`, `S.02`, etc.) + metric label.
  - `.val`: Large numeric figure (`1240`) + suffix (`+`, `%`).
  - `.dsc`: Description label.

### 7. Section Header Block (`.sec-head`)
- **Standard Header:** Semantic container used across all content sections.
  - `.idx`: Numbered index (`01`, `02`, `03`...).
  - `.lbl`: Section category title.
  - `.rule`: Expanding horizontal line separator (`flex: 1`).
  - `.more`: Deep-link action button pointing to dedicated subpage (`Semua berita →`).

### 8. Program Rows (`.prog`, `.prog-row`)
- **Structure:** Interactive horizontal list row.
- **Sub-elements:** Number indicator (`.p-num`), title (`.p-title`), description (`.p-desc`), action tags, and directional arrow. Hover state slides arrow rightward.

### 9. Article Cards (`.news-grid`)
- **Featured Card (`.feat`):** Large horizontal/vertical hybrid card with media cover (`.feat-media`), date badge, category tag, H3 title, and excerpt.
- **Secondary Card List (`.nl-item`):** Compact horizontal rows with thumbnail image (`.nl-thumb`) and text block. If no thumbnail is uploaded, a generated initial placeholder (`.nl-ph`) is displayed.

### 10. School Event Row (`.ag-row`)
- **Structure:** Clean calendar block displaying day of month (`.d`), month/year (`.m`), category tag, event title, excerpt, and venue/time tag (`.ag-loc`).

### 11. FAQ Accordion (`.faq`, `.faq-item`)
- **Structure:** Interactive collapsing cards with `aria-expanded` toggle states.
- **Animation:** JS sets `style.maxHeight = scrollHeight + 'px'` on open, collapsing sibling cards smoothly.

### 12. Modal Lightbox (`#lightbox`)
- **Structure:** Accessible full-screen modal viewer for photo albums.
- **Compatibility:** Dynamically clones either `<img>` nodes or vector `<svg>` artwork from the gallery grid into `#lbFig`.

### 13. Toast Notification Pill (`#toast`)
- **Structure:** Fixed floating toast pill at viewport bottom. Displays checkmark icon and status message with 3.6-second automated dismissal.

---

## 3. Reusable Administrative (CoreUI 5) Components

### 1. CRUD Master Table (`.table-crud`)
- **Features:** Responsive table with sticky uppercase headers, hover row highlighting, sortable drag handles (`.drag-handle`), status badges (`.badge.bg-success`, `.badge.bg-secondary`), and button action toolbars (`.btn-group-sm`).

### 2. Action Buttons & Controls
- Primary Action: `.btn.btn-primary.btn-sm.rounded-pill`
- Edit Trigger: `.btn.btn-light.btn-sm.border`
- Delete Trigger: `.btn.btn-outline-danger.btn-sm` (always wrapped in individual POST forms with CSRF token)
- Reorder Handle: `<span class="drag-handle text-muted cursor-move"><i class="fas fa-grip-vertical"></i></span>`

### 3. Form Layouts & Inputs
- Standard Form Group: `.mb-3` wrapper with `.form-label.fw-medium` and `.form-control.form-control-sm`.
- Rich Text Editor: `<div id="editor" style="min-height: 250px;"></div>` paired with hidden input `<textarea name="content" class="d-none"></textarea>`.
- Datalists: `<input list="categoryList">` paired with `<datalist id="categoryList">` for dynamically populating existing categories.
- File Pickers: `.form-control[type="file"]` with dimensions helper labels and existing image previews.
- Switch Checkbox: `.form-check.form-switch` for boolean flags (`show`, `is_featured`, `is_active`).

### 4. Tab Navigation System (`.nav-tabs`)
- Applied in `/admin/settings` across 10 functional panels with persistent Bootstrap tab switching (`data-coreui-toggle="tab"`).
