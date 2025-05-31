<?php
session_start();
include 'db.php';

$email = $_POST['email'];
$password = $_POST['password'];

// Login sebagai admin (hardcoded)
if ($email === 'admin@gmail.com' && $password === '123') {
  $_SESSION['user_id'] = 0;
  $_SESSION['role'] = 'admin';
  header("Location: dashboard_admin.php");
  exit;
}

// Login sebagai customer dari database
$stmt = $pdo->prepare("SELECT * FROM customers WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password']) && $user['role'] === 'customer') {
  $_SESSION['customer_id'] = $user['customer_id'];
  $_SESSION['role'] = 'customer';
  $_SESSION['name'] = $user['name'];
  header("Location: dashboard_customer.php");
  exit;
} else {
  header("Location: index.php?error=gagal");
  exit;
}
