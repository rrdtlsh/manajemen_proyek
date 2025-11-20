<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Dashboard Analitik SADANG THJ">
    <meta name="author" content="THJ">

    <title><?= $title ?? 'SADANG THJ' ?></title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/startbootstrap-sb-admin-2/4.1.4/css/sb-admin-2.min.css" rel="stylesheet">

    <?php
    // Logika Penentuan Tema Warna
    $role = session()->get('role');
    $userRole = strtolower($role ?? '');
    
    // Terapkan CSS Hijau untuk role berikut + Superadmin
    if (
        $userRole == 'penjualan' ||
        $userRole == 'inventaris' ||
        $userRole == 'keuangan' ||
        $userRole == 'pemilik' || 
        $userRole == 'superadmin'
    ) : ?>
        <link href="<?= base_url('css/penjualan.css') ?>" rel="stylesheet">
    <?php endif; ?>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <?= $this->renderSection('head') ?>

    <?php if($userRole === 'superadmin'): ?>
    <style>
        /* Beri border visual agar penguji sadar ini mode testing */
        body.penjualan-body {
            border-top: 5px solid #f6c23e; /* Warna kuning peringatan */
        }
        /* Ubah kursor tombol submit agar terlihat berbeda */
        .btn-danger, .btn-success, .btn-warning, button[type="submit"] {
            cursor: not-allowed;
            opacity: 0.8;
        }
    </style>
    <?php endif; ?>
</head>

<body id="page-top" <?php if (
                        $userRole == 'penjualan' ||
                        $userRole == 'inventaris' ||
                        $userRole == 'keuangan' ||
                        $userRole == 'superadmin'
                    ) : ?>class="penjualan-body" <?php endif; ?>>

    <?php if($userRole === 'superadmin'): ?>
    <div class="bg-warning text-dark text-center py-1 small font-weight-bold" style="position:fixed; top:0; width:100%; z-index:9999;">
        <i class="fas fa-user-shield mr-1"></i> MODE SUPERADMIN (PENGUJIAN): Anda dapat melihat semua fitur, namun penyimpanan data dinonaktifkan.
    </div>
    <div style="margin-top: 25px;"></div> <?php endif; ?>

    <div id="wrapper">

        <?= $this->include('layout/sidebar') ?>
        <div id="content-wrapper" class="d-flex flex-column">

            <div id="content">
                <?= $this->include('layout/topbar') ?>
                <div class="container-fluid">
                    <?= $this->renderSection('content') ?>
                </div>
            </div>
            
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; UD. SADANG THJ <?= date('Y'); ?></span>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Yakin ingin keluar?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Pilih "Logout" di bawah ini jika Anda siap untuk mengakhiri sesi Anda saat ini.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                    <a class="btn btn-primary" href="<?= base_url('logout'); ?>">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/startbootstrap-sb-admin-2/4.1.4/js/sb-admin-2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Flashdata Success
            <?php if (session()->getFlashdata('success')) : ?>
                Swal.fire({ icon: 'success', title: 'Berhasil!', text: '<?= session()->getFlashdata('success'); ?>', timer: 2000, showConfirmButton: false });
            <?php endif; ?>

            // Flashdata Error
            <?php if (session()->getFlashdata('error')) : ?>
                Swal.fire({ icon: 'error', title: 'Gagal!', text: '<?= session()->getFlashdata('error'); ?>', showConfirmButton: true });
            <?php endif; ?>

            // --- LOGIKA KHUSUS SUPERADMIN (Simulasi Pengujian) ---
            <?php if($userRole === 'superadmin'): ?>
                
                // 1. Intercept Formulir (Cegah Simpan/Update)
                $('form').on('submit', function(e) {
                    // Kecuali form logout atau filter laporan (GET method biasanya aman)
                    if ($(this).attr('method') && $(this).attr('method').toUpperCase() === 'POST') {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'info',
                            title: 'Mode Pengujian',
                            text: 'Dalam mode Superadmin, aksi simpan data diblokir agar database tetap aman.',
                            footer: 'Fitur ini berfungsi normal untuk akun karyawan.'
                        });
                    }
                });

                // 2. Intercept Tombol Hapus (Link dengan class btn-danger atau URL delete)
                $('a[href*="delete"], .btn-danger').on('click', function(e) {
                    // Biarkan tombol export PDF (biasanya warna merah) tetap jalan
                    if($(this).attr('href') && $(this).attr('href').includes('export')) {
                        return true; 
                    }

                    // Blokir aksi hapus
                    if ($(this).attr('href') && $(this).attr('href') !== '#' && !$(this).data('toggle')) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'warning',
                            title: 'Akses Dibatasi',
                            text: 'Anda tidak dapat menghapus data di mode ini.',
                        });
                    }
                });

                // 3. Modifikasi Tampilan Tombol agar jelas
                $('button[type="submit"]').text(function(i, text) {
                    return text + ' (Test)';
                });
                
            <?php endif; ?>
            // --- AKHIR LOGIKA SUPERADMIN ---
        });
    </script>

    <?= $this->renderSection('script') ?>

</body>
</html>