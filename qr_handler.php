<?php
// qr_handler.php
session_start();
require_once 'includes/config.php';
require_once 'includes/db.php';

// 1. Lấy mã QR từ URL (Ví dụ: qr_handler.php?code=GREEN_2026_XYZ)
$code = isset($_GET['code']) ? trim($_GET['code']) : '';

if (empty($code)) {
    header("Location: scan_qr.php");
    exit();
}

// Lưu mã vào session để xử lý sau khi đăng nhập/đăng ký nếu cần
$_SESSION['pending_qr_code'] = $code;

// 2. Kiểm tra người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    // Nếu chưa đăng nhập -> Chuyển hướng đến trang đăng ký
    header("Location: auth/register.php?msg=scan_success");
    exit();
}

$user_id = $_SESSION['user_id'];

// Kiểm tra xem User ID này có thực sự tồn tại trong DB không (tránh lỗi khóa ngoại nếu DB vừa bị xóa)
$stmtUser = $pdo->prepare("SELECT id FROM users WHERE id = ?");
$stmtUser->execute([$user_id]);
if (!$stmtUser->fetch()) {
    session_destroy();
    header("Location: auth/login.php?error=session_invalid");
    exit();
}

try {
    // 3. Kiểm tra mã QR trong database
    $stmt = $pdo->prepare("SELECT * FROM qr_codes WHERE code = ?");
    $stmt->execute([$code]);
    $qr = $stmt->fetch();

    if (!$qr) {
        // Mã không tồn tại
        header("Location: scan_qr.php?error=invalid_qr");
        exit();
    }

    if ($qr['is_used']) {
        // Mã đã được sử dụng
        header("Location: scan_qr.php?error=qr_already_used");
        exit();
    }

    // 4. Thực hiện cộng điểm và đánh dấu mã đã dùng (Transaction)
    $pdo->beginTransaction();

    // Cộng điểm cho user
    $stmt = $pdo->prepare("UPDATE users SET points = points + ? WHERE id = ?");
    $stmt->execute([$qr['points'], $user_id]);

    // Đánh dấu mã QR đã sử dụng
    $stmt = $pdo->prepare("UPDATE qr_codes SET is_used = 1, used_by = ?, used_at = NOW() WHERE id = ?");
    $stmt->execute([$user_id, $qr['id']]);

    // Ghi lại lịch sử hoạt động
    $description = "Quét mã " . ($qr['location'] ?? 'QR Trạm Xanh');
    $stmt = $pdo->prepare("INSERT INTO history (user_id, action_type, points, description, created_at) VALUES (?, 'earn', ?, ?, NOW())");
    $stmt->execute([$user_id, $qr['points'], $description]);

    $pdo->commit();

    // Xóa mã tạm trong session
    unset($_SESSION['pending_qr_code']);

    // 5. Thành công -> Chuyển về trang quét QR báo tin vui
    header("Location: scan_qr.php?success=points_added&amount=" . $qr['points']);
    exit();

} catch (Exception $e) {
    $pdo->rollBack();
    die("Lỗi hệ thống: " . $e->getMessage());
}
?>
