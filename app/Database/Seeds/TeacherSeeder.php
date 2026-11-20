<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TeacherSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('teachers')->truncate();

        $data = [
            [
                'name'             => 'Kepala SMK PGRI 5 Serpong',
                'photo'            => '/uploads/principal/1794538587_5c485468af07a9b0.jpg',
                'role'             => 'Kepala Sekolah',
                'experience'       => 'Manajemen Pendidikan Vokasi',
                'education'        => 'S2 Manajemen Pendidikan',
                'social_facebook'  => '',
                'social_instagram' => 'https://www.instagram.com/smkpgri5serpong/',
                'social_linkedin'  => '',
                'sort_order'       => 1,
                'show'             => 1,
            ],
            [
                'name'             => 'Wakasek Bidang Kurikulum',
                'photo'            => '',
                'role'             => 'Wakil Kepala Sekolah Kurikulum',
                'experience'       => 'Pengembangan Kurikulum Merdeka SMK',
                'education'        => 'S1/S2 Pendidikan',
                'social_facebook'  => '',
                'social_instagram' => '',
                'social_linkedin'  => '',
                'sort_order'       => 2,
                'show'             => 1,
            ],
            [
                'name'             => 'Kepala Program Keahlian TKJ',
                'photo'            => '',
                'role'             => 'Kaprog Teknik Komputer & Jaringan',
                'experience'       => 'Administrasi Jaringan & Server',
                'education'        => 'S1 Teknik Informatika',
                'social_facebook'  => '',
                'social_instagram' => '',
                'social_linkedin'  => '',
                'sort_order'       => 3,
                'show'             => 1,
            ],
            [
                'name'             => 'Kepala Program Keahlian RPL',
                'photo'            => '',
                'role'             => 'Kaprog Rekayasa Perangkat Lunak',
                'experience'       => 'Pemrograman Web & Basis Data',
                'education'        => 'S1 Ilmu Komputer / Sistem Informasi',
                'social_facebook'  => '',
                'social_instagram' => '',
                'social_linkedin'  => '',
                'sort_order'       => 4,
                'show'             => 1,
            ],
            [
                'name'             => 'Kepala Program Keahlian MPLB',
                'photo'            => '',
                'role'             => 'Kaprog Manajemen Perkantoran & Layanan Bisnis',
                'experience'       => 'Administrasi Perkantoran Digital',
                'education'        => 'S1 Pendidikan Administrasi Perkantoran',
                'social_facebook'  => '',
                'social_instagram' => '',
                'social_linkedin'  => '',
                'sort_order'       => 5,
                'show'             => 1,
            ],
            [
                'name'             => 'Kepala Program Keahlian AKL & Pemasaran',
                'photo'            => '',
                'role'             => 'Kaprog Akuntansi & Bisnis Digital',
                'experience'       => 'Akuntansi Keuangan & Pemasaran',
                'education'        => 'S1 Pendidikan Akuntansi / Ekonomi',
                'social_facebook'  => '',
                'social_instagram' => '',
                'social_linkedin'  => '',
                'sort_order'       => 6,
                'show'             => 1,
            ],
        ];

        $now = date('Y-m-d H:i:s');
        foreach ($data as &$row) {
            $row['created_at'] = $now;
            $row['updated_at'] = $now;
        }

        $this->db->table('teachers')->insertBatch($data);
    }
}
