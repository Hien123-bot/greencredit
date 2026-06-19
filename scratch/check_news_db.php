<?php
require_once 'includes/config.php';
require_once 'includes/db.php';

try {
    $stmt = $pdo->query("DESCRIBE news");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns as $col) {
        echo $col['Field'] . " - " . $col['Type'] . "\n";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
