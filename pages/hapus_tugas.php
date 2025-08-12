<?php
include '../includes/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Cek apakah ID tugas tersedia
if (!isset($_GET['id'])) {
    header("Location: daftar_tugas.php");
    exit();
}

$tugas_id = $_GET['id'];

// Cek apakah tugas milik user yang login
$query = "SELECT user_id FROM tugas WHERE id = $tugas_id";
$result = mysqli_query($conn, $query);
$tugas = mysqli_fetch_assoc($result);

if (!$tugas || $tugas['user_id'] != $_SESSION['user_id']) {
    echo "<script>alert('Anda tidak memiliki akses ke tugas ini!'); window.location='daftar_tugas.php';</script>";
    exit();
}

// Hapus tugas jika valid
$delete_query = "DELETE FROM tugas WHERE id = $tugas_id";
if (mysqli_query($conn, $delete_query)) {
    echo "<script>alert('Tugas berhasil dihapus!'); window.location='daftar_tugas.php';</script>";
} else {
    echo "<script>alert('Gagal menghapus tugas!');</script>";
}
?>
