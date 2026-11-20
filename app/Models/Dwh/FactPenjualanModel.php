<?php

namespace App\Models\Dwh;

use CodeIgniter\Model;

class FactPenjualanModel extends Model
{
    // Konfigurasi Koneksi ke Database Warehouse
    protected $DBGroup          = 'dwh'; 
    protected $table            = 'Fact_Penjualan'; 
    protected $primaryKey       = 'id_fakta_penjualan';
    protected $useAutoIncrement = true;
    protected $allowedFields    = [
        'sk_waktu', 'sk_produk', 'sk_pembeli', 'sk_lokasi', 
        'jumlah_barang_terjual', 'total_omzet', 'profit_kotor', 'no_transaksi_sumber',
        'status_pembayaran', 'metode_pembayaran'
    ];

    /**
     * 1. Ambil KPI (Pendapatan, Profit, Total Transaksi) per Bulan
     * Dipanggil oleh Owner::index()
     */
    public function getKpiBulanan($bulan, $tahun)
    {
        // Join ke Dimensi Waktu untuk filter bulan & tahun
        return $this->selectSum('total_omzet', 'pendapatan')
                    ->selectSum('profit_kotor', 'keuntungan')
                    ->selectCount('id_fakta_penjualan', 'total_transaksi') // Hitung jumlah baris transaksi
                    ->join('dim_waktu', 'dim_waktu.sk_waktu = Fact_Penjualan.sk_waktu')
                    ->where('dim_waktu.bulan', $bulan)
                    ->where('dim_waktu.tahun', $tahun)
                    ->get()->getRowArray();
    }

    /**
     * 2. Ambil Data Tren Penjualan Harian (7 Hari Terakhir)
     * Dipanggil oleh Owner::index() untuk Grafik Garis
     */
    public function getTrenPenjualan($limitHari = 7)
    {
        return $this->select('dim_waktu.tanggal as tanggal, SUM(total_omzet) as total')
                    ->join('dim_waktu', 'dim_waktu.sk_waktu = Fact_Penjualan.sk_waktu')
                    ->groupBy('dim_waktu.tanggal')
                    ->orderBy('dim_waktu.tanggal', 'DESC') 
                    ->limit($limitHari)
                    ->get()->getResultArray(); 
    }

    /**
     * 3. Ambil Top 5 Produk Terlaris
     * Dipanggil oleh Owner::index() untuk Grafik Donat
     */
    public function getTopProduk($limit = 5)
    {
        return $this->select('dim_produk.nama_produk, SUM(jumlah_barang_terjual) as total_qty')
                    ->join('dim_produk', 'dim_produk.sk_produk = Fact_Penjualan.sk_produk')
                    ->groupBy('dim_produk.nama_produk')
                    ->orderBy('total_qty', 'DESC')
                    ->limit($limit)
                    ->get()->getResultArray();
    }
    
    /**
     * 4. Ambil Pendapatan per Kategori
     * Dipanggil oleh Owner::index() untuk Grafik Bar Horizontal
     */
    public function getKategoriPie()
    {
        return $this->select('dim_produk.kategori, SUM(total_omzet) as total_pendapatan')
                    ->join('dim_produk', 'dim_produk.sk_produk = Fact_Penjualan.sk_produk')
                    ->groupBy('dim_produk.kategori')
                    ->orderBy('total_pendapatan', 'DESC')
                    ->get()->getResultArray();
    }
    /**
     * 5. Ambil Top 5 Pelanggan Terbaik
     * Dipanggil oleh Owner::index() untuk Daftar Pelanggan
     */
    public function getTopPelanggan($limit = 5)
    {
        return $this->select('dim_pembeli.nama_pembeli as nama_pelanggan, COUNT(fact_penjualan.id_fakta_penjualan) as total_transaksi, SUM(fact_penjualan.total_omzet) as total_belanja')
            ->join('dim_pembeli', 'dim_pembeli.sk_pembeli = fact_penjualan.sk_pembeli')
            ->groupBy('dim_pembeli.sk_pembeli')
            ->orderBy('total_belanja', 'DESC')
            ->limit($limit)
            ->get()->getResultArray();
    }

    /**
     * 6. Ambil Statistik Metode Pembayaran
     * Dipanggil oleh Owner::index() untuk Grafik Pie Chart
     */
    public function getMetodePembayaran()
    {
        return $this->select('metode_pembayaran, COUNT(id_fakta_penjualan) as jumlah')
                    ->groupBy('metode_pembayaran')
                    ->orderBy('jumlah', 'DESC')
                    ->get()->getResultArray();
    }
}