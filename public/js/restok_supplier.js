// public/js/restok_supplier.js
$(document).ready(function () {
    // Inisialisasi DataTable (tetap menggunakan sorting existing)
    $('#dataTableRestok').DataTable({
        "order": [
            [0, "asc"]
        ],
        autoWidth: false
    });

    // Element modal / form
    const $modal = $('#modalInputRestok');
    const $form = $('#formRestok');

    const $inputHarga = $('#restok_harga');
    const $inputJumlah = $('#restok_jumlah');
    const $inputTotal = $('#restok_total');
    const $inputSupplier = $('#restok_pt_supplier');
    const $inputNamaBarang = $('#restok_nama_barang');
    const $hiddenId = $('#restok_id_hidden');

    // Hitung total (harga * qty)
    function hitungTotal() {
        const harga = parseFloat($inputHarga.val()) || 0;
        const jumlah = parseInt($inputJumlah.val()) || 0;
        $inputTotal.val(harga * jumlah);
    }

    if ($inputHarga.length && $inputJumlah.length && $inputTotal.length) {
        $inputHarga.on('input', hitungTotal);
        $inputJumlah.on('input', hitungTotal);
    }

    // --- EDIT handler ---
    // Tombol edit pada tiap baris harus punya class .btn-edit dan data-* attributes seperti di view
    $(document).on('click', '.btn-edit', function (e) {
        e.preventDefault();

        const id = $(this).data('id');
        const supplier = $(this).data('supplier') ?? '';
        const barang = $(this).data('barang') ?? '';
        const qty = $(this).data('qty') ?? '';
        const harga_satuan = $(this).data('harga_satuan') ?? '';
        const total_harga = $(this).data('total_harga') ?? '';

        // Isi form modal dengan data
        $hiddenId.val(id);
        $inputSupplier.val(supplier);
        $inputNamaBarang.val(barang);
        $inputJumlah.val(qty);
        $inputHarga.val(harga_satuan);
        $inputTotal.val(total_harga);

        // Pastikan option supplier terpilih (nilai option adalah nama_supplier)
        if ($inputSupplier.find('option[value="' + supplier + '"]').length) {
            $inputSupplier.val(supplier);
        } else {
            // Jika option tidak ada, reset ke pilihan kosong
            $inputSupplier.val('');
        }

        // Ubah judul modal
        $modal.find('#modalInputRestokLabel').text('Edit Data Restok');

        // Tampilkan modal (jika tombol edit tidak memicu data-toggle)
        $modal.modal('show');

        // Form action dibiarkan sama (store_restok) — controller akan cek id_restok untuk update/insert
        // jika Anda ingin ubah action: $form.attr('action', '/karyawan/inventaris/store_restok');
    });

    // --- RESET modal saat ditutup (agar tidak mengosongkan jika validasi gagal client ingin mempertahankan nilai) ---
    // Kita reset hanya saat tombol "Tambah" diklik atau saat modal benar-benar ditutup
    // --- RESET modal saat tombol TAMBAH ditekan saja ---
    $('#btnTambahRestok').on('click', function () {
        $hiddenId.val('');
        $inputSupplier.val('');
        $inputNamaBarang.val('');
        $inputJumlah.val('');
        $inputHarga.val('');
        $inputTotal.val('');
        $modal.find('#modalInputRestokLabel').text('Input Barang Supplier');
    });


    // Jika modal ditutup, keep state minimal (tidak otomatis clear to preserve withInput server-side)
    $modal.on('hidden.bs.modal', function () {
        // hide preview / keep fields as-is
    });

    // --- CONFIRM DELETE fungsi ---
    // Akan redirect ke endpoint delete restok (pakai origin agar bekerja baik di local)
    window.confirmDeleteRestok = function (id) {
        Swal.fire({
            title: "Hapus Data Restok?",
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Ya, hapus!"
        }).then((result) => {
            if (result.isConfirmed) {
                // Gunakan origin + path (sesuaikan bila aplikasi memakai index.php di URL)
                const base = window.location.origin;
                // Jika aplikasi dijalankan di subfolder, kita coba gunakan location.pathname untuk membangun base
                // namun biasanya origin + '/karyawan/inventaris/delete_restok/' sudah cukup
                const deleteUrl = base + '/karyawan/inventaris/delete_restok/' + id;
                window.location.href = deleteUrl;
            }
        });
    };

});
