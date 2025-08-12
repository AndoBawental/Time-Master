<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: jadwal.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$id = $_GET['id'];

// Ambil data jadwal yang akan diedit
$query = "SELECT * FROM jadwal WHERE id = ? AND user_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "ii", $id, $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$jadwal = mysqli_fetch_assoc($result);

if (!$jadwal) {
    echo "<script>alert('Jadwal tidak ditemukan!'); window.location='jadwal.php';</script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul = $_POST['judul'];
    $tanggal = $_POST['tanggal'];
    $waktu_mulai = $_POST['waktu_mulai'];
    $waktu_selesai = $_POST['waktu_selesai'];
    $keterangan = $_POST['keterangan'];

    $update = "UPDATE jadwal SET judul = ?, tanggal = ?, waktu_mulai = ?, waktu_selesai = ?, keterangan = ? WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $update);
    mysqli_stmt_bind_param($stmt, "ssssssi", $judul, $tanggal, $waktu_mulai, $waktu_selesai, $keterangan, $id, $user_id);
    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Jadwal berhasil diperbarui!'); window.location='jadwal.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui jadwal!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Jadwal - TimeMaster</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2 class="fw-bold text-center mb-4 text-primary">✏️ Edit Jadwal</h2>
    <div class="card shadow p-4">
        <form method="POST">
            <div class="mb-3">
                <label>📝 Judul</label>
                <input type="text" name="judul" class="form-control" value="<?= $jadwal['judul'] ?>" required>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label>📅 Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" value="<?= $jadwal['tanggal'] ?>" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label>⏰ Waktu Mulai</label>
                    <input type="time" name="waktu_mulai" class="form-control" value="<?= $jadwal['waktu_mulai'] ?>" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label>⏱ Waktu Selesai</label>
                    <input type="time" name="waktu_selesai" class="form-control" value="<?= $jadwal['waktu_selesai'] ?>" required>
                </div>
            </div>
            <div class="mb-3">
                <label>🗒️ Keterangan</label>
                <textarea name="keterangan" class="form-control"><?= $jadwal['keterangan'] ?></textarea>
            </div>
            <div class="d-flex justify-content-between">
                <a href="jadwal.php" class="btn btn-outline-secondary">🔙 Kembali</a>
                <button type="submit" class="btn btn-primary">✔ Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
