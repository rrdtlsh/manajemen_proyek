<?= $this->extend('layout/template'); ?>

<?= $this->section('head'); ?>
<style>
    /* CSS untuk daftar produk */
    #daftar-produk {
        max-height: 70vh;
        overflow-y: auto;
    }
    .produk-item {
        cursor: pointer;
        transition: transform 0.1s ease-in-out;
    }
    .produk-item:hover {
        transform: scale(1.02);
        background-color: #f8f9fa;
    }
    .produk-item img {
        width: 100%;
        height: 120px;
        object-fit: cover;
    }
    /* Sembunyikan panah di input number */
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    input[type=number] {
        -moz-appearance: textfield;
    }
</style>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>

<h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

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

<div class="row">

    <div class="col-lg-7">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Produk</h6>
            </div>
            <div class="card-body" id="daftar-produk">
                <div class="row">
                    <?php foreach ($produk as $p) : ?>
                        <div class="col-md-4 mb-3">
                            <div class="card produk-item" 
                                 data-id="<?= $p['id_produk']; ?>" 
                                 data-nama="<?= $p['nama_produk']; ?>" 
                                 data-harga="<?= $p['harga']; ?>"
                                 data-stok="<?= $p['stok']; ?>">
                                
                                <img src="<?= base_url('images/gambar karpet/default.jpg'); ?>" class="card-img-top" alt="<?= $p['nama_produk']; ?>">
                                <div class="card-body p-2">
                                    <h6 class="font-weight-bold mb-0"><?= $p['nama_produk']; ?></h6>
                                    <small class="text-muted">Stok: <?= $p['stok']; ?></small>
                                    <p class="text-primary font-weight-bold mb-0">Rp <?= number_format($p['harga'], 0, ',', '.'); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Keranjang Belanja</h6>
            </div>
            <div class="card-body">
                <form action="<?= base_url('karyawan/store_penjualan'); ?>" method="POST" id="form-transaksi">                    
                    <?= csrf_field(); ?>
                    
                    <div id="cart-items-list" style="min-height: 150px; border-bottom: 1px solid #eee; margin-bottom: 15px;">
                        <p class="text-center text-muted" id="cart-empty-msg">Keranjang masih kosong</p>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-5 col-form-label font-weight-bold">Total Belanja</label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control-plaintext font-weight-bold text-success" id="total-belanja-display" value="Rp 0" readonly>
                        </div>
                    </div>

                    <hr>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="status_bayar">Status Pembayaran</label>
                            <select id="status_bayar" name="status_bayar" class="form-control" required>
                                <option value="lunas" selected>Lunas</option>
                                <option value="belum_lunas">Belum Lunas (DP)</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="metode_pembayaran">Metode Pembayaran</label>
                            <select id="metode_pembayaran" name="metode_pembayaran" class="form-control" required>
                                <option value="cash" selected>Cash</option>
                                <option value="transfer">Transfer</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group" id="input-dp" style="display: none;">
                        <label for="jumlah_dp">Jumlah DP (Rp)</label>
                        <input type="number" class="form-control" id="jumlah_dp" name="jumlah_dp" placeholder="Masukkan jumlah DP">
                    </div>
                    <input type="hidden" name="total_belanja" id="total_belanja_hidden">
                    <input type="hidden" name="cart_items" id="cart_items_hidden">

                    <button type="submit" class="btn btn-primary btn-lg btn-block" id="btn-bayar" disabled>
                        <i class="fas fa-save"></i> Simpan Transaksi
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>


<?= $this->section('script'); ?>
<script>
    // Variabel global untuk keranjang
    let cart = [];

    // Elemen-elemen DOM
    const daftarProduk = document.getElementById('daftar-produk');
    const cartList = document.getElementById('cart-items-list');
    const cartEmptyMsg = document.getElementById('cart-empty-msg');
    const totalDisplay = document.getElementById('total-belanja-display');
    const totalHidden = document.getElementById('total_belanja_hidden');
    const cartHidden = document.getElementById('cart_items_hidden');
    const btnBayar = document.getElementById('btn-bayar');
    
    // Elemen form baru
    const statusBayar = document.getElementById('status_bayar');
    const inputDP = document.getElementById('input-dp');

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
        if (this.value === 'belum_lunas') {
            inputDP.style.display = 'block';
            inputDP.querySelector('input').setAttribute('required', 'required');
        } else {
            inputDP.style.display = 'none';
            inputDP.querySelector('input').removeAttribute('required');
            inputDP.querySelector('input').value = '';
        }
    });

    function tambahKeKeranjang(product) {
        // Cek apakah produk sudah ada di keranjang
        const itemAda = cart.find(item => item.id === product.id);

        if (itemAda) {
            // Jika ada, tambahkan qty, tapi cek stok
            if (itemAda.qty < product.stok) {
                itemAda.qty++;
            } else {
                alert('Stok produk ' + product.nama + ' tidak mencukupi!');
            }
        } else {
            // Jika belum ada, tambahkan ke keranjang
            cart.push(product);
        }
        
        updateKeranjang();
    }

    function updateKeranjang() {
        // Kosongkan tampilan keranjang
        cartList.innerHTML = ''; 

        if (cart.length === 0) {
            cartList.innerHTML = '<p class="text-center text-muted" id="cart-empty-msg">Keranjang masih kosong</p>';
            btnBayar.disabled = true;
        } else {
            btnBayar.disabled = false;
            cart.forEach(item => {
                // Buat elemen HTML untuk setiap item di keranjang
                const itemDiv = document.createElement('div');
                itemDiv.classList.add('d-flex', 'justify-content-between', 'align-items-center', 'mb-2');
                itemDiv.innerHTML = `
                    <div>
                        <h6 class="mb-0 font-weight-bold">${item.nama}</h6>
                        <small>Rp ${number_format(item.harga)}</small>
                    </div>
                    <div class_="d-flex align-items-center">
                        <input type="number" class="form-control form-control-sm" value="${item.qty}" min="1" max="${item.stok}" style="width: 60px; text-align: center;" 
                               data-id="${item.id}" onchange="updateQty(this)">
                        <button type="button" class="btn btn-danger btn-sm ml-2" onclick="hapusDariKeranjang('${item.id}')">
                            <i class="fas fa-trash-alt"></i>
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

        if (newQty > item.stok) {
            alert('Stok tidak mencukupi! Stok tersisa: ' + item.stok);
            newQty = item.stok;
            input.value = item.stok;
        }
        
        if (newQty < 1) {
            newQty = 1;
            input.value = 1;
        }

        item.qty = newQty;
        hitungTotal();
    }

    function hapusDariKeranjang(id) {
        cart = cart.filter(item => item.id !== id);
        updateKeranjang();
    }

    function hitungTotal() {
        let total = 0;
        cart.forEach(item => {
            item.subtotal = item.harga * item.qty; // Hitung subtotal per item
            total += item.subtotal;
        });

        // Update tampilan
        totalDisplay.value = 'Rp ' + number_format(total);
        
        // Update hidden input untuk form
        totalHidden.value = total;
        // Simpan keranjang sebagai JSON string
        cartHidden.value = JSON.stringify(cart);
    }

    // Fungsi helper untuk format angka (Rupiah)
    function number_format(number) {
        return new Intl.NumberFormat('id-ID').format(number);
    }

</script>
<?= $this->endSection(); ?>