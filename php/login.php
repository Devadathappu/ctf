<?php
session_start();
require_once '../db/database.php';

// Initialize error message
$error = '';

// Track failed login attempts in the session
if (!isset($_SESSION['failed_attempts'])) {
    $_SESSION['failed_attempts'] = 0; // Initialize failed attempts counter
}
if (!isset($_SESSION['lockout_time'])) {
    $_SESSION['lockout_time'] = null; // Initialize lockout time
}

// Check if the user is locked out
if ($_SESSION['lockout_time'] !== null) {
    $current_time = time();
    $lockout_duration = 300; // 5 minutes in seconds

    if ($current_time - $_SESSION['lockout_time'] < $lockout_duration) {
        // User is still locked out
        $remaining_time = ceil(($lockout_duration - ($current_time - $_SESSION['lockout_time'])) / 60);
        die("Too many failed login attempts. Please try again in $remaining_time minute(s).");
    } else {
        // Lockout period has expired, reset counters
        $_SESSION['failed_attempts'] = 0;
        $_SESSION['lockout_time'] = null;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        // Query the database for the user
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Successful login
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['failed_attempts'] = 0; // Reset failed attempts on successful login
            header('Location: ../landing_page.php');
            exit;
        } else {
            // Invalid credentials, increment failed attempts
            $_SESSION['failed_attempts']++;

            if ($_SESSION['failed_attempts'] >= 5) {
                // Lock the account for 5 minutes
                $_SESSION['lockout_time'] = time();
                // Display lockout message and the local GIF
                echo "<p>OOMBE OOMBE OOMBE!!! Please try again in 5 minutes.</p>";
                echo '<div style="max-width: 200px; margin: 0 auto;">';
                echo '<img src="../media/i-just-lost-my-dog-white-dog.gif" alt="Locked" style="width: 200px; display: block; margin: 0 auto;">';
                echo '</div>';
                exit();
            } else {
                // For less than 5 failed attempts, alert error and redirect to index.html in root
                $error = 'Invalid username or password.';
                echo "<script>alert('$error'); window.location.href='../index.html';</script>";
                exit;
            }
        }
    } else {
        $error = 'Please fill in all fields.';
        echo "<script>alert('$error'); window.location.href='../index.html';</script>";
        exit;
    }
}
?>
