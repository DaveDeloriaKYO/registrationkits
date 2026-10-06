<?php
require_once __DIR__ . '/auth.php';
require_once 'db.php';
header('Content-Type: application/json');

$conn->exec("
    CREATE TABLE IF NOT EXISTS personnel_quarterly_history (
        id INT AUTO_INCREMENT PRIMARY KEY,
        personnel_id INT NOT NULL,
        quarter ENUM('Q1','Q2','Q3','Q4') NOT NULL,
        year YEAR(4) NOT NULL,
        position VARCHAR(100) NOT NULL,
        municipality VARCHAR(100) DEFAULT NULL,
        regno INT DEFAULT NULL,
        contract_start_date DATE NOT NULL,
        contract_end_date DATE NOT NULL,
        status ENUM('active','inactive') NOT NULL DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY uniq_personnel_quarter (personnel_id, quarter, year)
    )
");

$personnelId  = $_POST['personnel_id'] ?? '';
$quarter      = $_POST['quarter'] ?? '';
$year         = $_POST['year'] ?? '';
$position     = trim($_POST['position'] ?? '');
$municipality = trim($_POST['municipality'] ?? '');
$regno        = $_POST['regno'] ?? null;
$contractStart = $_POST['contract_start_date'] ?? '';
$contractEnd   = $_POST['contract_end_date'] ?? '';
$status        = $_POST['status'] ?? 'active';

if (!$personnelId || !in_array($quarter, ['Q1', 'Q2', 'Q3', 'Q4'], true) || !$year
    || !$position || !$contractStart || !$contractEnd) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
    exit;
}

try {
    $stmt = $conn->prepare("
        INSERT INTO personnel_quarterly_history
            (personnel_id, quarter, year, position, municipality, regno, contract_start_date, contract_end_date, status)
        VALUES
            (:personnel_id, :quarter, :year, :position, :municipality, :regno, :contract_start_date, :contract_end_date, :status)
    ");
    $stmt->execute([
        'personnel_id'         => $personnelId,
        'quarter'              => $quarter,
        'year'                 => $year,
        'position'             => $position,
        'municipality'         => $municipality ?: null,
        'regno'                => $regno ?: null,
        'contract_start_date'  => $contractStart,
        'contract_end_date'    => $contractEnd,
        'status'               => $status,
    ]);
    echo json_encode(['success' => true, 'message' => 'Quarterly record added.']);
} catch (PDOException $e) {
    if ($e->getCode() === '23000') {
        echo json_encode(['success' => false, 'message' => "This personnel already has a record for $quarter $year. Edit it instead of adding a duplicate."]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
}
