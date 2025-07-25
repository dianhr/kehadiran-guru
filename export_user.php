<?php
session_start();
if ($_SESSION['role'] != 'admin') {
  header("Location: login.php");
  exit;
}

include 'koneksi.php';

// Buat nama file export
$filename = "data_user_" . date('Ymd_His') . ".csv";

// Set header supaya browser mendownload file
header("Content-Description: File Transfer");
header("Content-Disposition: attachment; filename=$filename");
header("Content-Type: text/csv; charset=UTF-8");

// Buka output ke PHP output stream
$output = fopen("php://output", "w");

// Tulis header kolom
fputcsv($output, array('ID', 'Username', 'Password', 'Role'));

// Ambil data dari DB
$result = $conn->query("SELECT * FROM users");
while ($row = $result->fetch_assoc()) {
  fputcsv($output, $row);
}

fclose($output);
exit;
?>
