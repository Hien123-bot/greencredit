<?php
session_start();
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        header("Location: login.php?error=empty");
        exit();
    }

    // Check user in database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Success
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = isset($user['fullname']) ? $user['fullname'] : (isset($user['full_name']) ? $user['full_name'] : $user['username']);
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];
        
        // Kiểm tra nếu có mã QR đang chờ xử lý (từ qr_handler.php)
        if (isset($_SESSION['pending_qr_code'])) {
            $code = $_SESSION['pending_qr_code'];
            header("Location: ../qr_handler.php?code=" . $code);
            exit();
        }

        // Nếu không có mã QR, kiểm tra role
        if ($user['role'] === 'admin') {
            header("Location: ../admin/dashboard.php");
        } else {
            header("Location: ../index.php");
        }
        exit();
    } else {
        // No account or wrong password
        header("Location: login.php?error=invalid");
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}
