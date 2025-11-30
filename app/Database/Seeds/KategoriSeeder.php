<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class KategoriSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['nama_kategori' => 'Karpet'], // id_kategori = 1
            ['nama_kategori' => 'Sprei'],  // 2
            ['nama_kategori' => 'Gorden'], // 3
            ['nama_kategori' => 'Sajadah'], // 4
        ];

        $this->db->table('kategori')->insertBatch($data);
    }
}
