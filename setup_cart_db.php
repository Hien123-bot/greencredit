<?php
require_once 'includes/config.php';
require_once 'includes/db.php';

try {
    $sql = "CREATE TABLE IF NOT EXISTS `cart_items` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `user_id` int(11) NOT NULL,
      `product_id` int(11) NOT NULL,
      `quantity` int(11) NOT NULL DEFAULT 1,
      `created_at` timestamp DEFAULT current_timestamp(),
      PRIMARY KEY (`id`),
      KEY `user_id` (`user_id`),
      KEY `product_id` (`product_id`),
      CONSTRAINT `fk_ci_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
      CONSTRAINT `fk_ci_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
    
    $pdo->exec($sql);
    echo "Table cart_items created successfully.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
