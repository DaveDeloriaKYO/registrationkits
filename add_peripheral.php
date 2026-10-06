<?php
require_once __DIR__ . '/auth.php';
// add_peripheral.php - UPDATED for 5 Statuses and ID Auto-Increment Fix

// Ensure db.php is loaded. It MUST define the connection variable as $conn.
require_once __DIR__ . '/db.php'; 

// Set header to return JSON response
header('Content-Type: application/json'); 

// Define the expanded list of acceptable status values
$valid_statuses = [
    'serviceable', 
    'unserviceable', 
    'unidentified', 
    'for verification', 
    'for retrieval',
    'transferred'
];

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

// Check for required data (Only Reg No is strictly required)
if (!isset($_POST['regno']) || empty(trim($_POST['regno']))) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Registration number is required.']);
    exit;
}

// 2. Data Preparation
$regno = trim($_POST['regno']);

$peripherals = [
    'fp_scanner', 'iris_scanner', 'webcam', 'document_scanner',
    'extended_monitor', 'printer', 'battery_pack', 'photobooth_kit'
];

// Initialize data array (ID is excluded to allow AUTO_INCREMENT)
$data = [
    'regno' => $regno,
];

// Build the data array for insertion, including status and individual serials
foreach ($peripherals as $peripheral) {
    
    $is_present = isset($_POST[$peripheral]) ? 1 : 0;
    $data[$peripheral] = $is_present;
    
    $status_key = $peripheral . '_status';
    $serial_key = $peripheral . '_serial';
    
    // Default status if peripheral is not present (matching database default)
    $status_value = 'unidentified'; 
    $serial_value = null;

    if ($is_present == 1) {
        // 1. Status: Use the posted status if present and VALIDATED against the new list
        if (isset($_POST[$status_key])) {
            $posted_status = strtolower(trim($_POST[$status_key]));
            if (in_array($posted_status, $valid_statuses)) {
                $status_value = $posted_status;
            } else {
                // Should not happen if front-end is correct, but good for safety
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => "Invalid status provided for: " . htmlspecialchars($peripheral)]);
                exit;
            }
        }
        
        // 2. Serial (Must be present if the peripheral is checked)
        if (isset($_POST[$serial_key]) && !empty(trim($_POST[$serial_key]))) {
            $serial_value = trim($_POST[$serial_key]);
        }
        
        // CRITICAL SERVER-SIDE CHECK: If the checkbox was checked, the serial MUST be provided.
        if ($is_present && $serial_value === null) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => "Missing Serial Number for: " . htmlspecialchars($peripheral)]);
            exit;
        }
    }
    
    $data[$status_key] = $status_value;
    $data[$serial_key] = $serial_value;
}

// 3. Prepare SQL Statement
// Note: $data now correctly excludes 'id', enabling AUTO_INCREMENT
$field_names = implode(', ', array_keys($data));
$placeholders = implode(', ', array_fill(0, count($data), '?'));

$sql = "INSERT INTO peripherals ($field_names) VALUES ($placeholders)";

// 4. Execute Insertion
try {
    $stmt = $conn->prepare($sql);
    $values = array_values($data);
    $stmt->execute($values);

    echo json_encode(['success' => true, 'message' => 'Peripheral inventory for REG NO. ' . htmlspecialchars($regno) . ' added successfully!']);

} catch (PDOException $e) {
    $error_message = $e->getMessage();
    $user_friendly_message = "An error occurred during database insertion.";
    
    // Check for duplicate entry error (e.g., on regno or a unique serial number)
    if ($e->getCode() === '23000' && strpos($error_message, 'Duplicate entry') !== false) {
        $user_friendly_message = "Error: This Registration Number or a Peripheral Serial Number already exists in the system.";
    }

    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $user_friendly_message, 'details' => $error_message]);
}
?>