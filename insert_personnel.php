<?php
require_once __DIR__ . '/auth.php';
include 'db.php';

$response = ['success' => false, 'error' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $lastName = $_POST['last_name'];
        $firstName = $_POST['first_name'];
        $position = $_POST['position'];
        $yearStarted = $_POST['year_started'];
        $contractStart = $_POST['contract_start_date'];
        $contractEnd = $_POST['contract_end_date'];
        $regno = $_POST['regno'];
        $status = $_POST['status'];

        $query = "INSERT INTO personnel_info (last_name, first_name, position, year_started, contract_start_date, contract_end_date, regno, status) 
                  VALUES (:last_name, :first_name, :position, :year_started, :contract_start_date, :contract_end_date, :regno, :status)";

        $stmt = $conn->prepare($query);
        $stmt->bindParam(':last_name', $lastName);
        $stmt->bindParam(':first_name', $firstName);
        $stmt->bindParam(':position', $position);
        $stmt->bindParam(':year_started', $yearStarted);
        $stmt->bindParam(':contract_start_date', $contractStart);
        $stmt->bindParam(':contract_end_date', $contractEnd);
        $stmt->bindParam(':regno', $regno);
        $stmt->bindParam(':status', $status);

        if ($stmt->execute()) {
            // Get the inserted record
            $newPersonnel = [
                'id' => $conn->lastInsertId(),
                'last_name' => $lastName,
                'first_name' => $firstName,
                'position' => $position,
                'year_started' => $yearStarted,
                'contract_start_date' => $contractStart,
                'contract_end_date' => $contractEnd,
                'regno' => $regno,
                'status' => $status
            ];

            $response['success'] = true;
            $response['newPersonnel'] = $newPersonnel;
        } else {
            $response['error'] = 'Failed to insert data.';
        }
    } catch (Exception $e) {
        $response['error'] = 'Error: ' . $e->getMessage();
    }
}

echo json_encode($response);
?>
