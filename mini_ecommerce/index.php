<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
  <title>E-Commerce Login</title>
  <style>
    body { font-family: sans-serif; background: #f4f4f4; display: flex; justify-content: center; align-items: center; height: 100vh; }
    .box { background: white; padding: 40px; box-shadow: 0 0 10px rgba(0,0,0,0.1); border-radius: 10px; width: 300px; }
    h2 { text-align: center; }
    input[type=text], input[type=password] { width: 100%; padding: 10px; margin: 10px 0; }
    button { width: 100%; padding: 10px; background: teal; color: white; border: none; }
    a { text-decoration: none; display: block; text-align: center; margin-top: 10px; }
  </style>
</head>
<body>
  <div class="box">
    <h2>Login</h2>
    <form action="login.php" method="POST">
      <input type="text" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit">Masuk</button>
    </form>
    <a href="register.php">Belum punya akun?</a>
  </div>
</body>
</html>