<?php
session_start();
if ($_SESSION['role'] != 'admin') {
  header("Location: login.php");
  exit;
}

include 'koneksi.php';

// Ambil data user
$users = $conn->query("SELECT * FROM users ORDER BY id DESC");

// Ambil data kehadiran
$kehadiran = $conn->query("SELECT * FROM kehadiran_guru13 ORDER BY tanggal DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <title>Dashboard Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- DataTables -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">

  <style>
    html, body { height: 100%; }
    body { display: flex; flex-direction: column; }
    main { flex: 1; }
  </style>
</head>
<body class="bg-light">

<div class="container py-5">
 
 
 
  <a href="tambah_user.php" class="btn btn-info mb-2">Tambah User</a>
  <a href="rekap_kehadiran.php" class="btn btn-info mb-2">Rekap Kehadiran Guru</a>
  <a href="mapping_kelas.php" class="btn btn-info mb-2">Mapping Kelas</a>
  <a href="kelola_jurusan.php" class="btn btn-info mb-2">Kelola Jurusan</a>
  <a href="kelola_kelas.php" class="btn btn-info mb-2">Kelola Kelas</a>
 <a href="kelola_siswa.php" class="btn btn-info mb-2">Manajemen Siswa</a>

   


  



<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="#">Dashboard Admin</a>
    <div class="d-flex">
      <span class="navbar-text text-white">👋 Halo, <?= htmlspecialchars($_SESSION['username']) ?></span>
      <a href="logout.php" class="btn btn-outline-light btn-sm ms-3">Logout</a>
    </div>
  </div>
</nav>

<main class="container py-5">
  <!-- === TABEL DATA USER === -->
  <h2 class="mb-3">Daftar User</h2>
  <div class="card shadow mb-5">
    <div class="card-body table-responsive">
      <table id="tableUsers" class="table table-striped">
        <thead class="table-dark">
          <tr>
            <th>No</th>
            <th>Username</th>
            <th>Role</th>
            <th>Jurusan</th>
            <th>Kelas</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1; while ($row = $users->fetch_assoc()): ?>
            <tr>
              <td><?= $no++ ?></td>
              <td><?= htmlspecialchars($row['username']) ?></td>
              <td><?= htmlspecialchars($row['role']) ?></td>
              <td><?= $row['jurusan_id'] ?: '-' ?></td>
              <td><?= $row['kelas_id'] ?: '-' ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>



  
  <h2 class="mb-3">Data Kehadiran Guru & Tugas</h2>
  <div class="card shadow">
    <div class="card-body table-responsive">
      <table id="tableKehadiran" class="table table-striped">
        <thead class="table-dark">
          <tr>
            <th>#</th>
            <th>Nama Guru</th>
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
          <?php $no = 1; while ($row = $kehadiran->fetch_assoc()): ?>
            <tr>
              <td><?= $no++ ?></td>
              <td><?= htmlspecialchars($row['nama_guru']) ?></td>
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
              <td><?= htmlspecialchars($row['tanggal']) ?></td>
              


            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</main>

<footer class="bg-dark text-white text-center py-3">
  Dirancang & dibuat oleh Tim Kurikulum SMKN 1 Cikampek © <?= date('Y') ?>
</footer>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script>
$(document).ready(function() 
{
  $('#tableUsers').DataTable({
    responsive: true,
    dom: 'Bfrtip',
    buttons: ['excel',  'pdf', 'print'],
    pageLength: 5,
    lengthMenu: [5, 10, 25, 50, 100],
    order: [[0, 'asc']]
  });

  $('#tableKehadiran').DataTable({
    responsive: true,
    dom: 'Bfrtip',
    buttons: ['excel',  'pdf', 'print'],
    pageLength: 5,
    lengthMenu: [5, 10, 25, 50, 100],
    order: [[9, 'desc']] // Order by tanggal DESC
  });
}
);
</script>

</body>
</html>
