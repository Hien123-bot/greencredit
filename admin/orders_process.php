<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/db.php';

// Check admin role here...

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = isset($_POST['order_id']) ? (int)$_POST['order_id'] : 0;
    $status = isset($_POST['status']) ? $_POST['status'] : '';

    if ($order_id > 0 && in_array($status, ['completed', 'cancelled'])) {
        try {
            $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
            $stmt->execute([$status, $order_id]);
            
            if ($status === 'completed') {
                $_SESSION['msg'] = "Đã đánh dấu hoàn thành đơn hàng #{$order_id}.";
            } else {
                $_SESSION['msg'] = "Đã hủy đơn hàng #{$order_id}.";
                
                // Refund points if cancelled
                // You can add logic to fetch points_used and add it back to the user's account via a transaction.
            }
        } catch (PDOException $e) {
            $_SESSION['msg'] = "Lỗi xử lý đơn hàng: " . $e->getMessage();
        }
    } else {
        $_SESSION['msg'] = "Dữ liệu không hợp lệ.";
    }
}

header("Location: orders.php");
exit;
