<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('testimonials')->truncate();
        $data = [
            ['name' => 'Alya Putri', 'role' => 'Siswi Kelas XII MIPA · Medali Emas OSN 2026', 'quote' => 'Guru-guru di SMA Nusantara hafal nama setiap siswanya. Bimbingan olimpiadenya intensif dan fasilitas labnya lengkap — saya bangga menjadi bagian dari sekolah ini.', 'sort_order' => 1],
            ['name' => 'H. Mahmud Efendi', 'role' => 'Orang Tua Siswa Kelas X', 'quote' => 'Pembiasaan karakter di SMA Nusantara sangat terasa. Anak saya jadi lebih disiplin, mandiri, dan rajin salat berjamaah. Komunikasi wali kelas dengan orang tua juga terbuka.', 'sort_order' => 2],
            ['name' => 'Raka Firmansyah', 'role' => 'Alumni 2024 · Kini di Universitas Indonesia', 'quote' => 'Berkat bimbingan intensif UTBK sejak kelas XI, saya lolos ke PTN impian lewat jalur SNBP. Suasana belajarnya suportif — tidak ada anak yang dibiarkan tertinggal sendirian.', 'sort_order' => 3],
        ];

        $this->db->table('testimonials')->insertBatch($data);
    }
}
