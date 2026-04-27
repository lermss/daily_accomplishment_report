-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 27, 2026 at 07:50 AM
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
-- Database: `db_darsystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `event` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `details` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `event`, `description`, `details`, `created_at`, `updated_at`) VALUES
(1, 1, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-19 18:26:50', '2026-04-19 18:26:50'),
(2, 1, 'user_updated', 'user_updated', 'Authorized Google Authenticator setup for grantarachea09@gmail.com.', 'Authorized Google Authenticator setup for grantarachea09@gmail.com.', '2026-04-19 18:27:50', '2026-04-19 18:27:50'),
(3, 1, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-19 21:04:55', '2026-04-19 21:04:55'),
(4, 1, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-19 21:06:06', '2026-04-19 21:06:06'),
(5, 1, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-19 21:07:09', '2026-04-19 21:07:09'),
(6, 1, 'user_updated', 'user_updated', 'Authorized Google Authenticator setup for grantarachea09@gmail.com.', 'Authorized Google Authenticator setup for grantarachea09@gmail.com.', '2026-04-19 21:07:35', '2026-04-19 21:07:35'),
(7, 1, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-19 21:17:47', '2026-04-19 21:17:47'),
(8, 1, 'user_updated', 'user_updated', 'Authorized Google Authenticator setup for grantarachea09@gmail.com.', 'Authorized Google Authenticator setup for grantarachea09@gmail.com.', '2026-04-19 21:18:08', '2026-04-19 21:18:08'),
(9, 1, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-19 21:18:31', '2026-04-19 21:18:31'),
(10, 1, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-19 21:36:48', '2026-04-19 21:36:48'),
(11, 1, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-19 21:36:59', '2026-04-19 21:36:59'),
(12, 1, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-19 21:37:29', '2026-04-19 21:37:29'),
(13, 1, 'user_updated', 'user_updated', 'Provisioned Google Authenticator access for grantarachea09@gmail.com.', 'Provisioned Google Authenticator access for grantarachea09@gmail.com.', '2026-04-19 21:37:38', '2026-04-19 21:37:38'),
(14, 1, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-19 21:38:17', '2026-04-19 21:38:17'),
(15, 2, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-19 21:38:37', '2026-04-19 21:38:37'),
(16, 2, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-19 21:39:29', '2026-04-19 21:39:29'),
(17, 1, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-19 21:46:21', '2026-04-19 21:46:21'),
(18, 1, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-19 22:01:20', '2026-04-19 22:01:20'),
(19, 1, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-19 22:01:32', '2026-04-19 22:01:32'),
(20, 2, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-19 22:01:57', '2026-04-19 22:01:57'),
(21, 1, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-19 22:06:04', '2026-04-19 22:06:04'),
(22, 2, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-19 22:14:51', '2026-04-19 22:14:51'),
(23, 2, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-19 22:20:51', '2026-04-19 22:20:51'),
(24, 2, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-19 22:37:30', '2026-04-19 22:37:30'),
(25, 2, 'profile_updated', 'profile_updated', 'Updated personal profile.', 'Updated personal profile.', '2026-04-19 22:46:38', '2026-04-19 22:46:38'),
(26, 2, 'profile_updated', 'profile_updated', 'Updated personal profile.', 'Updated personal profile.', '2026-04-19 22:46:49', '2026-04-19 22:46:49'),
(27, 2, 'profile_updated', 'profile_updated', 'Updated personal profile.', 'Updated personal profile.', '2026-04-19 22:46:58', '2026-04-19 22:46:58'),
(28, 2, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-19 22:54:25', '2026-04-19 22:54:25'),
(29, 1, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-19 22:54:51', '2026-04-19 22:54:51'),
(30, 1, 'user_created', 'user_created', 'Created user account for asda@gmail.com.', 'Created user account for asda@gmail.com.', '2026-04-19 22:56:50', '2026-04-19 22:56:50'),
(31, 2, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-19 23:01:21', '2026-04-19 23:01:21'),
(32, 1, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-19 23:02:05', '2026-04-19 23:02:05'),
(33, 2, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-19 23:02:38', '2026-04-19 23:02:38'),
(34, 2, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-19 23:03:30', '2026-04-19 23:03:30'),
(35, 1, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-19 23:06:04', '2026-04-19 23:06:04'),
(36, 1, 'user_created', 'user_created', 'Created user account for felz092003@gmail.com.', 'Created user account for felz092003@gmail.com.', '2026-04-19 23:07:14', '2026-04-19 23:07:14'),
(37, 1, 'user_updated', 'user_updated', 'Provisioned Google Authenticator access for felz092003@gmail.com.', 'Provisioned Google Authenticator access for felz092003@gmail.com.', '2026-04-19 23:07:30', '2026-04-19 23:07:30'),
(38, 1, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-19 23:08:33', '2026-04-19 23:08:33'),
(39, 2, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-19 23:09:32', '2026-04-19 23:09:32'),
(40, 2, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-19 23:10:14', '2026-04-19 23:10:14'),
(41, 4, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-19 23:10:36', '2026-04-19 23:10:36'),
(42, 4, 'profile_updated', 'profile_updated', 'Updated personal profile.', 'Updated personal profile.', '2026-04-19 23:11:18', '2026-04-19 23:11:18'),
(43, 4, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-19 23:12:27', '2026-04-19 23:12:27'),
(44, 2, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-19 23:12:51', '2026-04-19 23:12:51'),
(45, 2, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-19 23:13:52', '2026-04-19 23:13:52'),
(46, 4, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-19 23:14:51', '2026-04-19 23:14:51'),
(47, 4, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-19 23:38:02', '2026-04-19 23:38:02'),
(48, 2, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-19 23:38:31', '2026-04-19 23:38:31'),
(49, 2, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-19 23:40:35', '2026-04-19 23:40:35'),
(50, 4, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-19 23:40:53', '2026-04-19 23:40:53'),
(51, 4, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-20 01:14:09', '2026-04-20 01:14:09'),
(52, 4, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-20 01:14:46', '2026-04-20 01:14:46'),
(53, 4, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-20 01:25:03', '2026-04-20 01:25:03'),
(54, 2, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-20 01:25:26', '2026-04-20 01:25:26'),
(55, 1, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-26 16:42:02', '2026-04-26 16:42:02'),
(56, 1, 'user_created', 'user_created', 'Created user account for jesusllamas052@gmail.com.', 'Created user account for jesusllamas052@gmail.com.', '2026-04-26 16:43:11', '2026-04-26 16:43:11'),
(57, 1, 'user_updated', 'user_updated', 'Provisioned Google Authenticator access for jesusllamas052@gmail.com.', 'Provisioned Google Authenticator access for jesusllamas052@gmail.com.', '2026-04-26 16:43:39', '2026-04-26 16:43:39'),
(58, 1, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-26 16:45:11', '2026-04-26 16:45:11'),
(59, 5, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-26 16:45:19', '2026-04-26 16:45:19'),
(60, 5, 'user_created', 'user_created', 'Created user account for kuroky281@gmail.com.', 'Created user account for kuroky281@gmail.com.', '2026-04-26 16:48:11', '2026-04-26 16:48:11'),
(61, 5, 'user_updated', 'user_updated', 'Provisioned Google Authenticator access for kuroky281@gmail.com.', 'Provisioned Google Authenticator access for kuroky281@gmail.com.', '2026-04-26 16:49:16', '2026-04-26 16:49:16'),
(62, 6, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-26 16:50:26', '2026-04-26 16:50:26'),
(63, 6, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-26 16:52:49', '2026-04-26 16:52:49'),
(64, 5, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-26 16:53:18', '2026-04-26 16:53:18'),
(65, 5, 'user_created', 'user_created', 'Created user account for emmanlabwork@gmail.com.', 'Created user account for emmanlabwork@gmail.com.', '2026-04-26 17:03:30', '2026-04-26 17:03:30'),
(66, 5, 'user_updated', 'user_updated', 'Provisioned Google Authenticator access for emmanlabwork@gmail.com.', 'Provisioned Google Authenticator access for emmanlabwork@gmail.com.', '2026-04-26 17:05:03', '2026-04-26 17:05:03'),
(67, 7, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-26 17:06:08', '2026-04-26 17:06:08'),
(68, 5, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-26 18:34:41', '2026-04-26 18:34:41'),
(69, 7, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-26 18:35:38', '2026-04-26 18:35:38'),
(70, 6, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-26 18:40:12', '2026-04-26 18:40:12'),
(71, 7, 'profile_updated', 'profile_updated', 'Updated personal profile.', 'Updated personal profile.', '2026-04-26 19:20:08', '2026-04-26 19:20:08'),
(72, 5, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-26 21:16:53', '2026-04-26 21:16:53'),
(73, 5, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-26 21:43:21', '2026-04-26 21:43:21'),
(74, 5, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-26 21:43:40', '2026-04-26 21:43:40');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` bigint(20) NOT NULL
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
(1, '2026_03_10_031857_create_users_table', 1),
(2, '2026_03_10_031909_create_reports_table', 1),
(3, '2026_03_10_031918_create_activity_logs_table', 1),
(4, '2026_03_10_065324_add_status_to_users_table', 1),
(5, '2026_03_12_130000_add_profile_fields_to_users_table', 1),
(6, '2026_03_12_130100_add_event_fields_to_activity_logs_table', 1),
(7, '2026_03_12_131000_add_dashboard_fields_to_reports_table', 1),
(8, '2026_03_12_140000_add_profile_asset_fields_to_users_table', 1),
(9, '2026_03_18_142502_create_sessions_table', 1),
(10, '2026_03_18_143312_create_cache_table', 1),
(11, '2026_03_18_143451_create_jobs_table', 1),
(12, '2026_03_18_153906_create_report_entries_table', 1),
(13, '2026_03_24_062325_add_user_avatar_path_to_users_table', 1),
(14, '2026_03_24_120000_add_notifications_read_at_to_users_table', 1),
(15, '2026_03_25_090000_add_review_comment_to_reports_table', 1),
(16, '2026_03_29_000001_add_google2fa_fields_to_users_table', 1),
(17, '2026_04_01_100000_add_separate_name_columns_to_users_table', 1),
(18, '2026_04_14_000001_add_assigned_provincial_head_id_to_reports_table', 1),
(19, '2026_04_18_184117_add_is_hidden_from_staff_dashboard_to_reports_table', 1),
(20, '2026_04_18_193938_add_is_hidden_from_staff_index_to_reports_table', 1),
(21, '2026_04_18_201756_add_is_hidden_from_admin_dashboard_to_reports_table', 1),
(22, '2026_04_19_120000_create_super_admin_notifications_table', 1),
(23, '2026_04_20_100000_add_authenticator_authorization_fields_to_users_table', 2),
(24, '2026_04_20_140000_create_office_reminder_schedules_table', 3),
(25, '2026_04_20_140100_create_office_reminders_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `office_reminders`
--

CREATE TABLE `office_reminders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `office` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `type` varchar(20) NOT NULL,
  `triggered_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `office_reminder_schedule_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `office_reminders`
--

INSERT INTO `office_reminders` (`id`, `office`, `message`, `type`, `triggered_at`, `created_by`, `office_reminder_schedule_id`, `created_at`, `updated_at`) VALUES
(1, 'Pangasinan', 'Reminder for Pangasinan: Please submit your accomplishment report.', 'manual', '2026-04-20 01:23:10', 4, NULL, '2026-04-20 01:23:10', '2026-04-20 01:23:10'),
(2, 'Pangasinan', 'Reminder for Pangasinan: Please submit your accomplishment report.', 'manual', '2026-04-20 01:23:14', 4, NULL, '2026-04-20 01:23:14', '2026-04-20 01:23:14'),
(3, 'Pangasinan', 'Reminder for Pangasinan: Please submit your accomplishment report.', 'manual', '2026-04-20 01:23:15', 4, NULL, '2026-04-20 01:23:15', '2026-04-20 01:23:15');

-- --------------------------------------------------------

--
-- Table structure for table `office_reminder_schedules`
--

CREATE TABLE `office_reminder_schedules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `office` varchar(255) NOT NULL,
  `message` text DEFAULT NULL,
  `send_time` time NOT NULL,
  `is_enabled` tinyint(1) NOT NULL DEFAULT 1,
  `last_sent_on` date DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `office_reminder_schedules`
--

INSERT INTO `office_reminder_schedules` (`id`, `office`, `message`, `send_time`, `is_enabled`, `last_sent_on`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'Pangasinan', 'hello mdafakas 5:25', '17:25:00', 1, NULL, 4, '2026-04-20 01:22:52', '2026-04-20 01:22:52');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `assigned_provincial_head_id` bigint(20) UNSIGNED DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `submitted_at` timestamp NULL DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `reviewed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `review_comment` text DEFAULT NULL,
  `is_hidden_from_staff_dashboard` tinyint(1) NOT NULL DEFAULT 0,
  `is_hidden_from_staff_index` tinyint(1) NOT NULL DEFAULT 0,
  `is_hidden_from_admin_dashboard` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `user_id`, `assigned_provincial_head_id`, `file_name`, `file_path`, `status`, `submitted_at`, `reviewed_at`, `reviewed_by`, `review_comment`, `is_hidden_from_staff_dashboard`, `is_hidden_from_staff_index`, `is_hidden_from_admin_dashboard`, `created_at`, `updated_at`) VALUES
(1, 2, 4, 'April 21 - 22', NULL, 'approved', '2026-04-19 23:13:43', '2026-04-19 23:36:04', 4, NULL, 0, 0, 0, '2026-04-19 22:41:34', '2026-04-19 23:36:04'),
(2, 2, 4, 'April 21 - 24', NULL, 'pending', '2026-04-19 23:39:21', NULL, NULL, NULL, 0, 0, 0, '2026-04-19 23:39:11', '2026-04-19 23:39:21'),
(3, 2, 4, 'April 21 - 24', NULL, 'pending', '2026-04-19 23:39:29', NULL, NULL, NULL, 0, 0, 0, '2026-04-19 23:39:11', '2026-04-19 23:39:29'),
(4, 2, 4, 'April 21 - 24', NULL, 'pending', '2026-04-19 23:40:21', NULL, NULL, NULL, 0, 0, 0, '2026-04-19 23:40:14', '2026-04-19 23:40:21'),
(5, 6, NULL, 'April 27 - 27', NULL, 'draft', NULL, NULL, NULL, NULL, 0, 0, 0, '2026-04-26 21:24:26', '2026-04-26 21:24:26'),
(6, 6, NULL, 'April 28 - 28', NULL, 'draft', NULL, NULL, NULL, NULL, 0, 0, 0, '2026-04-26 21:42:05', '2026-04-26 21:42:05');

-- --------------------------------------------------------

--
-- Table structure for table `report_entries`
--

CREATE TABLE `report_entries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `report_id` bigint(20) UNSIGNED NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `activity` varchar(255) NOT NULL,
  `details` text DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `report_entries`
--

INSERT INTO `report_entries` (`id`, `report_id`, `start_date`, `end_date`, `activity`, `details`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 1, '2026-04-20', '2026-04-21', 'q', 'qq', 'qq', '2026-04-19 22:41:34', '2026-04-19 22:41:34'),
(2, 1, '2026-04-22', '2026-04-23', 'q', 'qq', 'qq', '2026-04-19 22:41:34', '2026-04-19 22:41:34'),
(3, 2, '2026-04-21', '2026-04-23', 'qqq', 'qq', 'w', '2026-04-19 23:39:11', '2026-04-19 23:39:11'),
(4, 2, '2026-04-25', '2026-05-03', 'qw', 'w', 'w', '2026-04-19 23:39:11', '2026-04-19 23:39:11'),
(5, 3, '2026-04-21', '2026-04-23', 'qqq', 'qq', 'w', '2026-04-19 23:39:11', '2026-04-19 23:39:11'),
(6, 3, '2026-04-25', '2026-05-03', 'qw', 'w', 'w', '2026-04-19 23:39:11', '2026-04-19 23:39:11'),
(7, 4, '2026-04-21', '2026-04-23', 'q', 'w', 'w', '2026-04-19 23:40:14', '2026-04-19 23:40:14'),
(8, 4, '2026-04-28', '2026-05-05', 'w', 'rqwr', 'qwrq', '2026-04-19 23:40:14', '2026-04-19 23:40:14'),
(9, 5, '2026-04-27', '2026-04-27', 'DAR System', 'Polishing the DAR System', 'Done', '2026-04-26 21:24:26', '2026-04-26 21:24:26'),
(10, 6, '2026-04-28', '2026-04-28', 'zzz', 'xxx', 'Done', '2026-04-26 21:42:05', '2026-04-26 21:42:05');

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
('6cnLBDNplwBH2OHwDG7pazbuHlSHCBTlLkf25T5I', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'ZXlKcGRpSTZJa1pMVEZOU2VGZFlVM2xMZUhwYVJ6SXphRFZ0U1hjOVBTSXNJblpoYkhWbElqb2lTMlp5Y3psT1ZFTTJhMVp4VkZOWU9VTm5hMnM0V1dWUlkwaHVkWEpRYTNWbFEwRk1Va1l5TDJRclpUQk5Va3gwWjFGb2VraE9ZblZ2YVV4dlZ6ZExUWGQ0TmpkeFluQmtZWE42TmtsdlZHcG1jMk4xVjNSdlNUTndObWswYlRFcmJFOVVSa1F6YTFsMU5GaExURFpRVWxGRGR5dG9TVFJySzFSQlpXZENjVmQ2Y0RWakszSnpXRmRTWmpKQ1RUbDVVa3g0YkhkVlR6aHBlRTVKY0VRclQyZFdOR05YYW0xMWRqQkhjbVl6ZDJ4dFMwdDVhWFZpZEhsTmRqZFBZM2h1UkRoa1lqTTBWVVZzUzI5TmJHcGxVMWR2T0dOT1MzRTNUemQ0ZDJsMWNqbG9NakJVTVVGVGFFUXZja2d6U1ZOSVJEQlBSa295VnpWWlpuaDViRFJvWlV4alNHRkpaeXN6YUZFekwydG1Uakk1TmpJeGRtSndSaXQxYWtFeU9Xc3dNblV6Ym5BMFFrOTBibUZaU0hkU1JsZERNR0ZUTVZseVZUY3JWM1JoVGpSRVJFSXdNRk54TnlzNWVFZHZWR00zYTJWd015OUViMGxUUW1sWFdHODNSRkYwV1RGbGMyWlhjWFZuUFNJc0ltMWhZeUk2SWprM056SmlNRE0yWVdGbFlXUTJZelF5TmpBNFkyUXpOV0kyT0RobE9EWXlZbU16TWpSa01ESTVZMkZrWlRjMVlUUmhabUZpWW1GbU16RXlNamcyTVRNaUxDSjBZV2NpT2lJaWZRPT0=', 1777268614),
('8FYXxvG8Qhpcxhxc7Y2BAzLRKNM8mkuXpxK16YiU', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'ZXlKcGRpSTZJbVpxWXpKNWNtTk5kbXg0TjBOQlozUlhVREpqTDFFOVBTSXNJblpoYkhWbElqb2lOVXRaZUZRMllXeFpieTk1ZW5GaE4yWTJkVk4zWkc5MVRVeDZkMU5SVUhWUlR5ODJVRGxzZFdwM1UwSmlVeTg1WVRScFVIZzVhMnhtUjBNeVlUTklObFpoVTBOc2RIUllTa1o1TkRSdEwzVk5PRTlLU1ZBdlFVeFdZMkptY2xCMWJGTmxTMEpXWm1kVlNUUkVTRTE0Y2tkeWQzRXhWVkJWYzIxUlVUUXZVR2RGVDBwbWVYZGxXa2RQVkhJck1ERnVkMVZEWVdNeFVFRXhjRmRNV2psNE9IQlNZbmw1U0ZkcVFXdFVTMEZFTVRKSWRuVlJZMUpJVVZwd1RsTXJVRlJYUkhWWFVuQk1lVmRPYjJ4VmNYTlhkVkZ5UVU5WGVYRm5kV2xpYlZodVFuQjBNbkp6UlZkdFUxVlRXVzlZYzB0blRtbExWalpsVUhoeU9XMHdSR3hOVWs5NGFqaDJiblV6VWpSTFJ5dGFWVWt3TWxScWFIUmpUR3hCUWpSeFduRjVlR0kyU0ZWaVV5dGlLMEp6VUVGb2RESjNkME5VTkM5UFRsSXdVVFJuVEVwUGVteEtXaTlsVEdoSEswVnlSbGRTU0VwcE1XZHFLM05JUTBSSGJTdG1Xa2RqWTBRelRVOUJkRFFyYm1SbVltMVdkVEJIV2pNNE5USlBXbEkwYjJwMElpd2liV0ZqSWpvaVpqSm1NMlJqTURrMll6UTVNREUyWlRVeE56TTBOemt4TmpBMk16ZGxOalUwTVRKaE9XRmhNalkzWlRoaVpqbGxPREk1TXpFeU9HRmhOalV4TWpJMllpSXNJblJoWnlJNklpSjk=', 1777268625),
('aAzTJrkJYhmJYzmw36ZRni5WSxlebxTlW30GjzfB', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'ZXlKcGRpSTZJamxwYzFWd1duTXpVamRpWTFZeVNIUnZSa1pRYjJjOVBTSXNJblpoYkhWbElqb2lZMjlsVGxCSGFVWkVkRVJ6ZUhrd1IwUmlkVGg0UjBOVldXOUliMmx0VTFNM2VYbGlibmhYWldWblFWUlJSRUZIVVdWTlIxcHpVWEp1TkRKeWFYQXJkSEZOVHl0U016aGxiV3RPYW1wQlVUZFBVQ3RrSzFkWVF5dHVXWGhuTkVSdlNrTmlaMk5vTkZVckwzRjRUbEJtZEhWeFoydG1RbTlzTjNScVNtSlROMUZhU0N0SmVFTmFUbWw1TmtaMFoxSmFiamQwUmtVNGRrSndVVlJrWjNCSFUxWlliVWx4VWs5WFJucEdkSGhCVmtWRU0zcFlSbEpZU2tkdlEzWm5VblJUWm14bFJtaGxkMHB5TmpZMlpFWkNPVEJhUlVsaFFqTllTMHhpZGxGV2RGaHlWa2h6TTA5TVpFdExZMm93VlVoMmJHUmFaaXRYTUdzek5XOW9MekJOUWtvNVprUnNjRFppVGpaSFNqVkdiRFF6VGxOM2VWVklZVVpOVkVZeFZYY3pUQ3Q1YlVGdGJYZ3plVFEzYWt4RVltNUZkbEUwYVhwRkwxaDBNMDFsUVVsM1MwRXZNR1pJUjJWdVlURXhWeXQxYkRGM1ZIWnpWa3RyWkRacVpHVjBWVEl4VW0xTGNHVlRjbkEwUFNJc0ltMWhZeUk2SW1RME5XUmtZelJrWVRFMFpqQXhZekpqTlRjd1lXRTJPV05sTUdaa01EWXdNV0ZrWVdGa1pETXdaR1EyTnpZd09HSmpaRGN6Tm1NeE56UXhPVGN4TnpVaUxDSjBZV2NpT2lJaWZRPT0=', 1777266993),
('bpd4qksdnb6t3usK86oqFUaIL4JZkUGcs6reLVRo', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'ZXlKcGRpSTZJbmx4YkRoaFUyTklXVGxYV21aQk9EbDNSMnQxZVhjOVBTSXNJblpoYkhWbElqb2lTWEpyU2tVdk5XSnFRM2x6VERnMEwzWnNaRXRuTURoUGJHUlBUbE5VUW14dlYyUlRaa2N2VFVkUllteDVWRTFzUlV3MEsyOHhPSFZYTXpOSFdWcFFPWEl3U2xWR1RrVnRSWFJOWTI0NWQzSllLMkpOTTFCTVpWSlplVTUzYm14WlRWZHdRVWhTVlRsemVsaE5jV3c0VkVNck9FcFNXVGRCZVdOMlR6UkZMM0ZDUW5kWU5EbHdNVmcwVURVeFUweFBWVzlOTUVweGVXSnVSVWhpY1RGeWFGRjViM1J6UVhkUU1EUTFRMFZCYWtKR1lURk1SRlJLUjNkM09GRjBNbUpwYkUxUGRFZEJTVU4xU3lzeWRVbHFhRmR0TkVsT2RHdHNjbkpFWjFkTGJGQnNaVGxJYUZNeGJHaG5NM2Q2WjNWbGEycDVTMGN2WlZCc01XVlFWR0Y0UkVWaWNTdE5RazRyTVdWYVRVbEllSGhVWkcxaFdHVlZkM1ZYVkdsTFVrRjBVREJOTWpocWJEVXlWRnB3SzBFclRqZGlUVnBYTDBRMGRIRm1USGx6U2pWeGRFc3lSSEJIU2pVd05rcHJNR1JDUmxOa1IzbEtVaXRsYzBWUGNqVTJTREpLTmxCQ0swdDRjMDVYY2pOcGQwSnhZVEJDTW5sYVVteHBXWFYxV2pOS0lpd2liV0ZqSWpvaVlqYzBPR1l4T1dSak9USm1NelZpWWpJeE1UVXpOekpqTlRnME1qWTVZall3WmpjMU1qWmhZMk5tTkdJNE56SmpNakJrT1RBMllXWXlZalJpTVdSaE55SXNJblJoWnlJNklpSjk=', 1777268750),
('gO8vcUfDFHCMwwMVUfWak1tH7zQISROmnu1p2uYw', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'ZXlKcGRpSTZJalZDVEM5WmVFNTJZbE12ZVhGNWVtWnVUVmh0VG1jOVBTSXNJblpoYkhWbElqb2lhblV4VjFsVVZUZzRaMmN2SzNwTmJIVlBhR1ZyT1hKb1RVbHRMMHhQTkhCbmExUk5Ua0ZzV0ZwSlVYaHRiRlk1Y2s1QlZsQjBkSEpwT1VkaVIwbFBOR0ozVDFaUGFsTkhNMDVwTm5vdlVHcEVlVlJpWmtvekswTTBTVGhITVVGNWFGaEVlSGR0YWtkVVdVNHhWU3RLU1VGNVRWZzNPV1ZrTkVSV2JWZHZhRU5MU1hwdE9FVnpOSHBSVkZRNGJsbGhSbE5HVkdoVmEzZHdWM0ZPTUVOTlJIWXpSRTFDY1ZSeldsUlZlWGRNTUc4d2FYcGpORTVaZEcxVk5uRTNNVEpTV1RsNVZrVlVkVEl2WkZOaGNIZG9TRWhhTURCMk9VVnVOak51ZWxkdGVHdHBTMlZtUkhwTmJrVnZObXRZY1ZjdlNGb3pXRnBTUkdGNFNtdzNOU3R3ZWxSb1JTOXFkSGd4T1hoblZXVTNUMGRTTjA1eEx6SmpZMlZRYlhSRk9Vd3laM1p4WlhKemRWWmhWRXNyVG5CRGRucGxaV0V5TVd0aVN6WnVkbnAyWjA1b1dtSlROa1pQTDFKUk5tdHVLM0JIUTBwblpqVkVVbTEyT1dWak1VNHZhMnBqTWsxS0wwSklXa0ZyUFNJc0ltMWhZeUk2SWpZNU1UUXpNRFEzWkdFMU1EbG1ObUV3TmpWaVkyWTVNVEl6WWpJeVpHTTBNemd5Tm1KaE5HUXpPRGs0WVRObVlqWXhOakk1WXpJM05XRmxNREEzWXpJaUxDSjBZV2NpT2lJaWZRPT0=', 1777268630),
('kv1E25XwfynwveNRChnQWgxFbeSZNHRYaS3KFW8u', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'ZXlKcGRpSTZJbFZ4VUV3dk1tbE5TV0ZyWVRrNWJFVkVTR3RYTkdjOVBTSXNJblpoYkhWbElqb2lXRGg2TkVkUlZXNTFaRFJIYm1GUU9VeFNNVXhVYmsxelJEaHZURFpxYkdWMGFVeElTMGhsVFdjdlJsTlJkbXhrZUdObWN6WmxjeTg1VERCSllVNWpWMVJRWjBGSVdVNUlkV3B6ZW1zM1RrbFJURWxoTTJwcVJVOXBOelZJVkRWM04ySktTa0ZxVjBoc2FtWnNVVEJMUmxkbVdITnBVVXcwZFdOd1JVSmtPRVZQWW1jeFVGZFVVbWM1WTNsamQweG9ORFJoVjJkQmNUWmpZVlppVTNWQlNUbENiM2R3Ym1OeFIxcDFkMDgzVmk5NU1XcGlWRXBQVUV3dmQzQXJLeko1WkZwUlIwbE1NMHRoTXpsM0syMWFaa05VTlZaS1l5dFpkR3hJUlVWclNUQk9SVXhZWjNSU0syWmpPVk5PVFRjd01FNUhNM2hqYml0MVEyMTNlV0l3ZUVwbmRscHlNbTVZVERKRU5rMVNZbXhWVFRWdFVuTnBZMEoxY0djMlZFazFkRnBvVTNaRk9UVTFUM1ZNYXpZMWRsY3dMMGRXVUd4c2VsaDVhSEJEYTA1VE5rZDZlVEYxZG1WVWIwWkhiRzVxUVU5WFdXMW1aQ3M1YUc5Tk9GTndhVGg2WjFGV1V6UnZObE5uUFNJc0ltMWhZeUk2SWpGaE56WmlNbUl5TURnMVpUZ3pNekZqTWprM09HWTBOREF5TlRRMlltVmtZMlF3TlRnNVpUTTJNakZoTjJFM1ltRmlZMlUwTkRSaE56azVZMk5tTXpraUxDSjBZV2NpT2lJaWZRPT0=', 1777268581);

-- --------------------------------------------------------

--
-- Table structure for table `super_admin_notifications`
--

CREATE TABLE `super_admin_notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `source_key` varchar(255) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `type` varchar(20) NOT NULL,
  `read_status` tinyint(1) NOT NULL DEFAULT 0,
  `read_at` timestamp NULL DEFAULT NULL,
  `action_label` varchar(255) DEFAULT NULL,
  `action_url` varchar(255) DEFAULT NULL,
  `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `super_admin_notifications`
--

INSERT INTO `super_admin_notifications` (`id`, `source_key`, `title`, `message`, `type`, `read_status`, `read_at`, `action_label`, `action_url`, `meta`, `created_at`, `updated_at`) VALUES
(1, 'daily-summary:2026-04-20', 'Daily report summary', 'Today: 1 submitted, 0 approved, and 0 still pending.', 'INFO', 0, NULL, 'View Details', 'http://127.0.0.1:8000/dashboard/super-admin/reports', '{\"pending_today\": 0, \"approved_today\": 0, \"submitted_today\": 1}', '2026-04-19 23:08:26', '2026-04-19 23:08:26'),
(2, 'pending-reports-summary', '3 reports pending review', 'There are currently 3 report(s) waiting for review. Latest submission was 6 days ago.', 'REVIEW', 0, NULL, 'Review Now', 'http://127.0.0.1:8000/dashboard/super-admin/reports/pending', '{\"pending_count\":3,\"latest_pending_at\":\"2026-04-20 07:40:21\"}', '2026-04-26 16:42:03', '2026-04-26 16:42:03'),
(3, 'daily-summary:2026-04-27', 'Daily report summary', 'Today: 2 submitted, 0 approved, and 0 still pending.', 'INFO', 0, NULL, 'View Details', 'http://127.0.0.1:8000/dashboard/super-admin/reports', '{\"submitted_today\":2,\"approved_today\":0,\"pending_today\":0}', '2026-04-26 21:42:09', '2026-04-26 21:42:09');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL DEFAULT '',
  `middle_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'staff',
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `is_authorized` tinyint(1) NOT NULL DEFAULT 0,
  `department` varchar(255) DEFAULT NULL,
  `position` varchar(255) DEFAULT NULL,
  `project` varchar(255) DEFAULT NULL,
  `bureau` varchar(255) DEFAULT NULL,
  `division` varchar(255) DEFAULT NULL,
  `office` varchar(255) DEFAULT NULL,
  `institution` varchar(255) DEFAULT NULL,
  `avatar_path` varchar(255) DEFAULT NULL,
  `signature_path` varchar(255) DEFAULT NULL,
  `otp_hash` varchar(255) DEFAULT NULL,
  `google2fa_secret` text DEFAULT NULL,
  `google2fa_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `google2fa_authorization_code_hash` text DEFAULT NULL,
  `google2fa_authorization_code_expires_at` timestamp NULL DEFAULT NULL,
  `google2fa_authorization_sent_at` timestamp NULL DEFAULT NULL,
  `google2fa_authorized_by` bigint(20) UNSIGNED DEFAULT NULL,
  `google2fa_authorized_at` timestamp NULL DEFAULT NULL,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `otp_code` varchar(255) DEFAULT NULL,
  `otp_expiration` timestamp NULL DEFAULT NULL,
  `notifications_read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_avatar_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `first_name`, `middle_name`, `last_name`, `email`, `password`, `role`, `status`, `is_authorized`, `department`, `position`, `project`, `bureau`, `division`, `office`, `institution`, `avatar_path`, `signature_path`, `otp_hash`, `google2fa_secret`, `google2fa_enabled`, `google2fa_authorization_code_hash`, `google2fa_authorization_code_expires_at`, `google2fa_authorization_sent_at`, `google2fa_authorized_by`, `google2fa_authorized_at`, `two_factor_confirmed_at`, `otp_code`, `otp_expiration`, `notifications_read_at`, `created_at`, `updated_at`, `user_avatar_path`) VALUES
(1, 'Grant Arachea', 'Grant', NULL, 'Arachea', 'grantarachea@gmail.com', '$2y$12$eZtILWwNz77ROKTTExxr6OjLMQdbt1lYfrR2b/UXXFrYH13pzg0/W', 'hr-super-admin', 'active', 1, NULL, 'a', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'eyJpdiI6ImRvWjdpVERiUVM1L0c3ckpKTTNlcEE9PSIsInZhbHVlIjoiOG9tUmwxVkRiME43RllvT1hQQXVPeWFGOGRkUEdUeTFSdlBRUVVIL2YzMD0iLCJtYWMiOiIwOTExMmVhNDRkODdlZjQwNWFlNmExODI1MDc0OWVhZWMyNzFjZGJiZGE1N2YxYjE0YTlmMTlhYjkyZmNkYjQ1IiwidGFnIjoiIn0=', 1, NULL, NULL, NULL, NULL, NULL, '2026-04-19 18:26:50', NULL, NULL, NULL, '2026-04-19 18:11:18', '2026-04-19 18:11:18', NULL),
(2, 'Grant Arachea', 'Grant', NULL, 'Arachea', 'grantarachea09@gmail.com', '$2y$12$Zeke9h6bOD/IxZ/uEuZkyeJ9vOuvIH67cj3V.z86gA4LTGKN1dSIm', 'staff', 'active', 1, NULL, 'b', 'DigiGov', 'TCO', NULL, 'Pangasinan', NULL, NULL, NULL, NULL, 'eyJpdiI6ImZpUEtaU0NNWnhQdWEwN09ReStwZmc9PSIsInZhbHVlIjoiYTBrcFJQV3RNdXFsYmt6YW00Z1VtcnBGWUdTQkRxV1g0WklRVVJLcG92Zz0iLCJtYWMiOiIxOTUxOTRhOWJjMTkzZjg4ZTk5NTA5ZGMwMjZlMWU3NDEzN2UyNDkyMmY2OTI4ZDM1MWQ4MDZlMGE5ZDdiNzk4IiwidGFnIjoiIn0=', 1, NULL, NULL, '2026-04-19 21:37:34', 1, '2026-04-19 21:37:34', '2026-04-19 21:38:37', NULL, NULL, '2026-04-20 01:26:38', '2026-04-19 18:11:19', '2026-04-20 01:26:38', NULL),
(3, 'asda 13123123 asdada', 'asda', '13123123', 'asdada', 'asda@gmail.com', '$2y$12$b9TRUAhnUQoFidSvz08fAemNgQu2YBNPT/AxWG9dS32cWpAM6O1TO', 'interns', 'active', 0, NULL, 'ediwaw', 'SPARK', 'Provincial Office', NULL, 'Ilocos Norte', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-19 22:56:50', '2026-04-19 22:56:50', NULL),
(5, 'Emman Pos4 Llamas', 'Emman', 'Pos4', 'Llamas', 'jesusllamas052@gmail.com', '$2y$12$3h0mJD0Yi/9LZN591FvKs.d/j5m3VbrodYZOq4v0ooxeE3EW/qiIu', 'hr-super-admin', 'active', 1, NULL, 'Super Admin', 'Cybersecurity', 'Provincial Office', NULL, NULL, NULL, NULL, NULL, NULL, 'eyJpdiI6Im9Tc25HTXFTR0gyMC9za0lIRGJENWc9PSIsInZhbHVlIjoiL2llckI4U1NLcTg2MU5TOWpTRldLNzl4K2l1V0g0U2VTTHFwMjRleUdkMD0iLCJtYWMiOiJmODYxNWMwNDYxODg3ZDcxOTI5YTE4NTJjNWRjN2Q0OGQ2NmQ3YzBiNWMwZmVmYjU3Y2E1YTY2ZTkyZDU0MTgyIiwidGFnIjoiIn0=', 1, NULL, NULL, '2026-04-26 16:43:33', 1, '2026-04-26 16:43:33', '2026-04-26 16:45:19', NULL, NULL, NULL, '2026-04-26 16:43:11', '2026-04-26 16:43:33', NULL),
(6, 'Juan Fernandez Perez', 'Juan', 'Fernandez', 'Perez', 'kuroky281@gmail.com', '$2y$12$23G6V7LUtafoZDgt6q2Q4.PIO9PoByUQlZXCgwj5w8ycmDc84cSOS', 'staff', 'active', 1, NULL, 'Staff1', 'FW4A', 'Field Office', NULL, 'Ilocos Sur', NULL, NULL, NULL, NULL, 'eyJpdiI6InhPTlhuMGZnQjZxQlloT1RzNjdlTUE9PSIsInZhbHVlIjoiTjFwaDFZcmtJdGp0ZkcyS2Q2QjY4dERpczdKUytEMkU2TjhzbGoxR0hNWT0iLCJtYWMiOiIwNjNhNjA4ZTc5MTFiYmI4ZWM1NDUxZGU4YTI1NGM0YjkyM2EzM2Q4M2FhNjA0YjNmZmNmMDJiNDE5ZGZjMjg2IiwidGFnIjoiIn0=', 1, NULL, NULL, '2026-04-26 16:49:12', 5, '2026-04-26 16:49:12', '2026-04-26 16:50:26', NULL, NULL, '2026-04-26 19:36:10', '2026-04-26 16:48:11', '2026-04-26 19:36:10', NULL),
(7, 'Juana Dela Cruz', 'Juana', 'Garcia', 'Dela Cruz', 'emmanlabwork@gmail.com', '$2y$12$rhTQ4Vcz4ad6.kpN/AVPJ.mWttPTtENH2Gz1RM.3M4SnTK15cCv3e', 'ph-admin', 'active', 1, NULL, 'PO', NULL, NULL, 'NPPB', 'Pangasinan', NULL, 'profile-images/cpJI5AH8C7UOr5nXBn10dwGnQSJbV0aVVbhXMEuf.png', NULL, NULL, 'eyJpdiI6IjhCaWhmS21RNkxaaHFZV252Vk43OUE9PSIsInZhbHVlIjoiWjRpS0JaeDhibXYzRENObG5QcHpyb3l2WkJncEVGNjJDRHlNeTRmNy9BQT0iLCJtYWMiOiI0OGZhMmY3ZjFhMzYwMjhjMGQ4YmQ0NmJlNTYwM2Y0ZDY3NTEzMTA0NjZjY2Q0NmVmMDcwODU0NzgzNDI4Nzk5IiwidGFnIjoiIn0=', 1, NULL, NULL, '2026-04-26 17:04:59', 5, '2026-04-26 17:04:59', '2026-04-26 17:06:08', NULL, NULL, NULL, '2026-04-26 17:03:30', '2026-04-26 19:20:08', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `office_reminders`
--
ALTER TABLE `office_reminders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `office_reminder_schedules`
--
ALTER TABLE `office_reminder_schedules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `report_entries`
--
ALTER TABLE `report_entries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `report_entries_report_id_foreign` (`report_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `super_admin_notifications`
--
ALTER TABLE `super_admin_notifications`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `super_admin_notifications_source_key_unique` (`source_key`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `office_reminders`
--
ALTER TABLE `office_reminders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `office_reminder_schedules`
--
ALTER TABLE `office_reminder_schedules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `report_entries`
--
ALTER TABLE `report_entries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `super_admin_notifications`
--
ALTER TABLE `super_admin_notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `report_entries`
--
ALTER TABLE `report_entries`
  ADD CONSTRAINT `report_entries_report_id_foreign` FOREIGN KEY (`report_id`) REFERENCES `reports` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
