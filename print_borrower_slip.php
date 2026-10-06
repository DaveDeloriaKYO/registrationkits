<?php
require_once __DIR__ . '/auth.php';
require_once 'db.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    echo "Invalid request.";
    exit;
}

$stmt = $conn->prepare("SELECT * FROM borrower WHERE id = ?");
$stmt->execute([$id]);
$borrower = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$borrower) {
    echo "Borrower not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Borrower’s Slip</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .center {
            text-align: center;
        }
        .borrower-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .borrower-table th, .borrower-table td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }
        .signature {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
            padding: 0 50px;
        }
        .signature div {
            width: 30%;
            text-align: center;
        }
        .note {
            margin-top: 30px;
            font-size: 12px;
            font-style: italic;
            text-align: center;
        }
        .footer {
            position: fixed;
            bottom: 20px;
            font-size: 10px;
            text-align: center;
            width: 100%;
        }
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>

<div class="header center">
    <img src="assets/images/logos/HeaderPSA.png" alt="PSA Letter Head" style="width: 100%; max-width: 500px;">

    <!-- <h3>REPUBLIC OF THE PHILIPPINES<br>
        PHILIPPINE STATISTICS AUTHORITY<br>
        LEYTE</h3> -->
       
    <div style="text-align: right; margin-bottom: 10px;">
    <h4 style="margin: 0; font-weight: normal;">Annex C</h4>
</div>
    <div style="border: 2px solid black; padding: 5px; text-align: right; width: fit-content; margin-left: auto;">
    <h2 style="margin: 0;"><strong>BORROWER’S SLIP</strong></h2>
</div>

</div>

<table class="borrower-table">
    <thead>
        <tr>
            <th>Description of Property/Equipment</th>
            <th>Property No.</th>
            <th>Purpose</th>
            <th>Date and Time Borrowed</th>
            <th>Duration</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><?= htmlspecialchars($borrower['description']) ?></td>
            <td><?= htmlspecialchars($borrower['property_no']) ?></td>
            <td><?= htmlspecialchars($borrower['purpose']) ?></td>
            <td><?= htmlspecialchars($borrower['borrow_datetime']) ?></td>
            <td><?= htmlspecialchars($borrower['duration_days']) ?> day(s)</td>
        </tr>
    </tbody>
</table>

<div style="margin-top: 50px; font-family: Arial, sans-serif;">
    <!-- Borrowed by -->
    <div style="margin-bottom: 5px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px;">Borrowed by:</div>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px;">
        <div style="text-align: left; width: 300px;">
            <strong><?= htmlspecialchars($borrower['borrowed_by'] ?? '________________________') ?></strong><br>
            <span style="font-size: 12px;">(<?= htmlspecialchars($borrower['borrowed_by_designation'] ?? 'Name and Designation') ?>)</span>
        </div>
    </div>

    <!-- Noted by -->
    <div style="margin-bottom: 5px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; ">Noted by:</div>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px;">
        <div style="text-align: left; width: 300px;">
            <strong><?= htmlspecialchars($borrower['noted_by'] ?? '________________________') ?></strong><br>
            <span style="font-size: 12px;">(<?= htmlspecialchars($borrower['noted_by_designation'] ?? 'Name and Designation') ?>)</span>
        </div>
    </div>

    <!-- Approved by -->
    <div style="margin-bottom: 5px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px;">Approved by:</div>
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div style="text-align: left; width: 300px;">
            <strong><?= htmlspecialchars($borrower['approved_by'] ?? '________________________') ?></strong><br>
            <span style="font-size: 12px;">(<?= htmlspecialchars($borrower['approved_by_designation'] ?? 'Name and Designation') ?>)</span>
        </div>
    </div>
</div>

<p class="note">
    <strong>Note: This slip temporarily transfers the accountability of the above-mentioned property/equipment to the borrower.</strong>
</p>

<div class="footer">
    <img src="assets/images/logos/Footer_INEWISO.JPG" alt="Footer Logo" style="height: 60px;">
</div>

<div class="no-print center">
    <button onclick="window.print()">Print Borrower’s Slip</button>
</div>

</body>
</html>
