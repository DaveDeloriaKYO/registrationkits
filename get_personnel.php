<?php
require_once __DIR__ . '/auth.php';
header('Content-Type: application/json; charset=UTF-8');
include 'db.php';

try {
    $stmt = $conn->prepare("
        SELECT id, last_name, first_name, middle_name, position, status 
        FROM personnel_info 
        WHERE status = 'active'
        ORDER BY last_name ASC
    ");
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "success" => true,
        "data" => $result
    ]);
} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Database error: " . $e->getMessage()
    ]);
}
?>
