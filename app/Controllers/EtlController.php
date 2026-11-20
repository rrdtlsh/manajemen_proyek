<?php

namespace App\Controllers;

use App\Controllers\BaseController;

// Load Model OLTP (Sumber)
use App\Models\ProdukModel;
use App\Models\PenjualanModel;
use App\Models\DetailPenjualanModel;
use App\Models\PelangganModel;

// Load Model DWH (Target)
use App\Models\Dwh\DimProdukModel;
use App\Models\Dwh\DimWaktuModel;
use App\Models\Dwh\FactPenjualanModel;
use App\Models\Dwh\DimPembeliModel;

class EtlController extends BaseController
{
    /**
     * Fitur Data Lake: Simpan Raw Data ke File JSON (Ingest)
     * Menyimpan data mentah ke folder writable/datalake sebagai arsip
     */
    private function ingestToDataLake($data, $filename)
    {
        // Pastikan folder penyimpanan ada
        $path = WRITEPATH . 'datalake/';
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        // Tambahkan Timestamp agar file unik per kejadian
        $timestamp = date('Y-m-d_H-i-s');
        $fullPath = $path . $filename . '_' . $timestamp . '.json';
        
        // Simpan data sebagai JSON
        file_put_contents($fullPath, json_encode($data, JSON_PRETTY_PRINT));
        
        echo "   [Data Lake] Raw Data berhasil di-ingest ke: $filename" . "_$timestamp.json \n";
    }

    public function index()
    {
        echo "Silakan jalankan fungsi spesifik: \n";
        echo "1. php spark etl:produk \n";
        echo "2. php spark etl:penjualan \n";
    }

    /**
     * ETL 1: Dimensi Produk
     * Strategi: SCD Tipe 1 (Update jika ada perubahan nama/harga, Insert jika baru)
     */
    public function process_produk()
    {
        echo "--- Memulai ETL Dimensi Produk ---\n";
        
        $sourceModel = new ProdukModel();
        $targetModel = new DimProdukModel();

        // 1. EXTRACT (Ubah query untuk JOIN ke tabel kategori)
        // Agar kita mendapatkan 'nama_kategori' (Karpet, Sprei, dll) bukan hanya ID
        $dataSumber = $sourceModel->select('produk.*, kategori.nama_kategori')
            ->join('kategori', 'kategori.id_kategori = produk.id_kategori', 'left')
            ->findAll();

        $count = 0;
        $update = 0;

        foreach ($dataSumber as $row) {
            // 2. TRANSFORM
            // Cek apakah produk sudah ada di DWH berdasarkan ID Sumber
            $existing = $targetModel->where('id_produk_sumber', $row['id_produk'])->first();

            // Gunakan nama_kategori dari hasil join. Jika null, default ke 'Lainnya'
            $kategoriLabel = $row['nama_kategori'] ?? 'Lainnya';

            $dataDwh = [
                'id_produk_sumber'    => $row['id_produk'],
                'kode_produk_asli'    => $row['kode_produk'] ?? 'P-'.$row['id_produk'],
                'nama_produk'         => $row['nama_produk'],
                'kategori'            => $kategoriLabel, // <-- PERBAIKAN DI SINI (Sebelumnya 'Umum')
                'satuan'              => 'Pcs',
                'harga_beli_saat_ini' => $row['harga_beli'] ?? 0,
                'harga_jual_saat_ini' => $row['harga'],
            ];

            // 3. LOAD
            if ($existing) {
                // Update (SCD Type 1)
                $targetModel->update($existing['sk_produk'], $dataDwh);
                $update++;
            } else {
                // Insert Baru
                $targetModel->insert($dataDwh);
                $count++;
            }
        }

        echo "Selesai! Insert: $count, Update: $update \n";
    }

    /**
     * ETL 2: Fakta Penjualan
     * Strategi: Full Load (Hapus dulu hari ini, lalu isi ulang) atau Incremental
     * Untuk demo ini kita pakai metode 'Idempotent' (Cek no_transaksi)
     */
    public function process_penjualan()
    {
        echo "--- Memulai ETL Fakta Penjualan ---\n";

        $penjualanModel = new PenjualanModel();       
        $detailModel    = new DetailPenjualanModel(); 
        
        $dimProduk      = new DimProdukModel();
        $dimWaktu       = new DimWaktuModel();
        $factPenjualan  = new FactPenjualanModel();

        // 1. EXTRACT
        $transaksiSource = $penjualanModel->where('status_pembayaran', 'Lunas')->findAll();
        $this->ingestToDataLake($transaksiSource, 'raw_penjualan'); //simpan ke data lake
        $count = 0;

        foreach ($transaksiSource as $trx) {
            // Jika 'no_faktur' tidak ada, kita buat sendiri dari ID.
            $noTransaksi = $trx['no_faktur'] ?? 'TRX-' . $trx['id_penjualan'];

            // Ambil Detail Item
            $items = $detailModel->where('id_penjualan', $trx['id_penjualan'])->findAll();

            foreach ($items as $item) {
                // 2. TRANSFORM & LOOKUP SK
                
                // A. Lookup SK Waktu
                $tglTrx = date('Y-m-d', strtotime($trx['tanggal']));
                $skWaktu = $this->getSkWaktu($dimWaktu, $tglTrx);

                // B. Lookup SK Produk
                $skProduk = $dimProduk->where('id_produk_sumber', $item['id_produk'])->first()['sk_produk'] ?? null;
                $dimPembeli = new \App\Models\Dwh\DimPembeliModel();
                $skPembeli = 1; // Default Umum
                if (!empty($trx['id_pelanggan'])) {
                    $pembeliInfo = $dimPembeli->where('id_pelanggan_sumber', $trx['id_pelanggan'])->first();
                    if ($pembeliInfo) {
                        $skPembeli = $pembeliInfo['sk_pembeli'];
                    }
                }
                if (!$skWaktu || !$skProduk) {
                    continue; 
                }

                // C. Hitung Metrik
                $produkInfo = $dimProduk->find($skProduk);
                
                $hargaBeli = $produkInfo['harga_beli_saat_ini'] ?? 0;
                
                $profitPerItem = $item['harga_satuan'] - $hargaBeli;
                $profitTotal = $profitPerItem * $item['qty'];

                // D.Cek duplikat
                $cekFakta = $factPenjualan->where('no_transaksi_sumber', $noTransaksi)
                                         ->where('sk_produk', $skProduk)
                                         ->first();

                $dataFakta = [
                    'sk_waktu'              => $skWaktu,
                    'sk_produk'             => $skProduk,
                    'sk_pembeli'            => $skPembeli, 
                    'sk_lokasi'             => 1, 
                    'no_transaksi_sumber'   => $noTransaksi,
                    'jumlah_barang_terjual' => $item['qty'],
                    'total_omzet'           => $item['qty'] * $item['harga_satuan'],
                    'profit_kotor'          => $profitTotal,
                    'status_pembayaran'     => $trx['status_pembayaran'],
                    'metode_pembayaran'     => $trx['metode_pembayaran']
                ];

                // 3. LOAD
                if (!$cekFakta) {
                    $factPenjualan->insert($dataFakta);
                    $count++;
                }
            }
        }

        echo "Selesai! Berhasil memuat $count baris fakta penjualan.\n";
    }

    // Helper: Cari atau Buat Dimensi Waktu on-the-fly (Jika belum di-seeding)
    private function getSkWaktu($model, $tanggal)
    {
        $cek = $model->where('tanggal', $tanggal)->first();
        if ($cek) return $cek['sk_waktu'];

        // Jika tidak ada, insert baru (Otomatisasi Dimensi Waktu)
        $time = strtotime($tanggal);
        $newData = [
            'tanggal'   => $tanggal,
            'hari'      => date('d', $time),
            'nama_hari' => date('l', $time),
            'bulan'     => date('m', $time),
            'tahun'     => date('Y', $time),
            'kuartal'   => ceil(date('n', $time) / 3),
            'nama_bulan'=> date('F', $time),
            'minggu_ke' => date('W', $time)
        ];
        return $model->insert($newData, true);
    }


    public function process_pelanggan()
    {
        echo "--- Memulai ETL Dimensi Pembeli ---\n";
        
        $sourceModel = new \App\Models\PelangganModel();
        $targetModel = new \App\Models\Dwh\DimPembeliModel();

        $dataSumber = $sourceModel->findAll();
        $count = 0;

        // default data 
        if (!$targetModel->find(1)) {
            $targetModel->insert([
                'sk_pembeli' => 1,
                'nama_pembeli' => 'Pembeli Umum',
                'kategori_pelanggan' => 'Umum'
            ]);
        }

        foreach ($dataSumber as $row) {
            $existing = $targetModel->where('id_pelanggan_sumber', $row['id_pelanggan'])->first();

            $dataDwh = [
                'id_pelanggan_sumber' => $row['id_pelanggan'],
                'nama_pembeli'        => $row['nama_pelanggan'],
                'no_hp'               => $row['no_hp'],
                'alamat_domisili'     => $row['alamat'],
                'kategori_pelanggan'  => 'Member' 
            ];

            if ($existing) {
                $targetModel->update($existing['sk_pembeli'], $dataDwh);
            } else {
                $targetModel->insert($dataDwh);
                $count++;
            }
        }
        echo "Selesai! $count pelanggan baru ditambahkan.\n";
    }

    /**
     * ETL 3: Fakta Inventaris (Snapshot Harian)
     * Strategi: Periodic Snapshot
     * Mengambil sisa stok HARI INI dan pergerakan barang HARI INI
     */
    public function process_inventaris()
    {
        echo "--- Memulai ETL Fakta Inventaris ---\n";

        $db      = \Config\Database::connect();
        $dbDwh   = \Config\Database::connect('dwh');
        
        $dimProdukModel = new DimProdukModel();
        $dimWaktuModel  = new DimWaktuModel();
        $factInventaris = new \App\Models\Dwh\FactInventarisModel();

        // 1. Tentukan SK Waktu Hari Ini
        $today = date('Y-m-d');
        $skWaktu = $dimWaktuModel->getSkHariIni();

        if (!$skWaktu) {
            // Jika dimensi waktu belum di-generate hari ini, buat on-the-fly
            // (Sebaiknya gunakan seeder DimWaktuSeeder, ini hanya fallback)
            die("Error: SK Waktu untuk $today belum tersedia. Jalankan Seeder Waktu dulu.\n");
        }

        // 2. Ambil Semua Produk dari Toko (OLTP)
        // Kita butuh stok terakhir saat ini
        $produkOltp = $db->table('produk')->get()->getResultArray();
        $this->ingestToDataLake($produkOltp, 'raw_snapshot_stok'); //simpan ke data lake

        $count = 0;

        foreach ($produkOltp as $prod) {
            
            // A. Lookup SK Produk
            $skProduk = $dimProdukModel->getSkByIdSumber($prod['id_produk']);
            if (!$skProduk) continue; // Skip jika produk belum ada di dimensi

            // B. Hitung Barang Keluar HARI INI (Dari Penjualan)
            // Query ke OLTP Detail Penjualan join Penjualan
            $qtyKeluar = $db->table('detail_penjualan')
                ->join('penjualan', 'penjualan.id_penjualan = detail_penjualan.id_penjualan')
                ->where('penjualan.tanggal', $today)
                ->where('detail_penjualan.id_produk', $prod['id_produk'])
                ->selectSum('qty')
                ->get()->getRow()->qty ?? 0;

            // C. Hitung Barang Masuk HARI INI (Dari Restok yang Disetujui)
            // Query ke OLTP Restok
            $qtyMasuk = $db->table('restok')
                ->where('nama_barang', $prod['nama_produk']) // Asumsi matching by nama atau tambah kolom id_produk di tabel restok
                ->where('status_owner', 'Disetujui')
                ->where('DATE(tanggal_approve)', $today)
                ->selectSum('qty')
                ->get()->getRow()->qty ?? 0;

            // D. Siapkan Data Fakta
            // Kita simpan snapshot stok akhir hari ini
            $dataFakta = [
                'sk_waktu'         => $skWaktu,
                'sk_produk'        => $skProduk,
                'sk_lokasi'        => 1, // Default Toko Utama
                'sk_supplier'      => null, // Bisa diisi jika pergerakan spesifik per supplier, tapi untuk snapshot harian bisa null
                'jenis_pergerakan' => 'Snapshot Harian',
                'jumlah_masuk'     => $qtyMasuk,
                'jumlah_keluar'    => $qtyKeluar,
                'sisa_stok_harian' => $prod['stok'] // Stok aktual di detik ini
            ];

            // E. Cek apakah snapshot hari ini sudah ada? (Idempotent)
            $existing = $factInventaris->where('sk_waktu', $skWaktu)
                                       ->where('sk_produk', $skProduk)
                                       ->first();

            if ($existing) {
                // Update jika dijalankan ulang pada hari yang sama
                $factInventaris->update($existing['id_fakta_inventaris'], $dataFakta);
            } else {
                // Insert baru
                $factInventaris->insert($dataFakta);
            }
            $count++;
        }

        echo "Selesai! Snapshot stok harian untuk $count produk berhasil disimpan.\n";
    }
}