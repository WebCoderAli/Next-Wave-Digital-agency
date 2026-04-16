<?php
require_once "../../includes/db.php";
require_once "../../includes/auth.php";

adminAuth();

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: list.php");
    exit();
}

// Update order status to rejected
$stmt = mysqli_prepare(
    $conn,
    "UPDATE orders SET status = 'rejected' WHERE id = ?"
);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

// Optional: mark payment as not verified
$payStmt = mysqli_prepare(
    $conn,
    "UPDATE payments SET verified = 'no' WHERE order_id = ?"
);
mysqli_stmt_bind_param($payStmt, "i", $id);
mysqli_stmt_execute($payStmt);

// Redirect back to order view
header("Location: view.php?id=" . $id);
exit();
