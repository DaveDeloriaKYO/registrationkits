<?php
require_once __DIR__ . '/auth.php';
require 'db.php'; // PDO connection

header('Content-Type: text/html; charset=utf-8'); // Ensure proper encoding for special characters

// Fetch all provinces
try {
    // Prepare and execute query to get provinces
    $stmt = $conn->prepare("SELECT province_id, province_name FROM provinces ORDER BY province_name ASC");
    $stmt->execute();
    $provinces = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Check if provinces were found
    if ($provinces) {
        foreach ($provinces as $province) {
            // Sanitize output to prevent XSS vulnerabilities
            $province_id = htmlspecialchars($province['province_id'], ENT_QUOTES, 'UTF-8');
            $province_name = htmlspecialchars($province['province_name'], ENT_QUOTES, 'UTF-8');
            echo "<option value=\"$province_id\">$province_name</option>";
        }
    } else {
        echo '<option value="">No provinces found</option>';
    }
} catch (PDOException $e) {
    // Log error and show a generic error message
    error_log("Error fetching provinces: " . $e->getMessage()); // Log to server
    echo '<option value="">Error loading provinces</option>';
}
