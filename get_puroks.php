<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/db.php';
header('Content-Type: application/json');

if (!isset($_POST['barangay_id'])) {
    echo json_encode(["success" => false, "message" => "Barangay ID required"]);
    exit;
}

$barangay_id = $_POST['barangay_id'];

try {
    $stmt = $conn->prepare("SELECT purok_id, purok_name 
                            FROM puroks 
                            WHERE barangay_id = ? 
                            ORDER BY purok_name");
    $stmt->execute([$barangay_id]);

    $options = '<option value="">-- Select Purok --</option>';
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $options .= "<option value='{$row['purok_id']}'>{$row['purok_name']}</option>";
    }

    echo json_encode(["success" => true, "options" => $options]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
