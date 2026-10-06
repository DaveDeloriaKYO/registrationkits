<?php
require_once __DIR__ . '/auth.php';
require_once "db.php";

$municipality_id = $_POST['municipality_id'] ?? null;

if ($municipality_id) {
    $stmt = $conn->prepare("SELECT barangay_id, barangay_name FROM barangays WHERE municipality_id = ? ORDER BY barangay_name ASC");
    $stmt->execute([$municipality_id]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $options = "<option value=''>-- Select Barangay --</option>";
    foreach ($rows as $row) {
        $options .= "<option value='{$row['barangay_id']}'>{$row['barangay_name']}</option>";
    }
    echo $options;
}
