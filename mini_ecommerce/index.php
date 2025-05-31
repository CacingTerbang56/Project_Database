<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    body {
      background-color: #f8f9fa;
    }
    .login-container {
      max-width: 400px;
      margin: 100px auto;
      padding: 30px;
      background-color: white;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .regis{
      text-align:center;
    }
  </style>
</head>
<body>

<div class="login-container">
  <h3 class="text-center mb-4">Login</h3>
  <form action="login.php" method="POST">
    <div class="mb-3">
      <label for="email" class="form-label">Email</label>
      <input type="email" class="form-control" name="email" id="email" required>
    </div>
    <div class="mb-3">
      <label for="password" class="form-label">Kata Sandi</label>
      <input type="password" class="form-control" name="password" id="password" required>
    </div>
    <div class="d-grid">
      <button type="submit" class="btn btn-primary">Masuk</button>
    </div>
    <div class="regis">
      <a href="register.php">belum punya akun</a>
    </div>
  </form>
</div>

<?php if (isset($_GET['error']) && $_GET['error'] === 'gagal'): ?>
<script>
  Swal.fire({
    icon: 'error',
    title: 'Login Gagal!',
    text: 'Email atau password salah.',
    confirmButtonText: 'Coba Lagi',
    backdrop: true
  });
</script>
<?php endif; ?>

</body>
</html>
