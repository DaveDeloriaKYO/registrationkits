<?php
require_once __DIR__ . '/auth.php';
include 'db.php';  // Include your database connection script

header('Content-Type: application/json');

// Check if laptops are selected
if (isset($_GET['laptops'])) {
    $selectedLaptops = explode(',', $_GET['laptops']);

    // SQL query to fetch registration details for selected laptops
    $query = "SELECT computer_name, reg_code, machine_id, serial_number, password 
              FROM registrationdetails 
              WHERE computer_name IN ('" . implode("','", $selectedLaptops) . "')";

    // Prepare and execute the query
    $stmt = $conn->prepare($query);
    $stmt->execute();

    // Fetch the data
    $registrationDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the result as a JSON array
    echo json_encode($registrationDetails);
} else {
    // If no laptops are selected, fetch distinct laptop names
    $query = "SELECT DISTINCT computer_name FROM registrationdetails";

    // Prepare and execute the query
    $stmt = $conn->prepare($query);
    $stmt->execute();

    // Fetch all the distinct laptop names
    $laptop_names = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Return the result as a JSON array
    echo json_encode($laptop_names);
}
?>
