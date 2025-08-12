<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $tanggal = $_POST['tanggal'];
    $waktu_mulai = $_POST['waktu_mulai'];
    $waktu_selesai = $_POST['waktu_selesai'];
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);

    $query = "INSERT INTO jadwal (user_id, judul, tanggal, waktu_mulai, waktu_selesai, keterangan)
              VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "isssss", $user_id, $judul, $tanggal, $waktu_mulai, $waktu_selesai, $keterangan);
    mysqli_stmt_execute($stmt);

    echo "<script>alert('✅ Jadwal berhasil ditambahkan!'); window.location='jadwal.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Jadwal - TimeMaster</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="fw-bold text-center mb-4 text-primary">➕ Tambah Jadwal Kegiatan</h2>
        <div class="card shadow p-4">
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">📝 Judul Kegiatan</label>
                    <input type="text" name="judul" class="form-control" required>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">📅 Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">⏰ Waktu Mulai</label>
                        <input type="time" name="waktu_mulai" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">⏱ Waktu Selesai</label>
                        <input type="time" name="waktu_selesai" class="form-control" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">🗒️ Keterangan (Opsional)</label>
                    <textarea name="keterangan" class="form-control" rows="3"></textarea>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="jadwal.php" class="btn btn-outline-secondary">🔙 Kembali</a>
                    <button type="submit" class="btn btn-success">✔ Simpan</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
