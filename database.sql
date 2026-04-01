-- Adminer 5.4.2 MySQL 8.0.45-0ubuntu0.24.04.1 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `Apply`;
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

INSERT INTO `Apply` (`ID_offer`, `ID_profile`, `cv`, `motivation_letter`) VALUES
(17,	19,	'1774945730_Dossier_Synthese_Samuel_VEREL_CPI_A2_Info_25_26_Rouen_Semestre_3__29_.PDF',	''),
(17,	20,	'1775028491_CV_stage_Samuel_Verel.pdf',	'');

DROP TABLE IF EXISTS `Company`;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `Company` (`ID`, `name`, `description`, `email`, `number`, `average_mark`, `ID_user`) VALUES
(14,	'Tech Solutions',	'Entreprise spécialisée dans le développement web et mobile',	'contact@techsolutions.fr',	'01 23 45 67 89',	4,	NULL),
(15,	'CESI',	'ZETRH',	'CESI@cesi',	'18514851485',	NULL,	NULL);

DROP TABLE IF EXISTS `Location`;
CREATE TABLE `Location` (
  `ID_location` int NOT NULL AUTO_INCREMENT,
  `street_number` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `street_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `postcode` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `city` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`ID_location`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `Location` (`ID_location`, `street_number`, `street_name`, `postcode`, `city`) VALUES
(10,	NULL,	NULL,	NULL,	'Paris'),
(11,	NULL,	NULL,	NULL,	'Dublin');

DROP TABLE IF EXISTS `Note`;
CREATE TABLE `Note` (
  `ID_user` int NOT NULL,
  `ID_company` int NOT NULL,
  PRIMARY KEY (`ID_user`,`ID_company`),
  KEY `ID` (`ID_company`),
  CONSTRAINT `Note_ibfk_1` FOREIGN KEY (`ID_user`) REFERENCES `User` (`ID_user`),
  CONSTRAINT `Note_ibfk_2` FOREIGN KEY (`ID_company`) REFERENCES `Company` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


DROP TABLE IF EXISTS `Offer`;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `Offer` (`ID_offer`, `title`, `description`, `duration`, `remuneration`, `type`, `level`, `domain`, `publication_date`, `ID_location`, `ID_company`) VALUES
(17,	'Développeur Web',	'Création de site web',	'3 mois',	900,	'Stage',	'Bac+2/3',	'Développement',	'2026-03-28',	10,	14),
(18,	'Developpeur full stack f/m',	'Front-End et backEnd du site de stage',	'3 mois',	1400,	'Alternance',	'Bac+5',	'Développement',	'1111-11-11',	11,	14);

DROP TABLE IF EXISTS `Profile`;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `Profile` (`ID_profile`, `name`, `surname`, `ID_user`, `ID_promotion`, `status`) VALUES
(7,	'Roussel',	'Antoine',	4,	1,	'wait'),
(9,	'Tout',	'Firas',	6,	3,	'wait'),
(16,	'Martin',	'Aurélien',	13,	10,	'wait'),
(18,	'Fontaine',	'Pierre',	15,	1,	'wait'),
(19,	'Verel',	'Samuel',	3,	1,	'ok'),
(20,	'Linard',	'Raphael',	16,	1,	'wait'),
(21,	'Boulenger',	'Mathis',	17,	1,	'wait'),
(22,	'Benoît',	'Jeanne',	18,	2,	'wait');

DROP TABLE IF EXISTS `Promotion`;
CREATE TABLE `Promotion` (
  `ID_promotion` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`ID_promotion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `Promotion` (`ID_promotion`, `name`) VALUES
(1,	'CPI A2 Informatique'),
(2,	'CPI A2 Généraliste'),
(3,	'CPI A2 BTP'),
(10,	'FISE A3 Informatique');

DROP TABLE IF EXISTS `Role`;
CREATE TABLE `Role` (
  `ID_role` int NOT NULL AUTO_INCREMENT,
  `name_role` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`ID_role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `Role` (`ID_role`, `name_role`) VALUES
(1,	'administrateur'),
(2,	'pilote'),
(3,	'etudiant');

DROP TABLE IF EXISTS `Save_wishlist`;
CREATE TABLE `Save_wishlist` (
  `ID_offer` int NOT NULL,
  `ID_profile` int NOT NULL,
  PRIMARY KEY (`ID_offer`,`ID_profile`),
  KEY `ID_profile` (`ID_profile`),
  CONSTRAINT `Save_wishlist_ibfk_1` FOREIGN KEY (`ID_offer`) REFERENCES `Offer` (`ID_offer`),
  CONSTRAINT `Save_wishlist_ibfk_2` FOREIGN KEY (`ID_profile`) REFERENCES `Profile` (`ID_profile`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `Save_wishlist` (`ID_offer`, `ID_profile`) VALUES
(17,	19),
(17,	21);

DROP TABLE IF EXISTS `User`;
CREATE TABLE `User` (
  `ID_user` int NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `ID_role` int DEFAULT NULL,
  PRIMARY KEY (`ID_user`),
  UNIQUE KEY `email` (`email`),
  KEY `ID_role` (`ID_role`),
  CONSTRAINT `User_ibfk_1` FOREIGN KEY (`ID_role`) REFERENCES `Role` (`ID_role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `User` (`ID_user`, `email`, `password`, `ID_role`) VALUES
(1,	'admin1@test.com',	'$2y$10$ChgKtSzX8Wu.Le7HK4sGGuuvPu1OxOdKvKa9RWXf3Uo3gwyGdM5DO',	1),
(3,	'samuel.verel@viacesi.fr',	'$2y$10$Sl7pyEhhX/IZpzLVrFIL1.kHj4JvAVKBekYse6Wtb9rEvJxPZaRHO',	3),
(4,	'antoine.roussel@cesi.fr',	'$2y$10$a2SkQtALWs5Q42fj68WLfOvjuX5qBu8H6jhYZWY7GcxVPuttOmKES',	2),
(6,	'firas.tout@cesi.fr',	'$2y$10$4r3R2Fhxa/Rj5Hk2BLaVmenB4dElTfF516fkwm20RxGGpv3l3hN0K',	2),
(13,	'aurelien.martin@cesi.fr',	'$2y$10$drlACyV6Sf.cnjwyAjNQ0uByHdPCVm42Vw/bfs7Z6Q67SmItzElpu',	2),
(15,	'pierre.fontaine@cesi.fr',	'$2y$10$xGZ8SH2aimGWqLJkzXBMK.EMC09/GoTreqWp0HNe9R4AYFhgB5FXu',	2),
(16,	'raphael.linard@viacesi.fr',	'$2y$10$1DRKeWczzcT9jvEV/T4LUOddLw2uGxJhB0eEdTzJUTaDfcJ4vHkZm',	3),
(17,	'ma.boulenger@viacesi.fr',	'$2y$10$1Q3wUsbXB/HOKdT9JwxZuu4KIm10Tkhx2TjdBvLNgYWrOIuxWPZ7e',	3),
(18,	'jeanne.benoit@cesi.fr',	'$2y$10$Q0aklGm/xrKey4t6OWm4/e8yX1HnBMmM0knTP/Fo1JfVfjamF1IJS',	2);

-- 2026-04-01 09:19:14 UTC
