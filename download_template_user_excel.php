<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Template User Multi Role');

$sheet->setCellValue('A1', 'username');
$sheet->setCellValue('B1', 'password');
$sheet->setCellValue('C1', 'role');
$sheet->setCellValue('D1', 'jurusan_id');
$sheet->setCellValue('E1', 'kelas_id');

$sheet->setCellValue('A2', 'dian');
$sheet->setCellValue('B2', 'dian123');
$sheet->setCellValue('C2', 'admin');

$sheet->setCellValue('A3', 'budi');
$sheet->setCellValue('B3', 'budi123');
$sheet->setCellValue('C3', 'guru');

$sheet->setCellValue('A4', 'agus1');
$sheet->setCellValue('B4', 'agus1');
$sheet->setCellValue('C4', 'siswa');
$sheet->setCellValue('D4', '1');
$sheet->setCellValue('E4', '2');

foreach (range('A','E') as $col) {
  $sheet->getColumnDimension($col)->setAutoSize(true);
}

$filename = "Template_Import_User_MultiRole.xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$filename\"");
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
