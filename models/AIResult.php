<?php
require_once 'config/database.php';

class AIResult {
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    public function save($user_id, $label, $points, $image_path) {
        $sql = "INSERT INTO ai_results (user_id, label, points, image_path) VALUES (?, ?, ?, ?)";
        // Thực thi query
    }

    public function getAllLogs() {
        // Lấy lịch sử scan AI của toàn hệ thống
    }
}
?>
