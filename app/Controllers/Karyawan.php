<?php

namespace App\Controllers;

use App\Controllers\BaseController;
// Hapus semua 'use' Model, karena file ini tidak membutuhkannya lagi

class Karyawan extends BaseController
{
    /**
     * Fungsi ini HANYA bertugas mengarahkan (me-redirect)
     * user ke dashboard sesuai role mereka.
     */
    public function dashboard()
    {
        // [PERBAIKAN] Ambil role dan langsung ubah ke huruf kecil
        $role = strtolower(session()->get('role') ?? '');

        if ($role === 'penjualan') {
            return redirect()->to('penjualan/dashboard');
        } elseif ($role === 'keuangan') {
            return redirect()->to('karyawan/keuangan/laporan');
        } elseif ($role === 'inventaris') {
            // Rute 'inventaris/dashboard' sudah ada di Routes.php
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
