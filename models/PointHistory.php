<?php
require_once 'config/database.php';

class PointHistory {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getByUserId($user_id, $limit = 10) {
        try {
            $query = "SELECT * FROM point_history WHERE user_id = :user_id ORDER BY created_at DESC LIMIT :limit";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Mock data
            return [
                ['description' => 'Tích điểm từ mã QR', 'point' => 500, 'type' => 'qr', 'created_at' => date('Y-m-d H:i:s')],
                ['description' => 'Đổi quà tặng', 'point' => -200, 'type' => 'redeem', 'created_at' => date('Y-m-d H:i:s', strtotime('-1 day'))]
            ];
        }
    }

    public function addRecord($user_id, $point, $type, $description) {
        $query = "INSERT INTO point_history (user_id, point, type, description, created_at) VALUES (:user_id, :point, :type, :description, NOW())";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':point', $point);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':description', $description);
        return $stmt->execute();
    }
}
