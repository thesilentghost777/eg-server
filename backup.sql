/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.8.1-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: EGBP
-- ------------------------------------------------------
-- Server version	11.8.1-MariaDB-4

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `admin_stats`
--

DROP TABLE IF EXISTS `admin_stats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_stats` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `connection_count` int(11) NOT NULL DEFAULT 0,
  `request_count` int(11) NOT NULL DEFAULT 0,
  `average_response_time` int(11) NOT NULL DEFAULT 0,
  `error_count` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `admin_stats_date_index` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_stats`
--

LOCK TABLES `admin_stats` WRITE;
/*!40000 ALTER TABLE `admin_stats` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `admin_stats` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `clients`
--

DROP TABLE IF EXISTS `clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `clients` (
  `client_id` char(36) NOT NULL,
  `device_info` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clients`
--

LOCK TABLES `clients` WRITE;
/*!40000 ALTER TABLE `clients` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `clients` VALUES
('7491f733-57e1-4eaf-bd3e-0390314617c6','Unknown device','2025-11-10 16:45:43','2025-11-10 16:45:43'),
('920f1027-917c-4027-ac7e-064e8e9c7742','Unknown device','2025-11-10 16:58:51','2025-11-10 16:58:51'),
('97249c10-e50f-4418-863e-7ceb46f531e2','Unknown device','2025-11-10 16:48:53','2025-11-10 16:48:53'),
('eab39325-a3b0-45d8-b4cc-38692abc56df','Unknown device','2025-11-10 16:46:10','2025-11-10 16:46:10'),
('eb4c59ea-2c7f-4cf1-a1e6-6139f4ebf88b','Unknown device','2025-11-10 16:43:57','2025-11-10 16:43:57'),
('f37c016d-e7a8-4bb3-b5d1-c9fea6aebce0','Unknown device','2025-11-10 16:47:08','2025-11-10 16:47:08'),
('fcdeb3dc-39bc-4b89-89de-50a70baca0ea','Unknown device','2025-11-10 16:58:20','2025-11-10 16:58:20');
/*!40000 ALTER TABLE `clients` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `config_pdg`
--

DROP TABLE IF EXISTS `config_pdg`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `config_pdg` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code_inscription_pdg` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `config_pdg`
--

LOCK TABLES `config_pdg` WRITE;
/*!40000 ALTER TABLE `config_pdg` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `config_pdg` VALUES
(1,'PDG2025SECURE','2025-11-10 16:42:06','2025-11-10 16:42:06');
/*!40000 ALTER TABLE `config_pdg` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `error_logs`
--

DROP TABLE IF EXISTS `error_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `error_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `error_type` varchar(255) DEFAULT NULL,
  `error_message` text NOT NULL,
  `stack_trace` longtext DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `line_number` int(11) DEFAULT NULL,
  `request_method` varchar(255) DEFAULT NULL,
  `request_url` text DEFAULT NULL,
  `request_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`request_data`)),
  `user_agent` varchar(255) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `http_status_code` int(11) DEFAULT NULL,
  `error_time` timestamp NOT NULL,
  `email_sent` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `error_logs_user_id_foreign` (`user_id`),
  KEY `error_logs_error_time_error_type_index` (`error_time`,`error_type`),
  CONSTRAINT `error_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `error_logs`
--

LOCK TABLES `error_logs` WRITE;
/*!40000 ALTER TABLE `error_logs` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `error_logs` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `inventaire_details`
--

DROP TABLE IF EXISTS `inventaire_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventaire_details` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `inventaire_id` bigint(20) unsigned NOT NULL,
  `produit_id` bigint(20) unsigned NOT NULL,
  `quantite_restante` int(11) NOT NULL,
  `synced_clients` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`synced_clients`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inventaire_details_inventaire_id_foreign` (`inventaire_id`),
  KEY `inventaire_details_produit_id_foreign` (`produit_id`),
  CONSTRAINT `inventaire_details_inventaire_id_foreign` FOREIGN KEY (`inventaire_id`) REFERENCES `inventaires` (`id`) ON DELETE CASCADE,
  CONSTRAINT `inventaire_details_produit_id_foreign` FOREIGN KEY (`produit_id`) REFERENCES `produits` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventaire_details`
--

LOCK TABLES `inventaire_details` WRITE;
/*!40000 ALTER TABLE `inventaire_details` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `inventaire_details` VALUES
(1,1,1,15,'[\"97249c10-e50f-4418-863e-7ceb46f531e2\"]','2025-11-10 17:26:14','2025-11-10 17:26:14'),
(2,1,2,6,'[\"97249c10-e50f-4418-863e-7ceb46f531e2\"]','2025-11-10 17:26:14','2025-11-10 17:26:14'),
(3,1,3,3,'[\"97249c10-e50f-4418-863e-7ceb46f531e2\"]','2025-11-10 17:26:14','2025-11-10 17:26:14'),
(4,1,4,2,'[\"97249c10-e50f-4418-863e-7ceb46f531e2\"]','2025-11-10 17:26:14','2025-11-10 17:26:14'),
(5,1,5,0,'[\"97249c10-e50f-4418-863e-7ceb46f531e2\"]','2025-11-10 17:26:14','2025-11-10 17:26:14'),
(6,1,6,98,'[\"97249c10-e50f-4418-863e-7ceb46f531e2\"]','2025-11-10 17:26:14','2025-11-10 17:26:14'),
(7,1,7,0,'[\"97249c10-e50f-4418-863e-7ceb46f531e2\"]','2025-11-10 17:26:14','2025-11-10 17:26:14'),
(8,1,8,1,'[\"97249c10-e50f-4418-863e-7ceb46f531e2\"]','2025-11-10 17:26:14','2025-11-10 17:26:14'),
(9,1,9,0,'[\"97249c10-e50f-4418-863e-7ceb46f531e2\"]','2025-11-10 17:26:14','2025-11-10 17:26:14'),
(10,1,10,1,'[\"97249c10-e50f-4418-863e-7ceb46f531e2\"]','2025-11-10 17:26:14','2025-11-10 17:26:14'),
(11,2,1,6,'[\"97249c10-e50f-4418-863e-7ceb46f531e2\"]','2025-11-10 17:40:21','2025-11-10 17:40:21'),
(12,2,2,1,'[\"97249c10-e50f-4418-863e-7ceb46f531e2\"]','2025-11-10 17:40:21','2025-11-10 17:40:21'),
(13,2,3,0,'[\"97249c10-e50f-4418-863e-7ceb46f531e2\"]','2025-11-10 17:40:21','2025-11-10 17:40:21'),
(14,2,4,2,'[\"97249c10-e50f-4418-863e-7ceb46f531e2\"]','2025-11-10 17:40:21','2025-11-10 17:40:21'),
(15,2,5,0,'[\"97249c10-e50f-4418-863e-7ceb46f531e2\"]','2025-11-10 17:40:21','2025-11-10 17:40:21'),
(16,2,6,105,'[\"97249c10-e50f-4418-863e-7ceb46f531e2\"]','2025-11-10 17:40:21','2025-11-10 17:40:21'),
(17,2,7,0,'[\"97249c10-e50f-4418-863e-7ceb46f531e2\"]','2025-11-10 17:40:21','2025-11-10 17:40:21'),
(18,2,8,0,'[\"97249c10-e50f-4418-863e-7ceb46f531e2\"]','2025-11-10 17:40:21','2025-11-10 17:40:21'),
(19,2,9,0,'[\"97249c10-e50f-4418-863e-7ceb46f531e2\"]','2025-11-10 17:40:21','2025-11-10 17:40:21'),
(20,2,10,0,'[\"97249c10-e50f-4418-863e-7ceb46f531e2\"]','2025-11-10 17:40:21','2025-11-10 17:40:21');
/*!40000 ALTER TABLE `inventaire_details` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `inventaires`
--

DROP TABLE IF EXISTS `inventaires`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventaires` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `vendeur_sortant_id` bigint(20) unsigned NOT NULL,
  `vendeur_entrant_id` bigint(20) unsigned NOT NULL,
  `categorie` enum('boulangerie','patisserie') NOT NULL,
  `valide_sortant` tinyint(1) NOT NULL DEFAULT 0,
  `valide_entrant` tinyint(1) NOT NULL DEFAULT 0,
  `date_inventaire` timestamp NOT NULL,
  `synced_clients` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`synced_clients`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inventaires_vendeur_sortant_id_foreign` (`vendeur_sortant_id`),
  KEY `inventaires_vendeur_entrant_id_foreign` (`vendeur_entrant_id`),
  CONSTRAINT `inventaires_vendeur_entrant_id_foreign` FOREIGN KEY (`vendeur_entrant_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `inventaires_vendeur_sortant_id_foreign` FOREIGN KEY (`vendeur_sortant_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventaires`
--

LOCK TABLES `inventaires` WRITE;
/*!40000 ALTER TABLE `inventaires` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `inventaires` VALUES
(1,5,6,'patisserie',1,1,'2025-11-10 17:26:14','[\"97249c10-e50f-4418-863e-7ceb46f531e2\"]','2025-11-10 17:26:14','2025-11-10 17:26:14'),
(2,6,7,'patisserie',1,1,'2025-11-10 17:40:21','[\"97249c10-e50f-4418-863e-7ceb46f531e2\"]','2025-11-10 17:40:21','2025-11-10 17:40:21');
/*!40000 ALTER TABLE `inventaires` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
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
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `migrations` VALUES
(1,'0001_01_01_000000_create_users_table',1),
(2,'0001_01_01_000001_create_cache_table',1),
(3,'0001_01_01_000002_create_jobs_table',1),
(4,'2025_03_31_091517_create_notifications_table',1),
(5,'2025_05_23_074228_create_admin_stats_table',1),
(6,'2025_07_03_create_error_logs_table',1),
(7,'2025_10_21_111517_create_boulangerie_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) unsigned NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `personal_access_tokens` VALUES
(4,'App\\Models\\User',6,'auth_token','e6550fa6119eab9e9f062820af787b5e475f1f183a5be60dbd5e0fa6f88d4dad','[\"*\"]',NULL,NULL,'2025-11-10 16:47:08','2025-11-10 16:47:08'),
(5,'App\\Models\\User',7,'auth_token','e8028d725f20006be0b30d37e8c91601f3ae3de3f7041129a504c3cbb4df4ca4','[\"*\"]',NULL,NULL,'2025-11-10 16:48:53','2025-11-10 16:48:53'),
(10,'App\\Models\\User',9,'auth_token','b5e39c4782e226e41fdd84e1e9c9af5403ab296bbba12726f6252d77e27abb19','[\"*\"]',NULL,NULL,'2025-11-10 16:58:52','2025-11-10 16:58:52'),
(11,'App\\Models\\User',5,'auth_token','d322ce03dcc26685e504591d978769fd1d08a323cb45a0ef08cfadc3933ac51c','[\"*\"]',NULL,NULL,'2025-11-10 16:59:43','2025-11-10 16:59:43'),
(13,'App\\Models\\User',6,'auth_token','ae0124c4848c5c52ca092308fb62f075b4dd828f504667814d0e594a46200956','[\"*\"]',NULL,NULL,'2025-11-10 17:26:13','2025-11-10 17:26:13'),
(14,'App\\Models\\User',9,'auth_token','29f7e733c65163fae4d81dc807bcd19ba590b3237c230609e03f21d691906fa8','[\"*\"]',NULL,NULL,'2025-11-10 17:28:18','2025-11-10 17:28:18'),
(15,'App\\Models\\User',9,'auth_token','d0ccb03250b145d40a86f33669e7af205de1bf3ca50d72fbe0ff1e7dd99c6839','[\"*\"]',NULL,NULL,'2025-11-10 17:32:06','2025-11-10 17:32:06'),
(16,'App\\Models\\User',9,'auth_token','0136906efb423053a61acba9ea5120db5d527d5a78b25f1ff20ae225e4eee9cd','[\"*\"]',NULL,NULL,'2025-11-10 17:33:14','2025-11-10 17:33:14'),
(17,'App\\Models\\User',9,'auth_token','a0a4089a15a07724df9af5259798a451f25d3372026d4f1a30b503083f043d05','[\"*\"]',NULL,NULL,'2025-11-10 17:34:53','2025-11-10 17:34:53'),
(18,'App\\Models\\User',9,'auth_token','7a62b0a5b5cfe6584be16dda5663935574f3e37a38530b0a45eed839dafe698e','[\"*\"]',NULL,NULL,'2025-11-10 17:36:02','2025-11-10 17:36:02'),
(19,'App\\Models\\User',5,'auth_token','1463f398754b57870ce96994f4a23848d868fc315fc31ee0a24fd9adef2b8359','[\"*\"]',NULL,NULL,'2025-11-10 17:40:20','2025-11-10 17:40:20');
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `produits`
--

DROP TABLE IF EXISTS `produits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `produits` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `prix` decimal(10,2) NOT NULL,
  `categorie` enum('boulangerie','patisserie') NOT NULL,
  `actif` tinyint(1) NOT NULL DEFAULT 1,
  `synced_clients` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`synced_clients`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `produits`
--

LOCK TABLES `produits` WRITE;
/*!40000 ALTER TABLE `produits` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `produits` VALUES
(1,'Croissant au beurre',300.00,'patisserie',1,'[\"97249c10-e50f-4418-863e-7ceb46f531e2\",\"7491f733-57e1-4eaf-bd3e-0390314617c6\",\"920f1027-917c-4027-ac7e-064e8e9c7742\"]','2025-11-10 16:42:06','2025-11-10 17:01:01'),
(2,'Pain au chocolat',400.00,'patisserie',1,'[\"97249c10-e50f-4418-863e-7ceb46f531e2\",\"7491f733-57e1-4eaf-bd3e-0390314617c6\",\"920f1027-917c-4027-ac7e-064e8e9c7742\"]','2025-11-10 16:42:06','2025-11-10 17:01:01'),
(3,'Chausson aux pommes',350.00,'patisserie',1,'[\"97249c10-e50f-4418-863e-7ceb46f531e2\",\"7491f733-57e1-4eaf-bd3e-0390314617c6\",\"920f1027-917c-4027-ac7e-064e8e9c7742\"]','2025-11-10 16:42:06','2025-11-10 17:01:01'),
(4,'Donut sucré',250.00,'patisserie',1,'[\"97249c10-e50f-4418-863e-7ceb46f531e2\",\"7491f733-57e1-4eaf-bd3e-0390314617c6\",\"920f1027-917c-4027-ac7e-064e8e9c7742\"]','2025-11-10 16:42:06','2025-11-10 17:01:01'),
(5,'Éclair au chocolat',500.00,'patisserie',1,'[\"97249c10-e50f-4418-863e-7ceb46f531e2\",\"7491f733-57e1-4eaf-bd3e-0390314617c6\",\"920f1027-917c-4027-ac7e-064e8e9c7742\"]','2025-11-10 16:42:06','2025-11-10 17:01:01'),
(6,'Beignet sucré camerounais',100.00,'patisserie',1,'[\"97249c10-e50f-4418-863e-7ceb46f531e2\",\"7491f733-57e1-4eaf-bd3e-0390314617c6\",\"920f1027-917c-4027-ac7e-064e8e9c7742\"]','2025-11-10 16:42:06','2025-11-10 17:01:01'),
(7,'Tarte aux fruits',600.00,'patisserie',1,'[\"97249c10-e50f-4418-863e-7ceb46f531e2\",\"7491f733-57e1-4eaf-bd3e-0390314617c6\",\"920f1027-917c-4027-ac7e-064e8e9c7742\"]','2025-11-10 16:42:06','2025-11-10 17:01:01'),
(8,'Gâteau yaourt',700.00,'patisserie',1,'[\"97249c10-e50f-4418-863e-7ceb46f531e2\",\"7491f733-57e1-4eaf-bd3e-0390314617c6\",\"920f1027-917c-4027-ac7e-064e8e9c7742\"]','2025-11-10 16:42:06','2025-11-10 17:01:01'),
(9,'Madeleine',200.00,'patisserie',1,'[\"97249c10-e50f-4418-863e-7ceb46f531e2\",\"7491f733-57e1-4eaf-bd3e-0390314617c6\",\"920f1027-917c-4027-ac7e-064e8e9c7742\"]','2025-11-10 16:42:06','2025-11-10 17:01:01'),
(10,'Brioche sucrée',400.00,'patisserie',1,'[\"97249c10-e50f-4418-863e-7ceb46f531e2\",\"7491f733-57e1-4eaf-bd3e-0390314617c6\",\"920f1027-917c-4027-ac7e-064e8e9c7742\"]','2025-11-10 16:42:06','2025-11-10 17:01:01');
/*!40000 ALTER TABLE `produits` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `receptions_pointeur`
--

DROP TABLE IF EXISTS `receptions_pointeur`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `receptions_pointeur` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pointeur_id` bigint(20) unsigned NOT NULL,
  `producteur_id` bigint(20) unsigned NOT NULL,
  `produit_id` bigint(20) unsigned NOT NULL,
  `quantite` int(11) NOT NULL,
  `vendeur_assigne_id` bigint(20) unsigned DEFAULT NULL,
  `verrou` tinyint(1) NOT NULL DEFAULT 0,
  `date_reception` timestamp NOT NULL,
  `notes` text DEFAULT NULL,
  `synced_clients` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`synced_clients`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `receptions_pointeur_pointeur_id_foreign` (`pointeur_id`),
  KEY `receptions_pointeur_producteur_id_foreign` (`producteur_id`),
  KEY `receptions_pointeur_produit_id_foreign` (`produit_id`),
  KEY `receptions_pointeur_vendeur_assigne_id_foreign` (`vendeur_assigne_id`),
  CONSTRAINT `receptions_pointeur_pointeur_id_foreign` FOREIGN KEY (`pointeur_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `receptions_pointeur_producteur_id_foreign` FOREIGN KEY (`producteur_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `receptions_pointeur_produit_id_foreign` FOREIGN KEY (`produit_id`) REFERENCES `produits` (`id`) ON DELETE CASCADE,
  CONSTRAINT `receptions_pointeur_vendeur_assigne_id_foreign` FOREIGN KEY (`vendeur_assigne_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `receptions_pointeur`
--

LOCK TABLES `receptions_pointeur` WRITE;
/*!40000 ALTER TABLE `receptions_pointeur` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `receptions_pointeur` VALUES
(1,8,2,6,250,5,0,'2025-11-10 17:01:38',NULL,'[\"920f1027-917c-4027-ac7e-064e8e9c7742\",\"97249c10-e50f-4418-863e-7ceb46f531e2\"]','2025-11-10 17:03:24','2025-11-10 17:03:29'),
(2,8,2,1,15,5,0,'2025-11-10 17:02:25',NULL,'[\"920f1027-917c-4027-ac7e-064e8e9c7742\",\"97249c10-e50f-4418-863e-7ceb46f531e2\"]','2025-11-10 17:03:24','2025-11-10 17:03:29'),
(3,8,2,2,16,5,0,'2025-11-10 17:02:40','Mdr','[\"920f1027-917c-4027-ac7e-064e8e9c7742\",\"97249c10-e50f-4418-863e-7ceb46f531e2\"]','2025-11-10 17:03:24','2025-11-10 17:19:53'),
(4,9,2,1,30,6,0,'2025-11-10 17:32:23',NULL,'[\"920f1027-917c-4027-ac7e-064e8e9c7742\",\"97249c10-e50f-4418-863e-7ceb46f531e2\"]','2025-11-10 17:33:15','2025-11-10 17:37:50'),
(5,9,2,2,6,6,0,'2025-11-10 17:33:33',NULL,'[\"920f1027-917c-4027-ac7e-064e8e9c7742\",\"97249c10-e50f-4418-863e-7ceb46f531e2\"]','2025-11-10 17:34:54','2025-11-10 17:37:50'),
(6,9,2,4,4,6,0,'2025-11-10 17:35:09',NULL,'[\"920f1027-917c-4027-ac7e-064e8e9c7742\",\"97249c10-e50f-4418-863e-7ceb46f531e2\"]','2025-11-10 17:36:03','2025-11-10 17:37:50'),
(7,9,2,6,350,6,0,'2025-11-10 17:36:18',NULL,'[\"920f1027-917c-4027-ac7e-064e8e9c7742\",\"97249c10-e50f-4418-863e-7ceb46f531e2\"]','2025-11-10 17:37:33','2025-11-10 17:37:50'),
(8,9,2,9,15,6,0,'2025-11-10 17:37:06',NULL,'[\"920f1027-917c-4027-ac7e-064e8e9c7742\",\"97249c10-e50f-4418-863e-7ceb46f531e2\"]','2025-11-10 17:37:33','2025-11-10 17:37:50');
/*!40000 ALTER TABLE `receptions_pointeur` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `retours_produits`
--

DROP TABLE IF EXISTS `retours_produits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `retours_produits` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pointeur_id` bigint(20) unsigned NOT NULL,
  `vendeur_id` bigint(20) unsigned NOT NULL,
  `produit_id` bigint(20) unsigned NOT NULL,
  `quantite` int(11) NOT NULL,
  `raison` enum('perime','abime','autre') NOT NULL,
  `description` text DEFAULT NULL,
  `verrou` tinyint(1) NOT NULL DEFAULT 0,
  `date_retour` timestamp NOT NULL,
  `synced_clients` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`synced_clients`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `retours_produits_pointeur_id_foreign` (`pointeur_id`),
  KEY `retours_produits_vendeur_id_foreign` (`vendeur_id`),
  KEY `retours_produits_produit_id_foreign` (`produit_id`),
  CONSTRAINT `retours_produits_pointeur_id_foreign` FOREIGN KEY (`pointeur_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `retours_produits_produit_id_foreign` FOREIGN KEY (`produit_id`) REFERENCES `produits` (`id`) ON DELETE CASCADE,
  CONSTRAINT `retours_produits_vendeur_id_foreign` FOREIGN KEY (`vendeur_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `retours_produits`
--

LOCK TABLES `retours_produits` WRITE;
/*!40000 ALTER TABLE `retours_produits` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `retours_produits` VALUES
(1,8,5,2,2,'abime',NULL,0,'2025-11-10 17:04:46','[\"920f1027-917c-4027-ac7e-064e8e9c7742\",\"97249c10-e50f-4418-863e-7ceb46f531e2\"]','2025-11-10 17:05:43','2025-11-10 17:09:14'),
(2,8,5,6,15,'perime','Déjà avarier Après 3 jours',0,'2025-11-10 17:05:31','[\"920f1027-917c-4027-ac7e-064e8e9c7742\",\"97249c10-e50f-4418-863e-7ceb46f531e2\"]','2025-11-10 17:05:43','2025-11-10 17:05:50'),
(3,9,6,6,7,'abime',NULL,0,'2025-11-10 17:36:39','[\"920f1027-917c-4027-ac7e-064e8e9c7742\",\"97249c10-e50f-4418-863e-7ceb46f531e2\"]','2025-11-10 17:37:33','2025-11-10 17:37:50'),
(4,9,6,10,1,'perime',NULL,0,'2025-11-10 17:37:21','[\"920f1027-917c-4027-ac7e-064e8e9c7742\",\"97249c10-e50f-4418-863e-7ceb46f531e2\"]','2025-11-10 17:37:33','2025-11-10 17:37:50');
/*!40000 ALTER TABLE `retours_produits` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
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
set autocommit=0;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `sessions_vente`
--

DROP TABLE IF EXISTS `sessions_vente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions_vente` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `vendeur_id` bigint(20) unsigned NOT NULL,
  `categorie` enum('boulangerie','patisserie') NOT NULL,
  `fond_vente` decimal(10,2) NOT NULL DEFAULT 0.00,
  `orange_money_initial` decimal(10,2) NOT NULL DEFAULT 0.00,
  `mtn_money_initial` decimal(10,2) NOT NULL DEFAULT 0.00,
  `montant_verse` decimal(10,2) DEFAULT NULL,
  `orange_money_final` decimal(10,2) DEFAULT NULL,
  `mtn_money_final` decimal(10,2) DEFAULT NULL,
  `manquant` decimal(10,2) DEFAULT NULL,
  `statut` enum('ouverte','fermee') NOT NULL DEFAULT 'ouverte',
  `fermee_par` bigint(20) unsigned DEFAULT NULL,
  `date_ouverture` timestamp NOT NULL,
  `date_fermeture` timestamp NULL DEFAULT NULL,
  `synced_clients` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`synced_clients`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_vente_vendeur_id_foreign` (`vendeur_id`),
  KEY `sessions_vente_fermee_par_foreign` (`fermee_par`),
  CONSTRAINT `sessions_vente_fermee_par_foreign` FOREIGN KEY (`fermee_par`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `sessions_vente_vendeur_id_foreign` FOREIGN KEY (`vendeur_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions_vente`
--

LOCK TABLES `sessions_vente` WRITE;
/*!40000 ALTER TABLE `sessions_vente` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `sessions_vente` VALUES
(1,5,'patisserie',30000.00,45000.00,35000.00,NULL,NULL,NULL,NULL,'ouverte',NULL,'2025-11-10 17:20:17',NULL,'[\"97249c10-e50f-4418-863e-7ceb46f531e2\",\"920f1027-917c-4027-ac7e-064e8e9c7742\"]','2025-11-10 17:21:43','2025-11-10 17:21:55'),
(2,6,'patisserie',35000.00,12500.00,6500.00,NULL,NULL,NULL,NULL,'ouverte',NULL,'2025-11-10 17:27:15',NULL,'[\"97249c10-e50f-4418-863e-7ceb46f531e2\"]','2025-11-10 17:37:49','2025-11-10 17:37:49');
/*!40000 ALTER TABLE `sessions_vente` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `numero_telephone` varchar(255) NOT NULL,
  `role` enum('pdg','pointeur','vendeur_boulangerie','vendeur_patisserie','producteur') NOT NULL,
  `code_pin` varchar(255) NOT NULL,
  `actif` tinyint(1) NOT NULL DEFAULT 1,
  `preferred_language` varchar(2) NOT NULL DEFAULT 'fr',
  `synced_clients` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`synced_clients`)),
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_numero_telephone_unique` (`numero_telephone`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `users` VALUES
(1,'BiG BoSS','657929578','pdg','$2y$12$6W9wcI3ybJJtCPFX4w1uGeRoovYcdskfEvYuEdBxrspvYln2aAt.2',1,'fr','[]','g4WGmxWucp','2025-11-10 16:42:06','2025-11-10 16:42:06'),
(2,'Samba Tankru','633445566','producteur','$2y$12$wLDR4ijC3QhjtIALebe6S.TPYh2VpuzdqHzBWFlgZ8VmyvhNfmUCm',1,'fr','[\"97249c10-e50f-4418-863e-7ceb46f531e2\",\"7491f733-57e1-4eaf-bd3e-0390314617c6\",\"920f1027-917c-4027-ac7e-064e8e9c7742\"]','g8xm2u4qtL','2025-11-10 16:42:06','2025-11-10 17:01:01'),
(3,'Pointo','677339655','vendeur_patisserie','$2y$12$3acYeVJwG8kSMSRhTa5WGOX7cgjmteyXN18Vprphb6TzD3TrWK6j6',1,'fr','[\"eb4c59ea-2c7f-4cf1-a1e6-6139f4ebf88b\",\"97249c10-e50f-4418-863e-7ceb46f531e2\",\"7491f733-57e1-4eaf-bd3e-0390314617c6\",\"920f1027-917c-4027-ac7e-064e8e9c7742\"]',NULL,'2025-11-10 16:43:57','2025-11-10 17:01:01'),
(4,'Pointo2','699630575','vendeur_patisserie','$2y$12$hDH8WlIAHi/2pN9LnUHV6eZe40Q1SjKns7wgtpiqevU3o/3C1eK8.',1,'fr','[\"7491f733-57e1-4eaf-bd3e-0390314617c6\",\"97249c10-e50f-4418-863e-7ceb46f531e2\",\"920f1027-917c-4027-ac7e-064e8e9c7742\"]',NULL,'2025-11-10 16:45:43','2025-11-10 17:01:01'),
(5,'Vendo','600112233','vendeur_patisserie','$2y$12$D25wl.W2CJ.JYAJ7Z2k.3OJUav2iPB90McRLfw4BWLkKar.m22bgi',1,'fr','[\"eab39325-a3b0-45d8-b4cc-38692abc56df\",\"97249c10-e50f-4418-863e-7ceb46f531e2\",\"7491f733-57e1-4eaf-bd3e-0390314617c6\",\"920f1027-917c-4027-ac7e-064e8e9c7742\"]',NULL,'2025-11-10 16:46:11','2025-11-10 17:01:01'),
(6,'Vendo2','611223344','vendeur_patisserie','$2y$12$3fYF7FNWQbgk2IXwNAVAPeq0ZdMQ7ffnlWccEngKTF3Hb2.5r2oj2',1,'fr','[\"f37c016d-e7a8-4bb3-b5d1-c9fea6aebce0\",\"97249c10-e50f-4418-863e-7ceb46f531e2\",\"7491f733-57e1-4eaf-bd3e-0390314617c6\",\"920f1027-917c-4027-ac7e-064e8e9c7742\"]',NULL,'2025-11-10 16:47:08','2025-11-10 17:01:01'),
(7,'Vendo3','622334455','vendeur_patisserie','$2y$12$AqbCEMpH91ou2PfWNOjH5uulWlIp/QXY6vOpvzlAnQT5CDvcMzXBe',1,'fr','[\"97249c10-e50f-4418-863e-7ceb46f531e2\",\"7491f733-57e1-4eaf-bd3e-0390314617c6\",\"920f1027-917c-4027-ac7e-064e8e9c7742\"]',NULL,'2025-11-10 16:48:53','2025-11-10 17:01:01'),
(8,'Pointo1','699887766','pointeur','$2y$12$h1i7zliZfS.zjss92iCn1.qKYnMjSkyzmxdbMtsUYCAuDS6JxMUOm',1,'fr','[\"fcdeb3dc-39bc-4b89-89de-50a70baca0ea\",\"97249c10-e50f-4418-863e-7ceb46f531e2\",\"920f1027-917c-4027-ac7e-064e8e9c7742\"]',NULL,'2025-11-10 16:58:21','2025-11-10 17:01:01'),
(9,'Pointo2','688776655','pointeur','$2y$12$6IUOq0LqQdoMtPYshFYu1uTCmboVLXCMGWrV3H.stXqTZEE7nK7/u',1,'fr','[\"920f1027-917c-4027-ac7e-064e8e9c7742\",\"97249c10-e50f-4418-863e-7ceb46f531e2\"]',NULL,'2025-11-10 16:58:52','2025-11-10 16:59:45');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `vendeurs_actifs`
--

DROP TABLE IF EXISTS `vendeurs_actifs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `vendeurs_actifs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `categorie` enum('boulangerie','patisserie') NOT NULL,
  `vendeur_id` bigint(20) unsigned DEFAULT NULL,
  `connecte_a` timestamp NULL DEFAULT NULL,
  `synced_clients` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`synced_clients`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `vendeurs_actifs_categorie_unique` (`categorie`),
  KEY `vendeurs_actifs_vendeur_id_foreign` (`vendeur_id`),
  CONSTRAINT `vendeurs_actifs_vendeur_id_foreign` FOREIGN KEY (`vendeur_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vendeurs_actifs`
--

LOCK TABLES `vendeurs_actifs` WRITE;
/*!40000 ALTER TABLE `vendeurs_actifs` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `vendeurs_actifs` VALUES
(1,'patisserie',5,'2025-11-10 17:40:20','[\"97249c10-e50f-4418-863e-7ceb46f531e2\",\"7491f733-57e1-4eaf-bd3e-0390314617c6\",\"920f1027-917c-4027-ac7e-064e8e9c7742\"]','2025-11-10 16:50:34','2025-11-10 17:40:20');
/*!40000 ALTER TABLE `vendeurs_actifs` ENABLE KEYS */;
UNLOCK TABLES;
commit;

--
-- Table structure for table `ventes`
--

DROP TABLE IF EXISTS `ventes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ventes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `session_vente_id` bigint(20) unsigned NOT NULL,
  `produit_id` bigint(20) unsigned NOT NULL,
  `quantite` int(11) NOT NULL,
  `prix_unitaire` decimal(10,2) NOT NULL,
  `montant_total` decimal(10,2) NOT NULL,
  `mode_paiement` enum('cash','orange_money','mtn_money') NOT NULL DEFAULT 'cash',
  `date_vente` timestamp NOT NULL,
  `synced_clients` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`synced_clients`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ventes_session_vente_id_foreign` (`session_vente_id`),
  KEY `ventes_produit_id_foreign` (`produit_id`),
  CONSTRAINT `ventes_produit_id_foreign` FOREIGN KEY (`produit_id`) REFERENCES `produits` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ventes_session_vente_id_foreign` FOREIGN KEY (`session_vente_id`) REFERENCES `sessions_vente` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ventes`
--

LOCK TABLES `ventes` WRITE;
/*!40000 ALTER TABLE `ventes` DISABLE KEYS */;
set autocommit=0;
/*!40000 ALTER TABLE `ventes` ENABLE KEYS */;
UNLOCK TABLES;
commit;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2025-11-10  4:42:59
