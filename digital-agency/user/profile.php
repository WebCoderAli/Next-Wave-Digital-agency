<?php
require_once "../includes/db.php";
require_once "../includes/auth.php";

userAuth();

$user_id = $_SESSION[USER_SESSION];
$error = "";
$success = "";

// Fetch user data
$stmt = mysqli_prepare($conn, "SELECT name, email, created_at FROM users WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name  = trim($_POST['name']);
    $email = trim($_POST['email']);

    if (empty($name) || empty($email)) {
        $error = "All fields are required.";
    } else {

        // Check email uniqueness
        $check = mysqli_prepare(
            $conn,
            "SELECT id FROM users WHERE email = ? AND id != ?"
        );
        mysqli_stmt_bind_param($check, "si", $email, $user_id);
        mysqli_stmt_execute($check);
        mysqli_stmt_store_result($check);

        if (mysqli_stmt_num_rows($check) > 0) {
            $error = "Email already in use.";
        } else {

            $update = mysqli_prepare(
                $conn,
                "UPDATE users SET name = ?, email = ? WHERE id = ?"
            );
            mysqli_stmt_bind_param($update, "ssi", $name, $email, $user_id);
            mysqli_stmt_execute($update);

            $_SESSION['user_name'] = $name;
            $success = "Profile updated successfully.";
            
            // Re-fetch to reflect instant changes
            $user['name'] = $name;
            $user['email'] = $email;
        }
    }
}
?>

<?php require_once "../includes/header.php"; ?>

<div class="premium-wrapper">
    <div class="container">
        
        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-9">
                
                <div class="d-flex align-items-end justify-content-between mb-4">
                    <div>
                        <h2 class="fw-bold mb-1 text-white" style="font-family: 'Outfit', sans-serif; margin-top: 20px;">Profile</h2>
                        <p class="text-secondary mb-0">Update your name and email.</p>
                    </div>
                    <a href="change-password.php" class="btn btn-premium-outline d-none d-md-inline-flex align-items-center gap-2">
                        <i class="bi bi-shield-lock"></i>
                        <span>Change password</span>
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

                <div class="glass-card p-4 p-md-5 mt-2 mb-4">
                    
                    <!-- Avatar Preview -->
                    <div class="text-center mb-4 pb-3 border-bottom border-secondary border-opacity-25 mt-2">
                        <div class="profile-avatar mx-auto mb-3" aria-hidden="true">
                            <?= strtoupper(substr(htmlspecialchars($user['name']), 0, 1)); ?>
                        </div>
                        <h4 class="text-white fw-bold mb-1"><?= htmlspecialchars($user['name']); ?></h4>
                        <span class="badge profile-badge">User</span>
                    </div>

                    <form method="POST">
                        <div class="mb-4 ms-2">
                            <label class="form-label text-secondary fw-semibold small text-uppercase" style="letter-spacing: 0.12em;">Name</label>
                            <input type="text" name="name"
                                   value="<?php echo htmlspecialchars($user['name']); ?>"
                                   class="form-control" placeholder="Enter your full name" required>
                        </div>

                        <div class="mb-4 ms-2">
                            <label class="form-label text-secondary fw-semibold small text-uppercase" style="letter-spacing: 0.12em;">Email</label>
                            <input type="email" name="email"
                                   value="<?php echo htmlspecialchars($user['email']); ?>"
                                   class="form-control" placeholder="name@domain.com" required>
                        </div>

                        <div class="d-flex flex-column flex-md-row gap-2 mt-4 mb-4 ms-2">
                            <button type="submit" class="btn btn-primary d-inline-flex align-items-center justify-content-center gap-2">
                                <i class="bi bi-save"></i>
                                <span>Save changes</span>
                            </button>

                            <a href="dashboard.php" class="btn btn-premium-outline d-inline-flex align-items-center justify-content-center gap-2">
                                <i class="bi bi-arrow-left"></i>
                                <span>Back</span>
                            </a>

                            <a href="change-password.php" class="btn btn-outline-light d-inline-flex d-md-none align-items-center justify-content-center gap-2">
                                <i class="bi bi-shield-lock"></i>
                                <span>Change password</span>
                            </a>
                        </div>
                    </form>

                </div>

            </div>
        </div>

    </div>
</div>

<?php require_once "../includes/footer.php"; ?>
