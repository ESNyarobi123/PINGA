-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 14, 2026 at 09:37 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `Wingatz_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_audit_logs`
--

CREATE TABLE `admin_audit_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `model_type` varchar(255) DEFAULT NULL,
  `model_id` bigint(20) UNSIGNED DEFAULT NULL,
  `old_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`old_values`)),
  `new_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`new_values`)),
  `ip_address` varchar(255) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_audit_logs`
--

INSERT INTO `admin_audit_logs` (`id`, `admin_id`, `action`, `model_type`, `model_id`, `old_values`, `new_values`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES
(7, 62, 'approve_withdrawal', 'App\\Models\\WithdrawalRequest', 3, NULL, NULL, '197.250.51.209', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-04-21 20:00:05', '2026-04-21 20:00:05'),
(8, 62, 'complete_withdrawal', 'App\\Models\\WithdrawalRequest', 3, NULL, NULL, '197.250.51.209', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-04-21 20:01:09', '2026-04-21 20:01:09'),
(9, 62, 'reject_withdrawal', 'App\\Models\\WithdrawalRequest', 4, NULL, NULL, '197.250.51.209', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-04-21 20:02:55', '2026-04-21 20:02:55'),
(10, 62, 'reject_withdrawal', 'App\\Models\\WithdrawalRequest', 4, NULL, NULL, '197.250.51.209', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-04-21 20:03:04', '2026-04-21 20:03:04'),
(11, 62, 'reject_withdrawal', 'App\\Models\\WithdrawalRequest', 4, NULL, NULL, '197.250.51.209', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-04-21 20:03:13', '2026-04-21 20:03:13'),
(12, 62, 'approve_withdrawal', 'App\\Models\\WithdrawalRequest', 5, NULL, NULL, '197.250.51.209', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-04-21 20:08:50', '2026-04-21 20:08:50'),
(13, 62, 'complete_withdrawal', 'App\\Models\\WithdrawalRequest', 5, NULL, NULL, '197.250.51.209', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-04-21 20:08:58', '2026-04-21 20:08:58'),
(14, 62, 'reject_withdrawal', 'App\\Models\\WithdrawalRequest', 6, NULL, NULL, '197.250.51.209', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-04-21 20:09:43', '2026-04-21 20:09:43'),
(15, 62, 'reject_withdrawal', 'App\\Models\\WithdrawalRequest', 6, NULL, NULL, '197.250.51.209', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-04-21 20:10:00', '2026-04-21 20:10:00'),
(16, 62, 'reject_withdrawal', 'App\\Models\\WithdrawalRequest', 6, NULL, NULL, '197.250.51.209', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-04-21 20:10:13', '2026-04-21 20:10:13'),
(17, 62, 'reject_withdrawal', 'App\\Models\\WithdrawalRequest', 7, NULL, NULL, '197.250.51.209', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-04-21 20:12:52', '2026-04-21 20:12:52'),
(18, 62, 'reset_completion_code', 'App\\Models\\Job', 38, NULL, NULL, '197.250.51.209', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-04-21 20:48:07', '2026-04-21 20:48:07'),
(19, 62, 'approve_job', 'App\\Models\\Job', 38, '{\"is_approved\":false}', '{\"is_approved\":true}', '197.250.51.209', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-04-21 20:48:15', '2026-04-21 20:48:15'),
(20, 62, 'cancel_job', 'App\\Models\\Job', 38, '{\"status\":\"cancelled\"}', '{\"status\":\"cancelled\"}', '197.250.51.209', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-04-21 21:39:29', '2026-04-21 21:39:29'),
(21, 62, 'reject_job', 'App\\Models\\Job', 37, '{\"is_approved\":false}', '{\"status\":\"cancelled\"}', '197.250.51.209', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-04-21 21:39:57', '2026-04-21 21:39:57'),
(22, 62, 'reject_job', 'App\\Models\\Job', 38, '{\"is_approved\":false}', '{\"status\":\"cancelled\"}', '197.250.51.209', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-04-21 21:39:57', '2026-04-21 21:39:57'),
(23, 62, 'reset_approval', 'App\\Models\\Job', 37, '{\"is_approved\":true}', '{\"is_approved\":false}', '197.250.51.209', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-04-21 21:40:13', '2026-04-21 21:40:13'),
(24, 62, 'reset_approval', 'App\\Models\\Job', 38, '{\"is_approved\":true}', '{\"is_approved\":false}', '197.250.51.209', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-04-21 21:40:32', '2026-04-21 21:40:32'),
(25, 62, 'reject_job', 'App\\Models\\Job', 37, '{\"is_approved\":false}', '{\"status\":\"cancelled\"}', '197.250.51.209', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-04-21 21:41:17', '2026-04-21 21:41:17'),
(26, 62, 'reject_job', 'App\\Models\\Job', 38, '{\"is_approved\":false}', '{\"status\":\"cancelled\"}', '197.250.51.209', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-04-21 21:41:17', '2026-04-21 21:41:17'),
(27, 62, 'approve_job', 'App\\Models\\Job', 37, '{\"is_approved\":false}', '{\"is_approved\":true}', '197.250.51.209', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-04-21 21:41:45', '2026-04-21 21:41:45'),
(28, 62, 'approve_job', 'App\\Models\\Job', 38, '{\"is_approved\":false}', '{\"is_approved\":true}', '197.250.51.209', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-04-21 21:41:45', '2026-04-21 21:41:45'),
(29, 62, 'delete_job', 'App\\Models\\Job', 37, NULL, NULL, '197.250.51.209', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-04-21 21:42:05', '2026-04-21 21:42:05'),
(30, 62, 'delete_job', 'App\\Models\\Job', 38, NULL, NULL, '197.250.51.209', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-04-21 21:42:05', '2026-04-21 21:42:05'),
(31, 62, 'update_payment_settings', NULL, NULL, NULL, NULL, '197.250.51.209', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-04-21 21:47:28', '2026-04-21 21:47:28'),
(32, 62, 'update_payment_settings', NULL, NULL, NULL, NULL, '197.250.51.209', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-04-21 21:47:35', '2026-04-21 21:47:35'),
(33, 62, 'update_payment_settings', NULL, NULL, NULL, NULL, '197.250.51.209', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-04-21 21:47:37', '2026-04-21 21:47:37'),
(34, 62, 'update_payment_settings', NULL, NULL, NULL, NULL, '197.250.51.209', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-04-21 21:47:40', '2026-04-21 21:47:40'),
(35, 62, 'update_payment_settings', NULL, NULL, NULL, NULL, '197.250.51.209', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-04-21 21:48:04', '2026-04-21 21:48:04'),
(36, 62, 'admin_reply', 'App\\Models\\Conversation', 1, NULL, NULL, '197.250.51.209', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-04-21 21:51:03', '2026-04-21 21:51:03'),
(37, 62, 'update_category', 'App\\Models\\Category', 10, '{\"id\":10,\"name\":\"Afya & Ustawi\",\"slug\":\"afya-ustawi\",\"icon\":\"\\ud83c\\udfe5\",\"description\":\"Huduma za afya, lishe, mazoezi\",\"color\":null,\"sort_order\":0,\"parent_id\":null,\"is_active\":true,\"created_at\":\"2026-03-18T21:53:12.000000Z\",\"updated_at\":\"2026-03-18T21:53:12.000000Z\"}', '{\"id\":10,\"name\":\"Health\",\"slug\":\"afya-ustawi\",\"icon\":\"\\ud83c\\udfe5\",\"description\":\"Huduma za afya, lishe, mazoezi\",\"color\":\"#0d9488\",\"sort_order\":0,\"parent_id\":null,\"is_active\":true,\"created_at\":\"2026-03-18T21:53:12.000000Z\",\"updated_at\":\"2026-04-21T21:59:09.000000Z\"}', '197.250.51.209', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-04-21 21:59:09', '2026-04-21 21:59:09'),
(38, 62, 'update_category', 'App\\Models\\Category', 10, '{\"id\":10,\"name\":\"Health\",\"slug\":\"afya-ustawi\",\"icon\":\"\\ud83c\\udfe5\",\"description\":\"Huduma za afya, lishe, mazoezi\",\"color\":\"#0d9488\",\"sort_order\":0,\"parent_id\":null,\"is_active\":true,\"created_at\":\"2026-03-18T21:53:12.000000Z\",\"updated_at\":\"2026-04-21T21:59:09.000000Z\"}', '{\"id\":10,\"name\":\"Health\",\"slug\":\"afya-ustawi\",\"icon\":\"\\ud83c\\udfe5\",\"description\":\"Huduma za afya, lishe, mazoezi\",\"color\":\"#0d9488\",\"sort_order\":0,\"parent_id\":null,\"is_active\":true,\"created_at\":\"2026-03-18T21:53:12.000000Z\",\"updated_at\":\"2026-04-21T21:59:09.000000Z\"}', '197.250.51.209', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-04-21 21:59:11', '2026-04-21 21:59:11'),
(39, 62, 'toggle_category_status', 'App\\Models\\Category', 10, NULL, NULL, '197.250.51.209', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-04-21 21:59:28', '2026-04-21 21:59:28'),
(40, 62, 'update_category', 'App\\Models\\Category', 10, '{\"id\":10,\"name\":\"Health\",\"slug\":\"afya-ustawi\",\"icon\":\"\\ud83c\\udfe5\",\"description\":\"Huduma za afya, lishe, mazoezi\",\"color\":\"#0d9488\",\"sort_order\":0,\"parent_id\":null,\"is_active\":false,\"created_at\":\"2026-03-18T21:53:12.000000Z\",\"updated_at\":\"2026-04-21T21:59:28.000000Z\"}', '{\"id\":10,\"name\":\"Health\",\"slug\":\"afya-ustawi\",\"icon\":\"\\ud83c\\udfe5\",\"description\":\"Huduma za afya, lishe, mazoezi\",\"color\":\"#0d9488\",\"sort_order\":0,\"parent_id\":null,\"is_active\":true,\"created_at\":\"2026-03-18T21:53:12.000000Z\",\"updated_at\":\"2026-04-21T22:02:35.000000Z\"}', '197.250.51.209', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-04-21 22:02:35', '2026-04-21 22:02:35'),
(41, 62, 'update_category', 'App\\Models\\Category', 2, '{\"id\":2,\"name\":\"Teknolojia & IT\",\"slug\":\"teknolojia-it\",\"icon\":\"\\ud83d\\udcbb\",\"description\":\"Programu, tovuti, simu na zaidi\",\"color\":null,\"sort_order\":0,\"parent_id\":null,\"is_active\":true,\"created_at\":\"2026-03-18T21:53:12.000000Z\",\"updated_at\":\"2026-03-18T21:53:12.000000Z\"}', '{\"id\":2,\"name\":\"Teknolojia & IT 1\",\"slug\":\"teknolojia-it\",\"icon\":\"\\ud83d\\udcbb\",\"description\":\"Programu, tovuti, simu na zaidi\",\"color\":\"#0d9488\",\"sort_order\":0,\"parent_id\":null,\"is_active\":true,\"created_at\":\"2026-03-18T21:53:12.000000Z\",\"updated_at\":\"2026-04-21T22:03:25.000000Z\"}', '197.250.51.209', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-04-21 22:03:25', '2026-04-21 22:03:25'),
(42, 62, 'update_category', 'App\\Models\\Category', 2, '{\"id\":2,\"name\":\"Teknolojia & IT 1\",\"slug\":\"teknolojia-it\",\"icon\":\"\\ud83d\\udcbb\",\"description\":\"Programu, tovuti, simu na zaidi\",\"color\":\"#0d9488\",\"sort_order\":0,\"parent_id\":null,\"is_active\":true,\"created_at\":\"2026-03-18T21:53:12.000000Z\",\"updated_at\":\"2026-04-21T22:03:25.000000Z\"}', '{\"id\":2,\"name\":\"Teknolojia & IT \",\"slug\":\"teknolojia-it\",\"icon\":\"\\ud83d\\udcbb\",\"description\":\"Programu, tovuti, simu na zaidi\",\"color\":\"#0d9488\",\"sort_order\":0,\"parent_id\":null,\"is_active\":false,\"created_at\":\"2026-03-18T21:53:12.000000Z\",\"updated_at\":\"2026-04-21T22:04:27.000000Z\"}', '197.250.51.209', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-04-21 22:04:27', '2026-04-21 22:04:27'),
(43, 62, 'update_category', 'App\\Models\\Category', 2, '{\"id\":2,\"name\":\"Teknolojia & IT \",\"slug\":\"teknolojia-it\",\"icon\":\"\\ud83d\\udcbb\",\"description\":\"Programu, tovuti, simu na zaidi\",\"color\":\"#0d9488\",\"sort_order\":0,\"parent_id\":null,\"is_active\":false,\"created_at\":\"2026-03-18T21:53:12.000000Z\",\"updated_at\":\"2026-04-21T22:04:27.000000Z\"}', '{\"id\":2,\"name\":\"Teknolojia & IT \",\"slug\":\"teknolojia-it\",\"icon\":\"\\ud83d\\udcbb\",\"description\":\"Programu, tovuti, simu na zaidi\",\"color\":\"#0d9488\",\"sort_order\":0,\"parent_id\":null,\"is_active\":true,\"created_at\":\"2026-03-18T21:53:12.000000Z\",\"updated_at\":\"2026-04-21T22:04:47.000000Z\"}', '197.250.51.209', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-04-21 22:04:47', '2026-04-21 22:04:47'),
(44, 62, 'toggle_category_status', 'App\\Models\\Category', 8, NULL, NULL, '197.250.51.209', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-04-21 22:06:24', '2026-04-21 22:06:24'),
(45, 62, 'update_category', 'App\\Models\\Category', 8, '{\"id\":8,\"name\":\"Usafiri & Ushirikishaji\",\"slug\":\"usafiri-ushirikishaji\",\"icon\":\"\\ud83d\\ude9a\",\"description\":\"Uwasilishaji, usafiri, logistics\",\"color\":null,\"sort_order\":0,\"parent_id\":null,\"is_active\":false,\"created_at\":\"2026-03-18T21:53:12.000000Z\",\"updated_at\":\"2026-04-21T22:06:24.000000Z\"}', '{\"id\":8,\"name\":\"Usafiri & Ushirikishaji\",\"slug\":\"usafiri-ushirikishaji\",\"icon\":\"\\ud83d\\ude9a\",\"description\":\"Uwasilishaji, usafiri, logistics\",\"color\":\"#0d9488\",\"sort_order\":0,\"parent_id\":null,\"is_active\":true,\"created_at\":\"2026-03-18T21:53:12.000000Z\",\"updated_at\":\"2026-04-21T22:06:56.000000Z\"}', '197.250.51.209', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', '2026-04-21 22:06:56', '2026-04-21 22:06:56'),
(46, 62, 'reset_completion_code', 'App\\Models\\Job', 36, NULL, NULL, '197.186.70.219', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-23 19:26:27', '2026-04-23 19:26:27'),
(47, 62, 'reset_completion_code', 'App\\Models\\Job', 36, NULL, NULL, '197.186.70.219', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-23 19:32:46', '2026-04-23 19:32:46'),
(48, 62, 'reset_completion_code', 'App\\Models\\Job', 36, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-23 17:08:11', '2026-04-23 17:08:11'),
(49, 62, 'update_payment_settings', NULL, NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-23 17:11:15', '2026-04-23 17:11:15'),
(50, 62, 'update_payment_settings', NULL, NULL, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-23 17:11:16', '2026-04-23 17:11:16'),
(51, 62, 'update_payment_settings', NULL, NULL, NULL, '{\"commission_rate\":\"11\",\"min_withdrawal\":\"1000\",\"subscription_prices\":{\"msingi\":\"15000\",\"kawaida\":\"45000\",\"bora\":\"120000\"}}', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-23 17:15:53', '2026-04-23 17:15:53'),
(52, 62, 'send_broadcast', 'App\\Models\\BroadcastMessage', 1, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-23 17:17:20', '2026-04-23 17:17:20'),
(53, 62, 'send_broadcast', 'App\\Models\\BroadcastMessage', 2, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-23 17:27:36', '2026-04-23 17:27:36'),
(54, 62, 'send_broadcast', 'App\\Models\\BroadcastMessage', 3, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
(55, 62, 'create_category', 'App\\Models\\Category', 15, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-23 17:48:41', '2026-04-23 17:48:41'),
(56, 62, 'delete_category', 'App\\Models\\Category', 15, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-23 17:48:51', '2026-04-23 17:48:51'),
(57, 62, 'update_category', 'App\\Models\\Category', 9, '{\"id\":9,\"name\":\"Elimu & Mafunzo\",\"slug\":\"elimu-mafunzo\",\"icon\":\"\\ud83d\\udcda\",\"description\":\"Taaluma, lugha, stadi za kazi\",\"color\":null,\"sort_order\":0,\"parent_id\":null,\"is_active\":true,\"created_at\":\"2026-03-19T00:53:12.000000Z\",\"updated_at\":\"2026-03-19T00:53:12.000000Z\"}', '{\"id\":9,\"name\":\"Elimu & MafunzO\",\"slug\":\"elimu-mafunzo\",\"icon\":\"\\ud83d\\udcda\",\"description\":\"Taaluma, lugha, stadi za kazi\",\"color\":\"#0d9488\",\"sort_order\":0,\"parent_id\":null,\"is_active\":true,\"created_at\":\"2026-03-19T00:53:12.000000Z\",\"updated_at\":\"2026-04-23T20:51:03.000000Z\"}', '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-04-23 17:51:03', '2026-04-23 17:51:03'),
(58, 62, 'reject_withdrawal', 'App\\Models\\WithdrawalRequest', 8, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-02 12:23:25', '2026-05-02 12:23:25'),
(59, 62, 'reject_withdrawal', 'App\\Models\\WithdrawalRequest', 9, NULL, NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-02 12:35:44', '2026-05-02 12:35:44');

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `job_id` bigint(20) UNSIGNED NOT NULL,
  `worker_id` bigint(20) UNSIGNED NOT NULL,
  `cover_letter` text DEFAULT NULL,
  `proposed_budget` decimal(12,2) DEFAULT NULL,
  `proposed_duration` varchar(255) DEFAULT NULL,
  `status` enum('pending','accepted','rejected','withdrawn') NOT NULL DEFAULT 'pending',
  `rejection_comment` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`id`, `job_id`, `worker_id`, `cover_letter`, `proposed_budget`, `proposed_duration`, `status`, `rejection_comment`, `created_at`, `updated_at`) VALUES
(1, 35, 60, 'HII kazi naiweza kuifanya vizuri kabisa naomba hii nafasi ok', 400000.00, 'siku 2', 'rejected', NULL, '2026-03-19 04:48:20', '2026-03-19 04:49:11'),
(2, 34, 60, 'Tuma Ombi la Kazi\n\nJaza maelezo yako kumshawishi mte\n\nTuma Ombi la Kazi\n\nJaza maelezo yako kumshawishi mte', 800000.00, 'wiki 1', 'rejected', NULL, '2026-03-19 04:50:41', '2026-03-19 05:25:16'),
(3, 35, 64, 'Jaza maelezo yako kumshawishi \nJaza maelezo yako kumshawishi \nJaza maelezo yako kumshawishi \nJaza maelezo yako kumshawishi \nJaza maelezo yako kumshawishi ', 400000.00, '1 wiki', 'accepted', NULL, '2026-03-19 05:22:00', '2026-03-19 05:24:54'),
(4, 34, 64, 'Jaza maelezo yako kumshawishi ,Jaza maelezo yako kumshawishi ,Jaza maelezo yako kumshawishi ,Jaza maelezo yako kumshawishi ', 800000.00, '2 week', 'pending', NULL, '2026-03-19 06:10:10', '2026-03-19 06:10:10'),
(5, 29, 66, 'Ninatafuta mtaalamu wa lishe na mazoezi kutayarisha mpango wangu wa kupoteza ', 200000.00, '2 hours', 'pending', NULL, '2026-03-20 08:54:36', '2026-03-20 08:54:36'),
(6, 23, 60, 'Jaza maelezo yako kumshawishi mteja.\nJaza maelezo yako kumshawishi mteja.\nJaza maelezo yako kumshawishi mteja.', 1000.00, '1 day', 'accepted', NULL, '2026-03-21 09:37:39', '2026-03-21 09:38:04'),
(7, 36, 60, 'nataka kutengenezewa picha ya mwanamke wa kiafrika akiwa anachota maji na huku kabeba mtoto ', 10000.00, '1 day', 'accepted', NULL, '2026-03-24 01:36:42', '2026-03-24 01:36:58'),
(8, 32, 60, 'Fill in your details to convince the clientFill in your details to convince the clientFill in your details to convince the client', 80000.00, '3day', 'accepted', NULL, '2026-03-29 13:35:30', '2026-03-29 13:36:06');

-- --------------------------------------------------------

--
-- Table structure for table `broadcast_messages`
--

CREATE TABLE `broadcast_messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `admin_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `body` longtext NOT NULL,
  `announcement_type` varchar(32) DEFAULT NULL,
  `channels` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`channels`)),
  `target_type` enum('all','wingas','wateja','subscribed','mkoa','individual') NOT NULL,
  `target_segments` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`target_segments`)),
  `target_value` varchar(255) DEFAULT NULL,
  `scheduled_at` timestamp NULL DEFAULT NULL,
  `sent_at` timestamp NULL DEFAULT NULL,
  `recipient_count` int(11) NOT NULL DEFAULT 0,
  `status` enum('draft','scheduled','sent','failed') NOT NULL DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `broadcast_messages`
--

INSERT INTO `broadcast_messages` (`id`, `admin_id`, `title`, `body`, `announcement_type`, `channels`, `target_type`, `target_segments`, `target_value`, `scheduled_at`, `sent_at`, `recipient_count`, `status`, `created_at`, `updated_at`) VALUES
(1, 62, 'Welcome', 'Welcome to uor community', NULL, '[\"app\"]', 'all', NULL, NULL, NULL, '2026-04-23 17:17:20', 0, 'sent', '2026-04-23 17:17:20', '2026-04-23 17:17:20'),
(2, 62, 'Welcome', 'Welcome to uor community', 'announcement', '[\"app\"]', 'all', '[\"all\"]', NULL, NULL, '2026-04-23 17:27:36', 292, 'sent', '2026-04-23 17:27:36', '2026-04-23 17:27:36'),
(3, 62, 'Welcome', 'Welcome to uor community', 'announcement', '[\"app\"]', 'all', '[\"all\"]', NULL, NULL, '2026-04-23 17:39:50', 292, 'sent', '2026-04-23 17:39:50', '2026-04-23 17:39:50');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('winga-cache-1a1162ec85b1d21244d7d3ecba5bc65878b73777', 'i:1;', 1776924016),
('winga-cache-1a1162ec85b1d21244d7d3ecba5bc65878b73777:timer', 'i:1776924016;', 1776924016),
('winga-cache-boost.roster.scan', 'a:2:{s:6:\"roster\";O:21:\"Laravel\\Roster\\Roster\":3:{s:13:\"\0*\0approaches\";O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:23:\"Laravel\\Roster\\Approach\":1:{s:11:\"\0*\0approach\";E:38:\"Laravel\\Roster\\Enums\\Approaches:ACTION\";}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:11:\"\0*\0packages\";O:32:\"Laravel\\Roster\\PackageCollection\":2:{s:8:\"\0*\0items\";a:13:{i:0;O:22:\"Laravel\\Roster\\Package\":8:{s:9:\"\0*\0direct\";b:1;s:13:\"\0*\0constraint\";s:5:\"^1.30\";s:9:\"\0*\0source\";E:43:\"Laravel\\Roster\\Enums\\PackageSource:COMPOSER\";s:10:\"\0*\0package\";E:37:\"Laravel\\Roster\\Enums\\Packages:FORTIFY\";s:14:\"\0*\0packageName\";s:15:\"laravel/fortify\";s:10:\"\0*\0version\";s:6:\"1.35.0\";s:6:\"\0*\0dev\";b:0;s:7:\"\0*\0path\";s:48:\"/Users/eunice/WORKS/PINGA/vendor/laravel/fortify\";}i:1;O:22:\"Laravel\\Roster\\Package\":8:{s:9:\"\0*\0direct\";b:1;s:13:\"\0*\0constraint\";s:5:\"^12.0\";s:9:\"\0*\0source\";r:13;s:10:\"\0*\0package\";E:37:\"Laravel\\Roster\\Enums\\Packages:LARAVEL\";s:14:\"\0*\0packageName\";s:17:\"laravel/framework\";s:10:\"\0*\0version\";s:7:\"12.53.0\";s:6:\"\0*\0dev\";b:0;s:7:\"\0*\0path\";s:50:\"/Users/eunice/WORKS/PINGA/vendor/laravel/framework\";}i:2;O:22:\"Laravel\\Roster\\Package\":8:{s:9:\"\0*\0direct\";b:0;s:13:\"\0*\0constraint\";s:7:\"v0.3.13\";s:9:\"\0*\0source\";r:13;s:10:\"\0*\0package\";E:37:\"Laravel\\Roster\\Enums\\Packages:PROMPTS\";s:14:\"\0*\0packageName\";s:15:\"laravel/prompts\";s:10:\"\0*\0version\";s:6:\"0.3.13\";s:6:\"\0*\0dev\";b:0;s:7:\"\0*\0path\";s:48:\"/Users/eunice/WORKS/PINGA/vendor/laravel/prompts\";}i:3;O:22:\"Laravel\\Roster\\Package\":8:{s:9:\"\0*\0direct\";b:1;s:13:\"\0*\0constraint\";s:6:\"^2.9.0\";s:9:\"\0*\0source\";r:13;s:10:\"\0*\0package\";E:41:\"Laravel\\Roster\\Enums\\Packages:FLUXUI_FREE\";s:14:\"\0*\0packageName\";s:13:\"livewire/flux\";s:10:\"\0*\0version\";s:6:\"2.13.0\";s:6:\"\0*\0dev\";b:0;s:7:\"\0*\0path\";s:46:\"/Users/eunice/WORKS/PINGA/vendor/livewire/flux\";}i:4;O:22:\"Laravel\\Roster\\Package\":8:{s:9:\"\0*\0direct\";b:1;s:13:\"\0*\0constraint\";s:4:\"^4.0\";s:9:\"\0*\0source\";r:13;s:10:\"\0*\0package\";E:38:\"Laravel\\Roster\\Enums\\Packages:LIVEWIRE\";s:14:\"\0*\0packageName\";s:17:\"livewire/livewire\";s:10:\"\0*\0version\";s:5:\"4.2.1\";s:6:\"\0*\0dev\";b:0;s:7:\"\0*\0path\";s:50:\"/Users/eunice/WORKS/PINGA/vendor/livewire/livewire\";}i:5;O:22:\"Laravel\\Roster\\Package\":8:{s:9:\"\0*\0direct\";b:1;s:13:\"\0*\0constraint\";s:4:\"^2.0\";s:9:\"\0*\0source\";r:13;s:10:\"\0*\0package\";E:35:\"Laravel\\Roster\\Enums\\Packages:BOOST\";s:14:\"\0*\0packageName\";s:13:\"laravel/boost\";s:10:\"\0*\0version\";s:5:\"2.2.3\";s:6:\"\0*\0dev\";b:1;s:7:\"\0*\0path\";s:46:\"/Users/eunice/WORKS/PINGA/vendor/laravel/boost\";}i:6;O:22:\"Laravel\\Roster\\Package\":8:{s:9:\"\0*\0direct\";b:0;s:13:\"\0*\0constraint\";s:6:\"v0.6.0\";s:9:\"\0*\0source\";r:13;s:10:\"\0*\0package\";E:33:\"Laravel\\Roster\\Enums\\Packages:MCP\";s:14:\"\0*\0packageName\";s:11:\"laravel/mcp\";s:10:\"\0*\0version\";s:5:\"0.6.0\";s:6:\"\0*\0dev\";b:1;s:7:\"\0*\0path\";s:44:\"/Users/eunice/WORKS/PINGA/vendor/laravel/mcp\";}i:7;O:22:\"Laravel\\Roster\\Package\":8:{s:9:\"\0*\0direct\";b:1;s:13:\"\0*\0constraint\";s:6:\"^1.2.2\";s:9:\"\0*\0source\";r:13;s:10:\"\0*\0package\";E:34:\"Laravel\\Roster\\Enums\\Packages:PAIL\";s:14:\"\0*\0packageName\";s:12:\"laravel/pail\";s:10:\"\0*\0version\";s:5:\"1.2.6\";s:6:\"\0*\0dev\";b:1;s:7:\"\0*\0path\";s:45:\"/Users/eunice/WORKS/PINGA/vendor/laravel/pail\";}i:8;O:22:\"Laravel\\Roster\\Package\":8:{s:9:\"\0*\0direct\";b:1;s:13:\"\0*\0constraint\";s:5:\"^1.24\";s:9:\"\0*\0source\";r:13;s:10:\"\0*\0package\";E:34:\"Laravel\\Roster\\Enums\\Packages:PINT\";s:14:\"\0*\0packageName\";s:12:\"laravel/pint\";s:10:\"\0*\0version\";s:6:\"1.27.1\";s:6:\"\0*\0dev\";b:1;s:7:\"\0*\0path\";s:45:\"/Users/eunice/WORKS/PINGA/vendor/laravel/pint\";}i:9;O:22:\"Laravel\\Roster\\Package\":8:{s:9:\"\0*\0direct\";b:1;s:13:\"\0*\0constraint\";s:5:\"^1.41\";s:9:\"\0*\0source\";r:13;s:10:\"\0*\0package\";E:34:\"Laravel\\Roster\\Enums\\Packages:SAIL\";s:14:\"\0*\0packageName\";s:12:\"laravel/sail\";s:10:\"\0*\0version\";s:6:\"1.53.0\";s:6:\"\0*\0dev\";b:1;s:7:\"\0*\0path\";s:45:\"/Users/eunice/WORKS/PINGA/vendor/laravel/sail\";}i:10;O:22:\"Laravel\\Roster\\Package\":8:{s:9:\"\0*\0direct\";b:1;s:13:\"\0*\0constraint\";s:4:\"^4.4\";s:9:\"\0*\0source\";r:13;s:10:\"\0*\0package\";E:34:\"Laravel\\Roster\\Enums\\Packages:PEST\";s:14:\"\0*\0packageName\";s:12:\"pestphp/pest\";s:10:\"\0*\0version\";s:5:\"4.4.1\";s:6:\"\0*\0dev\";b:1;s:7:\"\0*\0path\";s:45:\"/Users/eunice/WORKS/PINGA/vendor/pestphp/pest\";}i:11;O:22:\"Laravel\\Roster\\Package\":8:{s:9:\"\0*\0direct\";b:0;s:13:\"\0*\0constraint\";s:7:\"12.5.12\";s:9:\"\0*\0source\";r:13;s:10:\"\0*\0package\";E:37:\"Laravel\\Roster\\Enums\\Packages:PHPUNIT\";s:14:\"\0*\0packageName\";s:15:\"phpunit/phpunit\";s:10:\"\0*\0version\";s:7:\"12.5.12\";s:6:\"\0*\0dev\";b:1;s:7:\"\0*\0path\";s:48:\"/Users/eunice/WORKS/PINGA/vendor/phpunit/phpunit\";}i:12;O:22:\"Laravel\\Roster\\Package\":8:{s:9:\"\0*\0direct\";b:1;s:13:\"\0*\0constraint\";s:6:\"^4.0.7\";s:9:\"\0*\0source\";E:38:\"Laravel\\Roster\\Enums\\PackageSource:NPM\";s:10:\"\0*\0package\";E:41:\"Laravel\\Roster\\Enums\\Packages:TAILWINDCSS\";s:14:\"\0*\0packageName\";s:11:\"tailwindcss\";s:10:\"\0*\0version\";s:5:\"4.2.1\";s:6:\"\0*\0dev\";b:0;s:7:\"\0*\0path\";s:50:\"/Users/eunice/WORKS/PINGA/node_modules/tailwindcss\";}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:21:\"\0*\0nodePackageManager\";E:43:\"Laravel\\Roster\\Enums\\NodePackageManager:NPM\";}s:9:\"timestamp\";i:1776978150;}', 1777064550),
('winga-cache-e6c3dd630428fd54834172b8fd2735fed9416da4', 'i:2;', 1777325651),
('winga-cache-e6c3dd630428fd54834172b8fd2735fed9416da4:timer', 'i:1777325651;', 1777325651),
('winga-cache-platform_setting:payment.auto_release_days', 'O:26:\"App\\Models\\PlatformSetting\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:17:\"platform_settings\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:9:{s:2:\"id\";i:48;s:3:\"key\";s:25:\"payment.auto_release_days\";s:5:\"value\";s:1:\"7\";s:4:\"type\";s:6:\"string\";s:5:\"group\";s:7:\"general\";s:11:\"description\";N;s:10:\"updated_by\";N;s:10:\"created_at\";s:19:\"2026-04-22 00:47:28\";s:10:\"updated_at\";s:19:\"2026-04-22 00:47:28\";}s:11:\"\0*\0original\";a:9:{s:2:\"id\";i:48;s:3:\"key\";s:25:\"payment.auto_release_days\";s:5:\"value\";s:1:\"7\";s:4:\"type\";s:6:\"string\";s:5:\"group\";s:7:\"general\";s:11:\"description\";N;s:10:\"updated_by\";N;s:10:\"created_at\";s:19:\"2026-04-22 00:47:28\";s:10:\"updated_at\";s:19:\"2026-04-22 00:47:28\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:4:{s:5:\"value\";s:6:\"string\";s:4:\"type\";s:6:\"string\";s:5:\"group\";s:6:\"string\";s:11:\"description\";s:6:\"string\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:6:{i:0;s:3:\"key\";i:1;s:5:\"value\";i:2;s:4:\"type\";i:3;s:5:\"group\";i:4;s:11:\"description\";i:5;s:10:\"updated_by\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}', 1777756989),
('winga-cache-platform_setting:payment.commission_rate', 'O:26:\"App\\Models\\PlatformSetting\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:17:\"platform_settings\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:9:{s:2:\"id\";i:44;s:3:\"key\";s:23:\"payment.commission_rate\";s:5:\"value\";s:2:\"11\";s:4:\"type\";s:6:\"string\";s:5:\"group\";s:7:\"general\";s:11:\"description\";N;s:10:\"updated_by\";N;s:10:\"created_at\";s:19:\"2026-04-22 00:47:28\";s:10:\"updated_at\";s:19:\"2026-04-23 20:15:53\";}s:11:\"\0*\0original\";a:9:{s:2:\"id\";i:44;s:3:\"key\";s:23:\"payment.commission_rate\";s:5:\"value\";s:2:\"11\";s:4:\"type\";s:6:\"string\";s:5:\"group\";s:7:\"general\";s:11:\"description\";N;s:10:\"updated_by\";N;s:10:\"created_at\";s:19:\"2026-04-22 00:47:28\";s:10:\"updated_at\";s:19:\"2026-04-23 20:15:53\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:4:{s:5:\"value\";s:6:\"string\";s:4:\"type\";s:6:\"string\";s:5:\"group\";s:6:\"string\";s:11:\"description\";s:6:\"string\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:6:{i:0;s:3:\"key\";i:1;s:5:\"value\";i:2;s:4:\"type\";i:3;s:5:\"group\";i:4;s:11:\"description\";i:5;s:10:\"updated_by\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}', 1777756989),
('winga-cache-platform_setting:payment.max_withdrawal_daily', 'O:26:\"App\\Models\\PlatformSetting\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:17:\"platform_settings\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:9:{s:2:\"id\";i:46;s:3:\"key\";s:28:\"payment.max_withdrawal_daily\";s:5:\"value\";s:7:\"1000000\";s:4:\"type\";s:6:\"string\";s:5:\"group\";s:7:\"general\";s:11:\"description\";N;s:10:\"updated_by\";N;s:10:\"created_at\";s:19:\"2026-04-22 00:47:28\";s:10:\"updated_at\";s:19:\"2026-04-22 00:47:28\";}s:11:\"\0*\0original\";a:9:{s:2:\"id\";i:46;s:3:\"key\";s:28:\"payment.max_withdrawal_daily\";s:5:\"value\";s:7:\"1000000\";s:4:\"type\";s:6:\"string\";s:5:\"group\";s:7:\"general\";s:11:\"description\";N;s:10:\"updated_by\";N;s:10:\"created_at\";s:19:\"2026-04-22 00:47:28\";s:10:\"updated_at\";s:19:\"2026-04-22 00:47:28\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:4:{s:5:\"value\";s:6:\"string\";s:4:\"type\";s:6:\"string\";s:5:\"group\";s:6:\"string\";s:11:\"description\";s:6:\"string\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:6:{i:0;s:3:\"key\";i:1;s:5:\"value\";i:2;s:4:\"type\";i:3;s:5:\"group\";i:4;s:11:\"description\";i:5;s:10:\"updated_by\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}', 1777756989),
('winga-cache-platform_setting:payment.min_deposit', 'O:26:\"App\\Models\\PlatformSetting\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:17:\"platform_settings\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:9:{s:2:\"id\";i:47;s:3:\"key\";s:19:\"payment.min_deposit\";s:5:\"value\";s:4:\"1000\";s:4:\"type\";s:6:\"string\";s:5:\"group\";s:7:\"general\";s:11:\"description\";N;s:10:\"updated_by\";N;s:10:\"created_at\";s:19:\"2026-04-22 00:47:28\";s:10:\"updated_at\";s:19:\"2026-04-22 00:47:28\";}s:11:\"\0*\0original\";a:9:{s:2:\"id\";i:47;s:3:\"key\";s:19:\"payment.min_deposit\";s:5:\"value\";s:4:\"1000\";s:4:\"type\";s:6:\"string\";s:5:\"group\";s:7:\"general\";s:11:\"description\";N;s:10:\"updated_by\";N;s:10:\"created_at\";s:19:\"2026-04-22 00:47:28\";s:10:\"updated_at\";s:19:\"2026-04-22 00:47:28\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:4:{s:5:\"value\";s:6:\"string\";s:4:\"type\";s:6:\"string\";s:5:\"group\";s:6:\"string\";s:11:\"description\";s:6:\"string\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:6:{i:0;s:3:\"key\";i:1;s:5:\"value\";i:2;s:4:\"type\";i:3;s:5:\"group\";i:4;s:11:\"description\";i:5;s:10:\"updated_by\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}', 1777756989),
('winga-cache-platform_setting:payment.min_withdrawal', 'O:26:\"App\\Models\\PlatformSetting\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:17:\"platform_settings\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:9:{s:2:\"id\";i:45;s:3:\"key\";s:22:\"payment.min_withdrawal\";s:5:\"value\";s:4:\"1000\";s:4:\"type\";s:6:\"string\";s:5:\"group\";s:7:\"general\";s:11:\"description\";N;s:10:\"updated_by\";N;s:10:\"created_at\";s:19:\"2026-04-22 00:47:28\";s:10:\"updated_at\";s:19:\"2026-04-22 00:47:28\";}s:11:\"\0*\0original\";a:9:{s:2:\"id\";i:45;s:3:\"key\";s:22:\"payment.min_withdrawal\";s:5:\"value\";s:4:\"1000\";s:4:\"type\";s:6:\"string\";s:5:\"group\";s:7:\"general\";s:11:\"description\";N;s:10:\"updated_by\";N;s:10:\"created_at\";s:19:\"2026-04-22 00:47:28\";s:10:\"updated_at\";s:19:\"2026-04-22 00:47:28\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:4:{s:5:\"value\";s:6:\"string\";s:4:\"type\";s:6:\"string\";s:5:\"group\";s:6:\"string\";s:11:\"description\";s:6:\"string\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:6:{i:0;s:3:\"key\";i:1;s:5:\"value\";i:2;s:4:\"type\";i:3;s:5:\"group\";i:4;s:11:\"description\";i:5;s:10:\"updated_by\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}', 1777756989),
('winga-cache-platform_setting:payment.payout_delay_hours', 'O:26:\"App\\Models\\PlatformSetting\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:17:\"platform_settings\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:9:{s:2:\"id\";i:49;s:3:\"key\";s:26:\"payment.payout_delay_hours\";s:5:\"value\";s:2:\"24\";s:4:\"type\";s:6:\"string\";s:5:\"group\";s:7:\"general\";s:11:\"description\";N;s:10:\"updated_by\";N;s:10:\"created_at\";s:19:\"2026-04-22 00:47:28\";s:10:\"updated_at\";s:19:\"2026-04-22 00:47:28\";}s:11:\"\0*\0original\";a:9:{s:2:\"id\";i:49;s:3:\"key\";s:26:\"payment.payout_delay_hours\";s:5:\"value\";s:2:\"24\";s:4:\"type\";s:6:\"string\";s:5:\"group\";s:7:\"general\";s:11:\"description\";N;s:10:\"updated_by\";N;s:10:\"created_at\";s:19:\"2026-04-22 00:47:28\";s:10:\"updated_at\";s:19:\"2026-04-22 00:47:28\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:4:{s:5:\"value\";s:6:\"string\";s:4:\"type\";s:6:\"string\";s:5:\"group\";s:6:\"string\";s:11:\"description\";s:6:\"string\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:6:{i:0;s:3:\"key\";i:1;s:5:\"value\";i:2;s:4:\"type\";i:3;s:5:\"group\";i:4;s:11:\"description\";i:5;s:10:\"updated_by\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}', 1777756989),
('winga-cache-platform_setting:platform_commission_rate', 'O:26:\"App\\Models\\PlatformSetting\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:17:\"platform_settings\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:9:{s:2:\"id\";i:9;s:3:\"key\";s:24:\"platform_commission_rate\";s:5:\"value\";s:2:\"10\";s:4:\"type\";s:5:\"float\";s:5:\"group\";s:7:\"payment\";s:11:\"description\";s:30:\"Platform commission percentage\";s:10:\"updated_by\";N;s:10:\"created_at\";N;s:10:\"updated_at\";N;}s:11:\"\0*\0original\";a:9:{s:2:\"id\";i:9;s:3:\"key\";s:24:\"platform_commission_rate\";s:5:\"value\";s:2:\"10\";s:4:\"type\";s:5:\"float\";s:5:\"group\";s:7:\"payment\";s:11:\"description\";s:30:\"Platform commission percentage\";s:10:\"updated_by\";N;s:10:\"created_at\";N;s:10:\"updated_at\";N;}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:4:{s:5:\"value\";s:6:\"string\";s:4:\"type\";s:6:\"string\";s:5:\"group\";s:6:\"string\";s:11:\"description\";s:6:\"string\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:6:{i:0;s:3:\"key\";i:1;s:5:\"value\";i:2;s:4:\"type\";i:3;s:5:\"group\";i:4;s:11:\"description\";i:5;s:10:\"updated_by\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}', 1777762043),
('winga-cache-platform_setting:subscription.bora_price', 'O:26:\"App\\Models\\PlatformSetting\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:17:\"platform_settings\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:9:{s:2:\"id\";i:52;s:3:\"key\";s:23:\"subscription.bora_price\";s:5:\"value\";s:6:\"120000\";s:4:\"type\";s:6:\"string\";s:5:\"group\";s:7:\"general\";s:11:\"description\";N;s:10:\"updated_by\";N;s:10:\"created_at\";s:19:\"2026-04-22 00:47:28\";s:10:\"updated_at\";s:19:\"2026-04-22 00:47:28\";}s:11:\"\0*\0original\";a:9:{s:2:\"id\";i:52;s:3:\"key\";s:23:\"subscription.bora_price\";s:5:\"value\";s:6:\"120000\";s:4:\"type\";s:6:\"string\";s:5:\"group\";s:7:\"general\";s:11:\"description\";N;s:10:\"updated_by\";N;s:10:\"created_at\";s:19:\"2026-04-22 00:47:28\";s:10:\"updated_at\";s:19:\"2026-04-22 00:47:28\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:4:{s:5:\"value\";s:6:\"string\";s:4:\"type\";s:6:\"string\";s:5:\"group\";s:6:\"string\";s:11:\"description\";s:6:\"string\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:6:{i:0;s:3:\"key\";i:1;s:5:\"value\";i:2;s:4:\"type\";i:3;s:5:\"group\";i:4;s:11:\"description\";i:5;s:10:\"updated_by\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}', 1777756989),
('winga-cache-platform_setting:subscription.kawaida_price', 'O:26:\"App\\Models\\PlatformSetting\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:17:\"platform_settings\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:9:{s:2:\"id\";i:51;s:3:\"key\";s:26:\"subscription.kawaida_price\";s:5:\"value\";s:5:\"45000\";s:4:\"type\";s:6:\"string\";s:5:\"group\";s:7:\"general\";s:11:\"description\";N;s:10:\"updated_by\";N;s:10:\"created_at\";s:19:\"2026-04-22 00:47:28\";s:10:\"updated_at\";s:19:\"2026-04-22 00:47:28\";}s:11:\"\0*\0original\";a:9:{s:2:\"id\";i:51;s:3:\"key\";s:26:\"subscription.kawaida_price\";s:5:\"value\";s:5:\"45000\";s:4:\"type\";s:6:\"string\";s:5:\"group\";s:7:\"general\";s:11:\"description\";N;s:10:\"updated_by\";N;s:10:\"created_at\";s:19:\"2026-04-22 00:47:28\";s:10:\"updated_at\";s:19:\"2026-04-22 00:47:28\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:4:{s:5:\"value\";s:6:\"string\";s:4:\"type\";s:6:\"string\";s:5:\"group\";s:6:\"string\";s:11:\"description\";s:6:\"string\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:6:{i:0;s:3:\"key\";i:1;s:5:\"value\";i:2;s:4:\"type\";i:3;s:5:\"group\";i:4;s:11:\"description\";i:5;s:10:\"updated_by\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}', 1777756989),
('winga-cache-platform_setting:subscription.msingi_price', 'O:26:\"App\\Models\\PlatformSetting\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:17:\"platform_settings\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:9:{s:2:\"id\";i:50;s:3:\"key\";s:25:\"subscription.msingi_price\";s:5:\"value\";s:5:\"15000\";s:4:\"type\";s:6:\"string\";s:5:\"group\";s:7:\"general\";s:11:\"description\";N;s:10:\"updated_by\";N;s:10:\"created_at\";s:19:\"2026-04-22 00:47:28\";s:10:\"updated_at\";s:19:\"2026-04-22 00:47:28\";}s:11:\"\0*\0original\";a:9:{s:2:\"id\";i:50;s:3:\"key\";s:25:\"subscription.msingi_price\";s:5:\"value\";s:5:\"15000\";s:4:\"type\";s:6:\"string\";s:5:\"group\";s:7:\"general\";s:11:\"description\";N;s:10:\"updated_by\";N;s:10:\"created_at\";s:19:\"2026-04-22 00:47:28\";s:10:\"updated_at\";s:19:\"2026-04-22 00:47:28\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:4:{s:5:\"value\";s:6:\"string\";s:4:\"type\";s:6:\"string\";s:5:\"group\";s:6:\"string\";s:11:\"description\";s:6:\"string\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:6:{i:0;s:3:\"key\";i:1;s:5:\"value\";i:2;s:4:\"type\";i:3;s:5:\"group\";i:4;s:11:\"description\";i:5;s:10:\"updated_by\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}', 1777756989),
('winga-cache-platform_setting:withdrawal_charge_percent', 'N;', 1777762071);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `color` varchar(255) DEFAULT NULL,
  `sort_order` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `icon`, `description`, `color`, `sort_order`, `parent_id`, `is_active`, `created_at`, `updated_at`) VALUES
(2, 'Teknolojia & IT ', 'teknolojia-it', '💻', 'Programu, tovuti, simu na zaidi', '#0d9488', 0, NULL, 1, '2026-03-18 21:53:12', '2026-04-21 22:04:47'),
(3, 'Ubunifu & Sanaa', 'ubunifu-sanaa', '🎨', 'Michoro, logo, video na picha', NULL, 0, NULL, 1, '2026-03-18 21:53:12', '2026-03-18 21:53:12'),
(4, 'Uandishi & Tafsiri', 'uandishi-tafsiri', '✍️', 'Makala, blog, tafsiri na uhariri', NULL, 0, NULL, 1, '2026-03-18 21:53:12', '2026-03-18 21:53:12'),
(5, 'Masoko Dijitali', 'masoko-dijitali', '📱', 'SEO, mitandao ya kijamii, matangazo', NULL, 0, NULL, 1, '2026-03-18 21:53:12', '2026-03-18 21:53:12'),
(6, 'Usimamizi & Ofisi', 'usimamizi-ofisi', '📋', 'Uhasibu, data entry, msaada wa mteja', NULL, 0, NULL, 1, '2026-03-18 21:53:12', '2026-03-18 21:53:12'),
(7, 'Ujenzi & Ufundi', 'ujenzi-ufundi', '🔨', 'Ujenzi, umeme, mabomba, rangi', NULL, 0, NULL, 1, '2026-03-18 21:53:12', '2026-03-18 21:53:12'),
(8, 'Usafiri & Ushirikishaji', 'usafiri-ushirikishaji', '🚚', 'Uwasilishaji, usafiri, logistics', '#0d9488', 0, NULL, 1, '2026-03-18 21:53:12', '2026-04-21 22:06:56'),
(9, 'Elimu & MafunzO', 'elimu-mafunzo', '📚', 'Taaluma, lugha, stadi za kazi', '#0d9488', 0, NULL, 1, '2026-03-18 21:53:12', '2026-04-23 17:51:03'),
(10, 'Health', 'afya-ustawi', '🏥', 'Huduma za afya, lishe, mazoezi', '#0d9488', 0, NULL, 1, '2026-03-18 21:53:12', '2026-04-21 22:02:35'),
(11, 'Kilimo & Mazingira', 'kilimo-mazingira', '🌱', 'Kilimo, bustani, mazingira', NULL, 0, NULL, 1, '2026-03-18 21:53:12', '2026-03-18 21:53:12'),
(12, 'Nyumbani & Usafi', 'nyumbani-usafi', '🏠', 'Usafi, kupika, utunzaji wa nyumba', NULL, 0, NULL, 1, '2026-03-18 21:53:12', '2026-03-18 21:53:12'),
(13, 'Burudani & Matukio', 'burudani-matukio', '🎭', 'Muziki, DJ, MC, sherehe', NULL, 0, NULL, 1, '2026-03-18 21:53:12', '2026-03-18 21:53:12');

-- --------------------------------------------------------

--
-- Table structure for table `conversations`
--

CREATE TABLE `conversations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `job_id` bigint(20) UNSIGNED NOT NULL,
  `employer_id` bigint(20) UNSIGNED NOT NULL,
  `worker_id` bigint(20) UNSIGNED NOT NULL,
  `employer_last_read` timestamp NULL DEFAULT NULL,
  `worker_last_read` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `conversations`
--

INSERT INTO `conversations` (`id`, `job_id`, `employer_id`, `worker_id`, `employer_last_read`, `worker_last_read`, `created_at`, `updated_at`) VALUES
(1, 35, 61, 64, '2026-04-23 18:07:57', '2026-03-19 05:40:15', '2026-03-19 05:24:54', '2026-04-23 18:07:57'),
(2, 23, 61, 60, '2026-03-21 09:38:34', '2026-03-21 23:16:58', '2026-03-21 09:38:04', '2026-03-21 23:16:58'),
(3, 36, 61, 60, NULL, NULL, '2026-03-24 01:36:58', '2026-03-24 01:36:58'),
(4, 32, 61, 60, '2026-04-21 21:09:36', '2026-04-21 17:31:47', '2026-03-29 13:36:06', '2026-04-21 21:09:36');

-- --------------------------------------------------------

--
-- Table structure for table `disputes`
--

CREATE TABLE `disputes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `job_id` bigint(20) UNSIGNED NOT NULL,
  `initiator_id` bigint(20) UNSIGNED NOT NULL,
  `respondent_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('open','investigating','resolved','closed') NOT NULL DEFAULT 'open',
  `reason` text NOT NULL,
  `description` text DEFAULT NULL,
  `priority` enum('high','medium','low') NOT NULL DEFAULT 'medium',
  `escalated_at` timestamp NULL DEFAULT NULL,
  `auto_resolve_at` timestamp NULL DEFAULT NULL,
  `admin_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dispute_evidence`
--

CREATE TABLE `dispute_evidence` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dispute_id` bigint(20) UNSIGNED NOT NULL,
  `submitted_by` bigint(20) UNSIGNED NOT NULL,
  `content` text DEFAULT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`images`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `failed_jobs`
--

INSERT INTO `failed_jobs` (`id`, `uuid`, `connection`, `queue`, `payload`, `exception`, `failed_at`) VALUES
(1, '4b72a7cd-ddbb-4c42-9e0a-e52f3e61ae9d', 'database', 'default', '{\"uuid\":\"4b72a7cd-ddbb-4c42-9e0a-e52f3e61ae9d\",\"displayName\":\"App\\\\Jobs\\\\TranslateJobPosting\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"60\",\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\TranslateJobPosting\",\"command\":\"O:28:\\\"App\\\\Jobs\\\\TranslateJobPosting\\\":1:{s:10:\\\"jobPosting\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:14:\\\"App\\\\Models\\\\Job\\\";s:2:\\\"id\\\";i:37;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}}\",\"batchId\":null},\"createdAt\":1774327669,\"delay\":null}', 'Illuminate\\Database\\Eloquent\\ModelNotFoundException: No query results for model [App\\Models\\Job]. in /Users/eunice/WORKS/PINGA/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php:780\nStack trace:\n#0 /Users/eunice/WORKS/PINGA/vendor/laravel/framework/src/Illuminate/Queue/SerializesAndRestoresModelIdentifiers.php(112): Illuminate\\Database\\Eloquent\\Builder->firstOrFail()\n#1 /Users/eunice/WORKS/PINGA/vendor/laravel/framework/src/Illuminate/Queue/SerializesAndRestoresModelIdentifiers.php(63): App\\Jobs\\TranslateJobPosting->restoreModel(Object(Illuminate\\Contracts\\Database\\ModelIdentifier))\n#2 /Users/eunice/WORKS/PINGA/vendor/laravel/framework/src/Illuminate/Queue/SerializesModels.php(97): App\\Jobs\\TranslateJobPosting->getRestoredPropertyValue(Object(Illuminate\\Contracts\\Database\\ModelIdentifier))\n#3 [internal function]: App\\Jobs\\TranslateJobPosting->__unserialize(Array)\n#4 /Users/eunice/WORKS/PINGA/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(97): unserialize(\'O:28:\"App\\\\Jobs\\\\...\')\n#5 /Users/eunice/WORKS/PINGA/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(64): Illuminate\\Queue\\CallQueuedHandler->getCommand(Array)\n#6 /Users/eunice/WORKS/PINGA/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#7 /Users/eunice/WORKS/PINGA/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(485): Illuminate\\Queue\\Jobs\\Job->fire()\n#8 /Users/eunice/WORKS/PINGA/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(435): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#9 /Users/eunice/WORKS/PINGA/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(358): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#10 /Users/eunice/WORKS/PINGA/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(148): Illuminate\\Queue\\Worker->runNextJob(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#11 /Users/eunice/WORKS/PINGA/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#12 /Users/eunice/WORKS/PINGA/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#13 /Users/eunice/WORKS/PINGA/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::{closure:Illuminate\\Container\\BoundMethod::call():35}()\n#14 /Users/eunice/WORKS/PINGA/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#15 /Users/eunice/WORKS/PINGA/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#16 /Users/eunice/WORKS/PINGA/vendor/laravel/framework/src/Illuminate/Container/Container.php(799): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#17 /Users/eunice/WORKS/PINGA/vendor/laravel/framework/src/Illuminate/Console/Command.php(211): Illuminate\\Container\\Container->call(Array)\n#18 /Users/eunice/WORKS/PINGA/vendor/symfony/console/Command/Command.php(341): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#19 /Users/eunice/WORKS/PINGA/vendor/laravel/framework/src/Illuminate/Console/Command.php(180): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#20 /Users/eunice/WORKS/PINGA/vendor/symfony/console/Application.php(1117): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#21 /Users/eunice/WORKS/PINGA/vendor/symfony/console/Application.php(356): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#22 /Users/eunice/WORKS/PINGA/vendor/symfony/console/Application.php(195): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#23 /Users/eunice/WORKS/PINGA/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(198): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#24 /Users/eunice/WORKS/PINGA/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#25 /Users/eunice/WORKS/PINGA/artisan(16): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#26 {main}', '2026-05-13 17:06:57'),
(2, 'a4c95cdd-cdd9-417b-ba61-8a9f57d01d04', 'database', 'default', '{\"uuid\":\"a4c95cdd-cdd9-417b-ba61-8a9f57d01d04\",\"displayName\":\"App\\\\Jobs\\\\TranslateJobPosting\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"60\",\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\TranslateJobPosting\",\"command\":\"O:28:\\\"App\\\\Jobs\\\\TranslateJobPosting\\\":1:{s:10:\\\"jobPosting\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:14:\\\"App\\\\Models\\\\Job\\\";s:2:\\\"id\\\";i:38;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}}\",\"batchId\":null},\"createdAt\":1776803891,\"delay\":null}', 'Illuminate\\Database\\Eloquent\\ModelNotFoundException: No query results for model [App\\Models\\Job]. in /Users/eunice/WORKS/PINGA/vendor/laravel/framework/src/Illuminate/Database/Eloquent/Builder.php:780\nStack trace:\n#0 /Users/eunice/WORKS/PINGA/vendor/laravel/framework/src/Illuminate/Queue/SerializesAndRestoresModelIdentifiers.php(112): Illuminate\\Database\\Eloquent\\Builder->firstOrFail()\n#1 /Users/eunice/WORKS/PINGA/vendor/laravel/framework/src/Illuminate/Queue/SerializesAndRestoresModelIdentifiers.php(63): App\\Jobs\\TranslateJobPosting->restoreModel(Object(Illuminate\\Contracts\\Database\\ModelIdentifier))\n#2 /Users/eunice/WORKS/PINGA/vendor/laravel/framework/src/Illuminate/Queue/SerializesModels.php(97): App\\Jobs\\TranslateJobPosting->getRestoredPropertyValue(Object(Illuminate\\Contracts\\Database\\ModelIdentifier))\n#3 [internal function]: App\\Jobs\\TranslateJobPosting->__unserialize(Array)\n#4 /Users/eunice/WORKS/PINGA/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(97): unserialize(\'O:28:\"App\\\\Jobs\\\\...\')\n#5 /Users/eunice/WORKS/PINGA/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(64): Illuminate\\Queue\\CallQueuedHandler->getCommand(Array)\n#6 /Users/eunice/WORKS/PINGA/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#7 /Users/eunice/WORKS/PINGA/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(485): Illuminate\\Queue\\Jobs\\Job->fire()\n#8 /Users/eunice/WORKS/PINGA/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(435): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#9 /Users/eunice/WORKS/PINGA/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(358): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#10 /Users/eunice/WORKS/PINGA/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(148): Illuminate\\Queue\\Worker->runNextJob(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#11 /Users/eunice/WORKS/PINGA/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(131): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#12 /Users/eunice/WORKS/PINGA/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#13 /Users/eunice/WORKS/PINGA/vendor/laravel/framework/src/Illuminate/Container/Util.php(43): Illuminate\\Container\\BoundMethod::{closure:Illuminate\\Container\\BoundMethod::call():35}()\n#14 /Users/eunice/WORKS/PINGA/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#15 /Users/eunice/WORKS/PINGA/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#16 /Users/eunice/WORKS/PINGA/vendor/laravel/framework/src/Illuminate/Container/Container.php(799): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#17 /Users/eunice/WORKS/PINGA/vendor/laravel/framework/src/Illuminate/Console/Command.php(211): Illuminate\\Container\\Container->call(Array)\n#18 /Users/eunice/WORKS/PINGA/vendor/symfony/console/Command/Command.php(341): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#19 /Users/eunice/WORKS/PINGA/vendor/laravel/framework/src/Illuminate/Console/Command.php(180): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#20 /Users/eunice/WORKS/PINGA/vendor/symfony/console/Application.php(1117): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#21 /Users/eunice/WORKS/PINGA/vendor/symfony/console/Application.php(356): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#22 /Users/eunice/WORKS/PINGA/vendor/symfony/console/Application.php(195): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#23 /Users/eunice/WORKS/PINGA/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(198): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#24 /Users/eunice/WORKS/PINGA/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#25 /Users/eunice/WORKS/PINGA/artisan(16): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#26 {main}', '2026-05-13 17:06:58');

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `favorable_type` varchar(255) NOT NULL,
  `favorable_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_listings`
--

CREATE TABLE `job_listings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employer_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `title_en` text DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `description_en` text DEFAULT NULL,
  `requirements` text DEFAULT NULL,
  `requirements_en` text DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `budget_min` decimal(12,2) DEFAULT NULL,
  `budget_max` decimal(12,2) DEFAULT NULL,
  `budget_type` enum('fixed','hourly') NOT NULL DEFAULT 'fixed',
  `duration` varchar(255) DEFAULT NULL,
  `status` enum('draft','open','in_progress','completed','cancelled','disputed') NOT NULL DEFAULT 'draft',
  `approval_status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `rejection_reason` text DEFAULT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT 0,
  `translation_status` enum('pending','done','failed') NOT NULL DEFAULT 'pending',
  `approved_at` timestamp NULL DEFAULT NULL,
  `completion_code` varchar(255) DEFAULT NULL,
  `hold_status` enum('active','released') NOT NULL DEFAULT 'released',
  `hold_started_at` timestamp NULL DEFAULT NULL,
  `code_generated_at` timestamp NULL DEFAULT NULL,
  `code_used_at` timestamp NULL DEFAULT NULL,
  `code_hold_until` timestamp NULL DEFAULT NULL,
  `hold_comment` text DEFAULT NULL,
  `hold_extended` tinyint(1) NOT NULL DEFAULT 0,
  `rejection_comment` text DEFAULT NULL,
  `hired_worker_id` bigint(20) UNSIGNED DEFAULT NULL,
  `hired_at` timestamp NULL DEFAULT NULL,
  `urgency` enum('normal','urgent','very_urgent') NOT NULL DEFAULT 'normal',
  `remote_allowed` tinyint(1) NOT NULL DEFAULT 0,
  `views_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `applications_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `job_listings`
--

INSERT INTO `job_listings` (`id`, `employer_id`, `category_id`, `title`, `title_en`, `slug`, `description`, `description_en`, `requirements`, `requirements_en`, `location`, `latitude`, `longitude`, `budget_min`, `budget_max`, `budget_type`, `duration`, `status`, `approval_status`, `rejection_reason`, `is_approved`, `translation_status`, `approved_at`, `completion_code`, `hold_status`, `hold_started_at`, `code_generated_at`, `code_used_at`, `code_hold_until`, `hold_comment`, `hold_extended`, `rejection_comment`, `hired_worker_id`, `hired_at`, `urgency`, `remote_allowed`, `views_count`, `applications_count`, `created_at`, `updated_at`, `approved_by`) VALUES
(21, 61, 2, 'Creating an E-commerce Website edited', 'Creating an E-commerce Website edited', 'kutengeneza-tovuti-ya-duka-la-mtandao-Yxye4t', 'I need a professional to create an e-commerce website for my business. The website should have a products page, shopping cart, and payment system. Please use Laravel and Vue.js. I prefer a modern design that works well on mobile devices. Please show me your previous work. edited', 'I need a professional to create an e-commerce website for my business. The website should have a products page, shopping cart, and payment system. Please use Laravel and Vue.js. I prefer a modern design that works well on mobile devices. Please show me your previous work. edited', NULL, NULL, 'Remote f', NULL, NULL, 800000.00, 28000000.00, 'fixed', 'miezi 3', 'open', 'pending', NULL, 0, 'failed', '2026-03-18 21:53:31', NULL, 'released', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'normal', 1, 65, 1, '2026-03-18 21:53:31', '2026-05-13 17:07:47', NULL),
(22, 61, 3, 'Kubuni Logo na Brand Identity ya Kampuni', 'Designing a Company Logo and Brand Identity', 'kubuni-logo-na-brand-identity-ya-kampuni-pQnPHk', 'Kampuni yangu mpya ya ushauri wa biashara (MH Consulting) inahitaji logo nzuri, rangi za brand, na mwongozo wa matumizi. Ninapenda logo ya kisasa na ya kitaalamu. Tafadhali toa mifano 3 na bei ya jumla.', 'My new business consulting company (MH Consulting) needs a good logo, brand colors, and usage guidelines. I prefer a modern and professional logo. Please provide 3 examples and the total cost.', NULL, NULL, 'Remote', NULL, NULL, 250000.00, 500000.00, 'fixed', 'wiki 1', 'open', 'pending', NULL, 1, 'done', '2026-03-18 21:53:31', NULL, 'released', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'normal', 1, 65, 5, '2026-03-18 21:53:31', '2026-03-21 12:44:43', NULL),
(23, 61, 4, 'Kutafsiri Mkataba wa Kibiashara (Kiingereza → Kiswahili)', 'Business Contract Translator', 'kutafsiri-mkataba-wa-kibiashara-kiingereza-kiswahili-mgyROz', 'Nina mkataba wa biashara wa kurasa 12 kwa Kiingereza unaohitaji kutafsiriwa kwa Kiswahili rasmi. Tafsiri lazima iwe sahihi na inayofaa kisheria. Haraka inahitajika — siku 3.', 'I have a business contract requiring translation of 12 pages from English to Swahili in a formal and legally accurate manner, and it needs to be done quickly within 3 days.', NULL, NULL, 'Remote', NULL, NULL, 120000.00, 200000.00, 'fixed', 'siku 3', 'completed', 'pending', NULL, 1, 'done', '2026-03-18 21:53:31', '984646', 'released', NULL, '2026-03-21 09:38:54', '2026-03-21 09:39:41', NULL, NULL, 0, NULL, 60, NULL, 'urgent', 1, 65, 6, '2026-03-18 21:53:31', '2026-03-21 12:50:28', NULL),
(24, 61, 5, 'Kusimamia Mitandao ya Kijamii — Miezi 3', 'Social Media Detox — 3 Months', 'kusimamia-mitandao-ya-kijamii-miezi-3-grD76U', 'Biashara yangu ya vyakula inahitaji mtu wa kusimamia Instagram, Facebook, na TikTok. Angalau machapisho 5 kwa wiki, kujibu macomment, na kutoa ripoti ya mwezi. Una uzoefu wa kufanya biashara za vyakula kukua mtandaoni?', 'My food business needs someone to manage Instagram, Facebook, and TikTok. At least 5 posts per week, respond to comments, and provide a monthly report. Do you have experience in growing food businesses online?', NULL, NULL, 'Remote / Dar es Salaam', NULL, NULL, 200000.00, 350000.00, 'hourly', 'miezi 3', 'open', 'pending', NULL, 1, 'done', '2026-03-18 21:53:31', NULL, 'released', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'normal', 1, 157, 4, '2026-03-18 21:53:31', '2026-03-21 12:44:46', NULL),
(26, 61, 7, 'Ufungaji wa Umeme — Nyumba Mpya Mbezi Beach', 'Electrification Project - New House Mbezi Beach', 'ufungaji-wa-umeme-nyumba-mpya-mbezi-beach-oUTdgp', 'Nyumba mpya ya ghorofa 2 inahitaji ufungaji kamili wa umeme: taa, vituo vya umeme, paneli kuu, na wiring. Karibu chumba 8. Eneo: Mbezi Beach. Vifaa ni vya mwenye kazi, fundi aje na ujuzi na zana tu.', 'A new 2-storey house requires a complete electrical installation: lights, electrical outlets, main panel, and wiring. Approximately 8 rooms. Location: Mbezi Beach. Tools are for professionals, a skilled electrician should come with skills and equipment only.', NULL, NULL, 'Mbezi Beach, Dar es Salaam', NULL, NULL, 1200000.00, 2000000.00, 'fixed', 'wiki 2', 'open', 'pending', NULL, 1, 'done', '2026-03-18 21:53:31', NULL, 'released', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'normal', 0, 48, 0, '2026-03-18 21:53:31', '2026-03-21 12:44:48', NULL),
(27, 61, 12, 'Msaidizi wa Nyumbani — Usafi na Kupika (Wiki 1)', 'Home Assistant — Cleaning and Cooking (Week 1)', 'msaidizi-wa-nyumbani-usafi-na-kupika-wiki-1-gRPApL', 'Ninahitaji msaidizi wa nyumbani kwa wiki 1 kufanya usafi wa kina wa nyumba nzima na kuandaa chakula cha mchana na usiku. Nyumba: vyumba 4. Eneo: Msasani, Dar es Salaam. Chakula ni cha kawaida cha Kitanzania.', 'I need a home helper for 1 week to thoroughly clean the entire house and prepare lunch and dinner. The house: 4 rooms. Location: Msasani, Dar es Salaam. The food is standard Tanzanian cuisine.', NULL, NULL, 'Msasani, Dar es Salaam', NULL, NULL, 70000.00, 120000.00, 'fixed', 'wiki 1', 'open', 'pending', NULL, 1, 'done', '2026-03-18 21:53:31', NULL, 'released', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'urgent', 0, 81, 6, '2026-03-18 21:53:31', '2026-03-21 12:44:50', NULL),
(28, 61, 9, 'Mwalimu wa Kiingereza kwa Watoto (Darasa la 5–7)', 'English Teacher for Children (Grades 5-7)', 'mwalimu-wa-kiingereza-kwa-watoto-darasa-la-5-7-ON7854', 'Watoto wangu wawili (darasa 5 na 7) wanahitaji mwalimu wa Kiingereza mara 3 kwa wiki. Masomo nyumbani kwetu, Mikocheni A. Saa 2 kwa siku. Ninahitaji mtu mwenye subira na uzoefu wa kufundisha watoto.', 'My two children (grade 5 and 7) need an English teacher three times a week. Home schooling at our place, Mikocheni A. 2 hours a day. I need someone with patience and teaching experience for children.', NULL, NULL, 'Mikocheni, Dar es Salaam', NULL, NULL, 150000.00, 250000.00, 'hourly', 'miezi 3', 'open', 'pending', NULL, 1, 'done', '2026-03-18 21:53:31', NULL, 'released', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'normal', 0, 110, 9, '2026-03-18 21:53:31', '2026-03-21 12:44:51', NULL),
(29, 61, 10, 'Mshauri wa Lishe na Mpango wa Mazoezi (Miezi 2)', 'Diet and Exercise Consultant (2 Months)', 'mshauri-wa-lishe-na-mpango-wa-mazoezi-miezi-2-4tRnEZ', 'Ninatafuta mtaalamu wa lishe na mazoezi kutayarisha mpango wangu wa kupoteza uzito kwa njia ya afya. Ninahitaji mtu aweze kunisaidia kwa mashauri ya kila wiki na mpango wa chakula. Magonjwa: hakuna. Uzito wa sasa: 92kg, lengo 75kg.', 'I am looking for a nutrition and fitness expert to help me create a healthy weight loss plan. I need someone who can provide me with weekly consultations and a meal plan. Health conditions: none. Current weight: 92kg, target weight: 75kg.', NULL, NULL, 'Dar es Salaam (online au uso kwa uso)', NULL, NULL, 200000.00, 400000.00, 'fixed', 'miezi 2', 'open', 'pending', NULL, 1, 'done', '2026-03-18 21:53:31', NULL, 'released', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'normal', 0, 50, 9, '2026-03-18 21:53:31', '2026-03-21 12:44:53', NULL),
(30, 61, 11, 'Kupanda Bustani ya Mboga — Nyumba Kibamba', 'Starting a Vegetable Garden — Backyard Farming', 'kupanda-bustani-ya-mboga-nyumba-kibamba-0I3GGu', 'Nina nafasi ya bustani nyuma ya nyumba (takriban mita 50 mraba). Ninahitaji mtu wa kusaidia kupanga, kulima, na kupanda mboga mbalimbali: nyanya, pilipili, mchicha, na matango. Mtu awe tayari kuja mara 2 kwa wiki.', 'I have a garden space behind my house (approximately 50 square meters). I need someone to assist in planning, cultivating, and harvesting various vegetables: tomatoes, peppers, spinach, and mangoes. The person should be available to come twice a week.', NULL, NULL, 'Kibamba, Dar es Salaam', NULL, NULL, 80000.00, 150000.00, 'fixed', 'miezi 1', 'open', 'pending', NULL, 1, 'done', '2026-03-18 21:53:31', NULL, 'released', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'normal', 0, 50, 2, '2026-03-18 21:53:31', '2026-03-21 12:44:54', NULL),
(31, 61, 13, 'MC wa Kitaalamu kwa Sherehe ya Ndoa — Desemba 2026', 'Celebrity Host for the Wedding — December 2026', 'mc-wa-kitaalamu-kwa-sherehe-ya-ndoa-desemba-2026-1FLVGN', 'Ninahitaji MC mzuri wa Kiswahili kwa sherehe ya ndoa itakayofanyika Desemba 2026 Dar es Salaam. Wageni takriban 300. Sherehe itakuwa ya kisasa na ya Kitanzania. Tafadhali tuma video za kazi zako za awali.', 'I need a good Swahili MC for a wedding ceremony scheduled to take place in December 2026 in Dar es Salaam. We expect approximately 300 guests. The event will be a modern and Tanzanian-themed wedding. Please send me videos of your past work.', NULL, NULL, 'Dar es Salaam', NULL, NULL, 500000.00, 1000000.00, 'fixed', 'siku 1', 'open', 'pending', NULL, 1, 'done', '2026-03-18 21:53:31', NULL, 'released', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'normal', 0, 42, 7, '2026-03-18 21:53:31', '2026-03-21 12:44:55', NULL),
(32, 61, 8, 'Delivery ya Bidhaa za Duka — Dar es Salaam Mjini', 'Delivery of Shop Goods — Dar es Salaam City', 'delivery-ya-bidhaa-za-duka-dar-es-salaam-mjini-pEJ2LD', 'Duka langu la bidhaa za nyumbani linahitaji msaada wa uwasilishaji bidhaa kwa wateja mjini Dar. Takriban deliveries 10–20 kwa siku. Unahitaji pikipiki au baiskeli ya motorized na ujue maeneo ya Dar vizuri.', 'My home goods store needs assistance with product delivery to customers in Dar. Approximately 10-20 deliveries per day are required. You will need a motorbike or a motorized bicycle and should be well familiar with Dar\'s areas.', NULL, NULL, 'Dar es Salaam', NULL, NULL, 80000.00, 150000.00, 'hourly', 'miezi 2', 'in_progress', 'pending', NULL, 1, 'done', '2026-03-18 21:53:31', '242284', 'released', NULL, '2026-03-29 13:36:17', '2026-03-29 13:36:28', NULL, NULL, 0, NULL, 60, NULL, 'normal', 0, 140, 2, '2026-03-18 21:53:31', '2026-05-02 13:48:47', NULL),
(33, 61, 2, 'Kurekebisha Mfumo wa POS wa Duka', 'Reconfiguring the Point of Sale (POS) System of a Store', 'kurekebisha-mfumo-wa-pos-wa-duka-wlf7KC', 'Duka langu linatumia mfumo wa POS (Point of Sale) unaohitaji marekebisho ya haraka: kuongeza bidhaa mpya, kutengeneza ripoti za mauzo, na kurekebisha bug ndogo. Mfumo ni wa Windows, built na Python.', 'My store uses a Point of Sale (POS) system that requires quick updates: adding new products, generating sales reports, and fixing a small bug. The system is built on Windows and Python.', NULL, NULL, 'Kariakoo, Dar es Salaam', NULL, NULL, 300000.00, 600000.00, 'fixed', 'wiki 1', 'open', 'pending', NULL, 1, 'done', '2026-03-18 21:53:31', NULL, 'released', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'urgent', 0, 67, 9, '2026-03-18 21:53:31', '2026-03-21 12:44:57', NULL),
(34, 61, 7, 'Kupaka Rangi Villa — Mbweni Dar es Salaam', 'Villa Painter — Mbweni Dar es Salaam', 'kupaka-rangi-villa-mbweni-dar-es-salaam-leRgme', 'Villa yangu ya vyumba 5 (ghorofa 2) inahitaji kupakwa rangi ndani na nje. Rangi itatolewa na mwenye nyumba (rangi nyeupe ndani, beige nje). Kazi ya siku 7–10. Lazima uje na wafanyakazi wako.', 'My 5-bedroom villa (2 floors) needs to be painted inside and out. The paint will be provided by the homeowner (white inside, beige outside). A 7-10 day job, you will need to bring your own workers.', NULL, NULL, 'Mbweni, Dar es Salaam', NULL, NULL, 800000.00, 1400000.00, 'fixed', 'wiki 1', 'open', 'pending', NULL, 1, 'done', '2026-03-18 21:53:31', NULL, 'released', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, 'normal', 0, 32, 10, '2026-03-18 21:53:31', '2026-03-21 12:50:29', NULL),
(35, 61, 4, 'Makala 20 za Blog kwa Tovuti ya Afya (Kiswahili)', 'Health Blog Post 20 for Website', 'makala-20-za-blog-kwa-tovuti-ya-afya-kiswahili-FcmdGW', 'Tovuti yangu ya afya inahitaji makala 20 za blog kwa Kiswahili. Kila makala iwe na maneno 600–900, imeandikwa vizuri, na SEO-friendly. Mada zimekwisha wa: afya ya akili, lishe, mazoezi, na mama na mtoto. Nitatoa mada zote.', 'My health website requires 20 blog articles in Swahili. Each article should be 600-900 words, well-written, and SEO-friendly. The topics are already decided: mental health, nutrition, exercise, and mother and child. I will provide all the topics.', NULL, NULL, 'Remote', NULL, NULL, 400000.00, 700000.00, 'fixed', 'wiki 3', 'in_progress', 'pending', NULL, 1, 'done', '2026-03-18 21:53:31', '552264', 'released', NULL, '2026-03-19 06:15:52', NULL, '2026-03-19 17:58:51', NULL, 0, NULL, 64, NULL, 'normal', 1, 82, 6, '2026-03-18 21:53:31', '2026-03-21 12:45:00', NULL),
(36, 61, 3, 'Graphic and designed', NULL, 'graphic-and-designed-80zc6x', 'nataka kutengenezewa picha ya mwanamke wa kiafrika akiwa anachota maji na huku kabeba mtoto', NULL, NULL, NULL, 'Dar es salaam,Kinondoni,mabibo', NULL, NULL, 10000.00, 20000.00, 'fixed', '1 week', 'completed', 'pending', NULL, 1, 'failed', '2026-03-24 01:34:51', '978501', 'released', NULL, '2026-04-23 17:08:11', '2026-03-24 01:53:07', NULL, NULL, 0, NULL, 60, NULL, 'normal', 0, 0, 0, '2026-03-24 01:33:17', '2026-05-13 17:06:57', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `job_skills`
--

CREATE TABLE `job_skills` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `job_id` bigint(20) UNSIGNED NOT NULL,
  `skill_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `job_skills`
--

INSERT INTO `job_skills` (`id`, `job_id`, `skill_id`) VALUES
(2, 21, 1),
(1, 21, 2),
(5, 22, 11),
(4, 22, 12),
(3, 22, 14),
(6, 23, 20),
(7, 23, 21),
(8, 26, 25),
(10, 30, 31),
(9, 30, 32),
(11, 33, 5),
(12, 33, 9),
(13, 34, 27),
(14, 35, 18),
(15, 35, 22);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `conversation_id` bigint(20) UNSIGNED NOT NULL,
  `sender_id` bigint(20) UNSIGNED NOT NULL,
  `body` text NOT NULL,
  `attachment_path` varchar(255) DEFAULT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `conversation_id`, `sender_id`, `body`, `attachment_path`, `read_at`, `created_at`, `updated_at`) VALUES
(1, 1, 64, 'poa', NULL, NULL, '2026-03-19 05:40:14', '2026-03-19 05:40:14'),
(2, 1, 61, 'poa', NULL, NULL, '2026-03-19 05:40:42', '2026-03-19 05:40:42'),
(3, 1, 61, 'mambo', NULL, NULL, '2026-03-21 09:38:18', '2026-03-21 09:38:18'),
(4, 1, 61, '0678165524', NULL, NULL, '2026-03-21 09:38:25', '2026-03-21 09:38:25'),
(5, 2, 61, '0679176625', NULL, NULL, '2026-03-21 09:38:34', '2026-03-21 09:38:34'),
(8, 1, 62, 'Test admin text', NULL, NULL, '2026-04-21 21:51:03', '2026-04-21 21:51:03');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_08_14_170933_add_two_factor_columns_to_users_table', 1),
(5, '2026_03_07_194551_create_permission_tables', 1),
(6, '2026_03_07_200000_create_winga_tables', 1),
(7, '2026_03_07_203922_add_otp_columns_to_users_table', 1),
(8, '2026_03_07_220654_add_role_column_to_users_table', 1),
(9, '2026_03_07_235111_add_onboarding_fields_to_users_table', 1),
(10, '2026_03_10_000001_create_conversations_and_messages_tables', 1),
(11, '2026_03_10_225852_add_missing_columns_to_users_table', 1),
(12, '2026_03_10_232446_create_subscriptions_table', 1),
(13, '2026_03_10_232453_add_approval_and_hold_to_job_listings_table', 1),
(14, '2026_03_10_232453_add_whatsapp_and_fields_to_users_table', 1),
(15, '2026_03_10_232453_create_withdrawal_requests_table', 1),
(16, '2026_03_11_001407_add_payout_columns_to_payments_and_withdrawals', 1),
(17, '2026_03_11_001408_add_status_to_transactions_table', 1),
(18, '2026_03_11_003702_create_subscription_plans_table', 1),
(19, '2026_03_11_003703_update_subscriptions_table_for_plans', 1),
(20, '2026_03_11_010139_add_subscription_badge_columns_to_users_table', 1),
(21, '2026_03_11_010140_create_profile_views_table', 1),
(22, '2026_03_11_010250_create_services_table', 1),
(23, '2026_03_11_020000_create_platform_settings_table', 1),
(24, '2026_03_11_020001_add_admin_control_center_tables', 1),
(25, '2026_03_11_023135_create_settings_table', 1),
(26, '2026_03_11_023312_create_admin_audit_logs_table', 1),
(27, '2026_03_11_025008_create_disputes_table', 1),
(28, '2026_03_11_025410_create_dispute_evidence_table', 1),
(29, '2026_03_11_025427_create_broadcast_messages_table', 1),
(30, '2026_03_11_160328_update_role_names_in_users_table', 1),
(31, '2026_03_11_160524_modify_role_column_length_in_users_table', 1),
(32, '2026_03_14_194833_add_retry_columns_to_payments_and_withdrawals', 1),
(33, '2026_03_17_223059_add_suspended_at_to_users_table', 1),
(34, '2026_03_17_223103_add_color_and_sort_order_to_categories_table', 1),
(35, '2026_03_21_150546_add_translation_columns_to_job_listings_table', 2),
(37, '2026_03_21_154521_change_title_en_to_text_on_job_listings', 3),
(38, '2026_03_22_020040_create_profile_views_table', 4),
(39, '2026_03_24_045105_create_favorites_table', 4),
(40, '2026_03_25_100148_add_hold_comment_and_extended_to_job_listings_table', 5),
(41, '2026_03_25_100825_add_charge_fields_to_withdrawal_requests_table', 5),
(42, '2026_03_25_101200_add_rejection_comment_to_applications_table', 5),
(43, '2026_04_19_221150_create_service_requests_table', 6),
(44, '2026_04_19_222311_create_service_packages_table', 7),
(45, '2026_04_19_222312_add_service_package_id_to_service_requests_table', 7),
(46, '2026_04_19_224949_add_legacy_wp_user_id_to_users_table', 7),
(47, '2026_04_20_000001_add_limits_to_subscription_plans_table', 8),
(48, '2026_04_23_202030_add_announcement_type_and_target_segments_to_broadcast_messages_table', 9),
(49, '2026_04_23_210238_add_service_request_completion_and_payment_link', 10),
(50, '2026_04_27_213617_add_is_featured_to_portfolios_table', 11),
(51, '2026_05_02_160657_add_decline_reason_to_service_requests_table', 12),
(55, '2026_05_13_185358_create_site_announcements_table', 13),
(56, '2026_05_13_194741_create_site_announcement_user_table', 13);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 65),
(1, 'App\\Models\\User', 67),
(2, 'App\\Models\\User', 64),
(2, 'App\\Models\\User', 66),
(2, 'App\\Models\\User', 351),
(2, 'App\\Models\\User', 354),
(3, 'App\\Models\\User', 62),
(4, 'App\\Models\\User', 69),
(4, 'App\\Models\\User', 70),
(4, 'App\\Models\\User', 71),
(4, 'App\\Models\\User', 72),
(4, 'App\\Models\\User', 73),
(4, 'App\\Models\\User', 74),
(4, 'App\\Models\\User', 75),
(4, 'App\\Models\\User', 76),
(4, 'App\\Models\\User', 77),
(4, 'App\\Models\\User', 78),
(4, 'App\\Models\\User', 79),
(4, 'App\\Models\\User', 80),
(4, 'App\\Models\\User', 81),
(4, 'App\\Models\\User', 82),
(4, 'App\\Models\\User', 83),
(4, 'App\\Models\\User', 84),
(4, 'App\\Models\\User', 85),
(4, 'App\\Models\\User', 86),
(4, 'App\\Models\\User', 87),
(4, 'App\\Models\\User', 88),
(4, 'App\\Models\\User', 89),
(4, 'App\\Models\\User', 90),
(4, 'App\\Models\\User', 91),
(4, 'App\\Models\\User', 92),
(4, 'App\\Models\\User', 93),
(4, 'App\\Models\\User', 94),
(4, 'App\\Models\\User', 95),
(4, 'App\\Models\\User', 96),
(4, 'App\\Models\\User', 97),
(4, 'App\\Models\\User', 98),
(4, 'App\\Models\\User', 99),
(4, 'App\\Models\\User', 100),
(4, 'App\\Models\\User', 101),
(4, 'App\\Models\\User', 102),
(4, 'App\\Models\\User', 103),
(4, 'App\\Models\\User', 104),
(4, 'App\\Models\\User', 105),
(4, 'App\\Models\\User', 106),
(4, 'App\\Models\\User', 107),
(4, 'App\\Models\\User', 108),
(4, 'App\\Models\\User', 109),
(4, 'App\\Models\\User', 110),
(4, 'App\\Models\\User', 111),
(4, 'App\\Models\\User', 112),
(4, 'App\\Models\\User', 113),
(4, 'App\\Models\\User', 114),
(4, 'App\\Models\\User', 115),
(4, 'App\\Models\\User', 116),
(4, 'App\\Models\\User', 117),
(4, 'App\\Models\\User', 118),
(4, 'App\\Models\\User', 119),
(4, 'App\\Models\\User', 120),
(4, 'App\\Models\\User', 121),
(4, 'App\\Models\\User', 122),
(4, 'App\\Models\\User', 123),
(4, 'App\\Models\\User', 124),
(4, 'App\\Models\\User', 125),
(4, 'App\\Models\\User', 126),
(4, 'App\\Models\\User', 127),
(4, 'App\\Models\\User', 128),
(4, 'App\\Models\\User', 129),
(4, 'App\\Models\\User', 130),
(4, 'App\\Models\\User', 131),
(4, 'App\\Models\\User', 132),
(4, 'App\\Models\\User', 133),
(4, 'App\\Models\\User', 134),
(4, 'App\\Models\\User', 135),
(4, 'App\\Models\\User', 136),
(4, 'App\\Models\\User', 137),
(4, 'App\\Models\\User', 138),
(4, 'App\\Models\\User', 139),
(4, 'App\\Models\\User', 140),
(4, 'App\\Models\\User', 141),
(4, 'App\\Models\\User', 142),
(4, 'App\\Models\\User', 143),
(4, 'App\\Models\\User', 144),
(4, 'App\\Models\\User', 145),
(4, 'App\\Models\\User', 146),
(4, 'App\\Models\\User', 147),
(4, 'App\\Models\\User', 148),
(4, 'App\\Models\\User', 149),
(4, 'App\\Models\\User', 150),
(4, 'App\\Models\\User', 151),
(4, 'App\\Models\\User', 152),
(4, 'App\\Models\\User', 153),
(4, 'App\\Models\\User', 154),
(4, 'App\\Models\\User', 155),
(4, 'App\\Models\\User', 156),
(4, 'App\\Models\\User', 157),
(4, 'App\\Models\\User', 158),
(4, 'App\\Models\\User', 159),
(4, 'App\\Models\\User', 160),
(4, 'App\\Models\\User', 161),
(4, 'App\\Models\\User', 162),
(4, 'App\\Models\\User', 163),
(4, 'App\\Models\\User', 164),
(4, 'App\\Models\\User', 165),
(4, 'App\\Models\\User', 166),
(4, 'App\\Models\\User', 167),
(4, 'App\\Models\\User', 168),
(4, 'App\\Models\\User', 169),
(4, 'App\\Models\\User', 170),
(4, 'App\\Models\\User', 171),
(4, 'App\\Models\\User', 172),
(4, 'App\\Models\\User', 173),
(4, 'App\\Models\\User', 174),
(4, 'App\\Models\\User', 175),
(4, 'App\\Models\\User', 176),
(4, 'App\\Models\\User', 177),
(4, 'App\\Models\\User', 178),
(4, 'App\\Models\\User', 179),
(4, 'App\\Models\\User', 180),
(4, 'App\\Models\\User', 181),
(4, 'App\\Models\\User', 182),
(4, 'App\\Models\\User', 183),
(4, 'App\\Models\\User', 184),
(4, 'App\\Models\\User', 185),
(4, 'App\\Models\\User', 186),
(4, 'App\\Models\\User', 187),
(4, 'App\\Models\\User', 188),
(4, 'App\\Models\\User', 189),
(4, 'App\\Models\\User', 190),
(4, 'App\\Models\\User', 191),
(4, 'App\\Models\\User', 192),
(4, 'App\\Models\\User', 193),
(4, 'App\\Models\\User', 194),
(4, 'App\\Models\\User', 195),
(4, 'App\\Models\\User', 196),
(4, 'App\\Models\\User', 197),
(4, 'App\\Models\\User', 198),
(4, 'App\\Models\\User', 199),
(4, 'App\\Models\\User', 200),
(4, 'App\\Models\\User', 201),
(4, 'App\\Models\\User', 202),
(4, 'App\\Models\\User', 203),
(4, 'App\\Models\\User', 204),
(4, 'App\\Models\\User', 205),
(4, 'App\\Models\\User', 206),
(4, 'App\\Models\\User', 207),
(4, 'App\\Models\\User', 208),
(4, 'App\\Models\\User', 209),
(4, 'App\\Models\\User', 210),
(4, 'App\\Models\\User', 211),
(4, 'App\\Models\\User', 212),
(4, 'App\\Models\\User', 213),
(4, 'App\\Models\\User', 214),
(4, 'App\\Models\\User', 215),
(4, 'App\\Models\\User', 216),
(4, 'App\\Models\\User', 217),
(4, 'App\\Models\\User', 218),
(4, 'App\\Models\\User', 219),
(4, 'App\\Models\\User', 220),
(4, 'App\\Models\\User', 221),
(4, 'App\\Models\\User', 222),
(4, 'App\\Models\\User', 223),
(4, 'App\\Models\\User', 224),
(4, 'App\\Models\\User', 225),
(4, 'App\\Models\\User', 226),
(4, 'App\\Models\\User', 227),
(4, 'App\\Models\\User', 228),
(4, 'App\\Models\\User', 229),
(4, 'App\\Models\\User', 230),
(4, 'App\\Models\\User', 231),
(4, 'App\\Models\\User', 232),
(4, 'App\\Models\\User', 233),
(4, 'App\\Models\\User', 234),
(4, 'App\\Models\\User', 235),
(4, 'App\\Models\\User', 236),
(4, 'App\\Models\\User', 237),
(4, 'App\\Models\\User', 238),
(4, 'App\\Models\\User', 239),
(4, 'App\\Models\\User', 240),
(4, 'App\\Models\\User', 241),
(4, 'App\\Models\\User', 242),
(4, 'App\\Models\\User', 243),
(4, 'App\\Models\\User', 244),
(4, 'App\\Models\\User', 245),
(4, 'App\\Models\\User', 246),
(4, 'App\\Models\\User', 247),
(4, 'App\\Models\\User', 248),
(4, 'App\\Models\\User', 249),
(4, 'App\\Models\\User', 250),
(4, 'App\\Models\\User', 251),
(4, 'App\\Models\\User', 252),
(4, 'App\\Models\\User', 253),
(4, 'App\\Models\\User', 254),
(4, 'App\\Models\\User', 255),
(4, 'App\\Models\\User', 256),
(4, 'App\\Models\\User', 257),
(4, 'App\\Models\\User', 258),
(4, 'App\\Models\\User', 259),
(4, 'App\\Models\\User', 260),
(4, 'App\\Models\\User', 261),
(4, 'App\\Models\\User', 262),
(4, 'App\\Models\\User', 263),
(4, 'App\\Models\\User', 264),
(4, 'App\\Models\\User', 265),
(4, 'App\\Models\\User', 266),
(4, 'App\\Models\\User', 267),
(4, 'App\\Models\\User', 268),
(4, 'App\\Models\\User', 269),
(4, 'App\\Models\\User', 270),
(4, 'App\\Models\\User', 271),
(4, 'App\\Models\\User', 272),
(4, 'App\\Models\\User', 273),
(4, 'App\\Models\\User', 274),
(4, 'App\\Models\\User', 275),
(4, 'App\\Models\\User', 276),
(4, 'App\\Models\\User', 277),
(4, 'App\\Models\\User', 278),
(4, 'App\\Models\\User', 279),
(4, 'App\\Models\\User', 280),
(4, 'App\\Models\\User', 281),
(4, 'App\\Models\\User', 282),
(4, 'App\\Models\\User', 283),
(4, 'App\\Models\\User', 284),
(4, 'App\\Models\\User', 285),
(4, 'App\\Models\\User', 286),
(4, 'App\\Models\\User', 287),
(4, 'App\\Models\\User', 288),
(4, 'App\\Models\\User', 289),
(4, 'App\\Models\\User', 290),
(4, 'App\\Models\\User', 291),
(4, 'App\\Models\\User', 292),
(4, 'App\\Models\\User', 293),
(4, 'App\\Models\\User', 294),
(4, 'App\\Models\\User', 295),
(4, 'App\\Models\\User', 296),
(4, 'App\\Models\\User', 297),
(4, 'App\\Models\\User', 298),
(4, 'App\\Models\\User', 299),
(4, 'App\\Models\\User', 300),
(4, 'App\\Models\\User', 301),
(4, 'App\\Models\\User', 302),
(4, 'App\\Models\\User', 303),
(4, 'App\\Models\\User', 304),
(4, 'App\\Models\\User', 305),
(4, 'App\\Models\\User', 306),
(4, 'App\\Models\\User', 307),
(4, 'App\\Models\\User', 308),
(4, 'App\\Models\\User', 309),
(4, 'App\\Models\\User', 310),
(4, 'App\\Models\\User', 311),
(4, 'App\\Models\\User', 312),
(4, 'App\\Models\\User', 313),
(4, 'App\\Models\\User', 314),
(4, 'App\\Models\\User', 315),
(4, 'App\\Models\\User', 316),
(4, 'App\\Models\\User', 317),
(4, 'App\\Models\\User', 318),
(4, 'App\\Models\\User', 319),
(4, 'App\\Models\\User', 320),
(4, 'App\\Models\\User', 321),
(4, 'App\\Models\\User', 322),
(4, 'App\\Models\\User', 323),
(4, 'App\\Models\\User', 324),
(4, 'App\\Models\\User', 325),
(4, 'App\\Models\\User', 326),
(4, 'App\\Models\\User', 327),
(4, 'App\\Models\\User', 328),
(4, 'App\\Models\\User', 329),
(4, 'App\\Models\\User', 330),
(4, 'App\\Models\\User', 331),
(4, 'App\\Models\\User', 332),
(4, 'App\\Models\\User', 333),
(4, 'App\\Models\\User', 334),
(4, 'App\\Models\\User', 335),
(4, 'App\\Models\\User', 336),
(4, 'App\\Models\\User', 337),
(4, 'App\\Models\\User', 338),
(4, 'App\\Models\\User', 339),
(4, 'App\\Models\\User', 340),
(4, 'App\\Models\\User', 341),
(4, 'App\\Models\\User', 342),
(4, 'App\\Models\\User', 343),
(4, 'App\\Models\\User', 344),
(4, 'App\\Models\\User', 345),
(4, 'App\\Models\\User', 346),
(4, 'App\\Models\\User', 347),
(4, 'App\\Models\\User', 348),
(4, 'App\\Models\\User', 349),
(4, 'App\\Models\\User', 350);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('002667ae-5cce-46a3-985b-16a58a7044ca', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 280, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('0032fbcf-d086-4c02-9075-00d76a06a5f2', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 188, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:19', '2026-05-13 17:07:19'),
('00792a21-481f-462a-aca8-cb83a8546f6c', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 113, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:06', '2026-05-13 17:07:06'),
('00b7fbd3-abba-4c20-bd14-78c333272d52', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 67, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('00d2c145-0f6b-4baf-b4b1-6d23860c5a09', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 169, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:16', '2026-05-13 17:07:16'),
('01429581-012a-4ecd-96da-6b8b9d60c294', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 300, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('015f6fdd-52fb-4859-a94e-143a2d0d7763', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 114, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('01665202-ca11-4b22-9d11-ad7817e7c3be', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 333, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('01a78b6a-da85-4d3f-852f-74db936a2100', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 266, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('01aef402-1674-4cbc-a2b9-63c8ee0a233c', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 260, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('01bb1ccd-f6e0-46e3-922a-0b26bf223831', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 60, '{\"title\":\"\\u2b50 Subscription Imewashwa!\",\"message\":\"Umejiunga na mpango wa Winga Complex. Sasa unaonekana kwenye orodha ya Winga Bora hadi 21 May 2026.\",\"icon\":\"star\",\"color\":\"green\",\"action_url\":\"https:\\/\\/winga.ericksky.online\\/winga\\/subscription\",\"action_label\":\"Angalia Subscription\"}', '2026-04-23 17:17:58', '2026-04-21 17:36:42', '2026-04-23 17:17:58'),
('01dee805-ef18-4a4f-bf36-031d16d1160c', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 325, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('02b37b16-e1b7-4826-9433-5cc13b6c27a1', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 293, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('02dedbd9-77d0-4a9d-8b72-28c7f43d602d', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 213, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('030136f6-ff10-44e6-ae77-2c6eaf8a9738', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 153, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:13', '2026-05-13 17:07:13'),
('041949bf-f91c-450f-83ea-573e5dcb9be7', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 93, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('046eef84-533c-4e4f-b391-14184025232a', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 253, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('054c9f41-3846-41d3-8b54-355c61e2480a', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 171, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:16', '2026-05-13 17:07:16'),
('05f7c02e-60cc-4f24-aad4-cb7cb540bd52', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 217, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('060b20b1-6d9a-4ebd-9d8a-a42939edab95', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 104, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('0634b532-0513-44c3-8626-f9b337699ad5', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 243, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:28', '2026-05-13 17:07:28'),
('068a731e-0f8e-4d04-9c7c-f97525b206fe', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 150, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('06a83d10-fbc0-4c1f-852e-e888b5eaff9e', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 60, '{\"title\":\"Withdrawal Request Rejected\",\"message\":\"Your withdrawal request for TZS 5,000 has been rejected. Amount returned to wallet.\",\"icon\":\"x-circle\",\"color\":\"red\",\"action_url\":\"https:\\/\\/winga.ericksky.online\\/winga\\/tomba-ombi\",\"action_label\":\"View Wallet\"}', '2026-04-23 17:17:58', '2026-04-21 20:10:00', '2026-04-23 17:17:58'),
('070b6bcc-0129-403e-a6df-85e169343bc6', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 136, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('07202473-2e38-4807-9cbe-c1b62968f7ef', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 111, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:06', '2026-05-13 17:07:06'),
('07d5287e-057d-414c-aaa6-6f580ab6018a', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 60, '{\"title\":\"\\u2b50 Subscription Imewashwa!\",\"message\":\"Umejiunga na mpango wa Winga Complex. Sasa unaonekana kwenye orodha ya Winga Bora hadi 21 May 2026.\",\"icon\":\"star\",\"color\":\"green\",\"action_url\":\"https:\\/\\/winga.ericksky.online\\/winga\\/subscription\",\"action_label\":\"Angalia Subscription\"}', '2026-04-23 17:17:58', '2026-04-21 20:16:31', '2026-04-23 17:17:58'),
('080c19ce-b4a4-4467-8cc8-47870dd0a555', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 353, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('087ea61f-afb9-458c-ad01-fc4944c36341', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 157, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('08b4c817-e2ce-478a-bbdc-aabbef06fedd', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 340, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:44', '2026-05-13 17:07:44'),
('08cc2566-763d-492e-afe4-e55a53a1b168', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 347, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('09588bb4-e89f-4680-9419-c3d5d0bcb92c', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 134, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:10', '2026-05-13 17:07:10'),
('0a7c6182-bc2a-4f5e-8ef7-eed7b4d05ac3', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 281, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('0b0ccec6-b06c-44d2-8f06-04ebd9a3b3ce', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 61, '{\"title\":\"\\u2705 Kazi Imekamilika!\",\"message\":\"Ali Juma ameweka code \\u2014 kazi \\\"Delivery ya Bidhaa za Duka \\u2014 Dar es Salaam Mjini\\\" imekamilika. Malipo yametumwa kwa Winga.\",\"icon\":\"check-circle\",\"color\":\"green\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/mteja\\/kazi-zangu\",\"action_label\":\"Angalia Kazi\"}', NULL, '2026-03-29 13:36:28', '2026-03-29 13:36:28'),
('0c1b1d84-77ee-4b82-b8ad-d1686f9a69d4', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 151, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:13', '2026-05-13 17:07:13'),
('0cd0f203-bb1a-47e4-be19-7e8010cca9a7', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 307, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:39', '2026-05-13 17:07:39'),
('0d4da967-622a-4169-be1b-cea81c7926d4', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 60, '{\"title\":\"Withdrawal Request Rejected\",\"message\":\"Your withdrawal request for TZS 5,000 has been rejected. Amount returned to wallet.\",\"icon\":\"x-circle\",\"color\":\"red\",\"action_url\":\"https:\\/\\/winga.ericksky.online\\/winga\\/tomba-ombi\",\"action_label\":\"View Wallet\"}', '2026-04-23 17:17:58', '2026-04-21 20:09:43', '2026-04-23 17:17:58'),
('0d53479b-2803-4350-84b2-5679df3ef5d5', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 170, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:16', '2026-05-13 17:07:16'),
('0d7219aa-1815-49e2-ad10-65208adbe034', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 117, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:07', '2026-05-13 17:07:07'),
('0da20d34-f607-4e05-bcc5-38fd99dc9c4f', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 194, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:20', '2026-05-13 17:07:20'),
('0e1cb06e-b5b8-4504-bd00-3bbedf1ae411', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 308, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:39', '2026-05-13 17:07:39'),
('0e3d94ea-97be-43db-86bd-143c572e07bf', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 218, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('0e93514b-5275-4045-845d-78f153d93516', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 65, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('0eb8cf19-f1fc-4edb-8355-7955252c2b0b', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 228, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('0f4e21ee-3316-410f-b676-6194abb254fe', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 60, '{\"title\":\"\\u2b50 Subscription Imewashwa!\",\"message\":\"Umejiunga na mpango wa Winga Karume. Sasa unaonekana kwenye orodha ya Winga Bora hadi 21 May 2026.\",\"icon\":\"star\",\"color\":\"green\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/winga\\/subscription\",\"action_label\":\"Angalia Subscription\"}', '2026-04-23 17:17:58', '2026-04-20 22:13:28', '2026-04-23 17:17:58'),
('0f8d94fc-1dd5-4482-bbfd-14f7a287d408', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 60, '{\"title\":\"\\ud83c\\udf89 Ombi Lako Limekubaliwa!\",\"message\":\"Hongera! Umeajiriwa kazi: \\\"Kutafsiri Mkataba wa Kibiashara (Kiingereza \\u2192 Kiswahili)\\\". Pesa yako imeshikiliwa na Winga, anza kufanya kazi!\",\"icon\":\"check-circle\",\"color\":\"green\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/messages\",\"action_label\":\"Fungua Chat\"}', '2026-04-23 17:17:58', '2026-03-21 09:38:04', '2026-04-23 17:17:58'),
('0ff89fe4-6d7c-4a25-8a50-3e4ba9f87ed1', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 147, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('102b55ef-7731-47aa-9e80-a2e3c66f05b3', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 212, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('10993dcc-8769-47be-aac2-08915db0e556', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 196, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('10aa2934-4b58-4378-b8c7-71209d501cd4', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 284, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('10b4f2c7-855a-4d19-85f3-b25525526f2b', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 119, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:07', '2026-05-13 17:07:07'),
('11639fbb-7c6f-43cb-b888-07e1f5ed394d', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 236, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('1183be0b-3d7f-4833-afce-db8c67d2d323', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 84, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('12247f46-0c93-4b56-9029-63c0c83fde26', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 108, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:05', '2026-05-13 17:07:05'),
('1252ea65-0045-4f79-8f45-307f19b8f869', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 169, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('1274cf66-21fb-4681-8f99-ca9486b2aba8', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 60, '{\"title\":\"New service request\",\"message\":\"Fatuma Ngozi requested package \\u201cAgricuture pro\\u201d for Agricuture pro\",\"icon\":\"inbox\",\"color\":\"blue\",\"action_url\":\"http:\\/\\/localhost:8000\\/winga\\/huduma-maombi\",\"action_label\":\"View requests\"}', '2026-05-13 16:13:54', '2026-05-02 13:47:07', '2026-05-13 16:13:54'),
('128c4a88-2a52-4a17-88e3-8abe6959b27a', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 300, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:38', '2026-05-13 17:07:38'),
('128ec22c-5449-4c99-9436-4903ef41576f', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 322, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('129f80c9-a5ef-4098-8c29-ac9c0f5fcadc', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 118, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:07', '2026-05-13 17:07:07'),
('132b0b98-7b67-463f-9adf-0153a77f395e', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 148, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('13e419a4-0900-4dd4-a46e-589f41e1c762', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 331, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('15582abe-064a-4ea8-9934-2b8a2f93ab09', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 132, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:09', '2026-05-13 17:07:09'),
('160e66a8-8bc3-417e-a69d-a39541626444', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 118, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('1672696e-57da-4b48-8623-7bfe7246f931', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 285, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:35', '2026-05-13 17:07:35'),
('1699db6c-55aa-4e1f-92f8-46afe73915cf', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 195, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:20', '2026-05-13 17:07:20'),
('16a417e1-4fbf-493f-bc99-812e2fd85c81', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 155, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:13', '2026-05-13 17:07:13'),
('1735fae1-f44f-4d55-bf8a-4ba042011eaf', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 150, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:13', '2026-05-13 17:07:13'),
('175afb02-ecb8-4058-9156-349d54c75d47', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 352, '{\"title\":\"Request declined\",\"message\":\"Ali Juma declined your request for Graphic designed (Graphics designed)\",\"icon\":\"x-circle\",\"color\":\"zinc\",\"action_url\":null,\"action_label\":null}', NULL, '2026-04-21 20:28:13', '2026-04-21 20:28:13'),
('176481bf-167b-4f6d-88b8-b8310017c0c5', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 199, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('185182e8-82f6-4fea-bf7b-c8370c5737b9', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 133, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('18596e74-0d82-4ebd-8580-6912b19ee812', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 270, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:33', '2026-05-13 17:07:33'),
('18fa5b7f-9415-48b8-bec4-7fb576aa5992', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 251, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('193c392a-c100-4f93-99b7-a79641df9b88', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 193, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('19bb654b-d98e-4fe1-8385-4fe61431af37', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 341, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('1a704a81-95b1-412c-b2be-5b5ad811f665', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 302, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('1af07212-43e5-4106-b8d6-7a7deb55d9b8', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 328, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:42', '2026-05-13 17:07:42'),
('1af47972-c305-4bdc-987b-234213febc64', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 166, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('1b07709e-5bf6-4932-b3bf-e4b5f067ed63', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 303, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('1b752ba7-91e9-4dee-8978-c8f4ce4701fe', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 331, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:43', '2026-05-13 17:07:43'),
('1bdda279-453d-4d22-834b-50c569ba5795', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 60, '{\"title\":\"New service request\",\"message\":\"Fatuma Ngozi requested package \\u201cAgricuture pro\\u201d for Agricuture pro\",\"icon\":\"inbox\",\"color\":\"blue\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/winga\\/huduma-maombi\",\"action_label\":\"View requests\"}', '2026-05-13 16:13:54', '2026-05-02 13:04:07', '2026-05-13 16:13:54'),
('1c0834c0-8dac-4ca8-9e17-86eb91ee62bd', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 61, '{\"title\":\"Request accepted\",\"message\":\"Ali Juma accepted your request for Agricuture pro (Agricuture pro)\",\"icon\":\"check-circle\",\"color\":\"green\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/mteja\\/huduma-malipo\",\"action_label\":\"Pay & start service\"}', NULL, '2026-04-23 18:09:12', '2026-04-23 18:09:12'),
('1c81134c-7e32-4181-b805-0a49d1019d3b', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 159, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:14', '2026-05-13 17:07:14'),
('1cb53307-46f3-492c-a2cd-9e09947400b5', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 84, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:01', '2026-05-13 17:07:01'),
('1cea9afc-0d17-47c8-9357-c77a2c8d0aef', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 137, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:10', '2026-05-13 17:07:10'),
('1e00bf05-e606-4b9f-ab8c-dd2df08aa505', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 342, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:45', '2026-05-13 17:07:45'),
('1e42a599-4739-4a51-ad2f-5eaedbb5e3d1', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 157, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:14', '2026-05-13 17:07:14'),
('1ecda72e-754f-4874-8a78-6f28e98e2c57', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 146, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('1f91d664-f36f-4ef2-92de-3a612fb57c8d', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 304, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:38', '2026-05-13 17:07:38'),
('1fa7a44c-ec50-4f02-af52-bc58c00fa289', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 149, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:12', '2026-05-13 17:07:12'),
('20730b3f-d491-43f3-936c-960d995f87af', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 170, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('21048ccf-e7a4-48f3-9c13-d76f246603a9', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 122, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('213538de-4b49-49e3-859b-3972ce4b4f8a', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 256, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('213d14d7-6478-4a65-b5ec-ea51a34e2a0e', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 334, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:43', '2026-05-13 17:07:43'),
('21f71bf0-8e94-4b1d-8387-6a2679d006e5', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 260, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:31', '2026-05-13 17:07:31'),
('229fb61d-f748-4f46-ac9b-1dc2768bccc6', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 246, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:29', '2026-05-13 17:07:29'),
('22ebc253-c136-4e96-9b78-3e20bb5a5b6a', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 142, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('23266746-5246-495e-b0ec-fcef8cf9938f', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 314, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('23a1fee9-17b9-4451-8914-1f930c4b863a', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 351, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('2402a876-b56e-43ca-a356-64e8b79cdabd', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 200, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('242d662b-1ff7-41ad-8636-ed91254c446a', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 162, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('24c42236-3905-402f-9b63-01957a4c9fbc', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 255, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('2548b6fc-c490-40f1-b559-d8765f9b584d', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 96, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:03', '2026-05-13 17:07:03'),
('25659ae4-3002-46d8-a864-e8c798292378', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 116, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('2578cca4-a333-4c46-b959-f92d30376d83', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 347, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:45', '2026-05-13 17:07:45'),
('25bdd1a1-22c2-4aac-bb49-6fa85ecc846b', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 288, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('25ca1aea-eb48-40c2-ac0d-d879e21c37b7', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 140, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:11', '2026-05-13 17:07:11'),
('265fe8b8-444e-49e9-a3f5-f674800d5271', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 61, '{\"title\":\"Ujumbe mpya kutoka ERICKsky\",\"message\":\"poa\",\"icon\":\"chat-bubble-left-right\",\"color\":\"blue\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/messages\\/1\",\"action_label\":\"Soma Ujumbe\"}', '2026-03-19 05:40:28', '2026-03-19 05:40:15', '2026-03-19 05:40:28'),
('26c1930d-da74-42d1-846e-878115ad9278', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 240, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('276a5f95-7385-44c5-b520-fd2954ecd379', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 60, '{\"title\":\"New service request\",\"message\":\"Railath Bunju requested package \\u201cNormal Service\\u201d for Car service\",\"icon\":\"inbox\",\"color\":\"blue\",\"action_url\":\"https:\\/\\/winga.ericksky.online\\/winga\\/huduma-maombi\",\"action_label\":\"View requests\"}', '2026-04-23 17:17:58', '2026-04-21 20:26:01', '2026-04-23 17:17:58'),
('27e7c1d1-b75f-4b4f-9d27-c82b82be7971', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 238, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('27f045c6-d9ac-48e0-9ab2-3e0f6e0f95a7', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 289, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:36', '2026-05-13 17:07:36'),
('28111cb7-ee0f-42d8-8eed-1cfc135484fb', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 320, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('2834c241-c3f9-41d5-8302-10b19e2199e8', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 60, '{\"title\":\"Withdrawal Request Rejected\",\"message\":\"Your withdrawal request for TZS 5,000 has been rejected. Amount returned to wallet.\",\"icon\":\"x-circle\",\"color\":\"red\",\"action_url\":\"https:\\/\\/winga.ericksky.online\\/winga\\/tomba-ombi\",\"action_label\":\"View Wallet\"}', '2026-04-23 17:17:58', '2026-04-21 20:03:13', '2026-04-23 17:17:58'),
('2845a6e1-92ce-46f6-8abe-b26994f486b7', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 80, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:01', '2026-05-13 17:07:01'),
('28ac302f-d1c6-4ffa-b917-fe3f662f89f9', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 67, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:06:59', '2026-05-13 17:06:59'),
('28e61042-4841-4495-a155-7a4b6866d805', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 270, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('29249679-e755-4200-923b-0b0157ad92c4', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 203, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:21', '2026-05-13 17:07:21'),
('2940dcd5-23c9-4dd4-ac19-c001b6bfe358', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 286, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:35', '2026-05-13 17:07:35'),
('29657917-cd37-4194-bb3b-a7b95e65f5ad', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 335, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:43', '2026-05-13 17:07:43'),
('29bff6a8-1119-4fe8-ae78-73f650f3c7ca', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 61, '{\"title\":\"Ombi Jipya la Kazi!\",\"message\":\"Msume Abdalah ameomba kazi yako: Mshauri wa Lishe na Mpango wa Mazoezi (Miezi 2)\",\"icon\":\"document-text\",\"color\":\"blue\",\"action_url\":\"https:\\/\\/winga.ericksky.online\\/mteja\\/maombi?job_id=29\",\"action_label\":\"Angalia Ombi\"}', NULL, '2026-03-20 08:54:36', '2026-03-20 08:54:36'),
('2a234247-a4fa-41ef-a6ab-55e0101cab7b', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 152, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('2a284d22-5a8b-450c-91c8-9898cc90f892', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 100, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('2a2a38a9-a898-4c5a-aab3-8415dfde4ce6', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 61, '{\"title\":\"Ombi Jipya la Kazi!\",\"message\":\"Abdul Bunju ameomba kazi yako: Shooper keep and guider\",\"icon\":\"document-text\",\"color\":\"blue\",\"action_url\":\"https:\\/\\/winga.ericksky.online\\/mteja\\/maombi?job_id=37\",\"action_label\":\"Angalia Ombi\"}', NULL, '2026-04-21 19:39:49', '2026-04-21 19:39:49'),
('2bd3c262-2d48-41ef-bfbf-876abf6cf326', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 298, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('2c01194e-31b9-4e2c-af05-d4d03bf688b1', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 160, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:14', '2026-05-13 17:07:14'),
('2c52f13a-76bf-41e5-ac41-1a811d8b5c48', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 60, '{\"title\":\"Withdrawal Request Rejected\",\"message\":\"Your withdrawal request for TZS 5,000 has been rejected. Amount returned to wallet.\",\"icon\":\"x-circle\",\"color\":\"red\",\"action_url\":\"https:\\/\\/winga.ericksky.online\\/winga\\/tomba-ombi\",\"action_label\":\"View Wallet\"}', '2026-04-23 17:17:58', '2026-04-21 20:02:55', '2026-04-23 17:17:58'),
('2cb2aa9c-61e0-4982-b4f5-f5cd1da7ec60', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 180, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:18', '2026-05-13 17:07:18'),
('2d09e72f-b6f2-4196-849f-c4a6834a0428', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 110, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:06', '2026-05-13 17:07:06'),
('2d29455d-d32f-4ccf-88f6-2ac40706e611', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 278, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('2d76a219-eee2-4dc0-b60b-994d11f27b71', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 92, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:03', '2026-05-13 17:07:03'),
('2de7c8b3-4485-4dec-8562-12fddc7b72c3', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 60, '{\"title\":\"Withdrawal Request Rejected\",\"message\":\"Your withdrawal request for TZS 10,000 has been rejected. Amount returned to wallet.\",\"icon\":\"x-circle\",\"color\":\"red\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/winga\\/tomba-ombi\",\"action_label\":\"View Wallet\"}', '2026-05-13 16:13:54', '2026-05-02 12:35:44', '2026-05-13 16:13:54'),
('2e1719e6-1a50-43bd-957a-1412586eb0cb', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 64, '{\"title\":\"Ujumbe mpya kutoka Fatuma Ngozi\",\"message\":\"poa\",\"icon\":\"chat-bubble-left-right\",\"color\":\"blue\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/messages\\/1\",\"action_label\":\"Soma Ujumbe\"}', NULL, '2026-03-19 05:40:42', '2026-03-19 05:40:42'),
('2e1dd8e6-c126-49e4-9180-997d41c067e0', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 76, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('2f4a5c81-9082-4220-9034-860548efcdd8', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 326, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('2fab2540-98dc-4991-9717-afa6215b2b27', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 159, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('2fe0b71b-53e5-4a4e-8348-9d3a9493eac6', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 190, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:19', '2026-05-13 17:07:19'),
('2fed5dc3-1c4e-4d25-8574-cd56ef7a70f1', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 307, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('306f5200-f838-4436-ba55-3f12f4c52152', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 158, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:14', '2026-05-13 17:07:14'),
('30fce429-be8e-448a-be38-8cd18052eca6', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 60, '{\"title\":\"New service request\",\"message\":\"Fatuma Ngozi requested package \\u201cWEB DEVELOPMENT\\u201d for Website and application development\",\"icon\":\"inbox\",\"color\":\"blue\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/winga\\/huduma-maombi\",\"action_label\":\"View requests\"}', '2026-04-23 17:17:58', '2026-04-20 22:01:28', '2026-04-23 17:17:58'),
('30fe496b-a74e-4681-843d-72b60375d65f', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 132, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('313a7a30-ef7d-4813-b937-cc9d3575bb6f', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 215, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:23', '2026-05-13 17:07:23');
INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('31748130-a4f9-4eb4-aad1-331e6f630187', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 216, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:24', '2026-05-13 17:07:24'),
('32d33e39-1a9d-47b3-ba71-3910402a34b7', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 263, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:31', '2026-05-13 17:07:31'),
('32ed1da2-a960-4ea0-b347-28cdaacd0eb6', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 273, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('331a21d3-9498-48b2-ada3-68db24f98646', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 60, '{\"title\":\"New service request\",\"message\":\"Railath Bunju requested package \\u201cAgricuture pro\\u201d for Agricuture pro\",\"icon\":\"inbox\",\"color\":\"blue\",\"action_url\":\"https:\\/\\/winga.ericksky.online\\/winga\\/huduma-maombi\",\"action_label\":\"View requests\"}', '2026-04-23 17:17:58', '2026-04-21 21:21:37', '2026-04-23 17:17:58'),
('34051326-fb49-44e9-9a3e-d2324145f419', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 174, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:17', '2026-05-13 17:07:17'),
('341b3a4a-b634-467d-a7a8-7479c8471393', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 330, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:43', '2026-05-13 17:07:43'),
('347ee2b1-1705-4ce3-b53d-9e82f471d7fc', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 173, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('359f4feb-fd8c-4dbe-80e1-ce5021970de9', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 93, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:03', '2026-05-13 17:07:03'),
('35d29ca2-7526-4f01-bdb7-a5c32e6726b8', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 247, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:29', '2026-05-13 17:07:29'),
('369f821b-3a40-455e-a794-2167d1cc1757', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 60, '{\"title\":\"Client paid!\",\"message\":\"Fatuma Ngozi paid TZS 20,000 for Agricuture pro. Start work \\u2014 they will issue a code when done.\",\"icon\":\"currency-dollar\",\"color\":\"green\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/winga\\/weka-code\",\"action_label\":\"Enter code\"}', '2026-05-13 16:13:54', '2026-04-23 18:17:26', '2026-05-13 16:13:54'),
('36a11f72-eaa9-4a19-b873-607cc085eeed', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 211, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('372ef8e4-4742-4dbd-9c08-cabc4aab774e', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 226, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:25', '2026-05-13 17:07:25'),
('37314237-34f7-48d7-92cf-7db9234edf9a', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 117, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('376cd928-f171-4701-806e-2f0a5caef424', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 291, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('3816f24d-00c8-412b-8734-3ac68377f1cb', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 60, '{\"title\":\"\\ud83c\\udf89 Ombi Lako Limekubaliwa!\",\"message\":\"Hongera! Umeajiriwa kazi: \\\"Graphic and designed\\\". Pesa yako imeshikiliwa na Winga, anza kufanya kazi!\",\"icon\":\"check-circle\",\"color\":\"green\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/messages\",\"action_label\":\"Fungua Chat\"}', '2026-04-23 17:17:58', '2026-03-24 01:36:58', '2026-04-23 17:17:58'),
('38af8dd8-3b9a-49c0-829a-64a5696d5a40', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 102, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:04', '2026-05-13 17:07:04'),
('39423fa6-dc01-45f2-acdd-403adf80b166', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 290, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:36', '2026-05-13 17:07:36'),
('398ce7ac-5c80-46f6-876b-d0487c7724d3', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 250, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('39eed68f-435e-4198-adb3-105163d38ecf', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 191, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('3a2cf107-e9e7-4134-acc8-60e0b301d688', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 183, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('3ad8d212-3206-47aa-90c0-a243c3015c3d', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 90, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:02', '2026-05-13 17:07:02'),
('3b2aa3d5-954f-4824-85ea-cc835f088a4a', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 202, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:21', '2026-05-13 17:07:21'),
('3b3e4e19-e526-4a27-bbdb-68792a272554', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 321, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:41', '2026-05-13 17:07:41'),
('3ca03a7d-3718-4c6f-a402-b5f6e6d84d3d', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 177, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('3d641bf5-f0e7-4b12-a3e9-186a69fa6fc5', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 230, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('3e3c157b-fd72-4f0d-8c4e-da2f60860a8a', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 208, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('3e911184-d17b-431a-bd46-d999a46035c9', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 76, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:00', '2026-05-13 17:07:00'),
('3e98cd47-7b7b-4bdf-82fb-bff62210dc93', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 139, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('3ea1bfcb-4b38-47f2-9204-0cbfe9b13aa4', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 60, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', '2026-05-13 16:13:54', '2026-04-23 17:39:50', '2026-05-13 16:13:54'),
('3f0a5ab7-2fa2-4caa-8248-6e81ed987fb4', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 112, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:06', '2026-05-13 17:07:06'),
('3fc18026-ae0a-498d-95ff-832263b79bd9', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 279, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('3fc35703-f720-4283-a382-0ef0c5587434', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 134, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('3fc7871e-d879-41aa-bca8-0746a9cf66ed', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 222, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:25', '2026-05-13 17:07:25'),
('3fce2624-837d-4057-bc95-1a1b14c55af9', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 193, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:20', '2026-05-13 17:07:20'),
('4035d495-8631-49b5-9828-98fae6e58be4', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 242, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:28', '2026-05-13 17:07:28'),
('41bdf4c1-af1a-41aa-a235-5d08a1a90c32', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 245, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('41ca7b57-b7e7-4281-95cc-10b65ce143e5', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 112, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('437e805c-6110-41ae-ab97-9d20ef3a328e', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 60, '{\"title\":\"New service request\",\"message\":\"Fatuma Ngozi requested package \\u201cAgricuture pro\\u201d for Agricuture pro\",\"icon\":\"inbox\",\"color\":\"blue\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/winga\\/huduma-maombi\",\"action_label\":\"View requests\"}', '2026-05-13 16:13:54', '2026-04-23 18:08:23', '2026-05-13 16:13:54'),
('43f0a86f-c59c-4cdc-a07a-ea9c0c04e0f5', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 168, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('44ab0f8a-4bc7-4798-8011-e26888416e10', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 221, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:24', '2026-05-13 17:07:24'),
('450d8973-e1bd-41ab-bbfb-0c3b78002054', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 60, '{\"title\":\"Ujumbe mpya kutoka Fatuma Ngozi\",\"message\":\"0679176625\",\"icon\":\"chat-bubble-left-right\",\"color\":\"blue\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/messages\\/2\",\"action_label\":\"Soma Ujumbe\"}', '2026-04-23 17:17:58', '2026-03-21 09:38:34', '2026-04-23 17:17:58'),
('453b04fe-9f80-4393-9934-218dd29aae0a', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 77, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:00', '2026-05-13 17:07:00'),
('45d80ac2-e345-42c8-a526-2fcec9416c81', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 276, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('46646961-30e6-4c1e-b11d-adc598e16586', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 314, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:40', '2026-05-13 17:07:40'),
('46776499-e71e-409c-a209-dee8a443ad4b', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 262, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:31', '2026-05-13 17:07:31'),
('46dbc9f9-0943-4001-9979-72457447cb6a', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 61, '{\"title\":\"Ujumbe mpya kutoka Ali Juma\",\"message\":\"Poa kabisa za kwako\",\"icon\":\"chat-bubble-left-right\",\"color\":\"blue\",\"action_url\":\"https:\\/\\/winga.ericksky.online\\/messages\\/5\",\"action_label\":\"Soma Ujumbe\"}', NULL, '2026-04-21 21:09:25', '2026-04-21 21:09:25'),
('473cef78-f52f-49bc-9015-08c269e2a61e', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 312, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('4745da34-23b1-4b15-9dac-2fbcc15efb7f', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 136, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:10', '2026-05-13 17:07:10'),
('476dfd15-cdb8-4147-8c0f-5fe848bf265d', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 135, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:10', '2026-05-13 17:07:10'),
('47a99230-c6ab-45db-bc7c-1e49f6d4f7bd', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 153, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('47b3b7aa-98e6-4649-97db-502be8881db1', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 107, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:05', '2026-05-13 17:07:05'),
('48883639-3ea2-4d2a-9bd8-c27b6e118a3f', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 143, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:11', '2026-05-13 17:07:11'),
('48ebbeca-8813-4699-9317-36f81d135f33', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 128, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('48f02a76-d80a-4e58-a3cb-94a1372d7de3', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 342, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('49464ff9-95de-4165-94c7-98f9d104d875', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 292, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:36', '2026-05-13 17:07:36'),
('49d192e8-3a8f-440f-8b58-c26cc4c29070', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 182, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('4a1ae2d4-72c4-4838-932d-bb78af3a8996', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 177, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:17', '2026-05-13 17:07:17'),
('4bd75dbd-d518-48f3-b465-5629973b5b44', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 339, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:44', '2026-05-13 17:07:44'),
('4ca8aaa8-d5d0-4de5-b084-9a7cc92fc2a0', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 60, '{\"title\":\"\\ud83d\\udcb8 Malipo Yanakuja!\",\"message\":\"Code imethibitishwa! TZS 880 yanakuja kwenye simu yako hivi karibuni.\",\"icon\":\"banknotes\",\"color\":\"green\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/winga\\/mapato\",\"action_label\":\"Angalia Mapato\"}', '2026-04-23 17:17:58', '2026-03-21 09:39:41', '2026-04-23 17:17:58'),
('4cd58f0b-07bf-4761-9549-34f313485d82', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 282, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:35', '2026-05-13 17:07:35'),
('4d41f37b-8342-448a-85f4-06e14e15e4ca', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 127, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('4dd5bc07-db50-4700-aac4-ee28132e353c', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 171, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('4e1eb304-d59f-420c-907d-fc347c801aaf', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 231, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:26', '2026-05-13 17:07:26'),
('4e9765bb-9142-4811-b136-214f6b624999', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 61, '{\"title\":\"Request accepted\",\"message\":\"Ali Juma accepted your request for Agricuture pro (Agricuture pro)\",\"icon\":\"check-circle\",\"color\":\"green\",\"action_url\":\"https:\\/\\/winga.ericksky.online\\/messages\",\"action_label\":\"Open messages\"}', NULL, '2026-04-21 17:33:33', '2026-04-21 17:33:33'),
('4eb223e3-af0f-49af-a98a-1246242c426a', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 60, '{\"title\":\"\\u23f3 Ombi Linashughulikiwa\",\"message\":\"Kuna tatizo la muda na mtoa huduma. Malipo yako ya TZS 10,000 yatafanyika ndani ya dakika 30.\",\"icon\":\"clock\",\"color\":\"amber\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/winga\\/tomba-ombi\",\"action_label\":\"Angalia Hali\"}', '2026-05-13 16:13:54', '2026-05-02 12:24:35', '2026-05-13 16:13:54'),
('4f08aaf2-8834-4b3a-af60-09e5b5ec774f', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 249, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('4f5ce488-1b99-4344-bb69-97be8f41e6fb', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 125, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('4f7423de-2047-49e2-89f2-fd5382ad0b0e', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 86, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:02', '2026-05-13 17:07:02'),
('4f9d117f-2aff-4fb8-8eac-cd25b72aae0c', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 108, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('5050c4e6-3088-4d4e-8870-99f779c7075e', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 192, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('50804450-24c0-4cad-8bc0-450a7246fa48', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 223, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('511c8975-6fd6-46d5-b2b9-925d09193e77', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 70, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:06:59', '2026-05-13 17:06:59'),
('51ec5102-8d29-4d1a-8a5c-ea2aa292d44a', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 173, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:16', '2026-05-13 17:07:16'),
('5222464f-11b5-4083-8dad-6229fa577eef', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 103, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:05', '2026-05-13 17:07:05'),
('5223b1a9-ab2c-4774-b737-06a5b7687698', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 166, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:15', '2026-05-13 17:07:15'),
('522c55a3-d359-4ea2-a464-556935a3eeed', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 60, '{\"title\":\"Withdrawal Request Rejected\",\"message\":\"Your withdrawal request for TZS 5,000 has been rejected. Amount returned to wallet.\",\"icon\":\"x-circle\",\"color\":\"red\",\"action_url\":\"https:\\/\\/winga.ericksky.online\\/winga\\/tomba-ombi\",\"action_label\":\"View Wallet\"}', '2026-04-23 17:17:58', '2026-04-21 20:10:13', '2026-04-23 17:17:58'),
('5246bbe0-d886-48ea-b30d-bdfd3f42b1f2', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 283, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:35', '2026-05-13 17:07:35'),
('5271577d-5554-429a-878c-537156df8827', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 328, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('52db4f43-8411-4f4a-b5d2-185dc48dba4d', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 145, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('52e6664f-1e74-483f-914f-6d3ea0cb66b5', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 72, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('532434a7-f0be-4041-96c2-d4e6fd86772f', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 61, '{\"title\":\"Ombi Jipya la Kazi!\",\"message\":\"Ali Juma ameomba kazi yako: Logo Designer\",\"icon\":\"document-text\",\"color\":\"blue\",\"action_url\":\"https:\\/\\/winga.ericksky.online\\/mteja\\/maombi?job_id=38\",\"action_label\":\"Angalia Ombi\"}', NULL, '2026-04-21 20:50:13', '2026-04-21 20:50:13'),
('5332a66b-3eb4-4a8f-96d5-ea408edc13f1', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 61, '{\"title\":\"Ombi Jipya la Kazi!\",\"message\":\"ERICKsky ameomba kazi yako: Makala 20 za Blog kwa Tovuti ya Afya (Kiswahili)\",\"icon\":\"document-text\",\"color\":\"blue\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/mteja\\/maombi?job_id=35\",\"action_label\":\"Angalia Ombi\"}', NULL, '2026-03-19 05:22:00', '2026-03-19 05:22:00'),
('5377c71a-27f0-4c20-b1eb-e6ed0f301e57', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 176, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:17', '2026-05-13 17:07:17'),
('53d86b85-f752-4491-b5da-e4138d927f70', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 175, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('543009de-7ec2-4c5c-9a82-ed4f73daba55', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 147, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:12', '2026-05-13 17:07:12'),
('545fb2e4-e9fa-4112-b3a3-45e95c669d23', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 252, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:30', '2026-05-13 17:07:30'),
('555e8455-993b-4fc4-a03e-f92e6cda839b', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 69, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('55952f02-fc52-4cab-86d1-b4fdffcc1236', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 60, '{\"title\":\"\\ud83d\\udcb8 Malipo Yanakuja!\",\"message\":\"Code imethibitishwa! TZS 880 yanakuja kwenye simu yako hivi karibuni.\",\"icon\":\"banknotes\",\"color\":\"green\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/winga\\/mapato\",\"action_label\":\"Angalia Mapato\"}', '2026-04-23 17:17:58', '2026-03-21 09:39:37', '2026-04-23 17:17:58'),
('564b950b-eb9f-4f6b-863d-2cd1b8e40c9e', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 228, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:26', '2026-05-13 17:07:26'),
('5698e2ac-e4ef-4e3a-9324-b5560d1ce06f', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 165, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:15', '2026-05-13 17:07:15'),
('56a2ef30-04ab-4403-bd29-79ddec4e78d9', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 161, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('56d91402-f03d-4cd6-85ed-fd89b93cdd0d', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 283, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('5705a207-2a07-4326-92ac-86a679b86ecc', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 130, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:09', '2026-05-13 17:07:09'),
('5773da2e-8eaf-463b-8d27-4e3037e0d841', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 310, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('577f5794-ad1e-4eaf-886e-40ee562f19f4', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 189, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:19', '2026-05-13 17:07:19'),
('5790f356-50cd-47fb-a40a-08b1903ba1b6', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 317, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('5893330c-da91-4478-8e8f-e27e6830a499', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 336, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:44', '2026-05-13 17:07:44'),
('5c8692d7-000e-4771-92d6-44978633cdd0', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 74, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:00', '2026-05-13 17:07:00'),
('5cab4134-66db-4d90-a1ea-bb97387eb5d3', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 225, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:25', '2026-05-13 17:07:25'),
('5cab7e39-2175-4e71-b85c-2c2676a99d33', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 265, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:32', '2026-05-13 17:07:32'),
('5cfcf24e-9614-4da4-9ef1-a1c4ac3311fc', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 310, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:39', '2026-05-13 17:07:39'),
('5d297851-436d-4b4c-881f-dceed64345fa', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 323, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:41', '2026-05-13 17:07:41'),
('5d549237-7ccf-445c-af92-2590f1ef69db', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 235, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:27', '2026-05-13 17:07:27'),
('5d5fdc92-e093-400e-a5c9-ab97c4102136', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 296, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:37', '2026-05-13 17:07:37'),
('5d8f891f-e5da-43dd-bd1e-03f5c09d2b42', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 105, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('5daf620e-7fdf-4720-bd66-e1307bb05d04', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 78, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('5e386128-73af-48d8-b902-44c7a8e68a5c', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 239, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('5e49b0b3-8e03-4e8f-9c72-fabfc44906ca', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 87, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:02', '2026-05-13 17:07:02'),
('5e7e761f-7e9a-4f9d-9710-ef2336403730', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 296, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('6068e03e-3077-414e-adb3-97f4d542b898', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 60, '{\"title\":\"\\ud83d\\udd11 Code Imekusanywa!\",\"message\":\"Muajili ametengeneza code ya kukamilisha kazi \\\"Delivery ya Bidhaa za Duka \\u2014 Dar es Salaam Mjini\\\". Ingiza code ukiwa kwenye page ya Weka Code.\",\"icon\":\"key\",\"color\":\"green\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/winga\\/weka-code\",\"action_label\":\"Weka Code Sasa\"}', '2026-04-23 17:17:58', '2026-03-29 13:36:17', '2026-04-23 17:17:58'),
('607af6ca-3781-4dcd-9a14-15293cf087d2', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 119, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('60b48d66-85fb-4b5f-9c61-33188da67bda', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 60, '{\"title\":\"Client paid!\",\"message\":\"Fatuma Ngozi paid TZS 20,000 for Agricuture pro. Start work \\u2014 they will issue a code when done.\",\"icon\":\"currency-dollar\",\"color\":\"green\",\"action_url\":\"http:\\/\\/localhost:8000\\/winga\\/weka-code\",\"action_label\":\"Enter code\"}', '2026-05-13 16:13:54', '2026-05-02 13:47:40', '2026-05-13 16:13:54'),
('60e928fe-5599-4aa5-8de4-297391102249', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 174, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('60ef3835-f526-4bed-a9a4-51868ad129be', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 252, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('615ef322-abe4-4345-9f8f-65123f01d95d', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 80, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('627361f2-66e1-45f5-a597-45dccec4f0ce', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 295, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:37', '2026-05-13 17:07:37'),
('62e89424-119d-4ee5-9e12-98374098f78f', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 317, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:40', '2026-05-13 17:07:40'),
('62f1ee66-5309-41dc-a33f-28326f0ee972', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 268, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('630a9467-70f6-46ae-8f96-6030a8819b4a', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 209, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:22', '2026-05-13 17:07:22'),
('630f8f60-b09f-4316-8a31-5cca5320d038', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 75, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:00', '2026-05-13 17:07:00'),
('631d3bf2-5c90-49a1-81d0-537a344fcb0c', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 151, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('6354f936-e777-4ff1-8cd2-b33ee0b323c6', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 60, '{\"title\":\"\\u2b50 Subscription Imewashwa!\",\"message\":\"Umejiunga na mpango wa Winga Karume. Sasa unaonekana kwenye orodha ya Winga Bora hadi 21 May 2026.\",\"icon\":\"star\",\"color\":\"green\",\"action_url\":\"https:\\/\\/winga.ericksky.online\\/winga\\/subscription\",\"action_label\":\"Angalia Subscription\"}', '2026-04-23 17:17:58', '2026-04-21 22:15:04', '2026-04-23 17:17:58'),
('639cc3e1-65c7-4a1d-901f-0d68f1b499d6', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 318, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:41', '2026-05-13 17:07:41'),
('63c30a96-eadc-4ea7-b9a0-c5b45c7c606a', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 181, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:18', '2026-05-13 17:07:18'),
('63e0f257-a720-41f1-8eb1-b94a429d25d9', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 128, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:09', '2026-05-13 17:07:09'),
('64490360-207b-4f46-a0f7-3e77965e9a0d', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 235, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('649c71c7-53af-4d2b-91b8-eaf8f63058b9', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 60, '{\"title\":\"Ujumbe mpya kutoka Fatuma Ngozi\",\"message\":\"Hello Mambo vp\",\"icon\":\"chat-bubble-left-right\",\"color\":\"blue\",\"action_url\":\"https:\\/\\/winga.ericksky.online\\/messages\\/5\",\"action_label\":\"Soma Ujumbe\"}', '2026-04-23 17:17:58', '2026-04-21 21:08:50', '2026-04-23 17:17:58'),
('65bf52ab-e994-4814-95c1-c3eda53f2010', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 187, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:19', '2026-05-13 17:07:19'),
('6614252e-5f04-4653-b1cd-33e17cd69d9d', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 227, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:25', '2026-05-13 17:07:25'),
('66ee5d08-7312-4d40-a448-c0c8a75208ea', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 222, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('66f44aec-5666-4916-81fb-c53cda7179bf', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 244, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('68174544-dca3-4f7b-b10f-c165bac0bd3e', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 299, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:37', '2026-05-13 17:07:37'),
('68faf1d4-7809-4c76-b9da-1413d738e27c', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 301, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:38', '2026-05-13 17:07:38'),
('6912b28c-cc78-495b-ad1b-45412b140778', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 60, '{\"title\":\"Withdrawal Request Rejected\",\"message\":\"Your withdrawal request for TZS 5,000 has been rejected. Amount returned to wallet.\",\"icon\":\"x-circle\",\"color\":\"red\",\"action_url\":\"https:\\/\\/winga.ericksky.online\\/winga\\/tomba-ombi\",\"action_label\":\"View Wallet\"}', '2026-04-23 17:17:58', '2026-04-21 20:12:52', '2026-04-23 17:17:58'),
('697de9ed-6b6d-4e45-ba05-9ee11f754bdb', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 253, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:30', '2026-05-13 17:07:30'),
('69827b27-dfaa-45f2-b6ff-48b8c73d352b', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 329, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:42', '2026-05-13 17:07:42'),
('69bb74f4-def8-45dc-a10d-9b78516bd131', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 71, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('6a3d4570-c26f-4f8d-9017-b2cfdefaa745', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 288, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:36', '2026-05-13 17:07:36'),
('6a5dec5b-b9e4-49d1-9b4f-c9ef2c63e4c6', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 175, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:17', '2026-05-13 17:07:17'),
('6abdf21a-cd5a-467d-96bb-0558a085b18a', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 213, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:23', '2026-05-13 17:07:23');
INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('6ae0ad7c-26bb-4757-be80-ffe795e62ea3', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 343, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('6b640412-2020-434d-9082-e9f7d8555742', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 122, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:08', '2026-05-13 17:07:08'),
('6b6d15ef-e99b-41ae-8ddd-ec1854a1cb51', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 144, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:12', '2026-05-13 17:07:12'),
('6b754551-472a-4da5-9527-dc9b5c4a6b8b', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 123, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('6be2feaf-95dd-419c-a454-c3fe608537f8', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 155, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('6be77e8a-f02d-4243-85c5-25914e5a921e', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 320, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:41', '2026-05-13 17:07:41'),
('6dae9506-4c90-421e-98a8-1ab0b64b4431', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 124, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('6ec0dedb-94fd-41af-afa9-a2827578626c', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 352, '{\"title\":\"Request accepted\",\"message\":\"Abdul Bunju accepted your request for Website (Premium)\",\"icon\":\"check-circle\",\"color\":\"green\",\"action_url\":\"https:\\/\\/winga.ericksky.online\\/messages\",\"action_label\":\"Open messages\"}', NULL, '2026-04-21 19:24:06', '2026-04-21 19:24:06'),
('6edf293e-8cd3-423b-b18d-ca3f823c810f', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 60, '{\"title\":\"\\ud83d\\udcb8 Malipo Yanakuja!\",\"message\":\"Code imethibitishwa! TZS 80,000 yanakuja kwenye simu yako hivi karibuni.\",\"icon\":\"banknotes\",\"color\":\"green\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/winga\\/mapato\",\"action_label\":\"Angalia Mapato\"}', '2026-04-23 17:17:58', '2026-03-29 13:36:28', '2026-04-23 17:17:58'),
('6edf664f-76aa-41ec-b310-d293a5fca11a', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 72, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:06:59', '2026-05-13 17:06:59'),
('6f1d869d-a54b-4e6e-835d-046c1c747bc4', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 287, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('6fc2634d-874a-423f-b451-926066d93aa4', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 338, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('6fca946e-b4cf-45d1-8e3f-846ec96b576a', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 184, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:18', '2026-05-13 17:07:18'),
('7003f93b-939a-4838-b792-677c8d392086', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 230, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:26', '2026-05-13 17:07:26'),
('70146c35-5854-4178-81e7-e18c26723fad', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 274, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('719fd4ec-9f39-45ad-a80f-262c4b5538cf', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 286, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('71f3a43a-298a-4cc5-9aca-c28abf056842', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 311, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:39', '2026-05-13 17:07:39'),
('720504e3-916f-450c-8fc4-fcba2738ee2a', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 232, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:26', '2026-05-13 17:07:26'),
('721c3c86-f684-4a8b-8c69-37506d60f1bb', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 60, '{\"title\":\"\\u23f3 Ombi Linashughulikiwa\",\"message\":\"Kuna tatizo la muda na mtoa huduma. Malipo yako ya TZS 10,000 yatafanyika ndani ya dakika 30.\",\"icon\":\"clock\",\"color\":\"amber\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/winga\\/tomba-ombi\",\"action_label\":\"Angalia Hali\"}', '2026-04-23 17:17:58', '2026-03-18 22:16:51', '2026-04-23 17:17:58'),
('7234c311-a900-405b-9811-05f7ed7a9918', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 257, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('7267d023-dc1b-4421-8b44-17a81d351200', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 346, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:45', '2026-05-13 17:07:45'),
('728800fd-e98a-41c5-915a-aff1c4081726', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 110, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('7303b82b-1ef8-4126-8c36-a80f3c40db0f', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 135, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('739d1e90-9bed-475b-9b41-9e01a2edb3bd', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 217, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:24', '2026-05-13 17:07:24'),
('73ba894b-c9dc-419e-8be5-e590690c260d', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 309, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:39', '2026-05-13 17:07:39'),
('73bdbe3b-46ca-4351-b68a-1937a78e2f83', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 294, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:37', '2026-05-13 17:07:37'),
('73c12b1d-896a-41d6-97c9-0d3f6353acd1', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 234, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:27', '2026-05-13 17:07:27'),
('75c0fa93-dba8-4858-a855-d922d16097c1', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 248, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:29', '2026-05-13 17:07:29'),
('75cbed5a-bb99-4bb6-a328-ce36a2c0c79d', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 190, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('75cce764-2f6c-4a44-899a-177fab70ed20', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 124, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:08', '2026-05-13 17:07:08'),
('76326a9f-043e-4473-9053-6234534ec913', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 164, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('764b702c-8166-4b5c-8708-8a69abfbe9d5', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 206, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('770f7f1f-8f46-4e75-a785-1cc3afeb2fea', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 60, '{\"title\":\"New service request\",\"message\":\"Railath Bunju requested package \\u201cGraphics designed\\u201d for Graphic designed\",\"icon\":\"inbox\",\"color\":\"blue\",\"action_url\":\"https:\\/\\/winga.ericksky.online\\/winga\\/huduma-maombi\",\"action_label\":\"View requests\"}', '2026-04-23 17:17:58', '2026-04-21 20:28:03', '2026-04-23 17:17:58'),
('7807c915-445f-4592-a2e6-bb4165db790b', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 219, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('78a15b00-f507-43aa-ab2d-30904e79aa7c', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 98, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:04', '2026-05-13 17:07:04'),
('78aed386-3e32-4034-a61d-e536433be6cf', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 250, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:29', '2026-05-13 17:07:29'),
('7913a264-2ffe-418d-a952-6740ab40bac8', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 229, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:26', '2026-05-13 17:07:26'),
('796e06de-40fc-40df-9d93-a9db5f02801a', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 180, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('79c55a0c-3549-43e8-9d12-e5fe7fea1f53', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 292, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('7a2668a6-007f-46ce-8fd1-a902a0751d80', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 89, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('7a89da43-a6f9-4f8f-8a71-f80b685c7aed', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 351, '{\"title\":\"Ombi Limekataliwa\",\"message\":\"Samahani, ombi lako kwa kazi \\\"Shooper keep and guider\\\" halikukubaliwa. Sababu: \\\"asbdiuabd\\\" Endelea kutuma maombi mengine!\",\"icon\":\"x-circle\",\"color\":\"red\",\"action_url\":\"https:\\/\\/winga.ericksky.online\\/tafuta-kazi\",\"action_label\":\"Tafuta Kazi Nyingine\"}', NULL, '2026-04-21 20:59:49', '2026-04-21 20:59:49'),
('7ab92246-172f-4704-b183-f517950feedc', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 115, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:07', '2026-05-13 17:07:07'),
('7af819db-999d-451c-a046-94e377c34d3a', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 83, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:01', '2026-05-13 17:07:01'),
('7b5482e9-6d97-427a-8f92-0ec8b7189abb', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 329, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('7b6f81ba-0813-4b09-b326-740ee2146069', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 195, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('7bf07afb-d208-4905-bb24-43708a504e11', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 69, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:06:59', '2026-05-13 17:06:59'),
('7cb397ee-cb16-4367-9efa-6624f6519dcb', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 97, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:04', '2026-05-13 17:07:04'),
('7cce6e7a-2a86-4e7e-bb68-ee36afdbd9fc', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 257, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:30', '2026-05-13 17:07:30'),
('7cfc593d-d168-40c1-af69-37945a84d551', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 309, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('7dc47e44-a548-4a17-b860-821b1aff2f8f', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 287, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:35', '2026-05-13 17:07:35'),
('7e0f0946-5ea6-44c8-8e83-6b0f5c54dec8', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 168, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:16', '2026-05-13 17:07:16'),
('7fafbb25-4a43-4cd7-956e-0a0cbe5be206', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 60, '{\"title\":\"\\ud83c\\udf89 Ombi Lako Limekubaliwa!\",\"message\":\"Hongera! Umeajiriwa kazi: \\\"Delivery ya Bidhaa za Duka \\u2014 Dar es Salaam Mjini\\\". Pesa yako imeshikiliwa na Winga, anza kufanya kazi!\",\"icon\":\"check-circle\",\"color\":\"green\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/messages\",\"action_label\":\"Fungua Chat\"}', '2026-04-23 17:17:58', '2026-03-29 13:36:06', '2026-04-23 17:17:58'),
('80b11abf-6949-490a-8c6c-71d5bfc548a1', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 74, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('81099f1c-31a2-47c8-b01f-416d0c277361', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 351, '{\"title\":\"New service request\",\"message\":\"Railath Bunju requested package \\u201cPremium\\u201d for Website\",\"icon\":\"inbox\",\"color\":\"blue\",\"action_url\":\"https:\\/\\/winga.ericksky.online\\/winga\\/huduma-maombi\",\"action_label\":\"View requests\"}', NULL, '2026-04-21 19:23:42', '2026-04-21 19:23:42'),
('81536988-987b-4d3e-8b73-3ffa829b4bdd', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 294, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('8234b27a-2a87-45b2-81cc-15f075ab924b', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 158, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('82d19686-652a-4be2-a525-961cfa57a6f4', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 319, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:41', '2026-05-13 17:07:41'),
('82d9d0d7-66f0-46aa-a8e8-e20c33648faa', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 126, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:08', '2026-05-13 17:07:08'),
('82dc6b5b-7817-42c5-8fe9-fa62afea69b1', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 121, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:08', '2026-05-13 17:07:08'),
('834b6951-bb98-4257-a4bb-c0ce9ac8e5ce', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 204, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:22', '2026-05-13 17:07:22'),
('83953e18-5b68-4838-a0c7-ddfcdf485fb9', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 120, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('8468f00b-f359-4a96-9962-3f761909896c', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 85, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:02', '2026-05-13 17:07:02'),
('84a5fe94-8c17-4447-b180-a3763c5e98e3', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 60, '{\"title\":\"Pesa Imewekwa! Anza Kufanya Kazi\",\"message\":\"Mteja amelipa TZS 80,000 kwa kazi: \\\"Delivery ya Bidhaa za Duka \\u2014 Dar es Salaam Mjini\\\". Pesa iko salama \\u2014 anza kazi!\",\"icon\":\"currency-dollar\",\"color\":\"green\",\"action_url\":\"http:\\/\\/localhost:8000\\/messages\",\"action_label\":\"Fungua Chat\"}', '2026-05-13 16:13:54', '2026-05-02 13:48:47', '2026-05-13 16:13:54'),
('858e457d-8be5-42e3-bdf6-d013063f1fd2', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 240, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:28', '2026-05-13 17:07:28'),
('863f948b-449a-4930-963a-a44a5277c091', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 325, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:42', '2026-05-13 17:07:42'),
('86423ff9-e0d2-4610-900c-0ca817cd743e', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 232, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('86c48e2f-c96d-4cbb-a1df-b3ecf7a5ae4f', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 95, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('86df5aa4-9773-4df7-a8cd-fade43124097', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 199, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:21', '2026-05-13 17:07:21'),
('87baf665-8a1a-4eb9-b612-47f8fd3cf1da', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 224, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('88651709-67dc-4a2d-adeb-094cae4d3f47', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 185, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('889b299f-fab0-4baa-bc27-728435e7a26b', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 224, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:25', '2026-05-13 17:07:25'),
('88e1bac2-4bf0-4124-a27e-dee1d82f34ce', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 186, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:19', '2026-05-13 17:07:19'),
('895d9eca-04b6-4ca4-bc6a-8f82217c2363', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 61, '{\"title\":\"Ombi Jipya la Kazi!\",\"message\":\"Ali Juma ameomba kazi yako: Delivery ya Bidhaa za Duka \\u2014 Dar es Salaam Mjini\",\"icon\":\"document-text\",\"color\":\"blue\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/mteja\\/maombi?job_id=32\",\"action_label\":\"Angalia Ombi\"}', NULL, '2026-03-29 13:35:30', '2026-03-29 13:35:30'),
('8a1e5269-1182-410b-802b-3cd9ff329bc4', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 350, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('8a45bc94-02ae-4df7-bedc-8a63679b712e', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 162, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:15', '2026-05-13 17:07:15'),
('8a76110b-2a7f-4cb3-88cb-45c3cc4b7991', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 279, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:34', '2026-05-13 17:07:34'),
('8b6ac36a-e3bf-4517-85cd-b65917ec2742', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 198, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('8ba1ab84-2f5d-4ee6-937e-93e5697ee474', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 345, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:45', '2026-05-13 17:07:45'),
('8c804ba6-128a-4998-ba74-f1e5cc9badfb', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 109, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('8c8125d2-1264-411b-8065-f19228e1031c', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 231, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('8cf3ad78-3600-4891-9f19-3bed645b676a', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 96, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('8d44a42a-c2fb-4162-9335-299d862903f0', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 272, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:33', '2026-05-13 17:07:33'),
('8d5991e7-8882-45da-a658-7010ca7a55fb', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 354, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:46', '2026-05-13 17:07:46'),
('8d63df21-9b0b-4d73-8a0f-5fe661b948fc', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 61, '{\"title\":\"\\u2705 Kazi Imekamilika!\",\"message\":\"Ali Juma ameweka code \\u2014 kazi \\\"Kutafsiri Mkataba wa Kibiashara (Kiingereza \\u2192 Kiswahili)\\\" imekamilika. Malipo yametumwa kwa Winga.\",\"icon\":\"check-circle\",\"color\":\"green\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/mteja\\/kazi-zangu\",\"action_label\":\"Angalia Kazi\"}', NULL, '2026-03-21 09:39:37', '2026-03-21 09:39:37'),
('8daa92cf-adbe-40e2-9d7d-d9494b36e9e4', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 87, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('8dd829ac-0743-463f-ab66-0eeb87103d54', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 208, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:22', '2026-05-13 17:07:22'),
('8dd9d9b3-81eb-4b1c-9349-cf0a03fa3e7a', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 301, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('8ddc4892-55c8-49d2-a0ca-9695e50d0005', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 212, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:23', '2026-05-13 17:07:23'),
('8df600ab-93ec-45e3-b471-97cfa922cdd8', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 60, '{\"title\":\"\\ud83d\\udcb8 Malipo Yanakuja!\",\"message\":\"Code imethibitishwa! TZS 8,800 yanakuja kwenye simu yako hivi karibuni.\",\"icon\":\"banknotes\",\"color\":\"green\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/winga\\/mapato\",\"action_label\":\"Angalia Mapato\"}', '2026-04-23 17:17:58', '2026-03-24 01:53:08', '2026-04-23 17:17:58'),
('8e2bf25b-99f1-4cd0-86f0-3c7c2faa0949', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 313, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('8ed24f37-fa7f-44aa-8794-2b6e6892d361', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 312, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:40', '2026-05-13 17:07:40'),
('8f49fc05-e218-4184-930f-819c7f22bdff', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 318, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('8f77d3a7-12d2-46e8-9e6e-744951e466b8', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 64, '{\"title\":\"Ujumbe mpya kutoka Fatuma Ngozi\",\"message\":\"mambo\",\"icon\":\"chat-bubble-left-right\",\"color\":\"blue\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/messages\\/1\",\"action_label\":\"Soma Ujumbe\"}', NULL, '2026-03-21 09:38:18', '2026-03-21 09:38:18'),
('9037fcef-99d6-456f-b990-75eb39d2145d', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 238, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:27', '2026-05-13 17:07:27'),
('90b49a4b-d5d0-488c-9f80-9aaddc836ea0', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 95, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:03', '2026-05-13 17:07:03'),
('90c6441e-d4ed-445a-b08c-61b27fa8ad4c', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 186, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('91bb8f3d-67d1-4033-8ab4-822bd22e4057', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 154, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('921f0264-9b86-4f15-91fb-b8beda6b6b26', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 141, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:11', '2026-05-13 17:07:11'),
('9257091a-d2ef-4547-87d0-54d02b15501c', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 247, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('92c8353b-dcb2-4ee7-8e04-3f52945ea866', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 82, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('92c866c4-3cf6-4f2d-9a50-6a24c313e9af', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 227, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('92ec9e34-9691-4343-801d-01826f11f24e', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 160, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('93376fd4-24ee-4402-86ab-f4ac8030e11e', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 289, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('938f84b8-a8be-4380-a898-618d53469793', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 130, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('94e578ac-a60b-4ae5-9535-9fb01d2a3fbe', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 116, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:07', '2026-05-13 17:07:07'),
('94e684e0-576a-4020-8570-76c589828277', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 259, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('961a8a70-8412-4790-9b10-af166f56a2c7', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 61, '{\"title\":\"Ombi Jipya la Kazi!\",\"message\":\"ERICKsky ameomba kazi yako: Kupaka Rangi Villa \\u2014 Mbweni Dar es Salaam\",\"icon\":\"document-text\",\"color\":\"blue\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/mteja\\/maombi?job_id=34\",\"action_label\":\"Angalia Ombi\"}', NULL, '2026-03-19 06:10:10', '2026-03-19 06:10:10'),
('96475500-a75d-40f3-ab3b-b390e78ecef3', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 131, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:09', '2026-05-13 17:07:09'),
('96629674-63e0-4cc6-80d9-bc02035c3631', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 274, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:33', '2026-05-13 17:07:33'),
('96a189eb-6077-451a-9be2-8ebe2970749e', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 349, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('96b7b9ba-22f4-4227-84dd-21647fd35bae', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 269, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('970486b8-58a4-48fa-ba06-871422a8164b', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 61, '{\"title\":\"Request accepted\",\"message\":\"Ali Juma accepted your request for Website and application development (WEB DEVELOPMENT)\",\"icon\":\"check-circle\",\"color\":\"green\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/mteja\\/huduma-malipo\",\"action_label\":\"Pay & start service\"}', NULL, '2026-04-23 18:06:53', '2026-04-23 18:06:53'),
('976735f4-827f-41c9-bb6e-a762ad2600ad', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 298, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:37', '2026-05-13 17:07:37'),
('97e03975-621d-4904-8038-dcd32fee88f2', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 192, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:20', '2026-05-13 17:07:20'),
('97f5714c-f4a5-4aa1-bacf-4d89e710aa83', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 141, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('982df6cb-9419-494d-b65d-bbc6fdd02c86', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 220, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('9897292b-a4dc-4535-85c1-b024bba17d6a', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 156, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('98a3a176-eb62-43f8-af2c-1c9e34e49b33', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 181, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('98d5328e-6741-43c1-a3a2-3524bbefc4cb', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 327, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:42', '2026-05-13 17:07:42'),
('99eb3d8b-19b9-42ee-a59b-93d5222afa70', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 354, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('9a663e6f-4ede-4a4e-a87d-e0c69452d592', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 129, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:09', '2026-05-13 17:07:09'),
('9a84983f-1ec4-41d9-9fa5-30c27669300a', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 343, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:45', '2026-05-13 17:07:45'),
('9ad5a510-b917-4bd2-b456-fff0fc215a8e', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 237, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:27', '2026-05-13 17:07:27'),
('9ae7ecd2-1c1e-42c1-8b8d-e727ed3332c2', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 337, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('9b26b324-01c2-43a4-aa92-167a33068fe6', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 267, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:32', '2026-05-13 17:07:32'),
('9b434976-e03a-4ffa-af1a-555aafd7c702', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 81, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('9b8318a9-e366-4273-8265-5ff96555f1bc', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 94, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('9b98059a-14cc-4d9a-9813-e65c6dd225ff', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 131, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('9bf8fb20-6355-4ccb-b0b6-f0d6df45c756', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 65, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:06:58', '2026-05-13 17:06:58'),
('9c4e2e1d-5f65-4e5f-92f2-a08a67df9d13', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 142, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:11', '2026-05-13 17:07:11'),
('9caccc01-840a-47b0-82ae-fcdee62deaf8', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 261, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:31', '2026-05-13 17:07:31'),
('9cff628e-89cc-4ad5-a6ac-a3e57faa41be', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 82, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:01', '2026-05-13 17:07:01'),
('9d40ecb5-0d20-43b1-92fc-5e10bc7ff023', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 323, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('9da3c537-1028-45e1-9a66-ffd942f50588', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 68, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('9e0b91c2-4e0b-4af5-9c11-f2130027b350', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 178, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('9e67780d-4c1a-466c-b23a-3d3cf931fe8c', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 304, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('9eaac003-1a2c-491e-ad13-f6cd642f566e', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 281, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:34', '2026-05-13 17:07:34'),
('9ecdafa2-0d08-4292-88c2-becadcf74ea5', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 60, '{\"title\":\"\\u23f3 Ombi Linashughulikiwa\",\"message\":\"Kuna tatizo la muda na mtoa huduma. Malipo yako ya TZS 5,000 yatafanyika ndani ya dakika 30.\",\"icon\":\"clock\",\"color\":\"amber\",\"action_url\":\"https:\\/\\/winga.ericksky.online\\/winga\\/tomba-ombi\",\"action_label\":\"Angalia Hali\"}', '2026-04-23 17:17:58', '2026-04-21 20:00:51', '2026-04-23 17:17:58'),
('9f744a69-ea1e-44cb-bdcf-2320fbe469da', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 140, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('9f77c878-6f2b-4eba-a2b6-645a02b2cc13', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 102, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('a07a7159-6b46-4871-9813-f2f7eede832b', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 215, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50');
INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('a07c213f-bc26-4705-893d-649b7f806108', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 241, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('a0fb60b8-db3d-4bae-9f17-9a13fcc22249', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 268, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:32', '2026-05-13 17:07:32'),
('a10a34af-d18d-49f3-8ce2-839fa3caa151', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 280, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:34', '2026-05-13 17:07:34'),
('a10f0228-38b7-4f8f-b20d-54cd74ab9e58', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 210, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:23', '2026-05-13 17:07:23'),
('a1539c8e-7c13-44cf-b946-d85b3b25a96b', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 60, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', '2026-05-13 17:11:22', '2026-05-13 17:06:58', '2026-05-13 17:11:22'),
('a2a8292b-e135-4e5f-8d6d-66ae02b8d854', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 326, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:42', '2026-05-13 17:07:42'),
('a2a979a9-04e6-4449-90f5-2fe813b9c0e1', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 313, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:40', '2026-05-13 17:07:40'),
('a2d4ab3f-5a0b-4017-9fd2-a52f788575d9', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 258, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('a3e2ec2f-90b8-4640-885e-7031bbff65f5', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 255, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:30', '2026-05-13 17:07:30'),
('a3e5b8cd-92a9-4bd7-8987-18d92e05dd92', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 167, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('a407b35c-4ca8-4b66-8d6b-49e63845606d', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 290, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('a46aa22b-ad41-4c00-9c7e-f6e475793a0f', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 226, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('a4a07784-1a6b-4c84-bbbb-aa0b7209b972', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 337, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:44', '2026-05-13 17:07:44'),
('a4f88186-4cee-481d-8669-9c8ca31b238a', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 350, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:46', '2026-05-13 17:07:46'),
('a522e193-d360-4388-a826-6693d4ef6f2d', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 137, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('a528b639-1c97-4975-9204-5cf74290a1a2', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 66, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:06:58', '2026-05-13 17:06:58'),
('a54b46a6-daa9-45c6-94f2-a72c87360f42', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 246, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('a5ba024b-0ee9-4d1a-8491-bcf486cb9d64', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 138, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:10', '2026-05-13 17:07:10'),
('a6263798-ca69-4d1f-a7f4-ed1a831367fc', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 149, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('a64a394a-0e28-4a29-8d8a-b07229febbd8', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 267, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('a68c7864-daf8-487c-aa1a-8aae786d4396', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 233, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('a7074d68-ea52-486d-a33f-d5418bad4d04', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 264, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:32', '2026-05-13 17:07:32'),
('a710ef82-a7ea-4601-a962-3da9ec9d8f99', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 60, '{\"title\":\"\\u23f3 Ombi Linashughulikiwa\",\"message\":\"Kuna tatizo la muda na mtoa huduma. Malipo yako ya TZS 5,000 yatafanyika ndani ya dakika 30.\",\"icon\":\"clock\",\"color\":\"amber\",\"action_url\":\"https:\\/\\/winga.ericksky.online\\/winga\\/tomba-ombi\",\"action_label\":\"Angalia Hali\"}', '2026-04-23 17:17:58', '2026-04-21 20:12:25', '2026-04-23 17:17:58'),
('a793bd50-54d8-408f-98cf-b44c6345e487', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 194, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('a7e0f3be-9cd6-4db3-a0be-54c73c6445c2', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 205, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:22', '2026-05-13 17:07:22'),
('a83fb976-124e-4378-bfb8-56e829141191', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 344, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('a8de5e47-fffc-429b-820a-5ae43896cbc1', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 185, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:18', '2026-05-13 17:07:18'),
('a97fcb93-a426-4127-9eda-fde63b35e58e', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 198, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:21', '2026-05-13 17:07:21'),
('a984f615-a053-49e8-a916-f189bddb44a4', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 106, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:05', '2026-05-13 17:07:05'),
('a99e4ce4-d4d9-4180-a55d-ce3a7a2cd22f', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 61, '{\"title\":\"Ombi Jipya la Kazi!\",\"message\":\"Ali Juma ameomba kazi yako: Makala 20 za Blog kwa Tovuti ya Afya (Kiswahili)\",\"icon\":\"document-text\",\"color\":\"blue\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/mteja\\/maombi?job_id=35\",\"action_label\":\"Angalia Ombi\"}', NULL, '2026-03-19 04:48:20', '2026-03-19 04:48:20'),
('a9cb9c7a-88de-481b-8d47-12dee211573d', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 143, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('a9d96d26-e7aa-4e36-83c2-1a8cf2329e35', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 202, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('aa0e46bc-b7f7-45df-9c56-fbd247224c11', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 210, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('aa45ec2c-94ae-4b57-a758-f338eb3c9c6f', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 197, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:20', '2026-05-13 17:07:20'),
('aa95e124-4132-4dbd-9286-230d011edfac', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 61, '{\"title\":\"Ombi Jipya la Kazi!\",\"message\":\"Ali Juma ameomba kazi yako: Graphic and designed\",\"icon\":\"document-text\",\"color\":\"blue\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/mteja\\/maombi?job_id=36\",\"action_label\":\"Angalia Ombi\"}', NULL, '2026-03-24 01:36:42', '2026-03-24 01:36:42'),
('aa9f32f6-8c24-41b4-95e0-5c79f487d425', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 81, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:01', '2026-05-13 17:07:01'),
('aab86c80-f674-4028-867d-ac54ab493924', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 163, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('aadfc8b2-f649-4523-9b8a-9796f3a716cf', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 256, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:30', '2026-05-13 17:07:30'),
('aae86e23-eb23-4e60-a2d6-0e7a1c2f29ce', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 273, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:33', '2026-05-13 17:07:33'),
('ab25bd44-0ad6-4f60-b23e-d28619108217', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 209, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('ab5c6d81-d96c-41d2-9d9f-7ce08afa9dca', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 146, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:12', '2026-05-13 17:07:12'),
('ab78ec13-69f2-4f07-9c68-55f72e191e66', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 291, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:36', '2026-05-13 17:07:36'),
('ac26f245-6355-4b01-9165-c9dc438a5d97', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 216, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('ac547010-6a98-4c3a-8844-84c0e75aae21', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 196, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:20', '2026-05-13 17:07:20'),
('ac78791c-b03b-42f6-b78c-c2d3e5d22e98', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 336, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('ad1b9957-323e-4af7-a38b-b18b2878a28b', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 101, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:04', '2026-05-13 17:07:04'),
('ad4b7d74-bea8-49bb-94ed-b736b948a720', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 178, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:17', '2026-05-13 17:07:17'),
('ae1a78f0-58eb-4f8b-b16f-d509c62e07df', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 182, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:18', '2026-05-13 17:07:18'),
('ae51e625-0c96-4dc9-bb18-09af6d1e2995', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 201, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:21', '2026-05-13 17:07:21'),
('aef10760-cd58-461a-b79d-986ace9fa95e', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 254, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:30', '2026-05-13 17:07:30'),
('af1eb17f-2822-4c57-a7b8-2961e48bcf54', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 297, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('af4c3267-a85a-484f-929d-7ec272288dbc', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 206, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:22', '2026-05-13 17:07:22'),
('af94c1d4-31e4-4c8b-9636-c7dcd7b968af', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 125, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:08', '2026-05-13 17:07:08'),
('afc01726-b89c-41e9-a3a5-cc65c28713d9', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 305, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('afce2d9d-fc5c-4b13-8702-4a7314a95315', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 205, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('afe965ef-b029-4c61-8a47-be0453d48d25', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 219, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:24', '2026-05-13 17:07:24'),
('b003ef7a-27e5-41c5-84de-d2b35ddc4f63', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 295, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('b016c0bd-ae65-42b7-a9c2-1db00c0e051c', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 327, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('b0966fc3-5a2d-41fa-92a0-6b1209663c9e', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 351, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:46', '2026-05-13 17:07:46'),
('b1d3d250-a283-489a-8fea-2ffd32d6fb16', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 241, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:28', '2026-05-13 17:07:28'),
('b1ddced8-79b9-4f15-80df-b7ef8ba64e07', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 332, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('b2cb6e78-e887-4067-87d7-5b433fad4823', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 315, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:40', '2026-05-13 17:07:40'),
('b2e12be6-ef1f-4d83-a574-b13a8f714db7', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 83, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('b30e37b7-4963-480e-8955-170246d749d6', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 184, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('b3a1b8ca-5e1f-4900-955d-60dc62db78c1', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 126, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('b3efafe3-77f4-4440-becf-75660e665698', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 98, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('b4fb35dc-6177-4659-add7-94a50ff5d200', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 120, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:07', '2026-05-13 17:07:07'),
('b507d1be-6567-4211-a584-c8e0e5690b10', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 61, '{\"title\":\"Ombi Jipya la Kazi!\",\"message\":\"Ali Juma ameomba kazi yako: Kupaka Rangi Villa \\u2014 Mbweni Dar es Salaam\",\"icon\":\"document-text\",\"color\":\"blue\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/mteja\\/maombi?job_id=34\",\"action_label\":\"Angalia Ombi\"}', NULL, '2026-03-19 04:50:41', '2026-03-19 04:50:41'),
('b52f2768-ed0c-4ad3-8581-4fe8060721e7', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 107, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('b61a452b-2250-477e-9135-42889bd67747', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 316, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:40', '2026-05-13 17:07:40'),
('b62addf0-530b-440b-af6c-dcc89d68661b', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 61, '{\"title\":\"\\u2705 Kazi Imekamilika!\",\"message\":\"Ali Juma ameweka code \\u2014 kazi \\\"Graphic and designed\\\" imekamilika. Malipo yametumwa kwa Winga.\",\"icon\":\"check-circle\",\"color\":\"green\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/mteja\\/kazi-zangu\",\"action_label\":\"Angalia Kazi\"}', NULL, '2026-03-24 01:53:08', '2026-03-24 01:53:08'),
('b6897003-1048-4f43-9799-2a4318029d58', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 129, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('b6cdc46a-311f-4238-aba9-455107e3e7aa', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 111, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('b7fe831d-32b6-4d55-985d-dd42d8952bcb', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 121, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('b82b0e73-4c06-4af1-a636-59352f61442e', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 154, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:13', '2026-05-13 17:07:13'),
('b8d097b0-c37e-4dc5-babe-5bd88a9ac839', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 163, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:15', '2026-05-13 17:07:15'),
('b8d111c9-84ec-40e4-b03b-abdf9d3f813e', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 272, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('b8d454fb-56d2-4013-bab9-726d328bb10d', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 214, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('b91a657e-e3b1-4768-8603-9b7b7159b79a', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 94, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:03', '2026-05-13 17:07:03'),
('b91d61b9-88f2-465b-9450-bfcbb5979dc8', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 161, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:14', '2026-05-13 17:07:14'),
('b96aa726-e648-44bf-9b92-28d1ab6f91aa', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 242, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('b985580c-1616-46c5-9b9d-612be68266d6', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 179, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('b9bc5608-f68e-4efb-a67c-ffa299070799', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 201, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('ba61a428-ea07-491f-be72-2a6f6d0786cb', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 172, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:16', '2026-05-13 17:07:16'),
('ba7e71f9-1b0c-4894-878a-46ccbee8bbd8', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 254, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('ba8855a3-50a5-4bb5-b00f-f201730b6da8', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 105, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:05', '2026-05-13 17:07:05'),
('bb4f2d9e-c243-41cf-a28b-a021d238de43', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 191, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:19', '2026-05-13 17:07:19'),
('bb5cf92c-2355-4d5f-a376-71f43152cf61', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 79, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('bb64e270-3541-4fb5-aa27-92e14717960e', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 269, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:32', '2026-05-13 17:07:32'),
('bb7c0703-3b68-4fa2-b957-5818d252c83b', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 349, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:46', '2026-05-13 17:07:46'),
('bc44c99c-f0ae-443a-bef9-85b587d93c73', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 237, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('bc733eb5-0192-4479-9a2b-bf12aed9fd6f', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 113, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('bc83f6c9-2a30-4d89-a25b-02556e58fb9d', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 344, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:45', '2026-05-13 17:07:45'),
('bcd1b5d2-7615-4097-8dbe-ab62c3b0766d', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 278, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:34', '2026-05-13 17:07:34'),
('bcf5d3bf-b5b1-4cc9-9c76-6d1033c6da6a', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 92, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('bd4d8124-a921-4a48-a96b-b5d6cc04ffd2', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 144, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('be51814c-2014-4575-94dc-2ef2480429c5', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 299, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('bece93d6-ac91-4fb1-8f82-c644e778bb2f', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 99, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('bf10d332-5464-48cc-89ba-859d6df16f99', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 73, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:00', '2026-05-13 17:07:00'),
('c0c6a8d7-041c-46c2-bb87-2c5545822ace', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 234, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('c0cb2899-5592-4209-b7b5-d1b493d800ab', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 293, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:36', '2026-05-13 17:07:36'),
('c0dac976-f1d2-44bc-a93e-4f40b8f7bc1b', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 61, '{\"title\":\"\\u2705 Kazi Imekamilika!\",\"message\":\"Ali Juma ameweka code \\u2014 kazi \\\"Kutafsiri Mkataba wa Kibiashara (Kiingereza \\u2192 Kiswahili)\\\" imekamilika. Malipo yametumwa kwa Winga.\",\"icon\":\"check-circle\",\"color\":\"green\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/mteja\\/kazi-zangu\",\"action_label\":\"Angalia Kazi\"}', NULL, '2026-03-21 09:39:41', '2026-03-21 09:39:41'),
('c12f295e-8fe6-47c9-af8c-35ae20e9907b', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 207, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('c13c2afa-15e7-4819-9c3f-af8d66e7f824', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 148, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:12', '2026-05-13 17:07:12'),
('c175b12b-e7e2-415d-ace4-3b2094a4d329', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 266, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:32', '2026-05-13 17:07:32'),
('c23c4c48-ae82-43fb-b40f-f3d8cd31dbb2', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 75, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('c27cbc45-97a0-4e3c-99e2-9ef87dae643e', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 60, '{\"title\":\"Ombi Limekataliwa\",\"message\":\"Samahani, ombi lako kwa kazi \\\"Kupaka Rangi Villa \\u2014 Mbweni Dar es Salaam\\\" halikukubaliwa. Endelea kutuma maombi mengine!\",\"icon\":\"x-circle\",\"color\":\"red\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/tafuta-kazi\",\"action_label\":\"Tafuta Kazi Nyingine\"}', '2026-04-23 17:17:58', '2026-03-19 05:25:16', '2026-04-23 17:17:58'),
('c2acf512-6cb0-418a-8e2a-a8ff222e8e5c', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 114, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:06', '2026-05-13 17:07:06'),
('c349c811-36ca-404f-a2b0-0a96193d3a7e', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 89, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:02', '2026-05-13 17:07:02'),
('c42eccd6-ce95-49bb-8bb9-2dd75d39fe04', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 285, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('c55f9f5b-9dd3-49ab-ad0e-b0e4eaae94f1', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 264, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('c560879f-8974-44f5-9801-5724dad4ae25', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 271, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('c5cfabf5-2437-4398-8755-716cf5ac20ca', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 61, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('c5d82807-8484-4c33-a773-a26879f39f01', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 324, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('c62a8503-4230-4a48-b2be-5f86e4584939', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 305, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:38', '2026-05-13 17:07:38'),
('c7019eea-4ea4-4b46-97ce-c4ec9e4244f4', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 282, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('c771a158-e427-4778-9dac-c00a267c810a', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 353, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:46', '2026-05-13 17:07:46'),
('c7a44523-b800-4334-8762-7861ea1befc8', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 306, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:39', '2026-05-13 17:07:39'),
('c7d89924-7192-4acf-a2f4-e6698afd8131', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 64, '{\"title\":\"\\ud83d\\udd11 Code Imekusanywa!\",\"message\":\"Muajili ametengeneza code ya kukamilisha kazi \\\"Makala 20 za Blog kwa Tovuti ya Afya (Kiswahili)\\\". Ingiza code ukiwa kwenye page ya Weka Code.\",\"icon\":\"key\",\"color\":\"green\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/winga\\/weka-code\",\"action_label\":\"Weka Code Sasa\"}', NULL, '2026-03-19 05:51:02', '2026-03-19 05:51:02'),
('c8412c70-00eb-48a3-9603-f82db4b47f7e', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 258, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:31', '2026-05-13 17:07:31'),
('c851df03-f489-41b3-8759-e9453bab4f3a', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 164, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:15', '2026-05-13 17:07:15'),
('c92e705f-4c19-48ce-a05c-57fcc9ae21b4', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 60, '{\"title\":\"Withdrawal Request Rejected\",\"message\":\"Your withdrawal request for TZS 5,000 has been rejected. Amount returned to wallet.\",\"icon\":\"x-circle\",\"color\":\"red\",\"action_url\":\"https:\\/\\/winga.ericksky.online\\/winga\\/tomba-ombi\",\"action_label\":\"View Wallet\"}', '2026-04-23 17:17:58', '2026-04-21 20:03:04', '2026-04-23 17:17:58'),
('c94778d8-e733-4e15-ad54-c47c2db05f06', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 73, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('c95f5df6-197c-44dc-b7f2-ba1ab1e1f119', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 248, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('c96fa768-26bc-4d09-b4e6-eb641d6f9753', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 60, '{\"title\":\"\\u23f3 Ombi Linashughulikiwa\",\"message\":\"Kuna tatizo la muda na mtoa huduma. Malipo yako ya TZS 5,000 yatafanyika ndani ya dakika 30.\",\"icon\":\"clock\",\"color\":\"amber\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/winga\\/tomba-ombi\",\"action_label\":\"Angalia Hali\"}', '2026-05-13 16:13:54', '2026-04-27 18:50:10', '2026-05-13 16:13:54'),
('ca408f63-042b-4055-a2f2-11734e240aea', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 297, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:37', '2026-05-13 17:07:37'),
('caa4f3ac-bd47-40c0-9c5f-345076672643', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 339, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('cb17e0c0-a5f6-4802-b898-5f102faa5359', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 123, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:08', '2026-05-13 17:07:08'),
('cb3109d2-3185-4494-9629-11f83ee369a5', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 100, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:04', '2026-05-13 17:07:04'),
('cbca2d98-525b-447e-8747-b4dd07b19ec0', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 103, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('cbfe5981-1a42-4072-80e6-2d0e40f6fbac', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 60, '{\"title\":\"\\ud83d\\udd11 Code Imekusanywa!\",\"message\":\"Muajili ametengeneza code ya kukamilisha kazi \\\"Kutafsiri Mkataba wa Kibiashara (Kiingereza \\u2192 Kiswahili)\\\". Ingiza code ukiwa kwenye page ya Weka Code.\",\"icon\":\"key\",\"color\":\"green\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/winga\\/weka-code\",\"action_label\":\"Weka Code Sasa\"}', '2026-04-23 17:17:58', '2026-03-21 09:38:54', '2026-04-23 17:17:58'),
('cc1f16bb-ba6e-482e-8df2-d7dca8ac56df', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 71, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:06:59', '2026-05-13 17:06:59'),
('ccc2b276-be5c-4001-8b81-0d74ab8a2e27', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 79, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:01', '2026-05-13 17:07:01'),
('ccc76c26-6182-4093-a8db-20fef3f2e4b8', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 86, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('cd36a844-d7f7-4bc0-8595-80b96f514073', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 91, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('cd6d560a-df98-49f5-8f2a-e881897819bb', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 348, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:46', '2026-05-13 17:07:46'),
('cdc9e63f-6e41-4554-bb4a-852e609596a1', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 145, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:12', '2026-05-13 17:07:12'),
('cdd114d2-e311-42cb-a396-cc7cb07871a9', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 88, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:02', '2026-05-13 17:07:02'),
('ce7cd4ba-83e5-4c52-9989-81c2542465b1', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 348, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('ce80ebe8-9318-4a22-abc2-1b11c45de4c5', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 88, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('cedb41df-cd88-49d8-a330-ba64f0f018cc', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 271, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:33', '2026-05-13 17:07:33'),
('cf74bb37-079f-42ec-acb1-0304c12d6639', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 218, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:24', '2026-05-13 17:07:24');
INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('cfc0306c-b3bd-405f-8bab-3c222ffc00bd', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 106, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('d0a724b9-759a-4f83-9837-55b81a448c63', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 70, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('d0ab812a-70da-4514-a89a-8effac844ee5', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 221, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('d0aeb101-4192-4326-b04f-8a1037b062de', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 251, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:29', '2026-05-13 17:07:29'),
('d0ca3f9a-5dd4-46ab-b9dd-42dd579412d9', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 187, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('d128c5fe-9ed0-471d-a4e2-176891a4b339', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 60, '{\"title\":\"\\ud83d\\udd11 Code Imekusanywa!\",\"message\":\"Muajili ametengeneza code ya kukamilisha kazi \\\"Graphic and designed\\\". Ingiza code ukiwa kwenye page ya Weka Code.\",\"icon\":\"key\",\"color\":\"green\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/winga\\/weka-code\",\"action_label\":\"Weka Code Sasa\"}', '2026-04-23 17:17:58', '2026-03-24 01:53:00', '2026-04-23 17:17:58'),
('d1415e5f-4798-4df3-96f1-d0be87f5f488', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 156, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:14', '2026-05-13 17:07:14'),
('d21ce88b-27ad-4a46-bc8f-7e1fc98745e7', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 245, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:28', '2026-05-13 17:07:28'),
('d2686298-938e-4207-ae1e-b748282db9a8', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 233, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:26', '2026-05-13 17:07:26'),
('d2856b47-ad73-4a69-b9e4-f356535e06ad', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 239, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:27', '2026-05-13 17:07:27'),
('d386347f-aba0-48fb-b2cc-ce91624ae714', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 197, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('d3a6b95d-4c9b-4607-8782-78d03c9a4be2', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 60, '{\"title\":\"\\u23f3 Ombi Linashughulikiwa\",\"message\":\"Kuna tatizo la muda na mtoa huduma. Malipo yako ya TZS 5,000 yatafanyika ndani ya dakika 30.\",\"icon\":\"clock\",\"color\":\"amber\",\"action_url\":\"https:\\/\\/winga.ericksky.online\\/winga\\/tomba-ombi\",\"action_label\":\"Angalia Hali\"}', '2026-04-23 17:17:58', '2026-04-21 20:08:21', '2026-04-23 17:17:58'),
('d41f4bfb-971a-4b9b-882f-43da779765da', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 229, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('d4378367-9890-4354-867f-2480931c4467', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 99, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:04', '2026-05-13 17:07:04'),
('d45a550f-efe2-4603-bdaa-971e866af265', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 277, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:34', '2026-05-13 17:07:34'),
('d4a18d21-4b3d-46f4-a828-4a5211ef17b2', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 91, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:03', '2026-05-13 17:07:03'),
('d59149f6-1d35-4578-8802-59a27e56b920', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 315, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('d5a1a1a8-18ac-4ee2-bf9c-9a512bd106ad', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 223, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:25', '2026-05-13 17:07:25'),
('d5ac13d6-4a59-4fe6-b782-c025bff06ac7', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 306, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('d5bda72b-cd0b-448f-a0ae-6bbf7a7a0b48', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 338, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:44', '2026-05-13 17:07:44'),
('d667f256-e9b0-4a89-bb6c-2adf54e99b75', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 61, '{\"title\":\"Request declined\",\"message\":\"Ali Juma declined your request for Agricuture pro (Agricuture pro)\\nSababu: sitaki\",\"icon\":\"x-circle\",\"color\":\"zinc\",\"action_url\":null,\"action_label\":null}', NULL, '2026-05-02 13:22:16', '2026-05-02 13:22:16'),
('d69247ea-aa1b-4221-b2c7-b680422b038f', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 115, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('d744f96c-a4e9-4d8d-a353-36ccbd68665f', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 64, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('d8019ebf-1ebb-4af3-86f0-6ae565b2256e', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 277, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('d8473113-3ffb-4e8d-87c9-d618b2ae2258', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 60, '{\"title\":\"\\u23f3 Ombi Linashughulikiwa\",\"message\":\"Kuna tatizo la muda na mtoa huduma. Malipo yako ya TZS 5,000 yatafanyika ndani ya dakika 30.\",\"icon\":\"clock\",\"color\":\"amber\",\"action_url\":\"https:\\/\\/winga.ericksky.online\\/winga\\/tomba-ombi\",\"action_label\":\"Angalia Hali\"}', '2026-04-23 17:17:58', '2026-04-21 19:58:40', '2026-04-23 17:17:58'),
('d8cf2aa5-8e75-4b51-9953-c947e8af91b4', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 311, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('d96f292a-689a-4e36-be50-8aa12b40d4bb', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 165, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('d9b32c7d-e242-43c0-a776-4f877325b95a', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 60, '{\"title\":\"\\u23f3 Ombi Linashughulikiwa\",\"message\":\"Kuna tatizo la muda na mtoa huduma. Malipo yako ya TZS 5,000 yatafanyika ndani ya dakika 30.\",\"icon\":\"clock\",\"color\":\"amber\",\"action_url\":\"https:\\/\\/winga.ericksky.online\\/winga\\/tomba-ombi\",\"action_label\":\"Angalia Hali\"}', '2026-04-23 17:17:58', '2026-04-21 20:08:07', '2026-04-23 17:17:58'),
('da411f73-1bbf-44cf-85ed-72bb5168a27e', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 225, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('db45aec3-a04e-4069-97a9-b064d0237c34', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 77, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('db8944fa-0b06-4186-8e3e-1751ade21f5f', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 97, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('dbb1042d-6af4-420e-bc62-ae1eb9a01787', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 244, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:28', '2026-05-13 17:07:28'),
('dbea2b15-519b-4dbf-a9b8-31b119ae74dc', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 188, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('dc36efa1-d4eb-4b35-861d-fcad6841662d', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 319, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('dd849c56-641c-4b9e-b1a4-0b665c1fee8b', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 284, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:35', '2026-05-13 17:07:35'),
('de237bee-870b-4d1a-af36-2d3576738e69', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 68, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:06:59', '2026-05-13 17:06:59'),
('defca8cb-35e7-467c-afb4-ba8316b68c02', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 104, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:05', '2026-05-13 17:07:05'),
('dfb13597-b470-419d-8778-fc15d7199cda', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 183, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:18', '2026-05-13 17:07:18'),
('e06a8cfd-0fb1-40b7-a7bb-9b7acc2ac897', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 60, '{\"title\":\"Ombi Limekataliwa\",\"message\":\"Samahani, ombi lako kwa kazi \\\"Makala 20 za Blog kwa Tovuti ya Afya (Kiswahili)\\\" halikukubaliwa. Endelea kutuma maombi mengine!\",\"icon\":\"x-circle\",\"color\":\"red\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/tafuta-kazi\",\"action_label\":\"Tafuta Kazi Nyingine\"}', '2026-04-23 17:17:58', '2026-03-19 04:49:11', '2026-04-23 17:17:58'),
('e0d6f223-bf36-4c8f-9818-c3b8ed66fde8', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 275, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:33', '2026-05-13 17:07:33'),
('e0de1826-078f-4baf-830f-be88f046d0f1', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 265, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('e17a2884-2c3f-4885-b5e0-15f39df8ed1e', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 64, '{\"title\":\"\\ud83d\\udd11 Code Imekusanywa!\",\"message\":\"Muajili ametengeneza code ya kukamilisha kazi \\\"Makala 20 za Blog kwa Tovuti ya Afya (Kiswahili)\\\". Ingiza code ukiwa kwenye page ya Weka Code.\",\"icon\":\"key\",\"color\":\"green\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/winga\\/weka-code\",\"action_label\":\"Weka Code Sasa\"}', NULL, '2026-03-19 06:15:52', '2026-03-19 06:15:52'),
('e25b6860-95d1-49a0-bcd6-7ffdcabb4554', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 243, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('e2d66a90-519e-4b97-8359-0f165b4430ae', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 276, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:34', '2026-05-13 17:07:34'),
('e4bd3e21-a51a-4a95-a42c-58d0c2d31a87', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 179, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:17', '2026-05-13 17:07:17'),
('e4eb5244-c7d0-47f3-b8b5-2201cb825818', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 64, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:06:58', '2026-05-13 17:06:58'),
('e520ebba-d939-45e9-ac75-dcac4f644e7b', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 138, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('e5ee715d-901c-4164-99df-91425b434676', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 139, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:11', '2026-05-13 17:07:11'),
('e70e8f56-01c6-46bb-9489-1c3f9b282d58', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 334, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('e73baf5b-1081-4025-92b8-62926a3771cd', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 211, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:23', '2026-05-13 17:07:23'),
('e8f559b1-0c36-4f60-a391-9f5277746f45', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 60, '{\"title\":\"Ombi Lako Limekubaliwa!\",\"message\":\"Hongera! Mteja amekubali ombi lako kwa kazi: \\\"Logo Designer\\\". Zungumza naye kukubaliana!\",\"icon\":\"check-circle\",\"color\":\"green\",\"action_url\":\"https:\\/\\/winga.ericksky.online\\/messages\",\"action_label\":\"Fungua Chat\"}', '2026-04-23 17:17:58', '2026-04-21 20:55:08', '2026-04-23 17:17:58'),
('e975dda3-5281-46bd-b86a-9834cbf68567', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 236, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:27', '2026-05-13 17:07:27'),
('e978a48e-3b80-4177-8871-07239d0036c7', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 61, '{\"title\":\"Ombi Jipya la Kazi!\",\"message\":\"Ali Juma ameomba kazi yako: Kutafsiri Mkataba wa Kibiashara (Kiingereza \\u2192 Kiswahili)\",\"icon\":\"document-text\",\"color\":\"blue\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/mteja\\/maombi?job_id=23\",\"action_label\":\"Angalia Ombi\"}', NULL, '2026-03-21 09:37:39', '2026-03-21 09:37:39'),
('e995fec3-d1c6-4b97-814a-4be251599727', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 78, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:00', '2026-05-13 17:07:00'),
('e9e006b3-0189-4ca5-858a-b888ae06910d', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 64, '{\"title\":\"Ujumbe mpya kutoka Fatuma Ngozi\",\"message\":\"0678165524\",\"icon\":\"chat-bubble-left-right\",\"color\":\"blue\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/messages\\/1\",\"action_label\":\"Soma Ujumbe\"}', NULL, '2026-03-21 09:38:25', '2026-03-21 09:38:25'),
('ea41c786-de09-4d83-80c2-fd06cf30a381', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 214, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:23', '2026-05-13 17:07:23'),
('ea81e406-6fe3-4cc4-80f5-b159b9907876', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 61, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:06:58', '2026-05-13 17:06:58'),
('eab1f7da-72e4-4aa5-b0d4-24e0969cdff3', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 261, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('eb19f380-3eb0-4d7f-b1eb-f341f88baf4b', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 263, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('ec93b28e-0e23-431f-b87d-1874d71a79a4', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 85, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('ecac453a-aa31-4254-bbae-548b583fb03d', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 308, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('ecc53fb6-5fe1-4847-b0c8-fb5505955a7a', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 64, '{\"title\":\"\\u23f8\\ufe0f Malipo Yameshikiliwa Muda\",\"message\":\"Muajili ameweka kazi \\\"Makala 20 za Blog kwa Tovuti ya Afya (Kiswahili)\\\" kwenye tathmini kwa masaa 12. Wasiliana nao ili utatue tatizo kabla ya muda haujaisha.\",\"icon\":\"clock\",\"color\":\"amber\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/messages\",\"action_label\":\"Fungua Chat\"}', NULL, '2026-03-19 05:58:51', '2026-03-19 05:58:51'),
('ece16ab8-e476-499d-a01d-b751a1c1e506', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 262, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('ecf8f262-9277-4581-b851-6f1d1de07575', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 127, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:09', '2026-05-13 17:07:09'),
('ed57091e-2d08-417c-a400-ca0d66320aac', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 220, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:24', '2026-05-13 17:07:24'),
('edf6d64b-f1ad-4487-8efe-424566ccb4b4', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 249, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:29', '2026-05-13 17:07:29'),
('ee076ec5-4f97-4c4f-b2cf-3ba4ab9bda12', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 189, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('eec1946c-b62c-4fda-8cb3-e76cb44a6fc4', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 259, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:31', '2026-05-13 17:07:31'),
('ef275ff5-a033-4911-b78c-788d44d749e3', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 346, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('efe9c377-215b-46de-8318-f27ac93ac67c', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 333, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:43', '2026-05-13 17:07:43'),
('f093ace4-b204-48e8-ada2-ae68c91dd10d', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 167, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:15', '2026-05-13 17:07:15'),
('f1cb3db7-e5cd-4a19-a3fe-5bb8e8273daa', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 60, '{\"title\":\"\\u2b50 Subscription Imewashwa!\",\"message\":\"Umejiunga na mpango wa Winga Karume. Sasa unaonekana kwenye orodha ya Winga Bora hadi 21 May 2026.\",\"icon\":\"star\",\"color\":\"green\",\"action_url\":\"https:\\/\\/winga.ericksky.online\\/winga\\/subscription\",\"action_label\":\"Angalia Subscription\"}', '2026-04-23 17:17:58', '2026-04-21 20:14:42', '2026-04-23 17:17:58'),
('f258ea04-5728-47c1-b46f-a5f15736c799', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 200, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:21', '2026-05-13 17:07:21'),
('f3b2bd51-54e7-40f6-9e28-d38cf4cfc6b5', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 321, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('f3c35987-578f-4c4c-8c05-a3317273f133', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 109, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:06', '2026-05-13 17:07:06'),
('f3d7869e-06f4-4b01-938a-3804a3f6c43b', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 341, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:44', '2026-05-13 17:07:44'),
('f43a3f03-0007-4bf8-af4a-3f71a41f6243', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 335, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('f45bd929-64af-41d5-a0b1-ad674605da03', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 340, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('f4c28fba-5405-4e24-a8e0-4d0bdbf41777', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 176, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('f54b0a42-9505-407b-9d4e-efa784a331ec', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 60, '{\"title\":\"Withdrawal Request Rejected\",\"message\":\"Your withdrawal request for TZS 5,000 has been rejected. Amount returned to wallet.\",\"icon\":\"x-circle\",\"color\":\"red\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/winga\\/tomba-ombi\",\"action_label\":\"View Wallet\"}', '2026-05-13 16:13:54', '2026-05-02 12:23:25', '2026-05-13 16:13:54'),
('f595e831-6fba-45c0-b03d-0f30ac32d557', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 66, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('f691096e-08aa-4e65-94d4-94bfc9342a4e', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 302, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:38', '2026-05-13 17:07:38'),
('f6ba9fbc-614f-4f56-99b1-861aefbb166c', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 303, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:38', '2026-05-13 17:07:38'),
('f7256091-d2c8-46d2-a099-322e3f554fd7', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 60, '{\"title\":\"New service request\",\"message\":\"Fatuma Ngozi requested package \\u201cAgricuture pro\\u201d for Agricuture pro\",\"icon\":\"inbox\",\"color\":\"blue\",\"action_url\":\"https:\\/\\/winga.ericksky.online\\/winga\\/huduma-maombi\",\"action_label\":\"View requests\"}', '2026-04-23 17:17:58', '2026-04-21 17:31:22', '2026-04-23 17:17:58'),
('f796b482-eee2-482c-b908-072bb66403c1', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 60, '{\"title\":\"\\u2b50 Subscription Imewashwa!\",\"message\":\"Umejiunga na mpango wa Winga Karume. Sasa unaonekana kwenye orodha ya Winga Bora hadi 21 May 2026.\",\"icon\":\"star\",\"color\":\"green\",\"action_url\":\"https:\\/\\/winga.ericksky.online\\/winga\\/subscription\",\"action_label\":\"Angalia Subscription\"}', '2026-04-23 17:17:58', '2026-04-21 20:17:39', '2026-04-23 17:17:58'),
('f7e7b70e-92ef-4a63-95fc-61dc7d724f04', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 330, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('f8adba4d-6a7e-493d-81bd-23f4310bfa37', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 332, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:43', '2026-05-13 17:07:43'),
('f8b4887a-f76e-4514-b0f8-c89b4647745c', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 207, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:22', '2026-05-13 17:07:22'),
('f8d4de4c-fb1a-4e31-a2e8-55c74a58fcaa', 'App\\Notifications\\WingaNotification', 'App\\Models\\User', 64, '{\"title\":\"\\ud83c\\udf89 Ombi Lako Limekubaliwa!\",\"message\":\"Hongera! Umeajiriwa kazi: \\\"Makala 20 za Blog kwa Tovuti ya Afya (Kiswahili)\\\". Pesa yako imeshikiliwa na Winga, anza kufanya kazi!\",\"icon\":\"check-circle\",\"color\":\"green\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/messages\",\"action_label\":\"Fungua Chat\"}', '2026-03-19 05:40:00', '2026-03-19 05:24:54', '2026-03-19 05:40:00'),
('f8f9f8bf-7184-400c-96e8-dde6c2361182', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 101, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('f94b04b2-e448-45c9-91e2-053fb01f4356', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 133, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:10', '2026-05-13 17:07:10'),
('f958a8ec-6e09-4588-a8f5-cdcc979bd769', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 316, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('f984803c-465d-4c76-ba21-221bd6331dfc', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 172, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('f98901bf-ec78-4ac8-82b7-4f7b03d6655c', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 204, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('fa3ab526-119c-4c9b-b20c-4b674fd608d5', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 203, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('fab1cd16-8e44-4727-a199-d74360ff71ed', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 345, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('fb604d58-d31b-489a-8dc6-866121d7b434', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 90, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('fc69f09d-d25a-46b9-a16c-316a6bb60013', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 324, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:42', '2026-05-13 17:07:42'),
('fcb39f34-ca64-462f-a72e-e4af5ecb8a1e', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 152, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:13', '2026-05-13 17:07:13'),
('fe637ee3-157f-4185-bb36-a97a54f1a752', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 275, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":3}', NULL, '2026-04-23 17:39:50', '2026-04-23 17:39:50'),
('fe89ec6f-4ae1-4bc3-b8c3-ca9a74042985', 'App\\Notifications\\AdminBroadcastNotification', 'App\\Models\\User', 322, '{\"title\":\"Welcome\",\"message\":\"Welcome to uor community\",\"icon\":\"megaphone\",\"color\":\"blue\",\"action_url\":null,\"action_label\":null,\"broadcast_id\":2}', NULL, '2026-05-13 17:07:41', '2026-05-13 17:07:41');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `job_id` bigint(20) UNSIGNED DEFAULT NULL,
  `service_request_id` bigint(20) UNSIGNED DEFAULT NULL,
  `employer_id` bigint(20) UNSIGNED NOT NULL,
  `worker_id` bigint(20) UNSIGNED DEFAULT NULL,
  `amount` decimal(12,2) NOT NULL,
  `platform_fee` decimal(12,2) NOT NULL DEFAULT 0.00,
  `worker_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `status` enum('pending','escrowed','released','refunded','disputed') NOT NULL DEFAULT 'pending',
  `payment_method` varchar(255) DEFAULT NULL,
  `payment_reference` varchar(255) DEFAULT NULL,
  `payout_reference` varchar(255) DEFAULT NULL,
  `payout_status` enum('pending','processing','completed','failed') NOT NULL DEFAULT 'pending',
  `retry_count` int(11) NOT NULL DEFAULT 0,
  `last_retry_at` timestamp NULL DEFAULT NULL,
  `escrow_released_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `job_id`, `service_request_id`, `employer_id`, `worker_id`, `amount`, `platform_fee`, `worker_amount`, `status`, `payment_method`, `payment_reference`, `payout_reference`, `payout_status`, `retry_count`, `last_retry_at`, `escrow_released_at`, `created_at`, `updated_at`, `approved_by`) VALUES
(1, 35, NULL, 61, 64, 400000.00, 48000.00, 352000.00, 'escrowed', 'wallet', NULL, NULL, 'pending', 0, NULL, NULL, '2026-03-19 05:24:54', '2026-03-19 05:24:54', NULL),
(2, 23, NULL, 61, 60, 1000.00, 120.00, 880.00, 'released', 'wallet', NULL, NULL, 'processing', 0, NULL, '2026-03-21 09:39:36', '2026-03-21 09:38:04', '2026-03-21 09:39:36', NULL),
(3, 36, NULL, 61, 60, 10000.00, 1200.00, 8800.00, 'released', 'wallet', NULL, NULL, 'processing', 0, NULL, '2026-03-24 01:53:07', '2026-03-24 01:36:58', '2026-03-24 01:53:07', NULL),
(4, 32, NULL, 61, 60, 88000.00, 8000.00, 80000.00, 'released', 'wallet', NULL, NULL, 'completed', 0, NULL, '2026-03-29 13:36:28', '2026-03-29 13:36:06', '2026-03-29 13:36:28', NULL),
(5, NULL, 7, 61, 60, 20000.00, 2000.00, 18000.00, 'escrowed', 'wallet', NULL, NULL, 'pending', 0, NULL, NULL, '2026-04-23 18:17:26', '2026-04-23 18:17:26', NULL),
(6, NULL, 2, 61, 60, 20000.00, 2000.00, 18000.00, 'escrowed', 'wallet', NULL, NULL, 'pending', 0, NULL, NULL, '2026-05-02 13:47:40', '2026-05-02 13:47:40', NULL),
(7, 32, NULL, 61, 60, 80000.00, 8000.00, 72000.00, 'escrowed', 'wallet', NULL, NULL, 'pending', 0, NULL, NULL, '2026-05-02 13:48:47', '2026-05-02 13:48:47', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'post-job', 'web', '2026-03-18 21:53:12', '2026-03-18 21:53:12'),
(2, 'edit-job', 'web', '2026-03-18 21:53:12', '2026-03-18 21:53:12'),
(3, 'delete-job', 'web', '2026-03-18 21:53:12', '2026-03-18 21:53:12'),
(4, 'manage-applications', 'web', '2026-03-18 21:53:12', '2026-03-18 21:53:12'),
(5, 'generate-code', 'web', '2026-03-18 21:53:12', '2026-03-18 21:53:12'),
(6, 'view-escrow', 'web', '2026-03-18 21:53:12', '2026-03-18 21:53:12'),
(7, 'apply-job', 'web', '2026-03-18 21:53:12', '2026-03-18 21:53:12'),
(8, 'enter-code', 'web', '2026-03-18 21:53:12', '2026-03-18 21:53:12'),
(9, 'manage-portfolio', 'web', '2026-03-18 21:53:12', '2026-03-18 21:53:12'),
(10, 'withdraw-funds', 'web', '2026-03-18 21:53:12', '2026-03-18 21:53:12'),
(11, 'manage-users', 'web', '2026-03-18 21:53:12', '2026-03-18 21:53:12'),
(12, 'manage-disputes', 'web', '2026-03-18 21:53:12', '2026-03-18 21:53:12'),
(13, 'view-analytics', 'web', '2026-03-18 21:53:12', '2026-03-18 21:53:12'),
(14, 'manage-categories', 'web', '2026-03-18 21:53:12', '2026-03-18 21:53:12');

-- --------------------------------------------------------

--
-- Table structure for table `phone_block_attempts`
--

CREATE TABLE `phone_block_attempts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `attempted_content` text NOT NULL,
  `blocked_pattern` varchar(255) NOT NULL,
  `form_type` enum('job','application','profile','portfolio') NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `platform_settings`
--

CREATE TABLE `platform_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` longtext DEFAULT NULL,
  `type` enum('string','integer','boolean','json','float') NOT NULL DEFAULT 'string',
  `group` enum('general','payment','security','notifications','subscription','smart_match','content','maintenance') NOT NULL DEFAULT 'general',
  `description` text DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `platform_settings`
--

INSERT INTO `platform_settings` (`id`, `key`, `value`, `type`, `group`, `description`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 'platform_name', 'Winga', 'string', 'general', 'Platform display name', NULL, NULL, NULL),
(2, 'platform_url', 'https://winga.co.tz', 'string', 'general', 'Platform base URL', NULL, NULL, NULL),
(3, 'support_email', 'support@winga.co.tz', 'string', 'general', 'Support email address', NULL, NULL, NULL),
(4, 'support_phone', '+255123456789', 'string', 'general', 'Support phone number', NULL, NULL, NULL),
(5, 'maintenance_mode', 'false', 'boolean', 'general', 'Enable maintenance mode', NULL, NULL, NULL),
(6, 'maintenance_message', 'Tunafanya matengenezo. Tafadhali jaribu tena baada ya dakika chache.', 'string', 'general', 'Maintenance mode message', NULL, NULL, NULL),
(7, 'allow_registrations', 'true', 'boolean', 'general', 'Allow new user registrations', NULL, NULL, NULL),
(8, 'snippe_api_key', '', 'string', 'payment', 'Snippe API key for payments', NULL, NULL, NULL),
(9, 'platform_commission_rate', '10', 'float', 'payment', 'Platform commission percentage', NULL, NULL, NULL),
(10, 'min_withdrawal_amount', '5000', 'integer', 'payment', 'Minimum withdrawal amount in TZS', NULL, NULL, NULL),
(11, 'max_withdrawal_daily', '1000000', 'integer', 'payment', 'Maximum withdrawal amount per day in TZS', NULL, NULL, NULL),
(12, 'min_deposit_amount', '1000', 'integer', 'payment', 'Minimum deposit amount in TZS', NULL, NULL, NULL),
(13, 'auto_payout_delay_hours', '24', 'integer', 'payment', 'Auto-payout delay after code verification (hours)', NULL, NULL, NULL),
(14, 'escrow_auto_release_days', '7', 'integer', 'payment', 'Auto-release escrow after X days of inactivity', NULL, NULL, NULL),
(15, 'max_login_attempts', '5', 'integer', 'security', 'Maximum login attempts before lockout', NULL, NULL, NULL),
(16, 'session_timeout_minutes', '1440', 'integer', 'security', 'Session timeout in minutes', NULL, NULL, NULL),
(17, 'admin_ip_whitelist', '[]', 'json', 'security', 'Allowed IP addresses for admin panel (JSON array)', NULL, NULL, NULL),
(18, 'force_admin_2fa', 'false', 'boolean', 'security', 'Force 2FA for all admin users', NULL, NULL, NULL),
(19, 'phone_block_patterns', '[]', 'json', 'security', 'Phone number patterns to block (JSON array of regex)', NULL, NULL, NULL),
(20, 'email_notifications_enabled', 'true', 'boolean', 'notifications', 'Enable email notifications', NULL, NULL, NULL),
(21, 'sms_notifications_enabled', 'true', 'boolean', 'notifications', 'Enable SMS notifications', NULL, NULL, NULL),
(22, 'admin_alert_email', 'admin@winga.co.tz', 'string', 'notifications', 'Admin alert email', NULL, NULL, NULL),
(23, 'alert_on_failed_payouts', 'true', 'boolean', 'notifications', 'Send admin alert on failed payouts', NULL, NULL, NULL),
(24, 'alert_on_disputes', 'true', 'boolean', 'notifications', 'Send admin alert on new disputes', NULL, NULL, NULL),
(25, 'alert_on_suspicious_activity', 'true', 'boolean', 'notifications', 'Send admin alert on suspicious activity', NULL, NULL, NULL),
(26, 'subscriptions_enabled', 'true', 'boolean', 'subscription', 'Enable subscription system', NULL, NULL, NULL),
(27, 'msingi_price', '15000', 'integer', 'subscription', 'Msingi plan price in TZS', NULL, NULL, NULL),
(28, 'kawaida_price', '45000', 'integer', 'subscription', 'Kawaida plan price in TZS', NULL, NULL, NULL),
(29, 'bora_price', '120000', 'integer', 'subscription', 'Bora plan price in TZS', NULL, NULL, NULL),
(30, 'free_max_services', '1', 'integer', 'subscription', 'Free tier max services per month', NULL, NULL, NULL),
(31, 'free_max_daily_bids', '3', 'integer', 'subscription', 'Free tier max daily bids', NULL, NULL, NULL),
(32, 'free_max_portfolio_images', '5', 'integer', 'subscription', 'Free tier max portfolio images', NULL, NULL, NULL),
(33, 'msingi_boost_points', '10', 'integer', 'smart_match', 'Msingi plan search boost points', NULL, NULL, NULL),
(34, 'kawaida_boost_points', '25', 'integer', 'smart_match', 'Kawaida plan search boost points', NULL, NULL, NULL),
(35, 'bora_boost_points', '50', 'integer', 'smart_match', 'Bora plan search boost points', NULL, NULL, NULL),
(36, 'msingi_notification_delay', '60', 'integer', 'smart_match', 'Msingi plan notification delay (minutes)', NULL, NULL, NULL),
(37, 'kawaida_notification_delay', '15', 'integer', 'smart_match', 'Kawaida plan notification delay (minutes)', NULL, NULL, NULL),
(38, 'bora_notification_delay', '0', 'integer', 'smart_match', 'Bora plan notification delay (minutes)', NULL, NULL, NULL),
(39, 'max_matching_distance_km', '100', 'integer', 'smart_match', 'Maximum distance for job matching in km', NULL, NULL, NULL),
(40, 'job_approval_required', 'true', 'boolean', 'content', 'Require admin approval for new jobs', NULL, NULL, NULL),
(41, 'auto_approve_verified_users', 'true', 'boolean', 'content', 'Auto-approve jobs from verified users', NULL, NULL, NULL),
(42, 'block_phone_in_descriptions', 'true', 'boolean', 'content', 'Block phone numbers in job descriptions', NULL, NULL, NULL),
(43, 'block_urls_in_descriptions', 'true', 'boolean', 'content', 'Block URLs in job descriptions', NULL, NULL, NULL),
(44, 'payment.commission_rate', '11', 'string', 'general', NULL, NULL, '2026-04-21 21:47:28', '2026-04-23 17:15:53'),
(45, 'payment.min_withdrawal', '1000', 'string', 'general', NULL, NULL, '2026-04-21 21:47:28', '2026-04-21 21:47:28'),
(46, 'payment.max_withdrawal_daily', '1000000', 'string', 'general', NULL, NULL, '2026-04-21 21:47:28', '2026-04-21 21:47:28'),
(47, 'payment.min_deposit', '1000', 'string', 'general', NULL, NULL, '2026-04-21 21:47:28', '2026-04-21 21:47:28'),
(48, 'payment.auto_release_days', '7', 'string', 'general', NULL, NULL, '2026-04-21 21:47:28', '2026-04-21 21:47:28'),
(49, 'payment.payout_delay_hours', '24', 'string', 'general', NULL, NULL, '2026-04-21 21:47:28', '2026-04-21 21:47:28'),
(50, 'subscription.msingi_price', '15000', 'string', 'general', NULL, NULL, '2026-04-21 21:47:28', '2026-04-21 21:47:28'),
(51, 'subscription.kawaida_price', '45000', 'string', 'general', NULL, NULL, '2026-04-21 21:47:28', '2026-04-21 21:47:28'),
(52, 'subscription.bora_price', '120000', 'string', 'general', NULL, NULL, '2026-04-21 21:47:28', '2026-04-21 21:47:28');

-- --------------------------------------------------------

--
-- Table structure for table `portfolios`
--

CREATE TABLE `portfolios` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `project_url` varchar(255) DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `portfolios`
--

INSERT INTO `portfolios` (`id`, `user_id`, `title`, `description`, `image_path`, `project_url`, `category_id`, `is_featured`, `created_at`, `updated_at`) VALUES
(1, 64, 'Kazi yangu', NULL, 'portfolios/64/rlWDBc4QBms5uuCrU3VhnkeDKlgiKEqEL67c4CcX.jpg', NULL, NULL, 0, '2026-03-19 05:21:17', '2026-03-19 05:21:17'),
(2, 66, 'Kazi yangu', NULL, 'portfolios/66/6JWfe8vceaWnWbQkkR9EfNODHFVSkO9yQAfPnK9i.jpg', NULL, NULL, 0, '2026-03-20 08:34:04', '2026-03-20 08:34:04'),
(3, 66, 'Ongeza Kazi Mpya', 'Ongeza Kazi Mpya', 'portfolio/OgeQbGtJt1IbZ7HnGL8fEbAPm27gybcWHcllRtdY.jpg', NULL, 13, 0, '2026-03-20 08:35:37', '2026-03-20 08:35:37'),
(8, 351, 'Work 2', 'How Fermentation and Gardening Go Hand in Hand for a Healthier You  \nIntegrating Mindfulness Practices into Your Gardening Routine  ', 'portfolio/6JvFdAPlfszapdWXwJ8CbLPa10fQEnKnXTFXZpy0.jpg', NULL, 12, 0, '2026-04-21 19:47:54', '2026-04-21 19:47:54'),
(9, 60, 'ssd', 'ssd', 'portfolio/xcsxYFdyuk6RfSU1SefCH9OBjUax47tmauDf58DP.jpg', NULL, 2, 0, '2026-04-27 18:33:34', '2026-04-27 18:33:34');

-- --------------------------------------------------------

--
-- Table structure for table `profile_views`
--

CREATE TABLE `profile_views` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `worker_id` bigint(20) UNSIGNED NOT NULL,
  `viewer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `viewed_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `profile_views`
--

INSERT INTO `profile_views` (`id`, `worker_id`, `viewer_id`, `ip_address`, `viewed_at`, `created_at`, `updated_at`) VALUES
(1, 66, NULL, '127.0.0.1', '2026-03-21 23:03:35', '2026-03-21 23:03:35', '2026-03-21 23:03:35'),
(2, 60, NULL, '197.250.51.209', '2026-04-21 15:59:57', '2026-04-21 15:59:57', '2026-04-21 15:59:57'),
(3, 60, NULL, '197.186.70.143', '2026-04-21 16:41:19', '2026-04-21 16:41:19', '2026-04-21 16:41:19'),
(4, 351, NULL, '197.250.51.209', '2026-04-21 18:30:46', '2026-04-21 18:30:46', '2026-04-21 18:30:46'),
(5, 60, NULL, '197.250.51.209', '2026-04-21 19:27:06', '2026-04-21 19:27:06', '2026-04-21 19:27:06'),
(6, 60, NULL, '74.7.227.5', '2026-04-22 11:35:46', '2026-04-22 11:35:46', '2026-04-22 11:35:46'),
(7, 60, NULL, '127.0.0.1', '2026-04-27 18:32:11', '2026-04-27 18:32:11', '2026-04-27 18:32:11');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `job_id` bigint(20) UNSIGNED NOT NULL,
  `reviewer_id` bigint(20) UNSIGNED NOT NULL,
  `reviewee_id` bigint(20) UNSIGNED NOT NULL,
  `rating` tinyint(3) UNSIGNED NOT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'muajili', 'web', '2026-03-18 21:53:12', '2026-03-18 21:53:12'),
(2, 'mfanyakazi', 'web', '2026-03-18 21:53:12', '2026-03-18 21:53:12'),
(3, 'admin', 'web', '2026-03-18 21:53:12', '2026-03-18 21:53:12'),
(4, 'mteja', 'web', '2026-04-19 19:57:17', '2026-04-19 19:57:17'),
(5, 'winga', 'web', '2026-04-19 19:57:17', '2026-04-19 19:57:17');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(1, 3),
(2, 1),
(2, 3),
(3, 1),
(3, 3),
(4, 1),
(4, 3),
(5, 1),
(5, 3),
(6, 1),
(6, 3),
(7, 2),
(7, 3),
(8, 2),
(8, 3),
(9, 2),
(9, 3),
(10, 2),
(10, 3),
(11, 3),
(12, 3),
(13, 3),
(14, 3);

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `price_type` enum('fixed','hourly','negotiable') NOT NULL DEFAULT 'fixed',
  `status` enum('active','inactive','draft') NOT NULL DEFAULT 'active',
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`images`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `user_id`, `category_id`, `title`, `description`, `price`, `price_type`, `status`, `images`, `created_at`, `updated_at`) VALUES
(1, 60, 2, 'Website and application development', 'We designed\n\nWebsite and system\nAll application developemnt android and ios\nAutomatiion bot', 200000.00, 'fixed', 'active', '[\"services\\/cZ2pTdm82zoISppADW7lulQcI5I03jOpLuTF0ZyS.jpg\"]', '2026-04-20 21:59:06', '2026-04-20 21:59:06'),
(2, 60, 5, 'Graphic designed', 'we designed\n-card\n-poster\n-image\n-cover', 15000.00, 'negotiable', 'active', '[\"services\\/37DLHomgbAP6x3isrh1NAGrwy0tDfaGQ4PrVYP5l.jpg\"]', '2026-04-20 22:09:47', '2026-04-20 22:09:47'),
(3, 351, 2, 'Website', 'Pay with Secret Code\nNo payment delays. The employer gives a 6-digit code when satisfied with work. Worker enters the code — money goes directly into their wallet.', 10000.00, 'fixed', 'active', '[]', '2026-04-21 17:21:24', '2026-04-21 17:21:24'),
(4, 60, 11, 'Agricuture pro edited', 'Agricuture prom Agricuture pro Agricuture pro\n-Agricuture pro\n-Agricuture pro\n-Agricuture pro edited', 20000.00, 'fixed', 'active', '[\"services\\/fyEzfFfAUUScu9cepuS3Ra76LQGQXMRFXLqsudFJ.jpg\"]', '2026-04-21 17:28:47', '2026-05-03 04:57:09');

-- --------------------------------------------------------

--
-- Table structure for table `service_packages`
--

CREATE TABLE `service_packages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(12,2) DEFAULT NULL,
  `sort_order` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `service_packages`
--

INSERT INTO `service_packages` (`id`, `service_id`, `title`, `description`, `price`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 1, 'WEB DEVELOPMENT', 'WEB DEVELOPMENT', 200000.00, 0, '2026-04-20 21:59:06', '2026-04-20 21:59:06'),
(2, 2, 'Graphics designed', 'Graphics designed', 15000.00, 0, '2026-04-20 22:09:47', '2026-04-20 22:09:47'),
(3, 3, 'Stadard', 'Pay with Secret Code\nNo payment delays. The employer gives a 6-digit code when satisfied with work. Worker enters the code — money goes directly into their wallet.', 10000.00, 0, '2026-04-21 17:21:24', '2026-04-21 17:21:24'),
(4, 3, 'Premium', 'Pay with Secret Code\nNo payment delays. The employer gives a 6-digit code when satisfied with work. Worker enters the code — money goes directly into their wallet.', 50000.00, 1, '2026-04-21 17:21:24', '2026-04-21 17:21:24'),
(8, 4, 'Agricuture pro', 'Agricuture pro', 20000.00, 0, '2026-05-03 04:57:09', '2026-05-03 04:57:09');

-- --------------------------------------------------------

--
-- Table structure for table `service_requests`
--

CREATE TABLE `service_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) UNSIGNED NOT NULL,
  `service_package_id` bigint(20) UNSIGNED DEFAULT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `message` text DEFAULT NULL,
  `status` varchar(32) NOT NULL DEFAULT 'pending',
  `decline_reason` text DEFAULT NULL,
  `responded_at` timestamp NULL DEFAULT NULL,
  `completion_code` varchar(12) DEFAULT NULL,
  `code_generated_at` timestamp NULL DEFAULT NULL,
  `code_used_at` timestamp NULL DEFAULT NULL,
  `code_hold_until` timestamp NULL DEFAULT NULL,
  `hold_comment` text DEFAULT NULL,
  `hold_extended` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `service_requests`
--

INSERT INTO `service_requests` (`id`, `service_id`, `service_package_id`, `client_id`, `message`, `status`, `decline_reason`, `responded_at`, `completion_code`, `code_generated_at`, `code_used_at`, `code_hold_until`, `hold_comment`, `hold_extended`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 61, 'Naomba tufanye hii kazi mmna wewe', 'accepted', NULL, '2026-04-23 18:06:53', NULL, NULL, NULL, NULL, NULL, 0, '2026-04-20 22:01:28', '2026-04-23 18:06:53'),
(2, 4, NULL, 61, 'naoimba tufainye hii kazi', 'in_progress', NULL, '2026-04-21 17:33:33', NULL, NULL, NULL, NULL, NULL, 0, '2026-04-21 17:31:22', '2026-05-02 13:47:40'),
(7, 4, NULL, 61, 'gggg', 'in_progress', NULL, '2026-04-23 18:09:12', NULL, NULL, NULL, NULL, NULL, 0, '2026-04-23 18:08:23', '2026-04-23 18:17:26'),
(8, 4, NULL, 61, 'naomba hii service', 'declined', 'sitaki', '2026-05-02 13:22:16', NULL, NULL, NULL, NULL, NULL, 0, '2026-05-02 13:04:07', '2026-05-02 13:22:16'),
(9, 4, NULL, 61, 'naomba', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2026-05-02 13:47:07', '2026-05-02 13:47:07');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('bGh1tHPMVSYlvo5L8zwnCIB7JdksHIg9QWpm41tv', NULL, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidVhoRjZRZmZhOUtkNzU2b1BTZjE4MXRmSms0bW1JZDVKUFZJWWJGWiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9fQ==', 1778703380);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'string',
  `description` text DEFAULT NULL,
  `category` varchar(255) NOT NULL DEFAULT 'general',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `site_announcements`
--

CREATE TABLE `site_announcements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'info',
  `audiences` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`audiences`)),
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_dismissible` tinyint(1) NOT NULL DEFAULT 1,
  `min_view_seconds` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `cta_label` varchar(255) DEFAULT NULL,
  `cta_url` varchar(255) DEFAULT NULL,
  `starts_at` timestamp NULL DEFAULT NULL,
  `ends_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `site_announcements`
--

INSERT INTO `site_announcements` (`id`, `title`, `body`, `type`, `audiences`, `is_active`, `is_dismissible`, `min_view_seconds`, `cta_label`, `cta_url`, `starts_at`, `ends_at`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'jjjj', 'hhhh', 'warning', '[\"public\",\"mteja\",\"winga\"]', 1, 1, 0, NULL, NULL, '2026-05-13 17:08:00', '2026-05-14 17:09:00', 62, '2026-05-13 17:10:34', '2026-05-13 17:14:48');

-- --------------------------------------------------------

--
-- Table structure for table `site_announcement_user`
--

CREATE TABLE `site_announcement_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `site_announcement_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `viewed_at` timestamp NULL DEFAULT NULL,
  `dismissed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `site_announcement_user`
--

INSERT INTO `site_announcement_user` (`id`, `site_announcement_id`, `user_id`, `viewed_at`, `dismissed_at`, `created_at`, `updated_at`) VALUES
(1, 1, 60, '2026-05-13 17:15:54', '2026-05-13 17:15:54', '2026-05-13 17:15:50', '2026-05-13 17:15:54'),
(2, 1, 61, '2026-05-13 17:16:08', '2026-05-13 17:16:08', '2026-05-13 17:16:07', '2026-05-13 17:16:08');

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `skills`
--

INSERT INTO `skills` (`id`, `name`, `slug`, `category_id`, `created_at`, `updated_at`) VALUES
(1, 'PHP', 'php', 2, '2026-03-18 21:53:12', '2026-03-18 21:53:12'),
(2, 'Laravel', 'laravel', 2, '2026-03-18 21:53:12', '2026-03-18 21:53:12'),
(3, 'JavaScript', 'javascript', 2, '2026-03-18 21:53:12', '2026-03-18 21:53:12'),
(4, 'React', 'react', 2, '2026-03-18 21:53:12', '2026-03-18 21:53:12'),
(5, 'Python', 'python', 2, '2026-03-18 21:53:12', '2026-03-18 21:53:12'),
(6, 'WordPress', 'wordpress', 2, '2026-03-18 21:53:12', '2026-03-18 21:53:12'),
(7, 'Mobile App', 'mobile-app', 2, '2026-03-18 21:53:12', '2026-03-18 21:53:12'),
(8, 'UI/UX Design', 'uiux-design', 2, '2026-03-18 21:53:12', '2026-03-18 21:53:12'),
(9, 'Database', 'database', 2, '2026-03-18 21:53:12', '2026-03-18 21:53:12'),
(10, 'API Development', 'api-development', 2, '2026-03-18 21:53:12', '2026-03-18 21:53:12'),
(11, 'Photoshop', 'photoshop', 3, '2026-03-18 21:53:12', '2026-03-18 21:53:12'),
(12, 'Illustrator', 'illustrator', 3, '2026-03-18 21:53:12', '2026-03-18 21:53:12'),
(13, 'Video Editing', 'video-editing', 3, '2026-03-18 21:53:12', '2026-03-18 21:53:12'),
(14, 'Logo Design', 'logo-design', 3, '2026-03-18 21:53:12', '2026-03-18 21:53:12'),
(15, 'Animation', 'animation', 3, '2026-03-18 21:53:12', '2026-03-18 21:53:12'),
(16, 'Photography', 'photography', 3, '2026-03-18 21:53:12', '2026-03-18 21:53:12'),
(17, '3D Modeling', '3d-modeling', 3, '2026-03-18 21:53:12', '2026-03-18 21:53:12'),
(18, 'Content Writing', 'content-writing', 4, '2026-03-18 21:53:12', '2026-03-18 21:53:12'),
(19, 'Copywriting', 'copywriting', 4, '2026-03-18 21:53:12', '2026-03-18 21:53:12'),
(20, 'Kiswahili-English Translation', 'kiswahili-english-translation', 4, '2026-03-18 21:53:12', '2026-03-18 21:53:12'),
(21, 'Proofreading', 'proofreading', 4, '2026-03-18 21:53:12', '2026-03-18 21:53:12'),
(22, 'Blog Writing', 'blog-writing', 4, '2026-03-18 21:53:12', '2026-03-18 21:53:12'),
(23, 'Academic Writing', 'academic-writing', 4, '2026-03-18 21:53:12', '2026-03-18 21:53:12'),
(24, 'Uashi', 'uashi', 7, '2026-03-18 21:53:12', '2026-03-18 21:53:12'),
(25, 'Umeme', 'umeme', 7, '2026-03-18 21:53:12', '2026-03-18 21:53:12'),
(26, 'Mabomba', 'mabomba', 7, '2026-03-18 21:53:12', '2026-03-18 21:53:12'),
(27, 'Rangi', 'rangi', 7, '2026-03-18 21:53:12', '2026-03-18 21:53:12'),
(28, 'Seremala', 'seremala', 7, '2026-03-18 21:53:12', '2026-03-18 21:53:12'),
(29, 'Welding', 'welding', 7, '2026-03-18 21:53:12', '2026-03-18 21:53:12'),
(30, 'Tile Fitting', 'tile-fitting', 7, '2026-03-18 21:53:12', '2026-03-18 21:53:12'),
(31, 'Kilimo cha Mboga', 'kilimo-cha-mboga', 11, '2026-03-18 21:53:12', '2026-03-18 21:53:12'),
(32, 'Bustani', 'bustani', 11, '2026-03-18 21:53:12', '2026-03-18 21:53:12'),
(33, 'Ufugaji', 'ufugaji', 11, '2026-03-18 21:53:12', '2026-03-18 21:53:12'),
(34, 'Irrigation', 'irrigation', 11, '2026-03-18 21:53:12', '2026-03-18 21:53:12'),
(35, 'Permaculture', 'permaculture', 11, '2026-03-18 21:53:12', '2026-03-18 21:53:12');

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `subscription_plan_id` bigint(20) UNSIGNED DEFAULT NULL,
  `plan` enum('basic','pro','premium') NOT NULL DEFAULT 'basic',
  `plan_slug` varchar(255) DEFAULT NULL,
  `amount_paid` decimal(12,2) NOT NULL DEFAULT 0.00,
  `starts_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `status` enum('active','expired','cancelled') NOT NULL DEFAULT 'active',
  `payment_status` varchar(255) NOT NULL DEFAULT 'completed',
  `payment_reference` varchar(255) DEFAULT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `payment_type` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subscriptions`
--

INSERT INTO `subscriptions` (`id`, `user_id`, `subscription_plan_id`, `plan`, `plan_slug`, `amount_paid`, `starts_at`, `expires_at`, `status`, `payment_status`, `payment_reference`, `payment_method`, `payment_type`, `notes`, `created_at`, `updated_at`) VALUES
(5, 60, 20, 'basic', 'winga-karume', 15000.00, '2026-04-20 22:13:28', '2026-05-20 22:13:28', 'expired', 'completed', 'wallet-sub-60-1776734008', 'wallet', NULL, NULL, '2026-04-20 22:13:28', '2026-04-21 17:36:42'),
(7, 60, 19, 'basic', 'winga-complex', 5000.00, '2026-04-21 17:36:42', '2026-05-21 17:36:42', 'expired', 'completed', 'wallet-sub-60-1776793002', 'wallet', NULL, NULL, '2026-04-21 17:36:42', '2026-04-21 20:14:42'),
(8, 60, 20, 'basic', 'winga-karume', 15000.00, '2026-04-21 20:14:42', '2026-05-21 20:14:42', 'expired', 'completed', 'wallet-sub-60-1776802482', 'wallet', NULL, NULL, '2026-04-21 20:14:42', '2026-04-21 20:16:31'),
(9, 60, 19, 'basic', 'winga-complex', 5000.00, '2026-04-21 20:16:31', '2026-05-21 20:16:31', 'expired', 'completed', 'wallet-sub-60-1776802591', 'wallet', NULL, NULL, '2026-04-21 20:16:31', '2026-04-21 20:17:39'),
(10, 60, 20, 'basic', 'winga-karume', 15000.00, '2026-04-21 20:17:39', '2026-05-21 20:17:39', 'cancelled', 'completed', 'wallet-sub-60-1776802659', 'wallet', NULL, NULL, '2026-04-21 20:17:39', '2026-04-21 22:14:11'),
(11, 60, 20, 'basic', 'winga-karume', 15000.00, '2026-04-21 22:15:04', '2026-05-21 22:15:04', 'active', 'completed', 'admin-manual-10-1776809704', 'admin', NULL, NULL, '2026-04-21 22:15:04', '2026-04-21 22:15:04');

-- --------------------------------------------------------

--
-- Table structure for table `subscription_plans`
--

CREATE TABLE `subscription_plans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `slug` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `name_en` varchar(255) DEFAULT NULL,
  `price` int(10) UNSIGNED NOT NULL,
  `duration_days` smallint(5) UNSIGNED NOT NULL,
  `features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`features`)),
  `limits` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`limits`)),
  `badge_label` varchar(255) NOT NULL DEFAULT 'Winga Bora',
  `badge_color` varchar(255) NOT NULL DEFAULT 'amber',
  `is_recommended` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subscription_plans`
--

INSERT INTO `subscription_plans` (`id`, `slug`, `name`, `name_en`, `price`, `duration_days`, `features`, `limits`, `badge_label`, `badge_color`, `is_recommended`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(19, 'winga-complex', 'Winga Complex', 'Simple Worker', 5000, 30, '[\"Beji ya \\\"Winga\\\" kwenye wasifu\",\"Maombi 5 ya kazi kwa siku\",\"Portfolio picha 3\",\"Maoni ya kazi 10\",\"Uonekane katika utafutaji\"]', NULL, 'Winga', 'amber', 0, 1, 1, '2026-03-20 01:00:53', '2026-04-21 22:08:04'),
(20, 'winga-karume', 'Winga Karume', 'Skilled Worker', 15000, 30, '[\"Faida zote za Winga Complex\",\"Beji ya \\\"Winga Karume\\\" ya bluu\",\"Maombi 15 ya kazi kwa siku\",\"Portfolio picha 10\",\"Upaumbele katika utafutaji\",\"Uonekane kwenye winga bora\",\"Analytics ya kazi 30 siku\",\"Verified badge\"]', NULL, 'Winga Karume ⭐', 'blue', 1, 1, 2, '2026-03-20 01:00:53', '2026-04-20 21:37:33'),
(21, 'winga-kkoo', 'Winga k/koo', 'Top Rated Worker', 35000, 90, '[\"Faida zote za Winga Karume\",\"Beji ya dhahabu \\\"Winga k\\/koo\\\"\",\"Maombi zisizo na kikomo za kazi\",\"Portfolio picha zisizo na kikomo\",\"Nafasi ya kwanza kwenye utafutaji\",\"Uonekane kwenye home page carousel\",\"Analytics kamili ya kazi\",\"Verified + Top Rated badge\",\"Msaada wa kipaumbele 24\\/7\",\"Smart match priority ya haraka\",\"Custom URL ya wasifu\",\"Ishara ya muda wa majibu\"]', NULL, 'Winga k/koo 🏆', 'winga', 0, 1, 3, '2026-03-20 01:00:53', '2026-03-20 01:00:53');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `payment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type` enum('credit','debit','withdrawal','deposit') NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `description` text DEFAULT NULL,
  `balance_after` decimal(12,2) NOT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `status` enum('pending','processing','completed','failed') NOT NULL DEFAULT 'completed',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `payment_id`, `type`, `amount`, `description`, `balance_after`, `reference`, `status`, `created_at`, `updated_at`) VALUES
(3, 60, NULL, 'withdrawal', 10000.00, 'Kutoa pesa — Tigo', 5000.00, NULL, 'processing', '2026-03-18 22:16:49', '2026-03-18 22:16:49'),
(4, 61, 1, 'debit', 400000.00, 'Malipo ya kushikiliwa (Escrow): Makala 20 za Blog kwa Tovuti ya Afya (Kiswahili)', 100000.00, NULL, 'completed', '2026-03-19 05:24:54', '2026-03-19 05:24:54'),
(5, 61, 2, 'debit', 1000.00, 'Malipo ya kushikiliwa (Escrow): Kutafsiri Mkataba wa Kibiashara (Kiingereza → Kiswahili)', 99000.00, NULL, 'completed', '2026-03-21 09:38:04', '2026-03-21 09:38:04'),
(6, 60, 2, 'credit', 880.00, 'Malipo ya kazi: Kutafsiri Mkataba wa Kibiashara (Kiingereza → Kiswahili)', 5000.00, 'payout-job-23-1774096776', 'failed', '2026-03-21 09:39:36', '2026-03-29 13:33:20'),
(7, 61, 3, 'debit', 10000.00, 'Malipo ya kushikiliwa (Escrow): Graphic and designed', 89000.00, NULL, 'completed', '2026-03-24 01:36:58', '2026-03-24 01:36:58'),
(8, 60, 3, 'credit', 8800.00, 'Malipo ya kazi: Graphic and designed', 5000.00, 'payout-job-36-1774327987', 'failed', '2026-03-24 01:53:07', '2026-03-29 13:33:20'),
(9, 61, 4, 'debit', 88000.00, 'Malipo ya kushikiliwa (Escrow): Delivery ya Bidhaa za Duka — Dar es Salaam Mjini', 1000.00, NULL, 'completed', '2026-03-29 13:36:06', '2026-03-29 13:36:06'),
(10, 60, 4, 'credit', 80000.00, 'Malipo ya kazi: Delivery ya Bidhaa za Duka — Dar es Salaam Mjini', 85000.00, NULL, 'completed', '2026-03-29 13:36:28', '2026-03-29 13:36:28'),
(11, 60, NULL, 'debit', 15000.00, 'Subscription ya Winga - Winga Karume', 70000.00, 'wallet-sub-60-1776734008', 'completed', '2026-04-20 22:13:28', '2026-04-20 22:13:28'),
(12, 60, NULL, 'debit', 5000.00, 'Subscription ya Winga - Winga Complex', 65000.00, 'wallet-sub-60-1776793002', 'completed', '2026-04-21 17:36:42', '2026-04-21 17:36:42'),
(13, 60, NULL, 'withdrawal', 5000.00, 'Kutoa pesa — Airtel', 60000.00, NULL, 'processing', '2026-04-21 19:58:39', '2026-04-21 19:58:39'),
(14, 60, NULL, 'withdrawal', 5000.00, 'Kutoa pesa — Airtel', 55000.00, NULL, 'processing', '2026-04-21 20:00:51', '2026-04-21 20:00:51'),
(15, 60, NULL, 'credit', 5000.00, 'Refund: Withdrawal rejected by admin - Tuna changamoto kwa sa', 60000.00, 'admin-reject-4-1776801775', 'completed', '2026-04-21 20:02:55', '2026-04-21 20:02:55'),
(16, 60, NULL, 'credit', 5000.00, 'Refund: Withdrawal rejected by admin - Tuna changamoto kwa sasa', 65000.00, 'admin-reject-4-1776801784', 'completed', '2026-04-21 20:03:04', '2026-04-21 20:03:04'),
(17, 60, NULL, 'credit', 5000.00, 'Refund: Withdrawal rejected by admin - Tuna changamoto kwa sasa', 70000.00, 'admin-reject-4-1776801793', 'completed', '2026-04-21 20:03:13', '2026-04-21 20:03:13'),
(18, 60, NULL, 'withdrawal', 5000.00, 'Kutoa pesa — Tigo', 65000.00, NULL, 'processing', '2026-04-21 20:08:06', '2026-04-21 20:08:06'),
(19, 60, NULL, 'withdrawal', 5000.00, 'Kutoa pesa — Airtel', 60000.00, NULL, 'processing', '2026-04-21 20:08:21', '2026-04-21 20:08:21'),
(20, 60, NULL, 'credit', 5000.00, 'Refund: Withdrawal rejected by admin - kwasasa ', 65000.00, 'admin-reject-6-1776802183', 'completed', '2026-04-21 20:09:43', '2026-04-21 20:09:43'),
(21, 60, NULL, 'credit', 5000.00, 'Refund: Withdrawal rejected by admin - kwasasa ', 70000.00, 'admin-reject-6-1776802200', 'completed', '2026-04-21 20:10:00', '2026-04-21 20:10:00'),
(22, 60, NULL, 'credit', 5000.00, 'Refund: Withdrawal rejected by admin - kwasasa ', 75000.00, 'admin-reject-6-1776802213', 'completed', '2026-04-21 20:10:13', '2026-04-21 20:10:13'),
(23, 60, NULL, 'withdrawal', 5000.00, 'Kutoa pesa — Tigo', 70000.00, NULL, 'processing', '2026-04-21 20:12:24', '2026-04-21 20:12:24'),
(24, 60, NULL, 'credit', 5000.00, 'Refund: Withdrawal rejected by admin - Test', 75000.00, 'admin-reject-7-1776802372', 'completed', '2026-04-21 20:12:52', '2026-04-21 20:12:52'),
(25, 60, NULL, 'debit', 15000.00, 'Subscription ya Winga - Winga Karume', 60000.00, 'wallet-sub-60-1776802482', 'completed', '2026-04-21 20:14:42', '2026-04-21 20:14:42'),
(26, 60, NULL, 'debit', 5000.00, 'Subscription ya Winga - Winga Complex', 55000.00, 'wallet-sub-60-1776802591', 'completed', '2026-04-21 20:16:31', '2026-04-21 20:16:31'),
(27, 60, NULL, 'debit', 15000.00, 'Subscription ya Winga - Winga Karume', 40000.00, 'wallet-sub-60-1776802659', 'completed', '2026-04-21 20:17:39', '2026-04-21 20:17:39'),
(28, 61, NULL, 'deposit', 0.00, 'Muamala Imehairishwa (Payment)', 1000.00, 'SN17743311727792468', 'completed', '2026-04-23 05:47:32', '2026-04-23 05:47:32'),
(29, 61, 5, 'debit', 20000.00, 'Escrow payment (service): Agricuture pro', 980000.00, NULL, 'completed', '2026-04-23 18:17:26', '2026-04-23 18:17:26'),
(30, 60, NULL, 'withdrawal', 5000.00, 'Kutoa pesa — Vodacom', 35000.00, NULL, 'processing', '2026-04-27 18:50:09', '2026-04-27 18:50:09'),
(31, 60, NULL, 'credit', 5000.00, 'Refund: Withdrawal rejected by admin - djsjsj. jsjsklslskjdjhdnd jdjdjk', 40000.00, 'admin-reject-8-1777735405', 'completed', '2026-05-02 12:23:25', '2026-05-02 12:23:25'),
(32, 60, NULL, 'withdrawal', 10000.00, 'Kutoa pesa — Tigo', 30000.00, NULL, 'processing', '2026-05-02 12:24:33', '2026-05-02 12:24:33'),
(33, 60, NULL, 'credit', 10000.00, 'Refund: Withdrawal rejected by admin - pole', 40000.00, 'admin-reject-9-1777736144', 'completed', '2026-05-02 12:35:44', '2026-05-02 12:35:44'),
(34, 61, 6, 'debit', 20000.00, 'Escrow payment (service): Agricuture pro', 960000.00, NULL, 'completed', '2026-05-02 13:47:40', '2026-05-02 13:47:40'),
(35, 61, 7, 'debit', 80000.00, 'Malipo ya kushikiliwa (Escrow): Delivery ya Bidhaa za Duka — Dar es Salaam Mjini', 880000.00, NULL, 'completed', '2026-05-02 13:48:47', '2026-05-02 13:48:47');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `legacy_wp_user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `custom_profile_slug` varchar(30) DEFAULT NULL COMMENT 'Custom URL slug for public profile',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Verified badge for Bora subscribers',
  `suspended_at` timestamp NULL DEFAULT NULL,
  `suspended_reason` varchar(255) DEFAULT NULL,
  `avg_response_hours` double DEFAULT NULL COMMENT 'Average chat response time in hours',
  `is_top_rated` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Top Rated badge eligibility',
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'winga',
  `bio` text DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `mkoa` varchar(255) DEFAULT NULL,
  `wilaya` varchar(255) DEFAULT NULL,
  `mtaa` varchar(255) DEFAULT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `payment_number` varchar(255) DEFAULT NULL,
  `bei_aina` varchar(255) DEFAULT NULL,
  `bei_wastani` int(10) UNSIGNED DEFAULT NULL,
  `uzoefu_miaka` tinyint(3) UNSIGNED DEFAULT NULL,
  `siku_zinazopatikana` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`siku_zinazopatikana`)),
  `nida` varchar(255) DEFAULT NULL,
  `veta` varchar(255) DEFAULT NULL,
  `wallet_balance` decimal(12,2) NOT NULL DEFAULT 0.00,
  `phone_verified_at` timestamp NULL DEFAULT NULL,
  `onboarding_completed` tinyint(1) NOT NULL DEFAULT 0,
  `whatsapp` varchar(255) DEFAULT NULL,
  `phone_visible` tinyint(1) NOT NULL DEFAULT 0,
  `two_factor_secret` text DEFAULT NULL,
  `two_factor_recovery_codes` text DEFAULT NULL,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `otp` varchar(255) DEFAULT NULL,
  `otp_expires_at` timestamp NULL DEFAULT NULL,
  `otp_attempts` int(11) NOT NULL DEFAULT 0,
  `two_factor_enabled` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `legacy_wp_user_id`, `name`, `email`, `phone`, `custom_profile_slug`, `email_verified_at`, `is_verified`, `suspended_at`, `suspended_reason`, `avg_response_hours`, `is_top_rated`, `password`, `role`, `bio`, `avatar`, `location`, `latitude`, `longitude`, `mkoa`, `wilaya`, `mtaa`, `payment_method`, `payment_number`, `bei_aina`, `bei_wastani`, `uzoefu_miaka`, `siku_zinazopatikana`, `nida`, `veta`, `wallet_balance`, `phone_verified_at`, `onboarding_completed`, `whatsapp`, `phone_visible`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `remember_token`, `created_at`, `updated_at`, `otp`, `otp_expires_at`, `otp_attempts`, `two_factor_enabled`) VALUES
(60, NULL, 'Ali Juma', 'winga@gmail.com', '+255744000001', NULL, '2026-03-18 21:52:56', 1, NULL, NULL, NULL, 0, '$2y$12$rSI8l7ZQdZaOREaIrFdDF.tNzWPOI962wpW8Ho3fkqu6g65yiI7pS', 'winga', 'Mtaalamu wa teknolojia, programu ya kompyuta, na IT. Nina uzoefu wa miaka 5 katika Laravel, React Native, na Python. Nimewahi kushirikiana na makampuni kadhaa ya Tanzania na Afrika Mashariki. Napenda kazi za haraka, ubora wa juu, na mawasiliano mazuri na wateja wangu.', NULL, 'Kinondoni, Dar es Salaam', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 40000.00, NULL, 1, NULL, 0, NULL, NULL, NULL, 'HssdoW8bnjoxYopU8ES9NIMj2FAXcXN384kSSghtWM1l1K4uNvHGZncOQH5V', '2026-03-18 21:52:56', '2026-05-02 12:35:44', '$2y$12$OeZquEhZCKwn8OPKHrDxWO57BuMzM26ajYn9DDNs53wQBVKcB/Ap.', '2026-03-18 22:17:38', 0, 0),
(61, NULL, 'Fatuma Ngozi', 'mteja@gmail.com', '+255755000002', NULL, '2026-03-18 21:52:56', 1, NULL, NULL, NULL, 0, '$2y$12$C3V1PvRDM7/pnx9uC0ARZ.I/oXcXP9u.8M61BbNGlE3xYbHZ1no8u', 'mteja', 'Mfanyabiashara mwenye uzoefu wa miaka 8 katika biashara ya rejareja na huduma. Ninamiliki biashara kadhaa Dar es Salaam na Arusha. Napenda kufanya kazi na wataalamu wa ndani ya Tanzania wanaojua kazi yao vizuri.', NULL, 'Masaki, Dar es Salaam', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 880000.00, NULL, 1, NULL, 0, NULL, NULL, NULL, 'oAxaDahB5PEMKB4S3Vk1M0sNQZzgRbfWwgiobinMBjHpj0zvAifob0Af5qoS', '2026-03-18 21:52:56', '2026-05-02 13:48:47', NULL, NULL, 0, 0),
(62, NULL, 'System Admin', 'admin@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$SnlWtUPcckLoXMDDJUE6.OT4BN3M/47fpqnuUqzzZAocqBtVXn7VS', 'admin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-03-18 22:26:07', '2026-03-18 22:26:07', NULL, NULL, 0, 0),
(64, NULL, 'ERICKsky', 'ezekielsalehe00@gmail.com', '0678165524', NULL, NULL, 1, NULL, NULL, NULL, 0, '$2y$12$tjcDpny2exuBJr9R804G2uEdS3IRyuu.X559hpgEIa1lRmHcyrf2G', 'winga', NULL, 'profile-photos/fx6T4iOeSXMLdvaAQREw8I9aGyaObt4yAgj48UHF.jpg', NULL, -6.80559587, 39.22493362, 'Dar es Salaam', 'kilwa', 'kiki', NULL, NULL, 'kazi', 100000, 10, '[\"Jmt\",\"Jtt\",\"Jnn\",\"Jtn\",\"Alh\",\"Ijm\",\"Jpi\"]', '20021022331160000328', NULL, 0.00, NULL, 1, '0678165524', 0, NULL, NULL, NULL, '338ZrDoQsLDvMuXInLMnuDMavydwwctK50S1DQjONB2c8IBeBNuZD8YnWFve', '2026-03-19 05:17:07', '2026-05-05 02:31:33', '$2y$12$f1aWEdYFimjpW/Ou7oxeD.A0trxevjDHfDTBH6vCiEXvc1twrY2fG', '2026-05-05 02:41:33', 0, 0),
(65, NULL, 'Janeth', 'erickesn0002@gmail.com', '0678165532', NULL, NULL, 1, NULL, NULL, NULL, 0, '$2y$12$2q1VCKLkB4oMwOESTnIw4eGB9SfV2bl/UHqR58jeDxg2zSd006D8O', 'mteja', NULL, 'profile-photos/iBwJ8JUB0mQ1s2FLWUagFjT0LJv1GB4MWYlLJCfE.jpg', NULL, NULL, NULL, 'Mara', 'kilwa', 'kiki', 'tigopesa', '0678165524', NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, '0678165523', 0, NULL, NULL, NULL, NULL, '2026-03-19 05:22:42', '2026-03-24 00:20:26', NULL, NULL, 0, 1),
(66, NULL, 'Msume Abdalah', 'esnyarobi614@gmail.com', '0789654532', NULL, NULL, 1, NULL, NULL, NULL, 0, '$2y$12$jWKGsOAC6g78UW6sc7Tz8.RBefWYe2CEYLA0jj72u/DeSziOJCZx2', 'winga', NULL, 'profile-photos/L4DcjpqghplsETeKBpWWQuFb6lbICzzEVezZTqHR.jpg', NULL, -6.80563694, 39.22492011, 'Arusha', 'kiratu', 'kiratu', NULL, NULL, 'kazi', 10000, 10, '[\"Jtt\",\"Jnn\",\"Jtn\",\"Alh\",\"Ijm\",\"Jmt\"]', '20021022331160000328', NULL, 0.00, NULL, 1, '0789654532', 0, NULL, NULL, NULL, NULL, '2026-03-20 08:28:39', '2026-03-23 23:40:36', NULL, NULL, 0, 0),
(67, NULL, 'Veronica mahuma', 'mrsecondchance001@gmail.com', '0678543221', NULL, NULL, 1, NULL, NULL, NULL, 0, '$2y$12$n40ZKVdtJBiuL4a4ogLBk.99FY/Vm9S.rABGKtNasaFPDbwE1jY9i', 'winga', NULL, 'profile-photos/8qMhjWdTPHeax9eiGLcMwN1sZzpZnj8zmLbHlqJ3.jpg', NULL, NULL, NULL, 'Dodoma', 'dodoma', 'dodoma', 'tigopesa', '0678165524', NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, '0678165587', 0, NULL, NULL, NULL, NULL, '2026-03-20 08:44:35', '2026-03-24 00:32:55', NULL, NULL, 0, 1),
(68, NULL, 'ezekiel salehe', 'ezekiel@gmail.com', '+255786543212', NULL, NULL, 1, NULL, NULL, NULL, 0, '$2y$12$gy2cLBUgx8rsfSXDaIur7uKxKDDCckb0JLuQ.CXVfXCx2b8ayAeIS', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, '2026-03-24 00:58:28', '2026-03-24 00:58:41', NULL, NULL, 0, 1),
(69, 2, 'frank', 'frankkapinga10@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$qqU2PvgucCmvo9CUGAvsU.871Fr2FSSFOqJtumnPzbKI6QNyiKHum', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:56:58', '2026-04-19 19:56:58', NULL, NULL, 0, 1),
(70, 3, 'eliakalawa34', 'eliakalawa34@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$X.uWz5NHkLI59SJJWDm0lu1yLX9VNPz4GbmMD.Dxd.YN2Cxqn.dT.', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:17', '2026-04-19 19:57:17', NULL, NULL, 0, 1),
(71, 5, 'frankkapinga007', 'frankkapinga007@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$n8QpgWi.BdY7LThgoe3/ruKhDhhK5v3wxggeKwtzsabuZ/cCYsd4i', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:17', '2026-04-19 19:57:17', NULL, NULL, 0, 1),
(72, 6, 'John D.', 'brightonalvin03@gmail.com', '0744965693', NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$6Z6/TxFdQ0ck8z18FoGQLuuEPybQgNUKUKhf8IQt80RAILYIdxA2O', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:17', '2026-04-19 19:57:17', NULL, NULL, 0, 1),
(73, 7, 'Gloria M.', 'gloriajohnmahende@gmail.com', '255789746608', NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$23DjqB1dJCCRO2hUYobprujANveZoV.toSlUmgfJyBjU1UVXJ2dau', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:17', '2026-04-19 19:57:17', NULL, NULL, 0, 1),
(74, 8, 'christonkabeta', 'christonkabeta@gmail.com', '0753864413', NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$EAQNLF.Dha2D1YbeTHR0/.UVKRjlqPhL8xmtO3d0HGOQOUgj.fx/u', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:18', '2026-04-19 19:57:18', NULL, NULL, 0, 1),
(75, 9, 'rodgence k.', 'planetshoptanzania@gmail.com', '0769500302', NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$XtHDJXkaq4xNP7whNxkEU.1pnvjNEYVJfkEZCVhJTc/ObOBzRK5gG', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:18', '2026-04-19 19:57:18', NULL, NULL, 0, 1),
(76, 10, 'Sospetere579', 'sospetere579@gmail.com', '0756740092', NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$ay7rQgr7S/RdbXUkUbcX4eLn4JOAsQXG8eupcPXq4aXveiK5Iq6LO', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:18', '2026-04-19 19:57:18', NULL, NULL, 0, 1),
(77, 11, 'kapingacarlos', 'kapingacarlos@gmail.com', '255783866343', NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$CStGCaRPNuGE/o.BRCeEj.OHvv3fo62wf9t3vdQh0VxGIzQEvEwyC', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:18', '2026-04-19 19:57:18', NULL, NULL, 0, 1),
(78, 12, 'Barack D.', 'barackstarboy001@gmail.com', '0747041162', NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$rtd6IPnn03iku3AHGoRFueB582ErO0PkoGvD6Ds8D/u0zv9iH7TDm', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:18', '2026-04-19 19:57:18', NULL, NULL, 0, 1),
(79, 13, 'annastaziah0', 'annastaziah0@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$DzBmUIdVkVAOiYRIqd4yKuUDeYUQLf5FOOITsLGqPMN/vGqwC8HzO', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:19', '2026-04-19 19:57:19', NULL, NULL, 0, 1),
(80, 14, 'mlawalisa911', 'mlawalisa911@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$x3F7iU54Uh.mRPSzjkLfO.2g8/5vUZ52kYi55zdIrezP3Id/jnSmK', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:19', '2026-04-19 19:57:19', NULL, NULL, 0, 1),
(81, 15, 'mbisedickson52', 'mbisedickson52@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$tS7tfuRoICwD8psoYqTbSuB6psTgqrI.D4NdEAAuO5IaTKU9F/h7u', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:19', '2026-04-19 19:57:19', NULL, NULL, 0, 1),
(82, 16, 'sensera07012005', 'sensera07012005@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$J7oZ.n719XmlV18OPhOC5.Cc/9bEkdA8sjQc4hlwY6Hy.HAF5iGuC', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:19', '2026-04-19 19:57:19', NULL, NULL, 0, 1),
(83, 17, 'Haidari M.', 'haidarimtipa25@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$OU33mmQo0tRYa9qnqj.He.b4zUtZt7qFoUDZPI2rXkP.zwmb7T9M.', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:19', '2026-04-19 19:57:19', NULL, NULL, 0, 1),
(84, 18, 'josephlucy323', 'josephlucy323@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$Iw8Z8YCnZ5uJNyjNbB/mf.sHgTpMS8Rn22cBQe8BNvS/EOLSRql4q', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:19', '2026-04-19 19:57:19', NULL, NULL, 0, 1),
(85, 19, 'mwachalirichard', 'mwachalirichard@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$PuM4bFxbyMZ3oteQCWkEXeOhso6lK4dHROj.9oJJQ9vcFEWADhdj6', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:20', '2026-04-19 19:57:20', NULL, NULL, 0, 1),
(86, 20, 'Danford', 'danfordmakota@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$SY8rvVGCnsnxepIa6a9Fu.CZUgRavqP0MDFSA7MwWSGNTj7ew/eyC', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:20', '2026-04-19 19:57:20', NULL, NULL, 0, 1),
(87, 21, 'Kelvin J.', 'rutabanzibwa619@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$4tz7tWNFHSEE3ChTR6cMMO/9VmPRBXwB.FJLEbT9lQtr0WSECwodS', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:20', '2026-04-19 19:57:20', NULL, NULL, 0, 1),
(88, 22, 'David R.', 'rutalombadavid@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$VnyAuYU/0theiFGyWAhJfePaKttrwfUd2g1JVaLg2mQvCh3idRJGO', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:20', '2026-04-19 19:57:20', NULL, NULL, 0, 1),
(89, 23, 'Chriss N.', 'chrstphrnyoni@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$v6jT7RVg8rW0BueH/hzexeNH7ggihVcqeFmmi.yzVQSlfBPJo52Du', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:20', '2026-04-19 19:57:20', NULL, NULL, 0, 1),
(90, 24, 'djprandoprando45', 'djprandoprando45@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$GaTPXQ4cib8RNjVlDrqwyuQqOu4rnvWPorktJECeHopM5LvNqPYp6', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:21', '2026-04-19 19:57:21', NULL, NULL, 0, 1),
(91, 25, 'iddysultan', 'iddysultan@icloud.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$BuCtnac9eR44SdeLeFG6RO9aejT.gcxRIPt.c/cHUxkzsaNty81RK', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:21', '2026-04-19 19:57:21', NULL, NULL, 0, 1),
(92, 26, 'Alex M.', 'alexmpimbwe@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$JoYfr1/AVl7nKCdgRVlg7.Ktozo5EuICpCxH1YtyVkLmMCSjgIlWK', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:21', '2026-04-19 19:57:21', NULL, NULL, 0, 1),
(93, 27, 'mathayobayaga', 'mathayobayaga@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$64Pv4KPuVEmSBpn7.OCPlunw04XbCQTKqk0vl6A8UJK.t.RkB41KW', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:21', '2026-04-19 19:57:21', NULL, NULL, 0, 1),
(94, 28, 'Mctrevo O.', 'kramadhani836@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$6.w0OAriTFctNiud9.QqV.kUy9rXHgcpnghGN68chWbJI2BAoyVnK', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:21', '2026-04-19 19:57:21', NULL, NULL, 0, 1),
(95, 29, 'Sabas M.', 'sabasmakata@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$l4Deu8N9dv7xN7j3m3qe2OnCfvFxyFGScIx8QBRjjLNtFH52WvLai', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:22', '2026-04-19 19:57:22', NULL, NULL, 0, 1),
(96, 30, 'Digitic B.', 'psboniphace@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$AKILW3JkVh/pPsNcIE7P6ezy5tY0xOLlx75ICev.2C/8GvV2MDV8W', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:22', '2026-04-19 19:57:22', NULL, NULL, 0, 1),
(97, 31, 'Hilda Viggo R.', 'hilda.viggo@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$6Sab685YqojICbtPOrMgmeV2HQYYWBnk4yBKTXraLA1XcZCHl2jw6', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:22', '2026-04-19 19:57:22', NULL, NULL, 0, 1),
(98, 32, 'aloycejeremiah6', 'aloycejeremiah6@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$O1RFMWuGWFzIq7s3.2.3OeLOKoY1Dfywkpk4N0wgRB9.lTLZFxz6y', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:22', '2026-04-19 19:57:22', NULL, NULL, 0, 1),
(99, 33, 'Kelvin K.', 'willkalvin1@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$3Li7X6.Hc4AGXxA3T8TFNOVjyXHnN7lbhKh5s4WRqZRnR79XcvoPa', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:22', '2026-04-19 19:57:22', NULL, NULL, 0, 1),
(100, 34, 'Michael R.', 'mvmraphael@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$l0pq6dZWNBY.b/OgN/tZhus5XuLC2D0h91uiHd7fogKEWAGzd7WES', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:23', '2026-04-19 19:57:23', NULL, NULL, 0, 1),
(101, 35, 'waydamarcel', 'waydamarcel@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$LkpNLPFNKk0oWRfL0wMbme9TKyfUhlyRLeVSVellC2DjKi/lQx/Xi', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:23', '2026-04-19 19:57:23', NULL, NULL, 0, 1),
(102, 36, 'fahmyjosee', 'fahmyjosee@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$wJSvLZtgdXTJJ20bUOMkcemhvPbJHa/49s8dC73vJOZbmPlzG.Nyu', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:23', '2026-04-19 19:57:23', NULL, NULL, 0, 1),
(103, 37, 'Steve A.', 'aveirosteve51@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$yCFj0CamC30SnyD4.Yx8c.y3/J3rh1h172oW36tRxMCcXCxOJGyMK', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:23', '2026-04-19 19:57:23', NULL, NULL, 0, 1),
(104, 38, 'kain b.', 'kainboaz@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$.9JZBvrn0zk4W56OHExouu55AQXH2Fq5XhK4Scyo0EyXN3TLv9ZhS', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:23', '2026-04-19 19:57:23', NULL, NULL, 0, 1),
(105, 39, 'stamili w.', 'stamiliwilson544@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$tbSztdInUQYl25isoGXDJetIwpu2M294QdfX8Ck1WsgUIS20q9H7u', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:24', '2026-04-19 19:57:24', NULL, NULL, 0, 1),
(106, 40, 'chrispinpius5', 'chrispinpius5@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$hOorH.TLQFx.kpK6jRMRbO81NrUBAq4fU1a8HO1B1qbr6OdETxa6C', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:24', '2026-04-19 19:57:24', NULL, NULL, 0, 1),
(107, 41, 'Cnda', 'sylvestersinda@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$3aIbGNwFXGu12wY0xjDMxeGZYS3efXqEzleimeSt9a0ePgSCo.7C6', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:24', '2026-04-19 19:57:24', NULL, NULL, 0, 1),
(108, 42, 'rajabsembe791', 'rajabsembe791@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$2NQLuzM2GTK3Sx7PgFJ2dun4yjWVpDTaaoC3wtZhmNtLDVpTGy4Pa', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:24', '2026-04-19 19:57:24', NULL, NULL, 0, 1),
(109, 43, 'mustaphaomary568', 'mustaphaomary568@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$ezFzzVCOpzYlzgldke2OV.YC2oLYZHV1otV8/BiYEvsaNJs0MADvW', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:24', '2026-04-19 19:57:24', NULL, NULL, 0, 1),
(110, 44, 'Japhary M.', 'japharykassim5@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$LLRpGQlgmoM6fFUwcCeV/ufLGeuPe5bVoZeaWCKM4PYZ7G02tohv2', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:25', '2026-04-19 19:57:25', NULL, NULL, 0, 1),
(111, 45, 'Hadijajuma728', 'hadijajuma728@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$RI6Cub31Jb8T5EyyB5gfBOqLlxpUYA4dRZjM/sIV/wSM.8GiCYg2.', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:25', '2026-04-19 19:57:25', NULL, NULL, 0, 1),
(112, 46, 'Kilimanjarodort', 'kilimanjarodort@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$WYPTkap9Ade2llPvNGwgJOYTMnAgk4Gcib3ur9ZcQBxI5CeCbQRtq', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:25', '2026-04-19 19:57:25', NULL, NULL, 0, 1),
(113, 47, 'brilliytgal1', 'brilliytgal1@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$Lka9PQ5Mf82EFPYTSYS0teTjR4C8AAB.uCFw3m8vpmjnYvAVz0AHK', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:25', '2026-04-19 19:57:25', NULL, NULL, 0, 1),
(114, 48, 'mercykalile02', 'mercykalile02@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$62mOtc2FXB2dz5/c3tcu1eCmB/Aln7NWKVRdlKTjbpmlnnWkBTlxG', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:25', '2026-04-19 19:57:25', NULL, NULL, 0, 1),
(115, 49, 'kiwia31', 'kiwia31@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$bnz3LHMObvPoeQ09LmoN4OKrpfuVvGvjUTFHqMjE/ndhJsObsrS.y', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:26', '2026-04-19 19:57:26', NULL, NULL, 0, 1),
(116, 50, 'martin N.', 'martinndunguru77@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$q.LgoB7h/2RPdeAnQHePdOv8hXlo7ByfVFt3rVktY7UI5HiHOFcdu', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:26', '2026-04-19 19:57:26', NULL, NULL, 0, 1),
(117, 51, 'saraphnaadamson', 'saraphnaadamson@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$7UIdX7ob1wgZDA/qiNoyvObnfyinK0cgVL2iF61LtbyWz/LZ/jBDK', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:26', '2026-04-19 19:57:26', NULL, NULL, 0, 1),
(118, 52, 'JOSHUA E.', 'joshuankuyumba98@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$2DVt2yCKLEldcRRPklQu/uIkT6OrRCD3OB9zQb/.8XLVMKo3eQNuy', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:26', '2026-04-19 19:57:26', NULL, NULL, 0, 1),
(119, 53, 'watusmartenterprisesltd', 'watusmartenterprisesltd@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$q.NvwMAKPL2MUZ5JZIEClOCB2jD/TpHWLd71S2OZVvcoLMb2Miake', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:26', '2026-04-19 19:57:26', NULL, NULL, 0, 1),
(120, 54, 'Young C.', 'allymbingu001@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$fphcmCIDivDLZExgvyNlmObczHcsPqZuEuNM8fi6c3IR831D.zWr6', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:27', '2026-04-19 19:57:27', NULL, NULL, 0, 1),
(121, 55, 'Erick George N.', 'erickngassa169@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$K0C34/AUXZCp5Uq6psB/XelBaE4j6rbYvcwzuRET.HgnOk75mG8Gi', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:27', '2026-04-19 19:57:27', NULL, NULL, 0, 1),
(122, 56, 'Lisabela K.', 'lisakomba1@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$Th/LUyZD4Q/nOQGt40BsEuM9KtQq4tZftxXWkbHRFvd4Ins8QuGTW', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:27', '2026-04-19 19:57:27', NULL, NULL, 0, 1),
(123, 57, 'CHIZA M.', 'chizamganda@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$wKdobz1fHhrXbV44AFvl0OyI2F.3QosG0BD5EojuiG0ETXF/kP8UK', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:27', '2026-04-19 19:57:27', NULL, NULL, 0, 1),
(124, 58, 'cmpeni', 'cmpeni@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$y2/2y8k7llTnzApwIg2OG.vObqVNhIQmmoWPiCRtuSx1KO8VHNWvG', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:27', '2026-04-19 19:57:27', NULL, NULL, 0, 1),
(125, 59, 'Ezekiel W.', 'nachaeze@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$ke1tVCQInwGAhnlugptMZe15VaSrO9aOMt3.JENlRn03KprECG9Oe', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:28', '2026-04-19 19:57:28', NULL, NULL, 0, 1),
(126, 60, 'KAMARU I.', 'kamaruisaka6252@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$Z.4TDATLUcGjhyLnLQKya.AHced411J/q8.rxSS5AC/e.ewp7Ofqu', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:28', '2026-04-19 19:57:28', NULL, NULL, 0, 1),
(127, 61, 'DENIS R.', 'denistemistokless591@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$Q95Z5rTIdFdJblnJoFrdheKmJXvf/JXGgfsgx/LbVzp/23ZJLp5n6', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:28', '2026-04-19 19:57:28', NULL, NULL, 0, 1),
(128, 62, 'Hamida A.', 'minahadam23@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$wMPnTyS5cNXBrIDMAAKtFeUB5YTqumsiGJ1cFqa2FjzqQPJO1YZ0m', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:28', '2026-04-19 19:57:28', NULL, NULL, 0, 1),
(129, 63, 'Dee F.', 'eridericgeorge1@gmail.com', '0748281701', NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$g2JXfDC2SifFLDhC5wSZDew.W6JWaWU5ER6CGMSL5ed5iJ1CPgIgq', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:28', '2026-04-19 19:57:28', NULL, NULL, 0, 1),
(130, 64, 'jurvisdanford329', 'jurvisdanford329@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$udkBFOtPsphxkSbrT79cP.42k3KA8ESrhAWFh3u03cdaWTKRABH3y', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:28', '2026-04-19 19:57:28', NULL, NULL, 0, 1),
(131, 65, 'Glory', 'naisulalaizer301@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$p22jGKntDex5.vwMVaq/zeEKuUJt1I2Imbope2Dq7YeGPhGGoo9zu', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:29', '2026-04-19 19:57:29', NULL, NULL, 0, 1),
(132, 66, 'Elia M.', 'eliaabel255@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$fyU2iJeuLos43PGrcbSqz.ngSTdddQcLb6JfspSM70Vx5u7ZRJnLq', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:29', '2026-04-19 19:57:29', NULL, NULL, 0, 1),
(133, 67, 'Bonventula M.', 'bonmgalla@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$YrJMJxQDXxsGKApd..enCusTXcWKkZcVUqVHKRWPmE5vI92JEw7Uq', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:29', '2026-04-19 19:57:29', NULL, NULL, 0, 1),
(134, 68, 'Nailah W.', 'wazirnailah@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$HmNLnXLDZWvDEYXbm2.BJOvr8/XRTxdyXkC21d/hgZCuZmddGZoli', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:29', '2026-04-19 19:57:29', NULL, NULL, 0, 1),
(135, 69, 'Elina', 'elinamart35@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$Hb7/eKDsaFf6yoJGuJBHMeFRcC0c9r9OTw3kZMb/ecb6hgKrkXswy', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:29', '2026-04-19 19:57:29', NULL, NULL, 0, 1),
(136, 70, 'thethinktank47', 'thethinktank47@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$K/6zesN7XhivtpF80Qi1d.MrvgfNBMxnrJAlaj3sVOk4DtvQjfPgK', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:30', '2026-04-19 19:57:30', NULL, NULL, 0, 1),
(137, 71, 'Patro M.', 'patromike62@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$6K.rTtfS0vyJXz3TrygVOuAIEQr8FxbXspMaCSH8.LDSN81IsN6i2', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:30', '2026-04-19 19:57:30', NULL, NULL, 0, 1),
(138, 72, 'Elisante S.', 'mr.elisante@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$DND0XjYzjAh0wTZZtntYreAJyla2d9CWf/ZPRA75ND2AEXFY4TW7K', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:30', '2026-04-19 19:57:30', NULL, NULL, 0, 1),
(139, 73, 'Adam S.', 'irfanhytham@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$st8xHQ/BYqUhriQWYYCd/efXdxkrmAa3zh7muyPt1PDcVQzbzDR0.', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:30', '2026-04-19 19:57:30', NULL, NULL, 0, 1),
(140, 74, 'Saidan A.', 'cydan1800@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$kYvyPakVLjJBdVrg0S8aIe2lDQAV.FCYqgy/Sr3tEXz9srTnr0aju', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:30', '2026-04-19 19:57:30', NULL, NULL, 0, 1),
(141, 75, 'Editha K.', 'edithakalonga@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$CtDPyFGRVqyB7/YnJz2O7ee2ZblFmc/61LVt9GGAsY8DnwC9jSioy', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:31', '2026-04-19 19:57:31', NULL, NULL, 0, 1),
(142, 76, 'ally omary m.', 'allyomarymtawazo@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$i.iDY304r38HQbxzVm9TVOGqr11vsOFckmk0cuju4SELApSZmZGFe', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:31', '2026-04-19 19:57:31', NULL, NULL, 0, 1),
(143, 77, 'Msafiri', 'masomemsafiri@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$hQb2gyj0mTfmZq804Cp.jO6JBwBn5iksSmLa/od4I.n6G/6DB9pP.', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:31', '2026-04-19 19:57:31', NULL, NULL, 0, 1),
(144, 78, 'Augustino M.', 'augustinomhanga@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$IVRV5/kpJ0/XaDd0rXxGQOtaYrrUXbqN5SLCuTt9HLOqGFI2FKPcG', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:31', '2026-04-19 19:57:31', NULL, NULL, 0, 1),
(145, 79, 'Marius M.', 'mutalemwamarius@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$MegFAMVAGkkoQYbGAYpZjepXr1NUhOYTcJQlXAYdWl2ieddt6.M1S', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:31', '2026-04-19 19:57:31', NULL, NULL, 0, 1),
(146, 80, 'Jackson M.', 'jizzymboya@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$m7NIhyw1sOfKEAqKUBl2rOw68yGQDG07SEO7cubbR/IKL219cKKPS', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:32', '2026-04-19 19:57:32', NULL, NULL, 0, 1),
(147, 81, 'Jackson T.', 'tujijackson@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$KnXXJ2ORIoGp6QbGqctOAu7WV.IEhuGjHQUIWkZgloYh5OstJXQxK', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:32', '2026-04-19 19:57:32', NULL, NULL, 0, 1),
(148, 82, 'SIKJUNIOR M.', 'meetsik24@gmail.com', '255788344348', NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$VaTBkcT0hH6oLNYgKmsjeu8H2J5RQ5V2LpqB34WluV6Efnf.EHtRC', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:32', '2026-04-19 19:57:32', NULL, NULL, 0, 1),
(149, 83, 'LUQMAN S.', 'suffianluqman01@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$WddB4yrxMQM5IuM9L4Z14eh9p4pZKTHsrRUxhkdOLTEDKH8y4Pqxm', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:32', '2026-04-19 19:57:32', NULL, NULL, 0, 1),
(150, 84, 'Calvin M.', 'calvinmwaipopo@hotmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$5fUGSEbMP1orxMSbizcSpuS0Izgg0vGOResvakVYKQqhBtMx1CuZW', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:32', '2026-04-19 19:57:32', NULL, NULL, 0, 1),
(151, 85, 'Julian N.', 'nyingejulian2@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$lXGbTjSjVWLPwiAYzj9Nz.kZdDoySyJqmR5AiZ0a/enInkH22Td4G', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:33', '2026-04-19 19:57:33', NULL, NULL, 0, 1),
(152, 86, 'benezethsinkamba008', 'benezethsinkamba008@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$RfQr1h69PnsdwT6ZVPOPh.myL/Bxdwy./0F8TQGUSxZcihTkUXkHu', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:33', '2026-04-19 19:57:33', NULL, NULL, 0, 1),
(153, 87, 'Latifazuberiomary', 'latifazuberiomary@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$k1dsGbRIhWXEGV1j8ds5b.HvlFxRetA8dtHMf/.eo5Up55/aVmQSe', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:33', '2026-04-19 19:57:33', NULL, NULL, 0, 1),
(154, 88, 'Jackie', 'bernardjackline3@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$eRJGrWA/CE0I/wEZaH8uWe0K78vsumklH2/yEH5hN10V1dQK1gr7y', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:33', '2026-04-19 19:57:33', NULL, NULL, 0, 1),
(155, 89, 'Sadick O.', 'so8852160@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$Gnp09L.1bwpNjY0gKL1rQ.YSvfYGzz8mjwypCfYSblH2uJeEJD2W2', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:33', '2026-04-19 19:57:33', NULL, NULL, 0, 1),
(156, 90, 'Angel M.', 'angelmlokozi1@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$DKtefsi4RJe6Ubma/KdhT.Mckj765BCsTuOJDhVcsgpSoFj4uTeJq', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:34', '2026-04-19 19:57:34', NULL, NULL, 0, 1),
(157, 91, 'Maria M.', 'enkuninga@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$cC2uLsWWz71mtZLlzDlnEeS2zf2DN4UqP5DHnpwHB00LY.akVoXrK', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:34', '2026-04-19 19:57:34', NULL, NULL, 0, 1),
(158, 92, 'Emmy M.', 'emmymkenda56@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$j5PVpG5i1kh8T/05vONKaODsWrHre.PHFW7KrMpHXL7N6Mtj5kXMe', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:34', '2026-04-19 19:57:34', NULL, NULL, 0, 1),
(159, 93, 'Steven E.', 'sesteph14@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$CPcxL3xK/ksfBMXAmABLUu7rMbfu2.SL2JINxiggCjqe9X4D4Osyq', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:34', '2026-04-19 19:57:34', NULL, NULL, 0, 1),
(160, 94, 'Hamisi S.', 'hamisishehe@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$3N/08XAfMcD/3AjA0u0cVuB.Tep/6vHNLnygCsATDT7dSt8JQF.Ay', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:34', '2026-04-19 19:57:34', NULL, NULL, 0, 1),
(161, 95, 'Nyama ya Nguruwe N.', 'nyamayanguruwe@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$1pGGf4PL4zodoo3YfShG6eqhDD8eZlMol3.RnbWqaDpaeKnO4Ym9u', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:35', '2026-04-19 19:57:35', NULL, NULL, 0, 1),
(162, 96, 'onolinajoseph999', 'onolinajoseph999@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$9F4GUNcqvyU.fPya.va1CuEEzdXMsx0utVugzY4qq9gbNgkZ.j.0C', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:35', '2026-04-19 19:57:35', NULL, NULL, 0, 1),
(163, 97, 'dullahnabbir', 'dullahnabbir@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$sw6UGMsGLgorWujOqbl3ZeMdLFNdGw8koSrKfTWcUMUvHHeT7TLhC', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:35', '2026-04-19 19:57:35', NULL, NULL, 0, 1),
(164, 98, 'Mary', 'amanaally65@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$zdr8/rzFBliUNrK/fJRO7eOzdCej9N8DkGYOzxyELviB8aMBd0VKi', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:35', '2026-04-19 19:57:35', NULL, NULL, 0, 1),
(165, 99, 'hissa7115', 'hissa7115@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$8DbV.nJlpueNvLI9G8/o7e2IA1kD0jRUGEOybA51dLI3Uk1X5CMRe', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:35', '2026-04-19 19:57:35', NULL, NULL, 0, 1),
(166, 100, 'lucykiria06', 'lucykiria06@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$8j4XQKs0xDQt6l6qTPy8EuFiYVugGurturwI5N0XaRg/KapZPcKne', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:36', '2026-04-19 19:57:36', NULL, NULL, 0, 1),
(167, 101, 'Lucky M.', 'munnisilucky@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$i47gDcOhkisB2e63EeaCg.ntKbjUcCD9GZCEq3vTUbr2/709tuWHq', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:36', '2026-04-19 19:57:36', NULL, NULL, 0, 1),
(168, 102, 'zainabmnandi1', 'zainabmnandi1@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$b6BVWN.2zS471vE0Xvrh6uJnLaNiC6vP6sds7bTvLDUKsOZb6hxJO', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:36', '2026-04-19 19:57:36', NULL, NULL, 0, 1),
(169, 103, 'mussalushiku7', 'mussalushiku7@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$ZbjVWN27KsjxnOqQ04OGBuVjtUkz/43WVoDQCYMjkKjmPmxUXjUke', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:36', '2026-04-19 19:57:36', NULL, NULL, 0, 1),
(170, 104, 'INNOCENT M.', 'imwalwama@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$FPP8pCVmiSCCdvP0YnUYuePrSfZsj66MQUL.wj1Uduo.jMmAWgFOe', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:36', '2026-04-19 19:57:36', NULL, NULL, 0, 1),
(171, 105, 'Twinamukama P.', 'ptwinamukama@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$30.2NwnyxQSaYdauMZlsXOTOsyddmOb/LRgIlPsjVI16EAiEgga06', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:36', '2026-04-19 19:57:36', NULL, NULL, 0, 1),
(172, 106, 'machanoamina844', 'machanoamina844@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$Q1OhpaXNINQPSB1Oxls1PuAFlvzObuCwpTLbhABFvw5NPz3kfG.li', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:37', '2026-04-19 19:57:37', NULL, NULL, 0, 1),
(173, 107, 'Cathy M.', 'cathymganah@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$hfcvssHaxI2hbm3mAYcANOJEN8TA7Rn4R9qo5jc2etq1ehpMZwlVW', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:37', '2026-04-19 19:57:37', NULL, NULL, 0, 1),
(174, 108, 'Mariam M.', 'magegemamuuh@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$uW0l8IvED21ZMd603OAP7OVK6ye6uAfFX16iX62OCLCfdX46wCYk.', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:37', '2026-04-19 19:57:37', NULL, NULL, 0, 1),
(175, 109, 'ALISON K.', 'godfreykulwamagen@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$VoLX4E9gBd/pF9dAt/vqjOfCokz5zvLXtMlUuPhZN4vK0eKC.2Ex.', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:37', '2026-04-19 19:57:37', NULL, NULL, 0, 1),
(176, 110, 'ssalha861', 'ssalha861@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$6FHUqSEd3WFW3Qg9d6ryXumqsZ0hNjncwP7/teVNKgjDLC6UdhLR6', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:37', '2026-04-19 19:57:37', NULL, NULL, 0, 1),
(177, 111, 'donatellah b.', 'donatellahbrown@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$dLkb9sIqmDKBPVLKXsURP.kg.rL5pqjjf82Yr3YC8TmePQ5h87noy', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:38', '2026-04-19 19:57:38', NULL, NULL, 0, 1),
(178, 112, 'Elizabeth M.', 'mwaijibeelizabeth@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$w7auFlX3HEQPVhxvRv8Pe.cFO5FFI.cTbbCOxLicfel8R4gbLpvK2', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:38', '2026-04-19 19:57:38', NULL, NULL, 0, 1),
(179, 113, 'Happy G.', 'gibsonhappy13@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$L1qmnZ60iSUedlCJ4.g/g.l7RmJONIBIERh5uQ573170ucajsUvU6', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:38', '2026-04-19 19:57:38', NULL, NULL, 0, 1),
(180, 114, 'Athuman D.', 'othumandawa@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$m5g4DKK4lQXZph3JnbIWKuydUHdGs1HX4nT6vz65AUKKHlx9v48fO', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:38', '2026-04-19 19:57:38', NULL, NULL, 0, 1),
(181, 115, 'Erica M.', 'ericambura24@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$HdRCTVSPFh74fhVFgRvw5ee8kb4u7iRdregqI6CKIgAJqUwJ2Diji', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:38', '2026-04-19 19:57:38', NULL, NULL, 0, 1),
(182, 116, 'venisiazephania', 'venisiazephania@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$GwWMLDRtf2bEfg723sNu8OR00wD2f5D3x8Kn3zlYYexHbkepqQnrq', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:39', '2026-04-19 19:57:39', NULL, NULL, 0, 1),
(183, 117, 'Amon A.', 'amonmwasyoge@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$VEtTCMHml2x9n2iKK.UcgO1/x7.bTjFDqxK92DlQvEqHAhZJvb6cq', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:39', '2026-04-19 19:57:39', NULL, NULL, 0, 1),
(184, 118, 'mangulah133', 'mangulah133@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$36iMusPGvfRSmeH9J8mbL.v5/V8oqVHUhrKDlYFrMg3/Y4XzVgnwy', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:39', '2026-04-19 19:57:39', NULL, NULL, 0, 1),
(185, 119, 'nyombiphyn96', 'nyombiphyn96@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$P3m8NGeytY6SBKrwHL//IOhuzsnT/u1ohodpKTjGIqvKAdmgizs5G', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:39', '2026-04-19 19:57:39', NULL, NULL, 0, 1),
(186, 120, 'Alfred B.', 'alfredbaraka287@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$hShybCY5z7Zxyw/.umdTDeiYBG29/gUd0n9QNQ3HjUwex2ZOoQjum', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:39', '2026-04-19 19:57:39', NULL, NULL, 0, 1),
(187, 121, 'adelphinashayo9', 'adelphinashayo9@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$EkdwPGiaqL4PneACGktaE.AXmQY9Uk90B9AfuSzNxqmMLdglEQJmy', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:40', '2026-04-19 19:57:40', NULL, NULL, 0, 1);
INSERT INTO `users` (`id`, `legacy_wp_user_id`, `name`, `email`, `phone`, `custom_profile_slug`, `email_verified_at`, `is_verified`, `suspended_at`, `suspended_reason`, `avg_response_hours`, `is_top_rated`, `password`, `role`, `bio`, `avatar`, `location`, `latitude`, `longitude`, `mkoa`, `wilaya`, `mtaa`, `payment_method`, `payment_number`, `bei_aina`, `bei_wastani`, `uzoefu_miaka`, `siku_zinazopatikana`, `nida`, `veta`, `wallet_balance`, `phone_verified_at`, `onboarding_completed`, `whatsapp`, `phone_visible`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `remember_token`, `created_at`, `updated_at`, `otp`, `otp_expires_at`, `otp_attempts`, `two_factor_enabled`) VALUES
(188, 122, 'boazipeter90', 'boazipeter90@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$D2y1ypr8.oK4DbfxxZQCk.4uSiLnfvz4NG4vhwJsLkE3a.dc0MY9K', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:40', '2026-04-19 19:57:40', NULL, NULL, 0, 1),
(189, 123, 'eddureign', 'eddureign@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$tdxw3p.UAzovc0p0gG3Q8uRJlAJY4OgmXzm.CEnyDTrno3QtpKcaS', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:40', '2026-04-19 19:57:40', NULL, NULL, 0, 1),
(190, 124, 'joycesaisai2', 'joycesaisai2@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$PSOGvTn1qTaddmPZAKAlruTYZ1qlhFxjrR3M2qb1R0XOZbD5QRjea', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:40', '2026-04-19 19:57:40', NULL, NULL, 0, 1),
(191, 125, 'cosmasgoima42', 'cosmasgoima42@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$fqWRjdCnCB8uX66oOug9aOoV3AnyjkxcUcAKJbmPvukhQKIp5.xda', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:40', '2026-04-19 19:57:40', NULL, NULL, 0, 1),
(192, 126, 'Mihayo D.', 'mihayod189@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$/YKNUh7LS.siAq7sqTd76eRkIHDia5YA4gY8OIomDCmC8ORAsfHua', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:41', '2026-04-19 19:57:41', NULL, NULL, 0, 1),
(193, 127, 'mwasomolategemea', 'mwasomolategemea@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$m2nDKq24G2FZqLqixqWmhu3Gigs6.qrTg7RnZfqh4xbXfqHxnQWWW', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:41', '2026-04-19 19:57:41', NULL, NULL, 0, 1),
(194, 128, 'Nagma J.', 'nagmajuma12@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$uNpIB0tX7Iso7IGYqLSQp.NObpdpNh1KO1HZkatemcfiG2SdCdXBG', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:41', '2026-04-19 19:57:41', NULL, NULL, 0, 1),
(195, 129, 'Ambrose M.', 'mindeambrose@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$U7gnePtcscn1QPtwxETUReugKNv4.hsD5FkxySisDAMjQ3BbR0Nfe', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:41', '2026-04-19 19:57:41', NULL, NULL, 0, 1),
(196, 130, 'Frank M.', 'frankmbaga490@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$kYSMvCS88wV1N3IDNQGbqOpsj9pmQTks.FhJDmnMNtNvLos8lw0C.', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:41', '2026-04-19 19:57:41', NULL, NULL, 0, 1),
(197, 131, 'lilianmuro3', 'lilianmuro3@gmail.com', '0757647519', NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$K5g19U1nT/OxptSCbt5RXe6wWH8N74koPQaLPWK/JSZ.y3YRmksXu', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:42', '2026-04-19 19:57:42', NULL, NULL, 0, 1),
(198, 132, 'Amanda K.', 'amandakessy73@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$/m/MFkwCIGXHK89gbnQEcusY5Sds02phAYyYNPt7CrtmqVxEUTm7y', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:42', '2026-04-19 19:57:42', NULL, NULL, 0, 1),
(199, 133, 'Lillany C.', 'charleslillan25@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$naJMxT8K.4GHdAOJE4r9x.rheH/VFuzVvO3GTs32ZnSLsekgLu6gG', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:42', '2026-04-19 19:57:42', NULL, NULL, 0, 1),
(200, 134, 'shoomichael', 'shoomichael@yahoo.com', '0785750087', NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$bhVrOaBHngVW.zmbgik5mOb6sf0GfQaXnA3Rn4bqt.n32qcPz1bc.', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:42', '2026-04-19 19:57:42', NULL, NULL, 0, 1),
(201, 135, 'John B.', 'jrjohn3002@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$t3N8wYXbprbtct4TodDdlORXI5eHDpXOwC3wnsTnA/cgMxyZdKFVO', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:42', '2026-04-19 19:57:42', NULL, NULL, 0, 1),
(202, 136, 'DEOGRATIUS K.', 'desderykaluta@gmail.com', '0686190576', NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$WGNbDUhRKGRAEnKj2K3Oke1i6YR16fDUq2AwhqVVQjWqlEmYqkx2u', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:43', '2026-04-19 19:57:43', NULL, NULL, 0, 1),
(203, 137, 'benjamin J.', 'jloserian@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$/80LQrCjQe2ksH6aYeLVSOEig3qQIntCPqbgYf7is7S20a5kgsWam', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:43', '2026-04-19 19:57:43', NULL, NULL, 0, 1),
(204, 138, 'Azim M.', 'azimecos17@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$JvIfopHfkcphvuDnBEkd3OzbYOgKWIhHff1K3eSYOCAbxD0r1wd3q', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:43', '2026-04-19 19:57:43', NULL, NULL, 0, 1),
(205, 139, 'HARUNA M.', 'harunamaulid@icloud.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$HXmNgIuE/FV3toANFv63xuk/M6qH7L4/ZRz.Y.B5X.LibNgA7rHwu', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:43', '2026-04-19 19:57:43', NULL, NULL, 0, 1),
(206, 140, 'venosajerome28', 'venosajerome28@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$QVFX6L1CQJRqOChI2IYhS.usB3O6Wq3khhWi5q5YuXA3SqJmBAVrC', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:43', '2026-04-19 19:57:43', NULL, NULL, 0, 1),
(207, 141, 'masturasaleh0', 'masturasaleh0@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$CjVX9r4sJI4t2ooKYCKI4eB9TFZgL6EhRAvj0s5E0CbH62Xq3RPOi', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:44', '2026-04-19 19:57:44', NULL, NULL, 0, 1),
(208, 142, 'Elisha P.', 'pangaelishatz@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$ixUdXursJEP5e49KM3qkzOcajwUKmw8L7mvp1BSUZzoA5acwb3TJS', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:44', '2026-04-19 19:57:44', NULL, NULL, 0, 1),
(209, 143, 'DEBORA N.', 'ferdinanddeborah9@gmail.com', '0629196806', NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$PBtOn.tEmDoekOkHYBf2LebxovGOkCF7e/FgwcMZIJj1ODDKHHK/i', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:44', '2026-04-19 19:57:44', NULL, NULL, 0, 1),
(210, 144, 'maryjoycekambarage', 'maryjoycekambarage@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$k.dyHlkCrWe04ixBhV0sLerbZtg4xhTIkKoVufoU7MOF28CP63MI.', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:44', '2026-04-19 19:57:44', NULL, NULL, 0, 1),
(211, 145, 'Mgogwe M.', 'mgogwejohn@yahoo.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$eror6dy6Ga4DaPdT2WJE5enkuE8AkRlurZVXH.WEYkyH6zlFgKg4W', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:44', '2026-04-19 19:57:44', NULL, NULL, 0, 1),
(212, 146, 'travisgeorgenkya', 'travisgeorgenkya@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$TEI9X99oeraLdX0MWRnfSuKMErzXlNIe7KgPCYctWcirK6Jp4MsH2', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:45', '2026-04-19 19:57:45', NULL, NULL, 0, 1),
(213, 147, 'Ubepari P.', 'mwasambilierick@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$Rj.j/4SbGx2S2g66par5Eu6OzF3nXtW10plai.0vQlD8dyp2h4nwm', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:45', '2026-04-19 19:57:45', NULL, NULL, 0, 1),
(214, 148, 'KELVIN CHARLES C.', 'kelvincharleschacha07@gmail.com', '0750792039', NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$Z9IQ6NvKcJFcyYIRVtZiP.IJE3M9xpNXICG77vsm3Af7m//FUndMe', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:45', '2026-04-19 19:57:45', NULL, NULL, 0, 1),
(215, 149, 'Janeth K.', 'kisanijaneth@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$heqPPN9M8gaRMWRkXIdglO4p2GsCFc33SmCy2A2DnLL4g/6scU6Um', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:45', '2026-04-19 19:57:45', NULL, NULL, 0, 1),
(216, 150, 'JORDAN G.', 'jordanleonidace@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$hslCzINomjUkI6wyJAxC4ONHFgQbh9OQE9DDWdUR5L0HdhN1oCkHG', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:45', '2026-04-19 19:57:45', NULL, NULL, 0, 1),
(217, 151, 'Dr Farashuu K.', 'farashuuk4@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$oSQU4T0ku.itdBwmOiv5ueF/h0kZ3QwvUxUJAdm3O86mioRFQdbxG', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:45', '2026-04-19 19:57:45', NULL, NULL, 0, 1),
(218, 152, 'Franco N.', 'francomushi143@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$hd0IvdNF19scaa87aYaSteDmAgUvb0eKXjkcx750rPhg46RYTHu3e', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:46', '2026-04-19 19:57:46', NULL, NULL, 0, 1),
(219, 153, 'Emmanuelmassawe66', 'emmanuelmassawe66@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$yhFEdbP2sWuM3Fie5D4FiOvTlOyhz3c5ZU4ilzKGA6HWiwe4E4YqG', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:46', '2026-04-19 19:57:46', NULL, NULL, 0, 1),
(220, 154, 'markomasoud8', 'markomasoud8@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$c6qlOVinS/zkLf8SOfkW8O/9tNTFOOrP6JTWNHprPPzDS8kXSi4zy', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:46', '2026-04-19 19:57:46', NULL, NULL, 0, 1),
(221, 155, 'aleksandermonstad', 'aleksandermonstad@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$qD2nzgSr4Y4pTgHwoPaEPe93zalPLKrhxvwaSdCuiMEztk/uzcR5C', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:46', '2026-04-19 19:57:46', NULL, NULL, 0, 1),
(222, 156, 'Comfort R.', 'comfortnaftal@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$d.fn8GH16Bo5v7ig0Y8pZuYUwA541k7SgR3tY3FPzIXRi5VVOdjUO', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:46', '2026-04-19 19:57:46', NULL, NULL, 0, 1),
(223, 157, 'Leonard E.', 'leonardelisante770@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$9THpYjJQJ8hurlEGXJ29tOwTd0gjPZu753skUvW.OPyYS6uPXJMii', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:47', '2026-04-19 19:57:47', NULL, NULL, 0, 1),
(224, 158, 'Hilali N.', 'ndondyhilal@gmail.com', '0616410346', NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$HMyBEg0rzTfgXPKUGPUs3OwAHWE/iD9IG7rtDP5KWRQ8xG5PHl0Fa', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:47', '2026-04-19 19:57:47', NULL, NULL, 0, 1),
(225, 159, 'prophetq46', 'prophetq46@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$fNr2QQRr.nLOhByu205q8O9PhkEermUBeYGlKubtN5OH2rY2bpjvy', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:47', '2026-04-19 19:57:47', NULL, NULL, 0, 1),
(226, 160, 'salamahmussa', 'salamahmussa@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$eaauZjFHf/BuzHzHSL67PO2wptfFScvj4jVkNFXVHCHzfhBaePuP.', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:47', '2026-04-19 19:57:47', NULL, NULL, 0, 1),
(227, 161, 'Martha R.', 'martharegnald0@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$syckoShIoryCOFP9aLHR0e7yUIdBY92Q/.vo5Fo5YOfufC2QfYaoC', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:47', '2026-04-19 19:57:47', NULL, NULL, 0, 1),
(228, 162, 'Rufauqy R.', 'ruqaiyya22@icloud.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$YFyssNjoWpYWWYhbtWMegOwEpqb.2ImSTeNVl2JiKek5Nm1W8ouhi', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:48', '2026-04-19 19:57:48', NULL, NULL, 0, 1),
(229, 163, 'Ezekia M.', 'mbunjuezekiel5@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$Sb9c3aoCD6huhEO/RAqUfOB5nUn9ZhR.NM/8.Po5X6sxJWUvo/RjC', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:48', '2026-04-19 19:57:48', NULL, NULL, 0, 1),
(230, 164, 'Sharon H.', 'hilarysharon759@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$XNUTbh21P.XKxkPZ7pV.nu5cGXUQf8nCGeLAPPQpo1EAYSD3Fm3EC', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:48', '2026-04-19 19:57:48', NULL, NULL, 0, 1),
(231, 165, 'Bertha A.', 'berthamagesse767@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$7VAIRTTibHT0yFb2KGS4meOrt5qfL.JvpOxN6UBjnYgGPMtktKQyO', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:48', '2026-04-19 19:57:48', NULL, NULL, 0, 1),
(232, 166, 'Lucia M.', 'moseslucia332@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$s.QXTFKSj9uDKoQHTo6tE.cssZciG3yIFSFAS9w6LGcEb86EU8FNG', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:48', '2026-04-19 19:57:48', NULL, NULL, 0, 1),
(233, 167, 'Alexander M.', 'mwengualexander@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$0nNiIRC4tglOIoRib9GTD.chPaeGbS.sA74F.z28GC9z5lHF.hvDi', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:49', '2026-04-19 19:57:49', NULL, NULL, 0, 1),
(234, 168, 'alexandermwengu', 'alexandermwengu@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$.W4OsAq0X/nUMBhVf8OWGuHSvDhQVbtKZLkF0qdkj6kCT4HcjfUR2', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:49', '2026-04-19 19:57:49', NULL, NULL, 0, 1),
(235, 169, 'najmaawesu04', 'najmaawesu04@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$otLcE72h2QY3OyhDkoR2w.bs6uwGhn0Nq7/5NXfd0WSKgBmK/hOYe', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:49', '2026-04-19 19:57:49', NULL, NULL, 0, 1),
(236, 170, 'Jermack B.', 'jeremiaminja96@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$29qHB9/nMb0BUOlaeCYp4uHqDw6ScwXPW5Xk7pEPC6eHmS/7RAaD2', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:49', '2026-04-19 19:57:49', NULL, NULL, 0, 1),
(237, 171, 'ambrosesamwel444', 'ambrosesamwel444@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$l16ctCorxM/Cn.ukmSy8PuhKHhtcph7IXF96no3N3PpK..fYXaoPC', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:49', '2026-04-19 19:57:49', NULL, NULL, 0, 1),
(238, 172, 'juma508jr', 'juma508jr@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$ZKsFZOMvpv8xMVoIl2r0aOkDuM.z1m2KGfyuLcrqT2VpCuqf1uM.u', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:50', '2026-04-19 19:57:50', NULL, NULL, 0, 1),
(239, 173, 'ATHUMAN M.', 'athuman_mbelwa@yahoo.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$Y67N97N0dYbuXEVUl3ivie9sN8tqlg4eI4vFra.YtY/ykZ/TXaKA.', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:50', '2026-04-19 19:57:50', NULL, NULL, 0, 1),
(240, 174, 'P M.', 'parfectmachinister@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$s3/MFmMjsD.EjHbvMJwfNeGyzT1gnSu3bh0WEKwnAPt7UVs8W7XNa', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:50', '2026-04-19 19:57:50', NULL, NULL, 0, 1),
(241, 175, 'Solace K.', 'solacesilas@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$ZLyvIO3z/lXkpsN9ZpOgguOPBBiwbU7PjMYPWua0hrP2Si/NFBb6a', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:50', '2026-04-19 19:57:50', NULL, NULL, 0, 1),
(242, 176, 'gsemkiwa', 'gsemkiwa@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$OOkLLr2w5i1qy6PtJhpN.eVTKdfjSoV4LbrGH2dyGHHNkP6BT7VcO', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:50', '2026-04-19 19:57:50', NULL, NULL, 0, 1),
(243, 177, 'Hash M.', 'hashimmalleta@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$1y/mnwE8fzgAr9IYBCB56usiqmc.eU.BSwBjVmvO51h8irBDRLFY.', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:51', '2026-04-19 19:57:51', NULL, NULL, 0, 1),
(244, 178, 'Miraji A.', 'mirajiamiri881@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$Hcj0fP9xtww58P7vM3VV3uh2Dw33IK80xyMA8Txn4ZGx6FzPDe8OO', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:51', '2026-04-19 19:57:51', NULL, NULL, 0, 1),
(245, 179, 'Andrew M.', 'mbuyaandrew185@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$ldTZnubIYdHQlZvyiQ/e4O31IDgdah5NjryF8L2UW90qBsEbeJQma', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:51', '2026-04-19 19:57:51', NULL, NULL, 0, 1),
(246, 180, 'miltonnderima', 'miltonnderima@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$ggq.Kn.64g9hOVvuG.lWyeWbuuUUeqyqXYM7D7NPk18J1H5yyydL.', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:51', '2026-04-19 19:57:51', NULL, NULL, 0, 1),
(247, 181, 'DAMAS K.', 'dkanyabwoya@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$YjBXEawkeDnHqSxSwObtNOV1Fh375QaRw2yYkTLzAkzn5syxEwUqq', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:51', '2026-04-19 19:57:51', NULL, NULL, 0, 1),
(248, 182, 'Najat M.', 'mwitanajat@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$AH3C6njm8Vav90fl4VwIBuVFF27RlFQqQgly01W3veNGV25Vy.Loy', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:52', '2026-04-19 19:57:52', NULL, NULL, 0, 1),
(249, 183, 'Lilian Z.', 'jastinlilian5@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$b7qVsKFQLOIa.37PvKnZ2ew43UrGUCBGehPjZ0bTd1mrJccEA11bW', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:52', '2026-04-19 19:57:52', NULL, NULL, 0, 1),
(250, 184, 'Cosmas K.', 'cosmaskayombo@gmail.com', '255757960988', NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$RSy/ZWt5ktGx.dfSOMwDp.Y8iFYlr8YzsHBTxYzuFw4Qt1H0V9Joy', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:52', '2026-04-19 19:57:52', NULL, NULL, 0, 1),
(251, 185, 'Magreth K.', 'magrethkakaku38@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$Y8cnNr4MxAOJzcPeeSOi6.KiF.J0x.vyGjr8BGOe/dNT6Yjo.FmQi', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:52', '2026-04-19 19:57:52', NULL, NULL, 0, 1),
(252, 186, 'fettydullah839', 'fettydullah839@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$csvKFFRg8ptaT2u.rcWaEO/2xloO5xekr0p8N0bfl99zomS6etIPy', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:52', '2026-04-19 19:57:52', NULL, NULL, 0, 1),
(253, 187, 'Shahezanaan R.', 'shazusajjadrai@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$K7il3n6q7x.oIb1.YXan2ut8ikatHRCmYHtYsydqZIzFuQVmzqLoa', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:53', '2026-04-19 19:57:53', NULL, NULL, 0, 1),
(254, 188, 'Melvin M.', 'mronimelvin@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$kc2ES7kA.sB2V04MYPZLoeAbT6F9O1we.6fIXw0MeE.7O/xWC37Ae', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:53', '2026-04-19 19:57:53', NULL, NULL, 0, 1),
(255, 189, 'raiderrowzie', 'raiderrowzie@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$8aYIkzzmiMBPBAU/N1B2NeOdBymTSb9aG4yBsW7mJ750JiG8L283e', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:53', '2026-04-19 19:57:53', NULL, NULL, 0, 1),
(256, 190, 'Emmanuel M.', 'emmanuelmsekwa424@gmai.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$V8zaxyZOveod3M8Po4gs7ONHUtaQbron1F5Iv2eCFeA.K/QL22Wwu', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:53', '2026-04-19 19:57:53', NULL, NULL, 0, 1),
(257, 192, 'wallenmbaoh718', 'wallenmbaoh718@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$xLhZErMOPOwya2ZypqjJ8.WaHgJXKw979Z6qN/9OV2ZodK2EwCT6O', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:53', '2026-04-19 19:57:53', NULL, NULL, 0, 1),
(258, 193, 'magegemam', 'magegemam@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$BqLPsAEc5bQbyNLOqTPxuu12OVZVvV67SlthRB7Lpzg03GiEz2Jle', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:54', '2026-04-19 19:57:54', NULL, NULL, 0, 1),
(259, 194, 'DAVID M.', 'davidmselewa26@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$qufLigwLomYwQxfE2vfxWu9K5S1R1z6LVoKX2aWwWIah5J7xezINe', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:54', '2026-04-19 19:57:54', NULL, NULL, 0, 1),
(260, 195, 'peterjoshua671', 'peterjoshua671@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$pcwlFWxKkBnTMBt6rDsEze3KX70kmuZefmrpv0S4Lc548WEWulHJ.', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:54', '2026-04-19 19:57:54', NULL, NULL, 0, 1),
(261, 196, 'mohsan.e', 'mohsan.e@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$Mo.RRI6LVJk0Efom4Vr4HOT1aGFLGIrReiRzP2X.gr6fWu.u7ttoy', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:54', '2026-04-19 19:57:54', NULL, NULL, 0, 1),
(262, 197, 'BRAVO S.', 'switmelodytz@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$VUYDZfGbq12ZJsACRpfyCeNlJsV0udCxv3a/GnuZT2E127W.1GDwe', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:54', '2026-04-19 19:57:54', NULL, NULL, 0, 1),
(263, 198, 'Khamis K.', 'ajmalat19@gmail.com', '255628672502', NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$MpDOTtePDGWOfcHTxgRE7.VN71AnYCj9ziptALM2i4ahAJ0QajhTu', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:54', '2026-04-19 19:57:54', NULL, NULL, 0, 1),
(264, 199, 'roseherman', 'roseherman@913.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$I0DgpvJKwqXIJ8aVWgqk5enevU2XVLTm.ekyQht195qr1E8b/cKAu', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:55', '2026-04-19 19:57:55', NULL, NULL, 0, 1),
(265, 200, 'Yussuf V.', 'yussufvani@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$zre8lDmk41nHO/UTR97TFOjtVFX1pFV3Bb4IHIABE0VJCgVZDhqUu', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:55', '2026-04-19 19:57:55', NULL, NULL, 0, 1),
(266, 201, 'kyaginorbie', 'kyaginorbie@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$Dg3xv0MEceYN4lhNRQ7jKOpgObvJHC15Ivzycur33BowgumoGeWJK', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:55', '2026-04-19 19:57:55', NULL, NULL, 0, 1),
(267, 202, 'augustinomustapha950', 'augustinomustapha950@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$FOltDJby4fBxC76P2NQHlOD0HNjuXyTFUgt5syNJM2J78thljwM7K', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:55', '2026-04-19 19:57:55', NULL, NULL, 0, 1),
(268, 203, 'Munirah S.', 'munirahsaleh82@yahoo.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$ZqDfa0SrsCDz0SiF.WTFOeRq/AhIFoVjLBmOKQW2eFCP3c87A/JPi', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:55', '2026-04-19 19:57:55', NULL, NULL, 0, 1),
(269, 204, 'Yusuf J.', 'yjong25@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$zo9WrQz24Gq9BJ2dz6bJmO62Yrrka.tOh6VLtcyb4mFtjiJq0CLXW', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:56', '2026-04-19 19:57:56', NULL, NULL, 0, 1),
(270, 205, 'isayamsenda39', 'isayamsenda39@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$1m9QKJHWpBFcbyN.ph92h.6ZuWd56yhAWadS1HyqBNJDUQb2oVj6u', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:56', '2026-04-19 19:57:56', NULL, NULL, 0, 1),
(271, 206, 'Steven S.', 'stevengwidosalufu@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$d3bieioi2OAoopc6fjq2qutAXcBBcsYqjnNc74K75Yg.SpfUpOkRe', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:56', '2026-04-19 19:57:56', NULL, NULL, 0, 1),
(272, 207, 'Edison S.', 'edisonluanda719@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$DqCx4zag.Lx1ZmPz/C7lDO7uoGeRCg0St6hSrI1.NjKFdEWAHi7jC', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:56', '2026-04-19 19:57:56', NULL, NULL, 0, 1),
(273, 208, 'Sarah A.', 'blessedsarah852@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$oYc46qSRITWg87G.1yE.5enju.WEP.DruzbgWywfAOYrnZnW9JaJm', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:56', '2026-04-19 19:57:56', NULL, NULL, 0, 1),
(274, 209, 'tunsumealfred99', 'tunsumealfred99@icloud.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$XW7/VjbXfnIstaAdU9PG1.M/1R5VHMf/EDZDWTNUoJyFGEI7dW7NW', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:57', '2026-04-19 19:57:57', NULL, NULL, 0, 1),
(275, 210, 'Man S.', 'mansejo930@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$mTUuZNUNtIm.Yh28yWH65OGbWcOnFhSJrSG7BoeBtHIIKg4tPauo.', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:57', '2026-04-19 19:57:57', NULL, NULL, 0, 1),
(276, 211, 'jimmyjunior617', 'jimmyjunior617@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$Pjez5cK2ZC3gbEM2cE5rk.0VYbVTjtZYl46DMnt53qiX/lApEaiHm', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:57', '2026-04-19 19:57:57', NULL, NULL, 0, 1),
(277, 212, 'juniormaganga223', 'juniormaganga223@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$yRJkJf5sAF6O1YxeMihNa.S/rpb.NZZt61EVFO0YkBphqBtftKzgq', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:57', '2026-04-19 19:57:57', NULL, NULL, 0, 1),
(278, 213, 'MUSA M.', 'www.masalamusa06@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$iewD1oHHDWUqCjCM3xb0ZuZ5FteGWo61plmflOg8eb2OtFUQZcDga', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:57', '2026-04-19 19:57:57', NULL, NULL, 0, 1),
(279, 214, 'blessingmahenge8', 'blessingmahenge8@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$egLrmI9XYHtr7vOAwsHvO.BojhFB6DEIH3SyQ4OTQqfcRvxKTqQe.', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:58', '2026-04-19 19:57:58', NULL, NULL, 0, 1),
(280, 215, 'coxmaxmartin', 'coxmaxmartin@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$XUw4Xxoi2HUZ9cZ1e.UX7Of.rm6eCmrER1TBfxueamSKVFWpUZE.y', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:58', '2026-04-19 19:57:58', NULL, NULL, 0, 1),
(281, 216, 'Happiness M.', 'happinessmwansakanile@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$QJAxSY19207opEz88Sv2juww/vAKdobZ6xuiW/yQQoIVRFNyhYKe6', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:58', '2026-04-19 19:57:58', NULL, NULL, 0, 1),
(282, 217, 'fahrda15', 'fahrda15@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$jvsHW1xZVsrE34ffUMhqSOYJDE8bn.eWDJOSlQataQ1Ay840WShR.', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:58', '2026-04-19 19:57:58', NULL, NULL, 0, 1),
(283, 218, 'RAINOLD M.', 'mahengorainold@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$EHEzeb6IaU5/m1jmfQ2USup31efVUHxh3KGGLr5gBaJ51S4MRiwZO', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:58', '2026-04-19 19:57:58', NULL, NULL, 0, 1),
(284, 219, 'tinakimaro99', 'tinakimaro99@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$mizCJsQlJHPc7brqkTXk2.7tENtE2eI7DgcWsqzt/rtvqDwimcFJW', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:59', '2026-04-19 19:57:59', NULL, NULL, 0, 1),
(285, 220, 'Ashley R.', 'ashleyrwanda06@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$AZYlGpbWVSX8j4JS5gL6z.mJ7T7uGLHkvTdEYsNtWFlz3AktMdIp6', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:59', '2026-04-19 19:57:59', NULL, NULL, 0, 1),
(286, 221, 'Regina J.', 'reginaespeditor95@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$q1kydp9ZisgedP4wli7xzumgv0GfzmxSTuS12kDljsJwcN/qqqmiO', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:59', '2026-04-19 19:57:59', NULL, NULL, 0, 1),
(287, 222, 'noshardgreenkikoti', 'noshardgreenkikoti@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$NwAOMvBN1qZ5Gs0kun8Oaeld8NqLnxfvz4JJ1Vjg2W5ZIjMWPCl1y', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:59', '2026-04-19 19:57:59', NULL, NULL, 0, 1),
(288, 223, 'daninyofromdowntown077', 'daninyofromdowntown077@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$iWBX31QiM9Rb0iyOj9olCOpVSZpchIWfA5cPaRCTO9gqX/OdkuOrW', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:57:59', '2026-04-19 19:57:59', NULL, NULL, 0, 1),
(289, 224, 'jasminemohamed154', 'jasminemohamed154@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$ehMEMVE0bHs0gmSbhc5hW.K0Ww3vkad81Beoy47c7UH7kRhsCi6gq', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:58:00', '2026-04-19 19:58:00', NULL, NULL, 0, 1),
(290, 225, 'kangakaren39', 'kangakaren39@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$3mSJJi1x3NgUPrDQvdeIX.vTQ8ly14PMwE/.R47fGLWLL8KD3J.vK', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:58:00', '2026-04-19 19:58:00', NULL, NULL, 0, 1),
(291, 226, 'priscillaishika2', 'priscillaishika2@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$5T.6ycbVRVr7niiaucqZ6OeDHJE75CogOk9J3j4a3MbRAWX/BGJfy', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:58:00', '2026-04-19 19:58:00', NULL, NULL, 0, 1),
(292, 227, 'Gift I.', 'edwardsgift96@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$5TshiQ3HvqFY83qhYqlYp.o.ZESqZ4vef0VXlzkWZKIhyuWh6mesK', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:58:00', '2026-04-19 19:58:00', NULL, NULL, 0, 1),
(293, 228, 'Erickson', 'ericksonphares1605@yahoo.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$sVxlL3xJI62z7rjkmmdhYeIAOZNyxH0iErw3Gc2MjNXDbYTOr7km.', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:58:00', '2026-04-19 19:58:00', NULL, NULL, 0, 1),
(294, 229, 'coolestmanagement', 'coolestmanagement@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$vySNMAsJqxD5IbCst7g5e.TNh3ndBNQ6asveDPmAmZZ.1cEPgI9Ky', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:58:01', '2026-04-19 19:58:01', NULL, NULL, 0, 1),
(295, 230, 'Zee H.', 'zulphahusseyn87@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$u.0cI3h0yD2UZ/JEDhC78evBFQVbjXwDcY0otbsRn5KJINcx9a3Je', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:58:01', '2026-04-19 19:58:01', NULL, NULL, 0, 1),
(296, 231, 'mjemaelizabeth', 'mjemaelizabeth@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$3BPK8usyhkXg/iOy0UmoF.x1hcWaOXQKBsZMwE9M/9neXD37favCK', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:58:01', '2026-04-19 19:58:01', NULL, NULL, 0, 1),
(297, 232, 'Musa K.', 'musakusekwa711@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$sHoWo1jUXBE5.yMk6Ju12ehfX/nD3EN5cRNIBwfozyrpUNwiqRbvi', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:58:01', '2026-04-19 19:58:01', NULL, NULL, 0, 1),
(298, 233, 'cammysah10', 'cammysah10@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$/dD2yLuTmXbSIaj2YO4CUuydZzlwF9Uy1FBUJzHVbb25FWyvaKaim', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:58:01', '2026-04-19 19:58:01', NULL, NULL, 0, 1),
(299, 234, 'patrickfaustin845', 'patrickfaustin845@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$sV4D.wpge6G9pO95VglBteirXKbiNBve738o9Vhgto64EJjOjSm6e', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:58:02', '2026-04-19 19:58:02', NULL, NULL, 0, 1),
(300, 235, 'mtemekelebendicto5', 'mtemekelebendicto5@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$dfarAibVWUMnHLKsfwORaekI9A.w3w3uiYVxMMiHsimN0wewNMh9G', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:58:02', '2026-04-19 19:58:02', NULL, NULL, 0, 1),
(301, 236, 'Benedicto M.', 'mtemekelebenedicto5@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$OgFfHZT2wy7y3yUeGRxNaOL4v763WVPKJREUK.bDYEmoJ.BoDp3PK', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:58:02', '2026-04-19 19:58:02', NULL, NULL, 0, 1),
(302, 237, 'jacobisa38', 'jacobisa38@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$Fq1gj4hgNev2D2G.HjwwR.J2Wgi6nqOj5EDNx6YwCy1HtJibII3ai', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:58:02', '2026-04-19 19:58:02', NULL, NULL, 0, 1),
(303, 238, 'Alice pure essences', 'alicegeofrey43@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$WSnmOBKOhtZoitX07t9TguqGOPAHKCUP.3hz/kEmgAKJvCtFe5Z32', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:58:02', '2026-04-19 19:58:02', NULL, NULL, 0, 1),
(304, 239, 'dilually78', 'dilually78@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$meHTDHyPd4pzjAB8W7O3iueufNi7RjHPyureaTKICTmbHEZOxIKNW', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:58:03', '2026-04-19 19:58:03', NULL, NULL, 0, 1),
(305, 240, 'princelinekaaya', 'princelinekaaya@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$INivyRjy7uBE6ta4n9BFE.Y9Cjd74TJ2tsv/DvgnnWTYDSH0PfBI.', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:58:03', '2026-04-19 19:58:03', NULL, NULL, 0, 1),
(306, 241, 'jenipherchogo', 'jenipherchogo@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$7lmZz5elQYpkvG.MD1k0C.1WUvrD73Tsx4kKMk2tAFYQ2TfqEIrLq', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:58:03', '2026-04-19 19:58:03', NULL, NULL, 0, 1),
(307, 242, 'Toola J.', 'toolaj1@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$rjVI0OD4I0TIWqqO43FbUeXiRZY7iJGeXZPVgcZR4ZBnXQzNmq6YS', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:58:03', '2026-04-19 19:58:03', NULL, NULL, 0, 1),
(308, 243, 'Catherine P.', 'winnerpaulo09@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$n5pnh.bHXyv3iaBfo/2ovuQ52AScc4/tHAsS5/7.pAgNWAyUXPQ/O', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:58:03', '2026-04-19 19:58:03', NULL, NULL, 0, 1),
(309, 244, 'wahidahamdan129', 'wahidahamdan129@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$KHRoSydUDeC4Sqv6kg1vG./u9C6OtZ3D/QJGeSudYRIOvxVGwBwJq', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:58:04', '2026-04-19 19:58:04', NULL, NULL, 0, 1),
(310, 245, 'Magasi m.', 'magasisebastian@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$WyIDrPQLSsvaeTYcZtpWfuhgnxctmy/7Bh4a0VibuuK9s2EMM1Z4K', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:58:04', '2026-04-19 19:58:04', NULL, NULL, 0, 1),
(311, 246, 'noelsamweliyona862', 'noelsamweliyona862@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$2fc1TAzOOjUEwZ0giBRPdulHKYZE2Yk9NPBUZgyel9I8Kpxw61owO', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:58:04', '2026-04-19 19:58:04', NULL, NULL, 0, 1),
(312, 247, 'Prosper J.', 'thegreatprosper02@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$SxrWYqxKDN7T/Ib5kG4JnOume813mbDw9yELrO8kRJ9GQ7VHCcAq6', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:58:04', '2026-04-19 19:58:04', NULL, NULL, 0, 1),
(313, 248, 'nurunissavicent', 'nurunissavicent@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$1gwvAA/CwPiZu0dgblzrj.eZsH4IuDAYyzjYC7dWTdti6WztvPYme', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:58:04', '2026-04-19 19:58:04', NULL, NULL, 0, 1),
(314, 249, 'Flavian N.', 'fntilla@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$rF8LgtdUkTJSNQs5OUBUb.5Yr48Db2Afeh2AzW0Zw9cWgkkMuC0t2', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:58:04', '2026-04-19 19:58:04', NULL, NULL, 0, 1),
(315, 250, 'EPHRAIM B.', 'gwandaephraim@gmail.com', '0675467237', NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$gBD187jovmWLTZynFd02Oen51QmQa6TS5RZzwpH0oXd0PRNTGRfDm', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:58:05', '2026-04-19 19:58:05', NULL, NULL, 0, 1),
(316, 251, 'butuxezapaqo13', 'butuxezapaqo13@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$Bl/hdOCZdfmQcIoreR3lDO8WxwZppj4b7ZHEM6MID0/TMobarN2jS', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:58:05', '2026-04-19 19:58:05', NULL, NULL, 0, 1),
(317, 252, 'susanlahout', 'susanlahout@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$161ldwBGHnL5Uk0/x38TmOkCcfHTJFWFTYnP.AQVS.A3KZUcJyi1.', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:58:05', '2026-04-19 19:58:05', NULL, NULL, 0, 1);
INSERT INTO `users` (`id`, `legacy_wp_user_id`, `name`, `email`, `phone`, `custom_profile_slug`, `email_verified_at`, `is_verified`, `suspended_at`, `suspended_reason`, `avg_response_hours`, `is_top_rated`, `password`, `role`, `bio`, `avatar`, `location`, `latitude`, `longitude`, `mkoa`, `wilaya`, `mtaa`, `payment_method`, `payment_number`, `bei_aina`, `bei_wastani`, `uzoefu_miaka`, `siku_zinazopatikana`, `nida`, `veta`, `wallet_balance`, `phone_verified_at`, `onboarding_completed`, `whatsapp`, `phone_visible`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `remember_token`, `created_at`, `updated_at`, `otp`, `otp_expires_at`, `otp_attempts`, `two_factor_enabled`) VALUES
(318, 253, 'gaynellpetty', 'gaynellpetty@att.net', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$wh.TswH1GSrhEPrzXjSXpuS3SowbNfC1MeWHcEBZXkPOF83hWdu/q', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:58:05', '2026-04-19 19:58:05', NULL, NULL, 0, 1),
(319, 254, 'gluhovs', 'gluhovs@yahoo.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$/IH4fnSPO53vNYj2hStZOuluGlDztXBr4KFV6U9kffqi3pY4F1ynq', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:58:05', '2026-04-19 19:58:05', NULL, NULL, 0, 1),
(320, 255, 'karen', 'karen@spii.us', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$Ttb.aQRIwIMmbV3RXzlNq.JxN4emS9XPuc3edFSQCuH2YQXFBrHuO', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:58:06', '2026-04-19 19:58:06', NULL, NULL, 0, 1),
(321, 256, 'vijay17kumar', 'vijay17kumar@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$h8maN1T7JI.qC88enk39Ie1xM8alKKvCTEoqlsbclb26.5Q/fy3NG', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:58:06', '2026-04-19 19:58:06', NULL, NULL, 0, 1),
(322, 257, 'info', 'info@caffeineandkilos.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$y7wdkigFoW12ZqM.3KMdKOyZi2H0UJvvr8NdA0xmUleDi.NuggfEK', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:58:06', '2026-04-19 19:58:06', NULL, NULL, 0, 1),
(323, 258, 'mildredg', 'mildredg@hubx.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$L1AOHp9KMYnU27efSQXxkuZVr1NoyZkPK.q3CabTWr6p1jWibfZXu', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:58:06', '2026-04-19 19:58:06', NULL, NULL, 0, 1),
(324, 259, 'rivardrivard70', 'rivardrivard70@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$18Np7N5eoUbOivCVHvC03esf2jrrCsRjjMUybxrq0MkqReTiLy1em', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:58:06', '2026-04-19 19:58:06', NULL, NULL, 0, 1),
(325, 260, 'ross_carol', 'ross_carol@comcast.net', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$FKXMA8o2wK6Cxbbsc1q1NupnB2YxY6TIbGedDb0LDJhCrWIAVZO8.', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:58:07', '2026-04-19 19:58:07', NULL, NULL, 0, 1),
(326, 261, 'zachargath', 'zachargath@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$AlC.Vr.yybUJbvjDVREyMuTemqiwKpOTIe0.9MqWI5cimT4/ENyoa', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:58:07', '2026-04-19 19:58:07', NULL, NULL, 0, 1),
(327, 262, 'Hussein H.', 'saidihussein360@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$1qgcvuc/i7XCOcpxzyoQwO7Cu/owB5l6hHJq6pxLyaWM.jKXo61wG', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:58:07', '2026-04-19 19:58:07', NULL, NULL, 0, 1),
(328, 263, 'jamesanyox2', 'jamesanyox2@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$P/SvyM26hm7U2IH4yZapn.S/VE8qw.orV3i1XtU/jS0EwDfjaVwsa', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:58:07', '2026-04-19 19:58:07', NULL, NULL, 0, 1),
(329, 264, 'www.salmaally03', 'www.salmaally03@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$T.h95fPp7ckqDijB9gd1g.DzVnXb1P0.cyczhH7USrUSVoao7odoa', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:58:07', '2026-04-19 19:58:07', NULL, NULL, 0, 1),
(330, 265, 'annamroxooh', 'annamroxooh@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$10eGaliI3BM8j1hvJXYPGO.nPVO0jFs/6B/fNt8dt2exz8YUmhuI2', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:58:08', '2026-04-19 19:58:08', NULL, NULL, 0, 1),
(331, 266, 'mwarengegibran', 'mwarengegibran@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$PQ8yq4jr8/evk18MksLrCOjzpYzUlXi0kOiNchRkO2nH.pFsMZZpe', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:58:08', '2026-04-19 19:58:08', NULL, NULL, 0, 1),
(332, 267, 'Lewis', 'lewisrevocatus9@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$dqC2hfSSSY50.5QvxhVTbe3odaTKYi4RzktJ14vPohWbo5gkDwape', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:58:08', '2026-04-19 19:58:08', NULL, NULL, 0, 1),
(333, 268, 'Sunday N.', 'sundayngereza5@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$x5mtsHzdVNscSijZ/w3KBu9eRaI0573CB3HT48xk/hnpsEiewl9OK', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:58:08', '2026-04-19 19:58:08', NULL, NULL, 0, 1),
(334, 269, 'Daudi F.', 'lucasmganga11@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$ywJO4j/szT0PMgDTE4VdmefZJtppMeGsqErRelKotj9G8mciq0iAa', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:58:08', '2026-04-19 19:58:08', NULL, NULL, 0, 1),
(335, 270, 'deniszabron5', 'deniszabron5@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$cyhskFPk5xxDyDg/k3ibc.As5JQo4w2uV3bvy8WoZdxYPIdARqJp6', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:58:09', '2026-04-19 19:58:09', NULL, NULL, 0, 1),
(336, 271, 'liqumocewi499', 'liqumocewi499@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$93ett90L.H.cLqOC/pA8qeaKi4A9q.pMnHG9M.UFAAqKQRj6w/GKG', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:58:09', '2026-04-19 19:58:09', NULL, NULL, 0, 1),
(337, 272, 'Nasma S.', 'inasma498@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$CmkobgHFZm3WqfS6cNx3cuc7vOQ.rdmC2cxatsh7EV/OL47t/.plK', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:58:09', '2026-04-19 19:58:09', NULL, NULL, 0, 1),
(338, 273, 'anasia868', 'anasia868@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$me9pDp/aoErnUJNJOaWlh.fSfTuuJ3DDUS1lBH9FhrNKGdFz7EwaW', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:58:09', '2026-04-19 19:58:09', NULL, NULL, 0, 1),
(339, 274, 'iritijanul31', 'iritijanul31@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$13OHkOhfbgqGPcntAZ7VHuNXEx/vlVmyiEOxAr9b1hSKGScI0PxUO', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:58:09', '2026-04-19 19:58:09', NULL, NULL, 0, 1),
(340, 275, 'Victoria A.', 'abelvicky003@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$1sspbsgu3CHqPQfEVxdqOusw8aY4mIRzD2FUaNB2CMoAyOXLFWAvu', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:58:10', '2026-04-19 19:58:10', NULL, NULL, 0, 1),
(341, 276, 'reephilemon22', 'reephilemon22@icloud.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$oF0qD78yjrMby9s16W/2GOZFm.nhxQRAHW6wXzoKeJCIhln85oaMq', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:58:10', '2026-04-19 19:58:10', NULL, NULL, 0, 1),
(342, 277, 'allankessy838', 'allankessy838@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$w5m/P0aeRderOjRG.pwM6.zYO75eJOkZ7lax9GOVUDbqrc57KxIte', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:58:10', '2026-04-19 19:58:10', NULL, NULL, 0, 1),
(343, 278, 'Brastus M.', 'jojikipendo@gmail.com', '0754022170', NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$i7jwn2jDzu3zGSVATvFue.J2RQgZIXjvvplkCXTFSJnouPMSweOGe', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:58:10', '2026-04-19 19:58:10', NULL, NULL, 0, 1),
(344, 279, 'Anitha K.', 'anithakaijage7@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$PyMiumhrKbGP81C6RAYwBu.hcpLEcbRmHUaAN8nBB7H4v/qpVWAo6', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:58:10', '2026-04-19 19:58:10', NULL, NULL, 0, 1),
(345, 280, 'giftmwansachewe', 'giftmwansachewe@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$EIlJeNqjlP8w9TndDBbWhOXCFgUPY/gIn2i4vQrr/62hTbYvImXgO', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:58:11', '2026-04-19 19:58:11', NULL, NULL, 0, 1),
(346, 281, 'Fidelis M.', 'fidelisboniphace1@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$TFkJEOxb2YDXOdl8FxrCLObs44rNKNiN/oWCM43QK8cISEWO7SVtC', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:58:11', '2026-04-19 19:58:11', NULL, NULL, 0, 1),
(347, 282, 'Mussa M.', 'montypapillon@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$PZ147zdh0OqnZ2LIpbMKl.pwYnP2sicvpnTzkICGAADdbqcmwTsoi', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:58:11', '2026-04-19 19:58:11', NULL, NULL, 0, 1),
(348, 283, 'shilangar', 'shilangar@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$/9DaQL8LVmMSMBm9H2Bx..xfzQniK..V4XpQKZhh2aQG30jAxqnci', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:58:11', '2026-04-19 19:58:11', NULL, NULL, 0, 1),
(349, 284, 'Ngwikiamina', 'ngwikiamina@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$9VxXMW3oD.akkBsZSeGule4hjv42yIZGPW3rC/7.8Itul3GL6v2fK', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:58:11', '2026-04-19 19:58:11', NULL, NULL, 0, 1),
(350, 285, 'Mikydady', 'masteruse001@gmail.com', NULL, NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$3DS8kKlFtdkxE03roGlJQOgMPkAetxC2hfinDXqa5N2y36bj8EdCS', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 1, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 19:58:12', '2026-04-19 19:58:12', NULL, NULL, 0, 1),
(351, NULL, 'Abdul Bunju', 'ABJUMA0000@GMAIL.COM', '0715553803', NULL, NULL, 1, NULL, NULL, NULL, 0, '$2y$12$5GuYOY.pylTTVGsGeYER6uaUJ6bhM4gdpvHvRDgdwhrcCJgZuZftK', 'winga', NULL, 'profile-photos/cEuLDiV7PVfEx60gNC3SDAXXPdqJTN22zd8biVZ5.png', NULL, -6.82294028, 39.19172267, 'Dar es Salaam', 'Ilala', 'Ndotonadai', NULL, NULL, 'kazi', 100000, 5, '[\"Jmt\",\"Jtt\",\"Jnn\",\"Jtn\",\"Alh\",\"Ijm\"]', NULL, NULL, 0.00, NULL, 1, '0715553803', 0, NULL, NULL, NULL, NULL, '2026-04-21 16:09:23', '2026-04-21 21:18:27', '$2y$12$bhMc4SEUO920gAgkaYIxoeEFsq9fulHqCkCVO.SCt.EXzT71nRTTa', '2026-04-21 21:28:27', 0, 1),
(353, NULL, 'Amina Kakoyi', 'aminakakoyi@gmail.com', '+255710100100', NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$2AbIBxtoC3zJI7LqxRzkn.sH5rnQNYIxi6k0Sk400N3aIAVvL59R.', 'mteja', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 0, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-21 21:27:15', '2026-04-21 21:27:45', '$2y$12$6b8V2rlKlofhoM2PRBII9.UBAhq28tLJ4IUHpJHJjw3htbDywXUQy', '2026-04-21 21:37:45', 0, 1),
(354, NULL, 'VIOLETH BERNARD', 'bernardviolet46@gmail.com', '0764398865 ', NULL, NULL, 0, NULL, NULL, NULL, 0, '$2y$12$EORJOSa04MDwVEkkkm0PguyXTtHUXU3ZH92wFPEpC7IjUYcVvzVPO', 'winga', NULL, NULL, NULL, NULL, NULL, 'Dar es Salaam', 'Ilala', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL, 0, '0764398865 ', 0, NULL, NULL, NULL, NULL, '2026-04-23 05:50:33', '2026-04-23 17:51:08', '$2y$12$Gn1VnN9mdeUy9Q5GpLg5QOeG5YQHBOjqyxRwqpqjEPYzEtnz3bM/G', '2026-04-23 17:59:53', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_skills`
--

CREATE TABLE `user_skills` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `skill_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_skills`
--

INSERT INTO `user_skills` (`id`, `user_id`, `skill_id`) VALUES
(1, 64, 1),
(2, 64, 2),
(3, 64, 3),
(4, 64, 4),
(5, 64, 5),
(6, 64, 6),
(7, 64, 7),
(8, 64, 8),
(9, 64, 9),
(10, 64, 10),
(11, 64, 11),
(12, 64, 12),
(13, 64, 18),
(14, 64, 19),
(15, 64, 24),
(16, 64, 25),
(17, 64, 31),
(18, 64, 34),
(19, 66, 1),
(20, 66, 2),
(21, 66, 3),
(22, 66, 4),
(23, 66, 5),
(24, 66, 11),
(25, 66, 12),
(26, 66, 19),
(27, 66, 20),
(28, 351, 1),
(29, 351, 2),
(30, 351, 7);

-- --------------------------------------------------------

--
-- Table structure for table `withdrawal_requests`
--

CREATE TABLE `withdrawal_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `charge_percent` decimal(5,2) NOT NULL DEFAULT 0.00,
  `charge_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `net_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `method` enum('mpesa','tigopesa','airtel_money','bank_transfer') NOT NULL DEFAULT 'mpesa',
  `network` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `account_number` varchar(255) NOT NULL,
  `account_name` varchar(255) DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected','paid') NOT NULL DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `admin_note` text DEFAULT NULL,
  `payout_reference` varchar(255) DEFAULT NULL,
  `payout_status` enum('pending','processing','completed','failed') NOT NULL DEFAULT 'pending',
  `retry_count` int(11) NOT NULL DEFAULT 0,
  `last_retry_at` timestamp NULL DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `processed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `processed_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `withdrawal_requests`
--

INSERT INTO `withdrawal_requests` (`id`, `user_id`, `amount`, `charge_percent`, `charge_amount`, `net_amount`, `method`, `network`, `phone`, `account_number`, `account_name`, `bank_name`, `status`, `notes`, `admin_note`, `payout_reference`, `payout_status`, `retry_count`, `last_retry_at`, `approved_at`, `processed_at`, `created_at`, `updated_at`, `approved_by`, `processed_by`) VALUES
(2, 60, 10000.00, 0.00, 0.00, 0.00, 'tigopesa', 'Tigo', NULL, '+255678165524', NULL, NULL, 'paid', NULL, NULL, NULL, 'completed', 0, NULL, '2026-03-18 22:29:09', '2026-03-24 00:57:35', '2026-03-18 22:16:49', '2026-03-24 00:57:35', 62, 62),
(3, 60, 5000.00, 0.00, 0.00, 0.00, 'airtel_money', 'Airtel', NULL, '+255744000001', NULL, NULL, 'paid', NULL, NULL, NULL, 'completed', 0, NULL, '2026-04-21 20:00:05', '2026-04-21 20:01:09', '2026-04-21 19:58:39', '2026-04-21 20:01:09', 62, 62),
(4, 60, 5000.00, 0.00, 0.00, 0.00, 'airtel_money', 'Airtel', NULL, '+255744000001', NULL, NULL, 'rejected', NULL, 'Tuna changamoto kwa sasa', NULL, 'failed', 0, NULL, NULL, '2026-04-21 20:03:13', '2026-04-21 20:00:51', '2026-04-21 20:03:13', NULL, 62),
(5, 60, 5000.00, 0.00, 0.00, 0.00, 'tigopesa', 'Tigo', NULL, '+255744000001', NULL, NULL, 'paid', NULL, NULL, NULL, 'completed', 0, NULL, '2026-04-21 20:08:50', '2026-04-21 20:08:58', '2026-04-21 20:08:06', '2026-04-21 20:08:58', 62, 62),
(6, 60, 5000.00, 0.00, 0.00, 0.00, 'airtel_money', 'Airtel', NULL, '+255744000001', NULL, NULL, 'rejected', NULL, 'kwasasa ', NULL, 'failed', 0, NULL, NULL, '2026-04-21 20:10:13', '2026-04-21 20:08:21', '2026-04-21 20:10:13', NULL, 62),
(7, 60, 5000.00, 0.00, 0.00, 0.00, 'tigopesa', 'Tigo', NULL, '+255744000001', NULL, NULL, 'rejected', NULL, 'Test', NULL, 'failed', 0, NULL, NULL, '2026-04-21 20:12:52', '2026-04-21 20:12:24', '2026-04-21 20:12:52', NULL, 62),
(8, 60, 5000.00, 0.00, 0.00, 0.00, 'mpesa', 'Vodacom', NULL, '+255744000001', NULL, NULL, 'rejected', NULL, 'djsjsj. jsjsklslskjdjhdnd jdjdjk', NULL, 'failed', 0, NULL, NULL, '2026-05-02 12:23:25', '2026-04-27 18:50:09', '2026-05-02 12:23:25', NULL, 62),
(9, 60, 10000.00, 0.00, 0.00, 0.00, 'tigopesa', 'Tigo', NULL, '+255744000001', NULL, NULL, 'rejected', NULL, 'pole', NULL, 'failed', 0, NULL, NULL, '2026-05-02 12:35:44', '2026-05-02 12:24:33', '2026-05-02 12:35:44', NULL, 62);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_audit_logs`
--
ALTER TABLE `admin_audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_audit_logs_admin_id_created_at_index` (`admin_id`,`created_at`),
  ADD KEY `admin_audit_logs_action_created_at_index` (`action`,`created_at`),
  ADD KEY `admin_audit_logs_model_type_model_id_index` (`model_type`,`model_id`),
  ADD KEY `admin_audit_logs_created_at_index` (`created_at`);

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `applications_job_id_worker_id_unique` (`job_id`,`worker_id`),
  ADD KEY `applications_worker_id_foreign` (`worker_id`);

--
-- Indexes for table `broadcast_messages`
--
ALTER TABLE `broadcast_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `broadcast_messages_admin_id_foreign` (`admin_id`),
  ADD KEY `broadcast_messages_status_scheduled_at_index` (`status`,`scheduled_at`),
  ADD KEY `broadcast_messages_target_type_index` (`target_type`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`),
  ADD KEY `categories_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `conversations`
--
ALTER TABLE `conversations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `conversations_job_id_employer_id_worker_id_unique` (`job_id`,`employer_id`,`worker_id`),
  ADD KEY `conversations_employer_id_foreign` (`employer_id`),
  ADD KEY `conversations_worker_id_foreign` (`worker_id`);

--
-- Indexes for table `disputes`
--
ALTER TABLE `disputes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `disputes_job_id_foreign` (`job_id`),
  ADD KEY `disputes_status_priority_index` (`status`,`priority`),
  ADD KEY `disputes_initiator_id_status_index` (`initiator_id`,`status`),
  ADD KEY `disputes_respondent_id_status_index` (`respondent_id`,`status`),
  ADD KEY `disputes_auto_resolve_at_index` (`auto_resolve_at`);

--
-- Indexes for table `dispute_evidence`
--
ALTER TABLE `dispute_evidence`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dispute_evidence_submitted_by_foreign` (`submitted_by`),
  ADD KEY `dispute_evidence_dispute_id_submitted_by_index` (`dispute_id`,`submitted_by`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `favorites_user_id_favorable_id_favorable_type_unique` (`user_id`,`favorable_id`,`favorable_type`),
  ADD KEY `favorites_favorable_type_favorable_id_index` (`favorable_type`,`favorable_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `job_listings`
--
ALTER TABLE `job_listings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `job_listings_slug_unique` (`slug`),
  ADD KEY `job_listings_employer_id_foreign` (`employer_id`),
  ADD KEY `job_listings_category_id_foreign` (`category_id`),
  ADD KEY `job_listings_hired_worker_id_foreign` (`hired_worker_id`),
  ADD KEY `job_listings_status_created_at_index` (`status`,`created_at`),
  ADD KEY `job_listings_latitude_longitude_index` (`latitude`,`longitude`),
  ADD KEY `job_listings_approved_by_foreign` (`approved_by`);

--
-- Indexes for table `job_skills`
--
ALTER TABLE `job_skills`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `job_skills_job_id_skill_id_unique` (`job_id`,`skill_id`),
  ADD KEY `job_skills_skill_id_foreign` (`skill_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `messages_sender_id_foreign` (`sender_id`),
  ADD KEY `messages_conversation_id_created_at_index` (`conversation_id`,`created_at`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_job_id_foreign` (`job_id`),
  ADD KEY `payments_employer_id_foreign` (`employer_id`),
  ADD KEY `payments_worker_id_foreign` (`worker_id`),
  ADD KEY `payments_approved_by_foreign` (`approved_by`),
  ADD KEY `payments_service_request_id_foreign` (`service_request_id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `phone_block_attempts`
--
ALTER TABLE `phone_block_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `phone_block_attempts_user_id_form_type_index` (`user_id`,`form_type`),
  ADD KEY `phone_block_attempts_blocked_pattern_index` (`blocked_pattern`),
  ADD KEY `phone_block_attempts_created_at_index` (`created_at`);

--
-- Indexes for table `platform_settings`
--
ALTER TABLE `platform_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `platform_settings_key_unique` (`key`),
  ADD KEY `platform_settings_updated_by_foreign` (`updated_by`),
  ADD KEY `platform_settings_group_key_index` (`group`,`key`);

--
-- Indexes for table `portfolios`
--
ALTER TABLE `portfolios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `portfolios_user_id_foreign` (`user_id`),
  ADD KEY `portfolios_category_id_foreign` (`category_id`);

--
-- Indexes for table `profile_views`
--
ALTER TABLE `profile_views`
  ADD PRIMARY KEY (`id`),
  ADD KEY `profile_views_worker_id_viewed_at_index` (`worker_id`,`viewed_at`),
  ADD KEY `profile_views_viewer_id_viewed_at_index` (`viewer_id`,`viewed_at`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reviews_job_id_reviewer_id_unique` (`job_id`,`reviewer_id`),
  ADD KEY `reviews_reviewer_id_foreign` (`reviewer_id`),
  ADD KEY `reviews_reviewee_id_foreign` (`reviewee_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `services_user_id_status_index` (`user_id`,`status`),
  ADD KEY `services_category_id_status_index` (`category_id`,`status`);

--
-- Indexes for table `service_packages`
--
ALTER TABLE `service_packages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_packages_service_id_sort_order_index` (`service_id`,`sort_order`);

--
-- Indexes for table `service_requests`
--
ALTER TABLE `service_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_requests_service_id_status_index` (`service_id`,`status`),
  ADD KEY `service_requests_client_id_status_index` (`client_id`,`status`),
  ADD KEY `service_requests_service_package_id_foreign` (`service_package_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`),
  ADD KEY `settings_category_key_index` (`category`,`key`);

--
-- Indexes for table `site_announcements`
--
ALTER TABLE `site_announcements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `site_announcements_created_by_foreign` (`created_by`),
  ADD KEY `site_announcements_is_active_starts_at_ends_at_index` (`is_active`,`starts_at`,`ends_at`);

--
-- Indexes for table `site_announcement_user`
--
ALTER TABLE `site_announcement_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `site_announcement_user_site_announcement_id_user_id_unique` (`site_announcement_id`,`user_id`),
  ADD KEY `site_announcement_user_user_id_foreign` (`user_id`);

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `skills_slug_unique` (`slug`),
  ADD KEY `skills_category_id_foreign` (`category_id`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subscriptions_user_id_status_index` (`user_id`,`status`),
  ADD KEY `subscriptions_expires_at_index` (`expires_at`),
  ADD KEY `subscriptions_subscription_plan_id_foreign` (`subscription_plan_id`);

--
-- Indexes for table `subscription_plans`
--
ALTER TABLE `subscription_plans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subscription_plans_slug_unique` (`slug`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transactions_user_id_foreign` (`user_id`),
  ADD KEY `transactions_payment_id_foreign` (`payment_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_phone_unique` (`phone`),
  ADD UNIQUE KEY `users_custom_profile_slug_unique` (`custom_profile_slug`),
  ADD UNIQUE KEY `users_legacy_wp_user_id_unique` (`legacy_wp_user_id`);

--
-- Indexes for table `user_skills`
--
ALTER TABLE `user_skills`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_skills_user_id_skill_id_unique` (`user_id`,`skill_id`),
  ADD KEY `user_skills_skill_id_foreign` (`skill_id`);

--
-- Indexes for table `withdrawal_requests`
--
ALTER TABLE `withdrawal_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `withdrawal_requests_user_id_status_index` (`user_id`,`status`),
  ADD KEY `withdrawal_requests_approved_by_foreign` (`approved_by`),
  ADD KEY `withdrawal_requests_processed_by_foreign` (`processed_by`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_audit_logs`
--
ALTER TABLE `admin_audit_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `broadcast_messages`
--
ALTER TABLE `broadcast_messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `conversations`
--
ALTER TABLE `conversations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `disputes`
--
ALTER TABLE `disputes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dispute_evidence`
--
ALTER TABLE `dispute_evidence`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=359;

--
-- AUTO_INCREMENT for table `job_listings`
--
ALTER TABLE `job_listings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `job_skills`
--
ALTER TABLE `job_skills`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `phone_block_attempts`
--
ALTER TABLE `phone_block_attempts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `platform_settings`
--
ALTER TABLE `platform_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `portfolios`
--
ALTER TABLE `portfolios`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `profile_views`
--
ALTER TABLE `profile_views`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `service_packages`
--
ALTER TABLE `service_packages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `service_requests`
--
ALTER TABLE `service_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `site_announcements`
--
ALTER TABLE `site_announcements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `site_announcement_user`
--
ALTER TABLE `site_announcement_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `subscription_plans`
--
ALTER TABLE `subscription_plans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=355;

--
-- AUTO_INCREMENT for table `user_skills`
--
ALTER TABLE `user_skills`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `withdrawal_requests`
--
ALTER TABLE `withdrawal_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_audit_logs`
--
ALTER TABLE `admin_audit_logs`
  ADD CONSTRAINT `admin_audit_logs_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `applications_job_id_foreign` FOREIGN KEY (`job_id`) REFERENCES `job_listings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `applications_worker_id_foreign` FOREIGN KEY (`worker_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `broadcast_messages`
--
ALTER TABLE `broadcast_messages`
  ADD CONSTRAINT `broadcast_messages_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `conversations`
--
ALTER TABLE `conversations`
  ADD CONSTRAINT `conversations_employer_id_foreign` FOREIGN KEY (`employer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `conversations_job_id_foreign` FOREIGN KEY (`job_id`) REFERENCES `job_listings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `conversations_worker_id_foreign` FOREIGN KEY (`worker_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `disputes`
--
ALTER TABLE `disputes`
  ADD CONSTRAINT `disputes_initiator_id_foreign` FOREIGN KEY (`initiator_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `disputes_job_id_foreign` FOREIGN KEY (`job_id`) REFERENCES `job_listings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `disputes_respondent_id_foreign` FOREIGN KEY (`respondent_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `dispute_evidence`
--
ALTER TABLE `dispute_evidence`
  ADD CONSTRAINT `dispute_evidence_dispute_id_foreign` FOREIGN KEY (`dispute_id`) REFERENCES `disputes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `dispute_evidence_submitted_by_foreign` FOREIGN KEY (`submitted_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `job_listings`
--
ALTER TABLE `job_listings`
  ADD CONSTRAINT `job_listings_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `job_listings_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `job_listings_employer_id_foreign` FOREIGN KEY (`employer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `job_listings_hired_worker_id_foreign` FOREIGN KEY (`hired_worker_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `job_skills`
--
ALTER TABLE `job_skills`
  ADD CONSTRAINT `job_skills_job_id_foreign` FOREIGN KEY (`job_id`) REFERENCES `job_listings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `job_skills_skill_id_foreign` FOREIGN KEY (`skill_id`) REFERENCES `skills` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_conversation_id_foreign` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `payments_employer_id_foreign` FOREIGN KEY (`employer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_job_id_foreign` FOREIGN KEY (`job_id`) REFERENCES `job_listings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_service_request_id_foreign` FOREIGN KEY (`service_request_id`) REFERENCES `service_requests` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_worker_id_foreign` FOREIGN KEY (`worker_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `phone_block_attempts`
--
ALTER TABLE `phone_block_attempts`
  ADD CONSTRAINT `phone_block_attempts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `platform_settings`
--
ALTER TABLE `platform_settings`
  ADD CONSTRAINT `platform_settings_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `portfolios`
--
ALTER TABLE `portfolios`
  ADD CONSTRAINT `portfolios_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `portfolios_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `profile_views`
--
ALTER TABLE `profile_views`
  ADD CONSTRAINT `profile_views_viewer_id_foreign` FOREIGN KEY (`viewer_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `profile_views_worker_id_foreign` FOREIGN KEY (`worker_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_job_id_foreign` FOREIGN KEY (`job_id`) REFERENCES `job_listings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_reviewee_id_foreign` FOREIGN KEY (`reviewee_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_reviewer_id_foreign` FOREIGN KEY (`reviewer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `services_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `services_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `service_packages`
--
ALTER TABLE `service_packages`
  ADD CONSTRAINT `service_packages_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `service_requests`
--
ALTER TABLE `service_requests`
  ADD CONSTRAINT `service_requests_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `service_requests_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `service_requests_service_package_id_foreign` FOREIGN KEY (`service_package_id`) REFERENCES `service_packages` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `site_announcements`
--
ALTER TABLE `site_announcements`
  ADD CONSTRAINT `site_announcements_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `site_announcement_user`
--
ALTER TABLE `site_announcement_user`
  ADD CONSTRAINT `site_announcement_user_site_announcement_id_foreign` FOREIGN KEY (`site_announcement_id`) REFERENCES `site_announcements` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `site_announcement_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `skills`
--
ALTER TABLE `skills`
  ADD CONSTRAINT `skills_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD CONSTRAINT `subscriptions_subscription_plan_id_foreign` FOREIGN KEY (`subscription_plan_id`) REFERENCES `subscription_plans` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `subscriptions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_payment_id_foreign` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_skills`
--
ALTER TABLE `user_skills`
  ADD CONSTRAINT `user_skills_skill_id_foreign` FOREIGN KEY (`skill_id`) REFERENCES `skills` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_skills_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `withdrawal_requests`
--
ALTER TABLE `withdrawal_requests`
  ADD CONSTRAINT `withdrawal_requests_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `withdrawal_requests_processed_by_foreign` FOREIGN KEY (`processed_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `withdrawal_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
