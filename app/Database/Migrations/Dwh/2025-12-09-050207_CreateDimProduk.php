<?php

namespace App\Database\Migrations\Dwh;
use CodeIgniter\Database\Migration;

class CreateDimProduk extends Migration
{
    protected $DBGroup = 'dwh';

    public function up()
    {
        $this->forge->addField([
            'sk_produk'           => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'id_produk_sumber'    => ['type' => 'INT', 'constraint' => 11, 'null' => true],
            'kode_produk_asli'    => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'nama_produk'         => ['type' => 'VARCHAR', 'constraint' => 255],
            'kategori'            => ['type' => 'VARCHAR', 'constraint' => 100],
            'satuan'              => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'harga_beli_saat_ini' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0],
            'harga_jual_saat_ini' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0],
        ]);
        $this->forge->addKey('sk_produk', true);
        $this->forge->createTable('dim_produk');
    }

    public function down()
    {
        $this->forge->dropTable('dim_produk');
    }
}