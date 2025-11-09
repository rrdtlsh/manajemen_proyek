<?= $this->extend('layout/template') ?>

<?= $this->section('content') ?>
    
    <div class="welcome-message">
        <h3>Selamat Datang, <?= esc(session()->get('username')) ?>!</h3>
        <p>Ini adalah ringkasan pekerjaan Anda hari ini.</p>
    </div>

    <div class="summary-cards">
        <div class="card">
            <h4>Penjualan Hari Ini</h4>
            <p>12</p>
        </div>
        <div class="card">
            <h4>Produk Terjual</h4>
            <p>30</p>
        </div>
        <div class="card">
            <h4>Total Omzet Hari Ini</h4>
            <p>Rp 2.500.000</p>
        </div>
    </div>

    <div class="card-table">
        <div class="card-header">
            <h4>Transaksi Terakhir Anda</h4>
        </div>
        <div class="card-body">
            <table>
                <thead>
                    <tr>
                        <th>ID Transaksi</th>
                        <th>Produk</th>
                        <th>Jumlah</th>
                        <th>Total</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>TRX-001</td>
                        <td>Karpet Turki</td>
                        <td>2</td>
                        <td>Rp 800.000</td>
                        <td>14:30</td>
                    </tr>
                    <tr>
                        <td>TRX-002</td>
                        <td>Sajadah</td>
                        <td>5</td>
                        <td>Rp 500.000</td>
                        <td>11:15</td>
                    </tr>
                    </tbody>
            </table>
        </div>
    </div>
    
<?= $this->endSection() ?>