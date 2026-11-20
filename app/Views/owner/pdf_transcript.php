<!DOCTYPE html>
<html>
<head>
    <title><?= $title ?></title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #2d8659; padding-bottom: 10px; }
        .header h2 { margin: 0; color: #2d8659; }
        .meta { text-align: center; margin-bottom: 30px; font-style: italic; color: #666; }
        
        .section-title { font-size: 14px; font-weight: bold; margin-top: 25px; margin-bottom: 10px; border-left: 5px solid #2d8659; padding-left: 10px; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        
        .kpi-box { width: 100%; margin-bottom: 20px; }
        .kpi-item { display: inline-block; width: 30%; background: #e8f5e9; padding: 15px; border-radius: 5px; text-align: center; margin-right: 2%; }
        .kpi-value { font-size: 16px; font-weight: bold; color: #2d8659; display: block; margin-top: 5px; }
        
        .text-danger { color: #e74a3b; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
    </style>
</head>
<body>

    <div class="header">
        <h2>TRANSKRIP STATUS TOKO - UD. THJ</h2>
    </div>

    <div class="meta">
        Periode Laporan: <strong><?= $periode ?></strong> <br>
        Dicetak pada: <?= date('d F Y H:i:s') ?>
    </div>

    <div class="kpi-box">
        <div class="kpi-item">
            <span>Total Pendapatan</span>
            <span class="kpi-value">Rp <?= number_format($totalPendapatan, 0, ',', '.') ?></span>
        </div>
        <div class="kpi-item">
            <span>Keuntungan Bersih</span>
            <span class="kpi-value">Rp <?= number_format($keuntunganBersih, 0, ',', '.') ?></span>
        </div>
        <div class="kpi-item">
            <span>Total Transaksi</span>
            <span class="kpi-value"><?= number_format($totalTransaksi, 0, ',', '.') ?></span>
        </div>
    </div>

    <div class="section-title">Peringatan Stok Menipis (< 10 Unit)</div>
    <?php if(!empty($lowStock)): ?>
        <table>
            <thead>
                <tr>
                    <th width="50%">Nama Produk</th>
                    <th width="30%">Kode</th>
                    <th width="20%">Sisa Stok</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($lowStock as $row): ?>
                <tr>
                    <td><?= esc($row['nama_produk']) ?></td>
                    <td><?= esc($row['kode_produk']) ?></td>
                    <td class="text-danger"><strong><?= $row['stok'] ?></strong></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p><i>Tidak ada stok yang menipis saat ini.</i></p>
    <?php endif; ?>

    <div class="section-title">Analisis Barang Mengendap (> 90 Hari)</div>
    <?php if(!empty($deadStock)): ?>
        <table>
            <thead>
                <tr>
                    <th>Nama Produk</th>
                    <th>Stok Gudang</th>
                    <th class="text-right">Potensi Kerugian (Aset Diam)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($deadStock as $row): ?>
                <tr>
                    <td><?= esc($row['nama_produk']) ?></td>
                    <td class="text-center"><?= $row['stok'] ?></td>
                    <td class="text-right">Rp <?= number_format($row['stok'] * $row['harga_jual'], 0, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p><i>Tidak ada indikasi barang mati (dead stock).</i></p>
    <?php endif; ?>

    <div class="section-title">10 Pelanggan Paling Loyal</div>
    <table>
        <thead>
            <tr>
                <th>Nama Pelanggan</th>
                <th class="text-center">Jml Transaksi</th>
                <th class="text-right">Total Belanja</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($topCustomers as $cust): ?>
            <tr>
                <td><?= esc($cust['nama_pelanggan']) ?></td>
                <td class="text-center"><?= $cust['total_transaksi'] ?></td>
                <td class="text-right">Rp <?= number_format($cust['total_belanja'], 0, ',', '.') ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>