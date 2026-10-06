<?php
require_once __DIR__ . '/auth.php';
header('Content-Type: application/json; charset=UTF-8');
include 'db.php';

try {
    // Fetch distinct municipalities from personnel_info
    $stmt = $conn->prepare("
        SELECT DISTINCT municipality_name
        FROM municipalities
        WHERE municipality_name IS NOT NULL 
        ORDER BY municipality_name ASC
    ");
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_COLUMN); // Fetch only the municipality column

    echo json_encode([
        "success" => true,
        "data" => $result
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    error_log("Database error in get_municipalities.php: " . $e->getMessage());
    echo json_encode([
        "success" => false,
        "message" => "Internal server error. Please try again later."
    ]);
}
?>
