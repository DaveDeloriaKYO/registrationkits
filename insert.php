<?php
require_once __DIR__ . '/auth.php';
// Database configuration
$host = 'localhost';
$dbName = 'nid';
$username = 'root';
$password = '';

try {
    // Create a new PDO instance for database connection
    $conn = new PDO("mysql:host=$host;dbname=$dbName", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Collect form data from AJAX request
    $last_name = $_POST['last_name'] ?? '';
    $middle_name = $_POST['middle_name'] ?? '';  
    $first_name = $_POST['first_name'] ?? '';
    $position = $_POST['position'] ?? '';
    $year_started = $_POST['year_started'] ?? '';
    $contract_start_date = $_POST['contract_start_date'] ?? '';
    $contract_end_date = $_POST['contract_end_date'] ?? '';
    $regno = $_POST['regno'] ?? '';
    $contact_num = $_POST['contact_num'] ?? '';
    $email = $_POST['email'] ?? '';
    $status = $_POST['status'] ?? '';

    // Basic validation (ensure all fields are filled)
    if (empty($last_name) || empty($middle_name) || empty($first_name) || empty($position) ||
        empty($year_started) || empty($contract_start_date) || empty($contract_end_date) || 
        empty($regno) || empty($contact_num) || empty($email) || empty($status)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
        exit;
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Invalid email format.']);
        exit;
    }

    // Validate contact number (digits only, 10-15 characters)
    if (!preg_match('/^[0-9]{10,15}$/', $contact_num)) {
        echo json_encode(['success' => false, 'message' => 'Invalid contact number. It should be 10-15 digits long.']);
        exit;
    }

    // Check if email already exists to prevent duplicates
    $checkEmail = $conn->prepare("SELECT id FROM personnel_info WHERE email = :email");
    $checkEmail->bindParam(':email', $email);
    $checkEmail->execute();

    if ($checkEmail->rowCount() > 0) {
        echo json_encode(['success' => false, 'message' => 'Email already exists. Please use a different email.']);
        exit;
    }

    // Prepare the SQL query to insert personnel data
    $stmt = $conn->prepare("INSERT INTO personnel_info 
        (last_name, middle_name, first_name, position, year_started, contract_start_date, contract_end_date, regno, contact_num, email, status) 
        VALUES 
        (:last_name, :middle_name, :first_name, :position, :year_started, :contract_start_date, :contract_end_date, :regno, :contact_num, :email, :status)");

    // Bind the values to the placeholders
    $stmt->bindParam(':last_name', $last_name);
    $stmt->bindParam(':middle_name', $middle_name);
    $stmt->bindParam(':first_name', $first_name);
    $stmt->bindParam(':position', $position);
    $stmt->bindParam(':year_started', $year_started);
    $stmt->bindParam(':contract_start_date', $contract_start_date);
    $stmt->bindParam(':contract_end_date', $contract_end_date);
    $stmt->bindParam(':regno', $regno);
    $stmt->bindParam(':contact_num', $contact_num);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':status', $status);

    // Execute the query
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Personnel information has been added successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to insert personnel data.']);
    }
} catch (PDOException $e) {
    // Handle duplicate entry error (email conflict)
    if ($e->getCode() == 23000) {
        echo json_encode(['success' => false, 'message' => 'Duplicate entry error: Email already exists.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
}
?>
