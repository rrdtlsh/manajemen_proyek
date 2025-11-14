<?php

namespace App\Controllers;

use App\Controllers\BaseController;
// Hapus semua 'use' Model, karena file ini tidak membutuhkannya lagi
// Cukup BaseController saja

class Karyawan extends BaseController
{
    /**
     * Fungsi ini HANYA bertugas mengarahkan (me-redirect)
     * user ke dashboard sesuai role mereka.
     */
    public function dashboard()
    {
        $role = session()->get('role');

        if ($role === 'penjualan') {
            // [PERBAIKAN] Arahkan ke dashboard penjualan yang baru
            // Rute ini ada di Routes.php Anda di grup 'penjualan'
            return redirect()->to('penjualan/dashboard');
        } elseif ($role === 'keuangan') {
            // [PERBAIKAN] Tambahkan awalan 'karyawan/' agar cocok dengan Routes.php
            return redirect()->to('karyawan/keuangan/laporan');
        } elseif ($role === 'inventaris') {
            // [PERBAIKAN] Arahkan ke rute 'karyawan/inventaris' (sesuai Routes.php)
            // Rute ini akan memanggil InventarisController::index
            return redirect()->to('inventaris/dashboard');
        } else {
            // Default fallback jika role tidak dikenal
            return redirect()->to('penjualan/dashboard');
        }
    }

    /**
     * Index default untuk karyawan, arahkan ke dashboard.
     */
    public function index()
    {
        return redirect()->to('/karyawan/dashboard');
    }
}
