<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $title; ?> (ID: #<?= esc($penjualan['id_penjualan']); ?>)</h1>
        <a href="<?= base_url('karyawan/riwayat_penjualan'); ?>" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali ke Riwayat
        </a>
    </div>

    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Info Transaksi</h6>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Tanggal</strong>
                            <span><?= date('d M Y', strtotime($penjualan['tanggal'])); ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Pelanggan</strong>
                            <span><?= esc($penjualan['nama_pelanggan'] ?? 'N/A'); ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Metode Bayar</strong>
                            <span class="badge badge-pill badge-light"><?= esc(ucfirst($penjualan['metode_pembayaran'])); ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Status</strong>
                            <?php
                            $status_class = ($penjualan['status_pembayaran'] == 'Lunas') ? 'success' : 'warning';
                            ?>
                            <span class="badge badge-pill badge-<?= $status_class; ?>"><?= esc($penjualan['status_pembayaran']); ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Uang Muka (DP)</strong>
                            <span>Rp <?= number_format($penjualan['jumlah_dp'], 0, ',', '.'); ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center bg-light">
                            <h5 class="font-weight-bold text-primary">Total</h5>
                            <h5 class="font-weight-bold text-primary">Rp <?= number_format($penjualan['total'], 0, ',', '.'); ?></h5>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-8 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Daftar Item Dibeli</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Produk</th>
                                    <th class="text-right">Harga Satuan</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                <?php foreach ($detail_items as $item) : ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td>
                                            <?= esc($item['nama_produk']); ?>
                                        </td>
                                        <td class="text-right">Rp <?= number_format($item['harga_satuan'], 0, ',', '.'); ?></td>
                                        <td class="text-center"><?= $item['qty']; ?></td>
                                        <td class="text-right">Rp <?= number_format($item['harga_satuan'] * $item['qty'], 0, ',', '.'); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-right font-weight-bold">Total Belanja</td>
                                    <td class="text-right font-weight-bold">Rp <?= number_format($penjualan['total'], 0, ',', '.'); ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<?= $this->endSection(); ?>