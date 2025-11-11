<?= $this->extend('layout/template'); ?>

<?= $this->section('head'); ?>
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<link href="<?= base_url('css/laporan_keuangan.css') ?>" rel="stylesheet">
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
        <i class="fas fa-file-invoice-dollar mr-2" style="color: #2d8659;"></i>
        <?= $title; ?>
    </h1>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3" style="background-color: #f8f9fc;">
        <h6 class="m-0 font-weight-bold text-gray-800">Filter Laporan</h6>
    </div>
    <div class="card-body">
        <form class="form-row align-items-end">
            <div class="form-group col-md-4">
                <label for="yearFilter">Tahun</label>
                <select id="yearFilter" class="form-control">
                    <option value="2025" <?= ($selectedYear == '2025') ? 'selected' : ''; ?>>2025</option>
                    <option value="2024" <?= ($selectedYear == '2024') ? 'selected' : ''; ?>>2024</option>
                    <option value="2023" <?= ($selectedYear == '2023') ? 'selected' : ''; ?>>2023</option>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label for="quarterFilter">Kuartal</label>
                <select id="quarterFilter" class="form-control">
                    <option value="">Semua Kuartal</option>
                    <option value="Q1" <?= ($selectedQuarter == 'Q1') ? 'selected' : ''; ?>>Q1 (Jan-Mar)</option>
                    <option value="Q2" <?= ($selectedQuarter == 'Q2') ? 'selected' : ''; ?>>Q2 (Apr-Jun)</option>
                    <option value="Q3" <?= ($selectedQuarter == 'Q3') ? 'selected' : ''; ?>>Q3 (Jul-Sep)</option>
                    <option value="Q4" <?= ($selectedQuarter == 'Q4') ? 'selected' : ''; ?>>Q4 (Okt-Des)</option>
                </select>
            </div>
            <div class="form-group col-md-4">
                <button type="button" id="filterButton" class="btn btn-primary btn-block" style="background-color: #2d8659; border-color: #2d8659;">
                    <i class="fas fa-filter mr-1"></i> Terapkan Filter
                </button>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card card-summary card-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Pemasukan</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">Rp <?= number_format($total_pemasukan, 0, ',', '.'); ?></div>
                    </div>
                    <div class="col-auto">
                        <div class="icon-circle bg-success-light">
                            <i class="fas fa-arrow-down fa-2x text-success-light"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card card-summary card-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Total Pengeluaran</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">Rp <?= number_format($total_pengeluaran, 0, ',', '.'); ?></div>
                    </div>
                    <div class="col-auto">
                        <div class="icon-circle bg-danger-light">
                            <i class="fas fa-arrow-up fa-2x text-danger-light"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card card-summary <?= ($laba_rugi >= 0) ? 'card-primary' : 'card-warning'; ?> shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold <?= ($laba_rugi >= 0) ? 'text-primary' : 'text-warning'; ?> text-uppercase mb-1">Laba / Rugi</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">Rp <?= number_format($laba_rugi, 0, ',', '.'); ?></div>
                    </div>
                    <div class="col-auto">
                        <div class="icon-circle <?= ($laba_rugi >= 0) ? 'bg-primary-light' : 'bg-warning-light'; ?>">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card card-summary card-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Transaksi (Lunas)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($total_transaksi, 0, ',', '.'); ?></div>
                    </div>
                    <div class="col-auto">
                        <div class="icon-circle bg-info-light">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3" style="background-color: #2d8659; color: white;">
        <h6 class="m-0 font-weight-bold text-white">Rincian Arus Kas (Buku Besar)</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTableLaporan" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th>Tipe</th>
                        <th>Pemasukan</th>
                        <th>Pengeluaran</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($laporan as $item) : ?>
                        <tr>
                            <td>
                                <?php if ($item['tanggal']) : ?>
                                    <?= \CodeIgniter\I18n\Time::parse($item['tanggal'])->toLocalizedString('dd MMMM yyyy'); ?>
                                <?php else : ?>
                                    <span class="text-muted">N/A</span>
                                <?php endif; ?>
                            </td>
                            <td><?= $item['keterangan']; ?></td>
                            <td>
                                <?php if ($item['tipe'] == 'Pemasukan' || $item['tipe'] == 'DP'): ?>
                                    <span class="badge badge-success"><?= $item['tipe']; ?></span>
                                <?php else: ?>
                                    <span class="badge badge-danger"><?= $item['tipe']; ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="text-success">
                                <?= ($item['pemasukan'] > 0) ? 'Rp ' . number_format($item['pemasukan'], 0, ',', '.') : '-'; ?>
                            </td>
                            <td class="text-danger">
                                <?= ($item['pengeluaran'] > 0) ? 'Rp ' . number_format($item['pengeluaran'], 0, ',', '.') : '-'; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('script'); ?>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url('js/laporan_keuangan.js') ?>"></script>
<?= $this->endSection(); ?>