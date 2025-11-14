<?= $this->extend('layout/template') ?>

<?= $this->section('head') ?>
<style>
    .chart-container {
        position: relative;
        height: 350px;
        width: 100%;
    }
</style>
<?= $this->endSection() ?>


<?= $this->section('content') ?>

<!-- GREETING -->
<div class="card shadow-sm mb-4" style="border-left: 5px solid #2d8659;">
    <div class="card-body p-3 d-flex align-items-center">
        <i class="fas fa-warehouse fa-3x mr-3" style="color:#2d8659;"></i>
        <div>
            <h4 class="font-weight-bold" style="color:#2d8659;">
                Selamat Datang, <?= esc(session()->get('username')) ?>!
            </h4>
            <p class="mb-0 text-gray-700">Ringkasan inventaris hari ini.</p>
        </div>
    </div>
</div>

<!-- RINGKASAN HARI INI -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold" style="color:#2d8659;">
            <i class="fas fa-chart-bar mr-2"></i>Ringkasan Hari Ini
        </h6>
    </div>
    <div class="card-body">
        <div class="chart-container">
            <canvas id="summaryChart"></canvas>
        </div>
    </div>
</div>

<!-- Grafik Stok 7 Hari -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold" style="color:#2d8659;">
            <i class="fas fa-chart-line mr-2"></i>Stok 7 Hari Terakhir
        </h6>
    </div>
    <div class="card-body">
        <div class="chart-container">
            <canvas id="stokChart"></canvas>
        </div>
    </div>
</div>

<!-- DATA TERBARU -->
<div class="row">

    <!-- PRODUK -->
    <div class="col-md-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold" style="color:#2d8659;">
                    <i class="fas fa-box mr-2"></i>Produk Terbaru
                </h6>
            </div>
            <div class="card-body p-2">
                <ul class="list-group">
                    <?php foreach ($latest_produk as $p): ?>
                        <li class="list-group-item small">
                            <b><?= esc($p['nama_produk']) ?></b><br>
                            Kode: <?= esc($p['kode_produk']) ?>
                        </li>
                    <?php endforeach ?>
                </ul>
            </div>
        </div>
    </div>

    <!-- RESTOK -->
    <div class="col-md-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold" style="color:#2d8659;">
                    <i class="fas fa-truck-loading mr-2"></i>Restok Terbaru
                </h6>
            </div>
            <div class="card-body p-2">
                <ul class="list-group">
                    <?php foreach ($latest_restok as $r): ?>
                        <li class="list-group-item small">
                            <b><?= esc($r['nama_barang']) ?></b><br>
                            Supplier: <?= esc($r['nama_supplier']) ?>
                        </li>
                    <?php endforeach ?>
                </ul>
            </div>
        </div>
    </div>

    <!-- SUPPLIER -->
    <div class="col-md-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold" style="color:#2d8659;">
                    <i class="fas fa-users mr-2"></i>Supplier Terbaru
                </h6>
            </div>
            <div class="card-body p-2">
                <ul class="list-group">
                    <?php foreach ($latest_supplier as $s): ?>
                        <li class="list-group-item small">
                            <b><?= esc($s['nama_supplier']) ?></b><br>
                            Telp: <?= esc($s['no_telp']) ?>
                        </li>
                    <?php endforeach ?>
                </ul>
            </div>
        </div>
    </div>

</div>

<?= $this->endSection() ?>


<?= $this->section('script') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    /* ================================
   RINGKASAN HARI INI (BAR CHART)
==================================*/
    const summaryChart = new Chart(document.getElementById('summaryChart'), {
        type: 'bar',
        data: {
            labels: ['Produk', 'Total Stok', 'Restok Menunggu', 'Restok Disetujui'],
            datasets: [{
                label: 'Jumlah',
                data: [
                    <?= $jumlah_produk ?>,
                    <?= $total_stok ?>,
                    <?= $restok_menunggu ?>,
                    <?= $restok_disetujui ?>
                ],
                backgroundColor: 'rgba(45,134,89,0.6)',
                borderColor: 'rgba(45,134,89,1)',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });


    /* ================================
       GRAFIK STOK 7 HARI (LINE CHART)
    ==================================*/
    const stokChart = new Chart(document.getElementById('stokChart'), {
        type: 'line',
        data: {
            labels: <?= $stok_labels ?>,
            datasets: [{
                label: 'Total Stok',
                data: <?= $stok_values ?>,
                borderColor: 'rgba(54,162,235,1)',
                backgroundColor: 'rgba(54,162,235,0.2)',
                borderWidth: 3,
                fill: true,
                tension: 0.2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
</script>

<?= $this->endSection() ?>