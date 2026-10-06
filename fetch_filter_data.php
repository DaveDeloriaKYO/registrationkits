<?php
require_once __DIR__ . '/auth.php';
header('Content-Type: application/json'); // Ensure JSON response

// Include database configuration
require 'db.php';

// Get POST parameters and sanitize them
$year_started = isset($_POST['year_started']) ? (int) $_POST['year_started'] : null;
$contract_duration = isset($_POST['contract_duration']) ? (int) $_POST['contract_duration'] : null;

// Check if both parameters are provided
if ($year_started && $contract_duration) {
    try {
        // Prepare the SQL query to fetch filtered personnel info
        $stmt = $conn->prepare("SELECT * FROM personnel_info WHERE year_started = :year_started AND contract_duration = :contract_duration");
        $stmt->bindParam(':year_started', $year_started, PDO::PARAM_INT);
        $stmt->bindParam(':contract_duration', $contract_duration, PDO::PARAM_INT);

        // Execute the query
        $stmt->execute();

        // Fetch the results
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Return JSON response
        if ($results) {
            echo json_encode($results);
        } else {
            echo json_encode(['message' => 'No data found for the selected criteria.']);
        }
    } catch (PDOException $e) {
        // Handle database errors gracefully
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    // Return error if parameters are missing or invalid
    echo json_encode(['error' => 'Invalid input. Please provide both year_started and contract_duration.']);
}
?>
