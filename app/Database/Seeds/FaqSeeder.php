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
                'question'   => 'Bagaimana cara mendaftar di SMK PGRI 5 Serpong?',
                'answer'     => 'Calon siswa dapat mendaftar secara online melalui halaman SPMB di website resmi smkpgri5serpong.sch.id/spmb, atau datang langsung ke sekretariat pendaftaran di Jl. Raya Puspitek Serpong, RT. 05 / RW. 04, Kec. Serpong, Kota Tangerang Selatan pada jam operasional sekolah.',
                'sort_order' => 1,
            ],
            [
                'question'   => 'Apa saja jurusan atau program keahlian di SMK PGRI 5 Serpong?',
                'answer'     => 'SMK PGRI 5 Serpong menyelenggarakan program keahlian yang relevan dengan dunia kerja, yaitu Teknik Komputer dan Jaringan (TKJ), Rekayasa Perangkat Lunak (RPL), Manajemen Perkantoran dan Layanan Bisnis (MPLB), Akuntansi dan Keuangan Lembaga (AKL), serta Bisnis Digital dan Pemasaran.',
                'sort_order' => 2,
            ],
            [
                'question'   => 'Apa status akreditasi dan NPSN SMK PGRI 5 Serpong?',
                'answer'     => 'SMK PGRI 5 Serpong berstatus Terakreditasi A (Sangat Baik) dari BAN-S/M dengan NPSN 20614731, berdiri sejak 20 Desember 2007 di bawah naungan Yayasan Pembina Lembaga Pendidikan (YPLP) PGRI Provinsi Banten.',
                'sort_order' => 3,
            ],
            [
                'question'   => 'Apakah ada program Praktik Kerja Lapangan (PKL) dan penyaluran kerja?',
                'answer'     => 'Ya. Seluruh siswa mengikuti Praktik Kerja Lapangan (PKL) di perusahaan dan instansi mitra DUDI di wilayah Serpong, BSD, dan Tangerang Selatan, dilengkapi Uji Kompetensi Keahlian (UKK) serta informasi lowongan kerja melalui Bursa Kerja Khusus (BKK) sekolah.',
                'sort_order' => 4,
            ],
            [
                'question'   => 'Dokumen apa saja yang dibutuhkan saat pendaftaran SPMB?',
                'answer'     => 'Persyaratan umum meliputi fotokopi/scan Kartu Keluarga (KK), Akta Kelahiran, Ijazah atau Surat Keterangan Lulus (SKL) SMP/MTs, NISN, serta pas foto terbaru. Berkas dapat diunggah langsung melalui formulir SPMB Online.',
                'sort_order' => 5,
            ],
            [
                'question'   => 'Di mana lokasi dan bagaimana cara menghubungi SMK PGRI 5 Serpong?',
                'answer'     => 'SMK PGRI 5 Serpong beralamat di Jl. Raya Puspitek Serpong, RT. 05 / RW. 04, Kel. Serpong, Kec. Serpong, Kota Tangerang Selatan, Banten 15310. Anda dapat menghubungi kami melalui email info@smkpgri5serpong.sch.id, Instagram @smkpgri5serpong, atau halaman Kontak di website ini.',
                'sort_order' => 6,
            ],
        ];

        $this->db->table('faq')->insertBatch($data);
    }
}
