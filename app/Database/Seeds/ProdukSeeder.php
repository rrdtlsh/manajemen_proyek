<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProdukSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama_produk'     => 'Karpet selimut',
                'harga'           => 750000,
                'stok'            => 25,
                'kategori_produk' => 'Karpet'
            ],
            [
                'nama_produk'     => 'Karpet Motif Bunga',
                'harga'           => 1000000,
                'stok'            => 50,
                'kategori_produk' => 'Karpet'
            ],
            [
                'nama_produk'     => 'Sprei Katun Jepang',
                'harga'           => 350000,
                'stok'            => 40,
                'kategori_produk' => 'Sprei'
            ],
            [
                'nama_produk'     => 'Gorden Blackout',
                'harga'           => 150000,
                'stok'            => 100,
                'kategori_produk' => 'Gorden'
            ],
        ];

        // Memasukkan data ke tabel 'produk'
        $this->db->table('produk')->insertBatch($data);
    }
}