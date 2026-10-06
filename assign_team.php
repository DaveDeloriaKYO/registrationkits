<?php
require_once __DIR__ . '/auth.php';
include 'db.php';

$data = json_decode(file_get_contents('php://input'), true);

$municipality = $data['municipality'];
$members = $data['members']; // Array of personnel_info IDs

$response = ['success' => false];

if (!$municipality || count($members) == 0 || count($members) > 4) {
    $response['message'] = "Invalid input. Please ensure municipality is provided and the member list contains between 1 and 4 members.";
    echo json_encode($response);
    exit;
}

try {
    foreach ($members as $id) {
        // Update the municipality in the personnel_info table for each member
        $update = $conn->prepare("UPDATE personnel_info SET municipality = :municipality WHERE id = :id");
        $update->execute([
            ':municipality' => $municipality,
            ':id' => $id
        ]);
    }

    $response['success'] = true;
    $response['message'] = 'Municipality updated for the selected members.';
} catch (Exception $e) {
    $response['message'] = 'Error: ' . $e->getMessage();
}

echo json_encode($response);
?>
