<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDetailPenjualanTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_detail' => [
                'type'           => 'INT',
                'auto_increment' => true,
            ],
            'id_penjualan' => [
                'type' => 'INT',
                'null' => true,
            ],
            'id_produk' => [
                'type' => 'INT',
                'null' => true,
            ],
            'qty' => [
                'type' => 'INT',
                'null' => false,
            ],
            'harga_satuan' => [
                'type' => 'DOUBLE',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id_detail', true);
        $this->forge->addForeignKey('id_penjualan', 'penjualan', 'id_penjualan', 'CASCADE', 'NO ACTION');
        $this->forge->addForeignKey('id_produk', 'produk', 'id_produk', 'CASCADE', 'NO ACTION');
        $this->forge->createTable('detail_penjualan');
    }

    public function down()
    {
        $this->forge->dropTable('detail_penjualan');
    }
}