<?php
session_start();
include 'db.php';
$email = $_POST['email'];
$password = $_POST['password'];

// Cek login sebagai admin (hardcoded)
if ($email === 'admin@gmail.com' && $password === '123') {
  $_SESSION['user_id'] = 0; // Bisa dibiarkan 0 karena tidak dari DB
  $_SESSION['role'] = 'admin';
  header("Location: dashboard_admin.php");
  exit;
}

// Jika bukan admin, cek database
$stmt = $pdo->prepare("SELECT * FROM customers WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password']) && $user['role'] === 'customer') {
  $_SESSION['customer_id'] = $user['customer_id'];
  $_SESSION['role'] = 'customer';
  $_SESSION['name'] = $user['name'];
  header("Location: dashboard_customer.php");
} else {
  echo "Login gagal. <a href='index.php'>Kembali</a>";
}
?>