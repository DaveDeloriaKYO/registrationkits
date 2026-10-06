<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/db.php';

try {
    $sql = "SELECT province_id, province_name FROM provinces ORDER BY province_name";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $provinces = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Generate HTML options
    $options = '<option value="">Select Province</option>';
    foreach ($provinces as $province) {
        $options .= '<option value="' . htmlspecialchars($province['province_id']) . '">' . htmlspecialchars($province['province_name']) . '</option>';
    }
    
    echo $options;

} catch (Exception $e) {
    echo '<option value="">Error loading provinces</option>';
}
?>
