-- MySQL dump 10.13  Distrib 5.5.62, for Win64 (AMD64)
--
-- Host: localhost    Database: quiz
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.18-MariaDB

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
-- Table structure for table `question_results`
--

DROP TABLE IF EXISTS `question_results`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `question_results` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question_id` int(11) DEFAULT NULL,
  `question_answer` text DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `question_answer_is_deleted` tinyint(1) DEFAULT 0,
  `question_answer_created_at` timestamp NULL DEFAULT NULL,
  `question_set_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=111 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `question_results`
--

LOCK TABLES `question_results` WRITE;
/*!40000 ALTER TABLE `question_results` DISABLE KEYS */;
INSERT INTO `question_results` VALUES (101,13,'asasdasd',1,1,'2022-09-03 20:41:00',3),(102,12,'asdasdasd',1,1,'2022-09-03 20:41:00',3),(103,10,'asdasdasd',1,1,'2022-09-03 20:41:00',3),(104,11,'asdasd',1,1,'2022-09-03 20:41:00',3),(105,9,'asdasdasd',1,1,'2022-09-03 20:41:00',3),(106,11,'asdasd',1,0,'2022-09-03 20:45:00',3),(107,12,'asdasda',1,0,'2022-09-03 20:45:00',3),(108,13,'asdasdasd',1,0,'2022-09-03 20:45:00',3),(109,9,'asdasdasd',1,0,'2022-09-03 20:46:00',3),(110,10,'asdasdasd',1,0,'2022-09-03 20:46:00',3);
/*!40000 ALTER TABLE `question_results` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `questions`
--

DROP TABLE IF EXISTS `questions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question_set_id` int(11) DEFAULT NULL,
  `question_text` text DEFAULT NULL,
  `question_is_deleted` tinyint(1) DEFAULT NULL,
  `question_created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `questions`
--

LOCK TABLES `questions` WRITE;
/*!40000 ALTER TABLE `questions` DISABLE KEYS */;
INSERT INTO `questions` VALUES (9,3,'What is your name1?',0,NULL),(10,3,'What is your name2?',0,NULL),(11,3,'What is your name3?',0,NULL),(12,3,'What is your name4?',0,NULL),(13,3,'What is your name5?',0,NULL);
/*!40000 ALTER TABLE `questions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `questions_set`
--

DROP TABLE IF EXISTS `questions_set`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `questions_set` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question_set_name` varchar(200) DEFAULT NULL,
  `question_set_category` varchar(100) DEFAULT NULL,
  `question_set_is_deleted` tinyint(1) DEFAULT NULL,
  `question_set_created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `questions_set`
--

LOCK TABLES `questions_set` WRITE;
/*!40000 ALTER TABLE `questions_set` DISABLE KEYS */;
INSERT INTO `questions_set` VALUES (3,'set 1','1',0,NULL),(4,'set 2','2',0,NULL);
/*!40000 ALTER TABLE `questions_set` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'quiz'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-09-04 16:51:14
