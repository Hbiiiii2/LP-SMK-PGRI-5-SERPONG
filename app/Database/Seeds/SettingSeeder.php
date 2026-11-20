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
            ['site_name', 'SMK PGRI 5 SERPONG'],
            ['site_tagline', 'Unggul dalam Prestasi, Luhur dalam Budi Pekerti'],
            ['site_description', 'SMK PGRI 5 Serpong merupakan lembaga pendidikan kejuruan swasta terakreditasi A (NPSN 20614731) yang berdiri sejak 20 Desember 2007 di Kota Tangerang Selatan di bawah naungan YPLP PGRI Provinsi Banten. Berkomitmen mencetak lulusan vokasi yang kompeten, berkarakter IMTAQ & IPTEK, serta siap kerja maupun berwirausaha.'],
            ['site_logo_text', 'SMK PGRI 5 SERPONG'],
            ['site_logo_icon', 'graduation-cap'],
            ['site_url', 'https://smkpgri5serpong.sch.id'],

            // Contact info
            ['contact_phone', '(021) 7560-5421'],
            ['contact_email', 'info@smkpgri5serpong.sch.id'],
            ['contact_address', 'Jl. Raya Puspitek Serpong, RT. 05 / RW. 04, Kel. Serpong, Kec. Serpong, Kota Tangerang Selatan, Banten 15310'],
            ['contact_hours', 'Senin – Sabtu, 07.00 – 17.00 WIB'],

            // Social media
            ['social_facebook', ''],
            ['social_instagram', 'https://www.instagram.com/smkpgri5serpong/'],
            ['social_youtube', ''],
            ['social_tiktok', ''],

            // SEO / Meta
            ['meta_author', 'SMK PGRI 5 Serpong'],
            ['meta_keywords', 'SMK PGRI 5 Serpong, SMK Tangerang Selatan, SMK Serpong, SPMB SMK PGRI 5 Serpong, TKJ, RPL, MPLB, AKL, Pemasaran, akreditasi A, YPLP PGRI Banten'],
            ['meta_description', 'Website resmi SMK PGRI 5 Serpong (NPSN 20614731), sekolah menengah kejuruan terakreditasi A di Serpong, Kota Tangerang Selatan. Program keahlian TKJ, RPL, MPLB, AKL, dan Bisnis Digital.'],

            // Hero section
            ['hero_badge', 'SPMB 2026/2027 · Terakreditasi A'],
            ['hero_title', 'Vokasi Unggul, Siap Kerja & Berkarakter.'],
            ['hero_subtitle', 'Sejak 2007, SMK PGRI 5 Serpong di bawah naungan YPLP PGRI Banten membina generasi muda Tangerang Selatan melalui pendidikan kejuruan berbasis IMTAQ, IPTEK, praktik industri, dan sertifikasi kompetensi.'],
            ['hero_btn_primary_text', 'Daftar SPMB Online'],
            ['hero_btn_primary_url', 'spmb'],
            ['hero_btn_secondary_text', 'Profil Sekolah'],
            ['hero_btn_secondary_url', 'profile'],
            ['spmb_url', 'https://smkpgri5serpong.sch.id/spmb'],

            // Hero stats (JSON)
            ['hero_stats', json_encode([
                ['label' => 'Siswa Aktif', 'value' => '500+', 'icon' => 'user-graduate'],
                ['label' => 'Guru & Tendik', 'value' => '32', 'icon' => 'chalkboard-teacher'],
                ['label' => 'Mitra Industri', 'value' => '40+', 'icon' => 'briefcase'],
            ])],

            // Principal (JSON)
            ['principal', json_encode([
                'name' => 'Kepala SMK PGRI 5 Serpong',
                'photo' => 'https://smkpgri5serpong.sch.id/uploads/principal/1794538587_5c485468af07a9b0.jpg',
                'role' => 'Kepala Sekolah',
                'role_title' => 'Kepala Sekolah',
                'quote' => 'Pendidikan kejuruan bukan sekadar mengajarkan teori, melainkan membentuk keterampilan nyata, etos kerja disiplin, dan akhlak mulia agar setiap lulusan siap melangkah ke dunia industri maupun perguruan tinggi.',
                'welcome_message' => 'Assalamu’alaikum warahmatullahi wabarakatuh. Selamat datang di portal resmi SMK PGRI 5 Serpong. Sejak berdiri pada tahun 2007 di Serpong, Kota Tangerang Selatan, kami terus berkomitmen menyelenggarakan pendidikan kejuruan yang selaras dengan kebutuhan Dunia Usaha dan Dunia Industri (DUDI). Dengan dukungan tenaga pendidik profesional, laboratorium praktik, program Praktik Kerja Lapangan (PKL), serta pembinaan karakter berbasis IMTAQ dan IPTEK, kami siap mendampingi putra-putri Anda meraih masa depan yang gemilang.',
                'sambutan' => 'Assalamu’alaikum warahmatullahi wabarakatuh. Selamat datang di portal resmi SMK PGRI 5 Serpong. Sejak berdiri pada tahun 2007 di Serpong, Kota Tangerang Selatan, kami terus berkomitmen menyelenggarakan pendidikan kejuruan yang selaras dengan kebutuhan Dunia Usaha dan Dunia Industri (DUDI). Dengan dukungan tenaga pendidik profesional, laboratorium praktik, program Praktik Kerja Lapangan (PKL), serta pembinaan karakter berbasis IMTAQ dan IPTEK, kami siap mendampingi putra-putri Anda meraih masa depan yang gemilang.',
                'education' => 'Magister Manajemen Pendidikan',
                'years_of_service' => 'SMK PGRI 5 Serpong · YPLP PGRI Banten',
            ])],

            // About (JSON)
            ['about', json_encode([
                'image' => 'https://smkpgri5serpong.sch.id/uploads/about/1794538800_7e4c553dc4762e3c.png',
                'content_title' => 'Sejarah & Profil SMK PGRI 5 Serpong',
                'content_1' => 'SMK PGRI 5 Serpong resmi didirikan pada 20 Desember 2007 berdasarkan SK Pendirian No. 421.5/072.1/DIS P&K/2007 dengan NPSN 20614731. Bernaung di bawah Yayasan Pembina Lembaga Pendidikan (YPLP) PGRI Provinsi Banten dan berlokasi strategis di Jl. Raya Puspitek Serpong, Kota Tangerang Selatan, sekolah ini hadir menjawab kebutuhan tenaga terampil tingkat menengah di kawasan industri dan teknologi Tangerang Selatan.',
                'content_2' => 'Berbekal Akreditasi A (Sangat Baik) dari BAN-S/M, SMK PGRI 5 Serpong menerapkan Kurikulum Merdeka berbasis proyek dan praktik bengkel/laboratorium nyata. Siswa dibekali kompetensi keahlian di bidang Teknologi Informasi maupun Bisnis & Manajemen, Praktik Kerja Lapangan (PKL) bersama mitra DUDI, Uji Kompetensi Keahlian (UKK), serta pembinaan karakter melalui ekstrakurikuler aktif.',
                'visi' => 'Terwujudnya lulusan SMK PGRI 5 Serpong yang beriman dan bertakwa (IMTAQ), menguasai ilmu pengetahuan dan teknologi (IPTEK), terampil, mandiri, berakhlak mulia, serta berdaya saing di dunia kerja dan wirausaha.',
                'misi' => 'Menyelenggarakan pembelajaran vokasi berbasis Kurikulum Merdeka yang selaras dengan standar Dunia Usaha dan Dunia Industri (DUDI); meningkatkan kualitas praktik laboratorium dan sertifikasi kompetensi siswa; menanamkan budaya disiplin, jujur, dan religius dalam kehidupan sekolah; serta memperluas kemitraan industri untuk penyaluran lulusan dan program PKL.',
                'accreditation' => 'A',
                'accreditation_label' => 'Terakreditasi A — BAN-S/M (SK 755/BAN-SM/SK/2019)',
                'highlights' => [
                    'Terakreditasi A (Sangat Baik) oleh BAN-S/M · NPSN 20614731',
                    'Berdiri sejak 20 Desember 2007 di bawah YPLP PGRI Provinsi Banten',
                    'Program Keahlian relevan industri: TKJ, RPL, MPLB, AKL, dan Bisnis Digital',
                    'Praktik Kerja Lapangan (PKL) & kemitraan aktif dengan 40+ DUDI di Tangerang Selatan',
                    'Uji Kompetensi Keahlian (UKK), Bursa Kerja Khusus (BKK), dan pembinaan karakter IMTAQ & IPTEK',
                ],
            ])],

            // Section settings (JSON)
            ['section_settings', json_encode([
                'hero' => ['title' => 'Vokasi Unggul, Siap Kerja & Berkarakter', 'subtitle' => 'SMK PGRI 5 Serpong — SMK swasta terakreditasi A di Kota Tangerang Selatan', 'icon' => 'fa-school', 'show' => true],
                'profile' => ['title' => 'Profil & Sejarah Sekolah', 'subtitle' => 'Mengenal perjalanan SMK PGRI 5 Serpong sejak 2007, visi-misi, dan budaya belajar', 'icon' => 'fa-building-columns', 'show' => true],
                'programs' => ['title' => 'Program Keahlian', 'subtitle' => 'Pilihan jurusan vokasi unggulan di bidang Teknologi Informasi serta Bisnis & Manajemen', 'icon' => 'fa-gem', 'show' => true],
                'extracurriculars' => ['title' => 'Ekstrakurikuler', 'subtitle' => 'Wadah pengembangan bakat, kepemimpinan, teknologi, olahraga, dan karakter siswa', 'icon' => 'fa-futbol', 'show' => true],
                'teachers' => ['title' => 'Tenaga Pendidik', 'subtitle' => 'Guru produktif, adaptif, dan normatif yang berpengalaman mendampingi siswa vokasi', 'icon' => 'fa-chalkboard-user', 'show' => true],
                'achievements' => ['title' => 'Prestasi Siswa', 'subtitle' => 'Capaian siswa SMK PGRI 5 Serpong di LKS, akademik, olahraga, dan seni', 'icon' => 'fa-trophy', 'show' => true],
                'testimonials' => ['title' => 'Testimoni', 'subtitle' => 'Kisah nyata siswa, alumni, dan orang tua bersama SMK PGRI 5 Serpong', 'icon' => 'fa-comment', 'show' => true],
                'news' => ['title' => 'Berita & Pengumuman', 'subtitle' => 'Informasi kegiatan sekolah, prestasi LKS, PKL, dan pengumuman resmi SPMB', 'icon' => 'fa-newspaper', 'show' => true],
                'events' => ['title' => 'Agenda Sekolah', 'subtitle' => 'Kalender kegiatan resmi — UKK, pembekalan PKL, SPMB, dan peringatan hari besar', 'icon' => 'fa-calendar-days', 'show' => true],
                'gallery' => ['title' => 'Galeri Kegiatan', 'subtitle' => 'Dokumentasi praktik laboratorium, kegiatan siswa, dan suasana kampus Serpong', 'icon' => 'fa-images', 'show' => true],
                'faq' => ['title' => 'Tanya Jawab (FAQ)', 'subtitle' => 'Jawaban atas pertanyaan umum seputar SPMB, jurusan, PKL, dan biaya pendidikan', 'icon' => 'fa-circle-question', 'show' => true],
                'downloads' => ['title' => 'Pusat Unduhan', 'subtitle' => 'Brosur SPMB, formulir pendaftaran, kalender akademik, dan panduan PKL', 'icon' => 'fa-download', 'show' => true],
                'contact' => ['title' => 'Kontak Kami', 'subtitle' => 'Jl. Raya Puspitek Serpong, Tangerang Selatan — layanan informasi hari kerja', 'icon' => 'fa-address-card', 'show' => true],
            ])],

            // Page banners (JSON)
            ['page_banners', json_encode([
                'news' => ['badge' => 'Berita & Artikel', 'title' => 'Kabar SMK PGRI 5 Serpong', 'subtitle' => 'Liputan kegiatan praktik, prestasi siswa, kerja sama industri, dan pengumuman sekolah'],
                'single_post' => ['badge' => 'Detail Berita', 'title' => 'Baca Berita', 'subtitle' => 'Informasi lengkap kegiatan dan prestasi warga SMK PGRI 5 Serpong'],
                'pages' => ['badge' => 'Halaman Informasi', 'title' => 'Informasi Resmi', 'subtitle' => 'Informasi resmi SMK PGRI 5 Serpong, Kota Tangerang Selatan'],
                'downloads' => ['badge' => 'Pusat Unduhan', 'title' => 'Dokumen Resmi', 'subtitle' => 'Brosur SPMB, formulir, kalender akademik, dan panduan PKL — selalu versi terbaru'],
            ])],

            // Footer
            ['footer_description', 'SMK PGRI 5 Serpong — Sekolah Menengah Kejuruan terakreditasi A (NPSN 20614731) di Kota Tangerang Selatan. Berdiri sejak 2007 di bawah naungan YPLP PGRI Provinsi Banten.'],
            ['footer_copyright', '© 2026 SMK PGRI 5 Serpong — Seluruh hak cipta dilindungi.'],
            ['footer_services', json_encode([
                ['label' => 'Teknik Komputer & Jaringan', 'url' => 'program/teknik-komputer-dan-jaringan'],
                ['label' => 'Rekayasa Perangkat Lunak', 'url' => 'program/rekayasa-perangkat-lunak'],
                ['label' => 'Manajemen Perkantoran (MPLB)', 'url' => 'program/manajemen-perkantoran-dan-layanan-bisnis'],
                ['label' => 'Akuntansi & Keuangan (AKL)', 'url' => 'program/akuntansi-dan-keuangan-lembaga'],
                ['label' => 'Bisnis Digital / Pemasaran', 'url' => 'program/bisnis-digital-dan-pemasaran'],
            ])],
            ['footer_links', json_encode([
                ['label' => 'SPMB Online', 'url' => 'spmb'],
                ['label' => 'Profil Sekolah', 'url' => 'profile'],
                ['label' => 'Pusat Unduhan', 'url' => 'downloads'],
                ['label' => 'FAQ', 'url' => 'faq'],
                ['label' => 'Kontak', 'url' => 'contact'],
            ])],

            // Theme
            ['active_theme', 'smkpgri5'],

            // Counter stats (JSON)
            ['counter_stats', json_encode([
                ['icon' => 'user-graduate', 'number' => '500', 'suffix' => '+', 'label' => 'Siswa Aktif'],
                ['icon' => 'chalkboard-teacher', 'number' => '32', 'suffix' => '', 'label' => 'Guru & Tendik'],
                ['icon' => 'briefcase', 'number' => '40', 'suffix' => '+', 'label' => 'Mitra Industri (DUDI)'],
                ['icon' => 'award', 'number' => '18', 'suffix' => '+', 'label' => 'Tahun Mengabdi'],
            ])],

            // SPMB steps (JSON)
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
