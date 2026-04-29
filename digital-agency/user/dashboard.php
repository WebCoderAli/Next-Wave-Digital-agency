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

<div class="premium-wrapper">

    <!-- ================= DASHBOARD HERO ================= -->
    <section class="premium-section" style="padding-top: 80px; padding-bottom: 40px; border-bottom: none;">
        <div class="container position-relative" style="z-index: 1;">
            <div class="glass-card glass-card-hover p-4 p-md-5 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                <div>
                    <h2 class="display-6 fw-bold mb-2">
                        Welcome back, <span class="fw-semibold"><?= htmlspecialchars($_SESSION['user_name']); ?></span>
                    </h2>
                    <p class="text-secondary mb-0" style="font-size: 1.05rem;">
                        Track your orders, messages, and account settings in one place.
                    </p>
                </div>
                <div class="mt-4 mt-md-0">
                    <a href="<?= BASE_URL ?>/user/services.php" class="btn btn-sm btn-premium px-3 py-2 d-none d-md-inline-flex align-items-center gap-2">
                        Start request
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
                        <div class="card-body d-flex align-items-center gap-4 p-4 p-lg-5">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-4"
                                 style="width: 72px; height: 72px; background: rgba(109, 94, 252, 0.14); border: 1px solid rgba(109, 94, 252, 0.20);">
                                <span class="fw-bold" style="color: #fff;"><i class="bi bi-bag-fill"></i> </span>
                            </div>
                            <div>
                                <h1 class="display-4 fw-bold text-white mb-0"><?= $totalOrders ?></h1>
                                <p class="text-secondary mt-1 mb-0 fw-semibold text-uppercase" style="letter-spacing: 0.12em;">Total orders</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PENDING ORDERS -->
                <div class="col-md-6">
                    <div class="glass-card glass-card-hover h-100 position-relative overflow-hidden">
                        <div class="card-body d-flex align-items-center gap-4 p-4 p-lg-5">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-4"
                                 style="width: 72px; height: 72px; background: rgba(45, 212, 191, 0.14); border: 1px solid rgba(45, 212, 191, 0.20);">
                                <span class="fw-bold" style="color: #fff;"><i class="bi bi-hourglass-split"></i></span>
                            </div>
                            <div>
                                <h1 class="display-4 fw-bold text-white mb-0"><?= $pendingOrders ?></h1>
                                <p class="text-secondary mt-1 mb-0 fw-semibold text-uppercase" style="letter-spacing: 0.12em;">Pending</p>
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
                <h4 class="fw-bold mb-0">Quick actions</h4>
            </div>

            <div class="row g-4">

                <div class="col-md-3">
                    <a href="<?= BASE_URL ?>/user/services.php" class="text-decoration-none h-100 glass-card glass-card-hover d-block">
                        <div class="text-center p-4">
                            <div class="mb-3 fw-bold" style="letter-spacing: 0.12em; opacity: .9;">SERVICES</div>
                            <h6 class="fw-bold text-white">Browse services</h6>
                            <p class="text-secondary small mb-0">Choose a service and start negotiating</p>
                        </div>
                    </a>
                </div>

                <div class="col-md-3">
                    <a href="my-orders.php" class="text-decoration-none h-100 glass-card glass-card-hover d-block">
                        <div class="text-center p-4">
                            <div class="mb-3 fw-bold" style="letter-spacing: 0.12em; opacity: .9;">ORDERS</div>
                            <h6 class="fw-bold text-white">My orders</h6>
                            <p class="text-secondary small mb-0">View status and updates</p>
                        </div>
                    </a>
                </div>

                <div class="col-md-3">
                    <a href="chat/inbox.php" class="text-decoration-none h-100 glass-card glass-card-hover d-block">
                        <div class="text-center p-4">
                            <div class="mb-3 fw-bold" style="letter-spacing: 0.12em; opacity: .9;">MESSAGES</div>
                            <h6 class="fw-bold text-white">Support chat</h6>
                            <p class="text-secondary small mb-0">Message the admin team</p>
                        </div>
                    </a>
                </div>

                <div class="col-md-3">
                    <a href="profile.php" class="text-decoration-none h-100 glass-card glass-card-hover d-block">
                        <div class="text-center p-4">
                            <div class="mb-3 fw-bold" style="letter-spacing: 0.12em; opacity: .9;">ACCOUNT</div>
                            <h6 class="fw-bold text-white">Profile</h6>
                            <p class="text-secondary small mb-0">Update name and email</p>
                        </div>
                    </a>
                </div>

            </div>
        </div>
    </section>

</div>

<?php require_once "../includes/footer.php"; ?>
