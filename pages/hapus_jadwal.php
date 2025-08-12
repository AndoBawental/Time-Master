<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: jadwal.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$id = $_GET['id'];

$query = "DELETE FROM jadwal WHERE id = ? AND user_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "ii", $id, $user_id);
if (mysqli_stmt_execute($stmt)) {
    echo "<script>alert('Jadwal berhasil dihapus.'); window.location='jadwal.php';</script>";
} else {
    echo "<script>alert('Gagal menghapus jadwal!'); window.location='jadwal.php';</script>";
}
?>
