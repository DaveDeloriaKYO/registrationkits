<?php
require_once __DIR__ . '/auth.php';
include 'db.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['regno'])) {
    $regno = $_POST['regno'];

    try {
        $sql = "DELETE FROM RegistrationDetails WHERE regno = :regno";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':regno', $regno, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo "Success";
        } else {
            echo "Error: Failed to delete record.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Error: Invalid request.";
}
?>
