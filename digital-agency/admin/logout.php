<?php
require_once "../includes/config.php";

// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Unset all session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Redirect to admin login
header("Location: " . BASE_URL . "/admin/login.php");
exit();
