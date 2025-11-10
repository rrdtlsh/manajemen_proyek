<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
    
    <div class="welcome-message">
        <h3>Selamat Datang, <?= esc(session()->get('username')) ?>!</h3>
        <p>Ini adalah dashboard Keuangan Anda.</p>
    </div>

    <!-- Nanti, letakkan konten khusus INVENTARIS di sini. -->
    <div class="summary-cards">
        <div class="card">
            <h4>Produk Hampir Habis</h4>
            <p>0</p>
        </div>
        <div class="card">
            <h4>Total Item Stok</h4>
            <p>0</p>
        </div>
    </div>
    
<?= $this->endSection() ?>