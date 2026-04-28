<?php
require_once "../includes/db.php";
require_once "../includes/auth.php";

redirectIfAdmin();

$error = "";
$message = "";

$token = $_GET['token'] ?? '';

if (empty($token)) {
    die("Invalid request.");
}

// Verify token
$stmt = mysqli_prepare(
    $conn,
    "SELECT id, token_expiry FROM admins WHERE reset_token = ? LIMIT 1"
);
mysqli_stmt_bind_param($stmt, "s", $token);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$admin = mysqli_fetch_assoc($result)) {
    die("Invalid or expired token.");
}

if (strtotime($admin['token_expiry']) < time()) {
    die("Reset link has expired.");
}

// Handle password update
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $password = $_POST['password'];
    $confirm  = $_POST['confirm'];

    if (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {

        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $update = mysqli_prepare(
            $conn,
            "UPDATE admins 
             SET password = ?, reset_token = NULL, token_expiry = NULL 
             WHERE id = ?"
        );
        mysqli_stmt_bind_param($update, "si", $hashed, $admin['id']);
        mysqli_stmt_execute($update);

        $message = "Password updated successfully. <a href='login.php'>Login now</a>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password | <?php echo SITE_NAME; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
    <link href="../assets/css/theme-toggle.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="../assets/js/theme.js" defer></script>
    <style>
        body { font-family: 'Inter', sans-serif; background: #0f172a; color: #f8fafc; }
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

            <div class="glass-card p-5" style="border-top: 4px solid #10b981;">
                <h4 class="text-center mb-4 fw-bold" style="font-family: 'Outfit', sans-serif;">
                    Reset <span style="background: linear-gradient(135deg, #34d399, #10b981); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Password</span>
                </h4>

                <?php if ($message): ?>
                    <div class="alert alert-success alert-premium text-center" style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); color: #6ee7b7;"><?php echo $message; ?></div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div class="alert alert-danger alert-premium text-center" style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: #fca5a5;"><?php echo $error; ?></div>
                <?php endif; ?>

                <?php if (!$message): ?>
                <form method="POST">
                    
                    <div class="mb-4">
                        <label class="form-label text-light fw-medium small text-uppercase" style="letter-spacing: 1px;">New Password</label>
                        <input type="password" name="password"
                               class="form-control form-control-dark py-2"
                               placeholder="••••••••" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-light fw-medium small text-uppercase" style="letter-spacing: 1px;">Confirm Password</label>
                        <input type="password" name="confirm"
                               class="form-control form-control-dark py-2"
                               placeholder="••••••••" required>
                    </div>

                    <button class="btn w-100 py-3 fw-bold mt-2" style="background: linear-gradient(135deg, #10b981, #059669); color: white; border-radius: 8px; border: none; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4); text-transform: uppercase; letter-spacing: 1px; font-size: 0.9rem;">
                        Reset Password
                    </button>
                    
                </form>
                <?php endif; ?>
                
            </div>

        </div>
    </div>
</div>

</body>
</html>
