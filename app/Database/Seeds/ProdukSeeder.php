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
            [
                'kode_produk'   => 'KP-003',
                'nama_produk'   => 'Karpet Premium Turki',
                'id_kategori'   => 1,
                'id_supplier'   => 2,
                'harga'         => 2500000,
                'stok'          => 15,
                'tanggal_masuk' => date('Y-m-d'),
                'gambar_produk' => 'default.jpg',
            ],
            [
                'kode_produk'   => 'KP-004',
                'nama_produk'   => 'Karpet Bulu Rasfur',
                'id_kategori'   => 1,
                'id_supplier'   => 3,
                'harga'         => 550000,
                'stok'          => 60,
                'tanggal_masuk' => date('Y-m-d'),
                'gambar_produk' => 'default.jpg',
            ],
            [
                'kode_produk'   => 'KP-005',
                'nama_produk'   => 'Karpet Shaggy Tebal',
                'id_kategori'   => 1,
                'id_supplier'   => 1,
                'harga'         => 650000,
                'stok'          => 30,
                'tanggal_masuk' => date('Y-m-d'),
                'gambar_produk' => 'default.jpg',
            ],
            [
                'kode_produk'   => 'SP-002',
                'nama_produk'   => 'Sprei King Size Premium',
                'id_kategori'   => 2,
                'id_supplier'   => 1,
                'harga'         => 450000,
                'stok'          => 35,
                'tanggal_masuk' => date('Y-m-d'),
                'gambar_produk' => 'default.jpg',
            ],
            [
                'kode_produk'   => 'SP-003',
                'nama_produk'   => 'Sprei Motif Polkadot',
                'id_kategori'   => 2,
                'id_supplier'   => 2,
                'harga'         => 300000,
                'stok'          => 50,
                'tanggal_masuk' => date('Y-m-d'),
                'gambar_produk' => 'default.jpg',
            ],
            [
                'kode_produk'   => 'SP-004',
                'nama_produk'   => 'Sprei Katun Lokal',
                'id_kategori'   => 2,
                'id_supplier'   => 3,
                'harga'         => 200000,
                'stok'          => 80,
                'tanggal_masuk' => date('Y-m-d'),
                'gambar_produk' => 'default.jpg',
            ],
            [
                'kode_produk'   => 'SP-005',
                'nama_produk'   => 'Sprei Anti Luntur',
                'id_kategori'   => 2,
                'id_supplier'   => 1,
                'harga'         => 280000,
                'stok'          => 55,
                'tanggal_masuk' => date('Y-m-d'),
                'gambar_produk' => 'default.jpg',
            ],
            [
                'kode_produk'   => 'GD-002',
                'nama_produk'   => 'Gorden Motif Minimalis',
                'id_kategori'   => 3,
                'id_supplier'   => 2,
                'harga'         => 220000,
                'stok'          => 70,
                'tanggal_masuk' => date('Y-m-d'),
                'gambar_produk' => 'default.jpg',
            ],
            [
                'kode_produk'   => 'GD-003',
                'nama_produk'   => 'Gorden Transparan (Vitras)',
                'id_kategori'   => 3,
                'id_supplier'   => 3,
                'harga'         => 180000,
                'stok'          => 90,
                'tanggal_masuk' => date('Y-m-d'),
                'gambar_produk' => 'default.jpg',
            ],
            [
                'kode_produk'   => 'GD-004',
                'nama_produk'   => 'Gorden Karakter Anak',
                'id_kategori'   => 3,
                'id_supplier'   => 1,
                'harga'         => 250000,
                'stok'          => 65,
                'tanggal_masuk' => date('Y-m-d'),
                'gambar_produk' => 'default.jpg',
            ],
            [
                'kode_produk'   => 'GD-005',
                'nama_produk'   => 'Gorden Luxury Emboss',
                'id_kategori'   => 3,
                'id_supplier'   => 2,
                'harga'         => 350000,
                'stok'          => 40,
                'tanggal_masuk' => date('Y-m-d'),
                'gambar_produk' => 'default.jpg',
            ],
            [
                'kode_produk'   => 'SJ-001',
                'nama_produk'   => 'Sajadah Turki Premium',
                'id_kategori'   => 4,
                'id_supplier'   => 1,
                'harga'         => 280000,
                'stok'          => 70,
                'tanggal_masuk' => date('Y-m-d'),
                'gambar_produk' => 'default.jpg',
            ],
            [
                'kode_produk'   => 'SJ-002',
                'nama_produk'   => 'Sajadah Travel Portable',
                'id_kategori'   => 4,
                'id_supplier'   => 2,
                'harga'         => 90000,
                'stok'          => 120,
                'tanggal_masuk' => date('Y-m-d'),
                'gambar_produk' => 'default.jpg',
            ],
            [
                'kode_produk'   => 'SJ-003',
                'nama_produk'   => 'Sajadah Bulu Lembut',
                'id_kategori'   => 4,
                'id_supplier'   => 3,
                'harga'         => 150000,
                'stok'          => 85,
                'tanggal_masuk' => date('Y-m-d'),
                'gambar_produk' => 'default.jpg',
            ],
            [
                'kode_produk'   => 'SJ-004',
                'nama_produk'   => 'Sajadah Ringan Anti Slip',
                'id_kategori'   => 4,
                'id_supplier'   => 1,
                'harga'         => 110000,
                'stok'          => 140,
                'tanggal_masuk' => date('Y-m-d'),
                'gambar_produk' => 'default.jpg',
            ],
            [
                'kode_produk'   => 'SJ-005',
                'nama_produk'   => 'Sajadah Motif Mekkah',
                'id_kategori'   => 4,
                'id_supplier'   => 2,
                'harga'         => 170000,
                'stok'          => 100,
                'tanggal_masuk' => date('Y-m-d'),
                'gambar_produk' => 'default.jpg',
            ]

        ];

        $this->db->query('SET FOREIGN_KEY_CHECKS=0;');

        $this->db->query('SET FOREIGN_KEY_CHECKS=1;');

        $this->db->table('produk')->insertBatch($data);
    }
}
