$(document).ready(function() {
    $('#dataTableInventaris').DataTable({
        "order": [
            [0, "asc"] // Urutkan berdasarkan No
        ],
        "columnDefs": [
            { "width": "1%", "targets": 0 }, // No
            { "width": "15%", "targets": 1 }, // Gambar
            { "width": "30%", "targets": 2 }, // Nama
            { "width": "15%", "targets": 3 }, // Harga
            { "width": "5%", "targets": 4 }, // Stok
            { "width": "20%", "targets": 5, "orderable": false } // Aksi
        ]
    });
});

// [PERBAIKAN] Fungsi menerima 'idProduk' dan 'deleteUrl'
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