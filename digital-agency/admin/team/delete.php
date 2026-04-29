<?php
require_once '../../includes/auth.php';
require_once '../../includes/db.php';
adminAuth();

$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    header("Location: list.php");
    exit;
}

// Fetch member first (to delete image file)
$stmt = $conn->prepare("SELECT image FROM team_members WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$member = $result->fetch_assoc();

if (!$member) {
    header("Location: list.php");
    exit;
}

// Delete image if exists
$uploadDir = "../../uploads/profiles/";

if (!empty($member['image']) && file_exists($uploadDir . $member['image'])) {
    unlink($uploadDir . $member['image']);
}

// Delete record
$stmt = $conn->prepare("DELETE FROM team_members WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: list.php");
exit;