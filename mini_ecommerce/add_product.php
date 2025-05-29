<?php
include 'db.php';

// Inisialisasi array agar tidak error saat pertama kali dibuka
$products = [];

// Tambah produk baru
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    $sql = "INSERT INTO products (name, description, price, stock) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$name, $desc, $price, $stock]);
}

// Tambah stok produk & catat ke log
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah_qty'])) {
    $product_id = $_POST['product_id'];
    $tambah_qty = intval($_POST['tambah_qty']);

    // Tambah stok
    $stmt = $pdo->prepare("UPDATE products SET stock = stock + ? WHERE product_id = ?");
    $stmt->execute([$tambah_qty, $product_id]);

    // Tambah log masuk
    $log = $pdo->prepare("INSERT INTO product_logs (product_id, type, qty, description) VALUES (?, 'masuk', ?, 'Penambahan stok manual oleh admin')");
    $log->execute([$product_id, $tambah_qty]);
}


// Ambil produk
$stmt = $pdo->query("SELECT * FROM products");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f9fa;
            margin: 40px;
        }
        .container {
            display: flex;
            gap: 50px;
        }
        .form-section, .table-section {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        input[type=text], input[type=number], textarea {
            width: 100%;
            padding: 10px;
            margin-top: 8px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        button {
            background-color: teal;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        button:hover {
            background-color: #006666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
        }
        th {
            background-color: #007B7F;
            color: white;
        }
        .stock-empty {
            color: red;
            font-weight: bold;
        }
        .button-back{
            display: inline-block;
            padding: 10px 20px;
            background-color: #1976D2;;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s ease;
            margin-bottom: 20px;
        }
        .button-back:hover{
            background-color: #1976D2;
        }
    </style>

</head>
<body>

<h2><a href="dashboard_admin.php" class="button-back" >Kembali</a></h2>

<div class="container">

    <!-- Form Tambah Produk -->
    <div class="form-section" style="flex:1">
        <h2>Tambah Produk</h2>
        <form method="POST">
            <input type="hidden" name="add_product" value="1">
            <label>Nama Produk:</label>
            <input type="text" name="name" required>

            <label>Deskripsi:</label>
            <textarea name="description" required></textarea>

            <label>Harga:</label>
            <input type="number" name="price" required>

            <label>Stok:</label>
            <input type="number" name="stock" required>

            <button type="submit">Simpan Produk</button>
        </form>
    </div>

    <!-- Tabel Produk -->
    <div class="table-section" style="flex:2">
        <h2>Daftar Produk</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Ubah Qty</th>
                <th>Status</th>
            </tr>
            <?php foreach ($products as $p): ?>
            <tr>
                <td><?= $p['product_id'] ?></td>
                <td><?= htmlspecialchars($p['name']) ?></td>
                <td><?= number_format($p['price'], 2) ?></td>
                <td><?= $p['stock'] ?></td>
                <td>
                    <form method="POST" style="display: flex; gap:5px">
                       <input type="hidden" name="product_id" value="<?= $p['product_id'] ?>">
                        <input type="number" name="tambah_qty" placeholder="+Qty" min="1" style="width: 70px;" required>
                        <button type="submit">Tambah</button>

                    </form>
                </td>
                <td>
                    <?php if ($p['stock'] == 0): ?>
                        <span class="stock-empty">Stok Kosong</span>
                    <?php else: ?>
                        Tersedia
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

</div>

</body>
</html>
