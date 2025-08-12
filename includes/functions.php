<?php
// Cek apakah user sudah login
function checkLogin() {
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../auth/login.php");
        exit();
    }
}

// Redirect jika sudah login
function redirectIfLoggedIn() {
    session_start();
    if (isset($_SESSION['user_id'])) {
        header("Location: ../pages/dashboard.php");
        exit();
    }
}
?>
