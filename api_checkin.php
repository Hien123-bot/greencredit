ng<?php
session_start();
header('Content-Type: application/json');
require_once 'includes/config.php';
require_once 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Bạn cần đăng nhập để điểm danh!']);
    exit;
}

$user_id = $_SESSION['user_id'];
$points_reward = 50;

try {
    $pdo->beginTransaction();

    // Kiểm tra xem hôm nay đã điểm danh chưa
    $stmt = $pdo->prepare("SELECT id FROM history WHERE user_id = ? AND action_type = 'earn' AND description = 'Điểm danh hằng ngày' AND DATE(created_at) = CURDATE()");
    $stmt->execute([$user_id]);
    
    if ($stmt->fetch()) {
        throw new Exception("Hôm nay bạn đã điểm danh rồi. Hãy quay lại vào ngày mai nhé!");
    }

    // Cộng điểm
    $stmt = $pdo->prepare("UPDATE users SET points = points + ? WHERE id = ?");
    $stmt->execute([$points_reward, $user_id]);

    // Ghi log
    $stmt = $pdo->prepare("INSERT INTO history (user_id, action_type, points, description) VALUES (?, 'earn', ?, 'Điểm danh hằng ngày')");
    $stmt->execute([$user_id, $points_reward]);

    $pdo->commit();
    
    echo json_encode(['success' => true, 'message' => 'Điểm danh thành công! Bạn nhận được +' . $points_reward . ' PTS.', 'points' => $points_reward]);

} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
