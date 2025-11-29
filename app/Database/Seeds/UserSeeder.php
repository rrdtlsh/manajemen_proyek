<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
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
            [

                'username' => 'dosentester',
                'password' => password_hash('12345', PASSWORD_DEFAULT),
                'role'     => 'Pemilik'
            ],
            [   /* PM only, buat testing" :D*/
                'username' => 'eza',
                'password' => password_hash('q1w2e3r4t5', PASSWORD_DEFAULT),
                'role'     => 'Superadmin' 
                /* PM only, buat testing" :D*/
            ],
        ];

        $this->db->table('user')->insertBatch($data);
    }
}