<?php
session_start();
include 'db.php';

$customer_id = $_SESSION['customer_id'];

// Ubah semua item pending ke status 'dipesan'
$pdo->prepare("UPDATE cart_items SET status = 'dipesan' WHERE customer_id = ? AND status = 'pending'")
    ->execute([$customer_id]);

// Redirect ke halaman konfirmasi
header("Location: confirm_order.php");
exit;
