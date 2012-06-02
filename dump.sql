-- MySQL dump 10.10
--
-- Host: localhost    Database: topcreator_db
-- ------------------------------------------------------
-- Server version	5.1.40-community

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES cp1251 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `tc_answers`
--

DROP TABLE IF EXISTS `tc_answers`;
CREATE TABLE `tc_answers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  `date` int(10) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `text` text NOT NULL,
  `project_ids` text NOT NULL,
  `flag_consumer_only` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tc_answers`
--


/*!40000 ALTER TABLE `tc_answers` DISABLE KEYS */;
LOCK TABLES `tc_answers` WRITE;
INSERT INTO `tc_answers` VALUES (1,1,1,1231242423,1,'текст ответа','',0);
UNLOCK TABLES;
/*!40000 ALTER TABLE `tc_answers` ENABLE KEYS */;

--
-- Table structure for table `tc_avatar_rep`
--

DROP TABLE IF EXISTS `tc_avatar_rep`;
CREATE TABLE `tc_avatar_rep` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` int(11) NOT NULL,
  `full_path` varchar(256) NOT NULL,
  `ext` varchar(4) NOT NULL,
  `original_name` varchar(256) NOT NULL,
  `size` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tc_avatar_rep`
--


/*!40000 ALTER TABLE `tc_avatar_rep` DISABLE KEYS */;
LOCK TABLES `tc_avatar_rep` WRITE;
INSERT INTO `tc_avatar_rep` VALUES (2,1337675227,'files/avatar/c8/1e/72/2.jpg','jpg','абвгд',29259);
UNLOCK TABLES;
/*!40000 ALTER TABLE `tc_avatar_rep` ENABLE KEYS */;

--
-- Table structure for table `tc_bookmarks`
--

DROP TABLE IF EXISTS `tc_bookmarks`;
CREATE TABLE `tc_bookmarks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `date` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tc_bookmarks`
--


/*!40000 ALTER TABLE `tc_bookmarks` DISABLE KEYS */;
LOCK TABLES `tc_bookmarks` WRITE;
INSERT INTO `tc_bookmarks` VALUES (1,1,4,1337606171),(9,8,1,1337863204),(8,1,24,1337678257),(7,1,5,1342567823);
UNLOCK TABLES;
/*!40000 ALTER TABLE `tc_bookmarks` ENABLE KEYS */;

--
-- Table structure for table `tc_comments`
--

DROP TABLE IF EXISTS `tc_comments`;
CREATE TABLE `tc_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` tinyint(4) NOT NULL,
  `target_id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  `text` text NOT NULL,
  `date` int(10) NOT NULL,
  `del_date` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tc_comments`
--


/*!40000 ALTER TABLE `tc_comments` DISABLE KEYS */;
LOCK TABLES `tc_comments` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `tc_comments` ENABLE KEYS */;

--
-- Table structure for table `tc_filemanager`
--

DROP TABLE IF EXISTS `tc_filemanager`;
CREATE TABLE `tc_filemanager` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `description` text NOT NULL,
  `nesting` int(4) DEFAULT '1',
  `root` varchar(256) NOT NULL,
  `allowed_ext` text NOT NULL,
  `min_file_size` int(11) DEFAULT '0',
  `max_file_size` int(11) DEFAULT '0',
  `resize` text NOT NULL,
  `min_img_size` varchar(64) NOT NULL,
  `max_img_size` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tc_filemanager`
--


/*!40000 ALTER TABLE `tc_filemanager` DISABLE KEYS */;
LOCK TABLES `tc_filemanager` WRITE;
INSERT INTO `tc_filemanager` VALUES (1,'avatar','User avatar repository',3,'files/','jpg,jpeg,png,gif',0,512000,'a:3:{s:4:\"main\";a:2:{s:1:\"w\";i:42;s:1:\"h\";i:42;}s:5:\"tmb31\";a:2:{s:1:\"w\";i:31;s:1:\"h\";i:31;}s:5:\"tmb20\";a:2:{s:1:\"w\";i:20;s:1:\"h\";i:20;}}','a:2:{s:1:\"w\";i:42;s:1:\"h\";i:42;}','N;'),(2,'projects','Repository for user project files',4,'files/','jpg,jpeg,png,gif',51200,10485760,'a:5:{s:6:\"common\";a:1:{s:1:\"w\";i:618;}s:6:\"tmb168\";a:2:{s:1:\"w\";i:168;s:1:\"h\";i:112;}s:6:\"tmb128\";a:2:{s:1:\"w\";i:128;s:1:\"h\";i:85;}s:5:\"tmb78\";a:2:{s:1:\"w\";i:78;s:1:\"h\";i:52;}s:5:\"tmb30\";a:2:{s:1:\"w\";i:30;s:1:\"h\";i:20;}}','a:1:{s:1:\"w\";s:3:\"640\";}','N;');
UNLOCK TABLES;
/*!40000 ALTER TABLE `tc_filemanager` ENABLE KEYS */;

--
-- Table structure for table `tc_friends`
--

DROP TABLE IF EXISTS `tc_friends`;
CREATE TABLE `tc_friends` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `subscriber_id` int(11) NOT NULL,
  `date` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tc_friends`
--


/*!40000 ALTER TABLE `tc_friends` DISABLE KEYS */;
LOCK TABLES `tc_friends` WRITE;
INSERT INTO `tc_friends` VALUES (16,1,8,1337863305),(15,8,1,1337789764);
UNLOCK TABLES;
/*!40000 ALTER TABLE `tc_friends` ENABLE KEYS */;

--
-- Table structure for table `tc_likes`
--

DROP TABLE IF EXISTS `tc_likes`;
CREATE TABLE `tc_likes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `date` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tc_likes`
--


/*!40000 ALTER TABLE `tc_likes` DISABLE KEYS */;
LOCK TABLES `tc_likes` WRITE;
INSERT INTO `tc_likes` VALUES (4,1,3,1337676929),(3,1,4,1337676888),(10,1,24,1337790285),(6,1,17,1337678503),(7,1,18,1337767382),(8,1,8,1337771661),(9,1,1,1337779172),(11,8,24,1337863571),(12,8,27,1337864630),(14,8,19,1337865947);
UNLOCK TABLES;
/*!40000 ALTER TABLE `tc_likes` ENABLE KEYS */;

/*!50003 SET @OLD_SQL_MODE=@@SQL_MODE*/;
DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `like_increment` AFTER INSERT ON `tc_likes` FOR EACH ROW BEGIN
  UPDATE  `tc_projects` SET  `like_count` =  `like_count` +1 WHERE  `id` = NEW.`project_id` ;
END */;;

/*!50003 SET SESSION SQL_MODE="" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `like_decrement` BEFORE DELETE ON `tc_likes` FOR EACH ROW BEGIN
  UPDATE  `tc_projects` SET  `like_count` =  `like_count` -1 WHERE  `id` = OLD.`project_id` ;
END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@OLD_SQL_MODE */;

--
-- Table structure for table `tc_messages`
--

DROP TABLE IF EXISTS `tc_messages`;
CREATE TABLE `tc_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `date` int(10) NOT NULL,
  `read` tinyint(1) NOT NULL,
  `subject` varchar(256) NOT NULL,
  `msg` text NOT NULL,
  `author_del_date` int(10) NOT NULL DEFAULT '0',
  `receiver_del_date` int(10) NOT NULL DEFAULT '0',
  `file_ids` text NOT NULL,
  `mailing_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tc_messages`
--


/*!40000 ALTER TABLE `tc_messages` DISABLE KEYS */;
LOCK TABLES `tc_messages` WRITE;
INSERT INTO `tc_messages` VALUES (2,1,8,1324556789,1,'','сообщение1',1337861486,0,'',0),(4,1,8,1324534563,1,'','сообщение3',1337861491,0,'',0),(6,1,8,0,0,'','сообщение5',0,0,'',0),(8,8,1,0,0,'','Если указать ключевое слово ALL, то результат будет содержать все найденные строки из всех примененных команд SELECT. ',0,0,'',0),(11,8,1,0,0,'','сообщение обратно 4',0,0,'',0),(12,8,1,0,1,'','сообщение обратно 5',0,0,'',0),(13,8,1,1324556789,0,'','сообщение обратно 6',0,1337860972,'',0),(24,1,8,1337861505,0,' ','еще новое сообщение',1337861617,0,'',0),(23,1,8,1337861501,0,' ','новое сообщение',1337861614,0,'',0),(18,1,8,1337858064,0,' ','сообщение',1337861418,0,'',0),(19,1,8,1337858071,0,' ','еще сообщение\n',1337861416,0,'',0),(20,1,8,1337858600,0,' ','третье сообщение',1337861405,0,'',0),(21,1,8,1337858651,0,' ','еще сообщение',1337861489,0,'',0),(22,1,8,1337859235,0,' ','пятое сообщение подряд',1337861616,0,'',0),(25,1,8,1337861611,0,' ','еще сообщение',1337861935,0,'',0),(26,1,8,1337861867,0,' ','новое сообщение',1337861933,0,'',0),(27,1,8,1337861907,0,' ','еще сообщение',1337861941,0,'',0),(28,1,8,1337861918,0,' ','получился почти чат',0,0,'',0),(29,1,8,1337862003,0,' ','еще сообщение',0,0,'',0),(30,1,8,1337862028,0,' ','сообщение',0,0,'',0),(31,1,8,1337862435,0,' ','ndfgkbdet',0,0,'',0),(32,8,1,1337863981,0,'тема','сообщение',0,0,'',0),(33,8,1,1337864008,0,'лджлджл','лджлдж',0,0,'',0);
UNLOCK TABLES;
/*!40000 ALTER TABLE `tc_messages` ENABLE KEYS */;

--
-- Table structure for table `tc_projects`
--

DROP TABLE IF EXISTS `tc_projects`;
CREATE TABLE `tc_projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_id` int(11) NOT NULL,
  `file_path` varchar(256) NOT NULL,
  `author_id` int(11) NOT NULL,
  `ready` tinyint(1) NOT NULL,
  `date` int(10) NOT NULL,
  `moderated` tinyint(1) NOT NULL,
  `date_moderated` int(10) NOT NULL,
  `moderator_id` int(11) NOT NULL,
  `title` varchar(256) NOT NULL,
  `description` text NOT NULL,
  `category` tinyint(3) NOT NULL,
  `year` int(4) NOT NULL,
  `like_count` int(11) NOT NULL,
  `view_count` int(11) NOT NULL,
  `comment_count` int(11) NOT NULL,
  `popularity` float NOT NULL,
  `can_watch_full` tinyint(1) NOT NULL,
  `can_watch` tinyint(1) NOT NULL,
  `can_comment` tinyint(1) NOT NULL,
  `license_type` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tc_projects`
--


/*!40000 ALTER TABLE `tc_projects` DISABLE KEYS */;
LOCK TABLES `tc_projects` WRITE;
INSERT INTO `tc_projects` VALUES (1,1,'files/projects/c4/ca/42/38/1.jpg',1,1,1337596432,0,0,0,'Пустыня','описание пустыни',0,2008,1,4,0,0,3,1,2,7),(2,2,'files/projects/c8/1e/72/8d/2.jpg',1,1,1337599491,0,0,0,'Маяк','',0,2012,0,15,0,0,3,1,2,7),(3,3,'files/projects/ec/cb/c8/7e/3.jpg',1,1,1337599493,0,0,0,'Пингвины','',0,2012,1,3,0,0,3,1,2,7),(4,4,'files/projects/a8/7f/f6/79/4.jpg',1,1,1337602419,0,0,0,'Коала','',0,2012,1,34,0,123,3,1,2,7),(5,5,'files/projects/e4/da/3b/7f/5.jpg',1,1,1337602420,0,0,0,'Тюльпаны','',0,2012,0,26,0,0,3,1,2,7),(6,7,'files/projects/8f/14/e4/5f/7.jpg',1,0,1337639673,0,0,0,'','',0,0,0,8,0,0,3,1,2,7),(7,8,'files/projects/c9/f0/f8/95/8.jpg',1,0,1337639689,0,0,0,'','',0,0,0,3,0,0,3,1,2,7),(8,9,'files/projects/45/c4/8c/ce/9.jpg',1,1,1337669075,0,0,0,'Маяк','',0,2009,1,4,0,0,0,0,0,0),(9,12,'files/projects/c2/0a/d4/d7/12.jpg',1,0,1337669555,0,0,0,'','',0,0,0,2,0,0,0,0,0,0),(10,15,'files/projects/9b/f3/1c/7f/15.jpg',1,0,1337669666,0,0,0,'','',0,0,0,3,0,0,0,0,0,0),(11,20,'files/projects/98/f1/37/08/20.jpg',1,0,1337671459,0,0,0,'','',0,0,0,2,0,0,0,0,0,0),(12,23,'files/projects/37/69/3c/fc/23.jpg',1,0,1337672201,0,0,0,'','',0,0,0,2,0,0,0,0,0,0),(13,24,'files/projects/1f/f1/de/77/24.jpg',1,0,1337672431,0,0,0,'','',0,0,0,1,0,0,0,0,0,0),(14,25,'files/projects/8e/29/6a/06/25.jpg',1,0,1337672575,0,0,0,'','',0,0,0,2,0,0,0,0,0,0),(15,27,'files/projects/02/e7/4f/10/27.jpg',1,0,1337673008,0,0,0,'','',0,0,0,2,0,0,0,0,0,0),(16,29,'files/projects/6e/a9/ab/1b/29.jpg',1,0,1337673139,0,0,0,'','',0,0,0,2,0,0,0,0,0,0),(17,30,'files/projects/34/17/3c/b3/30.jpg',1,0,1337673160,0,0,0,'','',0,0,1,4,0,0,0,0,0,0),(18,31,'files/projects/c1/6a/53/20/31.jpg',1,0,1337673185,0,0,0,'','',0,0,1,12,0,0,0,0,0,0),(19,32,'files/projects/63/64/d3/f0/32.jpg',1,0,1337673221,0,0,0,'','',0,0,1,7,0,0,0,0,0,0),(24,44,'files/projects/f7/17/71/63/44.jpg',8,1,1337678232,0,0,0,'цветок','',0,2012,2,21,0,0,0,0,0,0),(25,45,'files/projects/6c/83/49/cc/45.jpg',1,0,1337858319,0,0,0,'','',0,0,0,0,0,0,0,0,0,0),(26,46,'files/projects/d9/d4/f4/95/46.jpg',1,0,1337858321,0,0,0,'','',0,0,0,0,0,0,0,0,0,0),(27,47,'files/projects/67/c6/a1/e7/47.jpg',8,1,1337864526,0,0,0,'Маяк','описание',0,2008,1,9,0,0,0,0,0,0);
UNLOCK TABLES;
/*!40000 ALTER TABLE `tc_projects` ENABLE KEYS */;

--
-- Table structure for table `tc_projects_rep`
--

DROP TABLE IF EXISTS `tc_projects_rep`;
CREATE TABLE `tc_projects_rep` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` int(11) NOT NULL,
  `full_path` varchar(256) NOT NULL,
  `ext` varchar(4) NOT NULL,
  `original_name` varchar(256) NOT NULL,
  `size` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=48 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tc_projects_rep`
--


/*!40000 ALTER TABLE `tc_projects_rep` DISABLE KEYS */;
LOCK TABLES `tc_projects_rep` WRITE;
INSERT INTO `tc_projects_rep` VALUES (1,1337596432,'files/projects/c4/ca/42/38/1.jpg','jpg','Desert',845941),(2,1337599491,'files/projects/c8/1e/72/8d/2.jpg','jpg','Lighthouse',561276),(3,1337599493,'files/projects/ec/cb/c8/7e/3.jpg','jpg','Penguins',777835),(4,1337602419,'files/projects/a8/7f/f6/79/4.jpg','jpg','Koala',780831),(5,1337602420,'files/projects/e4/da/3b/7f/5.jpg','jpg','Tulips',620888),(6,1337639580,'files/projects/16/79/09/1c/6.jpg','jpg','Chrysanthemum',879394),(7,1337639673,'files/projects/8f/14/e4/5f/7.jpg','jpg','Tulips',620888),(8,1337639689,'files/projects/c9/f0/f8/95/8.jpg','jpg','Chrysanthemum',879394),(9,1337669075,'files/projects/45/c4/8c/ce/9.jpg','jpg','Lighthouse',561276),(10,1337669416,'files/projects/d3/d9/44/68/10.jpg','jpg','Penguins',777835),(11,1337669509,'files/projects/65/12/bd/43/11.jpg','jpg','Hydrangeas',595284),(12,1337669555,'files/projects/c2/0a/d4/d7/12.jpg','jpg','Koala',780831),(13,1337669568,'files/projects/c5/1c/e4/10/13.jpg','jpg','Chrysanthemum',879394),(14,1337669600,'files/projects/aa/b3/23/89/14.jpg','jpg','Penguins',777835),(15,1337669666,'files/projects/9b/f3/1c/7f/15.jpg','jpg','Chrysanthemum',879394),(16,1337669892,'files/projects/c7/4d/97/b0/16.jpg','jpg','Lighthouse',561276),(17,1337671283,'files/projects/70/ef/df/2e/17.jpg','jpg','Lighthouse',561276),(18,1337671344,'files/projects/6f/49/22/f4/18.jpg','jpg','Penguins',777835),(19,1337671370,'files/projects/1f/0e/3d/ad/19.jpg','jpg','Lighthouse',561276),(20,1337671459,'files/projects/98/f1/37/08/20.jpg','jpg','Hydrangeas',595284),(21,1337671638,'files/projects/3c/59/dc/04/21.jpg','jpg','Penguins',777835),(22,1337671656,'files/projects/b6/d7/67/d2/22.jpg','jpg','Lighthouse',561276),(23,1337672201,'files/projects/37/69/3c/fc/23.jpg','jpg','Tulips',620888),(24,1337672431,'files/projects/1f/f1/de/77/24.jpg','jpg','Tulips',620888),(25,1337672575,'files/projects/8e/29/6a/06/25.jpg','jpg','Jellyfish',775702),(26,1337672645,'files/projects/4e/73/2c/ed/26.jpg','jpg','Penguins',777835),(27,1337673008,'files/projects/02/e7/4f/10/27.jpg','jpg','Tulips',620888),(28,1337673076,'files/projects/33/e7/5f/f0/28.jpg','jpg','Hydrangeas',595284),(29,1337673139,'files/projects/6e/a9/ab/1b/29.jpg','jpg','Tulips',620888),(30,1337673160,'files/projects/34/17/3c/b3/30.jpg','jpg','Lighthouse',561276),(31,1337673185,'files/projects/c1/6a/53/20/31.jpg','jpg','Penguins',777835),(32,1337673221,'files/projects/63/64/d3/f0/32.jpg','jpg','Tulips',620888),(33,1337673250,'files/projects/18/2b/e0/c5/33.jpg','jpg','Penguins',777835),(34,1337673317,'files/projects/e3/69/85/3d/34.jpg','jpg','Lighthouse',561276),(35,1337673419,'files/projects/1c/38/3c/d3/35.jpg','jpg','Chrysanthemum',879394),(36,1337674292,'files/projects/19/ca/14/e7/36.jpg','jpg','Lighthouse',561276),(37,1337674364,'files/projects/a5/bf/c9/e0/37.jpg','jpg','Lighthouse',561276),(38,1337674406,'files/projects/a5/77/1b/ce/38.jpg','jpg','Koala',780831),(39,1337674484,'files/projects/d6/7d/8a/b4/39.jpg','jpg','Lighthouse',561276),(46,1337858321,'files/projects/d9/d4/f4/95/46.jpg','jpg','Hydrangeas',595284),(44,1337678232,'files/projects/f7/17/71/63/44.jpg','jpg','Chrysanthemum',879394),(45,1337858319,'files/projects/6c/83/49/cc/45.jpg','jpg','Desert',845941),(47,1337864526,'files/projects/67/c6/a1/e7/47.jpg','jpg','Lighthouse',561276);
UNLOCK TABLES;
/*!40000 ALTER TABLE `tc_projects_rep` ENABLE KEYS */;

--
-- Table structure for table `tc_specializations`
--

DROP TABLE IF EXISTS `tc_specializations`;
CREATE TABLE `tc_specializations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `specialization_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=66 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tc_specializations`
--


/*!40000 ALTER TABLE `tc_specializations` DISABLE KEYS */;
LOCK TABLES `tc_specializations` WRITE;
INSERT INTO `tc_specializations` VALUES (62,8,1),(65,8,4),(58,1,3),(63,8,2),(61,1,7),(60,1,6),(59,1,4),(49,1,1),(64,8,3);
UNLOCK TABLES;
/*!40000 ALTER TABLE `tc_specializations` ENABLE KEYS */;

--
-- Table structure for table `tc_user_news`
--

DROP TABLE IF EXISTS `tc_user_news`;
CREATE TABLE `tc_user_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `source_id` int(11) NOT NULL,
  `event` text NOT NULL,
  `date` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tc_user_news`
--


/*!40000 ALTER TABLE `tc_user_news` DISABLE KEYS */;
LOCK TABLES `tc_user_news` WRITE;
UNLOCK TABLES;
/*!40000 ALTER TABLE `tc_user_news` ENABLE KEYS */;

--
-- Table structure for table `tc_users`
--

DROP TABLE IF EXISTS `tc_users`;
CREATE TABLE `tc_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `online` tinyint(1) NOT NULL DEFAULT '0',
  `account_state` tinyint(1) NOT NULL,
  `user_type` tinyint(1) NOT NULL,
  `email` varchar(128) NOT NULL,
  `nickname` varchar(20) NOT NULL,
  `password` varchar(32) NOT NULL,
  `name` varchar(64) NOT NULL,
  `surname` varchar(64) NOT NULL,
  `pro` tinyint(1) NOT NULL DEFAULT '0',
  `balance` double NOT NULL,
  `country` int(3) NOT NULL,
  `city` int(5) NOT NULL,
  `site` varchar(256) NOT NULL,
  `about` text NOT NULL,
  `avatar_img` varchar(64) NOT NULL,
  `alias` varchar(20) NOT NULL,
  `specialization` varchar(256) NOT NULL,
  `notice_message` tinyint(1) NOT NULL,
  `notice_friend_projects` tinyint(1) NOT NULL,
  `notice_comments` tinyint(1) NOT NULL,
  `can_watch_full` tinyint(1) NOT NULL,
  `can_watch` tinyint(1) NOT NULL,
  `can_comment` tinyint(1) NOT NULL,
  `license_type` tinyint(1) NOT NULL,
  `friends_count` int(11) NOT NULL,
  `subscribers_count` int(11) NOT NULL,
  `projects_count` int(11) NOT NULL,
  `bookmarks_count` int(11) NOT NULL,
  `relations_count` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tc_users`
--


/*!40000 ALTER TABLE `tc_users` DISABLE KEYS */;
LOCK TABLES `tc_users` WRITE;
INSERT INTO `tc_users` VALUES (1,1,0,1,'joe_11@mail.ru','maximum','5e38c2509a374d0eafe6445385f4087d','Илья','Иванов',0,0,1,1,'http://www.maximum.ru','пара слов обо мне','files/avatar/c8/1e/72/2.jpg','maximum','1,3,4,6,7',1,3,2,3,1,2,7,0,0,0,0,0),(8,1,0,1,'dark.storoj@gmail.com','storoj','5e38c2509a374d0eafe6445385f4087d','Илья','Яковченко',0,0,1,1,'http://www.maximum.ru','пара слов обо мне','','storoj','1,2,3,4',0,0,0,0,0,0,0,0,0,0,0,0),(9,1,0,2,'employer@mail.ru','employer','5e38c2509a374d0eafe6445385f4087d','','',0,0,0,0,'','','','','',0,0,0,0,0,0,0,0,0,0,0,0);
UNLOCK TABLES;
/*!40000 ALTER TABLE `tc_users` ENABLE KEYS */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

