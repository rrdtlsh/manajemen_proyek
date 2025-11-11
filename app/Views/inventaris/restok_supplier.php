<?= $this->extend('layout/template') ?>

<?= $this->section('head') ?>
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<link href="<?= base_url('css/restok_supplier.css') ?>" rel="stylesheet">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
        <i class="fas fa-dolly mr-2 page-title-icon"></i>
        Restok Barang Supplier
    </h1>
    <button class="btn btn-success shadow-sm" data-toggle="modal" data-target="#modalInputRestok">
        <i class="fas fa-plus fa-sm text-white-50 mr-1"></i> Input Barang Supplier
    </button>
</div>

<?php if (session()->getFlashdata('success')) : ?>
    <div class="alert alert-success" role="alert">
        <?= session()->getFlashdata('success'); ?>
    </div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger" role="alert">
        <?= session()->getFlashdata('error'); ?>
    </div>
<?php endif; ?>


<div class="card shadow mb-4" style="border-left: 4px solid #2d8659;">
    <div class="card-header py-3" style="background-color: #2d8659; color: white;">
        <h6 class="m-0 font-weight-bold text-white">Data Pengajuan Restok</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTableRestok" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama PT Supplier</th>
                        <th>Barang</th>
                        <th>Qty</th>
                        <th>Harga</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($data_restok)) : ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted">Belum ada data pengajuan restok.</td>
                        </tr>
                    <?php else : ?>
                        <?php foreach ($data_restok as $restok) : ?>
                            <tr>
                                <td><?= $restok['id_restok']; // Ganti sesuai nama kolom ID Anda ?></td>
                                <td><?= esc($restok['nama_supplier']); ?></td>
                                <td><?= esc($restok['nama_barang']); ?></td>
                                <td><?= $restok['qty']; ?></td>
                                <td>Rp <?= number_format($restok['harga_satuan'], 0, ',', '.'); ?></td>
                                <td>Rp <?= number_format($restok['total_harga'], 0, ',', '.'); ?></td>
                                <td>
                                    <?php if ($restok['status'] == 'Disetujui') : ?>
                                        <span class="badge badge-success">Disetujui</span>
                                    <?php else : ?>
                                        <span class="badge badge-warning">Menunggu</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button class="btn btn-warning btn-sm" onclick="editRestok(<?= $restok['id_restok']; ?>)">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button class="btn btn-danger btn-sm" onclick="confirmDeleteRestok(<?= $restok['id_restok']; ?>, '<?= base_url('inventaris/delete_restok/' . $restok['id_restok']); ?>')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalInputRestok" tabindex="-1" role="dialog" aria-labelledby="modalInputRestokLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #2d8659; color: white;">
                <h5 class="modal-title text-white" id="modalInputRestokLabel">Input Barang Supplier</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close" style="color: white; opacity: 1;">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="formRestok" action="<?= base_url('inventaris/store_restok'); ?>" method="POST">
                <div class="modal-body">
                    <input type="hidden" id="restok_id_hidden" name="id_restok">

                    <h6 class="font-weight-bold text-gray-800">Supplier</h6>
                    <hr class="mt-0">
                    
                    <div class="form-row mb-3">
                        <div class="col-md-4">
                            <label for="restok_pt_supplier">Nama PT Supplier</label>
                            <input type="text" class="form-control" id="restok_pt_supplier" name="nama_supplier" placeholder="Contoh: PT. Jaya" required>
                        </div>
                        <div class="col-md-4">
                            <label for="restok_nama_barang">Nama Barang</label>
                            <input type="text" class="form-control" id="restok_nama_barang" name="nama_barang" placeholder="Contoh: Karpet Motif" required>
                        </div>
                        <div class="col-md-4">
                            <label for="restok_status">Status</label>
                            <select id="restok_status" name="status" class="form-control" required>
                                <option value="Menunggu">Menunggu</option>
                                <option value="Disetujui">Disetujui</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-4">
                            <label for="restok_jumlah">Jumlah (Qty)</label>
                            <input type="number" class="form-control" id="restok_jumlah" name="qty" min="1" required>
                        </div>
                        <div class="col-md-4">
                            <label for="restok_harga">Harga (Satuan)</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="number" class="form-control" id="restok_harga" name="harga_satuan" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="restok_total">Total</label>
                             <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="number" class="form-control" id="restok_total" name="total_harga" readonly style="background-color: #e9ecef;">
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save mr-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<script src="<?= base_url('js/restok_supplier.js') ?>"></script>
<?= $this->endSection() ?>