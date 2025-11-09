<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
    
    <div class="welcome-message">
        <h3>Selamat Datang, <?= esc(session()->get('username')) ?>!</h3>
        <p>Ini adalah dashboard Keuangan Anda.</p>
    </div>

    <!-- Nanti, letakkan konten khusus KEUANGAN di sini. -->
    <div class="summary-cards">
        <div class="card">
            <h4>Kas Masuk Hari Ini</h4>
            <p>Rp 0</p>
        </div>
        <div class="card">
            <h4>Kas Keluar Hari Ini</h4>
            <p>Rp 0</p>
        </div>
    </div>
    
<?= $this->endSection() ?>