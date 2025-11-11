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

    // --- RUTE OWNER (PEMILIK) ---
    $routes->get('owner', 'Owner::index');
    $routes->get('owner/laporan_penjualan', 'Owner::laporan_penjualan');
    $routes->get('owner/laporan_keuangan', 'Owner::laporan_keuangan');
    $routes->get('owner/manajemen_produk', 'Owner::manajemen_produk');


    // --- RUTE KARYAWAN (STAF) ---
    $routes->group('karyawan', static function ($routes) {
        $routes->get('/', 'Karyawan::index');
        $routes->get('dashboard', 'Karyawan::dashboard');

        // --- Rute Penjualan ---
        $routes->get('penjualan', 'Karyawan::penjualan');
        $routes->get('input_penjualan', 'Karyawan::input_penjualan');
        $routes->post('store_penjualan', 'Karyawan::store_penjualan');
        $routes->get('riwayat_penjualan', 'Karyawan::riwayat_penjualan');
        $routes->get('edit_penjualan/(:num)', 'Karyawan::edit_penjualan/$1');
        $routes->post('update_penjualan/(:num)', 'Karyawan::update_penjualan/$1');

        // [BARU] Rute AJAX untuk Pelanggan & Produk Search
        $routes->get('search_pelanggan', 'Karyawan::searchPelanggan');
        $routes->get('search_produk', 'Karyawan::searchProduk');
        $routes->post('add_pelanggan', 'Karyawan::addPelanggan');

        // --- Rute Inventaris ---
        $routes->get('inventaris', 'Karyawan::inventaris');
        $routes->get('inventaris/restok', 'Karyawan::restok_supplier');
        $routes->get('input_inventaris', 'Karyawan::input_inventaris');
        $routes->get('inventaris/tambah', 'Karyawan::tambah_produk');
        $routes->post('inventaris/store', 'Karyawan::store_produk');
        $routes->get('inventaris/edit/(:num)', 'Karyawan::edit_produk/$1');
        $routes->post('inventaris/update/(:num)', 'Karyawan::update_produk/$1');
        $routes->get('inventaris/delete/(:num)', 'Karyawan::delete_produk/$1');

        // --- Rute Keuangan ---
        $routes->get('keuangan/pemasukan', 'Karyawan::keuanganPemasukan');
        $routes->get('keuangan/pengeluaran', 'Karyawan::keuanganPengeluaran');
        $routes->get('keuangan/laporan', 'Karyawan::laporanKeuangan');
        $routes->get('input_keuangan', 'Karyawan::input_keuangan');
        $routes->post('store_keuangan', 'Karyawan::store_keuangan');

        $routes->get('delete_penjualan/(:num)', 'Karyawan::delete_penjualan/$1');
        $routes->get('detail_penjualan/(:num)', 'Karyawan::detail_penjualan/$1');
    });
});
