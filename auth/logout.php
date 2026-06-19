<?php
session_start();
require_once '../includes/config.php';

// 1. Xóa tất cả các biến session
$_SESSION = array();

// 2. Xóa cookie session nếu có
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 3. Hủy session hoàn toàn
session_destroy();

// 4. Chuyển hướng về trang đăng nhập
header("Location: login.php");
exit();
?>
