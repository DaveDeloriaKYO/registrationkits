<?php
require_once __DIR__ . '/auth.php';
include 'db.php';  // Include your database connection script

// Check if the ID is provided for deletion
if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // SQL query to delete the registration record
    $query = "DELETE FROM registrationdetails WHERE id = :id";

    // Prepare the query
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id);

    // Execute the query
    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }
} else {
    echo 'error';
}
?>
