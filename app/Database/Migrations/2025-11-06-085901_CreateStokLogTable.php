<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStokLogTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_log' => [
                'type'           => 'INT',
                'auto_increment' => true,
            ],
            'tanggal' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'jenis' => [
                'type'       => 'VARCHAR',
                'constraint' => '10',
                'null'       => true,
            ],
            'jumlah' => [
                'type' => 'INT',
                'null' => false,
            ],
            'keterangan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'id_produk' => [
                'type' => 'INT',
                'null' => true,
            ],
            'id_penjualan' => [
                'type' => 'INT',
                'null' => true,
            ],
            'id_pemasokan' => [
                'type' => 'INT',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id_log', true);
        $this->forge->addForeignKey('id_produk', 'produk', 'id_produk', 'CASCADE', 'NO ACTION');
        $this->forge->addForeignKey('id_penjualan', 'penjualan', 'id_penjualan', 'CASCADE', 'NO ACTION');
        $this->forge->addForeignKey('id_pemasokan', 'pemasokan', 'id_pemasokan', 'CASCADE', 'NO ACTION');
        $this->forge->createTable('stok_log');
    }

    public function down()
    {
        $this->forge->dropTable('stok_log');
    }
}