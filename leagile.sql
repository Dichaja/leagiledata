-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Oct 11, 2025 at 10:31 PM
-- Server version: 11.5.2-MariaDB
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `leagile`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

DROP TABLE IF EXISTS `admin_users`;
CREATE TABLE IF NOT EXISTS `admin_users` (
  `id` char(36) NOT NULL,
  `user_id` char(36) DEFAULT NULL,
  `usr_type` char(20) NOT NULL,
  `first_name` text DEFAULT NULL,
  `last_name` text DEFAULT NULL,
  `role` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `user_id`, `usr_type`, `first_name`, `last_name`, `role`, `created_at`, `updated_at`) VALUES
('7f621d39-9622-11f0-bd8f-68f72840d364', '550e8400-e29b-41d4-a716-446655440002', 'Super', NULL, NULL, 'System Administration Management', '2025-09-20 13:04:38', '2025-09-20 13:04:38');

-- --------------------------------------------------------

--
-- Table structure for table `consultations`
--

DROP TABLE IF EXISTS `consultations`;
CREATE TABLE IF NOT EXISTS `consultations` (
  `id` char(36) NOT NULL,
  `user_id` char(36) DEFAULT NULL,
  `expert_id` char(36) DEFAULT NULL,
  `status` text DEFAULT NULL,
  `consultation_type` text NOT NULL,
  `scheduled_at` timestamp NULL DEFAULT NULL,
  `duration_minutes` int(11) DEFAULT NULL,
  `topic` text NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `expert_id` (`expert_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expert_profiles`
--

DROP TABLE IF EXISTS `expert_profiles`;
CREATE TABLE IF NOT EXISTS `expert_profiles` (
  `id` char(36) NOT NULL,
  `user_id` char(36) DEFAULT NULL,
  `first_name` text NOT NULL,
  `last_name` text NOT NULL,
  `bio` text NOT NULL,
  `specialization` text NOT NULL,
  `years_experience` int(11) NOT NULL,
  `hourly_rate` decimal(10,2) NOT NULL,
  `avatar_url` text DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT NULL,
  `is_available` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
CREATE TABLE IF NOT EXISTS `payments` (
  `id` char(36) NOT NULL,
  `trans` char(64) NOT NULL,
  `trans_id` char(64) DEFAULT NULL,
  `acc_frm` varchar(100) NOT NULL,
  `acc_paid_to` varchar(100) NOT NULL,
  `pay_status` char(2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`trans_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `trans`, `trans_id`, `acc_frm`, `acc_paid_to`, `pay_status`, `created_at`, `updated_at`) VALUES
('e9d72116-9f7d-4fa3-b032-285daeff060a', 'a8192dad-aade-467c-9a8e-f9846c58c2fd', 'TXN254203', 'bank - 99000', '', '01', '2025-09-21 00:57:08', '2025-09-22 08:22:17');

-- --------------------------------------------------------

--
-- Table structure for table `profiles`
--

DROP TABLE IF EXISTS `profiles`;
CREATE TABLE IF NOT EXISTS `profiles` (
  `id` char(36) NOT NULL,
  `name` text DEFAULT NULL,
  `email` text DEFAULT NULL,
  `avatar_url` text DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `phone` text DEFAULT NULL,
  `company` text DEFAULT NULL,
  `position` text DEFAULT NULL,
  `subscription_type` text DEFAULT NULL,
  `subscription_end_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

DROP TABLE IF EXISTS `purchases`;
CREATE TABLE IF NOT EXISTS `purchases` (
  `id` char(36) NOT NULL,
  `user_id` char(36) DEFAULT NULL,
  `report_id` char(36) DEFAULT NULL,
  `subscription_id` char(36) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_id` text DEFAULT NULL,
  `payment_status` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `report_id` (`report_id`),
  KEY `subscription_id` (`subscription_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

DROP TABLE IF EXISTS `reports`;
CREATE TABLE IF NOT EXISTS `reports` (
  `id` char(36) NOT NULL,
  `title` text NOT NULL,
  `author` text NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `thumbnail` text DEFAULT NULL,
  `category` text NOT NULL,
  `status` text DEFAULT NULL,
  `download_url` text DEFAULT NULL,
  `page_count` int(11) DEFAULT NULL,
  `publish_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `file_size` char(20) NOT NULL,
  `file_type` char(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `title`, `author`, `description`, `price`, `thumbnail`, `category`, `status`, `download_url`, `page_count`, `publish_date`, `created_at`, `updated_at`, `file_size`, `file_type`) VALUES
('e513815d-313b-11f0-9c0e-68f72840d364', 'Blockchain in Finance 2024', 'Jane Doe', 'An in-depth review of blockchain adoption in financial services with case studies and forecasts.', 79.99, 'https://images.unsplash.com/photo-1677442135136-760c813028c0?w=400&q=80', 'Finance', 'published', 'https://example.com/downloads/blockchain-finance.pdf', 88, '2025-05-15 07:22:26', '2025-05-15 07:22:26', '2025-05-15 07:22:26', '5', 'pdf'),
('e540f107-313b-11f0-9c0e-68f72840d364', 'Renewable Energy Investment Guide', 'Michael Green', 'Strategies and analysis for investing in the renewable energy sector globally.', 59.99, 'https://images.unsplash.com/photo-1509395176047-4a66953fd231?w=400&q=80', 'Energy', 'published', 'https://example.com/downloads/renewable-energy.pdf', 102, '2025-05-15 07:22:26', '2025-05-15 07:22:26', '2025-05-15 07:22:26', '5', 'pdf'),
('5662c41c-e8bb-4a9f-aeff-94a5aee94af4', 'Report A', 'By A', 'Research', 1.00, '', 'Academic Research', '', 'uploads/686f580c9ee9f_All_the_Money_in_the_World_20220906.pdf', 15, '2025-07-10 03:05:00', '2025-07-10 06:05:00', '2025-07-10 03:05:00', '5', 'pdf'),
('9f5b790f-cfbd-4039-8bb7-ab7ef37eea46', 'Commercial Banks in Uganda', 'CiB', 'All About Banking', 2.00, '', 'Academic Research', '', 'uploads/689213cb14147_Commercial Banks in Uganda (2023) & location.pdf', 4, '2025-08-05 11:23:07', '2025-08-05 14:23:07', '2025-08-05 11:23:07', '924935', 'application/pdf'),
('53651698-f7b0-442a-b5d3-5a772aab76d6', 'List of Hotels', 'CiB', 'All Details of Banks Listed in Uganda', 3.00, '', 'Academic Research', '', 'uploads/68922a0732a4f_Commercial Banks in Uganda (2023) & location.pdf', 3, '2025-08-05 12:57:59', '2025-08-05 15:57:59', '2025-08-05 12:57:59', '903.26 KB', '.pdf'),
('b693f2b4-157d-4fc1-b1c8-cc044c9c05a3', 'Report A', 'By A', 'Test', 12.00, '', 'Academic Research', '', 'uploads/6892d070cd5e4_Commercial Banks in Uganda (2023) & location.docx', 1, '2025-08-06 00:48:00', '2025-08-06 03:48:00', '2025-08-06 00:48:00', '905.53 KB', 'docx');

-- --------------------------------------------------------

--
-- Table structure for table `report_downloads`
--

DROP TABLE IF EXISTS `report_downloads`;
CREATE TABLE IF NOT EXISTS `report_downloads` (
  `id` char(36) NOT NULL,
  `user_id` char(64) NOT NULL,
  `item_id` char(64) NOT NULL,
  `item_price` decimal(10,2) DEFAULT NULL,
  `download_status` enum('pending','approved','completed','failed') DEFAULT 'pending',
  `download_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `report_downloads`
--

INSERT INTO `report_downloads` (`id`, `user_id`, `item_id`, `item_price`, `download_status`, `download_at`, `created_at`) VALUES
('b4717584-e618-48da-bceb-27b636d56723', 'eac0ff8c-92d9-4313-98ea-e55be0ecbf76', 'b693f2b4-157d-4fc1-b1c8-cc044c9c05a3', 12.00, 'approved', '2025-09-20 09:18:46', '2025-09-20 09:18:46'),
('a8192dad-aade-467c-9a8e-f9846c58c2fd', 'eac0ff8c-92d9-4313-98ea-e55be0ecbf76', 'e513815d-313b-11f0-9c0e-68f72840d364', 79.99, 'approved', '2025-09-20 10:52:03', '2025-09-20 10:52:03'),
('d8476bc7-482f-416a-a8b3-7a7a7708f5cd', '550e8400-e29b-41d4-a716-446655440002', 'b693f2b4-157d-4fc1-b1c8-cc044c9c05a3', 12.00, 'pending', '2025-09-22 00:52:21', '2025-09-22 00:52:21');

-- --------------------------------------------------------

--
-- Table structure for table `report_reviews`
--

DROP TABLE IF EXISTS `report_reviews`;
CREATE TABLE IF NOT EXISTS `report_reviews` (
  `id` char(36) NOT NULL,
  `report_id` char(36) DEFAULT NULL,
  `user_id` char(36) DEFAULT NULL,
  `rating` int(11) NOT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `report_id` (`report_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

DROP TABLE IF EXISTS `subscriptions`;
CREATE TABLE IF NOT EXISTS `subscriptions` (
  `id` char(36) NOT NULL,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `duration_months` int(11) NOT NULL,
  `features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` char(36) NOT NULL,
  `usr_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `active_status` char(2) NOT NULL,
  `verify_token` char(64) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `usr_name`, `email`, `password`, `active_status`, `verify_token`, `created_at`, `updated_at`) VALUES
('550e8400-e29b-41d4-a716-446655440000', 'John Doe', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '', '', '2025-06-23 06:00:21', '2025-09-20 01:59:07'),
('550e8400-e29b-41d4-a716-446655440001', 'Jane Smith', 'jane@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '', '', '2025-06-23 06:00:21', '2025-09-20 01:59:07'),
('550e8400-e29b-41d4-a716-446655440002', 'Admin User', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '01', '', '2025-06-23 06:00:21', '2025-09-20 13:04:00'),
('0c2b28b5-aa37-4c49-b81e-714c78e460f5', 'Vado', 'vado@gmail.com', '$2y$10$FayjZAbB684TCNCeWUTMcepGF0Ib7VeVlkUKunair/Wum0/f6SerK', '', '', '2025-07-03 04:59:32', '2025-09-20 01:59:07'),
('eac0ff8c-92d9-4313-98ea-e55be0ecbf76', 'Vad', 'vady@gmail.com', '$2y$10$DZ3bTRLqh65hU5QSKY5Iauq2UrQqRrB4SPlkRU9tX7i1a7UrPs3Ta', '01', '', '2025-07-07 15:42:17', '2025-09-20 05:20:41'),
('f76ffa79-a566-4488-89f4-8d17217c9074', 'user', 'user@example.123', '$2y$10$MHKFrDXd6hp/c1tXO.EkQ.NVG650omql2D5j6eRYpC0jTbTZYGWRm', '', '', '2025-08-01 02:02:21', '2025-09-20 01:59:07');

-- --------------------------------------------------------

--
-- Table structure for table `user_activities`
--

DROP TABLE IF EXISTS `user_activities`;
CREATE TABLE IF NOT EXISTS `user_activities` (
  `id` char(36) NOT NULL,
  `user_id` char(36) NOT NULL,
  `activity_type` varchar(50) NOT NULL,
  `activity_id` char(64) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user_activities`
--

INSERT INTO `user_activities` (`id`, `user_id`, `activity_type`, `activity_id`, `created_at`) VALUES
('770e8400-e29b-41d4-a716-446655440000', '550e8400-e29b-41d4-a716-446655440000', 'download', '5662c41c-e8bb-4a9f-aeff-94a5aee94af4', '2025-06-23 06:00:21'),
('770e8400-e29b-41d4-a716-446655440001', '550e8400-e29b-41d4-a716-446655440000', 'save', 'Saved \"Healthcare Trends Report\"', '2025-06-23 06:00:21'),
('770e8400-e29b-41d4-a716-446655440002', '550e8400-e29b-41d4-a716-446655440000', 'share', 'Shared report with team members', '2025-06-23 06:00:21'),
('770e8400-e29b-41d4-a716-446655440003', '550e8400-e29b-41d4-a716-446655440000', 'comment', 'Commented on \"Technology Forecast\"', '2025-06-23 06:00:21'),
('770e8400-e29b-41d4-a716-446655440004', '550e8400-e29b-41d4-a716-446655440001', 'download', '5662c41c-e8bb-4a9f-aeff-94a5aee94af4', '2025-06-23 06:00:21'),
('e50ab145-0fcb-4027-b786-d8cecd27ee35', '550e8400-e29b-41d4-a716-446655440000', 'login', 'User logged in', '2025-06-25 05:26:22'),
('ca3d1a33-2672-48e6-aa3e-163dbd9faa9d', '550e8400-e29b-41d4-a716-446655440000', 'login', 'User logged in', '2025-06-25 10:13:01'),
('8f5b0d05-86ad-4be5-880e-b7fb0521755d', 'eac0ff8c-92d9-4313-98ea-e55be0ecbf76', 'login', 'User logged in', '2025-07-07 15:42:37'),
('81d62519-acc9-450e-98d3-aaf79cc001b7', '550e8400-e29b-41d4-a716-446655440000', 'login', 'User logged in', '2025-07-08 13:08:51'),
('c196a445-ec06-4464-991c-a40b8f087351', '550e8400-e29b-41d4-a716-446655440000', 'login', 'User logged in', '2025-07-10 06:10:24'),
('d7c24600-7a79-4920-bc97-00550e0b00f1', '550e8400-e29b-41d4-a716-446655440000', 'login', 'User logged in', '2025-07-24 15:38:56'),
('1867ea08-0bc7-4f36-a512-faad711e158b', '550e8400-e29b-41d4-a716-446655440000', 'login', 'User logged in', '2025-08-04 06:59:55'),
('203e9291-b691-457b-ac88-cce10de621c6', 'eac0ff8c-92d9-4313-98ea-e55be0ecbf76', 'login', 'User logged in', '2025-09-20 02:37:46'),
('921b84aa-4a92-467b-886d-83e00753adae', 'eac0ff8c-92d9-4313-98ea-e55be0ecbf76', 'login', 'User logged in', '2025-09-20 02:38:48'),
('0e0136a2-000f-4c4d-9316-e47b7c910e7a', 'eac0ff8c-92d9-4313-98ea-e55be0ecbf76', 'login', 'User logged in', '2025-09-20 05:21:30'),
('93f32ee0-0a3f-414a-b604-daccd85a51b9', 'eac0ff8c-92d9-4313-98ea-e55be0ecbf76', 'login', 'User logged in', '2025-09-20 08:56:12'),
('3d8ba09b-bbec-47fc-8fe0-c2284b26376c', '550e8400-e29b-41d4-a716-446655440002', 'login', 'User logged in', '2025-09-20 23:27:11'),
('c1add14e-55b6-43e0-b602-63e9d5f3f01f', '550e8400-e29b-41d4-a716-446655440002', 'login', 'User logged in', '2025-09-20 23:28:40'),
('10f691b9-a76f-48f9-a433-9e065c923fca', 'eac0ff8c-92d9-4313-98ea-e55be0ecbf76', 'login', 'User logged in', '2025-09-21 00:08:17'),
('90d144bf-ff30-4732-8775-e39761b3b904', '550e8400-e29b-41d4-a716-446655440002', 'login', 'User logged in', '2025-09-21 00:11:35'),
('e4f31a8c-fec7-499f-ad9f-df4b87b55ee6', 'eac0ff8c-92d9-4313-98ea-e55be0ecbf76', 'login', 'User logged in', '2025-09-21 00:50:51'),
('c97528e4-d8c3-4050-85f0-72875ee0f429', '550e8400-e29b-41d4-a716-446655440002', 'login', 'User logged in', '2025-09-21 00:59:47'),
('10761a95-ae85-4e24-91fe-3a4f1a936cd0', '550e8400-e29b-41d4-a716-446655440002', 'login', 'User logged in', '2025-09-22 08:21:58'),
('64781a18-f246-47b3-b183-f7c7a345a4ee', '550e8400-e29b-41d4-a716-446655440002', 'login', 'User logged in', '2025-09-24 01:53:07'),
('484c320f-6a28-4019-86f3-83d78376c77c', 'eac0ff8c-92d9-4313-98ea-e55be0ecbf76', 'login', 'User logged in', '2025-09-24 15:02:13'),
('a6fa7e41-c536-425f-be76-0ed5aa1ed329', '550e8400-e29b-41d4-a716-446655440002', 'login', 'User logged in', '2025-10-11 22:06:14');

-- --------------------------------------------------------

--
-- Table structure for table `user_subscriptions`
--

DROP TABLE IF EXISTS `user_subscriptions`;
CREATE TABLE IF NOT EXISTS `user_subscriptions` (
  `id` char(36) NOT NULL,
  `user_id` char(36) DEFAULT NULL,
  `subscription_id` char(36) DEFAULT NULL,
  `start_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `end_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `is_active` tinyint(1) DEFAULT NULL,
  `payment_id` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `subscription_id` (`subscription_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
