<?php
require_once "../includes/db.php";
require_once "../includes/auth.php";

userAuth();

$user_id = $_SESSION[USER_SESSION];
$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $current = $_POST['current_password'];
    $new     = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    if (empty($current) || empty($new) || empty($confirm)) {
        $error = "All fields are required.";
    } elseif ($new !== $confirm) {
        $error = "New passwords do not match.";
    } elseif (strlen($new) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {

        // Fetch current password
        $stmt = mysqli_prepare($conn, "SELECT password FROM users WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        $user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

        if (!password_verify($current, $user['password'])) {
            $error = "Current password is incorrect.";
        } else {

            $hashed = password_hash($new, PASSWORD_DEFAULT);

            $update = mysqli_prepare(
                $conn,
                "UPDATE users SET password = ? WHERE id = ?"
            );
            mysqli_stmt_bind_param($update, "si", $hashed, $user_id);
            mysqli_stmt_execute($update);

            $success = "Password updated successfully.";
        }
    }
}
?>

<?php require_once "../includes/header.php"; ?>

<h3 class="fw-bold mb-4">Change Password</h3>

<?php if ($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-body">

        <form method="POST">
            <div class="mb-3">
                <label>Current Password</label>
                <input type="password" name="current_password"
                       class="form-control" required>
            </div>

            <div class="mb-3">
                <label>New Password</label>
                <input type="password" name="new_password"
                       class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Confirm New Password</label>
                <input type="password" name="confirm_password"
                       class="form-control" required>
            </div>

            <button class="btn btn-dark">Update Password</button>
            <a href="dashboard.php" class="btn btn-secondary">Back</a>
        </form>

    </div>
</div>

<?php require_once "../includes/footer.php"; ?>
