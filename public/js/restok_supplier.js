$(document).ready(function() {
    $('#dataTableRestok').DataTable({
        "order": [
            [0, "desc"] // Urutkan berdasarkan ID terbaru
        ]
    });

    // (Opsional) Logika front-end untuk hitung total di modal
    const inputHarga = document.getElementById('restok_harga');
    const inputJumlah = document.getElementById('restok_jumlah');
    const inputTotal = document.getElementById('restok_total');

    function hitungTotal() {
        const harga = parseFloat(inputHarga.value) || 0;
        const jumlah = parseInt(inputJumlah.value) || 0;
        inputTotal.value = harga * jumlah;
    }

    if (inputHarga && inputJumlah && inputTotal) {
        inputHarga.addEventListener('input', hitungTotal);
        inputJumlah.addEventListener('input', hitungTotal);
    }
});