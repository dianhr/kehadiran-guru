<?php
session_start();
if ($_SESSION['role'] != 'admin') {
  header("Location: login.php");
  exit;
}

include 'koneksi.php';

if (isset($_POST['import'])) {
  $file = $_FILES['file_csv']['tmp_name'];

  if ($_FILES['file_csv']['size'] > 0) {
    $handle = fopen($file, "r");
    $row = 0;

    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
      if ($row == 0) {
        $row++;
        continue; // Lewati header
      }

      $username = $data[0];
      $password = $data[1];
      $jurusan_id = !empty($data[2]) ? $data[2] : 'NULL';
      $kelas_id = !empty($data[3]) ? $data[3] : 'NULL';

      $sql = "INSERT INTO users (username, password, role, jurusan_id, kelas_id)
              VALUES ('$username', '$password', 'siswa', $jurusan_id, $kelas_id)";
      $conn->query($sql);

      $row++;
    }

    fclose($handle);
    echo "<script>alert('Import data siswa selesai!'); window.location.href='dashboard_admin.php';</script>";
  } else {
    echo "<script>alert('File kosong!'); window.location.href='dashboard_admin.php';</script>";
  }
} else {
  header("Location: dashboard_admin.php");
}
?>
