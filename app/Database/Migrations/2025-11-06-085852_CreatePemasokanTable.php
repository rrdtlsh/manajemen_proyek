<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePemasokanTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_pemasokan' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'id_produk' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'id_supplier' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => false],
            'tanggal' => ['type' => 'DATE', 'null' => false],
            'harga' => ['type' => 'DOUBLE', 'null' => true],
            'jumlah' => ['type' => 'INT', 'null' => false],
            'status' => ['type' => 'VARCHAR', 'constraint' => '20', 'null' => true],
            'id_user' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
        ]);
        $this->forge->addKey('id_pemasokan', true);
        $this->forge->addForeignKey('id_produk', 'produk', 'id_produk', 'SET NULL', 'CASCADE', 'pemasokan_id_produk_foreign');
        $this->forge->addForeignKey('id_supplier', 'supplier', 'id_supplier', 'NO ACTION', 'CASCADE', 'pemasokan_id_supplier_foreign');
        $this->forge->addForeignKey('id_user', 'user', 'id_user', 'SET NULL', 'CASCADE', 'pemasokan_id_user_foreign');
        $this->forge->createTable('pemasokan');
    }
    public function down()
    {
        $this->forge->dropForeignKey('pemasokan', 'pemasokan_id_produk_foreign');
        $this->forge->dropForeignKey('pemasokan', 'pemasokan_id_supplier_foreign');
        $this->forge->dropForeignKey('pemasokan', 'pemasokan_id_user_foreign');
        $this->forge->dropTable('pemasokan');
    }
}
