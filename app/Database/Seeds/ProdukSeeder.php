<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProdukSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'kode_produk'   => 'KP-001',
                'nama_produk'   => 'Karpet selimut',
                'id_kategori'   => 1, // Merujuk ke 'Karpet'
                'id_supplier'   => 1, // Merujuk ke 'PT. Jaya Abadi'
                'harga'         => 750000,
                'stok'          => 25,
                'tanggal_masuk' => date('Y-m-d'),
                'gambar_produk' => 'default.jpg',
            ],
            [
                'kode_produk'   => 'KP-002',
                'nama_produk'   => 'Karpet Motif Bunga',
                'id_kategori'   => 1, // Merujuk ke 'Karpet'
                'id_supplier'   => 2, // Merujuk ke 'CV. Berkah Mandiri'
                'harga'         => 1000000,
                'stok'          => 50,
                'tanggal_masuk' => date('Y-m-d'),
                'gambar_produk' => 'default.jpg',
            ],
            [
                'kode_produk'   => 'SP-001',
                'nama_produk'   => 'Sprei Katun Jepang',
                'id_kategori'   => 2, // Merujuk ke 'Sprei'
                'id_supplier'   => 3, // Merujuk ke 'UD. Sinar Terang'
                'harga'         => 350000,
                'stok'          => 40,
                'tanggal_masuk' => date('Y-m-d'),
                'gambar_produk' => 'default.jpg',
            ],
            [
                'kode_produk'   => 'GD-001',
                'nama_produk'   => 'Gorden Blackout',
                'id_kategori'   => 3, // Merujuk ke 'Gorden'
                'id_supplier'   => 1, // Merujuk ke 'PT. Jaya Abadi'
                'harga'         => 150000,
                'stok'          => 100,
                'tanggal_masuk' => date('Y-m-d'),
                'gambar_produk' => 'default.jpg',
            ],
        ];

        $this->db->query('SET FOREIGN_KEY_CHECKS=0;');

        $this->db->query('SET FOREIGN_KEY_CHECKS=1;');

        $this->db->table('produk')->insertBatch($data);
    }
}
