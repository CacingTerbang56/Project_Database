<?php
session_start();

include 'db.php';

$customer_id = $_SESSION['customer_id'];
$stmt = $pdo->query("SELECT * FROM products");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Daftar Produk</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
  <h1 class="mb-4">🛒 Daftar Produk</h1>
  <a href="dashboard_customer.php" class="btn btn-secondary mb-4">⬅ Kembali</a>

  <?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
  <?php endif; ?>

  <form action="add_to_cart.php" method="POST">
    <div class="row row-cols-1 row-cols-md-3 g-4">
      <?php foreach ($products as $p): ?>
        <div class="col">
          <div class="card h-100">
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($p['name']) ?></h5>
              <p class="card-text"><?= htmlspecialchars($p['description']) ?></p>
              <p class="card-text fw-bold">Rp <?= number_format($p['price'], 2, ',', '.') ?></p>
              <p class="card-text">Stok: <?= $p['stock'] ?></p>
              <?php if ($p['stock'] > 0): ?>
                <div class="mb-2">
                  <label for="qty_<?= $p['product_id'] ?>">Qty:</label>
                  <input type="number" class="form-control" name="qty[<?= $p['product_id'] ?>]" id="qty_<?= $p['product_id'] ?>" min="1" max="<?= $p['stock'] ?>">
                </div>
              <?php else: ?>
                <p class="text-danger">Stok habis</p>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="mt-4">
      <button type="submit" class="btn btn-primary">🛒 Tambahkan ke Keranjang</button>
      <a href="view_cart.php" class="btn btn-success">🧺 Lihat Keranjang</a>
    </div>
  </form>
</div>
</body>
</html>
