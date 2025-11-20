<?= $this->extend('layout/template'); ?> 

<?= $this->section('content'); ?>
<div class="container-fluid">

    <!-- Judul Halaman -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard Owner</h1>
        <div class="d-none d-sm-inline-block">
            <span class="text-muted small">Periode: <?= date('F Y'); ?></span>
        </div>
    </div>

    <!-- BARIS 1: KARTU KPI -->
    <div class="row">

        <!-- KPI 1: Total Pendapatan -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Pendapatan (Bulan Ini)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= number_to_currency($totalPendapatan, 'IDR', 'id_ID'); ?>
                            </div>
                            
                            <!-- Indikator Growth -->
                            <div class="mt-2 text-xs font-weight-bold">
                                <?php if($pendapatanGrowth > 0): ?>
                                    <span class="text-success">
                                        <i class="fas fa-arrow-up"></i> <?= number_format($pendapatanGrowth, 1) ?>% 
                                    </span>
                                <?php elseif($pendapatanGrowth < 0): ?>
                                    <span class="text-danger">
                                        <i class="fas fa-arrow-down"></i> <?= number_format(abs($pendapatanGrowth), 1) ?>% 
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted">
                                        <i class="fas fa-minus"></i> 0% 
                                    </span>
                                <?php endif; ?>
                                <span class="text-gray-500 ml-1">dari bulan lalu</span>
                            </div>

                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI 2: Total Pengeluaran -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Pengeluaran (Bulan Ini)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= number_to_currency($totalPengeluaran, 'IDR', 'id_ID'); ?>
                            </div>

                             <!-- Indikator Growth Pengeluaran -->
                             <div class="mt-2 text-xs font-weight-bold">
                                <?php if($pengeluaranGrowth > 0): ?>
                                    <span class="text-danger"> <!-- Naik = Merah (Negatif utk biaya) -->
                                        <i class="fas fa-arrow-up"></i> <?= number_format($pengeluaranGrowth, 1) ?>% 
                                    </span>
                                <?php elseif($pengeluaranGrowth < 0): ?>
                                    <span class="text-success"> <!-- Turun = Hijau (Positif utk biaya) -->
                                        <i class="fas fa-arrow-down"></i> <?= number_format(abs($pengeluaranGrowth), 1) ?>% 
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted">
                                        <i class="fas fa-minus"></i> 0% 
                                    </span>
                                <?php endif; ?>
                                <span class="text-gray-500 ml-1">dari bulan lalu</span>
                            </div>

                        </div>
                        <div class="col-auto">
                            <i class="fas fa-arrow-down fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI 3: Keuntungan Bersih -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Keuntungan (Bulan Ini)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= number_to_currency($keuntunganBersih, 'IDR', 'id_ID'); ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-wallet fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI 4: Total Transaksi -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Transaksi (All Time)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalPenjualan; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- BARIS 2: GRAFIK UTAMA -->
    <div class="row">
        <!-- Grafik Penjualan -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Tren Penjualan (7 Hari Terakhir)</h6></div>
                <div class="card-body"><div class="chart-area"><canvas id="salesChart"></canvas></div></div>
            </div>
        </div>
        <!-- Produk Terlaris -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Top 5 Produk Terlaris</h6></div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2"><canvas id="topProductsChart"></canvas></div>
                    <div class="mt-4 text-center small"><span class="mr-2"><i class="fas fa-circle text-primary"></i> Unit Terjual</span></div>
                </div>
            </div>
        </div>
    </div>

    <!-- BARIS 3: OPERASIONAL (STOK & PELANGGAN) -->
    <div class="row">
        <!-- Stok Menipis -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-danger">Peringatan Stok Menipis (< 10)</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm" width="100%" cellspacing="0">
                            <thead class="thead-light">
                                <tr><th>Produk</th><th>Kategori</th><th class="text-center">Sisa</th></tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($lowStockProducts)) : ?>
                                    <?php foreach ($lowStockProducts as $prod) : ?>
                                        <tr>
                                            <td><?= esc($prod['nama_produk']); ?></td>
                                            <td><small><?= esc($prod['nama_kategori'] ?? '-'); ?></small></td>
                                            <td class="text-center text-danger font-weight-bold"><?= $prod['stok']; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr><td colspan="3" class="text-center text-success font-weight-bold"><i class="fas fa-check-circle"></i> Stok Aman</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
        <div class="col-12 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 border-bottom-warning">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-exclamation-triangle mr-1"></i> Analisa Dead Stock (Tidak Terjual > 90 Hari)
                    </h6>
                </div>
                <div class="card-body">
                    <p class="mb-3 small text-muted">Produk berikut memiliki stok tersedia namun tidak ada transaksi penjualan dalam 3 bulan terakhir. Pertimbangkan untuk diskon atau bundling.</p>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Nama Produk</th>
                                    <th>Kategori</th>
                                    <th class="text-center">Stok Mengendap</th>
                                    <th class="text-right">Harga Jual</th>
                                    <th class="text-right">Potensi Kerugian (Aset Diam)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($deadStock)) : ?>
                                    <?php foreach ($deadStock as $item) : ?>
                                        <tr>
                                            <td class="font-weight-bold"><?= esc($item['nama_produk']); ?></td>
                                            <td><?= esc($item['nama_kategori'] ?? '-'); ?></td>
                                            <td class="text-center"><?= $item['stok']; ?></td>
                                            <td class="text-right">
                                                <?= number_to_currency($item['harga_jual'], 'IDR', 'id_ID', 0); ?>
                                            </td>
                                            <td class="text-right text-danger font-weight-bold">
                                                <?php 
                                                    $asetDiam = $item['stok'] * $item['harga_jual'];
                                                    echo number_to_currency($asetDiam, 'IDR', 'id_ID', 0); 
                                                ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-success py-4">
                                            <i class="fas fa-check-circle fa-2x mb-2"></i><br>
                                            Hebat! Tidak ada Dead Stock ditemukan. Semua stok bergerak lancar.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <!-- Top Pelanggan -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">Top 5 Pelanggan Loyal</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm" width="100%" cellspacing="0">
                            <thead class="thead-light">
                                <tr><th>Nama Pelanggan</th><th class="text-center">Trx</th><th class="text-right">Total Belanja</th></tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($topCustomers)) : ?>
                                    <?php foreach ($topCustomers as $cust) : ?>
                                        <tr>
                                            <td>
                                                <i class="fas fa-user-circle text-gray-400 mr-1"></i>
                                                <?= esc($cust['nama_pelanggan'] ?? 'Umum'); ?>
                                            </td>
                                            <td class="text-center"><?= $cust['total_transaksi']; ?></td>
                                            <td class="text-right font-weight-bold text-success">
                                                <?= number_to_currency($cust['total_belanja'], 'IDR', 'id_ID', 0); ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr><td colspan="3" class="text-center text-muted">Belum ada data pelanggan member.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- BARIS 4: ANALISIS LANJUTAN -->
    <div class="row">
        <!-- Grafik Kategori -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Pendapatan per Kategori</h6></div>
                <div class="card-body"><div class="chart-bar"><canvas id="categorySalesChart"></canvas></div></div>
            </div>
        </div>
        <!-- Grafik Pembayaran -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-info">Metode Pembayaran</h6></div>
                <div class="card-body"><div class="chart-pie pt-4"><canvas id="paymentMethodChart"></canvas></div></div>
            </div>
        </div>
    </div>

</div>

<!-- JS Libraries -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var salesData = <?= $salesChartData ?? '[]'; ?>;
    var topProductsData = <?= $topProductsData ?? '[]'; ?>;
    var categorySalesData = <?= $categorySalesData ?? '[]'; ?>;
    var paymentMethodData = <?= $paymentMethodData ?? '[]'; ?>;
</script>
<script src="/js/dashboardowner.js"></script> 

<?= $this->endSection(); ?>