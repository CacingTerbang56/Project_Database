<?php
$koneksi = new mysqli("localhost", "root", "", "mini_ecommerce");

// Setujui transaksi pelanggan
if (isset($_GET['approve_customer_id'])) {
    $cid = intval($_GET['approve_customer_id']);

    // Ambil semua item pending untuk pelanggan tersebut
    $result = $koneksi->query("SELECT product_id, qty FROM cart_items WHERE customer_id=$cid AND status='pending'");
    while ($item = $result->fetch_assoc()) {
        $product_id = $item['product_id'];
        $qty = $item['qty'];

        // Kurangi stok produk
        $koneksi->query("UPDATE products SET stock = stock - $qty WHERE product_id = $product_id");

        // Ubah status menjadi approved
        $koneksi->query("UPDATE cart_items SET status='approved' WHERE customer_id=$cid AND product_id=$product_id AND status='pending'");
    }

    echo "<script>alert('Transaksi pelanggan telah disetujui!'); window.location='transactions.php';</script>";
    exit;
}

// Tolak transaksi pelanggan
if (isset($_GET['reject_customer_id'])) {
    $cid = intval($_GET['reject_customer_id']);
    $koneksi->query("DELETE FROM cart_items WHERE customer_id=$cid AND status='pending'");
    echo "<script>alert('Transaksi pelanggan ditolak.'); window.location='transactions.php';</script>";
    exit;
}

// Ambil daftar pelanggan dengan transaksi pending
$query = "
    SELECT ci.customer_id, cu.name, cu.email, COUNT(*) as total_item 
    FROM cart_items ci
    JOIN customers cu ON ci.customer_id = cu.customer_id
    WHERE ci.status = 'pending'
    GROUP BY ci.customer_id
    ORDER BY ci.customer_id DESC
";
$result = $koneksi->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Daftar Transaksi Pelanggan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 20px;
        }

        h2 a {
            text-decoration: none;
            color: #555;
        }

        h2 {
            color: #333;
        }

        .transaction-box {
            background: #fff;
            border: 1px solid #ddd;
            border-left: 5px solid #4CAF50;
            padding: 15px 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .transaction-box strong {
            color: #444;
        }

        .approve-btn, .reject-btn {
            display: inline-block;
            margin-top: 10px;
            padding: 6px 12px;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
        }

        .approve-btn {
            background-color: #4CAF50;
        }

        .approve-btn:hover {
            background-color: #45a049;
        }

        .reject-btn {
            background-color: #f44336;
        }

        .reject-btn:hover {
            background-color: #c0392b;
        }

        .transaction-box ul {
            list-style-type: disc;
            padding-left: 20px;
        }

        .transaction-box li {
            margin-bottom: 5px;
            color: #333;
        }

        .transaction-box h4 {
            margin-top: 15px;
            color: #555;
        }

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

<h2><a href="dashboard_admin.php" class="button">Kembali</a></h2>
<h2>Daftar Transaksi Pelanggan (Pending)</h2>

<?php if ($result->num_rows === 0): ?>
    <p>Tidak ada transaksi pending saat ini.</p>
<?php endif; ?>

<?php while ($row = $result->fetch_assoc()): ?>
    <div class="transaction-box">
        <strong>Nama:</strong> <?= htmlspecialchars($row['name']) ?><br>
        <strong>Email:</strong> <?= htmlspecialchars($row['email']) ?><br>
        <strong>Total Item Pending:</strong> <?= $row['total_item'] ?><br>

        <a class="approve-btn" href="?approve_customer_id=<?= $row['customer_id'] ?>" onclick="return confirm('Setujui semua transaksi pelanggan ini?')">✅ Setujui Transaksi</a>
        <a class="reject-btn" href="?reject_customer_id=<?= $row['customer_id'] ?>" onclick="return confirm('Tolak dan hapus semua transaksi pelanggan ini?')">❌ Tolak Transaksi</a>

        <h4>Detail Produk (Pending):</h4>
        <ul>
        <?php
        $cid = $row['customer_id'];
        $detail = $koneksi->query("
            SELECT p.name AS product_name, p.price, ci.qty 
            FROM cart_items ci
            JOIN products p ON ci.product_id = p.product_id
            WHERE ci.customer_id = $cid AND ci.status = 'pending'
        ");
        while ($item = $detail->fetch_assoc()):
        ?>
            <li>
                <?= htmlspecialchars($item['product_name']) ?> - <?= $item['qty'] ?> x Rp<?= number_format($item['price']) ?> 
                = Rp<?= number_format($item['price'] * $item['qty']) ?>
            </li>
        <?php endwhile; ?>
        </ul>
    </div>
<?php endwhile; ?>

</body>
</html>
