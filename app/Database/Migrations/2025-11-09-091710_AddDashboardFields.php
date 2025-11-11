<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDashboardFields extends Migration
{
    public function up()
    {
        // 1. Menambahkan kolom ke tabel 'penjualan'
        $fieldsPenjualan = [
            'status_bayar' => [
                'type'       => 'ENUM',
                'constraint' => ['lunas', 'belum_lunas'],
                'default'    => 'belum_lunas',
                'after'      => 'total', 
            ],
            'metode_pembayaran' => [
                'type'       => 'ENUM',
                'constraint' => ['cash', 'transfer'],
                'default'    => 'cash',
                'after'      => 'status_bayar',
            ],
        ];
        $this->forge->addColumn('penjualan', $fieldsPenjualan);

        // 2. Menambahkan kolom ke tabel 'produk'
        $fieldsProduk = [
            'kategori' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true, // Boleh kosong untuk produk yang sudah ada
                'after'      => 'nama_produk', // Ditempatkan setelah nama_produk
            ],
        ];
        $this->forge->addColumn('produk', $fieldsProduk);
    }

    public function down()
    {
        // Kebalikan dari 'up', untuk jika perlu rollback
        
        // Hapus kolom dari 'penjualan'
        $this->forge->dropColumn('penjualan', 'status_bayar');
        $this->forge->dropColumn('penjualan', 'metode_pembayaran');

        // Hapus kolom dari 'produk'
        $this->forge->dropColumn('produk', 'kategori');
    }
}