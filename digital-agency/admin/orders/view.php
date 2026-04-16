<?php
require_once "../../includes/db.php";
require_once "../../includes/auth.php";

adminAuth();

$order_id = $_GET['id'] ?? null;
if (!$order_id) {
    header("Location: list.php");
    exit();
}

/*
|--------------------------------------------------------------------------
| FETCH ORDER WITH SERVICE PRICE
|--------------------------------------------------------------------------
*/
$stmt = mysqli_prepare(
    $conn,
    "SELECT 
        orders.*,
        users.name AS user_name,
        users.email AS user_email,
        services.title AS service_title,
        services.price AS service_price
     FROM orders
     JOIN users ON users.id = orders.user_id
     JOIN services ON services.id = orders.service_id
     WHERE orders.id = ?"
);
mysqli_stmt_bind_param($stmt, "i", $order_id);
mysqli_stmt_execute($stmt);
$order = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$order) {
    die("Order not found.");
}

// Fetch payment
$payment = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT * FROM payments WHERE order_id = $order_id LIMIT 1"
    )
);
?>

<?php require_once "../../includes/admin_sidebar.php"; ?>

<div class="d-flex justify-content-between align-items-center mb-5">
    <h3 class="fw-bold m-0" style="font-family: 'Outfit', sans-serif;">Order <span style="background: linear-gradient(135deg, #60a5fa, #c084fc); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Details</span></h3>
    <a href="list.php" class="btn btn-sm" style="background: rgba(100, 116, 139, 0.1); border: 1px solid rgba(100, 116, 139, 0.3); color: #cbd5e1; border-radius: 6px;">← Back to Orders</a>
</div>

<div class="row g-4">

    <!-- ORDER INFO -->
    <div class="col-lg-6">
        <div class="glass-card h-100" style="border-top: 4px solid #6366f1;">
            <div class="card-body">

                <h5 class="fw-bold mb-4" style="font-family: 'Outfit', sans-serif;">Information</h5>

                <div class="d-flex flex-column gap-3">
                    <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); padding: 15px; border-radius: 10px;">
                        <span class="text-secondary text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">User</span><br>
                        <strong class="fw-bold fs-5"><?php echo htmlspecialchars($order['user_name']); ?></strong>
                    </div>

                    <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); padding: 15px; border-radius: 10px;">
                        <span class="text-secondary text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Email</span><br>
                        <strong class="fw-medium text-primary"><?php echo htmlspecialchars($order['user_email']); ?></strong>
                    </div>

                    <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); padding: 15px; border-radius: 10px;">
                        <span class="text-secondary text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Service</span><br>
                        <strong class="fw-medium"><?php echo htmlspecialchars($order['service_title']); ?></strong>
                    </div>

                    <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); padding: 15px; border-radius: 10px; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <span class="text-secondary text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Service Price</span><br>
                            <h4 class="m-0 fw-bold" style="color: #c084fc;">$<?php echo number_format($order['service_price'], 2); ?></h4>
                        </div>
                        <div>
                            <span class="badge" style="background: rgba(100, 116, 139, 0.15); border: 1px solid rgba(100, 116, 139, 0.3); color: #cbd5e1; padding: 8px 12px; font-size: 0.8rem;">
                                <?php echo ucfirst($order['status']); ?>
                            </span>
                        </div>
                    </div>

                    <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); padding: 15px; border-radius: 10px;">
                        <span class="text-secondary text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Short Description</span><br>
                        <span style="opacity: 0.9;"><?php echo nl2br(htmlspecialchars($order['short_desc'])); ?></span>
                    </div>

                    <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); padding: 15px; border-radius: 10px;">
                        <span class="text-secondary text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Detailed Description</span><br>
                        <span style="opacity: 0.9; line-height: 1.6;"><?php echo nl2br(htmlspecialchars($order['detailed_desc'])); ?></span>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- PAYMENT INFO -->
    <div class="col-lg-6">
        <div class="glass-card h-100" style="border-top: 4px solid #10b981;">
            <div class="card-body">

                <h5 class="fw-bold mb-4" style="font-family: 'Outfit', sans-serif;">Payment Proof</h5>

                <?php if ($payment): ?>
                    <div style="background: rgba(0,0,0,0.2); padding: 10px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.1); margin-bottom: 20px;">
                        <img src="../../uploads/payments/<?php echo $payment['screenshot']; ?>"
                             class="img-fluid rounded w-100" style="object-fit: contain; max-height: 400px;">
                    </div>

                    <div class="d-flex gap-3 mt-4">
                        <a href="approve.php?id=<?php echo $order_id; ?>"
                           class="btn flex-grow-1 py-3 fw-bold" style="background: linear-gradient(135deg, #10b981, #059669); color: white; border-radius: 8px; border: none; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4); text-transform: uppercase; letter-spacing: 1px;"
                           data-confirm="Approve this order?">
                            Approve Order
                        </a>

                        <a href="reject.php?id=<?php echo $order_id; ?>"
                           class="btn flex-grow-1 py-3 fw-bold" style="background: linear-gradient(135deg, #ef4444, #b91c1c); color: white; border-radius: 8px; border: none; box-shadow: 0 4px 15px rgba(239, 68, 68, 0.4); text-transform: uppercase; letter-spacing: 1px;"
                           data-confirm="Reject this order?">
                            Reject Order
                        </a>
                    </div>
                <?php else: ?>
                    <div class="h-100 d-flex flex-column align-items-center justify-content-center text-secondary" style="min-height: 250px;">
                        <div style="font-size: 3rem; margin-bottom: 10px;">📄</div>
                        <p>No payment proof uploaded yet.</p>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>

</div>

        </div> <!-- End p-4 wrapper from sidebar -->
    </main> <!-- End admin-content -->
</div> <!-- End admin-wrapper -->
</body>
</html>

