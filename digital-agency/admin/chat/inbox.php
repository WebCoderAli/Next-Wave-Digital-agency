<?php
require_once "../../includes/db.php";
require_once "../../includes/auth.php";

adminAuth();

// Fetch order-linked conversations
$query = mysqli_query(
    $conn,
    "SELECT orders.id AS order_id,
            orders.status,
            orders.offer_status,
            users.id AS user_id,
            users.name,
            services.title AS service_title,
            MAX(chats.created_at) AS last_message_at
     FROM chats
     JOIN orders ON orders.id = chats.order_id
     JOIN users ON users.id = orders.user_id
     JOIN services ON services.id = orders.service_id
     GROUP BY orders.id, orders.status, orders.offer_status, users.id, users.name, services.title
     ORDER BY last_message_at DESC"
);
?>

<?php require_once "../../includes/admin_sidebar.php"; ?>

<div class="d-flex justify-content-between align-items-center mb-5">
    <h3 class="fw-bold m-0" style="font-family: 'Outfit', sans-serif;">Inbox <span style="background: linear-gradient(135deg, #60a5fa, #c084fc); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Messages</span></h3>
</div>

<div class="glass-card mt-4 p-4 mb-4">
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
                    <?php while ($conversation = mysqli_fetch_assoc($query)): 
                        // Dynamic Avatar Logic
                        $nameParts = explode(' ', trim($conversation['name']));
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
                        $avatarColor = $gradients[abs(crc32($conversation['name'])) % count($gradients)];
                    ?>
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.03); transition: background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.02)'" onmouseout="this.style.background='transparent'">
                            <td class="py-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-sm" style="width: 40px; height: 40px; background: <?= $avatarColor ?>; font-size: 0.9rem;">
                                        <?= $initials ?>
                                    </div>
                                    <div>
                                        <span class="fw-medium d-block"><?php echo htmlspecialchars($conversation['name']); ?></span>
                                        <small class="text-secondary">#<?= (int) $conversation['order_id']; ?> · <?= htmlspecialchars($conversation['service_title']); ?> · <?= ucfirst($conversation['offer_status']); ?></small>
                                    </div>
                                </div>
                            </td>
                            <td class="text-end py-3">
                                <a href="send.php?order_id=<?php echo (int) $conversation['order_id']; ?>" class="btn btn-sm" style="background: rgba(99, 102, 241, 0.1); border: 1px solid rgba(99, 102, 241, 0.3); color: #818cf8; border-radius: 6px;">
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

