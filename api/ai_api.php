<?php
require_once 'services/AIService.php';
require_once 'models/User.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Logic xử lý upload ảnh và gọi AIService
    $aiService = new AIService();
    $result = $aiService->analyzeImage($_FILES['scan_image']['tmp_name'] ?? '');
    
    echo json_encode($result);
}
?>
