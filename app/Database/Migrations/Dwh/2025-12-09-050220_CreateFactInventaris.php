<?php

namespace App\Database\Migrations\Dwh;
use CodeIgniter\Database\Migration;

class CreateFactInventaris extends Migration
{
    protected $DBGroup = 'dwh';

    public function up()
    {
        $this->forge->addField([
            'id_fakta_inventaris' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            // --- Foreign Keys ke Dimensi ---
            'sk_waktu' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'sk_produk' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'sk_supplier' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true, // Null jika barang keluar (penjualan)
            ],
            'sk_lokasi' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            
            // --- Measures / Metrik ---
            'jenis_pergerakan' => [ // 'Masuk' (Restok) atau 'Keluar' (Terjual)
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'jumlah_masuk' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'jumlah_keluar' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'sisa_stok_harian' => [ // Snapshot stok akhir hari tersebut
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
        ]);
        $this->forge->addKey('id_fakta_inventaris', true);
        
        // Indexing untuk performa query analisis stok
        // $this->forge->addKey(['sk_waktu', 'sk_produk']); 

        $this->forge->createTable('Fact_Inventaris');
    }

    public function down()
    {
        $this->forge->dropTable('Fact_Inventaris');
    }
}