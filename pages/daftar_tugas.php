<?php 
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}
include '../includes/db.php';

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM tugas WHERE user_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Data untuk perhitungan SAW
$tugas = [];
while ($row = mysqli_fetch_assoc($result)) {
    $skor_saw = ($row['prioritas'] * 0.4) + ($row['urgensi'] * 0.3) + ($row['kesulitan'] * 0.3);
    $row['skor_saw'] = round($skor_saw, 2);
    $tugas[] = $row;
}

// Urutkan berdasarkan Skor SAW (dari yang tertinggi ke terendah)
usort($tugas, fn($a, $b) => $b['skor_saw'] <=> $a['skor_saw']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Tugas - TimeMaster</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script>
        function updateStatus(taskId, newStatus) {
            if (confirm("Apakah Anda yakin ingin mengubah status tugas ini?")) {
                fetch('update_status.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `id=${taskId}&status=${newStatus}`
                })
                .then(response => response.text())
                .then(data => {
                    if (data === 'success') {
                        location.reload();
                    } else {
                        alert("❌ Gagal memperbarui status!");
                    }
                });
            }
        }
    </script>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center fw-bold mb-4">📋 Daftar Tugas Anda</h2>

        <div class="card shadow-lg p-4">
            <div class="card-body">
                <h5 class="card-title text-center">Prioritas Tugas Berdasarkan Metode SAW</h5>
                
                <!-- Filter Status -->
                <div class="mb-3">
                    <label class="form-label fw-bold">🔎 Filter Berdasarkan Status</label>
                    <select id="filterStatus" class="form-select">
                        <option value="all">Semua Tugas</option>
                        <option value="Belum Dimulai">🚀 Belum Dimulai</option>
                        <option value="Sedang Berjalan">🏃 Sedang Berjalan</option>
                        <option value="Selesai">✅ Selesai</option>
                    </select>
                </div>

                <table class="table table-hover mt-3">
                    <thead class="table-dark">
                        <tr>
                            <th>📌 Nama Tugas</th>
                            <th>🔥 Prioritas</th>
                            <th>⏳ Urgensi</th>
                            <th>🎯 Kesulitan</th>
                            <th>📅 Deadline</th>
                            <th>🏆 Skor SAW</th>
                            <th>🔄 Status</th>
                            <th>⚙ Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tugas as $row) : ?>
                            <tr class="tugas-row <?= strtolower(str_replace(' ', '-', $row['status'])); ?>">
                                <td><?= htmlspecialchars($row['nama_tugas']); ?></td>
                                <td><?= $row['prioritas']; ?></td>
                                <td><?= $row['urgensi']; ?></td>
                                <td><?= $row['kesulitan']; ?></td>
                                <td><?= $row['tanggal_deadline']; ?></td>
                                <td><strong><?= $row['skor_saw']; ?></strong></td>
                                <td>
                                    <select class="form-select" onchange="updateStatus(<?= $row['id']; ?>, this.value)">
                                        <option value="Belum Dimulai" <?= ($row['status'] == "Belum Dimulai") ? "selected" : "" ?>>🚀 Belum Dimulai</option>
                                        <option value="Sedang Berjalan" <?= ($row['status'] == "Sedang Berjalan") ? "selected" : "" ?>>🏃 Sedang Berjalan</option>
                                        <option value="Selesai" <?= ($row['status'] == "Selesai") ? "selected" : "" ?>>✅ Selesai</option>
                                    </select>
                                </td>
                                <td>
                                    <a href="edit_tugas.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-warning">✏ Edit</a>
                                    <a href="hapus_tugas.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus tugas ini?');">🗑 Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="tambah_tugas.php" class="btn btn-success">➕ Tambah Tugas</a>
            <a href="dashboard.php" class="btn btn-outline-primary">🔙 Kembali ke Dashboard</a>
        </div>
    </div>

    <script>
        document.getElementById('filterStatus').addEventListener('change', function() {
            let status = this.value;
            let rows = document.querySelectorAll('.tugas-row');
            
            rows.forEach(row => {
                if (status === "all" || row.classList.contains(status.toLowerCase().replace(' ', '-'))) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        });
    </script>
</body>
</html>
