<?php

namespace App\Database\Migrations\Dwh;
use CodeIgniter\Database\Migration;

class CreateDimPembeli extends Migration
{
    protected $DBGroup = 'dwh';

    public function up()
    {
        $this->forge->addField([
            'sk_pembeli' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_pelanggan_sumber' => [ // ID Pelanggan dari OLTP
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true, // Nullable untuk "Pembeli Umum/Non-Member"
            ],
            'nama_pembeli' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'no_hp' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],
            'kategori_pelanggan' => [ // 'Baru', 'Loyal', 'Reseller'
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'default'    => 'Umum',
            ],
            'alamat_domisili' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('sk_pembeli', true);
        $this->forge->createTable('dim_pembeli');
    }

    public function down()
    {
        $this->forge->dropTable('dim_pembeli');
    }
}