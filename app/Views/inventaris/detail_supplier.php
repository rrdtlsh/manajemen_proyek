<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="container-fluid">

    <div class="d-flex justify-content-between mb-4">
        <h1 class="h3 text-gray-800"><?= $title; ?> (ID: #<?= $supplier['id_supplier']; ?>)</h1>
        <a href="<?= base_url('karyawan/inventaris/supplier'); ?>" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="row">
        <!-- CARD KIRI -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Supplier</h6>
                </div>
                <div class="card-body">

                    <ul class="list-group list-group-flush">

                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Nama Supplier</strong>
                            <span><?= esc($supplier['nama_supplier']); ?></span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Alamat</strong>
                            <span><?= esc($supplier['alamat']); ?></span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between">
                            <strong>No Telepon</strong>
                            <span><?= esc($supplier['no_telp']); ?></span>
                        </li>

                    </ul>

                </div>
            </div>
        </div>

        <!-- CARD KANAN -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Riwayat Restok Supplier</h6>
                </div>

                <div class="card-body">
                    <?php if (empty($riwayat)): ?>
                        <p class="text-muted">Belum ada riwayat restok.</p>
                    <?php else: ?>

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Barang</th>
                                        <th>Qty</th>
                                        <th>Harga</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php $no = 1; ?>
                                    <?php foreach ($riwayat as $r): ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td><?= esc($r['nama_barang']); ?></td>
                                            <td><?= $r['qty']; ?></td>
                                            <td>Rp <?= number_format($r['harga_satuan'], 0, ',', '.'); ?></td>
                                            <td>Rp <?= number_format($r['total_harga'], 0, ',', '.'); ?></td>
                                            <td>
                                                <span class="badge badge-<?= $r['status'] == 'Disetujui' ? 'success' : 'warning' ?>">
                                                    <?= esc($r['status']); ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>

                            </table>
                        </div>

                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>

</div>

<?= $this->endSection(); ?>