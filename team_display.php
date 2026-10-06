<?php
require_once __DIR__ . '/auth.php';
include 'db.php';

try {
    // Fetch all active personnel including regno and municipality
    $stmt = $conn->prepare("
        SELECT municipality, last_name, first_name, middle_name, position, contact_num, contract_start_date, contract_end_date, regno
        FROM personnel_info 
        WHERE status = 'active'
        ORDER BY municipality IS NULL, municipality = '', municipality, last_name ASC
    ");
    $stmt->execute();
    $personnel = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($personnel) {
        // Group personnel by municipality
        $grouped = [];
        foreach ($personnel as $member) {
            $key = empty($member['municipality']) ? 'Not Assigned to Any Team' : $member['municipality'];
            $grouped[$key][] = $member;
        }

        // Display grouped personnel
        foreach ($grouped as $municipality => $members) {
            echo "<div class='mt-4'>";
            echo "<h6 class='fw-bold mb-2'>Municipality: {$municipality}</h6>";
            echo "<div class='table-responsive'>";
            echo "<table class='table table-bordered table-sm'>
                    <thead class='table-light'>
                        <tr>
                            <th>Reg No.</th>
                            <th>Last Name</th>
                            <th>First Name</th>
                            <th>Middle Name</th>
                            <th>Position</th>
                            <th>Contact No.</th>
                            <th>Contract Start</th>
                            <th>Contract End</th>
                        </tr>
                    </thead>
                    <tbody>";

            foreach ($members as $m) {
                echo "<tr>
                        <td>{$m['regno']}</td>
                        <td>{$m['last_name']}</td>
                        <td>{$m['first_name']}</td>
                        <td>{$m['middle_name']}</td>
                        <td>{$m['position']}</td>
                        <td>{$m['contact_num']}</td>
                        <td>{$m['contract_start_date']}</td>
                        <td>{$m['contract_end_date']}</td>
                      </tr>";
            }

            echo "</tbody></table></div></div>";
        }
    } else {
        echo "<div class='alert alert-info'>No personnel records found.</div>";
    }
} catch (PDOException $e) {
    echo "<div class='alert alert-danger'>Error fetching team data: " . $e->getMessage() . "</div>";
}
?>
