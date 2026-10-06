<?php
require_once __DIR__ . '/auth.php';
require 'db.php'; // ✅ include PDO connection

// Ensure proper UTF-8 encoding for special characters
header('Content-Type: text/html; charset=utf-8');

// Check if barangay_id is set and not empty
if (isset($_POST['barangay_id']) && !empty($_POST['barangay_id'])) {
    $barangay_id = intval($_POST['barangay_id']); // Sanitize input

    try {
        // Prepare and execute query to fetch puroks
        $stmt = $conn->prepare("SELECT purok_id, purok_name 
                                FROM puroks 
                                WHERE barangay_id = :barangay_id 
                                ORDER BY purok_name ASC");
        $stmt->bindParam(':barangay_id', $barangay_id, PDO::PARAM_INT);
        $stmt->execute();
        $puroks = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Check if puroks were found
        if ($puroks) {
            echo '<option value="">-- Select Purok --</option>';
            foreach ($puroks as $purok) {
                // Sanitize output to prevent XSS vulnerabilities
                $purok_id = htmlspecialchars($purok['purok_id'], ENT_QUOTES, 'UTF-8');
                $purok_name = htmlspecialchars($purok['purok_name'], ENT_QUOTES, 'UTF-8');
                echo "<option value=\"$purok_id\">$purok_name</option>";
            }
        } else {
            echo '<option value="">No puroks found</option>';
        }
    } catch (PDOException $e) {
        // Log error and provide a generic error message
        error_log("Error fetching puroks: " . $e->getMessage()); // Log to server
        echo '<option value="">Error loading puroks</option>';
    }
} else {
    echo '<option value="">-- Select Purok --</option>';
}
?>