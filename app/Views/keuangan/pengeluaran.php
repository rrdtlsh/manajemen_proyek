<?= $this->extend('layout/template'); ?>

<?= $this->section('head'); ?>
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>

<?php if (session()->getFlashdata('success')) : ?>
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert" style="border-left: 4px solid #2d8659;">
        <i class="fas fa-check-circle mr-2"></i>
        <?= session()->getFlashdata('success'); ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </div>
<?php endif; ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
        <i class="fas fa-arrow-up mr-2" style="color: #e74a3b;"></i>
        <?= $title; ?>
    </h1>
    
    <button type="button" class="btn btn-danger shadow-sm" data-toggle="modal" data-target="#modalPengeluaran">
        <i class="fas fa-plus fa-sm text-white-50 mr-1"></i> Input Pengeluaran
    </button>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3" style="background-color: #2d8659; color: white;">
        <h6 class="m-0 font-weight-bold text-white">Rincian Pengeluaran</h6>
    </div>
    <div class="card-body">
        
        <div class="text-right mb-3">
            <a href="<?= base_url('karyawan/keuangan/pengeluaran/export/pdf'); ?>" class="btn btn-outline-danger btn-sm">
                <i class="fas fa-file-pdf mr-1"></i> Export PDF
            </a>
            <a href="<?= base_url('karyawan/keuangan/pengeluaran/export/excel'); ?>" class="btn btn-outline-success btn-sm">
                <i class="fas fa-file-excel mr-1"></i> Export Excel
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th>Tipe</th>
                        <th>Jumlah</th>
                        <th>Aksi</th> </tr>
                </thead>
                <tbody>
                    <?php foreach ($laporan as $item) : ?>
                        <tr>
                            <td>
                                <?= date('d M Y', strtotime($item['tanggal'])); ?>
                            </td>
                            <td><?= esc($item['keterangan']); ?></td>
                            <td><span class="badge badge-danger">Pengeluaran</span></td>
                            <td class="text-danger font-weight-bold">
                                Rp <?= number_format($item['pengeluaran'], 0, ',', '.'); ?>
                            </td>
                            <td class="text-center">
                                <a href="<?= base_url('karyawan/keuangan/delete_pengeluaran/' . $item['id_keuangan']); ?>" 
                                   class="btn btn-danger btn-sm btn-circle"
                                   onclick="return confirm('Yakin ingin menghapus data pengeluaran ini?');">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalPengeluaran" tabindex="-1" role="dialog" aria-labelledby="labelModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="labelModal">Input Pengeluaran Baru</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('karyawan/keuangan/store_pengeluaran'); ?>" method="POST">
                <?= csrf_field(); ?>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Tanggal</label>
                        <input type="date" class="form-control" name="tanggal" value="<?= date('Y-m-d'); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Jumlah Pengeluaran (Rp)</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input type="number" class="form-control" name="jumlah" required min="1" placeholder="0">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Keterangan / Keperluan</label>
                        <textarea class="form-control" name="keterangan" rows="3" required placeholder="Contoh: Biaya Listrik, Beli ATK, Uang Kebersihan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Simpan</button>
                </div>
            </form>
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
            "order": [[ 0, "desc" ]] // Urutkan tanggal terbaru
        });
    });
</script>
<?= $this->endSection(); ?>