$(document).ready(function() {
    $('#dataTableRiwayat').DataTable({
        "order": [
            [0, "desc"]
        ]
    });
});

function confirmDelete(idPenjualan,deleteUrl) {
    Swal.fire({
        title: 'Anda Yakin?',
        text: "Transaksi #" + idPenjualan + " akan dihapus permanen. Stok barang akan dikembalikan.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = deleteUrl}
    });
}
