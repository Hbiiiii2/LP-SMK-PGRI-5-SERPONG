<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('settings')->truncate();
        $settings = [
            // Site identity
            ['site_name', 'SMA Nusantara'],
            ['site_tagline', 'Unggul dalam Prestasi, Luhur dalam Budi Pekerti'],
            ['site_description', 'SMA Nusantara adalah sekolah menengah atas unggulan di Jakarta Timur yang memadukan pengajaran disiplin, pembinaan karakter, dan budaya prestasi sejak 1987.'],
            ['site_logo_text', 'SMA Nusantara'],
            ['site_logo_icon', 'graduation-cap'],
            ['site_url', 'https://smanusantara.sch.id'],

            // Contact info
            ['contact_phone', '(021) 8790-4521'],
            ['contact_email', 'info@smanusantara.sch.id'],
            ['contact_address', 'Jl. Cendekia No. 45, Cakung, Jakarta Timur 13910'],
            ['contact_hours', 'Senin–Jumat: 07.00–15.30 WIB · Sabtu: 08.00–11.00 WIB'],

            // Social media
            ['social_facebook', 'https://facebook.com/smanusantara'],
            ['social_instagram', 'https://instagram.com/sma.nusantara'],
            ['social_youtube', 'https://youtube.com/@SMANusantara'],
            ['social_tiktok', 'https://tiktok.com/@smanusantara'],

            // SEO / Meta
            ['meta_author', 'SMA Nusantara'],
            ['meta_keywords', 'sma nusantara, sekolah jakarta timur, kurikulum merdeka, sekolah unggulan, SPMB, akreditasi A'],
            ['meta_description', 'SMA Nusantara adalah SMA unggulan terakreditasi A di Jakarta Timur. Kurikulum Merdeka dengan peminatan MIPA, IPS, dan Bahasa, 96% lulusan diterima PTN.'],

            // Hero section
            ['hero_badge', 'SPMB 2026/2027 · Terakreditasi A'],
            ['hero_title', 'Tumbuh Berilmu, Tegak Berkarakter.'],
            ['hero_subtitle', 'Sejak 1987, SMA Nusantara mendampingi 1.240 siswa bertumbuh lewat pengajaran yang disiplin, pembinaan karakter yang konsisten, dan budaya prestasi yang hidup setiap hari.'],
            ['hero_btn_primary_text', 'Daftar SPMB'],
            ['hero_btn_primary_url', '#spmb'],
            ['hero_btn_secondary_text', 'Jelajahi Sekolah'],
            ['hero_btn_secondary_url', 'profile'],
            ['spmb_url', 'https://spmb.smanusantara.sch.id'],

            // Hero stats (JSON)
            ['hero_stats', json_encode([
                ['label' => 'Siswa Aktif', 'value' => '1240+', 'icon' => 'user-graduate'],
                ['label' => 'Guru & Tendik', 'value' => '86', 'icon' => 'chalkboard-teacher'],
                ['label' => 'Medali & Piala', 'value' => '120+', 'icon' => 'trophy'],
            ])],

            // Principal (JSON)
            ['principal', json_encode([
                'name' => 'Drs. Bambang Sutrisno, M.Pd.',
                'photo' => '/images/default-principal.jpg',
                'role' => 'Kepala Sekolah',
                'role_title' => 'Kepala Sekolah',
                'quote' => 'Setiap anak datang dengan bekal yang berbeda. Tugas kamilah memastikan mereka pulang dengan arah yang sama — menjadi pribadi yang berilmu, jujur, dan berguna bagi sesama.',
                'welcome_message' => 'Assalamu’alaikum warahmatullahi wabarakatuh. Selamat datang di SMA Nusantara. Selama hampir empat dekade, kami memegang tiga hal sederhana: apel pagi yang tertib, guru yang hafal nama setiap siswanya, dan target belajar yang transparan bagi orang tua. Cara-cara sederhana inilah yang mengantar 96% lulusan kami menembus perguruan tinggi negeri. Pintu sekolah ini selalu terbuka — datang, lihat kelas kami, dan berbicaralah dengan guru kami.',
                'sambutan' => 'Assalamu’alaikum warahmatullahi wabarakatuh. Selamat datang di SMA Nusantara. Selama hampir empat dekade, kami memegang tiga hal sederhana: apel pagi yang tertib, guru yang hafal nama setiap siswanya, dan target belajar yang transparan bagi orang tua. Cara-cara sederhana inilah yang mengantar 96% lulusan kami menembus perguruan tinggi negeri. Pintu sekolah ini selalu terbuka — datang, lihat kelas kami, dan berbicaralah dengan guru kami.',
                'education' => 'Magister Manajemen Pendidikan, Universitas Negeri Jakarta',
                'years_of_service' => 'Mengabdi sejak 2015',
            ])],

            // About (JSON)
            ['about', json_encode([
                'image' => '',
                'content_title' => 'Mengenal Lebih Dekat SMA Nusantara',
                'content_1' => 'Didirikan tahun 1987 oleh Yayasan Cendekia Nusantara, SMA Nusantara tumbuh dari tiga ruang kelas di sebuah ruko di Cakung menjadi kampus seluas 2,1 hektar dengan 1.240 siswa dan 86 guru serta tenaga kependidikan. Satu hal yang tidak pernah berubah: setiap anak dikenal namanya.',
                'content_2' => 'Kami memadukan Kurikulum Merdeka dengan pendalaman materi, bimbingan olimpiade, dan kelas persiapan UTBK sejak kelas XI. Di luar kelas, 12 ekstrakurikuler aktif dan program pembiasaan karakter — apel pagi, jurnal refleksi, dan mentoring wali kelas — membentuk lulusan yang siap kuliah, siap kerja, dan siap bermasyarakat.',
                'visi' => 'Menjadi sekolah unggul yang melahirkan lulusan beriman, cerdas, terampil, dan berdaya saing global pada tahun 2030.',
                'misi' => 'Menyelenggarakan pembelajaran Kurikulum Merdeka yang aktif dan bermakna; menanamkan karakter disiplin, jujur, dan peduli melalui pembiasaan harian; mengembangkan potensi siswa lewat peminatan MIPA, IPS, dan Bahasa beserta pembinaan olimpiade dan UTBK; serta menjalin kemitraan erat dengan orang tua dan masyarakat.',
                'accreditation' => 'A',
                'accreditation_label' => 'Unggul — BAN-S/M',
                'highlights' => ['Terakreditasi A oleh BAN-S/M', 'Kurikulum Merdeka dengan peminatan MIPA, IPS, dan Bahasa', 'Rasio guru–siswa 1:15, 61% guru bergelar magister', '96% lulusan 2025 diterima perguruan tinggi negeri', 'Lab IPA, perpustakaan digital, sport hall, dan makerspace robotik'],
            ])],

            // Section settings (JSON)
            ['section_settings', json_encode([
                'hero' => ['title' => 'Tumbuh Berilmu, Tegak Berkarakter', 'subtitle' => 'Profil SMA Nusantara — SMA unggulan terakreditasi A di Jakarta Timur', 'icon' => 'fa-school', 'show' => true],
                'profile' => ['title' => 'Profil Sekolah', 'subtitle' => 'Mengenal lebih dekat sejarah, visi-misi, dan budaya SMA Nusantara', 'icon' => 'fa-building-columns', 'show' => true],
                'programs' => ['title' => 'Program Unggulan', 'subtitle' => 'Peminatan MIPA, IPS, dan Bahasa dengan pendampingan olimpiade dan UTBK', 'icon' => 'fa-gem', 'show' => true],
                'extracurriculars' => ['title' => 'Ekstrakurikuler', 'subtitle' => '12 kegiatan siswa — dari robotik dan paskibra hingga teater dan jurnalistik', 'icon' => 'fa-futbol', 'show' => true],
                'teachers' => ['title' => 'Tenaga Pengajar', 'subtitle' => '86 guru dan tendik — 100% sarjana, 61% bergelar magister', 'icon' => 'fa-chalkboard-user', 'show' => true],
                'achievements' => ['title' => 'Prestasi Siswa', 'subtitle' => '120+ medali dan piala — dari OSN dan robotik hingga tahfidz dan pencak silat', 'icon' => 'fa-trophy', 'show' => true],
                'testimonials' => ['title' => 'Testimoni', 'subtitle' => 'Apa kata siswa, alumni, dan orang tua tentang SMA Nusantara?', 'icon' => 'fa-comment', 'show' => true],
                'news' => ['title' => 'Berita & Artikel', 'subtitle' => 'Kabar kampus, prestasi, dan pengumuman SMA Nusantara', 'icon' => 'fa-newspaper', 'show' => true],
                'events' => ['title' => 'Agenda & Kegiatan', 'subtitle' => 'Kalender resmi — UTS, open house, Cakrawala Cup, dan pentas seni', 'icon' => 'fa-calendar-days', 'show' => true],
                'gallery' => ['title' => 'Galeri', 'subtitle' => 'Suasana kampus, laboratorium, dan panggung prestasi', 'icon' => 'fa-images', 'show' => true],
                'faq' => ['title' => 'FAQ', 'subtitle' => 'Jawaban atas pertanyaan seputar SPMB, biaya, dan kurikulum', 'icon' => 'fa-circle-question', 'show' => true],
                'downloads' => ['title' => 'Download Center', 'subtitle' => 'Brosur SPMB, formulir pendaftaran, kalender akademik, dan tata tertib', 'icon' => 'fa-download', 'show' => true],
                'contact' => ['title' => 'Kontak Kami', 'subtitle' => 'Jl. Cendekia No. 45, Cakung — balasan maks. 1×24 jam hari kerja', 'icon' => 'fa-address-card', 'show' => true],
            ])],

            // Page banners (JSON)
            ['page_banners', json_encode([
                'news' => ['badge' => 'Berita & Artikel', 'title' => 'Kabar Kampus', 'subtitle' => 'Ditulis tim jurnalistik siswa, dikurasi kesiswaan — terbit setiap Senin dan Kamis'],
                'single_post' => ['badge' => 'Detail Berita', 'title' => 'Baca Berita', 'subtitle' => 'Liputan lengkap kegiatan dan prestasi warga SMA Nusantara'],
                'pages' => ['badge' => 'Halaman', 'title' => 'Baca Halaman', 'subtitle' => 'Informasi resmi SMA Nusantara'],
                'downloads' => ['badge' => 'Download Center', 'title' => 'Dokumen Resmi', 'subtitle' => 'Brosur SPMB, formulir, kalender akademik, dan tata tertib — selalu versi terbaru'],
            ])],

            // Footer
            ['footer_description', 'SMA Nusantara — sekolah menengah atas unggulan di Cakung, Jakarta Timur. Berdiri sejak 1987 di bawah Yayasan Cendekia Nusantara.'],
            ['footer_copyright', '© 2026 SMA Nusantara — All rights reserved.'],
            ['footer_services', json_encode([
                ['label' => 'MIPA Unggulan', 'url' => 'program'],
                ['label' => 'IPS & Kewirausahaan', 'url' => 'program'],
                ['label' => 'Bahasa & Budaya', 'url' => 'program'],
                ['label' => 'Bimbingan UTBK', 'url' => 'program'],
                ['label' => 'Beasiswa Prestasi', 'url' => 'achievements'],
            ])],
            ['footer_links', json_encode([
                ['label' => 'SPMB Online', 'url' => 'https://spmb.smanusantara.sch.id'],
                ['label' => 'E-Learning', 'url' => '#'],
                ['label' => 'Perpustakaan Digital', 'url' => '#'],
                ['label' => 'FAQ', 'url' => 'faq'],
                ['label' => 'Kontak', 'url' => 'contact'],
            ])],

            // Theme
            ['active_theme', 'nusantara'],

            // Counter stats (JSON)
            ['counter_stats', json_encode([
                ['icon' => 'user-graduate', 'number' => '1240', 'suffix' => '+', 'label' => 'Siswa Aktif'],
                ['icon' => 'chalkboard-teacher', 'number' => '86', 'suffix' => '', 'label' => 'Guru & Tendik'],
                ['icon' => 'trophy', 'number' => '120', 'suffix' => '+', 'label' => 'Medali & Piala'],
                ['icon' => 'graduation-cap', 'number' => '96', 'suffix' => '%', 'label' => 'Lulusan Diterima PTN'],
            ])],
            // SPMB steps (JSON) — tahapan pendaftaran, bisa beda per jenjang
            ['spmb_steps', json_encode(array_map(
                fn($s) => ['title' => $s['title'], 'desc' => $s['desc']],
                default_spmb_steps()
            ))],
        ];

        $this->db->table('settings')->insertBatch(
            array_map(fn($s) => ['key' => $s[0], 'value' => $s[1]], $settings)
        );
    }
}
