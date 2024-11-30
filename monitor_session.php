<?php
session_start();
require_once 'db.php';  // Include database connection and functions

header('Content-Type: application/json'); // Ensure the content type is JSON

$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
if (!$userId) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

// Monitor user session and check for anomalies without logging on each poll
$result = logUserActivity($userId, true);  // Use the $monitoring flag

// Return the result to the client
if ($result['status'] === 'anomaly') {
    echo json_encode(['status' => 'anomaly', 'anomalies' => $result['anomalies']]);
} else {
    echo json_encode(['status' => 'safe']);
}
