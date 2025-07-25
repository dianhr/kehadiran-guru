<?php
session_start();
if ($_SESSION['role'] != 'admin') {
  header("Location: login.php");
  exit;
}

include 'koneksi.php';

// --- Pagination ---
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// Hitung total user
$sql_total = "SELECT COUNT(*) as total FROM users";
$result_total = $conn->query($sql_total);
$total_row = $result_total->fetch_assoc()['total'];
$total_page = ceil($total_row / $limit);

// Query ambil user per halaman
$result = $conn->query("SELECT * FROM users ORDER BY id DESC LIMIT $limit OFFSET $offset");
?>



<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
  <h2 class="mb-4">Dashboard Admin: <?= $_SESSION['username'] ?></h2>
  <a href="logout.php" class="btn btn-danger mb-2">Logout</a>
  <a href="export_user.php" class="btn btn-success mb-2">Export User ke Excel</a>
  <a href="rekap_kehadiran.php" class="btn btn-info mb-2">Rekap Kehadiran Guru</a>
  <a href="kelola_jurusan.php" class="btn btn-info mb-2">Kelola Jurusan</a>
  <a href="kelola_kelas.php" class="btn btn-info mb-2">Kelola Kelas</a>


  <!-- Import User -->
  <div class="card shadow p-4 my-3">
    <h5>Import User dari File CSV</h5>
    <form action="import_user.php" method="POST" enctype="multipart/form-data">
      <div class="mb-3">
        <input type="file" name="file_csv" class="form-control" accept=".csv" required>
        <small class="text-muted">Format: username,password,role</small>
      </div>
      <button type="submit" name="import" class="btn btn-primary btn-sm">Import</button>
    </form>
  </div>

  <!-- Daftar User -->
  <div class="card shadow p-4 my-3">
    <h4>Daftar User</h4>
    <a href="tambah_user.php" class="btn btn-primary btn-sm mb-3">+ Tambah User</a>

    <table class="table table-striped table-hover">
      <thead class="table-dark">
        <tr>
          <th>#</th>
          <th>Username</th>
          <th>Password</th>
          <th>Role</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
      <?php
      $no = 1;
      $result = $conn->query("SELECT * FROM users");
      while ($row = $result->fetch_assoc()) {
        echo "<tr>
          <td>".$no++."</td>
          <td>".$row['username']."</td>
          <td>".$row['password']."</td>
          <td>".$row['role']."</td>
          <td>
            <a href='edit_user.php?id=".$row['id']."' class='btn btn-warning btn-sm'>Edit</a>
            <a href='delete_user.php?id=".$row['id']."' class='btn btn-danger btn-sm' onclick=\"return confirm('Yakin hapus user?')\">Hapus</a>
          </td>
        </tr>";
      }
      ?>
      </tbody>
    </table>
    <?php if ($total_page > 1): ?>
<nav aria-label="Page navigation" class="mt-4">
  <ul class="pagination justify-content-center">

    <?php
    $prev = max(1, $page - 1);
    $next = min($total_page, $page + 1);
    ?>

    <!-- First -->
    <li class="page-item <?= ($page == 1) ? 'disabled' : '' ?>">
      <a class="page-link" href="?page=1">First</a>
    </li>

    <!-- Prev -->
    <li class="page-item <?= ($page == 1) ? 'disabled' : '' ?>">
      <a class="page-link" href="?page=<?= $prev ?>">&laquo; Prev</a>
    </li>

    <!-- Page numbers window -->
    <?php
    $start = max(1, $page - 2);
    $end = min($total_page, $page + 2);

    for ($i = $start; $i <= $end; $i++):
    ?>
      <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
      </li>
    <?php endfor; ?>

    <!-- Next -->
    <li class="page-item <?= ($page == $total_page) ? 'disabled' : '' ?>">
      <a class="page-link" href="?page=<?= $next ?>">Next &raquo;</a>
    </li>

    <!-- Last -->
    <li class="page-item <?= ($page == $total_page) ? 'disabled' : '' ?>">
      <a class="page-link" href="?page=<?= $total_page ?>">Last</a>
    </li>

  </ul>
</nav>
<?php endif; ?>

  </div>

  <!-- Daftar Kehadiran & Tugas Guru -->
  <div class="card shadow p-4 my-3">
    <h4>Daftar Kehadiran Guru & Tugas</h4>
    <div class="table-responsive">
      <table class="table table-bordered table-striped table-hover">
        <thead class="table-primary">
          <tr>
            <th>#</th>
            <th>Nama Guru</th>
            <th>Ruangan</th>
            <th>Mata Pelajaran</th>
            <th>Jam Masuk</th>
            <th>Jam Keluar</th>
            <th>Status</th>
            <th>Tugas</th>
            <th>Keterangan</th>
            <th>Tanggal</th>
          </tr>
        </thead>
        <tbody>
        <?php
        $no = 1;
        $result = $conn->query("SELECT * FROM kehadiran_guru13 ORDER BY tanggal DESC");
        while ($row = $result->fetch_assoc()) {
          echo "<tr>
            <td>".$no++."</td>
            <td>".$row['nama_guru']."</td>
            <td>".$row['ruangan_kelas']."</td>
            <td>".$row['mata_pelajaran']."</td>
            <td>".$row['jam_masuk']."</td>
            <td>".$row['jam_keluar']."</td>
            <td>".$row['status_kehadiran']."</td>
            <td>";
          if ($row['tugas']) {
            echo "<a href='uploads/".$row['tugas']."' target='_blank'>Download</a>";
          } else {
            echo "-";
          }
          echo "</td>
            <td>".$row['keterangan_tugas']."</td>
            <td>".date('d-m-Y', strtotime($row['tanggal']))."</td>
          </tr>";
        }
        ?>
        </tbody>
      </table>
      <?php if ($total_page > 1): ?>
<nav aria-label="Page navigation" class="mt-4">
  <ul class="pagination justify-content-center">

    <?php
    $prev = max(1, $page - 1);
    $next = min($total_page, $page + 1);
    ?>

    <!-- First -->
    <li class="page-item <?= ($page == 1) ? 'disabled' : '' ?>">
      <a class="page-link" href="?page=1">First</a>
    </li>

    <!-- Prev -->
    <li class="page-item <?= ($page == 1) ? 'disabled' : '' ?>">
      <a class="page-link" href="?page=<?= $prev ?>">&laquo; Prev</a>
    </li>

    <!-- Page numbers window -->
    <?php
    $start = max(1, $page - 2);
    $end = min($total_page, $page + 2);

    for ($i = $start; $i <= $end; $i++):
    ?>
      <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
      </li>
    <?php endfor; ?>

    <!-- Next -->
    <li class="page-item <?= ($page == $total_page) ? 'disabled' : '' ?>">
      <a class="page-link" href="?page=<?= $next ?>">Next &raquo;</a>
    </li>

    <!-- Last -->
    <li class="page-item <?= ($page == $total_page) ? 'disabled' : '' ?>">
      <a class="page-link" href="?page=<?= $total_page ?>">Last</a>
    </li>

  </ul>
</nav>
<?php endif; ?>

    </div>
  </div>

</div>
</body>
</html>
