<?= $this->extend('layout/template') ?>

<?= $this->section('head') ?>
<style>
    /* Style untuk container chart agar responsif */
    .chart-container {
        position: relative;
        height: 350px;
        width: 100%;
    }

    /* [BARU] Menambahkan style untuk badge status (seperti di riwayat) */
    .badge-warning {
        background-color: #f6c23e;
        color: #fff;
    }

    .badge-success {
        background-color: #1cc88a;
        color: #fff;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card shadow-sm mb-4" style="border-left: 5px solid #2d8659;">
    <div class="card-body p-3">
        <div class="d-flex align-items-center">
            <i class="fas fa-user-circle fa-3x mr-3" style="color: #2d8659;"></i>
            <div>
                <h4 class="h5 mb-0 font-weight-bold" style="color: #2d8659;">Selamat Datang, <?= esc(session()->get('username')) ?>!</h4>
                <p class="mb-0 text-gray-700">Ini adalah ringkasan pekerjaan Anda hari ini.</p>
            </div>
        </div>
    </div>
</div>


<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold" style="color: #2d8659;">
            <i class="fas fa-chart-bar mr-2"></i>Ringkasan Hari Ini
        </h6>
    </div>
    <div class="card-body">
        <div class="chart-container">
            <canvas id="summaryChart"></canvas>
        </div>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold" style="color: #2d8659;">
            <i class="fas fa-chart-line mr-2"></i>Grafik Omzet 7 Hari Terakhir
        </h6>
    </div>
    <div class="card-body">
        <div class="chart-container">
            <canvas id="omzetChart"></canvas>
        </div>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold" style="color: #2d8659;">
            <i class="fas fa-history mr-2"></i>Transaksi Terakhir Anda
        </h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-striped table-sm" style="width: 100%;">
                <thead class="thead-light">
                    <tr>
                        <th>ID</th>
                        <th>Pelanggan</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($transaksi_terakhir)) : ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">
                                <i class="fas fa-info-circle mr-1"></i>Belum ada transaksi hari ini.
                            </td>
                        </tr>
                    <?php else : ?>
                        <?php foreach ($transaksi_terakhir as $trx) : ?>
                            <tr>
                                <td class="font-weight-bold">#<?= $trx['id_penjualan']; ?></td>
                                <td><?= esc($trx['nama_pelanggan'] ?? 'N/A'); ?></td>
                                <td>Rp <?= number_format($trx['total'], 0, ',', '.'); ?></td>
                                <td>
                                    <?php
                                    $status_class = ($trx['status_pembayaran'] == 'Lunas') ? 'badge badge-success' : 'badge badge-warning';
                                    echo '<span class="' . $status_class . '">' . $trx['status_pembayaran'] . '</span>';
                                    ?>
                                </td>
                                <td class="text-muted"><small><?= date('d M Y', strtotime($trx['tanggal'])); ?></small></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>


<?= $this->section('script') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // =======================================================
    // KODE UNTUK CHART GARIS (OMZET 7 HARI)
    // =======================================================
    const chartLabels = <?= $chart_labels; ?>;
    const chartData = <?= $chart_data; ?>;

    const ctx = document.getElementById('omzetChart').getContext('2d');
    const omzetChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartLabels,
            datasets: [{
                label: 'Omzet (Rp)',
                data: chartData,
                backgroundColor: 'rgba(45, 134, 89, 0.2)',
                borderColor: 'rgba(45, 134, 89, 1)',
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

    // =======================================================
    // KODE UNTUK CHART BATANG (RINGKASAN HARI INI)
    // =======================================================
    const penjualanHariIni = <?= $penjualan_hari_ini ?? 0; ?>;
    const produkTerjual = <?= $produk_terjual_hari_ini ?? 0; ?>;
    const omzetHariIni = <?= $omzet_hari_ini ?? 0; ?>;

    const ctxSummary = document.getElementById('summaryChart').getContext('2d');
    const summaryChart = new Chart(ctxSummary, {
        type: 'bar',
        data: {
            labels: ['Penjualan Hari Ini', 'Produk Terjual', 'Total Omzet Hari Ini'],
            datasets: [{
                    label: 'Jumlah (Satuan)',
                    data: [penjualanHariIni, produkTerjual, null],
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    yAxisID: 'yJumlah',
                    borderWidth: 1
                },
                {
                    label: 'Omzet (Rupiah)',
                    data: [null, null, omzetHariIni],
                    backgroundColor: 'rgba(45, 134, 89, 0.6)',
                    borderColor: 'rgba(45, 134, 89, 1)',
                    yAxisID: 'yRupiah',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                'yJumlah': {
                    type: 'linear',
                    position: 'left',
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Jumlah (Transaksi/Produk)'
                    },
                    grid: {
                        drawOnChartArea: false
                    }
                },
                'yRupiah': {
                    type: 'linear',
                    position: 'right',
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Omzet (Rp)'
                    },
                    ticks: {
                        callback: function(value) {
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
                            if (context.dataset.yAxisID === 'yRupiah') {
                                label += 'Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                            } else {
                                label += context.parsed.y + ' buah/transaksi';
                            }
                            return label;
                        }
                    }
                }
            }
        }
    });
</script>
<?= $this->endSection() ?>