<?php
session_start();
include 'db.php';
// session
$customer_id = $_SESSION['customer_id'] ;

// Validasi input
if (!isset($_POST['qty']) || !is_array($_POST['qty'])) {
    header("Location: products.php?error=Kuantitas tidak valid");
    exit();
}

$qtys = $_POST['qty'];
$error_messages = [];

// Ambil semua produk
$stmt = $pdo->query("SELECT * FROM products");
$all_products = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $all_products[$row['product_id']] = $row;
}

// Masukkan ke keranjang
foreach ($qtys as $product_id => $qty) {
    $qty = (int)$qty;
    if ($qty <= 0) continue;

    if (!isset($all_products[$product_id])) {
        $error_messages[] = "Produk ID $product_id tidak ditemukan.";
        continue;
    }

    $product = $all_products[$product_id];
    if ($qty > $product['stock']) {
        $error_messages[] = "Stok tidak mencukupi untuk produk: {$product['name']}.";
        continue;
    }

    // Cek apakah item sudah ada di keranjang (status pending)
    $stmt = $pdo->prepare("SELECT * FROM cart_items WHERE customer_id = ? AND product_id = ? AND status = 'pending'");
    $stmt->execute([$customer_id, $product_id]);
    $existing = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existing) {
        // Update qty jika sudah ada
        $new_qty = $existing['qty'] + $qty;
        $stmt = $pdo->prepare("UPDATE cart_items SET qty = ? WHERE cart_id = ?");
        $stmt->execute([$new_qty, $existing['cart_id']]);
    } else {
        // Tambah baru
        $stmt = $pdo->prepare("INSERT INTO cart_items (customer_id, product_id, qty, status) VALUES (?, ?, ?, 'pending')");
        $stmt->execute([$customer_id, $product_id, $qty]);
    }
}

if (!empty($error_messages)) {
    // Redirect kembali dengan pesan error
    $error = implode(', ', $error_messages);
    header("Location: products.php?error=" . urlencode($error));
    exit();
}

header("Location: view_cart.php");
exit();
