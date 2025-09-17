<?php
// Hardcoded users with correct Base64-encoded IDs
$users = [
    base64_encode("1234") => [
        "name" => "John Doe",
        "email" => "john@example.com",
        "phone" => "9876543210",
        "age" => 25,
        "role" => "User"
    ],
    base64_encode("1235") => [
        "name" => "Alice Smith",
        "email" => "alice@example.com",
        "phone" => "9123456789",
        "age" => 27,
        "role" => "User",
        "flag" => "flag{idor_found_2024}"
    ]
];

// Get Base64-encoded user ID from URL
$encodedId = $_GET['id'] ?? '';

// Decode user ID
$decodedId = base64_decode($encodedId);

// Validate user existence
if (isset($users[$encodedId])) {
    $user = $users[$encodedId];
} else {
    die("Invalid User ID!");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #121212;
            color: white;
            padding: 20px;
        }
        .container {
            width: 300px;
            margin: auto;
            background: #1e1e1e;
            padding: 20px;
            border-radius: 10px;
        }
        .flag {
            color: green;
            font-weight: bold;
            font-size: 18px;
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Profile</h2>
        <p><strong>Name:</strong> <?= htmlspecialchars($user['name']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
        <p><strong>Phone:</strong> <?= htmlspecialchars($user['phone']) ?></p>
        <p><strong>Age:</strong> <?= htmlspecialchars($user['age']) ?></p>
        <p><strong>Role:</strong> <?= htmlspecialchars($user['role']) ?></p>
        
        <?php 
        if (isset($user['flag'])) { 
            echo "<p class='flag'>" . htmlspecialchars($user['flag']) . "</p>";
        }
        ?>
    </div>

</body>
</html>

