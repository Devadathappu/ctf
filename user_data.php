<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

// In a real application, you would fetch this data from a database
$user_data = [
    'completedChallenges' => 2,
    'totalPoints' => 300
];

echo json_encode($user_data);

