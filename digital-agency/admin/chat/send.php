<?php
require_once "../../includes/db.php";
require_once "../../includes/auth.php";

adminAuth();

$user_id = $_GET['user_id'] ?? null;
if (!$user_id) {
    header("Location: inbox.php");
    exit();
}

// Fetch user info
$stmt = mysqli_prepare($conn, "SELECT name FROM users WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$user) {
    die("User not found.");
}

/*
|--------------------------------------------------------------------------
| SEND MESSAGE (TEXT + IMAGE + ZIP)
|--------------------------------------------------------------------------
*/
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $message  = trim($_POST['message'] ?? '');
    $fileName = null;

    if (!empty($_FILES['file']['name'])) {

        $targetSubDir = "/chat/";
        $uploadPath = UPLOAD_DIR . $targetSubDir;

        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'zip'];

        if (in_array($ext, $allowed)) {
            $fileName = uniqid("chat_", true) . "." . $ext;
            if (!move_uploaded_file($_FILES['file']['tmp_name'], $uploadPath . $fileName)) {
                // Silently fail or log error
            }
        }
    }

    if ($message !== '' || $fileName !== null) {

        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO chats (sender_id, receiver_id, sender_role, message, file)
             VALUES (?, ?, 'admin', ?, ?)"
        );
        mysqli_stmt_bind_param(
            $stmt,
            "iiss",
            $_SESSION[ADMIN_SESSION],
            $user_id,
            $message,
            $fileName
        );
        mysqli_stmt_execute($stmt);
    }
}

// Fetch conversation
$messages = mysqli_query(
    $conn,
    "SELECT * FROM chats
     WHERE (sender_id = $user_id AND sender_role = 'user')
        OR (receiver_id = $user_id AND sender_role = 'admin')
     ORDER BY created_at ASC"
);
?>
);
?>

<?php require_once "../../includes/admin_sidebar.php"; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold m-0" style="font-family: 'Outfit', sans-serif;">Chat with <span style="background: linear-gradient(135deg, #60a5fa, #c084fc); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"><?php echo htmlspecialchars($user['name']); ?></span></h3>
    <a href="inbox.php" class="btn btn-sm" style="background: rgba(100, 116, 139, 0.1); border: 1px solid rgba(100, 116, 139, 0.3); color: #cbd5e1; border-radius: 6px;">← Back</a>
</div>

<div class="glass-card mb-4 chat-container p-4" style="height: 500px; overflow-y: auto; display: flex; flex-direction: column; gap: 15px; border-top: 4px solid #6366f1;">

    <?php if (mysqli_num_rows($messages) > 0): ?>
        <?php while ($msg = mysqli_fetch_assoc($messages)): ?>
            
            <?php $isAdmin = ($msg['sender_role'] === 'admin'); ?>
            
            <div class="d-flex <?php echo $isAdmin ? 'justify-content-end' : 'justify-content-start'; ?>">
                <div class="chat-message <?php echo $isAdmin ? 'chat-bubble-admin' : 'chat-bubble-user'; ?>" 
                     style="max-width: 70%; padding: 12px 18px; border-radius: 16px; <?php echo $isAdmin ? 'background: linear-gradient(135deg, #4f46e5, #7c3aed); color: white; border-bottom-right-radius: 4px;' : 'background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); color: #e2e8f0; border-bottom-left-radius: 4px;'; ?>">
                    
                    <div style="font-size: 0.75rem; opacity: 0.8; margin-bottom: 5px; font-weight: 600; text-transform: uppercase;">
                        <?php echo $isAdmin ? 'You' : htmlspecialchars($user['name']); ?>
                    </div>

                    <?php if (!empty($msg['message'])): ?>
                        <div style="line-height: 1.5; font-size: 0.95rem;"><?php echo nl2br(htmlspecialchars($msg['message'])); ?></div>
                    <?php endif; ?>

                    <?php if (!empty($msg['file'])): ?>
                        <?php $ext = pathinfo($msg['file'], PATHINFO_EXTENSION); ?>

                        <?php if (in_array($ext, ['jpg','jpeg','png'])): ?>
                            <img src="../../uploads/chat/<?php echo $msg['file']; ?>"
                                 class="img-fluid mt-2 rounded"
                                 style="max-width: 200px; border: 2px solid rgba(255,255,255,0.2);">
                        <?php else: ?>
                            <a href="../../uploads/chat/<?php echo $msg['file']; ?>"
                               class="btn btn-sm mt-2"
                               style="background: rgba(255,255,255,0.2); color: white; border: none;"
                               download>
                                📦 Download ZIP
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>

                    <div class="text-end mt-1" style="font-size: 0.7rem; opacity: 0.6;">
                        <?php echo date("H:i", strtotime($msg['created_at'])); ?>
                    </div>
                </div>
            </div>

        <?php endwhile; ?>
    <?php else: ?>
        <div class="h-100 d-flex flex-column align-items-center justify-content-center text-secondary">
            <div style="font-size: 3rem; margin-bottom: 10px;">💬</div>
            <p>No messages yet. Start the conversation!</p>
        </div>
    <?php endif; ?>

</div>

<div class="glass-card p-3">
    <form method="POST" enctype="multipart/form-data" class="d-flex gap-3 align-items-center">
        <input type="text" name="message" class="form-control form-control-dark py-2"
               placeholder="Type your message here..." style="border-radius: 20px;">
        
        <label class="btn btn-sm mb-0 d-flex align-items-center justify-content-center" style="background: rgba(100, 116, 139, 0.1); border: 1px solid rgba(100, 116, 139, 0.3); color: #cbd5e1; border-radius: 50%; width: 40px; height: 40px; cursor: pointer;" title="Attach File">
            📎 <input type="file" name="file" class="d-none">
        </label>
        
        <button class="btn px-4 py-2" style="background: linear-gradient(135deg, #6366f1, #8b5cf6); color: white; border-radius: 20px; font-weight: bold; border: none;">
            Send
        </button>
    </form>
</div>

        </div> <!-- End p-4 wrapper from sidebar -->
    </main> <!-- End admin-content -->
</div> <!-- End admin-wrapper -->
<script>
    // Scroll chat to bottom
    const chatContainer = document.querySelector('.chat-container');
    if(chatContainer) {
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }
</script>
</body>
</html>

