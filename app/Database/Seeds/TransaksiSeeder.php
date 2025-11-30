<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class TransaksiSeeder extends Seeder
{
    public function run()
    {
        // ambil ID Pelanggan & User yang ada
        $pelangganIds = $this->db->table('pelanggan')->select('id_pelanggan')->get()->getResultArray();
        $userIds      = $this->db->table('user')->select('id_user')->get()->getResultArray();
        
        // ambil produk (ID & Harga)
        $produkList   = $this->db->table('produk')->select('id_produk, harga, stok')->get()->getResultArray();

        if (empty($pelangganIds) || empty($produkList)) {
            echo "Mohon jalankan PelangganSeeder dan ProdukSeeder terlebih dahulu.\n";
            return;
        }

        $dataPenjualan = [];
        $dataDetail    = [];
        $dataKeuangan  = [];

        // 20 transaksi random
        for ($i = 1; $i <= 20; $i++) {
            
            $randomDays = rand(0, 30);
            $tanggal    = Time::now()->subDays($randomDays)->toDateString();
            
            $idPelanggan = $pelangganIds[array_rand($pelangganIds)]['id_pelanggan'];
            $idUser      = $userIds[array_rand($userIds)]['id_user'] ?? 1; // Default admin id 1

            $jumlahJenisBarang = rand(1, 3);
            $totalTransaksi    = 0;
            $tempDetail        = [];

            for ($j = 0; $j < $jumlahJenisBarang; $j++) {
                $produk = $produkList[array_rand($produkList)];
                $qty    = rand(1, 3);
                $harga  = $produk['harga'];
                $subtotal = $harga * $qty;

                $totalTransaksi += $subtotal;

                $tempDetail[] = [
                    'id_produk'    => $produk['id_produk'],
                    'qty'          => $qty,
                    'harga_satuan' => $harga
                ];
            }

            $isLunas = (rand(1, 10) > 2); 
            $status  = $isLunas ? 'Lunas' : 'Belum Lunas';
            $metode  = (rand(1, 2) == 1) ? 'cash' : 'transfer';
            
            $dp = 0;
            if (!$isLunas) {
                $dp = round($totalTransaksi * 0.3, -3); // DP 30%
            }

            $this->db->table('penjualan')->insert([
                'tanggal'           => $tanggal,
                'total'             => $totalTransaksi,
                'status_bayar'      => $isLunas ? 'lunas' : 'belum_lunas', // Enum
                'metode_pembayaran' => $metode,
                'status_pembayaran' => $status, // Varchar (untuk display)
                'jumlah_dp'         => $isLunas ? 0 : $dp,
                'id_user'           => $idUser,
                'id_pelanggan'      => $idPelanggan
            ]);

            $idPenjualanBaru = $this->db->insertID();

            foreach ($tempDetail as $detail) {
                $dataDetail[] = [
                    'id_penjualan' => $idPenjualanBaru,
                    'id_produk'    => $detail['id_produk'],
                    'qty'          => $detail['qty'],
                    'harga_satuan' => $detail['harga_satuan']
                ];
            }

            $uangMasuk = $isLunas ? $totalTransaksi : $dp;
            $dataKeuangan[] = [
                'tanggal'     => $tanggal,
                'tipe'        => $isLunas ? 'Pemasukan' : 'DP',
                'pemasukan'   => $uangMasuk,
                'pengeluaran' => 0,
                'keterangan'  => 'Penjualan #' . $idPenjualanBaru,
                'id_user'     => $idUser
            ];
        }

        if (!empty($dataDetail)) {
            $this->db->table('detail_penjualan')->insertBatch($dataDetail);
        }
        if (!empty($dataKeuangan)) {
            $this->db->table('keuangan')->insertBatch($dataKeuangan);
        }
    }
}