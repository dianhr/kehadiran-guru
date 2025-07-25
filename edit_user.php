<?php
session_start();
if ($_SESSION['role'] != 'admin') {
  header("Location: login.php");
  exit;
}

include 'koneksi.php';

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM users WHERE id=$id");
$data = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit User</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
  <h2 class="mb-4">Edit User</h2>
  <a href="dashboard_admin.php" class="btn btn-secondary mb-3">Kembali</a>

  <div class="card shadow p-4">
    <form action="update_user.php" method="POST">
      <input type="hidden" name="id" value="<?= $data['id'] ?>">
      <div class="mb-3">
        <label class="form-label">Username</label>
        <input type="text" name="username" class="form-control" value="<?= $data['username'] ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="text" name="password" class="form-control" value="<?= $data['password'] ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Role</label>
        <select name="role" class="form-select" required>
          <option value="admin" <?= $data['role']=='admin' ? 'selected' : '' ?>>Admin</option>
          <option value="guru" <?= $data['role']=='guru' ? 'selected' : '' ?>>Guru</option>
          <option value="siswa" <?= $data['role']=='siswa' ? 'selected' : '' ?>>Siswa</option>
        </select>
      </div>
      <button type="submit" class="btn btn-success">Update</button>
    </form>
  </div>
</div>
</body>
</html>
