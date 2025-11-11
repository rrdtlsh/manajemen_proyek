<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql; // Penting untuk DEFAULT CURRENT_TIMESTAMP

class CreateTabelRestok extends Migration
{
    public function up()
    {
        // Fungsi ini akan dijalankan saat migrasi 'naik' (dibuat)
        $this->forge->addField([
            'id_restok' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama_supplier' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'nama_barang' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'qty' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'harga_satuan' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,0',
            ],
            'total_harga' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,0',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['Menunggu', 'Disetujui'],
                'default'    => 'Menunggu',
            ],
            'created_at' => [
                'type'    => 'DATETIME',
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'updated_at' => [
                'type'    => 'DATETIME',
                'default' => new RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
            ],
        ]);

        // Menambahkan Primary Key
        $this->forge->addKey('id_restok', true);
        
        // Membuat tabel 'restok'
        $this->forge->createTable('restok');
    }

    public function down()
    {
        // Fungsi ini akan dijalankan saat migrasi 'turun' (dihapus)
        $this->forge->dropTable('restok');
    }
}