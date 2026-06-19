<?php
require_once 'includes/config.php';
require_once 'includes/db.php';

try {
    // 1. Tạo bảng news
    $sql = "CREATE TABLE IF NOT EXISTS `news` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `title` varchar(255) NOT NULL,
      `description` text NOT NULL,
      `content` longtext DEFAULT NULL,
      `tag` varchar(50) DEFAULT 'Tin tức',
      `image` varchar(255) NOT NULL,
      `is_featured` tinyint(1) DEFAULT 0,
      `reading_time` int(11) DEFAULT 5,
      `created_at` timestamp DEFAULT current_timestamp(),
      `updated_at` timestamp DEFAULT current_timestamp() ON UPDATE current_timestamp(),
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
    
    $pdo->exec($sql);
    echo "Table 'news' created successfully.\n";

    // 2. Thêm một số dữ liệu mẫu ban đầu để test
    $stmt = $pdo->query("SELECT COUNT(*) FROM news");
    $count = $stmt->fetchColumn();

    if ($count == 0) {
        $sampleData = [
            [
                'title' => 'Tương lai của năng lượng sạch: Tại sao Green Credit là chìa khóa cho sự chuyển đổi bền vững?',
                'description' => 'Tìm hiểu cách nền tảng Green Fintech của chúng tôi đang tạo ra tác động mạnh mẽ đến thói quen tiêu dùng.',
                'tag' => 'Tiêu điểm',
                'image' => 'https://images.unsplash.com/photo-1473081556163-2a17de81fc97?auto=format&fit=crop&q=80&w=1200',
                'is_featured' => 1,
                'reading_time' => 15
            ],
            [
                'title' => 'Chiến dịch trồng 1 triệu cây xanh toàn quốc',
                'description' => 'Green Credit cùng các đối tác chiến lược chính thức khởi động chiến dịch phủ xanh các vùng đất trống...',
                'tag' => 'Sự kiện',
                'image' => 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&q=80&w=600',
                'is_featured' => 0,
                'reading_time' => 5
            ]
        ];

        $insertSql = "INSERT INTO news (title, description, tag, image, is_featured, reading_time) VALUES (?, ?, ?, ?, ?, ?)";
        $stmtInsert = $pdo->prepare($insertSql);
        
        foreach ($sampleData as $item) {
            $stmtInsert->execute([
                $item['title'],
                $item['description'],
                $item['tag'],
                $item['image'],
                $item['is_featured'],
                $item['reading_time']
            ]);
        }
        echo "Sample data inserted.\n";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
