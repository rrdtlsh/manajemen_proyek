<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $title; ?> (ID: #<?= esc($produk['id_produk']); ?>)</h1>
        <a href="<?= base_url('karyawan/inventaris'); ?>" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali ke Inventaris
        </a>
    </div>

    <div class="row">
        <!-- Detail Produk -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 bg-success text-white">
                    <h6 class="m-0 font-weight-bold">Informasi Produk</h6>
                </div>
                <div class="card-body text-center">
                    <img src="<?= base_url('uploads/produk/' . $produk['gambar_produk']); ?>"
                        alt="<?= esc($produk['nama_produk']); ?>"
                        class="img-fluid mb-3"
                        style="max-width: 200px; border-radius: 10px;">
                    <h5 class="font-weight-bold"><?= esc($produk['nama_produk']); ?></h5>
                    <p class="text-muted mb-1"><?= esc($kategori['nama_kategori'] ?? 'Tidak Ada Kategori'); ?></p>
                    <p class="mb-2"><strong>Supplier:</strong> <?= esc($supplier['nama_supplier'] ?? 'Tidak Ada'); ?></p>
                    <span class="badge badge-info p-2">Kode: <?= esc($produk['kode_produk']); ?></span>
                </div>
            </div>
        </div>

        <!-- Informasi Tambahan -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 bg-success text-white">
                    <h6 class="m-0 font-weight-bold">Detail Lengkap Produk</h6>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Kode Produk</strong>
                            <span><?= esc($produk['kode_produk']); ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Harga</strong>
                            <span>Rp <?= number_format($produk['harga'], 0, ',', '.'); ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Stok</strong>
                            <span><?= esc($produk['stok']); ?> Unit</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Tanggal Masuk</strong>
                            <span><?= date('d M Y', strtotime($produk['tanggal_masuk'])); ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center bg-light">
                            <h5 class="font-weight-bold text-success mb-0">Total Nilai Stok</h5>
                            <h5 class="font-weight-bold text-success mb-0">
                                Rp <?= number_format($produk['harga'] * $produk['stok'], 0, ',', '.'); ?>
                            </h5>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</div>

<?= $this->endSection(); ?>