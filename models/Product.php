<?php
require_once 'config/database.php';

class Product {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getAll($limit = 20) {
        try {
            $query = "SELECT * FROM products ORDER BY created_at DESC LIMIT :limit";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return $this->getMockProducts();
        }
    }

    public function getById($id) {
        $query = "SELECT * FROM products WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByCategory($category) {
        $query = "SELECT * FROM products WHERE category = :category";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':category', $category);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getMockProducts() {
        return [
            ['id' => 1, 'name' => 'Bình nước Tre Bamboo', 'points' => 500, 'image' => 'https://images.unsplash.com/photo-1602143302326-19220c2af277?auto=format&fit=crop&q=80&w=600', 'category' => 'Bình nước'],
            ['id' => 2, 'name' => 'Túi vải Canvas', 'points' => 200, 'image' => 'https://images.unsplash.com/photo-1544816155-12df9643f363?auto=format&fit=crop&q=80&w=600', 'category' => 'Túi vải'],
            ['id' => 3, 'name' => 'Bộ ống hút Inox', 'points' => 150, 'image' => 'https://images.unsplash.com/photo-1584263343327-4479f824cca6?auto=format&fit=crop&q=80&w=600', 'category' => 'Ống hút']
        ];
    }
}
