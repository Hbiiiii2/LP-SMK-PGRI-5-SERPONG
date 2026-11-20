# CMS SekolahKu v3.2.0 — Feature Inventory

> **Document Type:** Comprehensive Feature Specification & Status Matrix  
> **Target Version:** CMS SekolahKu v3.2.0  
> **Source Directory:** `c:\laragon\www\cms-sekolahku-v3.2.0-latest`  
> **Analysis Date:** 2026-11-12  

---

## 1. Feature Summary Overview

The CMS SekolahKu platform encompasses **22 distinct functional features** spanning the public presentation layer and the administrative management console. Every feature has been reverse-engineered from source code and verified against database schemas.

---

## 2. Granular Feature Inventory

### Feature 1: SPMB (Seleksi Penerimaan Murid Baru) Admissions Guidance
- **Purpose:** Guide prospective students and parents through the admission requirements and workflow for enrolling in the institution.
- **Related Pages:** Public Homepage (`/` under section `#spmb`), Public Contact (`/contact`), Admin Settings (`/admin/settings` under tab "Tahapan SPMB").
- **Related Components:** `.sp-flow`, `.sp-track`, `ol.steps`, `li[data-n]`, `.ppdb-flag` badge in header, SPMB preset action buttons in admin.
- **Backend Implementation:**
  - Controllers: `Home::index()`, `Admin\Settings::save()`.
  - Helpers: `default_spmb_steps()`, `spmb_step_presets()`, `resolve_spmb_steps()`.
  - Models: `SettingModel` (keys: `spmb_steps`, `spmb_url`, `hero_btn_primary_url`).
- **Database Dependencies:** `settings` table (`key = 'spmb_steps'` storing JSON array with up to 12 step objects `{title, desc}`).
- **User Interactions:**
  - Visitor views progress-bar steps from Step 01 to Step 10 (or custom configured steps). Clicking CTA directs to registration portal (`spmb_url`) or `/contact`.
  - Admin can add, edit, delete, or rearrange steps, or apply one-click presets for **SD** (Tematik), **SMP** (Program Unggulan), or **SMA** (Peminatan).
- **Implementation Status:** **Complete.** (Presentation and guidance flow is fully dynamic; no online form submission or student application table is hosted internally).

---

### Feature 2: Articles & School News Publishing (`News`)
- **Purpose:** Publish school announcements, press releases, competition updates, and student journalism.
- **Related Pages:**
  - Public: `/news` (archive listing), `/news/search` (AJAX search), `/news/(:any)` (article detail).
  - Admin: `/admin/news` (data table), `/admin/news/new` (create), `/admin/news/(:num)/edit` (update).
- **Related Components:** Article cards (`.feat`, `.nl-item`), category pills, monthly archive sidebar, tag badges, Quill 2.0 WYSIWYG editor, in-place Quick-Update modal in admin table, auto-resizing GD image pipeline.
- **Backend Implementation:**
  - Controllers: `App\Controllers\News`, `App\Controllers\Admin\News`.
  - Models: `App\Models\NewsModel`.
- **Database Dependencies:** `news` table (fields: `id`, `category`, `tags`, `title`, `slug`, `excerpt`, `content`, `image`, `author`, `published_at`, `status`, `is_featured`, `meta_title`, `meta_description`, `view_count`).
- **User Interactions:**
  - Visitor browses paginated articles (6/page), searches keywords, filters by category or month, reads article detail, reads related stories, navigates to next/previous posts.
  - Admin creates articles with Quill editor, tags, categories, featured flags, and image upload. Admin can also perform AJAX Quick-Updates (edit title, status, category, author, featured directly from the listing table).
- **Implementation Status:** **Complete.** Includes JSON-LD structured data (`NewsArticle`), view increment counter, and automated slug generation with collision detection.

---

### Feature 3: News Article Comments & Moderation (`Comments`)
- **Purpose:** Enable public feedback and reader engagement on news posts with strict administrator moderation.
- **Related Pages:**
  - Public: `/news/(:any)` (comment list & submission form).
  - Admin: `/admin/comments` (moderation console).
- **Related Components:** Nested comment list, reply trigger (`.c-reply`), character counter (`#cLeft`), character limit validation (2000 chars), moderation status filter tabs (All, Pending, Approved), bulk action checkboxes.
- **Backend Implementation:**
  - Controllers: `News::comment()`, `Admin\Comments` (`index`, `approve`, `reject`, `delete`, `bulk`).
  - Models: `NewsCommentModel`.
- **Database Dependencies:** `news_comments` table (`id`, `news_id`, `parent_id`, `name`, `email`, `avatar`, `comment`, `is_approved`, `created_at`, `updated_at`). Foreign keys to `news.id` and `news_comments.id` with `ON DELETE CASCADE`.
- **User Interactions:**
  - Visitor submits name, email, and comment. Honeypot check and IP throttling are applied. Submitted comment remains pending (`is_approved = 0`).
  - Admin filters comments by status, approves pending comments to make them public, rejects/deletes spam, or executes bulk approvals/deletions.
- **Implementation Status:** **Complete.** Defense against fake parent IDs, cross-article replies, and stored XSS fully implemented.

---

### Feature 4: Visitor Contact & Inquiries (`Contact` & `Messages`)
- **Purpose:** Receive prospective student inquiries, parental feedback, and general public messages.
- **Related Pages:**
  - Public: `/contact` (contact info, hours, form).
  - Admin: `/admin/messages` (inbox), `/admin/messages/(:num)` (message detail).
- **Related Components:** Contact form (`#contactForm`), toast alert notification, status badges (unread/read), mark-as-read toggles, bulk delete modal.
- **Backend Implementation:**
  - Controllers: `App\Controllers\Contact`, `App\Controllers\Admin\Messages`.
  - Models: `ContactMessageModel`.
- **Database Dependencies:** `contact_messages` table (`id`, `name`, `email`, `subject`, `message`, `is_read`, `created_at`, `updated_at`).
- **User Interactions:**
  - Visitor fills out name, email, subject, message (max 5000 characters). Submits via AJAX with toast notification.
  - Admin views unread count badges on Dashboard and Sidebar, reads messages (which automatically updates `is_read = 1`), toggles read/unread status, and performs bulk deletions.
- **Implementation Status:** **Complete.** Protected by session limit (1/30s), IP limit (5/10min), and honeypot.

---

### Feature 5: School Profile, History & Leadership (`Profile`)
- **Purpose:** Present the school's heritage, foundation history, accreditation, vision, mission, and principal's official greeting.
- **Related Pages:** Public `/profile`, Admin Settings `/admin/settings` (tabs: "Tentang Kami" & "Kepala Sekolah").
- **Related Components:** Principal quote card (`.plate`, `.plate-photo`, `.plate-name`), vision-mission cards, accreditation badge, culture/highlight bullet points.
- **Backend Implementation:**
  - Controllers: `Site::profile()`, `Home::index()`, `Admin\Settings::save()`.
  - Models: `SettingModel` (keys: `principal`, `about`, `site_name`, `site_tagline`).
- **Database Dependencies:** `settings` table (`key = 'principal'` and `key = 'about'` storing JSON serialized objects).
- **User Interactions:**
  - Public reads leadership speech, credentials, and organizational values.
  - Admin edits principal biography, uploads portrait photo, updates school history text, vision, mission, and highlights list.
- **Implementation Status:** **Complete.**

---

### Feature 6: Academic Curriculum Pathways (`Programs`)
- **Purpose:** Showcase academic specializations (e.g., MIPA, IPS, Bahasa, Kejuruan) and accelerated study programs.
- **Related Pages:** Public `/program`, Admin `/admin/programs`.
- **Related Components:** Program rows (`.prog-row`), sequence numbers (`.p-num`), link buttons, admin drag-and-drop sortable rows, active/inactive (`show`) toggle.
- **Backend Implementation:**
  - Controllers: `Site::program()`, `Admin\Programs`.
  - Helpers: `program_link()`.
  - Models: `ProgramModel`.
- **Database Dependencies:** `programs` table (`id`, `title`, `description`, `icon`, `link_url`, `link_text`, `sort_order`, `show`, `created_at`, `updated_at`).
- **User Interactions:**
  - Public views program cards with details and click-through links.
  - Admin creates programs, assigns custom URLs or default SPMB anchors, toggles visibility, and drags rows to reorder.
- **Implementation Status:** **Complete.** (Note: `programs.icon` field exists in the DB but is not currently rendered in theme templates).

---

### Feature 7: Extracurricular Activities (`Extracurriculars`)
- **Purpose:** Display student clubs and non-academic development programs (Robotics, Choir, Sports, Arts, Scouting, Journalism).
- **Related Pages:** Public `/extracurriculars`, Admin `/admin/extracurriculars`.
- **Related Components:** Activity cards, colored icon badges (`.cat > i`), FontAwesome icon selection, HEX color picker (`icon_color`).
- **Backend Implementation:**
  - Controllers: `Site::extracurriculars()`, `Admin\Extracurriculars`.
  - Models: `ExtracurricularModel`.
- **Database Dependencies:** `extracurriculars` table (`id`, `title`, `description`, `icon`, `icon_color`, `sort_order`, `show`, `created_at`, `updated_at`).
- **User Interactions:**
  - Public views extracurricular list with matching icons and custom brand colors.
  - Admin adds activities, inputs FontAwesome icon classes (e.g., `fas fa-microchip`), selects HEX colors (e.g., `#14b8a6`), and reorders list.
- **Implementation Status:** **Complete.**

---

### Feature 8: Faculty & Educator Directory (`Teachers`)
- **Purpose:** Display qualified teaching staff, subject specialties, academic backgrounds, and social profiles.
- **Related Pages:** Public `/teachers`, Admin `/admin/teachers`.
- **Related Components:** Educator profile cards (`.t-card`), circular portrait photos, degree badges, experience tags, social icons (Facebook, Instagram, LinkedIn).
- **Backend Implementation:**
  - Controllers: `Site::teachers()`, `Admin\Teachers`.
  - Models: `TeacherModel`.
- **Database Dependencies:** `teachers` table (`id`, `name`, `photo`, `role`, `experience`, `education`, `social_facebook`, `social_instagram`, `social_linkedin`, `sort_order`, `show`, `created_at`, `updated_at`).
- **User Interactions:**
  - Public inspects teacher credentials and leadership team.
  - Admin uploads photos, inputs educational backgrounds, updates positions, and drags rows to reorder.
- **Implementation Status:** **Complete.**

---

### Feature 9: Student Championships & Awards (`Achievements`)
- **Purpose:** Celebrate student competitive excellence across academic, sports, and artistic arenas.
- **Related Pages:** Public `/achievements`, Admin `/admin/achievements`.
- **Related Components:** Achievement cards, medal tier badges (Gold/Emas, Silver/Perak, Bronze/Perunggu), level scope badges (Nasional, Provinsi, Kota), student portrait, year grouping.
- **Backend Implementation:**
  - Controllers: `Site::achievements()`, `Admin\Achievements`.
  - Models: `AchievementModel`.
- **Database Dependencies:** `achievements` table (`id`, `student_name`, `photo`, `class_name`, `achievement`, `level`, `year`, `medal`, `sort_order`, `show`, `created_at`, `updated_at`).
- **User Interactions:**
  - Public explores student honors ordered chronologically and by medal tiers.
  - Admin adds award records, assigns student name, class, competition title, level, year, and medal tier.
- **Implementation Status:** **Complete.** (Photo field stores external URL or uploaded asset path; year is strictly validated).

---

### Feature 10: Parent, Student & Alumni Quotes (`Testimonials`)
- **Purpose:** Build institutional trust through authentic testimonials from parents, university-admitted alumni, and active students.
- **Related Pages:** Public `/testimonials`, Public Homepage (Quote banner), Admin `/admin/testimonials`.
- **Related Components:** Large blockquote (`.quote`, `blockquote`, `.q-mark`), avatar image (`.quote-ava`, `.t-ava`), author citation (`<cite>`).
- **Backend Implementation:**
  - Controllers: `Site::testimonials()`, `Home::index()`, `Admin\Testimonials`.
  - Models: `TestimonialModel`.
- **Database Dependencies:** `testimonials` table (`id`, `name`, `photo`, `role`, `quote`, `sort_order`, `show`, `created_at`, `updated_at`).
- **User Interactions:**
  - Public reads quotes; the primary quote is highlighted on the homepage.
  - Admin manages quotes, uploads avatars or provides photo URLs, and reorders priority.
- **Implementation Status:** **Complete.**

---

### Feature 11: School Calendar & Event Agenda (`Events`)
- **Purpose:** Inform the school community of upcoming holidays, examination periods, open houses, competitions, and arts festivals.
- **Related Pages:** Public `/agenda`, Public Homepage (Agenda preview), Admin `/admin/events`.
- **Related Components:** Calendar date blocks (`.ag-date`, `.d`, `.m`), event descriptions, location badges (`.ag-loc`), status badges (`upcoming`, `ongoing`, `completed`, `cancelled`).
- **Backend Implementation:**
  - Controllers: `Site::agenda()`, `Home::index()`, `Admin\Events`.
  - Models: `EventModel`.
- **Database Dependencies:** `events` table (`id`, `title`, `slug`, `description`, `location`, `event_date`, `event_time`, `sort_order`, `status`, `show`, `created_at`, `updated_at`).
- **User Interactions:**
  - Public sees events filtered by active date (past events automatically excluded from upcoming feeds via real-time date evaluation).
  - Admin schedules events with date, time, venue, and descriptions. Status updates automatically based on `CURDATE()`.
- **Implementation Status:** **Complete.**

---

### Feature 12: Photo Gallery & Lightbox (`Galleries`)
- **Purpose:** Document school campus life, ceremonies, laboratory facilities, and athletic events in high-resolution photography.
- **Related Pages:** Public `/gallery`, Admin `/admin/gallery`.
- **Related Components:** Photo grid (`.g-item`, `.g-art`), category filter tabs, featured badges (`★ Unggulan`), full-screen modal lightbox (`#lightbox`, `#lbFig`, `#lbCap`).
- **Backend Implementation:**
  - Controllers: `Site::gallery()`, `Admin\Galleries`.
  - Models: `GalleryModel`.
- **Database Dependencies:** `galleries` table (`id`, `category`, `image`, `caption`, `is_featured`, `sort_order`, `show`, `created_at`, `updated_at`).
- **User Interactions:**
  - Public clicks any photo to open the interactive lightbox; filters photos by category tab.
  - Admin uploads gallery images, assigns categories, marks featured photos, and arranges sorting.
- **Implementation Status:** **Complete.** Lightbox script safely supports both raster `<img>` photos and vector SVG artwork.

---

### Feature 13: Frequently Asked Questions (`FAQ`)
- **Purpose:** Answer common inquiries regarding SPMB registration, scholarships, school fees, and curriculum policies.
- **Related Pages:** Public `/faq`, Admin `/admin/faq`.
- **Related Components:** Accordion collapse cards (`.faq-item`, `.faq-q`, `.faq-a`), category groupings, automatic first-item expanded state.
- **Backend Implementation:**
  - Controllers: `Site::faq()`, `Admin\Faq`.
  - Models: `FaqModel`.
- **Database Dependencies:** `faq` table (`id`, `question`, `answer`, `category`, `sort_order`, `show`, `created_at`, `updated_at`).
- **User Interactions:**
  - Public clicks accordion items with animated smooth heights.
  - Admin manages questions and answers (supporting multi-line formatted text via `nl2br`), assigns categories via datalist, and reorders questions.
- **Implementation Status:** **Complete.**

---

### Feature 14: Download Center (`Downloads`)
- **Purpose:** Distribute official school PDFs, academic calendars, student code of conduct, and admissions application forms.
- **Related Pages:** Public `/downloads`, Admin `/admin/downloads`.
- **Related Components:** Category grouped document table (`.dl-row`), live client-side search input (`#dlSearch`), file size badges, direct download button (`.dl-btn`).
- **Backend Implementation:**
  - Controllers: `App\Controllers\Downloads`, `App\Controllers\Admin\Downloads`.
  - Models: `DownloadModel`.
- **Database Dependencies:** `downloads` table (`id`, `category`, `title`, `description`, `url`, `file_size`, `sort_order`, `show`, `created_at`, `updated_at`).
- **User Interactions:**
  - Public types in the live search bar to immediately filter matching files across categories and clicks download links.
  - Admin records file title, category, description, external URL (Google Drive, cloud storage, or local upload), file size string, and order.
- **Implementation Status:** **Complete.**

---

### Feature 15: Custom Static Pages Builder (`Pages`)
- **Purpose:** Create arbitrary institutional pages (e.g., "Komite Sekolah", "Laboratorium Komputer", "Kerjasama Industri") without modifying codebase routes.
- **Related Pages:** Public `/{slug}` (via catch-all route), Admin `/admin/pages`.
- **Related Components:** Quill 2.0 WYSIWYG editor, reserved slug collision detection, automatic menu linking checkbox (`add_to_menu`), metadata tags editor.
- **Backend Implementation:**
  - Controllers: `App\Controllers\Pages`, `App\Controllers\Admin\Pages`.
  - Models: `PageModel`.
- **Database Dependencies:** `pages` table (`id`, `title`, `slug`, `content`, `excerpt`, `image`, `status`, `meta_title`, `meta_description`, `created_at`, `updated_at`).
- **User Interactions:**
  - Public navigates to static URLs generated dynamically.
  - Admin authors rich HTML pages, sets publication status (Draft/Published), and optionally flags them to appear in navigation menus.
- **Implementation Status:** **Complete.** Safeguarded against reserving core slugs (`news`, `contact`, `admin`, etc.).

---

### Feature 16: Navigation Menu Builder & Cycle Engine (`Menus`)
- **Purpose:** Manage the school website navigation links, reorder positions, and attach custom or static page links.
- **Related Pages:** Admin `/admin/menus`.
- **Related Components:** Hierarchical menu tree table, drag-and-drop sortable rows, orphan detection alert, cycle detection validator.
- **Backend Implementation:**
  - Controllers: `Admin\Menus`.
  - Helpers: `get_extra_nav_items()`, `resolve_menu_url()`, `is_safe_link_url()`.
  - Models: `MenuItemModel`.
- **Database Dependencies:** `menu_items` table (`id`, `parent_id`, `title`, `url`, `target`, `icon`, `section_key`, `sort_order`, `status`, `type`, `page_id`, `created_at`, `updated_at`).
- **User Interactions:**
  - Admin creates custom menu links or attaches published pages, designates target (`_self` / `_blank`), and reorders positions.
- **Implementation Status:** **Partially Complete / Functional with Known Limitation.** (Admin supports multi-level tree hierarchy and cycle detection, but public theme headers only render top-level links because dropdown CSS is not implemented in the theme styles).

---

### Feature 17: Global Site Settings & Configuration Engine (`Settings`)
- **Purpose:** Centralized administrative control over all global school parameters, branding, and content blocks.
- **Related Pages:** Admin `/admin/settings`.
- **Related Components:** 10-tab settings interface:
  1. Identitas Sekolah (Name, Tagline, Description, Text Logo, Icon Logo).
  2. Kontak & Jam Kerja (Phone, Email, Address, Operating Hours).
  3. Media Sosial (Facebook, Instagram, YouTube, TikTok).
  4. Banner Halaman (Hero Badge, Title, Subtitle, CTA buttons, Subpage Banners).
  5. Statistik & Counter (Counter Stats, Hero Stats).
  6. Tahapan SPMB (10-step admissions guide & level presets).
  7. Footer & Tautan (Footer description, copyright, service links, quick links).
  8. Tema Tampilan (Active theme selector: Nusantara, Cakrawala, Lentera).
  9. Tentang Kami (History, Visi, Misi, Accreditation, Highlights).
  10. Kepala Sekolah (Name, Role, Welcome Speech, Portrait Photo).
- **Backend Implementation:**
  - Controllers: `Admin\Settings::index()`, `Admin\Settings::save()`.
  - Models: `SettingModel` (Atomic upsert via `ON DUPLICATE KEY UPDATE`).
- **Database Dependencies:** `settings` table (`id`, `key`, `value`, `created_at`, `updated_at`).
- **Implementation Status:** **Complete.**

---

### Feature 18: Upload File Manager (`Uploads`)
- **Purpose:** Inspect and manage media files stored across public uploads directories.
- **Related Pages:** Admin `/admin/uploads`.
- **Related Components:** Directory filter (`about`, `gallery`, `news`, `principal`, `teachers`), file thumbnail preview, file size/type categorization, safe delete modal.
- **Backend Implementation:**
  - Controllers: `Admin\Uploads`.
- **Database Dependencies:** Operates on the physical filesystem (`public/uploads/*`).
- **User Interactions:** Admin filters files by directory, previews media, checks modification timestamps, and safely deletes orphaned files without risking path traversal attacks.
- **Implementation Status:** **Complete.**

---

### Feature 19: User Administration & RBAC (`Users`)
- **Purpose:** Manage administrative staff accounts, assign roles, and control access permissions.
- **Related Pages:** Admin `/admin/users`.
- **Related Components:** User listing table, role badges, active/inactive toggles, password confirmation fields, Superadmin-only visibility wrapper.
- **Backend Implementation:**
  - Controllers: `Admin\Users`.
  - Models: `UserModel`.
- **Database Dependencies:** `users` table (`id`, `username`, `email`, `password`, `full_name`, `avatar`, `role`, `is_active`, `last_login`, `created_at`, `updated_at`).
- **User Interactions:**
  - Superadmin creates administrator (`admin`) or editor (`editor`) accounts, toggles activation, and resets passwords.
- **Implementation Status:** **Complete.** (Creation of additional `superadmin` accounts is blocked in controller logic).

---

### Feature 20: Admin Self-Service Profile (`Profile`)
- **Purpose:** Allow currently logged-in administrators to update their personal credentials and display names.
- **Related Pages:** Admin `/admin/profile`.
- **Related Components:** Username field, email field, full name field, password change with old password verification.
- **Backend Implementation:**
  - Controllers: `Admin\Profile`.
  - Models: `UserModel`.
- **Database Dependencies:** `users` table.
- **User Interactions:** User changes password or personal details; active session data is synchronized immediately.
- **Implementation Status:** **Complete.**

---

### Feature 21: Multi-Theme Engine & Frontend Switching (`theme_helper`)
- **Purpose:** Allow instant institutional redesigns between distinct aesthetic themes with zero content loss.
- **Related Pages:** All public pages; Admin Settings tab "Tema Tampilan".
- **Related Components:** Theme selector cards in admin with screenshots, metadata loader (`theme.json`), fallback view/asset loader.
- **Backend Implementation:**
  - Helpers: `theme_helper.php` (`active_theme()`, `theme_list()`, `theme_view()`, `theme_asset()`, `theme_og()`).
- **Implementation Status:** **Complete.** 3 fully functioning themes bundled (`nusantara`, `cakrawala`, `lentera`).

---

### Feature 22: Search Engine Optimization & XML Sitemap (`Sitemap`)
- **Purpose:** Ensure maximum visibility on Google, Bing, and national educational search portals.
- **Related Pages:** `/sitemap.xml`, all public `<head>` metadata tags.
- **Related Components:** XML URLset, Open Graph tags, Twitter Card tags, Canonical URLs, JSON-LD Schema.org (`HighSchool` & `NewsArticle`).
- **Backend Implementation:**
  - Controllers: `Sitemap::index()`, `Home::index()`, `News::detail()`.
- **Implementation Status:** **Complete.**
