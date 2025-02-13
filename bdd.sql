-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 13 fév. 2025 à 08:32
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

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
    `titre` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
    `tel` varchar(25) DEFAULT NULL,
    `ville` varchar(255) DEFAULT NULL,
    `imgPres` varchar(100) DEFAULT NULL,
    `imgLogo` varchar(100) DEFAULT NULL,
    `pdf` varchar(100) DEFAULT NULL,
    `nomBtnPdf` varchar(100) DEFAULT NULL,
    `couleur1` varchar(10) DEFAULT NULL,
    `couleur2` varchar(10) DEFAULT NULL,
    `descriptif` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
    `LienCommande` varchar(150) DEFAULT NULL,
    `lienQr` varchar(500) NOT NULL,
    `lienPdf` varchar(500) DEFAULT NULL,
    `lienAvis` varchar(500) DEFAULT NULL,
    `lienSiteWeb` varchar(500) DEFAULT NULL,
    `font` varchar(500) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT 'roboto',
    `idCompte` int NOT NULL,
    `idTemplate` int NOT NULL,
    PRIMARY KEY (`idCarte`),
    KEY `carte_compte_FK` (`idCompte`),
    KEY `carte_template_FK` (`idTemplate`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `guide`
--

DROP TABLE IF EXISTS `guide`;
CREATE TABLE IF NOT EXISTS `guide` (
                                       `id_guide` int NOT NULL AUTO_INCREMENT,
                                       `titre` varchar(100) NOT NULL,
    PRIMARY KEY (`id_guide`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;


INSERT INTO `guide` (`id_guide`, `titre`) VALUES
                                              (1, 'Modifier les Informations de l\'Entreprise'),
(2, 'Changer la Police'),
(3, 'Changer le Titre et la Description'),
(4, 'Gestion des Horaires'),
(5, 'Changer la Couleur du QR Code'),
(6, 'Télécharger le QR Code de l\'Entreprise'),
                                              (7, 'Télécharger le QR Code du PDF'),
                                              (8, 'Changer le Thème de la Carte'),
                                              (9, 'Choisir l\'Année'),
(10, 'Choisir la Semaine'),
(11, 'Nombre de Vues par Employé'),
(12, 'Nombre Global de Vues'),
(13, 'Nombre de Vues par Semaine'),
(14, 'Ajouter un Réseau Social'),
(15, 'Mettre à jour un Réseau Social'),
(16, 'Activer/Désactiver un Réseau Social'),
(17, 'Ajouter un Autre Réseau Social'),
(18, 'Recherche'),
(19, 'Modifier les Informations de l\'Employé'),
                                              (20, 'Supprimer un Employé'),
                                              (21, 'Rafraîchir le QR Code de l\'Employé'),
(22, 'Ajouter un Employé'),
(23, 'Ajouter/Supprimer un Logo'),
(24, 'Ajouter/Supprimer un PDF'),
(25, 'Ajouter/Supprimer des Vidéos YouTube'),
(26, 'Ajouter/Supprimer un Lien d\'Avis Google'),
                                              (27, 'Ajouter/Supprimer une URL de Prise de Rendez-vous'),
                                              (28, 'Ajouter/Supprimer une Galerie Photo'),
                                              (29, 'Télécharger le QR Code de l\'employé');
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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `img`
--

DROP TABLE IF EXISTS `img`;
CREATE TABLE IF NOT EXISTS `img` (
                                     `id_img` int NOT NULL AUTO_INCREMENT,
                                     `num_img` int NOT NULL,
                                     `chemin` varchar(50) NOT NULL,
    `id_guide` int NOT NULL,
    PRIMARY KEY (`id_img`),
    KEY `img_guide_FK` (`id_guide`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

INSERT INTO `img` (`id_img`, `num_img`, `chemin`, `id_guide`) VALUES
(4, 2, ''public/images/Accueil/modif_info.png'', 1),
(5, 3, ''public/images/Accueil/formulaire_modif.png'', 1),
(6, 2, ''public/images/Accueil/police.png'', 2),
(7, 2, ''public/images/Accueil/titre_description.png'', 3),
(8, 3, ''public/images/Accueil/modif_titre_description.png'', 3),
(9, 2, ''public/images/Accueil/horaire_modif.png'', 4),
(10, 2, ''public/images/Accueil/couleurQrcode.png'', 5),
(11, 3, ''public/images/Accueil/couleurQrcode_modif.png'', 5),
(12, 5, ''public/images/Accueil/enregistrer.png'', 5),
(13, 3, ''public/images/Accueil/dlQrCodeEntre.png'', 6),
(14, 3, ''public/images/Accueil/dlQrCodePdf.png'', 7),
(15, 2, ''public/images/Accueil/changer_template.png'', 8),
(16, 2, ''public/images/Statistique/annee.png'', 9),
(17, 2, ''public/images/Statistique/semaine.png'', 10),
(18, 2, ''public/images/Statistique/graphique_employe.png'', 11),
(19, 1, ''public/images/Statistique/nb_vue_global.png'', 12),
(20, 1, ''public/images/Statistique/nb_vue_semaine.png'', 13),
(21, 3, ''public/images/Accueil/police_2.png'', 2),
(22, 4, ''public/images/Accueil/enregistrer_2.png'', 2),
(23, 4, ''public/images/Accueil/enregistrer_2.png'', 2),
(24, 3, ''public/images/Reseaux/reseau_sociaux_1.png'', 14),
(25, 4, ''public/images/Reseaux/reseau_sociaux_2.png'', 14),
(26, 5, ''public/images/Reseaux/reseau_sociaux_3.png'', 14),
(27, 2, ''public/images/Reseaux/url_reseau_sociaux.png'', 14),
(28, 2, ''public/images/Reseaux/modif_reseau.png'', 15),
(29, 3, ''public/images/Reseaux/modif_reseau_2.png'', 15),
(30, 2, ''public/images/Reseaux/activer_reseaux.png'', 16),
(31, 3, ''public/images/Reseaux/desactiver_reseaux.png'', 16),
(32, 3, ''public/images/Reseaux/autre_reseau.png'', 17),
(33, 2, ''public/images/Reseaux/bouton_autre.png'', 17),
(34, 4, ''public/images/Reseaux/bouton_ajouter.png'', 17),
(35, 1, ''public/images/Employe/rechercher.png'', 18),
(36, 2, ''public/images/Employe/card_employe.png'', 19),
(37, 3, ''public/images/Employe/modif_employe.png'', 19),
(38, 4, ''public/images/Employe/modifier.png'', 19),
(39, 2, ''public/images/Employe/suppri_employe.png'', 20),
(40, 2, ''public/images/Employe/QrCodeEmploye.png'', 21),
(41, 2, ''public/images/Employe/Dl_QrCodeEmploye.png'', 29),
(42, 2, ''public/images/Employe/ajoutEmploye.png'', 22),
(43, 3, ''public/images/Employe/info_inscription.png'', 22),
(44, 4, ''public/images/Employe/info_inscription_ok.png'', 22),
(45, 3, ''public/images/Contenu/Logo.png'', 23),
(46, 4, ''public/images/Contenu/fichier_logo.png'', 23),
(47, 5, ''public/images/Contenu/enre_logo.png'', 23),
(48, 7, ''public/images/Contenu/suppri_logo.png'', 23);

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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `social`
--

DROP TABLE IF EXISTS `social`;
CREATE TABLE IF NOT EXISTS `social` (
                                        `idSocial` int NOT NULL AUTO_INCREMENT,
                                        `nom` varchar(500) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
    `lienLogo` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
    PRIMARY KEY (`idSocial`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `template`
--

DROP TABLE IF EXISTS `template`;
CREATE TABLE IF NOT EXISTS `template` (
                                          `idTemplate` int NOT NULL AUTO_INCREMENT,
                                          `nom` varchar(50) NOT NULL,
    PRIMARY KEY (`idTemplate`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

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
    `txt` varchar(500) NOT NULL,
    PRIMARY KEY (`id_txt`),
    KEY `id_guide` (`id_guide`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


INSERT INTO `txt` (`id_txt`, `num_txt`, `categorie`, `id_guide`, `txt`) VALUES
(1, 1, '', 1, 'Cette fonction permet de mettre à jour les données essentielles de votre entreprise. Elle est accessible facilement depuis la page d\'accueil et offre un formulaire intuitif pour modifier toutes les informations importantes de votre établissement.'),
                                              (2, 2, '', 1, '1. Sur la page d\'accueil, cliquez sur le bouton \"Modifier\" en bas à droite.'),
(3, 3, '', 1, '2. Vous arriverez sur un formulaire où vous pourrez modifier vos informations.'),
                                               (4, 4, '', 1, '3. Modifiez les informations que vous souhaitez.'),
                                               (5, 5, '', 1, '4. Appuyez sur le bouton \"Modifier\" quand vous aurez fini.'),
                                               (6, 1, '', 2, 'Cette fonctionnalité vous permet de personnaliser l\'apparence textuelle de votre carte numérique. Vous pouvez choisir parmi différentes polices de caractères pour donner un style unique à votre présentation.'),
(7, 2, '', 2, '1. Cliquez sur le bordereau blanc.'),
(8, 3, '', 2, '2. Sélectionnez une police parmi celles affichées.'),
(9, 4, '', 2, '3. Cliquez ensuite sur le bouton \"Modifier\", et le tour est joué !'),
(10, 1, '', 3, 'Cette option vous permet de modifier les éléments textuels principaux de votre carte. Vous pouvez ajuster votre titre pour qu\'il soit plus accrocheur et adapter votre description pour mieux présenter votre activité.'),
(11, 2, '', 3, '1. Cliquez sur le bouton \"Ajouter / Modifier Titre et Description\".'),
(12, 3, '', 3, '2. Vous arriverez sur un formulaire où vous pourrez ajouter ou modifier vos informations.'),
(13, 4, '', 3, '3. Cliquez ensuite sur le bouton \"Enregistrer\".'),
(14, 1, '', 4, 'Cette fonction est essentielle pour maintenir vos horaires d\'ouverture à jour. Le système permet une modification simple et rapide des horaires jour par jour, avec une interface intuitive utilisant des icônes d\'horloge.'),
(15, 2, '', 4, '1. Cliquez sur l\'horaire à modifier ou sur l\'horloge à droite du jour souhaité.'),
(16, 3, '', 4, '2. Cliquez ensuite sur le bouton \"Enregistrer\" en bas à droite.'),
(17, 1, '', 5, 'Cette option de personnalisation permet d\'adapter l\'apparence de votre QR Code à votre charte graphique. Vous pouvez modifier deux couleurs différentes pour créer un QR Code unique et en harmonie avec votre identité visuelle.'),
(18, 2, '', 5, '1. Cliquez sur l\'une des deux couleurs que vous souhaitez modifier en dessous du QR Code.'),
(19, 3, '', 5, '2. Sélectionnez ensuite la couleur souhaitée.'),
(20, 4, '', 5, '3. Cliquez en dehors de la sélection de couleur.'),
(21, 5, '', 5, '4. Cliquez sur le bouton \"Enregistrer\".'),
(22, 1, '', 6, 'Cette fonction offre deux options de téléchargement de votre QR Code Entreprise : en couleur ou en noir et blanc. Cela vous permet d\'utiliser le format le plus adapté à vos besoins de communication.'),
(23, 2, '', 6, '- Pour télécharger le QR Code de l\'entreprise en couleur, cliquez sur le bouton ci-dessous.'),
(24, 3, '', 6, '- Pour télécharger le QR Code de l\'entreprise en noir et blanc, cliquez sur le bouton ci-dessous.'),
(25, 1, '', 7, 'Cette fonction offre deux options de téléchargement de votre QR Code PDF : en couleur ou en noir et blanc. Cela vous permet d\'utiliser le format le plus adapté à vos besoins de communication.'),
(26, 2, '', 7, '- Pour télécharger le QR Code de votre PDF préalablement enregistré en couleur, cliquez sur le bouton ci-dessous.'),
(27, 3, '', 7, '- Pour télécharger le QR Code de votre PDF préalablement enregistré en noir et blanc, cliquez sur le bouton ci-dessous.'),
(28, 1, '', 8, 'Cette fonction vous permet de modifier l\'apparence générale de votre carte numérique en sélectionnant différents thèmes visuels, permettant ainsi de personnaliser davantage votre présentation.'),
(29, 2, '', 8, 'Cliquez sur le petit bouton au-dessus du thème désiré.'),
(30, 1, '', 9, 'Cette fonction permet de filtrer vos données statistiques par année. Via une liste déroulante simple, vous pouvez sélectionner l\'année dont vous souhaitez consulter les statistiques, ce qui permet d\'analyser l\'évolution de votre activité sur différen'),
(31, 2, '', 9, '1. Cliquez sur la liste déroulante.'),
(32, 3, '', 9, '2. Choisissez l\'année que vous souhaitez.'),
(33, 1, '', 10, 'Cette fonctionnalité offre une navigation intuitive entre les différentes semaines de l\'année sélectionnée. Grâce aux flèches directionnelles, vous pouvez facilement parcourir les données semaine par semaine pour une analyse plus précise.'),
(34, 2, '', 10, '1. Cliquez sur les flèches à droite ou à gauche pour naviguer entre les semaines.'),
(35, 1, '', 11, 'Cette fonction affiche les statistiques individuelles de consultation pour chaque employé. Elle permet de suivre la performance de chaque membre de l\'équipe à travers le nombre de scans de leurs QR Codes personnels. Si aucune donnée n\'est visible, cela indique soit l\'absence d\'employés enregistrés, soit qu\'aucun scan n\'a encore été effectué.'),
(36, 1, '', 12, 'Cette métrique importante présente le total des consultations pour l\'année sélectionnée. C\'est un indicateur clé qui permet d\'avoir une vue d\'ensemble de la visibilité de votre entreprise sur une période annuelle.'),
(37, 1, '', 13, 'Cette fonction offre une analyse plus granulaire en affichant le nombre de consultations pour la semaine sélectionnée. Elle permet de suivre les tendances à court terme et d\'identifier les périodes de forte ou faible activité.'),
(38, 1, '', 14, 'Cette fonction permet d\'intégrer vos réseaux sociaux existants à votre profil. Le processus est simple : vous sélectionnez d\'abord le réseau social désiré (en vérifiant bien l\'icône correspondante), puis vous ajoutez l\'URL de votre page. L\'activation'),
(39, 2, '', 14, '1. Choisissez le réseau que vous voulez ajouter en faisant attention à l\'icône en haut à gauche.'),
(40, 3, '', 14, '2. Entrez votre URL menant vers votre page là où il est écrit \"Lien du réseau social\".'),
(41, 4, '', 14, '3. Cochez ensuite la case \"Activer\" en dessous.'),
(42, 5, '', 14, '4. Appuyez sur le bouton \"Mettre à jour\".'),
(43, 1, '', 15, 'Cette fonctionnalité permet de modifier facilement les liens de vos réseaux sociaux existants. Il suffit de remplacer l\'ancien lien par le nouveau et de confirmer avec le bouton de mise à jour. C\'est particulièrement utile si vous changez de nom d\'ut'),
(44, 2, '', 15, '1. Entrez votre nouvelle URL à la place de l\'ancienne.'),
(45, 3, '', 15, '2. Appuyez sur le bouton \"Mettre à jour\" en dessous.'),
(46, 1, '', 16, 'Cette fonction offre une gestion flexible de la visibilité de vos réseaux sociaux :\n'),
(47, 1, '', 17, 'Cette option permet d\'ajouter des réseaux sociaux qui ne sont pas listés par défaut. C\'est particulièrement utile pour les réseaux sociaux moins courants ou spécifiques à votre secteur d\'activité.'),
(48, 2, '', 17, '1. En bas de la page, appuyez sur le petit \"+\".'),
(49, 3, '', 17, '2. Entrez le nom de votre réseau social et le lien.'),
(50, 4, '', 17, '3. Cliquez ensuite sur \"Ajouter\".'),
(51, 1, '', 18, 'Cette fonction offre une recherche flexible et multiparamètres des employés. Vous pouvez retrouver rapidement un employé en utilisant différents critères (nom, prénom, fonction, numéro de téléphone ou email) via la barre de recherche. C\'est particulièrement utile lorsque vous gérez une équipe importante.'),
(52, 1, '', 19, 'Cette fonctionnalité permet de mettre à jour les informations d\'un employé existant.'),
(53, 2, '', 19, '1. Cliquez sur le bouton \"Modifier\" de l\'employé que vous souhaitez modifier.'),
(54, 3, '', 19, '2. Modifiez ensuite les informations que vous voulez changer.'),
(55, 4, '', 19, '3. Cliquez sur le bouton \"Modifier\" en bas de la page.'),
(56, 5, '', 19, '⚠️ Après avoir modifié les informations de l\'employé, veuillez rafraîchir le QR Code pour que les modifications soient prises en compte.'),
(57, 1, '', 20, 'Cette fonction permet de retirer un employé du système de manière simple et directe.'),
(58, 2, '', 20, 'Cliquez sur le bouton \"Supprimer\" de l\'employé que vous souhaitez supprimer.'),
(59, 3, '', 20, '⚠️ Il est important de l\'utiliser avec précaution car l\'action est définitive.'),
(60, 1, '', 21, 'Cette fonction est cruciale pour la synchronisation des informations. En cliquant sur l\'icône adjacente au QR Code, vous vous assurez que toutes les modifications récentes sont bien intégrées dans le QR Code de l\'employé.'),
(61, 2, '', 21, 'Cliquez sur l\'icône à droite du QR Code de l\'employé concerné.'),
(62, 1, '', 22, 'Cette fonction permet d\'intégrer un nouvel employé au système.'),
(63, 2, '', 22, '1. Cliquez sur le bouton \"Ajouter un employé\" en haut à droite de la page.'),
(64, 3, '', 22, '2. Entrez ensuite les informations de l\'employé.'),
(65, 4, '', 22, '3. Cliquez sur le bouton \"Inscription\" en bas de la page.'),
(66, 1, '', 23, 'Cette fonction permet de gérer l\'identité visuelle principale de votre carte.'),
(67, 2, '', 23, 'Pour Ajouter un Logo'),
(68, 3, '', 23, '1. Cliquez sur \"Choisir un fichier\".'),
(69, 4, '', 23, '2. Sélectionnez un logo dans votre explorateur de fichiers.'),
(70, 5, '', 23, '3. Cliquez ensuite sur \"Enregistrer\".'),
(71, 6, '', 23, 'Pour Supprimer un Logo'),
(72, 7, '', 23, 'Cliquez sur le bouton \"Supprimer\".'),
(73, 8, '', 23, '⚠️ Vous ne pouvez ajouter qu\'un seul logo (formats acceptés : SVG, PNG, JPG, JPEG).'),
(74, 1, '', 24, 'Cette fonctionnalité permet d\'intégrer un document PDF à votre carte.'),
(75, 2, '', 24, 'Ajouter un PDF'),
(76, 3, '', 24, '1. Cliquez sur \"Choisir un fichier\".'),
(77, 4, '', 24, '2. Sélectionnez un PDF dans votre explorateur de fichiers.'),
(78, 5, '', 24, '3. Nommez votre PDF (le nom choisi apparaîtra dans votre Wisikard).'),
(79, 6, '', 24, '4. Cliquez ensuite sur \"Enregistrer\".'),
(80, 7, '', 24, 'Supprimer un PDF'),
(81, 8, '', 24, 'Cliquez sur le bouton \"Supprimer\".'),
(82, 9, '', 24, '⚠️ Vous ne pouvez ajouter qu\'un seul PDF.'),
(83, 1, '', 25, 'Cette fonction permet d\'intégrer des contenus vidéo YouTube à votre carte.'),
(84, 2, '', 25, 'Ajouter une Vidéo YouTube'),
(85, 3, '', 25, '1. Allez sur YouTube et cliquez sur la vidéo que vous avez choisie.'),
(86, 4, '', 25, '2. Copiez l\'URL de la vidéo.'),
(87, 5, '', 25, '3. Allez ensuite sur la page \"Contenu\" et collez le lien de la vidéo YouTube.'),
(88, 6, '', 25, '4. Cliquez ensuite sur le bouton \"Enregistrer\".'),
(89, 7, '', 25, 'Supprimer une Vidéo YouTube'),
(90, 8, '', 25, 'Cliquez sur le bouton \"Supprimer\".'),
(91, 9, '', 25, '⚠️ Vous ne pouvez ajouter que des vidéos YouTube.'),
(92, 1, '', 26, 'Cette fonction permet d\'intégrer vos avis Google, augmentant ainsi la crédibilité de votre entreprise.'),
(93, 2, '', 26, 'Ajouter un Lien d\'Avis Google'),
(94, 3, '', 26, '1. Copiez l\'URL de votre page d\'avis Google.'),
(95, 4, '', 26, '2. Allez ensuite sur la page \"Contenu\" et collez le lien.'),
(96, 5, '', 26, '3. Cliquez ensuite sur le bouton \"Enregistrer\".'),
(97, 6, '', 26, 'Supprimer un Lien d\'Avis Google'),
(98, 7, '', 26, 'Cliquez sur le bouton \"Supprimer\".'),
(99, 8, '', 26, '⚠️ Vous ne pouvez ajouter qu\'un seul lien d\'avis Google.'),
(100, 1, '', 27, 'Cette fonction permet d\'intégrer votre système de réservation en ligne.'),
(101, 2, '', 27, 'Ajouter une URL de Prise de Rendez-vous'),
(102, 3, '', 27, '1. Allez sur votre site de prise de rendez-vous.'),
(103, 4, '', 27, '2. Copiez le lien menant à votre entreprise.'),
(104, 5, '', 27, '3. Allez ensuite sur la page \"Contenu\" et collez le lien.'),
(105, 6, '', 27, '4. Cliquez ensuite sur le bouton \"Enregistrer\".'),
(106, 7, '', 27, 'Supprimer une URL de Prise de Rendez-vous'),
(107, 8, '', 27, 'Cliquez sur le bouton \"Supprimer\".'),
(108, 9, '', 27, '⚠️ Vous ne pouvez ajouter qu\'une seule URL de prise de rendez-vous.'),
(109, 1, '', 28, 'Cette fonction offre une gestion flexible de vos visuels.'),
(110, 2, '', 28, 'Ajouter des Photos'),
(111, 3, '', 28, '1. Cliquez sur \"Choisir un fichier\".'),
(112, 4, '', 28, '2. Sélectionnez une ou plusieurs images dans votre explorateur de fichiers (maintenez la touche Ctrl pour sélectionner plusieurs images).'),
(113, 5, '', 28, '3. Cliquez ensuite sur \"Enregistrer\".'),
(114, 6, '', 28, 'Supprimer une Photo'),
(115, 7, '', 28, 'Cliquez sur la petite croix en haut à droite de l\'image/photo que vous souhaitez supprimer.'),
(116, 8, '', 28, '⚠️ Vous pouvez ajouter jusqu\'à 10 images/photos.'),
(117, 2, '', 16, 'Pour l\'activation : la case cochée en bleu rend le réseau social visible sur votre profil'),
(118, 3, '', 16, 'Pour la désactivation : la case décochée en gris masque temporairement le réseau social sans supprimer les informations'),
(119, 1, '', 29, 'Ce QR Code mène à la Wisikard de l\'employé, affichant ses informations.'),
(120, 2, '', 29, 'Pour télécharger le QR Code de l\'employé, appuyez sur l\'icône montrée ci-dessous.');

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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `img`
--
ALTER TABLE `img`
    ADD CONSTRAINT `img_guide_FK` FOREIGN KEY (`id_guide`) REFERENCES `guide` (`id_guide`) ON DELETE CASCADE;

--
-- Contraintes pour la table `txt`
--
ALTER TABLE `txt`
    ADD CONSTRAINT `txt_ibfk_1` FOREIGN KEY (`id_guide`) REFERENCES `guide` (`id_guide`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
