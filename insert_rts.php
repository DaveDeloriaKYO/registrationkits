<?php
require_once __DIR__ . '/auth.php';
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $trn            = $_POST['trn'];
    $date_received  = $_POST['date_received'];
    $first_name     = $_POST['first_name'];
    $middle_name    = $_POST['middle_name'];
    $last_name      = $_POST['last_name'];
    $province_id    = $_POST['province_id'];
    $municipality_id= $_POST['municipality_id'];
    $barangay_id    = $_POST['barangay_id'];
    $purok_id       = !empty($_POST['purok_id']) ? $_POST['purok_id'] : null; // ✅ optional
    $status         = $_POST['status'];

    $stmt = $conn->prepare("INSERT INTO RTS 
        (trn, date_received, first_name, middle_name, last_name, province_id, municipality_id, barangay_id, purok_id, status) 
        VALUES 
        (:trn, :date_received, :first_name, :middle_name, :last_name, :province_id, :municipality_id, :barangay_id, :purok_id, :status)");

    $stmt->bindParam(':trn', $trn);
    $stmt->bindParam(':date_received', $date_received);
    $stmt->bindParam(':first_name', $first_name);
    $stmt->bindParam(':middle_name', $middle_name);
    $stmt->bindParam(':last_name', $last_name);
    $stmt->bindParam(':province_id', $province_id);
    $stmt->bindParam(':municipality_id', $municipality_id);
    $stmt->bindParam(':barangay_id', $barangay_id);
    $stmt->bindParam(':purok_id', $purok_id, PDO::PARAM_NULL | PDO::PARAM_INT); // ✅ handle NULL properly
    $stmt->bindParam(':status', $status);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
}
?>
