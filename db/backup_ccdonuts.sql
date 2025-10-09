-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: ccdonuts
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `ccdonuts`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `ccdonuts` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `ccdonuts`;

--
-- Table structure for table `creditcards`
--

DROP TABLE IF EXISTS `creditcards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `creditcards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `valid_name` varchar(100) NOT NULL,
  `card_number` varchar(25) NOT NULL,
  `card_brand` varchar(30) NOT NULL,
  `valid_month` tinyint(2) NOT NULL,
  `valid_year` smallint(4) NOT NULL,
  `security_code` varchar(4) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_creditcards_number_customer` (`customer_id`,`card_number`),
  KEY `idx_creditcards_customer` (`customer_id`),
  CONSTRAINT `fk_creditcards_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `creditcards`
--

LOCK TABLES `creditcards` WRITE;
/*!40000 ALTER TABLE `creditcards` DISABLE KEYS */;
/*!40000 ALTER TABLE `creditcards` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `furigana` varchar(100) NOT NULL,
  `postcode_a` char(3) NOT NULL,
  `postcode_b` char(4) NOT NULL,
  `address` varchar(200) NOT NULL,
  `mail` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mail` (`mail`)
) ENGINE=InnoDB AUTO_INCREMENT=1004 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customers`
--

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
INSERT INTO `customers` VALUES (1002,'茸筍','キノコタケノコ','123','4567','山','kinoko_takenoko@yama.com','$2y$10$HhAz0ioB9TkPnOqHgcmxZeiLAWQajt7gU1yLtrMt6QD0mhkw9Stuq'),(1003,'テスト1','テストイチ','123','4567','山','test1@yama.com','$2y$10$rg1mBMieuJGvXbMF4aU8eOaVd60LizwRtcGT9FOx3xE/PpTCfyxCy');
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `favorites`
--

DROP TABLE IF EXISTS `favorites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `favorites` (
  `customerId` int(11) NOT NULL,
  `productId` int(11) NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`customerId`,`productId`),
  KEY `idx_favorites_product` (`productId`),
  CONSTRAINT `fk_favorites_customer` FOREIGN KEY (`customerId`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_favorites_product` FOREIGN KEY (`productId`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `favorites`
--

LOCK TABLES `favorites` WRITE;
/*!40000 ALTER TABLE `favorites` DISABLE KEYS */;
INSERT INTO `favorites` VALUES (1003,1,'2025-10-09 14:18:27'),(1003,8,'2025-10-09 09:36:16');
/*!40000 ALTER TABLE `favorites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `purchaseId` int(11) NOT NULL,
  `creditcardId` int(11) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('authorized','captured','failed','cancelled','refunded') NOT NULL DEFAULT 'authorized',
  `paidAt` datetime DEFAULT NULL,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_payments_purchase` (`purchaseId`),
  KEY `idx_payments_creditcard` (`creditcardId`),
  CONSTRAINT `fk_payments_creditcard` FOREIGN KEY (`creditcardId`) REFERENCES `creditcards` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_payments_purchase` FOREIGN KEY (`purchaseId`) REFERENCES `purchases` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `price` int(11) NOT NULL,
  `introduction` varchar(1000) NOT NULL,
  `image` varchar(100) DEFAULT NULL,
  `isNew` int(11) NOT NULL,
  `isSet` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'CCドーナツ 当店オリジナル（5個入り）',1500,'当店のオリジナル商品、CCドーナツは、サクサクの食感が特徴のプレーンタイプのドーナツです。素材にこだわり、丁寧に揚げた生地は軽やかでサクッとした食感が楽しめます。一口食べれば、口の中に広がる甘くて香ばしい香りと、口どけの良い食感が感じられます。','original.jpg',0,0),(2,'チョコレートデライト（5個入り）',1600,'チョコレートデライトは、濃厚なカカオの風味となめらかな口どけが特徴です。ひとつひとつ丁寧に仕上げたひと口サイズのチョコレートは、口に入れた瞬間に広がる芳醇な香りと上品な甘さをお楽しみいただけます。','chocolateDelight.jpg',0,0),(3,'キャラメルクリーム（5個入り）',1600,'キャラメルクリームは、やさしい甘さのキャラメルと、とろけるようなクリームの味わいが楽しめるスイーツです。なめらかな口どけと香ばしい風味が広がり、ひと口ごとに心まで満たされる上品な味わいに仕上げました。','caramelCream.jpg',0,0),(4,'プレーンクラシック（5個入り）',1500,'プレーンクラシック（5個入り）は、シンプルだからこそ素材の良さと職人の技が際立つスイーツです。香ばしく焼き上げた生地はふんわり軽やかで、ひと口食べればやさしい甘さと素朴な風味が広がります。毎日でも食べたくなる、当店定番のクラシックな一品です。','plainClassic.jpg',0,0),(5,'サマーシトラス（5個入り）',1600,'サマーシトラス（5個入り）は、爽やかな香りと軽やかな甘さが楽しめる限定スイーツです。ふんわり焼き上げた生地にシトラスの風味を閉じ込め、ひと口ごとに広がる清涼感は暑い季節にぴったり。紅茶やアイスコーヒーとの相性も良く、贈り物にもおすすめの爽快な一品です。','summerCitrus.jpg',1,0),(6,'ストロベリークラッシュ（5個入り）',1800,'ストロベリークラッシュ（5個入り）は、甘酸っぱい苺の香りとジューシーな果実感が楽しめる華やかなスイーツです。ふんわり焼き上げた生地にストロベリーの風味をぎゅっと閉じ込め、ひと口ごとに広がるフレッシュな味わいが特徴の一品です。','strawberryCrush.jpg',0,0),(7,'フルーツドーナツセット（12個入り）',3500,'新鮮で豊かなフルーツをたっぷりと使用した贅沢な12個入りセットです。このセットには、季節の最高のフルーツを厳選し、ドーナツに取り入れました。口に入れた瞬間にフルーツの風味と生地のハーモニーが広がります。色鮮やかな見た目も魅力の一つです。','fruitDonutAssortment.jpg',0,1),(8,'フルーツドーナツセット（14個入り）',4000,'フルーツドーナツセット（14個入り）は、爽やかな柑橘や甘酸っぱい苺など、多彩なフルーツフレーバーをたっぷり楽しめるボリューム満点のセットです。人数の多い集まりや特別なシーンにもぴったり。華やかで満足感のあるひと箱が、食卓をより楽しく彩ります。','fruitDonutSet.jpg',0,1),(9,'ベストセレクションボックス（4個入り）',1200,'当店おすすめの人気フレーバーを詰め合わせたベストセレクションボックス（4個入り）は、少量ながらこだわりの味を堪能できる特別なセットです。丁寧に仕上げたドーナツは、贈り物や自分へのご褒美にもぴったり。コンパクトでも満足感のある自慢のセレクションです。','bestSelectionBox.jpg',0,1),(10,'チョコクラッシュボックス（7個入り）',2400,'濃厚なチョコレートの風味をたっぷり楽しめるチョコクラッシュボックス（7個入り）は、食べ応えのある満足セットです。外はサクッと、中はふんわり仕上げたドーナツは、家族や友人とのシェアやギフトにも最適。チョコ好きに贈る特別なひと箱です。','chocolateCrushBox.jpg',0,1),(11,'クリームボックス（4個入り）',1400,'なめらかなクリームの甘さが楽しめるクリームボックス（4個入り）は、気軽に味わえる少量セットです。ふんわり生地と濃厚クリームの絶妙なバランスは、ティータイムやちょっとしたギフトにもぴったり。コンパクトでも満足感のある一品です。','creamBox4pcs.jpg',0,1),(12,'クリームボックス（9個入り）',2800,'クリームボックス（9個入り）は、なめらかなクリームの濃厚な味わいをたっぷり楽しめるボリュームセットです。ふんわり軽い生地とコク深いクリームが、ひと口ごとに広がる贅沢なひとときを演出します。大切な方へのギフトにも喜ばれる存在感のあるひと箱です。','creamBox9pcs.jpg',0,1);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchase_details`
--

DROP TABLE IF EXISTS `purchase_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `purchase_details` (
  `purchaseId` int(11) NOT NULL,
  `productId` int(11) NOT NULL,
  `purchaseCount` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`purchaseId`,`productId`),
  KEY `idx_purchase_details_product` (`productId`),
  CONSTRAINT `fk_pdetail_product` FOREIGN KEY (`productId`) REFERENCES `products` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_pdetail_purchase` FOREIGN KEY (`purchaseId`) REFERENCES `purchases` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchase_details`
--

LOCK TABLES `purchase_details` WRITE;
/*!40000 ALTER TABLE `purchase_details` DISABLE KEYS */;
INSERT INTO `purchase_details` VALUES (1,2,5),(1,6,4),(2,12,6);
/*!40000 ALTER TABLE `purchase_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchases`
--

DROP TABLE IF EXISTS `purchases`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `purchases` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customerId` int(11) NOT NULL,
  `purchaseDate` datetime NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','paid','shipped','cancelled','refunded') NOT NULL DEFAULT 'pending',
  `totalAmount` int(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_purchases_customer_date` (`customerId`,`purchaseDate`),
  CONSTRAINT `fk_purchases_customer` FOREIGN KEY (`customerId`) REFERENCES `customers` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchases`
--

LOCK TABLES `purchases` WRITE;
/*!40000 ALTER TABLE `purchases` DISABLE KEYS */;
INSERT INTO `purchases` VALUES (1,1003,'2025-10-09 14:36:10','pending',15200),(2,1003,'2025-10-09 14:58:17','pending',16800);
/*!40000 ALTER TABLE `purchases` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'ccdonuts'
--

--
-- Dumping routines for database 'ccdonuts'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-10-09 15:29:26
