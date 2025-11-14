<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // 1. Matikan pengecekan foreign key
        $this->db->query('SET FOREIGN_KEY_CHECKS=0;');

        // 2. Kosongkan tabel (TRUNCATE) secara langsung dengan query SQL
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

        // 3. Aktifkan kembali foreign key
        $this->db->query('SET FOREIGN_KEY_CHECKS=1;');

        // 4. Jalankan seeder sesuai urutan relasi
        $this->call('UserSeeder');
        $this->call('KategoriSeeder');
        $this->call('SupplierSeeder');
        $this->call('ProdukSeeder');
        // Tambahkan seeder lainnya jika ada
    }
}
