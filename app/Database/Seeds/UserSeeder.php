<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Siapkan data user
        $data = [
            [
                'username' => 'penjualan',
                'password' => password_hash('12345', PASSWORD_DEFAULT),
                'role'     => 'Penjualan'
            ],
            [
                'username' => 'keuangan',
                'password' => password_hash('12345', PASSWORD_DEFAULT),
                'role'     => 'Keuangan'
            ],
            [
                'username' => 'inventaris',
                'password' => password_hash('12345', PASSWORD_DEFAULT),
                'role'     => 'Inventaris'
            ],
            [
                'username' => 'owner',  
                'password' => password_hash('12345', PASSWORD_DEFAULT),
                'role'     => 'Pemilik'
            ],
        ];

        // Menggunakan Query Builder untuk insert data
        // Ini akan memasukkan semua data dalam $data ke tabel 'user'
        $this->db->table('user')->insertBatch($data);
    }
}