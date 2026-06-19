<?php
require_once 'includes/db.php';
try {
    $stmt = $pdo->query("SELECT * FROM qr_codes");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (empty($rows)) {
        echo "Bảng qr_codes trống rỗng!\n";
    } else {
        foreach ($rows as $row) {
            echo json_encode($row, JSON_UNESCAPED_UNICODE) . "\n";
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
