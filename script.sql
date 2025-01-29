-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mar. 21 jan. 2025 à 10:41
-- Version du serveur : 8.3.0
-- Version de PHP : 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `wisikard2`
--

DELIMITER $$
--
-- Procédures
--
DROP PROCEDURE IF EXISTS `addVue`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `addVue` (`id_carte` INT)   BEGIN
    INSERT INTO vue (date, idCarte) VALUES (NOW(), id_Carte);
END$$

DROP PROCEDURE IF EXISTS `addVueEmp`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `addVueEmp` (`id_carte` INT, `id_Emp` INT)   BEGIN
    INSERT INTO vue (date, idCarte, idEmp) VALUES (NOW(), id_Carte, id_Emp);
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `carte`
--

DROP TABLE IF EXISTS `carte`;
CREATE TABLE IF NOT EXISTS `carte` (
    `idCarte` int NOT NULL AUTO_INCREMENT,
    `nomEntreprise` varchar(255) NOT NULL,
    `titre` varchar(150) NOT NULL,
    `tel` varchar(25) NOT NULL,
    `ville` varchar(255) NOT NULL,
    `imgPres` varchar(100) DEFAULT NULL,
    `imgLogo` varchar(100) DEFAULT NULL,
    `pdf` varchar(100) DEFAULT NULL,
    `nomBtnPdf` varchar(100) DEFAULT NULL,
    `couleur1` varchar(10) DEFAULT NULL,
    `couleur2` varchar(10) DEFAULT NULL,
    `descriptif` varchar(500) DEFAULT NULL,
    `LienCommande` varchar(150) DEFAULT NULL,
    `lienQr` varchar(500) NOT NULL,
    `idCompte` int NOT NULL,
    `idTemplate` int NOT NULL,
    PRIMARY KEY (`idCarte`),
    KEY `carte_compte_FK` (`idCompte`),
    KEY `carte_template_FK` (`idTemplate`)
    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `carte`
--

INSERT INTO `carte` (`idCarte`, `nomEntreprise`, `titre`, `tel`, `ville`, `imgPres`, `imgLogo`, `pdf`, `nomBtnPdf`, `couleur1`, `couleur2`, `descriptif`, `LienCommande`, `lienQr`, `idCompte`, `idTemplate`) VALUES
    (1, 'LIDL', 'Titre1', '123456789044', 'OuiOui', 'imgPres1.jpg', 'imgLogo1.jpg', 'pdf1.pdf', 'Télécharger', '#ff0000', '#12173b', 'Description1', 'http://liencommande1.com', '/entreprises/1_LIDL/QR_Codes/QR_Code.svg', 1, 3),
    (2, 'Entreprise2', 'Titre2', '987654321', 'Ville2', 'imgPres2.jpg', 'imgLogo2.jpg', 'pdf2.pdf', 'Télécharger', '#FF0000', '#00FF00', 'Description2', 'http://liencommande2.com', '/entreprises/2_Entreprise2/QR_Codes/QR_Code.svg', 2, 2),
    (3, 'Entreprise3', 'Titre3', '111223344', 'Ville3', 'imgPres3.jpg', 'imgLogo3.jpg', 'pdf3.pdf', 'Télécharger', '#00FFFF', '#FF00FF', 'Description3', 'http://liencommande3.com', '/entreprises/3_Entreprise3/QR_Codes/QR_Code.svg', 3, 3),
    (4, 'Entreprise4', 'Titre4', '443322111', 'Ville4', 'imgPres4.jpg', 'imgLogo4.jpg', 'pdf4.pdf', 'Télécharger', '#FFFF00', '#00FFFF', 'Description4', 'http://liencommande4.com', '/entreprises/4_Entreprise4/QR_Codes/QR_Code.svg', 4, 2),
    (9, 'nomEntreprise', 'titre', 'tel', 'ville', NULL, NULL, NULL, NULL, '#000000', '#FFFFFF', NULL, NULL, '/entreprises/14_nomEntreprise/QR_Codes/QR_Code.svg', 14, 1);

-- --------------------------------------------------------

--
-- Structure de la table `compte`
--

DROP TABLE IF EXISTS `compte`;
CREATE TABLE IF NOT EXISTS `compte` (
                                        `idCompte` int NOT NULL AUTO_INCREMENT,
                                        `email` varchar(100) NOT NULL,
    `password` varchar(500) NOT NULL,
    `role` varchar(50) NOT NULL,
    `tentativesCo` int NOT NULL DEFAULT '0',
    `estDesactiver` tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`idCompte`)
    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `compte`
--

INSERT INTO `compte` (`idCompte`, `email`, `password`, `role`, `tentativesCo`, `estDesactiver`) VALUES
    (1, 'cli@cli.fr', '$2y$10$QKrYdQf.24F8EUbOTIvqMuh4HjzINrS6s/ATrVm1ixGKERbGEmiUq', 'starter', 0, 0),
    (2, 'user2@example.com', 'hashed_password2', 'starter', 0, 0),
    (3, 'user3@example.com', 'hashed_password3', 'advanced', 0, 0),
    (4, 'user4@example.com', 'hashed_password4', 'advanced', 0, 0),
    (5, 'user5@example.com', 'hashed_password5', 'starter', 0, 0),
    (6, 'to.doguet@gmail.com', '$2y$10$QKrYdQf.24F8EUbOTIvqMuh4HjzINrS6s/ATrVm1ixGKERbGEmiUq', 'admin', 0, 0),
    (14, 'aa@aa.fr', '$2y$10$s7yLTAdH8bJpVCLoLJ1ml.FxFaPVlsuFMOHRi7NhQMtq152H3mTl2', 'advanced', 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `custom_link`
--

DROP TABLE IF EXISTS `custom_link`;
CREATE TABLE IF NOT EXISTS `custom_link` (
    `id_link` int NOT NULL AUTO_INCREMENT,
    `nom` varchar(150) NOT NULL,
    `lien` varchar(300) DEFAULT NULL,
    `activer` tinyint(1) NOT NULL DEFAULT '0',
    `idCarte` int DEFAULT NULL,
    PRIMARY KEY (`id_link`),
    KEY `idCarte` (`idCarte`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `employer`
--

DROP TABLE IF EXISTS `employer`;
CREATE TABLE IF NOT EXISTS `employer` (
    `idEmp` int NOT NULL AUTO_INCREMENT,
    `nom` varchar(100) NOT NULL,
    `prenom` varchar(100) NOT NULL,
    `fonction` varchar(100) NOT NULL,
    `idCarte` int DEFAULT NULL,
    `mail` varchar(100) NOT NULL,
    `telephone` varchar(100) NOT NULL,
    PRIMARY KEY (`idEmp`),
    KEY `employer_carte_FK` (`idCarte`)
    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `employer`
--

INSERT INTO `employer` (`idEmp`, `nom`, `prenom`, `fonction`, `idCarte`, `mail`, `telephone`) VALUES
    (1, 'Dupontsssss', 'Jeansssssss', 'Développeurlklmkssss', 1, 'dupont.jean@oui.frssss', '0782578920kkkssss'),
    (2, 'Martin', 'Marie', 'Designer', 2, '', ''),
    (3, 'Durand', 'Paul', 'Manager', 3, '', ''),
    (4, 'Lefevre', 'Sophie', 'Analyste', 4, '', ''),
    (6, 'Garnier', 'Claire', 'Développeur', 1, '', ''),
    (7, 'Rousseau', 'Luc', 'Designer', 2, '', ''),
    (8, 'Leroy', 'Anne', 'Manager', 3, '', ''),
    (9, 'Bernard', 'Marc', 'Analyste', 4, '', ''),
    (32, 'test', 'test', 'goatesque et oui', 1, 'to.doguet@gmail.com', '0782578920');

-- --------------------------------------------------------
--
-- Structure de la table `horaires`
--

DROP TABLE IF EXISTS `horaires`;
CREATE TABLE IF NOT EXISTS `horaires` (
                                          `id` int NOT NULL AUTO_INCREMENT,
                                          `idCarte` int NOT NULL,
                                          `jour` varchar(255) NOT NULL,
    `ouverture_matin` time DEFAULT NULL,
    `fermeture_matin` time DEFAULT NULL,
    `ouverture_aprmidi` time DEFAULT NULL,
    `fermeture_aprmidi` time DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `idCarte` (`idCarte`)
    ) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `horaires`
--
-- --------------------------------------------------------

INSERT INTO `horaires` (`id`, `idCarte`, `jour`, `ouverture_matin`, `fermeture_matin`, `ouverture_aprmidi`, `fermeture_aprmidi`) VALUES
                                                                                                                                     (1, 1, 'lundi', '07:10:00', '11:00:00', '13:00:00', '17:00:00'),
                                                                                                                                     (2, 1, 'mardi', NULL, NULL, NULL, NULL),
                                                                                                                                     (3, 1, 'mercredi', NULL, NULL, NULL, NULL),
                                                                                                                                     (4, 1, 'jeudi', NULL, NULL, NULL, NULL),
                                                                                                                                     (5, 1, 'vendredi', NULL, NULL, NULL, NULL),
                                                                                                                                     (6, 1, 'samedi', NULL, NULL, NULL, NULL),
                                                                                                                                     (7, 1, 'dimanche', NULL, NULL, NULL, NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
-- --------------------------------------------------------

--
-- Structure de la table `logs`
--

DROP TABLE IF EXISTS `logs`;
CREATE TABLE IF NOT EXISTS `logs` (
    `idLog` int NOT NULL AUTO_INCREMENT,
    `typeAction` varchar(500) NOT NULL,
    `dateHeureLog` datetime NOT NULL,
    `adresseIPLog` varchar(500) NOT NULL,
    `idCompte` int NOT NULL,
    PRIMARY KEY (`idLog`),
    KEY `logs_compte_FK` (`idCompte`)
    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `logs`
--

INSERT INTO `logs` (`idLog`, `typeAction`, `dateHeureLog`, `adresseIPLog`, `idCompte`) VALUES
    (1, 'Connexion', '2023-10-01 10:00:00', '192.168.1.1', 1),
    (2, 'Déconnexion', '2023-10-01 11:00:00', '192.168.1.2', 2),
    (3, 'Connexion', '2023-10-02 12:00:00', '192.168.1.3', 3),
    (4, 'Déconnexion', '2023-10-02 13:00:00', '192.168.1.4', 4),
    (5, 'Connexion', '2023-10-03 14:00:00', '192.168.1.5', 5),
    (6, 'Déconnexion', '2023-10-03 15:00:00', '192.168.1.6', 1),
    (7, 'Connexion', '2023-10-04 16:00:00', '192.168.1.7', 2),
    (8, 'Déconnexion', '2023-10-04 17:00:00', '192.168.1.8', 3),
    (9, 'Connexion', '2023-10-05 18:00:00', '192.168.1.9', 4),
    (10, 'Déconnexion', '2023-10-05 19:00:00', '192.168.1.10', 5),
    (11, 'Connexion échouée', '2025-01-10 15:40:12', '127.0.0.1', 6),
    (12, 'Connexion', '2025-01-10 15:40:17', '127.0.0.1', 6),
    (13, 'Connexion réussie', '2025-01-10 15:40:17', '127.0.0.1', 6),
    (14, 'Connexion', '2025-01-14 08:11:05', '127.0.0.1', 6),
    (15, 'Connexion réussie', '2025-01-14 08:11:05', '127.0.0.1', 6),
    (16, 'Connexion', '2025-01-14 09:12:25', '127.0.0.1', 6),
    (17, 'Connexion réussie', '2025-01-14 09:12:25', '127.0.0.1', 6),
    (18, 'Connexion', '2025-01-14 09:43:04', '127.0.0.1', 1),
    (19, 'Connexion réussie', '2025-01-14 09:43:04', '127.0.0.1', 1),
    (20, 'Connexion', '2025-01-14 09:44:47', '127.0.0.1', 1),
    (21, 'Connexion réussie', '2025-01-14 09:44:47', '127.0.0.1', 1),
    (22, 'Connexion', '2025-01-14 09:46:55', '127.0.0.1', 1),
    (23, 'Connexion réussie', '2025-01-14 09:46:55', '127.0.0.1', 1),
    (24, 'Connexion', '2025-01-14 09:51:09', '127.0.0.1', 1),
    (25, 'Connexion réussie', '2025-01-14 09:51:09', '127.0.0.1', 1),
    (26, 'Connexion', '2025-01-14 09:55:27', '127.0.0.1', 1),
    (27, 'Connexion réussie', '2025-01-14 09:55:27', '127.0.0.1', 1),
    (28, 'Connexion', '2025-01-14 10:01:50', '127.0.0.1', 6),
    (29, 'Connexion réussie', '2025-01-14 10:01:50', '127.0.0.1', 6),
    (30, 'Connexion', '2025-01-14 11:45:25', '127.0.0.1', 6),
    (31, 'Connexion réussie', '2025-01-14 11:45:25', '127.0.0.1', 6),
    (32, 'Connexion', '2025-01-14 11:56:10', '127.0.0.1', 1),
    (33, 'Connexion réussie', '2025-01-14 11:56:10', '127.0.0.1', 1),
    (34, 'Connexion', '2025-01-14 11:56:56', '127.0.0.1', 6),
    (35, 'Connexion réussie', '2025-01-14 11:56:56', '127.0.0.1', 6),
    (36, 'Connexion', '2025-01-14 12:03:13', '127.0.0.1', 1),
    (37, 'Connexion réussie', '2025-01-14 12:03:13', '127.0.0.1', 1),
    (38, 'Connexion', '2025-01-14 13:19:55', '127.0.0.1', 1),
    (39, 'Connexion réussie', '2025-01-14 13:19:55', '127.0.0.1', 1),
    (40, 'Connexion', '2025-01-14 14:23:31', '127.0.0.1', 1),
    (41, 'Connexion réussie', '2025-01-14 14:23:31', '127.0.0.1', 1),
    (42, 'Connexion', '2025-01-14 15:27:41', '127.0.0.1', 1),
    (43, 'Connexion réussie', '2025-01-14 15:27:41', '127.0.0.1', 1),
    (44, 'Connexion', '2025-01-14 15:28:37', '127.0.0.1', 1),
    (45, 'Connexion réussie', '2025-01-14 15:28:37', '127.0.0.1', 1),
    (46, 'Connexion', '2025-01-14 15:28:49', '127.0.0.1', 1),
    (47, 'Connexion réussie', '2025-01-14 15:28:49', '127.0.0.1', 1),
    (48, 'Connexion', '2025-01-14 15:30:38', '127.0.0.1', 6),
    (49, 'Connexion réussie', '2025-01-14 15:30:38', '127.0.0.1', 6),
    (50, 'Connexion', '2025-01-14 15:30:47', '127.0.0.1', 6),
    (51, 'Connexion réussie', '2025-01-14 15:30:47', '127.0.0.1', 6),
    (52, 'Connexion', '2025-01-14 15:31:08', '127.0.0.1', 1),
    (53, 'Connexion réussie', '2025-01-14 15:31:08', '127.0.0.1', 1),
                                                                                           (54, 'Inscription Employe', '2025-01-14 15:41:28', '127.0.0.1', 1),
                                                                                           (55, 'Inscription Employe', '2025-01-14 15:43:08', '127.0.0.1', 1),
                                                                                           (56, 'Inscription Employe', '2025-01-14 15:43:11', '127.0.0.1', 1),
                                                                                           (57, 'Inscription Employe', '2025-01-14 15:43:46', '127.0.0.1', 1),
                                                                                           (58, 'Inscription Employe', '2025-01-14 15:43:49', '127.0.0.1', 1),
                                                                                           (59, 'Inscription Employe', '2025-01-14 15:45:10', '127.0.0.1', 1),
                                                                                           (60, 'Inscription Employe', '2025-01-14 15:46:44', '127.0.0.1', 1),
                                                                                           (61, 'Inscription Employe', '2025-01-14 15:48:00', '127.0.0.1', 1),
                                                                                           (62, 'Inscription Employe', '2025-01-14 15:48:42', '127.0.0.1', 1),
                                                                                           (63, 'Inscription Employe', '2025-01-14 15:49:31', '127.0.0.1', 1),
                                                                                           (64, 'Inscription Employe', '2025-01-14 16:07:24', '127.0.0.1', 1),
                                                                                           (65, 'Connexion', '2025-01-14 16:10:12', '127.0.0.1', 1),
                                                                                           (66, 'Connexion réussie', '2025-01-14 16:10:12', '127.0.0.1', 1),
                                                                                           (67, 'Connexion', '2025-01-14 16:24:24', '127.0.0.1', 1),
                                                                                           (68, 'Connexion réussie', '2025-01-14 16:24:24', '127.0.0.1', 1),
                                                                                           (69, 'Connexion', '2025-01-15 07:44:29', '127.0.0.1', 1),
                                                                                           (70, 'Connexion réussie', '2025-01-15 07:44:29', '127.0.0.1', 1),
                                                                                           (71, 'Connexion', '2025-01-15 08:46:02', '127.0.0.1', 1),
                                                                                           (72, 'Connexion réussie', '2025-01-15 08:46:02', '127.0.0.1', 1),
                                                                                           (73, 'Connexion', '2025-01-15 08:48:11', '127.0.0.1', 1),
                                                                                           (74, 'Connexion réussie', '2025-01-15 08:48:11', '127.0.0.1', 1),
                                                                                           (75, 'Connexion', '2025-01-15 09:11:05', '127.0.0.1', 1),
                                                                                           (76, 'Connexion réussie', '2025-01-15 09:11:05', '127.0.0.1', 1),
                                                                                           (77, 'Connexion', '2025-01-15 09:32:49', '127.0.0.1', 1),
                                                                                           (78, 'Connexion réussie', '2025-01-15 09:32:49', '127.0.0.1', 1),
                                                                                           (79, 'Connexion', '2025-01-15 09:36:24', '127.0.0.1', 6),
                                                                                           (80, 'Connexion réussie', '2025-01-15 09:36:24', '127.0.0.1', 6),
                                                                                           (81, 'Connexion', '2025-01-15 09:36:47', '127.0.0.1', 1),
                                                                                           (82, 'Connexion réussie', '2025-01-15 09:36:47', '127.0.0.1', 1),
                                                                                           (83, 'Connexion', '2025-01-15 09:39:47', '127.0.0.1', 1),
                                                                                           (84, 'Connexion réussie', '2025-01-15 09:39:47', '127.0.0.1', 1),
                                                                                           (85, 'Modification Employe', '2025-01-15 09:41:10', '127.0.0.1', 1),
                                                                                           (86, 'Modification Employe', '2025-01-15 09:42:35', '127.0.0.1', 1),
                                                                                           (87, 'Connexion', '2025-01-15 10:43:41', '127.0.0.1', 1),
                                                                                           (88, 'Connexion réussie', '2025-01-15 10:43:41', '127.0.0.1', 1),
                                                                                           (89, 'Connexion', '2025-01-15 10:46:50', '127.0.0.1', 6),
                                                                                           (90, 'Connexion réussie', '2025-01-15 10:46:50', '127.0.0.1', 6),
                                                                                           (91, 'Connexion', '2025-01-15 13:03:12', '127.0.0.1', 6),
                                                                                           (92, 'Connexion réussie', '2025-01-15 13:03:12', '127.0.0.1', 6),
                                                                                           (97, 'Connexion', '2025-01-15 13:22:13', '127.0.0.1', 1),
                                                                                           (98, 'Connexion réussie', '2025-01-15 13:22:13', '127.0.0.1', 1),
                                                                                           (99, 'Connexion', '2025-01-15 14:35:17', '127.0.0.1', 6),
                                                                                           (100, 'Connexion réussie', '2025-01-15 14:35:17', '127.0.0.1', 6),
                                                                                           (102, 'Inscription', '2025-01-15 14:47:38', '127.0.0.1', 14),
                                                                                           (103, 'Connexion', '2025-01-15 15:25:15', '127.0.0.1', 1),
                                                                                           (104, 'Connexion réussie', '2025-01-15 15:25:15', '127.0.0.1', 1),
                                                                                           (105, 'Connexion', '2025-01-17 07:51:25', '127.0.0.1', 1),
                                                                                           (106, 'Connexion réussie', '2025-01-17 07:51:25', '127.0.0.1', 1),
                                                                                           (107, 'Connexion', '2025-01-20 08:54:19', '127.0.0.1', 1),
                                                                                           (108, 'Connexion réussie', '2025-01-20 08:54:19', '127.0.0.1', 1),
                                                                                           (109, 'Modification Employe', '2025-01-20 09:29:30', '127.0.0.1', 1),
                                                                                           (110, 'Connexion', '2025-01-20 09:55:17', '127.0.0.1', 1),
                                                                                           (111, 'Connexion réussie', '2025-01-20 09:55:17', '127.0.0.1', 1),
                                                                                           (112, 'Connexion', '2025-01-20 10:11:45', '127.0.0.1', 1),
                                                                                           (113, 'Connexion réussie', '2025-01-20 10:11:45', '127.0.0.1', 1),
                                                                                           (114, 'Connexion', '2025-01-20 10:27:27', '127.0.0.1', 1),
                                                                                           (115, 'Connexion réussie', '2025-01-20 10:27:27', '127.0.0.1', 1),
                                                                                           (116, 'Connexion', '2025-01-20 13:07:35', '127.0.0.1', 1),
                                                                                           (117, 'Connexion réussie', '2025-01-20 13:07:35', '127.0.0.1', 1),
                                                                                           (118, 'Suppression Employe', '2025-01-20 13:09:10', '127.0.0.1', 1),
                                                                                           (119, 'Suppression Employe', '2025-01-20 13:09:13', '127.0.0.1', 1),
                                                                                           (120, 'Suppression Employe', '2025-01-20 13:09:16', '127.0.0.1', 1),
                                                                                           (121, 'Suppression Employe', '2025-01-20 13:09:18', '127.0.0.1', 1),
                                                                                           (122, 'Suppression Employe', '2025-01-20 13:09:20', '127.0.0.1', 1),
                                                                                           (123, 'Suppression Employe', '2025-01-20 13:10:06', '127.0.0.1', 1),
                                                                                           (124, 'Suppression Employe', '2025-01-20 13:11:49', '127.0.0.1', 1),
                                                                                           (125, 'Suppression Employe', '2025-01-20 13:13:41', '127.0.0.1', 1),
                                                                                           (126, 'Suppression Employe', '2025-01-20 13:17:35', '127.0.0.1', 1),
                                                                                           (127, 'Connexion', '2025-01-20 13:50:42', '127.0.0.1', 6),
                                                                                           (128, 'Connexion réussie', '2025-01-20 13:50:42', '127.0.0.1', 6),
                                                                                           (129, 'Connexion', '2025-01-20 13:51:14', '127.0.0.1', 1),
                                                                                           (130, 'Connexion réussie', '2025-01-20 13:51:14', '127.0.0.1', 1),
                                                                                           (131, 'Connexion', '2025-01-20 13:54:45', '127.0.0.1', 1),
                                                                                           (132, 'Connexion réussie', '2025-01-20 13:54:45', '127.0.0.1', 1),
                                                                                           (133, 'Modification Employe', '2025-01-20 14:18:45', '127.0.0.1', 1),
                                                                                           (134, 'Modification Employe', '2025-01-20 14:18:56', '127.0.0.1', 1),
                                                                                           (135, 'Modification Employe', '2025-01-20 14:20:59', '127.0.0.1', 1),
                                                                                           (136, 'Modification Employe', '2025-01-20 14:21:55', '127.0.0.1', 1),
                                                                                           (137, 'Connexion', '2025-01-20 15:05:16', '127.0.0.1', 14),
                                                                                           (138, 'Connexion réussie', '2025-01-20 15:05:16', '127.0.0.1', 14),
                                                                                           (139, 'Connexion', '2025-01-20 15:05:29', '127.0.0.1', 1),
                                                                                           (140, 'Connexion réussie', '2025-01-20 15:05:29', '127.0.0.1', 1),
                                                                                           (141, 'Connexion', '2025-01-20 16:06:57', '127.0.0.1', 1),
                                                                                           (142, 'Connexion réussie', '2025-01-20 16:06:57', '127.0.0.1', 1),
                                                                                           (143, 'Connexion', '2025-01-21 08:03:21', '127.0.0.1', 1),
                                                                                           (144, 'Connexion réussie', '2025-01-21 08:03:21', '127.0.0.1', 1),
                                                                                           (145, 'Connexion', '2025-01-21 08:03:21', '127.0.0.1', 1),
                                                                                           (146, 'Connexion réussie', '2025-01-21 08:03:21', '127.0.0.1', 1),
                                                                                           (147, 'Connexion', '2025-01-21 09:04:17', '127.0.0.1', 1),
                                                                                           (148, 'Connexion réussie', '2025-01-21 09:04:17', '127.0.0.1', 1),
                                                                                           (149, 'Connexion', '2025-01-21 10:04:49', '127.0.0.1', 1),
                                                                                           (150, 'Connexion réussie', '2025-01-21 10:04:49', '127.0.0.1', 1);

-- --------------------------------------------------------

--
-- Structure de la table `message`
--

DROP TABLE IF EXISTS `message`;
CREATE TABLE IF NOT EXISTS `message` (
                                         `id` int NOT NULL AUTO_INCREMENT,
                                         `message` varchar(500) NOT NULL,
    `afficher` tinyint(1) NOT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `message`
--

INSERT INTO `message` (`id`, `message`, `afficher`) VALUES
                                                        (1, 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aperiam blanditiis dicta dolorem doloremque ullam vel. Deleniti, eos, veniam. Accusantium dolorum expedita minus quidem voluptas. Impedit nam nihil ut voluptatem voluptatum', 0),
                                                        (2, 'Ajout d un nouveau Template oui', 0);

-- --------------------------------------------------------

--
-- Structure de la table `reactivation`
--

DROP TABLE IF EXISTS `reactivation`;
CREATE TABLE IF NOT EXISTS `reactivation` (
  `idReactivation` int NOT NULL AUTO_INCREMENT,
  `codeReactivation` varchar(32) NOT NULL,
  `dateHeureExpirationReactivation` datetime NOT NULL,
  `idCompte` int NOT NULL,
  PRIMARY KEY (`idReactivation`),
  UNIQUE KEY `codeReactivation` (`codeReactivation`),
  KEY `reactivation_compte_FK` (`idCompte`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `reactivation`
--

INSERT INTO `reactivation` (`idReactivation`, `codeReactivation`, `dateHeureExpirationReactivation`, `idCompte`) VALUES
(1, 'code1', '2023-10-02 10:00:00', 1),
(2, 'code2', '2023-10-02 11:00:00', 2),
(3, 'code3', '2023-10-03 12:00:00', 3),
(4, 'code4', '2023-10-03 13:00:00', 4),
(5, 'code5', '2023-10-04 14:00:00', 5),
(6, 'code6', '2023-10-04 15:00:00', 1),
(7, 'code7', '2023-10-05 16:00:00', 2),
(8, 'code8', '2023-10-05 17:00:00', 3),
(9, 'code9', '2023-10-06 18:00:00', 4),
(10, 'code10', '2023-10-06 19:00:00', 5);

-- --------------------------------------------------------

--
-- Structure de la table `recuperation`
--

DROP TABLE IF EXISTS `recuperation`;
CREATE TABLE IF NOT EXISTS `recuperation` (
  `idRecuperation` int NOT NULL AUTO_INCREMENT,
  `codeRecuperation` varchar(32) NOT NULL,
  `dateHeureExpirationRecuperation` datetime NOT NULL,
  `idCompte` int NOT NULL,
  PRIMARY KEY (`idRecuperation`),
  UNIQUE KEY `codeRecuperation` (`codeRecuperation`),
  KEY `recuperation_compte_FK` (`idCompte`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `recuperation`
--

INSERT INTO `recuperation` (`idRecuperation`, `codeRecuperation`, `dateHeureExpirationRecuperation`, `idCompte`) VALUES
(1, 'code11', '2023-10-07 10:00:00', 1),
(2, 'code12', '2023-10-07 11:00:00', 2),
(3, 'code13', '2023-10-08 12:00:00', 3),
(4, 'code14', '2023-10-08 13:00:00', 4),
(5, 'code15', '2023-10-09 14:00:00', 5),
(6, 'code16', '2023-10-09 15:00:00', 1),
(7, 'code17', '2023-10-10 16:00:00', 2),
(8, 'code18', '2023-10-10 17:00:00', 3),
(9, 'code19', '2023-10-11 18:00:00', 4),
(10, 'code20', '2023-10-11 19:00:00', 5);

-- --------------------------------------------------------

--
-- Structure de la table `rediriger`
--

DROP TABLE IF EXISTS `rediriger`;
CREATE TABLE IF NOT EXISTS `rediriger` (
  `idSocial` int NOT NULL,
  `idCarte` int NOT NULL,
  `lien` varchar(500) DEFAULT NULL,
  `activer` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idSocial`,`idCarte`),
  KEY `rediriger_carte_FK` (`idCarte`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `rediriger`
--

INSERT INTO `rediriger` (`idSocial`, `idCarte`, `lien`, `activer`) VALUES
(1, 1, 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 1),
(1, 2, 'http://facebook.com/entreprise2a', 0),
(2, 1, 'http://testTwitter', 0),
(2, 2, 'http://twitter.com/entreprise2', 0),
(2, 3, 'http://twitter.com/entreprise3', 0),
(3, 1, 'http://test', 0),
(3, 3, 'http://linkedin.com/entreprise3', 0),
(3, 4, 'http://linkedin.com/entreprise4', 0),
(4, 1, NULL, 0),
(4, 4, 'http://instagram.com/entreprise4', 0),
(5, 1, 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 1),
(6, 1, 'http://youtube.com/entreprisedtwitter', 1),
(10, 1, NULL, 0);

-- --------------------------------------------------------

--
-- Structure de la table `social`
--

DROP TABLE IF EXISTS `social`;
CREATE TABLE IF NOT EXISTS `social` (
  `idSocial` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `lienLogo` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`idSocial`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `social`
--

INSERT INTO `social` (`idSocial`, `nom`, `lienLogo`) VALUES
(1, 'Facebook', '<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 512 512\"><path d=\"M512 256C512 114.6 397.4 0 256 0S0 114.6 0 256C0 376 82.7 476.8 194.2 504.5V334.2H141.4V256h52.8V222.3c0-87.1 39.4-127.5 125-127.5c16.2 0 44.2 3.2 55.7 6.4V172c-6-.6-16.5-1-29.6-1c-42 0-58.2 15.9-58.2 57.2V256h83.6l-14.4 78.2H287V510.1C413.8 494.8 512 386.9 512 256h0z\"/></svg>'),
(2, 'Twitter', '<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 512 512\"><path d=\"M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z\"/></svg>'),
                                                         (3, 'LinkedIn', '<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 448 512\"><path d=\"M416 32H31.9C14.3 32 0 46.5 0 64.3v383.4C0 465.5 14.3 480 31.9 480H416c17.6 0 32-14.5 32-32.3V64.3c0-17.8-14.4-32.3-32-32.3zM135.4 416H69V202.2h66.5V416zm-33.2-243c-21.3 0-38.5-17.3-38.5-38.5S80.9 96 102.2 96c21.2 0 38.5 17.3 38.5 38.5 0 21.3-17.2 38.5-38.5 38.5zm282.1 243h-66.4V312c0-24.8-.5-56.7-34.5-56.7-34.6 0-39.9 27-39.9 54.9V416h-66.4V202.2h63.7v29.2h.9c8.9-16.8 30.6-34.5 62.9-34.5 67.2 0 79.7 44.3 79.7 101.9V416z\"/></svg>'),
                                                         (4, 'Instagram', '<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 448 512\"><path d=\"M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z\"/></svg>'),
                                                         (5, 'YouTube', '<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 576 512\"><path d=\"M549.7 124.1c-6.3-23.7-24.8-42.3-48.3-48.6C458.8 64 288 64 288 64S117.2 64 74.6 75.5c-23.5 6.3-42 24.9-48.3 48.6-11.4 42.9-11.4 132.3-11.4 132.3s0 89.4 11.4 132.3c6.3 23.7 24.8 41.5 48.3 47.8C117.2 448 288 448 288 448s170.8 0 213.4-11.5c23.5-6.3 42-24.2 48.3-47.8 11.4-42.9 11.4-132.3 11.4-132.3s0-89.4-11.4-132.3zm-317.5 213.5V175.2l142.7 81.2-142.7 81.2z\"/></svg>'),
                                                         (6, 'TikTok', '<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 448 512\"><path d=\"M448 209.9a210.1 210.1 0 0 1 -122.8-39.3V349.4A162.6 162.6 0 1 1 185 188.3V278.2a74.6 74.6 0 1 0 52.2 71.2V0l88 0a121.2 121.2 0 0 0 1.9 22.2h0A122.2 122.2 0 0 0 381 102.4a121.4 121.4 0 0 0 67 20.1z\"/></svg>'),
                                                         (7, 'Discord', '<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 640 512\"><path d=\"M524.5 69.8a1.5 1.5 0 0 0 -.8-.7A485.1 485.1 0 0 0 404.1 32a1.8 1.8 0 0 0 -1.9 .9 337.5 337.5 0 0 0 -14.9 30.6 447.8 447.8 0 0 0 -134.4 0 309.5 309.5 0 0 0 -15.1-30.6 1.9 1.9 0 0 0 -1.9-.9A483.7 483.7 0 0 0 116.1 69.1a1.7 1.7 0 0 0 -.8 .7C39.1 183.7 18.2 294.7 28.4 404.4a2 2 0 0 0 .8 1.4A487.7 487.7 0 0 0 176 479.9a1.9 1.9 0 0 0 2.1-.7A348.2 348.2 0 0 0 208.1 430.4a1.9 1.9 0 0 0 -1-2.6 321.2 321.2 0 0 1 -45.9-21.9 1.9 1.9 0 0 1 -.2-3.1c3.1-2.3 6.2-4.7 9.1-7.1a1.8 1.8 0 0 1 1.9-.3c96.2 43.9 200.4 43.9 295.5 0a1.8 1.8 0 0 1 1.9 .2c2.9 2.4 6 4.9 9.1 7.2a1.9 1.9 0 0 1 -.2 3.1 301.4 301.4 0 0 1 -45.9 21.8 1.9 1.9 0 0 0 -1 2.6 391.1 391.1 0 0 0 30 48.8 1.9 1.9 0 0 0 2.1 .7A486 486 0 0 0 610.7 405.7a1.9 1.9 0 0 0 .8-1.4C623.7 277.6 590.9 167.5 524.5 69.8zM222.5 337.6c-29 0-52.8-26.6-52.8-59.2S193.1 219.1 222.5 219.1c29.7 0 53.3 26.8 52.8 59.2C275.3 311 251.9 337.6 222.5 337.6zm195.4 0c-29 0-52.8-26.6-52.8-59.2S388.4 219.1 417.9 219.1c29.7 0 53.3 26.8 52.8 59.2C470.7 311 447.5 337.6 417.9 337.6z\"/></svg>'),
                                                         (8, 'AirBNB', '<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 448 512\"><path d=\"M224 373.1c-25.2-31.7-40.1-59.4-45-83.2-22.6-88 112.6-88 90.1 0-5.5 24.3-20.3 52-45 83.2zm138.2 73.2c-42.1 18.3-83.7-10.9-119.3-50.5 103.9-130.1 46.1-200-18.9-200-54.9 0-85.2 46.5-73.3 100.5 6.9 29.2 25.2 62.4 54.4 99.5-32.5 36.1-60.6 52.7-85.2 54.9-50 7.4-89.1-41.1-71.3-91.1 15.1-39.2 111.7-231.2 115.9-241.6 15.8-30.1 25.6-57.4 59.4-57.4 32.3 0 43.4 25.9 60.4 59.9 36 70.6 89.4 177.5 114.8 239.1 13.2 33.1-1.4 71.3-37 86.6zm47-136.1C280.3 35.9 273.1 32 224 32c-45.5 0-64.9 31.7-84.7 72.8C33.2 317.1 22.9 347.2 22 349.8-3.2 419.1 48.7 480 111.6 480c21.7 0 60.6-6.1 112.4-62.4 58.7 63.8 101.3 62.4 112.4 62.4 62.9 .1 114.9-60.9 89.6-130.2 0-3.9-16.8-38.9-16.8-39.6z\"/></svg>'),
                                                         (9, 'Booking.com', '<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 3.036 3.037\"><path d=\"M1.113 2.524h-.51v-.61c0-.13.05-.2.162-.214h.35a.38.38 0 0 1 .41.411c0 .26-.157.415-.41.415zM.602.875v-.16c0-.14.06-.208.19-.216h.262c.224 0 .36.134.36.36 0 .17-.092.37-.35.37h-.46zm1.164.61l-.092-.052.08-.07c.094-.08.25-.262.25-.575 0-.48-.372-.79-.947-.79h-.73a.32.32 0 0 0-.309.317v2.72H1.07c.64 0 1.052-.348 1.052-.888 0-.29-.133-.54-.358-.665\"/><path d=\"M2.288 2.67c0-.203.163-.367.365-.367s.367.164.367.367-.164.367-.367.367-.365-.164-.365-.367\"/></svg>'),
                                                         (10, 'SnapChat', '<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 448 512\"><path d=\"M384 32H64A64 64 0 0 0 0 96V416a64 64 0 0 0 64 64H384a64 64 0 0 0 64-64V96A64 64 0 0 0 384 32zm-3.9 319.3-.1 .1a32.4 32.4 0 0 1 -8.7 6.8 90.3 90.3 0 0 1 -20.6 8.2 12.7 12.7 0 0 0 -3.9 1.8c-2.2 1.9-2.1 4.6-4.4 8.6a23.1 23.1 0 0 1 -6.8 7.5c-6.7 4.6-14.2 4.9-22.2 5.2-7.2 .3-15.4 .6-24.7 3.7-3.8 1.2-7.8 3.7-12.4 6.5-11.3 6.9-26.7 16.4-52.3 16.4s-40.9-9.4-52.1-16.3c-4.7-2.9-8.7-5.4-12.5-6.6-9.3-3.1-17.5-3.4-24.7-3.7-8-.3-15.5-.6-22.2-5.2a23.1 23.1 0 0 1 -6-6.1c-3.2-4.6-2.9-7.8-5.3-9.9a13.4 13.4 0 0 0 -4.1-1.8 90 90 0 0 1 -20.3-8.1 32.9 32.9 0 0 1 -8.3-6.3c-6.6-6.8-8.3-14.8-5.7-21.8 3.4-9.3 11.6-12.1 19.4-16.3 14.8-8 26.3-18.1 34.4-29.9a68.2 68.2 0 0 0 6-10.6c.8-2.2 .8-3.3 .2-4.4a7.4 7.4 0 0 0 -2.2-2.2c-2.5-1.7-5.1-3.4-6.9-4.5-3.3-2.1-5.9-3.8-7.5-5-6.3-4.4-10.7-9-13.4-14.2a28.4 28.4 0 0 1 -1.4-23.6c4.1-10.9 14.5-17.7 27-17.7a37.1 37.1 0 0 1 7.8 .8c.7 .2 1.4 .3 2 .5-.1-7.4 .1-15.4 .7-23.1 2.4-27.3 11.9-41.6 21.9-53a86.8 86.8 0 0 1 22.3-17.9C188.3 100.4 205.3 96 224 96s35.8 4.4 50.9 13a87.2 87.2 0 0 1 22.2 17.9c10 11.4 19.5 25.7 21.9 53a231.2 231.2 0 0 1 .7 23.1c.7-.2 1.4-.3 2.1-.5a37.1 37.1 0 0 1 7.8-.8c12.5 0 22.8 6.8 27 17.7a28.4 28.4 0 0 1 -1.4 23.6c-2.7 5.2-7.1 9.9-13.4 14.2-1.7 1.2-4.3 2.9-7.5 5-1.8 1.2-4.5 2.9-7.2 4.7a6.9 6.9 0 0 0 -2 2c-.5 1-.5 2.2 .2 4.2a69 69 0 0 0 6.1 10.8c8.3 12.1 20.2 22.3 35.5 30.4 1.5 .8 3 1.5 4.4 2.3 .7 .3 1.6 .8 2.5 1.3 4.9 2.7 9.2 6 11.5 12.2C387.8 336.9 386.3 344.7 380.1 351.3zm-16.7-18.5c-50.3-24.3-58.3-61.9-58.7-64.7-.4-3.4-.9-6 2.8-9.5 3.6-3.3 19.5-13.2 24-16.3 7.3-5.1 10.5-10.2 8.2-16.5-1.7-4.3-5.7-6-10-6a18.5 18.5 0 0 0 -4 .4c-8 1.7-15.8 5.8-20.4 6.9a7.1 7.1 0 0 1 -1.7 .2c-2.4 0-3.3-1.1-3.1-4 .6-8.8 1.8-25.9 .4-41.9-1.9-22-9-32.9-17.4-42.6-4.1-4.6-23.1-24.7-59.5-24.7S168.5 134.4 164.5 139c-8.4 9.7-15.5 20.6-17.4 42.6-1.4 16-.1 33.1 .4 41.9 .2 2.8-.7 4-3.1 4a7.1 7.1 0 0 1 -1.7-.2c-4.5-1.1-12.3-5.1-20.3-6.9a18.5 18.5 0 0 0 -4-.4c-4.3 0-8.3 1.6-10 6-2.4 6.3 .8 11.4 8.2 16.5 4.4 3.1 20.4 13 24 16.3 3.7 3.4 3.2 6.1 2.8 9.5-.4 2.8-8.4 40.4-58.7 64.7-2.9 1.4-8 4.5 .9 9.3 13.9 7.6 23.1 6.8 30.3 11.4 6.1 3.9 2.5 12.4 6.9 15.4 5.5 3.8 21.6-.3 42.3 6.6 17.4 5.7 28.1 22 59 22s41.8-16.3 58.9-22c20.8-6.9 36.9-2.8 42.3-6.6 4.4-3.1 .8-11.5 6.9-15.4 7.2-4.6 16.4-3.8 30.3-11.5C371.4 337.4 366.3 334.3 363.4 332.8z\"/></svg>'),
                                                         (11, 'Pinterest', '<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 496 512\"><path d=\"M496 256c0 137-111 248-248 248-25.6 0-50.2-3.9-73.4-11.1 10.1-16.5 25.2-43.5 30.8-65 3-11.6 15.4-59 15.4-59 8.1 15.4 31.7 28.5 56.8 28.5 74.8 0 128.7-68.8 128.7-154.3 0-81.9-66.9-143.2-152.9-143.2-107 0-163.9 71.8-163.9 150.1 0 36.4 19.4 81.7 50.3 96.1 4.7 2.2 7.2 1.2 8.3-3.3 .8-3.4 5-20.3 6.9-28.1 .6-2.5 .3-4.7-1.7-7.1-10.1-12.5-18.3-35.3-18.3-56.6 0-54.7 41.4-107.6 112-107.6 60.9 0 103.6 41.5 103.6 100.9 0 67.1-33.9 113.6-78 113.6-24.3 0-42.6-20.1-36.7-44.8 7-29.5 20.5-61.3 20.5-82.6 0-19-10.2-34.9-31.4-34.9-24.9 0-44.9 25.7-44.9 60.2 0 22 7.4 36.8 7.4 36.8s-24.5 103.8-29 123.2c-5 21.4-3 51.6-.9 71.2C65.4 450.9 0 361.1 0 256 0 119 111 8 248 8s248 111 248 248z\"/></svg>'),
                                                         (12, 'Twitch', '<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 512 512\"><path d=\"M391.2 103.5H352.5v109.7h38.6zM285 103H246.4V212.8H285zM120.8 0 24.3 91.4V420.6H140.1V512l96.5-91.4h77.3L487.7 256V0zM449.1 237.8l-77.2 73.1H294.6l-67.6 64v-64H140.1V36.6H449.1z\"/></svg>'),
                                                         (13, 'LeBonCoin', '<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"42\" height=\"42\" viewBox=\"0 0 652 652\"><path d=\"M177.4,370.6c9.4,0,15.3-7.5,15.3-18.6c0-11.1-5.8-18.6-15.3-18.6c-9.4,0-15.2,7.4-15.2,18.6 C162.2,363.1,168,370.6,177.4,370.6z M184.5,315.3c18,0,29.9,15.5,30.1,36.4c0,21.2-12.4,37-30.6,37c-4.5,0.1-9-1-12.9-3.1 c-4-2.1-7.3-5.2-9.8-9H161v10.8h-19.8v-94.6c0-7.2,4.8-11.7,10.5-11.7s10.5,4.5,10.5,11.7v33.7h0.3c2.5-3.5,5.8-6.3,9.6-8.3 C176,316.2,180.2,315.2,184.5,315.3L184.5,315.3z M257.3,370.6c9.5,0,15.2-7.5,15.2-18.6c0-11.1-5.8-18.6-15.2-18.6 c-9.5,0-15.2,7.4-15.2,18.6C242,363.1,247.8,370.6,257.3,370.6z M257.3,315.3c22.3,0,36.7,15.2,36.7,36.7 c0,21.5-14.4,36.7-36.7,36.7s-36.7-15.2-36.7-36.7C220.6,330.5,235,315.3,257.3,315.3z M537.3,315.3c5.8,0,10.6,4.5,10.7,11.7v60.3 h-21.2V327C526.9,319.8,531.5,315.3,537.3,315.3z M537.3,278.4c3.4,0,6.7,1.4,9.1,3.8c2.4,2.4,3.8,5.7,3.8,9.1s-1.4,6.7-3.8,9.1 c-2.4,2.4-5.7,3.8-9.1,3.8h0c-3.4,0-6.7-1.4-9.1-3.8c-2.4-2.4-3.8-5.7-3.8-9.1c0-3.4,1.4-6.7,3.8-9.1 C530.7,279.8,533.9,278.4,537.3,278.4L537.3,278.4z M603.5,315.3c14.7,0,22.6,10.4,22.6,26.2v45.8h-21.2v-39.8 c0-9.3-4.4-12.4-9.9-12.4c-8.2,0-14.4,6.9-14.4,21.6v30.7h-21.2v-60.9c0-6.9,4.5-11,9.9-11c5.3,0,9.9,4.1,9.9,11v2.6h0.3 c2.5-4.2,6-7.6,10.2-10C593.9,316.5,598.7,315.3,603.5,315.3L603.5,315.3z M481.2,370.6c9.5,0,15.3-7.5,15.3-18.6 c0-11.1-5.8-18.6-15.3-18.6c-9.4,0-15.2,7.4-15.2,18.6C465.9,363.1,471.7,370.6,481.2,370.6z M481.2,315.3 c22.3,0,36.7,15.2,36.7,36.7c0,21.5-14.4,36.7-36.7,36.7c-22.3,0-36.7-15.2-36.7-36.7C444.5,330.5,458.9,315.3,481.2,315.3z M413.2,333.6c-9.2,0-14.8,6.8-14.8,18.3c0,11.6,5.6,18.3,14.8,18.3c2.6,0.1,5.1-0.5,7.4-1.7c2.3-1.2,4.1-3.1,5.4-5.3h0.3l15.4,9.9 c-5.3,10.4-16.5,15.5-29.2,15.5c-22,0-35.6-15.2-35.6-36.7c0-21.5,13.6-36.7,35.6-36.7c12.7,0,24,5,29.2,15.5l-15.4,9.9H426 c-1.3-2.2-3.2-4.1-5.4-5.3C418.3,334.1,415.8,333.5,413.2,333.6L413.2,333.6z M346.4,315.3c14.7,0,22.6,10.4,22.6,26.2v45.8h-21.2 v-39.8c0-9.3-4.4-12.4-9.9-12.4c-8.2,0-14.4,6.9-14.4,21.6v30.7h-21.2v-61c0-6.9,4.5-11,9.8-11c5.4,0,9.9,4.1,9.9,11v2.6h0.3 c2.5-4.2,6-7.6,10.2-10C336.8,316.5,341.6,315.3,346.4,315.3L346.4,315.3z M83.7,347.8c0.1,2.7,0.5,5.3,1.4,7.9l23.6-17.5 c-1-1.9-2.6-3.5-4.5-4.6c-1.9-1.1-4.1-1.6-6.3-1.5C91.2,332,83.7,337.1,83.7,347.8L83.7,347.8z M104.2,371.4 c3.8-0.2,7.4-1.4,10.6-3.4c3.2-2,5.7-4.9,7.5-8.2l13.9,10.1c-5.3,10.8-16.2,18.8-34.5,18.8c-8.7,0-17.1-3.2-23.6-8.9 c-9.2,6.1-17.1,8.8-25,8.8c-15.9,0-27.1-10.6-27.1-26.6v-69.4c0-7.2,4.8-11.7,10.5-11.7c5.7,0,10.5,4.5,10.5,11.7v67.7 c0,6.8,3,10.5,9.3,10.5c3.4,0,7.1-2,11.9-4.6c-2.3-5.5-3.5-11.3-3.4-17.2c0-15,10.7-33.6,33.1-33.6c19.4,0,29.9,11.3,34.2,25.5 l-37.4,26.8C97.2,370,100.7,371.4,104.2,371.4L104.2,371.4z\"/></svg>'),
                                                         (14, 'Abritel', '<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"42\" height=\"42\" viewBox=\"0 0 652 652\"><path d=\"M54.8,350.9l-14.5,44.1H21.6l47.6-140.2h21.9L139,394.9h-19.2l-15-44.1H54.8z M100.7,336.7L87,296.4 c-3.1-9.2-5.2-17.4-7.4-25.6h-0.4c-2,8.6-4.4,17-7.2,25.4l-13.7,40.6H100.7z M153.2,395.1c0.4-6.8,0.8-17,0.8-26V247.4h18v63.3h0.4 c6.4-11.3,18.1-18.5,34.5-18.5c25,0,42.6,20.9,42.4,51.4c0,36-22.7,53.9-45.1,53.9c-14.5,0-26.2-5.5-33.6-18.8H170l-0.8,16.6h-16 V395.1z M172,354.8c0.1,15.5,12.7,28.1,28.3,28.1c19,0,30.5-15.6,30.5-38.7c0-20.1-10.5-37.5-29.9-37.5 c-13.4,0.3-24.8,9.6-27.9,22.6c-0.4,2-1,4.5-1,7.6V354.8z M268.1,325.9c0-11.9-0.2-22.1-0.8-31.3h16l0.6,19.7h0.8 c4.5-13.5,15.6-22.1,27.9-22.1c2,0,3.5,0.2,5.1,0.6v17.2c-1.8-0.4-3.7-0.6-6.1-0.6c-12.9,0-22.1,9.8-24.6,23.5 c-0.4,2.5-0.8,5.3-0.8,8.6v53.7h-18V325.9z M353.1,266c0.2,6.1-4.3,11.3-11.6,11.3c-6.2,0.1-11.3-5.1-11.1-11.3 c-0.1-6.4,5.1-11.6,11.4-11.4C348.8,254.6,353.2,259.7,353.1,266L353.1,266z M332.9,395.1V294.6h18.2v100.6H332.9z M399.5,265.5 v28.8h26.2v13.9h-26.2v54.3c0,12.5,3.5,19.5,13.7,19.5c4.7,0,8.4-0.6,10.7-1.2l0.8,13.7c-3.5,1.4-9.2,2.4-16.2,2.4 c-8.6,0-15.4-2.6-19.7-7.7c-5.1-5.3-7.2-14.4-7.2-26.3V308h-15.5v-13.9h15.5v-24.2L399.5,265.5L399.5,265.5z M451.1,348 c0.4,24.8,16.2,35,34.6,35c13.1,0,21.1-2.3,27.9-5.1l3.1,13.1c-6.4,2.8-17.4,6.1-33.4,6.1c-30.9,0-49.6-20.3-49.6-50.8 c0-30.4,17.8-54.3,47.1-54.3c32.8,0,41.6,28.9,41.6,47.3c0,3.7-0.4,6.5-0.6,8.6H451.1z M504.6,334.9c0.2-11.7-4.7-29.7-25.4-29.7 c-18.4,0-26.7,17-28.1,29.7H504.6z M540.8,247.4h18.3v147.7h-18.3V247.4z M621.6,263.4c0,10.8-8.9,19.5-19.7,19.3 c-10.8,0.2-19.6-8.5-19.7-19.3c0-10.6,8.8-19.1,19.9-19.1C612.8,244.2,621.5,252.7,621.6,263.4L621.6,263.4z M587.1,263.4 c0,8.6,6.4,15.4,15,15.4c8.4,0.2,14.5-6.7,14.5-15.2c0-8.6-6.3-15.4-14.7-15.4C593.6,248.2,586.9,255.1,587.1,263.4L587.1,263.4z M598.8,273.4h-4.3v-19.1c1.8-0.2,4.3-0.6,7.4-0.6c3.7,0,5.3,0.6,6.6,1.5c1.2,0.8,2,2.2,2,4.1c0,2.3-1.8,3.9-3.9,4.5v0.2 c1.8,0.6,2.9,2,3.5,4.7c0.6,2.8,1,3.9,1.5,4.7h-4.7c-0.6-0.8-0.8-2.2-1.4-4.7c-0.4-2.1-1.4-2.9-3.9-2.9h-2.1v7.6H598.8z M599.1,262.6h2c2.5,0,4.5-0.8,4.5-2.9c0-1.8-1.2-2.9-4.1-2.9c-1.2,0-2.1,0.2-2.4,0.2V262.6z M599.1,262.6\"/></svg>');

-- --------------------------------------------------------

--
-- Structure de la table `template`
--

DROP TABLE IF EXISTS `template`;
CREATE TABLE IF NOT EXISTS `template` (
                                          `idTemplate` int NOT NULL AUTO_INCREMENT,
                                          `nom` varchar(50) NOT NULL,
    PRIMARY KEY (`idTemplate`)
    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `template`
--

INSERT INTO `template` (`idTemplate`, `nom`) VALUES
                                                 (1, 'pomme'),
                                                 (2, 'fraise'),
                                                 (3, 'peche'),
                                                 (4, 'Template4'),
                                                 (5, 'Template5');

-- --------------------------------------------------------

--
-- Structure de la table `vue`
--

DROP TABLE IF EXISTS `vue`;
CREATE TABLE IF NOT EXISTS `vue` (
                                     `idVue` int NOT NULL AUTO_INCREMENT,
                                     `date` date NOT NULL,
                                     `idCarte` int NOT NULL,
                                     `idEmp` int DEFAULT NULL,
                                     PRIMARY KEY (`idVue`),
    KEY `vue_carte_FK` (`idCarte`),
    KEY `vue_employer_FK` (`idEmp`)
    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `vue`
--

INSERT INTO `vue` (`idVue`, `date`, `idCarte`, `idEmp`) VALUES
                                                            (1, '2024-01-01', 1, 1),
                                                            (2, '2024-01-02', 1, 2),
                                                            (3, '2024-01-03', 1, NULL),
                                                            (4, '2024-01-04', 1, NULL),
                                                            (6, '2024-02-01', 2, 6),
                                                            (7, '2024-02-02', 2, 7),
                                                            (8, '2024-02-03', 2, 8),
                                                            (9, '2024-02-04', 2, 9),
                                                            (11, '2024-02-06', 2, 1),
                                                            (12, '2024-02-07', 2, 2),
                                                            (13, '2024-02-08', 2, 3),
                                                            (14, '2024-03-01', 3, 4),
                                                            (16, '2024-03-03', 3, 6),
                                                            (17, '2024-03-04', 3, 7),
                                                            (18, '2024-03-05', 3, 8),
                                                            (19, '2024-03-06', 3, 9),
                                                            (21, '2024-03-08', 3, 1),
                                                            (22, '2024-03-09', 3, 2),
                                                            (23, '2024-03-10', 3, 3),
                                                            (24, '2024-04-01', 4, 4),
                                                            (29, '2024-04-06', 4, 9),
                                                            (31, '2024-04-08', 4, 1),
                                                            (32, '2024-04-09', 4, 2),
                                                            (33, '2024-04-10', 4, 3),
                                                            (34, '2024-04-11', 4, 4),
                                                            (53, '2024-06-05', 1, 3),
                                                            (54, '2024-06-06', 1, 4),
                                                            (56, '2024-06-08', 1, NULL),
                                                            (57, '2024-06-09', 1, NULL),
                                                            (58, '2024-06-10', 1, NULL),
                                                            (59, '2024-06-11', 1, NULL),
                                                            (61, '2024-06-13', 1, NULL),
                                                            (62, '2024-06-14', 1, NULL),
                                                            (63, '2024-07-01', 2, 3),
                                                            (64, '2024-07-02', 2, 4),
                                                            (66, '2024-07-04', 2, 6),
                                                            (67, '2024-07-05', 2, 7),
                                                            (68, '2024-07-06', 2, 8),
                                                            (69, '2024-07-07', 2, 9),
                                                            (71, '2024-07-09', 2, 1),
                                                            (72, '2024-07-10', 2, 2),
                                                            (73, '2024-07-11', 2, 3),
                                                            (74, '2024-07-12', 2, 4),
                                                            (76, '2024-07-14', 2, 6),
                                                            (77, '2024-07-15', 2, 7),
                                                            (78, '2024-08-01', 3, 8),
                                                            (82, '2024-08-05', 3, 2),
                                                            (83, '2024-08-06', 3, 3),
                                                            (84, '2024-08-07', 3, 4),
                                                            (92, '2024-08-15', 3, 2),
                                                            (93, '2024-08-16', 3, 3),
                                                            (94, '2024-09-01', 4, 4),
                                                            (96, '2024-09-03', 4, 6),
                                                            (97, '2024-09-04', 4, 7),
                                                            (98, '2024-09-05', 4, 8),
                                                            (101, '2024-09-08', 4, 1),
                                                            (102, '2024-09-09', 4, 2),
                                                            (103, '2024-09-10', 4, 3),
                                                            (108, '2024-09-15', 4, 8),
                                                            (109, '2024-09-16', 4, 9),
                                                            (133, '2024-11-05', 1, 3),
                                                            (134, '2024-11-06', 1, 4),
                                                            (136, '2024-11-08', 1, NULL),
                                                            (137, '2024-11-09', 1, NULL),
                                                            (142, '2024-11-14', 1, 2),
                                                            (143, '2024-11-15', 1, NULL),
                                                            (144, '2024-11-16', 1, 4),
                                                            (146, '2024-11-18', 1, NULL),
                                                            (147, '2024-11-19', 1, NULL),
                                                            (148, '2024-12-01', 2, 8),
                                                            (149, '2024-12-02', 2, 9),
                                                            (151, '2024-12-04', 2, 1),
                                                            (152, '2024-12-05', 2, 2),
                                                            (153, '2024-12-06', 2, 3),
                                                            (154, '2024-12-07', 2, 4),
                                                            (156, '2024-12-09', 2, 6),
                                                            (168, '2025-01-21', 1, NULL),
                                                            (169, '2025-01-22', 1, NULL);


-- --------------------------------------------------------

--
-- Structure de la table `custom_link`
--

DROP TABLE IF EXISTS `custom_link`;
CREATE TABLE IF NOT EXISTS `custom_link` (
                                             `id_link` int NOT NULL AUTO_INCREMENT,
                                             `nom` varchar(150) NOT NULL,
    `lien` varchar(300) DEFAULT NULL,
    `idCarte` int DEFAULT NULL,
    PRIMARY KEY (`id_link`),
    KEY `idCarte` (`idCarte`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `carte`
--
ALTER TABLE `carte`
    ADD CONSTRAINT `carte_compte_FK` FOREIGN KEY (`idCompte`) REFERENCES `compte` (`idCompte`) ON DELETE CASCADE,
  ADD CONSTRAINT `carte_template_FK` FOREIGN KEY (`idTemplate`) REFERENCES `template` (`idTemplate`);

--
-- Contraintes pour la table `custom_link`
--
ALTER TABLE `custom_link`
    ADD CONSTRAINT `fk_custom_link_carte` FOREIGN KEY (`idCarte`) REFERENCES `carte` (`idCarte`);

--
-- Contraintes pour la table `employer`
--
ALTER TABLE `employer`
    ADD CONSTRAINT `employer_carte_FK` FOREIGN KEY (`idCarte`) REFERENCES `carte` (`idCarte`) ON DELETE CASCADE;

--
-- Contraintes pour la table `logs`
--
ALTER TABLE `logs`
    ADD CONSTRAINT `logs_compte_FK` FOREIGN KEY (`idCompte`) REFERENCES `compte` (`idCompte`) ON DELETE CASCADE;

--
-- Contraintes pour la table `reactivation`
--
ALTER TABLE `reactivation`
    ADD CONSTRAINT `reactivation_compte_FK` FOREIGN KEY (`idCompte`) REFERENCES `compte` (`idCompte`) ON DELETE CASCADE;

--
-- Contraintes pour la table `recuperation`
--
ALTER TABLE `recuperation`
    ADD CONSTRAINT `recuperation_compte_FK` FOREIGN KEY (`idCompte`) REFERENCES `compte` (`idCompte`) ON DELETE CASCADE;

--
-- Contraintes pour la table `rediriger`
--
ALTER TABLE `rediriger`
    ADD CONSTRAINT `rediriger_carte_FK` FOREIGN KEY (`idCarte`) REFERENCES `carte` (`idCarte`) ON DELETE CASCADE,
  ADD CONSTRAINT `rediriger_social_FK` FOREIGN KEY (`idSocial`) REFERENCES `social` (`idSocial`);

--
-- Contraintes pour la table `vue`
--
ALTER TABLE `vue`
    ADD CONSTRAINT `vue_carte_FK` FOREIGN KEY (`idCarte`) REFERENCES `carte` (`idCarte`) ON DELETE CASCADE,
  ADD CONSTRAINT `vue_employer_FK` FOREIGN KEY (`idEmp`) REFERENCES `employer` (`idEmp`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
