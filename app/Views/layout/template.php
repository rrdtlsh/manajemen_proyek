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
    $role = session()->get('role');
    if (
        $role == 'Penjualan' || $role == 'penjualan' ||
        $role == 'inventaris' || $role == 'Inventaris'  ||
        $role == 'Keuangan' || $role == 'keuangan'||
        $role == 'pemilik' || $role == 'Pemilik' 
    ) : ?>
        <link href="<?= base_url('css/penjualan.css') ?>" rel="stylesheet">
    <?php endif; ?>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <?= $this->renderSection('head') ?>
</head>

<body id="page-top" <?php if (
                        $role == 'Penjualan' || $role == 'penjualan' ||
                        $role == 'inventaris' || $role == 'Inventaris'  ||
                        $role == 'Keuangan' || $role == 'keuangan'
                    ) : ?>class="penjualan-body" <?php endif; ?>>

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

    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
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
            // Cek jika ada session flashdata 'success'
            <?php if (session()->getFlashdata('success')) : ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '<?= session()->getFlashdata('success'); ?>',
                    timer: 2000, // Tampil selama 2 detik
                    showConfirmButton: false
                });
            <?php endif; ?>

            // Cek jika ada session flashdata 'error'
            <?php if (session()->getFlashdata('error')) : ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: '<?= session()->getFlashdata('error'); ?>',
                    showConfirmButton: true // Biarkan user klik OK
                });
            <?php endif; ?>
        });
    </script>

    <?= $this->renderSection('script') ?>

</body>

</html>