<?php
session_start();
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($full_name) || empty($email) || empty($password)) {
        header("Location: register.php?error=empty");
        exit();
    }

    if ($password !== $confirm_password) {
        header("Location: register.php?error=password_mismatch");
        exit();
    }

    // Check if email exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        header("Location: register.php?error=email_exists");
        exit();
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Generate unique username
    $username = explode('@', $email)[0] . '_' . rand(1000, 9999);

    // Insert user
    $stmt = $pdo->prepare("INSERT INTO users (username, full_name, email, password) VALUES (?, ?, ?, ?)");
    $result = $stmt->execute([$username, $full_name, $email, $hashed_password]);

    if ($result) {
        // Redirect to login page after successful registration
        header("Location: login.php?msg=registered");
        exit();
    } else {
        header("Location: register.php?error=system");
        exit();
    }
} else {
    header("Location: register.php");
    exit();
}
