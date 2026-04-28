<?php
require_once "../includes/db.php";
require_once "../includes/auth.php";

userAuth();

$user_id = $_SESSION[USER_SESSION];

// Fetch user orders with service
$query = mysqli_query(
    $conn,
    "SELECT orders.*,
            services.title AS service_title,
            (SELECT COALESCE(SUM(payments.amount), 0) FROM payments WHERE payments.order_id = orders.id) AS paid_amount
     FROM orders
     JOIN services ON services.id = orders.service_id
     WHERE orders.user_id = $user_id
     ORDER BY orders.id DESC"
);

$totalOrders = mysqli_num_rows($query);
?>

<?php require_once "../includes/header.php"; ?>

<div class="premium-wrapper">
    
    <!-- ================= PAGE HERO ================= -->
    <section class="premium-section pb-0" style="padding-top: 80px;">
        <div class="container position-relative" style="z-index: 1;">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-5">
                <div>
                    <h2 class="display-6 fw-bold mb-2">My orders</h2>
                    <p class="text-secondary mb-0" style="font-size: 1.05rem;">
                        Track status updates and view progress for each order.
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
                            <div class="d-inline-flex align-items-center justify-content-center rounded-4"
                                 style="width: 56px; height: 56px; background: rgba(109, 94, 252, 0.14); border: 1px solid rgba(109, 94, 252, 0.20);">
                                <span class="fw-bold text-white"><i class="bi bi-bag-fill"></i></span>
                            </div>
                            <div>
                                <h3 class="display-5 fw-bold text-white mb-0"><?= $totalOrders ?></h3>
                                <p class="text-secondary mt-1 mb-0 fw-semibold text-uppercase" style="letter-spacing: 0.12em; font-size: 0.85rem;">Total</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="glass-card glass-card-hover h-100 position-relative overflow-hidden">
                        <div class="card-body d-flex align-items-center gap-4 p-4">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-4"
                                 style="width: 56px; height: 56px; background: rgba(245, 158, 11, 0.12); border: 1px solid rgba(245, 158, 11, 0.18);">
                                <span class="fw-bold text-white"><i class="bi bi-hourglass-split"></i></span>
                            </div>
                            <div>
                                <h3 class="display-5 fw-bold text-white mb-0">
                                    <?= mysqli_num_rows(mysqli_query(
                                        $conn,
                                        "SELECT id FROM orders WHERE user_id = $user_id AND status = 'pending'"
                                    )) ?>
                               </h3>
                                <p class="text-secondary mt-1 mb-0 fw-semibold text-uppercase" style="letter-spacing: 0.12em; font-size: 0.85rem;">Pending</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="glass-card glass-card-hover h-100 position-relative overflow-hidden">
                        <div class="card-body d-flex align-items-center gap-4 p-4">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-4"
                                 style="width: 56px; height: 56px; background: rgba(45, 212, 191, 0.14); border: 1px solid rgba(45, 212, 191, 0.20);">
                                <span class="fw-bold text-white"><i class="bi bi-check-circle-fill"></i></span>
                            </div>
                            <div>
                                <h3 class="display-5 fw-bold text-white mb-0">
                                    <?= mysqli_num_rows(mysqli_query(
                                        $conn,
                                        "SELECT id FROM orders WHERE user_id = $user_id AND status = 'completed'"
                                    )) ?>
                                </h3>
                                <p class="text-secondary mt-1 mb-0 fw-semibold text-uppercase" style="letter-spacing: 0.12em; font-size: 0.85rem;">Completed</p>
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
                
                <div class="orders-toolbar d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                    <div>
                        <h5 class="fw-bold fs-4 mb-1 text-white">Orders</h5>
                        <div class="text-secondary small">Latest orders appear first.</div>
                    </div>
                    <a href="<?= BASE_URL ?>/services.php" class="btn btn-primary d-inline-flex align-items-center gap-2">
                        <i class="bi bi-plus-circle"></i>
                        <span>New order</span>
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="orders-table">
                        <thead>
                            <tr>
                                <th style="width:72px;">#</th>
                                <th>Service</th>
                                <th style="width:180px;">Status</th>
                                <th style="width:170px;">Date</th>
                                <th style="width:200px; text-align: right;">Action</th>
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
                                    <div class="order-ref mt-1">
                                        Ref #<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT); ?>
                                    </div>
                                    <?php if (!empty($order['offered_price'])): ?>
                                        <div class="order-ref mt-1">
                                            Offer: $<?= number_format((float) $order['offered_price'], 2); ?>
                                            <?php if (!empty($order['payment_mode'])): ?>
                                                · <?= htmlspecialchars(ucfirst($order['payment_mode'])); ?>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?php
                                    if ($order['status'] === 'pending') {
                                        $label = ($order['offer_status'] ?? 'waiting') === 'waiting' ? 'Awaiting Offer' : ucfirst($order['offer_status']);
                                        echo '<span class="status-pill is-pending"><i class="bi bi-hourglass-split"></i> ' . htmlspecialchars($label) . '</span>';
                                    } elseif ($order['status'] === 'approved') {
                                        echo '<span class="status-pill is-approved"><i class="bi bi-check2-circle"></i> Approved</span>';
                                    } elseif ($order['status'] === 'completed') {
                                        echo '<span class="status-pill is-completed"><i class="bi bi-check-circle-fill"></i> Completed</span>';
                                    } else {
                                        echo '<span class="status-pill is-rejected"><i class="bi bi-x-circle"></i> Rejected</span>';
                                    }
                                    ?>
                                </td>

                                <td class="text-secondary">
                                    <?= date("d M Y", strtotime($order['created_at'])); ?>
                                </td>

                                <!-- ACTION -->
                                <td style="text-align: right;">
                                    <?php if ($order['status'] === 'rejected'): ?>
                                        <a href="chat/inbox.php?order_id=<?= $order['id']; ?>" class="btn btn-sm btn-outline-light">
                                            <i class="bi bi-chat-dots"></i>
                                            Chat
                                        </a>
                                    <?php elseif ($order['status'] === 'pending'): ?>
                                        <a href="chat/inbox.php?order_id=<?= $order['id']; ?>" class="btn btn-sm btn-outline-light d-inline-flex align-items-center gap-2">
                                            <i class="bi bi-chat-dots"></i>
                                            <span>Open negotiation</span>
                                        </a>
                                    <?php elseif (($order['offer_status'] ?? '') === 'accepted'): ?>
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="chat/inbox.php?order_id=<?= $order['id']; ?>" class="btn btn-sm btn-outline-light d-inline-flex align-items-center gap-2">
                                                <i class="bi bi-chat-dots"></i>
                                                <span>Chat</span>
                                            </a>
                                            <a href="pay-order.php?id=<?= $order['id']; ?>" class="btn btn-sm btn-primary d-inline-flex align-items-center gap-2">
                                                <i class="bi bi-credit-card"></i>
                                                <span>Pay</span>
                                            </a>
                                        </div>
                                    <?php else: ?>
                                        <a href="order-progress.php?id=<?= $order['id']; ?>"
                                           class="btn btn-sm btn-outline-light d-inline-flex align-items-center gap-2">
                                            <i class="bi bi-activity"></i>
                                            <span>View progress</span>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>

                        <?php endwhile; else: ?>

                            <tr>
                                <td colspan="5" class="text-center py-5" style="border: none !important;">
                                    <div class="py-5">
                                        <div class="mb-2" style="opacity: .75;">
                                            <i class="bi bi-receipt" style="font-size: 2rem;"></i>
                                        </div>
                                        <h4 class="fw-bold text-white mb-2">No orders yet</h4>
                                        <p class="text-secondary mx-auto" style="max-width: 400px;">
                                            When you place an order, it will show up here with its status and progress.
                                        </p>
                                        <a href="<?= BASE_URL ?>/services.php" class="btn btn-primary mt-3 d-inline-flex align-items-center gap-2">
                                            <i class="bi bi-grid-3x3-gap"></i>
                                            <span>Browse services</span>
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
