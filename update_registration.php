<?php
require_once __DIR__ . '/auth.php';
include 'db.php'; // Database connection

if ($_POST) {
    $regno = $_POST['regno'];
    $computerName = $_POST['computerName'];
    $serialNumber = $_POST['serialNumber'];
    $propertyNum = $_POST['propertyNum']; // 👈 NEW FIELD
    $machineId = $_POST['machineId']; 
    $password = $_POST['password'];
    $rcsUnameCode = $_POST['rcsUnameCode'];
    $regCenterCode = $_POST['regCenterCode'];
    $regkitSerialNum = $_POST['regkitSerialNum']; 

    $sql = "UPDATE RegistrationDetails 
            SET computer_name = :computer_name, 
                serial_number = :serial_number, 
                property_num = :property_num, /* 👈 NEW FIELD IN UPDATE SET */
                machine_id = :machine_id, 
                password = :password, 
                rcs_uname_code = :rcs_uname_code, 
                reg_center_code = :reg_center_code,
                regkit_serial_num = :regkit_serial_num
            WHERE regno = :regno";

    $stmt = $conn->prepare($sql);
    
    // Bind all parameters
    $stmt->bindParam(':computer_name', $computerName);
    $stmt->bindParam(':serial_number', $serialNumber);
    $stmt->bindParam(':property_num', $propertyNum); // 👈 BIND NEW PARAMETER
    $stmt->bindParam(':machine_id', $machineId); 
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':rcs_uname_code', $rcsUnameCode);
    $stmt->bindParam(':reg_center_code', $regCenterCode);
    $stmt->bindParam(':regkit_serial_num', $regkitSerialNum); 
    $stmt->bindParam(':regno', $regno, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "Success";
    } else {
        echo "Error";
    }
}
?>