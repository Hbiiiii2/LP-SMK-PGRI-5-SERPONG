# CMS SekolahKu v3.2.0 — Project Intelligence Knowledge Index

> **Document Type:** Central Master Knowledge Index & Documentation Navigator  
> **Target Version:** CMS SekolahKu v3.2.0  
> **Source Directory:** `c:\laragon\www\cms-sekolahku-v3.2.0-latest`  
> **Compiled Date:** 2026-11-12  

---

## 1. Master Documentation Navigator

This central index provides full traceability across the 8 technical documentation files generated during the comprehensive project discovery and intelligence indexing phase. All specifications are grounded in actual source code evidence.

```text
docs/project-intelligence/
├── PROJECT_INDEX.md                 <-- You are here
├── PROJECT_OVERVIEW.md              (System purpose, stack, domain workflows, directory tree)
├── ARCHITECTURE_ANALYSIS.md         (Request lifecycle, MVC, theme engine, security, routing)
├── FEATURE_INVENTORY.md             (22 comprehensive feature profiles and status mappings)
├── DATABASE_ANALYSIS.md             (16 table schemas, ER diagram, keys, cascades, indexes)
├── UI_UX_AUDIT.md                   (Themes comparison, admin console, usability issues)
├── COMPONENT_INVENTORY.md           (Design tokens, typography, public & admin components)
├── TECHNICAL_RISK_ASSESSMENT.md     (RBAC gaps, proxy IP quirks, CDN risks, technical debt)
└── RECREATION_READINESS.md          (Preserve / Improve / Recreate boundary rules)
```

---

## 2. Document Summaries & Deep-Link Reference

### 1. [`PROJECT_OVERVIEW.md`](file:///c:/laragon/www/cms-sekolahku-v3.2.0-latest/docs/project-intelligence/PROJECT_OVERVIEW.md)
- **Core Subject:** Executive summary of CMS SekolahKu v3.2.0, institutional domain objectives (SD/SMP/SMA/Pesantren), detected technology stack (CodeIgniter 4.7.5, PHP 8.5, CoreUI 5, Quill, MySQL), full repository directory breakdown, and development conventions.

### 2. [`ARCHITECTURE_ANALYSIS.md`](file:///c:/laragon/www/cms-sekolahku-v3.2.0-latest/docs/project-intelligence/ARCHITECTURE_ANALYSIS.md)
- **Core Subject:** End-to-end request lifecycle, global filters pipeline, dual controller hierarchies (`BaseController` vs `Admin\BaseController`), fallback theme resolution engine (`theme_helper.php`), recursive menu cycle detection, multi-bucket rate limiting, CSRF sync protocols, cache strategies, and complete 35+ endpoint routing matrix.

### 3. [`FEATURE_INVENTORY.md`](file:///c:/laragon/www/cms-sekolahku-v3.2.0-latest/docs/project-intelligence/FEATURE_INVENTORY.md)
- **Core Subject:** Complete, granular audit of 22 individual system features (SPMB, News, Comments, Contact, Profile, Programs, Extracurriculars, Teachers, Achievements, Testimonials, Agenda, Gallery, FAQ, Downloads, Static Pages, Menus, Settings, Uploads, Users, Profile, Multi-Theming, Sitemap). Details purpose, related pages, components, backend code, DB dependencies, interactions, and implementation statuses.

### 4. [`DATABASE_ANALYSIS.md`](file:///c:/laragon/www/cms-sekolahku-v3.2.0-latest/docs/project-intelligence/DATABASE_ANALYSIS.md)
- **Core Subject:** Relational database architecture (InnoDB utf8mb4), complete visual ER diagram (Mermaid), exact DDL schema definitions for all 16 tables, primary/foreign key cascading behaviors, composite indexing strategies, migration timeline, and seeders profile.

### 5. [`UI_UX_AUDIT.md`](file:///c:/laragon/www/cms-sekolahku-v3.2.0-latest/docs/project-intelligence/UI_UX_AUDIT.md)
- **Core Subject:** Dual interface audit comparing the CoreUI administrative console against the three bundled public themes (`nusantara`, `cakrawala`, `lentera`). Identifies critical UX inconsistencies: dropped child menus, hardcoded error page palettes, disorienting desktop overlay takeover, and missing breadcrumbs.

### 6. [`COMPONENT_INVENTORY.md`](file:///c:/laragon/www/cms-sekolahku-v3.2.0-latest/docs/project-intelligence/COMPONENT_INVENTORY.md)
- **Core Subject:** Reusable component library catalog and design tokens. Details color palettes, typography scales, utility bars, mastheads, hero sections, announcement tickers, metric counters, content cards, accordions, lightboxes, toasts, and administrative CRUD elements.

### 7. [`TECHNICAL_RISK_ASSESSMENT.md`](file:///c:/laragon/www/cms-sekolahku-v3.2.0-latest/docs/project-intelligence/TECHNICAL_RISK_ASSESSMENT.md)
- **Core Subject:** Identification and risk scoring of technical debt, architectural bottlenecks, and security gaps: RBAC authorization granularity, frontend child menu omission, proxy/CDN IP detection issues, external CDN dependencies, and hosting compatibility.

### 8. [`RECREATION_READINESS.md`](file:///c:/laragon/www/cms-sekolahku-v3.2.0-latest/docs/project-intelligence/RECREATION_READINESS.md)
- **Core Subject:** System Design integration preparation guide. Establishes strict tripartite boundaries (**PRESERVE** non-negotiables, **IMPROVE** design tokens and navigation, **RECREATE** obsolete layouts and UI shells) and presents the pre-flight readiness verification checklist.

---

## 3. Reading Pathways by Stakeholder Role

- **For System Architects & Lead Engineers:**  
  Start with [`ARCHITECTURE_ANALYSIS.md`](file:///c:/laragon/www/cms-sekolahku-v3.2.0-latest/docs/project-intelligence/ARCHITECTURE_ANALYSIS.md) → [`DATABASE_ANALYSIS.md`](file:///c:/laragon/www/cms-sekolahku-v3.2.0-latest/docs/project-intelligence/DATABASE_ANALYSIS.md) → [`TECHNICAL_RISK_ASSESSMENT.md`](file:///c:/laragon/www/cms-sekolahku-v3.2.0-latest/docs/project-intelligence/TECHNICAL_RISK_ASSESSMENT.md).
- **For UI/UX Designers & Product Specialists:**  
  Start with [`UI_UX_AUDIT.md`](file:///c:/laragon/www/cms-sekolahku-v3.2.0-latest/docs/project-intelligence/UI_UX_AUDIT.md) → [`COMPONENT_INVENTORY.md`](file:///c:/laragon/www/cms-sekolahku-v3.2.0-latest/docs/project-intelligence/COMPONENT_INVENTORY.md) → [`RECREATION_READINESS.md`](file:///c:/laragon/www/cms-sekolahku-v3.2.0-latest/docs/project-intelligence/RECREATION_READINESS.md).
- **For Full-Stack Implementers preparing for the System Design prompt:**  
  Start with [`RECREATION_READINESS.md`](file:///c:/laragon/www/cms-sekolahku-v3.2.0-latest/docs/project-intelligence/RECREATION_READINESS.md) → [`FEATURE_INVENTORY.md`](file:///c:/laragon/www/cms-sekolahku-v3.2.0-latest/docs/project-intelligence/FEATURE_INVENTORY.md).
