<?php
session_start();
if ($_SESSION['role'] != 'admin') {
  header("Location: login.php");
  exit;
}

include 'koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Rekap Kehadiran Guru</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-light">
<div class="container py-5">
  <h2 class="mb-4">Rekapitulasi Kehadiran Guru</h2>
  <a href="dashboard_admin.php" class="btn btn-secondary mb-3">← Kembali ke Dashboard</a>

  <!-- Filter -->
  <form method="GET" class="row g-3 mb-4">
    <div class="col-md-3">
      <label class="form-label">Dari Tanggal</label>
      <input type="date" name="from" class="form-control" value="<?= isset($_GET['from']) ? $_GET['from'] : '' ?>">
    </div>
    <div class="col-md-3">
      <label class="form-label">Sampai Tanggal</label>
      <input type="date" name="to" class="form-control" value="<?= isset($_GET['to']) ? $_GET['to'] : '' ?>">
    </div>
    
    <div class="col-md-3">
  <label class="form-label">Nama Guru</label>
  <input type="text" id="nama_guru" name="nama_guru" class="form-control"
    placeholder="Cari Nama Guru..."
    value="<?= isset($_GET['nama_guru']) ? htmlspecialchars($_GET['nama_guru']) : '' ?>">
</div>



    <div class="col-md-3 d-flex align-items-end">
      <button type="submit" class="btn btn-primary">Filter</button>
    </div>
  </form>
<script>
$(function() {
  $("#nama_guru").autocomplete({
    source: "search_guru.php",
    minLength: 1
  });
});
</script>

  <!-- GRAFIK -->
  <div class="card shadow p-4 mb-4">
    <h5>Grafik Kehadiran per Guru</h5>
    <canvas id="kehadiranChart" height="100"></canvas>

    <?php
    $where = [];
    if (isset($_GET['from']) && $_GET['from'] && isset($_GET['to']) && $_GET['to']) {
      $from = $_GET['from'];
      $to = $_GET['to'];
      $where[] = "tanggal BETWEEN '$from' AND '$to'";
    }
    if (isset($_GET['nama_guru']) && $_GET['nama_guru']) {
      $nama_guru = $_GET['nama_guru'];
      $where[] = "nama_guru = '$nama_guru'";
    }







    $sql_rekap = "SELECT nama_guru,
      SUM(CASE WHEN status_kehadiran = 'Hadir' THEN 1 ELSE 0 END) AS hadir,
      SUM(CASE WHEN status_kehadiran = 'Izin' THEN 1 ELSE 0 END) AS izin,
      SUM(CASE WHEN status_kehadiran = 'Sakit' THEN 1 ELSE 0 END) AS sakit,
      SUM(CASE WHEN status_kehadiran = 'TK' THEN 1 ELSE 0 END) AS tk
      FROM kehadiran_guru13";

    if ($where) {
      $sql_rekap .= " WHERE " . implode(" AND ", $where);
    }
    $sql_rekap .= " GROUP BY nama_guru ORDER BY nama_guru";

    $result_rekap = $conn->query($sql_rekap);

    $labels = [];
    $hadir = [];
    $izin = [];
    $sakit = [];
    $tk = [];

    while ($row = $result_rekap->fetch_assoc()) {
      $labels[] = $row['nama_guru'];
      $hadir[] = $row['hadir'];
      $izin[] = $row['izin'];
      $sakit[] = $row['sakit'];
      $tk[] = $row['tk'];
    }
    ?>

    <script>
      const ctx = document.getElementById('kehadiranChart').getContext('2d');
      const kehadiranChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: <?= json_encode($labels) ?>,
          datasets: [
            {
              label: 'Hadir',
              backgroundColor: 'rgba(40,167,69,0.7)',
              data: <?= json_encode($hadir) ?>
            },
            {
              label: 'Izin',
              backgroundColor: 'rgba(255,193,7,0.7)',
              data: <?= json_encode($izin) ?>
            },
            {
              label: 'Sakit',
              backgroundColor: 'rgba(220,53,69,0.7)',
              data: <?= json_encode($sakit) ?>
            },
            {
              label: 'Tanpa Keterangan',
              backgroundColor: 'rgba(108,117,125,0.7)',
              data: <?= json_encode($tk) ?>
            }
          ]
        },
        options: {
          responsive: true,
          plugins: { legend: { position: 'top' } },
          scales: { y: { beginAtZero: true, precision: 0 } }
        }
      });
    </script>
  </div>

  <!-- Tabel Rekap -->
  <div class="card shadow p-4 mb-4">
    <h5>Rekap Jumlah Kehadiran per Guru</h5>
    <div class="table-responsive">
      <table class="table table-bordered table-striped">
        <thead class="table-success">
          <tr>
            <th>Nama Guru</th>
            <th>Hadir</th>
            <th>Izin</th>
            <th>Sakit</th>
            <th>TK</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody>
        <?php
        $result_rekap = $conn->query($sql_rekap);
        while ($row = $result_rekap->fetch_assoc()) {
          $total = $row['hadir'] + $row['izin'] + $row['sakit'] + $row['tk'];
          echo "<tr>
            <td>".$row['nama_guru']."</td>
            <td>".$row['hadir']."</td>
            <td>".$row['izin']."</td>
            <td>".$row['sakit']."</td>
            <td>".$row['tk']."</td>
            <td>".$total."</td>
          </tr>";
        }
        ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Detail -->
  <div class="card shadow p-4 mb-4">
    <h5>Detail Kehadiran & Tugas</h5>
    <div class="table-responsive">
      <table class="table table-bordered table-striped">
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
        <?php
        $no = 1;
        $sql_detail = "SELECT * FROM kehadiran_guru13";
        if ($where) {
          $sql_detail .= " WHERE " . implode(" AND ", $where);
        }
        $sql_detail .= " ORDER BY tanggal DESC";

        $result = $conn->query($sql_detail);
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
          echo $row['tugas'] ? "<a href='uploads/".$row['tugas']."' target='_blank'>Download</a>" : "-";
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

  <!-- Export -->
  <a href="export_kehadiran.php?from=<?= $_GET['from'] ?? '' ?>&to=<?= $_GET['to'] ?? '' ?>&nama_guru=<?= $_GET['nama_guru'] ?? '' ?>" 
     class="btn btn-success mt-3">
    Export Rekap ke Excel
  </a>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css" rel="stylesheet">
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>


</body>
</html>
