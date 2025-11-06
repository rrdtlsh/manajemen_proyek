<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama_supplier' => 'PT. Jaya Abadi',
                'alamat'        => 'Jl. Veteran No. 12, Banjarmasin',
                'kontak'        => '081234567890'
            ],
            [
                'nama_supplier' => 'CV. Berkah Mandiri',
                'alamat'        => 'Jl. A. Yani Km. 5, Banjarmasin',
                'kontak'        => '081198765432'
            ],
            [
                'nama_supplier' => 'UD. Sinar Terang',
                'alamat'        => 'Jl. Belitung Darat No. 45',
                'kontak'        => '085211223344'
            ],
        ];

        // Memasukkan data ke tabel 'supplier'
        $this->db->table('supplier')->insertBatch($data);
    }
}