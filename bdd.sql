-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 07 fév. 2025 à 09:57
-- Version du serveur : 8.3.0
-- Version de PHP : 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données : `wisikard2`
--

-- --------------------------------------------------------

--
-- Structure de la table `carte`
--

DROP TABLE IF EXISTS `carte`;
CREATE TABLE IF NOT EXISTS `carte` (
                                       `idCarte` int NOT NULL AUTO_INCREMENT,
                                       `nomEntreprise` varchar(255) NOT NULL,
    `titre` varchar(150) CHARACTER SET utf8  DEFAULT NULL,
    `tel` varchar(25) NULL,
    `ville` varchar(255) NULL,
    `imgPres` varchar(100) DEFAULT NULL,
    `imgLogo` varchar(100) DEFAULT NULL,
    `pdf` varchar(100) DEFAULT NULL,
    `nomBtnPdf` varchar(100) DEFAULT NULL,
    `couleur1` varchar(10) DEFAULT NULL,
    `couleur2` varchar(10) DEFAULT NULL,
    `descriptif` text CHARACTER SET utf8 ,
    `LienCommande` varchar(150) DEFAULT NULL,
    `lienQr` varchar(500) NOT NULL,
    `lienPdf` varchar(500) DEFAULT NULL,
    `lienAvis` varchar(500) DEFAULT NULL,
    'lienSiteWeb' varchar(500) DEFAULT NULL,
    `font` varchar(500) CHARACTER SET utf8  NOT NULL DEFAULT 'roboto',
    `idCompte` int NOT NULL,
    `idTemplate` int NOT NULL,
    PRIMARY KEY (`idCarte`),
    KEY `carte_compte_FK` (`idCompte`),
    KEY `carte_template_FK` (`idTemplate`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `custom_link`
--

DROP TABLE IF EXISTS `custom_link`;
CREATE TABLE IF NOT EXISTS `custom_link` (
                                             `id_link` int NOT NULL AUTO_INCREMENT,
                                             `nom` varchar(150) NOT NULL,
    `lien` varchar(300) DEFAULT NULL,
    `activer` tinyint(1) NOT NULL DEFAULT '1',
    `idCarte` int DEFAULT NULL,
    PRIMARY KEY (`id_link`),
    KEY `idCarte` (`idCarte`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `employer`
--

DROP TABLE IF EXISTS `employer`;
CREATE TABLE IF NOT EXISTS `employer` (
                                          `idEmp` int NOT NULL AUTO_INCREMENT,
                                          `nom` varchar(100) NOT NULL,
    `prenom` varchar(100) NOT NULL,
    `fonction` varchar(100) DEFAULT NULL,
    `idCarte` int DEFAULT NULL,
    `mail` varchar(100) DEFAULT NULL,
    `telephone` varchar(100) DEFAULT NULL,
    PRIMARY KEY (`idEmp`),
    KEY `employer_carte_FK` (`idCarte`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `horaires`
--

DROP TABLE IF EXISTS `horaires`;
CREATE TABLE IF NOT EXISTS `horaires` (
                                          `id` int NOT NULL AUTO_INCREMENT,
                                          `idCarte` int NOT NULL,
                                          `jour` varchar(255) NOT NULL,
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `ouverture_matin` time DEFAULT NULL,
    `fermeture_matin` time DEFAULT NULL,
    `ouverture_aprmidi` time DEFAULT NULL,
    `fermeture_aprmidi` time DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `idCarte` (`idCarte`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `inscript_attente`
--

DROP TABLE IF EXISTS `inscript_attente`;
CREATE TABLE IF NOT EXISTS `inscript_attente` (
                                                  `id_inscripAttente` int NOT NULL AUTO_INCREMENT,
                                                  `nom_entre` varchar(150) NOT NULL,
    `mail` varchar(150) NOT NULL,
    `mdp` varchar(150) NOT NULL,
    `role` varchar(150) NOT NULL,
    `date_inscription` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id_inscripAttente`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `rediriger`
--

DROP TABLE IF EXISTS `rediriger`;
CREATE TABLE IF NOT EXISTS `rediriger` (
                                           `idSocial` int NOT NULL,
                                           `idCarte` int NOT NULL,
                                           `lien` varchar(500) DEFAULT NULL,
    `activer` tinyint(1) NOT NULL DEFAULT '1',
    PRIMARY KEY (`idSocial`,`idCarte`),
    KEY `rediriger_carte_FK` (`idCarte`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `social`
--

DROP TABLE IF EXISTS `social`;
CREATE TABLE IF NOT EXISTS `social` (
                                        `idSocial` int NOT NULL AUTO_INCREMENT,
                                        `nom` varchar(500) CHARACTER SET utf8  NOT NULL,
    `lienLogo` text CHARACTER SET utf8  NOT NULL,
    PRIMARY KEY (`idSocial`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `template`
--

DROP TABLE IF EXISTS `template`;
CREATE TABLE IF NOT EXISTS `template` (
                                          `idTemplate` int NOT NULL AUTO_INCREMENT,
                                          `nom` varchar(50) NOT NULL,
    PRIMARY KEY (`idTemplate`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `guide`
--

DROP TABLE IF EXISTS `guide`;
CREATE TABLE IF NOT EXISTS `guide` (
                                          `id_guide` int NOT NULL AUTO_INCREMENT,
                                          `titre` varchar(100) NOT NULL,
    PRIMARY KEY (`id_guide`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `txt`
--

DROP TABLE IF EXISTS `txt`;
CREATE TABLE IF NOT EXISTS `txt` (
                                     `id_txt` int NOT NULL AUTO_INCREMENT,
                                     `num_txt` int NOT NULL,
                                     `categorie` varchar(50) NOT NULL,
    `id_guide` int NOT NULL,
    `txt` varchar(250) NOT NULL,
    PRIMARY KEY (`id_txt`),
    KEY `id_guide` (`id_guide`)
    ) ENGINE=InnoDB AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Structure de la table `IMG`
--

DROP TABLE IF EXISTS `img`;
CREATE TABLE IF NOT EXISTS `img` (
                                      `id_img` int NOT NULL AUTO_INCREMENT,
                                      `num_img` int NOT NULL,
                                      `categorie` VARCHAR(50) NOT NULL,
    PRIMARY KEY (`id_img`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
                                     `ip_address` text DEFAULT NULL,
                                     PRIMARY KEY (`idVue`),
    KEY `vue_carte_FK` (`idCarte`),
    KEY `vue_employer_FK` (`idEmp`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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

ALTER TABLE custom_link
    ADD CONSTRAINT fk_custom_link_carte
        FOREIGN KEY (idCarte) REFERENCES carte (idCarte)
            ON DELETE CASCADE;

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
