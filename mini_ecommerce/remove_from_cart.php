<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    die('Akses ditolak');
}

$cart_id = $_GET['id'];
$stmt = $pdo->prepare("DELETE FROM cart WHERE cart_id = ?");
$stmt->execute([$cart_id]);

header("Location: view_cart.php");
exit;
