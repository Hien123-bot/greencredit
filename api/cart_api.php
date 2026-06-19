<?php
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id = $_POST['id'] ?? null;

    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

    switch ($action) {
        case 'add':
            $name = $_POST['name'] ?? '';
            $points = $_POST['points'] ?? 0;
            $image = $_POST['image'] ?? '';
            
            $found = false;
            foreach ($_SESSION['cart'] as &$item) {
                if ($item['id'] == $id) {
                    $item['quantity']++;
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $_SESSION['cart'][] = [
                    'id' => $id,
                    'name' => $name,
                    'points' => $points,
                    'image' => $image,
                    'quantity' => 1
                ];
            }
            echo json_encode(['success' => true, 'message' => 'Đã thêm vào giỏ hàng']);
            break;

        case 'remove':
            foreach ($_SESSION['cart'] as $key => $item) {
                if ($item['id'] == $id) {
                    unset($_SESSION['cart'][$key]);
                    break;
                }
            }
            $_SESSION['cart'] = array_values($_SESSION['cart']);
            echo json_encode(['success' => true]);
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Hành động không hợp lệ']);
    }
}
