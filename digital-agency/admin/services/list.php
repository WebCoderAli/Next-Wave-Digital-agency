<?php
require_once "../../includes/db.php";
require_once "../../includes/auth.php";

adminAuth();

// Fetch services
$query = mysqli_query($conn, "SELECT * FROM services ORDER BY id DESC");
?>



<?php require_once "../../includes/admin_sidebar.php"; ?>

<div class="d-flex justify-content-between align-items-center mb-5">
    <h3 class="fw-bold m-0" style="font-family: 'Outfit', sans-serif;">Manage <span style="background: linear-gradient(135deg, #60a5fa, #c084fc); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Services</span></h3>
    <a href="add.php" class="btn btn-sm px-4 py-2 fw-bold" style="background: linear-gradient(135deg, #6366f1, #8b5cf6); color: white; border-radius: 8px; border: none; box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4); text-transform: uppercase; letter-spacing: 1px; font-size: 0.8rem;">+ Add New Service</a>
</div>

<div class="glass-card">
    <div class="table-responsive">
        <table class="table table-borderless table-premium align-middle mb-0">
            <thead>
                <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                    <th class="text-secondary fw-semibold text-uppercase" style="font-size: 0.8rem; letter-spacing: 1px; padding-bottom: 15px;">#</th>
                    <th class="text-secondary fw-semibold text-uppercase" style="font-size: 0.8rem; letter-spacing: 1px; padding-bottom: 15px;">Image</th>
                    <th class="text-secondary fw-semibold text-uppercase" style="font-size: 0.8rem; letter-spacing: 1px; padding-bottom: 15px;">Title</th>
                    <th class="text-secondary fw-semibold text-uppercase" style="font-size: 0.8rem; letter-spacing: 1px; padding-bottom: 15px;">Status</th>
                    <th class="text-secondary fw-semibold text-uppercase text-end" style="font-size: 0.8rem; letter-spacing: 1px; padding-bottom: 15px; width: 180px;">Actions</th>
                </tr>
            </thead>
            <tbody>

            <?php if (mysqli_num_rows($query) > 0): 
                $i = 1;
                while ($row = mysqli_fetch_assoc($query)): 
                    // Dynamic Header Avatar Logic for missing images
                    $nameParts = explode(' ', trim($row['title']));
                    $initials = strtoupper(substr($nameParts[0], 0, 1));
                    
                    $gradients = [
                        'linear-gradient(135deg, #6366f1, #8b5cf6)',
                        'linear-gradient(135deg, #10b981, #059669)',
                        'linear-gradient(135deg, #f59e0b, #d97706)',
                        'linear-gradient(135deg, #ef4444, #b91c1c)',
                        'linear-gradient(135deg, #0ea5e9, #0369a1)',
                        'linear-gradient(135deg, #ec4899, #be185d)'
                    ];
                    $avatarColor = $gradients[abs(crc32($row['title'])) % count($gradients)];
                ?>
                <tr style="border-bottom: 1px solid rgba(255,255,255,0.03); transition: background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.02)'" onmouseout="this.style.background='transparent'">
                    <td class="text-secondary fw-medium py-3"><?php echo $i++; ?></td>

                    <td class="py-3">
                        <?php if ($row['image'] && file_exists('../../uploads/services/'.$row['image'])): ?>
                            <img src="../../uploads/services/<?php echo $row['image']; ?>"
                                 width="80" height="60" class="rounded" style="object-fit: cover; border: 1px solid rgba(255,255,255,0.1); box-shadow: 0 4px 10px rgba(0,0,0,0.2);">
                        <?php else: ?>
                            <div class="rounded d-flex align-items-center justify-content-center text-white fw-bold shadow-sm" style="width: 80px; height: 60px; background: <?= $avatarColor ?>; font-size: 1.5rem; border: 1px solid rgba(255,255,255,0.1);">
                                <?= $initials ?>
                            </div>
                        <?php endif; ?>
                    </td>

                    <td class="py-3 fw-bold" style="font-size: 1.05rem;"><?php echo htmlspecialchars($row['title']); ?></td>

                    <td class="py-3">
                        <?php if ($row['status'] === 'active'): ?>
                            <span class="badge" style="background: rgba(34, 197, 94, 0.15); border: 1px solid rgba(34, 197, 94, 0.3); color: #86efac; padding: 6px 12px;">Active</span>
                        <?php else: ?>
                            <span class="badge" style="background: rgba(100, 116, 139, 0.15); border: 1px solid rgba(100, 116, 139, 0.3); color: #cbd5e1; padding: 6px 12px;">Inactive</span>
                        <?php endif; ?>
                    </td>

                    <td class="py-3 text-end">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="edit.php?id=<?php echo $row['id']; ?>"
                               class="btn btn-sm" style="background: rgba(56, 189, 248, 0.1); border: 1px solid rgba(56, 189, 248, 0.3); color: #7dd3fc; border-radius: 6px;">Edit</a>

                            <a href="delete.php?id=<?php echo $row['id']; ?>"
                               class="btn btn-sm" style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: #fca5a5; border-radius: 6px;"
                               onclick="return confirm('Delete this service?')">
                               Delete
                            </a>
                        </div>
                    </td>
                </tr>
            <?php endwhile; else: ?>
                <tr>
                    <td colspan="5" class="text-center text-secondary py-5">
                        No services found.
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

