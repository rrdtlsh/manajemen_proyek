<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class DashboardController extends BaseController
{
    public function __construct()
    {
        helper(['session']); // Pastikan helper session di-load
    }

    public function index()
    {
        // Cek apakah user sudah login
        if (!session()->get('isLoggedIn')) {
            // Jika belum, tendang ke login
            return redirect()->to('/login');
        }

        // Jika sudah, tampilkan dashboard
        // Anda bisa memuat view dashboard Anda di sini
        echo "Selamat Datang di Dashboard, " . session()->get('username');
    }
}