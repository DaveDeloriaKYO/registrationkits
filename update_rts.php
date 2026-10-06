<?php
require_once __DIR__ . '/auth.php';
require 'db.php'; // Include your PDO database connection

// Check if the necessary data has been sent via POST
if (isset($_POST['trn']) && isset($_POST['date_received']) && isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['province_id']) && isset($_POST['municipality_id']) && isset($_POST['barangay_id']) && isset($_POST['status'])) {
    
    // Sanitize and assign POST data to variables
    $trn = $_POST['trn'];
    $date_received = $_POST['date_received'];
    $first_name = $_POST['first_name'];
    $middle_name = isset($_POST['middle_name']) ? $_POST['middle_name'] : ''; // Optional field
    $last_name = $_POST['last_name'];
    $province_id = $_POST['province_id'];
    $municipality_id = $_POST['municipality_id'];
    $barangay_id = $_POST['barangay_id'];
    $purok_id = isset($_POST['purok_id']) && $_POST['purok_id'] !== "" ? $_POST['purok_id'] : null; // Optional field: use null if not provided
    $status = $_POST['status'];

    try {
        // Prepare the SQL statement for the update
        // Only update `purok_id` if it is provided (if it's not null)
        $sql = "UPDATE rts
                SET date_received = :date_received, 
                    first_name = :first_name, 
                    middle_name = :middle_name, 
                    last_name = :last_name, 
                    province_id = :province_id, 
                    municipality_id = :municipality_id, 
                    barangay_id = :barangay_id, 
                    status = :status";
        
        // If `purok_id` is provided, include it in the SQL update
        if ($purok_id !== null) {
            $sql .= ", purok_id = :purok_id";
        }

        // Add the WHERE condition
        $sql .= " WHERE trn = :trn";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind the parameters to the SQL query
        $stmt->bindParam(':date_received', $date_received);
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':middle_name', $middle_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':province_id', $province_id);
        $stmt->bindParam(':municipality_id', $municipality_id);
        $stmt->bindParam(':barangay_id', $barangay_id);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':trn', $trn);

        // Bind `purok_id` only if it's not null
        if ($purok_id !== null) {
            $stmt->bindParam(':purok_id', $purok_id, PDO::PARAM_INT);
        }

        // Execute the query
        $stmt->execute();

        // Check if any rows were affected (i.e., if the update was successful)
        if ($stmt->rowCount() > 0) {
            // If successful, return a success message
            echo json_encode([
                'success' => true,
                'message' => 'Record successfully updated!'
            ]);
        } else {
            // If no rows were updated, return an error message
            echo json_encode([
                'success' => false,
                'message' => 'No changes were made to the record.'
            ]);
        }

    } catch (PDOException $e) {
        // If an error occurs, catch the exception and return an error message
        echo json_encode([
            'success' => false,
            'message' => 'Error updating record: ' . $e->getMessage()
        ]);
    }
} else {
    // If required POST data is missing, return an error message
    echo json_encode([
        'success' => false,
        'message' => 'Missing required fields.'
    ]);
}
?>
