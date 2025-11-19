<?= $this->extend('layout/template'); ?>

<?= $this->section('head'); ?>
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<style>
    
    .modal-header-custom {
        background-color: #2d8659;
        color: white;
    }
    .total-row {
        font-weight: bold;
        background-color: #f8f9fc;
    }
</style>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
        <i class="fas fa-arrow-down mr-2" style="color: #1cc88a;"></i>
        <?= $title; ?>
    </h1>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3" style="background-color: #2d8659; color: white;">
        <h6 class="m-0 font-weight-bold text-white">Rincian Pemasukan</h6>
    </div>
    <div class="card-body">
        <div class="text-right mb-3">
            <a href="<?= base_url('karyawan/keuangan/pemasukan/export/pdf'); ?>" class="btn btn-danger btn-sm">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
            <a href="<?= base_url('karyawan/keuangan/pemasukan/export/excel'); ?>" class="btn btn-success btn-sm">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
        </div>
        
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th>Tipe</th>
                        <th>Jumlah</th>
                        <th>Aksi</th> </tr>
                </thead>
                <tbody>
                    <?php foreach ($laporan as $item) : ?>
                        <?php 
                            // Cek apakah keterangan mengandung format "Penjualan #ANGKA"
                            // Kita gunakan Regex untuk mengambil ID Penjualan
                            $isPenjualan = false;
                            $idPenjualan = null;
                            if (preg_match('/Penjualan #(\d+)/i', $item['keterangan'], $matches)) {
                                $isPenjualan = true;
                                $idPenjualan = $matches[1]; // Mengambil angka ID
                            }
                        ?>
                        <tr>
                            <td>
                                <?php if ($item['tanggal']) : ?>
                                    <?= date('d M Y', strtotime($item['tanggal'])); ?>
                                <?php else : ?>
                                    <span class="text-muted">N/A</span>
                                <?php endif; ?>
                            </td>
                            <td><?= $item['keterangan']; ?></td>
                            <td>
                                <span class="badge badge-success"><?= $item['tipe']; ?></span>
                            </td>
                            <td class="text-success font-weight-bold">
                                Rp <?= number_format($item['pemasukan'], 0, ',', '.'); ?>
                            </td>
                            <td class="text-center">
                                <?php if ($isPenjualan && $idPenjualan): ?>
                                    <button type="button" class="btn btn-info btn-sm btn-detail" 
                                            data-id="<?= $idPenjualan; ?>"
                                            data-toggle="modal" 
                                            data-target="#modalDetailTransaksi">
                                        <i class="fas fa-eye"></i> Detail
                                    </button>
                                <?php else: ?>
                                    <span class="text-muted small">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDetailTransaksi" tabindex="-1" role="dialog" aria-labelledby="modalDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title" id="modalDetailLabel">Detail Barang Terjual</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center" id="loading-spinner">
                    <div class="spinner-border text-success" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
                
                <div id="detail-content" style="display: none;">
                    <h6 class="font-weight-bold mb-3">ID Transaksi: #<span id="modal-id-transaksi"></span></h6>
                    <table class="table table-sm table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>Produk</th>
                                <th class="text-right">Harga</th>
                                <th class="text-center">Qty</th>
                                <th class="text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="modal-tbody">
                            </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('script'); ?>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            "order": [[ 0, "desc" ]] 
        });

        // Event Listener untuk Tombol Detail
        $('.btn-detail').on('click', function() {
            var idPenjualan = $(this).data('id');
            
            // Reset Modal
            $('#modal-id-transaksi').text(idPenjualan);
            $('#modal-tbody').empty();
            $('#loading-spinner').show();
            $('#detail-content').hide();

            // AJAX Request
            $.ajax({
                url: "<?= base_url('karyawan/keuangan/get_detail/'); ?>" + idPenjualan,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('#loading-spinner').hide();
                    $('#detail-content').show();
                    
                    var html = '';
                    if(data.length > 0) {
                        $.each(data, function(i, item) {
                            var harga = parseInt(item.harga_satuan);
                            var qty = parseInt(item.qty);
                            var subtotal = harga * qty;
                            
                            html += '<tr>';
                            html += '<td>' + item.nama_produk + ' <br><small class="text-muted">' + (item.kode_produk || '-') + '</small></td>';
                            html += '<td class="text-right">Rp ' + new Intl.NumberFormat('id-ID').format(harga) + '</td>';
                            html += '<td class="text-center">' + qty + '</td>';
                            html += '<td class="text-right font-weight-bold">Rp ' + new Intl.NumberFormat('id-ID').format(subtotal) + '</td>';
                            html += '</tr>';
                        });
                    } else {
                        html = '<tr><td colspan="4" class="text-center">Tidak ada data detail barang.</td></tr>';
                    }
                    
                    $('#modal-tbody').html(html);
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    $('#loading-spinner').hide();
                    alert('Gagal mengambil data detail.');
                }
            });
        });
    });
</script>
<?= $this->endSection(); ?>