<?php
if (!isset($_SESSION)) {
    session_start();
}
require_once __DIR__ . "/auth.php";
adminAuth();

$adminCurrentPath = strtolower($_SERVER['PHP_SELF'] ?? '');
$isAdminActive = function (string $needle) use ($adminCurrentPath): bool {
    return str_contains($adminCurrentPath, strtolower($needle));
};
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel - Digital Agency</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Premium Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- Custom CSS & Theme Engine -->
    <link href="<?= BASE_URL ?>/assets/css/style.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/assets/css/theme-toggle.css" rel="stylesheet">
    <script src="<?= BASE_URL ?>/assets/js/theme.js"></script>

    <style>
        /* Base Admin Layout Structure */
        body {
            background-color: #050505 !important;
            color: #e2e8f0;
            font-family: 'Inter', sans-serif;
        }

        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* PREMIUM SIDEBAR GLASSMORPHISM */
        .admin-sidebar {
            width: 260px;
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border-right: 1px solid rgba(255,255,255,0.06);
            color: #fff;
            display: flex;
            flex-direction: column;
            position: sticky;
            top: 0;
            height: 100vh;
            z-index: 1040;
            transition: transform 0.3s ease;
        }

        .sidebar-header {
            padding: 24px 22px;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            text-align: center;
        }

        .sidebar-header h4 {
            margin: 0;
            font-weight: 800;
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(135deg, #ffffff 0%, #a5b4fc 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .sidebar-header small {
            color: #94a3b8;
            font-weight: 500;
            letter-spacing: 0.5px;
        }

        .sidebar-menu {
            padding: 20px 0;
            flex: 1;
            overflow-y: auto;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 24px;
            color: #94a3b8;
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .sidebar-menu a:hover {
            color: #ffffff;
            background: rgba(255,255,255,0.05);
        }

        .sidebar-menu a.active {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: #ffffff;
            font-weight: 600;
            position: relative;
            box-shadow: 0 4px 15px rgba(124, 58, 237, 0.3);
        }

        .sidebar-footer {
            border-top: 1px solid rgba(255,255,255,0.06);
            padding: 15px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .sidebar-footer-link {
            color: #fca5a5 !important;
            text-decoration: none;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: opacity 0.3s ease;
        }
        .sidebar-footer-link:hover {
            opacity: 0.8;
        }

        /* CONTENT */
        .admin-content {
            flex: 1;
            padding: 40px;
            background: transparent;
            min-width: 0; /* Prevents flexbox expansion breaking */
        }

        /* Mobile Header */
        .mobile-header {
            display: none;
            padding: 15px 20px;
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255,255,255,0.06);
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1000;
            width: 100%;
        }

        .mobile-header h4 {
            margin: 0;
            font-size: 1.2rem;
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            background: linear-gradient(135deg, #ffffff 0%, #a5b4fc 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hamburger-btn {
            background: transparent;
            border: 1px solid rgba(255,255,255,0.2);
            color: white;
            border-radius: 8px;
            padding: 5px 12px;
            cursor: pointer;
            font-size: 1.2rem;
        }

        /* Sidebar Backdrop */
        .sidebar-backdrop {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(3px);
            z-index: 1030;
            display: none;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sidebar-backdrop.show {
            display: block;
            opacity: 1;
        }

        /* RESPONSIVE LAYOUT */
        @media (max-width: 992px) {
            .admin-wrapper {
                flex-direction: column;
            }
            .admin-sidebar {
                position: fixed;
                left: 0;
                transform: translateX(-100%);
            }
            .admin-sidebar.show {
                transform: translateX(0);
                box-shadow: 10px 0 30px rgba(0,0,0,0.5);
            }
            .mobile-header {
                display: flex;
            }
            .admin-content {
                padding: 20px 15px;
            }
        }
    </style>
</head>

<body>

<!-- BACKDROP FOR MOBILE -->
<div class="sidebar-backdrop" id="sidebarBackdrop" onclick="toggleSidebar()"></div>

<div class="admin-wrapper">

    <!-- MOBILE HEADER -->
    <div class="mobile-header">
        <h4>Admin Core</h4>
        <button class="hamburger-btn" onclick="toggleSidebar()">
            ☰
        </button>
    </div>

    <!-- SIDEBAR -->
    <aside class="admin-sidebar" id="adminSidebar">

        <div class="sidebar-header d-none d-lg-block">
            <h4>Admin Core</h4>
            <small>Command Center</small>
        </div>

        <div class="sidebar-menu">

            <a class="<?= $isAdminActive('/admin/dashboard.php') ? 'active' : '' ?>" href="<?= BASE_URL ?>/admin/dashboard.php">
                Dashboard
            </a>

            <a class="<?= $isAdminActive('/admin/services/') ? 'active' : '' ?>" href="<?= BASE_URL ?>/admin/services/list.php">
                Services
            </a>

            <a class="<?= $isAdminActive('/admin/orders/') ? 'active' : '' ?>" href="<?= BASE_URL ?>/admin/orders/list.php">
                Orders
            </a>

            <a class="<?= $isAdminActive('/admin/users/') ? 'active' : '' ?>" href="<?= BASE_URL ?>/admin/users/list.php">
                Users
            </a>

            <a class="<?= $isAdminActive('/admin/chat/') ? 'active' : '' ?>" href="<?= BASE_URL ?>/admin/chat/inbox.php">
                Inbox
            </a>

            <a class="<?= $isAdminActive('/admin/team/') ? 'active' : '' ?>" href="<?= BASE_URL ?>/admin/team/list.php">
                Team
            </a>

            <a class="<?= $isAdminActive('/admin/settings.php') ? 'active' : '' ?>" href="<?= BASE_URL ?>/admin/settings.php">
                Settings
            </a>

        </div>

        <div class="sidebar-footer">
            <a href="<?= BASE_URL ?>/admin/logout.php" class="sidebar-footer-link">
                Logout
            </a>
            
            <button class="theme-toggle-btn trigger-theme" aria-label="Toggle Theme" style="background:transparent; border:1px solid rgba(255,255,255,0.2); border-radius:50%; width:36px; height:36px; display:flex; align-items:center; justify-content:center; color:#ecc94b;">
                ☀️
            </button>
        </div>

    </aside>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('adminSidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            if(sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
                backdrop.classList.remove('show');
                setTimeout(() => backdrop.style.display = 'none', 300);
            } else {
                sidebar.classList.add('show');
                backdrop.style.display = 'block';
                setTimeout(() => backdrop.classList.add('show'), 10);
            }
        }
    </script>

    <!-- CONTENT (Admin Wrapper Extension) -->
    <main class="admin-content premium-wrapper" style="padding:0; min-height: 100vh;">
        <div class="p-4 p-md-5">
