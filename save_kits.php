<?php
require_once __DIR__ . '/auth.php';
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['kits'])) {
    $kits = $_POST['kits'];

    foreach ($kits as $kit) {
        $query = "INSERT INTO SavedKits (machine_id, computer_name, reg_code, regkit_serial_num, serial_num, password) 
                  VALUES (:machine_id, :computer_name, :reg_code, :regkit_serial_num, :serial_num, :password)";

        $stmt = $conn->prepare($query);
        $stmt->execute([
            ':machine_id' => $kit['machine_id'],
            ':computer_name' => $kit['computer_name'],
            ':reg_code' => $kit['reg_code'],
            ':regkit_serial_num' => $kit['regkit_serial_num'],
            ':serial_num' => $kit['serial_num'],
            ':password' => $kit['password']
        ]);
    }

    echo 'Saved';
} else {
    http_response_code(400);
    echo 'Invalid request';
}
?>
