<?= $this->extend('layout/template'); ?>

<?= $this->section('head'); ?>
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<style>
    
    .table thead th {
        background-color: #f8f9fc;
        color: #5a5c69;
        font-weight: bold;
        border-bottom: 2px solid #e3e6f0;
        vertical-align: middle;
        text-align: center;
    }
    .table tbody td {
        vertical-align: middle;
        color: #333;
    }
    .page-title {
        font-weight: bold;
        color: #000;
    }
    /* Badge Status */
    .badge-custom {
        font-size: 0.9rem;
        padding: 0.4em 0.8em;
    }
    /* Form elemen dalam tabel */
    .status-select {
        border-radius: 5px 0 0 5px;
        border: 1px solid #d1d3e2;
        height: calc(1.5em + .75rem + 2px);
    }
    .btn-ubah {
        border-radius: 0 5px 5px 0;
    }
    /* Tombol Aksi */
    .btn-action {
        width: 80px;
        margin-bottom: 5px;
        display: block; /* Agar tombol turun ke bawah (stack) */
        font-size: 0.8rem;
        font-weight: bold;
    }
    .btn-detail {
        background-color: #fd7e14;
        border-color: #fd7e14;
        color: white;
    }
    .btn-detail:hover {
        background-color: #e36d0d;
        color: white;
    }
</style>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>

<div class="container-fluid">

    <h1 class="h3 mb-4 page-title">Daftar Restok Supplier</h1>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTableRestok" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="5%">ID</th>
                            <th>Nama PT Supplier</th>
                            <th>Barang</th>
                            <th>Total</th>
                            <th width="25%">Ubah Status</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        <?php foreach ($data_restok as $r): ?>
                            <tr>
                                <td class="text-center font-weight-bold">S<?= $r['id_restok']; ?></td> <td><?= esc($r['nama_supplier']); ?></td>
                                <td>
                                    <div class="font-weight-bold"><?= esc($r['nama_barang']); ?></div>
                                    <small class="text-muted">Qty: <?= esc($r['qty']); ?></small>
                                </td>
                                <td class="font-weight-bold">
                                    Rp <?= number_format($r['total_harga'], 0, ',', '.'); ?>
                                </td>

                                <td>
                                    <form action="<?= base_url('owner/restok/update_status'); ?>" method="POST">
                                        <?= csrf_field(); ?>
                                        <input type="hidden" name="id_restok" value="<?= $r['id_restok']; ?>">
                                        
                                        <div class="input-group">
                                            <select name="status_owner" class="custom-select status-select">
                                                <option value="Menunggu" <?= ($r['status_owner'] == 'Menunggu') ? 'selected' : ''; ?>>Menunggu</option>
                                                <option value="Disetujui" <?= ($r['status_owner'] == 'Disetujui') ? 'selected' : ''; ?>>Disetujui</option>
                                                <option value="Ditolak" <?= ($r['status_owner'] == 'Ditolak') ? 'selected' : ''; ?>>Ditolak</option>
                                            </select>
                                            <div class="input-group-append">
                                                <button class="btn btn-primary btn-ubah" type="submit">Ubah</button>
                                            </div>
                                        </div>
                                    </form>
                                </td>

                                <td class="text-center">
                                    <button type="button" class="btn btn-detail btn-sm btn-action" 
                                            data-toggle="modal" data-target="#modalDetail<?= $r['id_restok']; ?>">
                                        <i class="fas fa-file-alt mr-1"></i> Detail
                                    </button>

                                <a href="<?= base_url('owner/restok/delete/' . $r['id_restok']); ?>" 
                                class="btn btn-danger btn-sm btn-action btn-hapus"> <i class="fas fa-trash mr-1"></i> Hapus
                                </a>
                                </td>
                            </tr>

                            <div class="modal fade" id="modalDetail<?= $r['id_restok']; ?>" tabindex="-1" role="dialog">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Detail Restok #S<?= $r['id_restok']; ?></h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p><strong>Supplier:</strong> <?= esc($r['nama_supplier']); ?></p>
                                            <p><strong>Barang:</strong> <?= esc($r['nama_barang']); ?></p>
                                            <p><strong>Jumlah:</strong> <?= esc($r['qty']); ?></p>
                                            <p><strong>Harga Satuan:</strong> Rp <?= number_format($r['harga_satuan'], 0, ',', '.'); ?></p>
                                            <p><strong>Total:</strong> Rp <?= number_format($r['total_harga'], 0, ',', '.'); ?></p>
                                            <p><strong>Status:</strong> <?= esc($r['status_owner']); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<?= $this->endSection(); ?>

<?= $this->section('script'); ?>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        // 1. Inisialisasi DataTable
        $('#dataTableRestok').DataTable({
            "language": {
                "search": "Cari:",
                "lengthMenu": "Menampilkan _MENU_ data per halaman",
                "zeroRecords": "Data tidak ditemukan",
                "info": "Halaman _PAGE_ dari _PAGES_",
                "infoEmpty": "Tidak ada data",
                "paginate": {
                    "first": "Awal",
                    "last": "Akhir",
                    "next": "Lanjut",
                    "previous": "Mundur"
                }
            }
        });

        // 2. Event Listener Tombol Hapus (Delegation untuk DataTables)
        // Kita pakai $('body').on() supaya tombol di halaman 2, 3, dst tetap bisa diklik
        $('body').on('click', '.btn-hapus', function(e) {
            e.preventDefault();
            const href = $(this).attr('href');

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data restok ini akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.location.href = href;
                }
            });
        });
    });
</script>

<?php if (session()->getFlashdata('success')) : ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '<?= session()->getFlashdata('success'); ?>',
            timer: 3000,
            showConfirmButton: false
        });
    </script>
<?php endif; ?>

<?php if (session()->getFlashdata('error')) : ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '<?= session()->getFlashdata('error'); ?>',
        });
    </script>
<?php endif; ?>

<?= $this->endSection(); ?>