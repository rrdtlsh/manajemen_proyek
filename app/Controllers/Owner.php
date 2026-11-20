<?php

namespace App\Controllers;

use App\Models\Dwh\FactPenjualanModel;
use App\Models\Dwh\FactInventarisModel;
use App\Models\ProdukModel;
use Dompdf\Dompdf;
use Dompdf\Options;

class Owner extends BaseController
{
    protected $factPenjualan;
    protected $factInventaris;
    protected $produkModel;

    public function __construct()
    {
        // 1. Load Model DWH (Untuk Analisis Historis & Tren)
        $this->factPenjualan  = new FactPenjualanModel();
        $this->factInventaris = new FactInventarisModel();
        
        // 2. Load Model Operasional (Hanya untuk Cek Stok Real-time hari ini)
        $this->produkModel    = new ProdukModel(); 
        
        helper(['number', 'form']);
    }

    public function index()
    {
        // --- A. PERSIAPAN WAKTU ---
        $bulanIni  = date('m');
        $tahunIni  = date('Y');
        $bulanLalu = date('m', strtotime('-1 month'));
        $tahunLalu = date('Y', strtotime('-1 month')); // Handle jika Januari (kembali ke Des tahun lalu)

        // --- B. AMBIL KPI UTAMA DARI DWH (Sangat Cepat) ---
        // Menggunakan method getKpiBulanan yang sudah kita buat di Model DWH
        $kpiNow  = $this->factPenjualan->getKpiBulanan($bulanIni, $tahunIni);
        $kpiLast = $this->factPenjualan->getKpiBulanan($bulanLalu, $tahunLalu);

        // Sanitasi nilai null jika belum ada penjualan
        $pendapatan     = $kpiNow['pendapatan'] ?? 0;
        $keuntungan     = $kpiNow['keuntungan'] ?? 0;
        $totalTransaksi = $kpiNow['total_transaksi'] ?? 0; // Pastikan query di model ada COUNT(id)
        
        // --- C. HITUNG PERTUMBUHAN (GROWTH) ---
        $pendapatanLalu = $kpiLast['pendapatan'] ?? 0;
        $growthPendapatan = 0;
        
        if ($pendapatanLalu > 0) {
            $growthPendapatan = (($pendapatan - $pendapatanLalu) / $pendapatanLalu) * 100;
        } elseif ($pendapatan > 0) {
            $growthPendapatan = 100; // Dari 0 ke ada nilai = 100%
        }

        // --- D. DATA UNTUK GRAFIK (DARI DWH) ---
        
        // 1. Tren Penjualan Harian
        $rawTren = $this->factPenjualan->getTrenPenjualan(7);
        $trenHarian = array_reverse($rawTren); // <-- SOLUSI: Balik urutan agar Masa Lalu -> Hari Ini
        // 2. Top Produk
        $topProduk = $this->factPenjualan->getTopProduk(5);

        // 3. Kategori (Perlu method baru di FactPenjualanModel, atau gunakan query builder DWH disini)
        // Kita asumsikan Anda menambahkan method getPendapatanKategori() di FactPenjualanModel
        // Atau kita query manual DWH disini untuk contoh
        $dbDwh = \Config\Database::connect('dwh');
        $kategoriData = $dbDwh->table('fact_penjualan fp')
            ->select('dp.kategori, SUM(fp.total_omzet) as total_pendapatan')
            ->join('dim_produk dp', 'dp.sk_produk = fp.sk_produk')
            ->groupBy('dp.kategori')
            ->orderBy('total_pendapatan', 'DESC')
            ->limit(5)
            ->get()->getResultArray();

        // --- E. DATA OPERASIONAL & ANALISIS STOK ---
        
        // 1. Stok Menipis (Tetap ambil dari OLTP agar Real-time saat ini juga)
        $lowStock = $this->produkModel
            // Perbaikan: Select 'nama_kategori', lalu di-alias jadi 'kategori'
            ->select('produk.nama_produk, produk.stok, produk.kode_produk') 
            ->join('kategori', 'kategori.id_kategori = produk.id_kategori', 'left')
            ->where('produk.stok <', 10)
            ->where('produk.stok >', 0)
            ->orderBy('produk.stok', 'ASC')
            ->limit(5)
            ->findAll();

        // 2. Dead Stock (Barang Mengendap > 90 Hari)
        // Menggunakan logika DWH FactInventaris
        $deadStock = $this->factInventaris->getDeadStock(90);
        
        // HYDRATION: Tambal data yang kosong dengan data Toko (OLTP)
        if (!empty($deadStock)) {
            foreach ($deadStock as &$item) {
                // Cari produk asli di database toko
                $produkAsli = $this->produkModel->find($item['id_produk_sumber']);
                
                if ($produkAsli) {
                    // 1. Ambil STOK Real-time (Wajib)
                    $item['stok'] = $produkAsli['stok']; 
                    
                    // 2. Ambil HARGA Real-time (Jika di DWH 0)
                    // Pastikan dikonversi ke float agar bisa dihitung matematikanya
                    $hargaDwh = (float) $item['harga_jual'];
                    if ($hargaDwh <= 0) {
                        $item['harga_jual'] = (float) $produkAsli['harga'];
                    }
                    
                    // 3. Pastikan Kode Produk ada
                    if (empty($item['kode_produk'])) {
                        $item['kode_produk'] = $produkAsli['kode_produk'];
                    }
                } else {
                    $item['stok'] = 0;
                }
            }
            unset($item); 
        }
        // 3. Tren Pergerakan Stok (Masuk vs Keluar)
        $rawStok = $this->factInventaris->getPergerakanStok(7);
        $trenStok = array_reverse($rawStok);
        // --- F. PACKING DATA KE VIEW ---
        $data = [
            'title'            => 'Dashboard Owner (DWH)',
            'totalPendapatan'  => $pendapatan,
            'totalPengeluaran' => 0, // DWH Penjualan biasanya tidak catat pengeluaran listrik/gaji, bisa ambil dari OLTP Keuangan jika perlu
            'keuntunganBersih' => $keuntungan,
            'totalPenjualan'   => $totalTransaksi, // Jumlah bon/transaksi
            'pendapatanGrowth' => $growthPendapatan,
            'pengeluaranGrowth'=> 0, // Opsional
            
            'lowStockProducts' => $lowStock,
            'deadStock'        => $deadStock,
            'topCustomers'     => $this->factPenjualan->getTopPelanggan(5), // Bisa ditambahkan query DWH DimPembeli jika mau

            'errorRate'      => count($deadStock),

            // Data JSON untuk chart.js
            'salesChartData'    => json_encode($trenHarian),
            'topProductsData'   => json_encode($topProduk),
            'categorySalesData' => json_encode($kategoriData),
            'stockMovementData' => json_encode($trenStok),
            'paymentMethodData' => json_encode($this->factPenjualan->getMetodePembayaran()) // Bisa query ke DWH FactPenjualan group by status_pembayaran
        ];

        return view('owner/dashboardowner', $data);
    }


    public function download_transcript()
    {
        // 1. SIAPKAN DATA (Sama seperti Dashboard)
        $bulanIni  = date('m');
        $tahunIni  = date('Y');
        
        $kpiNow  = $this->factPenjualan->getKpiBulanan($bulanIni, $tahunIni);
        
        // Data Stok Menipis (Real-time)
        $lowStock = $this->produkModel
            ->select('produk.nama_produk, produk.stok, produk.kode_produk') 
            ->join('kategori', 'kategori.id_kategori = produk.id_kategori', 'left')
            ->where('produk.stok <', 10)
            ->where('produk.stok >', 0)
            ->orderBy('produk.stok', 'ASC')
            ->findAll();

        // Data Dead Stock (Analisis DWH)
        $deadStock = $this->factInventaris->getDeadStock(90);
        // Hydration data deadstock (sama seperti di index)
        if (!empty($deadStock)) {
            foreach ($deadStock as &$item) {
                $produkAsli = $this->produkModel->find($item['id_produk_sumber']);
                if ($produkAsli) {
                    $item['stok'] = $produkAsli['stok']; 
                    $hargaDwh = (float) $item['harga_jual'];
                    if ($hargaDwh <= 0) $item['harga_jual'] = (float) $produkAsli['harga'];
                    if (empty($item['kode_produk'])) $item['kode_produk'] = $produkAsli['kode_produk'];
                } else {
                    $item['stok'] = 0;
                }
            }
            unset($item);
        }

        $data = [
            'title'            => 'Transkrip Status Toko',
            'periode'          => date('F Y'),
            'totalPendapatan'  => $kpiNow['pendapatan'] ?? 0,
            'keuntunganBersih' => $kpiNow['keuntungan'] ?? 0,
            'totalTransaksi'   => $kpiNow['total_transaksi'] ?? 0,
            'lowStock'         => $lowStock,
            'deadStock'        => $deadStock,
            'topCustomers'     => $this->factPenjualan->getTopPelanggan(10), // Ambil Top 10 untuk laporan
            'topProducts'      => $this->factPenjualan->getTopProduk(10)
        ];

        // 2. RENDER VIEW KE PDF
        $html = view('owner/pdf_transcript', $data);

        $options = new Options();
        $options->set('isRemoteEnabled', true); // Agar bisa load gambar/logo jika ada

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // 3. DOWNLOAD FILE
        $filename = 'Transkrip_Status_Toko_' . date('Y_m') . '.pdf';
        $dompdf->stream($filename, ["Attachment" => true]);
    }
}