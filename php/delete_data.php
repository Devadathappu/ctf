<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Only allow the admin user to perform this action
if (!isset($_SESSION['user_id']) || !isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    echo "Access denied. Only the admin can perform this action.";
    exit;
}

require_once '../db/database.php'; // Adjust the path as necessary

// Get admin user's id (assuming the admin username is "admin")
$stmtAdmin = $pdo->prepare("SELECT id FROM users WHERE username = 'admin' LIMIT 1");
$stmtAdmin->execute();
$admin = $stmtAdmin->fetch(PDO::FETCH_ASSOC);

if (!$admin) {
    echo "Admin user not found.";
    exit;
}

$adminId = $admin['id'];

// First, delete from the user_points table for all users except admin
$stmt = $pdo->prepare("DELETE FROM user_points WHERE user_id != :adminId");
$stmt->execute(['adminId' => $adminId]);

// Then, delete from the users table for all users except admin
$stmt = $pdo->prepare("DELETE FROM users WHERE id != :adminId");
$stmt->execute(['adminId' => $adminId]);

$stmt = $pdo->prepare("DELETE FROM reviews");
$stmt->execute();
// Now clear all files in the uploads folder.
// Adjust the path to your uploads folder. In this example, the uploads folder is inside "assets/uploads/"
// which is one level up from the "php" folder.
$uploadFolder = __DIR__ . '/../challenges/file_upload/uploads/';

// Check if the folder exists
if (is_dir($uploadFolder)) {
    // Get all files in the uploads folder
    foreach (glob($uploadFolder . '*') as $file) {
        // If it's a file, delete it
        if (is_file($file)) {
            unlink($file);
        }
    }
    $uploadsStatus = " and all files in the uploads folder";
} else {
    $uploadsStatus = "";
}

echo "All non-admin data has been deleted successfully.";
?>
