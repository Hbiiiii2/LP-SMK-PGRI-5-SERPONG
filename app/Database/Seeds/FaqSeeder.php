<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('faq')->truncate();
        $data = [
            [
                'question' => 'Bagaimana cara mendaftar di SMA Nusantara?',
                'answer' => 'Pendaftaran SPMB Gelombang 1 dibuka 1 Juni–31 Juli 2026 melalui portal spmb.smanusantara.sch.id atau datang langsung ke kantor pendaftaran di Jl. Cendekia No. 45, Cakung. Pendaftar Gelombang 1 mendapat potongan 50% biaya pengembangan.',
                'sort_order' => 1,
            ],
            [
                'question' => 'Apa saja program unggulan yang tersedia?',
                'answer' => 'SMA Nusantara memiliki tiga peminatan — MIPA, IPS, dan Bahasa — dengan pendalaman materi, bimbingan olimpiade, dan kelas persiapan UTBK sejak kelas XI. Tersedia pula 12 ekstrakurikuler, dari robotik dan paskibra hingga teater dan jurnalistik.',
                'sort_order' => 2,
            ],
            [
                'question' => 'Apakah SMA Nusantara menyediakan beasiswa?',
                'answer' => 'Ya. Tersedia beasiswa prestasi akademik dan non-akademik (potongan hingga 100% biaya pengembangan), serta beasiswa afirmasi bagi siswa kurang mampu. Informasi lebih lanjut dapat menghubungi bagian kesiswaan di (021) 8790-4521.',
                'sort_order' => 3,
            ],
            [
                'question' => 'Bagaimana sistem pembelajaran di SMA Nusantara?',
                'answer' => 'Kami menerapkan Kurikulum Merdeka dengan pembelajaran aktif, kolaboratif, dan berbasis proyek, didukung perpustakaan digital 4.000 e-book, lab IPA dan komputer 1:1, serta rapor daring yang bisa dipantau orang tua.',
                'sort_order' => 4,
            ],
            [
                'question' => 'Bagaimana cara menghubungi SMA Nusantara?',
                'answer' => 'Anda dapat menghubungi kami melalui telepon (021) 8790-4521, email info@smanusantara.sch.id, atau datang langsung ke Jl. Cendekia No. 45, Cakung, Jakarta Timur. Open house diadakan setiap Sabtu pertama tiap bulan, pukul 08.00–11.00.',
                'sort_order' => 5,
            ],
        ];

        $this->db->table('faq')->insertBatch($data);
    }
}
