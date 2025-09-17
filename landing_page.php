<?php
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CyberSecLab</title>
    <script src="js/index.js"></script>
    <link rel="stylesheet" href="css/index.css">
</head>
<body>
    <nav class="navbar">
        <div class="logo">CyberSecLab</div>
        <div class="nav-links">
            <div class="profile-dropdown">
                <a href="#profile">Profile</a>
                <div class="dropdown-menu">
                    <a href="signup.html">Account</a>
                    <a href="php/points.php">Points</a>
                    <a href="php/logout.php">Logout</a>
                    <a href="php/delete_data.php" onclick="return confirm('Are you sure you want to delete all non-admin data? This action cannot be undone.');">Delete Data</a>
                </div>
            </div>
        </div>
    </nav>
    <div class="container">
        <h2>Active Learning Paths</h2>
        <div class="path-grid">
            <div class="path-card" onclick="togglePath(1)">
                <h3>Web Security Fundamentals</h3>
                <p>Learn about XSS, SQLi, and more</p>
                <span class="difficulty beginner">Beginner</span>
                
                <div id="path-details-1" class="path-details">
                    <h4>Modules:</h4>
                    <div class="module-list">
                        <div class="module">
                            <h5>1. Introduction to Web Security</h5>
                            <p>You have just joined as a security analyst at a major company. Your first task is to understand how web applications work and identify common vulnerabilities before attackers exploit them.</p>
                            <p><strong>Hint:</strong> Think like a hacker. What common mistakes do developers make?</p>
                            <button class="btn" onclick="deployLab('intro')">Deploy Lab</button>
                        </div>
                        <div class="module">
                            <p>A mysterious attacker has been injecting malicious scripts into web pages, stealing user credentials. Can you find how they are doing it and stop them?</p>
                            <p><strong>Hint:</strong> User input fields are often the weakest link. Try injecting something unexpected.</p>
                            <button class="btn" onclick="deployLab('xss')">Deploy Lab</button>
                        </div>
                        <div class="module">
                            <p>Hackers are exploiting access control flaws to view unauthorized data. Your mission is to investigate and patch these vulnerabilities before customer data is leaked.</p>
                            <p><strong>Hint:</strong> URLs and API requests may reveal more than they should.just check the url and increment last digit!</p>
                            <button class="btn" onclick="deployLab('idor')">Deploy Lab</button>
                        </div>
                        <div class="module">
                            <p>Someone uploaded a suspicious file to the system, leading to a breach. Investigate and understand how attackers exploit file uploads.</p>
                            <p><strong>Hint:</strong> Not all files are safe. Can an uploaded file execute code?</p>
                            <button class="btn" onclick="deployLab('file')">Deploy Lab</button>
                        </div>
                    
                        <div class="module">
                            <p>The database has been compromised! Attackers are using malicious queries to extract sensitive data. Investigate and secure the database.</p>
                            <p><strong>Hint:</strong> Can you manipulate database queries through input fields?</p>
                            <button class="btn" onclick="deployLab('sqli')">Deploy Lab</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="path-card" onclick="togglePath(2)">
            <a href="../shop/index.html" class="path-card-link">
                <div class="path-card">
                    <h3>Shop CTF</h3>
                    <p>Explore the Shop CTF machine and find vulnerabilities.</p>
                    <span class="difficulty intermediate">Intermediate</span>
                    <p><strong>Hint:</strong> Always check source page!!.</p>
                </div>
            </a>
            </div>
        </div>
    </div>
        <div class="terminal">
            <div class="terminal-header">
                <div class="terminal-dots">
                    <span class="dot dot-red"></span>
                    <span class="dot dot-yellow"></span>
                    <span class="dot dot-green"></span>
                </div>
            </div>
        <div class="terminal-content">
            <pre>
root@kali:~# nmap -sV 10.10.10.10
Starting Nmap 7.91...
Scanning 10.10.10.10...
PORT   STATE SERVICE VERSION
80/tcp open  http    Apache/2.4.41
22/tcp open  ssh     OpenSSH 8.2p1
3306/tcp open mysql MySQL 8.0.23</pre>
        </div>
    </div>
    <div class="ctf-details">
    <h3>About This CTF Machine</h3>
    <p>
        Welcome to this challenge! Your task is to find vulnerabilities in this machine and exploit them.
        The system has multiple security flaws, including <strong>file upload vulnerabilities</strong> and 
        <strong>misconfigured web services</strong>.
    </p>
    <h4>Objectives:</h4>
    <ul>
        <li>Analyze open ports and running services.</li>
        <li>Find the vulnerable file upload functionality.</li>
        <li>Exploit it to gain system access.</li>
        <li>Capture the flag and submit it below.</li>
    </ul>
</div>
<div class="flag-submission">
    <h3>Submit Your Flag</h3>
    <input type="text" id="flag-input" placeholder="Enter flag (e.g., flag{...})">
    <button class="btn" onclick="checkFlag()">Submit Flag</button>
    <div id="flag-result"></div>
</div>

    </div>

    <script>
         // Function to handle flag submission
    function checkFlag() {
        const flag = document.getElementById('flag-input').value;
        const resultDiv = document.getElementById('flag-result');
        
        fetch('php/flag.php', {  
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ flag: flag })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                resultDiv.innerHTML = '<p style="color: green;">' + data.message + '</p>';
                if (data.points_awarded > 0) {
                    const pointsDisplay = document.getElementById('pointsDisplay');
                    if (pointsDisplay) {
                        fetchPoints();
                    }
                }
                document.getElementById('flag-input').value = '';
            } else {
                resultDiv.innerHTML = '<p style="color: red;">' + data.message + '</p>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            resultDiv.innerHTML = '<p style="color: red;">An error occurred while validating the flag</p>';
        });
    }

        // Optional: Simulate terminal output dynamically
        window.onload = () => {
            const terminalOutput = document.querySelector('.terminal');
            const lines = terminalOutput.innerText.split('\n');
            terminalOutput.innerText = ''; // Clear existing text

            let i = 0;
            const interval = setInterval(() => {
                if (i < lines.length) {
                    terminalOutput.innerText += lines[i] + '\n';
                    i++;
                } else {
                    clearInterval(interval);
                }
            }, 500); // Adjust speed of typing effect
        };
    </script>
</body>
</html>