<?php
session_start();
header('Content-Type: application/json');
require_once 'includes/config.php';
require_once 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'];
$action = $_GET['action'] ?? 'get';

if ($action === 'save') {
    $data = json_decode(file_get_contents('php://input'), true);
    $cart = $data['cart'] ?? [];
    
    try {
        $pdo->beginTransaction();
        $stmt = $pdo->prepare("DELETE FROM cart_items WHERE user_id = ?");
        $stmt->execute([$user_id]);
        
        $counts = [];
        foreach ($cart as $product) {
            if (!isset($product['id'])) continue;
            $pid = $product['id'];
            if (!isset($counts[$pid])) $counts[$pid] = 0;
            $counts[$pid]++;
        }
        
        foreach ($counts as $pid => $qty) {
            $stmt = $pdo->prepare("INSERT INTO cart_items (user_id, product_id, quantity) VALUES (?, ?, ?)");
            $stmt->execute([$user_id, $pid, $qty]);
        }
        
        $pdo->commit();
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} elseif ($action === 'get') {
    try {
        $stmt = $pdo->prepare("SELECT c.quantity, p.* FROM cart_items c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?");
        $stmt->execute([$user_id]);
        $db_cart = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $formatted_cart = [];
        foreach ($db_cart as $item) {
            for ($i=0; $i<$item['quantity']; $i++) {
                $formatted_cart[] = [
                    'id' => (int)$item['id'],
                    'name' => $item['name'],
                    'price_vnd' => (int)$item['price_vnd'],
                    'max_pts' => (int)$item['max_pts'],
                    'image' => $item['image'],
                    'category' => $item['category']
                ];
            }
        }
        
        echo json_encode(['success' => true, 'cart' => $formatted_cart]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
