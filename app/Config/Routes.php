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
    $routes->group('owner', static function ($routes) { 
        // Tidak perlu filter auth lagi karena parent group sudah punya
        
        $routes->get('/', 'Owner::index'); // Mengarah ke /owner
        $routes->get('dashboard', 'Owner::index'); // Mengarah ke /owner/dashboard

        // [PINDAHKAN KE SINI] Rute Owner untuk Approval Restok
        // URL akan menjadi /owner/restok
        $routes->get('restok', 'OwnerRestokController::index');
        $routes->get('restok/approve/(:num)', 'OwnerRestokController::approve/$1');
        $routes->get('restok/reject/(:num)', 'OwnerRestokController::reject/$1');
    });


    // --- RUTE KARYAWAN (STAF) ---
    $routes->group('karyawan', static function ($routes) {

        // [PERBAIKAN] Ini tetap di Karyawan.php (sebagai redirector)
        $routes->get('/', 'Karyawan::index');
        $routes->get('dashboard', 'Karyawan::dashboard');

        // --- Rute Penjualan ---
        // URL: /karyawan/penjualan, /karyawan/input_penjualan, dst.
        $routes->get('penjualan', 'PenjualanController::index');
        $routes->get('input_penjualan', 'PenjualanController::input_penjualan');
        $routes->post('store_penjualan', 'PenjualanController::store_penjualan');
        $routes->get('riwayat_penjualan', 'PenjualanController::riwayat_penjualan');
        $routes->get('edit_penjualan/(:num)', 'PenjualanController::edit_penjualan/$1');
        $routes->post('update_penjualan/(:num)', 'PenjualanController::update_penjualan/$1');
        $routes->get('delete_penjualan/(:num)', 'PenjualanController::delete_penjualan/$1');
        $routes->get('detail_penjualan/(:num)', 'PenjualanController::detail_penjualan/$1');

        // Rute AJAX
        $routes->get('search_pelanggan', 'PenjualanController::searchPelanggan');
        $routes->get('search_produk', 'PenjualanController::searchProduk');
        $routes->post('add_pelanggan', 'PenjualanController::addPelanggan');


        // --- Rute Inventaris ---
        // URL: /karyawan/inventaris, /karyawan/inventaris/restok, dst.
        $routes->get('inventaris', 'InventarisController::index');
        $routes->get('inventaris/restok', 'InventarisController::restok_supplier');
        $routes->get('inventaris/detail/(:num)', 'InventarisController::detail_produk/$1');

        // Rute CRUD Produk (Modal)
        $routes->post('inventaris/store', 'InventarisController::store_produk');
        $routes->post('inventaris/store_produk', 'InventarisController::store_produk');
        $routes->post('inventaris/update/(:num)', 'InventarisController::update_produk/$1');
        $routes->get('inventaris/delete/(:num)', 'InventarisController::delete_produk/$1');

        // Rute CRUD Restok (Modal)
        $routes->post('inventaris/store_restok', 'InventarisController::store_restok');
        $routes->get('inventaris/delete_restok/(:num)', 'InventarisController::delete_restok/$1');
        $routes->get('inventaris/detail_restok/(:num)', 'InventarisController::detail_restok/$1');

        // --- Rute Supplier ---
        $routes->get('inventaris/supplier', 'InventarisController::supplier');
        $routes->post('inventaris/store_supplier', 'InventarisController::store_supplier');
        $routes->post('inventaris/update_supplier/(:num)', 'InventarisController::update_supplier/$1');
        $routes->get('inventaris/detail_supplier/(:num)', 'InventarisController::detail_supplier/$1');
        $routes->get('inventaris/delete_supplier/(:num)', 'InventarisController::delete_supplier/$1');

        
        // --- Rute Keuangan ---
        // URL: /karyawan/keuangan, /karyawan/keuangan/pemasukan, dst.
        $routes->get('keuangan', 'KeuanganController::dashboard');
        $routes->get('keuangan/dashboard', 'KeuanganController::dashboard');
        $routes->get('keuangan/pemasukan', 'KeuanganController::pemasukan');
        $routes->get('keuangan/pengeluaran', 'KeuanganController::pengeluaran');
        $routes->get('keuangan/laporan', 'KeuanganController::laporanKeuangan');
        
        // Export
        $routes->get('keuangan/pemasukan/export/pdf', 'KeuanganController::exportPemasukanPDF');
        $routes->get('keuangan/pemasuka/export/excel', 'KeuanganController::exportPemasukanExcel');
        $routes->get('keuangan/pengeluaran/export/pdf', 'KeuanganController::exportPengeluaranPDF');
        $routes->get('keuangan/pengeluaran/export/excel', 'KeuanganController::exportPengeluaranExcel');

        
        // --- Rute restok owner Dihapus dari sini ---

    }); // Akhir grup 'karyawan'

    // --- RUTE BARU UNTUK DASHBOARD ---
    // Rute ini diperlukan agar redirect dari Karyawan::dashboard() berfungsi
    // Ini sudah benar!
    $routes->get('penjualan/dashboard', 'PenjualanController::dashboard');
    $routes->get('inventaris/dashboard', 'InventarisController::dashboard');
    
    // Saya tambahkan juga untuk keuangan untuk jaga-jaga
    $routes->get('keuangan/dashboard', 'KeuanganController::dashboard');

}); // Akhir grup '/'