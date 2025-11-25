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
                            <td><strong><?= esc($p['nama_produk']); ?></strong></td>
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
                                       class="btn btn-danger btn-sm btn-hapus">
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
                                var csrfName = '<?= csrf_token() ?>';
                                var csrfHash = '<?= csrf_hash() ?>';
                                
                                var btnSimpan = $('button[type="submit"]'); 
                                var inputKode = $('#kode_produk');
                                var msgBox = $('#error-kode-msg');

                                // 1. EVENT SAAT KURSOR KELUAR (CEK KE SERVER)
                                inputKode.on('blur', function() {
                                    var field = $(this);
                                    var kodeInput = field.val().trim();

                                    // Reset dulu
                                    field.removeClass('is-invalid is-valid');
                                    msgBox.html('');

                                    if (kodeInput === '') return;

                                    // KUNCI TOMBOL SAAT LOADING
                                    btnSimpan.prop('disabled', true);
                                    btnSimpan.text('Mengecek...'); 

                                    $.ajax({
                                        url: "<?= base_url('owner/manajemen_produk/cek-kode-otomatis') ?>", 
                                        type: "POST",
                                        dataType: "json",
                                        data: {
                                            kode_produk: kodeInput,
                                            [csrfName]: csrfHash
                                        },
                                        success: function(response) {
                                            csrfHash = response.token;
                                            $('input[name="' + csrfName + '"]').val(csrfHash);
                                            
                                            // Kembalikan teks tombol
                                            btnSimpan.text('Simpan');

                                            if (response.status === 'taken') {
                                                // KODE SUDAH ADA -> KUNCI TOMBOL
                                                field.addClass('is-invalid'); 
                                                msgBox.html(response.message); 
                                                btnSimpan.prop('disabled', true); // <--- INI SENGAJA DIKUNCI
                                            } else {
                                                // KODE AMAN -> BUKA TOMBOL
                                                field.addClass('is-valid'); 
                                                
                                                // Cek apakah kolom lain ada yang error? Kalau bersih, nyalakan tombol
                                                if ($('.is-invalid').length === 0) {
                                                    btnSimpan.prop('disabled', false); // <--- TOMBOL BISA DITEKAN
                                                }
                                            }
                                        },
                                        error: function() {
                                            btnSimpan.text('Simpan');
                                            field.addClass('is-invalid');
                                            btnSimpan.prop('disabled', true); // Error koneksi = Kunci tombol
                                        }
                                    });
                                });

                                // 2. EVENT SAAT MENGETIK ULANG (RESET TOMBOL)
                                // Ini penting agar saat user hapus/edit, tombol langsung "Ready" lagi
                                inputKode.on('input', function() {
                                    var field = $(this);
                                    
                                    // Hapus warna merah/hijau saat ngetik
                                    field.removeClass('is-invalid is-valid');
                                    msgBox.html('');

                                    // Langsung nyalakan tombol saat user mulai memperbaiki input
                                    // (Nanti akan dicek lagi saat user selesai ngetik/blur)
                                    if ($('.is-invalid').length === 0) {
                                        btnSimpan.prop('disabled', false);
                                        btnSimpan.text('Simpan');
                                    }
                                });
                            });
                        </script>

                        <div class="form-group col-md-6">
                            <label for="tanggal_masuk">Tanggal Masuk</label>
                            <input type="date" class="form-control" id="tanggal_masuk" name="tanggal_masuk" value="<?= date('Y-m-d'); ?>" required>
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
                            <img id="gambar-preview" src="" alt="Preview Gambar" style="width: 150px; height: 150px; object-fit: cover; border-radius: 5px;">
                        </div>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="gambar_produk" name="gambar_produk" accept=".png, .jpg, .jpeg" onchange="validasiFile(this)">
                            <label class="custom-file-label" id="gambar-label" for="gambar_produk">Pilih gambar...</label>
                        </div>
                        <small class="form-text text-muted">Maksimal ukuran file 2MB. Format: JPG, JPEG, PNG.</small>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // --- FUNGSI VALIDASI FILE (Diluar document.ready agar bisa dipanggil HTML) ---
    function validasiFile(input) {
        const file = input.files[0];
        const limit = 2 * 1024 * 1024; // 2MB

        if (file) {
            if (file.size > limit) {
                Swal.fire({
                    icon: 'error',
                    title: 'File Terlalu Besar!',
                    text: 'Maaf, ukuran gambar maksimal hanya 2MB.',
                    confirmButtonColor: '#d33'
                });
                input.value = "";
                document.getElementById('gambar-label').innerHTML = "Pilih gambar...";
                document.getElementById('gambar-preview-container').style.display = "none";
                return false;
            }

            // Preview Gambar
            document.getElementById('gambar-label').innerHTML = file.name;
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('gambar-preview').src = e.target.result;
                document.getElementById('gambar-preview-container').style.display = "block";
            }
            reader.readAsDataURL(file);
        }
    }

    // --- DOCUMENT READY ---
    $(document).ready(function() {
        
        // 1. Inisialisasi DataTable
        $('#dataTableInventaris').DataTable();

        // 2. SweetAlert untuk Tombol Hapus (.btn-hapus)
        // Gunakan 'body' on click agar tetap jalan meskipun di halaman 2 datatable
        $('body').on('click', '.btn-hapus', function(e) {
            e.preventDefault();
            const href = $(this).attr('href');

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data produk ini akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.location.href = href;
                }
            });
        });

        // 3. AJAX Cek Kode Produk
        var csrfName = '<?= csrf_token() ?>';
        var csrfHash = '<?= csrf_hash() ?>';

        $('#kode_produk').on('blur', function() {
            var field = $(this);
            var kodeInput = field.val();
            var msgBox = $('#error-kode-msg');

            field.removeClass('is-invalid is-valid');
            msgBox.html('');
            $('button[type="submit"]').prop('disabled', false);

            if (kodeInput === '') return;

            $.ajax({
                url: "<?= base_url('owner/manajemen_produk/cek-kode') ?>", // Pastikan route owner
                type: "POST",
                dataType: "json",
                data: {
                    kode_produk: kodeInput,
                    [csrfName]: csrfHash
                },
                success: function(response) {
                    csrfHash = response.token;
                    $('input[name="' + csrfName + '"]').val(csrfHash);

                    if (response.status === 'taken') {
                        field.addClass('is-invalid');
                        msgBox.html('<strong>Gagal!</strong> Kode sudah digunakan.');
                        $('button[type="submit"]').prop('disabled', true);
                    } else {
                        field.addClass('is-valid');
                        msgBox.html('<span class="text-success">Kode tersedia.</span>');
                    }
                },
                error: function() {
                    console.log("Error AJAX Cek Kode");
                }
            });
        });

        // Reset error saat ngetik ulang
        $('#kode_produk').on('input', function() {
            $(this).removeClass('is-invalid is-valid');
            $('#error-kode-msg').html('');
            $('button[type="submit"]').prop('disabled', false);
        });

        // 4. Logika Modal Tambah/Edit
        $('#btnTambahProduk').click(function() {
            $('#formProduk').attr('action', '<?= base_url('owner/manajemen_produk/store'); ?>');
            $('#modalProdukLabel').text('Tambah Produk Baru');
            $('#formProduk')[0].reset();
            $('#gambar-preview-container').hide();
            $('#gambar-label').text('Pilih gambar...');
            $('#kode_produk').removeClass('is-invalid is-valid'); // Reset validasi visual
        });

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
            
            // Saat edit, hapus class valid/invalid sisa ajax sebelumnya
            $('#kode_produk').removeClass('is-invalid is-valid');

            if (gambar) {
                $('#gambar-preview').attr('src', gambar);
                $('#gambar-preview-container').show();
            } else {
                $('#gambar-preview-container').hide();
            }
        });
    });
</script>

<?php if (session()->getFlashdata('success')) : ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '<?= session()->getFlashdata('success'); ?>',
            timer: 3000,
            showConfirmButton: false
        });
    </script>
<?php endif; ?>

<?php if (session()->getFlashdata('error')) : ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '<?= session()->getFlashdata('error'); ?>',
        });
    </script>
<?php endif; ?>

<?= $this->endSection(); ?>