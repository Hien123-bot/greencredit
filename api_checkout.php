<?php
session_start();
header('Content-Type: application/json');
require_once 'includes/config.php';
require_once 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Bạn cần đăng nhập để thanh toán!']);
    exit;
}

$user_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['cart']) || empty($data['cart'])) {
    echo json_encode(['success' => false, 'message' => 'Giỏ hàng trống!']);
    exit;
}

$use_points = isset($data['use_points']) ? (bool)$data['use_points'] : false;
$cart = $data['cart'];
$ptsToVnd = PTS_TO_VND;

try {
    $pdo->beginTransaction();

    // 1. Get user current points
    $stmt = $pdo->prepare("SELECT points FROM users WHERE id = ? FOR UPDATE");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
    $user_points = $user['points'];

    // 2. Calculate totals and validate products
    $subtotal = 0;
    $max_pts_usable = 0;
    
    foreach ($cart as $item) {
        // Validate product exists
        $stmt = $pdo->prepare("SELECT id, price_vnd, max_pts FROM products WHERE id = ?");
        $stmt->execute([$item['id']]);
        $product = $stmt->fetch();
        
        if (!$product) {
            throw new Exception("Sản phẩm không hợp lệ hoặc không tồn tại.");
        }
        
        $subtotal += $product['price_vnd'];
        $max_pts_usable += $product['max_pts'];
    }

    $discount = 0;
    $pts_used = 0;
    
    if ($use_points) {
        $pts_used = min($user_points, $max_pts_usable);
        $discount = $pts_used * $ptsToVnd;
    }

    $total = $subtotal - $discount;

    // 3. Create Order
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, subtotal_vnd, discount_vnd, points_used, total_vnd, status) VALUES (?, ?, ?, ?, ?, 'completed')");
    $stmt->execute([$user_id, $subtotal, $discount, $pts_used, $total]);
    $order_id = $pdo->lastInsertId();

    // 4. Create Order Items
    foreach ($cart as $item) {
        $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price_vnd) VALUES (?, ?, 1, ?)");
        $stmt->execute([$order_id, $item['id'], $item['price_vnd']]);
    }

    // 5. Deduct points & log history if points used
    if ($pts_used > 0) {
        $stmt = $pdo->prepare("UPDATE users SET points = points - ? WHERE id = ?");
        $stmt->execute([$pts_used, $user_id]);

        $description = "Sử dụng điểm cho đơn hàng #" . $order_id;
        $stmt = $pdo->prepare("INSERT INTO history (user_id, action_type, points, description) VALUES (?, 'spend', ?, ?)");
        $stmt->execute([$user_id, $pts_used, $description]);
    }

    // 6. Clear cart_items
    $stmt = $pdo->prepare("DELETE FROM cart_items WHERE user_id = ?");
    $stmt->execute([$user_id]);

    $pdo->commit();
    echo json_encode(['success' => true, 'message' => 'Thanh toán thành công!']);

} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
}
