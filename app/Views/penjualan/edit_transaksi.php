<?= $this->extend('layout/template'); ?>

<?= $this->section('head'); ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

<style>
    /* ... (CSS Anda sudah benar, saya salin dari file Anda) ... */
    #daftar-produk {
        max-height: 70vh;
        overflow-y: auto;
        padding: 1rem;
    }

    .produk-item.penjualan-item img {
        width: 100%;
        height: 140px;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .produk-item.penjualan-item:hover img {
        transform: scale(1.05);
    }

    .produk-info {
        padding: 0.75rem;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .produk-stok {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 0.75rem;
        padding: 2px 8px;
        border-radius: 12px;
        background: var(--gray-light);
        width: fit-content;
    }

    .produk-harga {
        margin-top: auto;
        padding-top: 0.5rem;
        border-top: 1px solid var(--gray-border);
    }

    .empty-cart-icon {
        font-size: 3rem;
        color: #d1d3e2;
        margin-bottom: 1rem;
    }

    .section-header {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-qty {
        width: 2.2rem;
        font-weight: 600;
    }

    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type=number] {
        -moz-appearance: textfield;
        appearance: textfield;
    }

    .select2-container--bootstrap-5 .select2-selection {
        border-radius: 0.35rem;
        padding: 0.375rem 0.75rem;
        height: calc(1.5em + 0.75rem + 2px);
    }

    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
        line-height: 1.5;
    }

    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__arrow {
        height: calc(1.5em + 0.75rem);
    }

    .select2-container--bootstrap-5.select2-container--focus .select2-selection,
    .select2-container--bootstrap-5.select2-container--open .select2-selection {
        border-color: var(--dark-green);
        box-shadow: 0 0 0 0.2rem rgba(45, 134, 89, 0.25);
    }

    .select2-results__option--highlighted {
        background-color: var(--dark-green) !important;
        color: white;
    }
</style>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 penjualan-page-title">
        <i class="fas fa-edit mr-2" style="color: #2d8659;"></i>
        <?= $title; ?> (ID: #<?= $penjualan['id_penjualan']; ?>)
    </h1>
</div>

<div class="row">
    <div class="col-lg-7">
        <div class="card penjualan-card shadow mb-4">
            <div class="card-header penjualan-card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold section-header" style="color: #2d8659;">
                    <i class="fas fa-box section-icon penjualan-icon"></i>
                    Daftar Produk
                </h6>
            </div>
            <div class="card-body" id="daftar-produk">
                <div class="row">
                    <?php foreach ($produk as $p) : ?>
                        <div class="col-md-4 mb-4">
                            <div class="produk-item penjualan-item"
                                data-id="<?= $p['id_produk']; ?>"
                                data-nama="<?= $p['nama_produk']; ?>"
                                data-harga="<?= $p['harga']; ?>"
                                data-stok="<?= $p['stok']; ?>">

                                <img src="<?= base_url('uploads/produk/' . $p['gambar_produk']); ?>" class="card-img-top" alt="<?= $p['nama_produk']; ?>">
                                <div class="produk-info">
                                    <h6 class="font-weight-bold mb-2 text-gray-800"><?= $p['nama_produk']; ?></h6>
                                    <div class="produk-stok mb-2">
                                        <i class="fas fa-cubes" style="color: #2d8659;"></i>
                                        <span class="text-muted">Stok: <strong><?= $p['stok']; ?></strong></span>
                                    </div>
                                    <div class="produk-harga">
                                        <p class="font-weight-bold mb-0" style="color: #2d8659;">
                                            <i class="fas fa-tag mr-1"></i>
                                            Rp <?= number_format($p['harga'], 0, ',', '.'); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card penjualan-card shadow mb-4" style="border-left: 4px solid #2d8659;">
            <div class="card-header penjualan-card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold section-header" style="color: #2d8659;">
                    <i class="fas fa-shopping-cart section-icon penjualan-icon"></i>
                    Edit Transaksi
                </h6>
            </div>
            <div class="card-body">
                <form action="<?= base_url('karyawan/update_penjualan/' . $penjualan['id_penjualan']); ?>" method="POST" id="form-transaksi">
                    <?= csrf_field(); ?>

                    <div class="form-group">
                        <label for="tanggal" class="font-weight-bold text-gray-700">Tanggal*</label>
                        <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?= esc($penjualan['tanggal']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="cari-pelanggan" class="font-weight-bold text-gray-700">Pelanggan*</label>
                        <div class="input-group">
                            <select id="cari-pelanggan" name="id_pelanggan" class="form-control" style="width: 80%;" required>
                                <?php if ($pelanggan_terpilih): ?>
                                    <option value="<?= esc($pelanggan_terpilih['id_pelanggan']); ?>" selected>
                                        <?= esc($pelanggan_terpilih['nama_pelanggan']); ?> (<?= esc($pelanggan_terpilih['no_hp']); ?>)
                                    </option>
                                <?php else: ?>
                                    <option value="" selected disabled>-- Wajib Pilih Pelanggan --</option>
                                <?php endif; ?>
                            </select>
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button" id="btn-pelanggan-baru" data-toggle="modal" data-target="#modalTambahPelanggan">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="cari-barang" class="font-weight-bold text-gray-700">Cari Barang (Opsional)</label>
                        <select id="cari-barang" class="form-control" style="width: 100%;"></select>
                    </div>

                    <hr>

                    <h6 class="font-weight-bold text-gray-800">Keranjang Belanja*</h6>
                    <div id="cart-items-list" style="min-height: 200px; max-height: 300px; overflow-y: auto; margin-bottom: 1.5rem; border: 1px solid #e3e6f0; border-radius: 0.35rem; padding: 0.5rem;">
                        <div class="text-center py-5" id="cart-empty-state">
                        </div>
                    </div>

                    <div class="total-belanja-card penjualan-total">
                        <div class="total-label">Total Belanja</div>
                        <div class="total-value" id="total-belanja-display">Rp 0</div>
                    </div>

                    <hr class="my-4">

                    <div class="form-row mb-3">
                        <div class="form-group col-md-6">
                            <label for="status_bayar" class="font-weight-bold text-gray-700">Status Pembayaran*</label>
                            <select id="status_bayar" class="form-control" disabled style="background-color: #eaecf4; opacity: 1;">
                                <option value="lunas">Lunas</option>
                                <option value="belum_lunas">Belum Lunas (DP)</option>
                            </select>
                            <input type="hidden" id="status_bayar_hidden" name="status_bayar" value="<?= ($penjualan['status_pembayaran'] == 'Lunas') ? 'lunas' : 'belum_lunas'; ?>">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="metode_pembayaran" class="font-weight-bold text-gray-700">Metode Pembayaran*</label>
                            <select id="metode_pembayaran" name="metode_pembayaran" class="form-control" required>
                                <option value="cash" <?= ($penjualan['metode_pembayaran'] == 'cash') ? 'selected' : ''; ?>>Cash</option>
                                <option value="transfer" <?= ($penjualan['metode_pembayaran'] == 'transfer') ? 'selected' : ''; ?>>Transfer</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group" id="input-dp" style="display: block;"> <label for="jumlah_dp" class="font-weight-bold text-gray-700">Jumlah Bayar/DP (Rp)*</label>
                        <input type="number" class="form-control" id="jumlah_dp" name="jumlah_dp" placeholder="Masukkan jumlah bayar" value="<?= $penjualan['jumlah_dp']; ?>" required>
                    </div>

                    <input type="hidden" name="total_belanja" id="total_belanja_hidden">
                    <input type="hidden" name="cart_items" id="cart_items_hidden">

                    <div class="row mt-4">
                        <div class="col-6">
                            <a href="<?= base_url('karyawan/riwayat_penjualan') ?>" class="btn btn-secondary penjualan-btn btn-lg btn-block">
                                <i class="fas fa-times mr-2"></i> Batal
                            </a>
                        </div>
                        <div class="col-6">
                            <button type="submit" class="btn btn-success penjualan-btn btn-lg btn-block" id="btn-bayar">
                                <i class="fas fa-save mr-2"></i> Update
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambahPelanggan" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Tambah Pelanggan Baru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-add-pelanggan">
                    <div id="modal-errors" class="alert alert-danger" style="display: none;"></div>
                    <div class="form-group">
                        <label for="nama_pelanggan">Nama Pelanggan*</label>
                        <input type="text" class="form-control" id="nama_pelanggan" name="nama_pelanggan" required>
                    </div>
                    <div class="form-group">
                        <label for="no_hp">No. HP*</label>
                        <input type="text" class="form-control" id="no_hp" name="no_hp" required>
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <textarea class="form-control" id="alamat" name="alamat" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btn-simpan-pelanggan">Simpan</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>


<?= $this->section('script'); ?>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    // [PERUBAHAN] Inisialisasi keranjang dengan data dari controller
    let cart = <?= $detail_penjualan_json; ?>;

    // Elemen DOM
    const daftarProduk = document.getElementById('daftar-produk');
    const cartList = document.getElementById('cart-items-list');
    const totalDisplay = document.getElementById('total-belanja-display');
    const totalHidden = document.getElementById('total_belanja_hidden');
    const cartHidden = document.getElementById('cart_items_hidden');
    const btnBayar = document.getElementById('btn-bayar');
    const statusBayarSelect = document.getElementById('status_bayar');
    const statusBayarHidden = document.getElementById('status_bayar_hidden');
    const inputDPDiv = document.getElementById('input-dp');
    const inputDP = document.getElementById('jumlah_dp');

    const inputTanggal = document.getElementById('tanggal');
    const selectPelanggan = $('#cari-pelanggan');
    const selectBarang = $('#cari-barang');
    const modalPelanggan = $('#modalTambahPelanggan');
    const btnSimpanPelanggan = $('#btn-simpan-pelanggan');
    const formAddPelanggan = $('#form-add-pelanggan');
    const modalErrors = $('#modal-errors');

    // =======================================================
    // [VALIDASI OTOMATIS] Fungsi untuk Cek Form & Status Bayar
    // =======================================================
    function validateForm() {
        let isValid = true;
        let currentTotal = parseFloat(totalHidden.value) || 0;
        let currentDP = parseFloat(inputDP.value) || 0;

        // 1. Cek Keranjang
        if (cart.length === 0) {
            isValid = false;
        }

        // 2. Cek Tanggal
        if (inputTanggal.value === '') {
            isValid = false;
        }

        // 3. Cek Pelanggan
        if (selectPelanggan.val() === null || selectPelanggan.val() === '') {
            isValid = false;
        }

        // 4. [LOGIKA OTOMATIS] Tentukan status berdasarkan DP vs Total
        if (currentDP >= currentTotal && currentTotal > 0) {
            statusBayarSelect.value = 'lunas';
            statusBayarHidden.value = 'lunas';
            inputDP.removeAttribute('required');
        } else {
            statusBayarSelect.value = 'belum_lunas';
            statusBayarHidden.value = 'belum_lunas';
            inputDP.setAttribute('required', 'required');

            // 5. Validasi Ulang: jika status jadi 'belum_lunas', DP harus diisi
            if (inputDP.value === '' || isNaN(currentDP) || currentDP <= 0) {
                isValid = false;
            }
        }

        // 6. Aktifkan atau Non-aktifkan tombol
        btnBayar.disabled = !isValid;
    }

    // =======================================================
    // Inisialisasi Halaman Edit
    // =======================================================
    $(document).ready(function() {

        selectPelanggan.select2({
            theme: "bootstrap-5",
            placeholder: 'Cari nama atau No. HP pelanggan...',
            allowClear: false,
            ajax: {
                url: "<?= base_url('karyawan/search_pelanggan'); ?>",
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    return {
                        results: data.results
                    };
                },
                cache: true
            }
        });

        selectBarang.select2({
            theme: "bootstrap-5",
            placeholder: 'Ketik nama barang...',
            allowClear: true,
            ajax: {
                url: "<?= base_url('karyawan/search_produk'); ?>",
                dataType: 'json',
                delay: 250,
                processResults: function(data) {
                    return {
                        results: data.results
                    };
                },
                cache: true
            }
        });

        selectBarang.on('select2:select', function(e) {
            var data = e.params.data.data;
            const product = {
                id: data.id_produk,
                nama: data.nama_produk,
                harga: parseFloat(data.harga),
                stok: parseInt(data.stok),
                qty: 1
            };
            tambahKeKeranjang(product);
            $(this).val(null).trigger('change');
        });

        btnSimpanPelanggan.on('click', function() {
            $.ajax({
                url: "<?= base_url('karyawan/add_pelanggan'); ?>",
                method: "POST",
                data: formAddPelanggan.serialize(),
                dataType: "json",
                success: function(response) {
                    if (response.status === 'success') {
                        modalPelanggan.modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false
                        });
                        var newOption = new Option(response.data.text, response.data.id, true, true);
                        selectPelanggan.append(newOption).trigger('change');
                        formAddPelanggan[0].reset();
                        modalErrors.hide();
                        validateForm();
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 400) {
                        var errors = xhr.responseJSON.errors;
                        var errorHtml = '<ul>';
                        $.each(errors, function(key, value) {
                            errorHtml += '<li>' + value + '</li>';
                        });
                        errorHtml += '</ul>';
                        modalErrors.html(errorHtml).show();
                    } else {
                        Swal.fire('Error!', 'Terjadi kesalahan, silakan coba lagi.', 'error');
                    }
                }
            });
        });

        modalPelanggan.on('hidden.bs.modal', function() {
            modalErrors.hide().html('');
            formAddPelanggan[0].reset();
        });

        inputTanggal.addEventListener('change', validateForm);
        selectPelanggan.on('change', validateForm);
        inputDP.addEventListener('input', validateForm);

        updateKeranjang();
        validateForm();

    }); // Akhir document.ready


    // =======================================================
    // Fungsi-fungsi Keranjang (Sama seperti input_transaksi)
    // =======================================================

    daftarProduk.addEventListener('click', function(e) {
        let item = e.target.closest('.produk-item');
        if (item) {
            const product = {
                id: item.dataset.id,
                nama: item.dataset.nama,
                harga: parseFloat(item.dataset.harga),
                stok: parseInt(item.dataset.stok),
                qty: 1
            };
            tambahKeKeranjang(product);
        }
    });

    function tambahKeKeranjang(product) {
        const itemAda = cart.find(item => item.id === product.id);
        if (itemAda) {
            const produkEl = daftarProduk.querySelector(`.produk-item[data-id="${product.id}"]`);
            const stokAsli = produkEl ? parseInt(produkEl.dataset.stok) : product.stok;

            if (itemAda.qty < stokAsli) {
                itemAda.qty++;
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Stok Tidak Cukup',
                    text: 'Stok produk ' + product.nama + ' tidak mencukupi!'
                });
            }
        } else {
            if (product.stok > 0) {
                cart.push(product);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Stok Habis',
                    text: 'Stok produk ' + product.nama + ' telah habis!'
                });
            }
        }
        updateKeranjang();
    }

    function updateKeranjang() {
        cartList.innerHTML = '';
        const emptyState = document.getElementById('cart-empty-state');

        if (cart.length === 0) {
            cartList.innerHTML = `
                <div class="text-center py-5" id="cart-empty-state">
                    <i class="fas fa-shopping-basket empty-cart-icon"></i>
                    <p class="text-muted mb-0" id="cart-empty-msg">Keranjang masih kosong</p>
                    <small class="text-muted">Klik produk di kiri atau cari barang</small>
                </div>
            `;
        } else {
            cart.forEach(item => {
                const itemDiv = document.createElement('div');
                itemDiv.classList.add('cart-item-card', 'penjualan-cart');
                const subtotal = item.harga * item.qty;

                const produkEl = daftarProduk.querySelector(`.produk-item[data-id="${item.id}"]`);
                const stokAsli = produkEl ? parseInt(produkEl.dataset.stok) : item.stok;

                itemDiv.innerHTML = `
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="flex-grow-1">
                            <h6 class="mb-1 font-weight-bold text-gray-800">
                                <i class="fas fa-box mr-1" style="color: #2d8659;"></i>
                                ${item.nama}
                            </h6>
                            <small class="text-muted d-block mb-1">
                                <i class="fas fa-tag mr-1" style="color: #4a90e2;"></i>
                                Rp ${number_format(item.harga)}
                            </small>
                            <small class="font-weight-bold" style="color: #2d8659;">
                                <i class="fas fa-coins mr-1"></i>
                                Subtotal: Rp ${number_format(subtotal)}
                            </small>
                        </div>
                        <button type="button" class="btn btn-link text-danger" style="padding: 0;" onclick="hapusDariKeranjang('${item.id}')">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                    <div class="d-flex align-items-center justify-content-start">
                        <label class="mb-0 mr-2 text-muted small">Qty:</label>
                        <div class="input-group" style="width: 120px;">
                            <div class="input-group-prepend">
                                <button class="btn btn-outline-danger btn-qty" type="button" onclick="ubahQty('${item.id}', 'kurang')">-</button>
                            </div>
                            <input type="number" class="form-control form-control-sm text-center" value="${item.qty}" min="1" max="${stokAsli}" 
                                   data-id="${item.id}" onchange="updateQty(this)" style="background-color: white;" readonly>
                            <div class="input-group-append">
                                <button class="btn btn-outline-success btn-qty" type="button" onclick="ubahQty('${item.id}', 'tambah')">+</button>
                            </div>
                        </div>
                    </div>
                `;
                cartList.appendChild(itemDiv);
            });
        }
        hitungTotal();
        validateForm();
    }

    function ubahQty(id, aksi) {
        const item = cart.find(item => item.id === id);
        if (!item) return;

        const produkEl = daftarProduk.querySelector(`.produk-item[data-id="${id}"]`);
        const stokAsli = produkEl ? parseInt(produkEl.dataset.stok) : item.stok;

        if (aksi === 'tambah') {
            if (item.qty < stokAsli) {
                item.qty++;
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Stok Tidak Cukup',
                    text: 'Stok tersisa hanya: ' + stokAsli,
                    timer: 1500,
                    showConfirmButton: false
                });
            }
        } else if (aksi === 'kurang') {
            if (item.qty > 1) {
                item.qty--;
            } else {
                hapusDariKeranjang(id);
                return;
            }
        }

        updateKeranjang();
    }

    function updateQty(input) {
        const id = input.dataset.id;
        let newQty = parseInt(input.value);
        const item = cart.find(item => item.id === id);

        const produkEl = daftarProduk.querySelector(`.produk-item[data-id="${id}"]`);
        const stokAsli = produkEl ? parseInt(produkEl.dataset.stok) : item.stok;

        if (isNaN(newQty) || newQty < 1) {
            newQty = 1;
        }

        if (newQty > stokAsli) {
            Swal.fire({
                icon: 'error',
                title: 'Stok Tidak Cukup',
                text: 'Stok tersisa hanya: ' + stokAsli,
                timer: 1500,
                showConfirmButton: false
            });
            newQty = stokAsli;
        }

        item.qty = newQty;
        input.value = newQty;
        updateKeranjang();
    }

    function hapusDariKeranjang(id) {
        if (typeof Swal === 'function') {
            Swal.fire({
                title: 'Hapus Produk?',
                text: "Anda yakin ingin menghapus produk ini dari keranjang?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    cart = cart.filter(item => item.id !== id);
                    updateKeranjang();
                }
            });
        } else {
            if (confirm('Anda yakin ingin menghapus produk ini?')) {
                cart = cart.filter(item => item.id !== id);
                updateKeranjang();
            }
        }
    }

    function hitungTotal() {
        let total = 0;
        cart.forEach(item => {
            item.subtotal = item.harga * item.qty;
            total += item.subtotal;
        });
        totalDisplay.innerHTML = 'Rp ' + number_format(total);
        totalHidden.value = total;
        cartHidden.value = JSON.stringify(cart);
    }

    function number_format(number) {
        return new Intl.NumberFormat('id-ID').format(number);
    }
</script>
<?= $this->endSection(); ?>