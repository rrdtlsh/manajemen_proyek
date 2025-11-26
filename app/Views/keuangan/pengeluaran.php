<?= $this->extend('layout/template'); ?>

<?= $this->section('head'); ?>
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<link rel="stylesheet" href="<?= base_url('css/pengeluaran.css'); ?>">
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
    <label>Keterangan Pengeluaran</label>
    <textarea 
        class="form-control" 
        name="keterangan" 
        required 
        placeholder="Contoh: Pembelian Alat Tulis"
        rows="3"
        maxlength="100"
        oninput="validasiKeterangan(this)"
    ></textarea>
    <small id="wordInfo" class="text-muted">0/100 Karakter</small>
    <small id="errorMsg" class="text-danger d-block mt-1" style="font-size: 85%;"></small>
</div>

<script>
    function validasiKeterangan(input) {
        let teks = input.value;
        let errorMsg = document.getElementById('errorMsg');
        let wordInfo = document.getElementById('wordInfo');
        
        // --- VALIDASI 1: Hapus Simbol (Hanya Huruf, Angka & Spasi) ---
        if (/[^a-zA-Z0-9\s]/.test(teks)) {
            // Hapus karakter terlarang
            teks = teks.replace(/[^a-zA-Z0-9\s]/g, '');
            input.value = teks; 
            errorMsg.innerText = "Simbol tidak diperbolehkan (Hanya Huruf & Angka).";
        } else {
            // Hapus pesan error simbol jika sudah bersih
            if (errorMsg.innerText.includes("Simbol")) {
                errorMsg.innerText = "";
            }
        }

// --- VALIDASI 2: Auto-Correct Huruf Berulang (Maksimal 2 huruf sama) ---
        // Regex: /(.)\1{2,}/g artinya mencari huruf yg diulang 3 kali atau lebih
        if (/(.)\1{2,}/.test(teks)) {
            // Lakukan penggantian otomatis
            // '$1$1' artinya: Ambil huruf tersebut ($1), dan tulis ulang cukup 2 kali saja
            teks = teks.replace(/(.)\1{2,}/g, '$1$1');
            
            // Update tampilan input seketika
            input.value = teks;
            
            errorMsg.innerText = "Huruf berulang tidak valid.";
        } else {
            // Hapus pesan error jika tidak ada spam, TAPI jangan hapus error simbol/limit
            if (errorMsg.innerText.includes("Huruf berulang")) {
                errorMsg.innerText = "";
            }
        }

        // --- VALIDASI 3: Update Counter & Cek Limit ---
        // Hitung jumlah karakter langsung
        let jumlahKarakter = teks.length;
        
        // Update teks counter
        if(wordInfo) {
            wordInfo.innerText = jumlahKarakter + '/100 Karakter';
        }

        // Cek Maksimal (Backup Logic jika maxlength ditembus via paste)
        if (jumlahKarakter >= 100) {
             errorMsg.innerText = "Maksimal 100 karakter";
             // Jika paste teks panjang, potong paksa ke 100
             if(jumlahKarakter > 100) {
                input.value = teks.substring(0, 100);
                wordInfo.innerText = '100/100 Karakter';
             }
        } 
        // Cek Minimal (Misal minimal 10 karakter agar valid)
        else if (jumlahKarakter < 10 && jumlahKarakter > 0) {
            input.setCustomValidity('Terlalu singkat, Minimal 10 karakter.');
             // Hapus pesan error max jika ada
             if (errorMsg.innerText.includes("Maksimal")) errorMsg.innerText = "";
        } 
        else {
            // Jika aman (antara 10 - 99 karakter)
            input.setCustomValidity(''); 
            if (errorMsg.innerText.includes("Maksimal") || errorMsg.innerText.includes("Terdeteksi")) {
                errorMsg.innerText = "";
            }
        }
    }
</script>

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