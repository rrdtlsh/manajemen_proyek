<?php

namespace App\Controllers;

use App\Models\PenjualanModel;
use App\Models\ProdukModel;
use CodeIgniter\I18n\Time; // Untuk mengelola tanggal

class Owner extends BaseController
{
        public function index()
        {
            $penjualanModel = new PenjualanModel();
            $produkModel = new ProdukModel();
            $db = \Config\Database::connect(); // Panggil database

            // === 1. DATA UNTUK KPI CARDS ===

            // Ambil tanggal awal dan akhir bulan ini
            $bulanIniAwal = Time::now()->setDay(1)->toDateString(); // 'YYYY-MM-01'
            $bulanIniAkhir = Time::now()->modify('last day of this month')->toDateString();
            
            // Total Pendapatan (Bulan Ini)
            $totalPendapatan = $penjualanModel
                ->where('created_at >=', $bulanIniAwal . ' 00:00:00')
                ->where('created_at <=', $bulanIniAkhir . ' 23:59:59')
                ->selectSum('total_harga')
                ->get()
                ->getRow()
                ->total_harga ?? 0; // Ambil hasil sum, jika null ganti 0

            // Total Transaksi (Hari Ini)
            $totalTransaksi = $penjualanModel
                ->where('created_at >=', $hariIni . ' 00:00:00')
                ->where('created_at <=', $hariIni . ' 23:59:59')
                ->countAllResults(); // Hitung jumlah baris/transaksi

            // Produk akan habis (Stok < 10)
            $stokMenipis = $produkModel
                ->where('stok <', 10)
                ->countAllResults();

            // === 2. DATA UNTUK PRODUK TERLARIS ===
            $produkTerlaris = $db->table('detail_penjualan')
                ->select('produk.nama_produk, SUM(detail_penjualan.jumlah) as total_terjual')
                ->join('produk', 'produk.id = detail_penjualan.produk_id')
                ->groupBy('produk.nama_produk')
                ->orderBy('total_terjual', 'DESC')
                ->limit(5)
                ->get()
                ->getResultArray();

            // === 3. DATA UNTUK GRAFIK PENJUALAN (7 Hari Terakhir) ===
            $grafikData = $penjualanModel
                ->select("DATE(created_at) as tanggal, SUM(total_harga) as total")
                ->where('created_at >=', Time::now()->subDays(7)->toDateString())
                ->groupBy('DATE(created_at)')
                ->orderBy('tanggal', 'ASC')
                ->get()
                ->getResultArray();

            // Format data untuk Chart.js
            $grafikLabels = [];
            $grafikValues = [];
            foreach ($grafikData as $row) {
                $grafikLabels[] = Time::parse($row['tanggal'])->toFormattedDateString('d M'); // Format "09 Nov"
                $grafikValues[] = $row['total'];
            }

            // Kumpulkan semua data untuk dikirim ke view
            $data = [
                'title'             => 'Dashboard Owner',
                'totalPendapatan'   => $totalPendapatan,
                'totalTransaksi'    => $totalTransaksi,
                'stokMenipis'       => $stokMenipis,
                'produkTerlaris'    => $produkTerlaris,
                'grafikLabels'      => $grafikLabels,
                'grafikValues'      => $grafikValues,
            ];

            return view('dashboardowner', $data);
        }
}