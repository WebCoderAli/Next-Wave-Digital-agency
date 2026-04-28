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
    } elseif (strlen($new) < 8) {
        $error = "Password must be at least 8 characters.";
    } elseif (!preg_match("/[0-9]/", $new)) {
        $error = "Password must include at least one number.";
    } else {

        // Fetch current password
        $stmt = mysqli_prepare($conn, "SELECT password FROM users WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        $user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

        if (!$user) {
            $error = "Account not found.";
        } elseif (!password_verify($current, $user['password'])) {
            $error = "Current password is incorrect.";
        } else {

            $hashed = password_hash($new, PASSWORD_DEFAULT);

            $update = mysqli_prepare(
                $conn,
                "UPDATE users SET password = ? WHERE id = ?"
            );
            mysqli_stmt_bind_param($update, "si", $hashed, $user_id);
            mysqli_stmt_execute($update);

            if (mysqli_stmt_affected_rows($update) >= 0) {
                $success = "Password updated successfully.";
            } else {
                $error = "Failed to update password. Please try again.";
            }
        }
    }
}
?>

<?php require_once "../includes/header.php"; ?>

<div class="premium-wrapper mb-4">
    <div class="container">

        <div class="row justify-content-center mt-4">
            <div class="col-lg-7 col-md-9">

                <div class="d-flex align-items-end justify-content-between mb-4">
                    <div>
                        <h2 class="fw-bold mb-1 text-white" style="font-family: 'Outfit', sans-serif;">Change password</h2>
                        <p class="text-secondary mb-0">Use a strong password you don’t use elsewhere.</p>
                    </div>
                    <a href="profile.php" class="btn btn-premium-outline d-none d-md-inline-flex align-items-center gap-2">
                        <i class="bi bi-person"></i>
                        <span>Profile</span>
                    </a>
                </div>

                <?php if ($error): ?>
                    <div class="alert alert-danger alert-premium d-flex align-items-center gap-2 mb-4" style="border-radius: 12px;">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <span><?php echo $error; ?></span>
                    </div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="alert alert-success alert-premium d-flex align-items-center gap-2 mb-4" style="border-radius: 12px;">
                        <i class="bi bi-check-circle-fill"></i>
                        <span><?php echo $success; ?></span>
                    </div>
                <?php endif; ?>

                <div class="glass-card p-4 p-md-5 mb-4">

                    <form method="POST" autocomplete="off">
                        <div class="mb-4">
                            <label class="form-label text-secondary fw-semibold small text-uppercase" style="letter-spacing: 0.12em;">Current password</label>
                            <input type="password"
                                   name="current_password"
                                   class="form-control"
                                   required
                                   autocomplete="current-password"
                                   placeholder="Enter your current password">
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-secondary fw-semibold small text-uppercase" style="letter-spacing: 0.12em;">New password</label>
                            <input type="password"
                                   name="new_password"
                                   class="form-control"
                                   required
                                   autocomplete="new-password"
                                   placeholder="At least 8 characters (include a number)">
                            <div class="form-text text-secondary mt-2">
                                Minimum 8 characters and at least 1 number.
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-secondary fw-semibold small text-uppercase" style="letter-spacing: 0.12em;">Confirm new password</label>
                            <input type="password"
                                   name="confirm_password"
                                   class="form-control"
                                   required
                                   autocomplete="new-password"
                                   placeholder="Re-enter the new password">
                        </div>

                        <div class="d-flex flex-column flex-md-row gap-2 mt-4">
                            <button type="submit" class="btn btn-primary d-inline-flex align-items-center justify-content-center gap-2">
                                <i class="bi bi-shield-check"></i>
                                <span>Update password</span>
                            </button>

                            <a href="dashboard.php" class="btn btn-premium-outline d-inline-flex align-items-center justify-content-center gap-2">
                                <i class="bi bi-arrow-left"></i>
                                <span>Back</span>
                            </a>

                            <a href="profile.php" class="btn btn-outline-light d-inline-flex d-md-none align-items-center justify-content-center gap-2">
                                <i class="bi bi-person"></i>
                                <span>Profile</span>
                            </a>
                        </div>
                    </form>

                </div>

            </div>
        </div>

    </div>
</div>

<?php require_once "../includes/footer.php"; ?>
