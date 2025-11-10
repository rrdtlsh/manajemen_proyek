<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

// Pastikan NAMA CLASS ini sama dengan NAMA FILE
// Contoh: Jika file-nya 2025-01-01_CreateKeuanganTable.php
// maka class-nya harus CreateKeuanganTable
class CreateKeuanganTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_keuangan' => [
                'type'           => 'INT',
                'auto_increment' => true,
            ],
            'tanggal' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'tipe' => [
                'type'       => 'VARCHAR',
                'constraint' => '50', // Tipe: 'Pemasukan', 'Pengeluaran', 'DP'
                'null'       => false,
            ],
            // INI ADALAH PERBAIKANNYA
            'pemasukan' => [
                'type'       => 'DECIMAL(15,2)',
                'null'       => false,
                'default'    => 0.00,
            ],
            'pengeluaran' => [
                'type'       => 'DECIMAL(15,2)',
                'null'       => false,
                'default'    => 0.00,
            ],
            // AKHIR PERBAIKAN
            'keterangan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'id_user' => [
                'type' => 'INT',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id_keuangan', true);
        $this->forge->addForeignKey('id_user', 'user', 'id_user', 'CASCADE', 'NO ACTION');
        $this->forge->createTable('keuangan');
    }

    public function down()
    {
        $this->forge->dropTable('keuangan');
    }
}