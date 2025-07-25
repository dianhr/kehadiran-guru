<?php
session_start();
if ($_SESSION['role'] != 'siswa' && $_SESSION['role'] != 'admin') {
  header("Location: login.php");
  exit;
}

include 'koneksi.php';
require('fpdf.php');

// Kalau siswa login → pakai session username
// Kalau admin buka: ?username=namauser
$username = $_SESSION['role'] == 'siswa' ? $_SESSION['username'] : $_GET['username'];

$data = $conn->query("SELECT * FROM biodata_siswa WHERE username='$username'")->fetch_assoc();
if (!$data) {
  die('Biodata tidak ditemukan.');
}

$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();

// Judul
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Kartu Identitas Siswa', 0, 1, 'C');

$pdf->Ln(5);

// Foto
if ($data['foto'] && file_exists('uploads/' . $data['foto'])) {
  $pdf->Image('uploads/' . $data['foto'], 160, 30, 30, 40);
}

// Data
$pdf->SetFont('Arial', '', 12);
$pdf->Ln(10);
$pdf->Cell(40, 10, 'Nama', 0, 0);
$pdf->Cell(60, 10, ': ' . $data['nama'], 0, 1);

$pdf->Cell(40, 10, 'NIS', 0, 0);
$pdf->Cell(60, 10, ': ' . $data['nis'], 0, 1);

$pdf->Cell(40, 10, 'Alamat', 0, 0);
$pdf->MultiCell(120, 10, ': ' . $data['alamat'], 0, 1);

$pdf->Cell(40, 10, 'No Telepon', 0, 0);
$pdf->Cell(60, 10, ': ' . $data['telepon'], 0, 1);

$pdf->Output();
?>
