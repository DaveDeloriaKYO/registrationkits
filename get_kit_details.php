<?php
require_once __DIR__ . '/auth.php';
include 'db.php';
header('Content-Type: application/json');

try {
    // Check if the request is POST and contains the 'laptop_names' parameter
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['laptop_names']) && is_array($_POST['laptop_names']) && count($_POST['laptop_names']) > 0) {
        
        $laptop_names = $_POST['laptop_names'];

        // Prepare the SQL query to fetch kit details for selected laptops
        $placeholders = implode(',', array_fill(0, count($laptop_names), '?'));
        $query = "SELECT reg_code, regkit_serial_num, computer_name, machine_id, serial_num, password
                  FROM registrationdetails
                  WHERE computer_name IN ($placeholders)";

        // Prepare and execute the statement
        $stmt = $conn->prepare($query);
        $stmt->execute($laptop_names);

        // Fetch all the results
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Return the results as JSON (empty array if no results)
        echo json_encode($results ?: []);
    } else {
        // If the request is invalid or laptop_names is empty
        echo json_encode(['error' => 'Invalid request or missing laptop names']);
    }
} catch (Exception $e) {
    // Return any exceptions as an error message
    echo json_encode(['error' => 'Error fetching kits: ' . $e->getMessage()]);
}
?>
