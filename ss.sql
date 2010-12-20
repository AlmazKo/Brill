-- MySQL dump 10.13  Distrib 5.1.49, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: brill
-- ------------------------------------------------------
-- Server version	5.1.49-1ubuntu8.1

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
-- Current Database: `brill`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `brill` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `brill`;

--
-- Table structure for table `Pages`
--

DROP TABLE IF EXISTS `Pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Pages` (
  `id` smallint(5) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `date` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Pages`
--

LOCK TABLES `Pages` WRITE;
/*!40000 ALTER TABLE `Pages` DISABLE KEYS */;
INSERT INTO `Pages` VALUES (0,'Главная страница','Контент для главной страницы','');
/*!40000 ALTER TABLE `Pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `as_Sites`
--

DROP TABLE IF EXISTS `as_Sites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `as_Sites` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `host` varchar(64) DEFAULT NULL,
  `config_status` enum('None','Yes','Edit') DEFAULT 'None',
  `date` varchar(64) DEFAULT NULL,
  `rule` blob,
  PRIMARY KEY (`id`),
  UNIQUE KEY `host_UNIQUE` (`host`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `as_Sites`
--

LOCK TABLES `as_Sites` WRITE;
/*!40000 ALTER TABLE `as_Sites` DISABLE KEYS */;
INSERT INTO `as_Sites` VALUES (1,'atrex.ru','Yes','2010-10-19 02:18:27',NULL),(3,'press-release.ru','Yes','2010-10-19 02:19:32',NULL),(28,'inthepress.ru','Yes','2010-12-06 06:14:24',NULL),(29,'cafe-chianti.ru','Yes','2010-12-08 02:14:36',NULL),(30,'nioc.mrsu.ru','Yes','2010-12-08 22:00:43',NULL);
/*!40000 ALTER TABLE `as_Sites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `as_Subscribes`
--

DROP TABLE IF EXISTS `as_Subscribes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `as_Subscribes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) unsigned NOT NULL,
  `name` varchar(64) NOT NULL,
  `form` blob NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `date_begin` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_end` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `as_Subscribes`
--

LOCK TABLES `as_Subscribes` WRITE;
/*!40000 ALTER TABLE `as_Subscribes` DISABLE KEYS */;
INSERT INTO `as_Subscribes` VALUES (8,0,'asdasdad','<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<document>\n  <field title=\"Заголовок\" type=\"text\" required=\"1\" validator=\"\" info=\"\" error=\"\" attr=\"\" name=\"title\">asdasdadsad</field>\n  <field title=\"Компания\" type=\"text\" required=\"1\" validator=\"\" info=\"\" error=\"\" name=\"company\">adad</field>\n  <field title=\"Анонс\" type=\"textarea\" required=\"1\" validator=\"\" info=\"\" error=\"\" attr=\"rows=4\" name=\"preview\">asdasdasdads</field>\n  <field title=\"Релиз\" type=\"textarea\" required=\"1\" validator=\"\" info=\"\" error=\"\" attr=\"rows=10\" name=\"release\">adasdasdasd</field>\n  <field title=\"Email\" type=\"text\" required=\"1\" validator=\"\" info=\"\" error=\"\" name=\"email\">asdad@sdfsf.ru</field>\n  <field title=\"Автор\" type=\"text\" required=\"1\" validator=\"\" info=\"\" error=\"\" name=\"name\">asdadad!</field>\n</document>\n','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00'),(9,0,'asdad','<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<document>\n  <field title=\"Заголовок\" type=\"text\" required=\"1\" validator=\"\" info=\"\" error=\"\" attr=\"\" name=\"title\">asdad</field>\n  <field title=\"Компания\" type=\"text\" required=\"1\" validator=\"\" info=\"\" error=\"\" name=\"company\">asdasd</field>\n  <field title=\"Анонс\" type=\"textarea\" required=\"1\" validator=\"\" info=\"\" error=\"\" attr=\"rows=4\" name=\"preview\">asdasd</field>\n  <field title=\"Релиз\" type=\"textarea\" required=\"1\" validator=\"\" info=\"\" error=\"\" attr=\"rows=10\" name=\"release\">asdasdasd</field>\n  <field title=\"Email\" type=\"text\" required=\"1\" validator=\"\" info=\"\" error=\"\" name=\"email\">adads</field>\n  <field title=\"Автор\" type=\"text\" required=\"1\" validator=\"\" info=\"\" error=\"\" name=\"name\">adasd</field>\n</document>\n','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00'),(10,0,'asd','<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<document>\n  <field title=\"Заголовок\" type=\"text\" required=\"1\" validator=\"\" info=\"\" error=\"\" attr=\"\" name=\"title\">xdf</field>\n  <field title=\"Компания\" type=\"text\" required=\"1\" validator=\"\" info=\"\" error=\"\" name=\"company\">sfsdf</field>\n  <field title=\"Анонс\" type=\"textarea\" required=\"1\" validator=\"\" info=\"\" error=\"\" attr=\"rows=4\" name=\"preview\">sfds</field>\n  <field title=\"Релиз\" type=\"textarea\" required=\"1\" validator=\"\" info=\"\" error=\"\" attr=\"rows=10\" name=\"release\">dfsfdsf</field>\n  <field title=\"Email\" type=\"text\" required=\"1\" validator=\"\" info=\"\" error=\"\" name=\"email\">sfsf</field>\n  <field title=\"Автор\" type=\"text\" required=\"1\" validator=\"\" info=\"\" error=\"\" name=\"name\">sfd</field>\n</document>\n','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00'),(11,0,'`x`x','<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<document>\n  <field title=\"Заголовок\" type=\"text\" required=\"1\" validator=\"\" info=\"\" error=\"\" attr=\"\" name=\"title\">`zx`z</field>\n  <field title=\"Компания\" type=\"text\" required=\"1\" validator=\"\" info=\"\" error=\"\" name=\"company\">x`xz`</field>\n  <field title=\"Анонс\" type=\"textarea\" required=\"1\" validator=\"\" info=\"\" error=\"\" attr=\"rows=4\" name=\"preview\">x`x`xz</field>\n  <field title=\"Релиз\" type=\"textarea\" required=\"1\" validator=\"\" info=\"\" error=\"\" attr=\"rows=10\" name=\"release\">`xdsfsdf</field>\n  <field title=\"Email\" type=\"text\" required=\"1\" validator=\"\" info=\"\" error=\"\" name=\"email\">fsdf</field>\n  <field title=\"Автор\" type=\"text\" required=\"1\" validator=\"\" info=\"\" error=\"\" name=\"name\">sdfs</field>\n</document>\n','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00'),(12,0,'asdad','<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<document>\n  <field title=\"Заголовок\" type=\"text\" required=\"1\" validator=\"\" info=\"\" error=\"\" attr=\"\" name=\"title\">dadad</field>\n  <field title=\"Компания\" type=\"text\" required=\"1\" validator=\"\" info=\"\" error=\"\" name=\"company\">ada</field>\n  <field title=\"Анонс\" type=\"textarea\" required=\"1\" validator=\"\" info=\"\" error=\"\" attr=\"rows=4\" name=\"preview\">asdasd</field>\n  <field title=\"Релиз\" type=\"textarea\" required=\"1\" validator=\"\" info=\"\" error=\"\" attr=\"rows=10\" name=\"release\">adsasd</field>\n  <field title=\"Email\" type=\"text\" required=\"1\" validator=\"\" info=\"\" error=\"\" name=\"email\">asdsada</field>\n  <field title=\"Автор\" type=\"text\" required=\"1\" validator=\"\" info=\"\" error=\"\" name=\"name\">sdads</field>\n</document>\n','0000-00-00 00:00:00','0000-00-00 00:00:00','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `as_Subscribes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `as_SubscribesSites`
--

DROP TABLE IF EXISTS `as_SubscribesSites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `as_SubscribesSites` (
  `subscribe_id` mediumint(8) unsigned NOT NULL,
  `site_id` smallint(5) unsigned NOT NULL,
  `status` enum('No','Ok','Busy','Error') NOT NULL DEFAULT 'No',
  `form` blob NOT NULL,
  PRIMARY KEY (`subscribe_id`,`site_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `as_SubscribesSites`
--

LOCK TABLES `as_SubscribesSites` WRITE;
/*!40000 ALTER TABLE `as_SubscribesSites` DISABLE KEYS */;
INSERT INTO `as_SubscribesSites` VALUES (8,1,'Ok',''),(8,3,'Ok',''),(8,28,'Ok',''),(9,1,'Ok',''),(9,3,'Ok',''),(9,28,'Ok',''),(10,3,'No',''),(10,28,'No',''),(10,29,'No',''),(10,30,'No',''),(11,28,'Busy','<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<document>\n  <field type=\"text\" required=\"1\" form=\"true\" name=\"title\" title=\"Заголовок\" info=\"Заголовок пресс-релиза\">`zx`z</field>\n  <field type=\"textarea\" required=\"1\" form=\"true\" name=\"release\" title=\"Текст пресс-релиза\" info=\"(не более 250 символов). В основной текст автоматически не копируется. \" attr=\"rows=12\">`xdsfsdf</field>\n  <field type=\"text\" required=\"1\" form=\"true\" name=\"company\" title=\"Компания\" info=\"\">x`xz`</field>\n  <field type=\"text\" required=\"1\" form=\"true\" name=\"contacts\" title=\"Контакты\" info=\"\"/>\n  <field type=\"captcha\" required=\"1\" form=\"true\" name=\"captcha\" title=\"Введите число, которое вы видите на картинке\" info=\"\"/>\n</document>\n'),(11,29,'No',''),(11,30,'No',''),(12,3,'No',''),(12,28,'No',''),(12,29,'No',''),(12,30,'No','');
/*!40000 ALTER TABLE `as_SubscribesSites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `au_Groups`
--

DROP TABLE IF EXISTS `au_Groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `au_Groups` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `descr` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `au_Groups`
--

LOCK TABLES `au_Groups` WRITE;
/*!40000 ALTER TABLE `au_Groups` DISABLE KEYS */;
INSERT INTO `au_Groups` VALUES (1,'Пользователь',NULL),(10,'Менеджер',NULL),(100,'Администратор',''),(101,'Разработчик',NULL);
/*!40000 ALTER TABLE `au_Groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `au_Users`
--

DROP TABLE IF EXISTS `au_Users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `au_Users` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(45) NOT NULL,
  `password` varchar(32) NOT NULL,
  `name` varchar(64) DEFAULT NULL,
  `group_id` smallint(5) unsigned NOT NULL,
  `status` enum('Active','Blocked','Deleted') DEFAULT NULL,
  `date_last` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `date_created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `au_Users`
--

LOCK TABLES `au_Users` WRITE;
/*!40000 ALTER TABLE `au_Users` DISABLE KEYS */;
INSERT INTO `au_Users` VALUES (1,'almaz','3676a865f34684bfe82bb0a7b2b45a92','Саша Суслов',100,'Active','0000-00-00 00:00:00','2010-12-14 02:54:23'),(2,'admin','admin','Админныч',0,'Active','2010-12-15 00:07:17','0000-00-00 00:00:00'),(3,'asdasc','ascasc','zxcsaa',0,NULL,'2010-12-15 20:44:32','0000-00-00 00:00:00'),(4,'asd','ads','ad',0,NULL,'2010-12-15 21:00:28','0000-00-00 00:00:00'),(5,'adsads','adsasd','asdasd',0,NULL,'2010-12-15 21:00:50','0000-00-00 00:00:00'),(6,'asd','ads','adsad',0,NULL,'2010-12-15 21:02:05','0000-00-00 00:00:00'),(7,'asdasd','adads','asdads',0,NULL,'2010-12-15 21:02:53','0000-00-00 00:00:00'),(8,'daqd','qdwq','asda',0,NULL,'2010-12-15 21:03:17','0000-00-00 00:00:00'),(9,'фвфы','фывфв','фывфв',0,'','2010-12-15 21:06:46','0000-00-00 00:00:00'),(10,'','фыв','ыфв',0,'Deleted','2010-12-15 21:07:04','0000-00-00 00:00:00'),(11,'в','цу','ыв',0,'Deleted','2010-12-15 21:07:23','0000-00-00 00:00:00'),(12,'wer','weweweew','werw',0,'Deleted','2010-12-16 06:54:44','0000-00-00 00:00:00'),(13,'asdasda','dadsasd','adasd',0,'Active','2010-12-16 07:05:29','0000-00-00 00:00:00'),(14,'asd','asdasd','zscas',0,'Active','2010-12-16 07:05:46','0000-00-00 00:00:00'),(15,'11111','b0baee9d279d34fa1dfd71aadb908c3f','2222',0,'Active','0000-00-00 00:00:00','0000-00-00 00:00:00'),(16,'asdad','c9c35cf409344312146fa7546a94d1a6','asdasd',0,'Active','2010-12-18 20:19:57','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `au_Users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `au_UsersGroups`
--

DROP TABLE IF EXISTS `au_UsersGroups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `au_UsersGroups` (
  `user_id` mediumint(8) unsigned NOT NULL,
  `group_id` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `au_UsersGroups`
--

LOCK TABLES `au_UsersGroups` WRITE;
/*!40000 ALTER TABLE `au_UsersGroups` DISABLE KEYS */;
INSERT INTO `au_UsersGroups` VALUES (1,100),(1,101),(2,100),(8,1),(8,10),(8,100),(8,101),(9,1),(9,10),(9,101),(10,1),(10,101),(11,1),(12,10),(13,100),(13,101),(14,1),(14,10),(15,1),(15,10),(16,1);
/*!40000 ALTER TABLE `au_UsersGroups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mErrors`
--

DROP TABLE IF EXISTS `mErrors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mErrors` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `class` varchar(64) NOT NULL DEFAULT '0',
  `descr` varchar(255) NOT NULL DEFAULT '',
  `date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mErrors`
--

LOCK TABLES `mErrors` WRITE;
/*!40000 ALTER TABLE `mErrors` DISABLE KEYS */;
INSERT INTO `mErrors` VALUES (1,'se_ParserYandexXml','ntcnjdfz ошибка','2010-11-28 22:51:43'),(2,'se_ParserYandexXml','ntcnjdfz ошибка','2010-11-28 22:51:44'),(3,'se_ParserYandexXml','ntcnjdfz ошибка','2010-11-28 22:51:45'),(4,'se_ParserYandexXml','ntcnjdfz ошибка','2010-11-28 22:58:06'),(5,'se_ParserYandexXml','ntcnjdfz ошибка','2010-11-28 22:58:41'),(6,'se_ParserYandexXml','Яндекс не ответил...','2010-11-28 23:14:23'),(7,'se_ParserYandexXml','Яндекс не ответил...','2010-11-28 23:15:36'),(8,'se_ParserYandexXml','Яндекс не ответил...','2010-11-28 23:17:02'),(9,'se_ParserYandexXml','Яндекс не ответил...','2010-11-28 23:17:03'),(10,'se_ParserYandexXml','Яндекс не ответил...','2010-11-28 23:51:12'),(11,'se_ParserYandexXml','Яндекс не ответил...','2010-11-28 23:51:13'),(12,'se_ParserYandexXml','Яндекс не ответил...','2010-11-28 23:53:31'),(13,'se_ParserYandexXml','Яндекс не ответил...','2010-11-28 23:53:32'),(14,'se_ParserYandexXml','Яндекс не ответил...','2010-11-28 23:54:09'),(15,'se_ParserYandexXml','Яндекс не ответил...','2010-11-28 23:54:10'),(16,'SimpleXMLElement','se_ParserYandexXml','2010-11-29 00:26:05'),(17,'SimpleXMLElement','se_ParserYandexXml','2010-11-29 00:26:35'),(18,'se_ParserYandexXml','Запрос пришёл с IP-адреса 178.177.4.17, не входящего в список разрешённых для данного пользователя','2010-11-29 00:28:44'),(19,'se_ParserYandexXml','Запрос пришёл с IP-адреса 178.177.4.17, не входящего в список разрешённых для данного пользователя','2010-11-29 00:29:29'),(20,'se_ParserYandexXml','Запрос пришёл с IP-адреса 178.177.4.17, не входящего в список разрешённых для данного пользователя','2010-11-29 00:30:10'),(21,'se_ParserYandexXml','Запрос пришёл с IP-адреса 178.177.4.17, не входящего в список разрешённых для данного пользователя','2010-11-29 00:30:27'),(22,'se_ParserYandexXml','Яндекс не ответил...','2010-12-13 05:19:54'),(23,'se_ParserYandexXml','Яндекс не ответил...','2010-12-13 05:20:01'),(24,'','','2010-12-13 05:20:02'),(25,'se_ParserYandexXml','Яндекс не ответил...','2010-12-13 05:22:04'),(26,'se_ParserYandexXml','Яндекс не ответил...','2010-12-13 05:22:10'),(27,'se_ParserYandexXml','Error: Яндекс не ответил на данный запрос','2010-12-13 05:22:11'),(28,'se_ParserYandexXml','Яндекс не ответил...','2010-12-13 05:22:36'),(29,'se_ParserYandexXml','Яндекс не ответил...','2010-12-13 05:22:37'),(30,'se_ParserYandexXml','Error: Яндекс не ответил на данный запрос','2010-12-13 05:22:38'),(31,'se_ParserYandexXml','Яндекс не ответил...','2010-12-13 05:23:54'),(32,'se_ParserYandexXml','Яндекс не ответил...','2010-12-13 05:23:55'),(33,'se_ParserYandexXml','Error: Яндекс не ответил на данный запрос','2010-12-13 05:23:56'),(34,'se_ParserYandexXml','Яндекс не ответил...','2010-12-13 05:25:01'),(35,'se_ParserYandexXml','Яндекс не ответил...','2010-12-13 05:25:02'),(36,'se_ParserYandexXml','Error: Яндекс не ответил на данный запрос','2010-12-13 05:25:03'),(37,'se_ParserYandexXml','Яндекс не ответил...','2010-12-13 05:28:08'),(38,'se_ParserYandexXml','Яндекс не ответил...','2010-12-13 05:28:09'),(39,'se_ParserYandexXml','Error: Яндекс не ответил на данный запрос','2010-12-13 05:28:10'),(40,'se_ParserYandexXml','Яндекс не ответил...','2010-12-13 06:39:11'),(41,'se_ParserYandexXml','Запрос пришёл с IP-адреса 178.177.12.169, не входящего в список разрешённых для данного пользователя','2010-12-13 06:39:33'),(42,'se_ParserYandexXml','Яндекс не ответил...','2010-12-13 18:30:44'),(43,'se_ParserYandexXml','Яндекс не ответил...','2010-12-13 18:31:05'),(44,'se_ParserYandexXml','Error: Яндекс не ответил на данный запрос','2010-12-13 18:31:26');
/*!40000 ALTER TABLE `mErrors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sep_Keywords`
--

DROP TABLE IF EXISTS `sep_Keywords`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sep_Keywords` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `region_id` smallint(5) unsigned NOT NULL,
  `yandex` enum('NoData','Busy','Сalculated','Error') NOT NULL,
  `google` enum('NoData','Busy','Сalculated','Error') NOT NULL,
  `rambler` enum('NoData','Busy','Сalculated','Error') NOT NULL,
  `set_id` mediumint(8) unsigned NOT NULL,
  `thematic_id` smallint(6) NOT NULL,
  `url_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `region_id` (`region_id`),
  KEY `status` (`yandex`),
  KEY `set_id` (`set_id`)
) ENGINE=InnoDB AUTO_INCREMENT=516 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sep_Keywords`
--

LOCK TABLES `sep_Keywords` WRITE;
/*!40000 ALTER TABLE `sep_Keywords` DISABLE KEYS */;
INSERT INTO `sep_Keywords` VALUES (1,'мебель ims',213,'Error','NoData','NoData',0,5,0),(2,'итальянские столы',213,'Error','NoData','NoData',0,5,0),(3,'столы итальянские',213,'Error','NoData','NoData',0,5,0),(4,'итальянские стенки',213,'Error','NoData','NoData',0,5,0),(5,'итальянские спальни',213,'Error','NoData','NoData',0,5,0),(6,'столы трансформеры',213,'Error','NoData','NoData',0,5,0),(7,'стулья',213,'Error','NoData','NoData',0,5,0),(8,'шезлонги',213,'Error','NoData','NoData',0,5,0),(9,'мебель для бассейна',213,'Error','NoData','NoData',0,5,0),(10,'стулья для ресторанов',213,'Error','NoData','NoData',0,5,0),(11,'складные столы',213,'Error','NoData','NoData',0,5,0),(12,'багажницы',213,'Error','NoData','NoData',0,5,0),(13,'оборудование для гостиниц',213,'Error','NoData','NoData',0,5,0),(14,'текстиль для гостиниц',213,'Error','NoData','NoData',0,5,0),(15,'текстиль для ресторанов',213,'Error','NoData','NoData',0,5,0),(16,'столы',213,'Error','NoData','NoData',0,5,0),(17,'столы и стулья',213,'Error','NoData','NoData',0,5,0),(18,'журнальные столы',213,'Сalculated','NoData','NoData',0,5,0),(19,'круглый стол',213,'Сalculated','NoData','NoData',0,5,0),(20,'мебель от производителя в Москве',213,'Сalculated','NoData','NoData',0,5,0),(21,'магазин столы и стулья',213,'Сalculated','NoData','NoData',0,5,0),(22,'столы и стулья в Москве',213,'Сalculated','NoData','NoData',0,5,0),(23,'обеденные столы и стулья',213,'Сalculated','NoData','NoData',0,5,0),(24,'кресла',213,'Error','NoData','NoData',0,5,0),(25,'стулья',213,'Error','NoData','NoData',0,5,0),(26,'купить стулья',213,'Error','NoData','NoData',0,5,0),(27,'стулья Москва',213,'Error','NoData','NoData',0,5,0),(28,'стулья для дома',213,'Error','NoData','NoData',0,5,0),(29,'мебельные магазины',213,'Error','NoData','NoData',0,5,0),(30,'купить стулья',213,'Error','NoData','NoData',0,5,0),(31,'купить столы',213,'Сalculated','NoData','NoData',0,5,0),(32,'магазин столы и стулья',213,'Error','NoData','NoData',0,5,0),(33,'стол',213,'Error','NoData','NoData',0,5,0),(34,'мебель для кафе',213,'Error','NoData','NoData',0,5,0),(35,'кухонные стулья',213,'Error','NoData','NoData',0,5,0),(36,'барный табурет',213,'Error','NoData','NoData',0,5,0),(37,'кресло',213,'Error','NoData','NoData',0,5,0),(38,'компьютерные кресла',213,'Error','NoData','NoData',0,5,0),(39,'журнальный стол',213,'Error','NoData','NoData',0,5,0),(40,'вешалка',213,'Сalculated','NoData','NoData',0,5,0),(41,'этикетки',213,'Сalculated','NoData','NoData',0,6,0),(42,'печать этикеток',213,'Сalculated','NoData','NoData',0,6,0),(43,'самоклеющаяся этикетка',213,'Сalculated','NoData','NoData',0,6,0),(44,'изготовление этикеток',213,'Сalculated','NoData','NoData',0,6,0),(45,'этикетка',213,'Error','NoData','NoData',0,6,0),(46,'наклейки',213,'Сalculated','NoData','NoData',0,6,0),(47,'рекламные наклейки',213,'NoData','NoData','NoData',0,6,0),(48,'наклейка',213,'NoData','NoData','NoData',0,6,0),(49,'голографические наклейки',213,'NoData','NoData','NoData',0,6,0),(50,'заказ наклеек',213,'NoData','NoData','NoData',0,6,0),(51,'защитные наклейки',213,'NoData','NoData','NoData',0,6,0),(52,'светоотражающие наклейки',213,'NoData','NoData','NoData',0,6,0),(53,'свадебные этикетки',213,'NoData','NoData','NoData',0,6,0),(54,'тканевые этикетки',213,'NoData','NoData','NoData',0,6,0),(55,'подарочные этикетки',213,'NoData','NoData','NoData',0,6,0),(56,'юбилей этикетка',213,'NoData','NoData','NoData',0,6,0),(57,'стикеры',213,'NoData','NoData','NoData',0,6,0),(58,'гарантийные стикеры',213,'NoData','NoData','NoData',0,6,0),(59,'производство стикеров',213,'NoData','NoData','NoData',0,6,0),(60,'шампанское этикетка',213,'NoData','NoData','NoData',0,6,0),(61,'этикетка водка',213,'NoData','NoData','NoData',0,6,0),(62,'алкогольная этикетка',213,'NoData','NoData','NoData',0,6,0),(63,'этикетка алкоголь',213,'NoData','NoData','NoData',0,6,0),(64,'полноцветная печать',213,'NoData','NoData','NoData',0,6,0),(65,'типография полного цикла москва качественная полноцветная печать',213,'NoData','NoData','NoData',0,6,0),(66,'полиграфия офсетная печать',213,'NoData','NoData','NoData',0,6,0),(67,'изготовление полиграфической продукции',213,'NoData','NoData','NoData',0,6,0),(68,'производство полиграфической продукции',213,'NoData','NoData','NoData',0,6,0),(69,'изготовление настенных календарей',213,'NoData','NoData','NoData',0,6,0),(70,'печать журналов',213,'NoData','NoData','NoData',0,6,0),(71,'изготовление печать журналов',213,'NoData','NoData','NoData',0,6,0),(72,'печать периодических журналов',213,'NoData','NoData','NoData',0,6,0),(73,'печать глянцевых журналов',213,'NoData','NoData','NoData',0,6,0),(74,'печать отчетов',213,'NoData','NoData','NoData',0,6,0),(75,'скотч москва',213,'NoData','NoData','NoData',0,7,0),(76,'скотч цена',213,'NoData','NoData','NoData',0,7,0),(77,'производство скотча',213,'NoData','NoData','NoData',0,7,0),(78,'колеса пневматические',213,'NoData','NoData','NoData',0,7,0),(79,'таль цепная цена',213,'NoData','NoData','NoData',0,7,0),(80,'стрейпинг лента',213,'NoData','NoData','NoData',0,7,0),(81,'Лента ТПЛ',213,'NoData','NoData','NoData',0,7,0),(82,'Армированный скотч',213,'NoData','NoData','NoData',0,7,0),(83,'Сантехнический скотч',213,'NoData','NoData','NoData',0,7,0),(84,'упаковочный станок',213,'NoData','NoData','NoData',0,7,0),(85,'ручной упаковочный инструмент упаковщик паллет',213,'NoData','NoData','NoData',0,7,0),(86,'поставщики хозтоваров',213,'NoData','NoData','NoData',0,7,0),(87,'хозтовары оптом',213,'NoData','NoData','NoData',0,7,0),(88,'хозяйственные товары оптом',213,'NoData','NoData','NoData',0,7,0),(89,'москва хозтовары оптом',213,'NoData','NoData','NoData',0,7,0),(90,'оптовая продажа хозтоваров',213,'NoData','NoData','NoData',0,7,0),(91,'купить хозтовары оптом',213,'NoData','NoData','NoData',0,7,0),(92,'пленка стрейч',213,'NoData','NoData','NoData',0,7,0),(93,'производство стрейч пленки',213,'NoData','NoData','NoData',0,7,0),(94,'пленка стрейч цена',213,'NoData','NoData','NoData',0,7,0),(95,'купить стрейч пленка',213,'NoData','NoData','NoData',0,7,0),(96,'продажа стрейч пленки',213,'NoData','NoData','NoData',0,7,0),(97,'упаковочная стрейч пленка',213,'NoData','NoData','NoData',0,7,0),(98,'продажа стрейч пленка',213,'NoData','NoData','NoData',0,7,0),(99,'стрейч пленка опт',213,'NoData','NoData','NoData',0,7,0),(100,'отказное письмо',213,'NoData','NoData','NoData',0,8,0),(101,'ростест',213,'NoData','NoData','NoData',0,8,0),(102,'рст',213,'NoData','NoData','NoData',0,8,0),(103,'сертификат получить',213,'NoData','NoData','NoData',0,8,0),(104,'сертификация бытовой техники',213,'NoData','NoData','NoData',0,8,0),(105,'сертификация видеотехники',213,'NoData','NoData','NoData',0,8,0),(106,'Сертификация компьютеров сертификация мобильных телефонов',213,'NoData','NoData','NoData',0,8,0),(107,'сертификация оргтехники',213,'NoData','NoData','NoData',0,8,0),(108,'сертификация посуды',213,'NoData','NoData','NoData',0,8,0),(109,'сертификация телефонов',213,'NoData','NoData','NoData',0,8,0),(110,'сертификация электрооборудования консультация по сертификации',213,'NoData','NoData','NoData',0,8,0),(111,'обучение сертификации',213,'NoData','NoData','NoData',0,8,0),(112,'обязательная сертификация продукции',213,'NoData','NoData','NoData',0,8,0),(113,'ростест москва',213,'NoData','NoData','NoData',0,8,0),(114,'сертификат гигиены',213,'NoData','NoData','NoData',0,8,0),(115,'сертификат Ростест',213,'NoData','NoData','NoData',0,8,0),(116,'сертификат рст',213,'NoData','NoData','NoData',0,8,0),(117,'сертификат связи',213,'NoData','NoData','NoData',0,8,0),(118,'сертификация одежды',213,'NoData','NoData','NoData',0,8,0),(119,'сертификация импортной продукции',213,'NoData','NoData','NoData',0,8,0),(120,'сертификация Ростест',213,'NoData','NoData','NoData',0,8,0),(121,'сертификация сотовых телефонов международные оффшоры',213,'NoData','NoData','NoData',0,8,0),(122,'создание оффшоров',213,'NoData','NoData','NoData',0,8,0),(123,'использование оффшоров',213,'NoData','NoData','NoData',0,8,0),(124,'оффшоры в европе',213,'NoData','NoData','NoData',0,8,0),(125,'страны оффшора',213,'NoData','NoData','NoData',0,8,0),(126,'выбор оффшора',213,'NoData','NoData','NoData',0,8,0),(127,'дешевый оффшор',213,'NoData','NoData','NoData',0,8,0),(128,'оффшорная зона',213,'NoData','NoData','NoData',0,8,0),(129,'куплю оффшор',213,'NoData','NoData','NoData',0,8,0),(130,'список оффшоров',213,'NoData','NoData','NoData',0,8,0),(131,'зарегистрировать оффшор лицензирование деятельности получение лицензии',213,'NoData','NoData','NoData',0,8,0),(132,'регистрация недвижимости',213,'NoData','NoData','NoData',0,8,0),(133,'юридическая фирма',213,'NoData','NoData','NoData',0,8,0),(134,'лицензирование медицинской деятельности',213,'NoData','NoData','NoData',0,8,0),(135,'регистрация прав',213,'NoData','NoData','NoData',0,8,0),(136,'регистрация прав на недвижимость',213,'NoData','NoData','NoData',0,8,0),(137,'юридическое обслуживание',213,'NoData','NoData','NoData',0,8,0),(138,'проведение банкетов',213,'NoData','NoData','NoData',0,9,0),(139,'организация банкетов',213,'NoData','NoData','NoData',0,9,0),(140,'организация корпоративных банкетов',213,'NoData','NoData','NoData',0,9,0),(141,'корпоративный банкет',213,'NoData','NoData','NoData',0,9,0),(142,'праздник банкеты',213,'NoData','NoData','NoData',0,9,0),(143,'восточный ресторан',213,'NoData','NoData','NoData',0,9,0),(144,'восточные рестораны москвы',213,'NoData','NoData','NoData',0,9,0),(145,'лучшие восточные рестораны',213,'NoData','NoData','NoData',0,9,0),(146,'узбекский ресторан',213,'NoData','NoData','NoData',0,9,0),(147,'узбекский ресторан москвы',213,'NoData','NoData','NoData',0,9,0),(148,'узбекская кухня ресторан',213,'NoData','NoData','NoData',0,9,0),(149,'ресторан узбекской кухни',213,'NoData','NoData','NoData',0,9,0),(150,'банкет москва',213,'NoData','NoData','NoData',0,9,0),(151,'заказ банкета',213,'NoData','NoData','NoData',0,9,0),(152,'заказать банкет',213,'NoData','NoData','NoData',0,9,0),(153,'заказ банкета москва',213,'NoData','NoData','NoData',0,9,0),(154,'заказать банкет в ресторане',213,'NoData','NoData','NoData',0,9,0),(155,'ресторан москва банкет',213,'NoData','NoData','NoData',0,9,0),(156,'где отметить день рождения',213,'NoData','NoData','NoData',0,9,0),(157,'где отметить день рождения москва',213,'NoData','NoData','NoData',0,9,0),(158,'отметить день рождения',213,'NoData','NoData','NoData',0,9,0),(159,'свадебный банкет',213,'NoData','NoData','NoData',0,9,0),(160,'японская кухня',213,'NoData','NoData','NoData',0,9,0),(161,'суши бар',213,'NoData','NoData','NoData',0,9,0),(162,'японская еда',213,'NoData','NoData','NoData',0,9,0),(163,'японский суши бар',213,'NoData','NoData','NoData',0,9,0),(164,'ресторан корпоратив',213,'NoData','NoData','NoData',0,9,0),(165,'осаго круглосуточно',213,'NoData','NoData','NoData',0,10,0),(166,'осаго круглосуточно москва',213,'NoData','NoData','NoData',0,10,0),(167,'гинекология москва',213,'NoData','NoData','NoData',0,11,0),(168,'лечение женского бесплодия',213,'NoData','NoData','NoData',0,11,0),(169,'женское бесплодие лечение',213,'NoData','NoData','NoData',0,11,0),(170,'школа материнства',213,'NoData','NoData','NoData',0,11,0),(171,'школа материнства москва',213,'NoData','NoData','NoData',0,11,0),(172,'лечение бесплодия у мужчин',213,'NoData','NoData','NoData',0,11,0),(173,'элитная стоматология',213,'NoData','NoData','NoData',0,11,0),(174,'лечение в германии',213,'NoData','NoData','NoData',0,11,0),(175,'диагностика в германии',213,'NoData','NoData','NoData',0,11,0),(176,'печать на футболках',213,'NoData','NoData','NoData',0,12,0),(177,'молодежная одежда',213,'NoData','NoData','NoData',0,12,0),(178,'интернет магазин молодежной одежды',213,'NoData','NoData','NoData',0,12,0),(179,'клубная одежда',213,'NoData','NoData','NoData',0,12,0),(180,'детская одежда оптом',213,'NoData','NoData','NoData',0,12,0),(181,'меховая фабрика',213,'NoData','NoData','NoData',0,12,0),(182,'шубы из бобра',213,'NoData','NoData','NoData',0,12,0),(183,'домашняя обувь',213,'NoData','NoData','NoData',0,12,0),(184,'домашние тапочки',213,'NoData','NoData','NoData',0,12,0),(185,'детская домашняя обувь',213,'NoData','NoData','NoData',0,12,0),(186,'модные ремни',213,'NoData','NoData','NoData',0,12,0),(187,'мужские ремни',213,'NoData','NoData','NoData',0,12,0),(188,'кожаные ремни',213,'NoData','NoData','NoData',0,12,0),(189,'модные мужские ремни',213,'NoData','NoData','NoData',0,12,0),(190,'мужской трикотаж',213,'NoData','NoData','NoData',0,12,0),(191,'производство трикотажа',213,'NoData','NoData','NoData',0,12,0),(192,'трикотаж на заказ',213,'NoData','NoData','NoData',0,12,0),(193,'мужской свитер',213,'NoData','NoData','NoData',0,12,0),(194,'свитер',213,'NoData','NoData','NoData',0,12,0),(195,'шерстяной свитер',213,'NoData','NoData','NoData',0,12,0),(196,'джемпер',213,'NoData','NoData','NoData',0,12,0),(197,'изделия из хлопка',213,'NoData','NoData','NoData',0,12,0),(198,'хлопковый трикотаж',213,'NoData','NoData','NoData',0,12,0),(199,'модная мужская одежда',213,'NoData','NoData','NoData',0,12,0),(200,'модный трикотаж',213,'NoData','NoData','NoData',0,12,0),(201,'изделия из меха',213,'NoData','NoData','NoData',0,12,0),(202,'каракулевая шуба',213,'NoData','NoData','NoData',0,12,0),(203,'одежда из меха',213,'NoData','NoData','NoData',0,12,0),(204,'женское полупальто',213,'NoData','NoData','NoData',0,12,0),(205,'полупальто',213,'NoData','NoData','NoData',0,12,0),(206,'полупальто мужское',213,'NoData','NoData','NoData',0,12,0),(207,'ковровая плитка',213,'NoData','NoData','NoData',0,13,0),(208,'проектирование офисов',213,'NoData','NoData','NoData',0,13,0),(209,'навесной фасад',213,'NoData','NoData','NoData',0,13,0),(210,'алюкобонд',213,'NoData','NoData','NoData',0,13,0),(211,'вентфасад',213,'NoData','NoData','NoData',0,13,0),(212,'фасадные панели',213,'NoData','NoData','NoData',0,13,0),(213,'композитные панели',213,'NoData','NoData','NoData',0,13,0),(214,'газосиликатные блоки',213,'NoData','NoData','NoData',0,13,0),(215,'кирпич шамотный',213,'NoData','NoData','NoData',0,13,0),(216,'кирпич огнеупорный',213,'NoData','NoData','NoData',0,13,0),(217,'композитные панели',213,'NoData','NoData','NoData',0,13,0),(218,'ковровые покрытия',213,'NoData','NoData','NoData',0,13,0),(219,'напольные покрытия',213,'NoData','NoData','NoData',0,13,0),(220,'спортивные покрытия',213,'NoData','NoData','NoData',0,13,0),(221,'пиломатериалы',213,'NoData','NoData','NoData',0,13,0),(222,'брус',213,'NoData','NoData','NoData',0,13,0),(223,'брус деревянный',213,'NoData','NoData','NoData',0,13,0),(224,'блок хаус',213,'NoData','NoData','NoData',0,13,0),(225,'доска половая',213,'NoData','NoData','NoData',0,13,0),(226,'половая доска',213,'NoData','NoData','NoData',0,13,0),(227,'вагонка',213,'NoData','NoData','NoData',0,13,0),(228,'евровагонка',213,'NoData','NoData','NoData',0,13,0),(229,'сухие смеси',213,'NoData','NoData','NoData',0,13,0),(230,'кладочная смесь',213,'NoData','NoData','NoData',0,13,0),(231,'сухие строительные смеси',213,'NoData','NoData','NoData',0,13,0),(232,'ДСП',213,'NoData','NoData','NoData',0,13,0),(233,'ДВП',213,'NoData','NoData','NoData',0,13,0),(234,'МДФ',213,'NoData','NoData','NoData',0,13,0),(235,'модульная плитка',213,'NoData','NoData','NoData',0,13,0),(236,'автоподъемник',213,'NoData','NoData','NoData',0,14,0),(237,'автоподъемники',213,'NoData','NoData','NoData',0,14,0),(238,'автовышки',213,'NoData','NoData','NoData',0,14,0),(239,'автогидроподъемник',213,'NoData','NoData','NoData',0,14,0),(240,'спецтехника запчасти',213,'NoData','NoData','NoData',0,14,0),(241,'лесовоз',213,'NoData','NoData','NoData',0,14,0),(242,'коники',213,'NoData','NoData','NoData',0,14,0),(243,'купить гидроцикл',213,'NoData','NoData','NoData',0,14,0),(244,'лимузины напрокат',213,'NoData','NoData','NoData',0,14,0),(245,'лимузины на свадьбу',213,'NoData','NoData','NoData',0,14,0),(246,'мотоциклы петербург',213,'NoData','NoData','NoData',0,14,0),(247,'автосервис форд',213,'NoData','NoData','NoData',0,14,0),(248,'автозапчасти форд',213,'NoData','NoData','NoData',0,14,0),(249,'автозапчасти ford',213,'NoData','NoData','NoData',0,14,0),(250,'автосервис форд',213,'NoData','NoData','NoData',0,14,0),(251,'автозапчасти форд',213,'NoData','NoData','NoData',0,14,0),(252,'гидроциклы продажа',213,'NoData','NoData','NoData',0,14,0),(253,'гидроциклы цены',213,'NoData','NoData','NoData',0,14,0),(254,'купить гидроцикл',213,'NoData','NoData','NoData',0,14,0),(255,'куплю гидроцикл',213,'NoData','NoData','NoData',0,14,0),(256,'продажа гидроциклов',213,'NoData','NoData','NoData',0,14,0),(257,'водный скутер',213,'NoData','NoData','NoData',0,14,0),(258,'аквабайк',213,'NoData','NoData','NoData',0,14,0),(259,'вейкборд купить',213,'NoData','NoData','NoData',0,14,0),(260,'водный мотоцикл',213,'NoData','NoData','NoData',0,14,0),(261,'катер',213,'NoData','NoData','NoData',0,14,0),(262,'катер цена',213,'NoData','NoData','NoData',0,14,0),(263,'катера продажа',213,'NoData','NoData','NoData',0,14,0),(264,'купить катер',213,'NoData','NoData','NoData',0,14,0),(265,'куплю катер',213,'NoData','NoData','NoData',0,14,0),(266,'запчасти для гидроциклов',213,'NoData','NoData','NoData',0,14,0),(267,'куплю квадроцикл',213,'NoData','NoData','NoData',0,14,0),(268,'магазин квадроциклов',213,'NoData','NoData','NoData',0,14,0),(269,'купить снегоход',213,'NoData','NoData','NoData',0,14,0),(270,'куплю снегоход',213,'NoData','NoData','NoData',0,14,0),(271,'продажа снегоходов',213,'NoData','NoData','NoData',0,14,0),(272,'снегоходы продажа',213,'NoData','NoData','NoData',0,14,0),(273,'запчасти mitsubishi lancer',213,'NoData','NoData','NoData',0,14,0),(274,'запчасти daewoo nexia',213,'NoData','NoData','NoData',0,14,0),(275,'запчасти peugeot',213,'NoData','NoData','NoData',0,14,0),(276,'запчасти опель вектра',213,'NoData','NoData','NoData',0,14,0),(277,'запчасти opel astra',213,'NoData','NoData','NoData',0,14,0),(278,'тюнинг лансер',213,'NoData','NoData','NoData',0,14,0),(279,'лесозаготовка',213,'NoData','NoData','NoData',0,15,0),(280,'Датчик давления',213,'NoData','NoData','NoData',0,15,0),(281,'Датчик температуры',213,'NoData','NoData','NoData',0,15,0),(282,'Датчик уровня',213,'NoData','NoData','NoData',0,15,0),(283,'Измерительные приборы',213,'NoData','NoData','NoData',0,15,0),(284,'калибраторы',213,'NoData','NoData','NoData',0,15,0),(285,'МТП',213,'NoData','NoData','NoData',0,15,0),(286,'Преобразователи давления',213,'NoData','NoData','NoData',0,15,0),(287,'Приборы',213,'NoData','NoData','NoData',0,15,0),(288,'Термопреобразователи',213,'NoData','NoData','NoData',0,15,0),(289,'Расходомеры',213,'NoData','NoData','NoData',0,15,0),(290,'Термометры',213,'NoData','NoData','NoData',0,15,0),(291,'Экстрактор',213,'NoData','NoData','NoData',0,15,0),(292,'турбина',213,'NoData','NoData','NoData',0,15,0),(293,'турбины',213,'NoData','NoData','NoData',0,15,0),(294,'турбокомпрессор',213,'NoData','NoData','NoData',0,15,0),(295,'турбокомпрессоры',213,'NoData','NoData','NoData',0,15,0),(296,'монтаж радиаторов',213,'NoData','NoData','NoData',0,15,0),(297,'газовые баллоны',213,'NoData','NoData','NoData',0,15,0),(298,'газовые счетчики',213,'NoData','NoData','NoData',0,15,0),(299,'бытовые газовые счетчики',213,'NoData','NoData','NoData',0,15,0),(300,'счетчик газа',213,'NoData','NoData','NoData',0,15,0),(301,'счетчик газа бытовой',213,'NoData','NoData','NoData',0,15,0),(302,'автоматизация производства',213,'NoData','NoData','NoData',0,15,0),(303,'Sick',213,'NoData','NoData','NoData',0,15,0),(304,'купить датчики',213,'NoData','NoData','NoData',0,15,0),(305,'датчики',213,'NoData','NoData','NoData',0,15,0),(306,'датчик',213,'NoData','NoData','NoData',0,15,0),(307,'энкодеры',213,'NoData','NoData','NoData',0,15,0),(308,'энкодер',213,'NoData','NoData','NoData',0,15,0),(309,'коммунальная техника',213,'NoData','NoData','NoData',0,15,0),(310,'автовышка',213,'NoData','NoData','NoData',0,15,0),(311,'автоподъемник',213,'NoData','NoData','NoData',0,15,0),(312,'мусоровоз',213,'NoData','NoData','NoData',0,15,0),(313,'грейфер',213,'NoData','NoData','NoData',0,15,0),(314,'автогрейдер',213,'NoData','NoData','NoData',0,15,0),(315,'грейдер',213,'NoData','NoData','NoData',0,15,0),(316,'отдых в Индонезии',213,'NoData','NoData','NoData',0,16,0),(317,'туры Индонезия',213,'NoData','NoData','NoData',0,16,0),(318,'отдых в Японии',213,'NoData','NoData','NoData',0,16,0),(319,'туры в Камбоджу',213,'NoData','NoData','NoData',0,16,0),(320,'туры в Лаос',213,'NoData','NoData','NoData',0,16,0),(321,'туры Лаос',213,'NoData','NoData','NoData',0,16,0),(322,'отдых в Малайзии',213,'NoData','NoData','NoData',0,16,0),(323,'отдых Малайзия',213,'NoData','NoData','NoData',0,16,0),(324,'Малайзия отдых',213,'NoData','NoData','NoData',0,16,0),(325,'отдых в Шри-Ланке',213,'NoData','NoData','NoData',0,16,0),(326,'туры во Вьетнам',213,'NoData','NoData','NoData',0,16,0),(327,'отдых Вьетнам',213,'NoData','NoData','NoData',0,16,0),(329,'Асбест',213,'NoData','NoData','NoData',0,0,0),(330,'Test',213,'NoData','NoData','NoData',0,0,0),(331,'тест1',213,'NoData','NoData','NoData',0,0,0),(332,'тест2',213,'NoData','NoData','NoData',0,0,0),(333,'ключевое слово раз',213,'NoData','NoData','NoData',2,2,0),(334,'Ключевое слово два!!!',213,'NoData','NoData','NoData',2,2,0),(335,'меню ресторана',213,'NoData','NoData','NoData',3,9,0),(336,'пивной ресторан',213,'NoData','NoData','NoData',3,9,0),(337,'сайт ресторана',213,'NoData','NoData','NoData',3,9,0),(338,'отзывы рестораны',213,'NoData','NoData','NoData',3,9,0),(339,'ресторан кухня',213,'NoData','NoData','NoData',3,9,0),(340,'ресторан бар',213,'NoData','NoData','NoData',3,9,0),(341,'китайский ресторан',213,'NoData','NoData','NoData',3,9,0),(342,'ресторан клуб',213,'NoData','NoData','NoData',3,9,0),(343,'итальянский ресторан',213,'NoData','NoData','NoData',3,9,0),(344,'ресторан доставка',213,'NoData','NoData','NoData',3,9,0),(345,'еда ресторан',213,'NoData','NoData','NoData',3,9,0),(346,'проведение банкетов',213,'NoData','NoData','NoData',3,9,0),(347,'банкеты свадьбы',213,'NoData','NoData','NoData',3,9,0),(348,'кафе и рестораны для свадьбы',213,'NoData','NoData','NoData',3,9,0),(349,'ресторан для свадьбы',213,'NoData','NoData','NoData',3,9,0),(350,'страхование',213,'NoData','NoData','NoData',3,10,0),(351,'страховые компании',213,'NoData','NoData','NoData',3,10,0),(352,'автострахование',213,'NoData','NoData','NoData',3,10,0),(353,'каско',213,'NoData','NoData','NoData',3,10,0),(354,'автокаско',213,'NoData','NoData','NoData',3,10,0),(355,'расчет каско',213,'NoData','NoData','NoData',3,10,0),(356,'осаго',213,'NoData','NoData','NoData',3,10,0),(357,'страхование автомобиля',213,'NoData','NoData','NoData',3,10,0),(358,'страховка',213,'NoData','NoData','NoData',3,10,0),(359,'страхование жизни',213,'NoData','NoData','NoData',3,10,0),(360,'медицинское страхование',213,'NoData','NoData','NoData',3,10,0),(361,'страхование имущества',213,'NoData','NoData','NoData',3,10,0),(362,'страхование недвижимости',213,'NoData','NoData','NoData',3,10,0),(363,'страхование квартиры',213,'NoData','NoData','NoData',3,10,0),(364,'добровольное медицинское страхование',213,'NoData','NoData','NoData',3,10,0),(365,'страхование грузов',213,'NoData','NoData','NoData',3,10,0),(366,'страхование детей',213,'NoData','NoData','NoData',3,10,0),(367,'страхование жилья',213,'NoData','NoData','NoData',3,10,0),(368,'мед страхование',213,'NoData','NoData','NoData',3,10,0),(369,'фонды страхования',213,'NoData','NoData','NoData',3,10,0),(370,'социально страхование',213,'NoData','NoData','NoData',3,10,0),(371,'пенсионное страхование',213,'NoData','NoData','NoData',3,10,0),(372,'страхование ответственности',213,'NoData','NoData','NoData',3,10,0),(373,'договор страхования',213,'NoData','NoData','NoData',3,10,0),(374,'государственное страхование',213,'NoData','NoData','NoData',3,10,0),(375,'полис медицинского страхования',213,'NoData','NoData','NoData',3,10,0),(376,'страхование случаи',213,'NoData','NoData','NoData',3,10,0),(377,'страхование вкладов',213,'NoData','NoData','NoData',3,10,0),(378,'страхование от несчастного случая',213,'NoData','NoData','NoData',3,10,0),(379,'гражданское страхование',213,'NoData','NoData','NoData',3,10,0),(380,'страхование рисков',213,'NoData','NoData','NoData',3,10,0),(381,'страховые организации',213,'NoData','NoData','NoData',3,10,0),(382,'страхование +в россии',213,'NoData','NoData','NoData',3,10,0),(383,'страховая компания россия',213,'NoData','NoData','NoData',3,10,0),(384,'зеленая карта',213,'NoData','NoData','NoData',3,10,0),(385,'страхование жизни +и здоровья',213,'NoData','NoData','NoData',3,10,0),(386,'лечение бесплодия',213,'NoData','NoData','NoData',3,11,0),(387,'искусственное оплодотворение',213,'NoData','NoData','NoData',3,11,0),(388,'клиника бесплодия',213,'NoData','NoData','NoData',3,11,0),(389,'женское бесплодие',213,'NoData','NoData','NoData',3,11,0),(390,'бесплодие лечить',213,'NoData','NoData','NoData',3,11,0),(391,'инсеминация',213,'NoData','NoData','NoData',3,11,0),(392,'дети из пробирки',213,'NoData','NoData','NoData',3,11,0),(393,'экстракорпоральное оплодотворение',213,'NoData','NoData','NoData',3,11,0),(394,'пластическая хирургия',213,'NoData','NoData','NoData',3,11,0),(395,'центр пластической хирургии',213,'NoData','NoData','NoData',3,11,0),(396,'маммопластика',213,'NoData','NoData','NoData',3,11,0),(397,'ринопластика',213,'NoData','NoData','NoData',3,11,0),(398,'пластика живота',213,'NoData','NoData','NoData',3,11,0),(399,'липосакция',213,'NoData','NoData','NoData',3,11,0),(400,'абдоминопластика',213,'NoData','NoData','NoData',3,11,0),(401,'подтяжка лица',213,'NoData','NoData','NoData',3,11,0),(402,'пластический хирург',213,'NoData','NoData','NoData',3,11,0),(403,'блефаропластика',213,'NoData','NoData','NoData',3,11,0),(404,'пластика носа',213,'NoData','NoData','NoData',3,11,0),(405,'лечение',213,'NoData','NoData','NoData',3,11,0),(406,'уролог',213,'NoData','NoData','NoData',3,11,0),(407,'консультация врача',213,'NoData','NoData','NoData',3,11,0),(408,'медицинские услуги',213,'NoData','NoData','NoData',3,11,0),(409,'дерматолог',213,'NoData','NoData','NoData',3,11,0),(410,'консультация уролога',213,'NoData','NoData','NoData',3,11,0),(411,'платная клиника',213,'NoData','NoData','NoData',3,11,0),(412,'лечение сердца',213,'NoData','NoData','NoData',3,11,0),(413,'инфаркт миокарда лечение',213,'NoData','NoData','NoData',3,11,0),(414,'стенокардия лечение',213,'NoData','NoData','NoData',3,11,0),(415,'аритмия лечение',213,'NoData','NoData','NoData',3,11,0),(416,'лечение гипертонии',213,'NoData','NoData','NoData',3,11,0),(417,'мерцательная аритмия лечение',213,'NoData','NoData','NoData',3,11,0),(418,'лечение инфаркта',213,'NoData','NoData','NoData',3,11,0),(419,'реабилитация после инфаркта',213,'NoData','NoData','NoData',3,11,0),(420,'инсульт лечение',213,'NoData','NoData','NoData',3,11,0),(421,'инсульт реабилитация',213,'NoData','NoData','NoData',3,11,0),(422,'женский трикотаж',213,'NoData','NoData','NoData',3,12,0),(423,'трикотаж интернет магазин',213,'NoData','NoData','NoData',3,12,0),(424,'стильная одежда',213,'NoData','NoData','NoData',3,12,0),(425,'где купить одежду',213,'NoData','NoData','NoData',3,12,0),(426,'магазин модной одежды',213,'NoData','NoData','NoData',3,12,0),(427,'магазин недорогой одежды',213,'NoData','NoData','NoData',3,12,0),(428,'магазин спортивной одежды',213,'NoData','NoData','NoData',3,12,0),(429,'женская одежда оптом',213,'NoData','NoData','NoData',3,12,0),(430,'магазин молодежной одежды',213,'NoData','NoData','NoData',3,12,0),(431,'деловая одежда',213,'NoData','NoData','NoData',3,12,0),(432,'итальянская одежда',213,'NoData','NoData','NoData',3,12,0),(433,'джинсовая одежда',213,'NoData','NoData','NoData',3,12,0),(434,'оптовая продажа одежды',213,'NoData','NoData','NoData',3,12,0),(435,'модная женская одежда',213,'NoData','NoData','NoData',3,12,0),(436,'офисная одежда',213,'NoData','NoData','NoData',3,12,0),(437,'стильная одежда',213,'NoData','NoData','NoData',3,12,0),(438,'магазин плитки',213,'NoData','NoData','NoData',3,13,0),(439,'керамическая плитка для ванной',213,'NoData','NoData','NoData',3,13,0),(440,'плитка для пола',213,'NoData','NoData','NoData',3,13,0),(441,'фасадная плитка',213,'NoData','NoData','NoData',3,13,0),(442,'ламинат купить',213,'NoData','NoData','NoData',3,13,0),(443,'проектирование домов',213,'NoData','NoData','NoData',3,13,0),(444,'проектирование зданий',213,'NoData','NoData','NoData',3,13,0),(445,'отделка фасадов',213,'NoData','NoData','NoData',3,13,0),(446,'облицовка фасадов',213,'NoData','NoData','NoData',3,13,0),(447,'фасадные системы',213,'NoData','NoData','NoData',3,13,0),(448,'панели мдф',213,'NoData','NoData','NoData',3,13,0),(449,'сендвич панели',213,'NoData','NoData','NoData',3,13,0),(450,'блоки стеновые',213,'NoData','NoData','NoData',3,13,0),(451,'кирпич керамический',213,'NoData','NoData','NoData',3,13,0),(452,'производство кирпича',213,'NoData','NoData','NoData',3,13,0),(453,'кирпич строительный',213,'NoData','NoData','NoData',3,13,0),(454,'печной кирпич',213,'NoData','NoData','NoData',3,13,0),(455,'магазин ковров',213,'NoData','NoData','NoData',3,13,0),(456,'туры',213,'NoData','NoData','NoData',3,16,0),(457,'горящие путевки',213,'NoData','NoData','NoData',3,16,0),(458,'путевки',213,'NoData','NoData','NoData',3,16,0),(459,'турфирмы',213,'NoData','NoData','NoData',3,16,0),(460,'туры турция',213,'NoData','NoData','NoData',3,16,0),(461,'турагенства',213,'NoData','NoData','NoData',3,16,0),(462,'турагентство',213,'NoData','NoData','NoData',3,16,0),(463,'отдых турция',213,'NoData','NoData','NoData',3,16,0),(464,'турция путевки',213,'NoData','NoData','NoData',3,16,0),(465,'туры египет',213,'NoData','NoData','NoData',3,16,0),(466,'горячие туры',213,'NoData','NoData','NoData',3,16,0),(467,'отдых египет',213,'NoData','NoData','NoData',3,16,0),(468,'магазин горящих путевок',213,'NoData','NoData','NoData',3,16,0),(469,'отдых',213,'NoData','NoData','NoData',3,16,0),(470,'туроператоры',213,'NoData','NoData','NoData',3,16,0),(471,'отдых за границей',213,'NoData','NoData','NoData',3,16,0),(472,'туристические агентства',213,'NoData','NoData','NoData',3,16,0),(473,'туроператоры +по турции',213,'NoData','NoData','NoData',3,16,0),(474,'отдых +в эмиратах',213,'NoData','NoData','NoData',3,16,0),(475,'отдых +в крыму',213,'NoData','NoData','NoData',3,16,0),(476,'туризм +и отдых',213,'NoData','NoData','NoData',3,16,0),(477,'турфирмы москвы',213,'NoData','NoData','NoData',3,16,0),(478,'автобусные туры',213,'NoData','NoData','NoData',3,16,0),(479,'отдых недорого +за границей',213,'NoData','NoData','NoData',3,16,0),(480,'дешевый отдых +за границей',213,'NoData','NoData','NoData',3,16,0),(481,'отдых +на черном море',213,'NoData','NoData','NoData',3,16,0),(482,'экскурсии +в петербурге',213,'NoData','NoData','NoData',3,16,0),(483,'новогодние туры',213,'NoData','NoData','NoData',3,16,0),(484,'сеты1',213,'NoData','NoData','NoData',4,0,0),(485,'соты1',213,'NoData','NoData','NoData',4,0,0),(486,'стол',213,'NoData','NoData','NoData',0,5,0),(487,'стул',213,'NoData','NoData','NoData',0,5,0),(488,'столы',213,'NoData','NoData','NoData',0,5,0),(489,'стулья',213,'NoData','NoData','NoData',0,5,0),(490,'шкаф',213,'NoData','NoData','NoData',0,5,0),(491,'шкафы',213,'NoData','NoData','NoData',0,5,0),(492,'sfsfsdf',213,'NoData','NoData','NoData',0,0,0),(493,'sdfsfsfd',213,'NoData','NoData','NoData',1,0,0),(494,'sdfsfds',213,'NoData','NoData','NoData',1,0,0),(495,'dfdfdfdfdfdfdf',213,'NoData','NoData','NoData',1,0,0),(496,'4fdddddddddddd',213,'NoData','NoData','NoData',1,0,0),(497,'ацуацуа',213,'NoData','NoData','NoData',1,0,0),(498,'ыв',213,'NoData','NoData','NoData',1,0,0),(499,'фывфыв',213,'NoData','NoData','NoData',1,0,0),(500,'фывфывыфыв',213,'NoData','NoData','NoData',0,0,0),(501,'фывфывфвцву3',213,'NoData','NoData','NoData',0,0,0),(502,'111111111111111',213,'NoData','NoData','NoData',0,0,0),(503,'11ывмыв ыв ыв',213,'NoData','NoData','NoData',0,0,0),(504,'цуафа ык кп ук',213,'NoData','NoData','NoData',5,0,0),(505,'цуацуа',213,'NoData','NoData','NoData',6,0,0),(506,'цуацуа',213,'NoData','NoData','NoData',6,0,0),(507,'цуацуа',213,'NoData','NoData','NoData',6,0,0),(508,'efewf2<12',213,'NoData','NoData','NoData',9,0,1),(509,'22222222222222222222222222222',213,'NoData','NoData','NoData',0,0,0),(510,'2323',213,'NoData','NoData','NoData',10,0,4),(511,'3232',213,'NoData','NoData','NoData',11,0,5),(512,'4444',213,'NoData','NoData','NoData',12,4,6),(513,'srfsfsdfsdf',213,'NoData','NoData','NoData',1,4,7),(514,'sdfsfsfsfsfsf',213,'NoData','NoData','NoData',1,4,7),(515,'ЛА лА!!',216,'NoData','NoData','NoData',13,5,8);
/*!40000 ALTER TABLE `sep_Keywords` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sep_Positions`
--

DROP TABLE IF EXISTS `sep_Positions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sep_Positions` (
  `keyword_id` mediumint(8) unsigned NOT NULL,
  `url_id` int(10) unsigned NOT NULL,
  `site_id` mediumint(8) unsigned NOT NULL,
  `pos` tinyint(3) unsigned NOT NULL,
  `pos_dot` tinyint(3) unsigned NOT NULL,
  `links_search` tinyint(1) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `pos` (`pos`,`pos_dot`),
  KEY `fk_site` (`site_id`),
  KEY `fk_url` (`url_id`),
  KEY `fk_keyword` (`keyword_id`),
  KEY `date` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sep_Positions`
--

LOCK TABLES `sep_Positions` WRITE;
/*!40000 ALTER TABLE `sep_Positions` DISABLE KEYS */;
INSERT INTO `sep_Positions` VALUES (46,1,1,1,1,0,'2010-12-20 01:06:11'),(46,2,2,2,2,0,'2010-12-20 01:06:11'),(46,3,3,3,3,0,'2010-12-20 01:06:11'),(46,4,4,4,4,0,'2010-12-20 01:06:11'),(46,5,5,5,5,0,'2010-12-20 01:06:11'),(46,6,6,6,6,0,'2010-12-20 01:06:11'),(46,7,7,7,7,0,'2010-12-20 01:06:11'),(46,8,8,8,8,0,'2010-12-20 01:06:11'),(46,9,9,9,9,0,'2010-12-20 01:06:11'),(46,10,10,10,10,0,'2010-12-20 01:06:11'),(46,11,11,11,11,0,'2010-12-20 01:06:11'),(46,12,12,12,12,0,'2010-12-20 01:06:11'),(46,13,13,13,13,0,'2010-12-20 01:06:11'),(46,14,14,14,14,0,'2010-12-20 01:06:11'),(46,15,15,15,15,0,'2010-12-20 01:06:11'),(46,16,16,16,16,0,'2010-12-20 01:06:11'),(46,17,17,17,17,0,'2010-12-20 01:06:11'),(46,18,18,18,18,0,'2010-12-20 01:06:11'),(46,19,19,19,19,0,'2010-12-20 01:06:11'),(46,20,20,20,20,0,'2010-12-20 01:06:11'),(46,21,21,21,21,0,'2010-12-20 01:06:11'),(46,22,22,22,22,0,'2010-12-20 01:06:11'),(46,23,23,23,23,0,'2010-12-20 01:06:11'),(46,24,24,24,24,0,'2010-12-20 01:06:11'),(46,25,25,25,25,0,'2010-12-20 01:06:11'),(46,26,26,26,26,0,'2010-12-20 01:06:11'),(46,27,27,27,27,0,'2010-12-20 01:06:11'),(46,28,28,28,28,0,'2010-12-20 01:06:11'),(46,29,29,29,29,0,'2010-12-20 01:06:11'),(46,30,30,30,30,0,'2010-12-20 01:06:11'),(46,31,31,31,31,0,'2010-12-20 01:06:11'),(46,32,32,32,32,0,'2010-12-20 01:06:11'),(46,33,33,33,33,0,'2010-12-20 01:06:11'),(46,34,34,34,34,0,'2010-12-20 01:06:11'),(46,35,35,35,35,0,'2010-12-20 01:06:11'),(46,36,36,36,36,0,'2010-12-20 01:06:11'),(46,37,37,37,37,0,'2010-12-20 01:06:11'),(46,38,38,38,38,0,'2010-12-20 01:06:11'),(46,39,39,39,39,0,'2010-12-20 01:06:11'),(46,40,40,40,40,0,'2010-12-20 01:06:11'),(46,41,41,41,41,0,'2010-12-20 01:06:11'),(46,42,42,42,42,0,'2010-12-20 01:06:11'),(46,43,43,43,43,0,'2010-12-20 01:06:11'),(46,44,44,44,44,0,'2010-12-20 01:06:11'),(46,45,45,45,45,0,'2010-12-20 01:06:11'),(46,46,46,46,46,0,'2010-12-20 01:06:11'),(46,47,47,47,47,0,'2010-12-20 01:06:11'),(46,48,48,48,48,0,'2010-12-20 01:06:11'),(46,49,49,49,49,0,'2010-12-20 01:06:11'),(46,50,50,50,50,0,'2010-12-20 01:06:11'),(46,51,51,51,51,0,'2010-12-20 01:06:11'),(46,52,52,52,52,0,'2010-12-20 01:06:11'),(46,53,53,53,53,0,'2010-12-20 01:06:11'),(46,54,54,54,54,0,'2010-12-20 01:06:11'),(46,55,55,55,55,0,'2010-12-20 01:06:11'),(46,56,56,56,56,0,'2010-12-20 01:06:11'),(46,57,57,57,57,0,'2010-12-20 01:06:11'),(46,58,58,58,58,0,'2010-12-20 01:06:11'),(46,59,59,59,59,0,'2010-12-20 01:06:11'),(46,60,60,60,60,0,'2010-12-20 01:06:11'),(46,61,61,61,61,0,'2010-12-20 01:06:11'),(46,62,62,62,62,0,'2010-12-20 01:06:11'),(46,63,63,63,63,0,'2010-12-20 01:06:11'),(46,64,64,64,64,0,'2010-12-20 01:06:11'),(46,65,65,65,65,0,'2010-12-20 01:06:11'),(46,66,66,66,66,0,'2010-12-20 01:06:11'),(46,67,67,67,67,0,'2010-12-20 01:06:11'),(46,68,68,68,68,0,'2010-12-20 01:06:11'),(46,69,69,69,69,0,'2010-12-20 01:06:11'),(46,70,70,70,70,0,'2010-12-20 01:06:11'),(46,71,71,71,71,0,'2010-12-20 01:06:11'),(46,72,72,72,72,0,'2010-12-20 01:06:11'),(46,73,73,73,73,0,'2010-12-20 01:06:11'),(46,74,74,74,74,0,'2010-12-20 01:06:11'),(46,75,75,75,75,0,'2010-12-20 01:06:11'),(46,76,76,76,76,0,'2010-12-20 01:06:11'),(46,77,77,77,77,0,'2010-12-20 01:06:11'),(46,78,78,78,78,0,'2010-12-20 01:06:11'),(46,79,79,79,79,0,'2010-12-20 01:06:11'),(46,80,80,80,80,0,'2010-12-20 01:06:11'),(46,81,81,81,81,0,'2010-12-20 01:06:11'),(46,82,82,82,82,0,'2010-12-20 01:06:11'),(46,83,83,83,83,0,'2010-12-20 01:06:11'),(46,84,84,84,84,0,'2010-12-20 01:06:11'),(46,85,85,85,85,0,'2010-12-20 01:06:11'),(46,86,86,86,86,0,'2010-12-20 01:06:11'),(46,87,87,87,87,0,'2010-12-20 01:06:11'),(46,88,88,88,88,0,'2010-12-20 01:06:11'),(46,89,89,89,89,0,'2010-12-20 01:06:11'),(46,90,90,90,90,0,'2010-12-20 01:06:11'),(46,91,91,91,91,0,'2010-12-20 01:06:11'),(46,92,92,92,92,0,'2010-12-20 01:06:11'),(46,93,93,93,93,0,'2010-12-20 01:06:11'),(46,94,94,94,94,0,'2010-12-20 01:06:11'),(46,95,95,95,95,0,'2010-12-20 01:06:11'),(46,96,96,96,96,0,'2010-12-20 01:06:11'),(46,97,97,97,97,0,'2010-12-20 01:06:11'),(46,98,98,98,98,0,'2010-12-20 01:06:11'),(46,99,99,99,99,0,'2010-12-20 01:06:11'),(46,100,100,100,100,0,'2010-12-20 01:06:11');
/*!40000 ALTER TABLE `sep_Positions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sep_Projects`
--

DROP TABLE IF EXISTS `sep_Projects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sep_Projects` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `site` varchar(64) NOT NULL,
  `descr` varchar(255) NOT NULL,
  `status` enum('Active','Blocked','Deleted') NOT NULL,
  `date_create` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sep_Projects`
--

LOCK TABLES `sep_Projects` WRITE;
/*!40000 ALTER TABLE `sep_Projects` DISABLE KEYS */;
INSERT INTO `sep_Projects` VALUES (2,'Сайт папы','suslov60.ru','Очень важный клиент','Active','2010-12-20 02:37:41');
/*!40000 ALTER TABLE `sep_Projects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sep_Regions`
--

DROP TABLE IF EXISTS `sep_Regions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sep_Regions` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `sort` smallint(5) NOT NULL DEFAULT '1',
  `yandex_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=221 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sep_Regions`
--

LOCK TABLES `sep_Regions` WRITE;
/*!40000 ALTER TABLE `sep_Regions` DISABLE KEYS */;
INSERT INTO `sep_Regions` VALUES (213,'Москва',-1,213),(215,'Нижний Новгород',0,47),(216,'Зеленоград',0,216),(217,'Мытищи',0,10740),(218,'Химки',0,10758),(219,'Ярославль',0,16),(220,'1221',1,1);
/*!40000 ALTER TABLE `sep_Regions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sep_Sets`
--

DROP TABLE IF EXISTS `sep_Sets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sep_Sets` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `project_id` mediumint(8) unsigned NOT NULL,
  `search_type` set('YaXml','YaXmlDot','Google','Rambler') NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sep_Sets`
--

LOCK TABLES `sep_Sets` WRITE;
/*!40000 ALTER TABLE `sep_Sets` DISABLE KEYS */;
INSERT INTO `sep_Sets` VALUES (1,'122__Z','2010-11-11 07:02:18',0,''),(2,'выв','2010-11-11 22:26:21',2,''),(3,'Нормальное название','2010-11-11 22:28:51',2,'YaXml,YaXmlDot'),(4,'111 авы','2010-11-11 22:29:15',2,''),(5,'11111112в','2010-11-11 22:29:57',0,''),(6,'уыацуа','2010-11-11 22:30:11',0,''),(9,'efefef','2010-11-12 06:39:05',0,''),(10,'2323','2010-11-28 12:22:15',0,''),(11,'3232','2010-11-28 12:23:12',0,''),(12,'444','2010-11-28 12:24:03',0,''),(13,'лалалала','2010-12-16 07:17:52',0,'');
/*!40000 ALTER TABLE `sep_Sets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sep_Sites`
--

DROP TABLE IF EXISTS `sep_Sites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sep_Sites` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `domain_create` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `domain_id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `site` (`name`,`domain_create`) USING BTREE,
  KEY `domain_id` (`domain_id`)
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sep_Sites`
--

LOCK TABLES `sep_Sites` WRITE;
/*!40000 ALTER TABLE `sep_Sites` DISABLE KEYS */;
INSERT INTO `sep_Sites` VALUES (1,'www.hansa-brenner.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(2,'www.warmwelcome.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(3,'www.Magic-Fantazy.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(4,'www.franklin-grant.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(5,'www.metallografika.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(6,'katalog.rasdva.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(7,'www.radp.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(8,'www.canadian-parket.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(9,'www.denol.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(10,'www.rifle.rutools.paintballers.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(11,'www.rctc.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(12,'bio.fizteh.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(13,'www.sievi.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(14,'www.kastorgaas.ee','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(15,'www.domiproject.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(16,'www.monada.com.ua','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(17,'www.library.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(18,'www.sotki.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(19,'www.aircon.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(20,'www.btmax.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(21,'www.burmester.de','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(22,'www.ispa.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(23,'skontrol.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(24,'www.liveinternet.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(25,'partner-ltd.spb.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(26,'www.tinko.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(27,'www.elitst.com','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(28,'blog.kp.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(29,'www.tatprof.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(30,'www.mialit.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(31,'www.intermetal.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(32,'euroterm.by','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(33,'www.dart-electro.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(34,'www.hdishow.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(35,'www.sgmonolit.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(36,'media.klinkmann.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(37,'www.ukr-print.net','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(38,'www.powerplate.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(39,'www.atlantauto.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(40,'blog.alexkrylov.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(41,'www.air-balance.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(42,'www.nsg.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(43,'www.adcmaster.com','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(44,'www.tmi-s.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(45,'www.aleron.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(46,'www.ngsgroup.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(47,'www.schorch.de','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(48,'www.kipa.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(49,'pobeda-mo.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(50,'it-managment.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(51,'www.deltamedical.tj','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(52,'www.asvik.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(53,'www.ekvator-hifi.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(54,'www.portniha.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(55,'archvestnik.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(56,'www.solntv.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(57,'www.mobilesmania.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(58,'www.rid.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(59,'mb.tomsk.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(60,'www.sts-sib.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(61,'smi.kurganobl.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(62,'www.eltp.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(63,'www.scat-technology.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(64,'www.torkret.by','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(65,'psk-snab.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(66,'uisrussia.msu.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(67,'www.ste.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(68,'www.stu.lipetsk.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(69,'www.filipark.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(70,'vita-corp.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(71,'www.mos-snab.com','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(72,'www.npp-simt.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(73,'www.denia.lt','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(74,'www.orenbasket.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(75,'www.rfc.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(76,'www.rapid-up.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(77,'www.stellapro.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(78,'www.termit-eh.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(79,'www.sladis.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(80,'www.ues-rb.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(81,'www.tppzkam.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(82,'www.termosistem.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(83,'www.voxtel.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(84,'orionmotor.narod.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(85,'www.besthause.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(86,'www.blogbaster.org','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(87,'www.gart.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(88,'www.mobiset.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(89,'www.power-e.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(90,'www.weldingassistance.com','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(91,'www.chirt.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(92,'www.energiatlt.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(93,'www.geotyumen.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(94,'www.vesta-trading.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(95,'www.alpha-house.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(96,'blog.aif.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(97,'www.musmaker.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(98,'www.agro3.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(99,'www.oncology.tomsk.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0),(100,'www.alur-id.ru','2010-12-20 01:06:11','0000-00-00 00:00:00',0);
/*!40000 ALTER TABLE `sep_Sites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sep_Thematics`
--

DROP TABLE IF EXISTS `sep_Thematics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sep_Thematics` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sep_Thematics`
--

LOCK TABLES `sep_Thematics` WRITE;
/*!40000 ALTER TABLE `sep_Thematics` DISABLE KEYS */;
INSERT INTO `sep_Thematics` VALUES (2,'раз1','2010-09-24 10:32:53'),(3,'апра','2010-09-27 05:13:05'),(4,'dds','2010-09-27 05:14:18'),(5,'Мебель','2010-09-27 10:24:30'),(6,'Типография','2010-09-27 10:51:06'),(7,'Упаковка','2010-09-27 10:51:29'),(8,'Юридические услуги','2010-09-27 10:51:38'),(9,'Рестораны','2010-09-27 10:51:45'),(10,'Страхование','2010-09-27 10:51:53'),(11,'Медицина','2010-09-27 10:52:09'),(12,'Одежда','2010-09-27 10:52:16'),(13,'Строительство / строительные и отделочные материалы','2010-09-27 10:52:23'),(14,'Автотехника','2010-09-27 10:52:30'),(15,'Оборудование (комплектующие, приборы)','2010-09-27 10:52:39'),(16,'Туризм','2010-09-27 10:52:48');
/*!40000 ALTER TABLE `sep_Thematics` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sep_UrlKeywords`
--

DROP TABLE IF EXISTS `sep_UrlKeywords`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sep_UrlKeywords` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `url` text NOT NULL,
  `keyword_id` mediumint(8) unsigned NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `keyword_id` (`keyword_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sep_UrlKeywords`
--

LOCK TABLES `sep_UrlKeywords` WRITE;
/*!40000 ALTER TABLE `sep_UrlKeywords` DISABLE KEYS */;
INSERT INTO `sep_UrlKeywords` VALUES (1,'http://localhost/ba/',0,'2010-11-12 06:38:16'),(2,'s',0,'2010-11-27 15:04:19'),(3,'url22222222222222222',0,'2010-11-28 12:21:06'),(4,'2323',0,'2010-11-28 12:22:15'),(5,'3232',0,'2010-11-28 12:23:12'),(6,'4444',0,'2010-11-28 12:24:03'),(7,'sdfsfsdf',0,'2010-11-29 00:37:39'),(8,'lala.ru',0,'2010-12-16 07:17:52');
/*!40000 ALTER TABLE `sep_UrlKeywords` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sep_Urls`
--

DROP TABLE IF EXISTS `sep_Urls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sep_Urls` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `url` text NOT NULL,
  `site_id` mediumint(8) unsigned NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `site_id` (`site_id`)
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sep_Urls`
--

LOCK TABLES `sep_Urls` WRITE;
/*!40000 ALTER TABLE `sep_Urls` DISABLE KEYS */;
INSERT INTO `sep_Urls` VALUES (1,'http://www.hansa-brenner.ru/uploads/tx_download/HPM1-RGB.pdf',1,'2010-12-20 01:06:11'),(2,'http://www.warmwelcome.ru/upload/information_system_22/1/2/9/item_129/information_items_129.pdf',2,'2010-12-20 01:06:11'),(3,'http://www.Magic-Fantazy.ru/?ukey=search&searchstring=РЅР°РєР»РµР№РєРё РЅР° С‚РµС…РЅРёРєСѓ',3,'2010-12-20 01:06:11'),(4,'http://www.franklin-grant.ru/ru/pr/pdf/bd_2007_03.pdf',4,'2010-12-20 01:06:11'),(5,'http://www.metallografika.ru/p155.aspx',5,'2010-12-20 01:06:11'),(6,'http://katalog.rasdva.ru/browsetag.php?tag=наклейки',6,'2010-12-20 01:06:11'),(7,'http://www.radp.ru/db/20074/030-036_Gajonova-1.pdf',7,'2010-12-20 01:06:11'),(8,'http://www.canadian-parket.ru/files/rukovodstva/ruk-po-ustanovke-dvuxslojnogo-parketa.pdf',8,'2010-12-20 01:06:11'),(9,'http://www.denol.ru/pdf/rmq-titan_2010.pdf',9,'2010-12-20 01:06:11'),(10,'http://www.rifle.rutools.paintballers.ru/klopp/texts/Animals/cats-brochure-vologda.pdf',10,'2010-12-20 01:06:11'),(11,'http://www.rctc.ru/veloclub/mater/rusbikes.pdf',11,'2010-12-20 01:06:11'),(12,'http://bio.fizteh.ru/student/files/biology/bioarticles/f_1ai2',12,'2010-12-20 01:06:11'),(13,'http://www.sievi.ru/files/catalogue_sievi_part1.pdf',13,'2010-12-20 01:06:11'),(14,'http://www.kastorgaas.ee/admin/prod/files/CTU paigaldus,kasutusjuhendRUS.pdf',14,'2010-12-20 01:06:11'),(15,'http://www.domiproject.ru/wcmfiles/Instruction_domitop_h_5.pdf',15,'2010-12-20 01:06:11'),(16,'http://www.monada.com.ua/img/UserFiles/File/userfiles/Vergokan/Metallicheskie koroba i lyuchki.pdf',16,'2010-12-20 01:06:11'),(17,'http://www.library.ru/1/education/journal/archive/mbd_1_2006.pdf',17,'2010-12-20 01:06:11'),(18,'http://www.sotki.ru/almanac/2010/05.pdf',18,'2010-12-20 01:06:11'),(19,'http://www.aircon.ru/useful/magazines/pdf/magazine/Mir_11.pdf',19,'2010-12-20 01:06:11'),(20,'http://www.btmax.ru/goods_instructs/1212941712.pdf',20,'2010-12-20 01:06:11'),(21,'http://www.burmester.de/de/media/pdf/prctlg/tests/file---AM_2009_03_Burmester_032 061.pdf',21,'2010-12-20 01:06:11'),(22,'http://www.ispa.ru/files/PNuUY.pdf',22,'2010-12-20 01:06:11'),(23,'http://skontrol.ru/uploads/files/VZO_PDF/VZO RUS.PDF',23,'2010-12-20 01:06:11'),(24,'http://www.liveinternet.ru/community/mac-faq/post122724407/',24,'2010-12-20 01:06:11'),(25,'http://partner-ltd.spb.ru/up/HP 40 WM.pdf',25,'2010-12-20 01:06:11'),(26,'http://www.tinko.ru/files/doc/6/manual/213114_213115_UM.pdf',26,'2010-12-20 01:06:11'),(27,'http://www.elitst.com/data/vorota/instruktion_montaj/alutech_instruction.pdf',27,'2010-12-20 01:06:11'),(28,'http://blog.kp.ru/users/3596141/post133881833/',28,'2010-12-20 01:06:11'),(29,'http://www.tatprof.ru/netcat_files/Catalog/EK-40_EK-69_EK-89.pdf',29,'2010-12-20 01:06:11'),(30,'http://www.mialit.ru/literatura/file_9121.pdf',30,'2010-12-20 01:06:11'),(31,'http://www.intermetal.ru/docs/kasafl-izoprflex/arktik-comf-int.pdf',31,'2010-12-20 01:06:11'),(32,'http://euroterm.by/wp-content/uploads/2010/04/supraline_K28_56_8.pdf',32,'2010-12-20 01:06:11'),(33,'http://www.dart-electro.ru/catalog/abb/1/2.pdf',33,'2010-12-20 01:06:11'),(34,'http://www.hdishow.ru/highendshow/pub/HES_2008_1.pdf',34,'2010-12-20 01:06:11'),(35,'http://www.sgmonolit.ru/UserFiles/price_1OK_120410.pdf',35,'2010-12-20 01:06:11'),(36,'http://media.klinkmann.ru/catalogue/ru/legrand/legrand_076-084.pdf',36,'2010-12-20 01:06:11'),(37,'http://www.ukr-print.net/tops/4-5922.htm',37,'2010-12-20 01:06:11'),(38,'http://www.powerplate.ru/club/biblioteka/pokupatel/files/2008-Power-Plate.pdf',38,'2010-12-20 01:06:11'),(39,'http://www.atlantauto.ru/files/9/7/OS.2005-01.p36-38.pdf',39,'2010-12-20 01:06:11'),(40,'http://blog.alexkrylov.ru/post129352764/',40,'2010-12-20 01:06:11'),(41,'http://www.air-balance.ru/files/DAIKIN/Ochistiteli vozduxa/MC707VM/RAZNOE/OM_MC707VMS(W).pdf',41,'2010-12-20 01:06:11'),(42,'http://www.nsg.ru/doc/nsg-linux/nsg-uitcp.pdf',42,'2010-12-20 01:06:11'),(43,'http://www.adcmaster.com/files/pdf/AROLO_TIFAR_.pdf',43,'2010-12-20 01:06:11'),(44,'http://www.tmi-s.ru/upload/kipis_articles/article_ahp.pdf',44,'2010-12-20 01:06:11'),(45,'http://www.aleron.ru/cat/legrand/pdf/2006_2007/11-3-strukturirovannaya-kabelnaya-sistema-lcs-shkafy-i-szity-vdi-atlantic-rozetochnye-bloki.pdf',45,'2010-12-20 01:06:11'),(46,'http://www.ngsgroup.ru/catalog_nederman.pdf',46,'2010-12-20 01:06:11'),(47,'http://www.schorch.de/Rep/Site/Download/Nieder/IP23/IP23_180-400_RU_00.pdf',47,'2010-12-20 01:06:11'),(48,'http://www.kipa.ru/pdir/pdf/8276_DLP600M.pdf',48,'2010-12-20 01:06:11'),(49,'http://pobeda-mo.ru/userdata/1271074984.pdf',49,'2010-12-20 01:06:11'),(50,'http://it-managment.ru/wp-content/uploads/2008/04/service-support.pdf',50,'2010-12-20 01:06:11'),(51,'http://www.deltamedical.tj/files/dda_5_2007.pdf',51,'2010-12-20 01:06:11'),(52,'http://www.asvik.ru/downloads/manuals/daikin/aircleaners/MC704VM(U).pdf',52,'2010-12-20 01:06:11'),(53,'http://www.ekvator-hifi.ru/images/uploads/Octave_(1).pdf',53,'2010-12-20 01:06:11'),(54,'http://www.portniha.ru/instr/janome_3050/dc3050.pdf',54,'2010-12-20 01:06:11'),(55,'http://archvestnik.ru/new/files/2062 Zagorod poselky 64-67.pdf',55,'2010-12-20 01:06:11'),(56,'http://www.solntv.ru/forum/profile.php?mode=viewprofile&u=269&sid=14a5fc67d49370b9f2689ce163fe24f7',56,'2010-12-20 01:06:11'),(57,'http://www.mobilesmania.ru/forum/index.php?showtopic=1579',57,'2010-12-20 01:06:11'),(58,'http://www.rid.ru/upload/files/Sistema.pdf',58,'2010-12-20 01:06:11'),(59,'http://mb.tomsk.ru/news_russia-17836.html',59,'2010-12-20 01:06:11'),(60,'http://www.sts-sib.ru/uploads/teh-info/proekt/nezamerz/1.pdf',60,'2010-12-20 01:06:11'),(61,'http://smi.kurganobl.ru/assets/files/arhiv/zarya/2009/04_06_2009-44.pdf',61,'2010-12-20 01:06:11'),(62,'http://www.eltp.ru/note/legrand/legrand_085-087.pdf',62,'2010-12-20 01:06:11'),(63,'http://www.scat-technology.ru/docs/switches/PushButtonSwitches.pdf',63,'2010-12-20 01:06:11'),(64,'http://www.torkret.by/upload/linedriver.pdf',64,'2010-12-20 01:06:11'),(65,'http://psk-snab.ru/wp-content/uploads/2010/01/P-651-16-RU-tcm135-6760.pdf',65,'2010-12-20 01:06:11'),(66,'http://uisrussia.msu.ru/docs/nov/pec/2000/4/ProEtContra_2000_4_04.pdf',66,'2010-12-20 01:06:11'),(67,'http://www.ste.ru/krohne/pdf/rus/MA_OPTISWITCH_5200_transistor_ru_050330.pdf',67,'2010-12-20 01:06:11'),(68,'http://www.stu.lipetsk.ru/files/materials/3363/2009_4_004.pdf',68,'2010-12-20 01:06:11'),(69,'http://www.filipark.ru/netcat_files/167/242/h_1495b658fc9def90b64e6b9d0df6107d',69,'2010-12-20 01:06:11'),(70,'http://vita-corp.ru/joom/PDF/PANHANS/ECOPAN.pdf',70,'2010-12-20 01:06:11'),(71,'http://www.mos-snab.com/index.php?option=com_content&view=article&id=93&Itemid=76',71,'2010-12-20 01:06:11'),(72,'http://www.npp-simt.ru/files/NPP-SiMT_rekl.pdf',72,'2010-12-20 01:06:11'),(73,'http://www.denia.lt/uploads/document/file_226_fm-ru.pdf',73,'2010-12-20 01:06:11'),(74,'http://www.orenbasket.ru/forum/profile.php?mode=viewprofile&u=38957&sid=563791c8f8c3950fba11a68939acf434',74,'2010-12-20 01:06:11'),(75,'http://www.rfc.ru/oracle9i_final_ru.pdf',75,'2010-12-20 01:06:11'),(76,'http://www.rapid-up.ru/cisco/postroenie_vpn.pdf?2fa1b67e0676e0cd3b38e06c3dd53dd7=97bb92ff67a9054297688c5c514a3cd8',76,'2010-12-20 01:06:11'),(77,'http://www.stellapro.ru/myFiles/file_66',77,'2010-12-20 01:06:11'),(78,'http://www.termit-eh.ru/uploads/media/SIEMENS_Delta.pdf',78,'2010-12-20 01:06:11'),(79,'http://www.sladis.ru/UserFiles/File/may_4(15)08.pdf',79,'2010-12-20 01:06:11'),(80,'http://www.ues-rb.ru/files/default/2ktp_c.pdf',80,'2010-12-20 01:06:11'),(81,'http://www.tppzkam.ru/publish/sport/pdf/5-98-2010.pdf',81,'2010-12-20 01:06:11'),(82,'http://www.termosistem.ru/files/vitorond10040-100.pdf',82,'2010-12-20 01:06:11'),(83,'http://www.voxtel.ru/Download/Obshee/Manual-Rus-Voxtel_Z11.pdf',83,'2010-12-20 01:06:11'),(84,'http://orionmotor.narod.ru/Germet_mot.pdf',84,'2010-12-20 01:06:11'),(85,'http://www.besthause.ru/public/files/files/instruktsiya_ftxs-g.pdf',85,'2010-12-20 01:06:11'),(86,'http://www.blogbaster.org/post128143342/',86,'2010-12-20 01:06:11'),(87,'http://www.gart.ru/digital/pdf/diga_01_05.pdf',87,'2010-12-20 01:06:11'),(88,'http://www.mobiset.ru/docs/philips_x710.pdf',88,'2010-12-20 01:06:11'),(89,'http://www.power-e.ru/pdf/2007_03_108.pdf',89,'2010-12-20 01:06:11'),(90,'http://www.weldingassistance.com/file/otherelement/pj/digi manuel_15151216_ru35545.pdf',90,'2010-12-20 01:06:11'),(91,'http://www.chirt.ru/download/student/vpo/SP/Test_LR.pdf',91,'2010-12-20 01:06:11'),(92,'http://www.energiatlt.ru/pdf/Sapfir22RDU.pdf',92,'2010-12-20 01:06:11'),(93,'http://www.geotyumen.ru/alternative.html',93,'2010-12-20 01:06:11'),(94,'http://www.vesta-trading.ru/prices/base/18.1/Supraline.pdf',94,'2010-12-20 01:06:11'),(95,'http://www.alpha-house.ru/data/files/technote30350270.pdf',95,'2010-12-20 01:06:11'),(96,'http://blog.aif.ru/users/kr-ev/post102607912/',96,'2010-12-20 01:06:11'),(97,'http://www.musmaker.ru/press/chip_2005-04a.pdf',97,'2010-12-20 01:06:11'),(98,'http://www.agro3.ru/InFo-data/item_037/file_0002358.pdf',98,'2010-12-20 01:06:11'),(99,'http://www.oncology.tomsk.ru/nii/journal/2007/pril2/information/soj_2007_Pril2_content.pdf',99,'2010-12-20 01:06:11'),(100,'http://www.alur-id.ru/spaw/uploads/files/X2 tech.pdf',100,'2010-12-20 01:06:11');
/*!40000 ALTER TABLE `sep_Urls` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sep_YaPagesSites`
--

DROP TABLE IF EXISTS `sep_YaPagesSites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sep_YaPagesSites` (
  `id` mediumint(8) unsigned NOT NULL,
  `site_id` mediumint(8) unsigned NOT NULL,
  `status` enum('Index','Noindex') NOT NULL DEFAULT 'Noindex',
  `parse` enum('Yes','No') NOT NULL DEFAULT 'No',
  `url` mediumtext NOT NULL,
  `date_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `date_first` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sep_YaPagesSites`
--

LOCK TABLES `sep_YaPagesSites` WRITE;
/*!40000 ALTER TABLE `sep_YaPagesSites` DISABLE KEYS */;
/*!40000 ALTER TABLE `sep_YaPagesSites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sep_YandexAccesses`
--

DROP TABLE IF EXISTS `sep_YandexAccesses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sep_YandexAccesses` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(45) NOT NULL DEFAULT '',
  `password` varchar(45) NOT NULL DEFAULT '',
  `xml_key` varchar(64) NOT NULL DEFAULT '',
  `interface_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sep_YandexAccesses`
--

LOCK TABLES `sep_YandexAccesses` WRITE;
/*!40000 ALTER TABLE `sep_YandexAccesses` DISABLE KEYS */;
INSERT INTO `sep_YandexAccesses` VALUES (2,'alexandersuslov','alY210411al','03.16530698:0be40b9b36e8180e342b869c26086871',136,'2010-11-28 18:57:29');
/*!40000 ALTER TABLE `sep_YandexAccesses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `st_Hosts`
--

DROP TABLE IF EXISTS `st_Hosts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `st_Hosts` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `url` varchar(127) NOT NULL,
  `descr` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `st_Hosts`
--

LOCK TABLES `st_Hosts` WRITE;
/*!40000 ALTER TABLE `st_Hosts` DISABLE KEYS */;
INSERT INTO `st_Hosts` VALUES (1,'Общий','','Все остальные сервисы и сайты','2010-12-18 22:37:45'),(2,'Яндекс Xml','http://xmlsearch.yandex.ru/xmlsearch','','2010-12-18 22:33:58'),(3,'Яндекс Wordstat','http://wordstat.yandex.ru/','','2010-12-18 22:33:58');
/*!40000 ALTER TABLE `st_Hosts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `st_InterfaceCountCallToday`
--

DROP TABLE IF EXISTS `st_InterfaceCountCallToday`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `st_InterfaceCountCallToday` (
  `interface_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `host_id` mediumint(8) unsigned NOT NULL,
  `count` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `last_date` time NOT NULL,
  PRIMARY KEY (`interface_id`,`host_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `st_InterfaceCountCallToday`
--

LOCK TABLES `st_InterfaceCountCallToday` WRITE;
/*!40000 ALTER TABLE `st_InterfaceCountCallToday` DISABLE KEYS */;
INSERT INTO `st_InterfaceCountCallToday` VALUES (0,1,1,'00:00:00'),(25,1,11,'00:05:49'),(135,1,11,'02:17:03'),(136,1,79,'05:41:59'),(137,1,18,'08:28:09');
/*!40000 ALTER TABLE `st_InterfaceCountCallToday` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `st_Interfaces`
--

DROP TABLE IF EXISTS `st_Interfaces`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `st_Interfaces` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `interface` varchar(64) NOT NULL DEFAULT '',
  `port` smallint(5) unsigned NOT NULL DEFAULT '80',
  `type` enum('Proxy','Usual') NOT NULL DEFAULT 'Usual',
  `proxy_type` enum('HTTP','SOCKS5') NOT NULL DEFAULT 'HTTP',
  `proxy_login` varchar(45) NOT NULL DEFAULT '',
  `proxy_password` varchar(45) NOT NULL DEFAULT '',
  `status` enum('Active','Blocked','Deleted') NOT NULL DEFAULT 'Active',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=142 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `st_Interfaces`
--

LOCK TABLES `st_Interfaces` WRITE;
/*!40000 ALTER TABLE `st_Interfaces` DISABLE KEYS */;
INSERT INTO `st_Interfaces` VALUES (25,'209.250.241.157',8080,'Proxy','HTTP','webexpert','pvofiusyf','Active','2010-11-28 10:19:31'),(39,'209.250.241.157',8080,'Proxy','HTTP','webexpert','pvofiusyf','Active','2010-11-28 12:00:32'),(40,'64.18.143.77',8080,'Proxy','HTTP','webexpert','pvofiusyf','Active','2010-11-28 12:00:32'),(41,'78.109.25.172',8080,'Proxy','HTTP','webexpert','pvofiusyf','Active','2010-11-28 12:00:32'),(42,'79.137.233.136',8080,'Proxy','HTTP','webexpert','pvofiusyf','Active','2010-11-28 12:00:32'),(43,'64.191.75.166',80,'Proxy','HTTP','webexpert','pvofiusyf','Active','2010-11-28 12:00:32'),(44,'66.197.175.66',80,'Proxy','HTTP','webexpert','pvofiusyf','Active','2010-11-28 12:00:32'),(45,'66.197.250.50',80,'Proxy','HTTP','webexpert','pvofiusyf','Active','2010-11-28 12:00:32'),(46,'66.197.220.148',80,'Proxy','HTTP','webexpert','pvofiusyf','Active','2010-11-28 12:00:32'),(47,'173.212.200.227',80,'Proxy','HTTP','webexpert','pvofiusyf','Active','2010-11-28 12:00:32'),(48,'64.120.205.76',80,'Proxy','HTTP','webexpert','pvofiusyf','Active','2010-11-28 12:00:32'),(49,'64.191.43.34',80,'Proxy','HTTP','webexpert','pvofiusyf','Active','2010-11-28 12:00:32'),(50,'173.212.211.24',80,'Proxy','HTTP','webexpert','pvofiusyf','Active','2010-11-28 12:00:32'),(51,'216.118.117.196',80,'Proxy','HTTP','webexpert','pvofiusyf','Active','2010-11-28 12:00:32'),(52,'67.196.3.150',80,'Proxy','HTTP','webexpert','pvofiusyf','Active','2010-11-28 12:00:32'),(53,'64.21.9.143',80,'Proxy','HTTP','webexpert','pvofiusyf','Active','2010-11-28 12:00:32'),(54,'66.29.68.100',80,'Proxy','HTTP','webexpert','pvofiusyf','Active','2010-11-28 12:00:32'),(55,'95.169.185.130',8080,'Proxy','HTTP','webexpert','pvofiusyf','Active','2010-11-28 12:00:32'),(56,'95.169.184.240',8080,'Proxy','HTTP','webexpert','pvofiusyf','Active','2010-11-28 12:00:32'),(57,'87.118.112.157',8080,'Proxy','HTTP','webexpert','pvofiusyf','Active','2010-11-28 12:00:32'),(58,'87.118.113.157',8080,'Proxy','HTTP','webexpert','pvofiusyf','Active','2010-11-28 12:00:32'),(59,'87.118.84.51',8080,'Proxy','HTTP','webexpert','pvofiusyf','Active','2010-11-28 12:00:32'),(60,'87.118.85.51',8080,'Proxy','HTTP','webexpert','pvofiusyf','Active','2010-11-28 12:00:32'),(61,'212.116.123.19',8080,'Proxy','HTTP','webexpert','pvofiusyf','Active','2010-11-28 12:00:32'),(62,'217.147.29.75',8080,'Proxy','HTTP','webexpert','pvofiusyf','Active','2010-11-28 12:00:32'),(63,'212.116.123.19',8080,'Proxy','HTTP','webexpert','pvofiusyf','Active','2010-11-28 12:00:32'),(64,'62.109.8.161',80,'Proxy','HTTP','webexpert','pvofiusyf','Active','2010-11-28 12:00:32'),(65,'62.109.6.178',80,'Proxy','HTTP','webexpert','pvofiusyf','Active','2010-11-28 12:00:32'),(66,'62.109.13.106',80,'Proxy','HTTP','webexpert','pvofiusyf','Active','2010-11-28 12:00:32'),(67,'62.109.17.145',80,'Proxy','HTTP','webexpert','pvofiusyf','Active','2010-11-28 12:00:32'),(68,'62.109.19.238',80,'Proxy','HTTP','webexpert','pvofiusyf','Active','2010-11-28 12:00:32'),(69,'87.249.29.29',8080,'Proxy','HTTP','webexpert','pvofiusyf','Active','2010-11-28 12:00:32'),(70,'94.198.51.138',8080,'Proxy','HTTP','webexpert','pvofiusyf','Active','2010-11-28 12:00:32'),(71,'94.198.52.205',8080,'Proxy','HTTP','webexpert','pvofiusyf','Active','2010-11-28 12:00:32'),(72,'94.198.53.14',8080,'Proxy','HTTP','webexpert','pvofiusyf','Active','2010-11-28 12:00:32'),(73,'94.198.54.194',8080,'Proxy','HTTP','webexpert','pvofiusyf','Active','2010-11-28 12:00:32'),(74,'94.103.95.171',8080,'Proxy','HTTP','webexpert','pvofiusyf','Active','2010-11-28 12:00:32'),(75,'213.155.11.153',8080,'Proxy','HTTP','webexpert','pvofiusyf','Active','2010-11-28 12:00:32'),(76,'213.155.2.84',8080,'Proxy','HTTP','webexpert','pvofiusyf','Active','2010-11-28 12:00:32'),(77,'64.120.207.228',80,'Proxy','HTTP','webexpert','pvofiusyf','Active','2010-11-28 12:00:32'),(78,'66.197.138.248',80,'Proxy','HTTP','webexpert','pvofiusyf','Active','2010-11-28 12:00:32'),(79,'89.249.22.228',80,'Usual','HTTP','','','Active','2010-11-28 18:40:55'),(80,'89.249.22.229',80,'Usual','HTTP','','','Active','2010-11-28 18:40:55'),(81,'89.249.22.230',80,'Usual','HTTP','','','Active','2010-11-28 18:40:55'),(82,'89.249.22.231',80,'Usual','HTTP','','','Active','2010-11-28 18:40:55'),(83,'89.249.22.232',80,'Usual','HTTP','','','Active','2010-11-28 18:40:55'),(84,'89.249.22.233',80,'Usual','HTTP','','','Active','2010-11-28 18:40:55'),(85,'89.249.22.234',80,'Usual','HTTP','','','Active','2010-11-28 18:40:55'),(86,'89.249.22.235',80,'Usual','HTTP','','','Active','2010-11-28 18:40:55'),(87,'89.249.22.236',80,'Usual','HTTP','','','Active','2010-11-28 18:40:55'),(88,'89.249.22.237',80,'Usual','HTTP','','','Active','2010-11-28 18:40:55'),(89,'89.249.22.238',80,'Usual','HTTP','','','Active','2010-11-28 18:40:55'),(90,'89.249.22.239',80,'Usual','HTTP','','','Active','2010-11-28 18:40:55'),(91,'89.249.22.240',80,'Usual','HTTP','','','Active','2010-11-28 18:40:55'),(92,'89.249.22.241',80,'Usual','HTTP','','','Active','2010-11-28 18:40:55'),(93,'89.249.22.242',80,'Usual','HTTP','','','Active','2010-11-28 18:40:55'),(94,'89.249.22.243',80,'Usual','HTTP','','','Active','2010-11-28 18:40:55'),(95,'89.249.22.244',80,'Usual','HTTP','','','Active','2010-11-28 18:40:55'),(96,'89.249.22.245',80,'Usual','HTTP','','','Active','2010-11-28 18:40:55'),(97,'89.249.22.246',80,'Usual','HTTP','','','Active','2010-11-28 18:40:55'),(98,'89.249.22.247',80,'Usual','HTTP','','','Active','2010-11-28 18:40:55'),(99,'89.249.22.248',80,'Usual','HTTP','','','Active','2010-11-28 18:40:55'),(100,'89.249.22.249',80,'Usual','HTTP','','','Active','2010-11-28 18:40:55'),(101,'89.249.22.250',80,'Usual','HTTP','','','Active','2010-11-28 18:40:55'),(102,'89.249.22.251',80,'Usual','HTTP','','','Active','2010-11-28 18:40:55'),(103,'89.249.22.252',80,'Usual','HTTP','','','Active','2010-11-28 18:40:55'),(104,'89.249.22.253',80,'Usual','HTTP','','','Active','2010-11-28 18:40:55'),(105,'89.249.22.254',80,'Usual','HTTP','','','Active','2010-11-28 18:40:55'),(106,'89.249.28.226',80,'Usual','HTTP','','','Active','2010-11-28 18:40:55'),(107,'89.249.28.227',80,'Usual','HTTP','','','Active','2010-11-28 18:40:55'),(108,'89.249.28.228',80,'Usual','HTTP','','','Active','2010-11-28 18:40:55'),(109,'89.249.28.229',80,'Usual','HTTP','','','Active','2010-11-28 18:40:55'),(110,'89.249.28.230',80,'Usual','HTTP','','','Active','2010-11-28 18:40:55'),(111,'89.249.28.231',80,'Usual','HTTP','','','Active','2010-11-28 18:40:55'),(112,'89.249.28.232',80,'Usual','HTTP','','','Active','2010-11-28 18:40:55'),(113,'89.249.28.233',80,'Usual','HTTP','','','Active','2010-11-28 18:40:55'),(114,'89.249.28.234',80,'Usual','HTTP','','','Active','2010-11-28 18:40:55'),(115,'89.249.28.235',80,'Usual','HTTP','','','Active','2010-11-28 18:40:55'),(116,'89.249.28.236',80,'Usual','HTTP','','','Active','2010-11-28 18:40:55'),(117,'89.249.28.237',80,'Usual','HTTP','','','Active','2010-11-28 18:40:55'),(118,'89.249.28.238',80,'Usual','HTTP','','','Active','2010-11-28 18:40:55'),(119,'89.249.28.239',80,'Usual','HTTP','','','Active','2010-11-28 18:40:55'),(120,'89.249.28.240',80,'Usual','HTTP','','','Active','2010-11-28 18:40:55'),(121,'89.249.28.241',80,'Usual','HTTP','','','Active','2010-11-28 18:40:55'),(122,'89.249.28.242',80,'Usual','HTTP','','','Active','2010-11-28 18:40:55'),(123,'89.249.28.243',80,'Usual','HTTP','','','Active','2010-11-28 18:40:55'),(124,'89.249.28.244',80,'Usual','HTTP','','','Active','2010-11-28 18:40:55'),(125,'89.249.28.245',80,'Usual','HTTP','','','Active','2010-11-28 18:40:55'),(126,'89.249.28.246',80,'Usual','HTTP','','','Active','2010-11-28 18:40:55'),(127,'89.249.28.247',80,'Usual','HTTP','','','Active','2010-11-28 18:40:55'),(128,'89.249.28.248',80,'Usual','HTTP','','','Active','2010-11-28 18:40:55'),(129,'89.249.28.249',80,'Usual','HTTP','','','Active','2010-11-28 18:40:55'),(130,'89.249.28.250',80,'Usual','HTTP','','','Active','2010-11-28 18:40:55'),(131,'89.249.28.251',80,'Usual','HTTP','','','Active','2010-11-28 18:40:55'),(132,'89.249.28.252',80,'Usual','HTTP','','','Active','2010-11-28 18:40:55'),(133,'89.249.28.253',80,'Usual','HTTP','','','Active','2010-11-28 18:40:55'),(134,'89.249.28.254',80,'Usual','HTTP','','','Active','2010-11-28 18:40:55'),(135,'178.177.38.67',80,'Usual','HTTP','','','Active','2010-11-28 18:54:42'),(136,'127.0.0.1',80,'Usual','HTTP','','','Active','2010-11-28 23:29:13'),(137,'178.177.9.4',80,'Usual','HTTP','','','Active','2010-12-13 05:15:57'),(138,'asdasd',80,'Usual','HTTP','','','Active','2010-12-16 07:14:50'),(139,'asdada',80,'Usual','HTTP','','','Active','2010-12-16 07:15:42'),(140,'asd',80,'Usual','HTTP','','','Active','2010-12-16 07:15:56'),(141,'asd',80,'Usual','HTTP','','','Active','2010-12-16 07:16:26');
/*!40000 ALTER TABLE `st_Interfaces` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `st_LimitsIpForHosts`
--

DROP TABLE IF EXISTS `st_LimitsIpForHosts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `st_LimitsIpForHosts` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `host_id` smallint(5) unsigned NOT NULL,
  `every_day` mediumint(8) unsigned NOT NULL,
  `every_hour` mediumint(8) unsigned NOT NULL,
  `every_min` mediumint(8) unsigned NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `st_LimitsIpForHosts`
--

LOCK TABLES `st_LimitsIpForHosts` WRITE;
/*!40000 ALTER TABLE `st_LimitsIpForHosts` DISABLE KEYS */;
INSERT INTO `st_LimitsIpForHosts` VALUES (1,1,300,50,10,'2010-12-18 22:36:04'),(2,2,1000,1000,10,'2010-12-18 22:36:33');
/*!40000 ALTER TABLE `st_LimitsIpForHosts` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2010-12-20  8:45:38
