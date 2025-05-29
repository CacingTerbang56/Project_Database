<?php 
session_start(); 

// Cek role
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { 
  die('Akses ditolak');
}

// Ambil nama dari session atau set default
$name = isset($_SESSION['name']) ? $_SESSION['name'] : 'Admin';
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

  <div class="container py-5">
    <div class="card shadow-lg">
      <div class="card-body">
        <h2 class="card-title mb-4">Halo, <?= htmlspecialchars($name) ?>! 👋</h2>
        <p class="lead">Selamat datang di <strong>Mini E-Commerce</strong>. Silakan pilih menu:</p>
        
        <ul class="list-group list-group-flush">
          <li class="list-group-item">
            <a href="add_product.php" class="btn btn-primary w-100">🛒 Tambah Produk</a>
          </li>
          <li class="list-group-item">
            <a href="transactions.php" class="btn btn-secondary w-100">📊 Lihat Detail Transaksi</a>
          </li>
          <li class="list-group-item">
            <a href="log.php" class="btn btn-success w-100">📦 Log Produk</a>
          </li>
          <li class="list-group-item">
            <a href="logout.php" class="btn btn-danger w-100">🚪 Logout</a>
          </li>
        </ul>
      </div>
    </div>
  </div>

</body>
</html>
