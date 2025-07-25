<?php
session_start();
if ($_SESSION['role'] != 'siswa') {
  header("Location: login.php");
  exit;
}

include 'koneksi.php';

$username = $_SESSION['username'];

// Cek jika sudah ada biodata
$query = $conn->query("SELECT * FROM biodata_siswa WHERE username = '$username'");
$data = $query->fetch_assoc();

if (isset($_POST['simpan'])) {
  $nama = $_POST['nama'];
  $nis = $_POST['nis'];
  $alamat = $_POST['alamat'];
  $telepon = $_POST['telepon'];

  // Handle foto
  $foto = $data['foto'] ?? null;
  if ($_FILES['foto']['name']) {
    $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
    $filename = $username . "_" . time() . "." . $ext;
    move_uploaded_file($_FILES['foto']['tmp_name'], "uploads/" . $filename);
    $foto = $filename;
  }

  if ($data) {
    $sql = "UPDATE biodata_siswa SET nama='$nama', nis='$nis', alamat='$alamat', telepon='$telepon', foto='$foto' WHERE username='$username'";
  } else {
    $sql = "INSERT INTO biodata_siswa (username, nama, nis, alamat, telepon, foto) VALUES ('$username', '$nama', '$nis', '$alamat', '$telepon', '$foto')";
  }

  if ($conn->query($sql)) {
    echo "<script>alert('Biodata berhasil disimpan!'); window.location.href='dashboard_siswa.php';</script>";
  } else {
    echo "Error: " . $conn->error;
  }
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Input Biodata Siswa</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="dashboard_siswa.php">Dashboard Siswa</a>
  </div>
</nav>

<main class="container py-5">
  <h2 class="mb-4">Input Biodata Siswa</h2>

  <form method="POST" class="card shadow p-4">
    <div class="mb-3">
      <label class="form-label">Nama Lengkap</label>
      <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($data['nama'] ?? '') ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">NIS</label>
      <input type="text" name="nis" class="form-control" value="<?= htmlspecialchars($data['nis'] ?? '') ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Alamat</label>
      <textarea name="alamat" class="form-control" rows="3" required><?= htmlspecialchars($data['alamat'] ?? '') ?></textarea>
    </div>

    <div class="mb-3">
      <label class="form-label">No Telepon</label>
      <input type="text" name="telepon" class="form-control" value="<?= htmlspecialchars($data['telepon'] ?? '') ?>">
    </div>
    
    <form method="POST" enctype="multipart/form-data" class="card shadow p-4">
        <div class="mb-3">
    <label class="form-label">Upload Foto</label>
    <input type="file" name="foto" class="form-control" accept=".jpg,.jpeg,.png,.gif">
    </div>

    <?php if (!empty($data['foto'])): ?>
    <div class="mb-3">
        <img src="uploads/<?= htmlspecialchars($data['foto']) ?>" alt="Foto Siswa" width="150" class="img-thumbnail">
    </div>
    <?php endif; ?>


    <button type="submit" name="simpan" class="btn btn-primary">Simpan Biodata</button>
    <a href="dashboard_siswa.php" class="btn btn-secondary ms-2">Kembali</a>
  </form>
</main>

<footer class="bg-dark text-white text-center py-3">
  Dirancang & dibuat oleh Tim Kurikulum SMKN 1 Cikampek © <?= date('Y') ?>
</footer>

</body>
</html>
