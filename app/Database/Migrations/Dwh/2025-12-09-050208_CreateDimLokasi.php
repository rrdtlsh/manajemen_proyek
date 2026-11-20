<?php

namespace App\Database\Migrations\Dwh;
use CodeIgniter\Database\Migration;

class CreateDimLokasi extends Migration
{
    protected $DBGroup = 'dwh'; // Pastikan ini ada

    public function up()
    {
        $this->forge->addField([
            'sk_lokasi' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_lokasi_sumber' => [ // ID Toko/Gudang dari OLTP (jika ada)
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'tipe_lokasi' => [ // 'Toko Fisik', 'Gudang', 'Online'
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'nama_wilayah' => [ // 'Banjarmasin Utara', dll
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'alamat_detail' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('sk_lokasi', true);
        $this->forge->createTable('dim_lokasi');
    }

    public function down()
    {
        $this->forge->dropTable('dim_lokasi');
    }
}