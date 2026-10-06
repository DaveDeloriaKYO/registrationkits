<?php
require_once __DIR__ . '/auth.php';
include 'db.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];

    // Prepare the delete query
    $sql = "DELETE FROM ismd_validated_regkits WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "Success";
    } else {
        echo "Error";
    }
} else {
    echo "Invalid request";
}
?>
