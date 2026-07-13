-- MySQL dump 10.13  Distrib 8.0.36, for Linux (x86_64)
--
-- Host: localhost    Database: school_lms_db
-- ------------------------------------------------------
-- Server version	8.0.36-0ubuntu0.23.10.1

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
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
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
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_reset_tokens_table',1),(3,'2019_08_19_000000_create_failed_jobs_table',1),(4,'2019_12_14_000001_create_personal_access_tokens_table',1),(5,'2024_02_01_095655_update_user_table',2),(6,'2024_02_01_100027_create_mst_chapters_table',3),(7,'2024_02_01_100251_create_mst_classes_table',4),(8,'2024_02_01_100339_update_mst_chapter_table',5),(9,'2024_02_01_100529_create_trn_chapter_assets_table',6),(10,'2024_02_01_100728_create_trn_chapter_teachers_table',7),(11,'2024_02_01_100844_create_trn_teacher_classes_table',8),(12,'2024_02_01_101059_update_trn_chapter_teachers_table',9),(13,'2014_10_12_100000_create_password_resets_table',10),(14,'2024_02_20_035130_change_foreign_key_to_json_on_my_table',10),(15,'2024_02_24_131924_add_column_to_table_name',10),(16,'2024_03_11_161018_create_jobs_table',11);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mst_chapters`
--

DROP TABLE IF EXISTS `mst_chapters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mst_chapters` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `release_date` date NOT NULL,
  `chapter_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_by` bigint unsigned NOT NULL,
  `status` int NOT NULL DEFAULT '1' COMMENT '1:Active, 0:Inactive',
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `asset_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `class_data` json DEFAULT NULL,
  `visibility` int NOT NULL DEFAULT '0' COMMENT '1:All Classes, 0:Class Specific',
  PRIMARY KEY (`id`),
  KEY `mst_chapters_created_by_foreign` (`created_by`),
  CONSTRAINT `mst_chapters_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mst_chapters`
--

LOCK TABLES `mst_chapters` WRITE;
/*!40000 ALTER TABLE `mst_chapters` DISABLE KEYS */;
INSERT INTO `mst_chapters` VALUES (1,'Surya Namaskar','2024-03-08','1709887748.jpg','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus condimentum sagittis lacus, laoreet luctus ligula laoreet ut. Vestibulum ullamcorper accumsan velit vel vehicula. Proin tempor lacus arcu. Nunc at elit condimentum, semper nisi et, condimentum mi. In venenatis blandit nibh at sollicitudin. Vestibulum dapibus mauris at orci maximus pellentesque. Nullam id elementum ipsum. Suspendisse cursus lobortis viverra. Proin et erat at mauris tincidunt porttitor vitae ac dui. Donec vulputate lorem tortor, nec fermentum nibh bibendum vel. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent dictum luctus massa, non euismod lacus. Pellentesque condimentum dolor est, ut dapibus lectus luctus ac. Ut sagittis commodo arcu. Integer nisi nulla, facilisis sit amet nulla quis, eleifend suscipit purus. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Aliquam euismod ultrices lorem, sit amet imperdiet est tincidunt vel. Phasellus dictum ju</p><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus condimentum sagittis lacus, laoreet luctus ligula laoreet ut. Vestibulum ullamcorper accumsan velit vel vehicula. Proin tempor lacus arcu. Nunc at elit condimentum, semper nisi et, condimentum mi. In venenatis blandit nibh at sollicitudin. Vestibulum dapibus mauris at orci maximus pellentesque. Nullam id elementum ipsum. Suspendisse cursus lobortis viverra. Proin et erat at mauris tincidunt porttitor vitae ac dui. Donec vulputate lorem tortor, nec fermentum nibh bibendum vel. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent dictum luctus massa, non euismod lacus. Pellentesque condimentum dolor est, ut dapibus lectus luctus ac. Ut sagittis commodo arcu. Integer nisi nulla, facilisis sit amet nulla quis, eleifend suscipit purus. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Aliquam euismod ultrices lorem, sit amet imperdiet est tincidunt vel. Phasellus dictum ju<br></p>',1,2,'surya-namaskar-1709887268','zOL6YMRl','2024-03-08 08:41:08','2024-03-11 09:21:32','[]',1),(2,'Mathematics','2024-03-09','1709890821.jpg','<p><span style=\"color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; text-align: justify;\">\"But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer of the truth, the master-builder of human happiness. No one rejects, dislikes, or avoids pleasure itself, because it is pleasure, but because those who do not know how to pursue pleasure rationally encounter consequences that are extremely painful. Nor again is there anyone who loves or pursues or desires to obtain pain of itself, because it is pain, but because occasionally circumstances occur in which toil and pain can procure him some great pleasure. To take a trivial example, which of us ever undertakes laborious physical exercise, except to obtain some advantage from it? But who has any right to find fault with a man who chooses to enjoy a pleasure that has no annoying consequences, or one who avoids a pain that produces no resultant pleasure?\"</span></p><p><span style=\"color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; text-align: justify;\">\"But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer of the truth, the master-builder of human happiness. No one rejects, dislikes, or avoids pleasure itself, because it is pleasure, but because those who do not know how to pursue pleasure rationally encounter consequences that are extremely painful. Nor again is there anyone who loves or pursues or desires to obtain pain of itself, because it is pain, but because occasionally circumstances occur in which toil and pain can procure him some great pleasure. To take a trivial example, which of us ever undertakes laborious physical exercise, except to obtain some advantage from it? But who has any right to find fault with a man who chooses to enjoy a pleasure that has no annoying consequences, or one who avoids a pain that produces no resultant pleasure?\"</span></p><p><span style=\"color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; text-align: justify;\"><br></span><br></p>',1,1,'mathematics-1709890821','5mGwhdXH','2024-03-08 09:40:21','2024-03-08 10:19:53','[\"1\"]',0),(3,'Science Chapter','2024-03-09','1709894770.jpg','<h3 style=\"margin: 15px 0px; padding: 0px; font-size: 14px; font-family: &quot;Open Sans&quot;, Arial, sans-serif;\">Section 1.10.33 of \"de Finibus Bonorum et Malorum\", written by Cicero in 45 BC</h3><p style=\"margin-right: 0px; margin-bottom: 15px; margin-left: 0px; padding: 0px; text-align: justify; color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif;\">\"At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus. Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates repudiandae sint et molestiae non recusandae. Itaque earum rerum hic tenetur a sapiente delectus, ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus asperiores repellat.\"</p>',1,1,'science-1709893639','2AVMZVOU','2024-03-08 10:27:19','2024-03-08 10:50:29','[\"1\"]',0),(4,'Computer Seminar 1','2024-03-11','1709897565.png','<p>For All classes this class is Manadatory.</p>',1,1,'computer-seminar-1709897565','GZITKHb4','2024-03-08 11:32:45','2024-03-11 07:55:04','[\"1\", \"2\", \"3\"]',0),(5,'PT','2024-03-11','1710145383.jpg','<p>Rules for PT Lectures&nbsp;</p><p>1- Should be present in School PT uniform.</p><p>2- Should carry Water bottle every PT Lecture.</p><p><br></p>',1,1,'pt-1710145383','xIt1Y8xN','2024-03-11 08:23:03','2024-03-11 08:23:04','[]',1),(6,'create Test chapter','2024-03-18','1710578778.jpg','<p>Test Chapter Email&nbsp; 18 march 2024</p>',1,1,'create-test-chapter-1710321314','O1ClAaKh','2024-03-13 09:15:14','2024-03-16 08:46:18','[\"2\"]',0),(7,'Test Chapter 1','2024-03-18','1710578567.jpg','<p>Release date Chapter Email</p>',1,1,'test-chapter-1-1710578567','kI5yzCRV','2024-03-16 08:42:47','2024-03-16 08:42:48','[]',1),(8,'Test Chapter 1','2024-03-20','1710759808.jpg','<p>Test Chapter&nbsp;</p>',1,1,'test-chapter-1-1710759808','oCsUWDnv','2024-03-18 11:03:28','2024-03-18 11:03:28','[\"1\", \"2\", \"3\"]',0),(9,'orientation','2024-04-01',NULL,'<p><br></p><p><br></p><p>getting acquainted</p>',1,1,'orientation-1711976076','QwMBKLPs','2024-04-01 12:54:36','2024-04-01 12:54:36','[]',1);
/*!40000 ALTER TABLE `mst_chapters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mst_classes`
--

DROP TABLE IF EXISTS `mst_classes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mst_classes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `class_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` int NOT NULL DEFAULT '1' COMMENT '1:Active, 0:Inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mst_classes`
--

LOCK TABLES `mst_classes` WRITE;
/*!40000 ALTER TABLE `mst_classes` DISABLE KEYS */;
INSERT INTO `mst_classes` VALUES (1,'10th (A) Class',1,'2024-03-08 06:42:33','2024-03-08 09:22:34'),(2,'10th (B) Class',1,'2024-03-08 09:27:45','2024-03-08 09:27:45'),(3,'1st Std Class',1,'2024-03-11 05:36:42','2024-03-11 05:44:57'),(4,'04_Del_9th A Test',2,'2024-03-11 15:22:29','2024-03-11 15:22:36'),(5,'05_Del_9th A Test',2,'2024-03-11 15:22:50','2024-03-11 15:22:56'),(6,'06_Del_Test Class',2,'2024-03-13 07:00:50','2024-03-13 07:01:09'),(7,'07_Del_Test Class',2,'2024-03-13 07:01:59','2024-03-13 07:02:10'),(8,'1st Class',1,'2024-03-30 05:34:18','2024-03-30 05:34:18'),(9,'teacher orientation',1,'2024-04-01 12:51:00','2024-04-01 12:51:00');
/*!40000 ALTER TABLE `mst_classes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `trn_chapter_assets`
--

DROP TABLE IF EXISTS `trn_chapter_assets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `trn_chapter_assets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `chapter_id` bigint unsigned NOT NULL,
  `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int NOT NULL DEFAULT '1' COMMENT '1:Active, 0:Inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trn_chapter_assets_chapter_id_foreign` (`chapter_id`),
  CONSTRAINT `trn_chapter_assets_chapter_id_foreign` FOREIGN KEY (`chapter_id`) REFERENCES `mst_chapters` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trn_chapter_assets`
--

LOCK TABLES `trn_chapter_assets` WRITE;
/*!40000 ALTER TABLE `trn_chapter_assets` DISABLE KEYS */;
/*!40000 ALTER TABLE `trn_chapter_assets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `trn_chapter_teachers`
--

DROP TABLE IF EXISTS `trn_chapter_teachers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `trn_chapter_teachers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `chapter_id` bigint unsigned NOT NULL,
  `teacher_id` bigint unsigned NOT NULL,
  `status` int NOT NULL DEFAULT '1' COMMENT '1:Active, 0:Inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `seen_status` int NOT NULL DEFAULT '0' COMMENT '1:Seen, 0:Unseen',
  `seen_date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trn_chapter_teachers_chapter_id_foreign` (`chapter_id`),
  KEY `trn_chapter_teachers_teacher_id_foreign` (`teacher_id`),
  CONSTRAINT `trn_chapter_teachers_chapter_id_foreign` FOREIGN KEY (`chapter_id`) REFERENCES `mst_chapters` (`id`) ON DELETE CASCADE,
  CONSTRAINT `trn_chapter_teachers_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trn_chapter_teachers`
--

LOCK TABLES `trn_chapter_teachers` WRITE;
/*!40000 ALTER TABLE `trn_chapter_teachers` DISABLE KEYS */;
INSERT INTO `trn_chapter_teachers` VALUES (2,1,2,2,'2024-03-08 08:49:08','2024-03-11 09:21:32',1,NULL),(6,2,2,1,'2024-03-08 09:41:28','2024-03-08 10:19:53',1,NULL),(11,3,2,1,'2024-03-08 10:46:38','2024-03-08 10:50:29',1,NULL),(12,4,2,1,'2024-03-08 11:32:45','2024-03-11 07:55:04',1,NULL),(17,4,3,1,'2024-03-11 07:55:04','2024-03-11 07:55:04',0,NULL),(18,4,5,1,'2024-03-11 07:55:04','2024-03-11 07:55:04',0,NULL),(19,4,7,1,'2024-03-11 07:55:04','2024-03-11 07:55:04',0,NULL),(20,5,2,1,'2024-03-11 08:23:03','2024-03-11 08:23:03',0,NULL),(21,5,3,1,'2024-03-11 08:23:03','2024-03-11 08:23:03',0,NULL),(22,5,5,1,'2024-03-11 08:23:03','2024-03-11 08:23:03',0,NULL),(23,5,7,1,'2024-03-11 08:23:03','2024-03-11 08:23:03',0,NULL),(28,7,2,1,'2024-03-16 08:42:47','2024-03-20 05:09:55',1,NULL),(29,7,3,1,'2024-03-16 08:42:47','2024-03-16 08:42:47',0,NULL),(30,7,5,1,'2024-03-16 08:42:47','2024-03-16 08:42:47',0,NULL),(31,7,7,1,'2024-03-16 08:42:47','2024-03-16 08:42:47',0,NULL),(32,6,2,1,'2024-03-16 08:46:18','2024-03-20 04:34:29',1,NULL),(33,6,3,1,'2024-03-16 08:46:18','2024-03-16 08:46:18',0,NULL),(34,6,5,1,'2024-03-16 08:46:18','2024-03-16 08:46:18',0,NULL),(35,6,7,1,'2024-03-16 08:46:18','2024-03-16 08:46:18',0,NULL),(36,8,2,1,'2024-03-18 11:03:28','2024-03-20 05:11:38',1,NULL),(37,8,3,1,'2024-03-18 11:03:28','2024-03-18 11:03:28',0,NULL),(38,8,5,1,'2024-03-18 11:03:28','2024-03-18 11:03:28',0,NULL),(39,8,7,1,'2024-03-18 11:03:28','2024-03-18 11:03:28',0,NULL),(40,9,2,1,'2024-04-01 12:54:36','2024-04-02 05:18:06',1,NULL),(41,9,3,1,'2024-04-01 12:54:36','2024-04-01 12:54:36',0,NULL),(42,9,5,1,'2024-04-01 12:54:36','2024-04-01 12:54:36',0,NULL),(43,9,7,1,'2024-04-01 12:54:36','2024-04-01 12:54:36',0,NULL),(44,9,9,1,'2024-04-01 12:54:36','2024-04-01 12:54:36',0,NULL),(45,9,10,1,'2024-04-01 12:54:36','2024-04-01 12:55:58',1,NULL);
/*!40000 ALTER TABLE `trn_chapter_teachers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `trn_teacher_classes`
--

DROP TABLE IF EXISTS `trn_teacher_classes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `trn_teacher_classes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `teacher_id` bigint unsigned NOT NULL,
  `class_id` bigint unsigned NOT NULL,
  `status` int NOT NULL DEFAULT '1' COMMENT '1:Active, 0:Inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trn_teacher_classes_teacher_id_foreign` (`teacher_id`),
  KEY `trn_teacher_classes_class_id_foreign` (`class_id`),
  CONSTRAINT `trn_teacher_classes_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `mst_classes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `trn_teacher_classes_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `trn_teacher_classes`
--

LOCK TABLES `trn_teacher_classes` WRITE;
/*!40000 ALTER TABLE `trn_teacher_classes` DISABLE KEYS */;
INSERT INTO `trn_teacher_classes` VALUES (4,3,2,1,'2024-03-08 09:32:30','2024-03-08 09:32:30'),(16,5,2,1,'2024-03-11 06:40:16','2024-03-11 06:40:16'),(17,7,1,1,'2024-03-11 06:44:43','2024-03-11 06:44:43'),(18,7,2,1,'2024-03-11 06:44:43','2024-03-11 06:44:43'),(19,7,3,1,'2024-03-11 06:44:43','2024-03-11 06:44:43'),(20,9,1,1,'2024-03-19 11:27:10','2024-03-19 11:27:10'),(21,9,2,1,'2024-03-19 11:27:10','2024-03-19 11:27:10'),(22,2,1,1,'2024-03-30 05:35:25','2024-03-30 05:35:25'),(23,2,2,1,'2024-03-30 05:35:25','2024-03-30 05:35:25'),(24,2,8,1,'2024-03-30 05:35:25','2024-03-30 05:35:25'),(25,10,8,1,'2024-04-01 12:49:38','2024-04-01 12:49:38');
/*!40000 ALTER TABLE `trn_teacher_classes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `phone_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `role` int NOT NULL DEFAULT '1' COMMENT '1:Super Admin, 2:Principal, 3:Teacher',
  `status` int NOT NULL DEFAULT '1' COMMENT '1:Active, 0:Inactive',
  `created_by` bigint unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Super Admin','pandurang@bizmo-tech.com','2024-02-04 04:27:28','$2y$10$pmYhx56vzIo1EWwosDVG/Oq0XpCCyU4KEh52ho4Z8APDbCpgg2pX6',NULL,'2024-02-04 04:27:28','2024-02-04 04:27:28','9011095147','Bizmo Technologies',1,1,1),(2,'Aishwarya Rathod','aishwarya@bizmo-tech.in',NULL,'$2y$12$cI5pzQW/wsKR8dT/0jgLfe4aZOxRUNUsrlcYQR.U0FKgRQqCOMbBO',NULL,'2024-03-08 06:47:39','2024-03-19 11:42:01','1234567890','Navle Bridge  Pune Maharashtra',3,1,1),(3,'Sachin Darkunde','sachin@bizmo-tech.in',NULL,'$2y$12$gSLhhqVmurtOXpFcOW83JeaUXBvowGKTHBYTw2NLYrDSL9m8RX1Da',NULL,'2024-03-08 09:31:31','2024-03-08 10:20:46','1123456789','Pune Maharashtra',3,1,1),(4,'Ram D Bhosale','04_rambhosale2010@gmail.com',NULL,'$2y$12$MJigovTZUEvwr8I3sgfbB.ZW.6Vzj03oP0BE2ZiDPzDKNHjqtfT1m',NULL,'2024-03-08 10:13:14','2024-03-11 07:06:46','5666686655','Warje pune, Maharashtra',2,2,1),(5,'Ganesh Ramchandra Patil','ganeshd1208@gmail.com',NULL,'$2y$12$eM1RbZ38Y9qhsOZUQRN5m.pNUPS4fdKiH1Xj2.Hw01tPzIM.Jctmu',NULL,'2024-03-08 11:24:17','2024-03-18 04:43:36','0127-444444444','Sangli , Maharashtra',3,1,1),(6,'Ram Bhosale 1','06_rathodbaishwarya07@gmail.com',NULL,'$2y$12$KqdtB7PGho8iDBn1IV2gju32kJeSQn8ksnaaAhy6fdAxORwsuA8D2',NULL,'2024-03-11 06:13:19','2024-03-11 06:41:09','0127- 2122345','Solapur , Maharashtra, 413004',3,2,1),(7,'Ram Bhosale','rathodbaishwarya07@gmail.com',NULL,'$2y$12$XpCef284ReVSeP/zYyBfWuiLwCGJfh4DifiNu40nYvRlRuu4.Wvzu',NULL,'2024-03-11 06:44:43','2024-03-11 06:44:43','0127-2122345','SOLAPUR',3,1,1),(8,'Ram Dasharath Bhosale','rambhosale2010@gmail.com',NULL,'$2y$12$zFg9fOnxWOUxKz6cR37V.OEb2vo9t2r5q4FBkQWGXf2kvYDWiDIWu',NULL,'2024-03-11 07:09:20','2024-03-11 07:09:20','5666686655','Warje pune, Maharashtra',2,1,1),(9,'Swaraj Baikar','baikarps@gmail.com',NULL,'$2y$12$sCRsGmg3Om8eUhshpNV0ZOpQZ6mK8wRXF7c.Z6RFs4dyN9oAYS2Om',NULL,'2024-03-19 11:27:10','2024-03-19 11:27:10','09011095147','Navale Ind. Estate. Narhe',3,1,1),(10,'Abirami.T','oppilal20@gmail.com',NULL,'$2y$12$gl44EoZbTMJNnCVqLN6QTuAh426e9ex3KRBgIHcFd3A58cWlv8hEu',NULL,'2024-04-01 12:49:38','2024-04-01 12:49:38','7397749197','Kochadai,madurai.',3,1,1),(11,'Oppilal','newschoolmdu@gmail.com',NULL,'$2y$12$cN61ZkdaFXSq3AoH9QyvweAbzW9AB.s3ceUEi2MnQvMzKJSbz8hIK',NULL,'2024-04-01 12:50:29','2024-04-01 12:50:29',NULL,NULL,2,1,1);
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

-- Dump completed on 2024-04-08  8:57:04
