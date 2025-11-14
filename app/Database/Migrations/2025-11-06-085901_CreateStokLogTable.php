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
                'constraint'     => 11,
                'unsigned'       => true, // <-- PERBAIKAN
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
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true, // <-- PERBAIKAN
                'null'       => true,
            ],
            'id_penjualan' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true, // <-- PERBAIKAN
                'null'       => true,
            ],
            'id_pemasokan' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true, // <-- PERBAIKAN
                'null'       => true,
            ],
        ]);
        $this->forge->addKey('id_log', true);
        // Memberi nama spesifik pada constraint
        $this->forge->addForeignKey('id_produk', 'produk', 'id_produk', 'SET NULL', 'CASCADE', 'stok_log_id_produk_foreign');
        $this->forge->addForeignKey('id_penjualan', 'penjualan', 'id_penjualan', 'SET NULL', 'CASCADE', 'stok_log_id_penjualan_foreign');
        $this->forge->addForeignKey('id_pemasokan', 'pemasokan', 'id_pemasokan', 'SET NULL', 'CASCADE', 'stok_log_id_pemasokan_foreign');
        $this->forge->createTable('stok_log');
    }

    public function down()
    {
        $this->forge->dropForeignKey('stok_log', 'stok_log_id_produk_foreign');
        $this->forge->dropForeignKey('stok_log', 'stok_log_id_penjualan_foreign');
        $this->forge->dropForeignKey('stok_log', 'stok_log_id_pemasokan_foreign');
        $this->forge->dropTable('stok_log');
    }
}
