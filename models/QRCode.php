<?php
require_once 'config/database.php';

class QRCode {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getByToken($token) {
        $query = "SELECT * FROM qr_codes WHERE token = :token LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function markAsUsed($id, $user_id) {
        $query = "UPDATE qr_codes SET is_used = 1, used_by = :user_id, used_at = NOW() WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
