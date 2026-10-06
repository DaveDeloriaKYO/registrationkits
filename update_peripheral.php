<?php
require_once __DIR__ . '/auth.php';
// update_peripheral.php - FIXED: Adds automatic update of a timestamp column upon successful modification.

require_once __DIR__ . '/db.php'; 
header('Content-Type: application/json'); 

// Define the expanded list of acceptable status values
$valid_statuses = [
    'serviceable', 'unserviceable', 'unidentified', 
    'for verification', 'for retrieval'
];

if (!isset($conn)) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method Not Allowed.']);
    exit;
}

// 1. Validate mandatory ID and Registration Number
if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid or missing Peripheral ID for update.']);
    exit;
}

$update_id = (int)$_POST['id'];
$check = $conn->prepare("SELECT is_frozen FROM peripherals WHERE id = ?");
$check->execute([$update_id]);
$existing = $check->fetch(PDO::FETCH_ASSOC);
if (!$existing) { http_response_code(404); echo json_encode(['success'=>false,'message'=>'Inventory record not found.']); exit; }
if ((int)$existing['is_frozen'] === 1) { http_response_code(423); echo json_encode(['success'=>false,'message'=>'This inventory record is frozen. Unfreeze it before updating.']); exit; }
$regno = trim($_POST['regno']); // Reg No is validated in JS, but captured here

$peripherals = [
    'fp_scanner', 'iris_scanner', 'webcam', 'document_scanner',
    'extended_monitor', 'printer', 'battery_pack', 'photobooth_kit'
];

$set_clauses = [];
$values = [];

// 2. Build Dynamic SQL SET Clauses
foreach ($peripherals as $peripheral) {
    
    // Determine if the checkbox was checked (peripheral present)
    $is_present = isset($_POST[$peripheral]) ? 1 : 0;
    
    $status_key = $peripheral . '_status';
    $serial_key = $peripheral . '_serial';
    
    $status_value = 'unidentified'; // Default status if not present
    $serial_value = null;

    if ($is_present == 1) {
        // Status Validation
        if (isset($_POST[$status_key])) {
            $posted_status = strtolower(trim($_POST[$status_key]));
            if (in_array($posted_status, $valid_statuses)) {
                $status_value = $posted_status;
            } else {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => "Invalid status provided for: " . htmlspecialchars($peripheral)]);
                exit;
            }
        }
        
        // Serial Validation
        if (isset($_POST[$serial_key]) && !empty(trim($_POST[$serial_key]))) {
            $serial_value = trim($_POST[$serial_key]);
        }
        
        // CRITICAL CHECK: If present, serial MUST be provided
        if ($serial_value === null) {
             http_response_code(400);
             echo json_encode(['success' => false, 'message' => "Missing Serial Number for: " . htmlspecialchars($peripheral)]);
             exit;
        }
    }

    // Add clauses to the SQL update statement
    $set_clauses[] = "{$peripheral} = ?";
    $values[] = $is_present;

    $set_clauses[] = "{$status_key} = ?";
    $values[] = $status_value;

    $set_clauses[] = "{$serial_key} = ?";
    $values[] = $serial_value;
}

// 3. Prepare Final SQL
if (empty($set_clauses)) {
    echo json_encode(['success' => true, 'message' => 'No changes submitted.']);
    exit;
}

// FIX: Add the timestamp update clause.
// NOTE: We assume the column is named 'creation_time', but 'last_updated_time' is usually preferred.
$set_clauses[] = "creation_time = NOW()";

$sql = "UPDATE peripherals SET " . implode(', ', $set_clauses) . " WHERE id = ?";
$values[] = $update_id; // Add the ID to the end of the values array

// 4. Execute Update
try {
    $stmt = $conn->prepare($sql);
    $stmt->execute($values);
    
    echo json_encode(['success' => true, 'message' => "Peripheral inventory for ID: {$update_id} updated successfully and timestamp recorded!"]);

} catch (PDOException $e) {
    $error_message = $e->getMessage();
    $user_friendly_message = "An error occurred during database update.";
    
    // Check for duplicate entry error on serial numbers (assuming they have a UNIQUE constraint)
    if ($e->getCode() === '23000' && strpos($error_message, 'Duplicate entry') !== false) {
        $user_friendly_message = "Error: One of the Serial Numbers you entered already belongs to another record.";
    }

    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $user_friendly_message, 'details' => $error_message]);
}
?>