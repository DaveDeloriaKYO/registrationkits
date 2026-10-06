<?php
require_once __DIR__ . '/auth.php';
// Include database connection
require_once 'db_connection.php'; // Adjust the path to your actual database connection file

// Check if TRN is set
if (isset($_POST['trn'])) {
    $trn = $_POST['trn'];

    // Prepare the SQL query to fetch RTS details
    $sql = "SELECT r.*, p.province_name, m.municipality_name, b.barangay_name, po.purok_name
            FROM rts_records r
            LEFT JOIN provinces p ON r.province_id = p.province_id
            LEFT JOIN municipalities m ON r.municipality_id = m.municipality_id
            LEFT JOIN barangays b ON r.barangay_id = b.barangay_id
            LEFT JOIN puroks po ON r.purok_id = po.purok_id
            WHERE r.trn = ?";

    // Prepare statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters
        $stmt->bind_param("s", $trn); // Assuming TRN is a string, adjust if necessary

        // Execute query
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Check if any record was found
        if ($result->num_rows > 0) {
            // Fetch the record as an associative array
            $record = $result->fetch_assoc();

            // Return the result as JSON
            echo json_encode($record);
        } else {
            // No record found
            echo json_encode(['error' => 'Record not found']);
        }

        // Close statement
        $stmt->close();
    } else {
        // Query preparation error
        echo json_encode(['error' => 'Failed to prepare query']);
    }

    // Close the database connection
    $conn->close();
} else {
    // TRN not set
    echo json_encode(['error' => 'TRN not provided']);
}
?>
