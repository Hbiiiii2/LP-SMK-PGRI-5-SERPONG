<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ExtracurricularSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('extracurriculars')->truncate();
        $data = [
            ['title' => 'IT Club & Coding', 'description' => 'Komunitas praktik jaringan komputer, pemrograman web, desain grafis, dan persiapan LKS SMK.', 'icon' => 'fas fa-laptop-code', 'icon_color' => '#1b61c9', 'sort_order' => 1],
            ['title' => 'Pramuka', 'description' => 'Pembentukan karakter disiplin, kemandirian, kepemimpinan, dan kepedulian sosial melalui kegiatan kepramukaan.', 'icon' => 'fas fa-campground', 'icon_color' => '#10b981', 'sort_order' => 2],
            ['title' => 'Paskibra', 'description' => 'Latihan baris-berbaris, tata upacara sekolah, dan pembinaan semangat kebangsaan.', 'icon' => 'fas fa-flag', 'icon_color' => '#dc2626', 'sort_order' => 3],
            ['title' => 'Futsal & Olahraga', 'description' => 'Pembinaan kebugaran dan prestasi tim futsal, bola voli, bulu tangkis, serta pencak silat.', 'icon' => 'fas fa-futbol', 'icon_color' => '#ef4444', 'sort_order' => 4],
            ['title' => 'Rohis (Kerohanian Islam)', 'description' => 'Kajian keagamaan, marawis/hadroh, peringatan hari besar Islam, dan penguatan karakter IMTAQ.', 'icon' => 'fas fa-book-open', 'icon_color' => '#059669', 'sort_order' => 5],
            ['title' => 'Jurnalistik & Multimedia', 'description' => 'Fotografi, videografi, penulisan berita sekolah, dan pengelolaan konten kreatif media sosial.', 'icon' => 'fas fa-camera', 'icon_color' => '#0ea5e9', 'sort_order' => 6],
        ];

        $this->db->table('extracurriculars')->insertBatch($data);
    }
}
