<?php
// Database Connection File
// define("BASE_URL", "/digital-agency");

require_once "config.php";

// Create connection
$conn = mysqli_connect(
    DB_HOST,
    DB_USER,
    DB_PASS,
    DB_NAME
);

// Check connection
if (!$conn) {
    die("Database Connection Failed: " . mysqli_connect_error());
}

// Set charset (important for security & Unicode)
mysqli_set_charset($conn, "utf8mb4");
