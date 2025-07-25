<?php
include 'koneksi.php';

// Ambil data form
$ruangan_kelas = $_POST['ruangan_kelas'];
$nama_guru = $_POST['nama_guru'];
$mata_pelajaran = $_POST['mata_pelajaran'];
$jam_masuk = $_POST['jam_masuk'];
$jam_keluar = $_POST['jam_keluar'];
$status_kehadiran = $_POST['status_kehadiran'];
$keterangan_tugas = $_POST['keterangan_tugas'];

// Handle upload file
$target_dir = "uploads/";
$tugas_name = "";

if (isset($_FILES["tugas"]) && $_FILES["tugas"]["error"] == 0) {
    $tugas_name = basename($_FILES["tugas"]["name"]);
    $target_file = $target_dir . $tugas_name;

    if (move_uploaded_file($_FILES["tugas"]["tmp_name"], $target_file)) {
        // File berhasil diupload
    } else {
        echo "Gagal upload file tugas.";
        exit;
    }
}

// Insert ke database
$sql = "INSERT INTO kehadiran_guru13 (ruangan_kelas, nama_guru, mata_pelajaran, jam_masuk, jam_keluar, status_kehadiran, tugas, keterangan_tugas) 
VALUES ('$ruangan_kelas', '$nama_guru', '$mata_pelajaran', '$jam_masuk', '$jam_keluar', '$status_kehadiran', '$tugas_name', '$keterangan_tugas')";

if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Data kehadiran berhasil disimpan!'); window.location.href='index.php';</script>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
