<?php
include '../includes/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: daftar_tugas.php");
    exit();
}

$tugas_id = $_GET['id'];
$query = "SELECT * FROM tugas WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $tugas_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$tugas = mysqli_fetch_assoc($result);

if (!$tugas || $tugas['user_id'] != $_SESSION['user_id']) {
    echo "<script>alert('Anda tidak memiliki akses ke tugas ini!'); window.location='daftar_tugas.php';</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_tugas = mysqli_real_escape_string($conn, $_POST['nama_tugas']);
    $prioritas = $_POST['prioritas'];
    $urgensi = $_POST['urgensi'];
    $kesulitan = $_POST['kesulitan'];
    $tanggal_deadline = $_POST['tanggal_deadline'];
    $status = $_POST['status'];

    $update_query = "UPDATE tugas SET 
                     nama_tugas=?, 
                     prioritas=?, 
                     urgensi=?, 
                     kesulitan=?, 
                     tanggal_deadline=?, 
                     status=? 
                     WHERE id=?";
                     
    $stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($stmt, "siiiisi", $nama_tugas, $prioritas, $urgensi, $kesulitan, $tanggal_deadline, $status, $tugas_id);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('✅ Tugas berhasil diperbarui!'); window.location='daftar_tugas.php';</script>";
    } else {
        echo "<script>alert('❌ Gagal memperbarui tugas!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Tugas - TimeMaster</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            border-radius: 10px;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .btn-danger {
            background-color: #dc3545;
        }
        .btn-danger:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center fw-bold mb-4 text-primary">✏ Edit Tugas</h2>

        <div class="card shadow-lg p-4">
            <div class="card-body">
                <form method="POST" id="editForm" onsubmit="return confirm('Apakah Anda yakin ingin menyimpan perubahan ini?');">
                    <div class="mb-3">
                        <label class="form-label fw-bold">📌 Nama Tugas</label>
                        <input type="text" name="nama_tugas" class="form-control" id="nama_tugas" value="<?= htmlspecialchars($tugas['nama_tugas']); ?>" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">🔥 Prioritas (1-10)</label>
                            <input type="number" name="prioritas" class="form-control" id="prioritas" min="1" max="10" value="<?= $tugas['prioritas']; ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">⏳ Urgensi (1-10)</label>
                            <input type="number" name="urgensi" class="form-control" id="urgensi" min="1" max="10" value="<?= $tugas['urgensi']; ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">🎯 Kesulitan (1-10)</label>
                            <input type="number" name="kesulitan" class="form-control" id="kesulitan" min="1" max="10" value="<?= $tugas['kesulitan']; ?>" required>
                        </div>
                    </div>

                    <div class="mb-3 mt-3">
                        <label class="form-label fw-bold">📅 Deadline</label>
                        <input type="date" name="tanggal_deadline" class="form-control" id="tanggal_deadline" value="<?= $tugas['tanggal_deadline']; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">🔄 Status Tugas</label>
                        <select name="status" class="form-control" id="status">
                            <option value="Belum Dimulai" <?= ($tugas['status'] == "Belum Dimulai") ? "selected" : "" ?>>🚀 Belum Dimulai</option>
                            <option value="Sedang Berjalan" <?= ($tugas['status'] == "Sedang Berjalan") ? "selected" : "" ?>>🏃 Sedang Berjalan</option>
                            <option value="Selesai" <?= ($tugas['status'] == "Selesai") ? "selected" : "" ?>>✅ Selesai</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-success w-50">✔ Simpan Perubahan</button>
                        <button type="button" class="btn btn-danger w-50" onclick="resetForm()">🔄 Reset</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="daftar_tugas.php" class="btn btn-outline-primary">🔙 Kembali ke Daftar Tugas</a>
        </div>
    </div>

    <script>
        // Menyesuaikan warna input berdasarkan nilai
        document.querySelectorAll('input[type="number"]').forEach(input => {
            input.addEventListener('input', function() {
                let value = parseInt(this.value);
                if (value >= 8) {
                    this.style.backgroundColor = '#f8d7da';
                } else if (value >= 5) {
                    this.style.backgroundColor = '#fff3cd';
                } else {
                    this.style.backgroundColor = '#d4edda';
                }
            });
        });

        // Reset form kembali ke nilai awal
        function resetForm() {
            document.getElementById('editForm').reset();
        }
    </script>
</body>
</html>
