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
                'constraint'     => 11,
                'unsigned'       => true, // <-- PERBAIKAN
                'auto_increment' => true,
            ],
            'id_penjualan' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true, // <-- PERBAIKAN
                'null'       => true,
            ],
            'id_produk' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true, // <-- PERBAIKAN
                'null'       => true,
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
        // Memberi nama spesifik pada constraint
        $this->forge->addForeignKey('id_penjualan', 'penjualan', 'id_penjualan', 'CASCADE', 'CASCADE', 'detail_id_penjualan_foreign');
        $this->forge->addForeignKey('id_produk', 'produk', 'id_produk', 'SET NULL', 'CASCADE', 'detail_id_produk_foreign');
        $this->forge->createTable('detail_penjualan');
    }

    public function down()
    {
        $this->forge->dropForeignKey('detail_penjualan', 'detail_id_penjualan_foreign');
        $this->forge->dropForeignKey('detail_penjualan', 'detail_id_produk_foreign');
        $this->forge->dropTable('detail_penjualan');
    }
}
