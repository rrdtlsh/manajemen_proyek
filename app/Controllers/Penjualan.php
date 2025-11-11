<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProdukModel;
use App\Models\PenjualanModel;
use App\Models\DetailPenjualanModel;
use App\Models\KeuanganModel;
use App\Models\PelangganModel;
// (Pastikan semua model yang diperlukan sudah di-use)

class Penjualan extends BaseController
{
    // ==========================================================
    // === METHOD UNTUK RIWAYAT PENJUALAN ===
    // ==========================================================

    public function index()
    {
        // Arahkan ke riwayat sebagai halaman utama
        return redirect()->to('penjualan/riwayat_penjualan');
    }

    public function riwayat_penjualan()
    {
        $penjualanModel = new PenjualanModel();
        $data = [
            'title' => 'Riwayat Transaksi Penjualan',
            'penjualan' => $penjualanModel
                ->select('penjualan.*, pelanggan.nama_pelanggan')
                ->join('pelanggan', 'pelanggan.id_pelanggan = penjualan.id_pelanggan', 'left')
                ->orderBy('penjualan.tanggal', 'DESC')
                ->orderBy('penjualan.id_penjualan', 'DESC')
                ->findAll()
        ];
        return view('penjualan/riwayat_penjualan', $data);
    }

    // ==========================================================
    // === METHOD UNTUK INPUT TRANSAKSI (POS) ===
    // ==========================================================

    public function input_penjualan()
    {
        $produkModel = new ProdukModel();
        $data = [
            'title'  => 'Input Transaksi Penjualan',
            'produk' => $produkModel->where('stok >', 0)->findAll()
        ];
        return view('penjualan/input_transaksi', $data);
    }

    public function store_penjualan()
    {
        // ... (Salin semua isi fungsi store_penjualan dari Karyawan.php) ...
        // ...
        // PENTING: Ubah redirect di akhir fungsi
        // return redirect()->to('/karyawan/input_penjualan') 
        // -> menjadi:
        return redirect()->to('/penjualan/input_penjualan')->with('success', 'Transaksi Lunas berhasil disimpan!');
    }

    // ==========================================================
    // === METHOD UNTUK EDIT/UPDATE TRANSAKSI ===
    // ==========================================================

    public function edit_penjualan($id_penjualan)
    {
        // ... (Salin semua isi fungsi edit_penjualan dari Karyawan.php) ...
        // ...
        // PENTING: Ubah redirect jika error
        // return redirect()->to('karyawan/riwayat_penjualan')
        // -> menjadi:
        return redirect()->to('penjualan/riwayat_penjualan')->with('error', 'Transaksi tidak ditemukan.');
        // ...
    }

    public function update_penjualan($id_penjualan)
    {
        // ... (Salin semua isi fungsi update_penjualan dari Karyawan.php) ...
        // ...
        // PENTING: Ubah redirect di akhir fungsi
        // return redirect()->to('karyawan/riwayat_penjualan')
        // -> menjadi:
        return redirect()->to('penjualan/riwayat_penjualan')->with('success', 'Transaksi #' . $id_penjualan . ' berhasil diperbarui.');
    }

    public function detail_penjualan($id_penjualan)
    {
        // ... (Salin semua isi fungsi detail_penjualan dari Karyawan.php) ...
    }

    public function delete_penjualan($id_penjualan)
    {
        // ... (Salin semua isi fungsi delete_penjualan dari Karyawan.php) ...
        // ...
        // PENTING: Ubah redirect di akhir fungsi
        // return redirect()->to('karyawan/riwayat_penjualan')
        // -> menjadi:
        return redirect()->to('penjualan/riwayat_penjualan')->with('success', 'Transaksi #' . $id_penjualan . ' berhasil dihapus...');
    }

    // ==========================================================
    // === METHOD AJAX UNTUK PELANGGAN ===
    // ==========================================================
    
    public function searchPelanggan()
    {
        // ... (Salin semua isi fungsi searchPelanggan dari Karyawan.php) ...
    }

    public function addPelanggan()
    {
        // ... (Salin semua isi fungsi addPelanggan dari Karyawan.php) ...
    }
}