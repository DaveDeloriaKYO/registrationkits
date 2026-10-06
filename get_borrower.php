<?php
require_once __DIR__ . '/auth.php';
require_once 'db.php';

header('Content-Type: application/json');

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Missing borrower ID'
    ]);
    exit;
}

$id = $_GET['id'];

try {
    $stmt = $conn->prepare("SELECT * FROM borrower WHERE id = ?");
    $stmt->execute([$id]);
    $borrower = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($borrower) {
        echo json_encode([
            'success' => true,
            'borrower' => $borrower
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Borrower not found.'
        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
