<?php
require_once __DIR__ . '/auth.php';
include 'db.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve all fields from the POST request
    $computer_name = $_POST['computerName'] ?? '';
    $regkit_serial_num = $_POST['regkitSerialNum'] ?? null; // Optional field
    $serial_number = $_POST['serialNumber'] ?? '';
    $property_num = $_POST['propertyNum'] ?? '';          // 👈 NEW FIELD
    $password = $_POST['password'] ?? '';
    $rcs_uname_code = $_POST['rcsUnameCode'] ?? '';
    $reg_center_code = $_POST['regCenterCode'] ?? '';
    $regno = $_POST['regNo'] ?? '';

    // Validate all required fields, including the new property_num
    if (empty($computer_name) || empty($serial_number) || empty($property_num) || empty($password) || empty($rcs_uname_code) || empty($reg_center_code) || empty($regno)) {
        echo "Error: Missing required fields.";
        exit;
    }

    try {
        // Prepare the SQL statement to include the new property_num column
        $sql = "INSERT INTO RegistrationDetails 
                (computer_name, regkit_serial_num, serial_number, property_num, password, rcs_uname_code, reg_center_code, regno) 
                VALUES 
                (:computer_name, :regkit_serial_num, :serial_number, :property_num, :password, :rcs_uname_code, :reg_center_code, :regno)";
        
        $stmt = $conn->prepare($sql);
        
        // Bind parameters
        $stmt->bindParam(':computer_name', $computer_name);
        $stmt->bindParam(':regkit_serial_num', $regkit_serial_num); // Can be NULL
        $stmt->bindParam(':serial_number', $serial_number);
        $stmt->bindParam(':property_num', $property_num);        // 👈 BIND NEW PARAMETER
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':rcs_uname_code', $rcs_uname_code);
        $stmt->bindParam(':reg_center_code', $reg_center_code);
        $stmt->bindParam(':regno', $regno);

        if ($stmt->execute()) {
            echo "Success";
        } else {
            // Log or display specific PDO error information if execution fails
            // echo "Error: Failed to insert data. PDO Error: " . json_encode($stmt->errorInfo()); 
            echo "Error: Failed to insert data.";
        }
    } catch (PDOException $e) {
        // Log the detailed exception message
        // error_log("Database Error in save_registration.php: " . $e->getMessage()); 
        echo "Error: " . $e->getMessage();
    }
} else {
    // Handle non-POST requests gracefully
    http_response_code(405);
    echo "Error: Method not allowed.";
}
?>