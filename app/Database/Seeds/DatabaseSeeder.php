<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->db->query('SET FOREIGN_KEY_CHECKS=0;');

        $tables = [
            'stok_log',
            'detail_penjualan',
            'pemasokan',
            'keuangan',
            'penjualan',
            'produk',
            'supplier',
            'kategori',
            'user',
            'pelanggan',
            'restok'
        ];

        foreach ($tables as $table) {
            $this->db->query("TRUNCATE TABLE `$table`;");
        }

        
        $this->db->query('SET FOREIGN_KEY_CHECKS=1;');

        // 4. Jalankan seeder sesuai urutan relasi
        $this->call('UserSeeder');
        $this->call('KategoriSeeder');
        $this->call('SupplierSeeder');
        $this->call('ProdukSeeder');
        $this->call('RestokSeeder');
        $this->call('PelangganSeeder');
        $this->call('TransaksiSeeder');
        $this->call('PengeluaranSeeder');
    }
}
