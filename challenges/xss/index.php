<?php
session_start(); // Only call session_start() once at the beginning
require_once '../../db/database.php';
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);

// Check if products table is empty
$result = $pdo->query("SELECT COUNT(*) as count FROM products");
$row = $result->fetch(PDO::FETCH_ASSOC);

// Define flags
$reflected_xss_flag = "";
$stored_xss_flag = "";

// Get flag values from the database
$flags_result = $pdo->query("SELECT * FROM flags");
if ($flags_result && $flags_result->rowCount() > 0) {
    while ($flag = $flags_result->fetch(PDO::FETCH_ASSOC)) {
        if ($flag['id'] == '18') {
            $reflected_xss_flag = $flag['flag_value'];
        } else if ($flag['id'] == '19') {
            $stored_xss_flag = $flag['flag_value'];
        }
    }
}

// Handle product search (Reflected XSS vulnerability)
$search_term = "";
$xss_triggered = false;
if (isset($_GET['search'])) {
    $search_term = $_GET['search']; // Vulnerable to reflected XSS - no sanitization
    
    // Check if XSS might be triggered in the search term
    if (strpos(strtolower($search_term), '<script>') !== false) {
        $xss_triggered = true;
    }
}

// Handle review submission (Stored XSS vulnerability)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_review'])) {
    $product_id = $_POST['product_id'];
    $username = $_POST['username'];
    $comment = $_POST['comment']; // Vulnerable to stored XSS - no sanitization
    $rating = $_POST['rating'];
    
    $sql = "INSERT INTO reviews (product_id, username, comment, rating) 
            VALUES (?, ?, ?, ?)";
    
    $stmt = $pdo->prepare($sql);
    $success = $stmt->execute([$product_id, $username, $comment, $rating]);
    
    if ($success) {
        echo "<script>alert('Review submitted successfully!');</script>";
        
        // Check if this is an XSS attempt
        if (strpos(strtolower($comment), '<script>') !== false) {
            // Store in session that stored XSS was triggered
            $_SESSION['stored_xss_triggered'] = true;
        }
    } else {
        echo "<!-- Error adding review -->";
    }
}

// Check if stored XSS has been triggered before
$stored_xss_triggered = isset($_SESSION['stored_xss_triggered']) && $_SESSION['stored_xss_triggered'] === true;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SHOP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
        }
        header {
            background-color: #333;
            color: white;
            padding: 10px 0;
            text-align: center;
        }
        .search-bar {
            margin: 20px 0;
            text-align: center;
        }
        .search-bar input[type="text"] {
            padding: 10px;
            width: 50%;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .search-bar button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .search-results {
            background-color: #ffffcc;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
        }
        .products {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }
        .product {
            width: 30%;
            background-color: white;
            margin: 10px;
            padding: 15px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 5px;
        }
        .product-image {
            width: 100%;
            height: 200px;
            background-color: #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
        }
        .product h3 {
            margin: 10px 0;
        }
        .price {
            font-weight: bold;
            color: #4CAF50;
            font-size: 1.2em;
        }
        .reviews {
            margin-top: 20px;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
        .review {
            background-color: #f9f9f9;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
        }
        .review-form {
            margin-top: 20px;
        }
        .review-form input, .review-form textarea, .review-form select {
            width: 100%;
            padding: 8px;
            margin: 5px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .review-form button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }
        #message {
            margin-top: 20px; 
            padding: 10px; 
            background-color: #ffffcc;
            border-radius: 5px;
        }
        .flag {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
        }
        .hint {
            background-color: #f0f0f0;
            padding: 10px;
            border-left: 4px solid #4CAF50;
            margin: 10px 0;
        }
        .progress {
            background-color: #333;
            padding: 15px;
            margin-top: 20px;
            border-radius: 5px;
            color: white;
        }
        .progress-item {
            margin: 5px 0;
            padding: 5px;
            border-radius: 3px;
        }
        .completed {
            background-color: #4CAF50;
        }
        .pending {
            background-color: #f44336;
        }
    </style>
</head>
<body>
    <header>
        <h1>TechStore</h1>
    </header>
    
    <div class="container">
       
        <!-- Display flags if challenges are completed -->
        <?php if ($xss_triggered): ?>
            <div class="flag">
                Reflected XSS Flag: <?php echo $reflected_xss_flag; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($stored_xss_triggered): ?>
            <div class="flag">
                Stored XSS Flag: <?php echo $stored_xss_flag; ?>
            </div>
        <?php endif; ?>
        
        <!-- Hints for the challenges -->
        <div class="hint">
            <h3>Challenge Hints:</h3>
            <p>1. Try finding a way to execute JavaScript through the search box.</p>
            <p>2. Find a way to store JavaScript that executes when other users view the page.</p>
        </div>
        
        <!-- Search form (Reflected XSS vulnerability) -->
        <div class="search-bar">
            <form action="" method="GET">
                <input type="text" name="search" placeholder="Search products..." value="<?php echo $search_term; ?>">
                <button type="submit">Search</button>
            </form>
        </div>
        
        <!-- Search results - Reflected XSS vulnerability -->
        <?php if ($search_term): ?>
            <div class="search-results">
                <h2>Search Results for: <?php echo $search_term; ?></h2>
                <!-- The search term is reflected without sanitization -->
            </div>
        <?php endif; ?>
        
        <!-- Products listing -->
        <div class="products">
            <?php
            $sql = "SELECT * FROM products";
            if (!empty($search_term)) {
                $sql .= " WHERE name LIKE :search OR description LIKE :search";
                $stmt = $pdo->prepare($sql);
                $search_param = "%$search_term%";
                $stmt->bindParam(':search', $search_param);
                $stmt->execute();
            } else {
                $stmt = $pdo->query($sql);
            }
            
            if ($stmt && $stmt->rowCount() > 0) {
                while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo '<div class="product">';
                    echo '<div class="product-image">Product Image: ' . $row["name"] . '</div>';
                    echo '<h3>' . $row["name"] . '</h3>';
                    echo '<p>' . $row["description"] . '</p>';
                    echo '<p class="price">$' . $row["price"] . '</p>';
                    
                    // Reviews section (Stored XSS vulnerability)
                    echo '<div class="reviews">';
                    echo '<h4>Customer Reviews</h4>';
                    
                    $review_sql = "SELECT * FROM reviews WHERE product_id = :product_id";
                    $review_stmt = $pdo->prepare($review_sql);
                    $review_stmt->bindParam(':product_id', $row["id"]);
                    $review_stmt->execute();
                    
                    if ($review_stmt && $review_stmt->rowCount() > 0) {
                        while($review = $review_stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo '<div class="review">';
                            echo '<p><strong>' . $review["username"] . '</strong> - Rating: ' . $review["rating"] . '/5</p>';
                            echo '<p>' . $review["comment"] . '</p>'; // Outputs unsanitized user input (stored XSS)
                            echo '</div>';
                        }
                    } else {
                        echo '<p>No reviews yet. Be the first to review!</p>';
                    }
                    
                    // Review submission form
                    echo '<div class="review-form">';
                    echo '<h4>Add Review</h4>';
                    echo '<form method="POST" action="">';
                    echo '<input type="hidden" name="product_id" value="' . $row["id"] . '">';
                    echo '<input type="text" name="username" placeholder="Your Name" required>';
                    echo '<select name="rating" required>';
                    echo '<option value="" disabled selected>Select Rating</option>';
                    for ($i = 1; $i <= 5; $i++) {
                        echo '<option value="' . $i . '">' . $i . ' Star</option>';
                    }
                    echo '</select>';
                    echo '<textarea name="comment" placeholder="Your Review" required rows="4"></textarea>';
                    echo '<button type="submit" name="submit_review">Submit Review</button>';
                    echo '</form>';
                    echo '</div>'; // End review form
                    
                    echo '</div>'; // End reviews
                    echo '</div>'; // End product
                }
            } else {
                echo '<p>No products found. Please check your database connection or products table.</p>';
            }
            ?>
        </div>
        
        <!-- Additional message display area for JS-based XSS -->
        <div id="message"></div>
    </div>

    <script>
        // JavaScript to process URL parameters - also vulnerable to XSS
        function getUrlParameter(name) {
            name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
            var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
            var results = regex.exec(location.search);
            return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
        }
        
        // Vulnerable function that processes a query parameter
        function processMessage() {
            var message = getUrlParameter('message');
            if (message) {
                document.getElementById('message').innerHTML = message; // XSS vulnerability
            }
        }
        
        // Check if XSS was triggered through JavaScript
        function checkXssAttempt() {
            // Log for reflected XSS attempt
            if (document.querySelector('script:not([src])')) {
                // XSS might be present - set to completed via AJAX
                var xhr = new XMLHttpRequest();
                xhr.open('GET', 'update_xss_flag.php?type=reflected', true);
                xhr.send();
                
                // You could replace this with a more sophisticated check
                // For CTF purposes, this simple check works for detection
                if (!document.querySelector('.flag')) {
                    setTimeout(function() {
                        location.reload();
                    }, 200000);
                }
            }
        }
        
        // Call the functions when the page loads
        window.onload = function() {
            processMessage();
            setTimeout(checkXssAttempt, 1000); // Slight delay to let any injected scripts run
        };
    </script>
</body>
</html>