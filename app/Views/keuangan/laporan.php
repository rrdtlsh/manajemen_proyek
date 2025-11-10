<?= $this->extend('layout/template'); ?>

<?= $this->section('head'); ?>
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<style>
    .card-summary {
        border-left-width: 4px;
    }

    .card-success {
        border-left-color: #1cc88a;
    }

    .card-danger {
        border-left-color: #e74a3b;
    }

    .card-info {
        border-left-color: #36b9cc;
    }

    .card-primary {
        border-left-color: #4e73df;
    }

    .card-warning {
        border-left-color: #f6c23e;
    }

    .card-summary .icon-circle {
        width: 3rem;
        height: 3rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .bg-success-light {
        background-color: rgba(28, 200, 138, 0.1);
    }

    .bg-danger-light {
        background-color: rgba(231, 74, 59, 0.1);
    }

    .bg-info-light {
        background-color: rgba(54, 185, 204, 0.1);
    }

    .bg-primary-light {
        background-color: rgba(78, 115, 223, 0.1);
    }

    .bg-warning-light {
        background-color: rgba(246, 194, 62, 0.1);
    }
</style>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
        <i class="fas fa-chart-line mr-2" style="color: #2d8659;"></i>
        <?= $title; ?>
    </h1>
    <div>
        <button class="btn btn-danger btn-sm shadow-sm">
            <i class="fas fa-file-pdf fa-sm text-white-50"></i> Ekspor PDF
        </button>
        <button class="btn btn-success btn-sm shadow-sm">
            <i class="fas fa-file-excel fa-sm text-white-50"></i> Ekspor Excel
        </button>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold" style="color: #2d8659;">Filter Laporan</h6>
    </div>
    <div class="card-body">
        <form action="<?= base_url('karyawan/keuangan/laporan'); ?>" method="GET" class="form-inline">
            <div class="form-group mb-2 mr-sm-2">
                <label for="year" class="mr-2">Tahun:</label>
                <select name="year" id="year" class="form-control">
                    <?php for ($y = date('Y'); $y >= 2020; $y--): ?>
                        <option value="<?= $y; ?>" <?= ($y == $selectedYear) ? 'selected' : ''; ?>>
                            <?= $y; ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="form-group mb-2 mr-sm-2">
                <label for="quarter" class="mr-2">Kuartal:</label>
                <select name="quarter" id="quarter" class="form-control">
                    <option value="" <?= (empty($selectedQuarter)) ? 'selected' : ''; ?>>Semua (Tahunan)</option>
                    <option value="Q1" <?= ($selectedQuarter == 'Q1') ? 'selected' : ''; ?>>Q1 (Jan - Mar)</option>
                    <option value="Q2" <?= ($selectedQuarter == 'Q2') ? 'selected' : ''; ?>>Q2 (Apr - Jun)</option>
                    <option value="Q3" <?= ($selectedQuarter == 'Q3') ? 'selected' : ''; ?>>Q3 (Jul - Sep)</option>
                    <option value="Q4" <?= ($selectedQuarter == 'Q4') ? 'selected' : ''; ?>>Q4 (Okt - Des)</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary mb-2">Tampilkan</button>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card card-summary card-success shadow h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Pemasukan</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">Rp <?= number_format($total_pemasukan, 0, ',', '.'); ?></div>
                    </div>
                    <div class="col-auto">
                        <div class="icon-circle bg-success-light">
                            <i class="fas fa-arrow-down fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card card-summary card-danger shadow h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Total Pengeluaran</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">Rp <?= number_format($total_pengeluaran, 0, ',', '.'); ?></div>
                    </div>
                    <div class="col-auto">
                        <div class="icon-circle bg-danger-light">
                            <i class="fas fa-arrow-up fa-2x text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card card-summary <?= ($laba_rugi >= 0) ? 'card-info' : 'card-danger'; ?> shadow h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold <?= ($laba_rugi >= 0) ? 'text-info' : 'text-danger'; ?> text-uppercase mb-1">Laba / Rugi</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">Rp <?= number_format($laba_rugi, 0, ',', '.'); ?></div>
                    </div>
                    <div class="col-auto">
                        <div class="<?= ($laba_rugi >= 0) ? 'bg-info-light' : 'bg-danger-light'; ?> icon-circle">
                            <i class="fas fa-calculator fa-2x <?= ($laba_rugi >= 0) ? 'text-info' : 'text-danger'; ?>"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-6 col-md-6 mb-4">
        <div class="card card-summary card-primary shadow h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Jumlah Transaksi (Lunas)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= number_format($total_transaksi); ?></div>
                    </div>
                    <div class="col-auto">
                        <div class="icon-circle bg-primary-light">
                            <i class="fas fa-receipt fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-6 col-md-6 mb-4">
        <div class="card card-summary card-warning shadow h-100">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Rata-Rata Transaksi (Lunas)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">Rp <?= number_format($avg_transaksi, 0, ',', '.'); ?></div>
                    </div>
                    <div class="col-auto">
                        <div class="icon-circle bg-warning-light">
                            <i class="fas fa-tags fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="card shadow mb-4">
    <div class="card-header py-3" style="background-color: #2d8659; color: white;">
        <h6 class="m-0 font-weight-bold text-white">
            Rincian Arus Kas (<?= $selectedQuarter ? $selectedQuarter . ' - ' : ''; ?><?= $selectedYear; ?>)
        </h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTableLaporan" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th>Tipe</th>
                        <th>Pemasukan (Debit)</th>
                        <th>Pengeluaran (Kredit)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($laporan)): ?>
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada data untuk periode ini.</td>
                        </tr>
                    <?php endif; ?>
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
<script>
    $(document).ready(function() {
        // Inisialisasi DataTable untuk tabel laporan
        $('#dataTableLaporan').DataTable({
            "order": [
                [0, "desc"]
            ],
            "paging": true,
            "searching": true
        });
    });
</script>
<?= $this->endSection(); ?>