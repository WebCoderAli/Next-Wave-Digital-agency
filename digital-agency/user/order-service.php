<?php
require_once "../includes/db.php";
require_once "../includes/auth.php";

userAuth();

$user_id = $_SESSION[USER_SESSION];
$service_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($service_id <= 0) {
    header("Location: services.php");
    exit();
}

$stmt = mysqli_prepare($conn, "SELECT * FROM services WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $service_id);
mysqli_stmt_execute($stmt);
$service = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$service) {
    die("Service not found.");
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $short_desc = trim($_POST['short_desc'] ?? '');
    $detailed_desc = trim($_POST['detailed_desc'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $client_budget_raw = trim($_POST['client_budget'] ?? '');
    $client_budget = $client_budget_raw !== '' ? (float) $client_budget_raw : null;

    if ($short_desc === '' || $detailed_desc === '' || $message === '') {
        $error = "Please fill in the project summary, requirements, and first message.";
    } elseif ($client_budget !== null && $client_budget < 0) {
        $error = "Budget must be a valid positive amount.";
    } else {
        $orderStmt = mysqli_prepare(
            $conn,
            "INSERT INTO orders (user_id, service_id, short_desc, detailed_desc, client_budget, offer_status)
             VALUES (?, ?, ?, ?, ?, 'waiting')"
        );
        mysqli_stmt_bind_param(
            $orderStmt,
            "iissd",
            $user_id,
            $service_id,
            $short_desc,
            $detailed_desc,
            $client_budget
        );

        if (mysqli_stmt_execute($orderStmt)) {
            $order_id = mysqli_insert_id($conn);

            $chatStmt = mysqli_prepare(
                $conn,
                "INSERT INTO chats (order_id, sender_id, receiver_id, sender_role, message, file)
                 VALUES (?, ?, 0, 'user', ?, NULL)"
            );
            mysqli_stmt_bind_param($chatStmt, "iis", $order_id, $user_id, $message);
            mysqli_stmt_execute($chatStmt);

            header("Location: chat/inbox.php?order_id=" . $order_id . "&created=1");
            exit();
        }

        $error = "Unable to create your request right now.";
    }
}
?>

<?php require_once "../includes/header.php"; ?>

<div class="premium-wrapper">
    <section class="premium-section py-4 py-md-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="glass-card p-4 h-100">
                        <div class="text-secondary small text-uppercase mb-2">Selected service</div>
                        <h3 class="fw-bold mb-2"><?php echo htmlspecialchars($service['title']); ?></h3>
                        <p class="text-muted mb-4"><?php echo htmlspecialchars($service['short_description']); ?></p>

                        <div class="mb-4">
                            <div class="text-secondary small text-uppercase">Starting price</div>
                            <div class="display-6 fw-bold text-white">$<?php echo number_format($service['price'], 2); ?></div>
                        </div>

                        <div class="alert alert-info mb-0">
                            Start with your project details and chat message. The admin will review your request and send a custom full-price or milestone offer.
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="glass-card p-4 p-md-5">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div>
                                <h3 class="fw-bold mb-1">Request a custom offer</h3>
                                <p class="text-secondary mb-0">Describe the work first, then continue the conversation in chat.</p>
                            </div>
                            <a href="services.php" class="btn btn-outline-light btn-sm">Back</a>
                        </div>

                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Project title</label>
                                <input type="text" name="short_desc" class="form-control" required value="<?php echo htmlspecialchars($_POST['short_desc'] ?? ''); ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Detailed requirements</label>
                                <textarea name="detailed_desc" class="form-control" rows="5" required><?php echo htmlspecialchars($_POST['detailed_desc'] ?? ''); ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Expected budget (optional)</label>
                                <input type="number" step="0.01" min="0" name="client_budget" class="form-control" placeholder="Example: 250" value="<?php echo htmlspecialchars($_POST['client_budget'] ?? ''); ?>">
                                <small class="text-muted">This helps the admin send a better offer.</small>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">First message to admin</label>
                                <textarea name="message" class="form-control" rows="4" placeholder="Share timeline, goals, references, or ask for milestones." required><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
                            </div>

                            <button class="btn btn-primary">Start chat and request offer</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php require_once "../includes/footer.php"; ?>
