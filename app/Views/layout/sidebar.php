<?php
// Ambil role pengguna dari session
$session = session();
$role = $session->get('role');
// Pengecekan role yang lebih konsisten (mengubah ke huruf kecil)
$userRole = strtolower($role ?? ''); // Tambah ?? '' untuk menghindari error jika null
$currentPath = uri_string();
?>

<?php
// --- BLOK UNTUK SEMUA ROLE (KARYAWAN + PEMILIK) ---
if ($userRole == 'penjualan' || $userRole == 'inventaris' || $userRole == 'keuangan' || $userRole == 'pemilik') : // <-- 'pemilik' DIMASUKKAN DI SINI
?>
    <ul class="navbar-nav sidebar penjualan-sidebar sidebar-dark accordion" id="accordionSidebar">

        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url('/'); ?>">
            <div class="sidebar-brand-icon rotate-n-15">
                <i class="fas fa-store"></i>
            </div>
            <div class="sidebar-brand-text mx-3">UD. THJ</div>
        </a>
        <hr class="sidebar-divider my-0">

        <?php // === MENU PENJUALAN ===
        if ($userRole == 'penjualan') : ?>
            <li class="nav-item <?= (strpos($currentPath, 'karyawan/dashboard') !== false || strpos($currentPath, 'penjualan/dashboard') !== false) ? 'active' : ''; ?>">
                <a class="nav-link" href="<?= base_url('karyawan/dashboard'); ?>">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard Penjualan</span>
                </a>
            </li>
            <li class="nav-item <?= (strpos($currentPath, 'karyawan/input_penjualan') !== false) ? 'active' : ''; ?>">
                <a class="nav-link" href="<?= base_url('karyawan/input_penjualan'); ?>">
                    <i class="fas fa-fw fa-cash-register"></i>
                    <span>Input Transaksi</span>
                </a>
            </li>
            <li class="nav-item <?= (strpos($currentPath, 'karyawan/riwayat_penjualan') !== false) ? 'active' : ''; ?>">
                <a class="nav-link" href="<?= base_url('karyawan/riwayat_penjualan'); ?>">
                    <i class="fas fa-fw fa-history"></i>
                    <span>Riwayat Penjualan</span>
                </a>
            </li>

        <?php // === MENU KEUANGAN ===
        elseif ($userRole == 'keuangan') : ?>
            <li class="nav-item <?= (strpos($currentPath, 'karyawan/keuangan/dashboard') !== false) ? 'active' : ''; ?>">
                <a class="nav-link" href="<?= base_url('karyawan/keuangan/dashboard'); ?>">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard Keuangan</span>
                </a>
            </li>
            <li class="nav-item <?= (strpos($currentPath, 'karyawan/keuangan/pemasukan') !== false) ? 'active' : ''; ?>">
                <a class="nav-link" href="<?= base_url('karyawan/keuangan/pemasukan'); ?>">
                    <i class="fas fa-fw fa-arrow-down"></i>
                    <span>Pemasukan</span>
                </a>
            </li>
            <li class="nav-item <?= (strpos($currentPath, 'karyawan/keuangan/pengeluaran') !== false) ? 'active' : ''; ?>">
                <a class="nav-link" href="<?= base_url('karyawan/keuangan/pengeluaran'); ?>">
                    <i class="fas fa-fw fa-arrow-up"></i>
                    <span>Pengeluaran</span>
                </a>
            </li>
            <li class="nav-item <?= (strpos($currentPath, 'karyawan/keuangan/laporan') !== false) ? 'active' : ''; ?>">
                <a class="nav-link" href="<?= base_url('karyawan/keuangan/laporan'); ?>">
                    <i class="fas fa-fw fa-file-invoice-dollar"></i>
                    <span>Laporan Keuangan</span>
                </a>
            </li>

        <?php // === MENU INVENTARIS ===
        elseif ($userRole == 'inventaris') : ?>
            <li class="nav-item <?= (strpos($currentPath, 'karyawan/dashboard') !== false || strpos($currentPath, 'inventaris/dashboard') !== false) ? 'active' : ''; ?>">
                <a class="nav-link" href="<?= base_url('karyawan/dashboard'); ?>">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard Inventaris</span>
                </a>
            </li>
            <li class="nav-item <?= (strpos($currentPath, 'karyawan/inventaris') !== false && strpos($currentPath, 'restok') === false && strpos($currentPath, 'tambah') === false) ? 'active' : ''; ?>">
                <a class="nav-link" href="<?= base_url('karyawan/inventaris'); ?>">
                    <i class="fas fa-fw fa-box-open"></i>
                    <span>Inventaris Stok</span>
                </a>
            </li>
            <li class="nav-item <?= (strpos($currentPath, 'karyawan/inventaris/restok') !== false) ? 'active' : ''; ?>">
                <a class="nav-link" href="<?= base_url('karyawan/inventaris/restok'); ?>">
                    <i class="fas fa-fw fa-dolly"></i>
                    <span>Restok Supplier</span>
                </a>
            </li>
            <li class="nav-item <?= (strpos($currentPath, 'karyawan/inventaris/supplier') !== false) ? 'active' : ''; ?>">
                <a class="nav-link" href="<?= base_url('karyawan/inventaris/supplier'); ?>">
                    <i class="fas fa-fw fa-truck"></i>
                    <span>Data Supplier</span>
                </a>
            </li>
        <?php endif; ?>


        <?php // === MENU PEMILIK (DI DALAM BLOK HIJAU) ===
        if ($userRole == 'pemilik') : ?>
            <li class="nav-item <?= (strpos($currentPath, 'owner') !== false) ? 'active' : ''; ?>">
                <a class="nav-link" href="<?= base_url('owner'); ?>">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard Owner</span>
                </a>
            </li>
            <hr class="sidebar-divider">
            <div class="sidebar-heading">
                Analitik & Manajemen
            </div>
            <li class="nav-item <?= (strpos($currentPath, 'owner/laporan_penjualan') !== false) ? 'active' : ''; ?>">
                <a class="nav-link" href="<?= base_url('owner/laporan_penjualan'); ?>">
                    <i class="fas fa-fw fa-chart-line"></i>
                    <span>Laporan Penjualan</span>
                </a>
            </li>
            <li class="nav-item <?= (strpos($currentPath, 'owner/laporan_keuangan') !== false) ? 'active' : ''; ?>">
                <a class="nav-link" href="<?= base_url('owner/laporan_keuangan'); ?>">
                    <i class="fas fa-fw fa-file-invoice-dollar"></i>
                    <span>Laporan Keuangan</span>
                </a>
            </li>
            <li class="nav-item <?= (strpos($currentPath, 'owner/manajemen_produk') !== false) ? 'active' : ''; ?>">
                <a class="nav-link" href="<?= base_url('owner/manajemen_produk'); ?>">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Manajemen Produk</span>
                </a>
            </li>
            <li class="nav-item <?= (strpos($currentPath, 'owner/restok') !== false) ? 'active' : ''; ?>">
                <a class="nav-link" href="<?= base_url('owner/restok'); ?>">
                    <i class="fas fa-fw fa-check-circle"></i>
                    <span>Restok Approval</span>
                </a>
            </li>
        <?php endif; ?>
        <hr class="sidebar-divider d-none d-md-block">
        <div class="text-center d-none d-md-inline mt-auto">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>

    </ul>

<?php
// --- PENTING: BLOK 'else' YANG LAMA (BERISI SIDEBAR BIRU) DIHAPUS TOTAL ---
endif;
?>