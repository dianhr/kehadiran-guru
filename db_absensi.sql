-- Buat database
CREATE DATABASE IF NOT EXISTS db_absensi13;
USE db_absensi13;

-- Tabel users
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL,
  password VARCHAR(50) NOT NULL,
  role ENUM('admin', 'guru', 'siswa') NOT NULL
);

INSERT INTO users (username, password, role) VALUES
('dian', 'dian', 'admin'),
('budi', 'budi', 'guru'),
('agus', 'agus', 'siswa');

-- Tabel kehadiran
CREATE TABLE IF NOT EXISTS kehadiran_guru13 (
  id INT AUTO_INCREMENT PRIMARY KEY,
  ruangan_kelas VARCHAR(100) NOT NULL,
  nama_guru VARCHAR(100) NOT NULL,
  mata_pelajaran VARCHAR(100) NOT NULL,
  jam_masuk TIME NOT NULL,
  jam_keluar TIME NOT NULL,
  status_kehadiran ENUM('Hadir', 'Izin', 'Sakit') NOT NULL,
  tugas VARCHAR(255) DEFAULT NULL,
  keterangan_tugas TEXT,
  tanggal DATE DEFAULT CURRENT_DATE
);

-- Contoh data dummy
INSERT INTO kehadiran_guru13 (ruangan_kelas, nama_guru, mata_pelajaran, jam_masuk, jam_keluar, status_kehadiran, tugas, keterangan_tugas, tanggal)
VALUES
('Kelas 7A', 'budi', 'Matematika', '07:00', '09:00', 'Hadir', NULL, 'Materi Aljabar', CURDATE()),
('Kelas 8B', 'budi', 'IPA', '09:00', '11:00', 'Hadir', NULL, 'Tugas Biologi', CURDATE());
