<?php
include '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Cek apakah ada field kosong
    if (empty($nama) || empty($email) || empty($password)) {
        echo "<script>
                alert('⚠ Harap isi semua kolom!');
                window.location='../index.php?error=empty_fields';
              </script>";
        exit();
    }

    // Cek apakah email sudah terdaftar
    $check_email_query = "SELECT id FROM users WHERE email = ?";
    $stmt_check = mysqli_prepare($conn, $check_email_query);
    mysqli_stmt_bind_param($stmt_check, "s", $email);
    mysqli_stmt_execute($stmt_check);
    $result_check = mysqli_stmt_get_result($stmt_check);

    if (mysqli_num_rows($result_check) > 0) {
        echo "<script>
                alert('⚠ Email sudah terdaftar! Silakan gunakan email lain.');
                window.location='../index.php?error=email_exists';
              </script>";
        exit();
    }

    // Hash password sebelum disimpan
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Simpan data ke database menggunakan prepared statement
    $query = "INSERT INTO users (nama, email, password) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sss", $nama, $email, $hashed_password);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>
                alert('✅ Registrasi berhasil! Silakan login.');
                window.location='../index.php?success=registered';
              </script>";
    } else {
        echo "<script>
                alert('❌ Registrasi gagal! Silakan coba lagi.');
                window.location='../index.php?error=registration_failed';
              </script>";
    }
}
?>
