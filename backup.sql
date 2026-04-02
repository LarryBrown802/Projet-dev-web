-- MySQL dump 10.13  Distrib 8.0.45, for Linux (x86_64)
--
-- Host: localhost    Database: lems
-- ------------------------------------------------------
-- Server version	8.0.45-0ubuntu0.24.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `Apply`
--

DROP TABLE IF EXISTS `Apply`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Apply` (
  `ID_offer` int NOT NULL,
  `ID_profile` int NOT NULL,
  `cv` text,
  `motivation_letter` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  PRIMARY KEY (`ID_offer`,`ID_profile`),
  KEY `ID_profile` (`ID_profile`),
  CONSTRAINT `Apply_ibfk_1` FOREIGN KEY (`ID_offer`) REFERENCES `Offer` (`ID_offer`),
  CONSTRAINT `Apply_ibfk_2` FOREIGN KEY (`ID_profile`) REFERENCES `Profile` (`ID_profile`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Apply`
--

LOCK TABLES `Apply` WRITE;
/*!40000 ALTER TABLE `Apply` DISABLE KEYS */;
INSERT INTO `Apply` VALUES (17,19,'1774945730_Dossier_Synthese_Samuel_VEREL_CPI_A2_Info_25_26_Rouen_Semestre_3__29_.PDF',''),(17,20,'1775028491_CV_stage_Samuel_Verel.pdf','');
/*!40000 ALTER TABLE `Apply` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Center`
--

DROP TABLE IF EXISTS `Center`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Center` (
  `ID_center` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`ID_center`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Center`
--

LOCK TABLES `Center` WRITE;
/*!40000 ALTER TABLE `Center` DISABLE KEYS */;
INSERT INTO `Center` VALUES (1,'Strasbourg'),(2,'Lyon'),(3,'Paris');
/*!40000 ALTER TABLE `Center` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Company`
--

DROP TABLE IF EXISTS `Company`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Company` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `description` text,
  `email` varchar(100) DEFAULT NULL,
  `number` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `average_mark` float DEFAULT NULL,
  `ID_user` int DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `ID_utilisateur` (`ID_user`),
  CONSTRAINT `Company_ibfk_1` FOREIGN KEY (`ID_user`) REFERENCES `User` (`ID_user`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Company`
--

LOCK TABLES `Company` WRITE;
/*!40000 ALTER TABLE `Company` DISABLE KEYS */;
INSERT INTO `Company` VALUES (14,'Tech Solutions','Entreprise spécialisée dans le développement web et mobile','contact@techsolutions.fr','01 23 45 67 89',4,NULL),(15,'CESI','ZETRH','CESI@cesi','18514851485',NULL,NULL);
/*!40000 ALTER TABLE `Company` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Location`
--

DROP TABLE IF EXISTS `Location`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Location` (
  `ID_location` int NOT NULL AUTO_INCREMENT,
  `street_number` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `street_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `postcode` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `city` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`ID_location`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Location`
--

LOCK TABLES `Location` WRITE;
/*!40000 ALTER TABLE `Location` DISABLE KEYS */;
INSERT INTO `Location` VALUES (10,NULL,NULL,NULL,'Paris'),(11,NULL,NULL,NULL,'Dublin');
/*!40000 ALTER TABLE `Location` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Note`
--

DROP TABLE IF EXISTS `Note`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Note` (
  `ID_user` int NOT NULL,
  `ID_company` int NOT NULL,
  PRIMARY KEY (`ID_user`,`ID_company`),
  KEY `ID` (`ID_company`),
  CONSTRAINT `Note_ibfk_1` FOREIGN KEY (`ID_user`) REFERENCES `User` (`ID_user`),
  CONSTRAINT `Note_ibfk_2` FOREIGN KEY (`ID_company`) REFERENCES `Company` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Note`
--

LOCK TABLES `Note` WRITE;
/*!40000 ALTER TABLE `Note` DISABLE KEYS */;
/*!40000 ALTER TABLE `Note` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Offer`
--

DROP TABLE IF EXISTS `Offer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Offer` (
  `ID_offer` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `description` text,
  `duration` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `remuneration` float DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `level` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `domain` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `publication_date` date DEFAULT NULL,
  `ID_location` int DEFAULT NULL,
  `ID_company` int DEFAULT NULL,
  PRIMARY KEY (`ID_offer`),
  KEY `ID_lieu` (`ID_location`),
  KEY `ID` (`ID_company`),
  CONSTRAINT `Offer_ibfk_1` FOREIGN KEY (`ID_location`) REFERENCES `Location` (`ID_location`),
  CONSTRAINT `Offer_ibfk_2` FOREIGN KEY (`ID_company`) REFERENCES `Company` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Offer`
--

LOCK TABLES `Offer` WRITE;
/*!40000 ALTER TABLE `Offer` DISABLE KEYS */;
INSERT INTO `Offer` VALUES (17,'Développeur Web','Création de site web','3 mois',900,'Stage','Bac+2/3','Développement','2026-03-28',10,14),(18,'Developpeur full stack f/m','Front-End et backEnd du site de stage','3 mois',1400,'Alternance','Bac+5','Développement','1111-11-11',11,14);
/*!40000 ALTER TABLE `Offer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Profile`
--

DROP TABLE IF EXISTS `Profile`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Profile` (
  `ID_profile` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `surname` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `ID_user` int DEFAULT NULL,
  `ID_promotion` int DEFAULT NULL,
  `status` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'wait',
  PRIMARY KEY (`ID_profile`),
  KEY `ID_utilisateur` (`ID_user`),
  KEY `Profile_ibfk_2` (`ID_promotion`),
  CONSTRAINT `Profile_ibfk_1` FOREIGN KEY (`ID_user`) REFERENCES `User` (`ID_user`),
  CONSTRAINT `Profile_ibfk_2` FOREIGN KEY (`ID_promotion`) REFERENCES `Promotion` (`ID_promotion`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Profile`
--

LOCK TABLES `Profile` WRITE;
/*!40000 ALTER TABLE `Profile` DISABLE KEYS */;
INSERT INTO `Profile` VALUES (7,'Roussel','Antoine',4,1,'wait'),(9,'Tout','Firas',6,3,'wait'),(16,'Martin','Aurélien',13,10,'wait'),(18,'Fontaine','Pierre',15,1,'wait'),(19,'Verel','Samuel',3,1,'ok'),(20,'Linard','Raphael',16,1,'wait'),(21,'Boulenger','Mathis',17,1,'wait'),(22,'Benoît','Jeanne',18,2,'wait'),(24,'pilote','pilote',20,1,'wait');
/*!40000 ALTER TABLE `Profile` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Promotion`
--

DROP TABLE IF EXISTS `Promotion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Promotion` (
  `ID_promotion` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`ID_promotion`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Promotion`
--

LOCK TABLES `Promotion` WRITE;
/*!40000 ALTER TABLE `Promotion` DISABLE KEYS */;
INSERT INTO `Promotion` VALUES (1,'CPI A2 Informatique'),(2,'CPI A2 Généraliste'),(3,'CPI A2 BTP'),(10,'FISE A3 Informatique');
/*!40000 ALTER TABLE `Promotion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Role`
--

DROP TABLE IF EXISTS `Role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Role` (
  `ID_role` int NOT NULL AUTO_INCREMENT,
  `name_role` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`ID_role`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Role`
--

LOCK TABLES `Role` WRITE;
/*!40000 ALTER TABLE `Role` DISABLE KEYS */;
INSERT INTO `Role` VALUES (1,'administrateur'),(2,'pilote'),(3,'etudiant');
/*!40000 ALTER TABLE `Role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Save_wishlist`
--

DROP TABLE IF EXISTS `Save_wishlist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Save_wishlist` (
  `ID_offer` int NOT NULL,
  `ID_profile` int NOT NULL,
  PRIMARY KEY (`ID_offer`,`ID_profile`),
  KEY `ID_profile` (`ID_profile`),
  CONSTRAINT `Save_wishlist_ibfk_1` FOREIGN KEY (`ID_offer`) REFERENCES `Offer` (`ID_offer`),
  CONSTRAINT `Save_wishlist_ibfk_2` FOREIGN KEY (`ID_profile`) REFERENCES `Profile` (`ID_profile`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Save_wishlist`
--

LOCK TABLES `Save_wishlist` WRITE;
/*!40000 ALTER TABLE `Save_wishlist` DISABLE KEYS */;
INSERT INTO `Save_wishlist` VALUES (17,19),(17,21);
/*!40000 ALTER TABLE `Save_wishlist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `User`
--

DROP TABLE IF EXISTS `User`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `User` (
  `ID_user` int NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `ID_role` int DEFAULT NULL,
  `remember_token` varchar(64) DEFAULT NULL,
  `remember_token_expiry` datetime DEFAULT NULL,
  PRIMARY KEY (`ID_user`),
  UNIQUE KEY `email` (`email`),
  KEY `ID_role` (`ID_role`),
  CONSTRAINT `User_ibfk_1` FOREIGN KEY (`ID_role`) REFERENCES `Role` (`ID_role`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `User`
--

LOCK TABLES `User` WRITE;
/*!40000 ALTER TABLE `User` DISABLE KEYS */;
INSERT INTO `User` VALUES (1,'admin1@test.com','$2y$10$ChgKtSzX8Wu.Le7HK4sGGuuvPu1OxOdKvKa9RWXf3Uo3gwyGdM5DO',1,'48b6d1948a36980326ee9b299d4719f7ff23c35523f2b7b52ad22cefeee36fbe','2026-05-01 13:18:13'),(3,'samuel.verel@viacesi.fr','$2y$10$Sl7pyEhhX/IZpzLVrFIL1.kHj4JvAVKBekYse6Wtb9rEvJxPZaRHO',3,NULL,NULL),(4,'antoine.roussel@cesi.fr','$2y$10$a2SkQtALWs5Q42fj68WLfOvjuX5qBu8H6jhYZWY7GcxVPuttOmKES',2,NULL,NULL),(6,'firas.tout@cesi.fr','$2y$10$4r3R2Fhxa/Rj5Hk2BLaVmenB4dElTfF516fkwm20RxGGpv3l3hN0K',2,NULL,NULL),(13,'aurelien.martin@cesi.fr','$2y$10$drlACyV6Sf.cnjwyAjNQ0uByHdPCVm42Vw/bfs7Z6Q67SmItzElpu',2,NULL,NULL),(15,'pierre.fontaine@cesi.fr','$2y$10$xGZ8SH2aimGWqLJkzXBMK.EMC09/GoTreqWp0HNe9R4AYFhgB5FXu',2,NULL,NULL),(16,'raphael.linard@viacesi.fr','$2y$10$1DRKeWczzcT9jvEV/T4LUOddLw2uGxJhB0eEdTzJUTaDfcJ4vHkZm',3,NULL,NULL),(17,'ma.boulenger@viacesi.fr','$2y$10$1Q3wUsbXB/HOKdT9JwxZuu4KIm10Tkhx2TjdBvLNgYWrOIuxWPZ7e',3,NULL,NULL),(18,'jeanne.benoit@cesi.fr','$2y$10$Q0aklGm/xrKey4t6OWm4/e8yX1HnBMmM0knTP/Fo1JfVfjamF1IJS',2,NULL,NULL),(20,'pilote1@test.com','$2y$10$mgTBmjC59Lck5TPkyODgn.s53AgX0Im8r8.m6O56O7fFYmwiMyLLC',2,NULL,NULL);
/*!40000 ALTER TABLE `User` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-04-02 13:22:55
