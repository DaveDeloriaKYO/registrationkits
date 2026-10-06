<?php
require_once __DIR__ . '/auth.php';
include 'db.php'; // Database connection

// Fetch total count
$sql_count = "SELECT COUNT(*) AS total FROM RegistrationDetails";
$stmt_count = $conn->prepare($sql_count);
$stmt_count->execute();
$totalCount = $stmt_count->fetch(PDO::FETCH_ASSOC)['total'];

// Fetch registration details
$sql = "SELECT * FROM RegistrationDetails ORDER BY regno ASC";
$stmt = $conn->prepare($sql);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

$html = '';
if ($results) {
    foreach ($results as $index => $row) {
        $regno_value       = htmlspecialchars($row['regno'] ?? '');
        $machine_id        = htmlspecialchars($row['machine_id'] ?? '');
        $computer_name     = htmlspecialchars($row['computer_name'] ?? '');
        $regkit_serial_num = htmlspecialchars($row['regkit_serial_num'] ?? '');
        $serial_number     = htmlspecialchars($row['serial_number'] ?? '');
        $property_num      = htmlspecialchars($row['property_num'] ?? '');
        $password          = htmlspecialchars($row['password'] ?? '');
        $rcs_uname_code    = htmlspecialchars($row['rcs_uname_code'] ?? '');
        $reg_center_code   = htmlspecialchars($row['reg_center_code'] ?? '');
        $reg_code_display  = htmlspecialchars($row['regno'] ?? '');

        $html .= '<tr>
                    <td style="display:none;">' . ($index + 1) . '</td>
                    <td>' . $machine_id . '</td>
                    <td>' . $computer_name . '</td>
                    <td>' . $regkit_serial_num . '</td>
                    <td>' . $serial_number . '</td>
                    <td>' . $property_num . '</td>
                    <td>
                      <span class="password-mask" data-value="' . $password . '">••••••••</span>
                      <i class="fa-solid fa-eye toggle-pw-icon" title="Show/hide" onclick="togglePasswordCell(this)"></i>
                    </td>
                    <td>' . $rcs_uname_code . '</td>
                    <td>' . $reg_center_code . '</td>
                    <td><span class="badge reg-code-badge">' . $reg_code_display . '</span></td>
                    <td class="text-nowrap">
                        <button class="btn btn-sm btn-warning" title="Edit" onclick="showUpdateModal(' . $regno_value . ')"><i class="fa-solid fa-pen"></i></button>
                        <button class="btn btn-sm btn-danger" title="Delete" onclick="deleteRegistration(' . $regno_value . ')"><i class="fa-solid fa-trash"></i></button>
                    </td>
                  </tr>';
    }
} else {
    $html = '<tr><td colspan="10" class="text-center text-muted py-4">No registration kits yet — click "Add Registration Kit" to create one.</td></tr>';
}

echo json_encode(["total" => $totalCount, "html" => $html]);
