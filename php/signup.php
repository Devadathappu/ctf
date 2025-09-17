<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once '../db/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if form data is received
    if (!isset($_POST['username']) || !isset($_POST['password'])) {
        die("Error: Form data not received.");
    }

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Validate input
    if (empty($username) || empty($password)) {
        echo "<script>alert('Please fill in all fields.'); window.location.href='../signup.html';</script>";
        exit;
    }

    if (strlen($username) < 3) {
        echo "<script>alert('Username must be at least 3 characters long.'); window.location.href='../signup.html';</script>";
        exit;
    }

    if (strlen($password) < 8) {
        echo "<script>alert('Password must be at least 8 characters long.'); window.location.href='../signup.html';</script>";
        exit;
    }

    try {
        // Check database connection
        if (!$pdo) {
            die("Database connection failed.");
        }

        // Check if username exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);

        if ($stmt->rowCount() > 0) {
            echo "<script>alert('Username already exists.'); window.location.href='../signup.html';</script>";
            exit;
        }

        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert user into database
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        $result = $stmt->execute([
            'username' => $username,
            'password' => $hashedPassword
        ]);

        if ($result) {
            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['username'] = $username;
            header('Location: ../landing_page.php');
            exit;
        } else {
            echo "<script>alert('Registration failed. Please try again.'); window.location.href='../signup.html';</script>";
            exit;
        }
    } catch (PDOException $e) {
        error_log("Registration error: " . $e->getMessage());
        echo "<script>alert('An error occurred during registration. Please try again.'); window.location.href='../signup.html';</script>";
        exit;
    }
}
?>
