<?= $this->extend('layout/template'); ?>

<?= $this->section('head'); ?>
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<link href="<?= base_url('css/riwayat_penjualan.css') ?>" rel="stylesheet">
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
        <i class="fas fa-history mr-2" style="color: #2d8659;"></i>
        <?= $title; ?>
    </h1>
</div>

<?php if (session()->getFlashdata('success')) : ?>
    <div class="alert alert-success d-none" role="alert">
        <?= session()->getFlashdata('success'); ?>
    </div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger d-none" role="alert">
        <?= session()->getFlashdata('error'); ?>
    </div>
<?php endif; ?>


<div class="card shadow mb-4">
    <div class="card-header py-3" style="background-color: #2d8659; color: white;">
        <h6 class="m-0 font-weight-bold text-white">Data Seluruh Transaksi</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTableRiwayat" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID Transaksi</th>
                        <th>Tanggal</th>
                        <th>Nama Pembeli</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Metode Bayar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($penjualan as $trx) : ?>
                        <tr>
                            <td>#<?= $trx['id_penjualan']; ?></td>
                            <td>
                                <?php if ($trx['tanggal']) : ?>
                                    <?= \CodeIgniter\I18n\Time::parse($trx['tanggal'])->toLocalizedString('dd MMMM yyyy'); ?>
                                <?php else : ?>
                                    <span class="text-muted">N/A</span>
                                <?php endif; ?>
                            </td>

                            <td><?= $trx['nama_pelanggan'] ?? 'N/A'; ?></td>

                            <td>Rp <?= number_format($trx['total'], 0, ',', '.'); ?></td>
                            <td>
                                <?php
                                if ($trx['status_pembayaran'] == 'Lunas'):
                                    echo '<span class="badge badge-success">Lunas</span>';
                                elseif ($trx['status_pembayaran'] == 'Belum Lunas'):
                                    echo '<span class="badge badge-warning">Belum Lunas</span>';
                                else:
                                    echo '<span class="badge badge-danger">' . $trx['status_pembayaran'] . '</span>';
                                endif;
                                ?>
                            </td>
                            <td><?= $trx['metode_pembayaran']; ?></td>
                            <td>
                                <a href="<?= base_url('karyawan/detail_penjualan/' . $trx['id_penjualan']); ?>" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <?php if ($trx['status_pembayaran'] == 'Belum Lunas') : ?>
                                    <a href="<?= base_url('karyawan/edit_penjualan/' . $trx['id_penjualan']); ?>" class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                <?php endif; ?>

                                <button type="button" class="btn btn-danger btn-sm"
                                    onclick="confirmDelete('<?= $trx['id_penjualan']; ?>', '<?= base_url('karyawan/delete_penjualan/' . $trx['id_penjualan']); ?>')">
                                    <i class="fas fa-trash"></i>
                                </button>
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
<script src="<?= base_url('js/riwayat_penjualan.js') ?>"></script>
<?= $this->endSection(); ?>