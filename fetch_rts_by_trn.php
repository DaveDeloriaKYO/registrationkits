<?php
require_once __DIR__ . '/auth.php';
// Include database connection
require_once 'db.php';

if (isset($_POST['trn'])) {
    $trn = $_POST['trn'];
    try {
        $sql = "
            SELECT r.trn, r.first_name, r.middle_name, r.last_name,
                   r.date_received, r.status,
                   r.province_id, r.municipality_id, r.barangay_id, r.purok_id
            FROM rts r
            WHERE r.trn = :trn
        ";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':trn', $trn);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            // If the record is found, return the data as JSON
            echo json_encode($result);
        } else {
            echo json_encode(['error' => 'Record not found']);
        }

    } catch (PDOException $e) {
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}
?>
