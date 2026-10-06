<?php
require_once __DIR__ . '/auth.php';
include 'db.php';

$municipality_id = $_POST['municipality_id'];

$stmt = $conn->prepare("SELECT barangay_id, barangay_name 
                        FROM barangays 
                        WHERE municipality_id = :municipality_id 
                        ORDER BY barangay_name");
$stmt->execute(['municipality_id' => $municipality_id]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo '<option value="">Select Barangay</option>';
foreach($rows as $row){
    echo '<option value="'.$row['barangay_id'].'">'.$row['barangay_name'].'</option>';
}
?>
