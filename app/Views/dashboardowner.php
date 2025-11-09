<?= $this->extend('layout/template'); ?>

<?= $this->section('head'); ?>
    <link href="<?= base_url('css/dashboardowner.css') ?>" rel="stylesheet">
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard Analitik Owner</h1>
    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
        <i class="fas fa-download fa-sm text-white-50"></i> Generate Laporan
    </a>
</div>

<div class="row">

    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Pendapatan (Bulan Ini)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            Rp <?= number_format($totalPendapatan, 0, ',', '.'); ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Transaksi (Hari Ini)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?= $totalTransaksi; ?> Transaksi
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-cash-register fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Produk Stok Menipis (&lt; 10)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?= $stokMenipis; ?> Produk
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">

    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Grafik Pendapatan (7 Hari Terakhir)</h6>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="grafikPenjualanArea"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Produk Terlaris (Bulan Ini)</h6>
            </div>
            <div class="card-body">
                <?php if (empty($produkTerlaris)) : ?>
                    <p class="text-center text-muted">Belum ada data penjualan.</p>
                <?php else : ?>
                    <?php foreach ($produkTerlaris as $produk) : ?>
                        <h4 class="small font-weight-bold">
                            <?= $produk['nama_produk']; ?>
                            <span class="float-right"><?= $produk['total_terjual']; ?> Pcs</span>
                        </h4>
                        <div class="progress mb-4">
                            <?php
                            // Hitung persentase (asumsi 100% adalah produk teratas)
                            $persentase = ($produk['total_terjual'] / $produkTerlaris[0]['total_terjual']) * 100;
                            ?>
                            <div class="progress-bar bg-info" role="progressbar" style="width: <?= $persentase; ?>%" 
                                 aria-valuenow="<?= $persentase; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>


<?= $this->section('script'); ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Siapkan data dari PHP untuk digunakan oleh file dashboardowner.js
        var dataGrafikPenjualan = {
            labels: <?= json_encode($grafikLabels); ?>,
            values: <?= json_encode($grafikValues); ?>
        };
    </script>

    <script src="<?= base_url('js/dashboardowner.js') ?>"></script>
<?= $this->endSection(); ?>