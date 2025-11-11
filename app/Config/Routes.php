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
$routes->get('/login/switch/(:segment)', 'Login::switchLanguage/$1');
$routes->get('/logout', 'Login::logout');


/*
 * --------------------------------------------------------------------
 * Rute Internal yang Dilindungi (Wajib Login)
 * --------------------------------------------------------------------
 */

// [PERBAIKAN]: Mengganti 'authfilter' menjadi 'auth'
// Asumsi filter Anda di app/Config/Filters.php bernama 'auth'
$routes->group('/', ['filter' => 'auth'], static function ($routes) {

    // --- RUTE OWNER ---
    $routes->get('owner', 'Owner::index'); 
    $routes->get('owner/laporan_penjualan', 'Owner::laporan_penjualan');
    $routes->get('owner/laporan_keuangan', 'Owner::laporan_keuangan');
    $routes->get('owner/manajemen_produk', 'Owner::manajemen_produk');


    // --- RUTE KARYAWAN (HANYA DASHBOARD) ---
    // URL: /karyawan/...
    $routes->group('karyawan', static function ($routes) {
        $routes->get('/', 'Karyawan::index');
        $routes->get('dashboard', 'Karyawan::dashboard');
    });


    // --- RUTE PENJUALAN ---
    // URL: /penjualan/...
    $routes->group('penjualan', static function ($routes) {
        $routes->get('/', 'Penjualan::index'); 
        $routes->get('riwayat_penjualan', 'Penjualan::riwayat_penjualan');
        $routes->get('input_penjualan', 'Penjualan::input_penjualan');
        $routes->post('store_penjualan', 'Penjualan::store_penjualan');
        $routes->get('edit_penjualan/(:num)', 'Penjualan::edit_penjualan/$1');
        $routes->post('update_penjualan/(:num)', 'Penjualan::update_penjualan/$1');
        $routes->get('detail_penjualan/(:num)', 'Penjualan::detail_penjualan/$1');
        $routes->get('delete_penjualan/(:num)', 'Penjualan::delete_penjualan/$1');
        $routes->get('search_pelanggan', 'Penjualan::searchPelanggan');
        $routes->post('add_pelanggan', 'Penjualan::addPelanggan');
    });


    // --- RUTE KEUANGAN ---
    // URL: /keuangan/...
    $routes->group('keuangan', static function ($routes) {
        $routes->get('/', 'Keuangan::index');
        $routes->get('laporanKeuangan', 'Keuangan::laporanKeuangan');
        $routes->get('pemasukan', 'Keuangan::keuanganPemasukan');
        $routes->get('pengeluaran', 'Keuangan::keuanganPengeluaran');
        $routes->get('input_keuangan', 'Keuangan::input_keuangan');
        $routes->post('store_keuangan', 'Keuangan::store_keuangan');
    });


    // --- RUTE INVENTARIS ---
    // URL: /inventaris/...
    $routes->group('inventaris', static function ($routes) {
        $routes->get('/', 'Inventaris::index');
        $routes->get('index', 'Inventaris::index'); 
        $routes->get('tambah_produk', 'Inventaris::tambah_produk');
        $routes->post('store_produk', 'Inventaris::store_produk');
        $routes->get('edit_produk/(:num)', 'Inventaris::edit_produk/$1');
        $routes->post('update_produk/(:num)', 'Inventaris::update_produk/$1');
        $routes->get('delete_produk/(:num)', 'Inventaris::delete_produk/$1');
        $routes->get('restok_supplier', 'Inventaris::restok_supplier'); 
        $routes->post('store_restok', 'Inventaris::store_restok');
        $routes->get('delete_restok/(:num)', 'Inventaris::delete_restok/$1');
        $routes->get('search_produk', 'Inventaris::searchProduk');
    });

});