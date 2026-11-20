<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('events')->truncate();
        $data = [
            [
                'title'       => 'Peringatan HUT PGRI & Hari Guru Nasional',
                'slug'        => 'peringatan-hut-pgri-dan-hari-guru-nasional',
                'description' => 'Upacara peringatan HUT PGRI dan Hari Guru Nasional bersama seluruh civitas akademika SMK PGRI 5 Serpong serta pentas apresiasi siswa.',
                'location'    => 'Lapangan SMK PGRI 5 Serpong',
                'event_date'  => '2026-11-25',
                'event_time'  => '07.00 WIB',
                'sort_order'  => 1,
            ],
            [
                'title'       => 'Penilaian Akhir Semester (PAS) Ganjil',
                'slug'        => 'penilaian-akhir-semester-ganjil-2026',
                'description' => 'Pelaksanaan evaluasi pembelajaran teori dan praktik semester ganjil tahun ajaran 2026/2027 berbasis CBT.',
                'location'    => 'Ruang Kelas & Lab Komputer',
                'event_date'  => '2026-12-07',
                'event_time'  => '07.30 WIB',
                'sort_order'  => 2,
            ],
            [
                'title'       => 'Pembekalan & Pelepasan PKL (Prakerin) Kelas XI',
                'slug'        => 'pembekalan-dan-pelepasan-pkl-kelas-xi',
                'description' => 'Pembekalan budaya kerja industri, K3, dan penyerahan siswa PKL kepada mitra DUDI di wilayah Serpong dan Tangerang Selatan.',
                'location'    => 'Aula SMK PGRI 5 Serpong',
                'event_date'  => '2027-01-11',
                'event_time'  => '08.00 WIB',
                'sort_order'  => 3,
            ],
            [
                'title'       => 'Uji Kompetensi Keahlian (UKK) Kelas XII',
                'slug'        => 'uji-kompetensi-keahlian-ukk-kelas-xii',
                'description' => 'Pelaksanaan ujian praktik kejuruan bersama asesor eksternal DUDI bagi seluruh siswa kelas XII TKJ, RPL, MPLB, AKL, dan Bisnis Digital.',
                'location'    => 'Laboratorium Praktik Kejuruan',
                'event_date'  => '2027-02-15',
                'event_time'  => '07.30 WIB',
                'sort_order'  => 4,
            ],
        ];

        $this->db->table('events')->insertBatch($data);
    }
}
