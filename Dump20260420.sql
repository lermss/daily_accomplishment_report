CREATE DATABASE  IF NOT EXISTS `db_darsystem` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `db_darsystem`;
-- MySQL dump 10.13  Distrib 8.0.41, for Win64 (x86_64)
--
-- Host: localhost    Database: db_darsystem
-- ------------------------------------------------------
-- Server version	8.0.41

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `activity_logs`
--

DROP TABLE IF EXISTS `activity_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `activity_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `details` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_logs`
--

LOCK TABLES `activity_logs` WRITE;
/*!40000 ALTER TABLE `activity_logs` DISABLE KEYS */;
INSERT INTO `activity_logs` VALUES (1,1,'login','login','User signed in successfully.','User signed in successfully.','2026-04-19 18:26:50','2026-04-19 18:26:50'),(2,1,'user_updated','user_updated','Authorized Google Authenticator setup for grantarachea09@gmail.com.','Authorized Google Authenticator setup for grantarachea09@gmail.com.','2026-04-19 18:27:50','2026-04-19 18:27:50'),(3,1,'login','login','User signed in successfully.','User signed in successfully.','2026-04-19 21:04:55','2026-04-19 21:04:55'),(4,1,'logout','logout','User signed out.','User signed out.','2026-04-19 21:06:06','2026-04-19 21:06:06'),(5,1,'login','login','User signed in successfully.','User signed in successfully.','2026-04-19 21:07:09','2026-04-19 21:07:09'),(6,1,'user_updated','user_updated','Authorized Google Authenticator setup for grantarachea09@gmail.com.','Authorized Google Authenticator setup for grantarachea09@gmail.com.','2026-04-19 21:07:35','2026-04-19 21:07:35'),(7,1,'login','login','User signed in successfully.','User signed in successfully.','2026-04-19 21:17:47','2026-04-19 21:17:47'),(8,1,'user_updated','user_updated','Authorized Google Authenticator setup for grantarachea09@gmail.com.','Authorized Google Authenticator setup for grantarachea09@gmail.com.','2026-04-19 21:18:08','2026-04-19 21:18:08'),(9,1,'logout','logout','User signed out.','User signed out.','2026-04-19 21:18:31','2026-04-19 21:18:31'),(10,1,'login','login','User signed in successfully.','User signed in successfully.','2026-04-19 21:36:48','2026-04-19 21:36:48'),(11,1,'logout','logout','User signed out.','User signed out.','2026-04-19 21:36:59','2026-04-19 21:36:59'),(12,1,'login','login','User signed in successfully.','User signed in successfully.','2026-04-19 21:37:29','2026-04-19 21:37:29'),(13,1,'user_updated','user_updated','Provisioned Google Authenticator access for grantarachea09@gmail.com.','Provisioned Google Authenticator access for grantarachea09@gmail.com.','2026-04-19 21:37:38','2026-04-19 21:37:38'),(14,1,'logout','logout','User signed out.','User signed out.','2026-04-19 21:38:17','2026-04-19 21:38:17'),(15,2,'login','login','User signed in successfully.','User signed in successfully.','2026-04-19 21:38:37','2026-04-19 21:38:37'),(16,2,'logout','logout','User signed out.','User signed out.','2026-04-19 21:39:29','2026-04-19 21:39:29'),(17,1,'login','login','User signed in successfully.','User signed in successfully.','2026-04-19 21:46:21','2026-04-19 21:46:21'),(18,1,'login','login','User signed in successfully.','User signed in successfully.','2026-04-19 22:01:20','2026-04-19 22:01:20'),(19,1,'logout','logout','User signed out.','User signed out.','2026-04-19 22:01:32','2026-04-19 22:01:32'),(20,2,'login','login','User signed in successfully.','User signed in successfully.','2026-04-19 22:01:57','2026-04-19 22:01:57'),(21,1,'login','login','User signed in successfully.','User signed in successfully.','2026-04-19 22:06:04','2026-04-19 22:06:04'),(22,2,'login','login','User signed in successfully.','User signed in successfully.','2026-04-19 22:14:51','2026-04-19 22:14:51'),(23,2,'login','login','User signed in successfully.','User signed in successfully.','2026-04-19 22:20:51','2026-04-19 22:20:51'),(24,2,'login','login','User signed in successfully.','User signed in successfully.','2026-04-19 22:37:30','2026-04-19 22:37:30'),(25,2,'profile_updated','profile_updated','Updated personal profile.','Updated personal profile.','2026-04-19 22:46:38','2026-04-19 22:46:38'),(26,2,'profile_updated','profile_updated','Updated personal profile.','Updated personal profile.','2026-04-19 22:46:49','2026-04-19 22:46:49'),(27,2,'profile_updated','profile_updated','Updated personal profile.','Updated personal profile.','2026-04-19 22:46:58','2026-04-19 22:46:58'),(28,2,'logout','logout','User signed out.','User signed out.','2026-04-19 22:54:25','2026-04-19 22:54:25'),(29,1,'login','login','User signed in successfully.','User signed in successfully.','2026-04-19 22:54:51','2026-04-19 22:54:51'),(30,1,'user_created','user_created','Created user account for asda@gmail.com.','Created user account for asda@gmail.com.','2026-04-19 22:56:50','2026-04-19 22:56:50'),(31,2,'login','login','User signed in successfully.','User signed in successfully.','2026-04-19 23:01:21','2026-04-19 23:01:21'),(32,1,'login','login','User signed in successfully.','User signed in successfully.','2026-04-19 23:02:05','2026-04-19 23:02:05'),(33,2,'login','login','User signed in successfully.','User signed in successfully.','2026-04-19 23:02:38','2026-04-19 23:02:38'),(34,2,'logout','logout','User signed out.','User signed out.','2026-04-19 23:03:30','2026-04-19 23:03:30'),(35,1,'login','login','User signed in successfully.','User signed in successfully.','2026-04-19 23:06:04','2026-04-19 23:06:04'),(36,1,'user_created','user_created','Created user account for felz092003@gmail.com.','Created user account for felz092003@gmail.com.','2026-04-19 23:07:14','2026-04-19 23:07:14'),(37,1,'user_updated','user_updated','Provisioned Google Authenticator access for felz092003@gmail.com.','Provisioned Google Authenticator access for felz092003@gmail.com.','2026-04-19 23:07:30','2026-04-19 23:07:30'),(38,1,'logout','logout','User signed out.','User signed out.','2026-04-19 23:08:33','2026-04-19 23:08:33'),(39,2,'login','login','User signed in successfully.','User signed in successfully.','2026-04-19 23:09:32','2026-04-19 23:09:32'),(40,2,'logout','logout','User signed out.','User signed out.','2026-04-19 23:10:14','2026-04-19 23:10:14'),(41,4,'login','login','User signed in successfully.','User signed in successfully.','2026-04-19 23:10:36','2026-04-19 23:10:36'),(42,4,'profile_updated','profile_updated','Updated personal profile.','Updated personal profile.','2026-04-19 23:11:18','2026-04-19 23:11:18'),(43,4,'logout','logout','User signed out.','User signed out.','2026-04-19 23:12:27','2026-04-19 23:12:27'),(44,2,'login','login','User signed in successfully.','User signed in successfully.','2026-04-19 23:12:51','2026-04-19 23:12:51'),(45,2,'logout','logout','User signed out.','User signed out.','2026-04-19 23:13:52','2026-04-19 23:13:52'),(46,4,'login','login','User signed in successfully.','User signed in successfully.','2026-04-19 23:14:51','2026-04-19 23:14:51'),(47,4,'logout','logout','User signed out.','User signed out.','2026-04-19 23:38:02','2026-04-19 23:38:02'),(48,2,'login','login','User signed in successfully.','User signed in successfully.','2026-04-19 23:38:31','2026-04-19 23:38:31'),(49,2,'logout','logout','User signed out.','User signed out.','2026-04-19 23:40:35','2026-04-19 23:40:35'),(50,4,'login','login','User signed in successfully.','User signed in successfully.','2026-04-19 23:40:53','2026-04-19 23:40:53'),(51,4,'login','login','User signed in successfully.','User signed in successfully.','2026-04-20 01:14:09','2026-04-20 01:14:09'),(52,4,'login','login','User signed in successfully.','User signed in successfully.','2026-04-20 01:14:46','2026-04-20 01:14:46'),(53,4,'logout','logout','User signed out.','User signed out.','2026-04-20 01:25:03','2026-04-20 01:25:03'),(54,2,'login','login','User signed in successfully.','User signed in successfully.','2026-04-20 01:25:26','2026-04-20 01:25:26');
/*!40000 ALTER TABLE `activity_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2026_03_10_031857_create_users_table',1),(2,'2026_03_10_031909_create_reports_table',1),(3,'2026_03_10_031918_create_activity_logs_table',1),(4,'2026_03_10_065324_add_status_to_users_table',1),(5,'2026_03_12_130000_add_profile_fields_to_users_table',1),(6,'2026_03_12_130100_add_event_fields_to_activity_logs_table',1),(7,'2026_03_12_131000_add_dashboard_fields_to_reports_table',1),(8,'2026_03_12_140000_add_profile_asset_fields_to_users_table',1),(9,'2026_03_18_142502_create_sessions_table',1),(10,'2026_03_18_143312_create_cache_table',1),(11,'2026_03_18_143451_create_jobs_table',1),(12,'2026_03_18_153906_create_report_entries_table',1),(13,'2026_03_24_062325_add_user_avatar_path_to_users_table',1),(14,'2026_03_24_120000_add_notifications_read_at_to_users_table',1),(15,'2026_03_25_090000_add_review_comment_to_reports_table',1),(16,'2026_03_29_000001_add_google2fa_fields_to_users_table',1),(17,'2026_04_01_100000_add_separate_name_columns_to_users_table',1),(18,'2026_04_14_000001_add_assigned_provincial_head_id_to_reports_table',1),(19,'2026_04_18_184117_add_is_hidden_from_staff_dashboard_to_reports_table',1),(20,'2026_04_18_193938_add_is_hidden_from_staff_index_to_reports_table',1),(21,'2026_04_18_201756_add_is_hidden_from_admin_dashboard_to_reports_table',1),(22,'2026_04_19_120000_create_super_admin_notifications_table',1),(23,'2026_04_20_100000_add_authenticator_authorization_fields_to_users_table',2),(24,'2026_04_20_140000_create_office_reminder_schedules_table',3),(25,'2026_04_20_140100_create_office_reminders_table',3);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `office_reminder_schedules`
--

DROP TABLE IF EXISTS `office_reminder_schedules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `office_reminder_schedules` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `office` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci,
  `send_time` time NOT NULL,
  `is_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `last_sent_on` date DEFAULT NULL,
  `created_by` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `office_reminder_schedules`
--

LOCK TABLES `office_reminder_schedules` WRITE;
/*!40000 ALTER TABLE `office_reminder_schedules` DISABLE KEYS */;
INSERT INTO `office_reminder_schedules` VALUES (1,'Pangasinan','hello mdafakas 5:25','17:25:00',1,NULL,4,'2026-04-20 01:22:52','2026-04-20 01:22:52');
/*!40000 ALTER TABLE `office_reminder_schedules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `office_reminders`
--

DROP TABLE IF EXISTS `office_reminders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `office_reminders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `office` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `triggered_at` timestamp NOT NULL,
  `created_by` bigint unsigned NOT NULL,
  `office_reminder_schedule_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `office_reminders`
--

LOCK TABLES `office_reminders` WRITE;
/*!40000 ALTER TABLE `office_reminders` DISABLE KEYS */;
INSERT INTO `office_reminders` VALUES (1,'Pangasinan','Reminder for Pangasinan: Please submit your accomplishment report.','manual','2026-04-20 01:23:10',4,NULL,'2026-04-20 01:23:10','2026-04-20 01:23:10'),(2,'Pangasinan','Reminder for Pangasinan: Please submit your accomplishment report.','manual','2026-04-20 01:23:14',4,NULL,'2026-04-20 01:23:14','2026-04-20 01:23:14'),(3,'Pangasinan','Reminder for Pangasinan: Please submit your accomplishment report.','manual','2026-04-20 01:23:15',4,NULL,'2026-04-20 01:23:15','2026-04-20 01:23:15');
/*!40000 ALTER TABLE `office_reminders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `report_entries`
--

DROP TABLE IF EXISTS `report_entries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `report_entries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `report_id` bigint unsigned NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `activity` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `details` text COLLATE utf8mb4_unicode_ci,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `report_entries_report_id_foreign` (`report_id`),
  CONSTRAINT `report_entries_report_id_foreign` FOREIGN KEY (`report_id`) REFERENCES `reports` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `report_entries`
--

LOCK TABLES `report_entries` WRITE;
/*!40000 ALTER TABLE `report_entries` DISABLE KEYS */;
INSERT INTO `report_entries` VALUES (1,1,'2026-04-20','2026-04-21','q','qq','qq','2026-04-19 22:41:34','2026-04-19 22:41:34'),(2,1,'2026-04-22','2026-04-23','q','qq','qq','2026-04-19 22:41:34','2026-04-19 22:41:34'),(3,2,'2026-04-21','2026-04-23','qqq','qq','w','2026-04-19 23:39:11','2026-04-19 23:39:11'),(4,2,'2026-04-25','2026-05-03','qw','w','w','2026-04-19 23:39:11','2026-04-19 23:39:11'),(5,3,'2026-04-21','2026-04-23','qqq','qq','w','2026-04-19 23:39:11','2026-04-19 23:39:11'),(6,3,'2026-04-25','2026-05-03','qw','w','w','2026-04-19 23:39:11','2026-04-19 23:39:11'),(7,4,'2026-04-21','2026-04-23','q','w','w','2026-04-19 23:40:14','2026-04-19 23:40:14'),(8,4,'2026-04-28','2026-05-05','w','rqwr','qwrq','2026-04-19 23:40:14','2026-04-19 23:40:14');
/*!40000 ALTER TABLE `report_entries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reports`
--

DROP TABLE IF EXISTS `reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reports` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `assigned_provincial_head_id` bigint unsigned DEFAULT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `submitted_at` timestamp NULL DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `reviewed_by` bigint unsigned DEFAULT NULL,
  `review_comment` text COLLATE utf8mb4_unicode_ci,
  `is_hidden_from_staff_dashboard` tinyint(1) NOT NULL DEFAULT '0',
  `is_hidden_from_staff_index` tinyint(1) NOT NULL DEFAULT '0',
  `is_hidden_from_admin_dashboard` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reports`
--

LOCK TABLES `reports` WRITE;
/*!40000 ALTER TABLE `reports` DISABLE KEYS */;
INSERT INTO `reports` VALUES (1,2,4,'April 21 - 22',NULL,'approved','2026-04-19 23:13:43','2026-04-19 23:36:04',4,NULL,0,0,0,'2026-04-19 22:41:34','2026-04-19 23:36:04'),(2,2,4,'April 21 - 24',NULL,'pending','2026-04-19 23:39:21',NULL,NULL,NULL,0,0,0,'2026-04-19 23:39:11','2026-04-19 23:39:21'),(3,2,4,'April 21 - 24',NULL,'pending','2026-04-19 23:39:29',NULL,NULL,NULL,0,0,0,'2026-04-19 23:39:11','2026-04-19 23:39:29'),(4,2,4,'April 21 - 24',NULL,'pending','2026-04-19 23:40:21',NULL,NULL,NULL,0,0,0,'2026-04-19 23:40:14','2026-04-19 23:40:21');
/*!40000 ALTER TABLE `reports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('5RDMnWoR21obaqpsC4EIIile2QNzSTfYW0k5DcAW',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0','ZXlKcGRpSTZJbXRYY2l0VlozVXhabGd3VldabVZHZEVZVGxaV21jOVBTSXNJblpoYkhWbElqb2laR054ZUZaMGVVNVpkRmhDZFRaNk9ITk1WSFJ1WVc1dWNHOWhjMWRyUzI0NFVVdEZhekJ3UlhGTGVsbExjVWd2V1hsME1FTmlhRlYyVkZNNFlTdFVNbWN4VG5wMFlteDNlRkJTVERRcldtbzFkRWhKTUV4VGQzQlBlR3RIVEV4RE9VdG9aVGhpVEhwT1UyOXBaR1ZRVUhBd2FGVk1RbTF5WlRSVWJ5OWFUbE5GTlhFNFpHTnNSV1UwZWk5elFqSXdVemh4YW5FemRVNW1hRzk1VERrMGNHRlJUWEJaVG5VdlJUY3lWM2RxV0RsblNtZzBNbEJGYW5adlZVbFBZbkp5VUhCYWF6VjBVVTVGVjBzNWJESk5iM0JrWjNCaWJDOWhhRkpJUmtZNU0zQlFSakpEZUdOTlptaFpibkZVYUZVNVpWaDFNMjVuVHpKWFRrOUNSMFpFUjNOQ1pEVTRkME00VjNCYUwzRXJZMnczUXpkVGVUVmxVVWwwVTFsU2VqWlpTWFJ5UTAxcGNHdFNjbFZ0YTJKS1JtZHFhMDVGZEZWTGFGZENNek5OZUV4eloySjFNMGxRZWtsUlZHdE1SV2xYTmxFM2FtUXdiMjl5ZEZKR1pVaFBhRkZHVTNjM016aElWSEpuUFNJc0ltMWhZeUk2SWpkbE1qWTFNakV6Wm1NeVptVXlPV000TTJNNU9EQmlZVFJoTnpRd04ySXhOMkk0WlRCbU9EVTRObUl4TnpjNE5qWXpPVE5tT1dObU16VXpOMlF6Tm1VaUxDSjBZV2NpT2lJaWZRPT0=',1776677122),('aDYuxvH6MLlnCFnIRgrlUk352qVGP4dhhMNYR0Qz',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0','ZXlKcGRpSTZJbGgzV1RkRFpVMUZLME5TTW10a1RUQklhWGhVTmtFOVBTSXNJblpoYkhWbElqb2lRVVkyYUhsWlpYSkNWVmxqYkRReGVqbHdiV0pTSzNncmVqQXZkVk0xVldaR1RuWlZWbGs0U1hwS1ZHVXZUVlJOYWpkT2NGUndMMmxqYmxkUE9VeFZjbVpzTVdKUWFqUnBTSGc0WVdNM1VuRk9WamRVYlRWMFlUZ3pLMnBuVG1oV2JtTXZNMVJ3ZFVwRmJ6Y3lSMlIzUm05eFNqVTFVa3R3VlRod1ozWmxaMUJPZFU5cVN6RTVkbUY2UTBvMlMwYzBMMjl3Uldoc1VsQXpkV1ZJT0RGek1YQmxaRXR2YlRGdU1tOXhUV3c0ZDNncldEUmhVR3NyYmpkbk5WbExRakp5VjNsbmMzZGxNemhJZVhscVNVUjRUVU54VVhwc1FtOUZPVk5qVGxkWlFrcEtOMU5SVGtWYVlXbFphRVJ5VW05QmFIVkJlWFJ4WVdGa2FXeHJabmQxVjFoTFJFaGpjSHBST1dwS0wxTkVRMFV4VWk5QlprTnRTVEkzTDJ4VGVWRkJOa2M0UlRRM2NHTXpWRVE1VW1vdmVrdGpiak5aZVRSa1V6SmxjRkF2ZWxwcU1sQnRWamhSWkZWemNHVmtkVGhFYWtNMFlrcEdZaTlOTUVkTGEzaElXVTFQV2k5NFJXcERlVzluWTBSSFZ6SndkRkJEWjJ0Rk9WVjBSVFVyZHpnemJtcG5iVWhUUWxBd01YbEtXVE4xTTJsTVZWbGFVVDA5SWl3aWJXRmpJam9pTXprek0yUmlNelpsTURFNU5tTTFPREJsTkRBd1l6bGpaVEV5WXpaalptTTFPREkxWlRjd1l6ZzNZek0yWVRWaE1qRTJNR1E1T1RKa00yVTNZalU1WVNJc0luUmhaeUk2SWlKOQ==',1776676442),('diwg7j9CxpdYWh6hvRfAAhlSM3YjUZ2IooJ8YzpA',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0','ZXlKcGRpSTZJa0owZWtnM1owdzVTVWRNYUVsWk5XRTBNRzl4YUVFOVBTSXNJblpoYkhWbElqb2liMDFUVkRCRmRpOTJVbkJaUlZOb2FIbEthVTluV1ZKWFlpdG5SVWx6U1RkMllXSTNhVTh4UmxoNWNVdGFVSGhVUTFOcFVXaFZkRTVhZG5VekwxUnVMMGRoY0dOT2F6WnBWMnQ1U3pOSVlVSldOWHBhZDJ4NVUybGpRaXRTYTJsSFFVSTVURkJMUkdoMk9VOXdVbGRKTkhCR09YTTFaalJZZEdOU05DOUVlbTFyTTB0UmVVTnBSbEIzYzJabGFFMHZPVzV6SzFBMlZsRnFNVEozZUdsMFMzVXJUREZaYTBGaFZreG9halZ4YzFjeGJHSkRTalJuTmxReFMxaERZeTloVjNnMlZrdHVja3BLZUhCNWNtUk9NWEJoT0ROSlJsZFlXUzh5VEVkUmFrTTJSak0wVmpGS2VFVTJOMHhPUlZSc1dEUllRVkU0YzFKMmNrcG9TRXhIYTBsbFJsTm1MMncwWTB4U00xUjVjSHBwYmxvNWRXdFpVMHhwVFU5eFMyRnZjVmRIU0hOTE5FdDFjamgyZVhvMFRrOVhOVzFvT0dsVVQyazJiRVJKWlhoUVpIazFWbEpXU1dOSFMyNWhLM0ZLUXpkcE1GUmhPVGRITkU1NFowcGplRkl3Ym1oWU0xcElhalUyV25OaFVXVnNURXR3YW5sRllrRjRUMmRqTWtSUklpd2liV0ZqSWpvaU5HWmtNV1kzWXpSaFpHRmxaalJpWTJSa09UYzNOamxtWTJWbFkyWm1PVEV6WmpWbU5qWTFPR0kwT0RreFpqRTNPRFkwT0dWbVlUY3hZbVU1T1RneE9TSXNJblJoWnlJNklpSjk=',1776677198),('DjFj81Ae4JJvCcVPHflxA3vGnIr4FAje2llOM72c',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0','ZXlKcGRpSTZJazExVURKM1YzTjNMMmhZS3psaVdXSlNkVWxpZUhjOVBTSXNJblpoYkhWbElqb2ljMVJNZG5KWldISjRkVkpzU1VoSVJUZFdkU3RZZEhsalUybFRiamg2WjJNclJrOXBiMkZ6ZUVJM2JHVnRRa1pETTJJMk5GRm9Uemh1YlVONlZrNTFNRUZsY0dKdVJrYzNZVXRuU1hwa1dteExZM0EyWkhGM1N5OUhhM1p4UTNsVFVVWXlXWEUzUnpCMVV6WnBZekZ0VkdaWVJtNVRaVTB5VEN0T01YSnZSSFUyVFRkUlVYVXdLek13VkZaTlIxUXdNM2RqZEdwM1MxbHFWVTVsYTFkbE4zVnlabnBEZERCRFVtdEJPRWxWUzFsek4yTXpRWFJ2Wm5OVFRXbFBhMGRuYlV4clRHUlBlVVpJZFZodlZTOUdNVlpMT1hwTllrd3piazVxUldWMVVubFVjbVIyZUc5blJHVnFUbm8wVUd4cE9VeFVUVGMxZVZwR1RVcENWR2RCWWtjMlUxbEVaM0JtYVdRcmFHRldkbEJZWkhSdWJXVlJNVEV4UW5GdFlVRjJXRFZzT1hwdWQyb3hWRmhDU1hwT09WaHVSWHA2TUV0blRqQnRlaTlrYkRoQmRHdEZSbmQyYTJ3eGJVUkRiM1FyVTJaM056RlJiMHgzZDFSVU5WaGFNWEZoVUhNd1pYcFZXbFJ6WlRKVE9IQjRWWHBUU1hVNWRURlNhamxZYnpKYVUyeHlNalE1YUVaNE5HbFZTMkZVVG01MmVtNXlRVDA5SWl3aWJXRmpJam9pTnpFNE1tSTVNbUl5TTJFd09EVmtZV1psWkRrd09EQTJNek16Tm1VeU1qUTNaR1EyWWpNNU9UbGpZMlV5TkdSall6Y3lOVFF5WmpFNU9UVTBPVFl4TlNJc0luUmhaeUk2SWlKOQ==',1776676478),('Z8LVSiqVnkNYS5cEYWc69Q1RIV2FT0iiojcwMe76',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0','ZXlKcGRpSTZJbFJZU1RKU1RUVnZjSHBtYUhJM2NFSkpZa3BPWW5jOVBTSXNJblpoYkhWbElqb2lVMGhpWTBsYWJ6SmFXakp4U0VwcmR6UlhTVXRPV0ZWQk9UQklVa0p2ZGpCRFEwYzJNMFZqYlVZd2JYRklWRXhSUTBadWRqZzJkVVI1VldWMlVFRlBhWGhEUzIxcmRreHhNREZ2Y3pOdkswRnVjbmt6ZDBFMUsxWjFPWGhoVmt0MFVDdDZMMHh1V25WbGVIcHBlbll2TXk5T1VHeHpXVzVrVnpRNE5WUXJUbTVWUlRKUlRVOHdUVVV3T1VwR1QwbFpkV2RyVUVkUVMxSlhUSGMxTlU5NGMwUXZhMUZGV0VKTkwwVTJTRUpvYkNzeFREWXhZWHByYWxBeE9FRlVVRTEyYVU1QmIyaHZLMDFJU0RrMlVqWnZjakJ6TVdOdVRVbEpVMmczY1ZVMVFqUlBjMFJFVEd3elJrVm5jbTlLTkVoQ1RIUlpTbTUwVFdORk5HdFdWMkpyZVRSUmJFbFJPVEptV1hOclkwbHRiSGhvU2tvd2QwZG9XWGhvTUc5MFUwcExkRkkzWjBob1kwTXJOU3RsVEdSS1UzVkdlRmg0VW5wU1JHRkxXVlJ1T1Vwb1EwTjJhRlV3ZDJOMVNXTkVWQ3RrV2toUllXVmlRWHBNUTJsNVYwUXhkVXAzUWt0V2RGVlRlSFJCUFNJc0ltMWhZeUk2SWpWaVpUWm1aRGN6WlRCalkyVTBORFZtT1daaE1URXpOV0pqWVRabVpETmpNamRpTWpkalpEVTBOV1ZtTW1GaE1tRmtNV1JpTldFd01XUmhaakkwTWpNaUxDSjBZV2NpT2lJaWZRPT0=',1776670688),('ZuL01LqinaQrXQCQ1UH69DYu2KMQR54T4gWUhLet',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0','ZXlKcGRpSTZJbmhFWTNaWkx6QmlNMFZMYlhGTVYzRXlaVXQ0ZW5jOVBTSXNJblpoYkhWbElqb2lTSGd4U1d0WFFVMHphM2hsY1ZGWlpEUjNjR1JsVjFob2FuQTNiblpSTTFOdGRtRlRZM0pQTm5KRWRHSTVTWEF3V1dkaFlYVTJPRE5UY1RVd1ZVOXJNMjk0UldocFlqSnNNMlE1YTJ4eFkwY3hVbGx2WTJ0ck56aEpTbkJCU1RkUk9EQXljR1JuSzB3MlduZ3JZVVJDUzFObmFpOXZjVXR5V0RNdlRtOVZkRzFrZVVadFYxQTVNelZ1TUZZNFdVSnVjRWhOV1dSTmNsQXZZalo1SzFKWlNVNUpSa2xyTDFCc1dqUk5XR0Z5WVc5VE5HeHBRekl6Y1VjMWQwd3hTekpFUmpSM04yTklTbWhMTm1OcGEwNDBlR2xNYjI1T1kyNXBSbFp6TVZkMU1rTXhXblppY0VVdlIzVnVRVmhaVm1aUU1XMVRSMGRvYzFONlpsZFZiVzl5Wm5RMU9WRTVlbmw0U1VndlREZGxaU3R3YjNSVFlXNWlNbVZJTjFCQmFXczFZME0xVlhGR2FUVkpRVXBtYVRWRGJUVmhjMHRNV0ZFNGNHTmFUbnA2VWtWeFEwcE5NelZ3VkRsdkszSTNaa3RNVjNONmFWUlROV1ZpTlc1c1draE5iM0pOYlhCa2MwVlJWMWxCUFNJc0ltMWhZeUk2SW1ObE16a3dZV013TVRNek9XSXhPRGxtWVROaFkyVTNZbVV3WW1JMU1UVmlZakV5WlRobE9ERTFNR0U1WmpCaE16RXhNalJrT0RGbVpERmxOVEl5WmpBaUxDSjBZV2NpT2lJaWZRPT0=',1776670841);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `super_admin_notifications`
--

DROP TABLE IF EXISTS `super_admin_notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `super_admin_notifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `source_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_status` tinyint(1) NOT NULL DEFAULT '0',
  `read_at` timestamp NULL DEFAULT NULL,
  `action_label` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `super_admin_notifications_source_key_unique` (`source_key`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `super_admin_notifications`
--

LOCK TABLES `super_admin_notifications` WRITE;
/*!40000 ALTER TABLE `super_admin_notifications` DISABLE KEYS */;
INSERT INTO `super_admin_notifications` VALUES (1,'daily-summary:2026-04-20','Daily report summary','Today: 1 submitted, 0 approved, and 0 still pending.','INFO',0,NULL,'View Details','http://127.0.0.1:8000/dashboard/super-admin/reports','{\"pending_today\": 0, \"approved_today\": 0, \"submitted_today\": 1}','2026-04-19 23:08:26','2026-04-19 23:08:26');
/*!40000 ALTER TABLE `super_admin_notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `middle_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'staff',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `is_authorized` tinyint(1) NOT NULL DEFAULT '0',
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
  `google2fa_authorization_code_hash` text COLLATE utf8mb4_unicode_ci,
  `google2fa_authorization_code_expires_at` timestamp NULL DEFAULT NULL,
  `google2fa_authorization_sent_at` timestamp NULL DEFAULT NULL,
  `google2fa_authorized_by` bigint unsigned DEFAULT NULL,
  `google2fa_authorized_at` timestamp NULL DEFAULT NULL,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `otp_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `otp_expiration` timestamp NULL DEFAULT NULL,
  `notifications_read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_avatar_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Grant Arachea','Grant',NULL,'Arachea','grantarachea@gmail.com','$2y$12$eZtILWwNz77ROKTTExxr6OjLMQdbt1lYfrR2b/UXXFrYH13pzg0/W','hr-super-admin','active',1,NULL,'a',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'eyJpdiI6ImRvWjdpVERiUVM1L0c3ckpKTTNlcEE9PSIsInZhbHVlIjoiOG9tUmwxVkRiME43RllvT1hQQXVPeWFGOGRkUEdUeTFSdlBRUVVIL2YzMD0iLCJtYWMiOiIwOTExMmVhNDRkODdlZjQwNWFlNmExODI1MDc0OWVhZWMyNzFjZGJiZGE1N2YxYjE0YTlmMTlhYjkyZmNkYjQ1IiwidGFnIjoiIn0=',1,NULL,NULL,NULL,NULL,NULL,'2026-04-19 18:26:50',NULL,NULL,NULL,'2026-04-19 18:11:18','2026-04-19 18:11:18',NULL),(2,'Grant Arachea','Grant',NULL,'Arachea','grantarachea09@gmail.com','$2y$12$Zeke9h6bOD/IxZ/uEuZkyeJ9vOuvIH67cj3V.z86gA4LTGKN1dSIm','staff','active',1,NULL,'b','DigiGov','TCO',NULL,'Pangasinan',NULL,NULL,NULL,NULL,'eyJpdiI6ImZpUEtaU0NNWnhQdWEwN09ReStwZmc9PSIsInZhbHVlIjoiYTBrcFJQV3RNdXFsYmt6YW00Z1VtcnBGWUdTQkRxV1g0WklRVVJLcG92Zz0iLCJtYWMiOiIxOTUxOTRhOWJjMTkzZjg4ZTk5NTA5ZGMwMjZlMWU3NDEzN2UyNDkyMmY2OTI4ZDM1MWQ4MDZlMGE5ZDdiNzk4IiwidGFnIjoiIn0=',1,NULL,NULL,'2026-04-19 21:37:34',1,'2026-04-19 21:37:34','2026-04-19 21:38:37',NULL,NULL,'2026-04-20 01:26:38','2026-04-19 18:11:19','2026-04-20 01:26:38',NULL),(3,'asda 13123123 asdada','asda','13123123','asdada','asda@gmail.com','$2y$12$b9TRUAhnUQoFidSvz08fAemNgQu2YBNPT/AxWG9dS32cWpAM6O1TO','interns','active',0,NULL,'ediwaw','SPARK','Provincial Office',NULL,'Ilocos Norte',NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2026-04-19 22:56:50','2026-04-19 22:56:50',NULL),(4,'popo qeqw','popo','wqe','qeqw','felz092003@gmail.com','$2y$12$sjDIb.DCSqV0oAS3LpfEAufldZX0Phyo.0nfoCUcrrP96UYDhz8Cm','ph-admin','active',1,NULL,'PO',NULL,NULL,'SPARK','Pangasinan',NULL,NULL,NULL,NULL,'eyJpdiI6ImlLZDdwSnlvalZQMmY3UzNmMUpDR0E9PSIsInZhbHVlIjoiWHZEWEZNeUxrUGxwblVRNkdVNHdRRnhmM25XUE0rcDFCOGdqNnhKRTVYdz0iLCJtYWMiOiI0ODY4ZjkxNzUyZWZiZDYyMzU1ZTY4MzA4NjU2N2E5YmMzNDgzMDlkZWUzYjgzYzRhZjQ3OWY2NjZiZjAwZThlIiwidGFnIjoiIn0=',1,NULL,NULL,'2026-04-19 23:07:24',1,'2026-04-19 23:07:24','2026-04-19 23:10:36',NULL,NULL,NULL,'2026-04-19 23:07:14','2026-04-19 23:11:17',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-04-20 17:30:44
