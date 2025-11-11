<?php
// Ambil role pengguna dari session
$session = session();
$role = $session->get('role');
// Pengecekan role yang lebih konsisten (mengubah ke huruf kecil)
$userRole = strtolower($role ?? ''); // Tambah ?? '' untuk menghindari error jika null
$currentPath = uri_string();
?>

<?php
// --- BLOK UNTUK KARYAWAN (PENJUALAN, KEUANGAN, INVENTARIS) ---
if ($userRole == 'penjualan' || $userRole == 'inventaris' || $userRole == 'keuangan') :
?>
    <ul class="navbar-nav sidebar penjualan-sidebar sidebar-dark accordion" id="accordionSidebar">

        <!-- Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url('/'); ?>">
            <div class="sidebar-brand-icon rotate-n-15">
                <i class="fas fa-store"></i>
            </div>
            <div class="sidebar-brand-text mx-3">UD. THJ</div>
        </a>
        <hr class="sidebar-divider my-0">

        <?php // === MENU PENJUALAN ===
        if ($userRole == 'penjualan') : ?>
            <li class="nav-item <?= (strpos($currentPath, 'karyawan/dashboard') !== false) ? 'active' : ''; ?>">
                <!-- Dashboard Penjualan tetap di Karyawan.php -->
                <a class="nav-link" href="<?= base_url('karyawan/dashboard'); ?>">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard Penjualan</span>
                </a>
            </li>
            <li class="nav-item <?= (strpos($currentPath, 'penjualan/input_penjualan') !== false) ? 'active' : ''; ?>">
                <!-- [PERBAIKAN] URL diubah ke /penjualan/... -->
                <a class="nav-link" href="<?= base_url('penjualan/input_penjualan'); ?>">
                    <i class="fas fa-fw fa-cash-register"></i>
                    <span>Input Transaksi</span>
                </a>
            </li>
            <li class="nav-item <?= (strpos($currentPath, 'penjualan/riwayat_penjualan') !== false) ? 'active' : ''; ?>">
                <!-- [PERBAIKAN] URL diubah ke /penjualan/... -->
                <a class="nav-link" href="<?= base_url('penjualan/riwayat_penjualan'); ?>">
                    <i class="fas fa-fw fa-history"></i>
                    <span>Riwayat Penjualan</span>
                </a>
            </li>

        <?php // === MENU KEUANGAN ===
        elseif ($userRole == 'keuangan') : ?>
            <li class="nav-item <?= (strpos($currentPath, 'keuangan/laporanKeuangan') !== false) ? 'active' : ''; ?>">
                <!-- [PERBAIKAN] URL diubah ke /keuangan/laporanKeuangan -->
                <a class="nav-link" href="<?= base_url('keuangan/laporanKeuangan'); ?>">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard Keuangan</span>
                </a>
            </li>
            <li class="nav-item <?= (strpos($currentPath, 'keuangan/pemasukan') !== false) ? 'active' : ''; ?>">
                <!-- [PERBAIKAN] URL diubah ke /keuangan/pemasukan -->
                <a class="nav-link" href="<?= base_url('keuangan/pemasukan'); ?>">
                    <i class="fas fa-fw fa-arrow-down"></i>
                    <span>Pemasukan</span>
                </a>
            </li>
            <li class="nav-item <?= (strpos($currentPath, 'keuangan/pengeluaran') !== false) ? 'active' : ''; ?>">
                <!-- [PERBAIKAN] URL diubah ke /keuangan/pengeluaran -->
                <a class="nav-link" href="<?= base_url('keuangan/pengeluaran'); ?>">
                    <i class="fas fa-fw fa-arrow-up"></i>
                    <span>Pengeluaran</span>
                </a>
            </li>
            <li class="nav-item <?= (strpos($currentPath, 'keuangan/laporanKeuangan') !== false) ? 'active' : ''; ?>">
                <a class="nav-link" href="<?= base_url('keuangan/laporanKeuangan'); ?>">
            <i class="fas fa-fw fa-chart-line"></i>
                <span>Laporan Keuangan</span>
                </a>
            </li>
            
        <?php // === MENU INVENTARIS ===
        elseif ($userRole == 'inventaris') : ?>
            <div class="sidebar-heading">
                Operasional Toko
            </div>
            <li class="nav-item <?= (strpos($currentPath, 'inventaris') !== false && strpos($currentPath, 'restok') === false) ? 'active' : ''; ?>">
                <!-- [PERBAIKAN] URL diubah ke /inventaris -->
                <a class="nav-link" href="<?= base_url('inventaris'); ?>">
                    <i class="fas fa-fw fa-box-open"></i>
                    <span>Inventaris Stok</span>
                </a>
            </li>
            <li class="nav-item <?= (strpos($currentPath, 'inventaris/restok_supplier') !== false) ? 'active' : ''; ?>">
                <!-- [PERBAIKAN] URL diubah ke /inventaris/restok_supplier -->
                <a class="nav-link" href="<?= base_url('inventaris/restok_supplier'); ?>">
                    <i class="fas fa-fw fa-dolly"></i>
                    <span>Restok Supplier</span>
                </a>
            </li>
        <?php endif; ?>

        <!-- Sidebar Toggler -->
        <hr class="sidebar-divider d-none d-md-block">
        <div class="text-center d-none d-md-inline mt-auto">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>

    </ul>

<?php
// --- BLOK UNTUK OWNER (ATAU ROLE LAIN) ---
else :
?>
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

        <!-- Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url('/'); ?>">
            <div class="sidebar-brand-icon rotate-n-15">
                <i class="fas fa-store"></i>
            </div>
            <div class="sidebar-brand-text mx-3">UD. THJ</div>
        </a>
        <hr class="sidebar-divider my-0">

        <!-- Hanya tampilkan menu jika rolenya 'owner' -->
        <?php if ($userRole == 'owner') : ?>
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

        <?php endif; ?>

        <!-- Sidebar Toggler -->
        <hr class="sidebar-divider d-none d-md-block">
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>

    </ul>
<?php endif; ?>