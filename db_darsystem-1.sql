-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Apr 20, 2026 at 01:31 AM
-- Server version: 5.7.24
-- PHP Version: 8.3.1

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
  `action` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `details` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `event`, `description`, `details`, `created_at`, `updated_at`) VALUES
(1, 1, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-06 21:59:39', '2026-04-06 21:59:39'),
(2, 1, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-06 21:59:54', '2026-04-06 21:59:54'),
(3, 1, 'user_updated', 'user_updated', 'Updated user account for lermamagno12@gmail.com.', 'Updated user account for lermamagno12@gmail.com.', '2026-04-06 22:00:22', '2026-04-06 22:00:22'),
(4, 1, 'user_created', 'user_created', 'Created user account for amrelmagno6@gmail.com.', 'Created user account for amrelmagno6@gmail.com.', '2026-04-06 22:02:14', '2026-04-06 22:02:14'),
(5, 1, 'user_created', 'user_created', 'Created user account for 22ln4415_ms@psu.edu.ph.', 'Created user account for 22ln4415_ms@psu.edu.ph.', '2026-04-06 22:03:42', '2026-04-06 22:03:42'),
(6, 1, 'user_updated', 'user_updated', 'Updated user account for 22ln4415_ms@psu.edu.ph.', 'Updated user account for 22ln4415_ms@psu.edu.ph.', '2026-04-06 22:04:31', '2026-04-06 22:04:31'),
(7, 1, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-06 22:05:15', '2026-04-06 22:05:15'),
(8, 3, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-06 22:06:23', '2026-04-06 22:06:23'),
(9, 3, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-06 22:08:21', '2026-04-06 22:08:21'),
(10, 3, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-06 22:08:40', '2026-04-06 22:08:40'),
(11, 3, 'profile_updated', 'profile_updated', 'Updated personal profile.', 'Updated personal profile.', '2026-04-06 22:43:58', '2026-04-06 22:43:58'),
(12, 3, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-06 22:44:51', '2026-04-06 22:44:51'),
(13, 2, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-06 22:45:06', '2026-04-06 22:45:06'),
(14, 2, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-06 22:45:53', '2026-04-06 22:45:53'),
(15, 2, 'report_returned', 'report_returned', 'Report \"April 6 - 10\" was returned for revision. Note: fix your grammar', 'Report \"April 6 - 10\" was returned for revision. Note: fix your grammar', '2026-04-06 23:02:20', '2026-04-06 23:02:20'),
(16, 2, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-06 23:03:21', '2026-04-06 23:03:21'),
(17, 3, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-06 23:03:32', '2026-04-06 23:03:32'),
(18, 3, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-06 23:03:53', '2026-04-06 23:03:53'),
(19, 3, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-07 01:24:05', '2026-04-07 01:24:05'),
(20, 3, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-07 01:24:17', '2026-04-07 01:24:17'),
(21, 3, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-07 16:22:45', '2026-04-07 16:22:45'),
(22, 3, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-07 16:23:06', '2026-04-07 16:23:06'),
(23, 3, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-07 17:30:01', '2026-04-07 17:30:01'),
(24, 3, 'otp_resent', 'otp_resent', 'OTP resent for sign in.', 'OTP resent for sign in.', '2026-04-07 17:31:43', '2026-04-07 17:31:43'),
(25, 3, 'otp_resent', 'otp_resent', 'OTP resent for sign in.', 'OTP resent for sign in.', '2026-04-07 17:33:34', '2026-04-07 17:33:34'),
(26, 3, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-07 17:34:00', '2026-04-07 17:34:00'),
(27, 3, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-07 17:39:18', '2026-04-07 17:39:18'),
(28, 3, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-07 17:39:37', '2026-04-07 17:39:37'),
(29, 3, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-07 18:43:23', '2026-04-07 18:43:23'),
(30, 3, 'otp_resent', 'otp_resent', 'OTP resent for sign in.', 'OTP resent for sign in.', '2026-04-07 18:45:41', '2026-04-07 18:45:41'),
(31, 3, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-07 18:46:51', '2026-04-07 18:46:51'),
(32, 3, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-07 20:24:22', '2026-04-07 20:24:22'),
(33, 3, 'otp_resent', 'otp_resent', 'OTP resent for sign in.', 'OTP resent for sign in.', '2026-04-07 20:26:09', '2026-04-07 20:26:09'),
(34, 3, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-07 20:26:50', '2026-04-07 20:26:50'),
(35, 3, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-07 22:03:14', '2026-04-07 22:03:14'),
(36, 3, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-07 22:03:36', '2026-04-07 22:03:36'),
(37, 3, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-07 22:18:50', '2026-04-07 22:18:50'),
(38, 3, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-07 22:19:26', '2026-04-07 22:19:26'),
(39, 3, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-07 22:52:25', '2026-04-07 22:52:25'),
(40, 1, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-07 22:52:43', '2026-04-07 22:52:43'),
(41, 1, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-07 22:52:56', '2026-04-07 22:52:56'),
(42, 1, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-07 22:59:30', '2026-04-07 22:59:30'),
(43, 3, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-07 22:59:45', '2026-04-07 22:59:45'),
(44, 3, 'otp_resent', 'otp_resent', 'OTP resent for sign in.', 'OTP resent for sign in.', '2026-04-07 23:01:42', '2026-04-07 23:01:42'),
(45, 3, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-07 23:01:54', '2026-04-07 23:01:54'),
(46, 3, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-07 23:15:49', '2026-04-07 23:15:49'),
(47, 3, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-07 23:16:10', '2026-04-07 23:16:10'),
(48, 3, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-07 23:40:10', '2026-04-07 23:40:10'),
(49, 3, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-07 23:40:46', '2026-04-07 23:40:46'),
(50, 3, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-07 23:48:57', '2026-04-07 23:48:57'),
(51, 1, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-07 23:49:11', '2026-04-07 23:49:11'),
(52, 1, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-07 23:49:28', '2026-04-07 23:49:28'),
(53, 1, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-07 23:50:10', '2026-04-07 23:50:10'),
(54, 3, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-07 23:50:29', '2026-04-07 23:50:29'),
(55, 3, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-07 23:51:00', '2026-04-07 23:51:00'),
(56, 3, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-08 16:20:05', '2026-04-08 16:20:05'),
(57, 3, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-08 16:20:24', '2026-04-08 16:20:24'),
(58, 3, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-08 16:52:42', '2026-04-08 16:52:42'),
(59, 3, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-08 16:52:55', '2026-04-08 16:52:55'),
(60, 3, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-08 16:54:01', '2026-04-08 16:54:01'),
(61, 2, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-08 16:54:15', '2026-04-08 16:54:15'),
(62, 2, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-08 16:54:53', '2026-04-08 16:54:53'),
(63, 2, 'report_returned', 'report_returned', 'Report \"April 6 - 10\" was returned for revision. Note: fix your grammar', 'Report \"April 6 - 10\" was returned for revision. Note: fix your grammar', '2026-04-08 16:56:52', '2026-04-08 16:56:52'),
(64, 2, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-08 16:57:26', '2026-04-08 16:57:26'),
(65, 1, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-08 16:57:44', '2026-04-08 16:57:44'),
(66, 1, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-08 16:58:35', '2026-04-08 16:58:35'),
(67, 1, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-08 17:08:32', '2026-04-08 17:08:32'),
(68, 1, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-08 17:08:49', '2026-04-08 17:08:49'),
(69, 1, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-08 17:09:42', '2026-04-08 17:09:42'),
(70, 2, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-08 17:10:16', '2026-04-08 17:10:16'),
(71, 2, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-08 17:10:45', '2026-04-08 17:10:45'),
(72, 2, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-08 17:30:39', '2026-04-08 17:30:39'),
(73, 3, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-08 17:30:55', '2026-04-08 17:30:55'),
(74, 3, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-08 17:31:23', '2026-04-08 17:31:23'),
(75, 3, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-08 17:40:07', '2026-04-08 17:40:07'),
(76, 1, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-08 17:40:24', '2026-04-08 17:40:24'),
(77, 1, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-08 17:40:45', '2026-04-08 17:40:45'),
(78, 1, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-08 17:41:43', '2026-04-08 17:41:43'),
(79, 3, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-08 17:41:55', '2026-04-08 17:41:55'),
(80, 3, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-08 17:42:22', '2026-04-08 17:42:22'),
(81, 3, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-08 18:16:35', '2026-04-08 18:16:35'),
(82, 3, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-08 18:16:55', '2026-04-08 18:16:55'),
(83, 3, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-08 20:48:02', '2026-04-08 20:48:02'),
(84, 3, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-08 20:48:25', '2026-04-08 20:48:25'),
(85, 3, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-08 22:10:24', '2026-04-08 22:10:24'),
(86, 2, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-08 22:10:38', '2026-04-08 22:10:38'),
(87, 2, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-08 22:11:10', '2026-04-08 22:11:10'),
(88, 2, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-08 22:33:04', '2026-04-08 22:33:04'),
(89, 3, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-08 22:33:36', '2026-04-08 22:33:36'),
(90, 3, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-08 22:33:45', '2026-04-08 22:33:45'),
(91, 3, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-08 22:34:48', '2026-04-08 22:34:48'),
(92, 3, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-08 22:35:45', '2026-04-08 22:35:45'),
(93, 2, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-08 22:36:02', '2026-04-08 22:36:02'),
(94, 2, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-08 22:36:40', '2026-04-08 22:36:40'),
(95, 2, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-08 23:40:53', '2026-04-08 23:40:53'),
(96, 3, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-08 23:41:10', '2026-04-08 23:41:10'),
(97, 3, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-08 23:41:20', '2026-04-08 23:41:20'),
(98, 3, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-08 23:41:30', '2026-04-08 23:41:30'),
(99, 3, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-08 23:41:51', '2026-04-08 23:41:51'),
(100, 3, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-09 01:03:27', '2026-04-09 01:03:27'),
(101, 3, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-09 01:03:47', '2026-04-09 01:03:47'),
(102, 3, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-09 01:10:34', '2026-04-09 01:10:34'),
(103, 3, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-09 01:10:51', '2026-04-09 01:10:51'),
(104, 3, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-09 01:12:02', '2026-04-09 01:12:02'),
(105, 2, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-09 01:12:16', '2026-04-09 01:12:16'),
(106, 2, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-09 01:12:29', '2026-04-09 01:12:29'),
(107, 2, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-09 01:20:11', '2026-04-09 01:20:11'),
(108, 3, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-09 01:20:27', '2026-04-09 01:20:27'),
(109, 3, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-09 01:20:49', '2026-04-09 01:20:49'),
(110, 3, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-09 01:46:51', '2026-04-09 01:46:51'),
(111, NULL, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-09 01:46:54', '2026-04-09 01:46:54'),
(112, 2, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-09 01:47:14', '2026-04-09 01:47:14'),
(113, 2, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-09 01:47:30', '2026-04-09 01:47:30'),
(114, 2, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-09 01:47:55', '2026-04-09 01:47:55'),
(115, 1, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-09 01:48:17', '2026-04-09 01:48:17'),
(116, 1, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-09 01:48:33', '2026-04-09 01:48:33'),
(117, 1, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-09 01:49:37', '2026-04-09 01:49:37'),
(118, 3, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-09 05:26:41', '2026-04-09 05:26:41'),
(119, 3, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-09 05:26:53', '2026-04-09 05:26:53'),
(120, 3, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-09 05:27:55', '2026-04-09 05:27:55'),
(121, 3, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-09 06:02:43', '2026-04-09 06:02:43'),
(122, 1, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-09 06:02:58', '2026-04-09 06:02:58'),
(123, 1, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-09 06:03:16', '2026-04-09 06:03:16'),
(124, 1, 'user_archived', 'user_archived', 'Archived user account for amrelmagno6@gmail.com.', 'Archived user account for amrelmagno6@gmail.com.', '2026-04-09 08:24:40', '2026-04-09 08:24:40'),
(125, 1, 'user_restored', 'user_restored', 'Restored user account for amrelmagno6@gmail.com.', 'Restored user account for amrelmagno6@gmail.com.', '2026-04-09 08:24:50', '2026-04-09 08:24:50'),
(126, 2, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-09 08:53:32', '2026-04-09 08:53:32'),
(127, 2, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-09 08:53:50', '2026-04-09 08:53:50'),
(128, 2, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-09 09:01:52', '2026-04-09 09:01:52'),
(129, 1, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-09 09:02:09', '2026-04-09 09:02:09'),
(130, 1, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-09 09:02:27', '2026-04-09 09:02:27'),
(131, 1, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-09 09:27:40', '2026-04-09 09:27:40'),
(132, 2, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-09 09:28:07', '2026-04-09 09:28:07'),
(133, 2, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-09 09:28:15', '2026-04-09 09:28:15'),
(134, 2, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-09 09:29:02', '2026-04-09 09:29:02'),
(135, 2, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-09 09:31:44', '2026-04-09 09:31:44'),
(136, 3, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-09 09:32:12', '2026-04-09 09:32:12'),
(137, 3, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-09 09:32:28', '2026-04-09 09:32:28'),
(138, 3, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-09 22:26:24', '2026-04-09 22:26:24'),
(139, 3, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-09 22:26:40', '2026-04-09 22:26:40'),
(140, 3, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-10 10:44:49', '2026-04-10 10:44:49'),
(141, 3, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-10 10:44:58', '2026-04-10 10:44:58'),
(142, 3, 'otp_resent', 'otp_resent', 'OTP resent for sign in.', 'OTP resent for sign in.', '2026-04-10 10:46:40', '2026-04-10 10:46:40'),
(143, 3, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-10 10:47:42', '2026-04-10 10:47:42'),
(144, 3, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-12 15:23:54', '2026-04-12 15:23:54'),
(145, 3, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-12 15:24:29', '2026-04-12 15:24:29'),
(146, 3, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-12 17:31:05', '2026-04-12 17:31:05'),
(147, 1, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-12 17:31:28', '2026-04-12 17:31:28'),
(148, 1, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-12 17:32:46', '2026-04-12 17:32:46'),
(149, 1, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-12 17:34:23', '2026-04-12 17:34:23'),
(150, 3, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-12 17:34:43', '2026-04-12 17:34:43'),
(151, 3, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-12 17:34:58', '2026-04-12 17:34:58'),
(152, 3, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-12 19:51:16', '2026-04-12 19:51:16'),
(153, 1, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-12 19:51:33', '2026-04-12 19:51:33'),
(154, 1, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-12 19:52:15', '2026-04-12 19:52:15'),
(155, 1, 'user_created', 'user_created', 'Created user account for gailramiro118@gmail.com.', 'Created user account for gailramiro118@gmail.com.', '2026-04-12 21:20:03', '2026-04-12 21:20:03'),
(156, 1, 'user_archived', 'user_archived', 'Archived user account for 22ln4415_ms@psu.edu.ph.', 'Archived user account for 22ln4415_ms@psu.edu.ph.', '2026-04-12 21:28:02', '2026-04-12 21:28:02'),
(157, 1, 'user_restored', 'user_restored', 'Restored user account for 22ln4415_ms@psu.edu.ph.', 'Restored user account for 22ln4415_ms@psu.edu.ph.', '2026-04-12 21:28:11', '2026-04-12 21:28:11'),
(158, 1, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-13 01:32:37', '2026-04-13 01:32:37'),
(159, 1, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-13 01:32:59', '2026-04-13 01:32:59'),
(160, 1, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-13 01:42:28', '2026-04-13 01:42:28'),
(161, 4, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-13 01:42:45', '2026-04-13 01:42:45'),
(162, 4, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-13 01:43:13', '2026-04-13 01:43:13'),
(163, 1, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-13 13:58:33', '2026-04-13 13:58:33'),
(164, 1, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-13 13:59:39', '2026-04-13 13:59:39'),
(165, 1, 'user_updated', 'user_updated', 'Updated user account for amrelmagno6@gmail.com.', 'Updated user account for amrelmagno6@gmail.com.', '2026-04-13 14:00:36', '2026-04-13 14:00:36'),
(166, 1, 'user_updated', 'user_updated', 'Updated user account for 22ln4415_ms@psu.edu.ph.', 'Updated user account for 22ln4415_ms@psu.edu.ph.', '2026-04-13 14:02:05', '2026-04-13 14:02:05'),
(167, 1, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-13 15:08:23', '2026-04-13 15:08:23'),
(168, 1, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-13 15:09:21', '2026-04-13 15:09:21'),
(169, 1, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-13 15:12:26', '2026-04-13 15:12:26'),
(170, 2, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-13 15:12:42', '2026-04-13 15:12:42'),
(171, 2, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-13 15:13:25', '2026-04-13 15:13:25'),
(172, 2, 'report_returned', 'report_returned', 'Report \"April 20 - 26\" was returned for revision. Note: fix grammar', 'Report \"April 20 - 26\" was returned for revision. Note: fix grammar', '2026-04-13 15:15:15', '2026-04-13 15:15:15'),
(173, 2, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-13 15:15:45', '2026-04-13 15:15:45'),
(174, 1, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-13 15:16:09', '2026-04-13 15:16:09'),
(175, 1, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-13 15:16:24', '2026-04-13 15:16:24'),
(176, 1, 'user_updated', 'user_updated', 'Updated user account for amrelmagno6@gmail.com.', 'Updated user account for amrelmagno6@gmail.com.', '2026-04-13 15:17:01', '2026-04-13 15:17:01'),
(177, 1, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-13 15:17:20', '2026-04-13 15:17:20'),
(178, 2, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-13 15:17:37', '2026-04-13 15:17:37'),
(179, 2, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-13 15:17:53', '2026-04-13 15:17:53'),
(180, 2, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-13 15:19:42', '2026-04-13 15:19:42'),
(181, 1, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-13 15:19:59', '2026-04-13 15:19:59'),
(182, 1, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-13 15:20:18', '2026-04-13 15:20:18'),
(183, 1, 'user_archived', 'user_archived', 'Archived user account for gailramiro118@gmail.com.', 'Archived user account for gailramiro118@gmail.com.', '2026-04-13 15:24:04', '2026-04-13 15:24:04'),
(184, 1, 'user_restored', 'user_restored', 'Restored user account for gailramiro118@gmail.com.', 'Restored user account for gailramiro118@gmail.com.', '2026-04-13 15:24:25', '2026-04-13 15:24:25'),
(185, 1, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-13 15:29:34', '2026-04-13 15:29:34'),
(186, 2, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-13 15:29:48', '2026-04-13 15:29:48'),
(187, 2, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-13 15:30:37', '2026-04-13 15:30:37'),
(188, 2, 'report_returned', 'report_returned', 'Report \"April 27 - May 1\" was returned for revision. Note: fix grammar', 'Report \"April 27 - May 1\" was returned for revision. Note: fix grammar', '2026-04-13 15:33:58', '2026-04-13 15:33:58'),
(189, 2, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-13 15:34:13', '2026-04-13 15:34:13'),
(190, 1, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-13 15:34:28', '2026-04-13 15:34:28'),
(191, 1, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-13 15:34:42', '2026-04-13 15:34:42'),
(192, 1, 'user_created', 'user_created', 'Created user account for lermamagn12@gmail.com.', 'Created user account for lermamagn12@gmail.com.', '2026-04-13 15:36:42', '2026-04-13 15:36:42'),
(193, 1, 'user_updated', 'user_updated', 'Updated user account for lermamagn08@gmail.com.', 'Updated user account for lermamagn08@gmail.com.', '2026-04-13 15:37:08', '2026-04-13 15:37:08'),
(194, 1, 'user_updated', 'user_updated', 'Updated user account for 22ln4415_ms@psu.edu.ph.', 'Updated user account for 22ln4415_ms@psu.edu.ph.', '2026-04-13 15:37:35', '2026-04-13 15:37:35'),
(195, 1, 'user_created', 'user_created', 'Created user account for amrelmagn76@gmail.com.', 'Created user account for amrelmagn76@gmail.com.', '2026-04-13 15:39:01', '2026-04-13 15:39:01'),
(196, 1, 'user_updated', 'user_updated', 'Updated user account for amrelmagno6@gmail.com.', 'Updated user account for amrelmagno6@gmail.com.', '2026-04-13 15:50:03', '2026-04-13 15:50:03'),
(197, 1, 'user_created', 'user_created', 'Created user account for magnolerma07@gamil.com.', 'Created user account for magnolerma07@gamil.com.', '2026-04-13 15:52:05', '2026-04-13 15:52:05'),
(198, 1, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-13 15:52:27', '2026-04-13 15:52:27'),
(199, 7, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-13 15:52:45', '2026-04-13 15:52:45'),
(200, 1, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-13 15:54:09', '2026-04-13 15:54:09'),
(201, 1, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-13 15:54:28', '2026-04-13 15:54:28'),
(202, 1, 'user_updated', 'user_updated', 'Updated user account for magnolerma07@gmail.com.', 'Updated user account for magnolerma07@gmail.com.', '2026-04-13 15:54:49', '2026-04-13 15:54:49'),
(203, 1, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-13 15:55:31', '2026-04-13 15:55:31'),
(204, 7, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-13 15:55:53', '2026-04-13 15:55:53'),
(205, 7, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-13 15:56:11', '2026-04-13 15:56:11'),
(206, 7, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-13 16:03:52', '2026-04-13 16:03:52'),
(207, 1, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-13 16:04:05', '2026-04-13 16:04:05'),
(208, 1, 'otp_resent', 'otp_resent', 'OTP resent for sign in.', 'OTP resent for sign in.', '2026-04-13 16:05:40', '2026-04-13 16:05:40'),
(209, 1, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-13 16:06:02', '2026-04-13 16:06:02'),
(210, 1, 'user_updated', 'user_updated', 'Updated user account for lerms9884@gmail.com.', 'Updated user account for lerms9884@gmail.com.', '2026-04-13 16:07:00', '2026-04-13 16:07:00'),
(211, 1, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-13 16:07:52', '2026-04-13 16:07:52'),
(212, 5, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-13 16:08:06', '2026-04-13 16:08:06'),
(213, 5, 'otp_resent', 'otp_resent', 'OTP resent for sign in.', 'OTP resent for sign in.', '2026-04-13 16:10:08', '2026-04-13 16:10:08'),
(214, 5, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-13 16:10:55', '2026-04-13 16:10:55'),
(215, 5, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-13 16:20:53', '2026-04-13 16:20:53'),
(216, 3, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-13 16:21:06', '2026-04-13 16:21:06'),
(217, 3, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-13 16:21:28', '2026-04-13 16:21:28'),
(218, 1, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-14 01:40:25', '2026-04-14 01:40:25'),
(219, 1, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-14 01:40:32', '2026-04-14 01:40:32'),
(220, 1, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-14 01:41:53', '2026-04-14 01:41:53'),
(221, 1, 'user_created', 'user_created', 'Created user account for jayramiro6@gmail.com.', 'Created user account for jayramiro6@gmail.com.', '2026-04-14 01:43:14', '2026-04-14 01:43:14'),
(222, 8, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-14 01:44:00', '2026-04-14 01:44:00'),
(223, 3, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-14 01:46:29', '2026-04-14 01:46:29'),
(224, 3, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-14 01:46:36', '2026-04-14 01:46:36'),
(225, 3, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-14 01:56:17', '2026-04-14 01:56:17'),
(226, 8, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-14 01:56:28', '2026-04-14 01:56:28'),
(227, 8, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-14 01:56:47', '2026-04-14 01:56:47'),
(228, 3, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-14 01:56:52', '2026-04-14 01:56:52'),
(229, 1, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-14 01:58:42', '2026-04-14 01:58:42'),
(230, 3, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-14 01:59:03', '2026-04-14 01:59:03'),
(231, 1, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-14 01:59:20', '2026-04-14 01:59:20'),
(232, 1, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-14 02:00:12', '2026-04-14 02:00:12'),
(233, 1, 'user_created', 'user_created', 'Created user account for jelandrada@gmail.com.', 'Created user account for jelandrada@gmail.com.', '2026-04-14 02:01:37', '2026-04-14 02:01:37'),
(234, 1, 'user_updated', 'user_updated', 'Updated user account for jelandrada23@gmail.com.', 'Updated user account for jelandrada23@gmail.com.', '2026-04-14 02:07:39', '2026-04-14 02:07:39'),
(235, 9, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-14 02:09:02', '2026-04-14 02:09:02'),
(236, 9, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-14 02:09:17', '2026-04-14 02:09:17'),
(237, 9, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-14 15:21:36', '2026-04-14 15:21:36'),
(238, 9, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-14 15:21:42', '2026-04-14 15:21:42'),
(239, 9, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-14 15:22:36', '2026-04-14 15:22:36'),
(240, 2, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-14 15:30:11', '2026-04-14 15:30:11'),
(241, 2, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-14 15:30:41', '2026-04-14 15:30:41'),
(242, 9, 'profile_updated', 'profile_updated', 'Updated personal profile.', 'Updated personal profile.', '2026-04-14 15:35:06', '2026-04-14 15:35:06'),
(243, 2, 'report_returned', 'report_returned', 'Report \"4/10/2026 - 4/11/2026\" was returned for revision. Note: fix', 'Report \"4/10/2026 - 4/11/2026\" was returned for revision. Note: fix', '2026-04-14 15:35:49', '2026-04-14 15:35:49'),
(244, 2, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-14 15:46:42', '2026-04-14 15:46:42'),
(245, 1, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-14 15:47:07', '2026-04-14 15:47:07'),
(246, 1, 'otp_resent', 'otp_resent', 'OTP resent for sign in.', 'OTP resent for sign in.', '2026-04-14 15:48:55', '2026-04-14 15:48:55'),
(247, 1, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-14 15:50:16', '2026-04-14 15:50:16'),
(248, 3, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-14 22:28:25', '2026-04-14 22:28:25'),
(249, 3, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-14 22:29:02', '2026-04-14 22:29:02'),
(250, 3, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-14 22:49:14', '2026-04-14 22:49:14'),
(251, 1, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-14 22:49:33', '2026-04-14 22:49:33'),
(252, 1, 'otp_resent', 'otp_resent', 'OTP resent for sign in.', 'OTP resent for sign in.', '2026-04-14 22:51:38', '2026-04-14 22:51:38'),
(253, 1, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-14 22:52:25', '2026-04-14 22:52:25'),
(254, 1, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-15 00:57:02', '2026-04-15 00:57:02'),
(255, 1, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-15 01:21:41', '2026-04-15 01:21:41'),
(256, 1, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-15 01:22:44', '2026-04-15 01:22:44'),
(257, 3, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-15 01:29:11', '2026-04-15 01:29:11'),
(258, 3, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-15 01:29:33', '2026-04-15 01:29:33'),
(259, 3, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-15 01:30:05', '2026-04-15 01:30:05'),
(260, 1, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-15 03:35:27', '2026-04-15 03:35:27'),
(261, 1, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-15 03:36:24', '2026-04-15 03:36:24'),
(262, 3, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-15 05:25:04', '2026-04-15 05:25:04'),
(263, 3, 'otp_resent', 'otp_resent', 'OTP resent for sign in.', 'OTP resent for sign in.', '2026-04-15 05:28:08', '2026-04-15 05:28:08'),
(264, 3, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-15 05:29:11', '2026-04-15 05:29:11'),
(265, 1, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-15 15:43:35', '2026-04-15 15:43:35'),
(266, 1, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-15 15:45:24', '2026-04-15 15:45:24'),
(267, 1, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-15 16:26:03', '2026-04-15 16:26:03'),
(268, 1, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-15 16:27:21', '2026-04-15 16:27:21'),
(269, 1, 'user_created', 'user_created', 'Created user account for chrisjericho.work@gmail.com.', 'Created user account for chrisjericho.work@gmail.com.', '2026-04-15 16:30:11', '2026-04-15 16:30:11'),
(270, 10, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-15 16:30:47', '2026-04-15 16:30:47'),
(271, 10, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-15 16:31:24', '2026-04-15 16:31:24'),
(272, 1, 'user_created', 'user_created', 'Created user account for costales.centenielor@gmail.com.', 'Created user account for costales.centenielor@gmail.com.', '2026-04-15 16:32:05', '2026-04-15 16:32:05'),
(273, 11, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-15 16:32:39', '2026-04-15 16:32:39'),
(274, 11, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-15 16:33:04', '2026-04-15 16:33:04'),
(275, 1, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-15 16:34:24', '2026-04-15 16:34:24'),
(276, 1, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-15 16:34:51', '2026-04-15 16:34:51'),
(277, 1, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-15 16:35:31', '2026-04-15 16:35:31'),
(278, 7, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-15 16:36:44', '2026-04-15 16:36:44'),
(279, 7, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-15 16:37:12', '2026-04-15 16:37:12'),
(280, 7, 'report_returned', 'report_returned', 'Report \"April 16 - 18\" was returned for revision. Note: mmm', 'Report \"April 16 - 18\" was returned for revision. Note: mmm', '2026-04-15 16:37:55', '2026-04-15 16:37:55'),
(281, 7, 'report_returned', 'report_returned', 'Report \"April 16 - 18\" was returned for revision. Note: mmm', 'Report \"April 16 - 18\" was returned for revision. Note: mmm', '2026-04-15 16:37:56', '2026-04-15 16:37:56'),
(282, 7, 'report_returned', 'report_returned', 'Report \"April 16 - 18\" was returned for revision. Note: add remarks', 'Report \"April 16 - 18\" was returned for revision. Note: add remarks', '2026-04-15 16:39:20', '2026-04-15 16:39:20'),
(283, 1, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-15 17:26:11', '2026-04-15 17:26:11'),
(284, 1, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-15 17:26:18', '2026-04-15 17:26:18'),
(285, 1, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-15 17:43:36', '2026-04-15 17:43:36'),
(286, 1, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-15 17:44:17', '2026-04-15 17:44:17'),
(287, 1, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-15 17:44:46', '2026-04-15 17:44:46'),
(288, 3, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-15 17:45:03', '2026-04-15 17:45:03'),
(289, 3, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-15 17:45:18', '2026-04-15 17:45:18'),
(290, 3, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-15 17:51:00', '2026-04-15 17:51:00'),
(291, 1, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-15 17:52:54', '2026-04-15 17:52:54'),
(292, 1, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-15 17:53:08', '2026-04-15 17:53:08'),
(293, 1, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-15 18:03:34', '2026-04-15 18:03:34'),
(294, 1, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-15 18:04:07', '2026-04-15 18:04:07'),
(295, 1, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-15 18:08:29', '2026-04-15 18:08:29'),
(296, 1, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-15 18:08:50', '2026-04-15 18:08:50'),
(297, 1, 'user_created', 'user_created', 'Created user account for allen.victorio@dict.gov.ph.', 'Created user account for allen.victorio@dict.gov.ph.', '2026-04-15 18:11:32', '2026-04-15 18:11:32'),
(298, 12, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-15 18:13:30', '2026-04-15 18:13:30'),
(299, 12, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-15 18:13:53', '2026-04-15 18:13:53'),
(300, 12, 'otp_resent', 'otp_resent', 'OTP resent for sign in.', 'OTP resent for sign in.', '2026-04-15 18:15:51', '2026-04-15 18:15:51'),
(301, 12, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-15 18:16:13', '2026-04-15 18:16:13'),
(302, 1, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-15 18:22:59', '2026-04-15 18:22:59'),
(303, NULL, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-15 18:23:02', '2026-04-15 18:23:02'),
(304, 2, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-15 18:23:54', '2026-04-15 18:23:54'),
(305, 2, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-15 18:24:03', '2026-04-15 18:24:03'),
(306, 2, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-15 18:24:34', '2026-04-15 18:24:34'),
(307, 12, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-15 18:57:42', '2026-04-15 18:57:42'),
(308, 2, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-15 20:18:55', '2026-04-15 20:18:55'),
(309, 2, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-15 20:19:21', '2026-04-15 20:19:21'),
(310, 2, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-17 06:51:54', '2026-04-17 06:51:54'),
(311, 2, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-17 06:52:23', '2026-04-17 06:52:23'),
(312, 2, 'report_returned', 'report_returned', 'Report \"April 1 - 4\" was returned for revision. Note: mbb', 'Report \"April 1 - 4\" was returned for revision. Note: mbb', '2026-04-17 06:54:24', '2026-04-17 06:54:24'),
(313, 2, 'report_returned', 'report_returned', 'Report \"April 1 - 4\" was returned for revision. Note: nn', 'Report \"April 1 - 4\" was returned for revision. Note: nn', '2026-04-17 06:54:54', '2026-04-17 06:54:54'),
(314, 2, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-17 07:58:54', '2026-04-17 07:58:54'),
(315, 3, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-17 07:59:53', '2026-04-17 07:59:53'),
(316, 3, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-17 08:00:36', '2026-04-17 08:00:36'),
(317, 3, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-17 10:43:44', '2026-04-17 10:43:44'),
(318, 2, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-17 10:44:03', '2026-04-17 10:44:03'),
(319, 2, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-17 10:44:32', '2026-04-17 10:44:32'),
(320, 2, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-17 17:33:06', '2026-04-17 17:33:06'),
(321, 2, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-17 17:33:46', '2026-04-17 17:33:46'),
(322, 2, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-17 17:52:14', '2026-04-17 17:52:14'),
(323, 2, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-17 17:52:46', '2026-04-17 17:52:46'),
(324, 2, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-17 17:59:17', '2026-04-17 17:59:17'),
(325, 2, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-17 17:59:24', '2026-04-17 17:59:24'),
(326, 2, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-17 17:59:59', '2026-04-17 17:59:59'),
(327, 2, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-17 18:04:03', '2026-04-17 18:04:03'),
(328, 2, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-17 18:04:09', '2026-04-17 18:04:09'),
(329, 2, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-17 18:04:16', '2026-04-17 18:04:16'),
(330, 2, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-17 18:05:12', '2026-04-17 18:05:12'),
(331, 3, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-17 19:19:16', '2026-04-17 19:19:16'),
(332, 3, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-17 19:19:43', '2026-04-17 19:19:43'),
(333, 3, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-17 19:24:39', '2026-04-17 19:24:39'),
(334, 2, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-17 19:24:55', '2026-04-17 19:24:55'),
(335, 2, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-17 19:25:30', '2026-04-17 19:25:30'),
(336, 2, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-17 19:40:08', '2026-04-17 19:40:08'),
(337, 3, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-17 19:40:25', '2026-04-17 19:40:25'),
(338, 3, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-17 19:41:00', '2026-04-17 19:41:00'),
(339, 3, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-17 20:48:16', '2026-04-17 20:48:16'),
(340, 3, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-17 20:51:54', '2026-04-17 20:51:54'),
(341, 3, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-17 20:52:17', '2026-04-17 20:52:17'),
(342, 3, 'profile_updated', 'profile_updated', 'Updated personal profile.', 'Updated personal profile.', '2026-04-17 20:56:41', '2026-04-17 20:56:41'),
(343, 3, 'profile_updated', 'profile_updated', 'Updated personal profile.', 'Updated personal profile.', '2026-04-17 20:56:52', '2026-04-17 20:56:52'),
(344, 3, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-17 20:56:58', '2026-04-17 20:56:58'),
(345, 2, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-17 20:57:11', '2026-04-17 20:57:11'),
(346, 2, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-17 20:58:37', '2026-04-17 20:58:37'),
(347, 2, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-17 21:59:06', '2026-04-17 21:59:06');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `event`, `description`, `details`, `created_at`, `updated_at`) VALUES
(348, 2, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-17 21:59:51', '2026-04-17 21:59:51'),
(349, 2, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-17 22:00:44', '2026-04-17 22:00:44'),
(350, 2, 'profile_updated', 'profile_updated', 'Updated personal profile.', 'Updated personal profile.', '2026-04-17 22:17:32', '2026-04-17 22:17:32'),
(351, 2, 'profile_updated', 'profile_updated', 'Updated personal profile.', 'Updated personal profile.', '2026-04-17 22:17:54', '2026-04-17 22:17:54'),
(352, 3, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-17 22:54:38', '2026-04-17 22:54:38'),
(353, 3, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-17 22:55:20', '2026-04-17 22:55:20'),
(354, 3, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-18 10:18:18', '2026-04-18 10:18:18'),
(355, 3, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-18 10:19:13', '2026-04-18 10:19:13'),
(356, 3, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-18 11:43:56', '2026-04-18 11:43:56'),
(357, 2, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-18 11:44:14', '2026-04-18 11:44:14'),
(358, 2, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-18 11:44:50', '2026-04-18 11:44:50'),
(359, 1, 'test_cleanup', NULL, 'Test old log for cleanup', NULL, '2026-04-18 12:33:49', '2026-04-18 12:33:49'),
(360, 1, 'test_cleanup_old', NULL, 'Test old log for cleanup - 25 days old', NULL, '2026-04-18 12:37:38', '2026-04-18 12:37:38'),
(362, 2, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-18 12:46:46', '2026-04-18 12:46:46'),
(363, 1, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-18 12:47:13', '2026-04-18 12:47:13'),
(364, 1, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-18 12:47:34', '2026-04-18 12:47:34'),
(365, 1, 'user_updated', 'user_updated', 'Updated user account for gailramiro118@gmail.com.', 'Updated user account for gailramiro118@gmail.com.', '2026-04-18 12:58:21', '2026-04-18 12:58:21'),
(366, 1, 'user_updated', 'user_updated', 'Updated user account for gailramiro118@gmail.com.', 'Updated user account for gailramiro118@gmail.com.', '2026-04-18 13:03:25', '2026-04-18 13:03:25'),
(367, 1, 'user_updated', 'user_updated', 'Updated user account for gailramiro118@gmail.com.', 'Updated user account for gailramiro118@gmail.com.', '2026-04-18 13:03:38', '2026-04-18 13:03:38'),
(368, 3, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-18 18:54:31', '2026-04-18 18:54:31'),
(369, 3, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-18 18:54:43', '2026-04-18 18:54:43'),
(370, 3, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-18 18:55:33', '2026-04-18 18:55:33'),
(371, 3, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-18 19:07:01', '2026-04-18 19:07:01'),
(372, 1, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-18 19:07:14', '2026-04-18 19:07:14'),
(373, 1, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-18 19:07:26', '2026-04-18 19:07:26'),
(374, 1, 'user_archived', 'user_archived', 'Archived user account for allen.victorio@dict.gov.ph.', 'Archived user account for allen.victorio@dict.gov.ph.', '2026-04-18 19:15:13', '2026-04-18 19:15:13'),
(375, 1, 'user_restored', 'user_restored', 'Restored user account for allen.victorio@dict.gov.ph.', 'Restored user account for allen.victorio@dict.gov.ph.', '2026-04-18 19:15:45', '2026-04-18 19:15:45'),
(376, 1, 'user_archived', 'user_archived', 'Archived user account for gailramiro118@gmail.com.', 'Archived user account for gailramiro118@gmail.com.', '2026-04-18 19:25:56', '2026-04-18 19:25:56'),
(377, 1, 'user_restored', 'user_restored', 'Restored user account for gailramiro118@gmail.com.', 'Restored user account for gailramiro118@gmail.com.', '2026-04-18 19:26:17', '2026-04-18 19:26:17'),
(378, 1, 'user_archived', 'user_archived', 'Archived user account for gailramiro118@gmail.com.', 'Archived user account for gailramiro118@gmail.com.', '2026-04-18 19:26:22', '2026-04-18 19:26:22'),
(379, 1, 'user_restored', 'user_restored', 'Restored user account for gailramiro118@gmail.com.', 'Restored user account for gailramiro118@gmail.com.', '2026-04-18 19:28:07', '2026-04-18 19:28:07'),
(380, 1, 'user_archived', 'user_archived', 'Archived user account for gailramiro118@gmail.com.', 'Archived user account for gailramiro118@gmail.com.', '2026-04-18 19:28:13', '2026-04-18 19:28:13'),
(381, 1, 'user_archived', 'user_archived', 'Archived user account for allen.victorio@dict.gov.ph.', 'Archived user account for allen.victorio@dict.gov.ph.', '2026-04-18 19:28:25', '2026-04-18 19:28:25'),
(382, 1, 'user_restored', 'user_restored', 'Restored user account for allen.victorio@dict.gov.ph.', 'Restored user account for allen.victorio@dict.gov.ph.', '2026-04-18 19:28:37', '2026-04-18 19:28:37'),
(383, 1, 'user_archived', 'user_archived', 'Archived user account for allen.victorio@dict.gov.ph.', 'Archived user account for allen.victorio@dict.gov.ph.', '2026-04-18 19:40:19', '2026-04-18 19:40:19'),
(384, 1, 'user_archived', 'user_archived', 'Archived user account for chrisjericho.work@gmail.com.', 'Archived user account for chrisjericho.work@gmail.com.', '2026-04-18 19:45:55', '2026-04-18 19:45:55'),
(385, 1, 'user_restored', 'user_restored', 'Restored user account for chrisjericho.work@gmail.com.', 'Restored user account for chrisjericho.work@gmail.com.', '2026-04-18 19:46:13', '2026-04-18 19:46:13'),
(386, 1, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-18 19:49:09', '2026-04-18 19:49:09'),
(387, 3, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-18 19:50:41', '2026-04-18 19:50:41'),
(388, 3, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-18 19:50:58', '2026-04-18 19:50:58'),
(389, 3, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-18 22:12:02', '2026-04-18 22:12:02'),
(390, 3, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-18 22:13:05', '2026-04-18 22:13:05'),
(391, 3, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-18 22:15:07', '2026-04-18 22:15:07'),
(392, 2, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-18 22:15:18', '2026-04-18 22:15:18'),
(393, 2, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-18 22:15:42', '2026-04-18 22:15:42'),
(394, 2, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-18 22:17:16', '2026-04-18 22:17:16'),
(395, 1, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-18 22:17:29', '2026-04-18 22:17:29'),
(396, 1, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-18 22:17:50', '2026-04-18 22:17:50'),
(397, 1, 'profile_updated', 'profile_updated', 'Updated personal profile.', 'Updated personal profile.', '2026-04-18 22:37:17', '2026-04-18 22:37:17'),
(398, 1, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-18 22:38:43', '2026-04-18 22:38:43'),
(399, 2, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-18 22:38:57', '2026-04-18 22:38:57'),
(400, 2, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-18 22:39:28', '2026-04-18 22:39:28'),
(401, 2, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-18 22:39:59', '2026-04-18 22:39:59'),
(402, 1, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-18 22:44:14', '2026-04-18 22:44:14'),
(403, 1, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-18 22:45:06', '2026-04-18 22:45:06'),
(404, 1, 'user_archived', 'user_archived', 'Archived user account for chrisjericho.work@gmail.com.', 'Archived user account for chrisjericho.work@gmail.com.', '2026-04-18 22:45:45', '2026-04-18 22:45:45'),
(405, 1, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-18 23:08:10', '2026-04-18 23:08:10'),
(406, 2, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-18 23:08:34', '2026-04-18 23:08:34'),
(407, 2, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-18 23:09:07', '2026-04-18 23:09:07'),
(408, 2, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-18 23:21:25', '2026-04-18 23:21:25'),
(409, 3, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-18 23:21:47', '2026-04-18 23:21:47'),
(410, 3, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-18 23:22:26', '2026-04-18 23:22:26'),
(411, 3, 'profile_updated', 'profile_updated', 'Updated personal profile.', 'Updated personal profile.', '2026-04-18 23:42:12', '2026-04-18 23:42:12'),
(412, 3, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-18 23:46:14', '2026-04-18 23:46:14'),
(413, 1, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-18 23:47:26', '2026-04-18 23:47:26'),
(414, 1, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-18 23:47:35', '2026-04-18 23:47:35'),
(415, 1, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-18 23:48:44', '2026-04-18 23:48:44'),
(416, 1, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-18 23:48:55', '2026-04-18 23:48:55'),
(417, 1, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-19 00:17:57', '2026-04-19 00:17:57'),
(418, 1, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-19 00:18:50', '2026-04-19 00:18:50'),
(419, 1, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-19 00:19:43', '2026-04-19 00:19:43'),
(420, 1, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-19 03:50:32', '2026-04-19 03:50:32'),
(421, 1, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-19 03:51:08', '2026-04-19 03:51:08'),
(422, 1, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-19 04:12:14', '2026-04-19 04:12:14'),
(423, 1, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-19 04:12:35', '2026-04-19 04:12:35'),
(424, 1, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-19 04:13:01', '2026-04-19 04:13:01'),
(425, 1, 'user_restored', 'user_restored', 'Restored user account for gailramiro118@gmail.com.', 'Restored user account for gailramiro118@gmail.com.', '2026-04-19 04:41:38', '2026-04-19 04:41:38'),
(426, 1, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-19 04:41:53', '2026-04-19 04:41:53'),
(427, 1, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-19 04:42:22', '2026-04-19 04:42:22'),
(428, 1, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-19 04:42:40', '2026-04-19 04:42:40'),
(429, 1, 'user_archived', 'user_archived', 'Archived user account for lerms9884@gmail.com.', 'Archived user account for lerms9884@gmail.com.', '2026-04-19 05:01:52', '2026-04-19 05:01:52'),
(430, 1, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-19 10:50:39', '2026-04-19 10:50:39'),
(431, 1, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-19 10:51:22', '2026-04-19 10:51:22'),
(432, 1, 'user_created', 'user_created', 'Created user account for magnolydia93@gmail.com.', 'Created user account for magnolydia93@gmail.com.', '2026-04-19 10:53:11', '2026-04-19 10:53:11'),
(433, 1, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-19 10:53:35', '2026-04-19 10:53:35'),
(434, 13, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-19 10:53:52', '2026-04-19 10:53:52'),
(435, 13, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-19 10:54:06', '2026-04-19 10:54:06'),
(436, 13, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-19 11:22:26', '2026-04-19 11:22:26'),
(437, 13, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-19 11:22:42', '2026-04-19 11:22:42'),
(438, 3, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-19 11:24:06', '2026-04-19 11:24:06'),
(439, 3, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-19 11:24:35', '2026-04-19 11:24:35'),
(440, 1, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-19 15:36:10', '2026-04-19 15:36:10'),
(441, 1, 'otp_resent', 'otp_resent', 'OTP resent for sign in.', 'OTP resent for sign in.', '2026-04-19 15:37:54', '2026-04-19 15:37:54'),
(442, 1, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-19 15:38:40', '2026-04-19 15:38:40'),
(443, 1, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-19 16:00:14', '2026-04-19 16:00:14'),
(444, 3, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-19 16:00:30', '2026-04-19 16:00:30'),
(445, 3, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-19 16:01:19', '2026-04-19 16:01:19'),
(446, 3, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-19 16:03:10', '2026-04-19 16:03:10'),
(447, 1, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-19 16:03:30', '2026-04-19 16:03:30'),
(448, 1, 'otp_resent', 'otp_resent', 'OTP resent for sign in.', 'OTP resent for sign in.', '2026-04-19 16:12:48', '2026-04-19 16:12:48'),
(449, 1, 'otp_resent', 'otp_resent', 'OTP resent for sign in.', 'OTP resent for sign in.', '2026-04-19 16:15:14', '2026-04-19 16:15:14'),
(450, 4, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-19 16:21:28', '2026-04-19 16:21:28'),
(451, 4, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-19 16:21:48', '2026-04-19 16:21:48'),
(452, 4, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-19 16:23:14', '2026-04-19 16:23:14'),
(453, 1, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-19 16:23:25', '2026-04-19 16:23:25'),
(454, 1, 'otp_resent', 'otp_resent', 'OTP resent for sign in.', 'OTP resent for sign in.', '2026-04-19 16:35:14', '2026-04-19 16:35:14'),
(455, 1, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-19 16:36:36', '2026-04-19 16:36:36'),
(456, 1, 'user_archived', 'user_archived', 'Archived user account for jayramiro6@gmail.com.', 'Archived user account for jayramiro6@gmail.com.', '2026-04-19 16:37:30', '2026-04-19 16:37:30'),
(457, 1, 'logout', 'logout', 'User signed out.', 'User signed out.', '2026-04-19 16:47:27', '2026-04-19 16:47:27'),
(458, 2, 'otp_requested', 'otp_requested', 'OTP requested for sign in.', 'OTP requested for sign in.', '2026-04-19 16:50:38', '2026-04-19 16:50:38'),
(459, 2, 'login', 'login', 'User signed in successfully.', 'User signed in successfully.', '2026-04-19 16:50:53', '2026-04-19 16:50:53');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('dictsystem-cache-otp-cooldown:5a1bd59f854ebffc621122ce7b80a85e552ef9d4', 'i:1;', 1776626627),
('dictsystem-cache-otp-cooldown:5a1bd59f854ebffc621122ce7b80a85e552ef9d4:timer', 'i:1776626627;', 1776626627),
('dictsystem-cache-otp-cooldown:697e169c485e89c263e71f6aefc7a029c20b89d9', 'i:1;', 1776645399),
('dictsystem-cache-otp-cooldown:697e169c485e89c263e71f6aefc7a029c20b89d9:timer', 'i:1776645399;', 1776645399),
('dictsystem-cache-otp-cooldown:ecefb753f3354697c6700aab35a2d0135db4be93', 'i:1;', 1776644572),
('dictsystem-cache-otp-cooldown:ecefb753f3354697c6700aab35a2d0135db4be93:timer', 'i:1776644572;', 1776644572),
('dictsystem-cache-otp-cooldown:f017b7c812d2b725aabf12873bec0bad61859c47', 'i:1;', 1776646322),
('dictsystem-cache-otp-cooldown:f017b7c812d2b725aabf12873bec0bad61859c47:timer', 'i:1776646322;', 1776646322),
('dictsystem-cache-otp-cooldown:f67134631629597256513dfd354e43bc02049669', 'i:1;', 1776643312),
('dictsystem-cache-otp-cooldown:f67134631629597256513dfd354e43bc02049669:timer', 'i:1776643312;', 1776643312);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
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
(18, '2026_04_14_000001_add_assigned_provincial_head_id_to_reports_table', 2),
(19, '2026_04_18_184117_add_is_hidden_from_staff_dashboard_to_reports_table', 3),
(20, '2026_04_18_193938_add_is_hidden_from_staff_index_to_reports_table', 4),
(21, '2026_04_18_201756_add_is_hidden_from_admin_dashboard_to_reports_table', 5);

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `assigned_provincial_head_id` bigint(20) UNSIGNED DEFAULT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `submitted_at` timestamp NULL DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `reviewed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `review_comment` text COLLATE utf8mb4_unicode_ci,
  `is_hidden_from_staff_dashboard` tinyint(1) NOT NULL DEFAULT '0',
  `is_hidden_from_staff_index` tinyint(1) NOT NULL DEFAULT '0',
  `is_hidden_from_admin_dashboard` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `user_id`, `assigned_provincial_head_id`, `file_name`, `file_path`, `status`, `submitted_at`, `reviewed_at`, `reviewed_by`, `review_comment`, `is_hidden_from_staff_dashboard`, `is_hidden_from_staff_index`, `is_hidden_from_admin_dashboard`, `created_at`, `updated_at`) VALUES
(1, 3, 2, 'March 30 - April 3', NULL, 'approved', '2026-04-06 22:38:35', '2026-04-06 22:55:39', 2, NULL, 0, 0, 0, '2026-04-06 22:15:32', '2026-04-06 22:55:39'),
(2, 3, 2, 'April 6 - 10', NULL, 'for_revision', '2026-04-06 22:40:17', '2026-04-08 16:56:52', 2, 'fix your grammar', 0, 0, 0, '2026-04-06 22:19:05', '2026-04-08 16:56:52'),
(3, 3, 2, 'April 12 - 17', NULL, 'approved', '2026-04-06 22:40:34', '2026-04-08 17:25:22', 2, NULL, 0, 0, 1, '2026-04-06 22:22:27', '2026-04-18 12:25:05'),
(4, 3, 2, 'April 20 - 26', NULL, 'pending', '2026-04-07 16:27:49', NULL, NULL, NULL, 0, 0, 0, '2026-04-06 22:33:43', '2026-04-18 20:19:12'),
(5, 3, 2, 'April 27 - May 1', NULL, 'pending', '2026-04-07 18:39:11', NULL, NULL, NULL, 0, 0, 0, '2026-04-06 22:38:10', '2026-04-18 20:01:20'),
(8, 3, 2, 'April 27 -  May 2', NULL, 'approved', '2026-04-09 10:50:13', '2026-04-18 11:53:24', 2, NULL, 0, 0, 1, '2026-04-07 01:39:49', '2026-04-18 12:24:16'),
(9, 3, 2, 'May 4 - 7', NULL, 'pending', '2026-04-09 22:28:58', NULL, NULL, NULL, 0, 1, 0, '2026-04-07 17:34:45', '2026-04-18 20:00:12'),
(12, 3, 2, 'May 4 - 8', NULL, 'draft', NULL, NULL, NULL, NULL, 0, 0, 0, '2026-04-07 17:52:00', '2026-04-07 17:52:00'),
(15, 3, 2, 'May 4 - 9', NULL, 'draft', NULL, NULL, NULL, NULL, 0, 0, 0, '2026-04-07 18:09:57', '2026-04-07 18:09:57'),
(27, 3, 2, 'April 6 - 8', NULL, 'draft', NULL, NULL, NULL, NULL, 0, 0, 0, '2026-04-09 10:27:48', '2026-04-15 06:07:27'),
(28, 9, 2, '4/10/2026 - 4/11/2026', NULL, 'approved', '2026-04-14 15:27:11', '2026-04-14 15:39:50', 2, NULL, 0, 0, 1, '2026-04-14 15:26:20', '2026-04-18 12:25:29'),
(29, 3, NULL, 'May 18 - 23', NULL, 'draft', NULL, NULL, NULL, NULL, 0, 0, 0, '2026-04-15 05:41:21', '2026-04-15 06:06:48'),
(31, 10, 7, 'April 16 - 18', NULL, 'approved', '2026-04-15 16:33:36', '2026-04-15 16:40:40', 7, NULL, 0, 0, 0, '2026-04-15 16:32:45', '2026-04-15 16:40:40'),
(32, 12, 2, 'April 1 - 4', NULL, 'for_revision', '2026-04-15 18:22:07', '2026-04-17 06:54:54', 2, 'nn', 0, 0, 0, '2026-04-15 18:19:42', '2026-04-17 06:54:54');

-- --------------------------------------------------------

--
-- Table structure for table `report_entries`
--

CREATE TABLE `report_entries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `report_id` bigint(20) UNSIGNED NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `activity` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `details` text COLLATE utf8mb4_unicode_ci,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `report_entries`
--

INSERT INTO `report_entries` (`id`, `report_id`, `start_date`, `end_date`, `activity`, `details`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 1, '2026-03-30', NULL, 'Strategic Planning & Roadmap Alignment:', 'Conducted an exhaustive review of current project milestones against the Q2 roadmap. Identified three potential bottlenecks in the procurement pipeline and drafted a mitigation strategy involving alternative vendor sourcing.', 'completed', '2026-04-06 22:15:32', '2026-04-06 22:15:32'),
(2, 1, '2026-03-31', NULL, 'Stakeholder Communication & Syncing:', 'Facilitated a cross-functional synchronization meeting with the Engineering and Marketing departments. Documented all action items, updated the centralized project dashboard, and ensured all dependencies were mapped for the upcoming sprint.', 'completed', '2026-04-06 22:15:32', '2026-04-06 22:15:32'),
(3, 1, '2026-04-01', NULL, 'Resource Allocation & Capacity Planning:', 'Analyzed team bandwidth to ensure optimal distribution of tasks. Adjusted the project timeline by 48 hours to accommodate an unplanned security patch, ensuring no degradation in final deliverable quality.', 'completed', '2026-04-06 22:15:32', '2026-04-06 22:15:32'),
(4, 1, '2026-04-02', NULL, 'Data Synthesis & Performance Metrics:', 'Aggregated weekly performance data into a high-level executive summary. Utilized advanced pivot tables and data visualization tools to highlight a 5% trend growth in user retention, while flagging a slight dip in organic reach for further investigation.', 'completed', '2026-04-06 22:15:32', '2026-04-06 22:15:32'),
(5, 1, '2026-04-03', NULL, 'Market Research & Competitive Intelligence:', 'Conducted a thorough competitive analysis of three primary industry rivals. Synthesized findings into a SWOT analysis report, focusing on their recent pricing shifts and how we can pivot our value proposition to remain competitive.', 'completed', '2026-04-06 22:15:32', '2026-04-06 22:15:32'),
(6, 2, '2026-04-06', NULL, 'System Optimization & Maintenance:', 'Performed a deep-dive audit of the current server architecture. Identified and resolved a recurring latency issue by optimizing database queries and implementing a more aggressive caching layer, resulting in a 15% increase in load speeds.', 'completed', '2026-04-06 22:19:05', '2026-04-09 22:34:00'),
(7, 2, '2026-04-07', NULL, 'Documentation & Standard Operating Procedures (SOPs):', 'Authored a comprehensive 12-page technical manual for the new internal CRM module. This includes troubleshooting guides, API integration steps, and user permission hierarchies to streamline the onboarding process for new hires.', 'completed', '2026-04-06 22:19:05', '2026-04-09 22:34:00'),
(8, 2, '2026-04-08', NULL, 'Quality Assurance (QA) & Testing:', 'Executed a full suite of regression tests on the latest software build. Logged 14 minor UI bugs and 2 critical backend exceptions; collaborated directly with the development team to verify fixes and ensure a stable production environment.', 'completed', '2026-04-06 22:19:05', '2026-04-09 22:34:00'),
(9, 2, '2026-04-09', NULL, 'Initiation, Audit, and Foundations', 'Conducted a comprehensive review of existing system protocols. Identified vulnerabilities within the authentication flow and mapped out a remediation plan. This involved documenting current \"as-is\" processes to establish a baseline for the month\'s upgrades.\r\n\r\nMet with department leads to define the month\'s Key Performance Indicators (KPIs). Finalized the project scope for the upcoming development cycle, ensuring all technical requirements were documented and approved.', 'completed', '2026-04-06 22:19:05', '2026-04-09 22:34:00'),
(10, 2, '2026-04-10', NULL, 'Environment Setup:', 'Configured necessary development environments and secured administrative credentials (including app-specific passwords and API keys) to ensure a seamless workflow for the following three weeks.', 'completedd', '2026-04-06 22:19:05', '2026-04-09 22:34:00'),
(11, 3, '2026-04-13', NULL, 'Core Module Implementation:', 'Focused on the primary build phase of the new system. Successfully integrated the logic for the automated notification engine and the secure login framework. This phase involved writing over 1,500 lines of modular, reusable code.', 'completed', '2026-04-06 22:22:27', '2026-04-06 22:22:27'),
(12, 3, '2026-04-14', NULL, 'Iterative Testing:', 'Performed \"unit testing\" on each individual component as it was built. Debugged critical logic errors in the data-handling layer, ensuring that user inputs are processed securely and efficiently without system crashes.', 'completed', '2026-04-06 22:22:27', '2026-04-06 22:22:27'),
(13, 3, '2026-04-15', NULL, 'Technical Documentation:', 'Started a living document for the system architecture, detailing the integration points between the frontend interface and the backend database to assist with future scalability.', 'completed', '2026-04-06 22:22:27', '2026-04-06 22:22:27'),
(14, 3, '2026-04-16', NULL, 'System Integration Testing (SIT):', 'Transitioned from component building to full-system integration. Verified that the communication between the security modules and the user database is functioning at 100% accuracy.', 'completed', '2026-04-06 22:22:27', '2026-04-06 22:22:27'),
(15, 3, '2026-04-17', '2026-04-12', 'Performance Tuning:', 'Analyzed system response times and optimized backend queries. Reduced the latency of the verification cycle by roughly 20% through refined logic and improved server-side handling.\r\n\r\nImplemented final security layers, including multi-factor logic and encrypted data transmission, to ensure the system meets modern industry standards for data protection.', 'completed', '2026-04-06 22:22:27', '2026-04-06 22:22:27'),
(16, 4, '2026-04-20', NULL, 'Final Quality Assurance (QA):', 'Conducted a final \"User Acceptance Test\" (UAT) to simulate real-world usage scenarios. Resolved three minor UI/UX inconsistencies to improve the overall user journey and interface clarity.', 'completed', '2026-04-06 22:33:44', '2026-04-06 23:13:19'),
(17, 4, '2026-04-21', NULL, 'End-of-Month Performance Review:', 'Aggregated data from the past 30 days to measure progress against the initial KPIs. Successfully met 95% of planned milestones, with the remaining 5% carried over to next month\'s \"polishing\" phase.', 'completed', '2026-04-06 22:33:44', '2026-04-06 23:13:19'),
(18, 4, '2026-04-22', NULL, 'Reporting & Handover:', 'Produced a final executive summary of the month’s achievements. Drafted the \"Month Ahead\" roadmap, prioritizing the next phase of feature rollouts and user onboarding strategies.', 'completed', '2026-04-06 22:33:44', '2026-04-06 23:13:19'),
(19, 4, '2026-04-23', NULL, 'Deep-Dive System Audit:', 'Spent the initial week conducting a forensic analysis of the existing operational framework. This included mapping out data flow diagrams to identify \"hidden\" bottlenecks that were causing intermittent delays in reporting. By isolating these variables, I established a clear baseline for performance improvement.', 'completed', '2026-04-06 22:33:44', '2026-04-06 23:13:19'),
(20, 4, '2026-04-24', '2026-04-26', 'Requirement Gathering & Synthesis:', 'Conducted one-on-one interviews with six key internal stakeholders to identify pain points in the current workflow. Synthesized these qualitative insights into a \"Functional Requirements Document\" (FRD) that served as the North Star for the month\'s technical pivots.\r\n\r\nIdentified three high-priority risks regarding data integrity during the planned transition. Developed a comprehensive rollback plan and a temporary fail-safe environment to ensure zero downtime during active testing phases.', 'completed', '2026-04-06 22:33:44', '2026-04-06 23:13:19'),
(21, 5, '2026-04-27', NULL, 'Modular Framework Construction:', 'Engineered the core logic of the new project module using a modular design pattern. This approach ensures that the code is not only functional for current needs but also scalable for future updates. Focused specifically on building a \"clean\" API layer that allows for seamless data exchange between legacy systems and new interfaces.', NULL, '2026-04-06 22:38:10', '2026-04-06 22:38:10'),
(22, 5, '2026-04-28', NULL, 'Database Schema Optimization:', 'Restructured the backend data tables to reduce redundancy. By implementing more efficient indexing and normalizing the database, I successfully decreased the storage overhead and improved query execution times, leading to a more responsive user experience.', NULL, '2026-04-06 22:38:10', '2026-04-06 22:38:10'),
(23, 5, '2026-04-29', NULL, 'Security Protocol Implementation:', 'Integrated advanced encryption standards (AES-256) for data at rest. This involved a multi-day effort to overhaul the credential management system, ensuring that all access tokens are rotated automatically and sensitive user information is obfuscated according to compliance standards.', NULL, '2026-04-06 22:38:10', '2026-04-06 22:38:10'),
(24, 5, '2026-04-30', NULL, 'Rigorous Regression Testing:', 'After the build phase, I initiated a 5-day regression testing cycle. This involved running over 50 unique test cases to ensure that new features didn\'t break existing functionalities. Documented 12 \"edge-case\" bugs that were previously undiscovered and successfully deployed patches for all of them.', NULL, '2026-04-06 22:38:10', '2026-04-06 22:38:10'),
(25, 5, '2026-05-01', NULL, 'User Experience (UX) Refinement:', 'Analyzed user heatmaps and click-stream data to identify areas of confusion in the interface. Redesigned the navigation menu and simplified the input forms, which reduced the average time-to-completion for standard tasks by approximately 30%.', NULL, '2026-04-06 22:38:10', '2026-04-06 22:38:10'),
(26, 4, '2026-04-27', NULL, 'Reports', 'Drafted the \"Month Ahead\" roadmap, prioritizing the next phase of feature rollouts and user onboarding strategies.', 'completed', '2026-04-06 23:13:19', '2026-04-06 23:13:19'),
(33, 8, '2026-04-27', NULL, 'Cross-Departmental Feedback Loops:', 'Presented the \"Beta\" version to the operations team for a \"stress test.\" Collected feedback regarding real-world usage and spent 48 hours rapidly iterating on the UI components to align with their daily operational habits.', 'completed', '2026-04-07 01:39:49', '2026-04-18 11:19:35'),
(34, 8, '2026-04-28', NULL, 'Production Environment Rollout:', 'Managed the phased deployment of the updated system. Monitored server logs in real-time during the \"Go-Live\" window to intercept and resolve any immediate post-deployment anomalies. The rollout achieved a 99.9% success rate with zero reported system outages.', 'completed', '2026-04-07 01:39:49', '2026-04-18 11:19:35'),
(35, 8, '2026-04-29', NULL, 'Comprehensive Knowledge Base Creation:', 'Authored a detailed \"Project Wiki\" containing 15+ articles. This includes step-by-step video walkthroughs, a Frequently Asked Questions (FAQ) repository, and a technical troubleshooting guide for the IT support desk.', 'completed', '2026-04-07 01:39:49', '2026-04-18 11:19:35'),
(36, 8, '2026-04-30', NULL, 'Post-Mortem Analysis & Future Roadmap:', 'Concluded the month by analyzing the delta between our initial goals and final outcomes. Produced a \"Lessons Learned\" report and a strategic roadmap for next month, prioritizing automated reporting and AI-driven data insights.', 'completed', '2026-04-07 01:39:49', '2026-04-18 11:19:35'),
(37, 8, '2026-05-01', NULL, 'Managed the phased deployment of the updated system.', 'Monitored server logs in real-time during the \"Go-Live\" window to intercept and resolve any immediate post-deployment anomalies. The rollout achieved a 99.9% success rate with zero reported system outages. H', 'completed', '2026-04-07 01:52:10', '2026-04-18 11:19:35'),
(38, 9, '2026-05-04', NULL, 'Conducted the Daily Morning Sync and Sprint Planning session.', 'Facilitated a 45-minute briefing with the cross-functional team (Design, Development, and QA) to align on today’s high-priority deliverables. We identified two potential bottlenecks regarding the API integration for the \"Client X\" portal. I delegated the troubleshooting tasks to the lead dev and re-prioritized the UI feedback loop to ensure the timeline remains intact despite the technical hurdle.', 'completed', '2026-04-07 17:34:45', '2026-04-18 19:59:41'),
(39, 9, '2026-05-05', NULL, 'Mid-Project Milestone Review with Jelan Corp.', 'Prepared and presented a detailed progress deck for the \"Phase 2\" rollout. This involved translating technical jargon into actionable business insights for the client’s executive board. I addressed their concerns regarding the Q4 budget allocation and successfully secured an approval for the extended scope of work. Followed up the meeting by circulating minutes and an updated Gantt chart to all stakeholders to maintain transparency.', 'completed', '2026-04-07 17:34:45', '2026-04-18 19:59:41'),
(40, 9, '2026-05-06', NULL, 'Comprehensive Audit of User Acceptance Testing (UAT) Feedback.', 'Spent three hours meticulously reviewing 45 logged tickets in the Jira backlog. I categorized these into \"Critical Bugs,\" \"UI Enhancements,\" and \"Future Scope\" to streamline the developers\' workflow. I personally verified the fix for the payment gateway encryption error (Ticket #882) by running three separate simulation environments, ensuring that the 256-bit AES encryption is functioning as specified in the security protocol.', 'completed', '2026-04-07 17:34:45', '2026-04-18 19:59:41'),
(41, 9, '2026-05-07', NULL, 'Standard Operating Procedure (SOP) Development', 'Noticed a recurring in efficiency in how the team handles digital asset handovers. To rectify this, I authored a 10-page SOP document outlining a new naming convention and folder hierarchy.  This included creating a visual flowchart for the creative team to follow, which is projected to reduce \"search-and-retrieval\" time by approximately 15% across the department.', 'completed', '2026-04-07 17:34:45', '2026-04-18 19:59:41'),
(49, 12, '2026-05-04', NULL, 'Conducted the Daily Morning Sync and Sprint Planning session.', 'Facilitated a 45-minute briefing with the cross-functional team (Design, Development, and QA) to align on today’s high-priority deliverables. We identified two potential bottlenecks regarding the API integration for the \"Client X\" portal. I delegated the troubleshooting tasks to the lead dev and re-prioritized the UI feedback loop to ensure the timeline remains intact despite the technical hurdle.', 'completed', '2026-04-07 17:52:00', '2026-04-17 08:50:38'),
(50, 12, '2026-05-05', NULL, 'Mid-Project Milestone Review with Jelan Corp.', 'Prepared and presented a detailed progress deck for the \"Phase 2\" rollout. This involved translating technical jargon into actionable business insights for the client’s executive board. I addressed their concerns regarding the Q4 budget allocation and successfully secured an approval for the extended scope of work. Followed up the meeting by circulating minutes and an updated Gantt chart to all stakeholders to maintain transparency.', 'completed', '2026-04-07 17:52:00', '2026-04-17 08:50:38'),
(51, 12, '2026-05-06', NULL, 'ab cd e f g h i j k l m n o p q r s t u v w x y z', 'a b c d e f g h i j k l m n o p q r st u v  w x yz \r\na b c d e f g h i j k l m n o p q r st u v w x y z', 'completed', '2026-04-07 17:52:00', '2026-04-17 08:50:38'),
(52, 12, '2026-05-07', NULL, 'a b c d e f g h i j k l m n o p q r st u v w x yz', 'a ba c d e f g h i j k lm n o p q r st u v wx yz nnvv GH  NM', 'completed', '2026-04-07 17:52:00', '2026-04-17 08:50:38'),
(61, 15, '2026-05-04', NULL, 'Conducted the Daily Morning Sync and Sprint Planning session.', 'Facilitated a 45-minute briefing with the cross-functional team (Design, Development, and QA) to align on today’s high-priority deliverables. We identified two potential bottlenecks regarding the API integration for the \"Client X\" portal. I delegated the troubleshooting tasks to the lead dev and re-prioritized the UI feedback loop to ensure the timeline remains intact despite the technical hurdle. \r\n- \r\nhuhj\r\n\r\njghh\r\n\r\njjj\r\n\r\nhh', 'completed', '2026-04-07 18:09:57', '2026-04-09 10:21:44'),
(62, 15, '2026-05-05', NULL, 'Mid-Project Milestone Review with Jelan Corp.', 'Prepared and presented a detailed progress deck for the \"Phase 2\" rollout. This involved translating technical jargon into actionable business insights for the client’s executive board. I addressed their concerns regarding the Q4 budget allocation and successfully secured an approval for the extended scope of work. Followed up the meeting by circulating minutes and an updated Gantt chart to all stakeholders to maintain transparency.\r\n\r\n\r\nihh', 'completed', '2026-04-07 18:09:57', '2026-04-09 10:21:44'),
(63, 15, '2026-05-06', NULL, 'ab cd e f g h i j k l m n o p q r s t u v w x y z', 'a b c d e f g h i j k l m n o p q r st u v  w x yz \r\na b c d e f g h i j k l m n o p q r st u v w x y z', 'completed', '2026-04-07 18:09:57', '2026-04-09 10:21:44'),
(64, 15, '2026-05-09', NULL, 'a b c d e f g h i j k l m n o p q r st u v w x yz', 'a ba c d e f g h i j k lm n o p q r st u v wx yz', NULL, '2026-04-07 18:09:57', '2026-04-09 10:21:44'),
(68, 9, '2026-04-05', NULL, 'c', 'cNBNjk', 'c', '2026-04-07 20:31:02', '2026-04-18 19:59:41'),
(83, 27, '2026-04-06', NULL, 'my \r\n\r\nhdgd \r\n\r\n\r\ndd', 'bvdb  \r\n\r\n\r\nndjb', 'he', '2026-04-09 10:27:48', '2026-04-09 10:28:37'),
(84, 27, '2026-04-07', NULL, 'N/Abbn', 'vbb', NULL, '2026-04-09 10:27:48', '2026-04-09 10:28:37'),
(88, 28, '2026-04-10', NULL, 'gfhfgbgf', 'fgfv bgfdgf', 'fvgfcvfgb', '2026-04-14 15:26:20', '2026-04-14 15:26:20'),
(89, 28, '2026-04-11', NULL, 'hfbfdgb', 'bfdgfdvf', 'dvfcfvfdgfvb', '2026-04-14 15:26:20', '2026-04-14 15:26:20'),
(90, 29, '2026-05-18', NULL, 'Executive Summary\"', 'This week, the primary focus was centered on the architectural refinement and security hardening of the core authentication module. I successfully integrated a robust email-based OTP (One-Time Password) system to replace the previous static credential method, significantly reducing the risk of unauthorized access. A substantial portion of the week was dedicated to configuring the SMTP relay and securing application-specific credentials to ensure seamless delivery of verification codes. Beyond the security implementation, I conducted a comprehensive audit of the existing codebase to identify and resolve several latent bugs in the session management logic. These optimizations have resulted in a 15% improvement in login response times and a more stable user experience. Additionally, I collaborated with the design team to ensure the front-end verification UI is intuitive and provides clear feedback during the authentication lifecycle. The week concluded with a full suite of integration tests, confirming that the new security layers are functioning as intended without disrupting peripheral system services.', 'completed', '2026-04-15 05:41:21', '2026-04-18 23:39:27'),
(91, 29, '2026-05-19', NULL, 'Backend & Security Focus', 'Throughout this week, the primary objective was the successful deployment of the secure authentication module. I focused on building the logic for email-based OTP delivery, ensuring that verification codes are generated, sent, and validated within a strict 300-second window to maintain high security standards. This involved configuring SMTP relay settings and generating the necessary application-specific credentials for the mail server. Additionally, I addressed several edge cases in the user registration flow where duplicate entries were causing database collisions. By the end of the week, the system was successfully handling concurrent login requests with a 100% success rate in local testing environments, and all new security endpoints have been fully documented for the rest of the team.', 'completed', '2026-04-15 05:41:21', '2026-04-18 23:39:27'),
(92, 29, '2026-05-20', NULL, 'Front-End & UI/UX Focus', 'This week’s efforts were dedicated to refining the user interface and improving the overall responsiveness of the dashboard. I overhauled the CSS framework to ensure a mobile-first experience, resolving layout breaks on smaller screens and tablets. A significant amount of time was spent implementing real-time form validation for the user profile section, providing immediate feedback to users and reducing invalid data submissions by approximately 40%. I also collaborated with the backend team to integrate the new API endpoints for the notification system, ensuring that status updates appear dynamically without requiring a page refresh. All design assets have been updated in the shared repository, and the final UI polish was approved during Friday’s design review.', 'completed', '2026-04-15 05:41:21', '2026-04-18 23:39:27'),
(93, 29, '2026-05-21', '2026-05-22', 'Quality Assurance & Testing Focus', 'My activities this week centered on comprehensive system testing and the establishment of a regression testing suite. I conducted rigorous manual testing on the newly implemented login and password recovery features, identifying three critical bugs related to session hijacking vulnerabilities which were immediately patched. I also drafted and executed twenty-five automated test scripts using Selenium to cover the core user journey from landing page to checkout. Beyond bug detection, I performed a performance stress test to determine the system\'s breaking point under high traffic, providing the infrastructure team with data needed for upcoming server scaling. The week concluded with a clean bill of health for the current build, allowing the project to move into the beta phase.', 'completed', '2026-04-15 05:41:21', '2026-04-18 23:39:27'),
(94, 29, '2026-05-23', NULL, 'General Project Management Focus & Technical Support & Maintenance Focus', 'This week was defined by stakeholder alignment and the finalization of the Q3 project roadmap. I facilitated three cross-functional meetings to ensure that the development, marketing, and sales departments are synchronized on the upcoming feature release schedule. A major accomplishment was the completion of the project risk assessment, where I identified potential bottlenecks in the third-party API integration and established a contingency plan with the engineering lead. I also managed the budget reconciliation for the month, ensuring all software licensing and external contractor invoices were processed on time. By Friday, the project remains 5% ahead of the original timeline, with all primary milestones for the current sprint successfully met.\r\n\r\nThe week was primarily focused on resolving high-priority support tickets and maintaining system uptime. I successfully cleared a backlog of fifteen tickets, most of which were related to user access issues and localized browser compatibility errors. Between support tasks, I performed a scheduled maintenance update on the production server, which included upgrading the database engine and clearing out orphaned logs to optimize storage capacity. I also created a new \"Frequently Asked Questions\" internal document for the help desk team to streamline the resolution of common technical inquiries. System monitoring tools indicated 99.9% uptime for the week, with an average response time for critical tickets dropping from four hours down to two.', 'completed', '2026-04-15 05:41:21', '2026-04-18 23:39:27'),
(98, 31, '2026-04-16', '2026-04-17', 'test', 'test', 'test', '2026-04-15 16:32:45', '2026-04-15 16:40:11'),
(99, 31, '2026-04-18', NULL, 'test', 'test', 'test', '2026-04-15 16:32:45', '2026-04-15 16:40:11'),
(100, 32, '2026-04-01', '2026-04-03', 'Provision of Technical Assistance', '-Monitored and Assist', 'Successful', '2026-04-15 18:19:42', '2026-04-15 18:21:55'),
(101, 32, '2026-04-06', '2026-04-06', 'Provincial Office Support', '-Attended Flag Ceremony', 'Completed', '2026-04-15 18:19:42', '2026-04-15 18:21:55');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('1qSWqdrLnklyy76QDFpCFpVIJmTtJ12rlBhN9iqQ', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'ZXlKcGRpSTZJbVZ0YmsxME9VMVBNalZhTjNFd1lrZE9RM0pEVlZFOVBTSXNJblpoYkhWbElqb2lTVkZUVjNBMlpqbHJPRTgxVW00M1RFSlRVSGh0ZDJ0RlVETk5UR3hCTTFOaVFtcHdjVlJzZUhwSFlYb3haa1pYYkRCTVRFUmhORTl2VWtGTWEzbE1NMlptWkhKa2NtNVBaMUY2UXpOU2RVbGpNbEpXUW1OdlRHUjVObTAxY0dveEt6VndSVEpTVFdkb1ZWSTJhakJQWjBwaWJrZERSQzgzUVM5S0sweDZla3hoTlhKaEwxRjZUbGREVTBKcGNWQXhlV3hrTWt4eGJVWnVaemd3U1dZd1MxVTBXbE5WTXpKUWR6WlhTeXRXWnpSSk4ySlVMM2xWY0VaVE4yUXJjVXhPTlhRd09WRjNjM0I0U0RWS1dHcHVVbUpvVVdWVVIyRXdVMEZ5Tmlzelp6QlBUVUp3WkdGeFVGaFZSVGs0TmxwWmJVNVBLMGxIYlhZd1RqVmFhSFJDTVdNMFYyNHJTRVZOTHpSRVdtWkRWbmRHZVZkYVdYSnZVM2xoWlRReVdHUm1MMUJ3YjBwWFYzY3pORFU0Ym1kaVRXVnpVbkkwY1ZZME9FOUNjV1F3VkdWcE4zaE1kVWd5YUU1MVUwUlFhV3BQSzFwVFZsQlJlRmh2YlhoYVVVc3ZkbWxqTkRGSmIyNXhNM0ZDUVhoSlNWa3pibkZxTm5rclNqZFVTMFY1YUVSb1JDOWtXakp5V0hBM05tVmtkVGhtWkVaRVEwMW9RbTkwTkhSQmRqbEtMeXN4YzNaS2FteFBXbU52WjJKMVdrOTVTRmhFYURSUlJITlJLMFZ0VUVsdFJFWnBNMkZ4VGxaa2FEaFVPVEZ5U1ZKbE9HRnJaSGRXYURWWVZGSTFOWFpTU3pSTGMxRlhPSFZVTTJSM09FWnJiVTFRSzBReVQzbExhMjFhZVd0T2RFWTRXVXR1VVRZeVZreHNReTlUV2poS2NWSktPRlIwZUhKS1EzbGlaRkZxVUVaRU0xb3pTMnRNVkVKYVJYbHViMFJSY0hsSGJsUTFTSFZ2VDJ4c01VcGlJaXdpYldGaklqb2lNekZsWXprMk5EYzRNR05qTkRjM01qVTNOR1prTmpVM01qYzRNV0ZrWVRVMlpXWmtaVEk0T0RBNU16WXlZalJqTW1Sa1pUWTNOREk0WkdFNU9EbGpNQ0lzSW5SaFp5STZJaUo5', 1776641875),
('6bwaIatczdoFfurIgcwNBPgBOC2megNr0Wa8Qc0p', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'ZXlKcGRpSTZJblZtZVVaWldqQm5TWEpWUkZRMGRHVm1jMmR1V2tFOVBTSXNJblpoYkhWbElqb2lkRTFIUmtOcVFYSnhkR3RoTWxabWEyUjZVVkpNWVZZMGRtMUhiRGxEWWxrM04ybE9hblJETkN0dWRFVjBNRGxFU0haNVdXaDVjakVyV2xSb2RHeExSa05ZUmxOcmFTdFhlQ3N6WW1GeWIxcGtXVkp2ZGpGRE5XbGtLMUJRTkVWalFtSXhja05VU2tOT1dGRlBVamc0UkZVM055dGtUV3BWY0Vsak5EbHNUMk0wVUdOb2IzQnpZa1Y1UWtwMFJrMXBWM0F5VmxvNWExUXljV1Y2YzBjMFRFTnpVR2cyTHpndk9HNDFXWHB3YzNadlZsWlllVWRDYW10WFVXTkdha0ZCT1Radk9EbEpkbVpGWlhkT1NWUnhOWEZPU1hOS2RYUkVMMVl5Wm5GS2RtZEtkbEkxWldScWJVOUNXRGxVUVhGSlprOVpaVXhFZFV0bk4xaE5MMjR3T0hCWGJqWkVVbEkzZDIxeFlVTjFPVmhIZFRCSlRXZ3lhME15WkVab1RsQmtTVUpwZEhWMFZVWkJjM2xVZEZneFVEUmxPRGg1ZWs5VFZWWTRRMlpwWTI5bmNHdDZhRmhFVFVWdWIwUmxMMU14VlhwcFdqTTFibTR3Y21KTFMwVkhVMEZ5ZUVFM01UTTVMMjl1YVZkUU0yZG9ORnAyVW1sTmNrWkhPSHBaZUhRNGNUbElRbGRDUjNaNFdGaG9aeXRQVHpnM0x6VkhiRTVFWVc1TFpqVmhiMjlSVFZjM1NtZ3lMemQyTW5CQ2FGSkxXVWwxT1haMlpITkhVREprYlhWUU4xVXpUbWxGTlhwM1J6Y3laSEpKWVd4TmRUQjVjVFp5YjJ0UFUxUllWWHBXZURsVFNXeHBiSEpvYVZCRGJVODBhbGRTUjFkUlFXZGpMMk56VW13NE5URnpOMGxrWWxWS1VWaEJVRVYxU1ZSTFJXaE5USHBzZG5aTGNtRjJZM1JKZVhwQlIySkNOREpSVWtaR09GSnNWbVJQY0RGR1VsQXZVbVJxTXk5TmEyTkpJaXdpYldGaklqb2lNakppTlRFMU5EWTROemxrTm1JeE5HVmhOR1JsTlRaaU16TXlNakJoTlRJd05HUTNNalE0Tm1SbU5EQm1NelF5TldOaE5tTmpPVGxsTkRJM01qRmxOeUlzSW5SaFp5STZJaUo5', 1776646239),
('cO06xIFeQu5uZvMEPyc46nVbcE8d4UqW0eXHYhYi', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'ZXlKcGRpSTZJa2N5VUd4bFNFWTVLM3BHWWpkRk5XdG1ObVppVldjOVBTSXNJblpoYkhWbElqb2lWWEYwWjJrdlZWbHJkV1ExZWpSTlltMXRRM28zUW1aRmJXdFFVVkYzUlRBeGFHbHdVVWhtTmtkU2RWcGxkM1JpYTIxSmIwcGpZbmxCWVM5cFVXY3ljVmQ1ZG5WQmRsZHlVWEJqTlRkNVowNVBORGxpZEZWYU5tRlNjR1JZT0hoaGJIcDBTSFpEVEVSUGRtRjVVbEV4ZHpOS2FFZEtXV3BUUW5vME1Fb3dkRkE0WVVKRVRHMUVhREI0YWxWR1lVcGljVEUzVlc1RFpHSm9URzFUTDNkdFJYaGFPR0oxWjJWT1ZEQmFTR2RvTHpKSFMwbFhPVWRaWTJkNE1ra3dUV2x4VUc4d05uSjVXamxXVEVwd2EzTkRXak54VkU0NVVURk5kbVZ6VGxwclN6UllXV28zYXpWRFRsTlJOVmQxUlV0eWR6UTRLMEZCUW5WV1dYZzRLemxvV0hOalJrNW9aM2xSUlRSeFRrWjJUakJhWkZCRlZUazRhMngyWm5wSVVrcHlVak40Vm5ONVl6WkdOMFoxYTBGalJESmphbGxVTDNWd1ZHNU5SRmQyZVVSVVJFaFZUbFZGV1dZNVp6TkpSSGxJUVRSbFNFeGpSRFZ2YW1acVlsbE5iMkZEVW1oNVZtbHBMMjB4YlRoYVFrSktSR3czUkV3ck5EUlNZM0ZRZWxaTGJFOW5SM0p6V20xcWVtNXlVV1ozTkdwSlJFWnBNekJwVHpjMlVsbFVaMVpSVEdWNmVrYzJiSEY1VTJGWU9EQXpPRWxpVEZWcWIyczRPWGsyVUhaRmFXcHROVWwzYVhGMFowNVBSVWhMTW1oQlZYVldWek4wV0ZSUVZVeDJkRFV2TUhkQ1ZsbENUazlHU25jME1UZHZSMDltU1dWa2RDOUlkMFozZDIxeU1qbGFiMmM1VjJOd2VVSndNRVJtVjNKTmFUTkRWMUJwT1ZaTVJIVnVPVEpPYW1SeGVXMXNUMmhKZURoSVVYZFdZWGhXTUUxd2FIRkdlbk0yU3paaU5GVnVJaXdpYldGaklqb2lOakppTldWbE9ETTVPVGd5TURFek1EQmhNekkwTVROaFpUaG1ZakpsWkdSbVkyUmpNV000TWpnMU16SXlOekkxT1RnNU1EZGlOREEzTnpVMU1qWmtOQ0lzSW5SaFp5STZJaUo5', 1776643231),
('DfMiHrpsN04dmKa86VRg9RMCH4C3c3HzLdyhE5WL', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'ZXlKcGRpSTZJbFZFTVRWS1FsUlJXbVZqWVc5T1VVUkNPRk1yTTFFOVBTSXNJblpoYkhWbElqb2lVVlU1YUdKb2JFaHphV3d5VDNoUldEaExTMFZCZWpGWlREUjFhVE4xYVVsVmIwRTFNR05JVUdKV1NtZFNVV0Z2SzBsME4yeDJVRmRuU0doTk0xRTBPR0ZEVkVsSlkwMTZSbVpWYUVwMGNrOWplVTVpU3l0eGJGQkNPSFJKVW01TE1GRmFZVmhWY2tSVmJUVllXbVJxTTFWamJtUlVabWRPYXpSTk9IZFpPR1pFY1VsTlkyOUpla2RxZGtoUmVXTlZVRlpVVmxOSWMwWjVOVm80V2pjeE0wOXBWbWs1VDBWNE5ETlhUM1F5ZG1GVFJEZHdUWGxwU0hwaVVrbGtTREowYVZoMFJYSnFWM05OV2xGYWVFOVFSREJyYzI5TmJrOXFOVlpFVW1rdlQydzFkR3BXWm5VNGJYTmpObHB4VEVKT2IySk5PWFZOZGxoaGNTdExTSE0yUnpoNFVXMW9OVTR6TnpCT1NFbDNWbXBZTmxCMFJWaDJRbVpyVlVONVVsbE1kVzV3U0RCclFsSm5lRVZJS3pscVozbElZamg2TkVZMGFYVldNWGxwUXl0d1ZYa3pVRmcwYUROcVlTOU1SVmR2UlVoNFdFdE5WVXcwYVRZMWFYazNTU3RGWmk5T1VuaDFjV0l5VTBaQ1lWQXpSa0VyVkZCV1REVmpURkJyTjFWTlExSXhVR3RNTjJOd1pWa3JiVGRCTWxaWllraHVUMjB5YWtVNU1qTk5VVk5pVGxkV1MzbzVaMFl4UjB0cldUbGxNMWhwVkhWblNVWlBWelJTYjJZMFZFTnhkV2RwYm5wNlJrSmpNRkJxVUhOS1VWSkdabkZIYm5WSkwzVldVRGw2TVRsbkswNXlibmwyUzJsVWNYZHZiaTl6VEN0WmVVMWFNa3BHY2pkRk1VRnlUa3BFWjBaVVluaGFWSEZFTDA1TVMxazNTRVV3TlhKcFlqSjVNRTFNUlZCa2JXcHlhMjVhYVV0eE5uZGFhbk4zUzNGeWVsWmtObFZvYkRRNWNDOTFJaXdpYldGaklqb2lNekl3TURObVpEazBOMkU0WlRFMU9XRmlaR0ppWm1Jd09HUXdOemN6TkRjeE5UaGxNRGt3TUdJd00yTTBNMlUzT0RneE5UQTBNV1kzWm1FMVlXRXpZeUlzSW5SaFp5STZJaUo5', 1776645369),
('HZG84n4DsNzMovn865RwMqAMWmVIag0EGe1hqVWq', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'ZXlKcGRpSTZJbU0yZW5FNU5GYzRMMEpSWTNWbFNrZDJSRXB1Y2xFOVBTSXNJblpoYkhWbElqb2lWVkpLUXlzM0sySk5SMFprWlhabFNWWlBjMmt3YXpJMFlYbzBiRmRxYXpkRU1ub3ljMGQ2UVZoNmVUUnFMMmxUV0RGUldHOU5iVzUyYldWTFl6SjVVSE51Y3pCU2NqTkljRlpTU2tNdk5ESlZNRlptWjFCU2VucGxXWFZ5T0haMFprMUxRM0J0ZG5saFFVOVVaRlo2TkRsM2VuWklXbEp5V0dndk1XZGtjRXA2UzFKRVIwOTViR3g0WWxOMWVVUmpkekZ2V0V4bGEwTnVPVlJSZGpSRlRsSmljMlJoU2trelJtRjRXa1JFYlRkd1FVbDNiR2hGYUhGc2RGUk1SMmRhVEcxblJFTk1iRkp0VW5CbVUySlBXRWx6TjBWRmRqQkZOMUZZWW14MlNqZEVlV2xsWjJJMFVrdGxTM2N4TUhaVWRqVjFlbVpxSzJGTU4zQklSRnBpVkdKS2RrNHZTVmhTU210MWJsUk5WbTF5YzB4d1oyMUpUMmRIVlRCUE1YZDZhWFpPTld4VU9GUnlUbGxEU2pGWFVrNU5USHBPTUVZNFpHUjRNSGQwTVZaakszWnNNa2d5ZGtkMlpXaHJZbnB0Vm13NE1rbFVaVGx0VlhOVGJub3hNa05hWlhwbk5VSklPVkZPUms5UFJHWlVXWGM1VW1VMmFIQlBaa1ZRTjB0TFFYSnBVSFJETVRaMGVWVlVjbUZQYmtKSE5VaHNkejA5SWl3aWJXRmpJam9pTnpjeU4yVXhNekEzWVdFME5qSTNNREEzTVRFeVlXUTRNek0xWWpVMFlXSTRZbUprTlRsbU5EQXhOakV4TXpJMk5EZzNaVGhrTjJWaU56QmpOelEzWmlJc0luUmhaeUk2SWlKOQ==', 1776647495),
('wpBfCjbC7kCresddSqQuTxnkTKtABAvMJ1vHgnoQ', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'ZXlKcGRpSTZJa3hQVERScmRTdHpWWGRsTURKU0swRlVaREZ4TVZFOVBTSXNJblpoYkhWbElqb2lObVZoYzJZNVJqVnNlakJNY1dkRGNVRkZOVlJTWjJkc01FUmhZMU5yZVVseGVHRkJPVkJGZUUxdEwyNUdiMVJsY2xCaGMwaExSakZXV1VKU2NURjFVVTU1UTFWeWFsbERRWGMyWVd3MVRsRjNZWE5MYjJORlZXbGlkSGcxYVZoSmFVTlRSVzVoVDJVNFNWaHVTMnRTWjNST01EWnJMM2RUYUU5Wk9HMVJZemhZWkRWaE1rVnBlbFkzUTFGMFdFRkJXR3RtYXl0c09ERktaVUpwY1hCWU1VeGhUa2hMVkU5UWNtaDFPR0V5UlROTWVWcHRSRUpPV0hGWFRrWmFiVGg0U1M5a1ZWZEJZME5tUmtGVVJEZGxaVTFaZEd4ak1VVndjSEJ1UmxSa1FXRnpOR0ZyV1hkcWQxQXJTRVp3Y3pabVdqZEtVbG8zVlhWbk1XeFdXbnBLWTBSS1MyNUZMMkYwZVVVeU5XNUxiMEUxUnlzeFNtTmFiRFpPU25sdlMyczVZbmxpWlZaRmNUSkdNMFJwYkhabmNFaEhUVXB1U0dwYVFtRlRSSEFyYVZaa1pqQlVabkJVTmpRMVIwUktWMnhCYm1JeFYybDFPVFZLY2tab2FHWkJLM2d4U2tOWlYwWmtWa2t2Y0ZnMmIwOTVVRWhtZWtscFFXVlhRVkozUjNWb1R6UkhiWEozVDB0c1NWSlRiVTFVYlhkR1VrRm1WMHBMTVdOSk0xaEpWazkwVW5WNGNtOVFhME5oZUd0QmNVdFJaM1pvY1cxTFIwbE9RMmx1Wm1RMGIyOXRZWFJHYXpSa1lrOHJWMVJOVUVsT1NYa3pUV3g2VlRsTUx6SkNjbmxpWVdWcmRXa3hlR1ZRU0dGb1FUTllUemRJYlU5M1FuRnhhWGxEV1dKaVpHOWtMMGRZZEVwMFYzUXdVWFpCYmpGSFNUZHlWREY1V1cxbFZubHFOMGt2WVRsQ1lUZDRVRXQ0YTNOTWQyOVZiVEZ3YzI1VmVVeGlTV3B5WWxKd2JrMVRVRVkxVlZaRlRrcDRTVmRVYWxoNGFrcDFMMHgwVVQwOUlpd2liV0ZqSWpvaU9XSXhOMll3T1Rjek9ESXpOR1V4WXpCbU5UWmpOalEyWVdNd1ltVmpZMkl3TXprM1l6ZzBZalJrTVdReVpURTFOREkxTlRVd09XUTVabUUwWkdZM1pTSXNJblJoWnlJNklpSjk=', 1776644489);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `middle_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'staff',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `department` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `project` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bureau` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `division` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `office` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `institution` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `signature_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `otp_hash` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `google2fa_secret` text COLLATE utf8mb4_unicode_ci,
  `google2fa_enabled` tinyint(1) NOT NULL DEFAULT '0',
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `otp_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `otp_expiration` timestamp NULL DEFAULT NULL,
  `notifications_read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_avatar_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `first_name`, `middle_name`, `last_name`, `email`, `password`, `role`, `status`, `department`, `position`, `project`, `bureau`, `division`, `office`, `institution`, `avatar_path`, `signature_path`, `otp_hash`, `google2fa_secret`, `google2fa_enabled`, `two_factor_confirmed_at`, `otp_code`, `otp_expiration`, `notifications_read_at`, `created_at`, `updated_at`, `user_avatar_path`) VALUES
(1, 'Lerma Magno', 'Lerma', 'Tandoc', 'Magno', 'lermamagno12@gmail.com', '$2y$12$wVls2BA2L5OnIdgtJ02P7eF9Q/PuiHu3DB9MVDYtPAAhnT5BmJbku', 'hr-super-admin', 'active', NULL, 'administrator1', 'SPARK', 'Regional Office', NULL, 'La Union', NULL, 'profile-images/N2ozgNHH9lc3V0116YK3nYzL668BRv3EV3Xbc5u7.jpg', NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-06 21:55:18', '2026-04-19 16:36:36', NULL),
(2, 'Lerm Sue Magn', 'Lerm Sue', 'Tan', 'Magn', 'amrelmagno6@gmail.com', '$2y$12$yollLQakZdsUa96PrGsXK.e6kIUQTGVd/7W9d/9peUrEo3s8DAH7e', 'ph-admin', 'active', NULL, 'administrator1', NULL, NULL, 'DigiGov', 'Pangasinan', NULL, 'profile-images/UWNuyMnrAiKI2D2sxwzbd4OLpDy4uYPs269YDS8B.jpg', 'signature-images/mLPfw4mfy5Woom73YZB2AlO6FO2TYCOsbjdaEu8j.jpg', NULL, NULL, 0, NULL, NULL, NULL, '2026-04-17 07:21:50', '2026-04-06 22:02:14', '2026-04-19 16:50:53', NULL),
(3, 'Lerms Magana', 'Lerms', 'Magna', 'Magana', '22ln4415_ms@psu.edu.ph', '$2y$12$0zU.7g02417zTfIColEQ2.BoAK8pO5LsZvU5FhY0DQ89EFMDeq4nG', 'staff', 'active', NULL, 'administrator2', 'FW4A', 'Provincial Office', NULL, 'Pangasinan', NULL, 'profile-images/1YrMt1ua6My22Xo7Q6iNyNPuBeMzvRHGmXFjNzaM.jpg', 'signature-images/jyfVY0w5OPG6Rz1YqZAXKBdpgC4mKT4SDiIcVqcu.jpg', NULL, NULL, 0, NULL, NULL, NULL, '2026-04-19 16:02:46', '2026-04-06 22:03:42', '2026-04-19 16:02:46', NULL),
(4, 'Abie Espanol Ramiro', 'Abie', 'Espanol', 'Ramiro', 'gailramiro118@gmail.com', '$2y$12$AQ8esMlG9eUvhKn3wyfteOAb4qxirW34.kNg4GjOY7WD74EIacz/e', 'interns', 'active', NULL, 'Focal', 'Cybersecurity', 'Provincial Office', NULL, 'Pangasinan', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, '2026-04-19 16:22:20', '2026-04-12 21:20:03', '2026-04-19 16:22:20', NULL),
(5, 'Lermsie Magne', 'Lermsie', NULL, 'Magne', 'lerms9884@gmail.com', '$2y$12$Wg8qiVQjP.zyCUJG3ct4JOYmqMVn1SzKCmJRHGLQEBMQdGGnXgYYS', 'ph-admin', 'archived', NULL, 'Focal', NULL, NULL, 'GECS', 'Ilocos Sur', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-13 15:36:42', '2026-04-19 05:01:52', NULL),
(6, 'Lerbie Magnolia', 'Lerbie', NULL, 'Magnolia', 'amrelmagn76@gmail.com', '$2y$12$VHnze4ZPnlkS626HuEcGEOE0naYk2W3uZhB/2ga7akusNIwtdjvFy', 'ph-admin', 'active', NULL, 'Focal', NULL, NULL, 'AFD', 'Ilocos Norte', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-13 15:39:01', '2026-04-13 15:39:01', NULL),
(7, 'Kimberly Aspa', 'Kimberly', NULL, 'Aspa', 'magnolerma07@gmail.com', '$2y$12$GielqYm2EwkkpaHRqZ1RGO2suQDsl7MEfXeIIPybd9.UobPFR6oKC', 'ph-admin', 'active', NULL, 'Provincial Head', NULL, NULL, 'Cybersecurity', 'La Union', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-13 15:52:05', '2026-04-15 16:37:12', NULL),
(8, 'Gail Ramiro', 'Gail', NULL, 'Ramiro', 'jayramiro6@gmail.com', '$2y$12$Ks7uPguENkvG62HLnqVjcepclfCGw.p/6k9bHmTv5mf.gdWWuMAc6', 'staff', 'archived', NULL, 'Focal', 'NBP', 'Regional Office', NULL, 'Pangasinan', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-14 01:43:14', '2026-04-19 16:37:30', NULL),
(9, 'Jelan Andrada', 'Jelan', NULL, 'Andrada', 'jelandrada23@gmail.com', '$2y$12$eRDDm6h0iTaFoLzTh8h7K.b7dPRPgHWZV/HjSqiBngWIZeP5Ybd7y', 'staff', 'active', NULL, 'Focal', 'ILD', 'AFD', NULL, 'Pangasinan', NULL, NULL, 'signature-images/CoPjU7vQP43TKaiXgqvlvuyd4iRCdvnEWoGb0l13.webp', NULL, NULL, 0, NULL, NULL, NULL, '2026-04-14 15:41:12', '2026-04-14 02:01:37', '2026-04-14 15:41:12', NULL),
(10, 'Chris Melendez Picoc', 'Chris', 'Melendez', 'Picoc', 'chrisjericho.work@gmail.com', '$2y$12$p6P.V9EhxMBYreJuaEcm7.mQBbaCPZMz5E1VJ2ks5FMXl.0qvOxmK', 'staff', 'archived', NULL, 'Student', 'FW4A', 'AFD', NULL, 'La Union', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-15 16:30:11', '2026-04-18 22:45:45', NULL),
(11, 'Louis Ramat Costales', 'Louis', 'Ramat', 'Costales', 'costales.centenielor@gmail.com', '$2y$12$sDkezl6uFNSQnH.w2WmL/OjsANUi7igaT.s8bi23TqR5Sm7Vgu.za', 'staff', 'active', NULL, 'Student', 'ILCDB', 'Field Office', NULL, 'La Union', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-15 16:32:05', '2026-04-15 16:33:04', NULL),
(12, 'Allen Reyes Victorio', 'Allen', 'Reyes', 'Victorio', 'allen.victorio@dict.gov.ph', '$2y$12$ch3/XmAcyyfeJIzjhyh6bujXx1Com.o7PT8pWCdJyy/j2t3YMU4F.', 'staff', 'archived', NULL, 'ISA I', 'DigiGov', 'Provincial Office', NULL, 'Pangasinan', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-15 18:11:32', '2026-04-18 19:40:19', NULL),
(13, 'mercy tandoc', 'mercy', NULL, 'tandoc', 'magnolydia93@gmail.com', '$2y$12$LAVz5OANFzo9jjf9E2wN7eycgeeE/L82e06oFbcmAFSIAjc3IdJLG', 'interns', 'active', NULL, 'Job Order', 'MISS', 'Regional Office', NULL, 'Ilocos Norte', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, '2026-04-19 10:53:11', '2026-04-19 11:22:42', NULL);

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=460;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `report_entries`
--
ALTER TABLE `report_entries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

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
