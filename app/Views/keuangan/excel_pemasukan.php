<table border="1">
    <tr>
        <th>ID</th>
        <th>Keterangan</th>
        <th>Jumlah</th>
        <th>Tanggal</th>
    </tr>

    <?php foreach ($data as $row): ?>
        <tr>
            <td><?= $row['id_keuangan']; ?></td>
            <td><?= $row['keterangan']; ?></td>
            <td><?= $row['jumlah']; ?></td>
            <td><?= $row['created_at']; ?></td>
        </tr>
    <?php endforeach; ?>
</table>