<?php
// Gunakan locale dari controller (sudah di-pass dari controller)
// Fallback ke 'id' jika tidak ada
$currentLocale = isset($currentLocale) ? $currentLocale : 'id';
$otherLocale = $currentLocale === 'id' ? 'en' : 'id';
$otherLocaleText = $currentLocale === 'id' ? lang('Login.switch_to_english') : lang('Login.switch_to_indonesian');
?>
<!DOCTYPE html>
<html lang="<?= $currentLocale ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?= lang('Login.title') ?></title>
    <link rel="stylesheet" href="<?= base_url('css/login.css') ?>">
</head>
<body>
    <div class="login-container">
        <!-- Bagian Kiri - GIF Toko -->
        <div class="left-section">
            <div class="illustration-wrapper">
                <img src="<?= base_url('images/gif toko.gif') ?>" alt="Toko Illustration" class="bank-image">
            </div>
        </div>

        <!-- Bagian Kanan - Form Login -->
        <div class="right-section">
            <div class="login-form-wrapper">
                <!-- Language Switcher -->
                <div class="language-switcher">
                    <a href="<?= base_url('login/switch/' . $otherLocale) ?>" class="lang-link">
                        <?= $otherLocaleText ?>
                    </a>
                </div>

                <div class="logo">
                    <img src="<?= base_url('images/logo-karpet.jpeg') ?>" alt="Logo SADANG THJ">
                </div>

                <h1 class="form-title"><?= lang('Login.title') ?></h1>
                <p class="form-subtitle"><?= lang('Login.subtitle') ?></p>
                
                <div class="employee-notice">
                    <p class="employee-text"><?= lang('Login.employee_only') ?></p>
                    <a href="<?= base_url('/') ?>" class="homepage-link"><?= lang('Login.go_to_homepage') ?></a>
                </div>

                <form action="<?= base_url('login/auth') ?>" method="post">
                    <div class="form-group">
                        <label for="username" class="form-label"><?= lang('Login.email') ?></label>
                        <input 
                            type="text" 
                            id="username" 
                            name="username" 
                            class="form-input"
                            placeholder="<?= lang('Login.email_placeholder') ?>" 
                            required>
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label"><?= lang('Login.password') ?></label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="form-input"
                            placeholder="<?= lang('Login.password_placeholder') ?>" 
                            required>
                    </div>

                    <div class="form-options">
                        <div class="remember-me">
                            <input type="checkbox" id="remember" name="remember">
                            <label for="remember"><?= lang('Login.remember_me') ?></label>
                        </div>
                        <a href="#" class="forgot-password"><?= lang('Login.forgot_password') ?></a>
                    </div>

                    <button type="submit" class="login-button"><?= lang('Login.login_button') ?></button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
