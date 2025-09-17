<?php
header("Content-Type: text/html");

// Simulated user data
$users = [
    "1234" => [
        "name" => "John Doe",
        "username" => "user1",
        "email" => "john@example.com",
        "phone" => "123-456-7890",
        "age" => 25,
        "address" => "123 Main Street, NY",
        "bio" => "Cybersecurity enthusiast, loves CTF challenges."
    ],
    "1235" => [
        "name" => "Alice Smith",
        "username" => "user2",
        "email" => "alic  e@example.com",
        "phone" => "987-654-3210",
        "age" => 30,
        "address" => "456 Elm Street, CA",
        "bio" => "Web developer, passionate about ethical hacking."
    ]
];

// Get ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("<h1 style='color: red;'>Error: No user ID provided!</h1>");
}

$encoded_id = $_GET['id'];
$user_id = base64_decode($encoded_id);

if (!isset($users[$user_id])) {
    die("<h1 style='color: red;'>Error: User not found!</h1>");
}

// Fetch user data
$user = $users[$user_id];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <style>
        body { font-family: Arial, sans-serif; background: #1A1625; color: #E9ECEF; text-align: center; }
        .container { max-width: 500px; margin: auto; padding: 20px; background: #2D2438; border-radius: 10px; border: 1px solid #7B2CBF; box-shadow: 0 0 15px rgba(123, 44, 191, 0.3); }
        h1 { color: #7B2CBF; }
        .info { background: rgba(123, 44, 191, 0.1); padding: 15px; border-radius: 8px; border: 1px solid #9D4EDD; margin: 10px 0; text-align: left; }
        p { margin: 5px 0; }
    </style>
</head>
<body>

    <div class="container">
        <h1>User Profile</h1>
        <div class="info">
            <p><strong>Name:</strong> <?= htmlspecialchars($user['name']) ?></p>
            <p><strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
            <p><strong>Phone:</strong> <?= htmlspecialchars($user['phone']) ?></p>
            <p><strong>Age:</strong> <?= htmlspecialchars($user['age']) ?></p>
            <p><strong>Address:</strong> <?= htmlspecialchars($user['address']) ?></p>
            <p><strong>Bio:</strong> <?= htmlspecialchars($user['bio']) ?></p>
        </div>
        <p>Encoded User ID: <strong><?= htmlspecialchars($encoded_id) ?></strong></p>
        <p>Current URL: <strong><?= htmlspecialchars($_SERVER['REQUEST_URI']) ?></strong></p>
    </div>

</body>
</html>
