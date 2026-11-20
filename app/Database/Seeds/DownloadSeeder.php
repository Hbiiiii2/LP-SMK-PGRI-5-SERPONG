<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DownloadSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('downloads')->truncate();
        $data = [
            [
                'category'    => 'SPMB',
                'title'       => 'Brosur SPMB SMK PGRI 5 Serpong 2026/2027',
                'description' => 'Informasi lengkap program keahlian, syarat pendaftaran, jadwal gelombang, dan fasilitas SMK PGRI 5 Serpong.',
                'url'         => '/spmb',
                'file_size'   => '1.5 MB',
                'sort_order'  => 1,
            ],
            [
                'category'    => 'SPMB',
                'title'       => 'Formulir & Panduan Pendaftaran SPMB Online',
                'description' => 'Panduan langkah demi langkah pengisian formulir pendaftaran peserta didik baru secara online.',
                'url'         => '/spmb',
                'file_size'   => '620 KB',
                'sort_order'  => 2,
            ],
            [
                'category'    => 'Akademik',
                'title'       => 'Kalender Akademik SMK PGRI 5 Serpong 2026/2027',
                'description' => 'Jadwal kegiatan belajar mengajar, PAS, UKK, PKL, dan hari libur resmi tahun ajaran 2026/2027.',
                'url'         => '/agenda',
                'file_size'   => '980 KB',
                'sort_order'  => 3,
            ],
            [
                'category'    => 'PKL & Industri',
                'title'       => 'Panduan Praktik Kerja Lapangan (PKL / Prakerin)',
                'description' => 'Pedoman pelaksanaan PKL, format jurnal kegiatan harian, dan ketentuan penyusunan laporan akhir PKL.',
                'url'         => '/profile',
                'file_size'   => '1.8 MB',
                'sort_order'  => 4,
            ],
            [
                'category'    => 'Kesiswaan',
                'title'       => 'Tata Tertib & Kode Etik Siswa SMK PGRI 5 Serpong',
                'description' => 'Peraturan kedisiplinan, seragam, kehadiran, dan budaya sekolah bagi seluruh peserta didik.',
                'url'         => '/profile',
                'file_size'   => '540 KB',
                'sort_order'  => 5,
            ],
        ];

        $this->db->table('downloads')->insertBatch($data);
    }
}
