<?php
session_start();
if (!isset($_SESSION['role'])) {
  header("Location: login.php");
  exit;
}

include 'koneksi.php';

// Contoh ambil data: kehadiran + tugas guru yg login
$nama_guru = $_SESSION['username'];
$result = $conn->query("SELECT * FROM kehadiran_guru13 WHERE nama_guru='$nama_guru' ORDER BY tanggal DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Dashboard</title>
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

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="#">Portal Guru</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
      data-bs-target="#navbarNav" aria-controls="navbarNav"
      aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <span class="nav-link text-white">👋 Halo, <?= htmlspecialchars($nama_guru) ?></span>
        </li>
        <li class="nav-item">
          <a href="logout.php" class="btn btn-outline-light btn-sm ms-2">Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- CONTENT -->
<main class="container py-4">
  <h2 class="mb-4">Data Kehadiran & Tugas Anda</h2>
  <a href="index.php" class="btn btn-primary mb-3">+ Tambah Kehadiran & Tugas</a>

  <div class="card shadow-sm">
    <div class="card-body table-responsive">
      <table class="table table-striped">
        <thead class="table-dark">
          <tr>
            <th>#</th>
            <th>Ruangan</th>
            <th>Mapel</th>
            <th>Jam Masuk</th>
            <th>Jam Keluar</th>
            <th>Status</th>
            <th>Tugas</th>
            <th>Keterangan</th>
            <th>Tanggal</th>
          </tr>
        </thead>
        <tbody>
        <?php $no=1; while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($row['ruangan_kelas']) ?></td>
            <td><?= htmlspecialchars($row['mata_pelajaran']) ?></td>
            <td><?= htmlspecialchars($row['jam_masuk']) ?></td>
            <td><?= htmlspecialchars($row['jam_keluar']) ?></td>
            <td><?= htmlspecialchars($row['status_kehadiran']) ?></td>
            <td>
              <?php if ($row['tugas']): ?>
                <a href="uploads/<?= htmlspecialchars($row['tugas']) ?>" target="_blank" class="btn btn-sm btn-success">Download</a>
              <?php else: ?>
                <span class="text-muted">-</span>
              <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($row['keterangan_tugas']) ?></td>
            <td><?= date('d-m-Y', strtotime($row['tanggal'])) ?></td>
          


          </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</main>

<!-- FOOTER -->
<footer class="bg-dark text-white text-center py-3">
  Dirancang & dibuat oleh Tim Kurikulum SMKN 1 Cikampek © <?= date('Y') ?>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
