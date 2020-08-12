-- MySQL dump 10.13  Distrib 5.7.21, for Linux (x86_64)
--
-- Host: localhost    Database: feddb
-- ------------------------------------------------------
-- Server version	5.7.21-0ubuntu0.16.04.1

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
-- Table structure for table `secrets`
--

DROP TABLE IF EXISTS `secrets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `secrets` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Server` varchar(255) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Owner` varchar(255) NOT NULL,
  `PERMITTED` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=54 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `secrets`
--

LOCK TABLES `secrets` WRITE;
/*!40000 ALTER TABLE `secrets` DISABLE KEYS */;
INSERT INTO `secrets` VALUES (1,'229.24.91.121','root','toor','USER',''),(2,'225.235.27.42','Cyllosis','corvette','USER',''),(3,'97.165.137.235','Quangocracy','bigdog','USER',''),(4,'233.20.10.219','Conative','cheese','USER',''),(5,'167.136.64.191','Hebetic','matthew','USER',''),(6,'185.73.33.10','Luminous','Nox','ADMIN',''),(7,'173.96.12.108','OrthopBuddle','patrick','USER',''),(8,'137.24.15.93','Whitherward','martin','USER',''),(9,'154.207.97.73','ChristaHamose','freedom','ADMIN',''),(10,'70.108.230.206','Hypnogeny','ginger','USER',''),(11,'169.36.89.252','Xenomorphic','blowjob','USER',''),(12,'142.159.96.5','Abattoir','nicole','USER',''),(13,'156.213.115.170','Sempect','sparky','ADMIN',''),(14,'126.37.107.168','Thelematic','yellow','USER',''),(15,'149.120.162.47','Recaption','camaro','USER',''),(16,'142.198.117.186','Estuary','secret','USER',''),(17,'135.112.25.229','Somatogenic','dick','USER',''),(18,'139.161.188.114','Redound','falcon','USER',''),(19,'44.187.133.144','Labiomancy','taylor','USER',''),(20,'164.229.49.173','Essssegobb','VHLS84RYTH2YMBsB','USER',''),(21,'169.189.149.228','Deloored43','Hb848xJpAT4L9xQA','USER',''),(22,'96.230.255.213','Pneumatosis','eA2j354HHX2ZWGAc','USER',''),(23,'235.237.118.176','Mvuleluit','bitch','USER',''),(24,'202.84.14.166','Delphically','hello','USER',''),(25,'69.43.216.67','Melanochroic','scooter','ADMIN',''),(26,'142.57.89.114','Tachometer','please','USER',''),(27,'94.6.55.124','Aneuria','porsche','USER',''),(28,'27.179.26.49','Quirenion777','guitar','ADMIN',''),(29,'114.10.123.119','Prepossess','chelsea','USER',''),(30,'212.89.144.215','Plumbless','black','USER','');
/*!40000 ALTER TABLE `secrets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `USERNAME` varchar(255) NOT NULL,
  `PASSWORD` varchar(255) NOT NULL,
  `ROLE` varchar(255) NOT NULL,
  `APITOKEN` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `USERNAME` (`USERNAME`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','2b6d315337f18617ba18922c0b9597ff','ADMIN','YWRtaW4=');
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

-- Dump completed on 2018-02-12 16:43:54
