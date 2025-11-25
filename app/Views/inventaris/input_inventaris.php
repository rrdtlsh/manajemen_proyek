<?= $this->extend('layout/template'); ?>

<?= $this->section('head'); ?>
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<link href="<?= base_url('css/input_inventaris.css') ?>" rel="stylesheet">
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
        <i class="fas fa-cubes mr-2" style="color: #2d8659;"></i>
        <?= esc($title); ?>
    </h1>
    <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm"
        data-toggle="modal" data-target="#modalProduk" id="btnTambahProduk">
        <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Produk Baru
    </button>
</div>

<?php $validation = \Config\Services::validation(); ?>
<?php if (session()->getFlashdata('errors') || session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger" role="alert">
        <ul class="mb-0">
            <?php 
            if(session()->getFlashdata('errors')){
                foreach (session()->getFlashdata('errors') as $error) : ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach;
            } else {
                echo '<li>'.session()->getFlashdata('error').'</li>';
            }
            ?>
        </ul>
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
                                <div class="produk-img-container">
                                    <img src="<?= base_url('uploads/produk/' . $p['gambar_produk']); ?>" alt="<?= esc($p['nama_produk']); ?>">
                                </div>
                            </td>
                            <td><?= esc($p['kode_produk']); ?></td>
                            <td><?= esc($p['nama_produk']); ?></td>
                            <td>Rp <?= number_format($p['harga'], 0, ',', '.'); ?></td>
                            <td>
                                <?php if($p['stok'] == 0): ?>
                                    <span class="badge badge-danger">Habis</span>
                                <?php else: ?>
                                    <?= esc($p['stok']); ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-aksi-group">
                                    <a href="<?= base_url('karyawan/inventaris/detail/' . $p['id_produk']); ?>"
                                        class="btn btn-info btn-sm">
                                        <i class="fas fa-info-circle"></i> Detail
                                    </a>

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
                                        <i class="fas fa-edit"></i> Edit
                                    </button>

                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="confirmDelete(<?= $p['id_produk']; ?>, '<?= base_url('karyawan/inventaris/delete/' . $p['id_produk']); ?>')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
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

            <form id="formProduk" action="" method="POST" enctype="multipart/form-data">
                <?= csrf_field(); ?>
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <div class="modal-header">
                    <h5 class="modal-title" id="modalProdukLabel">Tambah Produk Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="kode_produk">Kode Produk</label>
                            <input type="text"
                                class="form-control"
                                id="kode_produk"
                                name="kode_produk"
                                placeholder="Contoh: BR001"
                                autocomplete="off"
                                required
                                maxlength="10">
                            <div class="invalid-feedback" id="error-kode-msg"></div>
                            <small class="form-text text-muted">Maksimal 10 karakter (Huruf & Angka, Kapital).</small>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="tanggal_masuk">Tanggal Masuk</label>
                            <input type="date" class="form-control" id="tanggal_masuk" name="tanggal_masuk" required value="<?= date('Y-m-d') ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="nama_produk">Nama Produk</label>
                            <input type="text"
                                class="form-control"
                                id="nama_produk"
                                name="nama_produk"
                                required
                                maxlength="20"
                                placeholder="Nama Produk">
                            <small class="form-text text-muted">Maksimal 20 karakter.</small>
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
                                <input type="text"
                                    class="form-control"
                                    id="harga"
                                    name="harga"
                                    required
                                    placeholder="0"
                                    maxlength="15">
                                <div id="error-harga-msg" class="invalid-feedback"></div>
                            </div>
                            <small class="form-text text-muted">Hanya angka. Maksimal Rp 1 Miliar.</small>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="stok">Kuantitas (Stok)</label>
                        <input type="text"
                            class="form-control"
                            id="stok"
                            name="stok"
                            required
                            placeholder="Minimal 1">
                        <div id="error-stok-msg" class="invalid-feedback"></div>
                        <small class="form-text text-muted">Hanya angka. Wajib diisi > 0. Maksimal 1.000 unit.</small>
                    </div>

                    <div class="form-group">
                        <label>Gambar Produk</label>
                        <div id="gambar-preview-container" class="mb-2" style="display: none;">
                            <img id="gambar-preview" src="" alt="Preview Gambar"
                                style="width: 150px; height: 150px; object-fit: cover; border-radius: 5px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                        </div>
                        <div class="custom-file">
                            <input type="file"
                                class="custom-file-input"
                                id="gambar_produk"
                                name="gambar_produk"
                                accept=".png, .jpg, .jpeg"
                                onchange="validasiFile(this)">
                            <label class="custom-file-label" id="gambar-label" for="gambar_produk">Pilih gambar...</label>
                        </div>
                        <small class="form-text text-muted">Maksimal ukuran file 2MB. Format: JPG, JPEG, PNG.</small>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success" id="btnSimpan">Simpan</button>
                    </div>
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
    var BASE_URL = "<?= base_url('karyawan/inventaris'); ?>";
    var CHECK_CODE_URL = "<?= base_url('karyawan/inventaris/cek-kode'); ?>";
    
    // Variable Token CSRF agar bisa dibaca file eksternal
    var csrfName = '<?= csrf_token() ?>';
    var csrfHash = '<?= csrf_hash() ?>';
</script>

<script src="<?= base_url('js/input_inventaris.js') ?>"></script>

<?php if (session()->getFlashdata('success')) : ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "<?= session()->getFlashdata('success'); ?>",
            timer: 2500,
            showConfirmButton: false
        });
    </script>
<?php endif; ?>

<?php if (session()->getFlashdata('error')) : ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: "<?= session()->getFlashdata('error'); ?>",
        });
    </script>
<?php endif; ?>

<?= $this->endSection(); ?>