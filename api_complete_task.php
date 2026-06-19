<?php
session_start();
header('Content-Type: application/json');
require_once 'includes/config.php';
require_once 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Bạn cần đăng nhập để làm nhiệm vụ!']);
    exit;
}

$user_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['task_id'])) {
    echo json_encode(['success' => false, 'message' => 'ID nhiệm vụ không hợp lệ!']);
    exit;
}

if (!isset($data['proof']) || empty(trim($data['proof']))) {
    echo json_encode(['success' => false, 'message' => 'Bạn cần cung cấp minh chứng (ảnh hoặc mô tả) để hoàn thành nhiệm vụ này!']);
    exit;
}

$task_id = (int)$data['task_id'];

try {
    $pdo->beginTransaction();

    // 1. Kiểm tra task có tồn tại không
    $stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = ?");
    $stmt->execute([$task_id]);
    $task = $stmt->fetch();

    if (!$task) {
        throw new Exception("Nhiệm vụ không tồn tại!");
    }

    // 2. Kiểm tra user đã hoàn thành chưa
    $stmt = $pdo->prepare("SELECT * FROM user_tasks WHERE user_id = ? AND task_id = ?");
    $stmt->execute([$user_id, $task_id]);
    $user_task = $stmt->fetch();

    if ($user_task && $user_task['status'] === 'completed') {
        throw new Exception("Bạn đã hoàn thành nhiệm vụ này rồi!");
    }

    // 3. Đánh dấu hoàn thành
    if ($user_task) {
        $stmt = $pdo->prepare("UPDATE user_tasks SET status = 'completed', completed_at = NOW() WHERE id = ?");
        $stmt->execute([$user_task['id']]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO user_tasks (user_id, task_id, status, completed_at) VALUES (?, ?, 'completed', NOW())");
        $stmt->execute([$user_id, $task_id]);
    }

    // 4. Cộng điểm cho user
    $points_reward = $task['points'];
    $stmt = $pdo->prepare("UPDATE users SET points = points + ? WHERE id = ?");
    $stmt->execute([$points_reward, $user_id]);

    // 5. Ghi lịch sử
    $description = "Hoàn thành nhiệm vụ: " . $task['title'];
    $stmt = $pdo->prepare("INSERT INTO history (user_id, action_type, points, description) VALUES (?, 'earn', ?, ?)");
    $stmt->execute([$user_id, $points_reward, $description]);

    $pdo->commit();
    
    echo json_encode(['success' => true, 'message' => 'Tuyệt vời! Bạn nhận được +' . $points_reward . ' PTS', 'points' => $points_reward]);

} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
