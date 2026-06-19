<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/db.php';

// Check admin role here...

$action = $_POST['action'] ?? '';

if ($action === 'delete') {
    $id = (int)$_POST['id'];
    try {
        $stmt = $pdo->prepare("DELETE FROM news WHERE id = ?");
        $stmt->execute([$id]);
        $_SESSION['msg'] = "Đã xóa bài viết thành công!";
    } catch (PDOException $e) {
        $_SESSION['msg'] = "Lỗi khi xóa: " . $e->getMessage();
    }
    header("Location: news.php");
    exit;
}

if ($action === 'approve') {
    $id = (int)$_POST['id'];
    try {
        $stmt = $pdo->prepare("UPDATE news SET status = 'approved' WHERE id = ?");
        $stmt->execute([$id]);
        $_SESSION['msg'] = "Đã duyệt bài viết thành công!";
    } catch (PDOException $e) {
        $_SESSION['msg'] = "Lỗi khi duyệt: " . $e->getMessage();
    }
    header("Location: news.php");
    exit;
}

if ($action === 'reject') {
    $id = (int)$_POST['id'];
    try {
        $stmt = $pdo->prepare("UPDATE news SET status = 'rejected' WHERE id = ?");
        $stmt->execute([$id]);
        $_SESSION['msg'] = "Đã từ chối bài viết!";
    } catch (PDOException $e) {
        $_SESSION['msg'] = "Lỗi khi từ chối: " . $e->getMessage();
    }
    header("Location: news.php");
    exit;
}

if ($action === 'save') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $title = trim($_POST['title']);
    $tag = trim($_POST['tag']);
    $reading_time = (int)$_POST['reading_time'];
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $image = trim($_POST['image']); // URL ảnh
    $description = trim($_POST['description']);
    $content = trim($_POST['content']);

    try {
        if ($id > 0) {
            // Update
            $stmt = $pdo->prepare("UPDATE news SET title = ?, tag = ?, reading_time = ?, is_featured = ?, image = ?, description = ?, content = ? WHERE id = ?");
            $stmt->execute([$title, $tag, $reading_time, $is_featured, $image, $description, $content, $id]);
            $_SESSION['msg'] = "Đã cập nhật bài viết thành công!";
        } else {
            // Insert (Bài do Admin đăng thì luôn approved)
            $stmt = $pdo->prepare("INSERT INTO news (title, tag, reading_time, is_featured, image, description, content, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'approved')");
            $stmt->execute([$title, $tag, $reading_time, $is_featured, $image, $description, $content]);
            $_SESSION['msg'] = "Đã thêm bài viết mới thành công!";
        }
    } catch (PDOException $e) {
        $_SESSION['msg'] = "Lỗi lưu dữ liệu: " . $e->getMessage();
    }
    
    header("Location: news.php");
    exit;
}

header("Location: news.php");
exit;
