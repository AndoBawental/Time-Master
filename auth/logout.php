<?php
session_start();
session_unset();  // Hapus semua variabel session
session_destroy(); // Hancurkan session

// Mencegah pengguna kembali dengan tombol "Back"
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Expires: 0");
header("Pragma: no-cache");

// Redirect ke halaman login atau beranda
header("Location: ../index.php");
exit();
?>
