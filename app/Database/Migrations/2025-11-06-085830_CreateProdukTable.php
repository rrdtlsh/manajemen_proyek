<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProdukTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_produk' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true, // <-- PERBAIKAN
                'auto_increment' => true,
            ],
            'kode_produk' => ['type' => 'VARCHAR', 'constraint' => '50', 'null' => true, 'unique' => true],
            'nama_produk' => ['type' => 'VARCHAR', 'constraint' => '100'],
            'id_kategori' => [ // <-- PERBAIKAN (dari 'kategori_produk')
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'id_supplier' => [ // <-- BARU
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'harga' => ['type' => 'DOUBLE', 'null' => true],
            'stok' => ['type' => 'INT', 'null' => false, 'default' => 0],
            'tanggal_masuk' => ['type' => 'DATE', 'null' => true], // <-- BARU
            'gambar_produk' => [ // <-- Dari migrasi lain
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'default'    => 'default.jpg',
            ],
        ]);
        $this->forge->addKey('id_produk', true);

        // Menambahkan Foreign Key dengan NAMA SPESIFIK
        $this->forge->addForeignKey('id_kategori', 'kategori', 'id_kategori', 'SET NULL', 'CASCADE', 'produk_id_kategori_foreign');
        $this->forge->addForeignKey('id_supplier', 'supplier', 'id_supplier', 'SET NULL', 'CASCADE', 'produk_id_supplier_foreign');

        $this->forge->createTable('produk');
    }

    public function down()
    {
        $this->forge->dropForeignKey('produk', 'produk_id_kategori_foreign');
        $this->forge->dropForeignKey('produk', 'produk_id_supplier_foreign');
        $this->forge->dropTable('produk');
    }
}
