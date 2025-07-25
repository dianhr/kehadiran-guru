<?php
include 'koneksi.php';

$id = $_GET['id'];

// Ambil nama file dulu
$data = $conn->query("SELECT file FROM tugas WHERE id='$id'")->fetch_assoc();
$file = $data['file'];

// Hapus file fisik
if ($file && file_exists("uploads/" . $file)) {
  unlink("uploads/" . $file);
}

// Hapus dari DB
$conn->query("DELETE FROM tugas WHERE id='$id'");

echo "<script>alert('Tugas berhasil dihapus!'); window.location='dashboard.php';</script>";
?>
