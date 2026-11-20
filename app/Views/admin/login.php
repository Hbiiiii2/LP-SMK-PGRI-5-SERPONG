<?php
$siteName = trim((string) ($settings['site_name'] ?? 'SMK PGRI 5 Serpong')) ?: 'SMK PGRI 5 Serpong';
$logoUrl  = base_url('themes/smkpgri5/img/logo-smk-pgri-5-serpong.png');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk &mdash; Panel Admin <?= esc($siteName) ?></title>
    <meta name="description" content="Halaman masuk panel administrator <?= esc($siteName) ?>">
    <link rel="icon" type="image/png" href="<?= esc($logoUrl) ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= esc(base_url('themes/smkpgri5/css/tokens.css')) ?>">
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        html, body { height: 100%; margin: 0; overflow-x: clip; }
        body {
            font-family: var(--font-sans);
            font-size: 15px;
            line-height: 1.5;
            color: var(--color-text-primary);
            background: var(--color-background);
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1.1fr);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        .brand-panel {
            background-color: var(--color-primary-dark);
            color: var(--color-white);
            padding: 3rem 3.25rem;
            display: flex;
            flex-direction: column;
            border-right: 1px solid var(--color-border);
        }

        .brand-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .brand-logo-badge {
            width: 68px;
            height: 68px;
            padding: 6px;
            border-radius: var(--radius-md);
            background-color: var(--color-white);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: var(--shadow-sm);
        }

        .brand-logo-badge img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
        }

        .brand-role-label {
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--color-accent-yellow);
            margin-bottom: 4px;
        }

        .brand-name {
            font-size: 1.75rem;
            font-weight: 700;
            line-height: 1.2;
            letter-spacing: -0.02em;
            color: var(--color-white);
        }

        .brand-tagline {
            font-size: 0.9375rem;
            line-height: 1.6;
            color: var(--color-on-dark-muted);
            margin: 0 0 2rem;
            max-width: 42ch;
        }

        .brand-facts {
            margin: 0 0 2rem;
            padding: 0;
            list-style: none;
            border-top: 1px solid var(--color-on-dark-border);
        }

        .brand-facts li {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            gap: 1rem;
            padding: 0.8rem 0;
            border-bottom: 1px solid var(--color-on-dark-border);
            font-size: 0.875rem;
        }

        .brand-facts .k {
            color: var(--color-on-dark-muted);
            font-weight: 500;
        }

        .brand-facts .v {
            color: var(--color-white);
            font-weight: 600;
        }

        .brand-contact {
            padding-top: 0.25rem;
            font-size: 0.84rem;
            color: var(--color-on-dark-muted);
            line-height: 1.6;
        }

        .brand-contact-title {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--color-white);
            margin-bottom: 0.5rem;
        }

        .brand-contact ul {
            list-style: none;
            margin: 0 0 0.875rem;
            padding: 0;
        }

        .brand-contact li { padding: 0.15rem 0; }

        .brand-contact a {
            color: var(--color-white);
            text-decoration: none;
        }

        .brand-contact a:hover { text-decoration: underline; }

        .brand-social {
            display: flex;
            gap: 0.5rem;
            margin-top: 0.25rem;
        }

        .brand-social a {
            width: 34px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--color-on-dark-border);
            border-radius: var(--radius-sm);
            background-color: var(--color-on-dark-surface);
            color: var(--color-white);
            text-decoration: none;
            transition: background-color var(--transition-fast);
        }

        .brand-social a:hover {
            background-color: var(--color-primary);
        }

        .brand-foot {
            margin-top: auto;
            padding-top: 2rem;
            font-size: 0.78rem;
            color: var(--color-on-dark-muted);
        }

        .brand-foot a {
            color: var(--color-white);
            font-weight: 600;
            text-decoration: none;
        }

        .brand-foot a:hover { text-decoration: underline; }

        .form-col {
            background: var(--color-surface);
            display: grid;
            place-items: center;
            padding: 2.5rem 2rem;
        }

        .form-box {
            width: 100%;
            max-width: 390px;
        }

        .mobile-brand {
            display: none;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .mobile-brand img {
            width: 44px;
            height: 44px;
            object-fit: contain;
        }

        .mobile-brand-name {
            font-weight: 700;
            font-size: 1rem;
            color: var(--color-text-primary);
        }

        .form-title {
            font-size: 1.75rem;
            font-weight: 700;
            line-height: 1.2;
            letter-spacing: -0.02em;
            color: var(--color-text-primary);
            margin: 0 0 0.45rem;
        }

        .form-sub {
            margin: 0 0 1.75rem;
            font-size: 0.9375rem;
            color: var(--color-text-secondary);
        }

        .notice {
            padding: 0.8rem 1rem;
            margin-bottom: 1.25rem;
            border: 1px solid var(--color-accent-red-border);
            border-radius: var(--radius-sm);
            background: var(--color-accent-red-soft);
            color: #991B1B;
            font-size: 0.875rem;
            line-height: 1.5;
        }

        .notice ul { margin: 0; padding-left: 1.15rem; }

        .field { margin-bottom: 1.25rem; }

        .field label {
            display: block;
            margin-bottom: 0.4rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--color-text-primary);
        }

        .input-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }

        .field input {
            width: 100%;
            font: inherit;
            font-size: 0.9375rem;
            color: var(--color-text-primary);
            background: var(--color-surface);
            border: 1px solid var(--color-border-strong);
            border-radius: var(--radius-sm);
            padding: 0.7rem 0.875rem;
            outline: none;
            transition: border-color var(--transition-fast), box-shadow var(--transition-fast);
        }

        .field input::placeholder { color: var(--color-text-muted); }

        .field input:focus {
            border-color: var(--color-primary);
            box-shadow: var(--focus-ring);
        }

        .field .toggle {
            position: absolute;
            right: 0.35rem;
            background: transparent;
            border: 0;
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: var(--color-text-secondary);
            border-radius: var(--radius-sm);
            cursor: pointer;
            transition: color var(--transition-fast), background-color var(--transition-fast);
        }

        .field .toggle:hover {
            color: var(--color-text-primary);
            background-color: var(--color-background);
        }

        .field input[name="password"] { padding-right: 2.75rem; }

        .btn-submit {
            width: 100%;
            margin-top: 0.5rem;
            padding: 0.8rem 1.25rem;
            font: inherit;
            font-size: 0.9375rem;
            font-weight: 600;
            color: var(--color-white);
            background: var(--color-primary);
            border: 1px solid var(--color-primary);
            border-radius: var(--radius-sm);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: background-color var(--transition-fast), border-color var(--transition-fast);
        }

        .btn-submit:hover {
            background: var(--color-primary-dark);
            border-color: var(--color-primary-dark);
        }

        .btn-submit:focus-visible {
            outline: none;
            box-shadow: var(--focus-ring);
        }

        .form-meta {
            margin-top: 1.75rem;
            padding-top: 1.25rem;
            border-top: 1px solid var(--color-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 0.75rem;
            font-size: 0.8125rem;
            color: var(--color-text-secondary);
        }

        .back-link {
            color: var(--color-secondary-dark);
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }

        .back-link:hover {
            color: var(--color-primary-dark);
            text-decoration: underline;
        }

        @media (max-width: 900px) {
            body { grid-template-columns: minmax(0, 1fr); }
            .brand-panel { display: none; }
            .mobile-brand { display: flex; }
            .form-col { min-height: 100vh; padding: 2rem 1.25rem; }
        }
    </style>
</head>
<body>
    <aside class="brand-panel" aria-label="Identitas sekolah">
        <div class="brand-header">
            <div class="brand-logo-badge">
                <img src="<?= esc($logoUrl) ?>" alt="Logo Resmi <?= esc($siteName) ?>" width="56" height="56">
            </div>
            <div>
                <div class="brand-role-label">Panel Administrator CMS</div>
                <div class="brand-name"><?= esc($siteName) ?></div>
            </div>
        </div>
        <p class="brand-tagline"><?= esc($settings['site_tagline'] ?? 'Portal Administrasi Resmi Sekolah') ?></p>
        <ul class="brand-facts">
            <li><span class="k">Akreditasi</span><span class="v"><?= esc(($settings['accreditation'] ?? '') !== '' ? $settings['accreditation'] : '&mdash;') ?></span></li>
            <li><span class="k">Kurikulum</span><span class="v">Merdeka</span></li>
            <li><span class="k">Akses</span><span class="v">Khusus petugas resmi</span></li>
        </ul>
        <?php
        $__phone = trim((string) ($settings['contact_phone'] ?? ''));
        $__email = trim((string) ($settings['contact_email'] ?? ''));
        $__addr  = trim((string) ($settings['contact_address'] ?? ''));
        $__socials = [];
        foreach (['social_facebook' => ['Facebook', 'facebook'], 'social_instagram' => ['Instagram', 'instagram'], 'social_youtube' => ['YouTube', 'youtube'], 'social_tiktok' => ['TikTok', 'video']] as $__sk => $__meta) {
            $__su = trim((string) ($settings[$__sk] ?? ''));
            if ($__su !== '' && $__su !== '#') $__socials[$__sk] = [$__su, $__meta[0], $__meta[1]];
        }
        ?>
        <?php if ($__phone !== '' || $__email !== '' || $__addr !== '' || ! empty($__socials)): ?>
        <div class="brand-contact">
            <div class="brand-contact-title">Kontak Resmi Sekolah</div>
            <ul>
                <?php if ($__addr !== ''): ?><li><?= esc($__addr) ?></li><?php endif; ?>
                <?php if ($__phone !== ''): ?><li><a href="tel:<?= esc(preg_replace('/[^+\d]/', '', $__phone)) ?>"><?= esc($__phone) ?></a></li><?php endif; ?>
                <?php if ($__email !== ''): ?><li><a href="mailto:<?= esc($__email) ?>"><?= esc($__email) ?></a></li><?php endif; ?>
            </ul>
            <?php if (! empty($__socials)): ?>
            <div class="brand-social">
                <?php foreach ($__socials as $__s): ?>
                <a href="<?= esc($__s[0]) ?>" target="_blank" rel="noopener noreferrer" aria-label="<?= esc($__s[1]) ?>" title="<?= esc($__s[1]) ?>"><?= lucide_icon($__s[2], '', 15) ?></a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        <div class="brand-foot">CMS SEKOLAHKU &middot; <?= esc(defined('APP_VERSION') ? APP_VERSION : '') ?> &middot; Dikembangkan oleh <a href="https://sekolahku.web.id/" target="_blank" rel="noopener noreferrer">sekolahku.web.id</a></div>
    </aside>

    <main class="form-col">
        <div class="form-box">
            <div class="mobile-brand">
                <img src="<?= esc($logoUrl) ?>" alt="Logo Resmi <?= esc($siteName) ?>" width="44" height="44">
                <div>
                    <div class="mobile-brand-name"><?= esc($siteName) ?></div>
                    <div style="font-size: 0.78rem; color: var(--color-text-secondary);">Panel Administrator CMS</div>
                </div>
            </div>

            <h1 class="form-title">Masuk Administrator</h1>
            <p class="form-sub">Kelola konten, informasi akademik, dan layanan publik <?= esc($siteName) ?>.</p>

            <?php $loginError = session()->getFlashdata('error'); $loginErrors = session()->getFlashdata('errors'); ?>
            <?php if ($loginError || $loginErrors): ?>
                <div class="notice" role="alert">
                    <?php if ($loginError): ?><?= esc($loginError) ?><?php endif; ?>
                    <?php if ($loginErrors): ?>
                        <ul><?php foreach ((array) $loginErrors as $e): ?><li><?= esc($e) ?></li><?php endforeach; ?></ul>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <form action="/login/attempt" method="post" novalidate>
                <?= csrf_field() ?>
                <div class="field">
                    <label for="username">Nama Pengguna</label>
                    <div class="input-wrap">
                        <input type="text" name="username" id="username" placeholder="Masukkan nama pengguna" required autofocus autocomplete="username" value="<?= esc(old('username')) ?>">
                    </div>
                </div>
                <div class="field">
                    <label for="password">Kata Sandi</label>
                    <div class="input-wrap">
                        <input type="password" name="password" id="password" placeholder="Masukkan kata sandi" required autocomplete="current-password">
                        <button type="button" class="toggle" id="togglePassword" aria-label="Tampilkan kata sandi" aria-pressed="false" tabindex="-1">
                            <?= lucide_icon('eye', '', 17) ?>
                        </button>
                    </div>
                </div>
                <button type="submit" class="btn-submit">
                    <span>Masuk</span>
                    <?= lucide_icon('arrow-right', '', 16) ?>
                </button>
            </form>

            <div class="form-meta">
                <span>&copy; <?= date('Y') ?> <?= esc($siteName) ?></span>
                <a class="back-link" href="<?= base_url('/') ?>">
                    <?= lucide_icon('arrow-left', '', 14) ?>
                    <span>Kembali ke situs</span>
                </a>
            </div>
        </div>
    </main>

    <script>
    (function() {
        var btn = document.getElementById('togglePassword');
        var input = document.getElementById('password');
        var spriteBase = <?= json_encode(lucide_sprite_url()) ?>;
        if (!btn || !input) return;
        btn.addEventListener('click', function() {
            var show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            btn.setAttribute('aria-pressed', show ? 'true' : 'false');
            btn.setAttribute('aria-label', show ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
            var useEl = btn.querySelector('use');
            if (useEl) useEl.setAttribute('href', spriteBase + '#lucide-' + (show ? 'eye-off' : 'eye'));
            input.focus();
        });
    })();
    </script>
</body>
</html>
