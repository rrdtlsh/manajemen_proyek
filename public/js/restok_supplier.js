$(document).ready(function () {
    
    // 1. Inisialisasi DataTable
    $('#dataTableRestok').DataTable({
        "order": [
            [0, "asc"]
        ],
        autoWidth: false
    });

    // Definisi Element
    const $modal = $('#modalInputRestok');
    const $form = $('#formRestok');
    const $inputHarga = $('#restok_harga');
    const $inputJumlah = $('#restok_jumlah');
    const $inputTotal = $('#restok_total');
    const $inputSupplier = $('#restok_pt_supplier');
    const $inputNamaBarang = $('#restok_nama_barang');
    const $hiddenId = $('#restok_id_hidden');

    // ===========================================
    //       LOGIKA VALIDASI INPUT (STRICT)
    // ===========================================

    // Helper: Membersihkan input non-angka DAN menghapus 0 di depan
    function sanitizeNumberInput(inputField) {
        let val = inputField.val();
        
        let cleanVal = val.replace(/[^0-9]/g, '');
        cleanVal = cleanVal.replace(/^0+/, '');
        if (val !== cleanVal) {
            inputField.val(cleanVal);
        }
        
        return cleanVal;
    }

    // A. Validasi NAMA BARANG (Max 20 Karakter)
    $inputNamaBarang.on('input', function() {
        let val = $(this).val();
        if(val.length > 20) {
            $(this).val(val.substring(0, 20));
        }
    });

    // B. Validasi HARGA (Hanya Angka, Tidak Boleh 0, Max 1 Triliun)
    $inputHarga.on('input', function() {
        let cleanVal = sanitizeNumberInput($(this));
        const maxLimit = 1000000000000; // 1 Triliun

        // Jika input kosong (karena user ketik 0), biarkan kosong
        if (cleanVal === '') {
            // Tidak perlu set value lagi, sanitize sudah menghapusnya
        } 
        // Cek batas maksimal
        else if (parseInt(cleanVal) > maxLimit) {
            $(this).val(maxLimit.toString());
        }
        
        hitungTotal();
    });

    // C. Validasi JUMLAH/QTY (Hanya Angka, Tidak Boleh 0, Max 9999)
    $inputJumlah.on('input', function() {
        let cleanVal = sanitizeNumberInput($(this));
        const maxLimit = 9999;

        if (cleanVal === '') {
            // Biarkan kosong
        } 
        else if (parseInt(cleanVal) > maxLimit) {
            $(this).val(maxLimit.toString());
        }
        
        hitungTotal();
    });

    // Fungsi Hitung Total
    function hitungTotal() {
        // Ambil nilai, jika kosong atau NaN set ke 0 untuk perhitungan
        const harga = parseInt($inputHarga.val()) || 0;
        const jumlah = parseInt($inputJumlah.val()) || 0;
        
        const total = harga * jumlah;

        // Tampilkan format Rupiah
        $inputTotal.val(new Intl.NumberFormat('id-ID').format(total));
    }

    // ===========================================
    //           LOGIKA MODAL (EDIT)
    // ===========================================

    $(document).on('click', '.btn-edit', function (e) {
        e.preventDefault();

        const id = $(this).data('id');
        const supplier = $(this).data('supplier') ?? '';
        const barang = $(this).data('barang') ?? '';
        const qty = $(this).data('qty') ?? '';
        const harga_satuan = $(this).data('harga_satuan') ?? '';
        
        // Isi form modal
        $hiddenId.val(id);
        $inputNamaBarang.val(barang);
        $inputJumlah.val(qty);
        $inputHarga.val(harga_satuan);
        
        // Set Dropdown Supplier
        if ($inputSupplier.find('option[value="' + supplier + '"]').length) {
            $inputSupplier.val(supplier);
        } else {
            $inputSupplier.val('');
        }

        hitungTotal(); // Refresh total saat modal dibuka

        $modal.find('#modalInputRestokLabel').text('Edit Data Restok');
        $modal.modal('show');
    });

    // Reset saat tombol Tambah diklik
    $('button[data-target="#modalInputRestok"]').not('.btn-edit').on('click', function () {
        $form[0].reset();
        $hiddenId.val('');
        $modal.find('#modalInputRestokLabel').text('Input Barang Supplier');
        // Reset total manual karena reset() form tidak mentrigger event input
        $inputTotal.val(''); 
    });

    // ===========================================
    //           CONFIRM DELETE
    // ===========================================
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
                window.location.href = '/karyawan/inventaris/delete_restok/' + id;
            }
        });
    };

});