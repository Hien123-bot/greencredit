<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/db.php';

// Check admin role here...

$action = $_POST['action'] ?? '';

if ($action === 'delete') {
    $id = (int)$_POST['id'];
    try {
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $_SESSION['msg'] = "Đã xóa sản phẩm thành công!";
    } catch (PDOException $e) {
        $_SESSION['msg'] = "Lỗi khi xóa: " . $e->getMessage();
    }
    header("Location: products.php");
    exit;
}

if ($action === 'save') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $name = trim($_POST['name']);
    $category = trim($_POST['category']);
    $stock = (int)$_POST['stock'];
    $price_vnd = (int)$_POST['price_vnd'];
    $max_pts = (int)$_POST['max_pts'];
    $image = trim($_POST['image']);
    $description = trim($_POST['description']);

    try {
        if ($id > 0) {
            // Update
            $stmt = $pdo->prepare("UPDATE products SET name=?, category=?, stock=?, price_vnd=?, max_pts=?, image=?, description=? WHERE id=?");
            $stmt->execute([$name, $category, $stock, $price_vnd, $max_pts, $image, $description, $id]);
            $_SESSION['msg'] = "Đã cập nhật sản phẩm thành công!";
        } else {
            // Insert
            $stmt = $pdo->prepare("INSERT INTO products (name, category, stock, price_vnd, max_pts, image, description) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $category, $stock, $price_vnd, $max_pts, $image, $description]);
            $_SESSION['msg'] = "Đã thêm sản phẩm mới thành công!";
        }
    } catch (PDOException $e) {
        $_SESSION['msg'] = "Lỗi lưu dữ liệu: " . $e->getMessage();
    }
    
    header("Location: products.php");
    exit;
}

header("Location: products.php");
exit;
