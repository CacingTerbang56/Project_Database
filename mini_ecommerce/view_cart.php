<?php
session_start();
include 'db.php';

$customer_id = $_SESSION['customer_id'];

$stmt = $pdo->prepare("
  SELECT ci.*, p.name AS product_name, p.price
  FROM cart_items ci
  JOIN products p ON ci.product_id = p.product_id
  WHERE ci.customer_id = ? AND ci.status = 'pending'
");
$stmt->execute([$customer_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Keranjang Belanja</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
  <h1>🧺 Keranjang Belanja</h1>

  <?php if (empty($cart_items)): ?>
    <div class="alert alert-info">Keranjang kosong.</div>
  <?php else: ?>
    <form method="POST" action="confirm_order.php">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Produk</th>
            <th>Qty</th>
            <th>Harga</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody>
          <?php $grand_total = 0; ?>
          <?php foreach ($cart_items as $item): ?>
            <?php $total = $item['qty'] * $item['price']; ?>
            <tr>
              <td><?= htmlspecialchars($item['product_name']) ?></td>
              <td><?= $item['qty'] ?></td>
              <td>Rp <?= number_format($item['price'], 0, ',', '.') ?></td>
              <td>Rp <?= number_format($total, 0, ',', '.') ?></td>
            </tr>
            <?php $grand_total += $total; ?>
          <?php endforeach; ?>
          <tr>
            <th colspan="3">Total</th>
            <th>Rp <?= number_format($grand_total, 0, ',', '.') ?></th>
          </tr>
        </tbody>
      </table>
      <button type="submit" class="btn btn-success">Pesan Sekarang</button>
    </form>
  <?php endif; ?>

  <a href="dashboard_customer.php" class="btn btn-secondary mt-3">⬅ Kembali</a>
</div>
</body>
</html>
