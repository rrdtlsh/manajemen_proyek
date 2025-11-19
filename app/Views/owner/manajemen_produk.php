<?= $this->extend('layout/template'); ?>

<?= $this->section('head'); ?>
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<link href="<?= base_url('css/input_inventaris.css') ?>" rel="stylesheet">
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
        <i class="fas fa-boxes mr-2" style="color: #2d8659;"></i>
        <?= esc($title); ?>
    </h1>
    <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm"
        data-toggle="modal" data-target="#modalProduk" id="btnTambahProduk">
        <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Produk Baru
    </button>
</div>

<?php if (session()->getFlashdata('success')) : ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('success'); ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('error'); ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    </div>
<?php endif; ?>

<div class="card shadow mb-4">
    <div class="card-header py-3" style="background-color: #2d8659; color: white;">
        <h6 class="m-0 font-weight-bold text-white">Daftar Stok Produk</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTableInventaris" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Gambar</th>
                        <th>Kode</th>
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
                                <div class="produk-img-container" style="width: 60px; height: 60px; overflow: hidden; border-radius: 5px; border: 1px solid #ddd;">
                                    <img src="<?= base_url('uploads/produk/' . $p['gambar_produk']); ?>" 
                                         alt="<?= esc($p['nama_produk']); ?>"
                                         style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                            </td>
                            <td><?= esc($p['kode_produk']); ?></td>
                            <td>
                                <strong><?= esc($p['nama_produk']); ?></strong>
                            </td>
                            <td>Rp <?= number_format($p['harga'], 0, ',', '.'); ?></td>
                            <td class="<?= ($p['stok'] <= 5) ? 'text-danger font-weight-bold' : ''; ?>">
                                <?= esc($p['stok']); ?>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-warning btn-sm btn-edit"
                                        data-toggle="modal"
                                        data-target="#modalProduk"
                                        data-id="<?= $p['id_produk']; ?>"
                                        data-kode_produk="<?= esc($p['kode_produk']); ?>"
                                        data-nama="<?= esc($p['nama_produk']); ?>"
                                        data-id_kategori="<?= esc($p['id_kategori']); ?>"
                                        data-harga="<?= esc($p['harga']); ?>"
                                        data-stok="<?= esc($p['stok']); ?>"
                                        data-id_supplier="<?= esc($p['id_supplier']); ?>"
                                        data-tanggal_masuk="<?= esc($p['tanggal_masuk']); ?>"
                                        data-gambar="<?= base_url('uploads/produk/' . $p['gambar_produk']); ?>">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <a href="<?= base_url('owner/manajemen_produk/delete/' . $p['id_produk']); ?>"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Yakin ingin menghapus produk ini?');">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalProduk" tabindex="-1" role="dialog" aria-labelledby="modalProdukLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <form id="formProduk" action="<?= base_url('owner/manajemen_produk/store'); ?>" method="POST" enctype="multipart/form-data">
                <?= csrf_field(); ?>
                
                <div class="modal-header" style="background-color: #2d8659; color: white;">
                    <h5 class="modal-title" id="modalProdukLabel">Tambah Produk Baru</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="kode_produk">Kode Produk</label>
                            <input type="text" class="form-control" id="kode_produk" name="kode_produk" placeholder="Contoh: BR-001" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="tanggal_masuk">Tanggal Masuk</label>
                            <input type="date" class="form-control" id="tanggal_masuk" name="tanggal_masuk" value="<?= date('Y-m-d'); ?>" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="nama_produk">Nama Produk</label>
                            <input type="text" class="form-control" id="nama_produk" name="nama_produk" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="id_kategori">Kategori Produk</label>
                            <select id="id_kategori" name="id_kategori" class="form-control" required>
                                <option value="" selected disabled>-- Pilih Kategori --</option>
                                <?php foreach ($kategori as $kat): ?>
                                    <option value="<?= $kat['id_kategori']; ?>"><?= esc($kat['nama_kategori']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="id_supplier">Supplier</label>
                            <select id="id_supplier" name="id_supplier" class="form-control" required>
                                <option value="" selected disabled>-- Pilih Supplier --</option>
                                <?php foreach ($suppliers as $supplier): ?>
                                    <option value="<?= $supplier['id_supplier']; ?>"><?= esc($supplier['nama_supplier']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="harga">Harga</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="number" class="form-control" id="harga" name="harga" required min="0">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="stok">Kuantitas (Stok)</label>
                        <input type="number" class="form-control" id="stok" name="stok" required min="0">
                    </div>

                    <div class="form-group">
                        <label>Gambar Produk</label>
                        <div id="gambar-preview-container" class="mb-2" style="display: none;">
                            <img id="gambar-preview" src="" alt="Preview Gambar"
                                style="width: 100px; height: 100px; object-fit: cover; border-radius: 5px; border:1px solid #ddd;">
                        </div>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="gambar_produk" name="gambar_produk">
                            <label class="custom-file-label" id="gambar-label" for="gambar_produk">Pilih gambar...</label>
                        </div>
                        <small class="form-text text-muted">
                            Format: JPG/PNG. Maks 2MB. Kosongkan jika tidak ingin mengganti gambar saat edit.
                        </small>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
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
        $('#dataTableInventaris').DataTable();

        // Reset form saat tombol tambah diklik
        $('#btnTambahProduk').click(function() {
            $('#formProduk').attr('action', '<?= base_url('owner/manajemen_produk/store'); ?>');
            $('#modalProdukLabel').text('Tambah Produk Baru');
            $('#formProduk')[0].reset();
            $('#gambar-preview-container').hide();
            $('#gambar-label').text('Pilih gambar...');
        });

        // Isi modal saat tombol edit diklik
        $('.btn-edit').click(function() {
            const id = $(this).data('id');
            const kode = $(this).data('kode_produk');
            const nama = $(this).data('nama');
            const kategori = $(this).data('id_kategori');
            const harga = $(this).data('harga');
            const stok = $(this).data('stok');
            const supplier = $(this).data('id_supplier');
            const tanggal = $(this).data('tanggal_masuk');
            const gambar = $(this).data('gambar');

            $('#formProduk').attr('action', '<?= base_url('owner/manajemen_produk/update/'); ?>' + id);
            $('#modalProdukLabel').text('Edit Produk');

            $('#kode_produk').val(kode);
            $('#nama_produk').val(nama);
            $('#id_kategori').val(kategori);
            $('#harga').val(harga);
            $('#stok').val(stok);
            $('#id_supplier').val(supplier);
            $('#tanggal_masuk').val(tanggal);

            if (gambar) {
                $('#gambar-preview').attr('src', gambar);
                $('#gambar-preview-container').show();
            } else {
                $('#gambar-preview-container').hide();
            }
        });

        // Custom input file label
        $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });
    });
</script>
<?= $this->endSection(); ?>