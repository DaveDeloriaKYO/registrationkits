<?php
require_once __DIR__ . '/auth.php';
// Include the database connection file (db.php)
include 'db.php';  // Adjust the path if needed

// Check if laptops are selected
if (isset($_POST['laptops']) && !empty($_POST['laptops'])) {
    $laptops = $_POST['laptops'];
    
    // Prepare SQL to fetch registration details for selected laptops
    $placeholders = implode(',', array_fill(0, count($laptops), '?'));
    $sql = "SELECT computer_name, reg_code, machine_id, serial_number, password 
            FROM registrationdetails 
            WHERE computer_name IN ($placeholders)";
    
    // Prepare the statement using PDO
    $stmt = $conn->prepare($sql);
    
    // Execute the statement with the selected laptops
    $stmt->execute($laptops);
    
    // Fetch the results as an associative array
    $registrationDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Set the content type to JSON
    header('Content-Type: application/json');
    
    // Return the registration details as a JSON response
    echo json_encode($registrationDetails);
} else {
    // If no laptops were selected
    echo json_encode([]);
}
?>
