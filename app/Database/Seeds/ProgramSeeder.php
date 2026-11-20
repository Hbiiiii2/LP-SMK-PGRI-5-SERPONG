<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProgramSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('programs')->truncate();
        $data = [
            [
                'title'            => 'Teknik Komputer dan Jaringan',
                'slug'             => 'teknik-komputer-dan-jaringan',
                'description'      => 'Membekali siswa keahlian instalasi perangkat keras, administrasi jaringan LAN/WAN, konfigurasi MikroTik/Cisco, server Linux, dan teknologi fiber optik.',
                'content'          => '<p>Program Keahlian <strong>Teknik Komputer dan Jaringan (TKJ)</strong> SMK PGRI 5 Serpong dirancang untuk mencetak teknisi jaringan dan infrastruktur IT yang siap kerja. Siswa belajar langsung di laboratorium komputer dan jaringan melalui praktik perakitan PC, instalasi sistem operasi server, konfigurasi router MikroTik, pengkabelan fiber optik, hingga pengamanan jaringan.</p><p>Selain praktik di sekolah, siswa TKJ mengikuti Praktik Kerja Lapangan (PKL) di perusahaan IT, ISP, dan instansi perkantoran di kawasan Serpong dan Tangerang Selatan serta mengikuti Uji Kompetensi Keahlian (UKK) sebagai bekal sertifikasi industri.</p>',
                'learning_points'  => implode("\n", [
                    'Perakitan, perawatan, dan troubleshooting perangkat keras komputer (PC & laptop)',
                    'Instalasi dan administrasi sistem operasi Windows Server & Linux Server',
                    'Perancangan topologi jaringan LAN, WAN, routing, dan switching (MikroTik & Cisco)',
                    'Instalasi kabel Fiber Optik (FTTH), pengkabelan terstruktur, dan manajemen Wi-Fi',
                    'Keamanan jaringan (Firewall, VPN, manajemen bandwidth) dan dasar cloud computing',
                ]),
                'career_prospects' => implode("\n", [
                    'Network Administrator & Teknisi Jaringan',
                    'IT Support / Helpdesk & Infrastructure Engineer',
                    'Teknisi Fiber Optik & Telekomunikasi (ISP)',
                    'System Administrator Junior',
                    'Wirausaha Jasa Servis Komputer & Instalasi Jaringan',
                ]),
                'icon'             => 'network',
                'media_type'       => 'icon',
                'image'            => null,
                'sort_order'       => 1,
            ],
            [
                'title'            => 'Rekayasa Perangkat Lunak',
                'slug'             => 'rekayasa-perangkat-lunak',
                'description'      => 'Fokus pada pemrograman web, aplikasi mobile, perancangan basis data, UI/UX design, serta pengembangan proyek perangkat lunak sesuai standar industri.',
                'content'          => '<p>Program Keahlian <strong>Rekayasa Perangkat Lunak (RPL)</strong> SMK PGRI 5 Serpong membekali siswa dengan logika pemrograman modern dari tingkat dasar hingga pembuatan aplikasi siap pakai. Siswa dilatih membangun aplikasi web responsif, merancang database relasional, memahami desain antarmuka (UI/UX), serta berkolaborasi menggunakan Git.</p><p>Pembelajaran berbasis proyek nyata (Project-Based Learning) memastikan setiap lulusan memiliki portofolio aplikasi sendiri untuk melamar kerja di industri teknologi, membuka jasa freelance, atau melanjutkan studi di bidang Informatika dan Sistem Informasi.</p>',
                'learning_points'  => implode("\n", [
                    'Algoritma, struktur data, dan pemrograman berorientasi objek (OOP)',
                    'Pengembangan aplikasi web modern (HTML, CSS, JavaScript, PHP & framework)',
                    'Perancangan dan pengelolaan basis data relasional (MySQL / MariaDB)',
                    'Desain antarmuka pengguna (UI/UX) dan prototyping aplikasi digital',
                    'Pengujian perangkat lunak, version control (Git), dan deployment aplikasi',
                ]),
                'career_prospects' => implode("\n", [
                    'Web Developer (Frontend / Backend / Fullstack Junior)',
                    'UI/UX Designer & Web Designer',
                    'Database Administrator & QA Tester Junior',
                    'Freelance Programmer & Technopreneur',
                    'Melanjutkan studi S1 Teknik Informatika / Sistem Informasi',
                ]),
                'icon'             => 'laptop-code',
                'media_type'       => 'icon',
                'image'            => null,
                'sort_order'       => 2,
            ],
            [
                'title'            => 'Manajemen Perkantoran dan Layanan Bisnis',
                'slug'             => 'manajemen-perkantoran-dan-layanan-bisnis',
                'description'      => 'Mempersiapkan tenaga administrasi perkantoran modern yang mahir kearsipan digital, korespondensi bisnis, layanan pelanggan, dan teknologi perkantoran.',
                'content'          => '<p>Program Keahlian <strong>Manajemen Perkantoran dan Layanan Bisnis (MPLB)</strong> merupakan salah satu jurusan unggulan SMK PGRI 5 Serpong yang menyiapkan tenaga administrasi profesional di era perkantoran digital. Siswa dilatih menguasai tata kelola dokumen elektronik, surat-menyurat bisnis, pengelolaan rapat, kehumasan, dan pelayanan prima (service excellence).</p><p>Didukung praktik aplikasi perkantoran modern dan program PKL di berbagai instansi serta perusahaan kawasan BSD dan Serpong, lulusan MPLB memiliki peluang penyerapan kerja yang sangat luas.</p>',
                'learning_points'  => implode("\n", [
                    'Pengelolaan administrasi perkantoran modern dan otomatisasi dokumen digital',
                    'Korespondensi bisnis Bahasa Indonesia dan Bahasa Inggris',
                    'Sistem kearsipan elektronik, manajemen jadwal pimpinan, dan protokoler rapat',
                    'Pelayanan prima (service excellence), komunikasi bisnis, dan kehumasan (PR)',
                    'Pengelolaan sarana prasarana kantor dan aplikasi perkantoran terpadu',
                ]),
                'career_prospects' => implode("\n", [
                    'Staf Administrasi Perkantoran & General Affairs (GA)',
                    'Sekretaris Junior & Asisten Eksekutif',
                    'Customer Service & Front Office Executive',
                    'Staf Kearsipan & Operasional Bisnis',
                    'Melanjutkan studi Manajemen / Administrasi Bisnis',
                ]),
                'icon'             => 'briefcase',
                'media_type'       => 'icon',
                'image'            => null,
                'sort_order'       => 3,
            ],
            [
                'title'            => 'Akuntansi dan Keuangan Lembaga',
                'slug'             => 'akuntansi-dan-keuangan-lembaga',
                'description'      => 'Melatih ketelitian pencatatan transaksi keuangan, penyusunan laporan keuangan, perpajakan dasar, dan pengoperasian software akuntansi komputer.',
                'content'          => '<p>Program Keahlian <strong>Akuntansi dan Keuangan Lembaga (AKL)</strong> SMK PGRI 5 Serpong membekali siswa dengan kompetensi pengelolaan siklus akuntansi perusahaan jasa, dagang, dan manufaktur, baik secara manual maupun terkomputerisasi menggunakan spreadsheet dan aplikasi akuntansi.</p><p>Siswa juga mempelajari dasar perpajakan, pengelolaan kas kecil, serta etika profesi akuntansi sehingga siap bekerja sebagai staf keuangan yang teliti dan terpercaya di berbagai sektor usaha.</p>',
                'learning_points'  => implode("\n", [
                    'Siklus akuntansi perusahaan jasa, dagang, dan manufaktur sesuai standar SAK',
                    'Pengoperasian komputer akuntansi (MYOB / Accurate) dan pengolahan spreadsheet keuangan',
                    'Administrasi pajak dasar (PPh, PPN) dan pelaporan dokumen perpajakan',
                    'Pengelolaan kas kecil, rekonsiliasi bank, dan penganggaran operasional',
                    'Analisis laporan keuangan dasar serta etika profesi keuangan',
                ]),
                'career_prospects' => implode("\n", [
                    'Staf Akuntansi & Pembukuan (Accounting Staff)',
                    'Staf Keuangan & Kasir / Finance Administration',
                    'Staf Administrasi Pajak Junior (Tax Admin)',
                    'Teller & Staf Lembaga Keuangan / Koperasi',
                    'Melanjutkan studi S1 Akuntansi / Manajemen Keuangan',
                ]),
                'icon'             => 'calculator',
                'media_type'       => 'icon',
                'image'            => null,
                'sort_order'       => 4,
            ],
            [
                'title'            => 'Bisnis Digital dan Pemasaran',
                'slug'             => 'bisnis-digital-dan-pemasaran',
                'description'      => 'Mengembangkan kemampuan pemasaran modern, pengelolaan e-commerce, konten media sosial, manajemen ritel, dan kewirausahaan berbasis digital.',
                'content'          => '<p>Program Keahlian <strong>Bisnis Digital dan Pemasaran</strong> SMK PGRI 5 Serpong menjawab kebutuhan pasar kerja dan tren ekonomi digital. Siswa belajar strategi pemasaran daring (digital marketing), pengelolaan toko online/marketplace, pembuatan konten promosi kreatif, manajemen ritel modern, hingga analisis perilaku konsumen.</p><p>Melalui praktik penjualan langsung dan proyek kewirausahaan siswa, lulusan didorong mampu berkarier di tim pemasaran perusahaan maupun membangun bisnis mandiri.</p>',
                'learning_points'  => implode("\n", [
                    'Strategi digital marketing, social media marketing, dan copywriting promosi',
                    'Pengelolaan marketplace, e-commerce, dan katalog produk digital',
                    'Fotografi produk, desain konten promosi, dan video pendek pemasaran',
                    'Manajemen ritel modern, visual merchandising, dan teknik negosiasi penjualan',
                    'Perencanaan bisnis (business plan) dan pengelolaan UMKM berbasis digital',
                ]),
                'career_prospects' => implode("\n", [
                    'Digital Marketing & Social Media Specialist Junior',
                    'Admin E-Commerce & Marketplace Operations',
                    'Sales Executive & Business Development',
                    'Visual Merchandiser & Staf Ritel Modern',
                    'Wirausaha / Pemilik Bisnis Online Mandiri',
                ]),
                'icon'             => 'trending-up',
                'media_type'       => 'icon',
                'image'            => null,
                'sort_order'       => 5,
            ],
        ];

        $this->db->table('programs')->insertBatch($data);
    }
}
