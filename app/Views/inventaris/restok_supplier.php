<?= $this->extend('layout/template') ?>

<?= $this->section('head') ?>
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<link href="<?= base_url('css/restok_supplier.css') ?>" rel="stylesheet">
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

<?php if (session()->getFlashdata('success')) : ?>
    <div class="alert alert-success" role="alert"><?= session()->getFlashdata('success'); ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger" role="alert"><?= session()->getFlashdata('error'); ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('errors')) : ?>
    <div class="alert alert-danger">
        <ul>
        <?php foreach (session()->getFlashdata('errors') as $error) : ?>
            <li><?= esc($error) ?></li>
        <?php endforeach ?>
        </ul>
    </div>
<?php endif; ?>

<div class="card shadow mb-4" style="border-left: 4px solid #2d8659;">
    <div class="card-header py-3" style="background-color: #2d8659; color: white;">
        <h6 class="m-0 font-weight-bold text-white">Data Pengajuan Restok</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTableRestok" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Supplier</th>
                        <th>Nama Barang</th>
                        <th>Qty</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    <?php foreach ($data_restok as $item) : ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= esc($item['nama_supplier']); ?></td>
                            <td><?= esc($item['nama_barang']); ?></td>
                            <td><?= esc($item['qty']); ?></td>
                            <td>Rp <?= number_format($item['total_harga'], 0, ',', '.'); ?></td>
                            <td>
                                <?php $status_class = $item['status'] == 'Disetujui' ? 'badge-success' : ($item['status'] == 'Ditolak' ? 'badge-danger' : 'badge-warning'); ?>
                                <span class="badge <?= $status_class; ?>"><?= esc($item['status']); ?></span>
                            </td>
                            <td>
                                <div class="btn-aksi-group">
                                    <a href="<?= base_url('karyawan/inventaris/detail_restok/' . $item['id_restok']); ?>" class="btn btn-info btn-sm">
                                        <i class="fas fa-info-circle"></i> Detail
                                    </a>

                                    <?php if ($item['status'] !== 'Disetujui'): ?>
                                        <button class="btn btn-warning btn-sm btn-edit"
                                            data-id="<?= $item['id_restok']; ?>"
                                            data-supplier="<?= esc($item['nama_supplier']); ?>"
                                            data-barang="<?= esc($item['nama_barang']); ?>"
                                            data-qty="<?= $item['qty']; ?>"
                                            data-status="<?= $item['status']; ?>"
                                            data-harga_satuan="<?= $item['harga_satuan']; ?>"
                                            data-total_harga="<?= $item['total_harga']; ?>">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                    <?php else: ?>
                                        <button class="btn btn-secondary btn-sm" disabled>
                                            <i class="fas fa-lock"></i> Edit
                                        </button>
                                    <?php endif; ?>

                                    <button class="btn btn-danger btn-sm" onclick="confirmDeleteRestok(<?= $item['id_restok']; ?>)">
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

<div class="modal fade" id="modalInputRestok" tabindex="-1" role="dialog" aria-labelledby="modalInputRestokLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #2d8659; color: white;">
                <h5 class="modal-title text-white" id="modalInputRestokLabel">Input Barang Supplier</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close" style="color: white; opacity: 1;">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form id="formRestok" action="<?= base_url('karyawan/inventaris/store_restok'); ?>" method="POST">
                <div class="modal-body">
                    <input type="hidden" id="restok_id_hidden" name="id_restok">

                    <div class="form-row mb-3">
                        <div class="col-md-6">
                            <label for="restok_pt_supplier">Nama Supplier</label>
                            <select id="restok_pt_supplier" name="nama_supplier" class="form-control" required>
                                <option value="" disabled selected>-- Pilih Supplier --</option>
                                <?php foreach ($suppliers as $sup): ?>
                                    <option value="<?= esc($sup['nama_supplier']); ?>"><?= esc($sup['nama_supplier']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                    <div class="col-md-6">
                        <label for="restok_nama_barang">Nama Barang</label>
                        <input 
                            type="text" 
                            class="form-control" 
                            id="restok_nama_barang" 
                            name="nama_barang" 
                            maxlength="20" 
                            required
                            placeholder="Contoh: Gorden Motif Daun"
                            oninput="validasiNamaBarang(this)"
                        >
                        <small class="form-text text-muted">Maksimal 20 Karakter.</small>
                        
                        <small id="error_spam" class="text-danger" style="display:none;">
                            <i class="fas fa-exclamation-circle"></i> Jangan mengulang huruf lebih dari 2 kali
                        </small>
                        <small id="error_gibberish" class="text-danger" style="display:none;">
                            <i class="fas fa-exclamation-circle"></i> Nama barang tidak terbaca
                        </small>
                    </div>

                    <script>
                    function validasiNamaBarang(input) {
                        input.value = input.value.replace(/[^a-zA-Z\s]/g, '');
                        
                        let text = input.value;
                        let errorSpam = document.getElementById('error_spam');
                        let errorGibberish = document.getElementById('error_gibberish');
                        
                        // Regex: Karakter apapun (.) ditangkap, lalu dicek apakah muncul lagi (\1) sebanyak 4 kali
                        let isSpam = /(.)\1{2,}/.test(text);
                        
                        // Ini untuk menangkal asal ketik/keysmash
                        let isGibberish = /[bcdfghjklmnpqrstvwxyz]{5,}/i.test(text);

                        if (isSpam) {
                            errorSpam.style.display = 'block';
                            input.setCustomValidity('Huruf berulang terlalu banyak.');
                        } else if (isGibberish) {
                            errorSpam.style.display = 'none'; 
                            errorGibberish.style.display = 'block';
                            input.setCustomValidity('Nama barang tidak wajar.');
                        } else {
                            errorSpam.style.display = 'none';
                            errorGibberish.style.display = 'none';
                            input.setCustomValidity('');
                        }
                    }
                    </script>

                    <div class="col-md-4">
                        <label for="restok_jumlah">Qty</label>
                        <input 
                            type="number" 
                            class="form-control" 
                            id="restok_jumlah" 
                            name="qty" 
                            placeholder="0" 
                            inputmode="numeric" 
                            required
                            oninput="
                                this.value = !!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null;
                                if (this.value > 1000) {
                                    this.value = this.value.slice(0, -1);
                                }
                            "
                            onkeydown="return event.keyCode !== 69 && event.keyCode !== 189 && event.keyCode !== 190 && event.keyCode !== 188"
                        >
                        <small class="text-muted">Maksimal 1000 item</small>
                    </div>

                    <div class="col-md-4">
                        <label for="restok_harga">Harga Satuan</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input 
                                type="number" 
                                class="form-control" 
                                id="restok_harga" 
                                name="harga_satuan" 
                                placeholder="0" 
                                required
                                oninput="
                                    this.value = !!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null;

                                    if(this.value > 1000000000000) {
                                        this.value = this.value.slice(0, -1);
                                    }
                                    
                                    if(this.value.length < 4 && this.value.length > 0) {
                                        this.setCustomValidity('Harga tidak valid. Minimal 4 angka (Ribuan).');
                                    } else {
                                        this.setCustomValidity('');
                                    }
                                "
                                onkeydown="return event.keyCode !== 69 && event.keyCode !== 189 && event.keyCode !== 190 && event.keyCode !== 188"
                            >
                        </div>
                        <small class="text-muted">Maksimal Rp 1.000.000.000.000.</small>
                    </div>

                        <div class="col-md-4">
                            <label for="restok_total">Total Harga</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" class="form-control" id="restok_total" name="total_harga" readonly>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" id="restok_status" name="status" value="Menunggu">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success"><i class="fas fa-save mr-1"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url('js/restok_supplier.js') ?>"></script>
<?= $this->endSection() ?>