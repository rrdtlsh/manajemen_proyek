<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class PengeluaranSeeder extends Seeder
{
    public function run()
    {
        $data = [];
        
        $query   = $this->db->table('user')->select('id_user')->get();
        $results = $query->getResultArray(); 
        
        
        $userIds = array_column($results, 'id_user');

        // fallback jika tabel user kosong
        if (empty($userIds)) {
            $userIds = [1];
        }

        // data dummy 3 bulan terakhir
        for ($i = 0; $i < 3; $i++) {
            $bulan = Time::now()->subMonths($i);
            $tahunBulan = $bulan->format('Y-m'); 

            //pln
            $data[] = [
                'tanggal'     => $tahunBulan . '-05',
                'tipe'        => 'Pengeluaran',
                'pemasukan'   => 0,
                'pengeluaran' => rand(300000, 500000), // 300rb - 500rb
                'keterangan'  => 'Tagihan Listrik Periode ' . $bulan->format('F Y'),
                'id_user'     => $userIds[array_rand($userIds)],
            ];

            // internet
            $data[] = [
                'tanggal'     => $tahunBulan . '-10',
                'tipe'        => 'Pengeluaran',
                'pemasukan'   => 0,
                'pengeluaran' => 350000, // Tetap
                'keterangan'  => 'Internet WiFi ' . $bulan->format('F Y'),
                'id_user'     => $userIds[array_rand($userIds)],
            ];

            // gaji karyawan
            $data[] = [
                'tanggal'     => $tahunBulan . '-25',
                'tipe'        => 'Pengeluaran',
                'pemasukan'   => 0,
                'pengeluaran' => 7500000, 
                'keterangan'  => 'Gaji Karyawan (3 Orang) ' . $bulan->format('F Y'),
                'id_user'     => $userIds[array_rand($userIds)],
            ];

            // biaya tak terduga
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
        if (!empty($data)) {
            $this->db->table('keuangan')->insertBatch($data);
        }
    }
}