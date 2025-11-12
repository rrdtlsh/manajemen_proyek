<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Homepage
$routes->get('/', 'Home::homepage');
$routes->get('/homepage', 'Home::homepage');


/*
 * Rute Autentikasi
 */
$routes->get('/login', 'Login::index');
$routes->post('/login/auth', 'Login::auth');
$routes->get('/login/switch/(:segment)', 'Login::switchLanguage/$1');
$routes->get('/logout', 'Login::logout');


/*
 * Rute Internal yang Dilindungi
 */
$routes->group('/', ['filter' => 'auth'], static function ($routes) {

    // --- RUTE OWNER (PEMILIK) --- (Ini tetap sama)
    $routes->get('owner', 'Owner::index');
    $routes->get('owner/laporan_penjualan', 'Owner::laporan_penjualan');
    $routes->get('owner/laporan_keuangan', 'Owner::laporan_keuangan');
    $routes->get('owner/manajemen_produk', 'Owner::manajemen_produk');


    // --- RUTE KARYAWAN (STAF) ---
    $routes->group('karyawan', static function ($routes) {

        // [PERBAIKAN] Ini tetap di Karyawan.php (sebagai redirector)
        $routes->get('/', 'Karyawan::index');
        $routes->get('dashboard', 'Karyawan::dashboard');

        // --- Rute Penjualan ---
        // [PERBAIKAN] Mengarah ke PenjualanController
        $routes->get('penjualan', 'PenjualanController::index');
        $routes->get('input_penjualan', 'PenjualanController::input_penjualan');
        $routes->post('store_penjualan', 'PenjualanController::store_penjualan');
        $routes->get('riwayat_penjualan', 'PenjualanController::riwayat_penjualan');
        $routes->get('edit_penjualan/(:num)', 'PenjualanController::edit_penjualan/$1');
        $routes->post('update_penjualan/(:num)', 'PenjualanController::update_penjualan/$1');

        // [PERBAIKAN] Rute AJAX
        $routes->get('search_pelanggan', 'PenjualanController::searchPelanggan');
        $routes->get('search_produk', 'PenjualanController::searchProduk');
        $routes->post('add_pelanggan', 'PenjualanController::addPelanggan');

        // [PERBAIKAN] Rute Detail & Delete
        $routes->get('delete_penjualan/(:num)', 'PenjualanController::delete_penjualan/$1');
        $routes->get('detail_penjualan/(:num)', 'PenjualanController::detail_penjualan/$1');


        // --- Rute Inventaris ---
        // [PERBAIKAN] Mengarah ke InventarisController
        $routes->get('inventaris', 'InventarisController::index');
        $routes->get('inventaris/restok', 'InventarisController::restok_supplier');
        $routes->get('input_inventaris', 'InventarisController::index'); // Alias ke index
        $routes->get('inventaris/tambah', 'InventarisController::tambah_produk');
        $routes->post('inventaris/store', 'InventarisController::store_produk');
        $routes->get('inventaris/edit/(:num)', 'InventarisController::edit_produk/$1');
        $routes->post('inventaris/update/(:num)', 'InventarisController::update_produk/$1');
        $routes->get('inventaris/delete/(:num)', 'InventarisController::delete_produk/$1');

        // --- Rute Keuangan ---
        // [PERBAIKAN] Mengarah ke KeuanganController
        $routes->get('keuangan/pemasukan', 'KeuanganController::pemasukan');
        $routes->get('keuangan/pengeluaran', 'KeuanganController::pengeluaran');
        $routes->get('keuangan/laporan', 'KeuanganController::laporanKeuangan');
        $routes->get('input_keuangan', 'KeuanganController::input_keuangan');
        $routes->post('store_keuangan', 'KeuanganController::store_keuangan');
    });

    // --- RUTE BARU UNTUK DASHBOARD ---
    // Rute ini diperlukan agar redirect dari Karyawan::dashboard() berfungsi

    $routes->get('penjualan/dashboard', 'PenjualanController::dashboard', ['filter' => 'auth']);
    $routes->get('inventaris/dashboard', 'InventarisController::dashboard', ['filter' => 'auth']);
});
