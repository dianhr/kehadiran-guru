<?php
session_start();
include 'koneksi.php';

if ($_SESSION['role'] != 'siswa') {
  header("Location: login.php");
  exit;
}

$tugas_id = intval($_GET['tugas_id']);
$username = $_SESSION['username'];

// Cek tugas valid
$tugas = $conn->query("SELECT * FROM tugas WHERE id='$tugas_id'")->fetch_assoc();
if (!$tugas) {
  echo "Tugas tidak ditemukan!";
  exit;
}

if (isset($_POST['upload'])) {
  $file = null;

  if (isset($_FILES['file']['name']) && $_FILES['file']['name'] != '') {
    $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
    $filename = "jawaban_" . $username . "_" . time() . "." . $ext;
    move_uploaded_file($_FILES['file']['tmp_name'], "jawaban/" . $filename);
    $file = $filename;
  }

  $sql = "INSERT INTO jawaban (tugas_id, username_siswa, file, tanggal) VALUES ('$tugas_id', '$username', '$file', NOW())";
  $conn->query($sql);

  echo "<script>alert('Jawaban berhasil diupload!'); window.location='dashboard_siswa.php';</script>";
}
?>

<h3>Upload Jawaban untuk Tugas: <?= htmlspecialchars($tugas['judul']) ?></h3>

<form method="POST" enctype="multipart/form-data">
  <label>Pilih File Jawaban</label>
  <input type="file" name="file" class="form-control" required accept=".pdf,.jpg,.png,.doc,.docx">

  <button type="submit" name="upload" class="btn btn-success mt-3">Upload</button>
</form>
