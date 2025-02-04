<?php
require_once '../db/database.php';

try {
    $stmt = $pdo->query("SELECT * FROM users");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "Username: " . htmlspecialchars($row['username']) . "<br>";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>