<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Memanggil seeder lain secara berurutan
        $this->call('UserSeeder');
        $this->call('SupplierSeeder');
        $this->call('ProdukSeeder');
    }
}