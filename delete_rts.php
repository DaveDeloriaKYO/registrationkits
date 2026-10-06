<?php
require_once __DIR__ . '/auth.php';
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "db.php"; // <-- make sure this sets up $conn or $pdo

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_POST['trn']) || empty($_POST['trn'])) {
            echo json_encode([
                "success" => false,
                "message" => "Missing TRN"
            ]);
            exit;
        }

        $trn = $_POST['trn'];

        // Prepare delete query
        $stmt = $conn->prepare("DELETE FROM rts WHERE trn = :trn");
        $stmt->bindParam(":trn", $trn, PDO::PARAM_STR);

        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                echo json_encode([
                    "success" => true,
                    "message" => "Record with TRN {$trn} deleted successfully."
                ]);
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "No record found with TRN {$trn}."
                ]);
            }
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Failed to execute delete query."
            ]);
        }
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Invalid request method."
        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Database error: " . $e->getMessage()
    ]);
}
