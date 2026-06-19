<?php
session_start();
header('Content-Type: application/json');
require_once '../services/QRService.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập.']);
        exit;
    }

    $token = $_POST['token'] ?? '';
    $user_id = $_SESSION['user_id'];

    $qrService = new QRService();
    $result = $qrService->processScan($user_id, $token);

    echo json_encode($result);
} else {
    echo json_encode(['success' => false, 'message' => 'Yêu cầu không hợp lệ.']);
}
