<?php
session_start();
if ($_SESSION['role'] != 'admin') {
  header("Location: login.php");
  exit;
}

require 'vendor/autoload.php';
include 'koneksi.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

if (isset($_POST['import_excel'])) {
  $file = $_FILES['file_excel']['tmp_name'];

  if ($_FILES['file_excel']['size'] > 0) {
    $spreadsheet = IOFactory::load($file);
    $sheet = $spreadsheet->getActiveSheet();
    $rows = $sheet->toArray();

    $rowNum = 0;
    $inserted = 0;
    $skipped = 0;
    $duplikat = [];

    foreach ($rows as $r) {
      $rowNum++;
      if ($rowNum == 1) continue; // Lewati header

      $username = trim($r[0]);
      $password = trim($r[1]);
      $role = trim(strtolower($r[2]));
      $jurusan_id = !empty($r[3]) ? intval($r[3]) : 'NULL';
      $kelas_id = !empty($r[4]) ? intval($r[4]) : 'NULL';

      // Default role siswa kalau kosong/tidak valid
      if (!in_array($role, ['admin', 'guru', 'siswa'])) {
        $role = 'siswa';
      }

      if ($username && $password) {
        // Cek duplikat
        $cek = $conn->query("SELECT * FROM users WHERE username = '$username'");
        if ($cek->num_rows > 0) {
          $duplikat[] = $username;
          $skipped++;
        } else {
          $sql = "INSERT INTO users (username, password, role, jurusan_id, kelas_id)
                  VALUES ('$username', '$password', '$role', $jurusan_id, $kelas_id)";
          $conn->query($sql);
          $inserted++;
        }
      }
    }

    $msg = "Import selesai!\nBerhasil ditambahkan: $inserted\nDuplikat dilewati: $skipped";
    if (count($duplikat) > 0) {
      $msg .= "\nDuplikat: " . implode(", ", $duplikat);
    }

    echo "<script>alert(`$msg`); window.location.href='dashboard_admin.php';</script>";
  } else {
    echo "<script>alert('File kosong!'); window.location.href='dashboard_admin.php';</script>";
  }
} else {
  header("Location: dashboard_admin.php");
}
?>
