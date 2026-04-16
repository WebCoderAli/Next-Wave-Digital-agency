<?php
require_once '../../includes/auth.php';
require_once '../../includes/db.php';

adminAuth();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name     = trim($_POST['name']);
    $position = trim($_POST['position']);
    $bio      = trim($_POST['bio']);
    $status   = $_POST['status'];

    if (empty($name) || empty($position)) {
        $error = "Name and Position are required.";
    } else {

        $imageName = null;

        // Handle image upload
        if (!empty($_FILES['image']['name'])) {

            $targetSubDir = "/profiles/";
            $uploadPath = UPLOAD_DIR . $targetSubDir;

            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            $allowedTypes = ['image/jpeg','image/png','image/jpg','image/webp'];
            $fileType = $_FILES['image']['type'];

            if (!in_array($fileType, $allowedTypes)) {
                $error = "Only JPG, PNG, WEBP images allowed.";
            } else {

                $imageName = time() . "_" . basename($_FILES['image']['name']);
                if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath . $imageName)) {
                    $error = "Failed to move uploaded file. Check folder permissions.";
                }
            }
        }

        if (!isset($error)) {

            $stmt = $conn->prepare("
                INSERT INTO team_members (name, position, bio, image, status)
                VALUES (?, ?, ?, ?, ?)
            ");

            $stmt->bind_param("sssss", $name, $position, $bio, $imageName, $status);
            $stmt->execute();

            header("Location: list.php");
            exit;
        }
    }
}
?>

<?php include '../../includes/admin_sidebar.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-5">
    <h3 class="fw-bold m-0" style="font-family: 'Outfit', sans-serif;">Add <span style="background: linear-gradient(135deg, #60a5fa, #c084fc); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Team Member</span></h3>
    <a href="list.php" class="btn btn-sm" style="background: rgba(100, 116, 139, 0.1); border: 1px solid rgba(100, 116, 139, 0.3); color: #cbd5e1; border-radius: 6px;">← Back to Team</a>
</div>

<?php if (isset($error)): ?>
    <div class="alert alert-danger alert-premium text-center" style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: #fca5a5;">
        <?php echo $error; ?>
    </div>
<?php endif; ?>

<div class="glass-card" style="border-top: 4px solid #6366f1;">
    <div class="card-body p-4 p-md-5">

        <form method="POST" enctype="multipart/form-data">

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="mb-4">
                        <label class="form-label text-secondary fw-medium small text-uppercase" style="letter-spacing: 1px;">Name *</label>
                        <input type="text" name="name" class="form-control form-control-dark py-2" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-4">
                        <label class="form-label text-secondary fw-medium small text-uppercase" style="letter-spacing: 1px;">Position *</label>
                        <input type="text" name="position" class="form-control form-control-dark py-2" required>
                    </div>
                </div>

                <div class="col-12">
                    <div class="mb-4">
                        <label class="form-label text-secondary fw-medium small text-uppercase" style="letter-spacing: 1px;">Bio</label>
                        <textarea name="bio" class="form-control form-control-dark" rows="4"></textarea>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-4">
                        <label class="form-label text-secondary fw-medium small text-uppercase" style="letter-spacing: 1px;">Profile Image</label>
                        <input type="file" name="image" class="form-control form-control-dark py-2">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-4">
                        <label class="form-label text-secondary fw-medium small text-uppercase" style="letter-spacing: 1px;">Status</label>
                        <select name="status" class="form-control form-control-dark py-2">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="text-end mt-4">
                <button class="btn px-5 py-3 fw-bold" style="background: linear-gradient(135deg, #6366f1, #8b5cf6); color: white; border-radius: 8px; border: none; box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4); text-transform: uppercase; letter-spacing: 1px;">
                    Save Member
                </button>
            </div>

        </form>

    </div>
</div>

        </div> <!-- End p-4 wrapper from sidebar -->
    </main> <!-- End admin-content -->
</div> <!-- End admin-wrapper -->
</body>
</html>

