<?php
require_once "../../includes/db.php";
require_once "../../includes/auth.php";

adminAuth();

// Fetch users who have chat messages
$query = mysqli_query(
    $conn,
    "SELECT DISTINCT users.id, users.name
     FROM chats
     JOIN users ON users.id = chats.sender_id
     WHERE chats.sender_role = 'user'
     ORDER BY chats.created_at DESC"
);
?>

<?php require_once "../../includes/admin_sidebar.php"; ?>

<div class="d-flex justify-content-between align-items-center mb-5">
    <h3 class="fw-bold m-0" style="font-family: 'Outfit', sans-serif;">Inbox <span style="background: linear-gradient(135deg, #60a5fa, #c084fc); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Messages</span></h3>
</div>

<div class="glass-card">
    <h5 class="fw-bold mb-4 text-white" style="font-family: 'Outfit', sans-serif;">Recent Conversations</h5>

    <div class="table-responsive">
        <table class="table table-borderless table-premium align-middle mb-0">
            <thead>
                <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                    <th class="text-secondary fw-semibold text-uppercase" style="font-size: 0.8rem; letter-spacing: 1px; padding-bottom: 15px;">User</th>
                    <th class="text-secondary fw-semibold text-uppercase text-end" style="font-size: 0.8rem; letter-spacing: 1px; padding-bottom: 15px;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($query) > 0): ?>
                    <?php while ($user = mysqli_fetch_assoc($query)): 
                        // Dynamic Avatar Logic
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
                            <td class="py-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-sm" style="width: 40px; height: 40px; background: <?= $avatarColor ?>; font-size: 0.9rem;">
                                        <?= $initials ?>
                                    </div>
                                    <span class="fw-medium"><?php echo htmlspecialchars($user['name']); ?></span>
                                </div>
                            </td>
                            <td class="text-end py-3">
                                <a href="send.php?user_id=<?php echo $user['id']; ?>" class="btn btn-sm" style="background: rgba(99, 102, 241, 0.1); border: 1px solid rgba(99, 102, 241, 0.3); color: #818cf8; border-radius: 6px;">
                                    Open Chat
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="2" class="text-center text-muted py-4">No conversations yet.</td>
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

