<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<h1 class="h3 mb-4 text-gray-800">Persetujuan Restok Supplier</h1>

<div class="card shadow mb-4">
    <div class="card-header py-3" style="background-color:#2d8659;color:white;">
        <h6 class="m-0 font-weight-bold">Daftar Pengajuan Restok</h6>
    </div>

    <div class="card-body">
        <table class="table table-bordered" id="dataTableRestok">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Supplier</th>
                    <th>Barang</th>
                    <th>Qty</th>
                    <th>Total</th>
                    <th>Status Owner</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php $no = 1; ?>
                <?php foreach ($data_restok as $r): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $r['nama_supplier']; ?></td>
                        <td><?= $r['nama_barang']; ?></td>
                        <td><?= $r['qty']; ?></td>
                        <td>Rp <?= number_format($r['total_harga'], 0, ',', '.'); ?></td>

                        <td>
                            <?php if ($r['status_owner'] == 'Menunggu'): ?>
                                <span class="badge badge-warning">Menunggu</span>
                            <?php elseif ($r['status_owner'] == 'Disetujui'): ?>
                                <span class="badge badge-success">Disetujui</span>
                            <?php else: ?>
                                <span class="badge badge-danger">Ditolak</span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <?php if ($r['status_owner'] == 'Menunggu'): ?>

                                <a href="<?= base_url('owner/restok/approve/' . $r['id_restok']); ?>"
                                    class="btn btn-sm btn-success">
                                    <i class="fas fa-check"></i> Setujui
                                </a>

                                <a href="<?= base_url('owner/restok/reject/' . $r['id_restok']); ?>"
                                    class="btn btn-sm btn-danger">
                                    <i class="fas fa-times"></i> Tolak
                                </a>

                            <?php else: ?>
                                <i>Tidak ada aksi</i>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>

        </table>
    </div>
</div>

<?= $this->endSection(); ?>