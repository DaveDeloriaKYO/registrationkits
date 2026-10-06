<?php
require_once __DIR__ . '/auth.php';
// Include the database connection file
include 'db.php';

// Set the content type header for JSON response
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 1. Retrieve all fields, including the new ones
    $id = $_POST['id'] ?? null;
    $lastName = $_POST['last_name'] ?? null;
    $firstName = $_POST['first_name'] ?? null;
    // New Fields
    $middleName = $_POST['middle_name'] ?? ''; // Middle name is optional in the DB but included in the payload
    $contactNum = $_POST['contact_num'] ?? null;
    $email = $_POST['email'] ?? null;
    
    $position = $_POST['position'] ?? null;
    $yearStarted = $_POST['year_started'] ?? null;
    $contractStart = $_POST['contract_start_date'] ?? null;
    $contractEnd = $_POST['contract_end_date'] ?? null;
    $regno = $_POST['regno'] ?? null;
    $status = $_POST['status'] ?? null;

    // 2. Validate essential data (matching the JS validation)
    if (empty($id) || empty($lastName) || empty($firstName) || empty($position) || empty($yearStarted) || empty($contractStart) || empty($contractEnd) || empty($regno) || empty($contactNum) || empty($email)) {
        echo json_encode(['success' => false, 'error' => 'All required fields are missing data.']);
        exit;
    }
    
    // Optional: Basic email format validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'error' => 'Invalid email format.']);
        exit;
    }

    try {
        // 3. Update query to include middle_name, contact_num, and email
        $sql = "UPDATE personnel_info SET 
                    last_name = ?, 
                    first_name = ?, 
                    middle_name = ?,
                    position = ?, 
                    year_started = ?, 
                    contract_start_date = ?, 
                    contract_end_date = ?, 
                    regno = ?, 
                    contact_num = ?,
                    email = ?,
                    status = ? 
                WHERE id = ?";
        
        $stmt = $conn->prepare($sql);
        
        // 4. Execute with all parameters in the correct order
        $stmt->execute([
            $lastName, 
            $firstName, 
            $middleName, 
            $position, 
            $yearStarted, 
            $contractStart, 
            $contractEnd, 
            $regno, 
            $contactNum,
            $email,
            $status, 
            $id
        ]);

        // 5. Fetch the updated row to return it to the JavaScript
        $stmt = $conn->prepare("SELECT * FROM personnel_info WHERE id = ?");
        $stmt->execute([$id]);
        $updatedRow = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if a row was actually found and updated
        if ($updatedRow) {
            echo json_encode(['success' => true, 'updated' => $updatedRow]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Record not found or no changes were made.']);
        }
    } catch (PDOException $e) {
        // Return a JSON error response on failure
        echo json_encode(['success' => false, 'error' => 'Error updating record: ' . $e->getMessage()]);
    }
} else {
    // Handle non-POST requests gracefully
    http_response_code(405); // Method Not Allowed
    echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
}
?>