<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class CreateTabelRestok extends Migration
{
    public function up()
    {
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

            // Status inventaris (request)
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['Menunggu', 'Disetujui', 'Ditolak'],
                'default'    => 'Menunggu',
            ],

            // Status approval owner
            'status_owner' => [
                'type'       => 'ENUM',
                'constraint' => ['Menunggu', 'Disetujui', 'Ditolak'],
                'default'    => 'Menunggu',
            ],

            // Owner yang meng-ACC (opsional)
            'id_owner' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],

            // Waktu persetujuan owner
            'tanggal_approve' => [
                'type' => 'DATETIME',
                'null' => true,
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

        $this->forge->addKey('id_restok', true);

        $this->forge->createTable('restok');
    }

    public function down()
    {
        $this->forge->dropTable('restok');
    }
}
