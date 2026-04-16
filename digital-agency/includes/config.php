<?php
// Project Configuration File

// Environment Configuration
if ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1') {
    // Local Environment
    if(!defined("BASE_URL")) define("BASE_URL", "/digital-agency");
    if(!defined("DB_HOST")) define("DB_HOST", "localhost");
    if(!defined("DB_USER")) define("DB_USER", "root");
    if(!defined("DB_PASS")) define("DB_PASS", "");
    if(!defined("DB_NAME")) define("DB_NAME", "digital_agency");
} else {
    // Live Production Environment (InfinityFree)
    if(!defined("BASE_URL")) define("BASE_URL", "https://nextwave.infinityfree.me"); // Absolute base URL provided by user
    if(!defined("DB_HOST")) define("DB_HOST", "sql309.infinityfree.com");
    if(!defined("DB_USER")) define("DB_USER", "if0_41614772");
    if(!defined("DB_PASS")) define("DB_PASS", "PS9Txg95vrfCMX");
    if(!defined("DB_NAME")) define("DB_NAME", "if0_41614772_digital_agency");
}

// Paths
define("ROOT_DIR", realpath(__DIR__ . "/.."));
define("UPLOAD_DIR", ROOT_DIR . "/uploads");

// Security
define("SITE_NAME", "Digital Agency");
define("ADMIN_SESSION", "admin_id");
define("USER_SESSION", "user_id");

// Timezone
date_default_timezone_set("Asia/Karachi");
