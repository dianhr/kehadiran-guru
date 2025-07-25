<?php
session_start();
if ($_SESSION['role'] != 'admin') {
  header("Location: login.php");
  exit;
}

include 'koneksi.php';

$id = $_GET['id'];

$sql = "DELETE FROM users WHERE id=$id";
if ($conn->query($sql) === TRUE) {
  echo "<script>alert('User berhasil dihapus!'); window.location.href='dashboard_admin.php';</script>";
} else {
  echo "Error: " . $conn->error;
}

$conn->close();
?>
