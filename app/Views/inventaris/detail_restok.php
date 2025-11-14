<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="container-fluid">

    <div class="d-flex justify-content-between mb-4">
        <h1 class="h3 text-gray-800"><?= $title; ?> (ID: #<?= $restok['id_restok']; ?>)</h1>
        <a href="<?= base_url('karyawan/inventaris/restok'); ?>" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">Informasi Restok</h6>
        </div>

        <div class="card-body">
            <ul class="list-group list-group-flush">

                <li class="list-group-item d-flex justify-content-between">
                    <strong>Nama Supplier</strong>
                    <span><?= esc($restok['nama_supplier']); ?></span>
                </li>

                <li class="list-group-item d-flex justify-content-between">
                    <strong>Nama Barang</strong>
                    <span><?= esc($restok['nama_barang']); ?></span>
                </li>

                <li class="list-group-item d-flex justify-content-between">
                    <strong>Qty</strong>
                    <span><?= esc($restok['qty']); ?></span>
                </li>

                <li class="list-group-item d-flex justify-content-between">
                    <strong>Harga Satuan</strong>
                    <span>Rp <?= number_format($restok['harga_satuan'], 0, ',', '.'); ?></span>
                </li>

                <li class="list-group-item d-flex justify-content-between">
                    <strong>Total Harga</strong>
                    <span>Rp <?= number_format($restok['total_harga'], 0, ',', '.'); ?></span>
                </li>

                <li class="list-group-item d-flex justify-content-between">
                    <strong>Status</strong>
                    <span class="badge badge-<?= $restok['status'] == 'Disetujui' ? 'success' : 'warning'; ?>">
                        <?= esc($restok['status']); ?>
                    </span>
                </li>

            </ul>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>