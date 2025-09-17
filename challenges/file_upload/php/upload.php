<?php
// php/upload.php – Vulnerable File Upload Handler for the Challenge

// Define the directory where uploads are stored
$uploadDir = "../uploads/";

// Ensure the uploads directory exists (create if needed)
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Check if a file has been uploaded via "fileInput" from file.html
if (isset($_FILES["fileInput"])) {
    $fileName   = $_FILES["fileInput"]["name"];
    $targetFile = $uploadDir . basename($fileName);

    // 1. Special case: if the file name is "flag.php" (case-insensitive),
    //    write a simple PHP script that displays the flag
    if (strcasecmp($fileName, "flag.php") === 0) {
        // Payload that, when accessed, displays the flag
        $payload = "<?php echo 'FLAG{vulnerable_file_upload}'; ?>";

        // Write the payload to the target file
        if (file_put_contents($targetFile, $payload) !== false) {
            echo "File uploaded successfully! stored inside uploads folder inside file_upload.";
        } else {
            echo "Error writing flag payload.";
        }
        exit;
    }

    // 2. Flawed validation check:
    //    Block files containing the lowercase substring "php"
    //    Because this is case-sensitive, "shell.PHP" or "shEll.pHp" will bypass it.
    if (strpos($fileName, "php") !== false) {
        echo "Error: PHP files are not allowed.";
        exit;
    }

    // 3. Move the uploaded file to the uploads directory
    if (move_uploaded_file($_FILES["fileInput"]["tmp_name"], $targetFile)) {
        echo "File uploaded successfully!  stored inside uploads folder inside file_upload.";
    } else {
        echo "Error uploading file.";
    }
} else {
    echo "No file uploaded.";
}
?>
