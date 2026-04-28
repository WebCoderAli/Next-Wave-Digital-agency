<?php
require_once "../includes/db.php";
require_once "../includes/auth.php";

redirectIfUser();

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {

        $stmt = mysqli_prepare(
            $conn,
            "SELECT id, name, password, status FROM users WHERE email = ? LIMIT 1"
        );
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($user = mysqli_fetch_assoc($result)) {

            if ($user['status'] === 'blocked') {
                $error = "Your account is blocked by admin.";
            } elseif (password_verify($password, $user['password'])) {

                $_SESSION[USER_SESSION] = $user['id'];
                $_SESSION['user_name'] = $user['name'];

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

require_once "../includes/header.php";
?>

<style>
/* Global Body Fix for Premium Dark Mode */
body {
    background-color: #050505 !important;
}

/* ================= PREMIUM PAGE STYLES (AUTH) ================= */
.premium-wrapper {
    background-color: transparent;
    color: #e2e8f0;
    font-family: 'Inter', sans-serif;
    overflow-x: hidden;
    min-height: calc(100vh - 80px); /* Fill remaining space below navbar */
}

.premium-wrapper h1, 
.premium-wrapper h2, 
.premium-wrapper h3, 
.premium-wrapper h4 {
    font-family: 'Outfit', sans-serif;
}

.auth-section {
    padding: 60px 0; /* Reduced padding to fix alignment */
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: calc(100vh - 80px); /* Align perfectly in viewport */
}

.hero-glow-bg {
    position: absolute;
    width: 600px;
    height: 600px;
    background: radial-gradient(circle, rgba(124, 58, 237, 0.2) 0%, rgba(0,0,0,0) 70%);
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    border-radius: 50%;
    filter: blur(80px);
    z-index: 0;
    pointer-events: none;
}

.glass-card {
    background: rgba(255, 255, 255, 0.03);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    border-radius: 24px;
    position: relative;
    z-index: 1;
}

.text-gradient {
    background: linear-gradient(135deg, #60a5fa, #c084fc, #f472b6);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.form-control-dark {
    display: block;
    width: 100%;
    background-color: rgba(255,255,255,0.03);
    border: 1px solid rgba(255,255,255,0.08);
    color: #f8fafc;
    border-radius: 12px;
    padding: 14px 20px;
    transition: all 0.3s ease;
}
.form-control-dark::placeholder {
    color: #64748b;
}
.form-control-dark:focus {
    background-color: rgba(255,255,255,0.06);
    border-color: rgba(124, 58, 237, 0.5);
    box-shadow: 0 0 15px rgba(124, 58, 237, 0.2);
    color: #fff;
    outline: none;
}
label {
    font-weight: 500;
    margin-bottom: 8px;
    color: #cbd5e1;
    display: block;
    text-align: left;
}

.btn-premium {
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    color: #fff !important;
    border: none;
    border-radius: 12px;
    padding: 14px 32px;
    font-weight: 600;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    box-shadow: 0 10px 20px -5px rgba(124, 58, 237, 0.5);
    width: 100%;
}
.btn-premium:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 25px -5px rgba(124, 58, 237, 0.6);
}

.auth-link {
    color: #a5b4fc;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.3s;
}
.auth-link:hover {
    color: #c084fc;
}
</style>

<div class="premium-wrapper">
    <section class="auth-section">
        <div class="hero-glow-bg"></div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-7 border-0">

                    <?php if ($error): ?>
                        <div class="alert alert-danger text-center glass-card py-2 mb-4" style="background: rgba(248, 113, 113, 0.1); border-color: rgba(248, 113, 113, 0.3); color: #f87171;">
                            <?= $error ?>
                        </div>
                    <?php endif; ?>

                    <div class="glass-card p-5 shadow-lg border-0">
                        <div class="text-center mb-4">
                            <h3 class="fw-bold mb-2">Welcome <span class="text-gradient">Back</span></h3>
                            <p class="text-secondary small">Securely login to your administrative console.</p>
                        </div>

                        <form method="POST">

                            <div class="mb-4">
                                <label class="form-label">Email Address</label>
                                <input type="email"
                                       name="email"
                                       class="form-control form-control-dark"
                                       placeholder="Enter your email"
                                       required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Password</label>
                                <input type="password"
                                       name="password"
                                       class="form-control form-control-dark"
                                       placeholder="Enter your password"
                                       required>
                            </div>

                            <button type="submit" class="btn-premium mt-2">
                                Authenticate
                            </button>

                        </form>

                        <div class="text-center mt-4 border-top pt-4" style="border-color: rgba(255,255,255,0.1) !important;">
                            <p class="text-secondary mb-1 small">
                                Don’t have an authorized account?
                            </p>
                            <a href="register.php" class="auth-link small">
                                Request new account access
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
</div>

<?php
// We do not require footer here explicitly to keep the auth pages clean, 
// or if we must, it can be styled properly. The original didn't include footer.php.
?>

