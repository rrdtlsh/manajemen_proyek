<?= $this->extend('layout/template'); ?>

<?= $this->section('head'); ?>
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
        <i class="fas fa-arrow-down mr-2" style="color: #1cc88a;"></i>
        <?= $title; ?>
    </h1>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3" style="background-color: #2d8659; color: white;">
        <h6 class="m-0 font-weight-bold text-white">Rincian Pemasukan</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th>Tipe</th>
                        <th>Jumlah Pemasukan</th>
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
                                <span class="badge badge-success"><?= $item['tipe']; ?></span>
                            </td>
                            <td class="text-success">
                                Rp <?= number_format($item['pemasukan'], 0, ',', '.'); ?>
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
        $('#dataTable').DataTable({
            "order": [
                [0, "desc"]
            ]
        });
    });
</script>
<?= $this->endSection(); ?>