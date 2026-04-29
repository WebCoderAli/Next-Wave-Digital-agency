<?php
require_once "../includes/db.php";
require_once "../includes/auth.php";

/*
|--------------------------------------------------------------------------
| Redirect if already logged in
|--------------------------------------------------------------------------
*/
redirectIfUser();

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Clean input
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if ($name === '' || $email === '' || $password === '' || $confirmPassword === '') {
        $error = "All fields are required.";
    } elseif (strlen($name) < 3) {
        $error = "Name must be at least 3 characters.";
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", $name)) {
        $error = "Name can only contain letters and spaces.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters.";
    } elseif (!preg_match("/[0-9]/", $password)) {
        $error = "Password must contain at least one number.";
    } elseif ($password !== $confirmPassword) {
        $error = "Passwords do not match.";
    } else {

        /*
        |--------------------------------------------------------------------------
        | Check duplicate email
        |--------------------------------------------------------------------------
        */
        $check = mysqli_prepare(
            $conn,
            "SELECT id FROM users WHERE email = ? LIMIT 1"
        );
        mysqli_stmt_bind_param($check, "s", $email);
        mysqli_stmt_execute($check);
        mysqli_stmt_store_result($check);

        if (mysqli_stmt_num_rows($check) > 0) {
            $error = "Email already registered.";
        } else {

            /*
            |--------------------------------------------------------------------------
            | Hash password
            |--------------------------------------------------------------------------
            */
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            /*
            |--------------------------------------------------------------------------
            | Insert user
            |--------------------------------------------------------------------------
            */
            $stmt = mysqli_prepare(
                $conn,
                "INSERT INTO users (name, email, password, status)
                 VALUES (?, ?, ?, 'active')"
            );
            mysqli_stmt_bind_param(
                $stmt,
                "sss",
                $name,
                $email,
                $hashedPassword
            );

            if (mysqli_stmt_execute($stmt)) {
                $success = "Registration successful. You can now login.";
            } else {
                $error = "Something went wrong. Please try again.";
            }
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
    min-height: calc(100vh - 80px);
}

.premium-wrapper h1, 
.premium-wrapper h2, 
.premium-wrapper h3, 
.premium-wrapper h4 {
    font-family: 'Outfit', sans-serif;
}

.auth-section {
    padding: 60px 0;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: calc(100vh - 80px);
}

.hero-glow-bg {
    position: absolute;
    width: 600px;
    height: 600px;
    background: radial-gradient(circle, rgba(56, 189, 248, 0.15) 0%, rgba(0,0,0,0) 70%);
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
.text-gradient-alt {
    background: linear-gradient(135deg, #38bdf8, #818cf8);
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
    border-color: rgba(56, 189, 248, 0.5);
    box-shadow: 0 0 15px rgba(56, 189, 248, 0.2);
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
    background: linear-gradient(135deg, #0284c7 0%, #4f46e5 100%);
    color: #fff !important;
    border: none;
    border-radius: 12px;
    padding: 14px 32px;
    font-weight: 600;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    box-shadow: 0 10px 20px -5px rgba(79, 70, 229, 0.5);
    width: 100%;
}
.btn-premium:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 25px -5px rgba(79, 70, 229, 0.6);
}

.auth-link {
    color: #7dd3fc;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.3s;
}
.auth-link:hover {
    color: #e0f2fe;
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

                    <?php if ($success): ?>
                        <div class="alert alert-success text-center glass-card py-2 mb-4" style="background: rgba(74, 222, 128, 0.1); border-color: rgba(74, 222, 128, 0.3); color: #4ade80;">
                            <?= $success ?>
                        </div>
                    <?php endif; ?>

                    <div class="glass-card p-5 shadow-lg border-0">
                        <div class="text-center mb-4">
                            <h3 class="fw-bold mb-2">Create <span class="text-gradient-alt">Account</span></h3>
                            <p class="text-secondary small">Join our network and begin deploying your solutions.</p>
                        </div>

                        <form method="POST" autocomplete="off">

                            <div class="mb-4">
                                <label class="form-label">Full Name</label>
                                <input type="text"
                                       name="name"
                                       class="form-control form-control-dark"
                                       placeholder="Enter your full name"
                                       required
                                       minlength="2"
                                       pattern="^[a-zA-Z\s]+$"
                                       value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Email Address</label>
                                <input type="email"
                                       name="email"
                                       class="form-control form-control-dark"
                                       placeholder="Enter your email"
                                       required
                                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Secure Password</label>
                                <input type="password"
                                       name="password"
                                       id="password"
                                       class="form-control form-control-dark"
                                       placeholder="Create a strong password"
                                       required
                                       minlength="8">
                                <small class="text-muted mt-2 d-block">
                                    * Min 8 characters with at least one number.
                                </small>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Confirm Password</label>
                                <input type="password"
                                       name="confirm_password"
                                       id="confirm_password"
                                       class="form-control form-control-dark"
                                       placeholder="Repeat your password"
                                       required>
                            </div>

                            <button type="submit" class="btn-premium mt-2">
                                Initialize Account
                            </button>

                        </form>

                        <div class="text-center mt-4 border-top pt-4" style="border-color: rgba(255,255,255,0.1) !important;">
                            <p class="text-secondary mb-1 small">
                                Already possess secure access?
                            </p>
                            <a href="login.php" class="auth-link small">
                                Authenticate here
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirm_password');

    form.addEventListener('submit', function(e) {
        if (password.value !== confirmPassword.value) {
            e.preventDefault();
            alert('Passwords do not match!');
            confirmPassword.focus();
        }
    });

    // Simple real-time feedback for matching
    confirmPassword.addEventListener('input', function() {
        if (confirmPassword.value && password.value !== confirmPassword.value) {
            confirmPassword.style.borderColor = '#f87171';
        } else {
            confirmPassword.style.borderColor = 'rgba(255,255,255,0.08)';
        }
    });
});
</script>

<?php
// Authentication pages typically don't pull large footers...
?>
