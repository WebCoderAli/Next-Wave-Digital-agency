<?php
require_once '../../includes/auth.php';
require_once '../../includes/db.php';

adminAuth();

// Fetch team members
$result = $conn->query("SELECT * FROM team_members ORDER BY id DESC");
?>

<?php
require_once '../../includes/auth.php';
require_once '../../includes/db.php';

adminAuth();

// Fetch team members
$result = $conn->query("SELECT * FROM team_members ORDER BY id DESC");
?>

<?php include '../../includes/admin_sidebar.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-5">
    <h3 class="fw-bold m-0" style="font-family: 'Outfit', sans-serif;">Manage <span style="background: linear-gradient(135deg, #60a5fa, #c084fc); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Team</span></h3>
    <a href="add.php" class="btn btn-sm px-4 py-2 fw-bold" style="background: linear-gradient(135deg, #6366f1, #8b5cf6); color: white; border-radius: 8px; border: none; box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4); text-transform: uppercase; letter-spacing: 1px; font-size: 0.8rem;">+ Add New Member</a>
</div>

<div class="glass-card">
    <div class="table-responsive">
        <table class="table table-borderless table-premium align-middle mb-0">
            <thead>
                <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                    <th class="text-secondary fw-semibold text-uppercase" style="font-size: 0.8rem; letter-spacing: 1px; padding-bottom: 15px;">Image</th>
                    <th class="text-secondary fw-semibold text-uppercase" style="font-size: 0.8rem; letter-spacing: 1px; padding-bottom: 15px;">Name</th>
                    <th class="text-secondary fw-semibold text-uppercase" style="font-size: 0.8rem; letter-spacing: 1px; padding-bottom: 15px;">Position</th>
                    <th class="text-secondary fw-semibold text-uppercase" style="font-size: 0.8rem; letter-spacing: 1px; padding-bottom: 15px;">Status</th>
                    <th class="text-secondary fw-semibold text-uppercase text-end" style="font-size: 0.8rem; letter-spacing: 1px; padding-bottom: 15px; width: 150px;">Actions</th>
                </tr>
            </thead>
            <tbody>

            <?php if ($result->num_rows === 0): ?>
                <tr>
                    <td colspan="5" class="text-center text-secondary py-5">
                        No team members added yet.
                    </td>
                </tr>
            <?php else: ?>

                <?php while ($row = $result->fetch_assoc()): 
                    // Dynamic Avatar Logic
                    $nameParts = explode(' ', trim($row['name']));
                    $initials = strtoupper(substr($nameParts[0], 0, 1));
                    if (count($nameParts) > 1) {
                        $initials .= strtoupper(substr($nameParts[1], 0, 1));
                    }
                    
                    $gradients = [
                        'linear-gradient(135deg, #6366f1, #8b5cf6)',
                        'linear-gradient(135deg, #10b981, #059669)',
                        'linear-gradient(135deg, #f59e0b, #d97706)',
                        'linear-gradient(135deg, #ef4444, #b91c1c)',
                        'linear-gradient(135deg, #0ea5e9, #0369a1)',
                        'linear-gradient(135deg, #ec4899, #be185d)'
                    ];
                    $avatarColor = $gradients[abs(crc32($row['name'])) % count($gradients)];
                ?>
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.03); transition: background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.02)'" onmouseout="this.style.background='transparent'">
                        <td class="py-3">
                            <?php if (!empty($row['image']) && file_exists('../../uploads/profiles/'.$row['image'])): ?>
                                <img src="../../uploads/profiles/<?php echo $row['image']; ?>"
                                     width="60"
                                     height="60"
                                     style="object-fit:cover; border-radius:12px; border: 1px solid rgba(255,255,255,0.1); box-shadow: 0 4px 10px rgba(0,0,0,0.2);">
                            <?php else: ?>
                                <div class="d-flex align-items-center justify-content-center text-white fw-bold shadow-sm" style="width: 60px; height: 60px; background: <?= $avatarColor ?>; font-size: 1.2rem; border-radius: 12px;">
                                    <?= $initials ?>
                                </div>
                            <?php endif; ?>
                        </td>

                        <td class="py-3 fw-bold" style="font-size: 1.05rem;"><?php echo htmlspecialchars($row['name']); ?></td>

                        <td class="text-light py-3"><?php echo htmlspecialchars($row['position']); ?></td>

                        <td class="py-3">
                            <?php if ($row['status'] === 'active'): ?>
                                <span class="badge" style="background: rgba(34, 197, 94, 0.15); border: 1px solid rgba(34, 197, 94, 0.3); color: #86efac; padding: 6px 12px;">Active</span>
                            <?php else: ?>
                                <span class="badge" style="background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.3); color: #fca5a5; padding: 6px 12px;">Inactive</span>
                            <?php endif; ?>
                        </td>

                        <td class="py-3 text-end">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="edit.php?id=<?php echo $row['id']; ?>"
                                   class="btn btn-sm" style="background: rgba(56, 189, 248, 0.1); border: 1px solid rgba(56, 189, 248, 0.3); color: #7dd3fc; border-radius: 6px;">
                                    Edit
                                </a>

                                <a href="delete.php?id=<?php echo $row['id']; ?>"
                                   class="btn btn-sm" style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: #fca5a5; border-radius: 6px;"
                                   onclick="return confirm('Are you sure you want to delete this member?');">
                                    Delete
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>

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

