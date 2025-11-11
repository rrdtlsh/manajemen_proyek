<?= $this->extend('layout/template'); ?>

<?= $this->section('head'); ?>
<style>
    /* Style untuk container chart agar responsif */
    .chart-container {
        position: relative;
        height: 350px;
        width: 100%;
    }
    .kpi-card-icon {
        font-size: 2.5rem;
        opacity: 0.3;
    }
    .kpi-card .card-body {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
</style>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>

<!-- Salam Pembuka -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
        <i class="fas fa-user-shield mr-2"></i>
        <?= esc($title); ?>
    </h1>
    <span class="text-muted">Ringkasan Bisnis Bulan Ini</span>
</div>

<!-- 1. Rangkaian Kartu KPI (Key Performance Indicator) -->
<div class="row">

    <!-- KPI: Total Pendapatan -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2 kpi-card">
            <div class="card-body">
                <div>
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                        Pendapatan (Bulan Ini)</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">Rp <?= number_format($totalPendapatan, 0, ',', '.'); ?></div>
                </div>
                <i class="fas fa-dollar-sign kpi-card-icon text-gray-300"></i>
            </div>
        </div>
    </div>

    <!-- KPI: Laba Kotor -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2 kpi-card">
            <div class="card-body">
                <div>
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        Laba Kotor (Bulan Ini)</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">Rp <?= number_format($labaKotor, 0, ',', '.'); ?></div>
                </div>
                <i class="fas fa-piggy-bank kpi-card-icon text-gray-300"></i>
            </div>
        </div>
    </div>

    <!-- KPI: Transaksi Hari Ini -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2 kpi-card">
            <div class="card-body">
                <div>
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Transaksi (Hari Ini)</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalTransaksi; ?></div>
                </div>
                <i class="fas fa-cash-register kpi-card-icon text-gray-300"></i>
            </div>
        </div>
    </div>

    <!-- KPI: Stok Menipis -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2 kpi-card">
            <div class="card-body">
                <div>
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                        Produk Akan Habis</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $stokMenipis; ?> Produk</div>
                </div>
                <i class="fas fa-exclamation-triangle kpi-card-icon text-gray-300"></i>
            </div>
        </div>
    </div>
</div>

<!-- 2. Grafik dan Produk Terlaris -->
<div class="row">

    <!-- Grafik Omzet 7 Hari -->
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-chart-line mr-2"></i>Grafik Omzet 7 Hari Terakhir
                </h6>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="omzetChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Produk Terlaris -->
    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-fire-alt mr-2"></i>Produk Terlaris
                </h6>
            </div>
            <div class="card-body">
                <?php if (empty($produkTerlaris)) : ?>
                    <p class="text-muted text-center">Belum ada data penjualan.</p>
                <?php else : ?>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($produkTerlaris as $produk) : ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <?= esc($produk['nama_produk']); ?>
                                <span class="badge badge-primary badge-pill"><?= $produk['total_terjual']; ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>

<?= $this->endSection(); ?>


<?= $this->section('script'); ?>
<!-- Load Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Menunggu dokumen siap
    document.addEventListener("DOMContentLoaded", function() {
        
        // [PERBAIKAN ADA DI SINI]
        // Variabel disamakan dengan Controller: $chartLabels dan $chartValues
        // Tidak perlu json_encode lagi, karena sudah di-encode oleh Controller
        const chartLabels = <?= $chartLabels; ?>;
        const chartValues = <?= $chartValues; ?>;

        // Inisialisasi Grafik Omzet
        const ctx = document.getElementById('omzetChart').getContext('2d');
        const omzetChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Omzet (Rp)',
                    data: chartValues,
                    backgroundColor: 'rgba(78, 115, 223, 0.05)',
                    borderColor: 'rgba(78, 115, 223, 1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value, index, values) {
                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += 'Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
<?= $this->endSection(); ?>