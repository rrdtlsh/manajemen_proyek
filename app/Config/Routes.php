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
    $routes->get('karyawan', 'Karyawan::index'); // Halaman default Karyawan
    $routes->get('karyawan/dashboard', 'Karyawan::dashboard'); 
    $routes->get('karyawan/inventaris', 'Karyawan::inventaris'); 
    $routes->get('karyawan/keuangan', 'Karyawan::keuangan'); 
    
    // === TAMBAHKAN BARIS INI ===
    $routes->get('karyawan/penjualan', 'Karyawan::penjualan');
    // ============================
    
    // Fitur Input Transaksi (POS) Karyawan
    $routes->get('karyawan/input_penjualan', 'Karyawan::input_penjualan');
    $routes->post('karyawan/store_penjualan', 'Karyawan::store_penjualan');
    
   
});