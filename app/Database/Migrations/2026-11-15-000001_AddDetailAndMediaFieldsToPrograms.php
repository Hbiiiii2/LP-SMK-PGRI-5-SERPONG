<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDetailAndMediaFieldsToPrograms extends Migration
{
    public function up()
    {
        if (! $this->db->fieldExists('slug', 'programs')) {
            $this->forge->addColumn('programs', [
                'slug' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 220,
                    'null'       => true,
                    'default'    => null,
                    'after'      => 'title',
                ],
            ]);
        }

        if (! $this->db->fieldExists('content', 'programs')) {
            $this->forge->addColumn('programs', [
                'content' => [
                    'type'    => 'LONGTEXT',
                    'null'    => true,
                    'default' => null,
                    'after'   => 'description',
                ],
            ]);
        }

        if (! $this->db->fieldExists('learning_points', 'programs')) {
            $this->forge->addColumn('programs', [
                'learning_points' => [
                    'type'    => 'TEXT',
                    'null'    => true,
                    'default' => null,
                    'after'   => 'content',
                ],
            ]);
        }

        if (! $this->db->fieldExists('career_prospects', 'programs')) {
            $this->forge->addColumn('programs', [
                'career_prospects' => [
                    'type'    => 'TEXT',
                    'null'    => true,
                    'default' => null,
                    'after'   => 'learning_points',
                ],
            ]);
        }

        if (! $this->db->fieldExists('media_type', 'programs')) {
            $this->forge->addColumn('programs', [
                'media_type' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 20,
                    'null'       => false,
                    'default'    => 'icon',
                    'after'      => 'icon',
                ],
            ]);
        }

        if (! $this->db->fieldExists('image', 'programs')) {
            $this->forge->addColumn('programs', [
                'image' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'null'       => true,
                    'default'    => null,
                    'after'      => 'media_type',
                ],
            ]);
        }

        // Enrich existing records when detail columns are still empty
        $rows = $this->db->table('programs')->orderBy('id', 'ASC')->get()->getResultArray();
        $usedSlugs = [];

        foreach ($rows as $row) {
            $id    = (int) ($row['id'] ?? 0);
            $title = trim((string) ($row['title'] ?? ''));
            $desc  = trim((string) ($row['description'] ?? ''));
            $update = [];

            if (empty($row['slug'])) {
                $baseSlug = strtolower(trim((string) preg_replace('/[^a-z0-9]+/i', '-', $title), '-'));
                if ($baseSlug === '') {
                    $baseSlug = 'program-' . $id;
                }
                $candidate = $baseSlug;
                $suffix = 2;
                while (in_array($candidate, $usedSlugs, true)) {
                    $candidate = $baseSlug . '-' . $suffix++;
                }
                $usedSlugs[] = $candidate;
                $update['slug'] = $candidate;
            } else {
                $usedSlugs[] = (string) $row['slug'];
            }

            if (empty($row['learning_points']) || empty($row['career_prospects']) || empty($row['content'])) {
                $preset = $this->presetByTitle($title, $desc);
                if (empty($row['learning_points']) && ! empty($preset['learning_points'])) {
                    $update['learning_points'] = implode("\n", $preset['learning_points']);
                }
                if (empty($row['career_prospects']) && ! empty($preset['career_prospects'])) {
                    $update['career_prospects'] = implode("\n", $preset['career_prospects']);
                }
                if (empty($row['content']) && ! empty($preset['overview'])) {
                    $update['content'] = '<p>' . htmlspecialchars($preset['overview'], ENT_QUOTES, 'UTF-8') . '</p>';
                }
            }

            if ($update !== []) {
                $this->db->table('programs')->where('id', $id)->update($update);
            }
        }
    }

    public function down()
    {
        foreach (['slug', 'content', 'learning_points', 'career_prospects', 'media_type', 'image'] as $col) {
            if ($this->db->fieldExists($col, 'programs')) {
                $this->forge->dropColumn('programs', $col);
            }
        }
    }

    private function presetByTitle(string $title, string $description): array
    {
        $t = strtolower($title);

        if (str_contains($t, 'tkj') || str_contains($t, 'jaringan')) {
            return [
                'learning_points' => [
                    'Perakitan, perawatan, dan troubleshooting komputer (PC & perangkat keras)',
                    'Instalasi sistem operasi Windows & Linux Server serta manajemen layanan jaringan',
                    'Perancangan topologi LAN, WAN, routing switching (MikroTik & Cisco)',
                    'Instalasi kabel Fiber Optik (FTTH), pengkabelan terstruktur, dan Wi-Fi enterprise',
                    'Keamanan jaringan (Firewall, VPN, manajemen bandwidth) dan komputasi awan dasar',
                ],
                'career_prospects' => [
                    'Network Administrator & Teknisi Jaringan',
                    'IT Support / Helpdesk & Infrastructure Engineer',
                    'Teknisi Fiber Optik & Telekomunikasi',
                    'System Administrator & Cloud Junior',
                    'Wirausaha Jasa Servis & Instalasi IT',
                ],
                'overview' => $description !== '' ? $description : 'Program Keahlian Teknik Komputer dan Jaringan membekali siswa dengan kompetensi instalasi perangkat keras, konfigurasi jaringan, administrasi server, serta keamanan sistem.',
            ];
        }

        if (str_contains($t, 'sains') || str_contains($t, 'teknologi')) {
            return [
                'learning_points' => [
                    'Pendalaman konsep fisika terapan, kimia analisis, dan biologi eksperimental',
                    'Metodologi riset ilmiah, penyusunan karya tulis, dan pengujian hipotesis di laboratorium',
                    'Pemodelan masalah berbasis STEM dan rekayasa alat sederhana',
                    'Pembinaan intensif olimpiade sains dan kompetisi karya ilmiah remaja',
                ],
                'career_prospects' => [
                    'Studi lanjut bidang Sains, Teknik & Kedokteran',
                    'Asisten Riset & Analis Laboratorium Junior',
                    'Peserta Kompetisi Riset & Olimpiade Sains',
                ],
                'overview' => $description !== '' ? $description . ' Siswa dilatih berpikir analitis lewat praktikum terstruktur dan proyek eksperimen terpadu.' : 'Pembelajaran sains terpadu dengan pendekatan praktikum laboratorium dan riset berbasis proyek.',
            ];
        }

        if (str_contains($t, 'digital') || str_contains($t, 'literacy') || str_contains($t, 'rpl') || str_contains($t, 'informatika')) {
            return [
                'learning_points' => [
                    'Dasar algoritma, logika pemrograman, dan pengembangan aplikasi web modern',
                    'Desain antarmuka digital (UI/UX) serta pengolahan grafis vektor dan bitmap',
                    'Pengelolaan dokumen kolaboratif, produktivitas perkantoran, dan literasi data',
                    'Etika bermedia digital, keamanan akun, dan pencegahan risiko siber dasar',
                ],
                'career_prospects' => [
                    'Web Developer & Desainer Grafis Junior',
                    'Operator Digital & Konten Kreator Edukatif',
                    'Wirausaha Produk & Layanan Digital',
                ],
                'overview' => $description !== '' ? $description . ' Materi dirancang bertahap dari pemahaman dasar hingga pembuatan portofolio karya digital.' : 'Pembinaan keterampilan pemrograman dasar, desain grafis, dan kecakapan digital terapan.',
            ];
        }

        if (str_contains($t, 'seni') || str_contains($t, 'budaya')) {
            return [
                'learning_points' => [
                    'Eksplorasi teknik vokal, ansambel musik modern & tradisional',
                    'Seni tari kreasi, koreografi panggung, dan penguasaan seni peran (teater)',
                    'Dasar seni rupa terapan, komposisi visual, dan kurasi pameran karya siswa',
                    'Manajemen produksi pementasan seni dan pelestarian budaya Nusantara',
                ],
                'career_prospects' => [
                    'Desainer Kreatif & Pekerja Seni Pertunjukan',
                    'Tim Produksi Event Seni & Budaya Sekolah',
                    'Studi lanjut Seni Rupa, Desain & Pertunjukan',
                ],
                'overview' => $description !== '' ? $description . ' Siswa mendapat ruang latihan terjadwal dan tampil dalam pentas apresiasi seni berkala.' : 'Program pengembangan bakat kreatif siswa di bidang musik, tari, teater, dan seni rupa.',
            ];
        }

        if (str_contains($t, 'olahraga')) {
            return [
                'learning_points' => [
                    'Peningkatan kebugaran jasmani, daya tahan fisik, dan teknik dasar berbagai cabang',
                    'Pembinaan cabang futsal, bola basket, bola voli, bulu tangkis, dan atletik',
                    'Pemahaman aturan pertandingan resmi, strategi tim, dan sportivitas bertanding',
                    'Persiapan fisik dan mental menghadapi kejuaraan antarpelajar',
                ],
                'career_prospects' => [
                    'Atlet Berprestasi Tingkat Daerah & Nasional',
                    'Pendidikan Jasmani, Kepelatihan & Keolahragaan',
                    'Penggerak Komunitas Olahraga & Kebugaran',
                ],
                'overview' => $description !== '' ? $description . ' Pembinaan dilakukan secara rutin di bawah pendampingan guru olahraga dan pelatih berkompeten.' : 'Pembinaan olahraga terstruktur untuk membangun kebugaran, sportivitas, dan prestasi atlet muda.',
            ];
        }

        if (str_contains($t, 'bahasa')) {
            return [
                'learning_points' => [
                    'Praktik percakapan aktif (speaking & listening) dengan pengajar dan penutur asing',
                    'Penguasaan tata bahasa akademik, membaca teks internasional, dan menulis esai',
                    'Persiapan tes kemahiran bahasa asing dan lomba debat maupun pidato',
                    'Pemahaman lintas budaya dan etiket komunikasi di lingkungan kerja global',
                ],
                'career_prospects' => [
                    'Komunikator Multibahasa & Pemandu Acara',
                    'Studi lanjut Sastra, Hubungan Internasional & Pariwisata',
                    'Kesiapan Karier di Perusahaan Multinasional',
                ],
                'overview' => $description !== '' ? $description . ' Pendekatan komunikatif membantu siswa percaya diri menggunakan bahasa asing di dalam maupun luar kelas.' : 'Program penguatan komunikasi bahasa asing secara aktif untuk kesiapan studi dan dunia kerja.',
            ];
        }

        if (str_contains($t, 'karakter') || str_contains($t, 'religi')) {
            return [
                'learning_points' => [
                    'Pembiasaan ibadah harian, kajian keagamaan, dan pendalaman akhlak mulia',
                    'Pendidikan kedisiplinan diri, kepemimpinan, tanggung jawab, dan empati sosial',
                    'Program bakti sosial, kepedulian lingkungan, dan penguatan budaya sekolah santun',
                ],
                'career_prospects' => [
                    'Pribadi Berintegritas & Berkarakter Kuat',
                    'Pemimpin Organisasi Siswa & Kegiatan Sosial',
                ],
                'overview' => $description !== '' ? $description . ' Nilai karakter diintegrasikan dalam kegiatan pagi hari, bimbingan konseling, dan aksi sosial.' : 'Pembentukan karakter religius, disiplin, dan kepedulian sosial dalam kehidupan sekolah.',
            ];
        }

        return [
            'learning_points' => [
                'Penguasaan kompetensi dasar hingga lanjutan sesuai bidang ' . $title,
                'Praktik terbimbing di kelas maupun laboratorium / ruang praktik',
                'Pengerjaan proyek kolaboratif dan evaluasi portofolio hasil belajar',
                'Pembinaan karakter kerja, kedisiplinan, dan kesiapan melanjutkan studi atau berkarier',
            ],
            'career_prospects' => [
                'Kesiapan Dunia Usaha & Dunia Industri (DUDI)',
                'Melanjutkan Pendidikan Tinggi sesuai Bidang Minat',
                'Wirausaha Mandiri Berbasis Keterampilan',
            ],
            'overview' => $description !== '' ? $description : 'Program pembinaan terstruktur untuk mengembangkan kompetensi, keterampilan praktik, dan kesiapan masa depan siswa.',
        ];
    }
}
