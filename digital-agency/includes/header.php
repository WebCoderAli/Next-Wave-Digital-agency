<?php
if (!isset($_SESSION)) {
    session_start();
}

/*
|--------------------------------------------------------------------------
| BASE URL (IMPORTANT)
|--------------------------------------------------------------------------
*/
require_once __DIR__ . '/config.php';

// User login check
$isUserLoggedIn = isset($_SESSION['user_id']);
$userName = $isUserLoggedIn ? ($_SESSION['user_name'] ?? 'User') : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Digital Agency</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Premium Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?= BASE_URL ?>/assets/css/style.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>/assets/css/guest.css" rel="stylesheet">

    <style>
    /* ================= PREMIUM HEADER STYLES ================= */
    .navbar-premium {
        background: rgba(5, 5, 5, 0.7) !important;
        backdrop-filter: blur(25px);
        -webkit-backdrop-filter: blur(25px);
        border-bottom: 1px solid rgba(255,255,255,0.06);
        transition: padding 0.3s ease;
        padding: 16px 0;
    }
    .navbar-brand.brand-premium {
        font-family: 'Outfit', sans-serif;
        font-size: 1.6rem;
        font-weight: 800;
        letter-spacing: -0.5px;
        background: linear-gradient(135deg, #ffffff 0%, #a5b4fc 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        transition: opacity 0.3s ease;
    }
    .navbar-brand.brand-premium:hover {
        opacity: 0.85;
    }
    .nav-link-premium {
        font-family: 'Inter', sans-serif;
        font-weight: 500;
        font-size: 0.95rem;
        color: #94a3b8 !important;
        transition: all 0.3s ease;
        position: relative;
        padding: 8px 0;
        margin: 0 12px;
    }
    .nav-link-premium:hover, .nav-link-premium.active {
        color: #ffffff !important;
    }
    .nav-link-premium::after {
        content: '';
        position: absolute;
        bottom: 0; left: 50%;
        width: 0; height: 2px;
        background: linear-gradient(90deg, #60a5fa, #c084fc);
        transition: all 0.3s ease;
        transform: translateX(-50%);
        border-radius: 2px;
    }
    .nav-link-premium:hover::after, .nav-link-premium.active::after {
        width: 100%;
    }
    .btn-nav-cta {
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        color: #ffffff !important;
        border: none;
        border-radius: 50px;
        padding: 10px 24px;
        font-size: 0.9rem;
        font-weight: 600;
        font-family: 'Inter', sans-serif;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(124, 58, 237, 0.3);
    }
    .btn-nav-cta:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(124, 58, 237, 0.45);
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
    }
    .dropdown-menu-premium {
        background: rgba(15, 23, 42, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 16px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.4);
        padding: 10px;
    }
    .dropdown-menu-premium .dropdown-item {
        color: #e2e8f0;
        font-family: 'Inter', sans-serif;
        font-size: 0.9rem;
        font-weight: 500;
        border-radius: 8px;
        padding: 8px 16px;
        transition: all 0.2s;
    }
    .dropdown-menu-premium .dropdown-item:hover {
        background: rgba(255,255,255,0.05);
        color: #fff;
    }
    .dropdown-menu-premium .dropdown-item.text-danger:hover {
        background: rgba(239, 68, 68, 0.1);
        color: #f87171 !important;
    }
    .navbar-toggler {
        border-color: rgba(255,255,255,0.1);
    }
    .navbar-toggler:focus {
        box-shadow: none;
    }

    /* Mobile Menu (Collapse) Enhancements */
    @media (max-width: 991.98px) {
        .navbar-collapse {
            background: #000000; /* Solid black for visibility */
            margin-top: 15px;
            border-radius: 12px;
            padding: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        }
        .nav-link-premium {
            margin: 10px 0;
            padding: 10px 15px !important;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.03);
        }
        .nav-link-premium::after {
            display: none; /* Remove underline on mobile */
        }
    }
    </style>
    
    <!-- Theme Toggle Styles & Logic -->
    <link href="<?= BASE_URL ?>/assets/css/theme-toggle.css" rel="stylesheet">
    <script src="<?= BASE_URL ?>/assets/js/theme.js"></script>
</head>

<body>

<!-- ================= NAVBAR ================= -->
<?php if($isUserLoggedIn): ?>
<style>
    /* Force compact inline header to avoid hamburger slide entirely */
    .navbar-brand.brand-premium {
        font-size: 1.2rem !important;
        margin-right: 10px;
    }
    .navbar-premium {
        padding: 8px 0;
    }
    .navbar-nav {
        flex-direction: row !important;
        flex-wrap: nowrap;
        align-items: center;
    }
    .nav-link-premium {
        font-size: 0.8rem !important;
        padding: 5px 8px !important;
        margin: 0 2px !important;
        white-space: nowrap;
    }
    .theme-toggle-btn {
        width: 32px;
        height: 32px;
        font-size: 0.9rem;
    }
    .navbar-collapse {
        display: flex !important;
        flex-basis: auto;
    }
</style>
<?php endif; ?>

<nav class="navbar <?= $isUserLoggedIn ? 'navbar-expand px-2' : 'navbar-expand-lg' ?> navbar-dark navbar-premium sticky-top">
    <div class="container <?= $isUserLoggedIn ? 'fluid-container px-0' : '' ?>" style="<?= $isUserLoggedIn ? 'max-width: 100%; display:flex; flex-wrap: nowrap;' : '' ?>">

        <!-- BRAND -->
        <a class="navbar-brand brand-premium d-flex align-items-center" href="<?= BASE_URL ?>/">
            Next Wave Digital
        </a>

        <?php if (!$isUserLoggedIn): ?>
            <!-- ALWAYS VISIBLE RIGHT CONTROLS (Theme + Mobile Hamburger) FOR GUEST -->
            <div class="d-flex align-items-center order-lg-3 ms-auto gap-3">
                <button class="theme-toggle-btn trigger-theme" aria-label="Toggle Theme">☀️</button>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
        <?php endif; ?>

        <!-- NAV LINKS (Collapsible for guest, Inline for User) -->
        <div class="<?= !$isUserLoggedIn ? 'collapse navbar-collapse order-lg-2' : 'd-flex align-items-center w-100 justify-content-between' ?>" id="mainNavbar">
            
            <ul class="navbar-nav <?= !$isUserLoggedIn ? 'ms-auto me-lg-3 align-items-lg-center' : 'w-100 d-flex justify-content-end align-items-center' ?>">

                <!-- COMMON (Guest + User) -->
                <?php if (!$isUserLoggedIn): ?>
                    <li class="nav-item"><a class="nav-link nav-link-premium" href="<?= BASE_URL ?>/services.php">Services</a></li>
                    <li class="nav-item"><a class="nav-link nav-link-premium" href="<?= BASE_URL ?>/pricing.php">Pricing</a></li>
                    <li class="nav-item"><a class="nav-link nav-link-premium" href="<?= BASE_URL ?>/portfolio.php">Portfolio</a></li>
                    <li class="nav-item"><a class="nav-link nav-link-premium" href="<?= BASE_URL ?>/contact.php">Contact</a></li>
                <?php endif; ?>

                <!-- ================= GUEST ================= -->
                <?php if (!$isUserLoggedIn): ?>

                    <li class="nav-item ms-lg-3">
                        <a class="nav-link nav-link-premium" href="<?= BASE_URL ?>/user/login.php">
                            Login
                        </a>
                    </li>

                    <li class="nav-item ms-lg-2 mt-3 mt-lg-0 mb-3 mb-lg-0">
                        <a class="btn btn-nav-cta w-100"
                           href="<?= BASE_URL ?>/user/register.php">
                            Get Started
                        </a>
                    </li>

                <!-- ================= USER ================= -->
                <?php else: ?>

                    <!-- Hide marketing links on user dashboard to save space so there's NO slide needed -->
                    <li class="nav-item">
                        <a class="nav-link nav-link-premium" href="<?= BASE_URL ?>/user/dashboard.php">
                            Dashboard
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link nav-link-premium" href="<?= BASE_URL ?>/user/my-orders.php">
                            My Orders
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link nav-link-premium" href="<?= BASE_URL ?>/user/chat/inbox.php">
                            Chat
                        </a>
                    </li>

                    <!-- USER DROPDOWN (acts as native dropdown on inline) -->
                    <li class="nav-item dropdown ms-2">
                        <a class="nav-link nav-link-premium dropdown-toggle d-flex align-items-center gap-1"
                           href="#"
                           role="button"
                           data-bs-toggle="dropdown"
                           data-bs-auto-close="outside">
                            <!-- Small Avatar Circle -->
                            <span class="d-inline-flex align-items-center justify-content-center bg-primary rounded-circle text-white fw-bold" style="width: 22px; height: 22px; font-size:10px;">
                                <?= strtoupper(substr(htmlspecialchars($userName), 0, 1)); ?>
                            </span>
                            <span class="d-none d-sm-inline"><?= htmlspecialchars($userName); ?></span>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-premium mt-2 position-absolute">
                            <li>
                                <a class="dropdown-item" href="<?= BASE_URL ?>/user/profile.php">
                                    Profile
                                </a>
                            </li>
                            <li><hr class="dropdown-divider border-secondary opacity-25"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="<?= BASE_URL ?>/user/logout.php">
                                    Logout
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item ms-2 pe-2 d-flex align-items-center justify-content-center">
                        <button class="theme-toggle-btn trigger-theme" aria-label="Toggle Theme" style="flex-shrink: 0;">☀️</button>
                    </li>

                <?php endif; ?>

            </ul>
        </div>

    </div>
</nav>
<!-- =============== END NAVBAR =============== -->
