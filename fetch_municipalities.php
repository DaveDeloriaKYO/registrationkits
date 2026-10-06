<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/db.php';

if (isset($_POST['province_id'])) {
    $province_id = $_POST['province_id'];
    $sql = "
        SELECT municipality_id, municipality_name
        FROM municipalities
        WHERE province_id = :province_id
    ";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->execute(['province_id' => $province_id]);
        $municipalities = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Generate select options
        $options = '<option value="">Select Municipality</option>';
        foreach ($municipalities as $municipality) {
            $options .= '<option value="' . $municipality['municipality_id'] . '">' . $municipality['municipality_name'] . '</option>';
        }
        echo $options;
    } catch (Exception $e) {
        echo '<option value="">Failed to load municipalities</option>';
    }
}
?>
