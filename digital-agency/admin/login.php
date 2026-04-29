<?php
require_once "../includes/db.php";
require_once "../includes/auth.php";

// If admin already logged in, redirect
redirectIfAdmin();

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "All fields are required.";
    } else {

        $stmt = mysqli_prepare(
            $conn,
            "SELECT id, name, password, status FROM admins WHERE email = ? LIMIT 1"
        );
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($admin = mysqli_fetch_assoc($result)) {

            if ($admin['status'] === 'blocked') {
                $error = "Your account is blocked. Contact system administrator.";
            } elseif (password_verify($password, $admin['password'])) {

                $_SESSION[ADMIN_SESSION] = $admin['id'];
                $_SESSION['admin_name'] = $admin['name'];

                header("Location: dashboard.php");
                exit();

            } else {
                $error = "Invalid email or password.";
            }

        } else {
            $error = "Invalid email or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login | <?= SITE_NAME ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
    <link href="../assets/css/theme-toggle.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- IMPORTANT: theme.js applies .light-mode to body on load if preferred -->
    <script src="../assets/js/theme.js" defer></script>
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #0f172a; 
            color: #f8fafc;
            min-height: 100vh;
            display: flex;
            margin: 0;
            overflow-x: hidden;
        }

        /* The Left Side (Form) */
        .login-left {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 4rem;
            position: relative;
            z-index: 10;
        }

        /* The Right Side (Visual) */
        .login-right {
            flex: 1.2;
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
            position: relative;
            display: none;
            overflow: hidden;
        }

        @media (min-width: 992px) {
            .login-right {
                display: flex;
                align-items: center;
                justify-content: center;
            }
        }

        .auth-card {
            max-width: 420px;
            width: 100%;
            margin: 0 auto;
        }

        /* Animated background elements for the right side */
        .shape {
            position: absolute;
            filter: blur(80px);
            opacity: 0.6;
            animation: float 10s infinite alternate ease-in-out;
        }
        .shape-1 {
            width: 400px; height: 400px;
            background: #6366f1;
            top: 10%; left: 10%;
            border-radius: 40% 60% 70% 30% / 40% 50% 60% 50%;
        }
        .shape-2 {
            width: 500px; height: 500px;
            background: #8b5cf6;
            bottom: 10%; right: 10%;
            border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%;
            animation-delay: -5s;
        }

        @keyframes float {
            0% { transform: translateY(0) scale(1) rotate(0deg); }
            100% { transform: translateY(-50px) scale(1.1) rotate(10deg); }
        }

        .hero-text {
            position: relative;
            z-index: 2;
            text-align: center;
            padding: 3rem;
        }

        /* Light mode override for body inside theme-toggle.css works properly
           since we don't use !important for background here on body.
        */
        body.light-mode .login-left {
            background-color: #ffffff;
        }
        body.light-mode .login-right {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }
        body.light-mode .shape-1 { background: #818cf8; opacity: 0.4; }
        body.light-mode .shape-2 { background: #c084fc; opacity: 0.4; }
        
        /* Make sure label texts correctly respond to theme toggler */
        .form-label {
            font-size: 0.8rem;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
    </style>
</head>

<body>

    <!-- Left Side: Login Form -->
    <div class="login-left">
        <div class="auth-card">
            
            <div class="mb-5 text-center text-lg-start">
                <a href="../index.php" class="text-decoration-none d-inline-block mb-4">
                    <span class="fs-4 fw-bold" style="background: linear-gradient(135deg, #6366f1, #8b5cf6); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                        Agency.
                    </span>
                </a>
                <h2 class="fw-bold text-white mb-2" style="font-family: 'Outfit', sans-serif;">Welcome Back</h2>
                <p class="text-secondary">Please enter your credentials to access the admin portal.</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger text-center alert-premium mb-4" style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: #fca5a5; border-radius: 8px;">
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                
                <div class="mb-4">
                    <label class="form-label text-secondary fw-bold">Email Address</label>
                    <input type="email"
                           name="email"
                           class="form-control form-control-dark py-3"
                           style="border-radius: 10px;"
                           placeholder="admin@agency.com"
                           required>
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="form-label text-secondary fw-bold mb-0">Password</label>
                        <a href="forgot-password.php" class="text-decoration-none text-secondary" style="font-size: 0.8rem; font-weight: 500;">Forgot Password?</a>
                    </div>
                    <input type="password"
                           name="password"
                           class="form-control form-control-dark py-3"
                           style="border-radius: 10px;"
                           placeholder="••••••••"
                           required>
                </div>

                <button type="submit" class="btn w-100 py-3 fw-bold mt-2" style="background: linear-gradient(135deg, #6366f1, #8b5cf6); color: white; border-radius: 10px; border: none; box-shadow: 0 8px 25px rgba(99, 102, 241, 0.4); text-transform: uppercase; letter-spacing: 1px; font-size: 0.95rem; transition: transform 0.2s;">
                    Sign In
                </button>

            </form>

            <div class="mt-5 text-center text-lg-start">
                <p class="text-secondary mb-0" style="font-size: 0.85rem;">
                    © <?= date("Y") ?> <?= SITE_NAME ?>. All rights reserved.
                </p>
            </div>

        </div>
    </div>

    <!-- Right Side: Visual Graphic -->
    <div class="login-right">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        
        <div class="hero-text glass-card rounded" style="padding: 3rem; max-width: 450px; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05); text-align: left; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);">
            <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #6366f1, #8b5cf6); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: white; margin-bottom: 1.5rem;">
                ✦
            </div>
            <h3 class="text-white fw-bold mb-3" style="font-family: 'Outfit', sans-serif;">Command Your Digital Presence</h3>
            <p class="text-white mb-0" style="font-size: 1.05rem; line-height: 1.6;">
                The central nervous system for your agency. Manage orders, oversee services, and engage with your clients all in one seamless, high-performance environment.
            </p>
        </div>
    </div>

</body>
</html>
