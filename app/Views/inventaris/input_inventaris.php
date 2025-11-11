<?= $this->extend('layout/template'); ?>

<?= $this->section('head'); ?>
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<link href="<?= base_url('css/input_inventaris.css') ?>" rel="stylesheet"> 
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
        <i class="fas fa-cubes mr-2" style="color: #2d8659;"></i>
        <?= $title; ?>
    </h1>
    <a href="<?= base_url('karyawan/inventaris/tambah'); ?>" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm">
        <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Produk Baru
    </a>
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
        <h6 class="m-0 font-weight-bold text-white">Daftar Produk Inventaris</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTableInventaris" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Gambar</th>
                        <th>Nama Produk</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    <?php foreach ($produk as $p) : ?>
                        <tr>
                            <td><?= $i++; ?></td>
                            <td>
                                <div class="produk-img-container">
                                    <img src="<?= base_url('uploads/produk/' . $p['gambar_produk']); ?>" alt="<?= $p['nama_produk']; ?>">
                                </div>
                            </td>
                            <td><?= $p['nama_produk']; ?></td>
                            <td>Rp <?= number_format($p['harga'], 0, ',', '.'); ?></td>
                            <td><?= $p['stok']; ?></td>
                            <td>
                                <a href="<?= base_url('karyawan/inventaris/edit/' . $p['id_produk']); ?>" class="btn btn-primary btn-aksi">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                
                                <button type="button" class="btn btn-danger btn-aksi" 
                                    onclick="confirmDelete(<?= $p['id_produk']; ?>, '<?= base_url('karyawan/inventaris/delete/' . $p['id_produk']); ?>')">
                                    <i class="fas fa-trash"></i> Hapus
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
<script src="<?= base_url('js/input_inventaris.js') ?>"></script>
<?= $this->endSection(); ?>