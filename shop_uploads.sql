-- MySQL dump 10.13  Distrib 5.7.27, for Linux (x86_64)
--
-- Host: localhost    Database: la_moses
-- ------------------------------------------------------
-- Server version	5.7.27-0ubuntu0.18.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `shop_uploads`
--

DROP TABLE IF EXISTS `shop_uploads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_uploads` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `creater_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '创建用户ID',
  `shop_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '店铺ID',
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文件URL',
  `file_size` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小',
  `origin_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文件名',
  `mime_type` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '文件类型',
  `is_image` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否是图片',
  `file_width` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '图片宽度',
  `file_height` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '图片高度',
  `attach_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '附件类型',
  `attachable_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '所属实例类型',
  `attachable_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '创建用户ID',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sid-atype-obj` (`shop_id`,`attach_type`,`attachable_type`,`attachable_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_uploads`
--

LOCK TABLES `shop_uploads` WRITE;
/*!40000 ALTER TABLE `shop_uploads` DISABLE KEYS */;
INSERT INTO `shop_uploads` VALUES (1,0,1,'/uploads/attachemnts/default/202001/06/1578304160_vzcw1V7Pow.jpg',110448,'avatar.jpg','image/jpeg',1,1200,1200,'test','shops',1,'2020-01-06 17:49:20','2020-01-07 10:20:16'),(2,0,1,'/uploads/attachemnts/default/202001/06/1578305988_xaPRCRUni2.jpg',110448,'avatar.jpg','image/jpeg',1,1200,1200,'test','shops',1,'2020-01-06 18:19:49','2020-01-07 10:20:16'),(3,0,1,'/uploads/attachemnts/default/202001/06/1578306015_znAPkSDA9Z.jpg',110448,'avatar.jpg','image/jpeg',1,1200,1200,'demo','',0,'2020-01-06 18:20:16','2020-01-06 18:20:16'),(4,0,1,'/uploads/attachemnts/default/202001/06/1578306151_yU7dmhUyrJ.jpg',110448,'avatar.jpg','image/jpeg',1,1200,1200,'demo','',0,'2020-01-06 18:22:31','2020-01-06 18:22:31'),(5,0,1,'/uploads/attachemnts/default/202001/06/1578306167_q2fvTOOFPx.jpg',110448,'avatar.jpg','image/jpeg',1,1200,1200,'demo','',0,'2020-01-06 18:22:47','2020-01-06 18:22:47');
/*!40000 ALTER TABLE `shop_uploads` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-01-07  2:43:52
