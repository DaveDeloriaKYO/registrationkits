<?php
// Session guard — require_once this at the top of any page or AJAX
// endpoint that should only be reachable by a logged-in user.

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['user_id'])) {
    $isAjax = strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'xmlhttprequest';

    if ($isAjax) {
        http_response_code(401);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Please log in again.']);
    } else {
        header('Location: login.php');
    }
    exit;
}
