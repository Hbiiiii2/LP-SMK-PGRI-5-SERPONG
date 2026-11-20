<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSpmbApplicationsTable extends Migration
{
    public function up()
    {
        if (! $this->db->tableExists('spmb_applications')) {
            $this->forge->addField([
                'id' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'registration_no' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 32,
                ],
                'full_name' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 150,
                ],
                'nisn' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 30,
                    'null'       => true,
                ],
                'nik' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 30,
                    'null'       => true,
                ],
                'gender' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 5,
                    'default'    => 'L',
                ],
                'birth_place' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'null'       => true,
                ],
                'birth_date' => [
                    'type' => 'DATE',
                    'null' => true,
                ],
                'religion' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 40,
                    'null'       => true,
                ],
                'origin_school' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 150,
                    'null'       => true,
                ],
                'graduation_year' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 10,
                    'null'       => true,
                ],
                'student_phone' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 30,
                    'null'       => true,
                ],
                'address' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
                'program_choice_1' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 150,
                ],
                'program_choice_2' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 150,
                    'null'       => true,
                ],
                'parent_name' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 150,
                ],
                'parent_relationship' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 40,
                    'default'    => 'Orang Tua',
                ],
                'parent_phone' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 30,
                ],
                'parent_email' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 150,
                    'null'       => true,
                ],
                'parent_occupation' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 100,
                    'null'       => true,
                ],
                'doc_kk' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'null'       => true,
                ],
                'doc_akta' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'null'       => true,
                ],
                'doc_ijazah' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'null'       => true,
                ],
                'doc_foto' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'null'       => true,
                ],
                'docs_complete' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'default'    => 0,
                ],
                'status' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 30,
                    'default'    => 'pending',
                ],
                'admin_notes' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
                'wa_notified_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'is_read' => [
                    'type'       => 'TINYINT',
                    'constraint' => 1,
                    'default'    => 0,
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'updated_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);

            $this->forge->addKey('id', true);
            $this->forge->addUniqueKey('registration_no');
            $this->forge->addKey('status');
            $this->forge->addKey('created_at');
            $this->forge->createTable('spmb_applications', true);
        }

        // Seed 2 realistic initial sample applicants if table is empty
        $count = $this->db->table('spmb_applications')->countAllResults();
        if ($count === 0) {
            $year = date('Y');
            $now  = date('Y-m-d H:i:s');
            $this->db->table('spmb_applications')->insertBatch([
                [
                    'registration_no'     => 'SPMB-' . $year . '-0001',
                    'full_name'           => 'Ahmad Fauzan Ramadhan',
                    'nisn'                => '0098123456',
                    'nik'                 => '3674011505090001',
                    'gender'              => 'L',
                    'birth_place'         => 'Tangerang Selatan',
                    'birth_date'          => '2011-05-15',
                    'religion'            => 'Islam',
                    'origin_school'       => 'SMP Negeri 1 Serpong',
                    'graduation_year'     => $year,
                    'student_phone'       => '081298765432',
                    'address'             => 'Jl. Raya Serpong KM 8, Serpong, Tangerang Selatan',
                    'program_choice_1'    => 'TEKNIK KOMPUTER DAN JARINGAN',
                    'program_choice_2'    => 'Rekayasa Perangkat Lunak',
                    'parent_name'         => 'Budi Santoso',
                    'parent_relationship' => 'Ayah',
                    'parent_phone'        => '081234567890',
                    'parent_email'        => 'budi.santoso@contoh.id',
                    'parent_occupation'   => 'Karyawan Swasta',
                    'doc_kk'              => null,
                    'doc_akta'            => null,
                    'doc_ijazah'          => null,
                    'doc_foto'            => null,
                    'docs_complete'       => 1,
                    'status'              => 'verified',
                    'admin_notes'         => 'Data calon siswa dan berkas persyaratan fisik telah diverifikasi panitia. Menunggu jadwal daftar ulang.',
                    'wa_notified_at'      => null,
                    'is_read'             => 1,
                    'created_at'          => $now,
                    'updated_at'          => $now,
                ],
                [
                    'registration_no'     => 'SPMB-' . $year . '-0002',
                    'full_name'           => 'Siti Aisyah Putri',
                    'nisn'                => '0098765432',
                    'nik'                 => '3674015208110004',
                    'gender'              => 'P',
                    'birth_place'         => 'Tangerang',
                    'birth_date'          => '2011-08-12',
                    'religion'            => 'Islam',
                    'origin_school'       => 'SMP Negeri 3 Tangerang Selatan',
                    'graduation_year'     => $year,
                    'student_phone'       => '085611223344',
                    'address'             => 'Perumahan Puri Serpong Blok B3 No. 12, Serpong',
                    'program_choice_1'    => 'Rekayasa Perangkat Lunak',
                    'program_choice_2'    => 'TEKNIK KOMPUTER DAN JARINGAN',
                    'parent_name'         => 'Hendra Wijaya',
                    'parent_relationship' => 'Ayah',
                    'parent_phone'        => '081398761234',
                    'parent_email'        => 'hendra.wijaya@contoh.id',
                    'parent_occupation'   => 'Wiraswasta',
                    'doc_kk'              => null,
                    'doc_akta'            => null,
                    'doc_ijazah'          => null,
                    'doc_foto'            => null,
                    'docs_complete'       => 0,
                    'status'              => 'pending',
                    'admin_notes'         => null,
                    'wa_notified_at'      => null,
                    'is_read'             => 0,
                    'created_at'          => $now,
                    'updated_at'          => $now,
                ],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->tableExists('spmb_applications')) {
            $this->forge->dropTable('spmb_applications', true);
        }
    }
}
