<?php
session_start();
if ($_SESSION['role'] != 'admin') {
  header("Location: login.php");
  exit;
}

include 'koneksi.php';

$id = $_POST['id'];
$username = $_POST['username'];
$password = $_POST['password'];
$role = $_POST['role'];

$sql = "UPDATE users SET username='$username', password='$password', role='$role' WHERE id=$id";
if ($conn->query($sql) === TRUE) {
  echo "<script>alert('User berhasil diupdate!'); window.location.href='dashboard_admin.php';</script>";
} else {
  echo "Error: " . $conn->error;
}

$conn->close();
?>
