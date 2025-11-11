<?php

namespace App\Controllers;

use App\Controllers\BaseController;
// Hanya panggil model yang diperlukan untuk dashboard
use App\Models\PenjualanModel;

class Karyawan extends BaseController
{
    public function index()
    {
        // Arahkan ke dashboard sebagai halaman utama
        return redirect()->to('/karyawan/dashboard');
    }

    public function dashboard()
    {
        $role = session()->get('role');
        $username = session()->get('username');

        $data = [
            'username' => $username
        ];

        if ($role === 'penjualan') {
            $penjualanModel = new \App\Models\PenjualanModel();
            $salesData = $penjualanModel->getDashboardData();
            $data = array_merge($data, $salesData);
            $data['title'] = 'Dashboard Penjualan';
            return view('dashboard_staff/dashboard_penjualan', $data);

        } elseif ($role === 'keuangan') {
            // [PERUBAHAN] Arahkan ke controller Keuangan yang baru
            return redirect()->to('keuangan/laporanKeuangan');

        } elseif ($role === 'inventaris') {
            $data['title'] = 'Dashboard Inventaris';
            // Nanti Anda bisa membuat fungsi getDashboardData() untuk inventaris
            return view('dashboard_staff/dashboard_inventaris', $data);

        } else {
            // Role lain (misal: admin) bisa lihat dashboard penjualan
            $penjualanModel = new \App\Models\PenjualanModel();
            $salesData = $penjualanModel->getDashboardData();
            $data = array_merge($data, $salesData);
            $data['title'] = 'Dashboard';
            return view('dashboard_staff/dashboard_penjualan', $data);
        }
    }

    // === SEMUA FUNGSI LAIN (penjualan, keuangan, inventaris) SUDAH DIHAPUS ===
}