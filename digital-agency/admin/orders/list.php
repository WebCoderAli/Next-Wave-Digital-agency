<?php
require_once "../../includes/db.php";
require_once "../../includes/auth.php";

adminAuth();

$query = mysqli_query(
    $conn,
    "SELECT orders.*, users.name AS user_name, services.title AS service_title
     FROM orders
     JOIN users ON users.id = orders.user_id
     JOIN services ON services.id = orders.service_id
     ORDER BY orders.id DESC"
);
?>

<?php require_once "../../includes/admin_sidebar.php"; ?>



<div class="d-flex justify-content-between align-items-center mb-5">
    <h3 class="fw-bold m-0" style="font-family: 'Outfit', sans-serif;">Manage <span style="background: linear-gradient(135deg, #60a5fa, #c084fc); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Orders</span></h3>
</div>

<div class="glass-card mt-4 p-4 mb-4">
    <div class="table-responsive">
        <table class="table table-borderless table-premium align-middle mb-0">
            <thead>
                <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                    <th class="text-secondary fw-semibold text-uppercase" style="font-size: 0.8rem; letter-spacing: 1px; padding-bottom: 15px;">#</th>
                    <th class="text-secondary fw-semibold text-uppercase" style="font-size: 0.8rem; letter-spacing: 1px; padding-bottom: 15px;">Client</th>
                    <th class="text-secondary fw-semibold text-uppercase" style="font-size: 0.8rem; letter-spacing: 1px; padding-bottom: 15px;">Service</th>
                    <th class="text-secondary fw-semibold text-uppercase" style="font-size: 0.8rem; letter-spacing: 1px; padding-bottom: 15px;">Progress</th>
                    <th class="text-secondary fw-semibold text-uppercase" style="font-size: 0.8rem; letter-spacing: 1px; padding-bottom: 15px;">Status</th>
                    <th class="text-secondary fw-semibold text-uppercase" style="font-size: 0.8rem; letter-spacing: 1px; padding-bottom: 15px;">Date</th>
                    <th class="text-secondary fw-semibold text-uppercase text-end" style="font-size: 0.8rem; letter-spacing: 1px; padding-bottom: 15px; width: 250px;">Action</th>
                </tr>
            </thead>
            <tbody>

            <?php if (mysqli_num_rows($query) > 0):
                $i = 1;
                while ($order = mysqli_fetch_assoc($query)): 
                    // Dynamic Avatar
                    $nameParts = explode(' ', trim($order['user_name']));
                    $initials = strtoupper(substr($nameParts[0], 0, 1));
                    if (count($nameParts) > 1) {
                        $initials .= strtoupper(substr($nameParts[1], 0, 1));
                    }
                    
                    $gradients = [
                        'linear-gradient(135deg, #6366f1, #8b5cf6)',
                        'linear-gradient(135deg, #10b981, #059669)',
                        'linear-gradient(135deg, #f59e0b, #d97706)',
                        'linear-gradient(135deg, #ef4444, #b91c1c)',
                        'linear-gradient(135deg, #0ea5e9, #0369a1)',
                        'linear-gradient(135deg, #ec4899, #be185d)'
                    ];
                    $avatarColor = $gradients[abs(crc32($order['user_name'])) % count($gradients)];
                ?>

                <tr style="border-bottom: 1px solid rgba(255,255,255,0.03); transition: background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.02)'" onmouseout="this.style.background='transparent'">

                    <td class="text-secondary fw-medium py-3"><?= $i++; ?></td>

                    <td class="py-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-sm" style="width: 40px; height: 40px; background: <?= $avatarColor ?>; font-size: 0.9rem;">
                                <?= $initials ?>
                            </div>
                            <div>
                                <span class="fw-medium d-block"><?= htmlspecialchars($order['user_name']); ?></span>
                                <small class="text-secondary" style="font-size: 0.75rem;">Order #<?= $order['id']; ?></small>
                            </div>
                        </div>
                    </td>

                    <td class="text-light py-3">
                        <div><?= htmlspecialchars($order['service_title']); ?></div>
                        <?php if (!empty($order['offered_price'])): ?>
                            <small class="text-secondary">$<?= number_format((float) $order['offered_price'], 2); ?> · <?= ucfirst($order['offer_status']); ?></small>
                        <?php endif; ?>
                    </td>

                    <!-- PROGRESS -->
                    <td class="py-3">
                        <?php if ($order['status'] === 'rejected'): ?>
                            <span class="badge" style="background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.3); color: #fca5a5; padding: 6px 12px;">Rejected</span>
                        <?php else: ?>
                            <div class="d-flex align-items-center gap-2">
                                <span class="fw-bold text-white"><?= (int)$order['progress_percent']; ?>%</span>
                                <div class="progress" style="height: 6px; width: 60px; background: rgba(255,255,255,0.1); border-radius: 10px;">
                                    <div class="progress-bar" style="width: <?= (int)$order['progress_percent']; ?>%; background: linear-gradient(135deg, #6366f1, #8b5cf6); border-radius: 10px;"></div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </td>

                    <!-- STATUS -->
                    <td class="py-3">
                        <?php
                        if ($order['status'] === 'pending') {
                            echo '<span class="badge" style="background: rgba(234, 179, 8, 0.15); border: 1px solid rgba(234, 179, 8, 0.3); color: #fde047; padding: 6px 12px;">Pending</span>';
                        } elseif ($order['status'] === 'approved') {
                            echo '<span class="badge" style="background: rgba(56, 189, 248, 0.15); border: 1px solid rgba(56, 189, 248, 0.3); color: #7dd3fc; padding: 6px 12px;">Approved</span>';
                        } elseif ($order['status'] === 'completed') {
                            echo '<span class="badge" style="background: rgba(34, 197, 94, 0.15); border: 1px solid rgba(34, 197, 94, 0.3); color: #86efac; padding: 6px 12px;">Completed</span>';
                        } else {
                            echo '<span class="badge" style="background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.3); color: #fca5a5; padding: 6px 12px;">Rejected</span>';
                        }
                        ?>
                    </td>

                    <td class="text-secondary py-3 text-nowrap"><?= date("d M Y", strtotime($order['created_at'])); ?></td>

                    <!-- ACTION -->
                    <td class="py-3 text-end">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="view.php?id=<?= $order['id']; ?>"
                               class="btn btn-sm" style="background: rgba(99, 102, 241, 0.1); border: 1px solid rgba(99, 102, 241, 0.3); color: #818cf8; border-radius: 6px;">
                                View
                            </a>
                            <a href="../chat/send.php?order_id=<?= $order['id']; ?>"
                               class="btn btn-sm" style="background: rgba(14, 165, 233, 0.1); border: 1px solid rgba(14, 165, 233, 0.3); color: #38bdf8; border-radius: 6px;">
                                Chat
                            </a>

                            <?php if ($order['status'] === 'rejected'): ?>
                                <button type="button"
                                        class="btn btn-sm" style="background: rgba(100, 116, 139, 0.1); border: 1px solid rgba(100, 116, 139, 0.3); color: #cbd5e1; border-radius: 6px;"
                                        onclick="alert('This is a rejected order'); location.reload();">
                                    Update Progress
                                </button>
                            <?php else: ?>
                                <a href="add-progress.php?order_id=<?= $order['id']; ?>"
                                   class="btn btn-sm" style="background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.3); color: #86efac; border-radius: 6px;">
                                    Update Progress
                                </a>
                            <?php endif; ?>
                        </div>
                    </td>

                </tr>

            <?php endwhile; else: ?>
                <tr>
                    <td colspan="7" class="text-center text-secondary py-5">
                        No orders found.
                    </td>
                </tr>
            <?php endif; ?>

            </tbody>
        </table>
    </div>
</div>

        </div> <!-- End p-4 wrapper from sidebar -->
    </main> <!-- End admin-content -->
</div> <!-- End admin-wrapper -->
</body>
</html>
