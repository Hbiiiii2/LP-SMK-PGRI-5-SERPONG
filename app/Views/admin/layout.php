<?php
$officialLogo = base_url('themes/smkpgri5/img/logo-smk-pgri-5-serpong.png');
?>
<!DOCTYPE html>
<html lang="id" data-coreui-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Admin') ?> &mdash; CMS <?= esc($site_name ?? 'SMK PGRI 5 Serpong') ?></title>
    <link rel="icon" type="image/png" href="<?= esc($officialLogo) ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@coreui/coreui@5.9.1/dist/css/coreui.min.css" rel="stylesheet" integrity="sha384-hLBsN3gpx96kCbLUCbANpmr8cdCNM1sH85tJfeZT61uxTNIvelafstACefvdVnuP" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
    <link href="<?= base_url('css/simplebar.css') ?>" rel="stylesheet">
    <link href="<?= base_url('themes/smkpgri5/css/tokens.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/admin-theme.css') ?>" rel="stylesheet">
    <style>
        .sidebar-brand { overflow: hidden; }
        .sidebar-nav {
            --cui-sidebar-nav-padding-x: 0.25rem;
            --cui-sidebar-nav-padding-y: 0.25rem;
            --cui-sidebar-nav-gap: 0;
            --cui-sidebar-nav-link-padding-x: 0.75rem;
            --cui-sidebar-nav-link-padding-y: 0.5rem;
            --cui-sidebar-nav-link-icon-margin: 0.5rem;
            --cui-sidebar-nav-link-icon-width: 1.125rem;
            --cui-sidebar-nav-link-icon-height: 1.125rem;
            --cui-sidebar-nav-link-icon-font-size: 1rem;
        }
        .sidebar-nav[data-simplebar] {
            overflow: visible !important;
            max-height: calc(100vh - 60px);
        }
        .wrapper {
            padding-inline: var(--cui-sidebar-occupy-start, 0) var(--cui-sidebar-occupy-end, 0);
            transition: padding .15s;
        }
        .news-pagination {
            display: flex;
            gap: 4px;
        }
        .np-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            padding: 0 8px;
            border-radius: var(--radius-sm, 6px);
            border: 1px solid var(--cui-border-color);
            font-size: 0.85rem;
            color: var(--cui-body-color);
            background: var(--color-surface, #fff);
            text-decoration: none;
            transition: all .15s;
        }
        .np-btn:hover {
            border-color: var(--color-primary, #087F3E);
            color: var(--color-primary-dark, #056B35);
            background: var(--color-primary-light, #E8F5EC);
        }
        .np-btn.active {
            background: var(--color-primary, #087F3E);
            border-color: var(--color-primary, #087F3E);
            color: #fff;
        }
    </style>
</head>
<body class="small">
    <?php
    $uri = service('uri');
    $activeBase = $uri->getSegment(1) . '/' . $uri->getSegment(2);
    ?>
    <div class="sidebar sidebar-sm sidebar-dark sidebar-institutional sidebar-fixed sidebar-narrow-unfoldable border-end" id="sidebar">
        <div class="sidebar-header border-bottom">
            <div class="sidebar-brand">
                <h6 class="sidebar-brand-full fw-bold mb-0 d-flex align-items-center gap-2 text-white" style="min-width:0">
                    <span class="admin-brand-logo-wrap">
                        <img src="<?= esc($officialLogo) ?>" alt="Logo <?= esc($site_name ?? 'SMK PGRI 5 Serpong') ?>" width="26" height="26">
                    </span>
                    <span class="text-truncate" style="min-width:0;flex:1"><?= esc($site_name ?? 'SMK PGRI 5 Serpong') ?></span>
                </h6>
                <span class="sidebar-brand-narrow">
                    <span class="admin-brand-logo-wrap">
                        <img src="<?= esc($officialLogo) ?>" alt="Logo" width="26" height="26">
                    </span>
                </span>
            </div>
            <button class="btn-close d-lg-none" type="button" data-coreui-theme="dark" onclick="coreui.Sidebar.getInstance(document.querySelector('#sidebar')).toggle()"></button>
        </div>
        <ul class="sidebar-nav simplebar-scrollable-y" data-simplebar>
            <li class="nav-item"><a class="nav-link <?= $activeBase === 'admin/dashboard' ? 'active' : '' ?>" href="/admin/dashboard"><?= lucide_icon('gauge', 'nav-icon', 18) ?><span>Dashboard</span></a></li>
            <li class="nav-title">Konten</li>
            <li class="nav-item"><a class="nav-link <?= $activeBase === 'admin/news' ? 'active' : '' ?>" href="/admin/news"><?= lucide_icon('newspaper', 'nav-icon', 18) ?><span>Berita</span></a></li>
            <li class="nav-item"><a class="nav-link <?= $activeBase === 'admin/teachers' ? 'active' : '' ?>" href="/admin/teachers"><?= lucide_icon('users', 'nav-icon', 18) ?><span>Guru</span></a></li>
            <li class="nav-item"><a class="nav-link <?= $activeBase === 'admin/programs' ? 'active' : '' ?>" href="/admin/programs"><?= lucide_icon('book-open', 'nav-icon', 18) ?><span>Program</span></a></li>
            <li class="nav-item"><a class="nav-link <?= $activeBase === 'admin/extracurriculars' ? 'active' : '' ?>" href="/admin/extracurriculars"><?= lucide_icon('trophy', 'nav-icon', 18) ?><span>Ekskul</span></a></li>
            <li class="nav-item"><a class="nav-link <?= $activeBase === 'admin/achievements' ? 'active' : '' ?>" href="/admin/achievements"><?= lucide_icon('award', 'nav-icon', 18) ?><span>Prestasi</span></a></li>
            <li class="nav-item"><a class="nav-link <?= $activeBase === 'admin/testimonials' ? 'active' : '' ?>" href="/admin/testimonials"><?= lucide_icon('quote', 'nav-icon', 18) ?><span>Testimoni</span></a></li>
            <li class="nav-item"><a class="nav-link <?= $activeBase === 'admin/events' ? 'active' : '' ?>" href="/admin/events"><?= lucide_icon('calendar', 'nav-icon', 18) ?><span>Agenda</span></a></li>
            <li class="nav-item"><a class="nav-link <?= $activeBase === 'admin/gallery' ? 'active' : '' ?>" href="/admin/gallery"><?= lucide_icon('images', 'nav-icon', 18) ?><span>Galeri</span></a></li>
            <li class="nav-item"><a class="nav-link <?= $activeBase === 'admin/faq' ? 'active' : '' ?>" href="/admin/faq"><?= lucide_icon('help-circle', 'nav-icon', 18) ?><span>FAQ</span></a></li>
            <li class="nav-item"><a class="nav-link <?= $activeBase === 'admin/downloads' ? 'active' : '' ?>" href="/admin/downloads"><?= lucide_icon('download', 'nav-icon', 18) ?><span>Download</span></a></li>
            <li class="nav-item"><a class="nav-link <?= $activeBase === 'admin/pages' ? 'active' : '' ?>" href="/admin/pages"><?= lucide_icon('file-text', 'nav-icon', 18) ?><span>Halaman</span></a></li>
            <li class="nav-title">Layanan</li>
            <li class="nav-item"><a class="nav-link <?= $activeBase === 'admin/spmb' ? 'active' : '' ?>" href="/admin/spmb"><?= lucide_icon('graduation-cap', 'nav-icon', 18) ?><span>SPMB Online</span></a></li>
            <li class="nav-item"><a class="nav-link <?= $activeBase === 'admin/messages' ? 'active' : '' ?>" href="/admin/messages"><?= lucide_icon('mail', 'nav-icon', 18) ?><span>Pesan</span></a></li>
            <li class="nav-item"><a class="nav-link <?= $activeBase === 'admin/comments' ? 'active' : '' ?>" href="/admin/comments"><?= lucide_icon('message-circle', 'nav-icon', 18) ?><span>Komentar</span></a></li>
            <li class="nav-title">Sistem</li>
            <li class="nav-item"><a class="nav-link <?= $activeBase === 'admin/uploads' ? 'active' : '' ?>" href="/admin/uploads"><?= lucide_icon('upload', 'nav-icon', 18) ?><span>Upload</span></a></li>
            <?php if (($user['role'] ?? '') === 'superadmin'): ?>
            <li class="nav-item"><a class="nav-link <?= $activeBase === 'admin/users' ? 'active' : '' ?>" href="/admin/users"><?= lucide_icon('user-check', 'nav-icon', 18) ?><span>Pengguna</span></a></li>
            <?php endif; ?>
            <li class="nav-item"><a class="nav-link <?= $activeBase === 'admin/settings' ? 'active' : '' ?>" href="/admin/settings"><?= lucide_icon('settings', 'nav-icon', 18) ?><span>Pengaturan</span></a></li>
            <li class="nav-title">Navigasi</li>
            <li class="nav-item"><a class="nav-link <?= $activeBase === 'admin/menus' ? 'active' : '' ?>" href="/admin/menus"><?= lucide_icon('menu', 'nav-icon', 18) ?><span>Menu</span></a></li>
            <li class="nav-item"><a class="nav-link" href="/" target="_blank"><?= lucide_icon('external-link', 'nav-icon', 18) ?><span>Lihat Situs</span></a></li>
        </ul>
        <div class="sidebar-footer border-top d-none d-md-flex">
            <button class="sidebar-toggler" type="button" aria-label="Alihkan lebar sidebar"></button>
        </div>
    </div>
    <div class="wrapper d-flex flex-column min-vh-100">
        <header class="header header-sticky admin-header bg-white border-bottom px-3 px-md-4 py-2 d-flex justify-content-between align-items-center gap-2">
            <button class="header-toggler" type="button" onclick="coreui.Sidebar.getInstance(document.querySelector('#sidebar')).toggle()" aria-label="Buka menu sidebar">
                <?= lucide_icon('menu', '', 20) ?>
            </button>
            <div class="admin-header-actions">
                <a href="/admin/profile" class="btn btn-light btn-sm border d-inline-flex align-items-center gap-1">
                    <?= lucide_icon('user', '', 15) ?>
                    <span><?= esc($user['fullName'] ?? $user['username'] ?? 'Admin') ?></span>
                </a>
                <form action="/logout" method="post" class="d-inline">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-outline-danger btn-sm d-inline-flex align-items-center gap-1">
                        <?= lucide_icon('log-out', '', 15) ?>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </header>
        <main class="main flex-grow-1 p-4 bg-body-tertiary">
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show"><?= esc(session()->getFlashdata('success')) ?><button type="button" class="btn-close" data-coreui-dismiss="alert"></button></div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show"><?= esc(session()->getFlashdata('error')) ?><button type="button" class="btn-close" data-coreui-dismiss="alert"></button></div>
            <?php endif; ?>
            <?= $this->renderSection('content') ?>
        </main>
        <footer class="footer border-top px-4 py-3 text-body-tertiary small bg-white">
            <div class="d-flex justify-content-between flex-wrap gap-2 w-100">
                <span>&copy; <?= date('Y') ?> CMS <?= esc($site_name ?? 'SMK PGRI 5 Serpong') ?> &middot; Versi <?= esc(defined('APP_VERSION') ? APP_VERSION : '') ?></span>
                <span>CMS by <a href="https://sekolahku.web.id" target="_blank" rel="noopener noreferrer" class="admin-footer-link">sekolahku.web.id</a></span>
            </div>
        </footer>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@coreui/coreui@5.9.1/dist/js/coreui.bundle.min.js" integrity="sha384-yW+X2pqDqevsUW8M2p1APhb8cm2i5jneCZe2pkEvKVATzd6517WyXz6ElE46++OE" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
    <script src="<?= base_url('js/simplebar.min.js') ?>"></script>
    <script>
    window.lucideSpriteUrl = <?= json_encode(lucide_sprite_url()) ?>;
    window.lucideFaMap = <?= json_encode(lucide_icon_map()) ?>;
    window.resolveLucideSymbol = function (name) {
        var clean = String(name || '').toLowerCase().trim();
        if (!clean) return 'star';
        var tokens = clean.split(/\s+/);
        for (var i = 0; i < tokens.length; i++) {
            var t = tokens[i];
            if (['fa','fas','far','fab','fa-solid','fa-regular','fa-brands','fa-xs','fa-sm','fa-lg','fa-2x','fa-3x','fa-4x','fa-spin','fa-fw'].indexOf(t) !== -1) continue;
            t = t.replace(/^(lucide-|fa-)/, '');
            if (t) return window.lucideFaMap[t] || 'circle';
        }
        return 'star';
    };
    </script>
    <script>
    document.addEventListener("DOMContentLoaded", () => {
        const sidebar = document.querySelector(".sidebar");
        const toggler = document.querySelector(".sidebar-toggler");
        if (!sidebar || !toggler) return;

        let saved = window.localStorage.getItem("cmsSidebarState");
        if (!saved) {
            saved = "narrow";
            window.localStorage.setItem("cmsSidebarState", "narrow");
        }
        if (saved === "narrow") {
            sidebar.classList.add("sidebar-narrow-unfoldable");
        } else {
            sidebar.classList.remove("sidebar-narrow-unfoldable");
        }

        toggler.addEventListener("click", () => {
            const narrow = sidebar.classList.toggle("sidebar-narrow-unfoldable");
            window.localStorage.setItem("cmsSidebarState", narrow ? "narrow" : "wide");
        });
    });
    </script>
    <script>
    // Sinkronkan token CSRF ke seluruh halaman setelah aksi AJAX memutar token.
    // CSRF cookie memakai $regenerate = true, jadi setiap POST sukses menghasilkan
    // token baru. Tanpa ini, form yang di-render server (mis. tombol Hapus per-baris
    // dan form logout di header) tetap membawa token lama dan gagal 403.
    window.syncCsrf = function (hash) {
        if (!hash) return;
        document.querySelectorAll('meta[name="csrf-token"]').forEach(function (el) {
            el.setAttribute('content', hash);
        });
        document.querySelectorAll('input[name="csrf_test_name"]').forEach(function (el) {
            el.value = hash;
        });
    };
    </script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
