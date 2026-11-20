<?= $this->extend('layout/template'); ?> 

<?= $this->section('content'); ?>
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard Owner</h1>
    
    <div>
        <a href="<?= base_url('owner/download_transcript'); ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2">
            <i class="fas fa-download fa-sm text-white-50"></i> Download Transkrip Status
        </a>
        <span class="text-muted small">Periode: <?= date('F Y'); ?></span>
    </div>
    </div>

    <div class="row">
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
                            <div class="mt-2 text-xs font-weight-bold">
                                <?php if($pendapatanGrowth > 0): ?>
                                    <span class="text-success"><i class="fas fa-arrow-up"></i> <?= number_format($pendapatanGrowth, 1) ?>%</span>
                                <?php elseif($pendapatanGrowth < 0): ?>
                                    <span class="text-danger"><i class="fas fa-arrow-down"></i> <?= number_format(abs($pendapatanGrowth), 1) ?>%</span>
                                <?php else: ?>
                                    <span class="text-muted"><i class="fas fa-minus"></i> 0%</span>
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
                             <div class="mt-2 text-xs font-weight-bold">
                                <?php if($pengeluaranGrowth > 0): ?>
                                    <span class="text-danger"><i class="fas fa-arrow-up"></i> <?= number_format($pengeluaranGrowth, 1) ?>%</span>
                                <?php elseif($pengeluaranGrowth < 0): ?>
                                    <span class="text-success"><i class="fas fa-arrow-down"></i> <?= number_format(abs($pengeluaranGrowth), 1) ?>%</span>
                                <?php else: ?>
                                    <span class="text-muted"><i class="fas fa-minus"></i> 0%</span>
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

    <div class="row">
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Tren Penjualan (7 Hari Terakhir)</h6></div>
                <div class="card-body"><div class="chart-area"><canvas id="salesChart"></canvas></div></div>
            </div>
        </div>
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
    
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-info">Riwayat Pergerakan Stok (7 Hari Terakhir)</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area" style="height: 300px;">
                        <canvas id="stockMovementChart"></canvas>
                    </div>
                    <div class="mt-2 text-center small">
                        <span class="mr-2"><i class="fas fa-circle text-success"></i> Barang Masuk (Restok)</span>
                        <span class="mr-2"><i class="fas fa-circle text-danger"></i> Barang Keluar (Terjual)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>                                

    <div class="row">
        <div class="col-12 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-danger">Peringatan Stok Menipis (< 10)</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm" width="100%" cellspacing="0">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-center">Produk</th>
                                    <th class="text-center">Kode</th>
                                    <th class="text-center">Sisa Stok</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($lowStockProducts)) : ?>
                                    <?php foreach ($lowStockProducts as $prod) : ?>
                                        <tr>
                                            <td><?= esc($prod['nama_produk']); ?></td>
                                            <td><small><?= esc($prod['kode_produk'] ?? '-'); ?></small></td>
                                            <td class="text-center text-danger font-weight-bold"><?= $prod['stok']; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="3" class="text-center py-3 bg-light">
                                            <span class="text-success"><i class="fas fa-check-circle"></i> Stok Aman</span>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 mb-4">
            <div class="card shadow mb-4 border-left-warning">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-exclamation-triangle mr-2"></i> Analisa Dead Stock (Tidak Terjual > 90 Hari)
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-0" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center">Nama Produk</th>
                                <th class="text-center">Stok Mengendap</th>
                                <th class="text-center">Potensi Kerugian</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($deadStock)) : ?>
                                <?php foreach ($deadStock as $item) : ?>
                                    <tr>
                                        <td class="align-middle font-weight-bold text-dark text-left"><?= esc($item['nama_produk']); ?></td>
                                        <td class="align-middle text-center"><span class="font-weight-bold text-gray-800"><?= $item['stok']; ?></span> Unit</td>
                                        <td class="align-middle text-right text-danger font-weight-bold">Rp <?= number_format($item['stok'] * $item['harga_jual'], 0, ',', '.'); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr><td colspan="3" class="text-center py-3">Kinerja Penjualan Bagus!</td></tr>
                            <?php endif; ?>
                        </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">Top 5 Pelanggan Loyal</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm" width="100%" cellspacing="0">
                            <thead class="thead-light">
                                 <tr>
                                <th class="text-center">Nama Pelanggan</th>
                                <th class="text-center">Trx</th>
                                <th class="text-center">Total Belanja</th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($topCustomers)) : ?>
                                    <?php foreach ($topCustomers as $cust) : ?>
                                        <tr>
                                            <td><?= esc($cust['nama_pelanggan'] ?? 'Umum'); ?></td>
                                            <td class="text-center"><?= $cust['total_transaksi']; ?></td>
                                            <td class="text-right font-weight-bold text-success"><?= number_to_currency($cust['total_belanja'], 'IDR', 'id_ID', 0); ?></td>
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

    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4 h-100">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-success">Pendapatan per Kategori</h6>
                </div>
                <div class="card-body">
                    <div class="chart-bar" style="height: 250px;">
                        <canvas id="categorySalesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4 h-100 border-left-primary">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-wallet mr-2"></i> Proporsi Metode Pembayaran
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4" style="height: 250px;">
                        <canvas id="paymentMethodChartReplacement"></canvas>
                    </div>
                    <div class="mt-3 text-center small">
                        <span class="mr-2 text-muted">
                            <i class="fas fa-info-circle"></i> Menampilkan perbandingan jumlah transaksi Cash vs Transfer
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Ambil Data dari Controller (PHP ke JS)
    var salesData = <?= $salesChartData ?? '[]'; ?>;
    var topProductsData = <?= $topProductsData ?? '[]'; ?>;
    var categorySalesData = <?= $categorySalesData ?? '[]'; ?>;
    var paymentMethodData = <?= $paymentMethodData ?? '[]'; ?>; // Data ini sudah ada di controller
    var stockMovementData = <?= $stockMovementData ?? '[]'; ?>;
</script>

<script src="/js/dashboardowner.js"></script> 

<script>
document.addEventListener("DOMContentLoaded", function() {
   
    var labels = [];
    var dataValues = [];
    var colors = ['#4e73df', '#1cc88a', '#36b9cc']; 

    if (paymentMethodData && paymentMethodData.length > 0) {
        paymentMethodData.forEach(function(item) {
            var label = item.metode_pembayaran.charAt(0).toUpperCase() + item.metode_pembayaran.slice(1);
            labels.push(label);
            dataValues.push(item.jumlah);
        });
    } else {
        // Data Dummy jika kosong agar tidak error
        labels = ["Cash", "Transfer"];
        dataValues = [0, 0];
    }

    var ctx = document.getElementById("paymentMethodChartReplacement");
    if(ctx) {
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: dataValues,
                    backgroundColor: ['#4e73df', '#1cc88a'], // Biru (Cash), Hijau (Transfer)
                    hoverBackgroundColor: ['#2e59d9', '#17a673'],
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }],
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                },
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 20
                    }
                },
                cutoutPercentage: 70, 
            },
        });
    }
});
</script>

<?= $this->endSection(); ?>