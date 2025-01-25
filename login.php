<?php
session_start();
header('Content-Type: application/json');

// In a real application, you would validate these against a database
$valid_username = 'user';
$valid_password = 'password';

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if ($username === $valid_username && $password === $valid_password) {
    $_SESSION['user_id'] = 1;
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}

