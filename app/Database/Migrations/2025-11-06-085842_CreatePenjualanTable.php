<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePenjualanTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_penjualan' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'tanggal' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'total' => [
                'type' => 'DOUBLE',
                'null' => false,
            ],

            // 🔹 TAMBAHAN SESUAI PERMINTAAN
            'status_bayar' => [
                'type'       => "ENUM('lunas','belum_lunas')",
                'default'    => 'belum_lunas',
                'null'       => false,
            ],
            'metode_pembayaran' => [
                'type'       => "ENUM('cash','transfer')",
                'default'    => 'cash',
                'null'       => false,
            ],

            'status_pembayaran' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'jumlah_dp' => [
                'type' => 'DOUBLE',
                'null' => true,
            ],
            'id_user' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
        ]);

        $this->forge->addKey('id_penjualan', true);

        $this->forge->addForeignKey(
            'id_user',
            'user',
            'id_user',
            'SET NULL',
            'CASCADE',
            'penjualan_id_user_foreign'
        );

        $this->forge->createTable('penjualan');
    }

    public function down()
    {
        $this->forge->dropForeignKey('penjualan', 'penjualan_id_user_foreign');
        $this->forge->dropTable('penjualan');
    }
}
