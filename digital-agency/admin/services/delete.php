<?php
require_once "../../includes/db.php";
require_once "../../includes/auth.php";

adminAuth();

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: list.php");
    exit();
}

// Fetch service to get image
$stmt = mysqli_prepare($conn, "SELECT image FROM services WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$service = mysqli_fetch_assoc($result);

if ($service) {

    // Delete image if exists
    if (!empty($service['image'])) {
        $imagePath = "../../uploads/services/" . $service['image'];
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }

    // Delete service record
    $delete = mysqli_prepare($conn, "DELETE FROM services WHERE id = ?");
    mysqli_stmt_bind_param($delete, "i", $id);
    mysqli_stmt_execute($delete);
}

// Redirect back to list
header("Location: list.php");
exit();
