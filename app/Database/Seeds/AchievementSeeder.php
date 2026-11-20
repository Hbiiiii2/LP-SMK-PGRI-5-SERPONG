<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AchievementSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('achievements')->truncate();
        $data = [
            ['student_name' => 'Muhammad Rizky Pratama', 'class_name' => 'Kelas XII TKJ', 'achievement' => 'Juara 1 LKS SMK Bidang IT Network Systems Administration', 'level' => 'Kota Tangerang Selatan', 'year' => 2026, 'medal' => 'gold', 'sort_order' => 1],
            ['student_name' => 'Nadia Putri Azzahra', 'class_name' => 'Kelas XII RPL', 'achievement' => 'Juara 2 LKS SMK Bidang Web Technologies', 'level' => 'Provinsi Banten', 'year' => 2026, 'medal' => 'silver', 'sort_order' => 2],
            ['student_name' => 'Siti Rahmawati', 'class_name' => 'Kelas XI AKL', 'achievement' => 'Juara 1 Lomba Akuntansi & Spreadsheet Pelajar SMK', 'level' => 'Kota Tangerang Selatan', 'year' => 2026, 'medal' => 'gold', 'sort_order' => 3],
            ['student_name' => 'Dimas Saputra', 'class_name' => 'Kelas XI MPLB', 'achievement' => 'Juara 2 Lomba Keterampilan Administrasi Perkantoran', 'level' => 'Kota Tangerang Selatan', 'year' => 2025, 'medal' => 'silver', 'sort_order' => 4],
            ['student_name' => 'Tim Futsal SMK PGRI 5', 'class_name' => 'Ekstrakurikuler Futsal', 'achievement' => 'Juara 1 Turnamen Futsal Antar SMK Se-Tangerang Selatan', 'level' => 'Kota Tangerang Selatan', 'year' => 2025, 'medal' => 'gold', 'sort_order' => 5],
            ['student_name' => 'Aulia Ramadhani', 'class_name' => 'Kelas XI Bisnis Digital', 'achievement' => 'Juara 3 Lomba Konten Kreatif & Digital Marketing SMK', 'level' => 'Provinsi Banten', 'year' => 2025, 'medal' => 'bronze', 'sort_order' => 6],
        ];

        $this->db->table('achievements')->insertBatch($data);
    }
}
