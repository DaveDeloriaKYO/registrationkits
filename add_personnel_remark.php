<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/db.php';
header('Content-Type: application/json');

$personnelId = (int)($_POST['personnel_id'] ?? 0);
$quarterlyId = (int)($_POST['quarterly_history_id'] ?? 0);
$remark = trim($_POST['remark'] ?? '');

if (!$personnelId || !$quarterlyId || $remark === '') {
    echo json_encode(['success' => false, 'message' => 'Personnel, quarter, and remark are required.']);
    exit;
}

try {
    $stmt = $conn->prepare('SELECT id, year, quarter FROM personnel_quarterly_history WHERE id = ? AND personnel_id = ? LIMIT 1');
    $stmt->execute([$quarterlyId, $personnelId]);
    $quarter = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$quarter) {
        echo json_encode(['success' => false, 'message' => 'Invalid quarterly personnel record.']);
        exit;
    }

    $stmt = $conn->prepare('INSERT INTO personnel_remarks (personnel_id, quarterly_history_id, remark, created_by) VALUES (?, ?, ?, ?)');
    $stmt->execute([$personnelId, $quarterlyId, $remark, $_SESSION['username'] ?? null]);
    echo json_encode(['success' => true, 'message' => "Remark added for {$quarter['quarter']} {$quarter['year']}."]);
} catch (Throwable $e) {
    error_log($e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Unable to save the remark.']);
}
