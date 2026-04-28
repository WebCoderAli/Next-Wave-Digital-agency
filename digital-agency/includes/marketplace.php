<?php

function marketplaceTableExists(mysqli $conn, string $table): bool
{
    $table = mysqli_real_escape_string($conn, $table);
    $result = mysqli_query($conn, "SHOW TABLES LIKE '{$table}'");
    return $result instanceof mysqli_result && mysqli_num_rows($result) > 0;
}

function marketplaceColumnExists(mysqli $conn, string $table, string $column): bool
{
    $table = mysqli_real_escape_string($conn, $table);
    $column = mysqli_real_escape_string($conn, $column);
    $result = mysqli_query($conn, "SHOW COLUMNS FROM `{$table}` LIKE '{$column}'");
    return $result instanceof mysqli_result && mysqli_num_rows($result) > 0;
}

function marketplaceEnsureQuery(mysqli $conn, string $sql): void
{
    mysqli_query($conn, $sql);
}

function ensureMarketplaceSchema(mysqli $conn): void
{
    static $done = false;
    if ($done) {
        return;
    }
    $done = true;

    if (!marketplaceTableExists($conn, 'orders')) {
        return;
    }

    if (!marketplaceColumnExists($conn, 'orders', 'progress_percent')) {
        marketplaceEnsureQuery($conn, "ALTER TABLE orders ADD COLUMN progress_percent INT NOT NULL DEFAULT 0 AFTER status");
    }
    if (!marketplaceColumnExists($conn, 'orders', 'completed_at')) {
        marketplaceEnsureQuery($conn, "ALTER TABLE orders ADD COLUMN completed_at DATETIME NULL DEFAULT NULL AFTER created_at");
    }
    if (!marketplaceColumnExists($conn, 'orders', 'offered_price')) {
        marketplaceEnsureQuery($conn, "ALTER TABLE orders ADD COLUMN offered_price DECIMAL(10,2) NULL DEFAULT NULL AFTER detailed_desc");
    }
    if (!marketplaceColumnExists($conn, 'orders', 'payment_mode')) {
        marketplaceEnsureQuery($conn, "ALTER TABLE orders ADD COLUMN payment_mode ENUM('full','milestone') NULL DEFAULT NULL AFTER offered_price");
    }
    if (!marketplaceColumnExists($conn, 'orders', 'milestone_plan')) {
        marketplaceEnsureQuery($conn, "ALTER TABLE orders ADD COLUMN milestone_plan LONGTEXT NULL AFTER payment_mode");
    }
    if (!marketplaceColumnExists($conn, 'orders', 'offer_status')) {
        marketplaceEnsureQuery($conn, "ALTER TABLE orders ADD COLUMN offer_status ENUM('waiting','offered','countered','accepted','rejected') NOT NULL DEFAULT 'waiting' AFTER payment_mode");
    }
    if (!marketplaceColumnExists($conn, 'orders', 'client_budget')) {
        marketplaceEnsureQuery($conn, "ALTER TABLE orders ADD COLUMN client_budget DECIMAL(10,2) NULL DEFAULT NULL AFTER detailed_desc");
    }

    if (marketplaceTableExists($conn, 'payments')) {
        if (!marketplaceColumnExists($conn, 'payments', 'amount')) {
            marketplaceEnsureQuery($conn, "ALTER TABLE payments ADD COLUMN amount DECIMAL(10,2) NULL DEFAULT NULL AFTER order_id");
        }
        if (!marketplaceColumnExists($conn, 'payments', 'milestone_name')) {
            marketplaceEnsureQuery($conn, "ALTER TABLE payments ADD COLUMN milestone_name VARCHAR(255) NULL DEFAULT NULL AFTER amount");
        }
        if (!marketplaceColumnExists($conn, 'payments', 'note')) {
            marketplaceEnsureQuery($conn, "ALTER TABLE payments ADD COLUMN note TEXT NULL AFTER milestone_name");
        }
        if (!marketplaceColumnExists($conn, 'payments', 'verified')) {
            marketplaceEnsureQuery($conn, "ALTER TABLE payments ADD COLUMN verified ENUM('pending','yes','no') NOT NULL DEFAULT 'pending' AFTER screenshot");
        }
    }

    if (!marketplaceTableExists($conn, 'order_progress')) {
        marketplaceEnsureQuery($conn, "CREATE TABLE order_progress (
            id INT AUTO_INCREMENT PRIMARY KEY,
            order_id INT NOT NULL,
            progress_percent INT NOT NULL DEFAULT 0,
            message TEXT NOT NULL,
            file VARCHAR(255) DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
        )");
    }

    if (!marketplaceTableExists($conn, 'chats')) {
        marketplaceEnsureQuery($conn, "CREATE TABLE chats (
            id INT AUTO_INCREMENT PRIMARY KEY,
            order_id INT NOT NULL,
            sender_id INT NOT NULL,
            receiver_id INT NOT NULL DEFAULT 0,
            sender_role ENUM('admin','user') NOT NULL,
            message TEXT NULL,
            file VARCHAR(255) DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
        )");
    } else {
        if (!marketplaceColumnExists($conn, 'chats', 'sender_id')) {
            marketplaceEnsureQuery($conn, "ALTER TABLE chats ADD COLUMN sender_id INT NOT NULL DEFAULT 0 AFTER order_id");
        }
        if (!marketplaceColumnExists($conn, 'chats', 'receiver_id')) {
            marketplaceEnsureQuery($conn, "ALTER TABLE chats ADD COLUMN receiver_id INT NOT NULL DEFAULT 0 AFTER sender_id");
        }
        if (!marketplaceColumnExists($conn, 'chats', 'sender_role')) {
            marketplaceEnsureQuery($conn, "ALTER TABLE chats ADD COLUMN sender_role ENUM('admin','user') NOT NULL DEFAULT 'user' AFTER receiver_id");
        }
        if (!marketplaceColumnExists($conn, 'chats', 'message')) {
            marketplaceEnsureQuery($conn, "ALTER TABLE chats ADD COLUMN message TEXT NULL AFTER sender_role");
        }
        if (!marketplaceColumnExists($conn, 'chats', 'file')) {
            marketplaceEnsureQuery($conn, "ALTER TABLE chats ADD COLUMN file VARCHAR(255) DEFAULT NULL AFTER message");
        }
    }
}

function decodeMilestones(?string $milestoneJson): array
{
    if (!$milestoneJson) {
        return [];
    }

    $decoded = json_decode($milestoneJson, true);
    return is_array($decoded) ? $decoded : [];
}

function formatPaymentMode(?string $paymentMode): string
{
    return $paymentMode === 'milestone' ? 'Milestone payment' : 'Full payment';
}

