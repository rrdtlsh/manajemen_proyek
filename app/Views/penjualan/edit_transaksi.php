<?= $this->extend('layout/template'); ?>

<?= $this->section('head'); ?>
<style>
    /* ... (CSS Anda sama persis, tidak perlu diubah) ... */
    /* CSS khusus untuk input transaksi */
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

    /* Sembunyikan panah di input number */
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        appearance: none;
        margin: 0;
    }

    input[type=number] {
        -moz-appearance: textfield;
        appearance: textfield;
    }

    /* Override untuk konsistensi tema dark green */
    .penjualan-page-title {
        color: #374151;
        font-weight: 600;
    }

    .card-header.penjualan-card-header h6 {
        color: #2d8659 !important;
    }
</style>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 penjualan-page-title">
        <i class="fas fa-edit mr-2" style="color: #2d8659;"></i>
        <?= $title; ?>
    </h1>
</div>

<?php if (session()->getFlashdata('success')) : ?>
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert" style="border-left: 4px solid #2d8659;">
        <i class="fas fa-check-circle mr-2" style="color: #2d8659;"></i>
        <?= session()->getFlashdata('success'); ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
        <i class="fas fa-exclamation-circle mr-2"></i>
        <?= session()->getFlashdata('error'); ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>


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

                                <img src="<?= base_url('images/gambar karpet/Karpet Bulu/WhatsApp Image 2020-01-15 at 08.17.20.jpeg'); ?>" class="card-img-top" alt="<?= $p['nama_produk']; ?>">
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
                    Keranjang Belanja
                </h6>
            </div>
            <div class="card-body">
                <form action="<?= base_url('karyawan/update_penjualan/' . $penjualan['id_penjualan']); ?>" method="POST" id="form-transaksi">
                    <?= csrf_field(); ?>

                    <div id="cart-items-list" style="min-height: 200px; max-height: 300px; overflow-y: auto; margin-bottom: 1.5rem;">
                        <div class="text-center py-5" id="cart-empty-state" style="display: none;">
                            <i class="fas fa-shopping-basket empty-cart-icon"></i>
                            <p class="text-muted mb-0" id="cart-empty-msg">Keranjang masih kosong</p>
                            <small class="text-muted">Klik produk untuk menambah ke keranjang</small>
                        </div>
                    </div>

                    <div class="total-belanja-card penjualan-total">
                        <div class="total-label">Total Belanja</div>
                        <div class="total-value" id="total-belanja-display">Rp 0</div>
                    </div>

                    <hr class="my-4">

                    <div class="form-row mb-3">
                        <div class="form-group col-md-6">
                            <label for="status_bayar" class="font-weight-bold text-gray-700">
                                <i class="fas fa-money-check-alt mr-1" style="color: #2d8659;"></i>
                                Status Pembayaran
                            </label>
                            <select id="status_bayar" name="status_bayar" class="form-control" required>
                                <option value="lunas" <?= ($penjualan['status_pembayaran'] == 'Lunas') ? 'selected' : ''; ?>>Lunas</option>
                                <option value="belum_lunas" <?= ($penjualan['status_pembayaran'] == 'Belum Lunas') ? 'selected' : ''; ?>>Belum Lunas (DP)</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="metode_pembayaran" class="font-weight-bold text-gray-700">
                                <i class="fas fa-credit-card mr-1" style="color: #2d8659;"></i>
                                Metode Pembayaran
                            </label>
                            <select id="metode_pembayaran" name="metode_pembayaran" class="form-control" required>
                                <option value="cash" <?= ($penjualan['metode_pembayaran'] == 'cash') ? 'selected' : ''; ?>>Cash</option>
                                <option value="transfer" <?= ($penjualan['metode_pembayaran'] == 'transfer') ? 'selected' : ''; ?>>Transfer</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group" id="input-dp" style="<?= ($penjualan['status_pembayaran'] == 'Belum Lunas') ? 'display: block;' : 'display: none;'; ?>">
                        <label for="jumlah_dp" class="font-weight-bold text-gray-700">
                            <i class="fas fa-coins text-warning mr-1"></i>
                            Jumlah DP (Rp)
                        </label>
                        <input type="number" class="form-control" id="jumlah_dp" name="jumlah_dp" placeholder="Masukkan jumlah DP" value="<?= ($penjualan['status_pembayaran'] == 'Belum Lunas') ? $penjualan['jumlah_dp'] : ''; ?>">
                    </div>


                    <input type="hidden" name="total_belanja" id="total_belanja_hidden">
                    <input type="hidden" name="cart_items" id="cart_items_hidden">

                    <button type="submit" class="btn btn-primary penjualan-btn btn-lg btn-block mt-4" id="btn-bayar">
                        <i class="fas fa-save mr-2"></i> Update Transaksi
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>


<?= $this->section('script'); ?>
<script>
    // [MODIFIKASI] Inisialisasi keranjang dengan data dari controller
    let cart = <?= $detail_penjualan_json; ?>;

    // Elemen-elemen DOM
    const daftarProduk = document.getElementById('daftar-produk');
    const cartList = document.getElementById('cart-items-list');
    const totalDisplay = document.getElementById('total-belanja-display');
    const totalHidden = document.getElementById('total_belanja_hidden');
    const cartHidden = document.getElementById('cart_items_hidden');
    const btnBayar = document.getElementById('btn-bayar');
    const statusBayar = document.getElementById('status_bayar');
    const inputDP = document.getElementById('input-dp');

    // [MODIFIKASI] Panggil updateKeranjang() saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
        updateKeranjang();
    });

    // (Semua fungsi JS di bawah ini sama persis dengan input_transaksi.php)

    // Event Listener untuk klik produk
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

    // Event Listener untuk Status Bayar (menampilkan input DP)
    statusBayar.addEventListener('change', function() {
        if (this.value === 'belum_lunas') { // Cek value 'belum_lunas' (lowercase) dari form
            inputDP.style.display = 'block';
            inputDP.querySelector('input').setAttribute('required', 'required');
        } else {
            inputDP.style.display = 'none';
            inputDP.querySelector('input').removeAttribute('required');
            inputDP.querySelector('input').value = '';
        }
    });

    function tambahKeKeranjang(product) {
        const itemAda = cart.find(item => item.id === product.id);
        if (itemAda) {
            if (itemAda.qty < product.stok) {
                itemAda.qty++;
            } else {
                alert('Stok produk ' + product.nama + ' tidak mencukupi!');
            }
        } else {
            if (product.stok > 0) {
                cart.push(product);
            } else {
                alert('Stok produk ' + product.nama + ' habis!');
            }
        }
        updateKeranjang();
    }

    function updateKeranjang() {
        cartList.innerHTML = '';
        const emptyState = document.getElementById('cart-empty-state');

        if (cart.length === 0) {
            if (emptyState) emptyState.style.display = 'block';
            btnBayar.disabled = true;
        } else {
            if (emptyState) emptyState.style.display = 'none';
            btnBayar.disabled = false;
            cart.forEach(item => {
                const itemDiv = document.createElement('div');
                itemDiv.classList.add('cart-item-card', 'penjualan-cart');
                const subtotal = item.harga * item.qty;
                itemDiv.innerHTML = `
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="flex-grow-1">
                            <h6 class="mb-1 font-weight-bold text-gray-800">
                                <i class="fas fa-box mr-1" style="color: #2d8659;"></i>
                                ${item.nama}
                            </h6>
                            <small class="text-muted d-block mb-1">
                                <i class="fas fa-tag mr-1" style="color: #4a90e2;"></i>
                                Rp ${number_format(item.harga)} x ${item.qty}
                            </small>
                            <small class="font-weight-bold" style="color: #2d8659;">
                                <i class="fas fa-coins mr-1"></i>
                                Subtotal: Rp ${number_format(subtotal)}
                            </small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <label class="mb-0 mr-2 text-muted small">Qty:</label>
                            <input type="number" class="form-control form-control-sm" value="${item.qty}" min="1" max="${item.stok}" style="width: 70px; text-align: center;" 
                                   data-id="${item.id}" onchange="updateQty(this)">
                        </div>
                        <button type="button" class="btn btn-danger penjualan-btn btn-sm" onclick="hapusDariKeranjang('${item.id}')">
                            <i class="fas fa-trash-alt mr-1"></i> Hapus
                        </button>
                    </div>
                `;
                cartList.appendChild(itemDiv);
            });
        }
        hitungTotal();
    }

    function updateQty(input) {
        const id = input.dataset.id;
        let newQty = parseInt(input.value);
        const item = cart.find(item => item.id === id);

        // Temukan stok asli dari elemen produk (jika masih ada)
        const produkEl = daftarProduk.querySelector(`.produk-item[data-id="${id}"]`);
        const stokAsli = produkEl ? parseInt(produkEl.dataset.stok) : item.stok; // Fallback ke stok di keranjang

        if (newQty > stokAsli) {
            alert('Stok tidak mencukupi! Stok tersisa: ' + stokAsli);
            newQty = stokAsli;
            input.value = stokAsli;
        }

        if (newQty < 1) {
            newQty = 1;
            input.value = 1;
        }

        item.qty = newQty;
        updateKeranjang(); // Panggil updateKeranjang agar subtotal ikut terupdate
    }

    function hapusDariKeranjang(id) {
        cart = cart.filter(item => item.id !== id);
        updateKeranjang();
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