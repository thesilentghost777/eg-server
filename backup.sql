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
('4b9406cc-989f-4847-b1b7-bcbcbefe935d','Mozilla/5.0 (X11; Linux x86_64; rv:128.0) Gecko/20100101 Firefox/128.0','2026-01-27 12:16:21','2026-01-27 12:16:21'),
('client_1768837845546_5l3vtsi2k','Mozilla/5.0 (X11; Linux x86_64; rv:128.0) Gecko/20100101 Firefox/128.0','2026-01-27 14:08:33','2026-01-27 14:08:33'),
('client_1769511497519_owwdej1ax','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_6) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.6.7 Safari/605.1.15','2026-01-27 12:22:24','2026-01-27 12:22:24'),
('client_1769516607699_pxbqn80bh','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_6) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.6.7 Safari/605.1.15','2026-01-27 12:23:29','2026-01-27 12:23:29'),
('client_1769516884692_08djm6z26','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_6) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.6.7 Safari/605.1.15','2026-01-27 12:28:06','2026-01-27 12:28:06'),
('client_1769519365876_8ersmths3','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_6) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.6.7 Safari/605.1.15','2026-01-27 13:09:27','2026-01-27 13:09:27'),
('client_1769519490606_pb8i1da20','Mozilla/5.0 (X11; Linux x86_64; rv:128.0) Gecko/20100101 Firefox/128.0','2026-01-27 13:11:31','2026-01-27 13:11:31'),
('client_1769520544504_aka8o85pf','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','2026-01-27 13:29:05','2026-01-27 13:29:05'),
('client_1769522356623_uji257ktp','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_6) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.6.7 Safari/605.1.15','2026-01-27 13:59:18','2026-01-27 13:59:18');
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
(1,'PDG2025SECURE','2026-01-27 12:15:49','2026-01-27 12:15:49');
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `error_logs`
--

LOCK TABLES `error_logs` WRITE;
/*!40000 ALTER TABLE `error_logs` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `error_logs` VALUES
(1,'Illuminate\\View\\ViewException','Unsupported operand types: string - string (View: /home/ghost/Desktop/hack_the_world/BIG_PROJECT/EG_BP/EasyGestBP/resources/views/retours/index.blade.php)','#0 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/EG_BP/EasyGestBP/vendor/laravel/framework/src/Illuminate/View/Engines/PhpEngine.php(59): Illuminate\\View\\Engines\\CompilerEngine->handleViewException()\n#1 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/EG_BP/EasyGestBP/vendor/laravel/framework/src/Illuminate/View/Engines/CompilerEngine.php(76): Illuminate\\View\\Engines\\PhpEngine->evaluatePath()\n#2 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/EG_BP/EasyGestBP/vendor/laravel/framework/src/Illuminate/View/View.php(208): Illuminate\\View\\Engines\\CompilerEngine->get()\n#3 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/EG_BP/EasyGestBP/vendor/laravel/framework/src/Illuminate/View/View.php(191): Illuminate\\View\\View->getContents()\n#4 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/EG_BP/EasyGestBP/vendor/laravel/framework/src/Illuminate/View/View.php(160): Illuminate\\View\\View->renderContents()\n#5 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/EG_BP/EasyGestBP/vendor/laravel/framework/src/Illuminate/Http/Response.php(78): Illuminate\\View\\View->render()\n#6 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/EG_BP/EasyGestBP/vendor/laravel/framework/src/Illuminate/Http/Response.php(34): Illuminate\\Http\\Response->setContent()\n#7 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/EG_BP/EasyGestBP/vendor/laravel/framework/src/Illuminate/Routing/Router.php(939): Illuminate\\Http\\Response->__construct()\n#8 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/EG_BP/EasyGestBP/vendor/laravel/framework/src/Illuminate/Routing/Router.php(906): Illuminate\\Routing\\Router::toResponse()\n#9 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/EG_BP/EasyGestBP/vendor/laravel/framework/src/Illuminate/Routing/Router.php(821): Illuminate\\Routing\\Router->prepareResponse()\n#10 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/EG_BP/EasyGestBP/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Routing\\Router->{closure:Illuminate\\Routing\\Router::runRouteWithinStack():821}()\n#11 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/EG_BP/EasyGestBP/vendor/laravel/framework/src/Illuminate/Routing/Middleware/SubstituteBindings.php(50): Illuminate\\Pipeline\\Pipeline->{closure:Illuminate\\Pipeline\\Pipeline::prepareDestination():178}()\n#12 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/EG_BP/EasyGestBP/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(219): Illuminate\\Routing\\Middleware\\SubstituteBindings->handle()\n#13 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/EG_BP/EasyGestBP/vendor/laravel/framework/src/Illuminate/Auth/Middleware/Authenticate.php(63): Illuminate\\Pipeline\\Pipeline->{closure:{closure:Illuminate\\Pipeline\\Pipeline::carry():194}:195}()\n#14 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/EG_BP/EasyGestBP/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(219): Illuminate\\Auth\\Middleware\\Authenticate->handle()\n#15 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/EG_BP/EasyGestBP/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/VerifyCsrfToken.php(87): Illuminate\\Pipeline\\Pipeline->{closure:{closure:Illuminate\\Pipeline\\Pipeline::carry():194}:195}()\n#16 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/EG_BP/EasyGestBP/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(219): Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken->handle()\n#17 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/EG_BP/EasyGestBP/vendor/laravel/framework/src/Illuminate/View/Middleware/ShareErrorsFromSession.php(48): Illuminate\\Pipeline\\Pipeline->{closure:{closure:Illuminate\\Pipeline\\Pipeline::carry():194}:195}()\n#18 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/EG_BP/EasyGestBP/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(219): Illuminate\\View\\Middleware\\ShareErrorsFromSession->handle()\n#19 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/EG_BP/EasyGestBP/vendor/laravel/framework/src/Illuminate/Session/Middleware/StartSession.php(120): Illuminate\\Pipeline\\Pipeline->{closure:{closure:Illuminate\\Pipeline\\Pipeline::carry():194}:195}()\n#20 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/EG_BP/EasyGestBP/vendor/laravel/framework/src/Illuminate/Session/Middleware/StartSession.php(63): Illuminate\\Session\\Middleware\\StartSession->handleStatefulRequest()\n#21 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/EG_BP/EasyGestBP/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(219): Illuminate\\Session\\Middleware\\StartSession->handle()\n#22 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/EG_BP/EasyGestBP/vendor/laravel/framework/src/Illuminate/Cookie/Middleware/AddQueuedCookiesToResponse.php(36): Illuminate\\Pipeline\\Pipeline->{closure:{closure:Illuminate\\Pipeline\\Pipeline::carry():194}:195}()\n#23 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/EG_BP/EasyGestBP/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(219): Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse->handle()\n#24 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/EG_BP/EasyGestBP/vendor/laravel/framework/src/Illuminate/Cookie/Middleware/EncryptCookies.php(74): Illuminate\\Pipeline\\Pipeline->{closure:{closure:Illuminate\\Pipeline\\Pipeline::carry():194}:195}()\n#25 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/EG_BP/EasyGestBP/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(219): Illuminate\\Cookie\\Middleware\\EncryptCookies->handle()\n#26 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/EG_BP/EasyGestBP/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->{closure:{closure:Illuminate\\Pipeline\\Pipeline::carry():194}:195}()\n#27 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/EG_BP/EasyGestBP/vendor/laravel/framework/src/Illuminate/Routing/Router.php(821): Illuminate\\Pipeline\\Pipeline->then()\n#28 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/EG_BP/EasyGestBP/vendor/laravel/framework/src/Illuminate/Routing/Router.php(800): Illuminate\\Routing\\Router->runRouteWithinStack()\n#29 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/EG_BP/EasyGestBP/vendor/laravel/framework/src/Illuminate/Routing/Router.php(764): Illuminate\\Routing\\Router->runRoute()\n#30 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/EG_BP/EasyGestBP/vendor/laravel/framework/src/Illuminate/Routing/Router.php(753): Illuminate\\Routing\\Router->dispatchToRoute()\n#31 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/EG_BP/EasyGestBP/vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php(200): Illuminate\\Routing\\Router->dispatch()\n#32 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/EG_BP/EasyGestBP/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(180): Illuminate\\Foundation\\Http\\Kernel->{closure:Illuminate\\Foundation\\Http\\Kernel::dispatchToRouter():197}()\n#33 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/EG_BP/EasyGestBP/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/TransformsRequest.php(21): Illuminate\\Pipeline\\Pipeline->{closure:Illuminate\\Pipeline\\Pipeline::prepareDestination():178}()\n#34 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/EG_BP/EasyGestBP/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/ConvertEmptyStringsToNull.php(31): Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest->handle()\n#35 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/EG_BP/EasyGestBP/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(219): Illuminate\\Foundation\\Http\\Middleware\\ConvertEmptyStringsToNull->handle()\n#36 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/EG_BP/EasyGestBP/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/TransformsRequest.php(21): Illuminate\\Pipeline\\Pipeline->{closure:{closure:Illuminate\\Pipeline\\Pipeline::carry():194}:195}()\n#37 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/EG_BP/EasyGestBP/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/TrimStrings.php(51): Illuminate\\Foundation\\Http\\Middleware\\TransformsRequest->handle()\n#38 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/EG_BP/EasyGestBP/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(219): Illuminate\\Foundation\\Http\\Middleware\\TrimStrings->handle()\n#39 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/EG_BP/EasyGestBP/vendor/laravel/framework/src/Illuminate/Http/Middleware/ValidatePostSize.php(27): Illuminate\\Pipeline\\Pipeline->{closure:{closure:Illuminate\\Pipeline\\Pipeline::carry():194}:195}()\n#40 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/EG_BP/EasyGestBP/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(219): Illuminate\\Http\\Middleware\\ValidatePostSize->handle()\n#41 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/EG_BP/EasyGestBP/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/PreventRequestsDuringMaintenance.php(109): Illuminate\\Pipeline\\Pipeline->{closure:{closure:Illuminate\\Pipeline\\Pipeline::carry():194}:195}()\n#42 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/EG_BP/EasyGestBP/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(219): Illuminate\\Foundation\\Http\\Middleware\\PreventRequestsDuringMaintenance->handle()\n#43 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/EG_BP/EasyGestBP/vendor/laravel/framework/src/Illuminate/Http/Middleware/HandleCors.php(48): Illuminate\\Pipeline\\Pipeline->{closure:{closure:Illuminate\\Pipeline\\Pipeline::carry():194}:195}()\n#44 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/EG_BP/EasyGestBP/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(219): Illuminate\\Http\\Middleware\\HandleCors->handle()\n#45 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/EG_BP/EasyGestBP/vendor/laravel/framework/src/Illuminate/Http/Middleware/TrustProxies.php(58): Illuminate\\Pipeline\\Pipeline->{closure:{closure:Illuminate\\Pipeline\\Pipeline::carry():194}:195}()\n#46 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/EG_BP/EasyGestBP/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(219): Illuminate\\Http\\Middleware\\TrustProxies->handle()\n#47 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/EG_BP/EasyGestBP/vendor/laravel/framework/src/Illuminate/Foundation/Http/Middleware/InvokeDeferredCallbacks.php(22): Illuminate\\Pipeline\\Pipeline->{closure:{closure:Illuminate\\Pipeline\\Pipeline::carry():194}:195}()\n#48 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/EG_BP/EasyGestBP/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(219): Illuminate\\Foundation\\Http\\Middleware\\InvokeDeferredCallbacks->handle()\n#49 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/EG_BP/EasyGestBP/vendor/laravel/framework/src/Illuminate/Http/Middleware/ValidatePathEncoding.php(26): Illuminate\\Pipeline\\Pipeline->{closure:{closure:Illuminate\\Pipeline\\Pipeline::carry():194}:195}()\n#50 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/EG_BP/EasyGestBP/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(219): Illuminate\\Http\\Middleware\\ValidatePathEncoding->handle()\n#51 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/EG_BP/EasyGestBP/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->{closure:{closure:Illuminate\\Pipeline\\Pipeline::carry():194}:195}()\n#52 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/EG_BP/EasyGestBP/vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php(175): Illuminate\\Pipeline\\Pipeline->then()\n#53 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/EG_BP/EasyGestBP/vendor/laravel/framework/src/Illuminate/Foundation/Http/Kernel.php(144): Illuminate\\Foundation\\Http\\Kernel->sendRequestThroughRouter()\n#54 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/EG_BP/EasyGestBP/vendor/laravel/framework/src/Illuminate/Foundation/Application.php(1220): Illuminate\\Foundation\\Http\\Kernel->handle()\n#55 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/EG_BP/EasyGestBP/public/index.php(20): Illuminate\\Foundation\\Application->handleRequest()\n#56 /home/ghost/Desktop/hack_the_world/BIG_PROJECT/EG_BP/EasyGestBP/vendor/laravel/framework/src/Illuminate/Foundation/resources/server.php(23): require_once(\'...\')\n#57 {main}','/home/ghost/Desktop/hack_the_world/BIG_PROJECT/EG_BP/EasyGestBP/storage/framework/views/767b2eb22b5979dcd9c3e836200158a8.php',104,'GET','http://127.0.0.1:8000/retours','[]','Mozilla/5.0 (X11; Linux x86_64; rv:128.0) Gecko/20100101 Firefox/128.0','127.0.0.1',1,'qR4gILuxvZg1FGeUdmGOoRpKsWFnXiQFrbFnKyQR',500,'2026-01-27 14:17:45',1,'2026-01-27 14:17:45','2026-01-27 14:17:49');
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
) ENGINE=InnoDB AUTO_INCREMENT=472 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventaire_details`
--

LOCK TABLES `inventaire_details` WRITE;
/*!40000 ALTER TABLE `inventaire_details` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `inventaire_details` VALUES
(283,1,1,2,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(284,1,2,1,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(285,1,3,2,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(286,1,4,4,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(287,1,5,1,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(288,1,6,9,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(289,1,7,6,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(290,1,8,1,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(291,1,9,2,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(292,1,10,3,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(293,1,11,0,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(294,1,12,9,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(295,1,14,1,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(296,1,15,12,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(297,1,16,9,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(298,1,17,3,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(299,1,18,3,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(300,1,19,6,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(301,1,20,6,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(302,1,21,5,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(303,1,22,7,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(304,1,23,6,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(305,1,24,3,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(306,1,25,5,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(307,1,26,2,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(308,1,28,2,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(309,1,29,4,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(310,1,30,1,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(311,1,31,1,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(312,1,32,3,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(313,1,33,1,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(314,1,34,7,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(315,1,35,5,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(316,1,36,10,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(317,1,37,10,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(318,1,38,10,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(319,1,39,13,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(320,1,40,0,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(321,1,41,6,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(322,1,42,6,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(323,1,43,3,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(324,1,44,13,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(325,1,45,10,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(326,1,46,5,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(327,1,48,13,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(328,1,49,24,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(329,1,50,117,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(330,1,51,48,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(331,1,52,12,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(332,1,54,35,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(333,1,56,85,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(334,1,57,10,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(335,1,61,55,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(336,1,62,50,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(337,1,63,24,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(338,1,67,2,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(339,1,69,14,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(340,1,70,9,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(341,1,71,2,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(342,1,72,30,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(343,1,75,65,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(344,1,77,17,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(345,1,80,9,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(346,1,81,6,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(347,1,82,5,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(348,1,83,0,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(349,1,85,1,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(350,1,87,0,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(351,1,88,0,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(352,1,89,0,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(353,1,91,0,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(354,1,92,2,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(355,1,93,0,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(356,1,94,0,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(357,1,95,0,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(358,1,97,3,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(359,1,98,25,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(360,1,99,1,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(361,1,100,2,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(362,1,103,4,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(363,1,104,14,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(364,1,105,23,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(365,1,106,43,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(366,1,107,39,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(367,1,109,128,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(368,1,112,0,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(369,1,113,13,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(370,1,114,12,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(371,1,118,207,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(372,1,13,2,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(373,1,47,10,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(374,1,53,10,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(375,1,55,4,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(376,1,58,5,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(377,1,59,33,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(378,1,78,2,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(379,1,108,0,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(380,1,110,0,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(381,1,115,24,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(382,1,116,12,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(383,1,117,54,NULL,'2026-02-17 10:32:19','2026-02-17 10:32:19'),
(384,4,1,2,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(385,4,2,2,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(386,4,3,2,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(387,4,4,2,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(388,4,5,11,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(389,4,6,1,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(390,4,7,6,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(391,4,8,1,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(392,4,9,3,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(393,4,10,4,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(394,4,11,2,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(395,4,12,11,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(396,4,13,0,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(397,4,14,3,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(398,4,15,12,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(399,4,16,6,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(400,4,17,4,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(401,4,18,2,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(402,4,19,9,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(403,4,20,2,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(404,4,21,7,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(405,4,22,7,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(406,4,23,2,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(407,4,24,4,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(408,4,25,13,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(409,4,26,4,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(410,4,28,4,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(411,4,29,4,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(412,4,30,2,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(413,4,31,6,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(414,4,32,7,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(415,4,33,1,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(416,4,34,9,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(417,4,35,5,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(418,4,36,2,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(419,4,37,10,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(420,4,38,12,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(421,4,39,22,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(422,4,40,5,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(423,4,41,8,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(424,4,42,2,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(425,4,43,6,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(426,4,44,16,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(427,4,45,4,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(428,4,46,7,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(429,4,48,7,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(430,4,49,11,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(431,4,50,15,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(432,4,51,30,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(433,4,52,2,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(434,4,54,42,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(435,4,56,87,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(436,4,57,5,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(437,4,61,1,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(438,4,62,2,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(439,4,63,57,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(440,4,67,2,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(441,4,69,14,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(442,4,70,9,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(443,4,71,2,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(444,4,72,30,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(445,4,75,66,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(446,4,77,17,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(447,4,80,5,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(448,4,81,7,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(449,4,82,3,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(450,4,83,1,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(451,4,85,3,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(452,4,87,2,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(453,4,88,1,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(454,4,89,2,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(455,4,92,3,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(456,4,93,1,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(457,4,94,2,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(458,4,97,4,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(459,4,98,42,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(460,4,99,2,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(461,4,100,4,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(462,4,103,8,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(463,4,104,19,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(464,4,105,24,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(465,4,106,49,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(466,4,107,44,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(467,4,109,146,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(468,4,112,1,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(469,4,113,17,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(470,4,114,16,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05'),
(471,4,118,232,NULL,'2026-02-17 10:38:05','2026-02-17 10:38:05');
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventaires`
--

LOCK TABLES `inventaires` WRITE;
/*!40000 ALTER TABLE `inventaires` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `inventaires` VALUES
(1,4,5,'patisserie',1,1,'2026-01-24 13:24:42','[\"client_1769516884692_08djm6z26\",\"client_1769519490606_pb8i1da20\"]','2026-01-27 12:43:38','2026-01-27 13:24:42'),
(4,3,4,'patisserie',1,1,'2026-01-23 13:24:42','[\"client_1769519490606_pb8i1da20\"]','2026-01-27 14:54:13','2026-01-27 14:54:13');
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
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `personal_access_tokens` VALUES
(1,'App\\Models\\User',1,'auth_token','b67926c5f849754ca1169bfc098742d415680cf9f4cacce0e121aaf128dd4d21','[\"*\"]',NULL,NULL,'2026-01-27 12:16:21','2026-01-27 12:16:21'),
(14,'App\\Models\\User',6,'auth_token','67326e20d16d194923913fcf0c3851597172aab257ef6f1e88c662c5f9aee862','[\"*\"]',NULL,NULL,'2026-01-27 13:29:05','2026-01-27 13:29:05'),
(18,'App\\Models\\User',4,'auth_token','5b895f91a22504ca7dd5bdb895d2d2c2603efe3fee11c9372cb4de0790a3ba60','[\"*\"]',NULL,NULL,'2026-01-27 14:54:12','2026-01-27 14:54:12'),
(19,'App\\Models\\User',1,'auth_token','e4a31c275b9eb449f63c542a85b1a83d756631e5cdcfd81820ee4eaa273bc2e4','[\"*\"]',NULL,NULL,'2026-02-11 08:50:32','2026-02-11 08:50:32'),
(20,'App\\Models\\User',1,'auth_token','9b38095d4c2cd07a560cdd5bd7fc07a66ee400cacb23df99fdd42e165e78e69d','[\"*\"]',NULL,NULL,'2026-02-17 09:43:36','2026-02-17 09:43:36'),
(21,'App\\Models\\User',1,'auth_token','e24c3fed499e8e57540ebccf5f65649f09e8270d42887dd33c784d38e83b746c','[\"*\"]',NULL,NULL,'2026-02-17 14:52:46','2026-02-17 14:52:46'),
(22,'App\\Models\\User',1,'auth_token','cb352d33f43a340ca7155db065b8fdf6c33903cb3c16e00b2a6fa38e480d1448','[\"*\"]',NULL,NULL,'2026-02-17 21:20:29','2026-02-17 21:20:29');
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
) ENGINE=InnoDB AUTO_INCREMENT=119 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `produits`
--

LOCK TABLES `produits` WRITE;
/*!40000 ALTER TABLE `produits` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `produits` VALUES
(1,'cake rond',3000.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(2,'cake',3000.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(3,'cake choco',2000.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(4,'cake vanille',1750.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(5,'Bechamelle',1000.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(6,'pizza',1000.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(7,'quiche',1000.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(8,'croque monsieur',900.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(9,'croissant jambon',900.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(10,'friand',900.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(11,'pili',900.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(12,'cake choco',500.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(13,'kamar lot de 8',500.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(14,'croissant choco',500.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(15,'jumelle',500.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(16,'feuillete choco',500.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(17,'allumettes',450.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(18,'chausson pomme',450.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(19,'cake coco',450.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(20,'cake fruit confit',450.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(21,'cake rond raisin',450.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(22,'croissant',450.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(23,'doigt',450.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(24,'etoile',450.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(25,'pain chocolat',450.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(26,'porte feuille',450.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(27,'pudding',450.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(28,'raisin creme',450.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(29,'raisin sec',450.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(30,'roule creme',450.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(31,'triangle pomme',450.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(32,'4x4',400.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(33,'cake feuillete',400.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(34,'cake marbre',400.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(35,'flute',400.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(36,'langue',400.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(37,'mousseline',400.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(38,'palmier',400.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(39,'cake rond',350.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(40,'croissant boulan',350.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(41,'ficelle',350.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(42,'roule au sucre',350.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(43,'tresse au sucre',350.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(44,'triangle creme',350.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(45,'Beignet rond',250.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(46,'mini pain choco',250.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(47,'mini croissant',250.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(48,'brioche au sucre',200.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(49,'beignet tresse',150.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(50,'sable',150.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(51,'beignet souffle',125.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(52,'mini cake choco',125.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(53,'mini cake vanille',125.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(54,'sacristel',125.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(55,'beignet sucre',100.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(56,'croquette/chips',100.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(57,'muffins',100.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(58,'pom pom',100.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(59,'chouquettes',75.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(60,'madelaine',75.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(61,'biscuit choco',75.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(62,'biscuit vanille',75.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(63,'kamar',60.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(64,'bonbon/beignet',50.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(65,'gateau',13000.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(66,'gateau',9000.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(67,'gateau',7000.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(68,'gateau',4000.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(69,'HBD',1500.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(70,'HBD',1250.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(71,'HBD',1000.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(72,'HBD',900.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(73,'HBD',750.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(74,'Ballon',500.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(75,'Artifice/bougie',500.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(76,'chapeau',300.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(77,'pat/mocho/ball',800.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(78,'sandwich chaud',1200.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(79,'sandwich jambon',500.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(80,'sandwich saucisse',500.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(81,'sandwich viande',500.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(82,'hamburger',500.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(83,'roule cafe',900.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(84,'roule choco',900.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(85,'roule fraise',900.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(86,'roule poire',900.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(87,'roule vanille',900.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(88,'contraste',800.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(89,'foret blanche',800.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(90,'foret noir',800.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(91,'moka choco',800.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(92,'moka coco',800.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(93,'moka fraise',800.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(94,'moka vanille',800.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(95,'triangle choco',800.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(96,'Beignet creme',400.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(97,'Beignet choco',400.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(98,'Sac',100.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(99,'Mylo 500 ML',750.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(100,'Mylo Dokeri',600.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(101,'Mylo 330 ML',500.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(102,'Mylo Jus 500 ML',500.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(103,'Mylo Jus 250 ML',350.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(104,'Mylo Jus 170 G',300.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(105,'Mylo NATURE S,S',250.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(106,'Mylo 250 ML sachet',250.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(107,'Mylo boite',200.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(108,'Mylo sachet long',200.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(109,'Mylo 160 Ml sachet',150.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(110,'Mylo jus 150 Ml',150.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(111,'Mylo Jus',100.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(112,'Brio tortue',500.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(113,'sabot',250.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(114,'croquise',250.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(115,'aspiral',200.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(116,'bis baguette',150.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(117,'bis baguette',100.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-01-27 14:08:35'),
(118,'Olive/crystal',50.00,'patisserie',1,'[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:16:02','2026-02-17 10:11:50');
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
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `receptions_pointeur`
--

LOCK TABLES `receptions_pointeur` WRITE;
/*!40000 ALTER TABLE `receptions_pointeur` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `receptions_pointeur` VALUES
(1,6,2,6,10,4,0,'2026-01-24 13:24:42',NULL,'[\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:55:40','2026-01-27 14:08:35'),
(2,6,1,16,5,4,0,'2026-01-24 13:24:42',NULL,'[\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:55:40','2026-01-27 14:08:35'),
(3,6,1,18,4,4,0,'2026-01-24 13:24:42',NULL,'[\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:55:40','2026-01-27 14:08:35'),
(4,6,1,22,16,4,0,'2026-01-24 13:24:42',NULL,'[\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:55:40','2026-01-27 14:08:35'),
(5,6,1,23,5,4,0,'2026-01-24 13:24:42',NULL,'[\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:55:40','2026-01-27 14:08:35'),
(6,6,1,25,8,4,0,'2026-01-24 13:24:42',NULL,'[\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:55:40','2026-01-27 14:08:35'),
(7,6,1,28,6,4,0,'2026-01-24 13:24:42',NULL,'[\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:55:40','2026-01-27 14:08:35'),
(8,6,1,31,4,4,0,'2026-01-24 13:24:42',NULL,'[\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:55:40','2026-01-27 14:08:35'),
(9,6,1,33,6,4,0,'2026-01-24 13:24:42',NULL,'[\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:55:40','2026-01-27 14:08:35'),
(10,6,1,42,4,4,0,'2026-01-24 13:24:42',NULL,'[\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:55:40','2026-01-27 14:08:35'),
(11,6,1,45,6,4,0,'2026-01-24 13:24:42',NULL,'[\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:55:40','2026-01-27 14:08:35'),
(12,6,1,48,8,4,0,'2026-01-24 13:24:42',NULL,'[\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:55:40','2026-01-27 14:08:35'),
(13,6,1,50,109,4,0,'2026-01-24 13:24:42',NULL,'[\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:55:40','2026-01-27 14:08:35'),
(14,6,1,51,126,4,0,'2026-01-24 13:24:42',NULL,'[\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:55:40','2026-01-27 14:08:35'),
(15,6,1,53,12,4,0,'2026-01-24 13:24:42',NULL,'[\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:55:40','2026-01-27 14:08:35'),
(16,6,1,55,137,4,0,'2026-01-24 13:24:42',NULL,'[\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:55:40','2026-01-27 14:08:35'),
(17,6,1,57,19,4,0,'2026-01-24 13:24:42',NULL,'[\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:55:40','2026-01-27 14:08:35'),
(18,6,1,58,10,4,0,'2026-01-24 13:24:42',NULL,'[\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:55:40','2026-01-27 14:08:35'),
(19,6,1,4,2,4,0,'2026-01-24 13:24:42',NULL,'[\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:55:40','2026-01-27 14:08:35'),
(20,6,1,13,6,4,0,'2026-01-24 13:24:42',NULL,'[\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:55:40','2026-01-27 14:08:35'),
(25,6,1,20,6,4,0,'2026-01-24 13:24:42',NULL,'[\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 13:05:40','2026-01-27 14:08:35'),
(26,6,1,26,6,4,0,'2026-01-24 13:24:42',NULL,'[\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 13:05:40','2026-01-27 14:08:35'),
(27,6,1,36,9,4,0,'2026-01-24 13:24:42',NULL,'[\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 13:05:40','2026-01-27 14:08:35'),
(28,6,1,47,10,4,0,'2026-01-24 13:24:42',NULL,'[\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 13:05:40','2026-01-27 14:08:35'),
(29,6,1,49,15,4,0,'2026-01-24 13:24:42',NULL,'[\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 13:05:40','2026-01-27 14:08:35'),
(30,6,1,52,12,4,0,'2026-01-24 13:24:42',NULL,'[\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 13:05:40','2026-01-27 14:08:35'),
(31,6,1,55,106,4,0,'2026-01-24 13:24:42',NULL,'[\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 13:05:40','2026-01-27 14:08:35'),
(32,6,1,57,25,4,0,'2026-01-24 13:24:42',NULL,'[\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 13:05:40','2026-01-27 14:08:35'),
(33,6,1,59,103,4,0,'2026-01-24 13:24:42',NULL,'[\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 13:05:40','2026-01-27 14:08:35'),
(34,6,1,61,55,4,0,'2026-01-24 13:24:42',NULL,'[\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 13:05:40','2026-01-27 14:08:35'),
(35,6,1,62,50,4,0,'2026-01-24 13:24:42',NULL,'[\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 13:05:40','2026-01-27 14:08:35'),
(36,6,1,63,114,4,0,'2026-01-24 13:24:42',NULL,'[\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 13:05:40','2026-01-27 14:08:35'),
(37,6,1,66,3,4,0,'2026-01-24 13:24:42',NULL,'[\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 13:05:40','2026-01-27 14:08:35'),
(38,6,1,67,5,4,0,'2026-01-24 13:24:42',NULL,'[\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 13:05:40','2026-01-27 14:08:35'),
(39,6,1,68,4,4,0,'2026-01-24 13:24:42',NULL,'[\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 13:05:40','2026-01-27 14:08:35'),
(40,6,1,78,2,4,0,'2026-01-24 13:24:42',NULL,'[\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 13:05:40','2026-01-27 14:08:35'),
(41,6,1,80,5,4,0,'2026-01-24 13:24:42',NULL,'[\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 13:05:40','2026-01-27 14:08:35'),
(42,6,1,82,10,4,0,'2026-01-24 13:24:42',NULL,'[\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 13:05:40','2026-01-27 14:08:35'),
(43,6,1,115,26,4,0,'2026-01-24 13:24:42',NULL,'[\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 13:05:40','2026-01-27 14:08:35'),
(44,6,1,116,15,4,0,'2026-01-24 13:24:42',NULL,'[\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 13:05:40','2026-01-27 14:08:35'),
(45,6,1,117,56,4,0,'2026-01-24 13:24:42',NULL,'[\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 13:05:40','2026-01-27 14:08:35');
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
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `retours_produits`
--

LOCK TABLES `retours_produits` WRITE;
/*!40000 ALTER TABLE `retours_produits` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `retours_produits` VALUES
(1,6,4,18,1,'perime',NULL,0,'2026-01-24 13:24:42','[\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 13:05:40','2026-01-27 14:08:35'),
(2,6,4,31,1,'perime',NULL,0,'2026-01-24 13:24:42','[\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 13:05:40','2026-01-27 14:08:35'),
(5,6,4,33,1,'perime',NULL,0,'2026-01-24 13:24:42','[\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 13:06:20','2026-01-27 14:08:35'),
(6,6,4,55,15,'perime',NULL,0,'2026-01-24 13:24:42','[\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 13:06:20','2026-01-27 14:08:35'),
(7,6,4,63,48,'perime',NULL,0,'2026-01-24 13:24:42','[\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 13:06:20','2026-01-27 14:08:35'),
(8,6,4,2,1,'perime',NULL,0,'2026-01-24 13:24:42','[\"client_1769520544504_aka8o85pf\",\"client_1769519490606_pb8i1da20\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 13:39:06','2026-01-27 14:08:35'),
(9,6,4,11,1,'perime',NULL,0,'2026-01-24 13:24:42','[\"client_1769520544504_aka8o85pf\",\"client_1769519490606_pb8i1da20\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 13:39:06','2026-01-27 14:08:35'),
(10,6,4,19,1,'perime',NULL,0,'2026-01-24 13:24:42','[\"client_1769520544504_aka8o85pf\",\"client_1769519490606_pb8i1da20\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 13:39:06','2026-01-27 14:08:35'),
(11,6,4,31,6,'perime',NULL,0,'2026-01-24 13:24:42','[\"client_1769520544504_aka8o85pf\",\"client_1769519490606_pb8i1da20\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 13:39:06','2026-01-27 14:08:35'),
(12,6,4,10,1,'perime',NULL,0,'2026-01-24 13:24:42','[\"client_1769520544504_aka8o85pf\",\"client_1769519490606_pb8i1da20\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 13:39:06','2026-01-27 14:08:35'),
(13,6,4,26,4,'perime',NULL,0,'2026-01-24 13:24:42','[\"client_1769520544504_aka8o85pf\",\"client_1769519490606_pb8i1da20\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 13:39:06','2026-01-27 14:08:35'),
(14,6,4,9,1,'perime',NULL,0,'2026-01-24 13:24:42','[\"client_1769520544504_aka8o85pf\",\"client_1769519490606_pb8i1da20\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 13:39:06','2026-01-27 14:08:35'),
(15,6,4,39,5,'perime',NULL,0,'2026-01-24 13:24:42','[\"client_1769520544504_aka8o85pf\",\"client_1769519490606_pb8i1da20\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 13:39:06','2026-01-27 14:08:35'),
(16,6,4,21,2,'perime',NULL,0,'2026-01-24 13:24:42','[\"client_1769520544504_aka8o85pf\",\"client_1769519490606_pb8i1da20\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 13:39:06','2026-01-27 14:08:35'),
(17,6,4,20,2,'perime',NULL,0,'2026-01-24 13:24:42','[\"client_1769520544504_aka8o85pf\",\"client_1769519490606_pb8i1da20\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 13:39:06','2026-01-27 14:08:35'),
(18,6,4,67,1,'perime',NULL,0,'2026-01-24 13:24:42','[\"client_1769520544504_aka8o85pf\",\"client_1769519490606_pb8i1da20\",\"client_1768837845546_5l3vtsi2k\",\"client_1769516884692_08djm6z26\"]','2026-01-27 14:16:42','2026-01-27 14:33:42'),
(19,6,4,66,1,'perime',NULL,0,'2026-01-24 13:24:42','[\"client_1769520544504_aka8o85pf\",\"client_1769519490606_pb8i1da20\",\"client_1768837845546_5l3vtsi2k\",\"client_1769516884692_08djm6z26\"]','2026-01-27 14:16:42','2026-01-27 14:33:42'),
(20,6,4,75,2,'perime',NULL,0,'2026-01-24 13:24:42','[\"client_1769520544504_aka8o85pf\",\"client_1769519490606_pb8i1da20\",\"client_1768837845546_5l3vtsi2k\",\"client_1769516884692_08djm6z26\"]','2026-01-27 14:16:42','2026-01-27 14:33:42');
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
INSERT INTO `sessions` VALUES
('3YW18egPFd2WfHRx4DLExKsBT1VTAiWWd0eJQ0Cv',1,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64; rv:128.0) Gecko/20100101 Firefox/128.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoieVNmc2lYN25FdkpoQ3EycXY5ZmQ0NHlJZzZMVVlCbXNESHljZXpadyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9yZXRvdXJzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9',1771363277),
('fUmzMkSNNTsye4zs9KiyNdzywMUfcB4WJJgXksjy',NULL,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64; rv:128.0) Gecko/20100101 Firefox/128.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSVc3d3AyN2E3aktjWmZseDNxT3UybGZka3NzMlVHNFVVeHpjaU43cSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9pbnNjcmlwdGlvbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1771778838),
('rOe7dhVlBSnR39W4JmZXF43pXCa4j2ZfWNAJbQS2',1,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64; rv:128.0) Gecko/20100101 Firefox/128.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTjhsM0dseXJrdDV6M3NJNUw0TUJYVmxPb09QRXFOQVZMWVdKeERHTiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wZGcvZmx1eC1vcGVyYXRpb25uZWwvaW1wcmltZXI/ZGF0ZT0yMDI2LTAxLTI0JnZlbmRldXJfaWQ9NCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==',1771340034),
('sVAK1mZ0CU88JFPWR140nSjmT7dkLN5fHzV5FMUv',1,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64; rv:128.0) Gecko/20100101 Firefox/128.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiZ1BiRWVBZzJIQUtOZ2xVUGNCNFBoTU1Dbjd3WXlFaHAxUWlLQWRxeCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6ODQ6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wZGcvZmx1eC1vcGVyYXRpb25uZWw/ZGF0ZT0yMDI2LTAxLTI0JnByb2R1aXRfaWQ9JnZlbmRldXJfaWQ9NCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==',1771326578);
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
  `valeur_vente` decimal(10,2) DEFAULT NULL,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions_vente`
--

LOCK TABLES `sessions_vente` WRITE;
/*!40000 ALTER TABLE `sessions_vente` DISABLE KEYS */;
set autocommit=0;
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `users` VALUES
(1,'BiG BoSS','657929578','pdg','$2y$12$SztVzF.MzkjbuGBpWrOkpe/3ievMVaEqg2InRBt95UUu.Rw2ixVAK',1,'fr','[\"4b9406cc-989f-4847-b1b7-bcbcbefe935d\"]','Cf1K3RnBhn','2026-01-27 12:15:49','2026-01-27 12:16:21'),
(2,'unspecified','633445566','producteur','$2y$12$VTWfZtw4TBvLNWudgKn7Q.Jdyi11HFcJrzrK8FV7lCwhwjUPodmqy',1,'fr','[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','dFA7vmiyyR','2026-01-27 12:15:50','2026-01-27 14:08:35'),
(3,'TAM','611223344','vendeur_patisserie','$2y$12$7s7CnP8s.mz8SpNnKqg1Ken8l58KKtmVPvMwVOhWaKIvlsKIfqUIy',1,'fr','[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]',NULL,'2026-01-27 12:21:09','2026-01-27 14:08:35'),
(4,'SAGESSE','699630575','vendeur_patisserie','$2y$12$PaYnAzICJTzZTT0p30TazOgYq0nzAGGleMWgX.ls0jam.aG3Sp1Qq',1,'fr','[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]',NULL,'2026-01-27 12:21:30','2026-01-27 14:08:35'),
(5,'AUTRE PATISSIER','696087354','vendeur_patisserie','$2y$12$O.D00yl5AdTZ2PLdV69zueCn96LA/aYkruzSmvokm6TeHTf2ekTO6',1,'fr','[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]',NULL,'2026-01-27 12:21:55','2026-01-27 14:08:35'),
(6,'Pointeur','677339655','pointeur','$2y$12$o75vZMiwpdYzQTJp3ZBJ8.97wKYP1Bhtxa1q5oKobTQsg1f8IBNBC',1,'fr','[\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]',NULL,'2026-01-27 12:45:20','2026-01-27 14:08:35');
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vendeurs_actifs`
--

LOCK TABLES `vendeurs_actifs` WRITE;
/*!40000 ALTER TABLE `vendeurs_actifs` DISABLE KEYS */;
set autocommit=0;
INSERT INTO `vendeurs_actifs` VALUES
(1,'boulangerie',3,'2026-01-27 12:26:22','[\"client_1769511497519_owwdej1ax\",\"client_1769516607699_pxbqn80bh\",\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:22:24','2026-01-27 14:08:35'),
(2,'patisserie',4,'2026-01-27 14:54:12','[\"client_1769516884692_08djm6z26\",\"client_1769519365876_8ersmths3\",\"client_1769519490606_pb8i1da20\",\"client_1769520544504_aka8o85pf\",\"client_1769522356623_uji257ktp\",\"client_1768837845546_5l3vtsi2k\"]','2026-01-27 12:28:06','2026-01-27 14:54:12');
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

-- Dump completed on 2026-02-22 17:53:27
