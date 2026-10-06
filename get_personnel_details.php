<?php
require_once __DIR__ . '/auth.php';
require_once 'db.php';
header('Content-Type: application/json');

$personnelId = $_GET['personnel_id'] ?? '';
if (!$personnelId) {
    echo json_encode(['success' => false, 'message' => 'Missing personnel_id.']);
    exit;
}

$stmt = $conn->prepare("SELECT * FROM personnel_info WHERE id = :id LIMIT 1");
$stmt->execute(['id' => $personnelId]);
$personnel = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$personnel) {
    echo json_encode(['success' => false, 'message' => 'Personnel not found.']);
    exit;
}

$remarks = [];
try {
    $remStmt = $conn->prepare("SELECT r.id, r.remark, r.created_by, r.created_at, r.quarterly_history_id, h.year, h.quarter FROM personnel_remarks r LEFT JOIN personnel_quarterly_history h ON h.id = r.quarterly_history_id WHERE r.personnel_id = :id ORDER BY r.created_at DESC");
    $remStmt->execute(['id' => $personnelId]);
    $remarks = $remStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Table not created yet — no remarks to show.
}

echo json_encode(['success' => true, 'personnel' => $personnel, 'remarks' => $remarks]);
