<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class PengeluaranSeeder extends Seeder
{
    public function run()
    {
        $data = [];
        
        // [PERBAIKAN] Ambil ID User dengan cara yang benar untuk Query Builder
        $query   = $this->db->table('user')->select('id_user')->get();
        $results = $query->getResultArray(); // Hasil: [['id_user' => 1], ['id_user' => 2]]
        
        // Ubah menjadi array sederhana: [1, 2]
        $userIds = array_column($results, 'id_user');

        // Fallback jika tabel user kosong
        if (empty($userIds)) {
            $userIds = [1];
        }

        // Kita buat data pengeluaran untuk 3 bulan terakhir
        for ($i = 0; $i < 3; $i++) {
            $bulan = Time::now()->subMonths($i);
            $tahunBulan = $bulan->format('Y-m'); // Contoh: 2025-10

            // 1. Biaya Listrik (Tanggal 5 setiap bulan)
            $data[] = [
                'tanggal'     => $tahunBulan . '-05',
                'tipe'        => 'Pengeluaran',
                'pemasukan'   => 0,
                'pengeluaran' => rand(300000, 500000), // 300rb - 500rb
                'keterangan'  => 'Tagihan Listrik Periode ' . $bulan->format('F Y'),
                'id_user'     => $userIds[array_rand($userIds)],
            ];

            // 2. Biaya Internet (Tanggal 10 setiap bulan)
            $data[] = [
                'tanggal'     => $tahunBulan . '-10',
                'tipe'        => 'Pengeluaran',
                'pemasukan'   => 0,
                'pengeluaran' => 350000, // Tetap
                'keterangan'  => 'Internet WiFi ' . $bulan->format('F Y'),
                'id_user'     => $userIds[array_rand($userIds)],
            ];

            // 3. Gaji Karyawan (Tanggal 25 setiap bulan)
            $data[] = [
                'tanggal'     => $tahunBulan . '-25',
                'tipe'        => 'Pengeluaran',
                'pemasukan'   => 0,
                'pengeluaran' => 7500000, 
                'keterangan'  => 'Gaji Karyawan (3 Orang) ' . $bulan->format('F Y'),
                'id_user'     => $userIds[array_rand($userIds)],
            ];

            // 4. Biaya Tak Terduga (Random tanggal)
            for ($j=0; $j < 2; $j++) { 
                $tglRandom = rand(1, 28);
                $data[] = [
                    'tanggal'     => $tahunBulan . '-' . sprintf("%02d", $tglRandom),
                    'tipe'        => 'Pengeluaran',
                    'pemasukan'   => 0,
                    'pengeluaran' => rand(50000, 150000),
                    'keterangan'  => 'Biaya Konsumsi/Kebersihan Harian',
                    'id_user'     => $userIds[array_rand($userIds)],
                ];
            }
        }

        // Insert ke tabel keuangan
        // Pastikan array data tidak kosong sebelum insert batch
        if (!empty($data)) {
            $this->db->table('keuangan')->insertBatch($data);
        }
    }
}