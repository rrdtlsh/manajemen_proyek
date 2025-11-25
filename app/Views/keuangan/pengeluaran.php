<?= $this->extend('layout/template'); ?>

<?= $this->section('head'); ?>
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.20/dist/sweetalert2.min.css">
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
        <i class="fas fa-arrow-up mr-2" style="color: #e74a3b;"></i>
        <?= $title; ?>
    </h1>
    
    <button type="button" class="btn btn-danger shadow-sm" data-toggle="modal" data-target="#modalPengeluaran">
        <i class="fas fa-plus fa-sm text-white-50 mr-1"></i> Input Pengeluaran
    </button>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3" style="background-color: #2d8659; color: white;">
        <h6 class="m-0 font-weight-bold text-white">Rincian Pengeluaran</h6>
    </div>
    <div class="card-body">
        
        <div class="text-right mb-3">
            <a href="<?= base_url('karyawan/keuangan/pengeluaran/export/pdf'); ?>" class="btn btn-outline-danger btn-sm">
                <i class="fas fa-file-pdf mr-1"></i> Export PDF
            </a>
            <a href="<?= base_url('karyawan/keuangan/pengeluaran/export/excel'); ?>" class="btn btn-outline-success btn-sm">
                <i class="fas fa-file-excel mr-1"></i> Export Excel
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
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($laporan as $item) : ?>
                        <tr>
                            <td>
                                <?= date('d M Y', strtotime($item['tanggal'])); ?>
                            </td>
                            <td><?= esc($item['keterangan']); ?></td>
                            <td><span class="badge badge-danger">Pengeluaran</span></td>
                            <td class="text-danger font-weight-bold">
                                Rp <?= number_format($item['pengeluaran'], 0, ',', '.'); ?>
                            </td>
                            <td class="text-center">
                                <a href="#" 
                                    class="btn btn-danger btn-sm btn-circle btn-hapus-pengeluaran"
                                    data-url="<?= base_url('karyawan/keuangan/delete_pengeluaran/' . $item['id_keuangan']); ?>">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalPengeluaran" tabindex="-1" role="dialog" aria-labelledby="labelModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="labelModal">Input Pengeluaran Baru</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('karyawan/keuangan/store_pengeluaran'); ?>" method="POST">
                <?= csrf_field(); ?>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Tanggal</label>
                        <input type="date" class="form-control" name="tanggal" value="<?= date('Y-m-d'); ?>" required>
                    </div>
                <div class="form-group">
                    <label>Jumlah Pengeluaran (Rp)</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Rp</span>
                        </div>
                        <input 
                            type="number" 
                            class="form-control" 
                            name="jumlah" 
                            required 
                            placeholder="0"
                            oninput="
                                this.value = !!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null;
                                if(this.value > 1000000000000) {
                                    this.value = this.value.slice(0, -1);
                                }
                                if(this.value.length < 4 && this.value.length > 0) {
                                    this.setCustomValidity('Format Input tidak valid. Minimal 4 angka (Ribuan).');
                                } else {
                                    this.setCustomValidity('');
                                }
                            "
                            onkeydown="return event.keyCode !== 69 && event.keyCode !== 189 && event.keyCode !== 190 && event.keyCode !== 188"
                        >
                    </div>
                    <small class="text-muted">Maksimal Input Rp 1.000.000.000.000.</small>
                </div>
                <div class="form-group">
                    <label>Keterangan Penggunaan</label>
                    <textarea 
                        class="form-control" 
                        name="keterangan" 
                        required 
                        placeholder="Contoh: Pembelian Alat Tulis"
                        rows="3"
                        oninput="
                            let teks = this.value.trim();
                            let jumlahKata = teks.length === 0 ? 0 : teks.split(/\s+/).length;
                            document.getElementById('wordInfo').innerText = jumlahKata + '/20 Kata';
                            if(jumlahKata < 2) {
                                this.setCustomValidity('Terlalu singkat, Minimal 2 kata.');
                            } else if(jumlahKata > 20) {
                                this.setCustomValidity('Terlalu panjang! Maksimal 20 kata.');
                            } else {
                                this.setCustomValidity('');
                            }
                        "
                    ></textarea>
                    <small id="wordInfo" class="text-muted">0/20 Kata</small>
                </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('script'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.20/dist/sweetalert2.all.min.js"></script>

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function() {
        // 3. PERBAIKAN: SweetAlert untuk Flashdata Success
        <?php if (session()->getFlashdata('success')) : ?>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '<?= session()->getFlashdata('success'); ?>',
                timer: 3000,
                showConfirmButton: false
            });
        <?php endif; ?>

        // Inisialisasi DataTables
        $('#dataTable').DataTable({
            "order": [
                [0, "desc"]
            ] // Urutkan tanggal terbaru
        });

        // 4. PERBAIKAN: SweetAlert untuk Konfirmasi Hapus
        $('.btn-hapus-pengeluaran').on('click', function(e) {
            e.preventDefault();
            const deleteUrl = $(this).data('url');

            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: "Anda yakin ingin menghapus data pengeluaran ini secara permanen?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Jika dikonfirmasi, redirect ke URL hapus
                    window.location.href = deleteUrl;
                }
            });
        });
    });
</script>
<?= $this->endSection(); ?>