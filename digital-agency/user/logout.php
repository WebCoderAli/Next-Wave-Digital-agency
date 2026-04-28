<?php
require_once "../includes/config.php";

// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Remove all session variables
$_SESSION = [];

// Destroy session
session_destroy();

// Redirect to user login
header("Location: " . BASE_URL . "/user/login.php");
exit();
