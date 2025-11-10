<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPelangganToPenjualan extends Migration
{
    public function up()
    {
        $fields = [
            'id_pelanggan' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true, // Bolehkan 'null' jika transaksi tidak ada pelanggan
                'after'      => 'id_user',
            ],
        ];

        $this->forge->addColumn('penjualan', $fields);

        // Tambahkan foreign key
        $this->db->query("ALTER TABLE `penjualan` ADD CONSTRAINT `penjualan_pelanggan_fk` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan`(`id_pelanggan`) ON DELETE SET NULL ON UPDATE CASCADE");
    }

    public function down()
    {
        $this->forge->dropForeignKey('penjualan', 'penjualan_pelanggan_fk');
        $this->forge->dropColumn('penjualan', 'id_pelanggan');
    }
}
