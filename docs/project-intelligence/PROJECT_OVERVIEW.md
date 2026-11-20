# CMS SekolahKu v3.2.0 — Project Overview

> **Document Type:** Project Intelligence Specification  
> **Target Version:** CMS SekolahKu v3.2.0 (Released October 2026, upgraded from v3.1.2)  
> **Source Directory:** `c:\laragon\www\cms-sekolahku-v3.2.0-latest`  
> **Analysis Date:** 2026-11-12  

---

## 1. Executive Summary

**CMS SekolahKu** is an enterprise-grade, lightweight Content Management System purpose-built for Indonesian educational institutions spanning Elementary Schools (SD), Junior High Schools (SMP), Senior High Schools (SMA/SMK), and Islamic Boarding Schools (Pesantren/Madrasah). 

Originally operating as a single-page landing site with an admin dashboard (v3.1.2 and earlier), version **v3.2.0** underwent a major architectural evolution into a **fully decoupled, multi-theme, multi-page web platform** featuring 13 dedicated content pages, dynamic static page publishing, an academic downloads center, a rich student achievements portfolio, school event agenda scheduling, news publication with moderated visitor comments, and an end-to-end institutional configuration engine.

The platform is designed to run efficiently on standard PHP web hosting (LAMP/LEMP, Apache/Nginx, cPanel/Plesk, Laragon) while enforcing strict web application security standards (defense-in-depth CSRF token rotation, multi-bucket rate limiting, stored XSS neutralization, recursive menu cycle prevention, and SQL injection immunity).

---

## 2. Core Business Objectives & Domain Workflows

1. **Institutional Identity & Public Relations:**  
   Provide prospective parents, enrolled students, alumni, and education authorities (Dinas Pendidikan, Kemendikbudristek, Kemenag) with comprehensive information about school history, vision-mission, accreditation, leadership, and daily school culture.

2. **SPMB (Seleksi Penerimaan Murid Baru) Guidance:**  
   Deliver a 10-step step-by-step guidance workflow with progress tracking that walks prospective students through document preparation, report card entry, and registration form finalization. Supports tailored step presets for SD, SMP, and SMA levels.

3. **Academic & Extracurricular Showcase:**  
   Showcase specialized curriculum pathways (MIPA, IPS, Bahasa, Kejuruan) and student club activities with custom icon badges, color tagging, and direct inquiry links.

4. **Faculty & Staff Credibility:**  
   Maintain an organized public directory of certified educators, leadership personnel, academic experience, credentials, and professional social profiles.

5. **Excellence & Achievements Record:**  
   Catalog student awards, championship medals (Gold, Silver, Bronze), and competition scopes (City, Provincial, National, International) with photo documentation and drag-and-drop chronological sorting.

6. **Transparency & Public Services:**  
   Host an accessible Download Center for academic calendars, student handbook PDFs, curriculum syllabi, and official parent authorization forms with instant keyword search.

7. **Stakeholder Engagement:**  
   Facilitate secure visitor inquiries via a throttled contact form, moderated article comment discussions with nested replies, and school event calendar scheduling.

---

## 3. Technology Stack Breakdown

| Technology Layer | Specification | Evidence / Implementation Source |
| :--- | :--- | :--- |
| **Backend Language** | PHP ^8.5 (Platform Target: 8.5.0) | `composer.json` (`"php": "^8.5"`) |
| **Backend Framework** | CodeIgniter 4.7.5 | `composer.json` (`codeigniter4/framework: ^4.7`) |
| **Database Engine** | MySQL 8.x / MariaDB (InnoDB, utf8mb4) | `sekolahku_db.sql` (`ENGINE=InnoDB CHARSET=utf8mb4`) |
| **Asset Minification** | matthiasmullie/minify v1.3 | `composer.json` (`vendor/bin/minifycss`, `minifyjs`) |
| **Admin UI Framework** | CoreUI 5.9.1 (Bootstrap 5 variant) | `app/Views/admin/layout.php` (CoreUI CDN) |
| **Admin Icons** | FontAwesome 6.5.0 | `app/Views/admin/layout.php` (FontAwesome CDN) |
| **Admin Rich Text Editor**| Quill 2.0.3 (Snow Theme) | `app/Views/admin/layout.php` (Quill CDN) |
| **Admin Custom Scroller**| SimpleBar | `public/css/simplebar.css`, `public/js/simplebar.min.js` |
| **Public Frontend Architecture**| Multi-Theme Decoupled Views | `app/Views/themes/` (`nusantara`, `cakrawala`, `lentera`) |
| **Public Scripting** | Vanilla ES6 JavaScript (Zero Framework) | `public/themes/*/js/script.js` |
| **Public Typography** | Google Fonts (Fraunces, Archivo, IBM Plex Mono, Jakarta Sans, Lora, Inter, Newsreader) | `app/Views/themes/*/layouts/header.php` |
| **Session & Cache** | FileHandler (Sessions) / File Cache | `app/Config/Cache.php`, `writable/cache/` |
| **Security Standards** | CSRF Token Rotation, Throttler, Escaping | `app/Config/Filters.php`, `app/Controllers/Admin/BaseController.php` |

---

## 4. Top-Level Directory Anatomy

```text
cms-sekolahku-v3.2.0-latest/
├── app/                              # CodeIgniter 4 Core Application Root
│   ├── Config/                       # Application Configuration & Routing
│   │   ├── App.php                   # Base URL, protocol, and proxy configuration
│   │   ├── Constants.php             # Core constants (APP_VERSION = 'v3.2.0')
│   │   ├── Database.php              # MySQL/MariaDB database configuration
│   │   ├── Filters.php               # Global HTTP filters (CSRF, toolbar, secureheaders)
│   │   └── Routes.php                # Complete public & administrative route definitions
│   ├── Controllers/                  # Request Handlers & Business Logic
│   │   ├── BaseController.php        # Abstract parent controller
│   │   ├── Contact.php               # Public contact & message submission
│   │   ├── Downloads.php             # Public download repository controller
│   │   ├── Home.php                  # Public homepage aggregator
│   │   ├── News.php                  # Public articles, category filters, comments
│   │   ├── Pages.php                 # Public dynamic static pages controller
│   │   ├── Site.php                  # Public 9-module dedicated subpage controller
│   │   ├── Sitemap.php               # Dynamic XML sitemap generator
│   │   └── Admin/                    # Administrative Control Panel Controllers
│   │       ├── BaseController.php    # Admin session verification, auth guard, utilities
│   │       ├── Auth.php              # Login, anti-brute force, session management
│   │       ├── Dashboard.php         # Statistics aggregator & quick preview lists
│   │       ├── Settings.php          # 10-tab system configuration engine
│   │       ├── News.php              # Article CRUD, image resizer, quick AJAX updates
│   │       ├── Comments.php          # Comment moderation, approval, bulk operations
│   │       ├── Messages.php          # Contact message inbox, read state, bulk delete
│   │       ├── Menus.php             # Navigation tree, cycle detection, reorder
│   │       ├── Pages.php             # Static page builder, reserved slug protection
│   │       ├── Teachers.php          # Faculty directory CRUD, reorder
│   │       ├── Programs.php          # Curriculum program CRUD, reorder
│   │       ├── Extracurriculars.php  # Student club CRUD, badge styling, reorder
│   │       ├── Achievements.php      # Student achievements CRUD, medal tiers, reorder
│   │       ├── Testimonials.php      # Parent/alumni quote CRUD, reorder
│   │       ├── Events.php            # School calendar CRUD, real-time status
│   │       ├── Galleries.php         # Photo albums CRUD, featured flags, reorder
│   │       ├── Faq.php               # Accordion Q&A CRUD, category datalist, reorder
│   │       ├── Downloads.php         # Document download repository CRUD, reorder
│   │       ├── Uploads.php           # File manager, safe delete, directory scanner
│   │       ├── Users.php             # User management, role access (Superadmin only)
│   │       └── Profile.php           # Current user credential & profile manager
│   ├── Database/                     # Database Migrations & Seeders
│   │   ├── Migrations/               # Schema evolution scripts (v3.0 -> v3.2)
│   │   └── Seeds/                    # Default factory seeders for 14 models
│   ├── Helpers/                      # Custom Procedural Functions
│   │   ├── menu_helper.php           # Dynamic navigation resolver, URL safety guards
│   │   └── theme_helper.php          # Multi-theme resolution, SPMB presets, setting helpers
│   ├── Models/                       # CI4 Active Record Data Models (16 Models)
│   └── Views/                        # UI Presentation Templates
│       ├── admin/                    # CoreUI administrative views
│       ├── errors/                   # Error pages (404, 400, exceptions)
│       └── themes/                   # Frontend Themes (nusantara, cakrawala, lentera)
├── public/                           # Web Server Document Root
│   ├── .htaccess                     # Apache rewrite rules & security headers
│   ├── index.php                     # Front Controller Entrypoint
│   ├── css/                          # Shared public CSS (SimpleBar)
│   ├── js/                           # Shared public JavaScript (SimpleBar)
│   ├── themes/                       # Public theme static assets (CSS, JS, images)
│   └── uploads/                      # Uploaded media storage (about, news, teachers, etc.)
├── writable/                         # Ephemeral & runtime storage (logs, cache, sessions)
├── composer.json                     # PHP Dependencies & Autoload specifications
├── sekolahku_db.sql                  # Canonical database schema & demo seed dump
├── UPGRADE.sql                       # Upgrade migration script (v3.1.2 -> v3.2.0)
└── CATATAN_UPDATE.pdf                # Official release notes and architectural changelog
```

---

## 5. Development Guidelines & Standards Detected

1. **Separation of Concerns:**  
   Public business logic is cleanly isolated from administrative controllers. Shared view variables and brand parameters are queried centrally via `SettingModel::getMany()` to avoid redundant individual queries.

2. **Defense-in-Depth Security Guardrails:**  
   - Every administrative controller extends `App\Controllers\Admin\BaseController`, enforcing database-level session re-verification on every request.
   - External links and submitted URLs pass through `is_safe_link_url()` and `is_dangerous_url()` to eliminate `javascript:`, `data:`, and `vbscript:` payload vectors.
   - CSRF tokens are rotated on every POST and resynchronized automatically via JavaScript to maintain seamless user flows.
   - Quill WYSIWYG submissions are strictly bound to explicit HTML form actions to prevent accidental form nesting.

3. **Multi-Theme Resolution Convention:**  
   All frontend controllers invoke `theme_view('pages/<view_name>')` and `theme_asset('<path>')`. If an asset or view template does not exist inside the currently active theme directory, the framework seamlessly falls back to `themes/nusantara`.

4. **Zero-Database-Alteration Policy for Upgrades:**  
   Setting updates and migrations are structured defensively using conditional queries (`ON DUPLICATE KEY UPDATE`, `IF NOT EXISTS` prepared statements, and fallback helpers) so that upgrades can run repeatedly without corrupting existing school data.
