<?php

namespace App\Database\Migrations\Dwh;
use CodeIgniter\Database\Migration;

class CreateFactPenjualan extends Migration
{
    protected $DBGroup = 'dwh';

    public function up()
    {
        $this->forge->addField([
            'id_fakta_penjualan'    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'sk_waktu'              => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'sk_produk'             => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'sk_pembeli'            => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'sk_lokasi'             => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'no_transaksi_sumber'   => ['type' => 'VARCHAR', 'constraint' => 50],
            'jumlah_barang_terjual' => ['type' => 'INT', 'constraint' => 11],
            'total_omzet'           => ['type' => 'DECIMAL', 'constraint' => '15,2'],
            'profit_kotor'          => ['type' => 'DECIMAL', 'constraint' => '15,2'],
            'status_pembayaran'     => ['type' => 'VARCHAR', 'constraint' => 50],
        ]);
        $this->forge->addKey('id_fakta_penjualan', true);
        
        // Opsional: Tambahkan Foreign Key agar struktur kuat
        // $this->forge->addForeignKey('sk_waktu', 'dim_waktu', 'sk_waktu');
        
        $this->forge->createTable('Fact_Penjualan');
    }

    public function down()
    {
        $this->forge->dropTable('Fact_Penjualan');
    }
}