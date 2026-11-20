<?php

namespace App\Database\Seeds\Dwh;

use CodeIgniter\Database\Seeder;

class DimWaktuSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect('dwh'); // Koneksi ke DWH
        $builder = $db->table('dim_waktu');

        $startDate = strtotime('2024-01-01');
        $endDate   = strtotime('2030-12-31');

        $data = [];
        $currentDate = $startDate;

        while ($currentDate <= $endDate) {
            $dateValue = date('Y-m-d', $currentDate);
            
            $data[] = [
                'tanggal'    => $dateValue,
                'hari'       => date('d', $currentDate),
                'nama_hari'  => $this->translateHari(date('l', $currentDate)),
                'minggu_ke'  => date('W', $currentDate),
                'bulan'      => date('n', $currentDate),
                'nama_bulan' => $this->translateBulan(date('F', $currentDate)),
                'kuartal'    => ceil(date('n', $currentDate) / 3),
                'tahun'      => date('Y', $currentDate),
            ];

            if (count($data) >= 500) {
                $builder->insertBatch($data);
                $data = [];
            }

            $currentDate = strtotime('+1 day', $currentDate);
        }

        // Insert sisa data
        if (!empty($data)) {
            $builder->insertBatch($data);
        }
    }

    private function translateHari($day) {
        $days = ['Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'];
        return $days[$day] ?? $day;
    }

    private function translateBulan($month) {
        $months = ['January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret', 'April' => 'April', 'May' => 'Mei', 'June' => 'Juni', 'July' => 'Juli', 'August' => 'Agustus', 'September' => 'September', 'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'];
        return $months[$month] ?? $month;
    }
}