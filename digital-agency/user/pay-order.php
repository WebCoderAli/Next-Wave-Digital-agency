<?php
require_once "../includes/db.php";
require_once "../includes/auth.php";

userAuth();

$user_id = $_SESSION[USER_SESSION];
$order_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($order_id <= 0) {
    header("Location: my-orders.php");
    exit();
}

$orderQuery = mysqli_query(
    $conn,
    "SELECT orders.*, services.title AS service_title
     FROM orders
     JOIN services ON services.id = orders.service_id
     WHERE orders.id = $order_id AND orders.user_id = $user_id
     LIMIT 1"
);
$order = $orderQuery ? mysqli_fetch_assoc($orderQuery) : null;

if (!$order) {
    die("Order not found.");
}

$milestones = decodeMilestones($order['milestone_plan'] ?? null);
$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $amount = (float) ($_POST['amount'] ?? 0);
    $milestone_name = trim($_POST['milestone_name'] ?? '');
    $note = trim($_POST['note'] ?? '');

    if ($amount <= 0) {
        $error = "Please enter a valid amount.";
    } elseif (empty($_FILES['payment']['name'])) {
        $error = "Payment screenshot is required.";
    } else {
        $ext = strtolower(pathinfo($_FILES['payment']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png'];

        if (!in_array($ext, $allowed, true)) {
            $error = "Only JPG, JPEG, and PNG screenshots are allowed.";
        } else {
            $targetSubDir = "/payments/";
            $uploadPath = UPLOAD_DIR . $targetSubDir;

            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            $fileName = uniqid("payment_", true) . "." . $ext;
            if (!move_uploaded_file($_FILES['payment']['tmp_name'], $uploadPath . $fileName)) {
                $error = "Payment upload failed.";
            } else {
                $payStmt = mysqli_prepare(
                    $conn,
                    "INSERT INTO payments (order_id, amount, milestone_name, note, screenshot, verified)
                     VALUES (?, ?, ?, ?, ?, 'pending')"
                );
                mysqli_stmt_bind_param($payStmt, "idsss", $order_id, $amount, $milestone_name, $note, $fileName);
                mysqli_stmt_execute($payStmt);

                $chatMessage = "Client uploaded payment proof for $" . number_format($amount, 2);
                if ($milestone_name !== '') {
                    $chatMessage .= " (" . $milestone_name . ")";
                }
                if ($note !== '') {
                    $chatMessage .= ". Note: " . $note;
                }

                $chatStmt = mysqli_prepare(
                    $conn,
                    "INSERT INTO chats (order_id, sender_id, receiver_id, sender_role, message, file)
                     VALUES (?, ?, 0, 'user', ?, ?)"
                );
                mysqli_stmt_bind_param($chatStmt, "iiss", $order_id, $user_id, $chatMessage, $fileName);
                mysqli_stmt_execute($chatStmt);

                $success = "Payment proof uploaded successfully.";
            }
        }
    }
}

$payments = mysqli_query(
    $conn,
    "SELECT * FROM payments WHERE order_id = $order_id ORDER BY id DESC"
);
?>

<?php require_once "../includes/header.php"; ?>

<div class="premium-wrapper">
    <section class="premium-section py-4 py-md-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-5">
                    <div class="glass-card p-4 h-100">
                        <h3 class="fw-bold mb-3">Payment for <?= htmlspecialchars($order['service_title']); ?></h3>
                        <div class="text-secondary mb-2">Accepted offer</div>
                        <div class="display-6 fw-bold text-white mb-3">$<?= number_format((float) ($order['offered_price'] ?? 0), 2); ?></div>
                        <div class="text-secondary mb-3"><?= htmlspecialchars(formatPaymentMode($order['payment_mode'] ?? null)); ?></div>

                        <?php if (!empty($milestones)): ?>
                            <div class="small text-secondary text-uppercase mb-2">Milestone plan</div>
                            <?php foreach ($milestones as $milestone): ?>
                                <div class="border rounded p-2 mb-2">
                                    <div class="fw-semibold text-white"><?= htmlspecialchars($milestone['title'] ?? 'Milestone'); ?></div>
                                    <div class="small text-secondary">$<?= number_format((float) ($milestone['amount'] ?? 0), 2); ?></div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="glass-card p-4 p-md-5">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h3 class="fw-bold mb-0">Upload payment proof</h3>
                            <a href="my-orders.php" class="btn btn-outline-light btn-sm">Back</a>
                        </div>

                        <?php if ($success): ?>
                            <div class="alert alert-success"><?= htmlspecialchars($success); ?></div>
                        <?php endif; ?>

                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($error); ?></div>
                        <?php endif; ?>

                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label">Amount paid</label>
                                <input type="number" name="amount" min="0" step="0.01" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Milestone name (optional)</label>
                                <select name="milestone_name" class="form-select">
                                    <option value="">Full payment / not specific</option>
                                    <?php foreach ($milestones as $milestone): ?>
                                        <option value="<?= htmlspecialchars($milestone['title'] ?? ''); ?>"><?= htmlspecialchars($milestone['title'] ?? 'Milestone'); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Screenshot</label>
                                <input type="file" name="payment" class="form-control" accept=".jpg,.jpeg,.png" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Note (optional)</label>
                                <textarea name="note" class="form-control" rows="3"></textarea>
                            </div>

                            <button class="btn btn-primary">Submit payment proof</button>
                        </form>

                        <?php if ($payments instanceof mysqli_result && mysqli_num_rows($payments) > 0): ?>
                            <hr class="my-4">
                            <h5 class="fw-bold mb-3">Previous submissions</h5>
                            <div class="d-flex flex-column gap-3">
                                <?php while ($payment = mysqli_fetch_assoc($payments)): ?>
                                    <div class="border rounded p-3">
                                        <div class="d-flex justify-content-between gap-3">
                                            <div>
                                                <div class="fw-semibold text-white">$<?= number_format((float) ($payment['amount'] ?? 0), 2); ?></div>
                                                <div class="small text-secondary"><?= htmlspecialchars($payment['milestone_name'] ?: 'General payment'); ?></div>
                                            </div>
                                            <div class="small text-secondary"><?= ucfirst($payment['verified'] ?? 'pending'); ?></div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php require_once "../includes/footer.php"; ?>
