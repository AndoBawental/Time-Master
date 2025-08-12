<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}
include '../includes/db.php';

$user_id = $_SESSION['user_id'];
$query_user = "SELECT nama FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $query_user);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result_user = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result_user);
$nama_user = $user['nama'];

// Ambil jumlah tugas berdasarkan status
$query_status = "SELECT status, COUNT(*) as jumlah FROM tugas WHERE user_id = ? GROUP BY status";
$stmt_status = mysqli_prepare($conn, $query_status);
mysqli_stmt_bind_param($stmt_status, "i", $user_id);
mysqli_stmt_execute($stmt_status);
$result_status = mysqli_stmt_get_result($stmt_status);

$task_data = ["Belum Dimulai" => 0, "Sedang Berjalan" => 0, "Selesai" => 0];
while ($row = mysqli_fetch_assoc($result_status)) {
    $task_data[$row['status']] = $row['jumlah'];
}

// Ambil 3 kegiatan terdekat
$query_jadwal = "SELECT judul, tanggal, waktu_mulai FROM jadwal WHERE user_id = ? AND tanggal >= CURDATE() ORDER BY tanggal, waktu_mulai LIMIT 3";
$stmt_jadwal = mysqli_prepare($conn, $query_jadwal);
mysqli_stmt_bind_param($stmt_jadwal, "i", $user_id);
mysqli_stmt_execute($stmt_jadwal);
$result_jadwal = mysqli_stmt_get_result($stmt_jadwal);



// Progress Bar
$total_tugas = array_sum($task_data);
$persen_selesai = ($total_tugas > 0) ? round(($task_data["Selesai"] / $total_tugas) * 100, 2) : 0;

// Notifikasi Deadline
$query_deadline = "SELECT nama_tugas, tanggal_deadline FROM tugas WHERE user_id = ? AND status = 'Belum Dimulai' AND tanggal_deadline <= CURDATE() + INTERVAL 2 DAY";
$stmt_deadline = mysqli_prepare($conn, $query_deadline);
mysqli_stmt_bind_param($stmt_deadline, "i", $user_id);
mysqli_stmt_execute($stmt_deadline);
$result_deadline = mysqli_stmt_get_result($stmt_deadline);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - TimeMaster</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <nav class="bg-dark text-white vh-100 p-3" style="width: 260px; position: fixed; height: 100vh;">
            <h4 class="text-center mb-4">⏳ TimeMaster</h4>
            <ul class="nav flex-column">
                <li class="nav-item"><a href="dashboard.php" class="nav-link text-white">🏠 Dashboard</a></li>
                <li class="nav-item"><a href="daftar_tugas.php" class="nav-link text-white">📋 Daftar Tugas</a></li>
                <li class="nav-item"><a href="tambah_tugas.php" class="nav-link text-white">➕ Tambah Tugas</a></li>
                <li class="nav-item"><a href="jadwal.php" class="nav-link text-white">📅 Jadwal</a></li>
                <li class="nav-item"><a href="profile.php" class="nav-link text-white">👤 Profil</a></li>
                <li class="nav-item"><a href="../auth/logout.php" class="nav-link text-danger">🚪 Logout</a></li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="container p-4" style="margin-left: 270px;">
            <h2 class="fw-bold">👋 Selamat Datang, <?php echo htmlspecialchars($nama_user); ?>!</h2>
            <p>Kelola tugas Anda dan tingkatkan produktivitas dengan TimeMaster.</p>
            
            <!-- Status Tugas -->
            <div class="row text-center">
                <div class="col-md-4">
                    <a href="daftar_tugas.php?status=Belum Dimulai" class="text-white text-decoration-none">
                        <div class="card shadow-lg p-3 mb-3 bg-danger text-white">
                            <h5 class="fw-bold">🚀 Belum Dimulai</h5>
                            <h3><?php echo $task_data["Belum Dimulai"]; ?> Tugas</h3>
                        </div>
                    </a>
                </div>

                <div class="col-md-4">
                    <a href="daftar_tugas.php?status=Sedang Berjalan" class="text-white text-decoration-none">
                        <div class="card shadow-lg p-3 mb-3 bg-warning text-white">
                            <h5 class="fw-bold">🏃 Sedang Berjalan</h5>
                            <h3><?php echo $task_data["Sedang Berjalan"]; ?> Tugas</h3>
                        </div>
                    </a>
                </div>

                <div class="col-md-4">
                    <a href="daftar_tugas.php?status=Selesai" class="text-white text-decoration-none">
                        <div class="card shadow-lg p-3 mb-3 bg-success text-white">
                            <h5 class="fw-bold">✅ Selesai</h5>
                            <h3><?php echo $task_data["Selesai"]; ?> Tugas</h3>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="card shadow p-3 mb-3">
                <h5 class="fw-bold">📈 Progress Tugas</h5>
                <div class="progress" style="height: 25px;">
                    <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $persen_selesai; ?>%;" 
                        aria-valuenow="<?php echo $persen_selesai; ?>" aria-valuemin="0" aria-valuemax="100">
                        <?php echo $persen_selesai; ?>%
                    </div>
                </div>
                <?php if ($persen_selesai == 100): ?>
                    <p class="text-center mt-2 text-success fw-bold">🎉 Selamat! Semua tugas selesai!</p>
                <?php endif; ?>
            </div>

        <!-- Jadwal Kegiatan -->
<div class="card shadow p-4 mt-3">
    <h5 class="fw-bold">📅 Jadwal Kegiatan Terdekat</h5>
    <?php if (mysqli_num_rows($result_jadwal) > 0): ?>
        <ul class="list-group list-group-flush mt-2">
            <?php while ($jadwal = mysqli_fetch_assoc($result_jadwal)) : ?>
                <li class="list-group-item">
                    <strong><?= htmlspecialchars($jadwal['judul']) ?></strong> <br>
                    🗓 <?= date("d M Y", strtotime($jadwal['tanggal'])) ?> ⏰ <?= substr($jadwal['waktu_mulai'], 0, 5) ?>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p class="text-muted mt-2">Belum ada jadwal kegiatan terdekat.</p>
    <?php endif; ?>
</div>



<div class="text-end mt-3">
    <a href="jadwal.php" class="btn btn-outline-secondary btn-sm">📅 Lihat Semua Jadwal</a>
</div>
            
            <!-- Notifikasi Deadline -->
            <?php if (mysqli_num_rows($result_deadline) > 0): ?>
                <div class="alert alert-warning">
                    <strong>⚠ Tugas Mendekati Deadline!</strong>
                    <ul>
                        <?php while ($row = mysqli_fetch_assoc($result_deadline)) : ?>
                            <li><?php echo htmlspecialchars($row['nama_tugas']) . " - Deadline: " . $row['tanggal_deadline']; ?></li>
                        <?php endwhile; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- Grafik Statistik -->
            <div class="card shadow p-4 mt-3">
                <h5 class="fw-bold">📊 Statistik Tugas</h5>
                <canvas id="taskChart"></canvas>
            </div>
        </div>
    </div>

    <script>
        var ctx = document.getElementById('taskChart').getContext('2d');
        var taskChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ["Belum Dimulai", "Sedang Berjalan", "Selesai"],
                datasets: [{
                    data: [<?php echo $task_data["Belum Dimulai"]; ?>, <?php echo $task_data["Sedang Berjalan"]; ?>, <?php echo $task_data["Selesai"]; ?>],
                    backgroundColor: ['#dc3545', '#ffc107', '#28a745']
                }]
            }
        });
    </script>
</body>
</html>
