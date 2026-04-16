<?php
require_once "../includes/db.php";
require_once "../includes/auth.php";

userAuth();

$user_id = $_SESSION[USER_SESSION];

// Fetch user orders with service
$query = mysqli_query(
    $conn,
    "SELECT orders.*, services.title AS service_title
     FROM orders
     JOIN services ON services.id = orders.service_id
     WHERE orders.user_id = $user_id
     ORDER BY orders.id DESC"
);

$totalOrders = mysqli_num_rows($query);
?>

<?php require_once "../includes/header.php"; ?>

<style>
/* ================= PREMIUM ORDERS STYLES ================= */
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
    border-color: rgba(192, 132, 252, 0.6);
    background: rgba(255, 255, 255, 0.06);
    cursor: pointer;
}

.text-gradient {
    background: linear-gradient(135deg, #60a5fa, #c084fc, #f472b6);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
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
.icon-glow-total { background: linear-gradient(135deg, rgba(96,165,250,0.2), rgba(59,130,246,0.1)); border: 1px solid rgba(96,165,250,0.2); }
.icon-glow-pending { background: linear-gradient(135deg, rgba(168,85,247,0.2), rgba(126,34,206,0.1)); border: 1px solid rgba(168,85,247,0.2); }
.icon-glow-completed { background: linear-gradient(135deg, rgba(16,185,129,0.2), rgba(5,150,105,0.1)); border: 1px solid rgba(16,185,129,0.2); }

.ambient-glow {
    position: absolute;
    width: 500px;
    height: 500px;
    background: radial-gradient(circle, rgba(168,85,247,0.1) 0%, rgba(0,0,0,0) 70%);
    top: -100px;
    left: -100px;
    border-radius: 50%;
    filter: blur(80px);
    z-index: 0;
    pointer-events: none;
}

/* Premium Buttons */
.btn-premium {
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    color: #fff !important;
    border: none;
    border-radius: 50px;
    padding: 10px 24px;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 8px 15px -5px rgba(124, 58, 237, 0.5);
    display: inline-block;
    text-decoration: none;
}
.btn-premium:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 20px -5px rgba(124, 58, 237, 0.6);
}
.btn-outline-premium {
    background: transparent;
    color: #e2e8f0;
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 50px;
    padding: 8px 20px;
    font-weight: 500;
    transition: all 0.3s ease;
}
.btn-outline-premium:hover {
    background: rgba(255,255,255,0.1);
    color: #fff;
    border-color: rgba(255,255,255,0.4);
}

/* Premium Table */
.premium-table-wrapper {
    width: 100%;
}
.premium-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 10px; /* Space between rows */
}
.premium-table thead th {
    background: transparent;
    border: none;
    color: #94a3b8;
    text-transform: uppercase;
    font-size: 0.85rem;
    font-weight: 600;
    letter-spacing: 1px;
    padding: 15px 25px;
}
.premium-table tbody tr {
    background: rgba(255,255,255,0.02);
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    border: 1px solid rgba(255,255,255,0.05);
}
.premium-table tbody tr:hover {
    background: rgba(255,255,255,0.05);
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.3);
}
.premium-table td {
    padding: 20px 25px;
    border: none;
    color: #f8fafc;
    vertical-align: middle;
}
.premium-table tbody tr td:first-child { border-top-left-radius: 16px; border-bottom-left-radius: 16px; border-left: 1px solid rgba(255,255,255,0.05); }
.premium-table tbody tr td:last-child { border-top-right-radius: 16px; border-bottom-right-radius: 16px; border-right: 1px solid rgba(255,255,255,0.05); }

/* Badges */
.status-badge {
    padding: 6px 14px;
    border-radius: 30px;
    font-size: 0.85rem;
    font-weight: 600;
    letter-spacing: 0.5px;
    display: inline-block;
}
.badge-warning-custom { background: rgba(245, 158, 11, 0.15); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.3); }
.badge-info-custom { background: rgba(56, 189, 248, 0.15); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.3); }
.badge-success-custom { background: rgba(52, 211, 153, 0.15); color: #34d399; border: 1px solid rgba(52, 211, 153, 0.3); }
.badge-danger-custom { background: rgba(248, 113, 113, 0.15); color: #f87171; border: 1px solid rgba(248, 113, 113, 0.3); }
</style>

<div class="premium-wrapper">
    <div class="ambient-glow"></div>
    
    <!-- ================= PAGE HERO ================= -->
    <section class="premium-section pb-0" style="padding-top: 80px;">
        <div class="container position-relative" style="z-index: 1;">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-5">
                <div>
                    <h2 class="display-6 fw-bold mb-2">Order <span class="text-gradient">Management</span></h2>
                    <p class="text-secondary mb-0" style="font-size: 1.1rem;">
                        Monitor, track, and directly manage all your active project deployments continuously.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= KPI SUMMARY ================= -->
    <section class="premium-section py-4">
        <div class="container position-relative" style="z-index: 1;">
            <div class="row g-4">

                <div class="col-md-4">
                    <div class="glass-card glass-card-hover h-100 position-relative overflow-hidden">
                        <div class="card-body d-flex align-items-center gap-4 p-4">
                            <div class="icon-glow icon-glow-total mx-0 mb-0">📦</div>
                            <div>
                                <h3 class="display-5 fw-bold text-white mb-0"><?= $totalOrders ?></h3>
                                <p class="text-secondary mt-1 mb-0 fw-semibold text-uppercase" style="letter-spacing: 1px; font-size: 0.85rem;">Total Orders</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="glass-card glass-card-hover h-100 position-relative overflow-hidden">
                        <div class="card-body d-flex align-items-center gap-4 p-4">
                            <div class="icon-glow icon-glow-pending mx-0 mb-0">⏳</div>
                            <div>
                                <h3 class="display-5 fw-bold text-white mb-0">
                                    <?= mysqli_num_rows(mysqli_query(
                                        $conn,
                                        "SELECT id FROM orders WHERE user_id = $user_id AND status = 'pending'"
                                    )) ?>
                               </h3>
                                <p class="text-secondary mt-1 mb-0 fw-semibold text-uppercase" style="letter-spacing: 1px; font-size: 0.85rem;">Pending Validation</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="glass-card glass-card-hover h-100 position-relative overflow-hidden">
                        <div class="card-body d-flex align-items-center gap-4 p-4">
                            <div class="icon-glow icon-glow-completed mx-0 mb-0">✅</div>
                            <div>
                                <h3 class="display-5 fw-bold text-white mb-0">
                                    <?= mysqli_num_rows(mysqli_query(
                                        $conn,
                                        "SELECT id FROM orders WHERE user_id = $user_id AND status = 'completed'"
                                    )) ?>
                                </h3>
                                <p class="text-secondary mt-1 mb-0 fw-semibold text-uppercase" style="letter-spacing: 1px; font-size: 0.85rem;">Successfully Deployed</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ================= ORDERS TABLE ================= -->
    <section class="premium-section pt-3">
        <div class="container position-relative" style="z-index: 1;">

            <div class="glass-card p-4 p-lg-5">
                
                <div class="d-flex justify-content-between align-items-center mb-4 pb-3" style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                    <h5 class="fw-bold fs-4 mb-0 text-white">Transmission Data</h5>
                    <a href="<?= BASE_URL ?>/services.php" class="btn-premium">
                        + New Order
                    </a>
                </div>

                <div class="table-responsive premium-table-wrapper">
                    <table class="premium-table">
                        <thead>
                            <tr>
                                <th style="width:60px;">#</th>
                                <th>Service Signature</th>
                                <th style="width:160px;">System Status</th>
                                <th style="width:180px;">Ingest Date</th>
                                <th style="width:180px; text-align: right;">Action Code</th>
                            </tr>
                        </thead>

                        <tbody>

                        <?php if ($totalOrders > 0):
                            $i = 1;
                            mysqli_data_seek($query, 0);
                            while ($order = mysqli_fetch_assoc($query)): ?>

                            <tr>
                                <td class="text-secondary fw-bold">#<?= str_pad($i++, 2, '0', STR_PAD_LEFT); ?></td>

                                <td>
                                    <div class="fw-bold text-white fs-6">
                                        <?= htmlspecialchars($order['service_title']); ?>
                                    </div>
                                    <span class="text-secondary d-block mt-1" style="font-size: 0.85rem;">
                                        Internal Ref: #<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT); ?>
                                    </span>
                                </td>

                                <td>
                                    <?php
                                    if ($order['status'] === 'pending') {
                                        echo '<span class="status-badge badge-warning-custom">Pending</span>';
                                    } elseif ($order['status'] === 'approved') {
                                        echo '<span class="status-badge badge-info-custom">Approved</span>';
                                    } elseif ($order['status'] === 'completed') {
                                        echo '<span class="status-badge badge-success-custom">Completed</span>';
                                    } else {
                                        echo '<span class="status-badge badge-danger-custom">Rejected</span>';
                                    }
                                    ?>
                                </td>

                                <td class="text-secondary">
                                    <span class="d-flex align-items-center gap-2">
                                        🗓️ <?= date("d M Y", strtotime($order['created_at'])); ?>
                                    </span>
                                </td>

                                <!-- ACTION -->
                                <td style="text-align: right;">
                                    <?php if ($order['status'] === 'rejected'): ?>
                                        <button class="btn-outline-premium border-0 text-danger"
                                                onclick="alert('This order was rejected by an admin. Check messages for details.'); location.reload();" style="background: rgba(248, 113, 113, 0.1);">
                                            Intercepted
                                        </button>
                                    <?php else: ?>
                                        <a href="order-progress.php?id=<?= $order['id']; ?>"
                                           class="btn-outline-premium">
                                            View Logs
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>

                        <?php endwhile; else: ?>

                            <tr>
                                <td colspan="5" class="text-center py-5" style="border: none !important;">
                                    <div class="py-5">
                                        <div class="display-1 mb-3" style="opacity: 0.2;">📭</div>
                                        <h4 class="fw-bold text-white mb-2">No Active Transmissions</h4>
                                        <p class="text-secondary mx-auto" style="max-width: 400px;">
                                            You haven't initiated any deployments yet. Check the service matrix to begin.
                                        </p>
                                        <a href="<?= BASE_URL ?>/services.php" class="btn-premium mt-3">
                                            Access Services
                                        </a>
                                    </div>
                                </td>
                            </tr>

                        <?php endif; ?>

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </section>

</div>

<?php require_once "../includes/footer.php"; ?>
