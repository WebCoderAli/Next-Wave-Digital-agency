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

<style>
/* Base Fix for internal page background */
body {
    background-color: #050505 !important;
}

.premium-wrapper {
    background-color: transparent;
    color: #e2e8f0;
    font-family: 'Inter', sans-serif;
    min-height: calc(100vh - 80px);
    padding: 60px 0;
}
.glass-card {
    background: rgba(255, 255, 255, 0.03);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid rgba(255, 255, 255, 0.05);
    border-radius: 24px;
    padding: 30px;
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
    transition: transform 0.4s ease, border-color 0.4s ease;
}
.glass-card-hover:hover {
    transform: translateY(-5px);
    border-color: rgba(99, 102, 241, 0.5);
    box-shadow: 0 10px 40px rgba(99, 102, 241, 0.15);
}
.avatar-lg {
    width: 100px;
    height: 100px;
    font-size: 2.5rem;
    font-weight: 800;
    font-family: 'Outfit', sans-serif;
    border: 3px solid rgba(255,255,255,0.1);
    box-shadow: 0 0 20px rgba(99, 102, 241, 0.3);
}

/* Form Styles */
.form-label {
    font-weight: 600;
    color: #94a3b8;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}
.form-control {
    background-color: rgba(15, 23, 42, 0.6) !important;
    border: 1px solid rgba(255,255,255,0.1) !important;
    color: #f8fafc !important;
    padding: 12px 16px;
    border-radius: 12px;
    transition: all 0.3s ease;
}
.form-control:focus {
    background-color: rgba(15, 23, 42, 0.9) !important;
    border-color: #6366f1 !important;
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.2) !important;
}

body.light-mode .form-control {
    background-color: #ffffff !important;
    border-color: rgba(0,0,0,0.1) !important;
    color: #000000 !important;
}
body.light-mode .form-control:focus {
    border-color: #6366f1 !important;
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.2) !important;
}

/* Premium Button */
.btn-premium {
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    color: #fff !important;
    border: none;
    border-radius: 50px;
    padding: 12px 28px;
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
.btn-premium-outline {
    background: transparent;
    color: #94a3b8 !important;
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 50px;
    padding: 12px 28px;
    font-weight: 600;
    transition: all 0.3s ease;
    width: 100%;
    margin-top: 15px;
}
.btn-premium-outline:hover {
    color: #fff !important;
    border-color: rgba(255,255,255,0.5);
    background: rgba(255,255,255,0.05);
}

body.light-mode .btn-premium-outline {
    border-color: rgba(0,0,0,0.1);
    color: #333 !important;
}
body.light-mode .btn-premium-outline:hover {
    background: rgba(0,0,0,0.05);
    color: #000 !important;
}
</style>

<div class="premium-wrapper">
    <div class="container">
        
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                
                <h2 class="fw-bold mb-4 text-white text-center" style="font-family: 'Outfit', sans-serif;">Command <span style="background: linear-gradient(90deg, #60a5fa, #c084fc); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Center</span></h2>
                <p class="text-secondary text-center mb-5">Update your clearance parameters and core operational ID.</p>

                <?php if ($error): ?>
                    <div class="alert alert-danger alert-premium d-flex align-items-center mb-4" style="border-radius: 12px; background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: #fca5a5;">
                        <span class="me-2">⚠️</span> <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="alert alert-success alert-premium d-flex align-items-center mb-4" style="border-radius: 12px; background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.3); color: #86efac;">
                        <span class="me-2">✔️</span> <?php echo $success; ?>
                    </div>
                <?php endif; ?>

                <div class="glass-card glass-card-hover">
                    
                    <!-- Avatar Preview -->
                    <div class="text-center mb-4 pb-3 border-bottom border-secondary border-opacity-25">
                        <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle avatar-lg mx-auto mb-3" style="background: linear-gradient(135deg, #4f46e5, #c084fc) !important;">
                            <?= strtoupper(substr(htmlspecialchars($user['name']), 0, 1)); ?>
                        </div>
                        <h4 class="text-white fw-bold mb-1"><?= htmlspecialchars($user['name']); ?></h4>
                        <span class="badge" style="background: rgba(99, 102, 241, 0.2); color: #a5b4fc; border: 1px solid rgba(99, 102, 241, 0.3);">Verified Client</span>
                    </div>

                    <form method="POST">
                        <div class="mb-4">
                            <label class="form-label">Operative Name</label>
                            <input type="text" name="name"
                                   value="<?php echo htmlspecialchars($user['name']); ?>"
                                   class="form-control" placeholder="Enter your full name" required>
                        </div>

                        <div class="mb-5">
                            <label class="form-label">Authentication Email</label>
                            <input type="email" name="email"
                                   value="<?php echo htmlspecialchars($user['email']); ?>"
                                   class="form-control" placeholder="name@domain.com" required>
                        </div>

                        <button type="submit" class="btn btn-premium d-flex justify-content-center align-items-center gap-2">
                            Initialize Update <span>→</span>
                        </button>
                        
                        <a href="dashboard.php" class="btn btn-premium-outline text-center d-block">
                            Cancel & Return
                        </a>
                    </form>

                </div>

            </div>
        </div>

    </div>
</div>

<?php require_once "../includes/footer.php"; ?>
