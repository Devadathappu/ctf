<?php
session_start();

// Include the database connection file
require_once '../db/database.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit;
}

$userId = $_SESSION['user_id'];

try {
    // Sum all points from the user_points table for this user
    $stmt = $pdo->prepare("SELECT SUM(points_awarded) AS total_points FROM user_points WHERE user_id = :id");
    $stmt->execute(['id' => $userId]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $totalPoints = $result['total_points'] ?? 0;

    echo json_encode(['success' => true, 'points' => $totalPoints]);
} catch (PDOException $e) {
    error_log($e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error occurred']);
}
?>