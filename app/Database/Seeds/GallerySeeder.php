<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class GallerySeeder extends Seeder
{
    public function run()
    {
        $this->db->table('galleries')->truncate();
        $data = [
            ['category' => 'Akademik', 'image' => '/uploads/about/1794538800_7e4c553dc4762e3c.png', 'caption' => 'Profil & lingkungan belajar SMK PGRI 5 Serpong', 'is_featured' => 1, 'sort_order' => 1],
            ['category' => 'Kegiatan', 'image' => '/uploads/news/1794543956_69381d956260d87f.png', 'caption' => 'Kegiatan siswa dan sosialisasi program SMK PGRI 5 Serpong', 'is_featured' => 1, 'sort_order' => 2],
            ['category' => 'Akademik', 'image' => 'https://placehold.co/800x600/1b61c9/ffffff?text=Praktik+Lab+TKJ+%26+RPL', 'caption' => 'Praktik jaringan komputer dan pemrograman di Lab IT', 'is_featured' => 1, 'sort_order' => 3],
            ['category' => 'Akademik', 'image' => 'https://placehold.co/800x600/0f306a/ffffff?text=Lab+MPLB+%26+AKL', 'caption' => 'Praktik administrasi perkantoran dan akuntansi komputer', 'is_featured' => 0, 'sort_order' => 4],
            ['category' => 'Kegiatan', 'image' => 'https://placehold.co/800x600/254fad/ffffff?text=Pembekalan+PKL+Industri', 'caption' => 'Pembekalan Praktik Kerja Lapangan (PKL) bersama mitra DUDI', 'is_featured' => 0, 'sort_order' => 5],
            ['category' => 'Prestasi', 'image' => 'https://placehold.co/800x600/183f8c/ffffff?text=LKS+SMK+Tangsel', 'caption' => 'Kontingen SMK PGRI 5 Serpong pada LKS SMK Kota Tangerang Selatan', 'is_featured' => 0, 'sort_order' => 6],
        ];

        $this->db->table('galleries')->insertBatch($data);
    }
}
