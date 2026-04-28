<?php
require_once "../../includes/db.php";
require_once "../../includes/auth.php";

adminAuth();

$order_id = isset($_GET['order_id']) ? (int) $_GET['order_id'] : (int) ($_POST['order_id'] ?? 0);
if ($order_id <= 0) {
    header("Location: inbox.php");
    exit();
}

// Fetch order and user info
$stmt = mysqli_prepare(
    $conn,
    "SELECT orders.*, users.name, services.title AS service_title
     FROM orders
     JOIN users ON users.id = orders.user_id
     JOIN services ON services.id = orders.service_id
     WHERE orders.id = ?"
);
mysqli_stmt_bind_param($stmt, "i", $order_id);
mysqli_stmt_execute($stmt);
$order = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$order) {
    die("Conversation not found.");
}

$user_id = (int) $order['user_id'];
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $message  = trim($_POST['message'] ?? '');
    $fileName = null;
    $sendOffer = isset($_POST['send_offer']);

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
                $error = "Attachment upload failed.";
            }
        }
    }

    if ($error === "" && $sendOffer) {
        $offerPrice = (float) ($_POST['offer_price'] ?? 0);
        $paymentMode = $_POST['payment_mode'] ?? 'full';
        $milestoneTitles = $_POST['milestone_title'] ?? [];
        $milestoneAmounts = $_POST['milestone_amount'] ?? [];
        $milestones = [];

        if ($offerPrice <= 0) {
            $error = "Offer price must be greater than zero.";
        } elseif (!in_array($paymentMode, ['full', 'milestone'], true)) {
            $error = "Invalid payment mode.";
        } elseif ($paymentMode === 'milestone') {
            $totalMilestoneAmount = 0;
            foreach ($milestoneTitles as $index => $title) {
                $title = trim((string) $title);
                $amount = (float) ($milestoneAmounts[$index] ?? 0);
                if ($title === '' || $amount <= 0) {
                    continue;
                }
                $milestones[] = [
                    'title' => $title,
                    'amount' => $amount,
                ];
                $totalMilestoneAmount += $amount;
            }

            if (count($milestones) === 0) {
                $error = "Add at least one valid milestone.";
            } elseif (abs($totalMilestoneAmount - $offerPrice) > 0.01) {
                $error = "Milestone totals must match the offered price.";
            }
        }

        if ($error === "") {
            $milestoneJson = $paymentMode === 'milestone' ? json_encode($milestones) : null;
            $updateStmt = mysqli_prepare(
                $conn,
                "UPDATE orders
                 SET offered_price = ?, payment_mode = ?, milestone_plan = ?, offer_status = 'offered', status = 'pending'
                 WHERE id = ?"
            );
            mysqli_stmt_bind_param($updateStmt, "dssi", $offerPrice, $paymentMode, $milestoneJson, $order_id);
            mysqli_stmt_execute($updateStmt);

            $offerSummary = "Admin sent a " . ($paymentMode === 'milestone' ? 'milestone' : 'full payment') . " offer for $" . number_format($offerPrice, 2) . ".";
            if ($message !== '') {
                $offerSummary .= "\n" . $message;
            }
            $message = $offerSummary;
        }
    }

    if ($error === "" && ($message !== '' || $fileName !== null)) {
        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO chats (order_id, sender_id, receiver_id, sender_role, message, file)
             VALUES (?, ?, ?, 'admin', ?, ?)"
        );
        $adminId = (int) $_SESSION[ADMIN_SESSION];
        mysqli_stmt_bind_param($stmt, "iiiss", $order_id, $adminId, $user_id, $message, $fileName);
        mysqli_stmt_execute($stmt);
        header("Location: send.php?order_id=" . $order_id);
        exit();
    }
}

// Fetch conversation
$messages = mysqli_query(
    $conn,
    "SELECT * FROM chats
     WHERE order_id = $order_id
     ORDER BY created_at ASC"
);
$milestones = decodeMilestones($order['milestone_plan'] ?? null);
?>


<?php require_once "../../includes/admin_sidebar.php"; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold m-0" style="font-family: 'Outfit', sans-serif;">Chat with <span style="background: linear-gradient(135deg, #60a5fa, #c084fc); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"><?php echo htmlspecialchars($order['name']); ?></span></h3>
    <a href="inbox.php" class="btn btn-sm" style="background: rgba(100, 116, 139, 0.1); border: 1px solid rgba(100, 116, 139, 0.3); color: #cbd5e1; border-radius: 6px;">← Back</a>
</div>

<?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error); ?></div>
<?php endif; ?>

<div class="glass-card mb-4 p-4">
    <div class="row g-3">
        <div class="col-md-4">
            <div class="small text-secondary text-uppercase">Order</div>
            <div class="fw-bold text-white">#<?= (int) $order['id']; ?></div>
        </div>
        <div class="col-md-4">
            <div class="small text-secondary text-uppercase">Service</div>
            <div class="fw-bold text-white"><?= htmlspecialchars($order['service_title']); ?></div>
        </div>
        <div class="col-md-4">
            <div class="small text-secondary text-uppercase">Current offer</div>
            <div class="fw-bold text-white">
                <?= !empty($order['offered_price']) ? '$' . number_format((float) $order['offered_price'], 2) : 'Not sent yet'; ?>
            </div>
            <div class="small text-secondary"><?= ucfirst($order['offer_status']); ?><?= !empty($order['payment_mode']) ? ' · ' . htmlspecialchars(formatPaymentMode($order['payment_mode'])) : ''; ?></div>
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
</div>

<div class="text-secondary glass-card mb-4 chat-container p-4 font-weight-bold" style="height: 500px; overflow-y: auto; display: flex; flex-direction: column; gap: 15px; border-top: 4px solid #6366f1;">

    <?php if (mysqli_num_rows($messages) > 0): ?>
        <?php while ($msg = mysqli_fetch_assoc($messages)): ?>
            
            <?php $isAdmin = ($msg['sender_role'] === 'admin'); ?>
            
            <div class="d-flex <?php echo $isAdmin ? 'justify-content-end' : 'justify-content-start'; ?>">
                <div class="text-secondary" <?php echo $isAdmin ? 'chat-bubble-admin' : 'chat-bubble-user'; ?>" 
                     style="max-width: 70%; padding: 12px 18px; border-radius: 16px; <?php echo $isAdmin ? 'background: linear-gradient(135deg, #4f46e5, #7c3aed); color: white; border-bottom-right-radius: 4px;' : 'background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); color: #e2e8f0; border-bottom-left-radius: 4px;'; ?>">
                    
                    <div style="text-secondary font-size: 0.75rem; opacity: 0.8; margin-bottom: 5px; font-weight: 600; text-transform: uppercase;">
                        <?php echo $isAdmin ? 'You' : htmlspecialchars($order['name']); ?>
                    </div>

                    <?php if (!empty($msg['message'])): ?>
                        <div style="line-height: 1.5; font-size: 0.95rem;"><?php echo nl2br(htmlspecialchars($msg['message'])); ?></div>
                    <?php endif; ?>

                    <?php if (!empty($msg['file'])): ?>
                        <?php $ext = pathinfo($msg['file'], PATHINFO_EXTENSION); ?>

                        <?php if (in_array($ext, ['jpg','jpeg','png'])): ?>
                            <img src="../../uploads/chat/<?php echo $msg['file']; ?>"
                                 class="img-fluid mt-2 rounded"
                                 style="max-width: 200px; border: 2px solid rgba(255,255,255,0.2);">
                        <?php else: ?>
                            <a href="../../uploads/chat/<?php echo $msg['file']; ?>"
                               class="btn btn-sm mt-2"
                               style="background: rgba(255,255,255,0.2); color: white; border: none;"
                               download>
                                📦 Download ZIP
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>

                    <div class="text-end mt-1" style="font-size: 0.7rem; opacity: 0.6;">
                        <?php echo date("H:i", strtotime($msg['created_at'])); ?>
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
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="order_id" value="<?= (int) $order_id; ?>">

        <div class="row g-3">
            <div class="col-12">
                <textarea name="message" class="text-secondary form-control form-control-dark py-2"
                          placeholder="Type your message here..." style="border-radius: 20px;" rows="3"></textarea>
            </div>

            <div class="col-md-4">
                <label class="form-label small text-uppercase text-secondary">Attach file</label>
                <input type="file" name="file" class="form-control form-control-dark">
            </div>

            <div class="col-md-4">
                <label class="form-label small text-uppercase text-secondary">Offer price</label>
                <input type="number" step="0.01" min="0" name="offer_price" class="form-control form-control-dark" value="<?= !empty($order['offered_price']) ? htmlspecialchars((string) $order['offered_price']) : ''; ?>">
            </div>

            <div class="col-md-4">
                <label class="form-label small text-uppercase text-secondary">Payment mode</label>
                <select name="payment_mode" class="form-select form-control-dark">
                    <option value="full" <?= ($order['payment_mode'] ?? '') !== 'milestone' ? 'selected' : ''; ?>>Full payment</option>
                    <option value="milestone" <?= ($order['payment_mode'] ?? '') === 'milestone' ? 'selected' : ''; ?>>Milestones</option>
                </select>
            </div>

            <div class="col-12">
                <label class="form-label small text-uppercase text-secondary">Milestones</label>
                <div class="row g-2">
                    <?php for ($i = 0; $i < 3; $i++): ?>
                        <div class="col-md-8">
                            <input type="text" name="milestone_title[]" class="form-control form-control-dark" placeholder="Milestone title" value="<?= htmlspecialchars($milestones[$i]['title'] ?? ''); ?>">
                        </div>
                        <div class="col-md-4">
                            <input type="number" step="0.01" min="0" name="milestone_amount[]" class="form-control form-control-dark" placeholder="Amount" value="<?= htmlspecialchars(isset($milestones[$i]['amount']) ? (string) $milestones[$i]['amount'] : ''); ?>">
                        </div>
                    <?php endfor; ?>
                </div>
                <small class="text-secondary d-block mt-2">Leave milestone fields empty if you are sending a simple message.</small>
            </div>

            <div class="col-12 d-flex gap-2 justify-content-end">
                <button class="btn px-4 py-2" style="background: linear-gradient(135deg, #6366f1, #8b5cf6); color: white; border-radius: 20px; font-weight: bold; border: none;">
                    Send message
                </button>
                <button type="submit" name="send_offer" value="1" class="btn px-4 py-2" style="background: linear-gradient(135deg, #10b981, #059669); color: white; border-radius: 20px; font-weight: bold; border: none;">
                    Send / update offer
                </button>
            </div>
        </div>
    </form>
</div>

        </div> <!-- End p-4 wrapper from sidebar -->
    </main> <!-- End admin-content -->
</div> <!-- End admin-wrapper -->
<script>
    // Scroll chat to bottom
    const chatContainer = document.querySelector('.chat-container');
    if(chatContainer) {
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }
</script>
</body>
</html>

