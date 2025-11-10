<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePenjualanTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_penjualan' => [
                'type'           => 'INT',
                'auto_increment' => true,
            ],
            'tanggal' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'total' => [
                'type' => 'DOUBLE',
                'null' => false,
            ],
            'status_pembayaran' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'jumlah_dp' => [
                'type' => 'DOUBLE',
                'null' => true,
            ],
            'id_user' => [
                'type' => 'INT',
                'null' => true,
            ],
            'id_pelanggan' => [
            'type' => 'INT',
            'null' => true,
            ],
        ]);
        $this->forge->addKey('id_penjualan', true);
        $this->forge->addForeignKey('id_user', 'user', 'id_user', 'CASCADE', 'NO ACTION');
        $this->forge->addForeignKey('id_pelanggan', 'pelanggan', 'id_pelanggan', 'CASCADE', 'SET NULL');
        $this->forge->createTable('penjualan');
    }

    public function down()
    {
        $this->forge->dropTable('penjualan');
    }
}