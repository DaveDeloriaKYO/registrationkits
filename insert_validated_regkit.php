<?php
require_once __DIR__ . '/auth.php';
include 'db.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $computer_name = $_POST['computer_name'];
    $reg_code = $_POST['reg_code'];
    $regkit_serial_num = $_POST['regkit_serial_num'];
    $serial_number = $_POST['serial_number'];

    // Ensure data is not empty before inserting
    if (empty($computer_name) || empty($reg_code) || empty($regkit_serial_num) || empty($serial_number)) {
        echo "Error: Missing fields";
        exit;
    }

    // Insert data into ismd_validated_regkits
    $sql = "INSERT INTO ismd_validated_regkits (computer_name, reg_code, regkit_serial_num, serial_number) 
            VALUES (:computer_name, :reg_code, :regkit_serial_num, :serial_number)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':computer_name', $computer_name);
    $stmt->bindParam(':reg_code', $reg_code);
    $stmt->bindParam(':regkit_serial_num', $regkit_serial_num);
    $stmt->bindParam(':serial_number', $serial_number);

    if ($stmt->execute()) {
        echo "Success";
    } else {
        echo "Error";
    }
}
?>
