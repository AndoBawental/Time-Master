<?php
session_start();
include '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id']) && isset($_POST['status'])) {
    $task_id = $_POST['id'];
    $new_status = $_POST['status'];

    $query = "UPDATE tugas SET status = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "si", $new_status, $task_id);

    if (mysqli_stmt_execute($stmt)) {
        echo "success";
    } else {
        echo "error";
    }
}
?>
