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

<?php $validation = \Config\Services::validation(); ?>
<?php if ($validation->getErrors()) : ?>
    <div class="alert alert-danger" role="alert">
        <ul class="mb-0">
            <?php foreach ($validation->getErrors() as $error) : ?>
                <li><?= esc($error) ?></li>
            <?php endforeach ?>
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
                        <th>Kode Produk</th>
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
                            <td><?= esc($p['stok']); ?></td>
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
                                maxlength="10"
                                oninput="this.value = this.value.replace(/[^a-zA-Z0-9]/g, '').toUpperCase()">

                            <div class="invalid-feedback" id="error-kode-msg"></div>
                            <small class="form-text text-muted">Maks. 10 karakter (Huruf & Angka).</small>
                        </div>

                        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

                        <script>
                            $(document).ready(function() {
                                // Simpan nama token dan hash awal di variabel
                                var csrfName = '<?= csrf_token() ?>';
                                var csrfHash = '<?= csrf_hash() ?>';

                                $('#kode_produk').on('blur', function() {
                                    var field = $(this);
                                    var kodeInput = field.val();
                                    var msgBox = $('#error-kode-msg');

                                    // Reset style dulu
                                    field.removeClass('is-invalid is-valid');
                                    msgBox.html('');
                                    $('button[type="submit"]').prop('disabled', false);

                                    if (kodeInput === '') return;

                                    $.ajax({
                                        url: "<?= base_url('karyawan/inventaris/cek-kode') ?>",
                                        type: "POST",
                                        dataType: "json",
                                        data: {
                                            kode_produk: kodeInput,
                                            [csrfName]: csrfHash // Gunakan variabel token dinamis
                                        },
                                        success: function(response) {
                                            // 1. UPDATE TOKEN (PENTING!)
                                            csrfHash = response.token;
                                            $('input[name="' + csrfName + '"]').val(csrfHash);

                                            // 2. PROSES LOGIKA TAMPILAN
                                            if (response.status === 'taken') {
                                                field.addClass('is-invalid');
                                                msgBox.html('<strong>Gagal!</strong> Kode sudah digunakan produk lain.');
                                                $('button[type="submit"]').prop('disabled', true);
                                            } else {
                                                field.addClass('is-valid');
                                                msgBox.html('<span class="text-success">Kode tersedia.</span>');

                                                // Cek apakah ada error lain (seperti harga) sebelum enable tombol
                                                if ($('.is-invalid').length === 0) {
                                                    $('button[type="submit"]').prop('disabled', false);
                                                }
                                            }
                                        },
                                        error: function(xhr, ajaxOptions, thrownError) {
                                            console.error("Error:", thrownError);
                                            alert("Terjadi kesalahan koneksi atau Token Expired. Silakan refresh halaman.");
                                        }
                                    });
                                });

                                // Hapus error saat user mulai mengetik ulang
                                $('#kode_produk').on('input', function() {
                                    $(this).removeClass('is-invalid is-valid');
                                    $('#error-kode-msg').html('');

                                    // Cek error lain sebelum enable
                                    if ($('.is-invalid').length === 0) {
                                        $('button[type="submit"]').prop('disabled', false);
                                    }
                                });
                            });
                        </script>

                        <div class="form-group col-md-6">
                            <label for="tanggal_masuk">Tanggal Masuk</label>
                            <input type="date" class="form-control" id="tanggal_masuk" name="tanggal_masuk" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="nama_produk">Nama Produk</label>
                            <input type="text"
                                class="form-control"
                                id="nama_produk"
                                name="nama_produk"
                                placeholder="Karpet Motif Daun"
                                oninput="this.value = this.value.replace(/[^a-zA-Z\s]/g, '')"
                                title="Nama produk tidak boleh mengandung angka"
                                required
                                maxlength="20"> <small class="form-text text-muted">Maksimal 20 huruf.</small>
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
                            <input type="number"
                                class="form-control"
                                id="harga"
                                name="harga"
                                required
                                placeholder="0"
                                oninput="

                                    this.value = !!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null;

                                    if(this.value > 1000000000000) {
                                        this.value = this.value.slice(0, -1);
                                    }

                                    if(this.value.length < 4 && this.value.length > 0) {
                                        this.setCustomValidity('Harga tidak valid. Minimal 4 angka (Ribuan).');
                                    } else {
                                        this.setCustomValidity('');
                                    }
                                "
                                onkeydown="return event.keyCode !== 69 && event.keyCode !== 189 && event.keyCode !== 190 && event.keyCode !== 188"
                            >
                            <div id="error-harga-msg" class="invalid-feedback"></div>
                        </div>
                        <small class="form-text text-muted">Maksimal Input Rp 1.000.000.000.000</small>
                    </div>
                    </div>

                    <script>
                        $(document).ready(function() {
                            $('#harga').on('input', function() {
                                var field = $(this);
                                var rawValue = field.val().replace(/[^0-9]/g, '');
                                var maxLimit = 1000000000000;
                                var msgBox = $('#error-harga-msg');
                                var btnSimpan = $('#btnSimpan');

                                field.val(rawValue);

                                if (rawValue !== '' && parseFloat(rawValue) > maxLimit) {

                                    field.addClass('is-invalid');
                                    msgBox.html('<strong>Gagal!</strong> Harga tidak boleh melebihi Rp 1 Triliun.');
                                    btnSimpan.prop('disabled', true);
                                } else {
                                    field.removeClass('is-invalid');
                                    msgBox.html('');

                                    if ($('.is-invalid').length === 0) {
                                        btnSimpan.prop('disabled', false);
                                    }
                                }
                            });
                        });
                    </script>

                    <div class="form-group">
                        <label for="stok">Kuantitas (Stok)</label>
                        <input type="number"
                            class="form-control"
                            id="stok"
                            name="stok"
                            required
                            min="1"
                            max="1000"
                            oninput="
                                this.value = !!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null;

                                if (this.value > 1000) {
                                    this.value = this.value.slice(0, -1);
                                }
                            "
                            onkeydown="return event.keyCode !== 69 && event.keyCode !== 189 && event.keyCode !== 190 && event.keyCode !== 188"
                        >

                        <div id="error-stok-msg" class="invalid-feedback"></div>
                        <small class="form-text text-muted">Maksimal 1000 item.</small>
                    </div>

                    <script>
                        $(document).ready(function() {
                            $('#stok').on('input', function() {
                                var field = $(this);
                                var rawValue = field.val().replace(/[^0-9]/g, '');
                                var maxLimit = 1000000000;
                                var msgBox = $('#error-stok-msg');
                                var btnSimpan = $('#btnSimpan');

                                if (field.val() !== rawValue) {
                                    field.val(rawValue);
                                }

                                if (rawValue !== '' && parseInt(rawValue) > maxLimit) {
                                    rawValue = maxLimit.toString();
                                    field.val(rawValue);
                                } else if (parseInt(rawValue) < 1) {

                                    field.addClass('is-invalid');
                                    msgBox.html('<strong>Gagal!</strong> Stok harus lebih dari 0 (Minimal 1).');
                                    btnSimpan.prop('disabled', true);
                                } else if (parseInt(rawValue) > maxLimit) {

                                    field.addClass('is-invalid');
                                    msgBox.html('<strong>Gagal!</strong> Stok tidak boleh melebihi 1 Milyar.');
                                    btnSimpan.prop('disabled', true);
                                } else {

                                    field.removeClass('is-invalid');
                                    msgBox.html('');

                                    if ($('.is-invalid').length === 0) {
                                        btnSimpan.prop('disabled', false);
                                    }
                                }
                            });
                        });
                    </script>

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

                    <script>
                        function validasiFile(input) {
                            const file = input.files[0];
                            const limit = 2 * 1024 * 1024; // 2MB

                            if (file) {
                                // Daftar tipe file yang diizinkan
                                const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];

                                // --- Cek Ukuran File
                                if (file.size > limit) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'File Terlalu Besar!',
                                        text: 'Maaf, ukuran gambar maksimal hanya 2MB.',
                                        footer: 'Silakan kompres gambar atau pilih gambar lain.',
                                        confirmButtonColor: '#d33',
                                        confirmButtonText: 'Oke, Mengerti'
                                    });

                                    input.value = "";
                                    document.getElementById('gambar-label').innerHTML = "Pilih gambar...";
                                    document.getElementById('gambar-preview-container').style.display = "none";
                                    return false;
                                }

                                // Jika lolos kedua validasi (Format & Ukuran)
                                document.getElementById('gambar-label').innerHTML = file.name;

                                const reader = new FileReader();
                                reader.onload = function(e) {
                                    document.getElementById('gambar-preview').src = e.target.result;
                                    document.getElementById('gambar-preview-container').style.display = "block";
                                }
                                reader.readAsDataURL(file);
                            }
                        }
                    </script>

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
    const BASE_URL = "<?= base_url('karyawan/inventaris'); ?>";
</script>
<script src="<?= base_url('js/input_inventaris.js') ?>"></script>
<?= $this->endSection(); ?>