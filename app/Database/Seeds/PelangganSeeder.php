<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class PelangganSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create('id_ID'); // Pakai locale Indonesia
        $data = [];

        // Buat 15 pelanggan dummy
        for ($i = 0; $i < 15; $i++) {
            $data[] = [
                'nama_pelanggan' => $faker->name,
                'no_hp'          => $faker->phoneNumber,
                'alamat'         => $faker->address,
                'created_at'     => Time::now()->toDateTimeString(),
                'updated_at'     => Time::now()->toDateTimeString(),
            ];
        }

        // Tambah pelanggan manual tetap (untuk testing pasti)
        $data[] = [
            'nama_pelanggan' => 'Budi Santoso',
            'no_hp'          => '081234567890',
            'alamat'         => 'Jl. Kayu Tangi No. 1 Banjarmasin',
            'created_at'     => Time::now()->toDateTimeString(),
            'updated_at'     => Time::now()->toDateTimeString(),
        ];

        $this->db->table('pelanggan')->insertBatch($data);
    }
}