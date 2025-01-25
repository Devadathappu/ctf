<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$challenge_id = $_POST['challenge_id'] ?? 0;

// In a real application, you would validate the solution and update the database
$success = (bool) rand(0, 1);

echo json_encode(['success' => $success]);

