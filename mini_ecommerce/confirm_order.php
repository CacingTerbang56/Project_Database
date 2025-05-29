<?php
session_start();

include 'db.php';

$customer_id = $_SESSION['customer_id'];

// Ambil semua item yang statusnya masih pending atau sudah disetujui
$stmt = $pdo->prepare("
  SELECT ci.*, p.name AS product_name, p.price
  FROM cart_items ci
  JOIN products p ON ci.product_id = p.product_id
  WHERE ci.customer_id = ?
  AND ci.status IN ('pending', 'approved')
");
$stmt->execute([$customer_id]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Daftar Pesanan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
  <h1>📦 Daftar Pesanan Anda</h1>

  <?php if (empty($orders)): ?>
    <div class="alert alert-info">Belum ada pesanan.</div>
  <?php else: ?>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Produk</th>
          <th>Qty</th>
          <th>Harga</th>
          <th>Total</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php $grand_total = 0; ?>
        <?php foreach ($orders as $item): ?>
          <?php $total = $item['qty'] * $item['price']; ?>
          <tr>
            <td><?= htmlspecialchars($item['product_name']) ?></td>
            <td><?= $item['qty'] ?></td>
            <td>Rp <?= number_format($item['price'], 0, ',', '.') ?></td>
            <td>Rp <?= number_format($total, 0, ',', '.') ?></td>
            <td>
              <?php if ($item['status'] === 'pending'): ?>
                <span class="badge bg-warning text-dark">Menunggu Konfirmasi Admin</span>
              <?php elseif ($item['status'] === 'approved'): ?>
                <span class="badge bg-success">✅ Berhasil Dipesan</span>
              <?php endif; ?>
            </td>
          </tr>
          <?php $grand_total += $total; ?>
        <?php endforeach; ?>
        <tr>
          <th colspan="4">Total</th>
          <th>Rp <?= number_format($grand_total, 0, ',', '.') ?></th>
        </tr>
      </tbody>
    </table>
  <?php endif; ?>

  <a href="dashboard_customer.php" class="btn btn-secondary mt-3">⬅ Kembali ke Dashboard</a>
</div>
</body>
</html>
