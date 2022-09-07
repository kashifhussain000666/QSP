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
) ENGINE=InnoDB AUTO_INCREMENT=184 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `question_results`
--

LOCK TABLES `question_results` WRITE;
/*!40000 ALTER TABLE `question_results` DISABLE KEYS */;
INSERT INTO `question_results` VALUES (172,30,'Answer 3',1,0,'2022-09-05 05:52:00',12),(173,29,'Answer 2',1,0,'2022-09-05 05:52:00',12),(174,31,'Answer 4',1,0,'2022-09-05 05:53:00',12),(175,28,'Answer 1',1,0,'2022-09-05 05:53:00',12),(176,32,'Answer 1',1,0,'2022-09-05 05:53:00',13),(177,34,'Answer 3',1,0,'2022-09-05 05:53:00',13),(178,33,'Answer 2',1,0,'2022-09-05 05:53:00',13),(179,37,'Answer 3',1,1,'2022-09-05 05:56:00',14),(180,36,'Answer 2',1,1,'2022-09-05 05:56:00',14),(181,39,'Answer 5',1,1,'2022-09-05 05:57:00',14),(182,38,'Answer 4',1,1,'2022-09-05 05:57:00',14),(183,35,'Answer 1',1,1,'2022-09-05 05:57:00',14);
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
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `questions`
--

LOCK TABLES `questions` WRITE;
/*!40000 ALTER TABLE `questions` DISABLE KEYS */;
INSERT INTO `questions` VALUES (28,12,'Question 1?',0,'2022-09-05 05:45:00'),(29,12,'Question 2?',0,'2022-09-05 05:45:00'),(30,12,'Question 3?',0,'2022-09-05 05:45:00'),(31,12,'Question 4?',0,'2022-09-05 05:45:00'),(32,13,'Question 1?',0,'2022-09-05 05:45:00'),(33,13,'Question 2?',0,'2022-09-05 05:45:00'),(34,13,'Question 3?',0,'2022-09-05 05:45:00'),(35,14,'Question 1?',0,'2022-09-05 05:45:00'),(36,14,'Question 2?',0,'2022-09-05 05:46:00'),(37,14,'Question 3?',0,'2022-09-05 05:46:00'),(38,14,'Question 4?',0,'2022-09-05 05:46:00'),(39,14,'Question 5?',0,'2022-09-05 05:46:00');
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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `questions_set`
--

LOCK TABLES `questions_set` WRITE;
/*!40000 ALTER TABLE `questions_set` DISABLE KEYS */;
INSERT INTO `questions_set` VALUES (12,'Question Set 1','2',0,'2022-09-05 05:44:00'),(13,'Question Set 2','2',0,'2022-09-05 05:44:00'),(14,'Question Set 3','1',0,'2022-09-05 05:44:00');
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

-- Dump completed on 2022-09-06  1:57:37
