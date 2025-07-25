<?php
session_start();
if ($_SESSION['role'] != 'guru') {
  header("Location: login.php");
  exit;
}

include 'koneksi.php';
$mapel_list = $conn->query("SELECT * FROM mata_pelajaran ORDER BY nama_mapel");

$nama_guru = $_SESSION['username'];

// Proses jika disubmit
if (isset($_POST['submit'])) {
  $ruangan = $_POST['ruangan'];
  $mapel = $_POST['mapel'];
  $jam_masuk = $_POST['jam_masuk'];
  $jam_keluar = $_POST['jam_keluar'];
  $status = $_POST['status'];
  $keterangan = $_POST['keterangan'];

  // Upload file tugas jika ada
  $tugas = null;
  if ($_FILES['tugas']['name']) {
    $filename = time() . "_" . $_FILES['tugas']['name'];
    move_uploaded_file($_FILES['tugas']['tmp_name'], "uploads/" . $filename);
    $tugas = $filename;
  }

  $sql = "INSERT INTO kehadiran_guru13 (ruangan_kelas, nama_guru, mata_pelajaran, jam_masuk, jam_keluar, status_kehadiran, tugas, keterangan_tugas)
          VALUES ('$ruangan', '$nama_guru', '$mapel', '$jam_masuk', '$jam_keluar', '$status', '$tugas', '$keterangan')";
  $conn->query($sql);

  echo "<script>alert('Data kehadiran berhasil disimpan!'); window.location.href='dashboard.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Input Kehadiran & Tugas</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    html, body {
      height: 100%;
    }
    body {
      display: flex;
      flex-direction: column;
    }
    main {
      flex: 1;
    }
  </style>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="#">Portal Guru</a>
    <div class="d-flex">
      <span class="navbar-text text-white">👋 Halo, <?= htmlspecialchars($nama_guru) ?></span>
      <a href="logout.php" class="btn btn-outline-light btn-sm ms-3">Logout</a>
    </div>
  </div>
</nav>

<main class="container py-5">
  <h2 class="mb-4">Input Kehadiran & Tugas</h2>

  <form method="POST" enctype="multipart/form-data" class="card shadow p-4">
    <div class="mb-3">
      <label class="form-label">Ruangan Kelas</label>
      <input type="text" name="ruangan" class="form-control" required placeholder="Contoh: Kelas 7A">
    </div>

      <div class="mb-3">
    <label class="form-label">Mata Pelajaran</label>
    <input type="text" name="mapel" class="form-control" list="mapel-list" required placeholder="Contoh: Matematika">
    <datalist id="mapel-list">
      <?php while ($m = $mapel_list->fetch_assoc()): ?>
        <option value="<?= htmlspecialchars($m['nama_mapel']) ?>"></option>
      <?php endwhile; ?>
    </datalist>
    </div>


    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label">Jam Masuk</label>
        <input type="time" name="jam_masuk" class="form-control" required>
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label">Jam Keluar</label>
        <input type="time" name="jam_keluar" class="form-control" required>
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label">Status Kehadiran</label>
      <select name="status" class="form-select" required>
        <option value="">-- Pilih Status --</option>
        <option value="Hadir">Hadir</option>
        <option value="Izin">Izin</option>
        <option value="Sakit">Sakit</option>
        <option value="TK">Tanpa Keterangan</option>
      </select>
    </div>

    <div class="mb-3">
      <label class="form-label">Upload Tugas (opsional)</label>
      <input type="file" name="tugas" class="form-control" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg">
    </div>

    <div class="mb-3">
      <label class="form-label">Keterangan Tugas</label>
      <textarea name="keterangan" class="form-control" rows="3" placeholder="Contoh: Materi Aljabar Bab 2"></textarea>
    </div>

    <button type="submit" name="submit" class="btn btn-primary">Simpan Kehadiran</button>
    <a href="dashboard.php" class="btn btn-secondary ms-2">Kembali ke Dashboard</a>
  </form>
</main>

<footer class="bg-dark text-white text-center py-3">
  Dirancang & dibuat oleh Tim Kurikulum SMKN 1 Cikampek © <?= date('Y') ?>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
