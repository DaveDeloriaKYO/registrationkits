<?php
require_once __DIR__ . '/auth.php';
require 'db.php'; // ✅ include PDO connection

// Ensure proper UTF-8 encoding
header('Content-Type: text/html; charset=utf-8');

// Check if the province_id is set and not empty
if (isset($_POST['province_id']) && !empty($_POST['province_id'])) {
    $province_id = intval($_POST['province_id']); // Sanitize input

    try {
        // Prepare and execute query to fetch municipalities
        $stmt = $conn->prepare("SELECT municipality_id, municipality_name 
                                FROM municipalities 
                                WHERE province_id = :province_id 
                                ORDER BY municipality_name ASC");
        $stmt->bindParam(':province_id', $province_id, PDO::PARAM_INT);
        $stmt->execute();
        $municipalities = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Check if municipalities were found
        if ($municipalities) {
            echo '<option value="">-- Select Municipality --</option>';
            foreach ($municipalities as $municipality) {
                // Sanitize output to prevent XSS vulnerabilities
                $municipality_id = htmlspecialchars($municipality['municipality_id'], ENT_QUOTES, 'UTF-8');
                $municipality_name = htmlspecialchars($municipality['municipality_name'], ENT_QUOTES, 'UTF-8');
                echo "<option value=\"$municipality_id\">$municipality_name</option>";
            }
        } else {
            echo '<option value="">No municipalities found</option>';
        }
    } catch (PDOException $e) {
        // Log the error and provide a generic error message
        error_log("Error fetching municipalities: " . $e->getMessage()); // Log to server
        echo '<option value="">Error loading municipalities</option>';
    }
} else {
    echo '<option value="">-- Select Municipality --</option>';
}
