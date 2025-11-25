// const BASE_URL dan CHECK_CODE_URL didefinisikan di file view .php (layout script)

$(document).ready(function () {
    
    // 1. Inisialisasi DataTable
    $('#dataTableInventaris').DataTable({
        "order": [],        // Tidak auto-sort No
        "autoWidth": false,
        "columnDefs": [
            { "orderable": false, "targets": [1, 6] },
            { "width": "1%", "targets": 0 },  // No
            { "width": "10%", "targets": 1 }, // Gambar
            { "width": "15%", "targets": 2 }, // Kode Produk
            { "width": "30%", "targets": 3 }, // Nama
            { "width": "20%", "targets": 4 }, // Harga
            { "width": "10%", "targets": 5 }, // Stok
            { "width": "14%", "targets": 6, "orderable": false } // Aksi
        ]
    });

    // ===========================================
    //       LOGIKA VALIDASI INPUT (REAL-TIME)
    // ===========================================

    // A. Validasi KODE PRODUK
    // Aturan: Max 10 karakter, Hanya Huruf & Angka (Tanpa spasi/simbol)
    $('#kode_produk').on('input', function() {
        let val = $(this).val();
        
        // 1. Hapus karakter selain huruf dan angka (Hapus @!#$- spasi dll)
        let cleanVal = val.replace(/[^a-zA-Z0-9]/g, '').toUpperCase();
        
        // 2. Batasi Max 10 Karakter
        if(cleanVal.length > 10) {
            cleanVal = cleanVal.substring(0, 10);
        }

        // Update nilai input jika ada karakter terlarang yang dihapus
        if(val !== cleanVal) {
            $(this).val(cleanVal);
        }

        // Reset style validasi manual
        $(this).removeClass('is-invalid is-valid');
        $('#error-kode-msg').html(''); 
        // (Validasi duplikat AJAX biasanya dipanggil di event 'blur')
    });

    // B. Validasi NAMA PRODUK
    // Aturan: Max 20 karakter
    $('#nama_produk').on('input', function() {
        let val = $(this).val();
        
        if(val.length > 20) {
            // Potong string jika lebih dari 20
            $(this).val(val.substring(0, 20));
            // Opsional: Tampilkan alert kecil atau visual feedback
            // alert('Nama produk maksimal 20 karakter'); 
        }
    });

    // C. Validasi HARGA
    // Aturan: Hanya angka, Maksimal 1 Miliar
    $('#harga').on('input', function() {
        let field = $(this);
        let rawVal = field.val().replace(/[^0-9]/g, ''); // Hapus titik, koma, minus
        let maxLimit = 1000000000; // 1 Miliar

        // Update field dengan angka bersih
        if (field.val() !== rawVal) {
            field.val(rawVal);
        }

        let numVal = parseFloat(rawVal);

        // Cek Limit
        if (rawVal !== '' && numVal > maxLimit) {
            field.addClass('is-invalid');
            // Anda perlu menambahkan <div class="invalid-feedback"> di HTML view untuk menampilkan pesan ini
            let errorMsg = field.next('.invalid-feedback');
            if (errorMsg.length === 0) {
                field.after('<div class="invalid-feedback" id="err-harga">Maksimal harga Rp 1 Miliar</div>');
            } else {
                errorMsg.text('Maksimal harga Rp 1 Miliar');
            }
            // Matikan tombol simpan jika invalid
            $('#btnSimpan').prop('disabled', true);
        } else {
            field.removeClass('is-invalid');
            $('#btnSimpan').prop('disabled', false);
        }
    });

    // D. Validasi STOK (Kuantitas)
    // Aturan: Hanya angka, Lebih dari 0, Maksimal 1.000
    $('#stok').on('input', function() {
        let field = $(this);
        let rawVal = field.val().replace(/[^0-9]/g, ''); // Hapus non-angka
        let maxLimit = 1000; // 1 Ribu

        if (field.val() !== rawVal) {
            field.val(rawVal);
        }

        let numVal = parseInt(rawVal);
        let btnSimpan = $('#btnSimpan'); // Pastikan tombol submit punya id="btnSimpan"

        // Hapus error sebelumnya
        field.removeClass('is-invalid');
        let errorMsg = field.next('.invalid-feedback');
        if(errorMsg.length) errorMsg.remove();

        if (rawVal === '') return;

        if (numVal < 1) {
            // Validasi Min 1
            field.addClass('is-invalid');
            field.after('<div class="invalid-feedback">Stok harus lebih dari 0.</div>');
            btnSimpan.prop('disabled', true);
        } else if (numVal > maxLimit) {
            // Validasi Max 1000
            field.addClass('is-invalid');
            field.after('<div class="invalid-feedback">Stok maksimal 1.000 unit.</div>');
            btnSimpan.prop('disabled', true);
        } else {
            // Valid
            btnSimpan.prop('disabled', false);
        }
    });


    // ===========================================
    //           LOGIKA MODAL & FORM
    // ===========================================

    // === FUNGSI MODAL TAMBAH ===
    $('#btnTambahProduk').on('click', function () {
        $('#formProduk').attr('action', BASE_URL + '/store');
        $('#formMethod').val('POST');
        $('#modalProdukLabel').text('Tambah Produk Baru');

        // Kosongkan form
        $('#formProduk')[0].reset();
        
        // Reset visual validasi
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        $('#btnSimpan').prop('disabled', false);

        $('#kode_produk').prop('readonly', false);
        
        // Reset input gambar
        $('#gambar-preview-container').hide();
        $('#gambar-label').text('Pilih gambar...');
        $('#gambar-help').text('Maks 2MB. Format: jpg, jpeg, png, webp.');
    });

    // === FUNGSI MODAL EDIT ===
    $('#dataTableInventaris').on('click', '.btn-edit', function () {
        // Ambil data dari atribut tombol
        const id = $(this).data('id');
        const kode = $(this).data('kode_produk');
        const nama = $(this).data('nama');
        const id_kategori = $(this).data('id_kategori');
        const harga = $(this).data('harga');
        const stok = $(this).data('stok');
        const id_supplier = $(this).data('id_supplier');
        const tanggal_masuk = $(this).data('tanggal_masuk');
        const gambar = $(this).data('gambar');

        // Reset visual validasi sebelum isi data
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        $('#btnSimpan').prop('disabled', false);

        // Atur form untuk mode EDIT
        $('#formProduk').attr('action', BASE_URL + '/update/' + id);
        $('#formMethod').val('POST');
        $('#modalProdukLabel').text('Edit Produk: ' + nama);

        // Isi form
        $('#kode_produk').val(kode); 
        // Opsional: Jika edit tidak boleh ganti kode, uncomment baris bawah:
        // $('#kode_produk').prop('readonly', true);

        $('#nama_produk').val(nama);
        $('#id_kategori').val(id_kategori);
        $('#id_supplier').val(id_supplier);
        $('#harga').val(harga);
        $('#stok').val(stok);
        $('#tanggal_masuk').val(tanggal_masuk);

        // Gambar
        $('#gambar_produk').val('');
        $('#gambar-label').text('Ganti gambar (jika perlu)...');
        $('#gambar-help').text('Maks 2MB. Kosongkan jika tidak ingin mengganti gambar.');

        // Tampilkan gambar preview
        if (gambar) {
            $('#gambar-preview').attr('src', gambar);
            $('#gambar-preview-container').show();
        } else {
            $('#gambar-preview-container').hide();
        }
    });

    // === PREVIEW FILE UPLOAD ===
    $('.custom-file-input').on('change', function (e) {
        if (e.target.files.length > 0) {
            var file = e.target.files[0];
            var fileName = file.name;
            
            // Validasi Ukuran (2MB) di JS
            if(file.size > 2 * 1024 * 1024) {
                Swal.fire('Gagal', 'Ukuran file terlalu besar (Maks 2MB)', 'error');
                $(this).val('');
                $('.custom-file-label').text('Pilih gambar...');
                return;
            }

            $(this).next('.custom-file-label').html(fileName);

            var reader = new FileReader();
            reader.onload = function (e) {
                $('#gambar-preview').attr('src', e.target.result);
                $('#gambar-preview-container').show();
            };
            reader.readAsDataURL(file);
        }
    });

    // === RESET FORM SAAT MODAL DITUTUP ===
    $('#modalProduk').on('hidden.bs.modal', function () {
        $('#formProduk')[0].reset();
        $('#gambar-preview-container').hide();
        $('#gambar-label').text('Pilih gambar...');
        
        // Bersihkan pesan error
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        $('#btnSimpan').prop('disabled', false);
    });
});

// === KONFIRMASI DELETE (Fungsi Global) ===
function confirmDelete(idProduk, deleteUrl) {
    Swal.fire({
        title: 'Anda Yakin?',
        text: "Produk ini (ID: " + idProduk + ") akan dihapus permanen.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = deleteUrl;
        }
    });
}