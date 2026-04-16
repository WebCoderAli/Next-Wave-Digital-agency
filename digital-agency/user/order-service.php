<?php
require_once "../includes/db.php";
require_once "../includes/auth.php";

userAuth();

$user_id = $_SESSION[USER_SESSION];
$service_id = $_GET['id'] ?? null;

if (!$service_id) {
    header("Location: services.php");
    exit();
}

// Fetch service
$stmt = mysqli_prepare($conn, "SELECT * FROM services WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $service_id);
mysqli_stmt_execute($stmt);
$service = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$service) {
    die("Service not found.");
}

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $short_desc = trim($_POST['short_desc'] ?? '');
    $detailed_desc = trim($_POST['detailed_desc'] ?? '');

    if ($short_desc === '' || $detailed_desc === '') {
        $error = "All fields are required.";
    } elseif (empty($_FILES['payment']['name'])) {
        $error = "Payment screenshot is required.";
    } else {

        // Upload payment screenshot
        $ext = strtolower(pathinfo($_FILES['payment']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png'];

        if (!in_array($ext, $allowed)) {
            $error = "Only JPG, JPEG, PNG files are allowed.";
        } else {

            $targetSubDir = "/payments/";
            $uploadPath = UPLOAD_DIR . $targetSubDir;

            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            $fileName = uniqid("payment_", true) . "." . $ext;
            if (!move_uploaded_file($_FILES['payment']['tmp_name'], $uploadPath . $fileName)) {
                $error = "Failed to upload payment screenshot. Check folder permissions.";
            }

            // Create order
            $orderStmt = mysqli_prepare(
                $conn,
                "INSERT INTO orders (user_id, service_id, short_desc, detailed_desc)
                 VALUES (?, ?, ?, ?)"
            );
            mysqli_stmt_bind_param(
                $orderStmt,
                "iiss",
                $user_id,
                $service_id,
                $short_desc,
                $detailed_desc
            );
            mysqli_stmt_execute($orderStmt);

            $order_id = mysqli_insert_id($conn);

            // Save payment
            $payStmt = mysqli_prepare(
                $conn,
                "INSERT INTO payments (order_id, screenshot)
                 VALUES (?, ?)"
            );
            mysqli_stmt_bind_param($payStmt, "is", $order_id, $fileName);
            mysqli_stmt_execute($payStmt);

            $success = "Order placed successfully. Waiting for admin approval.";
        }
    }
}
?>

<?php require_once "../includes/header.php"; ?>

<h3 class="fw-bold mb-3">
    Order Service: <?php echo htmlspecialchars($service['title']); ?>
</h3>

<!-- PRICE DISPLAY -->
<div class="alert alert-info mb-4">
    <strong>Service Price:</strong>
    $<?php echo number_format($service['price'], 2); ?>
</div>

<?php if ($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-body">

        <form method="POST" enctype="multipart/form-data">

            <div class="mb-3">
                <label>Short Description</label>
                <input type="text" name="short_desc"
                       class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Detailed Requirements</label>
                <textarea name="detailed_desc"
                          class="form-control" rows="5" required></textarea>
            </div>

            <div class="mb-3">
                <label>Payment Screenshot</label>
                <input type="file" name="payment"
                       class="form-control" required>
                <small class="text-muted">
                    Upload proof of payment for the above amount.
                </small>
            </div>

            <button class="btn btn-dark">Place Order</button>
            <a href="services.php" class="btn btn-secondary">Back</a>

        </form>

    </div>
</div>

<?php require_once "../includes/footer.php"; ?>
