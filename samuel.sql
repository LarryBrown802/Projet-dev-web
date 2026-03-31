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
(1,	'Tech Solutions',	'Tech Solutions développe des plateformes web pour le retail et l\'industrie.',	NULL,	NULL,	NULL,	NULL),
(2,	'Data Insights',	'Data Insights accompagne des PME avec des dashboards décisionnels.',	NULL,	NULL,	NULL,	NULL),
(3,	'SecureTech',	'SecureTech audite et sécurise les SI des grandes entreprises.',	NULL,	NULL,	NULL,	NULL),
(4,	'App Innovate',	'App Innovate conçoit des applications mobiles pour la santé et le sport.',	NULL,	NULL,	NULL,	NULL),
(5,	'AI Labs',	'AI Labs développe des solutions d\'IA pour l\'industrie et les services.',	NULL,	NULL,	NULL,	NULL),
(6,	'Cloud Solutions',	'Cloud Solutions accompagne les entreprises dans leur transformation cloud.',	NULL,	NULL,	NULL,	NULL),
(7,	'WebAgency',	'WebAgency crée des solutions web sur mesure pour les PME françaises.',	NULL,	NULL,	NULL,	NULL),
(8,	'Creative Studio',	'Creative Studio est une agence de design spécialisée en expérience utilisateur.',	NULL,	NULL,	NULL,	NULL),
(9,	'NetWork Pro',	'NetWork Pro gère les infrastructures réseau de plus de 50 entreprises en Alsace.',	NULL,	NULL,	NULL,	NULL),
(10,	'Innova Group',	'Innova Group est un cabinet de conseil en transformation digitale.',	NULL,	NULL,	NULL,	NULL),
(11,	'StartupX',	'StartupX révolutionne la gestion RH avec une plateforme SaaS innovante.',	NULL,	NULL,	NULL,	NULL),
(12,	'HelpDesk Plus',	'HelpDesk Plus fournit des services de support IT externalisé pour les entreprises.',	NULL,	NULL,	NULL,	NULL),
(13,	'Digital Experts',	'Digital Experts est un cabinet de conseil spécialisé en transformation digitale.',	NULL,	NULL,	NULL,	NULL);

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
(1,	NULL,	NULL,	NULL,	'Lyon'),
(2,	NULL,	NULL,	NULL,	'Paris'),
(3,	NULL,	NULL,	NULL,	'Lille'),
(4,	NULL,	NULL,	NULL,	'Toulouse'),
(5,	NULL,	NULL,	NULL,	'Bordeaux'),
(6,	NULL,	NULL,	NULL,	'Marseille'),
(7,	NULL,	NULL,	NULL,	'Nantes'),
(8,	NULL,	NULL,	NULL,	'Strasbourg'),
(9,	NULL,	NULL,	NULL,	'Rennes');

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
(1,	'Développeur Web',	'Rejoignez notre équipe pour développer des applications web innovantes.',	'6 mois',	900,	'Stage',	'Bac+2/3',	'Développement',	'2026-01-10',	1,	1),
(2,	'Data Analyst',	'Analysez des données pour aider à la décision stratégique.',	'12 mois',	1200,	'Alternance',	'Bac+3',	'Data / BI',	'2026-01-15',	2,	2),
(3,	'Spécialiste Cybersécurité',	'Protégez les systèmes d\'information contre les menaces et attaques.',	'6 mois',	1100,	'Stage',	'Bac+4/5',	'Cybersécurité',	'2026-01-20',	3,	3),
(4,	'Développeur Mobile',	'Participez au développement d\'applications mobiles innovantes.',	'4 mois',	1000,	'Stage',	'Bac+3',	'Développement',	'2026-01-22',	4,	4),
(5,	'Ingénieur IA',	'Concevez et entraînez des modèles d\'intelligence artificielle.',	'12 mois',	1500,	'Alternance',	'Bac+5',	'Data / BI',	'2026-01-25',	5,	5),
(6,	'Ingénieur DevOps',	'Automatisez les déploiements et assurez la fiabilité des systèmes.',	'6 mois',	1300,	'Stage',	'Bac+4/5',	'DevOps / Cloud',	'2026-01-25',	6,	6),
(7,	'Développeur Backend PHP',	'Développez des APIs robustes et performantes en PHP.',	'3 mois',	950,	'Stage',	'Bac+3',	'Développement',	'2026-02-01',	7,	7),
(8,	'UX/UI Designer',	'Concevez des interfaces intuitives et esthétiques pour nos clients.',	'4 mois',	800,	'Stage',	'Bac+2/3',	'Développement',	'2026-02-03',	1,	8),
(9,	'Administrateur Réseau',	'Administrez et sécurisez l\'infrastructure réseau de l\'entreprise.',	'12 mois',	1050,	'Alternance',	'Bac+2/3',	'Réseau / Systèmes',	'2026-02-05',	8,	9),
(10,	'Chef de Projet IT',	'Pilotez des projets IT de A à Z en méthode Agile/Scrum.',	'24 mois',	1400,	'Alternance',	'Bac+5',	'Gestion de projet',	'2026-02-10',	2,	10),
(11,	'Développeur Full Stack',	'Développez des fonctionnalités end-to-end dans une startup en forte croissance.',	'6 mois',	1100,	'Stage',	'Bac+4/5',	'Développement',	'2026-02-12',	5,	11),
(12,	'Technicien Support IT',	'Assistez les utilisateurs et résolvez les incidents informatiques.',	'3 mois',	850,	'Stage',	'Bac+2/3',	'Support IT',	'2026-02-15',	9,	12),
(13,	'Consultant en Transformation Digitale',	'Accompagnez les entreprises dans leur transformation digitale.',	'12 mois',	1300,	'Alternance',	'Bac+5',	'Conseil',	'2026-02-20',	2,	13),
(14,	'Développeur Frontend',	'Créez des interfaces utilisateur modernes et réactives.',	'4 mois',	900,	'Stage',	'Bac+3',	'Développement',	'2026-02-25',	1,	8);

DROP TABLE IF EXISTS `Profile`;
CREATE TABLE `Profile` (
  `ID_profile` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `surname` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `ID_user` int DEFAULT NULL,
  PRIMARY KEY (`ID_profile`),
  KEY `ID_utilisateur` (`ID_user`),
  CONSTRAINT `Profile_ibfk_1` FOREIGN KEY (`ID_user`) REFERENCES `User` (`ID_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


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
(1,	'admin1@test.com',	'admin123',	1),
(2,	'pilote1@test.com',	'pilote123',	2),
(3,	'etudiant1@test.com',	'etudiant123',	3);

-- 2026-03-26 09:23:09 UTC
