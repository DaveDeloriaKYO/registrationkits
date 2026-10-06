<?php
require_once __DIR__ . '/auth.php';
include 'db.php'; // Database connection

if (isset($_GET['regno'])) {
    $regno = $_GET['regno'];

    // Updated SQL query to include the 'property_num' column
    $sql = "SELECT regno, computer_name, serial_number, property_num, machine_id, password, rcs_uname_code, reg_center_code, regkit_serial_num 
            FROM RegistrationDetails 
            WHERE regno = :regno";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':regno', $regno, PDO::PARAM_INT);
    $stmt->execute();

    $record = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($record) {
        // The record now includes property_num
        echo json_encode($record);
    } else {
        echo json_encode(["error" => "No record found"]);
    }
} else {
    // Handle case where regno is not provided
    echo json_encode(["error" => "Registration number not provided"]);
}
?>