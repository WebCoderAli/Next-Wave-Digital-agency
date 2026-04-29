<?php
require_once "../../includes/db.php";
require_once "../../includes/auth.php";

adminAuth();

$payment_id = $_GET['id'] ?? null;
$status = $_GET['status'] ?? null;
$order_id = $_GET['order_id'] ?? null;

if (!$payment_id || !$status || !$order_id) {
    header("Location: list.php");
    exit();
}

// Map status labels to database values
$db_status = ($status === 'paid') ? 'yes' : (($status === 'not_received') ? 'no' : 'pending');

// Update payment status
$stmt = mysqli_prepare(
    $conn,
    "UPDATE payments SET verified = ? WHERE id = ?"
);
mysqli_stmt_bind_param($stmt, "si", $db_status, $payment_id);
mysqli_stmt_execute($stmt);

// Redirect back to order view
header("Location: view.php?id=" . $order_id);
exit();
