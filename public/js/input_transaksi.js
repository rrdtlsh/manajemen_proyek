let cart = []; // Variabel global keranjang

// Elemen DOM
const daftarProduk = document.getElementById('daftar-produk');
const cartList = document.getElementById('cart-items-list');
const totalDisplay = document.getElementById('total-belanja-display');
const totalHidden = document.getElementById('total_belanja_hidden');
const cartHidden = document.getElementById('cart_items_hidden');
const btnBayar = document.getElementById('btn-bayar');
const btnReset = document.getElementById('btn-reset');
const statusBayar = document.getElementById('status_bayar');

// [PERBAIKAN] Ambil <input> DP-nya, bukan <div>-nya
const inputDPDiv = document.getElementById('input-dp'); // Ini div
const inputDP = document.getElementById('jumlah_dp'); // Ini input number-nya

// Elemen DOM untuk Validasi
const inputTanggal = document.getElementById('tanggal');
const selectPelanggan = $('#cari-pelanggan');
const selectBarang = $('#cari-barang');
const modalPelanggan = $('#modalTambahPelanggan');
const btnSimpanPelanggan = $('#btn-simpan-pelanggan');
const formAddPelanggan = $('#form-add-pelanggan');
const modalErrors = $('#modal-errors');

// =======================================================
// [VALIDASI] Fungsi untuk Cek Form dan Aktifkan Tombol Simpan
// =======================================================
function validateForm() {
    let isValid = true;

    // 1. Cek Keranjang (minimal 1 barang)
    if (cart.length === 0) {
        isValid = false;
    }

    // 2. Cek Tanggal
    if (inputTanggal.value === '') {
        isValid = false;
    }

    // 3. Cek Pelanggan (wajib dipilih)
    if (selectPelanggan.val() === null || selectPelanggan.val() === '') {
        isValid = false;
    }

    // 4. Cek Uang DP (jika 'belum_lunas')
    if (statusBayar.value === 'belum_lunas') {
        // [PERBAIKAN] Gunakan 'inputDP.value' (variabel input)
        const dpValue = parseFloat(inputDP.value);
        if (inputDP.value === '' || isNaN(dpValue) || dpValue <= 0) {
            isValid = false;
        }
    }

    // 5. Aktifkan atau Non-aktifkan tombol
    btnBayar.disabled = !isValid;
}

// =======================================================
// Inisialisasi Select2
// =======================================================
$(document).ready(function() {

    // [PERBAIKAN] Ambil URL dinamis dari atribut data- di form
    const formTransaksi = $('#form-transaksi');
    const searchPelangganUrl = formTransaksi.data('url-search-pelanggan');
    const searchProdukUrl = formTransaksi.data('url-search-produk');
    const addPelangganUrl = formTransaksi.data('url-add-pelanggan');

    selectPelanggan.select2({
        theme: "bootstrap-5",
        placeholder: 'Cari nama atau No. HP pelanggan...',
        allowClear: false,
        ajax: {
            url: searchPelangganUrl, // [PERBAIKAN] Menggunakan URL dinamis
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
            url: searchProdukUrl, // [PERBAIKAN] Menggunakan URL dinamis
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

    // Event handler ketika produk dipilih dari PENCARIAN
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

    // Event handler untuk tombol Simpan Pelanggan Baru
    btnSimpanPelanggan.on('click', function() {
        $.ajax({
            url: addPelangganUrl, // [PERBAIKAN] Menggunakan URL dinamis
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
                    validateForm(); // [VALIDASI] Cek form lagi
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

    // [VALIDASI] Panggil validateForm() saat event-event ini terjadi
    inputTanggal.addEventListener('change', validateForm);
    selectPelanggan.on('change', validateForm);
    statusBayar.addEventListener('change', validateForm);
    inputDP.addEventListener('input', validateForm); // Targetkan <input>

    validateForm(); // Panggil sekali saat halaman dimuat

}); // Akhir document.ready


// =======================================================
// Fungsi-fungsi Keranjang
// =======================================================

// Klik produk di GRID KIRI
if (daftarProduk) {
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
}


// Tombol Reset
btnReset.addEventListener('click', function() {
    if (cart.length === 0) {
        Swal.fire({
            icon: 'info',
            title: 'Keranjang sudah kosong',
            showConfirmButton: false,
            timer: 1500
        });
        return;
    }
    Swal.fire({
        title: 'Reset Keranjang?',
        text: "Anda yakin ingin mengosongkan keranjang belanja?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Reset!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            cart = [];
            updateKeranjang();
            Swal.fire('Direset!', 'Keranjang belanja telah dikosongkan.', 'success');
        }
    });
});

// Status Bayar
statusBayar.addEventListener('change', function() {
    // [PERBAIKAN] Targetkan div-nya untuk display
    if (this.value === 'belum_lunas') {
        inputDPDiv.style.display = 'block';
        inputDP.setAttribute('required', 'required');
    } else {
        inputDPDiv.style.display = 'none';
        inputDP.removeAttribute('required');
        inputDP.value = '';
    }
    validateForm(); // [VALIDASI] Panggil cek form
});


function tambahKeKeranjang(product) {
    const itemAda = cart.find(item => item.id === product.id);

    if (itemAda) {
        if (itemAda.qty < product.stok) {
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
        // baris 'btnBayar.disabled = true' dihapus dari sini (sudah benar)
    } else {
        // baris 'btnBayar.disabled = false' dihapus dari sini (sudah benar)
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
                        <input type="number" class="form-control form-control-sm text-center" value="${item.qty}" min="1" max="${item.stok}" 
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
    validateForm(); // [VALIDASI] Panggil cek form setiap keranjang berubah
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
