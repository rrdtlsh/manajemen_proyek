<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Homepage untuk pelanggan/public (Tidak perlu login)
$routes->get('/', 'Home::homepage');
$routes->get('/homepage', 'Home::homepage');


/*
 * --------------------------------------------------------------------
 * Rute Autentikasi (Publik)
 * --------------------------------------------------------------------
 */
$routes->get('/login', 'Login::index');
$routes->post('/login/auth', 'Login::auth');
$routes->get('/login/switch/(:segment)', 'Login::switchLanguage/$1'); // Dari file Anda
$routes->get('/logout', 'Login::logout');


/*
 * --------------------------------------------------------------------
 * Rute Internal yang Dilindungi (Wajib Login)
 * --------------------------------------------------------------------
 */
$routes->group('/', ['filter' => 'auth'], static function ($routes) {

    // --- RUTE OWNER (PEMILIK) ---
    // ===================================
    $routes->get('owner', 'Owner::index'); // Dashboard Analitik Owner

    // Rute Owner untuk Laporan & Manajemen
    // (Anda bisa tambahkan method baru di Owner.php untuk ini)
    $routes->get('owner/laporan_penjualan', 'Owner::laporan_penjualan');
    $routes->get('owner/laporan_keuangan', 'Owner::laporan_keuangan');
    $routes->get('owner/manajemen_produk', 'Owner::manajemen_produk');


    // --- RUTE KARYAWAN (STAF) ---
    $routes->group('karyawan', static function ($routes) {
        $routes->get('/', 'Karyawan::index'); // Halaman default Karyawan
        $routes->get('dashboard', 'Karyawan::dashboard');
        $routes->get('inventaris', 'Karyawan::inventaris');
        $routes->get('keuangan', 'Karyawan::keuangan');

        // Rute untuk Penjualan
        $routes->get('penjualan', 'Karyawan::penjualan'); // Ini akan me-redirect ke riwayat

        // Halaman Input Transaksi (POS)
        $routes->get('input_penjualan', 'Karyawan::input_penjualan');
        $routes->post('store_penjualan', 'Karyawan::store_penjualan');

        // == RUTE BARU UNTUK RIWAYAT ==
        $routes->get('riwayat_penjualan', 'Karyawan::riwayat_penjualan');

        // == RUTE EDIT & UPDATE (SUDAH DIPINDAHKAN KE DALAM GRUP 'karyawan') ==
        $routes->get('edit_penjualan/(:num)', 'Karyawan::edit_penjualan/$1');
        $routes->post('update_penjualan/(:num)', 'Karyawan::update_penjualan/$1');
    });
});
