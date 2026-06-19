<?php
require_once 'config/database.php';

class Order {
    private $db;

    public function __construct() {
        $this->db = (new Database())->connect();
    }

    public function create($user_id, $total_points, $items) {
        // Logic tạo đơn hàng
    }

    public function getByUserId($user_id) {
        // Lấy danh sách đơn hàng của user
    }
}
?>
