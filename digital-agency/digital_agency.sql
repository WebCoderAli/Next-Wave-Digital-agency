-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 20, 2026 at 07:47 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `digital_agency`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `status` enum('active','blocked') DEFAULT 'active',
  `reset_token` varchar(255) DEFAULT NULL,
  `token_expiry` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `password`, `status`, `reset_token`, `token_expiry`, `created_at`) VALUES
(1, 'Super Admin', 'admin@agency.com', '$2y$10$mx1Ir/HwFwqKByfXS6Eokex2iaRXLp3AyMy8ZNDFHlvoZu0de1Yqu', 'active', NULL, NULL, '2026-01-16 21:31:54');

-- --------------------------------------------------------

--
-- Table structure for table `chats`
--

CREATE TABLE `chats` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `receiver_id` int(11) DEFAULT NULL,
  `sender_role` enum('admin','user') DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `file` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chats`
--

INSERT INTO `chats` (`id`, `sender_id`, `receiver_id`, `sender_role`, `message`, `created_at`, `file`) VALUES
(1, 1, 0, 'user', 'hy', '2026-01-16 21:44:53', NULL),
(2, 1, 1, 'admin', 'hello', '2026-01-16 21:48:09', NULL),
(3, 1, 1, 'admin', '', '2026-01-16 21:53:58', 'chat_696ab376b9dc60.51899785.png'),
(4, 1, 1, 'admin', '', '2026-01-16 21:56:15', 'chat_696ab3ff1216a6.12815848.png'),
(5, 1, 1, 'admin', '', '2026-01-16 21:56:25', 'chat_696ab409ed8da1.65315424.zip'),
(6, 2, 0, 'user', 'hey', '2026-01-18 20:03:29', NULL),
(7, 1, 2, 'admin', 'gg', '2026-01-18 20:05:13', NULL),
(8, 1, 2, 'admin', '', '2026-01-18 20:05:21', 'chat_696d3d018dfeb3.57126645.zip'),
(9, 4, 0, 'user', 'bgeskjnlnlnlnlnlnlnlnlnlnlnlnl', '2026-02-10 10:50:37', NULL),
(10, 4, 0, 'user', '', '2026-02-10 10:50:54', 'chat_698b0d8e534417.89285127.png');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `service_id` int(11) DEFAULT NULL,
  `short_desc` varchar(255) DEFAULT NULL,
  `detailed_desc` text DEFAULT NULL,
  `client_budget` decimal(10,2) DEFAULT NULL,
  `offered_price` decimal(10,2) DEFAULT NULL,
  `payment_mode` enum('full','milestone') DEFAULT NULL,
  `offer_status` enum('waiting','offered','countered','accepted','rejected') DEFAULT 'waiting',
  `milestone_plan` longtext DEFAULT NULL,
  `status` enum('pending','approved','completed','rejected') DEFAULT 'pending',
  `progress_percent` int(11) DEFAULT 0,
  `completed_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `service_id`, `short_desc`, `detailed_desc`, `status`, `progress_percent`, `completed_at`, `created_at`) VALUES
(1, 1, 1, 'adjsdasj', 'aksdnkjewqn', 'approved', 50, NULL, '2026-01-16 21:44:35'),
(2, 1, 2, 'adjsdasj', 'dsfgdagertgwer', 'completed', 100, '2026-01-23 01:51:14', '2026-01-17 20:59:16'),
(3, 2, 2, 'make a e commerece site', 'wifuhiewnhkofmnwe\'lmlfmopwekmowemroi]mweo]mfnroifnroiwer', 'approved', 0, NULL, '2026-01-18 20:03:09'),
(4, 1, 2, 'WEb', 'jihei2wwwwwwwbnjewr`', 'rejected', 10, NULL, '2026-01-20 09:35:48'),
(5, 4, 2, 'bdjfhcymjfvkjfymjyjfrj', 'zarsgtbzxfrstyhdxtsgyhd5te', 'completed', 100, '2026-02-10 15:53:29', '2026-02-10 10:50:07');

-- --------------------------------------------------------

--
-- Table structure for table `order_answers`
--

CREATE TABLE `order_answers` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `question` varchar(255) DEFAULT NULL,
  `answer` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_progress`
--

CREATE TABLE `order_progress` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `progress_percent` int(11) NOT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `file` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_progress`
--

INSERT INTO `order_progress` (`id`, `order_id`, `progress_percent`, `message`, `created_at`, `file`) VALUES
(1, 4, 10, 'your order is 10 percent done', '2026-01-22 20:19:22', NULL),
(2, 2, 50, 'We are working on your project', '2026-01-22 20:42:18', NULL),
(3, 2, 100, 'done', '2026-01-22 20:45:57', NULL),
(4, 2, 100, 'das', '2026-01-22 20:46:49', NULL),
(5, 2, 100, 'das', '2026-01-22 20:51:14', NULL),
(6, 1, 50, '50%', '2026-01-22 21:01:40', 'delivery_69729034b6320.jpeg'),
(7, 5, 25, 'hogya', '2026-02-10 10:52:36', NULL),
(8, 5, 100, 'phir ,kisep-09fgds', '2026-02-10 10:53:29', 'delivery_698b0e2932818.png');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `milestone_name` varchar(255) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `screenshot` varchar(255) DEFAULT NULL,
  `verified` enum('pending','yes','no') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `order_id`, `screenshot`, `verified`, `created_at`) VALUES
(1, 1, '696ab143a9fcc.png', 'yes', '2026-01-16 21:44:35'),
(2, 2, 'payment_696bf824681906.95476155.png', 'yes', '2026-01-17 20:59:16'),
(3, 3, 'payment_696d3c7d1bdcc9.52346181.jpg', 'yes', '2026-01-18 20:03:09'),
(4, 4, 'payment_696f4c74388ff8.53559903.png', 'no', '2026-01-20 09:35:48'),
(5, 5, 'payment_698b0d5f8e4479.16233380.png', 'yes', '2026-02-10 10:50:07');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `short_description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `price` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `title`, `short_description`, `image`, `status`, `created_at`, `price`) VALUES
(1, 'High-Quality Website Development for Your Business', 'Need a website that converts visitors into customers? I offer professional web development services using modern technologies to create visually appealing, mobile-friendly, and performance-optimized websites that support your business goals.', 'service_6998a073788125.75137162.png', 'active', '2026-01-16 21:40:16', 200.00),
(2, 'Digital Marketing & Online Growth Solutions', 'I provide result-driven digital marketing services to help businesses grow online, increase brand visibility, and generate quality leads. My strategy focuses on targeted audience reach, performance tracking, and continuous optimization to ensure maximum ROI. From planning to execution, I help your brand stand out in the digital world.', 'service_69989ffa36c6d5.97771462.png', 'active', '2026-01-16 21:40:44', 100.00),
(3, 'Creative Graphic Design Services', 'We provide visually appealing and professional graphic design solutions that help businesses communicate their brand effectively. From logos to marketing materials, my designs are created to attract attention, build trust, and leave a lasting impression.', 'service_6998a1125c3360.33342754.png', 'active', '2026-02-20 17:59:46', 100.00);

-- --------------------------------------------------------

--
-- Table structure for table `service_questions`
--

CREATE TABLE `service_questions` (
  `id` int(11) NOT NULL,
  `service_id` int(11) DEFAULT NULL,
  `question` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `site_name` varchar(100) DEFAULT NULL,
  `site_email` varchar(100) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `site_name`, `site_email`, `updated_at`) VALUES
(1, 'Digital Agency', 'support@agency.com', '2026-01-16 21:31:54');

-- --------------------------------------------------------

--
-- Table structure for table `team`
--

CREATE TABLE `team` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `role` varchar(100) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `team`
--

INSERT INTO `team` (`id`, `name`, `role`, `image`, `created_at`) VALUES
(1, 'Ali', 'Developer', '696ab081b2044.jpeg', '2026-01-16 21:41:21');

-- --------------------------------------------------------

--
-- Table structure for table `team_members`
--

CREATE TABLE `team_members` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `position` varchar(100) NOT NULL,
  `bio` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `team_members`
--

INSERT INTO `team_members` (`id`, `name`, `position`, `bio`, `image`, `status`, `created_at`) VALUES
(1, 'Ali Hamza', 'Website Developer', 'Ali Hamza is a skilled web developer with strong expertise in building responsive, secure, and user-friendly websites. He manages projects from planning to deployment, ensuring high-quality delivery and client satisfaction. With a problem-solving mindset, he focuses on clean code and scalable solutions.', '1771612513_WhatsApp Image 2025-11-01 at 9.54.17 AM.jpeg', 'active', '2026-02-20 18:35:13');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `status` enum('active','blocked') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `status`, `created_at`) VALUES
(1, 'Ali', 'ali@gmail.com', '$2y$10$prQ/FvF5NWHx9IAsqXsGP.3RA0u9YCJ.NwBqhQvIgou/KZl3OmoBS', 'active', '2026-01-16 21:43:27'),
(2, 'Razzaq', 'razzaq@gmail.com', '$2y$10$W2R6CR6B3B9BCnI21JaOKeBZq9S0bBnVeGuWasS.PE/vjV9AvcOBO', 'active', '2026-01-18 20:01:35'),
(3, 'Fiza Mehmood', 'fiza@gmail.com', '$2y$10$2XXFFjr9NkFJ5rkPDiKB9OpCYMg7PiWY2Rv6Id/LCkALow2//Yhma', 'active', '2026-01-22 19:26:21'),
(4, 'awab javed', 'awab@gmail.com', '$2y$10$NzM1MaBZhb5xjAHETyGGUOhSf3k5qtvJRnZILG0uxHP8c447NvZJS', 'active', '2026-02-10 10:48:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_answers`
--
ALTER TABLE `order_answers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_progress`
--
ALTER TABLE `order_progress`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_order_progress` (`order_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_questions`
--
ALTER TABLE `service_questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `team`
--
ALTER TABLE `team`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `team_members`
--
ALTER TABLE `team_members`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `chats`
--
ALTER TABLE `chats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `order_answers`
--
ALTER TABLE `order_answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_progress`
--
ALTER TABLE `order_progress`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `service_questions`
--
ALTER TABLE `service_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `team`
--
ALTER TABLE `team`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `team_members`
--
ALTER TABLE `team_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order_progress`
--
ALTER TABLE `order_progress`
  ADD CONSTRAINT `fk_order_progress` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
