<?php
session_start();
if ($_SESSION['role'] != 'admin') {
  header("Location: login.php");
  exit;
}

require 'vendor/autoload.php';
include 'koneksi.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

// Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Rekap Kehadiran');

// Judul
$sheet->mergeCells('A1:F1');
$sheet->setCellValue('A1', 'REKAPITULASI KEHADIRAN GURU');
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
$sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

$row = 3;
$sheet->setCellValue("A$row", 'Nama Guru')
      ->setCellValue("B$row", 'Hadir')
      ->setCellValue("C$row", 'Izin')
      ->setCellValue("D$row", 'Sakit')
      ->setCellValue("E$row", 'Tanpa Keterangan')
      ->setCellValue("F$row", 'Total');

// Filter periode
$where = [];
if (isset($_GET['from']) && $_GET['from'] && isset($_GET['to']) && $_GET['to']) {
  $from = $_GET['from'];
  $to = $_GET['to'];
  $where[] = "tanggal BETWEEN '$from' AND '$to'";
  $sheet->mergeCells('A2:F2');
  $sheet->setCellValue('A2', "Periode: $from s/d $to");
}

if (isset($_GET['nama_guru']) && $_GET['nama_guru']) {
  $nama_guru = $_GET['nama_guru'];
  $where[] = "nama_guru = '$nama_guru'";
}

// Query rekap
$sql_rekap = "SELECT nama_guru,
  SUM(CASE WHEN status_kehadiran = 'Hadir' THEN 1 ELSE 0 END) AS hadir,
  SUM(CASE WHEN status_kehadiran = 'Izin' THEN 1 ELSE 0 END) AS izin,
  SUM(CASE WHEN status_kehadiran = 'Sakit' THEN 1 ELSE 0 END) AS sakit,
  SUM(CASE WHEN status_kehadiran = 'TK' THEN 1 ELSE 0 END) AS tk
  FROM kehadiran_guru13";

if ($where) {
  $sql_rekap .= " WHERE " . implode(" AND ", $where);
}

$sql_rekap .= " GROUP BY nama_guru ORDER BY nama_guru";
$result_rekap = $conn->query($sql_rekap);

$row++;
while ($r = $result_rekap->fetch_assoc()) {
  $total = $r['hadir'] + $r['izin'] + $r['sakit'] + $r['tk'];
  $sheet->setCellValue("A$row", $r['nama_guru']);
  $sheet->setCellValue("B$row", $r['hadir']);
  $sheet->setCellValue("C$row", $r['izin']);
  $sheet->setCellValue("D$row", $r['sakit']);
  $sheet->setCellValue("E$row", $r['tk']);
  $sheet->setCellValue("F$row", $total);
  $row++;
}

// Batas rekap → mulai detail
$row += 2;
$sheet->setCellValue("A$row", 'Detail Kehadiran');
$sheet->getStyle("A$row")->getFont()->setBold(true);
$row++;

$sheet->setCellValue("A$row", '#')
      ->setCellValue("B$row", 'Nama Guru')
      ->setCellValue("C$row", 'Ruangan')
      ->setCellValue("D$row", 'Mapel')
      ->setCellValue("E$row", 'Jam Masuk')
      ->setCellValue("F$row", 'Jam Keluar')
      ->setCellValue("G$row", 'Status')
      ->setCellValue("H$row", 'Tugas')
      ->setCellValue("I$row", 'Keterangan')
      ->setCellValue("J$row", 'Tanggal');

$detailHeaderRow = $row;

$row++;

$sql_detail = "SELECT * FROM kehadiran_guru13";
if ($where) {
  $sql_detail .= " WHERE " . implode(" AND ", $where);
}
$sql_detail .= " ORDER BY tanggal DESC";

$result_detail = $conn->query($sql_detail);
$no = 1;
while ($d = $result_detail->fetch_assoc()) {
  $sheet->setCellValue("A$row", $no++);
  $sheet->setCellValue("B$row", $d['nama_guru']);
  $sheet->setCellValue("C$row", $d['ruangan_kelas']);
  $sheet->setCellValue("D$row", $d['mata_pelajaran']);
  $sheet->setCellValue("E$row", $d['jam_masuk']);
  $sheet->setCellValue("F$row", $d['jam_keluar']);
  $sheet->setCellValue("G$row", $d['status_kehadiran']);
  $sheet->setCellValue("H$row", $d['tugas']);
  $sheet->setCellValue("I$row", $d['keterangan_tugas']);
  $sheet->setCellValue("J$row", $d['tanggal']);
  $row++;
}

// === Style Header Rekap
$sheet->getStyle("A3:F3")->getFont()->setBold(true)->getColor();
$sheet->getStyle("A3:F3")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle("A3:F3")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('D9E1F2');

// === Style Header Detail
$sheet->getStyle("A$detailHeaderRow:J$detailHeaderRow")->getFont()->setBold(true);
$sheet->getStyle("A$detailHeaderRow:J$detailHeaderRow")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle("A$detailHeaderRow:J$detailHeaderRow")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('D9E1F2');

// === Border Rekap
$rekapLastRow = $row - 2;
$sheet->getStyle("A3:F$rekapLastRow")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

// === Border Detail
$sheet->getStyle("A$detailHeaderRow:J".($row-1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

// === Auto width
foreach (range('A','J') as $col) {
  $sheet->getColumnDimension($col)->setAutoSize(true);
}

// === Simpan file
$filename = "Rekap_Kehadiran_" . date('Ymd_His') . ".xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$filename\"");
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
