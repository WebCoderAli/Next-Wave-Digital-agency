<?php
require_once "../includes/db.php";
require_once "../includes/auth.php";

redirectIfAdmin();

$message = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST['email']);

    if (empty($email)) {
        $error = "Email is required.";
    } else {

        $stmt = mysqli_prepare(
            $conn,
            "SELECT id FROM admins WHERE email = ? LIMIT 1"
        );
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($admin = mysqli_fetch_assoc($result)) {

            $token = bin2hex(random_bytes(32));
            $expiry = date("Y-m-d H:i:s", strtotime("+15 minutes"));

            $update = mysqli_prepare(
                $conn,
                "UPDATE admins SET reset_token = ?, token_expiry = ? WHERE id = ?"
            );
            mysqli_stmt_bind_param($update, "ssi", $token, $expiry, $admin['id']);
            mysqli_stmt_execute($update);

            // For uni project: show link instead of email
            $resetLink = BASE_URL . "admin/reset-password.php?token=" . $token;
            $message = "Password reset link (demo): <br><a href='$resetLink'>$resetLink</a>";

        } else {
            $error = "Email not found.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password | <?php echo SITE_NAME; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
    <link href="../assets/css/theme-toggle.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="../assets/js/theme.js" defer></script>
    <style>
        body { text-family: 'Inter', sans-serif; background: #0f172a; color: #f8fafc; }
        .auth-wrapper { position: relative; z-index: 1; }
        .auth-bg-decor { position: absolute; top: 0; left: 0; right: 0; bottom: 0; overflow: hidden; z-index: 0; }
        .auth-bg-decor::before { content: ''; position: absolute; top: -10%; left: -10%; width: 50vw; height: 50vw; background: radial-gradient(circle, rgba(99,102,241,0.1) 0%, transparent 70%); border-radius: 50%; }
        .auth-bg-decor::after { content: ''; position: absolute; bottom: -10%; right: -10%; width: 50vw; height: 50vw; background: radial-gradient(circle, rgba(139,92,246,0.1) 0%, transparent 70%); border-radius: 50%; }
    </style>
</head>
<body class="d-flex align-items-center min-vh-100 position-relative">

<div class="auth-bg-decor"></div>

<div class="container auth-wrapper">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">

            <div class="glass-card p-5" style="border-top: 4px solid #f59e0b;">
                <h4 class="text-center mb-4 fw-bold" style="font-family: 'Outfit', sans-serif;">
                    Forgot <span style="background: linear-gradient(135deg, #fcd34d, #f59e0b); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Password</span>
                </h4>

                <?php if ($message): ?>
                    <div class="alert alert-success alert-premium text-center" style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); color: #6ee7b7;"><?php echo $message; ?></div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div class="alert alert-danger alert-premium text-center" style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: #fca5a5;"><?php echo $error; ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-4">
                        <label class="form-label text-light fw-medium small text-uppercase" style="letter-spacing: 1px;">Admin Email</label>
                        <input type="email" name="email" class="form-control form-control-dark py-2"
                               placeholder="admin@example.com" required>
                    </div>
                    <button class="btn w-100 py-3 fw-bold mt-2" style="background: linear-gradient(135deg, #fbbf24, #f59e0b); color: #000; border-radius: 8px; border: none; box-shadow: 0 4px 15px rgba( ২৪৫, ১৫৮, ১১, 0.4); text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem;">
                        Send Reset Link
                    </button>
                </form>

                <div class="text-center mt-5">
                    <a href="login.php" class="text-secondary mt-3" style="font-size: 0.9rem;">Back to Login</a>
                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>
