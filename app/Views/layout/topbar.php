<?php
$session = session();
$role = $session->get('role');
$username = $session->get('username') ?? 'Pengguna';
?>

<?php if (
    $role == 'Penjualan' || $role == 'penjualan' ||
    $role == 'inventaris' || $role == 'Inventaris'  ||
    $role == 'Keuangan' || $role == 'keuangan' ||
    $role == 'pemilik' || $role == 'Pemilik' ||
    $role == 'owner' || $role == 'Owner' ||
    $role == 'superadmin' || $role == 'Superadmin'
    
) : ?>
    <nav class="navbar navbar-expand navbar-light topbar penjualan-topbar mb-4 static-top shadow">
        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3 text-white">
            <i class="fa fa-bars"></i>
        </button>

        <ul class="navbar-nav ml-auto">
            <li class="nav-item d-flex align-items-center">
                <div class="user-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div class="user-info mr-3">
                    <p class="user-name mb-0"><?= $username; ?></p>
                    <p class="user-role mb-0"><?= $role; ?></p>
                </div>
                <a href="#" class="btn-logout" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt"></i>
                    Log Out
                </a>
            </li>
        </ul>
    </nav>
<?php else : ?>
    <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
        </button>

        <ul class="navbar-nav ml-auto">

            <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                        <?= $username; ?>
                    </span>
                    <img class="img-profile rounded-circle" src="https://via.placeholder.com/60">
                </a>
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="#">
                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                        Profil
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                        <i class="fas fa-arrow-left fa-sm fa-fw mr-2 text-gray-400"></i> Logout
                    </a>
                </div>
            </li>

        </ul>

    </nav>
<?php endif; ?>