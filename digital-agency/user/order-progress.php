<?php
require_once "../includes/db.php";
require_once "../includes/auth.php";

userAuth();

/* ------------------------------
   Validate order
------------------------------ */
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid order.");
}

$order_id = (int)$_GET['id'];
$user_id  = $_SESSION[USER_SESSION];

/* ------------------------------
   Fetch order
------------------------------ */
$orderQuery = mysqli_query(
    $conn,
    "SELECT orders.*, services.title AS service_title
     FROM orders
     JOIN services ON services.id = orders.service_id
     WHERE orders.id = $order_id AND orders.user_id = $user_id
     LIMIT 1"
);

if (mysqli_num_rows($orderQuery) === 0) {
    die("Order not found.");
}

$order = mysqli_fetch_assoc($orderQuery);

/* ------------------------------
   Fetch progress timeline
------------------------------ */
$progressQuery = mysqli_query(
    $conn,
    "SELECT * FROM order_progress
     WHERE order_id = $order_id
     ORDER BY created_at DESC"
);
?>

<?php require_once "../includes/header.php"; ?>

<style>
/* ================= PREMIUM PROGRESS STYLES ================= */
.premium-wrapper {
    background-color: #050505;
    color: #e2e8f0;
    font-family: 'Inter', sans-serif;
    overflow-x: hidden;
    min-height: calc(100vh - 80px);
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
}

.glass-card {
    background: rgba(255, 255, 255, 0.02);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid rgba(255, 255, 255, 0.05);
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.2);
    border-radius: 20px;
    position: relative;
    overflow: hidden;
}

.text-gradient {
    background: linear-gradient(135deg, #60a5fa, #c084fc, #f472b6);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Custom Progress Bar */
.progress-container {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 30px;
    height: 24px;
    border: 1px solid rgba(255, 255, 255, 0.1);
    box-shadow: inset 0 2px 10px rgba(0,0,0,0.5);
    overflow: hidden;
}
.progress-bar-neon {
    background: linear-gradient(90deg, #4f46e5, #c084fc, #ec4899);
    height: 100%;
    border-radius: 30px;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    padding-right: 12px;
    font-size: 0.8rem;
    font-weight: 700;
    color: #fff;
    box-shadow: 0 0 15px rgba(192, 132, 252, 0.6);
    transition: width 1s ease-in-out;
}

/* Timeline Customization */
.timeline-feed {
    border-left: 2px solid rgba(124, 58, 237, 0.3);
    padding-left: 30px;
    position: relative;
    margin-left: 15px;
}
.timeline-node {
    margin-bottom: 40px;
    position: relative;
}
.timeline-node::before {
    content: '';
    position: absolute;
    width: 16px;
    height: 16px;
    background: #050505;
    border: 3px solid #c084fc;
    border-radius: 50%;
    left: -39px;
    top: 4px;
    box-shadow: 0 0 10px rgba(192, 132, 252, 0.5);
}

/* Custom Alerts */
.alert-premium {
    background: rgba(255,255,255,0.03);
    border-radius: 16px;
    padding: 16px 20px;
    border: 1px solid rgba(255,255,255,0.1);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 12px;
}
.alert-success-neon { border-color: rgba(52, 211, 153, 0.4); background: rgba(52, 211, 153, 0.05); color: #6ee7b7; box-shadow: 0 0 15px rgba(52, 211, 153, 0.1); }
.alert-danger-neon { border-color: rgba(248, 113, 113, 0.4); background: rgba(248, 113, 113, 0.05); color: #fca5a5; box-shadow: 0 0 15px rgba(248, 113, 113, 0.1); }

/* Buttons */
.btn-outline-premium {
    background: transparent;
    color: #e2e8f0;
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 50px;
    padding: 10px 24px;
    font-weight: 500;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}
.btn-outline-premium:hover {
    background: rgba(255,255,255,0.05);
    color: #fff;
    border-color: #c084fc;
    box-shadow: 0 0 15px rgba(192, 132, 252, 0.3);
}

.ambient-glow {
    position: absolute;
    width: 600px;
    height: 600px;
    background: radial-gradient(circle, rgba(124, 58, 237, 0.12) 0%, rgba(0,0,0,0) 70%);
    top: 50px;
    right: -100px;
    border-radius: 50%;
    filter: blur(80px);
    z-index: 0;
    pointer-events: none;
}
</style>

<div class="premium-wrapper position-relative">
    <div class="ambient-glow"></div>

    <div class="container py-5 position-relative" style="z-index: 1;">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                <!-- ================= BACK BUTTON ================= -->
                <div class="mb-4">
                    <a href="my-orders.php" class="btn-outline-premium" style="padding: 8px 20px; font-size: 0.9rem;">
                        ← Disconnect & Return
                    </a>
                </div>

                <!-- ================= ORDER HEADER ================= -->
                <div class="glass-card p-4 p-md-5 mb-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div>
                        <h2 class="display-6 fw-bold mb-2">Transmission <span class="text-gradient">Link</span></h2>
                        <p class="text-secondary mb-0">
                            Monitoring data feed for: <span class="text-white fw-bold"><?= htmlspecialchars($order['service_title']); ?></span>
                        </p>
                    </div>
                    <div class="text-md-end">
                        <span class="d-block text-secondary small text-uppercase fw-bold letter-spacing-1">Routing Code</span>
                        <span class="fs-4 fw-bold text-white">#<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT); ?></span>
                    </div>
                </div>

                <!-- ================= STATUS CARD ================= -->
                <div class="glass-card mb-4" style="background: rgba(255,255,255,0.01);">
                    <div class="p-4 p-md-5">

                        <?php if ($order['status'] === 'rejected'): ?>

                            <div class="alert-premium alert-danger-neon mb-0">
                                <span class="fs-4">🛑</span>
                                <div>
                                    <h6 class="fw-bold mb-1">Transmission Intercepted</h6>
                                    <p class="mb-0 small opacity-75">This deployment was rejected by central command. Action terminated.</p>
                                </div>
                            </div>

                        <?php else: ?>

                            <div class="d-flex justify-content-between align-items-end mb-3">
                                <span class="fw-bold text-white text-uppercase" style="letter-spacing: 1px;">Completion Matrix</span>
                                <span class="fs-4 fw-bold text-gradient"><?= (int)$order['progress_percent']; ?>%</span>
                            </div>

                            <div class="progress-container mb-4">
                                <div class="progress-bar-neon" style="width: <?= (int)$order['progress_percent']; ?>%">
                                    <?php if((int)$order['progress_percent'] > 5) echo (int)$order['progress_percent'].'%'; ?>
                                </div>
                            </div>

                            <?php if ($order['status'] === 'completed'): ?>
                                <div class="alert-premium alert-success-neon mb-0">
                                    <span class="fs-4">✅</span>
                                    <div>
                                        <h6 class="fw-bold mb-1">Deployment Successful</h6>
                                        <p class="mb-0 small opacity-75">Service logic executed completely. Connection closed securely.</p>
                                    </div>
                                </div>
                            <?php endif; ?>

                        <?php endif; ?>

                    </div>
                </div>

                <!-- ================= TIMELINE ================= -->
                <div class="glass-card mb-5">
                    <div class="p-4 p-md-5">

                        <h4 class="fw-bold mb-4 pb-3" style="border-bottom: 1px solid rgba(255,255,255,0.05);">Operation Logs</h4>

                        <?php if (mysqli_num_rows($progressQuery) > 0): ?>

                            <div class="timeline-feed mt-2">
                                <?php while ($progress = mysqli_fetch_assoc($progressQuery)): ?>
                                    <div class="timeline-node">
                                        
                                        <!-- TITLE -->
                                        <h5 class="fw-bold text-white mb-2 d-flex align-items-center gap-3">
                                            <span class="badge" style="background: rgba(192, 132, 252, 0.2); color: #d8b4fe; border: 1px solid rgba(192, 132, 252, 0.3);">NODE <?= (int)$progress['progress_percent']; ?>%</span>
                                            <small class="text-secondary fw-normal fs-6">
                                                <?= date("d M, Y - H:i", strtotime($progress['created_at'])); ?>
                                            </small>
                                        </h5>

                                        <!-- MESSAGE -->
                                        <div class="text-light opacity-75 mb-3 lh-lg" style="font-size: 1.05rem;">
                                            <?= nl2br(htmlspecialchars($progress['message'])); ?>
                                        </div>

                                        <!-- FILE / IMAGE -->
                                        <?php if (!empty($progress['file'])):
                                            $filePath = "../uploads/order-progress/" . $progress['file'];
                                            $ext = strtolower(pathinfo($progress['file'], PATHINFO_EXTENSION));
                                            $isImage = in_array($ext, ['jpg','jpeg','png','gif']);
                                        ?>
                                            <div class="p-3 mt-3 rounded border" style="background: rgba(0,0,0,0.2); border-color: rgba(255,255,255,0.05) !important;">
                                                <?php if ($isImage): ?>
                                                    <div class="mb-3">
                                                        <img src="<?= $filePath ?>" class="img-fluid rounded border border-secondary border-opacity-25" style="max-height: 250px;">
                                                    </div>
                                                <?php endif; ?>
                                                <a href="<?= $filePath ?>" class="btn-outline-premium" style="font-size: 0.85rem; padding: 6px 16px; border-color: rgba(56, 189, 248, 0.4); color: #38bdf8;" download>
                                                    ⬇ Download Attachment (<?= strtoupper($ext) ?>)
                                                </a>
                                            </div>
                                        <?php endif; ?>

                                    </div>
                                <?php endwhile; ?>
                            </div>

                        <?php else: ?>

                            <div class="py-5 text-center">
                                <div class="display-3 mb-3 text-secondary" style="opacity: 0.5;">📡</div>
                                <h5 class="fw-bold text-white">Awaiting Signal</h5>
                                <p class="text-secondary mx-auto">
                                    No log entries received from central command yet. 
                                </p>
                            </div>

                        <?php endif; ?>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php require_once "../includes/footer.php"; ?>
