<?php
session_start();
if ($_SESSION['role'] != 'admin') {
  header("Location: login.php");
  exit;
}
include 'koneksi.php';

$id = $_GET['id'];
$q = $conn->query("SELECT * FROM kelas WHERE id_kelas = '$id'");
$data = $q->fetch_assoc();

if (isset($_POST['update'])) {
  $nama_kelas = $_POST['nama_kelas'];
  $wali_kelas = $_POST['wali_kelas'];
  $conn->query("UPDATE kelas SET nama_kelas='$nama_kelas', wali_kelas='$wali_kelas' WHERE id_kelas='$id'");
  header("Location: kelola_kelas.php");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Kelas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-4">
    <h2>Edit Kelas</h2>
    <form method="post">
      <div class="mb-3">
        <label>Nama Kelas</label>
        <input type="text" name="nama_kelas" value="<?= $data['nama_kelas'] ?>" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Wali Kelas</label>
        <input type="text" name="wali_kelas" value="<?= $data['wali_kelas'] ?>" class="form-control">
      </div>
      <button type="submit" name="update" class="btn btn-primary">Update</button>
      <a href="kelola_kelas.php" class="btn btn-secondary">Kembali</a>
    </form>
  </div>
</body>
</html>
