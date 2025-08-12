<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}
include '../includes/db.php';

$user_id = $_SESSION['user_id'];

// Ambil data pengguna
$query = "SELECT nama, email, foto_profil FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

// Update Profil
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

    if ($password) {
        $update_query = "UPDATE users SET nama = ?, email = ?, password = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($stmt, "sssi", $nama, $email, $password, $user_id);
    } else {
        $update_query = "UPDATE users SET nama = ?, email = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($stmt, "ssi", $nama, $email, $user_id);
    }

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['nama'] = $nama;
        header("Location: profile.php?success=1");
        exit();
    } else {
        echo "<script>alert('❌ Gagal memperbarui profil!');</script>";
    }
}

// Upload Foto Profil
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['foto_profil'])) {
    $target_dir = "../uploads/";
    if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);

    $target_file = $target_dir . basename($_FILES["foto_profil"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($imageFileType, $allowed_types)) {
        if (move_uploaded_file($_FILES["foto_profil"]["tmp_name"], $target_file)) {
            $update_foto = "UPDATE users SET foto_profil = ? WHERE id = ?";
            $stmt = mysqli_prepare($conn, $update_foto);
            mysqli_stmt_bind_param($stmt, "si", $target_file, $user_id);
            mysqli_stmt_execute($stmt);
            header("Location: profile.php?success=1");
            exit();
        } else {
            echo "<script>alert('❌ Gagal mengunggah foto!');</script>";
        }
    } else {
        echo "<script>alert('❌ Format file tidak didukung!');</script>";
    }
}

// Hapus Foto Profil
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['hapus_foto'])) {
    $default_avatar = "../assets/default-avatar.png";
    if (!empty($user['foto_profil']) && file_exists($user['foto_profil'])) {
        unlink($user['foto_profil']);
    }
    $update_foto = "UPDATE users SET foto_profil = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $update_foto);
    mysqli_stmt_bind_param($stmt, "si", $default_avatar, $user_id);
    mysqli_stmt_execute($stmt);
    header("Location: profile.php?success=1");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - TimeMaster</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .profile-container {
            max-width: 500px;
            margin: auto;
        }
        .password-container {
            position: relative;
        }
        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }
        .profile-img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #007bff;
        }
    </style>
</head>
<body>
    <div class="container mt-5 profile-container">
        <h2 class="text-center fw-bold mb-4 text-primary">👤 Profil Saya</h2>

        <div class="card shadow-lg p-4">
            <div class="card-body text-center">
                <img src="<?php echo !empty($user['foto_profil']) ? $user['foto_profil'] : '../assets/default-avatar.png'; ?>" 
                     class="profile-img mb-3" alt="Foto Profil">
                
                <form method="POST" enctype="multipart/form-data" class="mb-3">
                    <input type="file" name="foto_profil" class="form-control mb-2" accept="image/*">
                    <button type="submit" class="btn btn-secondary btn-sm">🖼️ Upload Foto</button>
                </form>

                <?php if (!empty($user['foto_profil']) && $user['foto_profil'] !== '../assets/default-avatar.png') : ?>
                    <form method="POST">
                        <button type="submit" name="hapus_foto" class="btn btn-danger btn-sm">❌ Hapus Foto</button>
                    </form>
                <?php endif; ?>

                <form method="POST" id="profileForm">
                    <?php if (isset($_GET['success'])) : ?>
                        <div class="alert alert-success">✅ Profil berhasil diperbarui!</div>
                    <?php endif; ?>
                    <div class="mb-3">
                        <label class="form-label fw-bold">📌 Nama</label>
                        <input type="text" name="nama" class="form-control" value="<?php echo htmlspecialchars($user['nama']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">📧 Email</label>
                        <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                    <div class="mb-3 password-container">
                        <label class="form-label fw-bold">🔑 Password (Kosongkan jika tidak ingin mengubah)</label>
                        <input type="password" name="password" id="password" class="form-control">
                        <span class="toggle-password" onclick="togglePassword()">👁️</span>
                    </div>
                    <button type="reset" class="btn btn-secondary w-100 mb-2">🔄 Reset</button>
                    <button type="submit" name="update_profile" class="btn btn-primary w-100" onclick="return confirm('Apakah Anda yakin ingin menyimpan perubahan?')">✔ Simpan Perubahan</button>
                </form>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="dashboard.php" class="btn btn-outline-primary">🏠 Kembali ke Dashboard</a>
        </div>
    </div>

    <script>
        function togglePassword() {
            var passwordField = document.getElementById("password");
            passwordField.type = passwordField.type === "password" ? "text" : "password";
        }
    </script>
</body>
</html>
