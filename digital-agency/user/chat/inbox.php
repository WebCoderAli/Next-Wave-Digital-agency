<?php
require_once "../../includes/db.php";
require_once "../../includes/auth.php";

userAuth();

$user_id = $_SESSION[USER_SESSION];

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
                // Handle error
            }
        }
    }

    if ($message !== '' || $fileName !== null) {

        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO chats (sender_id, receiver_id, sender_role, message, file)
             VALUES (?, 0, 'user', ?, ?)"
        );
        mysqli_stmt_bind_param(
            $stmt,
            "iss",
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
     WHERE sender_id = $user_id OR receiver_id = $user_id
     ORDER BY created_at ASC"
);
?>

<?php require_once "../../includes/header.php"; ?>

<style>
:root {
    --primary: #6366f1;
    --primary-hover: #4f46e5;
    --bg-dark: #0f172a;
    --bg-card: rgba(30, 41, 59, 0.7);
    --text-main: #f8fafc;
    --text-muted: #94a3b8;
    --border-color: rgba(255, 255, 255, 0.08);
}

.premium-wrapper {
    background-color: var(--bg-dark);
    color: var(--text-main);
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
    min-height: calc(100vh - 80px);
    position: relative;
    overflow: hidden;
}

.ambient-glow {
    position: absolute;
    width: 800px;
    height: 800px;
    background: radial-gradient(circle, rgba(99, 102, 241, 0.15) 0%, rgba(15, 23, 42, 0) 60%);
    top: -200px;
    left: 50%;
    transform: translateX(-50%);
    border-radius: 50%;
    filter: blur(100px);
    z-index: 0;
    pointer-events: none;
}

.glass-card {
    background: var(--bg-card);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid var(--border-color);
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    border-radius: 24px;
    overflow: hidden;
    position: relative;
    z-index: 1;
}

/* Header */
.chat-header {
    padding: 24px 30px;
    border-bottom: 1px solid var(--border-color);
    background: rgba(255, 255, 255, 0.02);
}

.text-gradient {
    background: linear-gradient(135deg, #818cf8, #c084fc);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

/* Chat Feed */
.chat-container {
    height: 550px;
    overflow-y: auto;
    padding: 30px;
    display: flex;
    flex-direction: column;
    gap: 24px;
    scroll-behavior: smooth;
}
.chat-container::-webkit-scrollbar { width: 6px; }
.chat-container::-webkit-scrollbar-track { background: transparent; }
.chat-container::-webkit-scrollbar-thumb {
    background: rgba(255,255,255,0.1);
    border-radius: 10px;
}
.chat-container::-webkit-scrollbar-thumb:hover {
    background: rgba(255,255,255,0.2);
}

/* Bubbles */
.chat-bubble-wrapper {
    display: flex;
    flex-direction: column;
    max-width: 75%;
    animation: fadeIn 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(15px); }
    to { opacity: 1; transform: translateY(0); }
}

.chat-bubble-wrapper.user {
    align-self: flex-end;
}
.chat-bubble-wrapper.admin {
    align-self: flex-start;
}

.chat-bubble {
    padding: 16px 20px;
    border-radius: 20px;
    font-size: 0.95rem;
    line-height: 1.5;
    position: relative;
    word-break: break-word;
}

.chat-bubble-wrapper.user .chat-bubble {
    background: linear-gradient(135deg, var(--primary), #818cf8);
    color: #fff;
    border-bottom-right-radius: 4px;
    box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
}

.chat-bubble-wrapper.admin .chat-bubble {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid var(--border-color);
    color: var(--text-main);
    border-bottom-left-radius: 4px;
}

.chat-meta {
    font-size: 0.75rem;
    color: var(--text-muted);
    margin-top: 6px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.chat-bubble-wrapper.user .chat-meta {
    justify-content: flex-end;
}

/* Avatar */
.chat-avatar {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
    color: #fff;
}
.avatar-admin { background: linear-gradient(135deg, #10b981, #059669); }
.avatar-user { background: rgba(255,255,255,0.2); }

/* Inputs */
.chat-input-area {
    padding: 24px;
    background: rgba(0,0,0,0.2);
    border-top: 1px solid var(--border-color);
}

.form-control-dark {
    background-color: rgba(15, 23, 42, 0.6);
    border: 1px solid var(--border-color);
    color: var(--text-main);
    border-radius: 16px;
    padding: 16px 24px;
    transition: all 0.3s ease;
    font-size: 0.95rem;
}
.form-control-dark::placeholder { color: var(--text-muted); }
.form-control-dark:focus {
    background-color: rgba(15, 23, 42, 0.8);
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
    outline: none;
}

.btn-upload {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid var(--border-color);
    color: var(--text-muted);
    border-radius: 16px;
    width: 56px;
    height: 56px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    flex-shrink: 0;
}
.btn-upload:hover {
    background: rgba(255, 255, 255, 0.1);
    color: var(--text-main);
}

.btn-send {
    background: linear-gradient(135deg, var(--primary), #818cf8);
    color: #fff;
    border: none;
    border-radius: 16px;
    padding: 0 32px;
    font-weight: 600;
    transition: all 0.3s ease;
    height: 56px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
    flex-shrink: 0;
}
.btn-send:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(99, 102, 241, 0.4);
    color: #fff;
}
.btn-send svg { width: 18px; height: 18px; fill: none; }

/* Empty State */
.empty-state {
    text-align: center;
    color: var(--text-muted);
    padding: 60px 0;
}
.empty-state-icon {
    width: 64px;
    height: 64px;
    background: rgba(99, 102, 241, 0.1);
    color: var(--primary);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    margin: 0 auto 20px;
}
</style>

<div class="premium-wrapper">
    <div class="ambient-glow"></div>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">
                
                <div class="mb-4">
                    <h2 class="display-6 fw-bold mb-1">Encrypted <span class="text-gradient">Comms</span></h2>
                    <p class="text-muted mb-0">Direct channel to secure administrative support.</p>
                </div>

                <div class="glass-card mb-4">
                    
                    <!-- Header -->
                    <div class="chat-header d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-admin chat-avatar" style="width: 40px; height: 40px; font-size: 1.2rem;">
                                🛡️
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold text-white">Admin Support</h5>
                                <div class="text-success small d-flex align-items-center gap-2">
                                    <span style="width: 8px; height: 8px; background: #10b981; border-radius: 50%; display: inline-block;"></span>
                                    Online Secure Channel
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Messages -->
                    <div class="chat-container">
                        <?php if (mysqli_num_rows($messages) > 0): ?>
                            <?php while ($msg = mysqli_fetch_assoc($messages)): 
                                $isUser = ($msg['sender_role'] === 'user');
                            ?>
                                
                                <div class="chat-bubble-wrapper <?= $isUser ? 'user' : 'admin' ?>">
                                    <div class="chat-bubble">
                                        <?php if (!empty($msg['message'])): ?>
                                            <?= htmlspecialchars($msg['message']); ?>
                                        <?php endif; ?>

                                        <?php if (!empty($msg['file'])): 
                                            $ext = strtolower(pathinfo($msg['file'], PATHINFO_EXTENSION));
                                            $isImage = in_array($ext, ['jpg','jpeg','png']);
                                        ?>
                                            <div class="<?= !empty($msg['message']) ? 'mt-3' : '' ?>">
                                                <?php if ($isImage): ?>
                                                    <img src="../../uploads/chat/<?= $msg['file']; ?>" 
                                                         class="img-fluid rounded shadow-sm" 
                                                         style="max-width: 250px; border: 1px solid rgba(255,255,255,0.1);">
                                                <?php else: ?>
                                                    <a href="../../uploads/chat/<?= $msg['file']; ?>" 
                                                       class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded text-decoration-none" 
                                                       style="background: rgba(0,0,0,0.2); color: #fff; border: 1px solid rgba(255,255,255,0.1);" 
                                                       download>
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                                                        Download <?= strtoupper($ext) ?>
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="chat-meta">
                                        <div class="chat-avatar <?= $isUser ? 'avatar-user' : 'avatar-admin' ?>">
                                            <?= $isUser ? 'U' : '🛡️' ?>
                                        </div>
                                        <span><?= date("M d, H:i", strtotime($msg['created_at'])); ?></span>
                                    </div>
                                </div>

                            <?php endwhile; ?>
                        <?php else: ?>
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                                </div>
                                <h5 class="fw-bold mb-2 text-white">Channel is Open</h5>
                                <p class="mb-0">Send a message to securely contact an admin.</p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Input Form -->
                    <div class="chat-input-area">
                        <form method="POST" enctype="multipart/form-data" class="d-flex gap-3 align-items-center mb-0">
                            
                            <label class="btn-upload m-0" title="Attach File">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"></path></svg>
                                <input type="file" name="file" class="d-none">
                            </label>
                            
                            <input type="text" name="message" class="form-control-dark flex-grow-1 m-0" 
                                   placeholder="Transmit secure message..." autofocus autocomplete="off">
                            
                            <button type="submit" class="btn-send">
                                <span>Send</span>
                                <svg viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                            </button>
                        </form>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

<script>
    const chatContainer = document.querySelector('.chat-container');
    if (chatContainer) {
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }
</script>

<?php require_once "../../includes/footer.php"; ?>
