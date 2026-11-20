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
                'category' => 'Prestasi',
                'tags' => json_encode(['prestasi', 'olimpiade', 'sains']),
                'title' => 'Siswa SMA Nusantara Raih Medali Emas Olimpiade Sains Nasional',
                'slug' => 'siswa-sma-nusantara-raih-medali-emas-olimpiade-sains-nasional',
                'excerpt' => 'Alya Putri, siswa kelas XII MIPA, berhasil meraih medali emas dalam ajang Olimpiade Sains Nasional yang diselenggarakan di Jakarta.',
                'content' => '<p>Prestasi membanggakan kembali diraih oleh siswa SMA Nusantara. Alya Putri, siswa kelas XII MIPA, berhasil meraih <strong>medali emas</strong> dalam ajang Olimpiade Sains Nasional (OSN) 2026 yang diselenggarakan di Jakarta pada tanggal 15-20 Maret 2026.</p><blockquote>"Saya sangat bersyukur dan bangga bisa mengharumkan nama SMA Nusantara. Terima kasih kepada guru pembimbing yang selalu mendukung saya."</blockquote><p>Kepala SMA Nusantara, Drs. Bambang Sutrisno, M.Pd., menyampaikan apresiasi setinggi-tingginya atas prestasi yang diraih. "Semoga prestasi ini menjadi motivasi bagi siswa lainnya untuk terus berprestasi," ujarnya.</p>',
                'author' => 'Admin',
                'published_at' => '2026-03-22 08:00:00',
                'status' => 'published',
                'is_featured' => 1,
            ],
            [
                'category' => 'Kegiatan',
                'tags' => json_encode(['kegiatan', 'bakti-sosial', 'peduli']),
                'title' => 'Bakti Sosial Siswa SMA Nusantara ke Panti Asuhan',
                'slug' => 'bakti-sosial-siswa-sma-nusantara-ke-panti-asuhan',
                'excerpt' => 'Siswa SMA Nusantara mengadakan bakti sosial ke panti asuhan sebagai bagian dari program pembinaan karakter.',
                'content' => '<p>Sebanyak 50 siswa SMA Nusantara mengadakan kunjungan dan bakti sosial ke Panti Asuhan Harapan Mulia pada Sabtu, 5 September 2026. Kegiatan ini merupakan bagian dari program pembinaan karakter yang rutin dilaksanakan setiap semester.</p><p>Dalam kegiatan tersebut, siswa menyalurkan donasi berupa sembako, alat tulis, dan pakaian layak pakai. Selain itu, siswa juga berinteraksi dan bermain bersama anak-anak panti asuhan.</p>',
                'author' => 'Admin',
                'published_at' => '2026-09-06 10:00:00',
                'status' => 'published',
                'is_featured' => 1,
            ],
            [
                'category' => 'Pengumuman',
                'tags' => json_encode(['spmb', 'pendaftaran', 'siswa-baru']),
                'title' => 'Pendaftaran SPMB Tahun Ajaran 2026/2027 Telah Dibuka',
                'slug' => 'pendaftaran-spmb-tahun-ajaran-2026-2027-telah-dibuka',
                'excerpt' => 'Penerimaan Peserta Didik Baru (SPMB) untuk tahun ajaran 2026/2027 telah resmi dibuka. Simak informasi lengkapnya di sini.',
                'content' => '<p>SMA Nusantara resmi membuka pendaftaran Penerimaan Peserta Didik Baru (SPMB) untuk tahun ajaran 2026/2027. Pendaftaran Gelombang 1 dimulai tanggal 1 Juni hingga 31 Juli 2026 melalui portal SPMB atau datang langsung ke kampus di Jl. Cendekia No. 45, Cakung.</p><p>Calon siswa dapat mendaftar secara online atau datang langsung ke kantor pendaftaran. Persyaratan lengkap dan jadwal seleksi dapat diunduh di halaman Download Center website ini.</p>',
                'author' => 'Admin',
                'published_at' => '2026-06-01 07:00:00',
                'status' => 'published',
                'is_featured' => 0,
            ],
        ];

        $this->db->table('news')->insertBatch($data);
    }
}
