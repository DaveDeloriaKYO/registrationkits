<?php
require_once __DIR__ . '/auth.php';
include 'db.php';

$barangay_id = $_POST['barangay_id'];

$stmt = $conn->prepare("SELECT purok_id, purok_name 
                        FROM puroks 
                        WHERE barangay_id = :barangay_id 
                        ORDER BY purok_name");
$stmt->execute(['barangay_id' => $barangay_id]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo '<option value="">-- None --</option>'; // make purok optional
foreach($rows as $row){
    echo '<option value="'.$row['purok_id'].'">'.$row['purok_name'].'</option>';
}
?>
