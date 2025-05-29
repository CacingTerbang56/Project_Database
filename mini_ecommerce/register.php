<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <title>Daftar Akun</title>
</head>
<style>

</style>
<body>
  <h2>Form Registrasi Untuk Menjadi Customer</h2>
  <form action="" method="POST">
    Nama: <input type="text" name="name" required><br>
    Email: <input type="email" name="email" required><br>
    Password: <input type="password" name="password" required><br>
    <input type="hidden" name="role" value="customer">
    <button type="submit">Daftar</button>
  </form>

  <?php
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'customer';

    $stmt = $pdo->prepare("INSERT INTO customers (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $email, $password, $role]);

    echo "Akun berhasil dibuat. <a href='index.php'>Login</a>";
  }
  ?>
</body>
</html>
