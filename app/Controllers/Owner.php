<?php

namespace App\Controllers;

use App\Models\PenjualanModel;
use App\Models\ProdukModel;
use App\Models\PelangganModel;
use App\Models\DetailPenjualanModel;
use App\Models\KeuanganModel;

class Owner extends BaseController
{
    protected $penjualanModel;
    protected $produkModel;
    protected $pelangganModel;
    protected $detailPenjualanModel;
    protected $keuanganModel;

    public function __construct()
    {
        $this->penjualanModel = new PenjualanModel();
        $this->produkModel = new ProdukModel();
        $this->pelangganModel = new PelangganModel();
        $this->detailPenjualanModel = new DetailPenjualanModel();
        $this->keuanganModel = new KeuanganModel();
        helper('number');
    }

    // Fungsi Helper untuk Menghitung Pertumbuhan (%)
    private function calculateGrowth($current, $previous)
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }
        return (($current - $previous) / $previous) * 100;
    }

    public function index()
    {
        // =================================================================
        // 1. KPI UTAMA & PERTUMBUHAN (GROWTH)
        // =================================================================
        
        $bulanIni = date('m');
        $tahunIni = date('Y');
        $bulanLalu = date('m', strtotime('-1 month'));
        $tahunLalu = date('Y', strtotime('-1 month'));

        // --- PENDAPATAN ---
        // Bulan Ini
        $pendapatanRow = $this->keuanganModel->selectSum('pemasukan')
            ->where('tipe', 'pemasukan')
            ->where('MONTH(tanggal)', $bulanIni)
            ->where('YEAR(tanggal)', $tahunIni)
            ->get()->getRow();
        // FIXED: Pastikan float dan handle null
        $totalPendapatan = ($pendapatanRow && $pendapatanRow->pemasukan !== null) ? (float)$pendapatanRow->pemasukan : 0.0;

        // Bulan Lalu
        $pendapatanLaluRow = $this->keuanganModel->selectSum('pemasukan')
            ->where('tipe', 'pemasukan')
            ->where('MONTH(tanggal)', $bulanLalu)
            ->where('YEAR(tanggal)', $tahunLalu)
            ->get()->getRow();
        $totalPendapatanLalu = ($pendapatanLaluRow && $pendapatanLaluRow->pemasukan !== null) ? (float)$pendapatanLaluRow->pemasukan : 0.0;

        // Hitung Growth Pendapatan
        $pendapatanGrowth = $this->calculateGrowth($totalPendapatan, $totalPendapatanLalu);


        // --- PENGELUARAN ---
        // Bulan Ini
        $pengeluaranRow = $this->keuanganModel->selectSum('pengeluaran')
            ->where('tipe', 'pengeluaran')
            ->where('MONTH(tanggal)', $bulanIni)
            ->where('YEAR(tanggal)', $tahunIni)
            ->get()->getRow();
        // FIXED: Pastikan float dan handle null
        $totalPengeluaran = ($pengeluaranRow && $pengeluaranRow->pengeluaran !== null) ? (float)$pengeluaranRow->pengeluaran : 0.0;

        // Bulan Lalu
        $pengeluaranLaluRow = $this->keuanganModel->selectSum('pengeluaran')
            ->where('tipe', 'pengeluaran')
            ->where('MONTH(tanggal)', $bulanLalu)
            ->where('YEAR(tanggal)', $tahunLalu)
            ->get()->getRow();
        $totalPengeluaranLalu = ($pengeluaranLaluRow && $pengeluaranLaluRow->pengeluaran !== null) ? (float)$pengeluaranLaluRow->pengeluaran : 0.0;

        // Hitung Growth Pengeluaran
        $pengeluaranGrowth = $this->calculateGrowth($totalPengeluaran, $totalPengeluaranLalu);


        // --- KEUNTUNGAN & LAINNYA ---
        $keuntunganBersih = $totalPendapatan - $totalPengeluaran;
        $totalPenjualan = $this->penjualanModel->countAllResults(); // Total sepanjang masa
        $totalProduk = $this->produkModel->countAllResults();
        $totalPelanggan = $this->pelangganModel->countAllResults();


        // =================================================================
        // 2. DATA GRAFIK & INSIGHT UTAMA
        // =================================================================

        // Grafik 1: Tren Penjualan
        $salesChartData = $this->penjualanModel
            ->select("DATE(tanggal) as tanggal, COUNT(id_penjualan) as jumlah")
            ->where('tanggal >=', date('Y-m-d', strtotime('-7 days')))
            ->groupBy("DATE(tanggal)")
            ->orderBy("DATE(tanggal)", "ASC")
            ->findAll();

        // Grafik 2: Produk Terlaris
        $topProductsData = $this->detailPenjualanModel
            ->select("produk.nama_produk, SUM(detail_penjualan.qty) as total_terjual")
            ->join("produk", "produk.id_produk = detail_penjualan.id_produk")
            ->groupBy("produk.id_produk")
            ->orderBy("total_terjual", "DESC")
            ->limit(5)
            ->findAll();

        // Grafik 3: Pendapatan Kategori (SAFE MODE - Hitung Manual)
        // Menggunakan left join dan hitung (qty * harga) karena kolom subtotal tdk ada
        $categorySalesData = $this->detailPenjualanModel
            ->select('kategori.nama_kategori, SUM(detail_penjualan.qty * detail_penjualan.harga_satuan) as total_pendapatan')
            ->join('produk', 'produk.id_produk = detail_penjualan.id_produk')
            ->join('kategori', 'kategori.id_kategori = produk.id_kategori', 'left')
            ->groupBy('kategori.nama_kategori')
            ->orderBy('total_pendapatan', 'DESC')
            ->limit(5)
            ->findAll();
            
        // Sanitasi data
        foreach ($categorySalesData as &$cat) {
            if (empty($cat['nama_kategori'])) { $cat['nama_kategori'] = 'Lainnya'; }
            $cat['total_pendapatan'] = (float)$cat['total_pendapatan']; 
        }

        // Insight 4: Stok Menipis
        $lowStockProducts = $this->produkModel
            ->select('produk.nama_produk, produk.stok, kategori.nama_kategori')
            ->join('kategori', 'kategori.id_kategori = produk.id_kategori', 'left')
            ->where('produk.stok <', 10)
            ->orderBy('produk.stok', 'ASC')
            ->limit(5)
            ->findAll();

        // Insight 5: Metode Pembayaran
        $paymentMethodData = $this->penjualanModel
            ->select('metode_pembayaran, COUNT(id_penjualan) as jumlah')
            ->where('metode_pembayaran !=', '') 
            ->groupBy('metode_pembayaran')
            ->orderBy('jumlah', 'DESC')
            ->findAll();

        // Insight 6: Top Pelanggan
        $topCustomers = $this->penjualanModel
            ->select('pelanggan.nama_pelanggan, COUNT(penjualan.id_penjualan) as total_transaksi, SUM(penjualan.total) as total_belanja')
            ->join('pelanggan', 'pelanggan.id_pelanggan = penjualan.id_pelanggan', 'left')
            ->where('penjualan.id_pelanggan !=', null)
            ->groupBy('penjualan.id_pelanggan')
            ->orderBy('total_belanja', 'DESC')
            ->limit(5)
            ->findAll();

        // --- 3. DEAD STOCK ANALYSIS (BARU) ---
        // Logika: Cari produk yg stok > 0 TAPI id_produknya TIDAK ADA di transaksi 90 hari terakhir
        
        // Subquery: Ambil ID produk yang TERJUAL dalam 90 hari terakhir
        $db = \Config\Database::connect();
        $subQuery = $db->table('detail_penjualan')
                       ->select('id_produk')
                       ->join('penjualan', 'penjualan.id_penjualan = detail_penjualan.id_penjualan')
                       ->where('penjualan.tanggal >=', date('Y-m-d', strtotime('-90 days')))
                       ->groupBy('id_produk')
                       ->getCompiledSelect();

        // Query Utama: Produk yg stok > 0 DAN id_produk NOT IN (Subquery)
        $deadStock = $this->produkModel
            ->select('produk.nama_produk, produk.stok, produk.harga as harga_jual, kategori.nama_kategori')
            ->join('kategori', 'kategori.id_kategori = produk.id_kategori', 'left')
            ->where('produk.stok >', 0)
            ->where("produk.id_produk NOT IN ($subQuery)", null, false) // false agar tidak di-escape
            ->limit(5)
            ->findAll();


        // =================================================================
        // 4. PACKING DATA
        // =================================================================

        $data = [
            'title'             => 'Dashboard Owner',
            
            'totalPenjualan'    => $totalPenjualan,
            'totalPendapatan'   => $totalPendapatan,
            'totalPengeluaran'  => $totalPengeluaran,
            'keuntunganBersih'  => $keuntunganBersih,
            
            // Data Pertumbuhan (Growth) untuk View
            'pendapatanGrowth'  => $pendapatanGrowth,
            'pengeluaranGrowth' => $pengeluaranGrowth,

            'totalProduk'       => $totalProduk,
            'totalPelanggan'    => $totalPelanggan,
            'lowStockProducts'  => $lowStockProducts,
            'topCustomers'      => $topCustomers,
            'deadStock'         => $deadStock, // Variabel baru dikirim
            
            'salesChartData'    => json_encode($salesChartData),
            'topProductsData'   => json_encode($topProductsData),
            'categorySalesData' => json_encode($categorySalesData),
            'paymentMethodData' => json_encode($paymentMethodData)
        ];

        return view('owner/dashboardowner', $data);
    }
}