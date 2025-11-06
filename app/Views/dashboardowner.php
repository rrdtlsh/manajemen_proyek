<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Owner - SADANG THJ</title>
    <link rel="stylesheet" href="<?= base_url('css/dashboardowner.css') ?>">
</head>

<body>
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <img src="<?= base_url('images/logo-karpet.jpeg') ?>" alt="Logo" class="sidebar-logo">
            <span class="brand">SADANG</span>
        </div>
        <nav class="menu">
            <a class="menu-item active" href="#">Dashboard</a>
            <a class="menu-item" href="#">Penjualan</a>
            <a class="menu-item" href="#">Produk</a>
            <a class="menu-item" href="#">Keuangan</a>
        </nav>

    </aside>

    <main class="main">
        <header class="topbar">
            <div class="topbar-left">
                <button id="burger" class="btn-icon" aria-label="Toggle sidebar" aria-controls="sidebar" aria-expanded="true">
                    <svg xmlns="http://www.w3.org/2000/svg" shape-rendering="geometricPrecision" text-rendering="geometricPrecision" image-rendering="optimizeQuality" fill-rule="evenodd" clip-rule="evenodd" viewBox="0 0 512 389.24" width="20" height="20" fill="currentColor">
                        <path fill-rule="nonzero" d="M0 0h275.1v50.04H0V0zm361.79 115.14 54.44 54.44H0v50.04h416.2l-54.41 54.41 35.39 35.39L512 194.6v-.03l-35.39-35.36-79.43-79.46-35.39 35.39zM0 339.2h275.1v50.04H0V339.2z" />
                    </svg>
                </button>
                <h1 class="page-title">Home Page</h1>
            </div>
            <div class="user-actions">
                <div class="user-chip">
                    <img class="avatar" src="https://ui-avatars.com/api/?background=2d8659&color=fff&name=Owner" alt="Owner">
                    <span class="username">Owner</span>
                </div>
                <a href="<?= base_url('logout') ?>" class="btn btn-danger">Log out</a>
            </div>
        </header>

        <section class="grid grid-cards">
            <div class="card stat stat-blue">
                <div class="stat-value">2</div>
                <div class="stat-label">Order (Bayar)</div>
            </div>
            <div class="card stat stat-red">
                <div class="stat-value">1</div>
                <div class="stat-label">Order (Belum Bayar)</div>
            </div>
            <div class="card stat stat-orange">
                <div class="stat-value">0</div>
                <div class="stat-label">Barang Stok Kosong</div>
            </div>
            <div class="card stat stat-green">
                <div class="stat-value">50</div>
                <div class="stat-label">Barang in Stok</div>
            </div>
        </section>

        <section class="grid grid-2">
            <div class="card">
                <div class="card-header">Grafik Perbandingan Order</div>
                <div class="chart-placeholder" aria-label="chart">Chart area</div>
            </div>
            <div class="card">
                <div class="card-header">Grafik Perbandingan Barang</div>
                <div class="chart-placeholder" aria-label="chart">Chart area</div>
            </div>
        </section>

        <section class="grid grid-cards">
            <div class="card kpi">
                <div class="kpi-value">200</div>
                <div class="kpi-label">Jumlah Barang</div>
            </div>
            <div class="card kpi">
                <div class="kpi-value">451</div>
                <div class="kpi-label">Total Penjualan</div>
            </div>
            <div class="card kpi">
                <div class="kpi-value">597</div>
                <div class="kpi-label">Total Transaksi</div>
            </div>
            <div class="card kpi">
                <div class="kpi-value">415.13K</div>
                <div class="kpi-label">Pendapatan</div>
            </div>
        </section>

        <section class="grid grid-1">
            <div class="card">
                <div class="card-header with-controls">
                    <span>Laporan Penjualan dan Transaksi per Quartal</span>
                    <div class="controls">
                        <button class="btn btn-light">1 Jan - 31 Mar 2024</button>
                        <button class="btn btn-light">Lihat</button>
                    </div>
                </div>
                <div class="chart-placeholder large">Area chart</div>
            </div>
        </section>

        <section class="grid grid-2">
            <div class="card">
                <div class="card-header">Pendapatan per Produk</div>
                <div class="chart-placeholder">Bar chart</div>
            </div>
            <div class="card">
                <div class="card-header">Jumlah Berdasarkan Metode Pembayaran</div>
                <div class="chart-placeholder">Donut chart</div>
            </div>
        </section>

        <footer class="footer">
            <p>&copy; <?= date('Y') ?> SADANG THJ. All rights reserved.</p>
        </footer>
    </main>
    <script src="<?= base_url('js/dashboardowner.js') ?>"></script>
</body>

</html>