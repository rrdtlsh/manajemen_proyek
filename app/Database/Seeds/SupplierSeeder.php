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
                'no_telp'       => '081234567890' // [PERBAIKAN] dari 'kontak'
            ],
            [
                'nama_supplier' => 'CV. Berkah Mandiri',
                'alamat'        => 'Jl. A. Yani Km. 5, Banjarmasin',
                'no_telp'       => '081198765432' // [PERBAIKAN] dari 'kontak'
            ],
            [
                'nama_supplier' => 'UD. Sinar Terang',
                'alamat'        => 'Jl. Belitung Darat No. 45',
                'no_telp'       => '085211223344' // [PERBAIKAN] dari 'kontak'
            ],
        ];

        // Memasukkan data ke tabel 'supplier'
        $this->db->table('supplier')->insertBatch($data);
    }
}
