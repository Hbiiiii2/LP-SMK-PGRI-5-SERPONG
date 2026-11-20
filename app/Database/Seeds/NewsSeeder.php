<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class NewsSeeder extends Seeder
{
    public function run()
    {
        $this->db->query('SET FOREIGN_KEY_CHECKS=0');
        $this->db->table('news')->truncate();
        $this->db->query('SET FOREIGN_KEY_CHECKS=1');
        $data = [
            [
                'category'     => 'Pengumuman',
                'tags'         => json_encode(['spmb', 'pendaftaran', 'smkpgri5serpong']),
                'title'        => 'Pendaftaran SPMB SMK PGRI 5 Serpong Tahun Ajaran 2026/2027 Resmi Dibuka',
                'slug'         => 'pendaftaran-spmb-smk-pgri-5-serpong-2026-2027-resmi-dibuka',
                'excerpt'      => 'SMK PGRI 5 Serpong resmi membuka Sistem Penerimaan Murid Baru (SPMB) 2026/2027 untuk seluruh program keahlian unggulan. Daftar mudah secara online.',
                'content'      => '<p><strong>SMK PGRI 5 Serpong</strong> (Terakreditasi A · NPSN 20614731) resmi membuka pendaftaran <strong>Sistem Penerimaan Murid Baru (SPMB) Tahun Ajaran 2026/2027</strong> bagi lulusan SMP/MTs sederajat di wilayah Kota Tangerang Selatan dan sekitarnya.</p><p>Calon peserta didik dapat memilih lima program keahlian unggulan sesuai minat dan bakat, yaitu Teknik Komputer dan Jaringan (TKJ), Rekayasa Perangkat Lunak (RPL), Manajemen Perkantoran dan Layanan Bisnis (MPLB), Akuntansi dan Keuangan Lembaga (AKL), serta Bisnis Digital dan Pemasaran.</p><p>Pendaftaran dapat dilakukan secara daring melalui menu <strong>SPMB Online</strong> di website resmi sekolah atau datang langsung ke kampus SMK PGRI 5 Serpong di Jl. Raya Puspitek Serpong, Kota Tangerang Selatan.</p>',
                'image'        => '/uploads/news/1794543956_69381d956260d87f.png',
                'author'       => 'Panitia SPMB',
                'published_at' => '2026-11-10 08:00:00',
                'status'       => 'published',
                'is_featured'  => 1,
            ],
            [
                'category'     => 'Prestasi',
                'tags'         => json_encode(['lks', 'prestasi', 'tkj', 'rpl']),
                'title'        => 'Siswa SMK PGRI 5 Serpong Torehkan Prestasi di Ajang LKS SMK Kota Tangerang Selatan',
                'slug'         => 'siswa-smk-pgri-5-serpong-raih-prestasi-lks-smk-tangsel',
                'excerpt'      => 'Kontingen SMK PGRI 5 Serpong berhasil membawa pulang piala dalam Lomba Kompetensi Siswa (LKS) SMK tingkat Kota Tangerang Selatan bidang IT dan Bisnis.',
                'content'      => '<p>Kebanggaan kembali hadir bagi keluarga besar <strong>SMK PGRI 5 Serpong</strong>. Delegasi siswa dari Program Keahlian TKJ, RPL, dan Akuntansi berhasil meraih penghargaan dalam ajang <strong>Lomba Kompetensi Siswa (LKS) SMK Tingkat Kota Tangerang Selatan</strong>.</p><blockquote>"Latihan rutin di laboratorium sekolah dan bimbingan intensif dari guru produktif membuat kami percaya diri menghadapi soal studi kasus industri pada LKS tahun ini."</blockquote><p>Kepala SMK PGRI 5 Serpong menyampaikan apresiasi atas kerja keras para peserta dan pembimbing. Prestasi ini menjadi bukti bahwa pembelajaran praktik vokasi di SMK PGRI 5 Serpong mampu bersaing di tingkat kota maupun provinsi.</p>',
                'image'        => null,
                'author'       => 'Tim Humas',
                'published_at' => '2026-10-18 09:30:00',
                'status'       => 'published',
                'is_featured'  => 1,
            ],
            [
                'category'     => 'Kegiatan',
                'tags'         => json_encode(['pkl', 'prakerin', 'industri', 'dudi']),
                'title'        => 'Pembekalan Praktik Kerja Lapangan (PKL) Siswa Kelas XI Bersama Mitra Industri',
                'slug'         => 'pembekalan-pkl-siswa-kelas-xi-bersama-mitra-industri',
                'excerpt'      => 'Menjelang penempatan PKL di perusahaan mitra kawasan BSD dan Serpong, siswa kelas XI mengikuti pembekalan budaya kerja dan keselamatan kerja.',
                'content'      => '<p>Dalam rangka menyiapkan siswa menghadapi lingkungan kerja nyata, <strong>SMK PGRI 5 Serpong</strong> menyelenggarakan kegiatan <strong>Pembekalan Praktik Kerja Lapangan (PKL)</strong> bagi seluruh siswa kelas XI di aula sekolah.</p><p>Kegiatan ini menghadirkan praktisi dari mitra Dunia Usaha dan Dunia Industri (DUDI) di kawasan Serpong dan Tangerang Selatan yang memaparkan etos kerja profesional, komunikasi di tempat kerja, kedisiplinan, serta penyusunan laporan PKL. Diharapkan para siswa dapat mengaplikasikan ilmu yang diperoleh di kelas sekaligus memperluas jejaring karier sejak bangku SMK.</p>',
                'image'        => null,
                'author'       => 'Pokja PKL & BKK',
                'published_at' => '2026-09-24 10:00:00',
                'status'       => 'published',
                'is_featured'  => 0,
            ],
            [
                'category'     => 'Kegiatan',
                'tags'         => json_encode(['karakter', 'bakti-sosial', 'osis']),
                'title'        => 'OSIS SMK PGRI 5 Serpong Gelar Bakti Sosial dan Santunan di Wilayah Serpong',
                'slug'         => 'osis-smk-pgri-5-serpong-gelar-bakti-sosial-wilayah-serpong',
                'excerpt'      => 'Wujud nyata pendidikan karakter IMTAQ dan kepedulian sosial, pengurus OSIS bersama siswa menyalurkan paket sembako dan perlengkapan belajar.',
                'content'      => '<p>Pengurus OSIS bersama perwakilan ekstrakurikuler <strong>SMK PGRI 5 Serpong</strong> mengadakan kegiatan bakti sosial dan santunan bagi warga sekitar dan anak yatim di Kecamatan Serpong, Kota Tangerang Selatan.</p><p>Kegiatan ini merupakan agenda rutin pembinaan karakter berbasis IMTAQ untuk menumbuhkan rasa empati, gotong royong, dan tanggung jawab sosial di tengah masyarakat.</p>',
                'image'        => null,
                'author'       => 'Kesiswaan',
                'published_at' => '2026-09-05 11:00:00',
                'status'       => 'published',
                'is_featured'  => 0,
            ],
        ];

        $this->db->table('news')->insertBatch($data);
    }
}
