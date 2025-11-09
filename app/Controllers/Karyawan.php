<?php

namespace App\Controllers;

use App\Controllers\BaseController;

// TAMBAHKAN 'USE' STATEMENTS BARU INI UNTUK FUNGSI PENJUALAN
use App\Models\ProdukModel;
use App\Models\PenjualanModel;
use App\Models\DetailPenjualanModel;
use CodeIgniter\I18n\Time; 

// GANTI NAMA CLASS DARI 'Manajemen' (JIKA SEBELUMNYA) MENJADI 'Karyawan'
class Karyawan extends BaseController
{
    /**
     * Menampilkan dashboard yang sesuai untuk role karyawan
     * (Method ini dari file Manajemen.php lama Anda)
     */
    public function dashboard()
    {
        // Ambil role dan username dari session
        $role = session()->get('role');
        $username = session()->get('username');
        
        $data = [
            'username' => $username
            // 'title' akan di-set di dalam if-else
        ];

        // Tentukan view mana yang akan di-load berdasarkan role
        if ($role === 'penjualan') {
            $data['title'] = 'Dashboard Penjualan';
            return view('dashboard_staff/dashboard_penjualan', $data); 
        } 
        elseif ($role === 'keuangan') {
            $data['title'] = 'Dashboard Keuangan';
            return view('dashboard_staff/dashboard_keuangan', $data);
        } 
        elseif ($role === 'inventaris') {
            $data['title'] = 'Dashboard Inventaris';
            return view('dashboard_staff/dashboard_inventaris', $data);
        } 
        else {
            // Role 'manajemen' biasa atau role lain
            $data['title'] = 'Dashboard';
            // Kita gunakan view penjualan sebagai default sementara
            return view('dashboard_staff/dashboard_penjualan', $data); 
        }
    }

    /**
     * Halaman default untuk /karyawan
     * (Method ini dari file Manajemen.php lama Anda)
     */
    public function index()
    {
        // Anda bisa arahkan ke dashboard, atau ke halaman input
        // Mari kita arahkan ke halaman input penjualan sebagai default
        return redirect()->to('/karyawan/input_penjualan');
    }
    
    /**
     * (Method ini dari file Manajemen.php lama Anda)
     */
    public function inventaris()
    {
        // Pastikan 'use App\Models\ProdukModel;' ada di atas
        $produkModel = new ProdukModel();
        $data = [
            'title'  => 'Dashboard Inventaris',
            'produk' => $produkModel->findAll()
        ];
        return view('dashboard_staff/dashboard_inventaris', $data);
    }

    /**
     * (Method ini dari file Manajemen.php lama Anda)
     */
    public function penjualan()
    {
        // Ini mungkin untuk melihat riwayat?
        // Untuk saat ini, kita arahkan ke dashboard penjualan
        return view('dashboard_staff/dashboard_penjualan');
    }

    /**
     * (Method ini dari file Manajemen.php lama Anda)
     */
    public function keuangan()
    {
        // ...
        return view('dashboard_staff/dashboard_keuangan');
    }

    // ==========================================================
    // === METHOD BARU UNTUK INPUT TRANSAKSI (POS) DITAMBAHKAN DI SINI ===
    // ==========================================================

    /**
     * [BARU] Menampilkan halaman Input Transaksi (POS)
     * Ini akan diakses melalui rute /karyawan/input_penjualan
     */
    public function input_penjualan()
    {
        $produkModel = new ProdukModel();
        
        $data = [
            'title'  => 'Input Transaksi Penjualan',
            'produk' => $produkModel->where('stok >', 0)->findAll() // Hanya tampilkan produk yg ada stok
        ];
        
        return view('penjualan/input_transaksi', $data);
    }

    /**
     * [BARU] Menyimpan transaksi baru ke database
     * Ini akan diakses melalui rute /karyawan/store_penjualan
     */
/**
     * [BARU] Menyimpan transaksi baru ke database
     * Ini akan diakses melalui rute /karyawan/store_penjualan
     */
    public function store_penjualan()
    {
        $db = \Config\Database::connect();
        $db->transStart(); // Mulai Database Transaction

        try {
            // 1. Ambil Model
            $penjualanModel = new PenjualanModel();
            $detailModel = new DetailPenjualanModel();
            $produkModel = new ProdukModel();

            // 2. Ambil data dari FORM POST
            $cartItems  = $this->request->getPost('cart_items'); 
            $totalHarga = $this->request->getPost('total_belanja');
            $jumlahDP   = $this->request->getPost('jumlah_dp');
            $statusBayar = $this->request->getPost('status_bayar'); // Ambil status bayar

            // 3. Simpan data ke tabel 'penjualan'
            $dataPenjualan = [
                'tanggal'           => Time::now()->toDateString(),
                'total'             => $totalHarga,
                'status_pembayaran' => $statusBayar, // SUDAH DIPERBAIKI (dari 'status_bayar')
                'metode_pembayaran' => $this->request->getPost('metode_pembayaran'),
                'jumlah_dp'         => empty($jumlahDP) ? 0 : $jumlahDP,
                'id_user'           => session()->get('user_id') 
            ];
            
            $penjualanModel->insert($dataPenjualan);
            $idPenjualanBaru = $penjualanModel->getInsertID();

            // 4. Proses Keranjang (Cart)
            $items = json_decode($cartItems, true);
            $dataDetailBatch = [];

            foreach ($items as $item) {
                // Konversi data dari JS agar aman
                $qty = (int)($item['qty'] ?? 0);
                $subtotal = (float)($item['subtotal'] ?? 0);

                // HITUNG HARGA SATUAN DARI SUBTOTAL DAN QTY
                $harga_satuan = 0;
                if ($qty > 0) {
                    $harga_satuan = $subtotal / $qty;
                }

                // Masukkan data yang SESUAI DENGAN DATABASE
                $dataDetailBatch[] = [
                    'id_penjualan' => $idPenjualanBaru,
                    'id_produk'    => $item['id'],
                    'qty'          => $qty,           // Nama kolom database
                    'harga_satuan' => $harga_satuan   // Nama kolom database
                ];

                // 5. Kurangi Stok Produk
                $produkModel->where('id_produk', $item['id'])
                            ->set('stok', 'stok - ' . $qty, false) 
                            ->update();
            }

            // 6. Simpan ke tabel 'detail_penjualan'
            if (!empty($dataDetailBatch)) {
                $detailModel->insertBatch($dataDetailBatch); // Ini akan berhasil jika Model sudah benar
            }

            $db->transComplete(); // Selesaikan Transaction

            // ==========================================================
            // === LOGIKA REDIRECT YANG ANDA MINTA ===
            // ==========================================================
            if ($statusBayar === 'Lunas') {
                // Jika LUNAS, lempar ke dashboard
                return redirect()->to('/karyawan/dashboard')->with('success', 'Transaksi Lunas berhasil disimpan!');
            } else {
                // Jika DP atau Utang, kembali ke halaman input (untuk transaksi baru)
                return redirect()->to('/karyawan/input_penjualan')->with('success', 'Transaksi (Belum Lunas) berhasil disimpan!');
            }

        } catch (\Exception $e) {
            $db->transRollback(); // Batalkan semua jika ada error
            // Tampilkan error yang lebih spesifik
            log_message('error', 'Error di store_penjualan: ' . $e->getMessage() . ' - File: ' . $e->getFile() . ' - Line: ' . $e->getLine());
            return redirect()->to('/karyawan/input_penjualan')->with('error', 'Error: ' . $e->getMessage());
        }
    }
}