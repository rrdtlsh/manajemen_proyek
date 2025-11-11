<?= $this->extend('layout/template'); ?>

<?= $this->section('head'); ?>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<link href="<?= base_url('css/edit_transaksi.css') ?>" rel="stylesheet">
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 penjualan-page-title">
        <i class="fas fa-edit mr-2 icon-title"></i>
        <?= $title; ?> (ID: #<?= $penjualan['id_penjualan']; ?>)
    </h1>
</div>

<div class="row">
    <div class="col-lg-7">
        <div class="card penjualan-card shadow mb-4">
            <div class="card-header penjualan-card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold section-header">
                    <i class="fas fa-box section-icon penjualan-icon"></i>
                    Daftar Produk
                </h6>
            </div>
            <div class="card-body" id="daftar-produk">
                <div class="row">
                    <?php foreach ($produk as $p) : ?>
                        <div class="col-md-4 mb-4">
                            <div class="produk-item penjualan-item"
                                data-id="<?= $p['id_produk']; ?>"
                                data-nama="<?= $p['nama_produk']; ?>"
                                data-harga="<?= $p['harga']; ?>"
                                data-stok="<?= $p['stok']; ?>">

                                <img src="<?= base_url('uploads/produk/' . $p['gambar_produk']); ?>" class="card-img-top" alt="<?= $p['nama_produk']; ?>">
                                <div class="produk-info">
                                    <h6 class="font-weight-bold mb-2 text-gray-800"><?= $p['nama_produk']; ?></h6>
                                    <div class="produk-stok mb-2">
                                        <i class="fas fa-cubes icon-stok"></i>
                                        <span class="text-muted">Stok: <strong><?= $p['stok']; ?></strong></span>
                                    </div>
                                    <div class="produk-harga">
                                        <p class="font-weight-bold mb-0 price-tag">
                                            <i class="fas fa-tag mr-1"></i>
                                            Rp <?= number_format($p['harga'], 0, ',', '.'); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card penjualan-card shadow mb-4 card-transaksi">
            <div class="card-header penjualan-card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold section-header">
                    <i class="fas fa-shopping-cart section-icon penjualan-icon"></i>
                    Edit Transaksi
                </h6>
            </div>
            <div class="card-body">
                <form action="<?= base_url('karyawan/update_penjualan/' . $penjualan['id_penjualan']); ?>" method="POST" id="form-transaksi">
                    <?= csrf_field(); ?>

                    <div class="form-group">
                        <label for="tanggal" class="font-weight-bold text-gray-700">Tanggal*</label>
                        <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?= esc($penjualan['tanggal']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="cari-pelanggan" class="font-weight-bold text-gray-700">Pelanggan*</label>
                        <div class="input-group">
                            <select id="cari-pelanggan" name="id_pelanggan" class="form-control select2-custom" required>
                                <?php if ($pelanggan_terpilih): ?>
                                    <option value="<?= esc($pelanggan_terpilih['id_pelanggan']); ?>" selected>
                                        <?= esc($pelanggan_terpilih['nama_pelanggan']); ?> (<?= esc($pelanggan_terpilih['no_hp']); ?>)
                                    </option>
                                <?php else: ?>
                                    <option value="" selected disabled>-- Wajib Pilih Pelanggan --</option>
                                <?php endif; ?>
                            </select>
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button" id="btn-pelanggan-baru" data-toggle="modal" data-target="#modalTambahPelanggan">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="cari-barang" class="font-weight-bold text-gray-700">Cari Barang (Opsional)</label>
                        <select id="cari-barang" class="form-control select2-custom"></select>
                    </div>

                    <hr>

                    <h6 class="font-weight-bold text-gray-800">Keranjang Belanja*</h6>
                    <div id="cart-items-list" class="cart-container" data-initial-cart='<?= esc($detail_penjualan_json); ?>'>
                        <div class="text-center py-5" id="cart-empty-state">
                        </div>
                    </div>

                    <div class="total-belanja-card penjualan-total">
                        <div class="total-label">Total Belanja</div>
                        <div class="total-value" id="total-belanja-display">Rp 0</div>
                    </div>

                    <hr class="my-4">

                    <div class="form-row mb-3">
                        <div class="form-group col-md-6">
                            <label for="status_bayar" class="font-weight-bold text-gray-700">Status Pembayaran*</label>
                            <select id="status_bayar" class="form-control disabled-select">
                                <option value="lunas">Lunas</option>
                                <option value="belum_lunas">Belum Lunas (DP)</option>
                            </select>
                            <input type="hidden" id="status_bayar_hidden" name="status_pembayaran" value="<?= ($penjualan['status_pembayaran'] == 'Lunas') ? 'lunas' : 'belum_lunas'; ?>">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="metode_pembayaran" class="font-weight-bold text-gray-700">Metode Pembayaran*</label>
                            <select id="metode_pembayaran" name="metode_pembayaran" class="form-control" required>
                                <option value="cash" <?= ($penjualan['metode_pembayaran'] == 'cash') ? 'selected' : ''; ?>>Cash</option>
                                <option value="transfer" <?= ($penjualan['metode_pembayaran'] == 'transfer') ? 'selected' : ''; ?>>Transfer</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group" id="input-dp">
                        <label for="jumlah_dp" class="font-weight-bold text-gray-700">Jumlah Bayar/DP (Rp)*</label>
                        <input type="number" class="form-control" id="jumlah_dp" name="jumlah_dp" placeholder="Masukkan jumlah bayar" value="<?= $penjualan['jumlah_dp']; ?>" required>
                    </div>

                    <input type="hidden" name="total" id="total_belanja_hidden">
                    <input type="hidden" name="cart_items" id="cart_items_hidden">

                    <div class="row mt-4">
                        <div class="col-6">
                            <a href="<?= base_url('karyawan/riwayat_penjualan') ?>" class="btn btn-secondary penjualan-btn btn-lg btn-block">
                                <i class="fas fa-times mr-2"></i> Batal
                            </a>
                        </div>
                        <div class="col-6">
                            <button type="submit" class="btn btn-success penjualan-btn btn-lg btn-block" id="btn-bayar">
                                <i class="fas fa-save mr-2"></i> Update
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambahPelanggan" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Tambah Pelanggan Baru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-add-pelanggan">
                    <div id="modal-errors" class="alert alert-danger modal-errors"></div>
                    <div class="form-group">
                        <label for="nama_pelanggan">Nama Pelanggan*</label>
                        <input type="text" class="form-control" id="nama_pelanggan" name="nama_pelanggan" required>
                    </div>
                    <div class="form-group">
                        <label for="no_hp">No. HP*</label>
                        <input type="text" class="form-control" id="no_hp" name="no_hp" required>
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <textarea class="form-control" id="alamat" name="alamat" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btn-simpan-pelanggan">Simpan</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>


<?= $this->section('script'); ?>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="<?= base_url('js/edit_transaksi.js') ?>"></script>
<?= $this->endSection(); ?>