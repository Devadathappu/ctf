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
                    <a href="php/logout.php">Logout</a>
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
                            <p>Understanding web architecture and common vulnerabilities</p>
                            <button class="btn" onclick="deployLab('intro')">Deploy Lab</button>
                        </div>
                        <div class="module">
                            <h5>2. Cross-Site Scripting (XSS)</h5>
                            <p>Learn about different types of XSS attacks</p>
                            <button class="btn" onclick="deployLab('xss')">Deploy Lab</button>
                        </div>
                        <div class="module">
                            <h5>3. Indirect Object Reference (IDOR)</h5>
                            <p>Learn about IDOR attacks</p>
                            <button class="btn" onclick="deployLab('idor')">Deploy Lab</button>
                        </div>
                        <div class="module">
                            <h5>4. HTML Injection</h5>
                            <p>Learn about HTML Injection</p>
                            <button class="btn" onclick="deployLab('html')">Deploy Lab</button>
                        </div>
                        <div class="module">
                            <h5>5. Directory Traversal</h5>
                            <p>Learn about Directory Traversal</p>
                            <button class="btn" onclick="deployLab('traversal')">Deploy Lab</button>
                        </div>
                        <div class="module">
                            <h5>6. File Upload Vulnerability</h5>
                            <p>Learn about File Upload Vulnerability</p>
                            <button class="btn" onclick="deployLab('file')">Deploy Lab</button>
                        </div>
                        <div class="module">
                            <h5>7. Command Injection</h5>
                            <p>Learn about Command Injection Vulnerability</p>
                            <button class="btn" onclick="deployLab('command')">Deploy Lab</button>
                        </div>
                        <div class="module">
                            <h5>8. SQLi</h5>
                            <p>Learn about SQL Injection Vulnerability</p>
                            <button class="btn" onclick="deployLab('sqli')">Deploy Lab</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="path-card" onclick="togglePath(2)">
                <h3>Network Security</h3>
                <p>Master network protocols and exploitation</p>
                <span class="difficulty intermediate">Intermediate</span>
                
                <div id="path-details-2" class="path-details">
                    <h4>Modules:</h4>
                    <div class="module-list">
                        <div class="module">
                            <h5>1. Network Fundamentals</h5>
                            <p>Understanding OSI model and TCP/IP</p>
                            <button class="btn" onclick="deployLab('network')">Deploy Lab</button>
                        </div>
                        <div class="module">
                            <h5>2. Packet Analysis</h5>
                            <p>Learn to use Wireshark and analyze traffic</p>
                            <button class="btn" onclick="deployLab('packet')">Deploy Lab</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="challenge-area">
            <h3>CTF Challenge: Capture the Flag</h3>
            <div class="terminal">
                root@kali:~# nmap -sV 10.10.10.10
                Starting Nmap 7.91...
                Scanning 10.10.10.10...
                PORT   STATE SERVICE VERSION
                80/tcp open  http    Apache/2.4.41
                22/tcp open  ssh     OpenSSH 8.2p1
                3306/tcp open mysql MySQL 8.0.23
            </div>
            <p><strong>Objective:</strong> Exploit the vulnerable web application running on port 80 to retrieve the flag.</p>
            <p><strong>Hints:</strong></p>
            <ul>
                <li>The web application may be vulnerable to SQL Injection.</li>
                <li>Check for hidden directories using tools like `dirb` or `gobuster`.</li>
                <li>Look for sensitive information in source code or comments.</li>
            </ul>
            <p>Found the flag? Submit it below:</p>
            <input type="text" id="flag-input" placeholder="Enter flag (e.g., flag{...})">
            <button class="btn" onclick="submitFlag()">Submit Flag</button>
            <div id="flag-result"></div>
        </div>
    </div>

    <script>
        // Function to handle flag submission
        function submitFlag() {
            const flagInput = document.getElementById('flag-input').value;
            const resultDiv = document.getElementById('flag-result');

            // Example flag (replace with your actual flag)
            const correctFlag = 'flag{sql_injection_is_fun}';

            if (flagInput === correctFlag) {
                resultDiv.innerHTML = '<p style="color: green;">Correct! You captured the flag!</p>';
            } else {
                resultDiv.innerHTML = '<p style="color: red;">Incorrect flag. Try again!</p>';
            }
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