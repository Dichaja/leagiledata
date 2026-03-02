-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Mar 02, 2026 at 04:27 AM
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
-- Table structure for table `consultation_requests`
--

DROP TABLE IF EXISTS `consultation_requests`;
CREATE TABLE IF NOT EXISTS `consultation_requests` (
  `id` varchar(36) NOT NULL,
  `user_id` varchar(36) NOT NULL,
  `expert_id` varchar(36) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `preferred_date` date DEFAULT NULL,
  `preferred_time` time DEFAULT NULL,
  `duration_hours` decimal(3,1) DEFAULT 1.0,
  `budget` decimal(10,2) DEFAULT NULL,
  `status` enum('pending','accepted','rejected','completed','cancelled') DEFAULT 'pending',
  `expert_response` text DEFAULT NULL,
  `scheduled_date` datetime DEFAULT NULL,
  `meeting_link` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `expert_id` (`expert_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `consultation_requests`
--

INSERT INTO `consultation_requests` (`id`, `user_id`, `expert_id`, `subject`, `message`, `preferred_date`, `preferred_time`, `duration_hours`, `budget`, `status`, `expert_response`, `scheduled_date`, `meeting_link`, `created_at`, `updated_at`) VALUES
('70d173ea-0918-4ff3-9c58-942582c4ef51', 'eac0ff8c-92d9-4313-98ea-e55be0ecbf76', '57ec1474-2a5d-4e7a-9321-30e1fd840e06', 'Hi', 'Greetings', NULL, NULL, 1.0, NULL, 'pending', NULL, NULL, NULL, '2025-10-13 11:27:06', '2025-10-13 11:27:06'),
('8bc4ec53-e4b1-4dfb-8b14-7c3bef652f7d', 'eac0ff8c-92d9-4313-98ea-e55be0ecbf76', '57ec1474-2a5d-4e7a-9321-30e1fd840e06', 'Banking Research', 'Please Help', '2025-10-21', '01:01:00', 1.0, 5.00, 'pending', NULL, NULL, NULL, '2025-10-13 11:28:54', '2025-10-13 11:28:54'),
('2bf640ba-3545-478c-9649-f86636e5b663', '9226c3f5-c2f2-4bf5-9685-7629a2a60c7c', '57ec1474-2a5d-4e7a-9321-30e1fd840e06', 'Need Your Contacts', 'Can i plse have your contact', NULL, NULL, 1.0, NULL, 'pending', NULL, NULL, NULL, '2025-10-13 13:46:07', '2025-10-13 13:46:07');

-- --------------------------------------------------------

--
-- Table structure for table `donations`
--

DROP TABLE IF EXISTS `donations`;
CREATE TABLE IF NOT EXISTS `donations` (
  `id` varchar(36) NOT NULL,
  `donor_name` varchar(255) NOT NULL,
  `donor_email` varchar(255) NOT NULL,
  `donor_phone` varchar(20) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `donor_msg` text DEFAULT NULL,
  `status` enum('pending','completed','failed') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `donations`
--

INSERT INTO `donations` (`id`, `donor_name`, `donor_email`, `donor_phone`, `amount`, `donor_msg`, `status`, `created_at`, `updated_at`) VALUES
('1293cc16-4673-46b0-9917-8e633554ae07', 'Vado Vady', 'obacheisaac@gmail.com', '', 50.00, '', 'pending', '2025-11-17 13:32:37', '2025-11-17 13:32:37'),
('2ef15ca8-0656-4be1-bf16-b97a6ac8b192', 'Vado opac', 'obacheisaac@gmail.com', '0773089254', 50.00, '', 'pending', '2025-11-15 06:35:05', '2025-11-15 06:35:05'),
('4156a217-9220-4137-a330-d672ea56a505', 'Charlie Boy', 'vady@gmail.com', '0773089254', 50.00, '', 'pending', '2025-11-15 06:44:27', '2025-11-15 06:44:27'),
('6e286a7d-5291-4df6-b26b-a9164da29ea1', 'Vado Vady', 'obacheisaac@gmail.com', '0773089254', 25.00, '', 'pending', '2025-11-15 06:31:15', '2025-11-15 06:31:15'),
('991c9cdb-d916-48c2-85db-427f4b7028d6', 'Vado Vady', 'obacheisaac@gmail.com', '0773089254', 25.00, '', 'pending', '2025-11-15 05:59:21', '2025-11-15 05:59:21'),
('bb52dec7-24d0-43b3-be71-49265e0a2654', 'Vado opac', 'vady@gmail.com', '', 50.00, '', 'pending', '2025-11-15 06:39:20', '2025-11-15 06:39:20'),
('c8d4750b-3eb1-40c1-9cba-db83d557b0ae', 'Vado opac', 'obacheisaac@gmail.com', '', 50.00, '', 'pending', '2025-11-15 06:36:59', '2025-11-15 06:36:59'),
('d2723ad2-c44d-4849-a665-c3e3d58603d1', 'Charlie Boy', 'obacheisaac@gmail.com', '', 50.00, '', 'pending', '2025-11-16 04:09:48', '2025-11-16 04:09:48'),
('f6520809-803b-4851-85e8-04c878dcfa5e', 'Charlie 2 Vady', 'obacheisaac@gmail.com', '', 50.00, '', 'pending', '2025-11-17 18:55:31', '2025-11-17 18:55:31');

-- --------------------------------------------------------

--
-- Table structure for table `experts`
--

DROP TABLE IF EXISTS `experts`;
CREATE TABLE IF NOT EXISTS `experts` (
  `id` varchar(36) NOT NULL,
  `user_id` varchar(36) NOT NULL,
  `title` varchar(100) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `education` text DEFAULT NULL,
  `experience_years` int(11) DEFAULT 0,
  `hourly_rate` decimal(10,2) DEFAULT 0.00,
  `profile_image` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected','suspended') DEFAULT 'pending',
  `rating` decimal(3,2) DEFAULT 0.00,
  `total_reviews` int(11) DEFAULT 0,
  `total_consultations` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `experts`
--

INSERT INTO `experts` (`id`, `user_id`, `title`, `first_name`, `last_name`, `email`, `phone`, `bio`, `education`, `experience_years`, `hourly_rate`, `profile_image`, `status`, `rating`, `total_reviews`, `total_consultations`, `created_at`, `updated_at`) VALUES
('57ec1474-2a5d-4e7a-9321-30e1fd840e06', 'eac0ff8c-92d9-4313-98ea-e55be0ecbf76', 'Dr.', 'Vado', 'Chaja', 'vady@gmail.com', '0773089254', 'Proff in A.i Digital Research', 'Phd', 1, 5.00, NULL, 'approved', 0.00, 0, 0, '2025-10-13 06:11:44', '2025-10-13 06:14:48');

-- --------------------------------------------------------

--
-- Table structure for table `expert_availability`
--

DROP TABLE IF EXISTS `expert_availability`;
CREATE TABLE IF NOT EXISTS `expert_availability` (
  `id` varchar(36) NOT NULL,
  `expert_id` varchar(36) NOT NULL,
  `day_of_week` enum('monday','tuesday','wednesday','thursday','friday','saturday','sunday') NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `is_available` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `expert_id` (`expert_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expert_categories`
--

DROP TABLE IF EXISTS `expert_categories`;
CREATE TABLE IF NOT EXISTS `expert_categories` (
  `id` varchar(36) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `expert_categories`
--

INSERT INTO `expert_categories` (`id`, `name`, `description`, `created_at`) VALUES
('cat-1', 'Business & Finance', 'Business strategy, financial analysis, market research', '2025-10-13 05:19:17'),
('cat-2', 'Technology', 'Software development, AI, data science, cybersecurity', '2025-10-13 05:19:17'),
('cat-3', 'Science & Medicine', 'Research, healthcare, pharmaceuticals, biotechnology', '2025-10-13 05:19:17'),
('cat-4', 'Marketing & Sales', 'Digital marketing, sales strategy, brand management', '2025-10-13 05:19:17'),
('cat-5', 'Legal & Compliance', 'Legal advice, regulatory compliance, intellectual property', '2025-10-13 05:19:17'),
('cat-6', 'Education & Training', 'Academic research, curriculum development, training programs', '2025-10-13 05:19:17');

-- --------------------------------------------------------

--
-- Table structure for table `expert_category_assignments`
--

DROP TABLE IF EXISTS `expert_category_assignments`;
CREATE TABLE IF NOT EXISTS `expert_category_assignments` (
  `id` varchar(36) NOT NULL,
  `expert_id` varchar(36) NOT NULL,
  `category_id` varchar(36) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `expert_id` (`expert_id`),
  KEY `category_id` (`category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `expert_category_assignments`
--

INSERT INTO `expert_category_assignments` (`id`, `expert_id`, `category_id`, `created_at`) VALUES
('2e8c3e57-f7c4-47ce-a6bb-ffaddf9d9e59', '57ec1474-2a5d-4e7a-9321-30e1fd840e06', 'cat-1', '2025-10-13 06:11:44');

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
-- Table structure for table `expert_reviews`
--

DROP TABLE IF EXISTS `expert_reviews`;
CREATE TABLE IF NOT EXISTS `expert_reviews` (
  `id` varchar(36) NOT NULL,
  `expert_id` varchar(36) NOT NULL,
  `user_id` varchar(36) NOT NULL,
  `consultation_id` varchar(36) DEFAULT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `review_text` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `expert_id` (`expert_id`),
  KEY `user_id` (`user_id`),
  KEY `consultation_id` (`consultation_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expert_specialties`
--

DROP TABLE IF EXISTS `expert_specialties`;
CREATE TABLE IF NOT EXISTS `expert_specialties` (
  `id` varchar(36) NOT NULL,
  `expert_id` varchar(36) NOT NULL,
  `specialty` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `expert_id` (`expert_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `expert_specialties`
--

INSERT INTO `expert_specialties` (`id`, `expert_id`, `specialty`, `created_at`) VALUES
('b2c708c7-f2ea-4cb3-9906-8e06328cce4b', '57ec1474-2a5d-4e7a-9321-30e1fd840e06', 'Research', '2025-10-13 06:11:44');

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
('3aa70918-d760-4ba5-af48-42e96fa9f746', '38e42dc8-d5e7-47ae-aff2-9d46375e22ea', 'TXN517952', 'mobile money - 808', '', '01', '2025-11-04 15:33:20', '2025-11-17 18:53:52'),
('61f9f39b-01e2-49d2-bf08-e8054d748c39', '2fd9aa27-c99e-4ab8-9984-806c206d3221', 'TXN346225', '', '', '00', '2025-11-07 12:56:40', '2025-11-17 15:24:49'),
('594a79dd-c3d9-4a3d-b7d2-4152ace2c54f', '165ddadd-48ad-46aa-840b-26617bb7f1f2', 'TXN091448', '', '', '00', '2025-11-16 09:32:03', '2025-11-16 13:22:43'),
('1326766e-4922-4b7e-9daf-bdf48d8c6be5', '1293cc16-4673-46b0-9917-8e633554ae07', 'TXN668009', '', '', '00', '2025-11-17 16:53:21', '2025-11-17 16:53:21'),
('638c9f94-8d00-424d-a6df-06be4362876b', 'f6520809-803b-4851-85e8-04c878dcfa5e', 'TXN229624', '', '', '00', '2025-11-17 18:55:44', '2025-11-17 18:55:44');

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
('53651698-f7b0-442a-b5d3-5a772aab76d6', 'List of Hotels', 'CiB', 'All Details of Banks Listed in Uganda', 3.00, '', 'Academic Research', 'published', 'uploads/68922a0732a4f_Commercial Banks in Uganda (2023) & location.pdf', 3, '2025-08-05 12:57:59', '2025-08-05 15:57:59', '2025-08-05 12:57:59', '903.26 KB', '.pdf'),
('b693f2b4-157d-4fc1-b1c8-cc044c9c05a3', 'Report A', 'By A', 'Test', 12.00, '', 'Academic Research', 'rejected', 'uploads/6892d070cd5e4_Commercial Banks in Uganda (2023) & location.docx', 1, '2025-08-06 00:48:00', '2025-08-06 03:48:00', '2025-08-06 00:48:00', '905.53 KB', 'docx');

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
('38e42dc8-d5e7-47ae-aff2-9d46375e22ea', 'eac0ff8c-92d9-4313-98ea-e55be0ecbf76', 'e513815d-313b-11f0-9c0e-68f72840d364', 79.99, 'pending', '2025-11-17 18:44:47', '2025-11-04 15:33:01'),
('2fd9aa27-c99e-4ab8-9984-806c206d3221', 'eac0ff8c-92d9-4313-98ea-e55be0ecbf76', '53651698-f7b0-442a-b5d3-5a772aab76d6', 3.00, 'pending', '2025-11-17 15:24:39', '2025-11-07 12:56:22'),
('165ddadd-48ad-46aa-840b-26617bb7f1f2', 'eac0ff8c-92d9-4313-98ea-e55be0ecbf76', 'e540f107-313b-11f0-9c0e-68f72840d364', 59.99, 'pending', '2025-11-16 08:58:02', '2025-11-16 08:58:02');

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
  `id` varchar(36) NOT NULL,
  `subscriber_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `plan` enum('basic','standard','premium') NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` enum('pending','active','expired','cancelled') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_active_subscription` (`email`,`status`),
  KEY `idx_email` (`email`),
  KEY `idx_status` (`status`),
  KEY `idx_dates` (`start_date`,`end_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
('f76ffa79-a566-4488-89f4-8d17217c9074', 'user', 'user@example.123', '$2y$10$MHKFrDXd6hp/c1tXO.EkQ.NVG650omql2D5j6eRYpC0jTbTZYGWRm', '', '', '2025-08-01 02:02:21', '2025-09-20 01:59:07'),
('9226c3f5-c2f2-4bf5-9685-7629a2a60c7c', 'Mava', 'workusent@gmail.com', '$2y$10$xT9cLPL1F4u.S7BeoowTkeVJXsaUsh7aRB1pEsnc6eRpXcTpT34um', '01', '5b98755aaaa00e3070267468bc3b0fc1ce4ee354dfebca9ab6c96984abc99032', '2025-10-13 13:36:55', '2025-10-13 13:39:14'),
('d914bb0c-6dcb-4b21-8517-a07b7157ca5e', 'obache', 'obacheis@gmail.com', '$2y$10$ouRWkbtwF.7Do1SWs7iowOI84ddx1Gx81gLtmENuEDIhhGRf8s99G', '00', '97ad2200afc7e6091491257b203e031fc1a5544b6f53c5de08bc93d4a08d9bf9', '2025-11-08 05:19:51', '2025-11-08 05:19:51');

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
('a6fa7e41-c536-425f-be76-0ed5aa1ed329', '550e8400-e29b-41d4-a716-446655440002', 'login', 'User logged in', '2025-10-11 22:06:14'),
('1b61b088-c5be-4d50-bfb2-c46b11920bc0', 'eac0ff8c-92d9-4313-98ea-e55be0ecbf76', 'login', 'User logged in', '2025-10-13 06:06:35'),
('13476d9b-ed0e-4e00-a5c1-544062d38f5e', 'eac0ff8c-92d9-4313-98ea-e55be0ecbf76', 'login', 'User logged in', '2025-10-13 11:20:48'),
('0cd9316d-d329-412a-bef7-0c2f57150785', '550e8400-e29b-41d4-a716-446655440002', 'login', 'User logged in', '2025-10-13 11:30:42'),
('676332d8-c664-4cb0-883c-54aa3b769708', 'eac0ff8c-92d9-4313-98ea-e55be0ecbf76', 'login', 'User logged in', '2025-10-13 13:31:16'),
('11dbe751-f8c3-4252-8c1e-38a1e7167d92', '9226c3f5-c2f2-4bf5-9685-7629a2a60c7c', 'login', 'User logged in', '2025-10-13 13:39:37'),
('d2355356-3d28-4d32-a308-2aff71c01b2a', '550e8400-e29b-41d4-a716-446655440002', 'login', 'User logged in', '2025-10-13 17:25:54'),
('cf572426-52a3-4992-a632-fad8bd300852', '550e8400-e29b-41d4-a716-446655440002', 'login', 'User logged in', '2025-10-13 17:26:53'),
('b665b19c-52c8-464b-b663-82a905f154d7', '550e8400-e29b-41d4-a716-446655440002', 'login', 'User logged in', '2025-10-14 12:16:15'),
('95abd2e0-9a61-4016-958b-ac0c1abc354d', '550e8400-e29b-41d4-a716-446655440002', 'login', 'User logged in', '2025-10-14 12:16:24'),
('b0b69d3a-c7b1-440c-bfdf-23e747c4fba4', 'eac0ff8c-92d9-4313-98ea-e55be0ecbf76', 'login', 'User logged in', '2025-10-14 20:09:59'),
('945c4911-1b2e-4015-910c-1eda206c7c0a', 'eac0ff8c-92d9-4313-98ea-e55be0ecbf76', 'login', 'User logged in', '2025-11-03 12:14:41'),
('3b72743c-9e17-4b8e-b4c6-ca9708fd9a9f', 'eac0ff8c-92d9-4313-98ea-e55be0ecbf76', 'login', 'User logged in', '2025-11-03 14:07:17'),
('a252ea6b-6e3b-41ab-bf1f-49221eb81c39', '550e8400-e29b-41d4-a716-446655440002', 'login', 'User logged in', '2025-11-03 15:48:32'),
('246147bc-4a3f-480f-b0f8-4dfc21cc50a8', 'eac0ff8c-92d9-4313-98ea-e55be0ecbf76', 'login', 'User logged in', '2025-11-04 15:31:29'),
('c56e666f-1289-48fd-8010-30563671a74a', '550e8400-e29b-41d4-a716-446655440002', 'login', 'User logged in', '2025-11-04 15:34:19'),
('0454d046-6e52-4d81-9447-c85fb196bbd7', '550e8400-e29b-41d4-a716-446655440002', 'login', 'User logged in', '2025-11-06 13:43:44'),
('c112cf9b-64bc-4ebf-b791-35d8f2bd4cfa', '550e8400-e29b-41d4-a716-446655440002', 'login', 'User logged in', '2025-11-07 01:29:38'),
('8472ba83-2daa-4d9a-92de-1534667640e4', 'eac0ff8c-92d9-4313-98ea-e55be0ecbf76', 'login', 'User logged in', '2025-11-07 12:55:07'),
('2b23df09-718d-4218-ad9b-c5d53988c294', 'eac0ff8c-92d9-4313-98ea-e55be0ecbf76', 'login', 'User logged in', '2025-11-08 05:02:01'),
('84482f7b-7022-4f63-95ff-793173c36401', 'eac0ff8c-92d9-4313-98ea-e55be0ecbf76', 'login', 'User logged in', '2025-11-08 05:08:43'),
('9ed062b9-7b37-4c4b-8537-5280b58d26b9', '550e8400-e29b-41d4-a716-446655440002', 'login', 'User logged in', '2025-11-08 05:13:00'),
('6ae67b82-0b1d-4a6e-bf41-74c1ce7e4c35', '550e8400-e29b-41d4-a716-446655440002', 'login', 'User logged in', '2025-11-08 05:20:23'),
('fdb59aa6-07ca-4465-8f43-aa2fe7a1a8e2', 'eac0ff8c-92d9-4313-98ea-e55be0ecbf76', 'login', 'User logged in', '2025-11-08 08:36:35'),
('2e6bd731-93b7-4299-86f3-ff4653e62dc2', 'eac0ff8c-92d9-4313-98ea-e55be0ecbf76', 'login', 'User logged in', '2025-11-08 08:36:36'),
('3a0dd6c5-f79b-4db2-870e-86a9447b40ab', 'eac0ff8c-92d9-4313-98ea-e55be0ecbf76', 'login', 'User logged in', '2025-11-15 06:43:43'),
('c88400fa-24a5-464f-9f21-7e79d0ed5c3f', 'eac0ff8c-92d9-4313-98ea-e55be0ecbf76', 'login', 'User logged in', '2025-11-17 13:34:37'),
('ad29b6fe-d596-4f76-b616-005c39d0564c', 'eac0ff8c-92d9-4313-98ea-e55be0ecbf76', 'login', 'User logged in', '2025-11-17 21:32:01');

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
