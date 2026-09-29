-- MySQL dump 10.13  Distrib 8.4.3, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: booking_lapang
-- ------------------------------------------------------
-- Server version	8.4.3

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `bookings`
--

DROP TABLE IF EXISTS `bookings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bookings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `lapangan_id` bigint unsigned NOT NULL,
  `tanggal_booking` date NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `total_harga` decimal(10,2) NOT NULL,
  `status` enum('pending','confirmed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `metode_pembayaran` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_pembayaran` enum('unpaid','paid','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unpaid',
  `payment_reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bookings_payment_reference_unique` (`payment_reference`),
  KEY `bookings_user_id_foreign` (`user_id`),
  KEY `bookings_lapangan_id_foreign` (`lapangan_id`),
  KEY `bookings_status_index` (`status`),
  KEY `bookings_status_pembayaran_index` (`status_pembayaran`),
  KEY `bookings_tanggal_booking_index` (`tanggal_booking`),
  CONSTRAINT `bookings_lapangan_id_foreign` FOREIGN KEY (`lapangan_id`) REFERENCES `lapangans` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bookings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bookings`
--

LOCK TABLES `bookings` WRITE;
/*!40000 ALTER TABLE `bookings` DISABLE KEYS */;
INSERT INTO `bookings` VALUES (1,1,1,'2026-08-12','13:00:00','17:30:00',450000.00,'confirmed',NULL,'unpaid',NULL,'2026-08-11 23:22:19','2026-08-11 23:25:32'),(2,1,3,'2026-08-12','14:00:00','19:00:00',600000.00,'confirmed',NULL,'unpaid',NULL,'2026-08-12 00:45:03','2026-08-12 00:45:41'),(3,1,2,'2026-08-12','14:00:00','18:00:00',200000.00,'cancelled',NULL,'unpaid',NULL,'2026-08-12 00:46:09','2026-08-12 00:52:08'),(4,1,1,'2026-08-31','14:00:00','17:00:00',300000.00,'cancelled',NULL,'unpaid',NULL,'2026-08-12 00:53:29','2026-08-12 00:53:44'),(5,1,2,'2026-08-18','12:40:00','17:00:00',216666.67,'confirmed',NULL,'unpaid',NULL,'2026-08-17 22:41:03','2026-08-17 22:43:32'),(6,1,1,'2026-08-23','12:50:00','16:00:00',316666.67,'confirmed',NULL,'unpaid',NULL,'2026-08-17 22:47:41','2026-08-17 22:47:54'),(7,1,3,'2026-08-27','16:00:00','22:00:00',720000.00,'confirmed',NULL,'unpaid',NULL,'2026-08-17 22:55:18','2026-08-17 22:55:34'),(8,1,1,'2026-09-01','13:00:00','16:00:00',300000.00,'confirmed',NULL,'unpaid',NULL,'2026-08-17 22:59:21','2026-08-17 22:59:30'),(9,3,1,'2026-09-05','10:00:00','12:00:00',200000.00,'pending',NULL,'unpaid',NULL,'2026-08-18 23:16:06','2026-08-18 23:16:06'),(10,1,2,'2026-09-01','14:00:00','20:30:00',325000.00,'pending',NULL,'unpaid','BOOKING-10-1787212414','2026-08-20 00:31:58','2026-08-20 00:53:35'),(11,1,1,'2026-08-20','14:55:00','20:00:00',508333.33,'confirmed',NULL,'paid','BOOKING-11-1787212535','2026-08-20 00:54:04','2026-08-20 00:56:51'),(12,1,3,'2026-08-20','20:00:00','22:00:00',240000.00,'pending',NULL,'failed','BOOKING-12-1787213648','2026-08-20 01:13:58','2026-08-20 01:16:43');
/*!40000 ALTER TABLE `bookings` ENABLE KEYS */;
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
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
INSERT INTO `failed_jobs` VALUES (1,'2f6ca532-a072-4677-87fc-54af04dbb868','database','default','{\"uuid\":\"2f6ca532-a072-4677-87fc-54af04dbb868\",\"displayName\":\"App\\\\Notifications\\\\BookingDikonfirmasi\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"deleteWhenMissingModels\":false,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";a:1:{i:0;i:1;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:37:\\\"App\\\\Notifications\\\\BookingDikonfirmasi\\\":2:{s:10:\\\"\\u0000*\\u0000booking\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:18:\\\"App\\\\Models\\\\Booking\\\";s:2:\\\"id\\\";i:8;s:9:\\\"relations\\\";a:1:{i:0;s:4:\\\"user\\\";}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:2:\\\"id\\\";s:36:\\\"1ea5dd0b-5149-4799-966f-939af11d3795\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\",\"batchId\":null},\"createdAt\":1787032770,\"delay\":null}','Exception: Simulasi job gagal in D:\\laragon\\www\\Booking\\booking-lapang\\app\\Notifications\\BookingDikonfirmasi.php:25\nStack trace:\n#0 D:\\laragon\\www\\Booking\\booking-lapang\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\Channels\\MailChannel.php(55): App\\Notifications\\BookingDikonfirmasi->toMail(Object(App\\Models\\User))\n#1 D:\\laragon\\www\\Booking\\booking-lapang\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(165): Illuminate\\Notifications\\Channels\\MailChannel->send(Object(App\\Models\\User), Object(App\\Notifications\\BookingDikonfirmasi))\n#2 D:\\laragon\\www\\Booking\\booking-lapang\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(120): Illuminate\\Notifications\\NotificationSender->sendToNotifiable(Object(App\\Models\\User), \'afc62e74-addb-4...\', Object(App\\Notifications\\BookingDikonfirmasi), \'mail\')\n#3 D:\\laragon\\www\\Booking\\booking-lapang\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\Localizable.php(21): Illuminate\\Notifications\\NotificationSender->Illuminate\\Notifications\\{closure}()\n#4 D:\\laragon\\www\\Booking\\booking-lapang\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(115): Illuminate\\Notifications\\NotificationSender->withLocale(NULL, Object(Closure))\n#5 D:\\laragon\\www\\Booking\\booking-lapang\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\ChannelManager.php(61): Illuminate\\Notifications\\NotificationSender->sendNow(Object(Illuminate\\Database\\Eloquent\\Collection), Object(App\\Notifications\\BookingDikonfirmasi), Array)\n#6 D:\\laragon\\www\\Booking\\booking-lapang\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\SendQueuedNotifications.php(130): Illuminate\\Notifications\\ChannelManager->sendNow(Object(Illuminate\\Database\\Eloquent\\Collection), Object(App\\Notifications\\BookingDikonfirmasi), Array)\n#7 D:\\laragon\\www\\Booking\\booking-lapang\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Notifications\\SendQueuedNotifications->handle(Object(Illuminate\\Notifications\\ChannelManager))\n#8 D:\\laragon\\www\\Booking\\booking-lapang\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#9 D:\\laragon\\www\\Booking\\booking-lapang\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#10 D:\\laragon\\www\\Booking\\booking-lapang\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#11 D:\\laragon\\www\\Booking\\booking-lapang\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(800): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#12 D:\\laragon\\www\\Booking\\booking-lapang\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(136): Illuminate\\Container\\Container->call(Array)\n#13 D:\\laragon\\www\\Booking\\booking-lapang\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(180): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#14 D:\\laragon\\www\\Booking\\booking-lapang\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#15 D:\\laragon\\www\\Booking\\booking-lapang\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(140): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#16 D:\\laragon\\www\\Booking\\booking-lapang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(155): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(Illuminate\\Notifications\\SendQueuedNotifications), false)\n#17 D:\\laragon\\www\\Booking\\booking-lapang\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(180): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#18 D:\\laragon\\www\\Booking\\booking-lapang\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(137): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#19 D:\\laragon\\www\\Booking\\booking-lapang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(148): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#20 D:\\laragon\\www\\Booking\\booking-lapang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(86): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#21 D:\\laragon\\www\\Booking\\booking-lapang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#22 D:\\laragon\\www\\Booking\\booking-lapang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(559): Illuminate\\Queue\\Jobs\\Job->fire()\n#23 D:\\laragon\\www\\Booking\\booking-lapang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(505): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#24 D:\\laragon\\www\\Booking\\booking-lapang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(257): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#25 D:\\laragon\\www\\Booking\\booking-lapang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(149): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#26 D:\\laragon\\www\\Booking\\booking-lapang\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(132): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#27 D:\\laragon\\www\\Booking\\booking-lapang\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#28 D:\\laragon\\www\\Booking\\booking-lapang\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#29 D:\\laragon\\www\\Booking\\booking-lapang\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#30 D:\\laragon\\www\\Booking\\booking-lapang\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#31 D:\\laragon\\www\\Booking\\booking-lapang\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(800): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#32 D:\\laragon\\www\\Booking\\booking-lapang\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(292): Illuminate\\Container\\Container->call(Array)\n#33 D:\\laragon\\www\\Booking\\booking-lapang\\vendor\\symfony\\console\\Command\\Command.php(341): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#34 D:\\laragon\\www\\Booking\\booking-lapang\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(261): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#35 D:\\laragon\\www\\Booking\\booking-lapang\\vendor\\symfony\\console\\Application.php(1117): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#36 D:\\laragon\\www\\Booking\\booking-lapang\\vendor\\symfony\\console\\Application.php(356): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#37 D:\\laragon\\www\\Booking\\booking-lapang\\vendor\\symfony\\console\\Application.php(195): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#38 D:\\laragon\\www\\Booking\\booking-lapang\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(198): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#39 D:\\laragon\\www\\Booking\\booking-lapang\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1242): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#40 D:\\laragon\\www\\Booking\\booking-lapang\\artisan(16): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#41 {main}','2026-08-17 22:59:32');
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
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
  `attempts` smallint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lapangans`
--

DROP TABLE IF EXISTS `lapangans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lapangans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_lapangan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `harga_per_jam` decimal(10,2) NOT NULL,
  `status` enum('aktif','nonaktif') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lapangans`
--

LOCK TABLES `lapangans` WRITE;
/*!40000 ALTER TABLE `lapangans` DISABLE KEYS */;
INSERT INTO `lapangans` VALUES (1,'Lapangan Futsal A','futsal',100000.00,'aktif',NULL,NULL),(2,'Lapangan Badminton B','badminton',50000.00,'aktif',NULL,NULL),(3,'Lapangan Basket C','basket',120000.00,'aktif',NULL,'2026-08-11 23:32:53');
/*!40000 ALTER TABLE `lapangans` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_08_10_000001_create_lapangans_table',1),(5,'2026_08_10_000002_create_bookings_table',1),(6,'2026_08_12_050739_add_role_to_users_table',1),(7,'2026_08_19_032144_create_personal_access_tokens_table',2),(8,'2026_08_20_000001_add_payment_columns_to_bookings_table',3),(9,'2026_08_24_030534_add_index_to_bookings_table',4);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
INSERT INTO `personal_access_tokens` VALUES (1,'App\\Models\\User',1,'api-token','f320d6688eefe2b471fbacc64eb1678b8f4900b36c0968c0235f4a16f9e57566','[\"*\"]',NULL,NULL,'2026-08-18 20:57:08','2026-08-18 20:57:08'),(3,'App\\Models\\User',1,'api-token','e0d4ff0e68986c77cbf736388d7502f8c1258dbaa224180042dfa87a61284b64','[\"*\"]',NULL,NULL,'2026-08-18 23:20:55','2026-08-18 23:20:55'),(4,'App\\Models\\User',1,'api-token','c5e22de11dcb91c425e0c4429ea177245c110474748cb3b609ed55e665c9cbed','[\"*\"]','2026-08-18 23:22:18',NULL,'2026-08-18 23:21:32','2026-08-18 23:22:18');
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
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
INSERT INTO `sessions` VALUES ('72WNvZq6FxkvwrYXBzgjXv1eZeyvoAyyoBnLa4kF',NULL,'127.0.0.1','Veritrans','eyJfdG9rZW4iOiJTeDByMVYzSjlZTkQwbWZudnFkaHA4cGhUejRNeTJucklzQTltMXFDIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1787213803),('DxByh6J67YHFKLfIdzj8jW3Cl88tfXCnRtyIxl19',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.133.0 Chrome/148.0.7778.280 Electron/42.8.0 Safari/537.36','eyJfdG9rZW4iOiI2a0RFRTZGZll2c1p6WUdCb3lEZGJnMFhlS2xZbjQ2M0RYY1pMNXdPIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19',1787111675),('jx4QNlBymsNCCzuybJ3u7CUDLoxtWu460pAR95bQ',NULL,'127.0.0.1','Veritrans','eyJfdG9rZW4iOiIyM2I1bjFsQ0p6bDlLbE9LQ1QwSFRDa0Q1dzV0WnEyWklKcU5OWGtlIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1787212611),('KdKRGdHUj0mfjgVfbhNhPFitrqXbVItoeRk0rFCQ',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.133.0 Chrome/148.0.7778.280 Electron/42.8.0 Safari/537.36','eyJfdG9rZW4iOiJJakUxb0pIT0dPcGYwY3hCUlFPZ2FkdU9Va2JKUGJTYmlFQURPVEo4IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19',1787119528),('Kzw158Qg3zsAVkwdfONy2t0Bw93Em59hv0KH2bSY',NULL,'127.0.0.1','Veritrans','eyJfdG9rZW4iOiJlQ25MNXNCblRpckNvdGUyODE0UW9YazNTWFFOa3JibjZubHRlc0MzIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1787212546),('mfZSggSfvGlYOOPggNZ4hUptlbQHClIxiyAbGvMw',NULL,'127.0.0.1','Thunder Client (https://www.thunderclient.com)','eyJfdG9rZW4iOiJBTmVOVmhSd3Z4OWMyWTZZZDB4Rm5aQXV1Y2huTVczazUxbHNYVENLIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9oZWFsdGgiLCJyb3V0ZSI6bnVsbH0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1787543468),('NryBm2S5KCNj6h5eIjtrtqg4F6RlDiNPjWV91mci',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJhSXNKdEo3anpkMG80YXpDdHhnaEFEc2VzNlBXODZtZ1g1ckRxSjdFIiwidXJsIjpbXSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2Jvb2tpbmdcLzEyXC9iYXlhciIsInJvdXRlIjoiYm9va2luZy5iYXlhciJ9LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MX0=',1787213648),('nZu1GVNsMFhaaxrXN1dMEPD6A88MH1r67JNEuZtO',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJnUDJQN2Y1YnZ5VkhycWhRblZMN2FxQVhCQmRzVFdVa2g2dklza25CIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2Jvb2tpbmciLCJyb3V0ZSI6ImJvb2tpbmcuaW5kZXgifSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjF9',1786521239),('Qb3Lyf8IHOTTqAq4SMCvJwfruFcDeiRsvODZXE0V',NULL,'127.0.0.1','Thunder Client (https://www.thunderclient.com)','eyJfdG9rZW4iOiJOM1ZteU5RQUpHdnp4YjIyRkp0d05SQmV4cEVrTFkzRVBmdlBCUDNOIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1787213988),('QLh4zPQeNkXaNORyfvr9Z5tfYCLJ0SQKtwVzuw2K',NULL,'127.0.0.1','Thunder Client (https://www.thunderclient.com)','eyJfdG9rZW4iOiJrdnEzckJMUTdCREpnakRUWmlIQ0UyQ2diUW5lenZBWnpnTHhCUlo4IiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1787111725),('U7KJgrc7XbUIgGBd48veUsdCHl5r9eHh9zNZl7Lr',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.133.0 Chrome/148.0.7778.280 Electron/42.8.0 Safari/537.36','eyJfdG9rZW4iOiJ0UjRHbGtDT2hmSnJGSzU2d09iUmlkSXBVdTg5R0Fab3NjNDc3YjBhIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19',1787210762),('uCZPhz9Z8FISpUJ7n5hX4N22uBAUSobJfVm7RIvX',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJiS3JoRXIwWm1uRkNTbVNyc2o0RFZ5dHRhek1jSXlMOUNEbnMzTVdFIiwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjEsIl9wcmV2aW91cyI6eyJ1cmwiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYWRtaW5cL2Jvb2tpbmdcL2V4cG9ydCIsInJvdXRlIjoiYWRtaW4uYm9va2luZy5leHBvcnQifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1787034113),('uUs4NZ7ZzYl4xlBnqYekCrCCBid7NZbYV4DsINYr',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJBdWlBSlpVQ2FZYnE4MDBRZ0tSb1VNdmxVYmtiMllwQ3h6NXFNcXhHIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2xvZ2luIiwicm91dGUiOiJsb2dpbiJ9fQ==',1787111694),('xtshM6COKJXxZG7i9BEdP6NUya9OI5iHk6O74rXQ',NULL,'127.0.0.1','Veritrans','eyJfdG9rZW4iOiIxbTNuTU9qOWNqMEh4dWNpdXdOVVBheHZ2VkNxMHFvS1haVEM1WXFYIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1787213680),('zAjX2yOlkeVMrBYHeHs6djLkpIjq4l5LOHZLcyFO',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.132.1 Chrome/148.0.7778.280 Electron/42.7.1 Safari/537.36','eyJfdG9rZW4iOiJhRmpJZGZLY3RTQU5xbEEwemxwdzJaTlhKeFpNUmh5b3FPS3hGTzhuIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19',1787031456);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
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
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'testing','testing@test.com',NULL,'$2y$12$k.cDhXh09Zrr/3yWsq0k.OGJmHivSC8P0ZQXYVu73EnSUvaD1Z3ne','sc0x1DpjEWTvLID9Vx5zZe0OiAOK72XwuvB6oYUyP8NmvPAxcKdTP6lt6jEB','2026-08-11 22:36:42','2026-08-11 22:37:38','admin'),(2,'kiwong','test1@example.com',NULL,'$2y$12$JsLN9lGDfBnOZV7UbXE8g.kaFwvaqgqS6ty8DRbstEFrU13lDYKFG',NULL,'2026-08-11 23:19:55','2026-08-11 23:19:55','user'),(3,'user lain','lain@test.com',NULL,'$2y$12$0Q4grsHtVDjPixqoYE.V8uJU.OfpjmQvlTwDiVMtUwHQtmf1wTcd.',NULL,'2026-08-18 23:14:37','2026-08-18 23:14:37','user');
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

-- Dump completed on 2026-08-24 10:53:42
