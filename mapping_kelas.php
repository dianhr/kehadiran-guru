<?php
session_start();
if ($_SESSION['role'] != 'admin') {
  header("Location: login.php");
  exit;
}

include 'koneksi.php';

// Dapatkan nilai filter
$from = isset($_GET['from']) ? $_GET['from'] : date('Y-m-01');
$to = isset($_GET['to']) ? $_GET['to'] : date('Y-m-d');
$ruangan = isset($_GET['ruangan']) ? trim($_GET['ruangan']) : '';
$guru = isset($_GET['guru']) ? trim($_GET['guru']) : '';

// Siapkan query
$where = ["tanggal BETWEEN '$from' AND '$to'"];
if ($ruangan) $where[] = "ruangan_kelas = '$ruangan'";
if ($guru) $where[] = "nama_guru = '$guru'";

$sql = "SELECT * FROM kehadiran_guru13 WHERE " . implode(' AND ', $where) . " ORDER BY tanggal DESC";
$result = $conn->query($sql);

// Buat opsi Ruangan & Guru unik untuk select filter
$ruangan_list = $conn->query("SELECT DISTINCT ruangan_kelas FROM kehadiran_guru13 ORDER BY ruangan_kelas");
// $guru_list = $conn->query("SELECT DISTINCT nama_guru FROM kehadiran_guru13 ORDER BY nama_guru");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Mapping Kelas</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- DataTables -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="dashboard_admin.php">Dashboard Admin</a>
  </div>
</nav>

<main class="container py-5">
  <h2 class="mb-4">Mapping Kelas - Guru</h2>

  <!-- Filter Form -->
  <form method="GET" class="row g-3 mb-4">
    <div class="col-md-3">
      <label class="form-label">Dari Tanggal</label>
      <input type="date" name="from" class="form-control" value="<?= htmlspecialchars($from) ?>" required>
    </div>
    <div class="col-md-3">
      <label class="form-label">Sampai Tanggal</label>
      <input type="date" name="to" class="form-control" value="<?= htmlspecialchars($to) ?>" required>
    </div>
    <div class="col-md-3">
      <label class="form-label">Ruangan/Kelas</label>
      <select name="ruangan" class="form-select">
        <option value="">-- Semua Ruangan --</option>
        <?php while ($r = $ruangan_list->fetch_assoc()): ?>
          <option value="<?= htmlspecialchars($r['ruangan_kelas']) ?>"
            <?= ($ruangan == $r['ruangan_kelas']) ? 'selected' : '' ?>>
            <?= htmlspecialchars($r['ruangan_kelas']) ?>
          </option>
        <?php endwhile; ?>
      </select>
    </div>

    <div class="col-md-3">
  <label class="form-label">Nama Guru</label>
  <input type="text" id="guru" name="guru" class="form-control"
         placeholder="Ketik Nama Guru..." value="<?= htmlspecialchars($guru) ?>">
</div>



    <div class="col-md-3 align-self-end">
      <button type="submit" class="btn btn-primary">Tampilkan</button>
      <a href="mapping_kelas.php" class="btn btn-secondary">Reset</a>
    </div>
  </form>

  <!-- Tabel Mapping -->
  <div class="card shadow">
    <div class="card-body table-responsive">
      <table id="tableMapping" class="table table-striped">
        <thead class="table-dark">
          <tr>
            <th>#</th>
            <th>Ruangan/Kelas</th>
            <th>Nama Guru</th>
            <th>Mapel</th>
            <th>Jam Masuk</th>
            <th>Jam Keluar</th>
            <th>Status</th>
            <th>Tanggal</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= $no++ ?></td>
              <td><?= htmlspecialchars($row['ruangan_kelas']) ?></td>
              <td><?= htmlspecialchars($row['nama_guru']) ?></td>
              <td><?= htmlspecialchars($row['mata_pelajaran']) ?></td>
              <td><?= htmlspecialchars($row['jam_masuk']) ?></td>
              <td><?= htmlspecialchars($row['jam_keluar']) ?></td>
              <td><?= htmlspecialchars($row['status_kehadiran']) ?></td>
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
$(document).ready(function() {
  $('#tableMapping').DataTable({
    responsive: true,
    dom: 'Bfrtip',
    buttons: ['copy', 'excel', 'csv', 'pdf', 'print'],
    pageLength: 10,
    lengthMenu: [5, 10, 25, 50, 100],
    order: [[7, 'desc']]
  });
});
</script>
<!-- jQuery UI -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<script>
$(function() {
  $("#guru").autocomplete({
    source: "search_guru.php",
    minLength: 1
  });
});
</script>


</body>
</html>
