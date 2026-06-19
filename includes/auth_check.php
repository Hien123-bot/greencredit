<?php
// includes/auth_check.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function checkLogin() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: auth/login.php");
        exit();
    }
}

function checkAdmin() {
    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
        header("Location: ../index.php");
        exit();
    }
}
?>
