<?php
include '../includes/db.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $nama_tugas = mysqli_real_escape_string($conn, $_POST['nama_tugas']);
    $prioritas = $_POST['prioritas'];
    $urgensi = $_POST['urgensi'];
    $kesulitan = $_POST['kesulitan'];
    $deadline = $_POST['tanggal_deadline'];
    $status = $_POST['status'];

    $query = "INSERT INTO tugas (user_id, nama_tugas, prioritas, urgensi, kesulitan, tanggal_deadline, status) 
              VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "isiiiss", $user_id, $nama_tugas, $prioritas, $urgensi, $kesulitan, $deadline, $status);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>
                alert('✅ Tugas berhasil ditambahkan!');
                window.location='daftar_tugas.php';
              </script>";
    } else {
        echo "<script>alert('❌ Gagal menambahkan tugas!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Tugas - TimeMaster</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 600px;
        }
        .card {
            border-radius: 10px;
        }
        .btn-reset {
            background-color: #f8f9fa;
            border: 1px solid #6c757d;
        }
        .btn-reset:hover {
            background-color: #e9ecef;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center fw-bold mb-4 text-primary">➕ Tambah Tugas Baru</h2>

        <div class="card shadow-lg p-4">
            <div class="card-body">
                <form method="POST" onsubmit="return validateForm()">
                    <div class="mb-3">
                        <label class="form-label fw-bold">📌 Nama Tugas</label>
                        <input type="text" name="nama_tugas" id="nama_tugas" class="form-control" placeholder="Masukkan nama tugas..." required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">🔥 Prioritas (1-10)</label>
                            <input type="number" name="prioritas" id="prioritas" class="form-control" min="1" max="10" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">⏳ Urgensi (1-10)</label>
                            <input type="number" name="urgensi" id="urgensi" class="form-control" min="1" max="10" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">🎯 Kesulitan (1-10)</label>
                            <input type="number" name="kesulitan" id="kesulitan" class="form-control" min="1" max="10" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">📅 Tanggal Deadline</label>
                            <input type="date" name="tanggal_deadline" id="tanggal_deadline" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">📌 Status Tugas</label>
                        <select name="status" class="form-select">
                            <option value="Belum Dimulai">🔴 Belum Dimulai</option>
                            <option value="Sedang Berjalan">🟡 Sedang Berjalan</option>
                            <option value="Selesai">🟢 Selesai</option>
                        </select>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="reset" class="btn btn-reset">🔄 Reset</button>
                        <button type="submit" class="btn btn-success">✔ Simpan Tugas</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="daftar_tugas.php" class="btn btn-outline-primary">🔙 Kembali ke Daftar Tugas</a>
        </div>
    </div>

    <script>
        function validateForm() {
            let deadline = document.getElementById("tanggal_deadline").value;
            let today = new Date().toISOString().split('T')[0];

            if (deadline < today) {
                alert("⚠ Tanggal deadline tidak boleh lebih kecil dari hari ini!");
                return false;
            }
            return true;
        }
    </script>
</body>
</html>
