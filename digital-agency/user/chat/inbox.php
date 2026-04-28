<?php
require_once "../../includes/db.php";
require_once "../../includes/auth.php";

userAuth();

$user_id = $_SESSION[USER_SESSION];
$selected_order_id = isset($_GET['order_id']) ? (int) $_GET['order_id'] : 0;
$error = "";
$success = isset($_GET['created']) ? "Request created. Continue the discussion below." : "";

$orders = mysqli_query(
    $conn,
    "SELECT orders.*, services.title AS service_title
     FROM orders
     JOIN services ON services.id = orders.service_id
     WHERE orders.user_id = $user_id
     ORDER BY orders.id DESC"
);

if ($selected_order_id <= 0 && $orders instanceof mysqli_result && mysqli_num_rows($orders) > 0) {
    $firstOrder = mysqli_fetch_assoc($orders);
    $selected_order_id = (int) $firstOrder['id'];
    mysqli_data_seek($orders, 0);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $selected_order_id = (int) ($_POST['order_id'] ?? 0);
    $message = trim($_POST['message'] ?? '');
    $action = $_POST['action'] ?? 'message';
    $fileName = null;

    if ($selected_order_id <= 0) {
        $error = "Please select an order conversation.";
    } else {
        $orderCheck = mysqli_query(
            $conn,
            "SELECT * FROM orders WHERE id = {$selected_order_id} AND user_id = {$user_id} LIMIT 1"
        );
        $selectedOrder = $orderCheck ? mysqli_fetch_assoc($orderCheck) : null;

        if (!$selectedOrder) {
            $error = "Order not found.";
        } else {
            if (!empty($_FILES['file']['name'])) {
                $targetSubDir = "/chat/";
                $uploadPath = UPLOAD_DIR . $targetSubDir;

                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }

                $ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'zip'];

                if (in_array($ext, $allowed, true)) {
                    $fileName = uniqid("chat_", true) . "." . $ext;
                    if (!move_uploaded_file($_FILES['file']['tmp_name'], $uploadPath . $fileName)) {
                        $error = "Could not upload the attachment.";
                    }
                } else {
                    $error = "Only JPG, JPEG, PNG, and ZIP files are allowed.";
                }
            }

            if ($error === "") {
                if ($action === 'accept_offer') {
                    mysqli_query($conn, "UPDATE orders SET status = 'approved', offer_status = 'accepted' WHERE id = {$selected_order_id} AND user_id = {$user_id}");
                    $message = "Client accepted the offer.";
                } elseif ($action === 'reject_offer') {
                    mysqli_query($conn, "UPDATE orders SET status = 'rejected', offer_status = 'rejected' WHERE id = {$selected_order_id} AND user_id = {$user_id}");
                    $message = "Client rejected the offer.";
                } elseif ($action === 'counter_offer') {
                    $counterPrice = trim($_POST['counter_price'] ?? '');
                    $counterMessage = trim($_POST['counter_message'] ?? '');
                    $message = "Counter offer";
                    if ($counterPrice !== '') {
                        $message .= " $" . number_format((float) $counterPrice, 2);
                    }
                    if ($counterMessage !== '') {
                        $message .= ": " . $counterMessage;
                    }
                    mysqli_query($conn, "UPDATE orders SET offer_status = 'countered', status = 'pending' WHERE id = {$selected_order_id} AND user_id = {$user_id}");
                }

                if ($message !== '' || $fileName !== null) {
                    $stmt = mysqli_prepare(
                        $conn,
                        "INSERT INTO chats (order_id, sender_id, receiver_id, sender_role, message, file)
                         VALUES (?, ?, 0, 'user', ?, ?)"
                    );
                    mysqli_stmt_bind_param($stmt, "iiss", $selected_order_id, $user_id, $message, $fileName);
                    mysqli_stmt_execute($stmt);
                    header("Location: inbox.php?order_id=" . $selected_order_id);
                    exit();
                }
            }
        }
    }
}

$selectedOrder = null;
if ($selected_order_id > 0) {
    $selectedOrderQuery = mysqli_query(
        $conn,
        "SELECT orders.*, services.title AS service_title
         FROM orders
         JOIN services ON services.id = orders.service_id
         WHERE orders.id = {$selected_order_id} AND orders.user_id = {$user_id}
         LIMIT 1"
    );
    $selectedOrder = $selectedOrderQuery ? mysqli_fetch_assoc($selectedOrderQuery) : null;
}

$messages = $selectedOrder
    ? mysqli_query($conn, "SELECT * FROM chats WHERE order_id = {$selected_order_id} ORDER BY created_at ASC")
    : false;
$milestones = $selectedOrder ? decodeMilestones($selectedOrder['milestone_plan'] ?? null) : [];
?>

<?php require_once "../../includes/header.php"; ?>

<div class="premium-wrapper">
    <div class="container py-4 py-md-5">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-9">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="fw-bold m-0">Chat with <span class="text-white"><?= $selectedOrder ? htmlspecialchars($selectedOrder['service_title']) : 'Admin'; ?></span></h3>
                    <a href="<?= BASE_URL ?>/user/dashboard.php" class="btn btn-sm btn-outline-light">← Back</a>
                </div>

                <?php if ($success): ?>
                    <div class="alert alert-success mb-4"><?= htmlspecialchars($success); ?></div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div class="alert alert-danger mb-4"><?= htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <div class="glass-card mb-4 p-4">
                    <form method="GET" class="row g-3 align-items-end">
                        <div class="col-md-8">
                            <label class="form-label text-secondary">Select order</label>
                            <select name="order_id" class="form-select form-control-dark" onchange="this.form.submit()">
                                <option value="">Choose a conversation</option>
                                <?php if ($orders instanceof mysqli_result): ?>
                                    <?php while ($order = mysqli_fetch_assoc($orders)): ?>
                                        <option value="<?= (int) $order['id']; ?>" <?= $selected_order_id === (int) $order['id'] ? 'selected' : ''; ?>>
                                            #<?= str_pad((int) $order['id'], 5, '0', STR_PAD_LEFT); ?> - <?= htmlspecialchars($order['service_title']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <a href="<?= BASE_URL ?>/user/services.php" class="btn btn-primary w-100">New request</a>
                        </div>
                    </form>
                </div>

                <?php if ($selectedOrder): ?>
                    <div class="glass-card mb-4 p-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="small text-secondary text-uppercase">Order</div>
                                <div class="fw-bold text-white">#<?= (int) $selectedOrder['id']; ?></div>
                            </div>
                            <div class="col-md-4">
                                <div class="small text-secondary text-uppercase">Status</div>
                                <div class="fw-bold text-white"><?= ucfirst($selectedOrder['offer_status']); ?></div>
                            </div>
                            <div class="col-md-4">
                                <div class="small text-secondary text-uppercase">Current offer</div>
                                <div class="fw-bold text-white">
                                    <?= !empty($selectedOrder['offered_price']) ? '$' . number_format((float) $selectedOrder['offered_price'], 2) : 'Waiting for admin'; ?>
                                </div>
                                <div class="small text-secondary">
                                    <?= !empty($selectedOrder['payment_mode']) ? htmlspecialchars(formatPaymentMode($selectedOrder['payment_mode'])) : 'No payment mode yet'; ?>
                                </div>
                            </div>
                        </div>

                        <?php if (!empty($milestones)): ?>
                            <div class="row g-2 mt-2">
                                <?php foreach ($milestones as $milestone): ?>
                                    <div class="col-md-6">
                                        <div class="border rounded p-2">
                                            <div class="fw-semibold text-white"><?= htmlspecialchars($milestone['title'] ?? 'Milestone'); ?></div>
                                            <div class="small text-secondary">$<?= number_format((float) ($milestone['amount'] ?? 0), 2); ?></div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <?php if (($selectedOrder['offer_status'] ?? '') === 'accepted'): ?>
                            <div class="mt-3">
                                <a href="../pay-order.php?id=<?= (int) $selectedOrder['id']; ?>" class="btn btn-primary">Upload payment proof</a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if ($selectedOrder && ($selectedOrder['offer_status'] ?? '') === 'offered'): ?>
                    <div class="glass-card mb-4 p-4">
                        <div class="row g-2">
                            <div class="col-md-4">
                                <form method="POST">
                                    <input type="hidden" name="order_id" value="<?= (int) $selectedOrder['id']; ?>">
                                    <input type="hidden" name="action" value="accept_offer">
                                    <button type="submit" class="btn btn-success w-100">Accept offer</button>
                                </form>
                            </div>
                            <div class="col-md-4">
                                <form method="POST">
                                    <input type="hidden" name="order_id" value="<?= (int) $selectedOrder['id']; ?>">
                                    <input type="hidden" name="action" value="reject_offer">
                                    <button type="submit" class="btn btn-danger w-100">Reject offer</button>
                                </form>
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-outline-light w-100" type="button" data-bs-toggle="collapse" data-bs-target="#counterOfferBox">Counter offer</button>
                            </div>
                        </div>

                        <div class="collapse mt-3" id="counterOfferBox">
                            <div class="border rounded p-3">
                                <form method="POST">
                                    <input type="hidden" name="order_id" value="<?= (int) $selectedOrder['id']; ?>">
                                    <input type="hidden" name="action" value="counter_offer">
                                    <div class="mb-2">
                                        <label class="form-label">Your counter price</label>
                                        <input type="number" step="0.01" min="0" name="counter_price" class="form-control form-control-dark">
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">Message</label>
                                        <textarea name="counter_message" class="form-control form-control-dark" rows="3"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Send counter</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="text-secondary glass-card mb-4 chat-container p-4" style="height: 500px; overflow-y: auto; display: flex; flex-direction: column; gap: 15px; border-top: 4px solid #6366f1;">
                    <?php if ($messages && mysqli_num_rows($messages) > 0): ?>
                        <?php while ($msg = mysqli_fetch_assoc($messages)): ?>
                            <?php $isUser = ($msg['sender_role'] === 'user'); ?>
                            <div class="d-flex <?= $isUser ? 'justify-content-end' : 'justify-content-start'; ?>">
                                <div style="max-width: 70%; padding: 12px 18px; border-radius: 16px; <?= $isUser ? 'background: linear-gradient(135deg, #4f46e5, #7c3aed); color: white; border-bottom-right-radius: 4px;' : 'background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); color: #e2e8f0; border-bottom-left-radius: 4px;'; ?>">
                                    <div style="font-size: 0.75rem; opacity: 0.8; margin-bottom: 5px; font-weight: 600; text-transform: uppercase;">
                                        <?= $isUser ? 'You' : 'Admin'; ?>
                                    </div>

                                    <?php if (!empty($msg['message'])): ?>
                                        <div style="line-height: 1.5; font-size: 0.95rem; white-space: pre-wrap; word-break: break-word;"><?= htmlspecialchars($msg['message']); ?></div>
                                    <?php endif; ?>

                                    <?php if (!empty($msg['file'])): ?>
                                        <?php $ext = strtolower(pathinfo($msg['file'], PATHINFO_EXTENSION)); ?>
                                        <?php if (in_array($ext, ['jpg','jpeg','png'], true)): ?>
                                            <img src="../../uploads/chat/<?php echo $msg['file']; ?>" class="img-fluid mt-2 rounded" style="max-width: 220px; border: 2px solid rgba(255,255,255,0.2);">
                                        <?php else: ?>
                                            <a href="../../uploads/chat/<?php echo $msg['file']; ?>" class="btn btn-sm mt-2" style="background: rgba(255,255,255,0.2); color: white; border: none;" download>
                                                Download <?= strtoupper($ext) ?>
                                            </a>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                    <div class="text-end mt-1" style="font-size: 0.7rem; opacity: 0.6;">
                                        <?= date("H:i", strtotime($msg['created_at'])); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="h-100 d-flex flex-column align-items-center justify-content-center text-secondary">
                            <div style="font-size: 3rem; margin-bottom: 10px;">💬</div>
                            <p>No messages yet. Start the conversation!</p>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="text-secondary glass-card p-3">
                    <form method="POST" enctype="multipart/form-data" class="d-flex gap-3 align-items-center">
                        <input type="hidden" name="order_id" value="<?= (int) $selected_order_id; ?>">
                        <input type="hidden" name="action" value="message">

                        <input type="text" name="message" class="text-secondary form-control form-control-dark py-2"
                               placeholder="Type your message here..." style="border-radius: 20px;" <?= $selected_order_id <= 0 ? 'disabled' : ''; ?>>

                        <label class="btn btn-sm mb-0 d-flex align-items-center justify-content-center" style="background: rgba(100, 116, 139, 0.1); border: 1px solid rgba(100, 116, 139, 0.3); color: #cbd5e1; border-radius: 50%; width: 40px; height: 40px; cursor: pointer;" title="Attach File">
                            📎 <input type="file" name="file" class="d-none" accept=".jpg,.jpeg,.png,.zip" <?= $selected_order_id <= 0 ? 'disabled' : ''; ?>>
                        </label>

                        <button class="btn px-4 py-2" style="background: linear-gradient(135deg, #6366f1, #8b5cf6); color: white; border-radius: 20px; font-weight: bold; border: none;" <?= $selected_order_id <= 0 ? 'disabled' : ''; ?>>
                            Send
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const chatContainer = document.querySelector('.chat-container');
    if (chatContainer) {
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= BASE_URL ?>/assets/js/guest.js"></script>
</body>
</html>
