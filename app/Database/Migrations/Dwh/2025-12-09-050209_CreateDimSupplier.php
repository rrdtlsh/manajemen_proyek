<?php

namespace App\Database\Migrations\Dwh;
use CodeIgniter\Database\Migration;

class CreateDimSupplier extends Migration
{
    protected $DBGroup = 'dwh';

    public function up()
    {
        $this->forge->addField([
            'sk_supplier' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_supplier_sumber' => [ // ID Supplier dari OLTP
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'nama_supplier' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'kontak_person' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'no_telepon' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],
            'alamat_supplier' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('sk_supplier', true);
        $this->forge->createTable('dim_supplier');
    }

    public function down()
    {
        $this->forge->dropTable('dim_supplier');
    }
}