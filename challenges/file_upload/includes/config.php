<?php
// includes/config.php

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');          // MySQL username
define('DB_PASS', 'Appu@kali');      // MySQL password
define('DB_NAME', 'cyberseclab');     // Database name

// File upload configuration
define('UPLOAD_DIR', __DIR__ . '/../assets/uploads/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB

// Allowed file types - intentionally vulnerable
$ALLOWED_TYPES = array(
    'image/jpeg',
    'image/png',
    'image/gif',
    'application/pdf'
);
?>
