<?php
session_start();
if ($_SESSION['role'] != 'admin') {
  header("Location: login.php");
  exit;
}

include 'koneksi.php';

$username = $_POST['username'];
$password = $_POST['password'];
$role = $_POST['role'];
$jurusan_id = $_POST['jurusan_id'] ?: 'NULL';
$kelas_id = $_POST['kelas_id'] ?: 'NULL';

//$sql = "INSERT INTO users (username, password, role) VALUES ('$username', '$password', '$role')";
$sql = "INSERT INTO users (username, password, role, jurusan_id, kelas_id) VALUES ('$username', '$password', '$role', $jurusan_id, $kelas_id)";
if ($conn->query($sql) === TRUE) {
  echo "<script>alert('User berhasil ditambahkan!'); window.location.href='dashboard_admin.php';</script>";
} else {
  echo "Error: " . $conn->error;
}

$conn->close();
?>





