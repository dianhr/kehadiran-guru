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
        // Lewati header baris pertama
        $row++;
        continue;
      }
      $username = $data[0];
      $password = $data[1];
      $role = $data[2];

      // Insert ke tabel users
      $sql = "INSERT INTO users (username, password, role) VALUES ('$username', '$password', '$role')";
      $conn->query($sql);
      $row++;
    }

    fclose($handle);
    echo "<script>alert('Import data user selesai!'); window.location.href='dashboard_admin.php';</script>";
  } else {
    echo "<script>alert('File kosong!'); window.location.href='dashboard_admin.php';</script>";
  }
} else {
  header("Location: dashboard_admin.php");
}
?>
