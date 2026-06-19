<?php
session_start();
header('Content-Type: application/json');
require_once '../services/OrderService.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập.']);
        exit;
    }

    $user_id = $_SESSION['user_id'];
    // In a real app, we'd fetch cart items from session or DB
    // This is a simplified version for the 'full build' request
    $cart_items = $_POST['items'] ?? []; 
    $total_points = $_POST['total_points'] ?? 0;

    $orderService = new OrderService();
    $result = $orderService->createOrder($user_id, $cart_items, $total_points);

    echo json_encode($result);
} else {
    echo json_encode(['success' => false, 'message' => 'Yêu cầu không hợp lệ.']);
}
