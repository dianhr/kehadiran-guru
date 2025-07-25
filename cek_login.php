<?php
session_start();
include 'koneksi.php';

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  $data = $result->fetch_assoc();
  $_SESSION['username'] = $data['username'];
  $_SESSION['role'] = $data['role'];

  if ($data['role'] == 'admin') {
    header("Location: dashboard_admin.php");
  } elseif ($data['role'] == 'guru') {
    header("Location: dashboard.php");
  } elseif ($data['role'] == 'siswa') {
    header("Location: dashboard_siswa.php");
  }
} else {
  echo "<script>alert('Login gagal!'); window.location.href='login.php';</script>";
}
?>
