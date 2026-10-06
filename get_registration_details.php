<?php
require_once __DIR__ . '/auth.php';
// get_registration_details.php

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

if (!isset($_GET['regno']) || empty(trim($_GET['regno']))) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Missing Registration Number (regno).']);
    exit;
}

$regno = trim($_GET['regno']);

try {
    // SQL to select the required columns from the registrationdetails table
    $sql = "SELECT computer_name, serial_number, regkit_serial_num 
            FROM registrationdetails 
            WHERE regno = :regno 
            LIMIT 1"; // Limit to 1 in case of duplicates

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':regno', $regno, PDO::PARAM_STR);
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($data) {
        echo json_encode(['success' => true, 'data' => $data]);
    } else {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => "No registration details found for Reg No. '{$regno}'. (Possible data mismatch)"]);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error while fetching registration data.', 'details' => $e->getMessage()]);
}
?>