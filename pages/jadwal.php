<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$jadwal = [];

$query = "SELECT * FROM jadwal WHERE user_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

while ($row = mysqli_fetch_assoc($result)) {
    $jadwal[] = [
        'title' => $row['judul'],
        'start' => $row['tanggal'] . 'T' . $row['waktu_mulai'],
        'end'   => $row['tanggal'] . 'T' . $row['waktu_selesai'],
        'color' => '#0d6efd'
    ];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Jadwal Saya - TimeMaster</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.2/main.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .btn-sm {
            margin-right: 4px;
        }
    </style>
</head>
<body>

<div class="container py-5">
    <h2 class="text-center fw-bold mb-5 text-primary">📆 Jadwal Kegiatan Anda</h2>

    <!-- Tabel Jadwal -->
    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white fw-bold">📋 Daftar Jadwal</div>
        <div class="card-body p-0">
            <table class="table table-striped m-0">
                <thead class="table-light text-center">
                    <tr>
                        <th>Judul</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    <?php
                    mysqli_data_seek($result, 0);
                    while ($row = mysqli_fetch_assoc($result)) :
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($row['judul']) ?></td>
                            <td><?= $row['tanggal'] ?></td>
                            <td><?= substr($row['waktu_mulai'], 0, 5) . ' - ' . substr($row['waktu_selesai'], 0, 5) ?></td>
                            <td>
                                <a href="edit_jadwal.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">✏ Edit</a>
                                <a href="hapus_jadwal.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus jadwal ini?')">🗑 Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Kalender -->
    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white fw-bold">📅 Kalender Jadwal</div>
        <div class="card-body">
            <div id="calendar"></div>
        </div>
    </div>

    <!-- Tombol -->
    <div class="d-flex justify-content-between">
        <a href="dashboard.php" class="btn btn-outline-primary">🔙 Kembali ke Dashboard</a>
        <a href="tambah_jadwal.php" class="btn btn-success">➕ Tambah Jadwal</a>
    </div>
</div>

<!-- Script Kalender -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.2/main.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let calendarEl = document.getElementById('calendar');
        let calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: <?= json_encode($jadwal); ?>,
            eventClick: function (info) {
                alert("📌 " + info.event.title + "\n🕒 " + info.event.start.toLocaleString());
            }
        });
        calendar.render();
    });
</script>

</body>
</html>
