<?php
$host = 'localhost';
$db   = 'mini_ecommerce';
$user = 'root';
$pass = '';
$dsn  = "mysql:host=$host;dbname=$db;charset=utf8mb4";
try {
  $pdo = new PDO($dsn, $user, $pass);
} catch (PDOException $e) {
  exit('Connection failed: ' . $e->getMessage());
}
?>