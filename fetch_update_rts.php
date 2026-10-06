<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/db.php';

if (isset($_POST['trn'])) {
    $trn = $_POST['trn'];

    // Query to fetch the RTS record along with address components
    $sql = "
        SELECT r.trn, r.first_name, r.middle_name, r.last_name, r.date_received, r.status,
               r.province_id, r.municipality_id, r.barangay_id, r.purok_id,
               p.province_name, m.municipality_name, b.barangay_name, k.purok_name
        FROM rts r
        LEFT JOIN provinces p ON r.province_id = p.province_id
        LEFT JOIN municipalities m ON r.municipality_id = m.municipality_id
        LEFT JOIN barangays b ON r.barangay_id = b.barangay_id
        LEFT JOIN puroks k ON r.purok_id = k.purok_id
        WHERE r.trn = :trn
    ";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':trn', $trn);
        $stmt->execute();
        $rts = $stmt->fetch(PDO::FETCH_ASSOC);

        // Return the data as JSON
        echo json_encode($rts);
    } catch (Exception $e) {
        echo json_encode(['error' => 'Failed to fetch RTS record.']);
    }
}
?>
