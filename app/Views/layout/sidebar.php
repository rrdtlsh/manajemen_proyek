<?php
// Ambil role pengguna dari session
$session = session();
$role = $session->get('role');
$currentPath = uri_string();
?>

<?php if (
    $role == 'Penjualan' || $role == 'penjualan' ||
    $role == 'inventaris' || $role == 'Inventaris'  || // <-- Ini sudah benar
    $role == 'Keuangan' || $role == 'keuangan'
) : ?>
    <ul class="navbar-nav sidebar penjualan-sidebar sidebar-dark accordion" id="accordionSidebar">

        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url('/'); ?>">
            <div class="sidebar-brand-icon rotate-n-15">
                <i class="fas fa-store"></i>
            </div>
            <div class="sidebar-brand-text mx-3">UD. THJ</div>
        </a>

        <hr class="sidebar-divider my-0">

        <?php if ($role == 'Penjualan' || $role == 'penjualan') : ?>
            <li class="nav-item <?= (strpos($currentPath, 'dashboard') !== false) ? 'active' : ''; ?>">
                <a class="nav-link" href="<?= base_url('karyawan/dashboard'); ?>">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard Penjualan</span>
                </a>
            </li>
            <li class="nav-item <?= (strpos($currentPath, 'input_penjualan') !== false) ? 'active' : ''; ?>">
                <a class="nav-link" href="<?= base_url('karyawan/input_penjualan'); ?>">
                    <i class="fas fa-fw fa-cash-register"></i>
                    <span>Input Transaksi</span>
                </a>
            </li>
            <li class="nav-item <?= (strpos($currentPath, 'riwayat_penjualan') !== false) ? 'active' : ''; ?>">
                <a class="nav-link" href="<?= base_url('karyawan/riwayat_penjualan'); ?>">
                    <i class="fas fa-fw fa-history"></i>
                    <span>Riwayat Penjualan</span>
                </a>
            </li>

        <?php elseif ($role == 'Keuangan' || $role == 'keuangan') : ?>
            <li class="nav-item <?= (strpos($currentPath, 'keuangan/laporan') !== false) ? 'active' : ''; ?>">
                <a class="nav-link" href="<?= base_url('karyawan/keuangan/laporan'); ?>">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard Keuangan</span>
                </a>
            </li>
            <li class="nav-item <?= (strpos($currentPath, 'keuangan/pemasukan') !== false) ? 'active' : ''; ?>">
                <a class="nav-link" href="<?= base_url('karyawan/keuangan/pemasukan'); ?>">
                    <i class="fas fa-fw fa-arrow-down"></i>
                    <span>Pemasukan</span>
                </a>
            </li>
            <li class="nav-item <?= (strpos($currentPath, 'keuangan/pengeluaran') !== false) ? 'active' : ''; ?>">
                <a class="nav-link" href="<?= base_url('karyawan/keuangan/pengeluaran'); ?>">
                    <i class="fas fa-fw fa-arrow-up"></i>
                    <span>Pengeluaran</span>
                </a>
            </li>
            <li class="nav-item <?= (strpos($currentPath, 'keuangan/laporan') !== false) ? 'active' : ''; ?>">
                <a class="nav-link" href="<?= base_url('karyawan/keuangan/laporan'); ?>">
                    <i class="fas fa-fw fa-chart-line"></i>
                    <span>Laporan Keuangan</span>
                </a>
            </li>
            
        <?php elseif ($role == 'inventaris' || $role == 'Inventaris') : ?>
            <div class="sidebar-heading">
                Operasional Toko
            </div>
            
            <li class="nav-item <?= (strpos($currentPath, 'karyawan/inventaris') !== false && strpos($currentPath, 'restok') === false) ? 'active' : ''; ?>">
                <a class="nav-link" href="<?= base_url('karyawan/inventaris'); ?>">
                    <i class="fas fa-fw fa-box-open"></i>
                    <span>Inventaris Stok</span>
                </a>
            </li>

            <li class="nav-item <?= (strpos($currentPath, 'inventaris/restok') !== false) ? 'active' : ''; ?>">
                <a class="nav-link" href="<?= base_url('karyawan/inventaris/restok'); ?>">
                    <i class="fas fa-fw fa-dolly"></i>
                    <span>Restok Supplier</span>
                </a>
            </li>

        <?php endif; ?>

        <hr class="sidebar-divider d-none d-md-block">

        <div class="text-center d-none d-md-inline mt-auto">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>

    </ul>

<?php else : ?>
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
            <?php else : // Fallback untuk role 'staff' atau 'karyawan' umum 
            ?>
                <a class="nav-link" href="<?= base_url('karyawan/dashboard'); ?>">
                    <i class="fas fa-fw fa-cash-register"></i>
                    <span>Dashboard</span>
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
            <li class="nav-item">
                <a class="nav-link" href="<?= base_url('karyawan/inventaris'); ?>">
                    <i class="fas fa-fw fa-box-open"></i>
                    <span>Inventaris Stok</span>
                </a>
            </li>
        <?php endif; ?>

        <hr class="sidebar-divider d-none d-md-block">

        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>

    </ul>
<?php endif; ?>