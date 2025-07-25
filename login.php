<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Login - Portal Sekolah</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body, html {
      height: 100%;
      margin: 0;
      background: url('mading.jpg') no-repeat center center fixed;
      background-size: cover;
    }
    .overlay {
      position: absolute;
      top: 0; left: 0; right: 0; bottom: 0;
      background: rgba(0, 0, 0, 0.5);
    }
    .login-container {
      position: relative;
      z-index: 2;
    }
    .login-card {
      background: rgba(255, 255, 255, 0.9);
      border-radius: 10px;
      padding: 30px;
      max-width: 400px;
      margin: auto;
      margin-top: 10%;
      box-shadow: 0 4px 10px rgba(0,0,0,0.3);
    }
    .login-card h3 {
      margin-bottom: 20px;
    }
  </style>
</head>
<body>
  <div class="overlay"></div>
  <div class="container login-container">
    <div class="login-card text-center">
      <h3 class="mb-3">Selamat Datang di Manajemen PBM SMKN 1 Cikampek</h3>
      <p class="text-muted mb-4">Silakan login untuk melanjutkan</p>
      <form action="cek_login.php" method="POST">
        <div class="form-floating mb-3">
          <input type="text" name="username" class="form-control" placeholder="Username" required>
          <label>Username</label>
        </div>
        <div class="form-floating mb-3">
          <input type="password" name="password" class="form-control" placeholder="Password" required>
          <label>Password</label>
        </div>
        <button type="submit" class="btn btn-primary w-100">Login</button>
      </form>
    </div>
  </div>
</div> <!-- penutup .container -->
<footer class="bg-dark text-white text-center py-3 mt-5">
  Dirancang dan dibuat oleh Tim Kurikulum SMKN 1 Cikampek © <?= date('Y') ?>
</footer>
</body>
</html>



</body>
</html>
