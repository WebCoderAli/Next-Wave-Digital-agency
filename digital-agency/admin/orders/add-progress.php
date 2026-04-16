<?php
require_once "../../includes/db.php";
require_once "../../includes/auth.php";

adminAuth();

/* ===============================
   Validate Order
================================ */
if (!isset($_GET['order_id']) || !is_numeric($_GET['order_id'])) {
    die("Invalid Order ID");
}
$order_id = (int)$_GET['order_id'];

$success = "";
$error   = "";

/* ===============================
   Fetch Order Details
================================ */
$orderQuery = mysqli_query(
    $conn,
    "SELECT orders.id, users.name AS user_name, services.title AS service_title
     FROM orders
     JOIN users ON users.id = orders.user_id
     JOIN services ON services.id = orders.service_id
     WHERE orders.id = $order_id
     LIMIT 1"
);

if (mysqli_num_rows($orderQuery) === 0) {
    die("Order not found.");
}

$order = mysqli_fetch_assoc($orderQuery);

/* ===============================
   Handle Submit
================================ */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $progress = (int)($_POST['progress_percent'] ?? 0);
    $message  = trim($_POST['message'] ?? '');
    $fileName = null;

    // Validation
    if ($progress < 0 || $progress > 100) {
        $error = "Progress must be between 0 and 100.";
    }
    elseif ($message === "") {
        $error = "Progress description is required.";
    }
    elseif ($progress === 100 && empty($_FILES['file']['name'])) {
        $error = "Final delivery file is required at 100% progress.";
    }
    else {

        /* ===============================
           File Upload
        ================================ */
        if (!empty($_FILES['file']['name'])) {

            $targetSubDir = "/order-progress/";
            $uploadPath = UPLOAD_DIR . $targetSubDir;

            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            $allowedExt = ['jpg','jpeg','png','gif','pdf','zip'];
            $ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));

            if (!in_array($ext, $allowedExt)) {
                $error = "Invalid file type. Allowed: JPG, PNG, GIF, PDF, ZIP.";
            } else {
                $fileName = uniqid("delivery_") . "." . $ext;

                if (!move_uploaded_file($_FILES['file']['tmp_name'], $uploadPath . $fileName)) {
                    $error = "File upload failed. Check folder permissions.";
                }
            }
        }

        /* ===============================
           Save Progress
        ================================ */
        if (!$error) {

            $stmt = mysqli_prepare(
                $conn,
                "INSERT INTO order_progress 
                 (order_id, progress_percent, message, file)
                 VALUES (?, ?, ?, ?)"
            );
            mysqli_stmt_bind_param(
                $stmt,
                "iiss",
                $order_id,
                $progress,
                $message,
                $fileName
            );
            mysqli_stmt_execute($stmt);

            // Update main order
            mysqli_query(
                $conn,
                "UPDATE orders SET progress_percent = $progress WHERE id = $order_id"
            );

            // Complete order if 100%
            if ($progress === 100) {
                mysqli_query(
                    $conn,
                    "UPDATE orders
                     SET status = 'completed', completed_at = NOW()
                     WHERE id = $order_id"
                );
            }

            $success = "Order progress updated successfully.";
        }
    }
}
?>

<?php require_once "../../includes/admin_sidebar.php"; ?>

<!-- ================= HEADER ================= -->
<div class="d-flex justify-content-between align-items-center mb-5">
    <div>
        <h3 class="fw-bold m-0" style="font-family: 'Outfit', sans-serif;">Update <span style="background: linear-gradient(135deg, #34d399, #10b981); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Progress</span></h3>
        <p class="text-secondary mt-2 mb-0" style="font-size: 0.9rem;">
            <strong class="fw-bold fs-6">Service:</strong> <?= htmlspecialchars($order['service_title']); ?> |
            <strong class="fw-bold fs-6 ms-2">Client:</strong> <?= htmlspecialchars($order['user_name']); ?>
        </p>
    </div>
    <a href="list.php" class="btn btn-sm" style="background: rgba(100, 116, 139, 0.1); border: 1px solid rgba(100, 116, 139, 0.3); color: #cbd5e1; border-radius: 6px;">← Back to Orders</a>
</div>

<!-- ================= FORM ================= -->
<div class="glass-card" style="border-top: 4px solid #10b981;">
    <div class="card-body p-4 p-md-5">

        <?php if ($success): ?>
            <div class="alert alert-success alert-premium text-center" style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); color: #6ee7b7;"><?= $success ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-danger alert-premium text-center" style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: #fca5a5;"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">

            <div class="row g-4">
                <!-- Progress -->
                <div class="col-md-6">
                    <div class="mb-4">
                        <label class="form-label text-secondary fw-medium small text-uppercase" style="letter-spacing: 1px;">Progress Level</label>
                        <select name="progress_percent" class="form-select form-control-dark py-2" required>
                            <option value="">Select progress</option>
                            <option value="10">10% – Order Received</option>
                            <option value="25">25% – Work Started</option>
                            <option value="50">50% – In Progress</option>
                            <option value="75">75% – Almost Done</option>
                            <option value="100">100% – Final Delivery</option>
                        </select>
                    </div>
                </div>

                <!-- File -->
                <div class="col-md-6">
                    <div class="mb-4">
                        <label class="form-label text-secondary fw-medium small text-uppercase" style="letter-spacing: 1px;">
                            Upload File <span class="text-muted" style="text-transform: none;">(Required at 100%)</span>
                        </label>
                        <input type="file" name="file" class="form-control form-control-dark py-2">
                        <small class="text-secondary mt-2 d-block">
                            JPG, PNG, GIF, PDF, ZIP supported
                        </small>
                    </div>
                </div>
                
                <!-- Message -->
                <div class="col-12">
                    <div class="mb-4">
                        <label class="form-label text-secondary fw-medium small text-uppercase" style="letter-spacing: 1px;">Progress Description</label>
                        <textarea name="message"
                                  class="form-control form-control-dark"
                                  rows="6"
                                  placeholder="Describe progress for the client..."
                                  required></textarea>
                    </div>
                </div>

                <!-- Actions -->
                <div class="col-12 text-end mt-4">
                    <button class="btn px-5 py-3 fw-bold" style="background: linear-gradient(135deg, #10b981, #059669); color: white; border-radius: 8px; border: none; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4); text-transform: uppercase; letter-spacing: 1px;">
                        Save Progress
                    </button>
                </div>
            </div>

        </form>

    </div>
</div>

        </div> <!-- End p-4 wrapper from sidebar -->
    </main> <!-- End admin-content -->
</div> <!-- End admin-wrapper -->
</body>
</html>
