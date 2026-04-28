<?php
// Authentication Guard File

require_once "config.php";

// Start session only if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*
|--------------------------------------------------------------------------
| ADMIN AUTH CHECK
|--------------------------------------------------------------------------
| Use this on admin pages
*/
function adminAuth()
{
    if (!isset($_SESSION[ADMIN_SESSION])) {
        header("Location: " . BASE_URL . "admin/login.php");
        exit();
    }
}

/*
|--------------------------------------------------------------------------
| USER AUTH CHECK
|--------------------------------------------------------------------------
| Use this on user pages
*/
function userAuth()
{
    if (!isset($_SESSION[USER_SESSION])) {
        header("Location: " . BASE_URL . "user/login.php");
        exit();
    }
}

/*
|--------------------------------------------------------------------------
| GUEST REDIRECT (if already logged in)
|--------------------------------------------------------------------------
*/
function redirectIfAdmin()
{
    if (isset($_SESSION[ADMIN_SESSION])) {
        header("Location: " . BASE_URL . "admin/dashboard.php");
        exit();
    }
}

function redirectIfUser()
{
    if (isset($_SESSION[USER_SESSION])) {
        header("Location: " . BASE_URL . "user/dashboard.php");
        exit();
    }
}
