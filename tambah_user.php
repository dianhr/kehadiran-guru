<?php
session_start();
if ($_SESSION['role'] != 'admin') {
  header("Location: login.php");
  exit;
}

include 'koneksi.php';

// Ambil jurusan & kelas untuk dropdown
$qJurusan = $conn->query("SELECT * FROM jurusan");
$qKelas = $conn->query("SELECT * FROM kelas");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah User</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
  <h2 class="mb-4">Tambah User</h2>
  <a href="dashboard_admin.php" class="btn btn-secondary mb-3">← Kembali</a>

  <!-- Tambah User Manual -->
  <div class="card shadow p-4 mb-4">
    <h5>Tambah User Manual</h5>
    <form action="insert_user.php" method="POST">
      <div class="mb-3">
        <label class="form-label">Username</label>
        <input type="text" name="username" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="text" name="password" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Role</label>
        <select name="role" class="form-select" required>
          <option value="admin">Admin</option>
          <option value="guru">Guru</option>
          <option value="siswa">Siswa</option>
        </select>
      </div>

      <!-- Tambah jurusan & kelas -->
      <div class="mb-3">
        <label class="form-label">Jurusan</label>
        <select name="jurusan_id" class="form-select">
          <option value="">- Pilih Jurusan -</option>
          <?php
          $result = $conn->query("SELECT * FROM jurusan");
          while ($row = $result->fetch_assoc()) {
            echo "<option value='".$row['id']."'>".$row['nama_jurusan']."</option>";
          }
          ?>
        </select>
      </div>
      <div class="mb-3">
        <label class="form-label">Kelas</label>
        <select name="kelas_id" class="form-select">
          <option value="">- Pilih Kelas -</option>
          <?php
          $result = $conn->query("SELECT * FROM kelas");
          while ($row = $result->fetch_assoc()) {
            echo "<option value='".$row['id']."'>".$row['nama_kelas']."</option>";
          }
          ?>
        </select>
      </div>

      <button type="submit" class="btn btn-primary">Tambah</button>
    </form>
  </div>


<!-- Import User dari Excel Multi Role -->
<div class="card shadow p-4">
  <h5>Import User Multi Role dari Excel (.xlsx)</h5>
  <p class="text-muted mb-2">Format: username, password, role (admin/guru/siswa), jurusan_id, kelas_id</p>
  <form action="import_user_excel.php" method="POST" enctype="multipart/form-data">
    <div class="mb-3">
      <input type="file" name="file_excel" class="form-control" accept=".xlsx,.xls" required>
    </div>
    <button type="submit" name="import_excel" class="btn btn-success">Import User</button>
    <a href="download_template_user_excel.php" class="btn btn-outline-primary mt-2">Download Template Excel</a>
  </form>
</div>















  <!-- Import Siswa CSV -->
  <div class="card shadow p-4">
    <h5>Import Siswa dari File CSV</h5>
    <p class="text-muted mb-2">Format: username,password,jurusan_id,kelas_id</p>
          
agus1,agus1,1,2 <br>
agus2,agus2,1,2 <br>
agus3,agus3,1,3 <br>

    <form action="import_siswa.php" method="POST" enctype="multipart/form-data">
      <div class="mb-3">
        <input type="file" name="file_csv" class="form-control" accept=".csv" required>
      </div>
      <button type="submit" name="import" class="btn btn-success">Import Siswa</button>
    </form>
  </div>

</div>
</body>
</html>
