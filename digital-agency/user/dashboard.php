<?php
require_once "../includes/db.php";
require_once "../includes/auth.php";

userAuth();

$user_id = $_SESSION[USER_SESSION];

// Fetch user stats
$totalOrders = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders WHERE user_id = $user_id")
)['total'] ?? 0;

$pendingOrders = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders WHERE user_id = $user_id AND status = 'pending'")
)['total'] ?? 0;
?>

<?php require_once "../includes/header.php"; ?>

<style>
/* ================= PREMIUM DASHBOARD STYLES ================= */
.premium-wrapper {
    background-color: #050505;
    color: #e2e8f0;
    font-family: 'Inter', sans-serif;
    overflow-x: hidden;
    min-height: calc(100vh - 80px); /* Fill space below navbar */
}

.premium-wrapper h1, 
.premium-wrapper h2, 
.premium-wrapper h3, 
.premium-wrapper h4, 
.premium-wrapper h5, 
.premium-wrapper h6 {
    font-family: 'Outfit', sans-serif;
}

.premium-section {
    padding: 60px 0;
    position: relative;
    border-bottom: 1px solid rgba(255,255,255,0.05);
}

.glass-card {
    background: rgba(255, 255, 255, 0.03);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid rgba(255, 255, 255, 0.05);
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
    border-radius: 20px;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}
.glass-card-hover:hover {
    transform: translateY(-8px) scale(1.01);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3), 0 0 25px rgba(124, 58, 237, 0.3);
    border-color: rgba(192, 132, 252, 0.6); /* Vibrant purple border edge */
    background: rgba(255, 255, 255, 0.06);
    cursor: pointer;
}

.text-gradient {
    background: linear-gradient(135deg, #60a5fa, #c084fc, #f472b6);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
.text-gradient-alt {
    background: linear-gradient(135deg, #34d399, #3b82f6);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

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
    display: inline-block;
    text-decoration: none;
}
.btn-premium:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 25px -5px rgba(124, 58, 237, 0.6);
}

.icon-glow {
    width: 60px;
    height: 60px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    margin-bottom: 20px;
}
.icon-glow-1 {
    background: linear-gradient(135deg, rgba(96,165,250,0.2), rgba(59,130,246,0.1));
    border: 1px solid rgba(96,165,250,0.2);
}
.icon-glow-2 {
    background: linear-gradient(135deg, rgba(168,85,247,0.2), rgba(126,34,206,0.1));
    border: 1px solid rgba(168,85,247,0.2);
}

/* Background effects */
.ambient-glow {
    position: absolute;
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, rgba(79,70,229,0.15) 0%, rgba(0,0,0,0) 70%);
    top: -100px;
    right: -100px;
    border-radius: 50%;
    filter: blur(80px);
    z-index: 0;
    pointer-events: none;
}
</style>

<div class="premium-wrapper">

    <!-- ================= DASHBOARD HERO ================= -->
    <section class="premium-section" style="padding-top: 80px; padding-bottom: 40px; border-bottom: none;">
        <div class="ambient-glow"></div>
        <div class="container position-relative" style="z-index: 1;">
            <div class="glass-card glass-card-hover p-4 p-md-5 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                <div>
                    <h2 class="display-6 fw-bold mb-2">
                        Welcome, <span class="text-gradient"><?= htmlspecialchars($_SESSION['user_name']); ?></span> 👋
                    </h2>
                    <p class="text-secondary mb-0" style="font-size: 1.1rem;">
                        Your command center. Monitor activity and securely manage your connected workspace.
                    </p>
                </div>
                <div class="mt-4 mt-md-0">
                    <a href="<?= BASE_URL ?>/services.php" class="btn-premium">
                        + Deploy New Order
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= KPI CARDS ================= -->
    <section class="premium-section" style="padding-top: 20px; border-bottom: none;">
        <div class="container">
            <div class="row g-4">

                <!-- TOTAL ORDERS -->
                <div class="col-md-6">
                    <div class="glass-card glass-card-hover h-100 position-relative overflow-hidden">
                        <!-- Tiny inner glow -->
                        <div style="position:absolute; bottom:-50px; right:-50px; width:150px; height:150px; background: rgba(96,165,250,0.1); filter: blur(30px);"></div>
                        
                        <div class="card-body d-flex align-items-center gap-4 p-4 p-lg-5">
                            <div class="icon-glow icon-glow-1 mb-0 mx-0" style="width: 80px; height: 80px; font-size: 2.5rem;">
                                📦
                            </div>
                            <div>
                                <h1 class="display-4 fw-bold text-white mb-0"><?= $totalOrders ?></h1>
                                <p class="text-secondary mt-1 mb-0 fw-semibold text-uppercase" style="letter-spacing: 1px;">Global Deployments</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PENDING ORDERS -->
                <div class="col-md-6">
                    <div class="glass-card glass-card-hover h-100 position-relative overflow-hidden">
                        <div style="position:absolute; bottom:-50px; right:-50px; width:150px; height:150px; background: rgba(168,85,247,0.1); filter: blur(30px);"></div>

                        <div class="card-body d-flex align-items-center gap-4 p-4 p-lg-5">
                            <div class="icon-glow icon-glow-2 mb-0 mx-0" style="width: 80px; height: 80px; font-size: 2.5rem;">
                                ⏳
                            </div>
                            <div>
                                <h1 class="display-4 fw-bold text-white mb-0"><?= $pendingOrders ?></h1>
                                <p class="text-secondary mt-1 mb-0 fw-semibold text-uppercase" style="letter-spacing: 1px;">Awaiting Execution</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ================= USER ACTIONS / WORKSPACE ================= -->
    <section class="premium-section">
        <div class="container">

            <div class="d-flex align-items-center mb-4">
                <span class="d-inline-flex align-items-center justify-content-center bg-primary rounded-circle me-3" style="width: 12px; height: 12px; box-shadow: 0 0 10px #4f46e5;"></span>
                <h4 class="fw-bold mb-0">Tactical Workspace</h4>
            </div>

            <div class="row g-4">

                <div class="col-md-3">
                    <a href="<?= BASE_URL ?>/services.php" class="text-decoration-none h-100 glass-card glass-card-hover d-block">
                        <div class="text-center p-4">
                            <div class="icon-glow icon-glow-1 mx-auto">🛒</div>
                            <h6 class="fw-bold text-white">Service Matrix</h6>
                            <p class="text-secondary small mb-0">Browse platform architecture</p>
                        </div>
                    </a>
                </div>

                <div class="col-md-3">
                    <a href="my-orders.php" class="text-decoration-none h-100 glass-card glass-card-hover d-block">
                        <div class="text-center p-4">
                            <div class="icon-glow icon-glow-2 mx-auto" style="background: linear-gradient(135deg, rgba(236,72,153,0.2), rgba(219,39,119,0.1)); border-color: rgba(236,72,153,0.2);">📄</div>
                            <h6 class="fw-bold text-white">Order Intel</h6>
                            <p class="text-secondary small mb-0">Track active transmissions</p>
                        </div>
                    </a>
                </div>

                <div class="col-md-3">
                    <a href="chat/inbox.php" class="text-decoration-none h-100 glass-card glass-card-hover d-block">
                        <div class="text-center p-4">
                            <div class="icon-glow mx-auto" style="background: linear-gradient(135deg, rgba(16,185,129,0.2), rgba(5,150,105,0.1)); border-color: rgba(16,185,129,0.2);">💬</div>
                            <h6 class="fw-bold text-white">Comms Link</h6>
                            <p class="text-secondary small mb-0">Encrypted admin channel</p>
                        </div>
                    </a>
                </div>

                <div class="col-md-3">
                    <a href="profile.php" class="text-decoration-none h-100 glass-card glass-card-hover d-block">
                        <div class="text-center p-4">
                            <div class="icon-glow mx-auto" style="background: linear-gradient(135deg, rgba(245,158,11,0.2), rgba(217,119,6,0.1)); border-color: rgba(245,158,11,0.2);">👤</div>
                            <h6 class="fw-bold text-white">Identity Core</h6>
                            <p class="text-secondary small mb-0">Manage security clearances</p>
                        </div>
                    </a>
                </div>

            </div>
        </div>
    </section>

</div>

<?php require_once "../includes/footer.php"; ?>
