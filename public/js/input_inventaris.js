// const BASE_URL didefinisikan di file view .php

$(document).ready(function () {
    // Inisialisasi DataTable
    $('#dataTableInventaris').DataTable({
        "order": [],        // Tidak auto-sort No
        "autoWidth": false,
        "columnDefs": [
            { "orderable": false, "targets": [1, 6] },
            { "width": "1%", "targets": 0 },  // No
            { "width": "15%", "targets": 1 }, // Gambar
            { "width": "20%", "targets": 2 }, // Kode Produk
            { "width": "25%", "targets": 3 }, // Nama
            { "width": "15%", "targets": 4 }, // Harga
            { "width": "5%", "targets": 5 },  // Stok
            { "width": "20%", "targets": 6, "orderable": false } // Aksi
        ]
    });

    // === FUNGSI MODAL TAMBAH ===
    $('#btnTambahProduk').on('click', function () {
        $('#formProduk').attr('action', BASE_URL + '/store');
        $('#formMethod').val('POST');
        $('#modalProdukLabel').text('Tambah Produk Baru');

        // Kosongkan form
        $('#formProduk')[0].reset();
        $('#kode_produk').val('').prop('readonly', false);
        $('#nama_produk').val('');
        $('#id_kategori').val(''); // ganti dari kategori_produk → id_kategori
        $('#id_supplier').val('');
        $('#harga').val('');
        $('#stok').val('');
        $('#tanggal_masuk').val('');

        // Reset input gambar
        $('#gambar_produk').val('');
        $('#gambar-preview-container').hide();
        $('#gambar-label').text('Pilih gambar...');
        $('#gambar-help').text('Maks 2MB. Format: jpg, jpeg, png, webp.');
    });

    // === FUNGSI MODAL EDIT ===
    $('#dataTableInventaris').on('click', '.btn-edit', function () {
        // Ambil data dari tombol
        const id = $(this).data('id');
        const kode = $(this).data('kode_produk');
        const nama = $(this).data('nama');
        const id_kategori = $(this).data('id_kategori'); // perbaikan dari kategori_produk
        const harga = $(this).data('harga');
        const stok = $(this).data('stok');
        const id_supplier = $(this).data('id_supplier');
        const tanggal_masuk = $(this).data('tanggal_masuk');
        const gambar = $(this).data('gambar');

        // Atur form untuk mode EDIT
        $('#formProduk').attr('action', BASE_URL + '/update/' + id);
        $('#formMethod').val('POST');
        $('#modalProdukLabel').text('Edit Produk: ' + nama);

        // Isi form
        $('#kode_produk').val(kode).prop('readonly', false);
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
        $('#gambar-preview').attr('src', gambar);
        $('#gambar-preview-container').show();
    });

    // === PREVIEW FILE UPLOAD ===
    $('.custom-file-input').on('change', function (e) {
        if (e.target.files.length > 0) {
            var fileName = e.target.files[0].name;
            $(this).next('.custom-file-label').html(fileName);

            var reader = new FileReader();
            reader.onload = function (e) {
                $('#gambar-preview').attr('src', e.target.result);
                $('#gambar-preview-container').show();
            };
            reader.readAsDataURL(e.target.files[0]);
        }
    });

    // === RESET FORM SAAT MODAL DITUTUP ===
    $('#modalProduk').on('hidden.bs.modal', function () {
        $('#formProduk')[0].reset();
        $('#gambar-preview-container').hide();
        $('#gambar-label').text('Pilih gambar...');
        $('#gambar_produk').val('');
        $('#kode_produk').prop('readonly', false);
    });
});

// === KONFIRMASI DELETE ===
function confirmDelete(idProduk, deleteUrl) {
    Swal.fire({
        title: 'Anda Yakin?',
        text: "Produk ini (ID: " + idProduk + ") akan dihapus permanen. Anda tidak dapat mengembalikannya.",
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
