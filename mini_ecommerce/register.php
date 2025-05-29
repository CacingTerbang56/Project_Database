<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
  <title>Daftar Akun</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f8f9fa;
      margin: 0;
      padding: 40px;
    }

    .container {
      max-width: 400px;
      margin: auto;
      background: #fff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .container h2 {
      text-align: center;
      margin-bottom: 20px;
    }

    .form-group {
      margin-bottom: 15px;
    }

    label {
      display: block;
      margin-bottom: 6px;
      font-weight: bold;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"],
    textarea {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
      box-sizing: border-box;
    }

    .button,
    button[type="submit"] {
      display: inline-block;
      padding: 10px 20px;
      background-color: #1976D2;
      color: white;
      border: none;
      border-radius: 6px;
      font-size: 16px;
      font-weight: bold;
      text-decoration: none;
      transition: background-color 0.3s ease;
      cursor: pointer;
    }

    .button:hover,
    button[type="submit"]:hover {
      background-color: #145ea8;
    }

    .form-actions {
      display: flex;
      justify-content: space-between;
      margin-top: 20px;
    }

    .success {
      margin-top: 20px;
      text-align: center;
      color: green;
    }
  </style>
</head>
<body>

  <div class="container">
    <h2>Form Registrasi Customer</h2>

    <form action="" method="POST">
      <div class="form-group">
        <label>Nama</label>
        <input type="text" name="name" required>
      </div>

      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" required>
      </div>

      <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" required>
      </div>

      <input type="hidden" name="role" value="customer">

      <div class="form-actions">
        <a class="button" href="index.php">Kembali</a>
        <button type="submit">Daftar</button>
      </div>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
      $name = $_POST['name'];
      $email = $_POST['email'];
      $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
      $role = 'customer';

      $stmt = $pdo->prepare("INSERT INTO customers (name, email, password, role) VALUES (?, ?, ?, ?)");
      $stmt->execute([$name, $email, $password, $role]);

      echo "<div class='success'>Akun berhasil dibuat. <a href='index.php'>Login</a></div>";
    }
    ?>
  </div>

</body>
</html>
