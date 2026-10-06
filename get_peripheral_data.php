<?php
require_once __DIR__ . '/auth.php';
// get_peripheral_data.php

require_once __DIR__ . '/db.php'; 
header('Content-Type: application/json'); 

if (!isset($conn)) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method Not Allowed.']);
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid or missing ID.']);
    exit;
}

$id = (int)$_GET['id'];

// Build the dynamic SELECT list
$peripherals_columns = [
    'fp_scanner', 'iris_scanner', 'webcam', 'document_scanner',
    'extended_monitor', 'printer', 'battery_pack', 'photobooth_kit'
];
$select_cols = ['id', 'regno']; 
foreach ($peripherals_columns as $p) {
    $select_cols[] = $p;              
    $select_cols[] = $p . '_status';  
    $select_cols[] = $p . '_serial';  
}
$select_list = implode(', ', $select_cols);


try {
    $sql = "SELECT {$select_list} FROM peripherals WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($data) {
        echo json_encode(['success' => true, 'data' => $data]);
    } else {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Record not found.']);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error while fetching data.', 'details' => $e->getMessage()]);
}
?>