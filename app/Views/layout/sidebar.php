<?php
// Ambil role pengguna dari session
$session = session();
$role = $session->get('role');
$currentPath = uri_string();
?>

<?php if ($role == 'Penjualan' || $role == 'penjualan') : ?>
    <!-- Sidebar khusus untuk Penjualan - Full Green dengan Poppins -->
    <ul class="navbar-nav sidebar penjualan-sidebar sidebar-dark accordion" id="accordionSidebar">

        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url('/'); ?>">
            <div class="sidebar-brand-icon rotate-n-15">
                <i class="fas fa-store"></i>
            </div>
            <div class="sidebar-brand-text mx-3">SADANG</div>
        </a>

        <hr class="sidebar-divider my-0">

        <li class="nav-item">
            <a class="nav-link <?= ($currentPath == 'karyawan/penjualan' || $currentPath == 'karyawan/dashboard') ? 'active' : ''; ?>" href="<?= base_url('karyawan/penjualan'); ?>">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard Penjualan</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= ($currentPath == 'karyawan/input_penjualan' || strpos($currentPath, 'input') !== false) ? 'active' : ''; ?>" href="<?= base_url('karyawan/input_penjualan'); ?>">
                <i class="fas fa-fw fa-cash-register"></i>
                <span>Input Transaksi Penjualan</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link <?= (strpos($currentPath, 'riwayat') !== false || strpos($currentPath, 'data') !== false) ? 'active' : ''; ?>" href="<?= base_url('karyawan/penjualan'); ?>">
                <i class="fas fa-fw fa-history"></i>
                <span>Riwayat Penjualan</span>
            </a>
        </li>

        <div class="text-center d-none d-md-inline mt-auto">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>

    </ul>

<?php else : ?>
    <!-- Sidebar untuk role lain (owner, staff, dll) -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url('/'); ?>">
            <div class="sidebar-brand-icon rotate-n-15">
                <i class="fas fa-store"></i>
            </div>
            <div class="sidebar-brand-text mx-3">UD. THJ</div>
        </a>

        <hr class="sidebar-divider my-0">

        <li class="nav-item">
            <?php if ($role == 'owner') : ?>
                <a class="nav-link" href="<?= base_url('owner'); ?>">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard Owner</span>
                </a>
            <?php else : ?>
                <a class="nav-link" href="<?= base_url('karyawan/penjualan'); ?>">
                    <i class="fas fa-fw fa-cash-register"></i>
                    <span>Dashboard Penjualan</span>
                </a>
            <?php endif; ?>
        </li>

        <hr class="sidebar-divider">


        <?php if ($role == 'owner') : ?>
            <div class="sidebar-heading">
                Analitik & Manajemen
            </div>

            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('owner'); ?>">
                    <i class="fas fa-fw fa-chart-line"></i>
                    <span>Dashboard Analitik</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('manajemen/karyawan'); ?>">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Manajemen Karyawan</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('laporan/keuangan'); ?>">
                    <i class="fas fa-fw fa-file-invoice-dollar"></i>
                    <span>Laporan Keuangan</span>
                </a>
            </li>
        <?php endif; ?>


        <?php if ($role == 'staff' || $role == 'karyawan') : ?>
            <div class="sidebar-heading">
                Operasional Toko
            </div>

            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('penjualan/input_transaksi'); ?>">
                    <i class="fas fa-fw fa-cash-register"></i>
                    <span>Input Transaksi</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('karyawan/inventaris'); ?>">
                    <i class="fas fa-fw fa-box-open"></i>
                    <span>Inventaris Stok</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('karyawan/keuangan'); ?>">
                    <i class="fas fa-fw fa-hand-holding-usd"></i>
                    <span>Catat Keuangan</span>
                </a>
            </li>
        <?php endif; ?>


        <hr class="sidebar-divider d-none d-md-block">

        <li class="nav-item">
            <a class="nav-link" href="<?= base_url('login/logout'); ?>" data-toggle="modal" data-target="#logoutModal">
                <i class="fas fa-fw fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </li>

        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>

    </ul>
<?php endif; ?>