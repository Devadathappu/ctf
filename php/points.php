<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: /ctf/index.html'); // Redirect to login page if not logged in
    exit;
}

// Prevent browser caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

require_once '../db/database.php';

$userId = $_SESSION['user_id'];

// Fetch the current user's points from the database
$stmt = $pdo->prepare("SELECT SUM(points_awarded) AS total_points FROM user_points WHERE user_id = :id");
$stmt->execute(['id' => $userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$totalPoints = $user['total_points'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>CyberSecLab - Points</title>
  <link rel="stylesheet" href="../css/index.css">
  <script src="../js/index.js"></script>
  <style>
    /* Additional styles specific to the Points page */
    .points-container {
      max-width: 400px;
      margin: 2rem auto;
      background: rgba(255, 255, 255, 0.05);
      padding: 2rem;
      border-radius: 10px;
      text-align: center;
    }
    .points-container h2 {
      margin-bottom: 1rem;
      color: #7b68ee;
    }
    .points-display {
      font-size: 3rem;
      color: #ffffff;
      margin: 20px 0;
    }
    .btn {
      padding: 0.8rem 1.5rem;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-weight: bold;
      margin-top: 1rem;
      background: #7b68ee;
      color: white;
      transition: transform 0.3s;
    }
    .btn:hover {
      transform: translateY(-2px);
    }
  </style>
</head>
<body>
  <!-- Navigation bar using your theme -->
  <nav class="navbar">
      <div class="logo">CyberSecLab</div>
      <div class="nav-links">
          <div class="profile-dropdown">
              <a href="#profile">Profile</a>
              <div class="dropdown-menu">
                  <a href="../signup.html">Account</a>
                  <a href="points.php">Points</a>
                  <a href="logout.php">Logout</a>
              </div>
          </div>
      </div>
  </nav>

  <div class="container">
    <div class="points-container">
      <h2>Your Points</h2>
      <div class="points-display" id="pointsDisplay"><?php echo htmlspecialchars($totalPoints); ?></div>
      <button class="btn" id="refreshBtn">Refresh Points</button>
    </div>
  </div>

  <script>
    // Refresh the points display using the API endpoint
    document.getElementById('refreshBtn').addEventListener('click', function(){
      fetch('points_api.php')
        .then(response => response.json())
        .then(data => {
          if(data.success) {
            document.getElementById('pointsDisplay').textContent = data.points;
          } else {
            document.getElementById('pointsDisplay').textContent = 'Error';
          }
        })
        .catch(err => {
          console.error(err);
          document.getElementById('pointsDisplay').textContent = 'Error';
        });
    });
  </script>
</body>
</html>
