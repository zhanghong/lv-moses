-- MySQL dump 10.13  Distrib 5.7.27, for Linux (x86_64)
--
-- Host: localhost    Database: la_moses_old
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
-- Table structure for table `shops`
--

DROP TABLE IF EXISTS `shops`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shops` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `creater_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '创建管理员ID',
  `editor_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '更新用户ID',
  `manager_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '负责用户ID',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '名称',
  `main_image_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'Logo URL',
  `store_count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '门店数量',
  `order` int(11) NOT NULL DEFAULT '0' COMMENT '排序编号',
  `is_default` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否默认店铺',
  `is_enabled` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否启用',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shops`
--

LOCK TABLES `shops` WRITE;
/*!40000 ALTER TABLE `shops` DISABLE KEYS */;
INSERT INTO `shops` VALUES (1,1,1,1,'shop nameaaa','/uploads/shop/202001/19/1579397026_DG9vAQDmZ6.jpg',0,0,1,1,'2020-01-01 12:43:56','2020-01-19 09:23:49',NULL);
/*!40000 ALTER TABLE `shops` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shop_configs`
--

DROP TABLE IF EXISTS `shop_configs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shop_configs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `editor_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '更新用户ID',
  `shop_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '店铺ID',
  `seo_keywords` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'SEO 关键词',
  `seo_description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'SEO 描述',
  `introduce` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'SEO 描述',
  `banner_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'Banner图片URL',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shop_configs`
--

LOCK TABLES `shop_configs` WRITE;
/*!40000 ALTER TABLE `shop_configs` DISABLE KEYS */;
INSERT INTO `shop_configs` VALUES (1,0,1,'','','店铺简介店铺简介店铺简介店铺简介','/uploads/shop/202001/19/1579397234_XJlZW3U75B.png','2020-01-01 12:43:56','2020-01-19 09:27:17',NULL);
/*!40000 ALTER TABLE `shop_configs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stores`
--

DROP TABLE IF EXISTS `stores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stores` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `editor_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '更新用户ID',
  `shop_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '更新用户ID',
  `agent_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '经销商ID',
  `manager_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '负责人ID',
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '名称',
  `auth_no` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '授权码',
  `main_image_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '主图片ID',
  `main_image_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '主图片URL',
  `area_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '所在区县',
  `full_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '详细地址',
  `longitude` decimal(10,7) unsigned NOT NULL DEFAULT '0.0000000' COMMENT '详细地址经度',
  `latitude` decimal(10,7) unsigned NOT NULL DEFAULT '0.0000000' COMMENT '详细地址纬度',
  `work_start_time` time NOT NULL DEFAULT '00:00:00' COMMENT '是否启用',
  `work_end_time` time NOT NULL DEFAULT '24:00:00' COMMENT '是否启用',
  `is_enabled` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否启用',
  `order` int(11) NOT NULL DEFAULT '0' COMMENT '排序编号',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `shop-id` (`shop_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stores`
--

LOCK TABLES `stores` WRITE;
/*!40000 ALTER TABLE `stores` DISABLE KEYS */;
INSERT INTO `stores` VALUES (1,0,1,1,0,'store_3','xxx01933',14,'/uploads/store/202001/18/1579329677_KlWivM6j0N.jpg',3303,'',0.0000000,0.0000000,'08:00:00','24:00:00',1,12,'2020-01-17 17:24:43','2020-02-04 16:04:04',NULL),(2,0,1,1,0,'store_1','xxx01933',0,'',3,'',0.0000000,0.0000000,'08:00:00','24:00:00',1,0,'2020-01-18 14:42:13','2020-02-04 14:43:25',NULL),(3,0,1,3,0,'2333','',57,'/uploads/store/202001/19/1579425533_UwcYpnIrm6.png',402,'',0.0000000,0.0000000,'00:00:00','24:00:00',1,10,'2020-01-19 16:36:54','2020-01-20 17:44:34',NULL),(4,0,1,6,0,'2222','',58,'/uploads/store/202001/20/1579513641_fdDk1uNCR8.png',89,'',0.0000000,0.0000000,'00:00:00','24:00:00',1,1,'2020-01-20 17:47:27','2020-01-20 17:47:27',NULL),(5,0,1,4,0,'demoara','',0,'',398,'',0.0000000,0.0000000,'00:00:00','24:00:00',1,2,'2020-02-04 16:07:05','2020-02-04 16:07:05',NULL);
/*!40000 ALTER TABLE `stores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `store_configs`
--

DROP TABLE IF EXISTS `store_configs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `store_configs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `editor_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '更新用户ID',
  `store_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '店铺ID',
  `contact_name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '联系人',
  `contact_phone` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '联系电话',
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '详细地址',
  `zip_code` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '邮编',
  `staff_count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '员工人数',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `store-id` (`store_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `store_configs`
--

LOCK TABLES `store_configs` WRITE;
/*!40000 ALTER TABLE `store_configs` DISABLE KEYS */;
INSERT INTO `store_configs` VALUES (1,0,1,'helllo','18619561234','address地址地址地址','',10,'2020-01-20 17:39:58','2020-02-04 16:00:17',NULL),(2,0,3,'User Name','12344555','Address3333','',0,'2020-01-20 17:40:51','2020-01-20 17:44:34',NULL),(3,0,4,'','','111111','',0,'2020-01-20 17:47:27','2020-01-20 17:47:27',NULL),(4,0,2,'','','工地址地址地址','',0,'2020-02-04 14:43:25','2020-02-04 14:43:25',NULL),(5,0,5,'','','地址地址地址地址地址','',0,'2020-02-04 16:07:05','2020-02-04 16:07:05',NULL);
/*!40000 ALTER TABLE `store_configs` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-02-07  8:12:51
