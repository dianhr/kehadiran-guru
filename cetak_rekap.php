<?php
session_start();
if ($_SESSION['role'] != 'admin') {
  header("Location: login.php");
  exit;
}

include 'koneksi.php';
require('fpdf/fpdf.php');

class PDF extends FPDF
{
  // Header
  function Header()
  {
    // Logo
    $this->Image('logo.png',10,6,20); // Ganti logo.png sesuai file
    // Judul
    $this->SetFont('Arial','B',14);
    $this->Cell(0,7,'REKAPITULASI KEHADIRAN GURU',0,1,'C');
    $this->SetFont('Arial','',10);
    $this->Ln(5);
  }

  // Footer
  function Footer()
  {
    $this->SetY(-20);
    $this->SetFont('Arial','I',8);
    $this->Cell(0,10,'Dicetak pada '.date('d-m-Y H:i'). ' oleh '.$_SESSION['username'],0,0,'L');
    $this->Cell(0,10,'Halaman '.$this->PageNo().'/{nb}',0,0,'R');
  }
}

$pdf = new PDF('L','mm','A4');
$pdf->AliasNbPages();
$pdf->AddPage();

// Filter info
if (isset($_GET['from']) && isset($_GET['to']) && $_GET['from'] && $_GET['to']) {
  $from = $_GET['from'];
  $to = $_GET['to'];
  $pdf->SetFont('Arial','',10);
  $pdf->Cell(0,5,"Periode: $from s/d $to",0,1,'C');
}

$pdf->Ln(5);

// === Bagian 1: Rekap Jumlah ===
$pdf->SetFont('Arial','B',10);
$pdf->Cell(60,7,'Nama Guru',1);
$pdf->Cell(30,7,'Hadir',1);
$pdf->Cell(30,7,'Izin',1);
$pdf->Cell(30,7,'Sakit',1);
$pdf->Cell(30,7,'Total',1);
$pdf->Ln();

$sql_rekap = "SELECT nama_guru,
  SUM(CASE WHEN status_kehadiran = 'Hadir' THEN 1 ELSE 0 END) AS hadir,
  SUM(CASE WHEN status_kehadiran = 'Izin' THEN 1 ELSE 0 END) AS izin,
  SUM(CASE WHEN status_kehadiran = 'Sakit' THEN 1 ELSE 0 END) AS sakit
  FROM kehadiran_guru13";

if (isset($_GET['from']) && isset($_GET['to']) && $_GET['from'] && $_GET['to']) {
  $sql_rekap .= " WHERE tanggal BETWEEN '$from' AND '$to'";
}
$sql_rekap .= " GROUP BY nama_guru ORDER BY nama_guru";

$result_rekap = $conn->query($sql_rekap);
$pdf->SetFont('Arial','',10);
while($row = $result_rekap->fetch_assoc()) {
  $total = $row['hadir'] + $row['izin'] + $row['sakit'];
  $pdf->Cell(60,7,$row['nama_guru'],1);
  $pdf->Cell(30,7,$row['hadir'],1,0,'C');
  $pdf->Cell(30,7,$row['izin'],1,0,'C');
  $pdf->Cell(30,7,$row['sakit'],1,0,'C');
  $pdf->Cell(30,7,$total,1,0,'C');
  $pdf->Ln();
}

$pdf->Ln(5);

// === Bagian 2: Detail ===
$pdf->SetFont('Arial','B',10);
$pdf->Cell(10,7,'#',1);
$pdf->Cell(40,7,'Nama Guru',1);
$pdf->Cell(25,7,'Ruangan',1);
$pdf->Cell(35,7,'Mapel',1);
$pdf->Cell(20,7,'Masuk',1);
$pdf->Cell(20,7,'Keluar',1);
$pdf->Cell(20,7,'Status',1);
$pdf->Cell(40,7,'Tugas',1);
$pdf->Cell(50,7,'Keterangan',1);
$pdf->Cell(25,7,'Tanggal',1);
$pdf->Ln();

$pdf->SetFont('Arial','',10);
$no = 1;
$sql_detail = "SELECT * FROM kehadiran_guru13";
if (isset($_GET['from']) && isset($_GET['to']) && $_GET['from'] && $_GET['to']) {
  $sql_detail .= " WHERE tanggal BETWEEN '$from' AND '$to'";
}
$sql_detail .= " ORDER BY tanggal DESC";

$result = $conn->query($sql_detail);
while($row = $result->fetch_assoc()) {
  $pdf->Cell(10,7,$no++,1);
  $pdf->Cell(40,7,$row['nama_guru'],1);
  $pdf->Cell(25,7,$row['ruangan_kelas'],1);
  $pdf->Cell(35,7,$row['mata_pelajaran'],1);
  $pdf->Cell(20,7,$row['jam_masuk'],1);
  $pdf->Cell(20,7,$row['jam_keluar'],1);
  $pdf->Cell(20,7,$row['status_kehadiran'],1);
  $pdf->Cell(40,7,$row['tugas'],1);
  $pdf->Cell(50,7,$row['keterangan_tugas'],1);
  $pdf->Cell(25,7,$row['tanggal'],1);
  $pdf->Ln();
}

$pdf->Ln(10);

// === Tanda Tangan ===
$pdf->SetFont('Arial','',10);
$pdf->Cell(0,7,'',0,1,'R'); // Spacer
$pdf->Cell(0,7,'Mengetahui,',0,1,'R');
$pdf->Cell(0,7,'Kepala Sekolah',0,1,'R');
$pdf->Ln(20);
$pdf->Cell(0,7,'_______________________',0,1,'R');

$pdf->Output();
?>
