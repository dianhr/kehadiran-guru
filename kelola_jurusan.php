<?php
session_start();
if ($_SESSION['role'] != 'admin') {
  header("Location: login.php");
  exit;
}
include 'koneksi.php';

if (isset($_POST['tambah'])) {
  $nama = $_POST['nama_jurusan'];
  $conn->query("INSERT INTO jurusan (nama_jurusan) VALUES ('$nama')");
  header("Location: kelola_jurusan.php");
}

if (isset($_GET['hapus'])) {
  $id = $_GET['hapus'];
  $conn->query("DELETE FROM jurusan WHERE id=$id");
  header("Location: kelola_jurusan.php");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Kelola Jurusan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
  <h3>Kelola Jurusan</h3>
  <a href="dashboard_admin.php" class="btn btn-secondary mb-3">← Kembali</a>
  <form method="POST" class="mb-3">
    <div class="input-group">
      <input type="text" name="nama_jurusan" class="form-control" placeholder="Nama Jurusan" required>
      <button type="submit" name="tambah" class="btn btn-primary">Tambah</button>
    </div>
  </form>
  <table class="table table-bordered">
    <thead><tr><th>#</th><th>Nama Jurusan</th><th>Aksi</th></tr></thead>
    <tbody>
      <?php
      $no = 1;
      $q = $conn->query("SELECT * FROM jurusan");
      while ($r = $q->fetch_assoc()) {
        echo "<tr>
          <td>".$no++."</td>
          <td>".$r['nama_jurusan']."</td>
          <td><a href='kelola_jurusan.php?hapus=".$r['id']."' onclick=\"return confirm('Hapus?')\" class='btn btn-danger btn-sm'>Hapus</a></td>
        </tr>";
      }
      ?>
    </tbody>
  </table>
</div>
</body>
</html>
