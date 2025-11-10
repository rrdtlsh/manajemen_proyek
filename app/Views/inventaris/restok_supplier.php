<?= $this->extend('layout/template') ?>

<?= $this->section('head') ?>
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<style>
    /* Menyesuaikan Tombol Aksi */
    .btn-aksi {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    .page-title-icon {
        color: #2d8659;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
        <i class="fas fa-dolly mr-2 page-title-icon"></i>
        Restok Barang Supplier
    </h1>
    <button class="btn btn-success shadow-sm" data-toggle="modal" data-target="#modalInputRestok">
        <i class="fas fa-plus fa-sm text-white-50 mr-1"></i> Input Barang Supplier
    </button>
</div>

<div class="card shadow mb-4" style="border-left: 4px solid #2d8659;">
    <div class="card-header py-3" style="background-color: #2d8659; color: white;">
        <h6 class="m-0 font-weight-bold text-white">Data Pengajuan Restok</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTableRestok" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama PT Supplier</th>
                        <th>Barang</th>
                        <th>Qty</th>
                        <th>Harga</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>S1</td>
                        <td>PT. Jaya</td>
                        <td>Karpet selimut</td>
                        <td>25</td>
                        <td>Rp 250.000</td>
                        <td>Rp 6.250.000</td>
                        <td>
                            <span class="badge badge-warning">Menunggu Disetujui</span>
                        </td>
                        <td>
                            <button class="btn btn-warning btn-aksi">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="btn btn-danger btn-aksi">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>S2</td>
                        <td>PT. Maju</td>
                        <td>Karpet Motif</td>
                        <td>25</td>
                        <td>Rp 200.000</td>
                        <td>Rp 5.000.000</td>
                        <td>
                            <span class="badge badge-success">Disetujui</span>
                        </td>
                        <td>
                            <button class="btn btn-warning btn-aksi">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="btn btn-danger btn-aksi">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalInputRestok" tabindex="-1" role="dialog" aria-labelledby="modalInputRestokLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #2d8659; color: white;">
                <h5 class="modal-title text-white" id="modalInputRestokLabel">Input Barang Supplier</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close" style="color: white; opacity: 1;">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form>
                <div class="modal-body">
                    <h6 class="font-weight-bold text-gray-800">Supplier</h6>
                    <hr class="mt-0">
                    
                    <div class="form-row mb-3">
                        <div class="col-md-4">
                            <label for="restok_id">ID</label>
                            <input type="text" class="form-control" id="restok_id" name="restok_id" placeholder="ID Pengajuan (Otomatis)">
                        </div>
                        <div class="col-md-4">
                            <label for="restok_pt_supplier">Nama PT Supplier</label>
                            <input type="text" class="form-control" id="restok_pt_supplier" name="restok_pt_supplier" placeholder="Contoh: PT. Jaya" required>
                        </div>
                        <div class="col-md-4">
                            <label for="restok_nama_barang">Nama Barang</label>
                            <input type="text" class="form-control" id="restok_nama_barang" name="restok_nama_barang" placeholder="Contoh: Karpet Motif" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-4">
                            <label for="restok_jumlah">Jumlah (Qty)</label>
                            <input type="number" class="form-control" id="restok_jumlah" name="restok_jumlah" min="1" required>
                        </div>
                        <div class="col-md-4">
                            <label for="restok_harga">Harga (Satuan)</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="number" class="form-control" id="restok_harga" name="restok_harga" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="restok_total">Total</label>
                             <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="number" class="form-control" id="restok_total" name="restok_total" readonly style="background-color: #e9ecef;">
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save mr-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<script>
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

        inputHarga.addEventListener('input', hitungTotal);
        inputJumlah.addEventListener('input', hitungTotal);
    });
</script>
<?= $this->endSection() ?>