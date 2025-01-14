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
    `descirptif` varchar(500) DEFAULT NULL,
    `LienCommande` varchar(150) DEFAULT NULL,
    `idCompte` int NOT NULL,
    `idTemplate` int NOT NULL,
    PRIMARY KEY (`idCarte`),
    KEY `carte_compte_FK` (`idCompte`),
    KEY `carte_template_FK` (`idTemplate`)
    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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

-- --------------------------------------------------------

--
-- Structure de la table `rediriger`
--

DROP TABLE IF EXISTS `rediriger`;
CREATE TABLE IF NOT EXISTS `rediriger` (
                                           `idSocial` int NOT NULL,
                                           `idCarte` int NOT NULL,
                                           `lien` varchar(500) DEFAULT NULL,
    `activer` tinyint(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (`idSocial`,`idCarte`),
    KEY `rediriger_carte_FK` (`idCarte`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `social`
--

DROP TABLE IF EXISTS `social`;
CREATE TABLE IF NOT EXISTS `social` (
                                        `idSocial` int NOT NULL AUTO_INCREMENT,
                                        `nom` varchar(500) NOT NULL,
    `lienLogo` varchar(500) NOT NULL,
    PRIMARY KEY (`idSocial`)
    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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

-- --------------------------------------------------------

--
-- Structure de la table `message`
--

DROP TABLE IF EXISTS `message`;
CREATE TABLE IF NOT EXISTS `message` (
                                         id INT AUTO_INCREMENT PRIMARY KEY,
                                         message VARCHAR(500) NOT NULL,
    afficher BOOLEAN NOT NULL
    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
