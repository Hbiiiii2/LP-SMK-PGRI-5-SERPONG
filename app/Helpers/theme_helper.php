<?php

// Helper theme frontend: resolves views & assets to the active theme
// with automatic fallback to the "nusantara" theme.

use App\Models\SettingModel;

if (!function_exists('active_theme')) {
    /**
     * Get the sanitized active theme name (fallback: "nusantara").
     */
    function active_theme(): string
    {
        static $theme = null;
        if ($theme !== null) {
            return $theme;
        }
        $theme = 'nusantara';
        try {
            $val = new SettingModel()->get('active_theme');
            // BC: "default" was renamed to "nusantara" in v3.2.0
            if ($val === 'default') {
                $val = 'nusantara';
            }
            if (is_string($val) && preg_match('/^[a-z0-9_-]+$/i', $val)
                && is_dir(APPPATH . 'Views/themes/' . $val)) {
                $theme = $val;
            }
        } catch (Throwable) {
            $theme = 'nusantara';
        }
        return $theme;
    }
}

if (!function_exists('theme_list')) {
    /**
     * List available frontend themes (folder + theme.json metadata).
     *
     * @return array<string, array{name,title,description,version,screenshot}>
     */
    function theme_list(): array
    {
        $list = [];
        foreach (glob(APPPATH . 'Views/themes/*', GLOB_ONLYDIR) ?: [] as $dir) {
            $name = basename($dir);
            if (!preg_match('/^[a-z0-9_-]+$/i', $name)) {
                continue;
            }
            $meta = [];
            $json = $dir . '/theme.json';
            if (is_file($json)) {
                $meta = json_decode((string)file_get_contents($json), true) ?: [];
            }
            $shot = 'themes/' . $name . '/screenshot.png';
            $list[$name] = [
                'name' => $name,
                'title' => $meta['title'] ?? ucfirst($name),
                'description' => $meta['description'] ?? '',
                'version' => $meta['version'] ?? '',
                'screenshot' => is_file(FCPATH . $shot) ? base_url($shot) : '',
            ];
        }
        return $list;
    }
}

if (!function_exists('theme_view')) {
    /**
     * Resolve a view path (e.g. "pages/home") to the active theme,
     * falling back to the nusantara theme when the file is missing.
     */
    function theme_view(string $view): string
    {
        $view = ltrim(str_replace('\\', '/', $view), '/');
        $t = active_theme();
        if ($t !== 'nusantara'
            && is_file(APPPATH . 'Views/themes/' . $t . '/' . $view . '.php')) {
            return 'themes/' . $t . '/' . $view;
        }
        return 'themes/nusantara/' . $view;
    }
}

if (!function_exists('theme_asset')) {
    /**
     * Resolve a public asset path (e.g. "css/style.min.css") to the
     * active theme, falling back to the nusantara theme.
     */
    function theme_asset(string $path): string
    {
        $path = ltrim($path, '/');
        $t = active_theme();
        if ($t !== 'nusantara' && is_file(FCPATH . 'themes/' . $t . '/' . $path)) {
            return base_url('themes/' . $t . '/' . $path);
        }
        return base_url('themes/nusantara/' . $path);
    }
}

if (!function_exists('theme_og')) {
    /**
     * Open Graph cover image for the active theme (fallback: nusantara).
     */
    function theme_og(): string
    {
        return theme_asset('img/og-cover.png');
    }
}

if (!function_exists('default_spmb_steps')) {
    /**
     * Default SPMB steps (10 steps). Used when the "spmb_steps" setting
     * has not been filled in — each level can customize them via
     * Admin → Settings (SPMB steps section, see spmb_step_presets()).
     *
     * @return array<int, array{title: string, desc: string}>
     */
    function default_spmb_steps(): array
    {
        return [
            ['title' => 'Identitas Diri', 'desc' => 'Nama lengkap, TTL, jenis kelamin'],
            ['title' => 'Alamat Tinggal', 'desc' => 'Alamat, kelurahan, kecamatan, kota, provinsi'],
            ['title' => 'Kontak', 'desc' => 'Telepon, email, kontak darurat'],
            ['title' => 'Data Ayah & Ibu', 'desc' => 'Nama, pekerjaan, pendidikan, penghasilan'],
            ['title' => 'Data Wali', 'desc' => 'Nama, alamat, hubungan, kontak'],
            ['title' => 'Asal Sekolah', 'desc' => 'Nama sekolah, alamat, NISN'],
            ['title' => 'Pilihan Program', 'desc' => 'Pilih program/jurusan yang diminati'],
            ['title' => 'Input Nilai Rapor', 'desc' => 'Isi nilai sesuai mata pelajaran'],
            ['title' => 'Upload Dokumen', 'desc' => 'Foto, ijazah, KK, dokumen pendukung'],
            ['title' => 'Finalisasi & Cetak PDF', 'desc' => 'Periksa kembali lalu cetak formulir'],
        ];
    }
}

if (!function_exists('spmb_step_presets')) {
    /**
     * SPMB step presets per level for the "Quick customize" button on the
     * admin page. Only the steps specific to each level are different
     * (program & report grades); the other steps match the defaults.
     *
     * @return array<string, array<int, array{title: string, desc: string}>>
     */
    function spmb_step_presets(): array
    {
        $base = default_spmb_steps();

        $sd = $base;
        $sd[6] = ['title' => 'Pilihan Layanan', 'desc' => 'Pilih layanan yang diminati: reguler, tahfidz, atau bilingual'];
        $sd[7] = ['title' => 'Input Nilai Rapor', 'desc' => 'Isi nilai rapor tematik semester 1–5 (tema 1–8)'];

        $smp = $base;
        $smp[6] = ['title' => 'Pilihan Program', 'desc' => 'Pilih program unggulan: sains, tahfidz, bahasa, atau olahraga'];
        $smp[7] = ['title' => 'Input Nilai Rapor', 'desc' => 'Isi nilai rapor semester 1–5 sesuai mata pelajaran'];

        return ['SD' => $sd, 'SMP' => $smp, 'SMA' => $base];
    }
}

if (!function_exists('resolve_spmb_steps')) {
    /**
     * Get the SPMB steps from the setting (JSON) or return the defaults.
     * Rows without a title are dropped; maximum 12 steps.
     *
     * @return array<int, array{title: string, desc: string}>
     */
    function resolve_spmb_steps(?string $json): array
    {
        if (is_string($json) && $json !== '') {
            $rows = json_decode($json, true);
            if (is_array($rows)) {
                $out = [];
                foreach (array_slice(array_values($rows), 0, 12) as $r) {
                    $t = trim((string)($r['title'] ?? ''));
                    if ($t === '') {
                        continue;
                    }
                    $out[] = ['title' => $t, 'desc' => trim((string)($r['desc'] ?? ''))];
                }
                if (!empty($out)) {
                    return $out;
                }
            }
        }
        return default_spmb_steps();
    }
}

if (!function_exists('theme_brand_text')) {
    /**
     * Brand text in the navbar: the `site_logo_text` setting when filled in.
     * Read directly from the setting (not from a view variable) because each
     * controller uses a different default placeholder.
     */
    function theme_brand_text(): string
    {
        static $text = null;
        if ($text !== null) {
            return $text;
        }

        try {
            $logo = new SettingModel()->get('site_logo_text');
        } catch (Throwable) {
            $logo = null;
        }

        $text = is_string($logo) ? trim($logo) : '';

        return $text;
    }
}

if (!function_exists('theme_setting_links')) {
    /**
     * Get the footer link list from the JSON settings (`footer_services` /
     * `footer_links`). Entries without a label are dropped.
     *
     * @return list<array{label: string, url: string}>
     */
    function theme_setting_links(string $key): array
    {
        try {
            $raw = new SettingModel()->get($key);
        } catch (Throwable) {
            return [];
        }

        $rows = is_string($raw) && $raw !== '' ? json_decode($raw, true) : null;
        if (!is_array($rows)) {
            return [];
        }

        $out = [];
        foreach (array_slice($rows, 0, 10) as $row) {
            if (!is_array($row)) {
                continue;
            }
            $label = trim((string)($row['label'] ?? ''));
            if ($label === '') {
                continue;
            }
            $out[] = ['label' => $label, 'url' => trim((string)($row['url'] ?? ''))];
        }

        return $out;
    }
}

if (!function_exists('theme_banner')) {
    /**
     * Get the banner for a single page from the `page_banners` setting
     * (keys: news, single_post, pages, contact, downloads).
     *
     * @return array{badge: string, title: string, subtitle: string}
     */
    function theme_banner(string $key): array
    {
        static $all = null;
        if ($all === null) {
            try {
                $raw = new SettingModel()->get('page_banners');
            } catch (Throwable) {
                $raw = null;
            }
            $decoded = is_string($raw) && $raw !== '' ? json_decode($raw, true) : null;
            $all = is_array($decoded) ? $decoded : [];
        }

        $b = is_array($all[$key] ?? null) ? $all[$key] : [];
        $str = static fn($v): string => is_string($v) ? $v : '';

        return [
            'badge' => $str($b['badge'] ?? null),
            'title' => $str($b['title'] ?? null),
            'subtitle' => $str($b['subtitle'] ?? null),
        ];
    }
}

if (!function_exists('theme_nav_url')) {
    /**
     * Prepare a setting link URL for the href attribute: anchors & paths are kept,
     * relative paths ("program") are converted with base_url(), and unknown
     * schemes (including javascript:/data:) become "#". Values must still be
     * escaped when printed.
     */
    function theme_nav_url(?string $url): string
    {
        $url = trim((string)$url);
        if ($url === '' || is_dangerous_url($url)) {
            return '#';
        }

        if (str_starts_with($url, '#') || str_starts_with($url, '/')) {
            return $url;
        }

        if (preg_match('#^([a-z][a-z0-9+.\-]*):#i', $url, $m)) {
            return in_array(strtolower($m[1]), ['http', 'https', 'mailto', 'tel'], true) ? $url : '#';
        }

        return base_url($url);
    }
}

if (!function_exists('program_link')) {
    /**
     * Determine the destination URL for program cards on the frontend.
     *
     * Uses explicit custom `link_url` when set (other than bare 'program'),
     * otherwise links directly to `/program/{slug}` (or ID).
     */
    function program_link(array $program, string $fallback = '#spmb'): string
    {
        $url = trim((string)($program['link_url'] ?? ''));
        $norm = trim($url, '/');
        if ($url !== '' && $norm !== 'program') {
            if (preg_match('#^https?://#i', $url)) {
                return $url;
            }
            if (str_starts_with($url, '#')) {
                return base_url('/') . $url;
            }
            return base_url(ltrim($url, '/'));
        }

        $slug = trim((string)($program['slug'] ?? ''));
        if ($slug !== '') {
            return base_url('program/' . $slug);
        }

        $id = (int)($program['id'] ?? 0);
        if ($id > 0) {
            return base_url('program/' . $id);
        }

        return base_url(ltrim($fallback, '/'));
    }
}

if (!function_exists('program_image_url')) {
    /**
     * Return resolved photo URL for a program when visual mode is set to 'image'.
     */
    function program_image_url(array $program): string
    {
        if (($program['media_type'] ?? 'icon') !== 'image') {
            return '';
        }

        $raw = trim((string)($program['image'] ?? ''));
        if ($raw === '') {
            return '';
        }

        if (preg_match('#^https?://(?:localhost|127\.0\.0\.1)(?::\d+)?/(.*)$#i', $raw, $m)) {
            return base_url(ltrim($m[1], '/'));
        }

        if (preg_match('#^https?://#i', $raw) || str_starts_with($raw, 'data:')) {
            return $raw;
        }

        $clean = ltrim($raw, '/');
        if (str_starts_with($clean, 'uploads/') || str_starts_with($clean, 'media_library/') || str_starts_with($clean, 'themes/')) {
            return base_url($clean);
        }

        return base_url('uploads/programs/' . $clean);
    }
}

if (!function_exists('program_preset_curriculum')) {
    /**
     * Provide structured curriculum, career prospect, and overview defaults
     * when optional program detail fields have not been customized yet.
     */
    function program_preset_curriculum(string $title, string $description = ''): array
    {
        $t = strtolower(trim($title));

        if (str_contains($t, 'tkj') || str_contains($t, 'komputer dan jaringan') || str_contains($t, 'jaringan')) {
            return [
                'learning_points' => [
                    'Perakitan, perawatan, dan troubleshooting komputer (PC & perangkat keras)',
                    'Instalasi sistem operasi Windows & Linux Server serta manajemen layanan jaringan',
                    'Perancangan topologi LAN, WAN, routing switching (MikroTik & Cisco)',
                    'Instalasi kabel Fiber Optik (FTTH), pengkabelan terstruktur, dan Wi-Fi enterprise',
                    'Keamanan jaringan (Firewall, VPN, manajemen bandwidth) dan komputasi awan dasar',
                ],
                'career_prospects' => [
                    'Network Administrator & Teknisi Jaringan',
                    'IT Support / Helpdesk & Infrastructure Engineer',
                    'Teknisi Fiber Optik & Telekomunikasi',
                    'System Administrator & Cloud Junior',
                    'Wirausaha Jasa Servis & Instalasi IT',
                ],
                'overview' => $description !== '' ? $description : 'Program Keahlian Teknik Komputer dan Jaringan membekali peserta didik dengan penguasaan perakitan komputer, administrasi jaringan lokal & luas, konfigurasi server, hingga keamanan jaringan modern.',
            ];
        }

        if (str_contains($t, 'rpl') || str_contains($t, 'pplg') || str_contains($t, 'perangkat lunak') || str_contains($t, 'gim')) {
            return [
                'learning_points' => [
                    'Algoritma pemrograman, struktur data, dan pemrograman berorientasi objek',
                    'Pengembangan aplikasi web full-stack (HTML, CSS, JavaScript, PHP, database SQL)',
                    'Perancangan antarmuka pengguna (UI/UX) dan pembuatan aplikasi mobile dasar',
                    'Pengelolaan repositori Git, pengujian aplikasi, dan praktik kerja proyek perangkat lunak',
                ],
                'career_prospects' => [
                    'Full-Stack / Front-End / Back-End Web Developer Junior',
                    'Mobile App Developer & UI/UX Designer',
                    'Quality Assurance & Database Administrator Junior',
                ],
                'overview' => $description !== '' ? $description : 'Program Rekayasa Perangkat Lunak melatih siswa merancang, membangun, dan menguji aplikasi web maupun mobile sesuai kebutuhan industri teknologi.',
            ];
        }

        if (str_contains($t, 'dkv') || str_contains($t, 'multimedia') || str_contains($t, 'desain komunikasi')) {
            return [
                'learning_points' => [
                    'Dasar tipografi, teori warna, ilustrasi digital, dan identitas visual brand',
                    'Desain grafis berbasis vektor dan raster menggunakan perangkat lunak industri',
                    'Fotografi studio, videografi, tata cahaya, dan penyuntingan audio-visual',
                    'Produksi konten kreatif media sosial, animasi 2D/3D, dan portofolio karya',
                ],
                'career_prospects' => [
                    'Graphic Designer & Brand Visual Specialist',
                    'Video Editor, Videografer & Motion Designer',
                    'Social Media Creative & Studio Freelancer',
                ],
                'overview' => $description !== '' ? $description : 'Program Desain Komunikasi Visual memadukan kreativitas estetika dengan penguasaan produksi grafis, fotografi, dan multimedia digital.',
            ];
        }

        if (str_contains($t, 'akl') || str_contains($t, 'akuntansi') || str_contains($t, 'keuangan')) {
            return [
                'learning_points' => [
                    'Siklus akuntansi perusahaan jasa, dagang, manufaktur, serta lembaga keuangan',
                    'Pengolahan angka dengan Microsoft Excel lanjutan dan aplikasi akuntansi (MYOB / Accurate)',
                    'Administrasi perpajakan, penyusunan laporan laba rugi, neraca, dan arus kas',
                    'Etika profesi keuangan, audit dasar, serta pengelolaan kas dan perbankan mikro',
                ],
                'career_prospects' => [
                    'Staf Akuntansi & Keuangan Perusahaan',
                    'Operator Perpajakan & Pembukuan UMKM',
                    'Teller / Customer Service Perbankan & Lembaga Keuangan',
                ],
                'overview' => $description !== '' ? $description : 'Program Akuntansi dan Keuangan Lembaga menyiapkan lulusan yang teliti mengelola pembukuan, laporan keuangan, perpajakan, dan perangkat lunak akuntansi.',
            ];
        }

        if (str_contains($t, 'mplb') || str_contains($t, 'otkp') || str_contains($t, 'perkantoran') || str_contains($t, 'administrasi')) {
            return [
                'learning_points' => [
                    'Korespondensi bisnis bahasa Indonesia & Inggris serta tata kelola surat-menyurat digital',
                    'Pengelolaan kearsipan modern, administrasi kepegawaian, dan manajemen jadwal pimpinan',
                    'Penguasaan aplikasi perkantoran terpadu dan sistem informasi manajemen kantor',
                    'Etika layanan prima (customer care), public speaking, dan manajemen rapat',
                ],
                'career_prospects' => [
                    'Staf Administrasi Perkantoran & Sekretaris Junior',
                    'Customer Relations & Front Office Executive',
                    'Arsiparis Digital & Staf Human Resources',
                ],
                'overview' => $description !== '' ? $description : 'Program Manajemen Perkantoran dan Layanan Bisnis membekali siswa keterampilan tata kelola administrasi modern, korespondensi, kearsipan digital, dan komunikasi bisnis.',
            ];
        }

        if (str_contains($t, 'bdp') || str_contains($t, 'pemasaran') || str_contains($t, 'bisnis')) {
            return [
                'learning_points' => [
                    'Strategi pemasaran digital, pengelolaan marketplace, dan konten promosi produk',
                    'Teknik komunikasi penjualan, negosiasi bisnis, dan pelayanan pelanggan ritel modern',
                    'Analisis riset pasar, pencatatan transaksi ritel, dan penataan produk (visual merchandising)',
                    'Perencanaan bisnis mandiri (business plan) dan pengelolaan toko daring',
                ],
                'career_prospects' => [
                    'Digital Marketing & E-Commerce Specialist',
                    'Sales Executive & Retail Store Supervisor',
                    'Wirausaha Muda & Pengelola Toko Daring',
                ],
                'overview' => $description !== '' ? $description : 'Program Pemasaran dan Bisnis Digital melatih kecakapan berwirausaha, strategi pemasaran modern, serta pengelolaan niaga ritel dan daring.',
            ];
        }

        if (str_contains($t, 'sains') || str_contains($t, 'teknologi')) {
            return [
                'learning_points' => [
                    'Pendalaman konsep fisika terapan, kimia analisis, dan biologi eksperimental',
                    'Metodologi riset ilmiah, penyusunan karya tulis, dan pengujian hipotesis di laboratorium',
                    'Pemodelan masalah berbasis STEM dan rekayasa alat sederhana',
                    'Pembinaan intensif olimpiade sains dan kompetisi karya ilmiah remaja',
                ],
                'career_prospects' => [
                    'Studi lanjut bidang Sains, Teknik & Kedokteran',
                    'Asisten Riset & Analis Laboratorium Junior',
                    'Peserta Kompetisi Riset & Olimpiade Sains',
                ],
                'overview' => $description !== '' ? $description : 'Pembelajaran sains terpadu dengan pendekatan praktikum laboratorium modern dan riset berbasis proyek.',
            ];
        }

        if (str_contains($t, 'digital') || str_contains($t, 'literacy')) {
            return [
                'learning_points' => [
                    'Dasar algoritma, logika pemrograman, dan pengembangan aplikasi web modern',
                    'Desain antarmuka digital (UI/UX) serta pengolahan grafis vektor dan bitmap',
                    'Pengelolaan dokumen kolaboratif, produktivitas perkantoran, dan literasi data',
                    'Etika bermedia digital, keamanan akun, dan pencegahan risiko siber dasar',
                ],
                'career_prospects' => [
                    'Web Developer & Desainer Grafis Junior',
                    'Operator Digital & Konten Kreator Edukatif',
                    'Wirausaha Produk & Layanan Digital',
                ],
                'overview' => $description !== '' ? $description : 'Pembinaan keterampilan pemrograman dasar, desain grafis, dan kecakapan digital terapan.',
            ];
        }

        return [
            'learning_points' => [
                'Penguasaan kompetensi dasar hingga lanjutan sesuai bidang ' . ($title ?: 'program keahlian'),
                'Praktik terbimbing di ruang kelas maupun laboratorium / ruang praktik',
                'Pengerjaan proyek kolaboratif dan evaluasi portofolio hasil belajar',
                'Pembinaan karakter kerja, kedisiplinan, dan kesiapan studi atau berkarier',
            ],
            'career_prospects' => [
                'Kesiapan Dunia Usaha & Dunia Industri (DUDI)',
                'Melanjutkan Pendidikan Tinggi sesuai Bidang Minat',
                'Wirausaha Mandiri Berbasis Keterampilan',
            ],
            'overview' => $description !== '' ? $description : 'Program pembinaan terstruktur untuk mengembangkan kompetensi, keterampilan praktik, dan kesiapan masa depan siswa.',
        ];
    }
}

if (!function_exists('program_parse_lines')) {
    /**
     * Split multi-line curriculum/prospect text stored in DB into clean items.
     */
    function program_parse_lines(?string $raw): array
    {
        $raw = strtr(trim((string)$raw), ['Â·' => '·', 'â€”' => '—', 'â€“' => '–']);
        if ($raw === '') {
            return [];
        }
        $lines = preg_split('/\r?\n+/', $raw) ?: [];
        $out = [];
        foreach ($lines as $line) {
            $clean = trim(ltrim(trim($line), "-•* \t"));
            if ($clean !== '') {
                $out[] = $clean;
            }
        }
        return $out;
    }
}

if (!function_exists('program_detail_payload')) {
    /**
     * Resolve complete curriculum detail data for one program record.
     */
    function program_detail_payload(array $program): array
    {
        $title = trim((string)($program['title'] ?? 'Program Keahlian'));
        $desc = trim((string)($program['description'] ?? ''));
        $preset = program_preset_curriculum($title, $desc);

        $learning = program_parse_lines($program['learning_points'] ?? null);
        if ($learning === []) {
            $learning = $preset['learning_points'];
        }

        $careers = program_parse_lines($program['career_prospects'] ?? null);
        if ($careers === []) {
            $careers = $preset['career_prospects'];
        }

        $contentHtml = trim((string)($program['content'] ?? ''));
        if ($contentHtml === '') {
            $contentHtml = '<p>' . esc($preset['overview']) . '</p>';
        }

        return [
            'learning_points'  => $learning,
            'career_prospects' => $careers,
            'content_html'     => $contentHtml,
            'image_url'        => program_image_url($program),
        ];
    }
}
