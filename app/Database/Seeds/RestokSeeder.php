<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class RestokSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // 1. Kasus Menunggu (Baru diajukan staf)
            [
                'nama_supplier'   => 'PT. Jaya Abadi',
                'nama_barang'     => 'Karpet Selimut Tebal',
                'qty'             => 50,
                'harga_satuan'    => 125000,
                'total_harga'     => 6250000,
                'status'          => 'Menunggu',
                'status_owner'    => 'Menunggu',
                'id_owner'        => null,
                'tanggal_approve' => null,
                'created_at'      => Time::now()->subDays(1)->toDateTimeString(),
            ],
            // 2. Kasus Menunggu (Item lain)
            [
                'nama_supplier'   => 'CV. Berkah Mandiri',
                'nama_barang'     => 'Sprei Katun Jepang King',
                'qty'             => 20,
                'harga_satuan'    => 250000,
                'total_harga'     => 5000000,
                'status'          => 'Menunggu',
                'status_owner'    => 'Menunggu',
                'id_owner'        => null,
                'tanggal_approve' => null,
                'created_at'      => Time::now()->subHours(5)->toDateTimeString(),
            ],
            // 3. Kasus Disetujui Owner
            [
                'nama_supplier'   => 'UD. Sinar Terang',
                'nama_barang'     => 'Gorden Blackout Polos',
                'qty'             => 100,
                'harga_satuan'    => 75000,
                'total_harga'     => 7500000,
                'status'          => 'Disetujui',
                'status_owner'    => 'Disetujui',
                'id_owner'        => 4, // Asumsi ID Owner = 4
                'tanggal_approve' => Time::now()->subDays(3)->toDateTimeString(),
                'created_at'      => Time::now()->subDays(4)->toDateTimeString(),
            ],
            // 4. Kasus Ditolak Owner
            [
                'nama_supplier'   => 'PT. Jaya Abadi',
                'nama_barang'     => 'Sajadah Travel Tipis',
                'qty'             => 200,
                'harga_satuan'    => 15000,
                'total_harga'     => 3000000,
                'status'          => 'Ditolak',
                'status_owner'    => 'Ditolak',
                'id_owner'        => 4,
                'tanggal_approve' => Time::now()->subDays(2)->toDateTimeString(),
                'created_at'      => Time::now()->subDays(2)->toDateTimeString(),
            ],
            // 5. Kasus Menunggu (Baru banget)
            [
                'nama_supplier'   => 'CV. Berkah Mandiri',
                'nama_barang'     => 'Bedcover Set Rumbai',
                'qty'             => 10,
                'harga_satuan'    => 450000,
                'total_harga'     => 4500000,
                'status'          => 'Menunggu',
                'status_owner'    => 'Menunggu',
                'id_owner'        => null,
                'tanggal_approve' => null,
                'created_at'      => Time::now()->toDateTimeString(),
            ],
        ];

        // Insert Batch agar cepat
        $this->db->table('restok')->insertBatch($data);
    }
}