<?php
require_once __DIR__ . '/auth.php';
require 'vendor/autoload.php'; // Load PhpSpreadsheet
require 'db.php'; // Include your database connection

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['excel_file'])) {
    $file = $_FILES['excel_file']['tmp_name'];

    if (!file_exists($file)) {
        echo json_encode(['success' => false, 'message' => 'No file uploaded.']);
        exit;
    }

    try {
        $spreadsheet = IOFactory::load($file);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(); // Convert Excel data to array

        // Remove header row
        array_shift($rows);

        // Prepare SQL query to insert data into personnel_info table
        $insertQuery = "INSERT INTO personnel_info 
            (last_name, first_name, middle_name, position, cluster, contact_num, email, 
             year_started, contract_start_date, contract_end_date, regno, status) 
             VALUES (:last_name, :first_name, :middle_name, :position, :cluster, :contact_num, :email, 
                     :year_started, :contract_start_date, :contract_end_date, :regno, :status)";

        $stmt = $conn->prepare($insertQuery);

        foreach ($rows as $row) {
            // Bind parameters and execute query
            $stmt->execute([
                ':last_name' => $row[0] ?? null,
                ':first_name' => $row[1] ?? null,
                ':middle_name' => $row[2] ?? null,
                ':position' => $row[3] ?? null,
                ':cluster' => $row[4] ?? null,
                ':contact_num' => $row[5] ?? null,
                ':email' => $row[6] ?? null,
                ':year_started' => $row[7] ?? null,
                ':contract_start_date' => $row[8] ?? null,
                ':contract_end_date' => $row[9] ?? null,
                ':regno' => $row[10] ?? null,
                ':status' => $row[11] ?? 'active', // Default status as 'active' if empty
            ]);
        }

        // Delete entry from excel_display table (assuming the latest uploaded file is being processed)
        $deleteQuery = "DELETE FROM excel WHERE excel_display = :file_name";
        $deleteStmt = $conn->prepare($deleteQuery);
        $deleteStmt->execute([':file_name' => $_FILES['excel_file']['name']]);

        echo json_encode(['success' => true, 'message' => 'Data successfully imported into personnel_info.']);

    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error processing file: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>
