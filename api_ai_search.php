<?php
session_start();
header('Content-Type: application/json');
require_once 'includes/config.php';
require_once 'includes/db.php';

// Giả lập delay của AI
sleep(2);

// Mock data response based on random product
try {
    $stmt = $pdo->query("SELECT id, name, category FROM products ORDER BY RAND() LIMIT 1");
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        echo json_encode([
            'success' => true,
            'message' => 'Phân tích hình ảnh hoàn tất.',
            'data' => [
                'detected_keyword' => mb_strtolower($product['name']),
                'category' => $product['category'],
                'confidence' => rand(80, 99) . '%'
            ]
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Không tìm thấy sản phẩm phù hợp trong hệ thống.'
        ]);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi kết nối cơ sở dữ liệu.']);
}
?>
