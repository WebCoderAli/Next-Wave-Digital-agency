<?php
require_once "../../includes/db.php";
require_once "../../includes/auth.php";

adminAuth();

$id = $_GET['id'] ?? null;
$action = $_GET['action'] ?? null;

// Validate request
if (!$id || !in_array($action, ['block', 'unblock'])) {
    header("Location: list.php");
    exit();
}

// Decide status
$status = ($action === 'block') ? 'blocked' : 'active';

// Update user status
$stmt = mysqli_prepare(
    $conn,
    "UPDATE users SET status = ? WHERE id = ?"
);
mysqli_stmt_bind_param($stmt, "si", $status, $id);
mysqli_stmt_execute($stmt);

// Redirect back to users list
header("Location: list.php");
exit();
