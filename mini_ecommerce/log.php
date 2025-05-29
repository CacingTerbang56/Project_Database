<?php
$koneksi = new mysqli("localhost", "root", "", "mini_ecommerce");

// Cek koneksi
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Log Keluar-Masuk Produk</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        h2 { color: #333; }
        table { border-collapse: collapse; width: 100%; margin-top: 15px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; }
        .masuk { color: green; font-weight: bold; }
        .keluar { color: red; font-weight: bold; }

        .button {
        display: inline-block;
        padding: 10px 20px;
        background-color: #2196F3;
        color: white;
        text-decoration: none;
        border-radius: 6px;
        font-size: 16px;
        font-weight: bold;
        transition: background-color 0.3s ease;
        margin-bottom: 20px;
        }

        .button:hover {
            background-color: #1976D2;
        }

    </style>
</head>
<body>

<h2>📈 Log Keluar-Masuk Produk</h2>
<a href="dashboard_admin.php" class="button">Kembali </a>

<table>
    <thead>
        <tr>
            <th>Waktu</th>
            <th>Nama Produk</th>
            <th>Jenis</th>
            <th>Qty</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
    <?php
    $query = "
        SELECT l.created_at, p.name AS product_name, l.type, l.qty, l.description
        FROM product_logs l
        JOIN products p ON l.product_id = p.product_id
        ORDER BY l.created_at DESC
    ";
    $result = $koneksi->query($query);

    if ($result->num_rows > 0):
        while ($log = $result->fetch_assoc()):
    ?>
        <tr>
            <td><?= $log['created_at'] ?></td>
            <td><?= htmlspecialchars($log['product_name']) ?></td>
            <td class="<?= $log['type'] ?>"><?= ucfirst($log['type']) ?></td>
            <td><?= $log['qty'] ?></td>
            <td><?= htmlspecialchars($log['description']) ?></td>
        </tr>
    <?php
        endwhile;
    else:
    ?>
        <tr><td colspan="5">Belum ada log tercatat.</td></tr>
    <?php endif; ?>
    </tbody>
</table>

</body>
</html>
