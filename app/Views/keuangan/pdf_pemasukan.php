<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Laporan Pemasukan</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            font-size: 12px;
        }

        th {
            background: #eee;
        }

        h3 {
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

    <h3>Laporan Pemasukan</h3>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Keterangan</th>
                <th>Jumlah</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($rows)) : ?>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?= $row['id_keuangan'] ?></td>
                        <td><?= $row['keterangan'] ?></td>
                        <td>Rp <?= number_format($row['pemasukan'], 0, ',', '.') ?></td>
                        <td><?= $row['tanggal'] ?></td>
                    </tr>
                <?php endforeach ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" style="text-align:center;">Tidak ada data</td>
                </tr>
            <?php endif ?>
        </tbody>
    </table>

</body>

</html>