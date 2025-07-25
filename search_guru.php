<?php
include 'koneksi.php';

$term = isset($_GET['term']) ? $_GET['term'] : '';

$stmt = $conn->prepare("SELECT DISTINCT nama_guru FROM kehadiran_guru13 WHERE nama_guru LIKE CONCAT('%', ?, '%') ORDER BY nama_guru LIMIT 20");
$stmt->bind_param("s", $term);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
  $data[] = $row['nama_guru'];
}

echo json_encode($data);
?>
