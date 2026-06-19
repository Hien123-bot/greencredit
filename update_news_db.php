<?php
require_once 'includes/config.php';
require_once 'includes/db.php';

echo "Bắt đầu cập nhật cấu trúc bảng 'news'...\n";

// Kiểm tra các cột hiện có
$stmt = $pdo->query("DESCRIBE news");
$columns = $stmt->fetchAll(PDO::FETCH_COLUMN);

// 1. Rename summary to description if summary exists and description doesn't
if (in_array('summary', $columns) && !in_array('description', $columns)) {
    try {
        $pdo->exec("ALTER TABLE `news` CHANGE `summary` `description` TEXT");
        echo "- Đã đổi tên cột 'summary' thành 'description'.\n";
    } catch (PDOException $e) {
        echo "- Lỗi đổi tên cột summary: " . $e->getMessage() . "\n";
    }
} elseif (!in_array('description', $columns)) {
    try {
        $pdo->exec("ALTER TABLE `news` ADD COLUMN `description` TEXT AFTER `title`");
        echo "- Đã thêm cột 'description'.\n";
    } catch (PDOException $e) {
        echo "- Lỗi thêm cột description: " . $e->getMessage() . "\n";
    }
}

// 2. Add author_id if not exists
if (!in_array('author_id', $columns)) {
    try {
        $pdo->exec("ALTER TABLE `news` ADD COLUMN `author_id` int(11) DEFAULT NULL AFTER `id`");
        echo "- Đã thêm cột 'author_id'.\n";
    } catch (PDOException $e) {
        echo "- Lỗi thêm cột author_id: " . $e->getMessage() . "\n";
    }
}

// 3. Add is_featured if not exists
if (!in_array('is_featured', $columns)) {
    try {
        $pdo->exec("ALTER TABLE `news` ADD COLUMN `is_featured` tinyint(1) DEFAULT 0");
        echo "- Đã thêm cột 'is_featured'.\n";
    } catch (PDOException $e) {
        echo "- Lỗi thêm cột is_featured: " . $e->getMessage() . "\n";
    }
}

// 4. Add status if not exists
if (!in_array('status', $columns)) {
    try {
        $pdo->exec("ALTER TABLE `news` ADD COLUMN `status` ENUM('pending', 'approved', 'rejected') DEFAULT 'approved'");
        echo "- Đã thêm cột 'status'.\n";
    } catch (PDOException $e) {
        echo "- Lỗi thêm cột status: " . $e->getMessage() . "\n";
    }
}

// 5. Add reading_time if not exists
if (!in_array('reading_time', $columns)) {
    try {
        $pdo->exec("ALTER TABLE `news` ADD COLUMN `reading_time` int(11) DEFAULT 5");
        echo "- Đã thêm cột 'reading_time'.\n";
    } catch (PDOException $e) {
        echo "- Lỗi thêm cột reading_time: " . $e->getMessage() . "\n";
    }
}

echo "Cập nhật hoàn tất! Bạn có thể làm mới trang và sử dụng chức năng Tin tức bình thường.\n";
?>
