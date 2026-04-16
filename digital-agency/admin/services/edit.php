<?php
require_once "../../includes/db.php";
require_once "../../includes/auth.php";

adminAuth();

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: list.php");
    exit();
}

// Fetch service
$stmt = mysqli_prepare($conn, "SELECT * FROM services WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$service = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$service) {
    die("Service not found.");
}

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = trim($_POST['title'] ?? '');
    $short_desc = trim($_POST['short_description'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $status = $_POST['status'] ?? 'active';

    // Validation
    if ($title === '' || $short_desc === '' || $price <= 0) {
        $error = "All fields are required and price must be greater than 0.";
    } else {

        $imageName = $service['image'];

        // Image upload (optional)
        if (!empty($_FILES['image']['name'])) {

            $targetSubDir = "/services/";
            $uploadPath = UPLOAD_DIR . $targetSubDir;

            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png'];

            if (!in_array($ext, $allowed)) {
                $error = "Only JPG, JPEG, PNG images are allowed.";
            } else {

                // Remove old image
                if ($imageName && file_exists($uploadPath . $imageName)) {
                    unlink($uploadPath . $imageName);
                }

                $imageName = uniqid("service_", true) . "." . $ext;
                if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath . $imageName)) {
                    $error = "Failed to move uploaded file. Check folder permissions.";
                }
            }
        }

        // Update service
        if ($error === "") {

            $update = mysqli_prepare(
                $conn,
                "UPDATE services
                 SET title = ?, short_description = ?, image = ?, price = ?, status = ?
                 WHERE id = ?"
            );
            mysqli_stmt_bind_param(
                $update,
                "sssdsi",
                $title,
                $short_desc,
                $imageName,
                $price,
                $status,
                $id
            );
            mysqli_stmt_execute($update);

            $success = "Service updated successfully.";

            // Refresh data
            $service['title'] = $title;
            $service['short_description'] = $short_desc;
            $service['price'] = $price;
            $service['status'] = $status;
            $service['image'] = $imageName;
        }
    }
}
?>

<?php require_once "../../includes/admin_sidebar.php"; ?>

<div class="d-flex justify-content-between align-items-center mb-5">
    <h3 class="fw-bold m-0" style="font-family: 'Outfit', sans-serif;">Edit <span style="background: linear-gradient(135deg, #60a5fa, #c084fc); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Service</span></h3>
    <a href="list.php" class="btn btn-sm" style="background: rgba(100, 116, 139, 0.1); border: 1px solid rgba(100, 116, 139, 0.3); color: #cbd5e1; border-radius: 6px;">← Back to Services</a>
</div>

<?php if ($error): ?>
    <div class="alert alert-danger alert-premium text-center" style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: #fca5a5;"><?php echo $error; ?></div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="alert alert-success alert-premium text-center" style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); color: #6ee7b7;"><?php echo $success; ?></div>
<?php endif; ?>

<div class="glass-card" style="border-top: 4px solid #6366f1;">
    <div class="card-body p-4 p-md-5">

        <form method="POST" enctype="multipart/form-data">

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="mb-4">
                        <label class="form-label text-secondary fw-medium small text-uppercase" style="letter-spacing: 1px;">Service Title</label>
                        <input type="text" name="title"
                               value="<?php echo htmlspecialchars($service['title']); ?>"
                               class="form-control form-control-dark py-2" required>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-4">
                        <label class="form-label text-secondary fw-medium small text-uppercase" style="letter-spacing: 1px;">Status</label>
                        <select name="status" class="form-control form-control-dark py-2">
                            <option value="active" <?php if ($service['status']=='active') echo "selected"; ?>>
                                Active
                            </option>
                            <option value="inactive" <?php if ($service['status']=='inactive') echo "selected"; ?>>
                                Inactive
                            </option>
                        </select>
                    </div>
                </div>

                <div class="col-12">
                    <div class="mb-4">
                        <label class="form-label text-secondary fw-medium small text-uppercase" style="letter-spacing: 1px;">Short Description</label>
                        <textarea name="short_description" rows="4"
                                  class="form-control form-control-dark" required><?php
                            echo htmlspecialchars($service['short_description']);
                        ?></textarea>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-4">
                        <label class="form-label text-secondary fw-medium small text-uppercase" style="letter-spacing: 1px;">Service Price ($)</label>
                        <input type="number" step="0.01" name="price"
                               value="<?php echo htmlspecialchars($service['price']); ?>"
                               class="form-control form-control-dark py-2" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-4">
                        <label class="form-label text-secondary fw-medium small text-uppercase" style="letter-spacing: 1px;">Change Image (optional)</label>
                        <input type="file" name="image" class="form-control form-control-dark py-2">
                    </div>
                    <?php if ($service['image']): ?>
                        <div class="mb-3 p-3 rounded" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05);">
                            <label class="form-label text-secondary fw-medium small text-uppercase" style="letter-spacing: 1px;">Current Image</label><br>
                            <img src="../../uploads/services/<?php echo $service['image']; ?>"
                                 class="rounded" style="max-height: 100px; border: 1px solid rgba(255,255,255,0.1);">
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="text-end mt-4">
                <button class="btn px-5 py-3 fw-bold" style="background: linear-gradient(135deg, #6366f1, #8b5cf6); color: white; border-radius: 8px; border: none; box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4); text-transform: uppercase; letter-spacing: 1px;">Update Service</button>
            </div>

        </form>

    </div>
</div>

        </div> <!-- End p-4 wrapper from sidebar -->
    </main> <!-- End admin-content -->
</div> <!-- End admin-wrapper -->
</body>
</html>

