<?= $this->extend('layout/template') ?>

<?= $this->section('head') ?>
<style>
    .chart-container {
        position: relative;
        height: 350px;
        width: 100%;
    }

    .badge-success {
        background-color: #1cc88a;
        color: #fff;
    }

    .badge-danger {
        background-color: #e74a3b;
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
                <h4 class="h5 mb-0 font-weight-bold" style="color: #2d8659;">
                    Selamat Datang, <?= esc(session()->get('username')) ?>!
                </h4>
                <p class="mb-0 text-gray-700">Berikut rangkuman kondisi keuangan hari ini.</p>
            </div>
        </div>
    </div>
</div>

<!-- RINGKASAN -->
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

<!-- GRAFIK 7 HARI -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold" style="color: #2d8659;">
            <i class="fas fa-chart-line mr-2"></i>Pemasukan & Pengeluaran 7 Hari Terakhir
        </h6>
    </div>
    <div class="card-body">
        <div class="chart-container">
            <canvas id="trendChart"></canvas>
        </div>
    </div>
</div>

<!-- TABEL TRANSAKSI -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold" style="color: #2d8659;">
            <i class="fas fa-history mr-2"></i>Transaksi Keuangan Terakhir
        </h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-striped table-sm">
                <thead class="thead-light">
                    <tr>
                        <th>ID</th>
                        <th>Jenis</th>
                        <th>Keterangan</th>
                        <th>Jumlah</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($transaksi_terakhir)) : ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-3">
                                <i class="fas fa-info-circle mr-1"></i>Belum ada transaksi terbaru.
                            </td>
                        </tr>
                    <?php else : ?>
                        <?php foreach ($transaksi_terakhir as $trx) : ?>
                            <tr>
                                <td class="font-weight-bold">#<?= $trx['id_keuangan']; ?></td>
                                <td>
                                    <?php if ($trx['tipe'] === 'Pemasukan' || $trx['tipe'] === 'DP') : ?>
                                        <span class="badge badge-success">Pemasukan</span>
                                    <?php else : ?>
                                        <span class="badge badge-danger">Pengeluaran</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= esc($trx['keterangan']); ?></td>
                                <td>
                                    Rp
                                    <?php
                                    $nominal = ($trx['tipe'] === 'Pemasukan' || $trx['tipe'] === 'DP')
                                        ? $trx['pemasukan']
                                        : $trx['pengeluaran'];
                                    echo number_format($nominal, 0, ',', '.');
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


<!-- SCRIPT CHART -->
<?= $this->section('script') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // ==================== TREND 7 HARI ====================
    const trendLabels = <?= $trend_labels ?? '[]' ?>;
    const pemasukanData = <?= $pemasukan_data ?? '[]' ?>;
    const pengeluaranData = <?= $pengeluaran_data ?? '[]' ?>;

    const ctxTrend = document.getElementById('trendChart').getContext('2d');
    new Chart(ctxTrend, {
        type: 'line',
        data: {
            labels: trendLabels,
            datasets: [{
                label: 'Pemasukan',
                data: pemasukanData,
                borderColor: 'rgba(45, 134, 89, 1)',
                backgroundColor: 'rgba(45, 134, 89, 0.2)',
                borderWidth: 3,
                tension: 0.1,
                fill: true
            }, {
                label: 'Pengeluaran',
                data: pengeluaranData,
                borderColor: 'rgba(220, 53, 69, 1)',
                backgroundColor: 'rgba(220, 53, 69, 0.2)',
                borderWidth: 3,
                tension: 0.1,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // ==================== RINGKASAN BAR ====================
    const pemasukanHariIni = <?= $pemasukan_hari_ini ?? 0 ?>;
    const pengeluaranHariIni = <?= $pengeluaran_hari_ini ?? 0 ?>;
    const saldoHariIni = pemasukanHariIni - pengeluaranHariIni;

    const ctxSummary = document.getElementById('summaryChart').getContext('2d');
    new Chart(ctxSummary, {
        type: 'bar',
        data: {
            labels: ['Pemasukan', 'Pengeluaran', 'Saldo Hari Ini'],
            datasets: [{
                data: [pemasukanHariIni, pengeluaranHariIni, saldoHariIni],
                backgroundColor: [
                    'rgba(45, 134, 89, 0.6)',
                    'rgba(220, 53, 69, 0.6)',
                    'rgba(54, 162, 235, 0.6)'
                ],
                borderColor: [
                    'rgba(45, 134, 89, 1)',
                    'rgba(220, 53, 69, 1)',
                    'rgba(54, 162, 235, 1)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
</script>

<?= $this->endSection() ?>