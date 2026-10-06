<?php
require_once __DIR__ . '/auth.php';
// fetch_available_regnos.php
// Fetches RegNos from the 'registrationdetails' table that do NOT exist in the 'peripherals' table.

// Ensure db.php is loaded. It MUST define the connection variable as $conn.
require_once __DIR__ . '/db.php'; 

// Set header to return JSON response
header('Content-Type: application/json');

// Check if the database connection failed in db.php
if (!isset($conn)) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
    exit;
}

try {
    // SQL uses a LEFT JOIN to find records in 'registrationdetails' (rd) 
    // that have no matching entry in 'peripherals' (p).
    $sql = "SELECT rd.regno 
            FROM registrationdetails rd
            LEFT JOIN peripherals p ON rd.regno = p.regno
            WHERE p.regno IS NULL 
            ORDER BY rd.regno ASC";
            
    // Execute the query using the established connection ($conn)
    $stmt = $conn->query($sql); 
    
    // Fetch all results as a simple column array (a list of RegNos)
    $regnos = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Success response: return the list of available RegNos
    echo json_encode(['success' => true, 'regnos' => $regnos]);
    
} catch (PDOException $e) {
    // Database error handling
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error fetching available Registration Numbers.', 'details' => $e->getMessage()]);
}
?>