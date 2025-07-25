<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'siswa') {
  header("Location: login.php");
  exit;
}

include 'koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Siswa</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #f8f9fa;
    }
    .table-responsive {
      border-radius: 8px;
      overflow: hidden;
    }
    .header {
      background: #007bff;
      color: #fff;
      padding: 20px 30px;
      border-radius: 8px;
    }
    .logout-btn {
      float: right;
    }
  </style>
</head>
<body>
<div class="container py-5">
  <div class="header mb-4 d-flex justify-content-between align-items-center">
    <h3 class="mb-0">Selamat Datang, <?= $_SESSION['username'] ?> (Siswa)</h3>
    <a href="biodata_siswa.php" class="btn btn-success mb-3">Perbaharui Biodata Siswa </a>
    <a href="kartu_siswa.php" class="btn btn-success mb-3">🎫 Cetak Kartu Identitas</a>
    <a href="logout.php" class="btn btn-light btn-sm logout-btn">Logout</a>
  </div>

<?php


$nama_guru = isset($_GET['guru']) ? trim($_GET['guru']) : '';

$where = [];
if ($nama_guru) {
  $where[] = "nama_guru = '$nama_guru'";
}

$sql = "SELECT * FROM tugas";
if ($where) {
  $sql .= " WHERE " . implode(' AND ', $where);
}
$sql .= " ORDER BY tanggal DESC";

$result = $conn->query($sql);
?>

<!-- FORM FILTER -->
<form method="GET" class="row g-3 mb-4">
  <div class="col-md-4">
    <label class="form-label">Cari Nama Guru</label>
    <input type="text" id="guru" name="guru" class="form-control"
      placeholder="Ketik Nama Guru..."
      value="<?= htmlspecialchars($nama_guru) ?>">
  </div>
  <div class="col-md-2 align-self-end">
    <button type="submit" class="btn btn-primary">Cari</button>
    <a href="dashboard_siswa.php" class="btn btn-secondary">Reset</a>
  </div>
</form>

  
  <div class="card shadow p-4">
    <h4 class="mb-4">Daftar Tugas dari Guru</h4>
    <div class="table-responsive">
      <table class="table table-striped table-hover align-middle">
        <thead class="table-primary">
          <tr>
            <th>#</th>
            <th>Nama Guru</th>
            <th>Mata Pelajaran</th>
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
            <td>".$row['mata_pelajaran']."</td>
            <td>";
          if ($row['tugas']) {
            echo "<a href='uploads/".$row['tugas']."' class='btn btn-sm btn-success' target='_blank'>Download</a>";
          } else {
            echo "<span class='badge bg-secondary'>Tidak Ada</span>";
          }
          echo "</td>
            <td>".$row['keterangan_tugas']."</td>
            <td>".date('d-m-Y', strtotime($row['tanggal']))."</td>
          </tr>";
        }




        
        ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
</div> <!-- penutup .container -->
<footer class="bg-dark text-white text-center py-3 mt-5">
  Dirancang dan dibuat oleh Tim Kurikulum SMKN 1 Cikampek © <?= date('Y') ?>
</footer>
</body>
</html>
<!-- jQuery UI -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<script>
$(function() {
  $("#guru").autocomplete({
    source: "search_guru_tugas.php",
    minLength: 1
  });
});
</script>


</body>
</html>
