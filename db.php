<?php
// Database configuration
$host = 'localhost'; // Host name (e.g., localhost)
$dbName = 'nid'; // Database name
$username = 'root'; // Database username
$password = ''; // Database password (leave blank for default XAMPP/MAMP setup)

try {
    // Create a new PDO instance
    $conn = new PDO("mysql:host=$host;dbname=$dbName", $username, $password);
    
    // Set PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // echo "Database connection successful!";
} catch (PDOException $e) {
    // Handle connection errors
    echo "Database connection failed: " . $e->getMessage();
}
?>
