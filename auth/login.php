<?php
session_start();
include '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
        // Gunakan prepared statement untuk keamanan
        $query = "SELECT id, nama, password FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);

            // Verifikasi password
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['nama'] = $user['nama'];

                // Catat login ke log_aktivitas
                $log_query = "INSERT INTO log_aktivitas (user_id, aktivitas) VALUES (?, 'User login ke sistem')";
                $stmt_log = mysqli_prepare($conn, $log_query);
                mysqli_stmt_bind_param($stmt_log, "i", $user['id']);
                mysqli_stmt_execute($stmt_log);

                header("Location: ../pages/dashboard.php");
                exit();
            } else {
                // Jika password salah, buka modal login kembali
                echo "<script>
                        alert('⚠ Login gagal! Password salah.');
                        window.location='../index.php?error=password';
                      </script>";
            }
        } else {
            // Jika email tidak terdaftar, buka modal pendaftaran
            echo "<script>
                    alert('⚠ Login gagal! Email tidak ditemukan, silakan daftar.');
                    window.location='../index.php?error=not_registered';
                  </script>";
        }
    } else {
        echo "<script>
                alert('⚠ Mohon isi email dan password.');
                window.location='../index.php?error=empty_fields';
              </script>";
    }
}
?>
