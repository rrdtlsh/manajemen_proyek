<?= $this->extend('layout/template'); ?>

<?= $this->section('head'); ?>
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
        <i class="fas fa-file-invoice-dollar mr-2" style="color: #2d8659;"></i>
        <?= $title; ?>
    </h1>
    
    </div>

<div class="card shadow mb-4">
    <div class="card-header py-3" style="background-color: #f8f9fc; border-bottom: 1px solid #e3e6f0;">
        <h6 class="m-0 font-weight-bold text-primary">Data Transaksi Masuk</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="dataTableLaporan" width="100%" cellspacing="0">
                <thead class="thead-light">
                    <tr>
                        <th>ID</th>
                        <th>Tanggal</th>
                        <th>Pelanggan</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Metode</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($penjualan as $trx) : ?>
                        <tr>
                            <td class="font-weight-bold">#<?= $trx['id_penjualan']; ?></td>
                            <td>
                                <?= date('d M Y', strtotime($trx['tanggal'])); ?>
                            </td>
                            <td><?= esc($trx['nama_pelanggan'] ?? 'Umum'); ?></td>
                            <td class="text-right">Rp <?= number_format($trx['total'], 0, ',', '.'); ?></td>
                            <td class="text-center">
                                <?php
                                if ($trx['status_pembayaran'] == 'Lunas'):
                                    echo '<span class="badge badge-success">Lunas</span>';
                                elseif ($trx['status_pembayaran'] == 'Belum Lunas'):
                                    echo '<span class="badge badge-warning">Belum Lunas (DP)</span>';
                                else:
                                    echo '<span class="badge badge-danger">' . esc($trx['status_pembayaran']) . '</span>';
                                endif;
                                ?>
                            </td>
                            <td class="text-center"><?= esc(ucfirst($trx['metode_pembayaran'])); ?></td>
                            <td class="text-center">
                                <a href="<?= base_url('karyawan/detail_penjualan/' . $trx['id_penjualan']); ?>" 
                                   class="btn btn-info btn-sm" title="Lihat Detail">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
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
        $('#dataTableLaporan').DataTable({
            "order": [[ 0, "desc" ]], 
            "language": {
                "search": "Cari Laporan:",
                "lengthMenu": "Tampilkan _MENU_ data",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ transaksi",
                "paginate": {
                    "next": "Lanjut",
                    "previous": "Mundur"
                }
            }
        });
    });
</script>
<?= $this->endSection(); ?>