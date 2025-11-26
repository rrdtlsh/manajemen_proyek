<?= $this->extend('layout/template'); ?>

<?= $this->section('head'); ?>
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<link href="<?= base_url('css/input_inventaris.css') ?>" rel="stylesheet">
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
        <i class="fas fa-truck mr-2" style="color:#2d8659;"></i>
        Data Supplier
    </h1>

    <button type="button" class="btn btn-success btn-sm shadow-sm"
        data-toggle="modal" data-target="#modalSupplier">
        <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Supplier
    </button>
</div>

<?php if (session()->getFlashdata('success')) : ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "<?= session()->getFlashdata('success'); ?>",
            timer: 2500,
            showConfirmButton: false
        });
    </script>
<?php endif; ?>

<?php if (session()->getFlashdata('error')) : ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            html: "<?= session()->getFlashdata('error'); ?>",
        });
    </script>
<?php endif; ?>

<div class="card shadow mb-4">
    <div class="card-header py-3" style="background-color:#2d8659; color:white;">
        <h6 class="m-0 font-weight-bold">Daftar Supplier</h6>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTableSupplier" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Supplier</th>
                        <th>Alamat</th>
                        <th>No Telp</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?> <?php foreach ($suppliers as $s): ?>
                        <tr>
                            <td><?= $i++; ?></td>
                            <td><?= esc($s['nama_supplier']); ?></td>
                            <td><?= esc($s['alamat']); ?></td>
                            <td><?= esc($s['no_telp']); ?></td>

                            <td>
                                <div class="btn-aksi-group">

                                    <a href="<?= base_url('karyawan/inventaris/detail_supplier/' . $s['id_supplier']); ?>"
                                        class="btn btn-info btn-sm">
                                        <i class="fas fa-info-circle"></i> Detail
                                    </a>

                                    <button class="btn btn-warning btn-sm btn-edit-supplier"
                                        data-toggle="modal"
                                        data-target="#modalSupplier"
                                        data-id="<?= $s['id_supplier']; ?>"
                                        data-nama="<?= esc($s['nama_supplier']); ?>"
                                        data-alamat="<?= esc($s['alamat']); ?>"
                                        data-telp="<?= esc($s['no_telp']); ?>">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>

                                    <button class="btn btn-danger btn-sm"
                                        onclick="confirmDeleteSupplier(<?= $s['id_supplier']; ?>)">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>

                                </div>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalSupplier" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <form action="<?= base_url('karyawan/inventaris/store_supplier'); ?>" method="POST">
                <?= csrf_field(); ?>
                <input type="hidden" id="id_supplier" name="id_supplier">

                <div class="modal-header">
                    <h5 class="modal-title">Tambah Supplier</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="form-group">
                        <label for="nama_supplier">Nama Supplier</label>
                        <input type="text"
                            class="form-control"
                            id="nama_supplier"
                            name="nama_supplier"
                            placeholder="Contoh: PT. Sumber Jaya"
                            required
                            maxlength="20">
                        <small class="form-text text-muted">Maksimal 20 karakter.</small>
                    </div>

                    <div class="form-group">
                        <label for="alamat">Alamat Supplier</label>
                        <textarea class="form-control"
                            id="alamat"
                            name="alamat"
                            required
                            rows="3"
                            maxlength="50"
                            placeholder="Contoh: Jl. Merdeka No. 1, Jakarta"
                            oninput="this.value = this.value.replace(/[@#$!]/g, '')"></textarea>

                        <small class="form-text text-muted">
                            Maksimal 50 karakter.
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="no_telp">No Telepon</label>
                        <input type="text"
                            class="form-control"
                            id="no_telp"
                            name="no_telp"
                            required
                            maxlength="12"
                            minlength="11"
                            pattern="[0-9]+"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                        <small class="form-text text-muted">Hanya angka, maksimal 12 digit.</small>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>

        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('script'); ?>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<script>
    // DataTable
    $('#dataTableSupplier').DataTable({
        "order": [
            [0, "asc"]
        ],
    });

    // Edit Supplier (auto-fill modal)
    $('.btn-edit-supplier').on('click', function() {
        $('#id_supplier').val($(this).data('id'));
        $('#nama_supplier').val($(this).data('nama'));
        $('#alamat').val($(this).data('alamat'));
        $('#no_telp').val($(this).data('telp'));

        // Ganti action form menjadi UPDATE
        $('#modalSupplier form').attr('action',
            "<?= base_url('karyawan/inventaris/update_supplier/'); ?>" + $(this).data('id')
        );

        $('.modal-title').text('Edit Data Supplier');
    });

    // Ketika klik tombol tambah
    $('#modalSupplier').on('show.bs.modal', function(e) {
        if (!$(e.relatedTarget).hasClass('btn-edit-supplier')) {
            $('#modalSupplier form').attr('action', "<?= base_url('karyawan/inventaris/store_supplier'); ?>");
            $('#id_supplier').val('');
            $('#nama_supplier').val('');
            $('#alamat').val('');
            $('#no_telp').val('');
            $('.modal-title').text('Tambah Supplier');
        }
    });

    function confirmDeleteSupplier(id) {
        Swal.fire({
            title: "Hapus Data Supplier?",
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Ya, hapus!"
        }).then((result) => {
            if (result.isConfirmed) {
                // gunakan path base dari server
                window.location.href = "<?= base_url('karyawan/inventaris/delete_supplier/'); ?>" + id;
            }
        });
    }
</script>

<?= $this->endSection(); ?>