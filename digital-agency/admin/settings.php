<?php
require_once "../includes/db.php";
require_once "../includes/auth.php";

adminAuth();

$error = "";
$success = "";

/* =========================================================
   FETCH SETTINGS
========================================================= */
$settings = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT * FROM settings LIMIT 1")
);

/* =========================================================
   UPDATE SITE SETTINGS
========================================================= */
if (isset($_POST['update_settings'])) {

    $site_name  = trim($_POST['site_name']);
    $site_email = trim($_POST['site_email']);

    if (empty($site_name) || empty($site_email)) {
        $error = "All fields are required.";
    } else {
        $stmt = mysqli_prepare(
            $conn,
            "UPDATE settings SET site_name = ?, site_email = ? WHERE id = ?"
        );
        mysqli_stmt_bind_param($stmt, "ssi", $site_name, $site_email, $settings['id']);
        mysqli_stmt_execute($stmt);

        $success = "Site settings updated successfully.";
    }
}

/* =========================================================
   CHANGE ADMIN PASSWORD
========================================================= */
if (isset($_POST['change_password'])) {

    $current = $_POST['current_password'];
    $new     = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    if (empty($current) || empty($new) || empty($confirm)) {
        $error = "All password fields are required.";
    } elseif ($new !== $confirm) {
        $error = "New passwords do not match.";
    } elseif (strlen($new) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {

        // Fetch admin password
        $stmt = mysqli_prepare(
            $conn,
            "SELECT password FROM admins WHERE id = ?"
        );
        mysqli_stmt_bind_param($stmt, "i", $_SESSION[ADMIN_SESSION]);
        mysqli_stmt_execute($stmt);
        $admin = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

        if (!password_verify($current, $admin['password'])) {
            $error = "Current password is incorrect.";
        } else {

            $hashed = password_hash($new, PASSWORD_DEFAULT);

            $update = mysqli_prepare(
                $conn,
                "UPDATE admins SET password = ? WHERE id = ?"
            );
            mysqli_stmt_bind_param($update, "si", $hashed, $_SESSION[ADMIN_SESSION]);
            mysqli_stmt_execute($update);

            $success = "Admin password updated successfully.";
        }
    }
}
?>

<?php require_once "../includes/admin_sidebar.php"; ?>

<div class="d-flex justify-content-between align-items-center mb-5">
    <h3 class="fw-bold m-0" style="font-family: 'Outfit', sans-serif;">System <span style="background: linear-gradient(135deg, #60a5fa, #c084fc); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Settings</span></h3>
</div>

<?php if ($error): ?>
    <div class="alert alert-danger alert-premium text-center" style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: #fca5a5;"><?php echo $error; ?></div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="alert alert-success alert-premium text-center" style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); color: #6ee7b7;"><?php echo $success; ?></div>
<?php endif; ?>

<div class="row g-4">

    <!-- SITE SETTINGS -->
    <div class="col-lg-6">
        <div class="glass-card mb-4 p-4" style="border-top: 4px solid #6366f1;">
            <div class="card-body">
                <h5 class="fw-bold mb-4 text-white" style="font-family: 'Outfit', sans-serif;">Site Settings</h5>

                <form method="POST">
                    <div class="mb-4">
                        <label class="form-label text-secondary fw-medium small text-uppercase" style="letter-spacing: 1px;">Site Name</label>
                        <input type="text" name="site_name"
                               value="<?php echo htmlspecialchars($settings['site_name']); ?>"
                               class="form-control form-control-dark py-2" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-secondary fw-medium small text-uppercase" style="letter-spacing: 1px;">Support Email</label>
                        <input type="email" name="site_email"
                               value="<?php echo htmlspecialchars($settings['site_email']); ?>"
                               class="form-control form-control-dark py-2" required>
                    </div>

                    <button name="update_settings" class="btn w-100 py-3 fw-bold mt-2" style="background: linear-gradient(135deg, #6366f1, #8b5cf6); color: white; border-radius: 8px; border: none; box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4); text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem;">
                        Update Settings
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- CHANGE PASSWORD -->
    <div class="col-lg-6">
        <div class="glass-card mb-4" style="border-top: 4px solid #ef4444;">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-4 text-white" style="font-family: 'Outfit', sans-serif;">Change Admin Password</h5>

                <form method="POST">
                    <div class="mb-4">
                        <label class="form-label text-secondary fw-medium small text-uppercase" style="letter-spacing: 1px;">Current Password</label>
                        <input type="password" name="current_password"
                               class="form-control form-control-dark py-2" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-secondary fw-medium small text-uppercase" style="letter-spacing: 1px;">New Password</label>
                        <input type="password" name="new_password"
                               class="form-control form-control-dark py-2" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-secondary fw-medium small text-uppercase" style="letter-spacing: 1px;">Confirm New Password</label>
                        <input type="password" name="confirm_password"
                               class="form-control form-control-dark py-2" required>
                    </div>

                    <button name="change_password" class="btn w-100 py-3 fw-bold mt-2" style="background: linear-gradient(135deg, #ef4444, #b91c1c); color: white; border-radius: 8px; border: none; box-shadow: 0 4px 15px rgba(239, 68, 68, 0.4); text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem;">
                        Change Password
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>

        </div> <!-- End p-4 wrapper from sidebar -->
    </main> <!-- End admin-content -->
</div> <!-- End admin-wrapper -->
</body>
</html>

