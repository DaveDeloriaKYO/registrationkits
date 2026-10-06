<?php
require_once __DIR__ . '/auth.php';
include 'db.php';
require 'vendor/autoload.php'; // Make sure the PhpSpreadsheet autoload is included

use PhpOffice\PhpSpreadsheet\IOFactory;

$response = ['success' => false, 'error' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];

    // Check for errors
    if ($file['error'] != 0) {
        $response['error'] = 'File upload failed.';
        echo json_encode($response);
        exit;
    }

    $fileType = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    // Validating file type
    if (!in_array($fileType, ['csv', 'xlsx', 'xls'])) {
        $response['error'] = 'Invalid file type. Only CSV and Excel files are allowed.';
        echo json_encode($response);
        exit;
    }

    try {
        if ($fileType == 'csv') {
            // Read CSV File
            $filePath = $file['tmp_name'];
            if (($handle = fopen($filePath, 'r')) !== false) {
                // Skip header
                fgetcsv($handle);
                while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                    $stmt = $conn->prepare("INSERT INTO personnel_info (last_name, first_name, position, year_started, contract_start_date, contract_end_date, regno, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6], $data[7]]);
                }
                fclose($handle);
            }
        } elseif (in_array($fileType, ['xlsx', 'xls'])) {
            // Read Excel File
            $filePath = $file['tmp_name'];
            $spreadsheet = IOFactory::load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            foreach ($sheet->getRowIterator() as $rowIndex => $row) {
                if ($rowIndex == 1) continue; // Skip header row

                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);
                $data = [];
                foreach ($cellIterator as $cell) {
                    $data[] = $cell->getFormattedValue();
                }

                // Insert data into database
                $stmt = $conn->prepare("INSERT INTO personnel_info (last_name, first_name, position, year_started, contract_start_date, contract_end_date, regno, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6], $data[7]]);
            }
        }

        // If everything is successful, return success response
        $response['success'] = true;
        echo json_encode($response);
        exit;
    } catch (Exception $e) {
        $response['error'] = 'An error occurred: ' . $e->getMessage();
        echo json_encode($response);
        exit;
    }
} else {
    $response['error'] = 'No file uploaded.';
    echo json_encode($response);
    exit;
}
?>
