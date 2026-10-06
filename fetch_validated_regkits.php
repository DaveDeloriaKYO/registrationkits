<?php
require_once __DIR__ . '/auth.php';
include 'db.php'; // Include database connection

$sql = "SELECT * FROM ismd_validated_regkits ORDER BY id DESC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($results) {
    foreach ($results as $row) {
        echo "
            <tr>
                <td>{$row['machine_id']}</td> <!-- Added machine_id column -->
                <td>{$row['computer_name']}</td>
                <td>{$row['reg_code']}</td>
                <td>{$row['regkit_serial_num']}</td>
                <td>{$row['serial_number']}</td>
               
                <td><button class='btn btn-danger btn-sm deleteRow' data-id='{$row['id']}'>Delete</button></td>
            </tr>
        ";
    }
} else {
    echo "<tr><td colspan='6' class='text-center'>No records found</td></tr>";
}
?>
