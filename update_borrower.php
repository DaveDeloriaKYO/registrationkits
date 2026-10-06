<?php
require_once __DIR__ . '/auth.php';
require 'db.php';
header('Content-Type: application/json');

try {
    // Validate required fields
    $required = [
        'id', 'description', 'property_no', 'purpose', 'borrow_datetime', 'duration_days',
        'borrowed_by', 'borrowed_by_designation',
        'noted_by', 'noted_by_designation',
        'approved_by', 'approved_by_designation',
        'status'
    ];

    foreach ($required as $field) {
        if (!isset($_POST[$field]) || trim($_POST[$field]) === '') {
            echo json_encode(['success' => false, 'message' => "Missing or empty field: $field"]);
            exit;
        }
    }

    $stmt = $conn->prepare("UPDATE borrower SET 
        description = ?, 
        property_no = ?, 
        purpose = ?, 
        borrow_datetime = ?, 
        duration_days = ?, 
        borrowed_by = ?, 
        borrowed_by_designation = ?,
        noted_by = ?, 
        noted_by_designation = ?,
        approved_by = ?, 
        approved_by_designation = ?,
        status = ?
        WHERE id = ?");

    $stmt->execute([
        trim($_POST['description']),
        trim($_POST['property_no']),
        trim($_POST['purpose']),
        trim($_POST['borrow_datetime']),
        trim($_POST['duration_days']),
        trim($_POST['borrowed_by']),
        trim($_POST['borrowed_by_designation']),
        trim($_POST['noted_by']),
        trim($_POST['noted_by_designation']),
        trim($_POST['approved_by']),
        trim($_POST['approved_by_designation']),
        trim($_POST['status']),
        trim($_POST['id'])
    ]);

    echo json_encode(['success' => true, 'message' => 'Borrower updated successfully!']);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
