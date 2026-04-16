<?php
require_once "../../includes/db.php";
require_once "../../includes/auth.php";

adminAuth();

// Fetch users
$users = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");
?>

<?php require_once "../../includes/admin_sidebar.php"; ?>

<?php
require_once "../../includes/db.php";
require_once "../../includes/auth.php";

adminAuth();

// Fetch users
$users = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");
?>

<?php require_once "../../includes/admin_sidebar.php"; ?>

<div class="d-flex justify-content-between align-items-center mb-5">
    <h3 class="fw-bold m-0" style="font-family: 'Outfit', sans-serif;">Manage <span style="background: linear-gradient(135deg, #60a5fa, #c084fc); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Users</span></h3>
</div>

<div class="glass-card">
    <div class="table-responsive">
        <table class="table table-borderless table-premium align-middle mb-0">
            <thead>
                <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                    <th class="text-secondary fw-semibold text-uppercase" style="font-size: 0.8rem; letter-spacing: 1px; padding-bottom: 15px;">#</th>
                    <th class="text-secondary fw-semibold text-uppercase" style="font-size: 0.8rem; letter-spacing: 1px; padding-bottom: 15px;">Name</th>
                    <th class="text-secondary fw-semibold text-uppercase" style="font-size: 0.8rem; letter-spacing: 1px; padding-bottom: 15px;">Email</th>
                    <th class="text-secondary fw-semibold text-uppercase" style="font-size: 0.8rem; letter-spacing: 1px; padding-bottom: 15px;">Status</th>
                    <th class="text-secondary fw-semibold text-uppercase text-end" style="font-size: 0.8rem; letter-spacing: 1px; padding-bottom: 15px; width: 160px;">Action</th>
                </tr>
            </thead>
            <tbody>

            <?php if (mysqli_num_rows($users) > 0):
                $i = 1;
                while ($user = mysqli_fetch_assoc($users)): 
                    // Dynamic Avatar
                    $nameParts = explode(' ', trim($user['name']));
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
                    $avatarColor = $gradients[abs(crc32($user['name'])) % count($gradients)];
                ?>
                <tr style="border-bottom: 1px solid rgba(255,255,255,0.03); transition: background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.02)'" onmouseout="this.style.background='transparent'">
                    <td class="text-secondary fw-medium py-3"><?php echo $i++; ?></td>
                    <td class="py-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-sm" style="width: 40px; height: 40px; background: <?= $avatarColor ?>; font-size: 0.9rem;">
                                <?= $initials ?>
                            </div>
                            <span class="fw-medium"><?php echo htmlspecialchars($user['name']); ?></span>
                        </div>
                    </td>
                    <td class="text-secondary py-3"><?php echo htmlspecialchars($user['email']); ?></td>
                    <td class="py-3">
                        <?php if ($user['status'] === 'active'): ?>
                            <span class="badge" style="background: rgba(34, 197, 94, 0.15); border: 1px solid rgba(34, 197, 94, 0.3); color: #86efac; padding: 6px 12px;">Active</span>
                        <?php else: ?>
                            <span class="badge" style="background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.3); color: #fca5a5; padding: 6px 12px;">Blocked</span>
                        <?php endif; ?>
                    </td>
                    <td class="py-3 text-end">
                        <div class="d-flex justify-content-end gap-2">
                            <?php if ($user['status'] === 'active'): ?>
                                <a href="block.php?id=<?php echo $user['id']; ?>&action=block"
                                   class="btn btn-sm" style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: #fca5a5; border-radius: 6px;"
                                   onclick="return confirm('Block this user?')">
                                   Block
                                </a>
                            <?php else: ?>
                                <a href="block.php?id=<?php echo $user['id']; ?>&action=unblock"
                                   class="btn btn-sm" style="background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.3); color: #86efac; border-radius: 6px;">
                                   Unblock
                                </a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
            <?php endwhile; else: ?>
                <tr>
                    <td colspan="5" class="text-center text-secondary py-5">
                        No users found.
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

