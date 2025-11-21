<?php

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SADANG THJ</title>
    <link rel="stylesheet" href="<?= base_url('css/login.css') ?>">
</head>

<body>
    <div class="login-container">
        <div class="left-section">
            <div class="illustration-wrapper">
                <img src="<?= base_url('images/bank.png') ?>" alt="Illustration" class="bank-image">
            </div>
        </div>

        <div class="right-section">
            <div class="login-form-wrapper">
                <div class="logo">
                    <img src="<?= base_url('images/logo-karpet.jpeg') ?>" alt="Logo SADANG THJ">
                </div>

                <h1 class="form-title">SADANG THJ Dashboard</h1>
                <p class="form-subtitle">Selamat datang kembali! Silakan masukkan detail Anda.</p>

                <div class="employee-notice">
                    <p class="employee-text">Hanya untuk karyawan.</p>
                    <a href="<?= base_url('/') ?>" class="homepage-link">Klik Disini untuk Kembali ke beranda</a>
                </div>

                <form action="<?= base_url('login/auth') ?>" method="post">
                    <div class="form-group">
                        <label for="username" class="form-label">Nama Pengguna</label>
                        <input
                            type="text"
                            id="username"
                            name="username"
                            class="form-input"
                            placeholder="Masukkan nama pengguna"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Kata Sandi</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-input"
                            placeholder="Masukkan kata sandi"
                            required>
                    </div>

                    <button type="submit" class="login-button">Masuk</button>
                </form>
            </div>
        </div>
    </div>

    <?php if (session()->getFlashdata('error')) : ?>
        <script>
            // Menunggu halaman siap
            window.onload = function() {
                // 1. Mengosongkan field username dan password
                document.getElementById('username').value = '';
                document.getElementById('password').value = '';

                // 2. Menampilkan pop-up alert biasa
                alert("<?= session()->getFlashdata('error') ?>");
            };
        </script>
    <?php endif; ?>
    </body>
</html>