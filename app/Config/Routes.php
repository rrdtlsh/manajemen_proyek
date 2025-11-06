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
// Catatan: Pastikan nama Controller Anda adalah 'Login' (bukan 'LoginController')
$routes->get('/login', 'Login::index');
$routes->post('/login/auth', 'Login::auth'); // Ini rute 'authenticate' Anda, sudah OK
$routes->get('/login/switch/(:segment)', 'Login::switchLanguage/$1');
$routes->get('/logout', 'Login::logout'); // <-- INI RUTE PENTING YANG HARUS DITAMBAH


/*
 * --------------------------------------------------------------------
 * Rute Internal yang Dilindungi (Wajib Login)
 * --------------------------------------------------------------------
 */
// Semua rute di dalam grup ini akan dijaga oleh filter 'auth'
// (Pastikan 'auth' sudah didaftarkan di app/Config/Filters.php)

$routes->group('', ['filter' => 'auth'], function($routes) {

    // Dashboard Owner
    // Catatan: Pastikan nama Controller Anda adalah 'Owner'
    $routes->get('/dashboardowner', 'Owner::dashboard');

    // Nanti, semua rute lain yang perlu login harus ditaruh DI DALAM SINI:
    // Contoh:
    // $routes->get('/penjualan', 'Penjualan::index');
    // $routes->get('/produk', 'Produk::index');
    // $routes->get('/keuangan', 'Keuangan::index');

});