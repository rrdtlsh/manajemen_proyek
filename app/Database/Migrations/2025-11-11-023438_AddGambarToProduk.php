<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddGambarToProduk extends Migration
{
    public function up()
    {
        $fields = [
            'gambar_produk' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'default'    => 'default.jpg',
                'after'      => 'stok', // Menempatkan kolom ini setelah 'stok'
            ],
        ];

        $this->forge->addColumn('produk', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('produk', 'gambar_produk');
    }
}
