<?php
require_once __DIR__ . '/auth.php';
require_once 'db.php';
header('Content-Type: application/json');

$personnelId = $_GET['personnel_id'] ?? '';
if (!$personnelId) {
    echo json_encode(['success' => false, 'message' => 'Missing personnel_id.']);
    exit;
}

try {
    $stmt = $conn->prepare("
        SELECT quarter, year, position, municipality, regno, contract_start_date, contract_end_date, status
        FROM personnel_quarterly_history
        WHERE personnel_id = :personnel_id
        ORDER BY year DESC, FIELD(quarter, 'Q4','Q3','Q2','Q1')
    ");
    $stmt->execute(['personnel_id' => $personnelId]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['success' => true, 'data' => $rows]);
} catch (PDOException $e) {
    // Table may not exist yet if no quarterly record was ever added.
    echo json_encode(['success' => true, 'data' => []]);
}
