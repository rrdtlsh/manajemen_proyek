<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class KategoriSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['nama_kategori' => 'Karpet'], // Akan mendapat id_kategori = 1
            ['nama_kategori' => 'Sprei'],  // Akan mendapat id_kategori = 2
            ['nama_kategori' => 'Gorden'], // Akan mendapat id_kategori = 3
            ['nama_kategori' => 'Sajadah'], // Akan mendapat id_kategori = 4
        ];

        // Memasukkan data ke tabel 'kategori'
        $this->db->table('kategori')->insertBatch($data);
    }
}
