<?php
require_once __DIR__ . '/auth.php';
require 'db.php'; // ✅ include PDO connection

// Ensure proper UTF-8 encoding for special characters
header('Content-Type: text/html; charset=utf-8');

// Check if municipality_id is set and not empty
if (isset($_POST['municipality_id']) && !empty($_POST['municipality_id'])) {
    $municipality_id = intval($_POST['municipality_id']); // Sanitize input

    try {
        // Prepare and execute query to fetch barangays
        $stmt = $conn->prepare("SELECT barangay_id, barangay_name 
                                FROM barangays 
                                WHERE municipality_id = :municipality_id 
                                ORDER BY barangay_name ASC");
        $stmt->bindParam(':municipality_id', $municipality_id, PDO::PARAM_INT);
        $stmt->execute();
        $barangays = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Check if barangays were found
        if ($barangays) {
            echo '<option value="">-- Select Barangay --</option>';
            foreach ($barangays as $barangay) {
                // Sanitize output to prevent XSS vulnerabilities
                $barangay_id = htmlspecialchars($barangay['barangay_id'], ENT_QUOTES, 'UTF-8');
                $barangay_name = htmlspecialchars($barangay['barangay_name'], ENT_QUOTES, 'UTF-8');
                echo "<option value=\"$barangay_id\">$barangay_name</option>";
            }
        } else {
            echo '<option value="">No barangays found</option>';
        }
    } catch (PDOException $e) {
        // Log error and provide a generic error message
        error_log("Error fetching barangays: " . $e->getMessage()); // Log to server
        echo '<option value="">Error loading barangays</option>';
    }
} else {
    echo '<option value="">-- Select Barangay --</option>';
}
