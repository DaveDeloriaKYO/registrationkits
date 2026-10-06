<?php
require_once __DIR__ . '/auth.php';
// delete_peripheral.php

// Ensure db.php is loaded. It MUST define the connection variable as $conn.
require_once __DIR__ . '/db.php'; 

// Set header to return JSON response
header('Content-Type: application/json'); 

// Check if the connection failed in db.php
if (!isset($conn)) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
    exit;
}

// 1. Basic Request and Data Validation
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method Not Allowed.']);
    exit;
}

// Check for required ID
if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid or missing Peripheral ID.']);
    exit;
}

$id = (int)$_POST['id'];
$check = $conn->prepare("SELECT is_frozen FROM peripherals WHERE id = ?");
$check->execute([$id]);
$existing = $check->fetch(PDO::FETCH_ASSOC);
if (!$existing) { http_response_code(404); echo json_encode(['success'=>false,'message'=>'Inventory record not found.']); exit; }
if ((int)$existing['is_frozen'] === 1) { http_response_code(423); echo json_encode(['success'=>false,'message'=>'This inventory record is frozen. Unfreeze it before deleting.']); exit; }

// 2. Prepare SQL Statement
// Use a prepared statement to prevent SQL injection
$sql = "DELETE FROM peripherals WHERE id = :id";

// 3. Execute Deletion
try {
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    // Check if any row was actually deleted
    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => "Peripheral record ID: {$id} successfully deleted."]);
    } else {
        // This handles cases where the ID was valid but no matching record existed
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => "Deletion failed. No record found with ID: {$id}."]);
    }

} catch (PDOException $e) {
    // Handle database errors (e.g., connection drop, foreign key constraint issues)
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'An error occurred during database deletion.', 'details' => $e->getMessage()]);
}
?>