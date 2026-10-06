<?php
require_once __DIR__ . '/auth.php';
require_once 'db.php'; // Ensure this sets up a PDO connection in $conn

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $description     = trim($_POST['description']);
        $property_no     = trim($_POST['property_no']);
        $purpose         = trim($_POST['purpose']);
        $borrow_datetime = trim($_POST['borrow_datetime']);
        $duration_days   = intval($_POST['duration_days']);
        $borrowed_by     = trim($_POST['borrowed_by']);
        $noted_by        = trim($_POST['noted_by']);
        $approved_by     = trim($_POST['approved_by']);
        $borrowed_by_designation  = trim($_POST['borrowed_by_designation']);
        $noted_by_designation     = trim($_POST['noted_by_designation']);
        $approved_by_designation  = trim($_POST['approved_by_designation']);

        // Validation
        if (
            empty($description) || empty($property_no) || empty($purpose) ||
            empty($borrow_datetime) || empty($duration_days) ||
            empty($borrowed_by) || empty($noted_by) || empty($approved_by) ||
            empty($borrowed_by_designation) || empty($noted_by_designation) || empty($approved_by_designation)
        ) {
            echo json_encode(['success' => false, 'error' => 'All fields are required.']);
            exit;
        }

        // PDO prepared statement
        $stmt = $conn->prepare("
            INSERT INTO borrower (
                description, property_no, purpose, borrow_datetime, duration_days,
                borrowed_by, borrowed_by_designation,
                noted_by, noted_by_designation,
                approved_by, approved_by_designation
            ) VALUES (
                :description, :property_no, :purpose, :borrow_datetime, :duration_days,
                :borrowed_by, :borrowed_by_designation,
                :noted_by, :noted_by_designation,
                :approved_by, :approved_by_designation
            )
        ");

        $stmt->execute([
            ':description'     => $description,
            ':property_no'     => $property_no,
            ':purpose'         => $purpose,
            ':borrow_datetime' => $borrow_datetime,
            ':duration_days'   => $duration_days,
            ':borrowed_by'     => $borrowed_by,
            ':borrowed_by_designation' => $borrowed_by_designation,
            ':noted_by'        => $noted_by,
            ':noted_by_designation'    => $noted_by_designation,
            ':approved_by'     => $approved_by,
            ':approved_by_designation' => $approved_by_designation
        ]);

        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
}
