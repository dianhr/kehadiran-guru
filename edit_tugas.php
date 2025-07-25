<?php
session_start();
include 'koneksi.php';

$id = $_GET['id'];
$data = $conn->query("SELECT * FROM tugas WHERE id='$id'")->fetch_assoc();

if (isset($_POST['update'])) {
  $judul = $_POST['judul'];
  $deskripsi = $_POST['deskripsi'];

  // File update?
  $file = $data['file'];
  if ($_FILES['file']['name']) {
    $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
    $filename = "tugas_" . time() . "." . $ext;
    move_uploaded_file($_FILES['file']['tmp_name'], "uploads/" . $filename);

    // Hapus file lama
    if ($file && file_exists("uploads/" . $file)) {
      unlink("uploads/" . $file);
    }

    $file = $filename;
  }

  $sql = "UPDATE tugas SET judul='$judul', deskripsi='$deskripsi', file='$file' WHERE id='$id'";
  if ($conn->query($sql)) {
    echo "<script>alert('Tugas berhasil diupdate!'); window.location='dashboard.php';</script>";
  } else {
    echo "Error: " . $conn->error;
  }
}
?>

<h2>Edit Tugas</h2>
<form method="POST" enctype="multipart/form-data">
  <label>Judul</label>
  <input type="text" name="judul" class="form-control" value="<?= htmlspecialchars($data['judul']) ?>" required>
  
  <label>Deskripsi</label>
  <textarea name="deskripsi" class="form-control"><?= htmlspecialchars($data['deskripsi']) ?></textarea>

  <label>File (kosongkan jika tidak ganti)</label>
  <input type="file" name="file" class="form-control">

  <button type="submit" name="update" class="btn btn-primary mt-3">Update</button>
</form>
