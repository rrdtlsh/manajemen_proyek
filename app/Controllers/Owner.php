<?php

namespace App\Controllers;

// Models
use App\Models\PenjualanModel;
use App\Models\ProdukModel;
use App\Models\KeuanganModel;
use CodeIgniter\I18n\Time; 

// [PERBAIKAN] Tambahkan 3 baris ini untuk memanggil controller lain
use App\Controllers\Penjualan;
use App\Controllers\Keuangan;
use App\Controllers\Inventaris;

class Owner extends BaseController
{
    public function index()
    {
        $penjualanModel = new PenjualanModel();
        $produkModel = new ProdukModel();
        $keuanganModel = new KeuanganModel();
        $db = \Config\Database::connect();

        // === 1. DATA UNTUK KPI CARDS ===

        // Ambil tanggal
        $hariIni = Time::now()->toDateString(); // [PERBAIKAN 1] Definisikan $hariIni
        $bulanIniAwal = Time::now()->setDay(1)->toDateString(); 
        
        /** @var \CodeIgniter\I18n\Time $akhirBulanObj */ // Petunjuk untuk editor kode
        $akhirBulanObj = Time::now()->modify('last day of this month');
        $bulanIniAkhir = $akhirBulanObj->toDateString();
        
        // Total Pendapatan (Bulan Ini)
        // Kita asumsikan pendapatan diambil dari tabel 'penjualan' yang Lunas
        $totalPendapatan = $penjualanModel
            ->where('status_pembayaran', 'Lunas')
            ->where('tanggal >=', $bulanIniAwal)
            ->where('tanggal <=', $bulanIniAkhir)
            ->selectSum('total') // Asumsi kolom total harga adalah 'total'
            ->get()->getRow()->total ?? 0;

        // [TAMBAHAN] Total Pengeluaran (Bulan Ini)
        $totalPengeluaran = $keuanganModel
            ->where('tipe', 'Pengeluaran')
            ->where('tanggal >=', $bulanIniAwal)
            ->where('tanggal <=', $bulanIniAkhir)
            ->selectSum('pengeluaran')
            ->get()->getRow()->pengeluaran ?? 0;

        // [TAMBAHAN] Laba Kotor
        $labaKotor = $totalPendapatan - $totalPengeluaran;

        // Total Transaksi (Hari Ini)
        $totalTransaksi = $penjualanModel
            ->where('tanggal', $hariIni) // Gunakan $hariIni yang sudah didefinisi
            ->countAllResults(); 

        // Produk akan habis (Stok < 10)
        $stokMenipis = $produkModel
            ->where('stok <', 10)
            ->countAllResults();

        // === 2. DATA UNTUK PRODUK TERLARIS ===
        $produkTerlaris = $db->table('detail_penjualan')
            // [PERBAIKAN 3] Ganti SUM(jumlah) menjadi SUM(qty)
            ->select('produk.nama_produk, SUM(detail_penjualan.qty) as total_terjual')
            // [PERBAIKAN 2] Ganti join ke id_produk
            ->join('produk', 'produk.id_produk = detail_penjualan.id_produk') 
            ->groupBy('produk.nama_produk')
            ->orderBy('total_terjual', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();

        // === 3. DATA UNTUK GRAFIK PENJUALAN (7 Hari Terakhir) ===
        $grafikData = $penjualanModel
            ->select("DATE(tanggal) as tanggal, SUM(total) as total")
            ->where('tanggal >=', Time::now()->subDays(7)->toDateString())
            ->where('status_pembayaran', 'Lunas') // Hanya hitung yang lunas
            ->groupBy('DATE(tanggal)')
            ->orderBy('tanggal', 'ASC')
            ->get()
            ->getResultArray();

        // Format data untuk Chart.js
        $grafikLabels = [];
        $grafikValues = [];
        foreach ($grafikData as $row) {
            $grafikLabels[] = Time::parse($row['tanggal'])->toFormattedDateString('d M'); // Format "11 Nov"
            $grafikValues[] = $row['total'];
        }

        // Kumpulkan semua data untuk dikirim ke view
        $data = [
            'title'             => 'Dashboard Owner',
            'totalPendapatan'   => $totalPendapatan,
            'totalPengeluaran'  => $totalPengeluaran, // [TAMBAHAN]
            'labaKotor'         => $labaKotor,        // [TAMBAHAN]
            'totalTransaksi'    => $totalTransaksi,
            'stokMenipis'       => $stokMenipis,
            'produkTerlaris'    => $produkTerlaris,
            // Ubah nama variabel agar lebih jelas
            'chartLabels'       => json_encode($grafikLabels), 
            'chartValues'       => json_encode($grafikValues),
        ];

        return view('dashboardowner', $data);
    }
    
    // Rute-rute lain yang ada di Routes.php Anda
    // Fungsi-fungsi ini sekarang akan bekerja karena 'use' statement di atas
    
    public function laporan_penjualan()
    {
        // Panggil controller Penjualan
        return (new Penjualan())->riwayat_penjualan();
    }

    public function laporan_keuangan()
    {
        // Panggil controller Keuangan
        return (new Keuangan())->laporanKeuangan();
    }

    public function manajemen_produk()
    {
        // Panggil controller Inventaris
        return (new Inventaris())->index();
    }
}