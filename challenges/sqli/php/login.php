<?php
session_start();
require_once '../../../db/database.php';

// Initialize error message
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve posted values
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Intentionally vulnerable query (for SQL injection challenge purposes)
    $query = "SELECT id FROM sqli_users WHERE username = '$username' AND password = '$password'";

    try {
        // Execute the query using PDO
        $stmt = $pdo->query($query);

        if ($stmt && $stmt->rowCount() > 0) {
            // Successful login: set session variable and redirect
            $_SESSION['user'] = $username;
            echo "<script>alert('Login Successful!'); window.location='../dashboard.html';</script>";
            exit();
        } else {
            // Invalid credentials: alert and redirect back to login page
            echo "<script>alert('Wrong Username or Password! Try Again.'); window.location='../login_sqli.html';</script>";
            exit();
        }
    } catch (PDOException $e) {
        die("SQL Error: " . $e->getMessage());
    }
}
?>
