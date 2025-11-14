<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKeuanganTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_keuangan' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true, // <-- PERBAIKAN
                'auto_increment' => true,
            ],
            'tanggal' => ['type' => 'DATE', 'null' => false],
            'tipe' => ['type' => 'VARCHAR', 'constraint' => '50', 'null' => false],
            'pemasukan' => ['type' => 'DECIMAL(15,2)', 'null' => false, 'default' => 0.00],
            'pengeluaran' => ['type' => 'DECIMAL(15,2)', 'null' => false, 'default' => 0.00],
            'keterangan' => ['type' => 'TEXT', 'null' => true],
            'id_user' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true, // <-- PERBAIKAN
                'null'       => true,
            ],
        ]);
        $this->forge->addKey('id_keuangan', true);
        $this->forge->addForeignKey('id_user', 'user', 'id_user', 'SET NULL', 'CASCADE', 'keuangan_id_user_foreign');
        $this->forge->createTable('keuangan');
    }
    public function down()
    {
        $this->forge->dropForeignKey('keuangan', 'keuangan_id_user_foreign');
        $this->forge->dropTable('keuangan');
    }
}
