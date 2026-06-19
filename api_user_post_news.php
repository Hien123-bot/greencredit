<?php
session_start();
header('Content-Type: application/json');
require_once 'includes/config.php';
require_once 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Bạn cần đăng nhập để đăng bài!']);
    exit;
}

$user_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['title']) || empty(trim($data['title']))) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng nhập tiêu đề bài viết!']);
    exit;
}

$title = trim($data['title']);
$image = trim($data['image'] ?? '');
$description = trim($data['description'] ?? '');
$content = trim($data['content'] ?? '');

try {
    $stmt = $pdo->prepare("INSERT INTO news (author_id, title, tag, image, description, content, status, reading_time, is_featured) VALUES (?, ?, 'Cộng đồng', ?, ?, ?, 'pending', 5, 0)");
    $stmt->execute([$user_id, $title, $image, $description, $content]);
    
    echo json_encode(['success' => true, 'message' => 'Gửi bài viết thành công! Vui lòng chờ Ban quản trị duyệt.']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi lưu dữ liệu: ' . $e->getMessage()]);
}
?>
