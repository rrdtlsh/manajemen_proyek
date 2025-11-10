<?= $this->extend('layout/template') ?>

<?= $this->section('head') ?>
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<style>
    /* Menyesuaikan Tombol Aksi */
    .btn-aksi {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }

    /* [PENJUALAN STYLE] Memberi warna pada ikon title */
    .page-title-icon {
        color: #2d8659;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
        <i class="fas fa-box-open mr-2 page-title-icon"></i>
        Data Produk (Inventaris)
    </h1>
    <button class="btn btn-success shadow-sm" data-toggle="modal" data-target="#modalInputProduk">
        <i class="fas fa-plus fa-sm text-white-50 mr-1"></i> Input Produk Baru
    </button>
</div>

<div class="card shadow mb-4" style="border-left: 4px solid #2d8659;">
    <div class="card-header py-3" style="background-color: #2d8659; color: white;">
        <h6 class="m-0 font-weight-bold text-white">Daftar Produk</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTableProduk" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID Produk</th>
                        <th>Nama Produk</th>
                        <th>Harga</th>
                        <th>Kuantitas (Stok)</th>
                        <th>Supplier</th>
                        <th>Tanggal Masuk</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    // Asumsi variabel $produk dan $supplier dikirim dari controller
                    // Ini adalah data dummy untuk contoh tampilan
                    $produk = [
                        ['id_produk' => '00S1', 'nama_produk' => 'Karpet Selimut', 'harga' => 750000, 'stok' => 25, 'id_supplier' => 1, 'tanggal_masuk' => '2025-10-23'],
                        ['id_produk' => '00K2', 'nama_produk' => 'Karpet Motif', 'harga' => 1000000, 'stok' => 50, 'id_supplier' => 2, 'tanggal_masuk' => '2025-10-23']
                    ];
                    $supplier = [
                        1 => 'PT. Maju',
                        2 => 'PT. Jaya'
                    ];

                    foreach ($produk as $p) : 
                    ?>
                        <tr>
                            <td><?= $p['id_produk']; ?></td>
                            <td><?= $p['nama_produk']; ?></td>
                            <td>Rp <?= number_format($p['harga'], 0, ',', '.'); ?></td>
                            <td><?= $p['stok']; ?></td>
                            <td><?= $supplier[$p['id_supplier']] ?? 'N/A'; ?></td>
                            <td><?= date('d/m/Y', strtotime($p['tanggal_masuk'])); ?></td>
                            <td>
                                <button class="btn btn-warning btn-aksi" data-toggle="modal" data-target="#modalEditProduk<?= $p['id_produk']; ?>">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="btn btn-danger btn-aksi" onclick="confirmDelete('<?= $p['id_produk']; ?>')">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalInputProduk" tabindex="-1" role="dialog" aria-labelledby="modalInputProdukLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #2d8659; color: white;">
                <h5 class="modal-title text-white" id="modalInputProdukLabel">Input Data Produk</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close" style="color: white; opacity: 1;">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="<?= base_url('inventaris/save_produk'); ?>" method="POST">
                <?= csrf_field(); ?>
                <div class="modal-body">
                    <p>Masukkan detail data barang yang masuk ke gudang.</p>
                    
                    <div class="form-row mb-3">
                        <div class="col-md-4">
                            <label for="id_produk">ID Produk</label>
                            <input type="text" class="form-control" id="id_produk" name="id_produk" placeholder="Contoh: KRP001" required>
                        </div>
                        <div class="col-md-4">
                            <label for="tanggal_masuk">Tanggal Masuk</label>
                            <input type="date" class="form-control" id="tanggal_masuk" name="tanggal_masuk" value="<?= date('Y-m-d'); ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label for="nama_produk">Nama Barang</label>
                            <input type="text" class="form-control" id="nama_produk" name="nama_produk" placeholder="Masukkan nama barang" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-4">
                            <label for="harga">Harga</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="number" class="form-control" id="harga" name="harga" placeholder="Contoh: 750000" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="stok">Jumlah (Kuantitas)</label>
                            <input type="number" class="form-control" id="stok" name="stok" placeholder="Jumlah stok masuk" min="1" required>
                        </div>
                        <div class="col-md-4">
                            <label for="id_supplier">Supplier</label>
                            <select class="form-control" id="id_supplier" name="id_supplier" required>
                                <option value="" disabled selected>-- Pilih Supplier --</option>
                                <?php 
                                // Asumsi $semua_supplier dikirim dari controller
                                $semua_supplier = [
                                    ['id_supplier' => 1, 'nama_supplier' => 'PT. Maju'],
                                    ['id_supplier' => 2, 'nama_supplier' => 'PT. Jaya'],
                                    ['id_supplier' => 3, 'nama_supplier' => 'CV. Abadi']
                                ];
                                
                                foreach ($semua_supplier as $s) : ?>
                                    <option value="<?= $s['id_supplier']; ?>"><?= $s['nama_supplier']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <hr>
                    <div class="form-group">
                        <label for="gambar_produk">Upload Gambar Produk (Opsional)</label>
                        <input type="file" class="form-control-file" id="gambar_produk" name="gambar_produk">
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
        $('#dataTableProduk').DataTable({
            "order": [
                [5, "desc"] // Urutkan berdasarkan Tanggal Masuk (kolom ke-5) terbaru
            ]
        });
    });

    /**
     * Fungsi konfirmasi hapus (mirip dengan riwayat_penjualan.php)
     */
    function confirmDelete(idProduk) {
        Swal.fire({
            title: 'Anda Yakin?',
            text: "Produk dengan ID #" + idProduk + " akan dihapus permanen.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Arahkan ke URL delete (sesuaikan rute di controller Anda)
                // window.location.href = `<?= base_url('inventaris/delete_produk/'); ?>${idProduk}`;
                
                // Untuk demo, kita tampilkan alert sukses
                Swal.fire(
                    'Terhapus!',
                    'Produk ' + idProduk + ' telah dihapus (DEMO).',
                    'success'
                )
            }
        });
    }
</script>
<?= $this->endSection() ?>