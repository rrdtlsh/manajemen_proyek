<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePemasokanTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_pemasokan' => [
                'type'           => 'INT',
                'auto_increment' => true,
            ],
            'id_produk' => [
                'type' => 'INT',
                'null' => true,
            ],
            'id_supplier' => [
                'type' => 'INT',
                'null' => false,
            ],
            'tanggal' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'harga' => [
                'type' => 'DOUBLE',
                'null' => true,
            ],
            'jumlah' => [
                'type' => 'INT',
                'null' => false,
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'id_user' => [
                'type' => 'INT',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id_pemasokan', true);
        $this->forge->addForeignKey('id_produk', 'produk', 'id_produk', 'CASCADE', 'NO ACTION');
        $this->forge->addForeignKey('id_supplier', 'supplier', 'id_supplier', 'CASCADE', 'NO ACTION');
        $this->forge->addForeignKey('id_user', 'user', 'id_user', 'CASCADE', 'NO ACTION');
        $this->forge->createTable('pemasokan');
    }

    public function down()
    {
        $this->forge->dropTable('pemasokan');
    }
}