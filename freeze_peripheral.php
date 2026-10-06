<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/db.php';
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); echo json_encode(['success'=>false,'message'=>'Method Not Allowed.']); exit; }
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$action = $_POST['action'] ?? '';
if (!$id || !in_array($action, ['freeze','unfreeze'], true)) { http_response_code(400); echo json_encode(['success'=>false,'message'=>'Invalid freeze request.']); exit; }
try {
    $stmt=$conn->prepare('UPDATE peripherals SET is_frozen = ?, frozen_at = ?, frozen_by = ? WHERE id = ?');
    $user=$_SESSION['username'] ?? 'Administrator';
    $frozen=$action==='freeze'?1:0;
    $stmt->execute([$frozen, $frozen?date('Y-m-d H:i:s'):null, $frozen?$user:null, $id]);
    if (!$stmt->rowCount()) { $check=$conn->prepare('SELECT id FROM peripherals WHERE id=?'); $check->execute([$id]); if(!$check->fetch()){http_response_code(404);echo json_encode(['success'=>false,'message'=>'Inventory record not found.']);exit;} }
    echo json_encode(['success'=>true,'message'=>$frozen?'Inventory record frozen successfully.':'Inventory record unfrozen successfully.']);
} catch(PDOException $e) { http_response_code(500); echo json_encode(['success'=>false,'message'=>'Database operation failed.']); }
