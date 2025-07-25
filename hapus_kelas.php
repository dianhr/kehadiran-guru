<?php
// hapus_kelas.php

include 'koneksi.php';
session_start();

if ($_SESSION['role'] != 'admin') {
  header("Location: login.php");
  exit;
}

$id = intval($_GET['id']);

// Cek apakah kelas memiliki siswa
$cek = $conn->query("SELECT COUNT(*) as jml FROM users WHERE kelas_id='$id'");
$jml = $cek->fetch_assoc()['jml'];

if ($jml > 0) {
  echo "<script>alert('Tidak bisa hapus! Masih ada siswa di kelas ini.'); window.location='kelola_kelas.php';</script>";
} else {
  $conn->query("DELETE FROM kelas WHERE id='$id'");
  echo "<script>alert('Kelas berhasil dihapus'); window.location='kelola_kelas.php';</script>";
}
?>
