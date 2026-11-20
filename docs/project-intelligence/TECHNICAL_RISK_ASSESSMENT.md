# CMS SekolahKu v3.2.0 — Technical Risk Assessment

> **Document Type:** Architectural Risk, Technical Debt & Vulnerability Assessment  
> **Target Version:** CMS SekolahKu v3.2.0  
> **Source Directory:** `c:\laragon\www\cms-sekolahku-v3.2.0-latest`  
> **Analysis Date:** 2026-11-12  

---

## 1. Executive Risk Matrix

| Risk Domain | Severity | Probability | Impact Summary |
| :--- | :--- | :--- | :--- |
| **RBAC Authorization Gap** | **HIGH** | Medium | Editors can access and alter Settings, Pages, and Menus |
| **Submenu Disconnection** | **MEDIUM** | High | Nested menus configured in admin do not render on frontend |
| **Proxy IP Rate-Limiting Flaw**| **MEDIUM** | Medium | Empty `$proxyIPs` triggers collective visitor lockouts behind CDN/proxy |
| **External CDN Dependency** | **MEDIUM** | High | Admin panel breaks if external CDNs are blocked or offline |
| **PHP 8.5 Deployment Friction** | **MEDIUM** | Medium | `composer.json` targets PHP ^8.5, limiting compatibility on older hostings |
| **Local File Storage Coupled** | **LOW** | Medium | Public uploads reside in `public/uploads/` without cloud storage drivers |

---

## 2. In-Depth Technical Risk Findings

### 1. Role-Based Access Control (RBAC) Granularity Gap
- **Code Evidence:**  
  In `app/Controllers/Admin/BaseController.php`, the system verifies that the user is logged in and active, and populates `$this->data['user']['role']`. However, individual feature controllers (`Settings`, `Menus`, `Pages`, `News`, `Teachers`, etc.) do not check user roles. Only `Admin\Users` restricts access to `superadmin`.
- **Architectural Risk:**  
  Users assigned the `editor` role can directly access `/admin/settings`, `/admin/menus`, and `/admin/pages` by typing the URL, allowing them to modify institutional configuration, footer links, and site navigation.
- **Remediation Recommendation:**  
  Implement a route filter or BaseController guard that enforces granular permissions (e.g. `superadmin` only for Settings and Menus; `admin` for Pages and Masters; `editor` for News, Agenda, and Galleries).

---

### 2. Navigation Disconnection & Child Menu Dropping
- **Code Evidence:**  
  `app/Helpers/menu_helper.php` (Line 89):
  ```php
  $items = new MenuItemModel()
      ->where('status', 1)
      ->where('parent_id', null)  // Only selects top-level items!
      ->whereNotIn('type', ['system'])
      ->orderBy('sort_order', 'ASC')
      ->findAll();
  ```
- **Architectural Risk:**  
  The database schema `menu_items` supports nested trees (`parent_id`), and the Admin Menu Builder encourages administrators to create multi-level navigation trees. However, the frontend theme engine discards all child items (`parent_id IS NOT NULL`) because dropdown styling was omitted from the theme CSS.
- **Remediation Recommendation:**  
  During UI recreation, implement modern, accessible CSS dropdown menus and mobile drill-down drawers so that nested menus configured by school administrators render reliably.

---

### 3. Proxy & CDN IP Detection Vulnerability
- **Code Evidence:**  
  `app/Config/App.php` sets `public array $proxyIPs = [];`.  
  Both `Admin\Auth`, `Contact::send()`, and `News::comment()` invoke `service('throttler')->check('contact-ip-' . $ip, 5, 600)`.
- **Architectural Risk:**  
  When the application is deployed behind Cloudflare, an Nginx reverse proxy, or within a school computer lab sharing a single NAT gateway, `$this->request->getIPAddress()` returns the reverse proxy IP. As a consequence, one visitor's spam triggers a global throttle locking out all users on that network.
- **Remediation Recommendation:**  
  Document clear instructions to configure `$proxyIPs` in `App.php` or derive it from environment variables (`.env`).

---

### 4. Critical External CDN Dependencies in Admin Console
- **Code Evidence:**  
  `app/Views/admin/layout.php` loads core assets via external third-party CDNs:
  - CoreUI CSS: `cdn.jsdelivr.net/npm/@coreui/coreui@5.9.1/dist/css/coreui.min.css`
  - FontAwesome: `cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css`
  - Quill Editor: `cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css` & `quill.js`
  - CoreUI JS: `cdn.jsdelivr.net/npm/@coreui/coreui@5.9.1/dist/js/coreui.bundle.min.js`
- **Operational Risk:**  
  If the hosting server or local client network experiences CDN outages, high latency, or government/institutional firewall filtering, the admin panel loses all styling, navigation toggles, and rich-text editing capabilities.
- **Remediation Recommendation:**  
  Vendor all administrative assets locally into `public/assets/vendor/` to achieve 100% self-contained offline readiness.

---

### 5. PHP 8.5 Version Constraint vs Indonesian Hosting Landscape
- **Code Evidence:**  
  `composer.json` defines `"require": { "php": "^8.5" }` and `"platform": { "php": "8.5.0" }`.
- **Deployment Risk:**  
  In Indonesia, the vast majority of shared hosting providers and government cloud instances (Pusdatin, Kemdikbud, provincial hosting) run PHP 8.1, 8.2, or 8.3. Enforcing PHP 8.5 creates severe deployment resistance for standard school webmasters.
- **Technical Finding:**  
  An inspection of the codebase indicates standard PHP 8.2/8.3 syntax (match expressions, typed class constants, typed properties). Lowering the constraint to `^8.2 || ^8.3 || ^8.4 || ^8.5` would dramatically improve installation viability.

---

### 6. Local Disk Coupling for Media Uploads
- **Code Evidence:**  
  Controllers write directly to `FCPATH . 'uploads/' . $folder` using native PHP `move()` and GD image streams.
- **Scalability Risk:**  
  Deploying the application in containerized, multi-instance, or serverless environments (e.g. Kubernetes, Google Cloud Run, AWS ECS) will cause uploaded images to disappear when containers recycle, unless persistent volumes or S3-compatible cloud storage drivers are integrated.
- **Remediation Recommendation:**  
  Abstract file storage behind an adapter interface to support both local filesystem storage and S3/GCS object storage.

---

### 7. Inconsistent Error Handling Across AJAX Reorder Endpoints
- **Code Evidence:**  
  In controllers like `Admin\Teachers::reorder()` or `Admin\Achievements::reorder()`:
  ```php
  $this->db->query("UPDATE {$this->table} SET sort_order = CASE {$case} END WHERE id IN ({$idsList})");
  ```
  This is executed as raw SQL rather than through the query builder, and lacks transactional wrapping (`transBegin()`, `transCommit()`).
- **Data Integrity Risk:**  
  If a network interrupt occurs mid-query or an ID list contains invalid integers, sort orders can become corrupted or scrambled across pages.
- **Remediation Recommendation:**  
  Wrap all batch updates inside database transactions.
