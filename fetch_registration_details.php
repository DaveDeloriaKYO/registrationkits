<?php
require_once __DIR__ . '/auth.php';
include 'db.php'; // Include your database connection

// Check if regno is provided via POST
if (isset($_POST['regno'])) {
    $regno = $_POST['regno'];

    try {
        // SQL Query to fetch registration details based on regno
        $sql = "SELECT reg_code, regkit_serial_num, serial_number, machine_id, computer_name, password, rcs_uname_code, reg_center_code
                FROM RegistrationDetails
                WHERE reg_code = :regno
                LIMIT 1";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind the regno to the query
        $stmt->bindParam(':regno', $regno, PDO::PARAM_STR);
        
        // Execute the statement
        $stmt->execute();

        // Fetch the result
        $record = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if record is found
        if ($record) {
            echo json_encode([
                'success' => true,
                'data' => $record
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'error' => 'No record found for the given registration number.'
            ]);
        }

    } catch (PDOException $e) {
        // Handle PDO exception
        echo json_encode([
            'success' => false,
            'error' => 'Database error: ' . $e->getMessage()
        ]);
    }
} else {
    // Return error if regno is missing
    echo json_encode([
        'success' => false,
        'error' => 'Invalid request: regno parameter is missing.'
    ]);
}
?>
