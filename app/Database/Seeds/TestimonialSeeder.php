<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('testimonials')->truncate();
        $data = [
            [
                'name'       => 'Muhammad Rizky Pratama',
                'role'       => 'Siswa Kelas XII TKJ · SMK PGRI 5 Serpong',
                'quote'      => 'Praktik langsung di lab jaringan dan bimbingan guru produktif di SMK PGRI 5 Serpong membuat saya paham konfigurasi MikroTik, server Linux, dan siap saat menjalani PKL di perusahaan IT.',
                'sort_order' => 1,
            ],
            [
                'name'       => 'Siti Aisyah',
                'role'       => 'Alumni MPLB 2024 · Staf Administrasi di Kawasan BSD',
                'quote'      => 'Pembiasaan disiplin dan praktik administrasi perkantoran di SMK PGRI 5 Serpong sangat membantu saya beradaptasi cepat di dunia kerja. Tidak lama setelah lulus, saya langsung diterima bekerja.',
                'sort_order' => 2,
            ],
            [
                'name'       => 'Budi Santoso',
                'role'       => 'Orang Tua Siswa Kelas XI',
                'quote'      => 'Saya memilih SMK PGRI 5 Serpong karena akreditasinya A, lingkungannya tertib, dan guru-gurunya perhatian terhadap kedisiplinan serta akhlak anak. Perkembangan keterampilan anak saya sangat terasa.',
                'sort_order' => 3,
            ],
        ];

        $this->db->table('testimonials')->insertBatch($data);
    }
}
