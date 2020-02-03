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
-- Table structure for table `expresses`
--

DROP TABLE IF EXISTS `expresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `expresses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `editor_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '创建管理员ID',
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '名称',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '名称',
  `logo_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'Logo 图片',
  `order` int(11) NOT NULL DEFAULT '0' COMMENT '排序编号',
  `is_enabled` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否启用',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `outer_name` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '来源名称',
  `outer_key` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '来源主键',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `expresses`
--

LOCK TABLES `expresses` WRITE;
/*!40000 ALTER TABLE `expresses` DISABLE KEYS */;
INSERT INTO `expresses` VALUES (1,0,'ems','EMS','',1,1,'2020-02-03 15:57:42','2020-02-03 15:58:19',NULL,'wechat','2000'),(2,0,'jingdong','京东物流','',2,1,'2020-02-03 15:57:42','2020-02-03 15:58:19',NULL,'wechat','2017'),(3,0,'shunfeng','顺丰速运','',3,1,'2020-02-03 15:57:42','2020-02-03 15:58:19',NULL,'wechat','2011'),(4,0,'yunda','韵达','',4,1,'2020-02-03 15:57:42','2020-02-03 15:58:19',NULL,'wechat','2005'),(5,0,'dhl','DHL','',5,1,'2020-02-03 15:57:42','2020-02-03 15:58:19',NULL,'wechat','2002'),(6,0,'yuantong','圆通','',6,1,'2020-02-03 15:57:42','2020-02-03 15:58:19',NULL,'wechat','2001'),(7,0,'zhongtong','中通','',7,1,'2020-02-03 15:57:42','2020-02-03 15:58:19',NULL,'wechat','2004'),(8,0,'huitongkuaidi','百世快递','',8,1,'2020-02-03 15:57:42','2020-02-03 15:58:19',NULL,'wechat','2008'),(9,0,'debangwuliu','德邦','',9,1,'2020-02-03 15:57:42','2020-02-03 15:58:19',NULL,'wechat','2009'),(10,0,'shentong','申通快递','',10,1,'2020-02-03 15:57:42','2020-02-03 15:58:19',NULL,'wechat','2010'),(11,0,'shunxing','顺兴','',11,1,'2020-02-03 15:57:42','2020-02-03 15:58:19',NULL,'wechat','2012'),(12,0,'rufeng','如风达','',12,1,'2020-02-03 15:57:42','2020-02-03 15:58:19',NULL,'wechat','2014'),(13,0,'yousu','优速','',13,1,'2020-02-03 15:57:42','2020-02-03 15:58:19',NULL,'wechat','2015'),(14,0,'changling','畅灵','',14,1,'2020-02-03 15:57:42','2020-02-03 15:58:19',NULL,'wechat','2006'),(15,0,'zhaijisong','宅急送','',15,1,'2020-02-03 15:57:42','2020-02-03 15:58:19',NULL,'',''),(16,0,'quanfeng','全峰快递','',16,1,'2020-02-03 15:57:42','2020-02-03 15:58:19',NULL,'',''),(17,0,'tiantian','天天快递','',17,1,'2020-02-03 15:57:42','2020-02-03 15:58:19',NULL,'','');
/*!40000 ALTER TABLE `expresses` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-02-03  9:55:05
