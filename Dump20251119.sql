-- MySQL dump 10.13  Distrib 8.0.31, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: 3cshop
-- ------------------------------------------------------
-- Server version	8.0.31

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
-- Table structure for table `abouts`
--

DROP TABLE IF EXISTS `abouts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `abouts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `abouts`
--

LOCK TABLES `abouts` WRITE;
/*!40000 ALTER TABLE `abouts` DISABLE KEYS */;
INSERT INTO `abouts` VALUES (1,'title2333','content22','about/ADIanJXuHXRe84w3rAZDOIl5Gd6lUld1D0v8nvN1.jpg',NULL,'2025-10-29 01:58:15');
/*!40000 ALTER TABLE `abouts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cart_items`
--

DROP TABLE IF EXISTS `cart_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cart_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cart_id` bigint unsigned NOT NULL,
  `product_option_id` bigint unsigned NOT NULL,
  `qty` int unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cart_items_cart_id_product_option_id_unique` (`cart_id`,`product_option_id`),
  KEY `cart_items_product_option_id_foreign` (`product_option_id`),
  CONSTRAINT `cart_items_cart_id_foreign` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cart_items_product_option_id_foreign` FOREIGN KEY (`product_option_id`) REFERENCES `product_options` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart_items`
--

LOCK TABLES `cart_items` WRITE;
/*!40000 ALTER TABLE `cart_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `cart_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `carts`
--

DROP TABLE IF EXISTS `carts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `carts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carts`
--

LOCK TABLES `carts` WRITE;
/*!40000 ALTER TABLE `carts` DISABLE KEYS */;
/*!40000 ALTER TABLE `carts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` int unsigned NOT NULL DEFAULT '0',
  `is_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_name_unique` (`name`),
  KEY `categories_is_enabled_index` (`is_enabled`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'slfjhdslfkh',1,0,'2025-11-02 22:40:48','2025-11-03 00:35:23'),(2,'fugit aut',2,1,'2025-11-02 22:40:48','2025-11-02 22:40:48'),(4,'55688',4,1,'2025-11-03 00:22:55','2025-11-03 00:22:55'),(8,'yyuyuyuy',5,1,'2025-11-03 01:28:16','2025-11-03 01:28:32'),(9,'jjjttjtjtj',6,1,'2025-11-03 01:28:50','2025-11-03 01:28:56'),(10,'wwwrrrrr',7,1,'2025-11-03 01:36:39','2025-11-03 01:36:44'),(12,'unde at',1,1,'2025-11-03 20:01:28','2025-11-03 20:01:28'),(13,'voluptatibus dolor',2,1,'2025-11-03 20:01:28','2025-11-03 20:01:28'),(14,'tempora voluptatum',3,1,'2025-11-03 20:01:28','2025-11-03 20:01:28');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
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
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_reset_tokens_table',1),(3,'2019_08_19_000000_create_failed_jobs_table',1),(4,'2019_12_14_000001_create_personal_access_tokens_table',1),(5,'2025_10_21_063554_add_contact_fields_to_users_table',1),(6,'2025_10_21_064812_create_social_accounts_table',1),(7,'2025_10_21_074430_create_categories_table',1),(8,'2025_10_21_075515_create_subcategories_table',1),(9,'2025_10_21_080629_create_products_table',1),(10,'2025_10_21_091400_create_product_options_table',1),(11,'2025_10_21_093025_create_product_images_table',1),(12,'2025_10_21_093708_create_carts_table',1),(13,'2025_10_21_093734_create_cart_items_table',1),(14,'2025_10_21_095630_create_orders_table',1),(15,'2025_10_21_100518_create_order_items_table',1),(16,'2025_10_22_084611_create_abouts_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `product_option_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `option_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` int unsigned NOT NULL,
  `qty` int unsigned NOT NULL DEFAULT '1',
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_product_option_id_foreign` (`product_option_id`),
  KEY `order_items_order_id_index` (`order_id`),
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_product_option_id_foreign` FOREIGN KEY (`product_option_id`) REFERENCES `product_options` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_order_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_status` tinyint unsigned NOT NULL DEFAULT '0',
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` int unsigned NOT NULL,
  `recipient_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `recipient_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `note` text COLLATE utf8mb4_unicode_ci COMMENT '訂單備註',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_number_unique` (`order_number`),
  UNIQUE KEY `orders_payment_order_number_unique` (`payment_order_number`),
  UNIQUE KEY `orders_payment_token_unique` (`payment_token`),
  KEY `orders_user_id_order_status_created_at_index` (`user_id`,`order_status`,`created_at`),
  KEY `orders_order_status_index` (`order_status`),
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
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
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
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
-- Table structure for table `product_images`
--

DROP TABLE IF EXISTS `product_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_images` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT '0',
  `alt_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_images_product_id_is_primary_sort_order_index` (`product_id`,`is_primary`,`sort_order`),
  KEY `product_images_is_primary_index` (`is_primary`),
  CONSTRAINT `product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=92 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_images`
--

LOCK TABLES `product_images` WRITE;
/*!40000 ALTER TABLE `product_images` DISABLE KEYS */;
INSERT INTO `product_images` VALUES (83,5,'products/5/KD7ij9DCuMCk9uSpxg86xA7DomPpDqG4ZdbodAZv.jpg',1,'504729699_17876079372379360_81035208702985695_n.jpg',1,'2025-11-16 18:16:00','2025-11-18 21:20:58'),(84,5,'products/5/eQYL9wlrvOLKUnR9cLhC993WptgOxNG3uHEdgV7S.jpg',0,'521585088_17876079363379360_9178088187148262917_n.jpg',4,'2025-11-16 18:16:00','2025-11-18 21:20:58'),(85,5,'products/5/QFzZfJYKdH0idF6OZESspEG2VZXwFgG6u7KrjQPD.jpg',0,'523108224_17876079354379360_4358054233925040787_n.jpg',2,'2025-11-16 18:16:00','2025-11-18 21:20:58'),(86,5,'products/5/asYsb5hKdpSWFg82kjTQw0BmlDGJmsooc74JGavo.webp',0,'p689231734.webp',3,'2025-11-16 18:16:00','2025-11-18 21:20:58');
/*!40000 ALTER TABLE `product_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_options`
--

DROP TABLE IF EXISTS `product_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_options` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `option_text` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `original_price` int unsigned NOT NULL,
  `price` int unsigned NOT NULL,
  `inventory` int unsigned NOT NULL DEFAULT '0',
  `sort_order` int unsigned NOT NULL DEFAULT '0',
  `is_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_options_product_id_option_text_unique` (`product_id`,`option_text`),
  KEY `product_options_product_id_is_enabled_sort_order_index` (`product_id`,`is_enabled`,`sort_order`),
  KEY `product_options_is_enabled_index` (`is_enabled`),
  CONSTRAINT `product_options_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_options`
--

LOCK TABLES `product_options` WRITE;
/*!40000 ALTER TABLE `product_options` DISABLE KEYS */;
INSERT INTO `product_options` VALUES (1,5,'praesentium libero',1773,859,51,40,1,'2025-11-03 20:03:11','2025-11-13 00:20:25'),(4,6,'consequuntur sequi',8954,3502,44,1,1,'2025-11-03 22:21:49','2025-11-03 22:21:49'),(5,6,'est reprehenderit',3776,1043,8,2,1,'2025-11-03 22:21:49','2025-11-03 22:21:49'),(6,6,'dolorem ipsum',9658,2405,37,3,1,'2025-11-03 22:21:49','2025-11-03 22:21:49'),(7,7,'eveniet sit',4529,3849,19,1,0,'2025-11-03 22:21:49','2025-11-03 22:21:49'),(8,7,'et dolor',1457,806,21,2,1,'2025-11-03 22:21:49','2025-11-03 22:21:49'),(9,7,'totam architecto',6956,1134,49,3,1,'2025-11-03 22:21:49','2025-11-03 22:21:49'),(10,8,'sit enim',5944,302,49,1,1,'2025-11-04 18:36:13','2025-11-04 18:36:13'),(11,8,'provident illo',1573,419,20,2,1,'2025-11-04 18:36:13','2025-11-04 18:36:13'),(12,8,'rerum esse',9590,3866,43,3,1,'2025-11-04 18:36:13','2025-11-04 18:36:13'),(13,9,'enim voluptatem',8305,2284,30,1,0,'2025-11-04 18:36:13','2025-11-04 18:36:13'),(14,9,'distinctio temporibus',9753,606,48,2,1,'2025-11-04 18:36:13','2025-11-04 18:36:13'),(15,9,'odio voluptatibus',6789,4077,33,3,1,'2025-11-04 18:36:13','2025-11-04 18:36:13'),(16,10,'sit quas',7041,3974,44,1,1,'2025-11-04 18:36:13','2025-11-04 18:36:13'),(17,10,'vel quidem',3693,3271,37,2,1,'2025-11-04 18:36:13','2025-11-04 18:36:13'),(18,10,'et repudiandae',9149,5229,22,3,1,'2025-11-04 18:36:13','2025-11-04 18:36:13'),(19,11,'neque minima',4450,2976,24,1,1,'2025-11-04 18:36:13','2025-11-04 18:36:13'),(20,11,'enim nobis',4263,3443,2,2,1,'2025-11-04 18:36:13','2025-11-04 18:36:13'),(21,11,'corrupti quisquam',2855,1620,22,3,1,'2025-11-04 18:36:13','2025-11-04 18:36:13'),(22,12,'ea quidem',6785,3219,42,1,1,'2025-11-04 18:36:13','2025-11-04 18:36:13'),(23,12,'in harum',9416,353,38,2,1,'2025-11-04 18:36:13','2025-11-04 18:36:13'),(24,12,'impedit culpa',1422,1019,7,3,1,'2025-11-04 18:36:13','2025-11-04 18:36:13'),(25,13,'eos aliquam',9508,9490,42,1,1,'2025-11-04 18:36:13','2025-11-04 18:36:13'),(26,13,'veniam repellat',7316,1158,35,2,1,'2025-11-04 18:36:13','2025-11-04 18:36:13'),(27,13,'quia perferendis',781,397,8,3,1,'2025-11-04 18:36:13','2025-11-04 18:36:13'),(28,14,'labore et',8985,6184,31,1,1,'2025-11-04 18:36:13','2025-11-04 18:36:13'),(29,14,'est praesentium',4386,2683,19,2,1,'2025-11-04 18:36:13','2025-11-04 18:36:13'),(30,14,'non voluptatem',9853,4610,16,3,1,'2025-11-04 18:36:13','2025-11-04 18:36:13'),(41,5,'eeeeww',33,333,3333,0,0,'2025-11-11 23:52:29','2025-11-13 00:20:25'),(46,5,'wwwww',33,44,2,42,0,'2025-11-12 22:42:42','2025-11-13 00:20:25'),(50,5,'hello sdf',2323,2322,215,43,0,'2025-11-12 23:58:40','2025-11-13 00:20:08'),(52,5,'65465',544,5465,0,44,1,'2025-11-18 00:52:23','2025-11-18 00:57:42');
/*!40000 ALTER TABLE `product_options` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `subcategory_id` bigint unsigned NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` int unsigned DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_slug_unique` (`slug`),
  KEY `products_subcategory_id_is_enabled_index` (`subcategory_id`,`is_enabled`),
  KEY `products_is_enabled_index` (`is_enabled`),
  CONSTRAINT `products_subcategory_id_foreign` FOREIGN KEY (`subcategory_id`) REFERENCES `subcategories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (5,31,'werer','fgfdg',8780,'Maiores ut quaMaiores ut quas consequuntur aut numquam fugit consequuntur aMaiores ut quas sdsad',0,'2025-11-03 20:03:11','2025-11-18 21:20:26'),(6,31,'quam-quis','quam quis',5490,'Sed dicta et architecto ut et nemo sequi soluta totam aut.',0,'2025-11-03 22:21:49','2025-11-16 23:18:26'),(7,31,'rerum-eligendi','rerum eligendi',1548,'Magni sit necessitatibus ea tempore reprehenderit et occaecati eos sed.',1,'2025-11-03 22:21:49','2025-11-03 22:21:49'),(8,31,'explicabo-nostrum','explicabo nostrum',7808,'Quas odit modi atque ex voluptate et sapiente et voluptatibus quaerat.',1,'2025-11-04 18:36:13','2025-11-04 18:36:13'),(9,31,'nihil-facilis','nihil facilis',8174,'Qui eius nam sed voluptas qui at nihil consequatur eligendi et enim voluptatem.',1,'2025-11-04 18:36:13','2025-11-04 18:36:13'),(10,31,'non-deleniti','non deleniti',1292,'Sed cupiditate et est magnam eos voluptatibus qui quia qui blanditiis est.',1,'2025-11-04 18:36:13','2025-11-04 18:36:13'),(11,31,'non-sed','non sed',4193,'Quae velit tempora consequatur provident sed unde qui repellat numquam commodi inventore quo illum in voluptatibus voluptatem.',1,'2025-11-04 18:36:13','2025-11-04 18:36:13'),(12,31,'et-autem','et autem',4485,'Et nemo cupiditate neque doloremque non iure odio.',1,'2025-11-04 18:36:13','2025-11-04 18:36:13'),(13,31,'dignissimos-magnam','dignissimos magnam',7438,'Velit fuga mollitia quas incidunt similique eaque officiis iste laboriosam sint enim assumenda dolor eum.',1,'2025-11-04 18:36:13','2025-11-16 23:26:14'),(14,31,'sed-rerum','sed rerum',6693,'Molestias quia ut eveniet rem sed minima sit et deserunt velit suscipit sit accusantium esse sint voluptatum.',0,'2025-11-04 18:36:13','2025-11-16 23:25:46'),(15,31,'voluptatem-delectus','voluptatem delectus',7814,'Dolores labore aut tempore aperiam ut beatae autem nihil.',1,'2025-11-04 18:36:13','2025-11-04 18:36:13'),(16,31,'earum-nihil','earum nihil',215,'Eos dolor quis dolor placeat ullam tempora non aut quidem.',1,'2025-11-04 18:36:13','2025-11-04 18:36:13'),(18,4,'sdf','sdfd',333,'sdfdsf',1,'2025-11-17 02:28:31','2025-11-17 02:28:31'),(19,4,'sdfds','sdfdf',222,'dsfsdf',1,'2025-11-17 02:35:02','2025-11-17 02:35:02'),(20,4,'32','sdfsdf',334,'sdfsdff',1,'2025-11-17 02:35:27','2025-11-17 02:35:27');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `social_accounts`
--

DROP TABLE IF EXISTS `social_accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `social_accounts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `provider` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `social_accounts_provider_provider_id_unique` (`provider`,`provider_id`),
  KEY `social_accounts_user_id_foreign` (`user_id`),
  CONSTRAINT `social_accounts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `social_accounts`
--

LOCK TABLES `social_accounts` WRITE;
/*!40000 ALTER TABLE `social_accounts` DISABLE KEYS */;
/*!40000 ALTER TABLE `social_accounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subcategories`
--

DROP TABLE IF EXISTS `subcategories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `subcategories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort_order` int unsigned NOT NULL DEFAULT '0',
  `is_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `subcategories_category_id_name_unique` (`category_id`,`name`),
  KEY `subcategories_category_id_is_enabled_sort_order_index` (`category_id`,`is_enabled`,`sort_order`),
  KEY `subcategories_is_enabled_index` (`is_enabled`),
  CONSTRAINT `subcategories_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subcategories`
--

LOCK TABLES `subcategories` WRITE;
/*!40000 ALTER TABLE `subcategories` DISABLE KEYS */;
INSERT INTO `subcategories` VALUES (1,1,'kuhkuhkhkuj',2,1,'2025-11-02 22:40:48','2025-11-18 02:34:57'),(2,1,'qui non',3,1,'2025-11-02 22:40:48','2025-11-18 02:34:57'),(3,1,'aspernatur',1,0,'2025-11-02 22:40:48','2025-11-18 02:34:57'),(4,2,'eligendi quo',1,1,'2025-11-02 22:40:48','2025-11-02 22:40:48'),(5,2,'inventore ducimus',2,1,'2025-11-02 22:40:48','2025-11-02 22:40:48'),(10,8,'bbbdfgdfg',4,0,'2025-11-03 01:28:22','2025-11-03 01:28:42'),(11,8,'jjjjjj',5,1,'2025-11-03 01:28:26','2025-11-03 01:28:26'),(12,9,'yyyy',6,1,'2025-11-03 01:29:00','2025-11-03 01:29:31'),(13,9,'gfdgdfgfggf',7,1,'2025-11-03 01:33:01','2025-11-03 01:33:01'),(14,9,'dsdsfsfsdf',8,1,'2025-11-03 01:33:30','2025-11-03 01:33:30'),(15,10,'rrrwww',4,1,'2025-11-03 01:36:52','2025-11-18 02:35:03'),(16,10,'ererererer',5,1,'2025-11-03 01:39:02','2025-11-18 02:35:03'),(17,10,'bbb',1,1,'2025-11-03 01:43:17','2025-11-18 02:35:03'),(20,10,'hhhhggg',2,1,'2025-11-03 01:46:25','2025-11-18 02:35:03'),(22,10,'asdsd',3,0,'2025-11-03 01:47:46','2025-11-18 02:35:03'),(23,12,'iusto aut',3,1,'2025-11-03 20:01:28','2025-11-18 02:34:59'),(24,12,'ratione consequuntur',2,1,'2025-11-03 20:01:28','2025-11-18 02:34:59'),(25,12,'assumenda dolorem',1,1,'2025-11-03 20:01:28','2025-11-18 02:34:59'),(26,13,'dolores architecto',1,1,'2025-11-03 20:01:28','2025-11-17 22:41:38'),(27,13,'reiciendis excepturi',2,1,'2025-11-03 20:01:28','2025-11-17 22:41:38'),(28,13,'soluta nostrum',3,1,'2025-11-03 20:01:28','2025-11-17 22:41:38'),(29,14,'voluptas dolor',1,1,'2025-11-03 20:01:28','2025-11-18 02:35:25'),(30,14,'explicabo quibusdam',2,1,'2025-11-03 20:01:28','2025-11-18 02:35:25'),(31,14,'ipsa iure',3,1,'2025-11-03 20:01:28','2025-11-18 02:35:25');
/*!40000 ALTER TABLE `subcategories` ENABLE KEYS */;
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
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_phone_unique` (`phone`),
  KEY `users_is_admin_index` (`is_admin`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'a54321580',0,'a5432158086@gmail.com',NULL,'$2y$12$7o2PBT2ZF8fgP4D9faNGruM9GunPNJUWfvPuJVMouIXDGX/BWtzmW',NULL,NULL,NULL,'ntDquqLY18XsHl4n4dJTNquMNUyGNuemWZRYfa0yna4L522HVU4D9eZzxAkQ','2025-10-27 19:44:02','2025-10-27 19:44:02');
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

-- Dump completed on 2025-11-19 15:53:45
