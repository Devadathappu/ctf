<?php
session_start();
session_destroy();
header('Location: /ctf/index.html'); // Redirect to login page
exit;
?>