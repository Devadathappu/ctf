<?php
session_start();

// Include the database connection file
require_once '../db/database.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$submittedFlag = $data['flag'] ?? '';

try {
    // Find the flag in the flags table
    $stmt = $pdo->prepare("SELECT * FROM flags WHERE flag_value = ?");
    error_log("Executing query: SELECT * FROM flags WHERE flag_value = '$submittedFlag'");
    $stmt->execute([$submittedFlag]);
    $flag = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($flag) {
        // Check if the user has already found this flag
        $stmt = $pdo->prepare("SELECT user_id FROM user_points WHERE user_id = ? AND flag_id = ?");
        error_log("Executing query: SELECT id FROM user_points WHERE user_id = {$_SESSION['user_id']} AND flag_id = {$flag['id']}");
        $stmt->execute([$_SESSION['user_id'], $flag['id']]);
        if ($stmt->rowCount() === 0) {
            // User hasn't found this flag yet, award points
            $stmt = $pdo->prepare("INSERT INTO user_points (user_id, flag_id, points_awarded) VALUES (?, ?, ?)");
            error_log("Executing query: INSERT INTO user_points (user_id, flag_id, points_awarded) VALUES ({$_SESSION['user_id']}, {$flag['id']}, {$flag['points']})");
            $stmt->execute([$_SESSION['user_id'], $flag['id'], $flag['points']]);
            echo json_encode([
                'success' => true,
                'message' => "Congratulations! You've found the flag and earned {$flag['points']} points!",
                'points_awarded' => $flag['points']
            ]);
        } else {
            // User has already found this flag
            echo json_encode([
                'success' => true,
                'message' => "You've already found this flag!",
                'points_awarded' => 0
            ]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Incorrect flag. Try again!']);
    }
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error occurred']);
}

?>