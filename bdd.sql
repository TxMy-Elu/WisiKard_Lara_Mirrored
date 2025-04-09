-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : mer. 09 avr. 2025 à 07:46
-- Version du serveur : 10.11.11-MariaDB-0+deb12u1
-- Version de PHP : 8.2.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `c1sdxwisikardv2`
--

-- --------------------------------------------------------

--
-- Structure de la table `carte`
--

DROP TABLE IF EXISTS `carte`;
CREATE TABLE IF NOT EXISTS `carte` (
  `idCarte` int(11) NOT NULL AUTO_INCREMENT,
  `nomEntreprise` varchar(255) NOT NULL,
  `titre` varchar(150) DEFAULT NULL,
  `tel` varchar(25) DEFAULT NULL,
  `ville` varchar(255) DEFAULT NULL,
  `imgPres` varchar(100) DEFAULT NULL,
  `imgLogo` varchar(100) DEFAULT NULL,
  `pdf` varchar(100) DEFAULT NULL,
  `nomBtnPdf` varchar(100) DEFAULT NULL,
  `couleur1` varchar(10) DEFAULT NULL,
  `couleur2` varchar(10) DEFAULT NULL,
  `descriptif` text DEFAULT NULL,
  `LienCommande` varchar(150) DEFAULT NULL,
  `lienQr` varchar(500) NOT NULL,
  `lienPdf` varchar(500) DEFAULT NULL,
  `lienAvis` varchar(500) DEFAULT NULL,
  `lienSiteWeb` varchar(500) DEFAULT NULL,
  `font` varchar(500) NOT NULL DEFAULT 'roboto',
  `idCompte` int(11) NOT NULL,
  `idTemplate` int(11) NOT NULL,
  `afficher_email` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`idCarte`),
  KEY `carte_compte_FK` (`idCompte`),
  KEY `carte_template_FK` (`idTemplate`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `compte`
--

DROP TABLE IF EXISTS `compte`;
CREATE TABLE IF NOT EXISTS `compte` (
  `idCompte` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `password` varchar(500) NOT NULL,
  `role` varchar(50) NOT NULL,
  `tentativesCo` int(11) NOT NULL DEFAULT 0,
  `estDesactiver` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`idCompte`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `custom_link`
--

DROP TABLE IF EXISTS `custom_link`;
CREATE TABLE IF NOT EXISTS `custom_link` (
  `id_link` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(150) NOT NULL,
  `lien` varchar(300) DEFAULT NULL,
  `activer` tinyint(1) NOT NULL DEFAULT 1,
  `idCarte` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_link`),
  KEY `idCarte` (`idCarte`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `employer`
--

DROP TABLE IF EXISTS `employer`;
CREATE TABLE IF NOT EXISTS `employer` (
  `idEmp` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `fonction` varchar(100) DEFAULT NULL,
  `idCarte` int(11) DEFAULT NULL,
  `mail` varchar(100) DEFAULT NULL,
  `telephone` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`idEmp`),
  KEY `employer_carte_FK` (`idCarte`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `guide`
--

DROP TABLE IF EXISTS `guide`;
CREATE TABLE IF NOT EXISTS `guide` (
  `id_guide` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(100) NOT NULL,
  PRIMARY KEY (`id_guide`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `guide`
--

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
(29, 'Télécharger le QR Code de l\'Employé'),
(30, 'Mot de passe oublié ?'),
(31, 'Ajouter/Supprimer un lien vers un Site Web'),
(32, 'Installer l\'application sur l\'écran d\'accueil depuis un iPhone'),
(33, 'Utiliser la fonction \"Fiche de contact\"'),
(34, 'Partager son QR Code efficacement');

-- --------------------------------------------------------

--
-- Structure de la table `horaires`
--

DROP TABLE IF EXISTS `horaires`;
CREATE TABLE IF NOT EXISTS `horaires` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idCarte` int(11) NOT NULL,
  `jour` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ouverture_matin` time DEFAULT NULL,
  `fermeture_matin` time DEFAULT NULL,
  `ouverture_aprmidi` time DEFAULT NULL,
  `fermeture_aprmidi` time DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idCarte` (`idCarte`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `img`
--

DROP TABLE IF EXISTS `img`;
CREATE TABLE IF NOT EXISTS `img` (
  `id_img` int(11) NOT NULL AUTO_INCREMENT,
  `num_img` int(11) NOT NULL,
  `id_guide` int(11) NOT NULL,
  `chemin` varchar(150) NOT NULL,
  PRIMARY KEY (`id_img`),
  KEY `id_guide` (`id_guide`)
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `img`
--

INSERT INTO `img` (`id_img`, `num_img`, `id_guide`, `chemin`) VALUES
(4, 2, 1, 'public/images/Page_Aide/Accueil/Modif_Info_Entreprise/modif_info.png'),
(5, 3, 1, 'public/images/Page_Aide/Accueil/Modif_Info_Entreprise/formulaire_modif.png'),
(6, 2, 2, 'public/images/Page_Aide/Accueil/Police/police.png'),
(7, 2, 3, 'public/images/Page_Aide/Accueil/Titre_description/titre_description.png'),
(8, 3, 3, 'public/images/Page_Aide/Accueil/Titre_description/modif_titre_description.png'),
(9, 2, 4, 'public/images/Page_Aide/Accueil/Horaire/horaire_modif.png'),
(10, 2, 5, 'public/images/Page_Aide/Accueil/Couleur_Qr_Code/couleurQrcode.png'),
(11, 3, 5, 'public/images/Page_Aide/Accueil/Couleur_Qr_Code/couleurQrcode_modif.png'),
(12, 5, 5, 'public/images/Page_Aide/Accueil/Couleur_Qr_Code/enregistrer_couleur.png'),
(13, 3, 6, 'public/images/Page_Aide/Accueil/Telecharger_Qr_Code/dlQrCodeEntre.png'),
(14, 3, 7, 'public/images/Page_Aide/Accueil/Telecharger_Qr_Code/dlQrCodePdf.png'),
(15, 2, 8, 'public/images/Page_Aide/Accueil/Template/changer_template.png'),
(16, 2, 9, 'public/images/Page_Aide/Statistique/Annee/annee.png'),
(17, 2, 10, 'public/images/Page_Aide/Statistique/Semaine/semaine.png'),
(18, 1, 11, 'public/images/Page_Aide/Statistique/Graphique_Employe/graphique_employe.png'),
(19, 1, 12, 'public/images/Page_Aide/Statistique/Nb_Vue_Global/nb_vue_global.png'),
(20, 1, 13, 'public/images/Page_Aide/Statistique/Nb_Vue_Semaine/nb_vue_semaine.png'),
(21, 3, 2, 'public/images/Page_Aide/Accueil/Police/police_2.png'),
(22, 4, 2, 'public/images/Page_Aide/Accueil/enregistrer_2.png'),
(23, 4, 2, 'public/images/Page_Aide/Accueil/enregistrer_2.png'),
(24, 3, 14, 'public/images/Page_Aide/Reseaux/Ajout_reseaux/reseau_sociaux_1.png'),
(25, 4, 14, 'public/images/Page_Aide/Reseaux/Ajout_reseaux/reseau_sociaux_2.png'),
(26, 5, 14, 'public/images/Page_Aide/Reseaux/Ajout_reseaux/reseau_sociaux_3.png'),
(27, 2, 14, 'public/images/Page_Aide/Reseaux/Ajout_reseaux/url_reseau_sociaux.png'),
(28, 2, 15, 'public/images/Page_Aide/Reseaux/Mise_A_Jour_Reseaux/modif_reseau.png'),
(29, 3, 15, 'public/images/Page_Aide/Reseaux/Mise_A_Jour_Reseaux/modif_reseau_2.png'),
(30, 2, 16, 'public/images/Page_Aide/Reseaux/Activer_Desactiver_Reseaux/activer_reseaux.png'),
(31, 3, 16, 'public/images/Page_Aide/Reseaux/Activer_Desactiver_Reseaux/desactiver_reseaux.png'),
(32, 3, 17, 'public/images/Page_Aide/Reseaux/Autre_reseaux/autre_reseau.png'),
(33, 2, 17, 'public/images/Page_Aide/Reseaux/Autre_reseaux/bouton_autre.png'),
(34, 4, 17, 'public/images/Page_Aide/Reseaux/Autre_reseaux/bouton_ajouter.png'),
(35, 1, 18, 'public/images/Page_Aide/Employe/Recherche/rechercher.png'),
(36, 2, 19, 'public/images/Page_Aide/Employe/Modif_Employe/card_employe.png'),
(37, 3, 19, 'public/images/Page_Aide/Employe/Modif_Employe/modif_employe.png'),
(38, 4, 19, 'public/images/Page_Aide/Employe/Modif_Employe/modifier.png'),
(39, 2, 20, 'public/images/Page_Aide/Employe/Suppri_Employe/suppri_employe.png'),
(40, 2, 21, 'public/images/Page_Aide/Employe/Refresh_Qr_Code/QrCodeEmploye.png'),
(41, 2, 29, 'public/images/Page_Aide/Employe/DL_Qr_Code/Dl_QrCodeEmploye.png'),
(42, 2, 22, 'public/images/Page_Aide/Employe/Ajout_Employe/ajoutEmploye.png'),
(43, 3, 22, 'public/images/Page_Aide/Employe/Inscription_Employe/info_inscription.png'),
(44, 4, 22, 'public/images/Page_Aide/Employe/Inscription_Employe/info_inscription_ok.png'),
(45, 3, 23, 'public/images/Page_Aide/Contenu/Logo/Logo.png'),
(46, 4, 23, 'public/images/Page_Aide/Contenu/Logo/fichier_logo.png'),
(47, 5, 23, 'public/images/Page_Aide/Contenu/Logo/enre_logo.png'),
(48, 7, 23, 'public/images/Page_Aide/Contenu/Logo/suppri_logo.png'),
(49, 3, 24, 'public/images/Page_Aide/Contenu/PDF/PDF_1.png'),
(50, 3, 24, 'public/images/Page_Aide/Contenu/PDF/PDF_1.png'),
(51, 4, 24, 'public/images/Page_Aide/Contenu/PDF/PDF_2.png'),
(52, 5, 24, 'public/images/Page_Aide/Contenu/PDF/PDF_4.png'),
(53, 8, 24, 'public/images/Page_Aide/Contenu/PDF/suppri_pdf.png'),
(54, 3, 25, 'public/images/Page_Aide/Contenu/Video/youtube_1.png'),
(55, 5, 25, 'public/images/Page_Aide/Contenu/Video/youtube_2.png'),
(56, 6, 25, 'public/images/Page_Aide/Contenu/Video/youtube_3.png'),
(57, 4, 25, 'public/images/Page_Aide/Contenu/Video/youtube_url.png'),
(58, 8, 25, 'public/images/Page_Aide/Contenu/Video/suppri_youtube.png'),
(59, 3, 26, 'public/images/Page_Aide/Contenu/Lien_Avis/lien_avis_1.png'),
(60, 4, 26, 'public/images/Page_Aide/Contenu/Lien_Avis/lien_avis_2.png'),
(61, 5, 26, 'public/images/Page_Aide/Contenu/Lien_Avis/lien_avis_3.png'),
(62, 7, 26, 'public/images/Page_Aide/Contenu/Lien_Avis/suppri_avis.png'),
(63, 3, 31, 'public/images/Page_Aide/Contenu/Site_Web/lien_site1.png'),
(64, 4, 31, 'public/images/Page_Aide/Contenu/Site_Web/lien_site2.png'),
(65, 5, 31, 'public/images/Page_Aide/Contenu/Site_Web/lien_site3.png'),
(66, 7, 31, 'public/images/Page_Aide/Contenu/Site_Web/suppri_site.png'),
(67, 3, 28, 'public/images/Page_Aide/Contenu/Galerie/galerie_1.png'),
(68, 4, 28, 'public/images/Page_Aide/Contenu/Galerie/galerie_2.png'),
(69, 5, 28, 'public/images/Page_Aide/Contenu/Galerie/galerie_3.png'),
(70, 7, 28, 'public/images/Page_Aide/Contenu/Galerie/suppri_galerie.png'),
(71, 4, 27, 'public/images/Page_Aide/Contenu/Lien_RDV/url_rdv1.png'),
(72, 5, 27, 'public/images/Page_Aide/Contenu/Lien_RDV/url_rdv2.png'),
(73, 6, 27, 'public/images/Page_Aide/Contenu/Lien_RDV/url_rdv3.png'),
(74, 8, 27, 'public/images/Page_Aide/Contenu/Lien_RDV/suppri_url_rdv.png'),
(75, 3, 30, 'public/images/Page_Aide/Accueil/Oublie_MDP/connexion.png'),
(76, 4, 30, 'public/images/Page_Aide/Accueil/Oublie_MDP/mdp_oublie.png'),
(77, 3, 4, 'public/images/Page_Aide/Accueil/Horaire/horaire_modif_2.png');

-- --------------------------------------------------------

--
-- Structure de la table `inscript_attente`
--

DROP TABLE IF EXISTS `inscript_attente`;
CREATE TABLE IF NOT EXISTS `inscript_attente` (
  `id_inscripAttente` int(11) NOT NULL AUTO_INCREMENT,
  `nom_entre` varchar(150) NOT NULL,
  `mail` varchar(150) NOT NULL,
  `mdp` varchar(150) NOT NULL,
  `role` varchar(150) NOT NULL,
  `date_inscription` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id_inscripAttente`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `logs`
--

DROP TABLE IF EXISTS `logs`;
CREATE TABLE IF NOT EXISTS `logs` (
  `idLog` int(11) NOT NULL AUTO_INCREMENT,
  `typeAction` varchar(500) NOT NULL,
  `dateHeureLog` datetime NOT NULL,
  `adresseIPLog` varchar(500) NOT NULL,
  `idCompte` int(11) NOT NULL,
  PRIMARY KEY (`idLog`),
  KEY `logs_compte_FK` (`idCompte`)
) ENGINE=InnoDB AUTO_INCREMENT=1072 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `message`
--

DROP TABLE IF EXISTS `message`;
CREATE TABLE IF NOT EXISTS `message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message` varchar(1000) NOT NULL,
  `afficher` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `reactivation`
--

DROP TABLE IF EXISTS `reactivation`;
CREATE TABLE IF NOT EXISTS `reactivation` (
  `idReactivation` int(11) NOT NULL AUTO_INCREMENT,
  `codeReactivation` varchar(32) NOT NULL,
  `dateHeureExpirationReactivation` datetime NOT NULL,
  `idCompte` int(11) NOT NULL,
  PRIMARY KEY (`idReactivation`),
  UNIQUE KEY `codeReactivation` (`codeReactivation`),
  KEY `reactivation_compte_FK` (`idCompte`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `recuperation`
--

DROP TABLE IF EXISTS `recuperation`;
CREATE TABLE IF NOT EXISTS `recuperation` (
  `idRecuperation` int(11) NOT NULL AUTO_INCREMENT,
  `codeRecuperation` varchar(32) NOT NULL,
  `dateHeureExpirationRecuperation` datetime NOT NULL,
  `idCompte` int(11) NOT NULL,
  PRIMARY KEY (`idRecuperation`),
  UNIQUE KEY `codeRecuperation` (`codeRecuperation`),
  KEY `recuperation_compte_FK` (`idCompte`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `rediriger`
--

DROP TABLE IF EXISTS `rediriger`;
CREATE TABLE IF NOT EXISTS `rediriger` (
  `idSocial` int(11) NOT NULL,
  `idCarte` int(11) NOT NULL,
  `lien` varchar(500) DEFAULT NULL,
  `activer` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`idSocial`,`idCarte`),
  KEY `rediriger_carte_FK` (`idCarte`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `social`
--

DROP TABLE IF EXISTS `social`;
CREATE TABLE IF NOT EXISTS `social` (
  `idSocial` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(500) NOT NULL,
  `lienLogo` text NOT NULL,
  PRIMARY KEY (`idSocial`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `social`
--

INSERT INTO `social` (`idSocial`, `nom`, `lienLogo`) VALUES
(1, 'Facebook', '<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 512 512\"><path d=\"M512 256C512 114.6 397.4 0 256 0S0 114.6 0 256C0 376 82.7 476.8 194.2 504.5V334.2H141.4V256h52.8V222.3c0-87.1 39.4-127.5 125-127.5c16.2 0 44.2 3.2 55.7 6.4V172c-6-.6-16.5-1-29.6-1c-42 0-58.2 15.9-58.2 57.2V256h83.6l-14.4 78.2H287V510.1C413.8 494.8 512 386.9 512 256h0z\"/></svg>\n'),
(2, 'Twitter', '<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 512 512\"><path d=\"M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z\"/></svg>\n'),
(3, 'LinkedIn', '<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 448 512\"><path d=\"M416 32H31.9C14.3 32 0 46.5 0 64.3v383.4C0 465.5 14.3 480 31.9 480H416c17.6 0 32-14.5 32-32.3V64.3c0-17.8-14.4-32.3-32-32.3zM135.4 416H69V202.2h66.5V416zm-33.2-243c-21.3 0-38.5-17.3-38.5-38.5S80.9 96 102.2 96c21.2 0 38.5 17.3 38.5 38.5 0 21.3-17.2 38.5-38.5 38.5zm282.1 243h-66.4V312c0-24.8-.5-56.7-34.5-56.7-34.6 0-39.9 27-39.9 54.9V416h-66.4V202.2h63.7v29.2h.9c8.9-16.8 30.6-34.5 62.9-34.5 67.2 0 79.7 44.3 79.7 101.9V416z\"/></svg>\n'),
(4, 'Instagram', '<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 448 512\"><path d=\"M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z\"/></svg>\n'),
(5, 'YouTube', '<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 576 512\"><path d=\"M549.7 124.1c-6.3-23.7-24.8-42.3-48.3-48.6C458.8 64 288 64 288 64S117.2 64 74.6 75.5c-23.5 6.3-42 24.9-48.3 48.6-11.4 42.9-11.4 132.3-11.4 132.3s0 89.4 11.4 132.3c6.3 23.7 24.8 41.5 48.3 47.8C117.2 448 288 448 288 448s170.8 0 213.4-11.5c23.5-6.3 42-24.2 48.3-47.8 11.4-42.9 11.4-132.3 11.4-132.3s0-89.4-11.4-132.3zm-317.5 213.5V175.2l142.7 81.2-142.7 81.2z\"/></svg>\n'),
(6, 'TikTok', '<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 448 512\"><path d=\"M448 209.9a210.1 210.1 0 0 1 -122.8-39.3V349.4A162.6 162.6 0 1 1 185 188.3V278.2a74.6 74.6 0 1 0 52.2 71.2V0l88 0a121.2 121.2 0 0 0 1.9 22.2h0A122.2 122.2 0 0 0 381 102.4a121.4 121.4 0 0 0 67 20.1z\"/></svg>\n'),
(7, 'Discord', '<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 640 512\"><path d=\"M524.5 69.8a1.5 1.5 0 0 0 -.8-.7A485.1 485.1 0 0 0 404.1 32a1.8 1.8 0 0 0 -1.9 .9 337.5 337.5 0 0 0 -14.9 30.6 447.8 447.8 0 0 0 -134.4 0 309.5 309.5 0 0 0 -15.1-30.6 1.9 1.9 0 0 0 -1.9-.9A483.7 483.7 0 0 0 116.1 69.1a1.7 1.7 0 0 0 -.8 .7C39.1 183.7 18.2 294.7 28.4 404.4a2 2 0 0 0 .8 1.4A487.7 487.7 0 0 0 176 479.9a1.9 1.9 0 0 0 2.1-.7A348.2 348.2 0 0 0 208.1 430.4a1.9 1.9 0 0 0 -1-2.6 321.2 321.2 0 0 1 -45.9-21.9 1.9 1.9 0 0 1 -.2-3.1c3.1-2.3 6.2-4.7 9.1-7.1a1.8 1.8 0 0 1 1.9-.3c96.2 43.9 200.4 43.9 295.5 0a1.8 1.8 0 0 1 1.9 .2c2.9 2.4 6 4.9 9.1 7.2a1.9 1.9 0 0 1 -.2 3.1 301.4 301.4 0 0 1 -45.9 21.8 1.9 1.9 0 0 0 -1 2.6 391.1 391.1 0 0 0 30 48.8 1.9 1.9 0 0 0 2.1 .7A486 486 0 0 0 610.7 405.7a1.9 1.9 0 0 0 .8-1.4C623.7 277.6 590.9 167.5 524.5 69.8zM222.5 337.6c-29 0-52.8-26.6-52.8-59.2S193.1 219.1 222.5 219.1c29.7 0 53.3 26.8 52.8 59.2C275.3 311 251.9 337.6 222.5 337.6zm195.4 0c-29 0-52.8-26.6-52.8-59.2S388.4 219.1 417.9 219.1c29.7 0 53.3 26.8 52.8 59.2C470.7 311 447.5 337.6 417.9 337.6z\"/></svg>\n'),
(8, 'AirBNB', '<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 448 512\"><path d=\"M224 373.1c-25.2-31.7-40.1-59.4-45-83.2-22.6-88 112.6-88 90.1 0-5.5 24.3-20.3 52-45 83.2zm138.2 73.2c-42.1 18.3-83.7-10.9-119.3-50.5 103.9-130.1 46.1-200-18.9-200-54.9 0-85.2 46.5-73.3 100.5 6.9 29.2 25.2 62.4 54.4 99.5-32.5 36.1-60.6 52.7-85.2 54.9-50 7.4-89.1-41.1-71.3-91.1 15.1-39.2 111.7-231.2 115.9-241.6 15.8-30.1 25.6-57.4 59.4-57.4 32.3 0 43.4 25.9 60.4 59.9 36 70.6 89.4 177.5 114.8 239.1 13.2 33.1-1.4 71.3-37 86.6zm47-136.1C280.3 35.9 273.1 32 224 32c-45.5 0-64.9 31.7-84.7 72.8C33.2 317.1 22.9 347.2 22 349.8-3.2 419.1 48.7 480 111.6 480c21.7 0 60.6-6.1 112.4-62.4 58.7 63.8 101.3 62.4 112.4 62.4 62.9 .1 114.9-60.9 89.6-130.2 0-3.9-16.8-38.9-16.8-39.6z\"/></svg>\n'),
(9, 'Booking.com', '<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 3.036 3.037\"><path d=\"M1.113 2.524h-.51v-.61c0-.13.05-.2.162-.214h.35a.38.38 0 0 1 .41.411c0 .26-.157.415-.41.415zM.602.875v-.16c0-.14.06-.208.19-.216h.262c.224 0 .36.134.36.36 0 .17-.092.37-.35.37h-.46zm1.164.61l-.092-.052.08-.07c.094-.08.25-.262.25-.575 0-.48-.372-.79-.947-.79h-.73a.32.32 0 0 0-.309.317v2.72H1.07c.64 0 1.052-.348 1.052-.888 0-.29-.133-.54-.358-.665\"/><path d=\"M2.288 2.67c0-.203.163-.367.365-.367s.367.164.367.367-.164.367-.367.367-.365-.164-.365-.367\"/></svg>\n'),
(10, 'SnapChat', '<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 448 512\"><path d=\"M384 32H64A64 64 0 0 0 0 96V416a64 64 0 0 0 64 64H384a64 64 0 0 0 64-64V96A64 64 0 0 0 384 32zm-3.9 319.3-.1 .1a32.4 32.4 0 0 1 -8.7 6.8 90.3 90.3 0 0 1 -20.6 8.2 12.7 12.7 0 0 0 -3.9 1.8c-2.2 1.9-2.1 4.6-4.4 8.6a23.1 23.1 0 0 1 -6.8 7.5c-6.7 4.6-14.2 4.9-22.2 5.2-7.2 .3-15.4 .6-24.7 3.7-3.8 1.2-7.8 3.7-12.4 6.5-11.3 6.9-26.7 16.4-52.3 16.4s-40.9-9.4-52.1-16.3c-4.7-2.9-8.7-5.4-12.5-6.6-9.3-3.1-17.5-3.4-24.7-3.7-8-.3-15.5-.6-22.2-5.2a23.1 23.1 0 0 1 -6-6.1c-3.2-4.6-2.9-7.8-5.3-9.9a13.4 13.4 0 0 0 -4.1-1.8 90 90 0 0 1 -20.3-8.1 32.9 32.9 0 0 1 -8.3-6.3c-6.6-6.8-8.3-14.8-5.7-21.8 3.4-9.3 11.6-12.1 19.4-16.3 14.8-8 26.3-18.1 34.4-29.9a68.2 68.2 0 0 0 6-10.6c.8-2.2 .8-3.3 .2-4.4a7.4 7.4 0 0 0 -2.2-2.2c-2.5-1.7-5.1-3.4-6.9-4.5-3.3-2.1-5.9-3.8-7.5-5-6.3-4.4-10.7-9-13.4-14.2a28.4 28.4 0 0 1 -1.4-23.6c4.1-10.9 14.5-17.7 27-17.7a37.1 37.1 0 0 1 7.8 .8c.7 .2 1.4 .3 2 .5-.1-7.4 .1-15.4 .7-23.1 2.4-27.3 11.9-41.6 21.9-53a86.8 86.8 0 0 1 22.3-17.9C188.3 100.4 205.3 96 224 96s35.8 4.4 50.9 13a87.2 87.2 0 0 1 22.2 17.9c10 11.4 19.5 25.7 21.9 53a231.2 231.2 0 0 1 .7 23.1c.7-.2 1.4-.3 2.1-.5a37.1 37.1 0 0 1 7.8-.8c12.5 0 22.8 6.8 27 17.7a28.4 28.4 0 0 1 -1.4 23.6c-2.7 5.2-7.1 9.9-13.4 14.2-1.7 1.2-4.3 2.9-7.5 5-1.8 1.2-4.5 2.9-7.2 4.7a6.9 6.9 0 0 0 -2 2c-.5 1-.5 2.2 .2 4.2a69 69 0 0 0 6.1 10.8c8.3 12.1 20.2 22.3 35.5 30.4 1.5 .8 3 1.5 4.4 2.3 .7 .3 1.6 .8 2.5 1.3 4.9 2.7 9.2 6 11.5 12.2C387.8 336.9 386.3 344.7 380.1 351.3zm-16.7-18.5c-50.3-24.3-58.3-61.9-58.7-64.7-.4-3.4-.9-6 2.8-9.5 3.6-3.3 19.5-13.2 24-16.3 7.3-5.1 10.5-10.2 8.2-16.5-1.7-4.3-5.7-6-10-6a18.5 18.5 0 0 0 -4 .4c-8 1.7-15.8 5.8-20.4 6.9a7.1 7.1 0 0 1 -1.7 .2c-2.4 0-3.3-1.1-3.1-4 .6-8.8 1.8-25.9 .4-41.9-1.9-22-9-32.9-17.4-42.6-4.1-4.6-23.1-24.7-59.5-24.7S168.5 134.4 164.5 139c-8.4 9.7-15.5 20.6-17.4 42.6-1.4 16-.1 33.1 .4 41.9 .2 2.8-.7 4-3.1 4a7.1 7.1 0 0 1 -1.7-.2c-4.5-1.1-12.3-5.1-20.3-6.9a18.5 18.5 0 0 0 -4-.4c-4.3 0-8.3 1.6-10 6-2.4 6.3 .8 11.4 8.2 16.5 4.4 3.1 20.4 13 24 16.3 3.7 3.4 3.2 6.1 2.8 9.5-.4 2.8-8.4 40.4-58.7 64.7-2.9 1.4-8 4.5 .9 9.3 13.9 7.6 23.1 6.8 30.3 11.4 6.1 3.9 2.5 12.4 6.9 15.4 5.5 3.8 21.6-.3 42.3 6.6 17.4 5.7 28.1 22 59 22s41.8-16.3 58.9-22c20.8-6.9 36.9-2.8 42.3-6.6 4.4-3.1 .8-11.5 6.9-15.4 7.2-4.6 16.4-3.8 30.3-11.5C371.4 337.4 366.3 334.3 363.4 332.8z\"/></svg>\n'),
(11, 'Pinterest', '<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 496 512\"><path d=\"M496 256c0 137-111 248-248 248-25.6 0-50.2-3.9-73.4-11.1 10.1-16.5 25.2-43.5 30.8-65 3-11.6 15.4-59 15.4-59 8.1 15.4 31.7 28.5 56.8 28.5 74.8 0 128.7-68.8 128.7-154.3 0-81.9-66.9-143.2-152.9-143.2-107 0-163.9 71.8-163.9 150.1 0 36.4 19.4 81.7 50.3 96.1 4.7 2.2 7.2 1.2 8.3-3.3 .8-3.4 5-20.3 6.9-28.1 .6-2.5 .3-4.7-1.7-7.1-10.1-12.5-18.3-35.3-18.3-56.6 0-54.7 41.4-107.6 112-107.6 60.9 0 103.6 41.5 103.6 100.9 0 67.1-33.9 113.6-78 113.6-24.3 0-42.6-20.1-36.7-44.8 7-29.5 20.5-61.3 20.5-82.6 0-19-10.2-34.9-31.4-34.9-24.9 0-44.9 25.7-44.9 60.2 0 22 7.4 36.8 7.4 36.8s-24.5 103.8-29 123.2c-5 21.4-3 51.6-.9 71.2C65.4 450.9 0 361.1 0 256 0 119 111 8 248 8s248 111 248 248z\"/></svg>\n'),
(12, 'Twitch', '<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 512 512\"><path d=\"M391.2 103.5H352.5v109.7h38.6zM285 103H246.4V212.8H285zM120.8 0 24.3 91.4V420.6H140.1V512l96.5-91.4h77.3L487.7 256V0zM449.1 237.8l-77.2 73.1H294.6l-67.6 64v-64H140.1V36.6H449.1z\"/></svg>\n'),
(13, 'LeBonCoin', '<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"42\" height=\"42\" viewBox=\"0 0 652 652\"><path d=\"M177.4,370.6c9.4,0,15.3-7.5,15.3-18.6c0-11.1-5.8-18.6-15.3-18.6c-9.4,0-15.2,7.4-15.2,18.6\n        C162.2,363.1,168,370.6,177.4,370.6z M184.5,315.3c18,0,29.9,15.5,30.1,36.4c0,21.2-12.4,37-30.6,37c-4.5,0.1-9-1-12.9-3.1\n        c-4-2.1-7.3-5.2-9.8-9H161v10.8h-19.8v-94.6c0-7.2,4.8-11.7,10.5-11.7s10.5,4.5,10.5,11.7v33.7h0.3c2.5-3.5,5.8-6.3,9.6-8.3\n        C176,316.2,180.2,315.2,184.5,315.3L184.5,315.3z M257.3,370.6c9.5,0,15.2-7.5,15.2-18.6c0-11.1-5.8-18.6-15.2-18.6\n        c-9.5,0-15.2,7.4-15.2,18.6C242,363.1,247.8,370.6,257.3,370.6z M257.3,315.3c22.3,0,36.7,15.2,36.7,36.7\n        c0,21.5-14.4,36.7-36.7,36.7s-36.7-15.2-36.7-36.7C220.6,330.5,235,315.3,257.3,315.3z M537.3,315.3c5.8,0,10.6,4.5,10.7,11.7v60.3\n        h-21.2V327C526.9,319.8,531.5,315.3,537.3,315.3z M537.3,278.4c3.4,0,6.7,1.4,9.1,3.8c2.4,2.4,3.8,5.7,3.8,9.1s-1.4,6.7-3.8,9.1\n        c-2.4,2.4-5.7,3.8-9.1,3.8h0c-3.4,0-6.7-1.4-9.1-3.8c-2.4-2.4-3.8-5.7-3.8-9.1c0-3.4,1.4-6.7,3.8-9.1\n        C530.7,279.8,533.9,278.4,537.3,278.4L537.3,278.4z M603.5,315.3c14.7,0,22.6,10.4,22.6,26.2v45.8h-21.2v-39.8\n        c0-9.3-4.4-12.4-9.9-12.4c-8.2,0-14.4,6.9-14.4,21.6v30.7h-21.2v-60.9c0-6.9,4.5-11,9.9-11c5.3,0,9.9,4.1,9.9,11v2.6h0.3\n        c2.5-4.2,6-7.6,10.2-10C593.9,316.5,598.7,315.3,603.5,315.3L603.5,315.3z M481.2,370.6c9.5,0,15.3-7.5,15.3-18.6\n        c0-11.1-5.8-18.6-15.3-18.6c-9.4,0-15.2,7.4-15.2,18.6C465.9,363.1,471.7,370.6,481.2,370.6z M481.2,315.3\n        c22.3,0,36.7,15.2,36.7,36.7c0,21.5-14.4,36.7-36.7,36.7c-22.3,0-36.7-15.2-36.7-36.7C444.5,330.5,458.9,315.3,481.2,315.3z\n         M413.2,333.6c-9.2,0-14.8,6.8-14.8,18.3c0,11.6,5.6,18.3,14.8,18.3c2.6,0.1,5.1-0.5,7.4-1.7c2.3-1.2,4.1-3.1,5.4-5.3h0.3l15.4,9.9\n        c-5.3,10.4-16.5,15.5-29.2,15.5c-22,0-35.6-15.2-35.6-36.7c0-21.5,13.6-36.7,35.6-36.7c12.7,0,24,5,29.2,15.5l-15.4,9.9H426\n        c-1.3-2.2-3.2-4.1-5.4-5.3C418.3,334.1,415.8,333.5,413.2,333.6L413.2,333.6z M346.4,315.3c14.7,0,22.6,10.4,22.6,26.2v45.8h-21.2\n        v-39.8c0-9.3-4.4-12.4-9.9-12.4c-8.2,0-14.4,6.9-14.4,21.6v30.7h-21.2v-61c0-6.9,4.5-11,9.8-11c5.4,0,9.9,4.1,9.9,11v2.6h0.3\n        c2.5-4.2,6-7.6,10.2-10C336.8,316.5,341.6,315.3,346.4,315.3L346.4,315.3z M83.7,347.8c0.1,2.7,0.5,5.3,1.4,7.9l23.6-17.5\n        c-1-1.9-2.6-3.5-4.5-4.6c-1.9-1.1-4.1-1.6-6.3-1.5C91.2,332,83.7,337.1,83.7,347.8L83.7,347.8z M104.2,371.4\n        c3.8-0.2,7.4-1.4,10.6-3.4c3.2-2,5.7-4.9,7.5-8.2l13.9,10.1c-5.3,10.8-16.2,18.8-34.5,18.8c-8.7,0-17.1-3.2-23.6-8.9\n        c-9.2,6.1-17.1,8.8-25,8.8c-15.9,0-27.1-10.6-27.1-26.6v-69.4c0-7.2,4.8-11.7,10.5-11.7c5.7,0,10.5,4.5,10.5,11.7v67.7\n        c0,6.8,3,10.5,9.3,10.5c3.4,0,7.1-2,11.9-4.6c-2.3-5.5-3.5-11.3-3.4-17.2c0-15,10.7-33.6,33.1-33.6c19.4,0,29.9,11.3,34.2,25.5\n        l-37.4,26.8C97.2,370,100.7,371.4,104.2,371.4L104.2,371.4z\"/></svg>'),
(14, 'Abritel', '<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"42\" height=\"42\" viewBox=\"0 0 652 652\"><path d=\"M54.8,350.9l-14.5,44.1H21.6l47.6-140.2h21.9L139,394.9h-19.2l-15-44.1H54.8z M100.7,336.7L87,296.4\n        c-3.1-9.2-5.2-17.4-7.4-25.6h-0.4c-2,8.6-4.4,17-7.2,25.4l-13.7,40.6H100.7z M153.2,395.1c0.4-6.8,0.8-17,0.8-26V247.4h18v63.3h0.4\n        c6.4-11.3,18.1-18.5,34.5-18.5c25,0,42.6,20.9,42.4,51.4c0,36-22.7,53.9-45.1,53.9c-14.5,0-26.2-5.5-33.6-18.8H170l-0.8,16.6h-16\n        V395.1z M172,354.8c0.1,15.5,12.7,28.1,28.3,28.1c19,0,30.5-15.6,30.5-38.7c0-20.1-10.5-37.5-29.9-37.5\n        c-13.4,0.3-24.8,9.6-27.9,22.6c-0.4,2-1,4.5-1,7.6V354.8z M268.1,325.9c0-11.9-0.2-22.1-0.8-31.3h16l0.6,19.7h0.8\n        c4.5-13.5,15.6-22.1,27.9-22.1c2,0,3.5,0.2,5.1,0.6v17.2c-1.8-0.4-3.7-0.6-6.1-0.6c-12.9,0-22.1,9.8-24.6,23.5\n        c-0.4,2.5-0.8,5.3-0.8,8.6v53.7h-18V325.9z M353.1,266c0.2,6.1-4.3,11.3-11.6,11.3c-6.2,0.1-11.3-5.1-11.1-11.3\n        c-0.1-6.4,5.1-11.6,11.4-11.4C348.8,254.6,353.2,259.7,353.1,266L353.1,266z M332.9,395.1V294.6h18.2v100.6H332.9z M399.5,265.5\n        v28.8h26.2v13.9h-26.2v54.3c0,12.5,3.5,19.5,13.7,19.5c4.7,0,8.4-0.6,10.7-1.2l0.8,13.7c-3.5,1.4-9.2,2.4-16.2,2.4\n        c-8.6,0-15.4-2.6-19.7-7.7c-5.1-5.3-7.2-14.4-7.2-26.3V308h-15.5v-13.9h15.5v-24.2L399.5,265.5L399.5,265.5z M451.1,348\n        c0.4,24.8,16.2,35,34.6,35c13.1,0,21.1-2.3,27.9-5.1l3.1,13.1c-6.4,2.8-17.4,6.1-33.4,6.1c-30.9,0-49.6-20.3-49.6-50.8\n        c0-30.4,17.8-54.3,47.1-54.3c32.8,0,41.6,28.9,41.6,47.3c0,3.7-0.4,6.5-0.6,8.6H451.1z M504.6,334.9c0.2-11.7-4.7-29.7-25.4-29.7\n        c-18.4,0-26.7,17-28.1,29.7H504.6z M540.8,247.4h18.3v147.7h-18.3V247.4z M621.6,263.4c0,10.8-8.9,19.5-19.7,19.3\n        c-10.8,0.2-19.6-8.5-19.7-19.3c0-10.6,8.8-19.1,19.9-19.1C612.8,244.2,621.5,252.7,621.6,263.4L621.6,263.4z M587.1,263.4\n        c0,8.6,6.4,15.4,15,15.4c8.4,0.2,14.5-6.7,14.5-15.2c0-8.6-6.3-15.4-14.7-15.4C593.6,248.2,586.9,255.1,587.1,263.4L587.1,263.4z\n         M598.8,273.4h-4.3v-19.1c1.8-0.2,4.3-0.6,7.4-0.6c3.7,0,5.3,0.6,6.6,1.5c1.2,0.8,2,2.2,2,4.1c0,2.3-1.8,3.9-3.9,4.5v0.2\n        c1.8,0.6,2.9,2,3.5,4.7c0.6,2.8,1,3.9,1.5,4.7h-4.7c-0.6-0.8-0.8-2.2-1.4-4.7c-0.4-2.1-1.4-2.9-3.9-2.9h-2.1v7.6H598.8z\n         M599.1,262.6h2c2.5,0,4.5-0.8,4.5-2.9c0-1.8-1.2-2.9-4.1-2.9c-1.2,0-2.1,0.2-2.4,0.2V262.6z M599.1,262.6\"/></svg>');

-- --------------------------------------------------------

--
-- Structure de la table `template`
--

DROP TABLE IF EXISTS `template`;
CREATE TABLE IF NOT EXISTS `template` (
  `idTemplate` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  PRIMARY KEY (`idTemplate`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `template`
--

INSERT INTO `template` (`idTemplate`, `nom`) VALUES
(1, 'wisibase'),
(2, 'custom'),
(3, 'pomme'),
(4, 'classy'),
(5, 'oxygen');

-- --------------------------------------------------------

--
-- Structure de la table `txt`
--

DROP TABLE IF EXISTS `txt`;
CREATE TABLE IF NOT EXISTS `txt` (
  `id_txt` int(11) NOT NULL AUTO_INCREMENT,
  `num_txt` int(11) NOT NULL,
  `id_guide` int(11) NOT NULL,
  `txt` varchar(500) NOT NULL,
  PRIMARY KEY (`id_txt`),
  KEY `id_guide` (`id_guide`)
) ENGINE=InnoDB AUTO_INCREMENT=154 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `txt`
--

INSERT INTO `txt` (`id_txt`, `num_txt`, `id_guide`, `txt`) VALUES
(1, 1, 1, 'Cette fonction permet de mettre à jour les données essentielles de votre entreprise. Elle est accessible facilement depuis la page d\'accueil et offre un formulaire intuitif pour modifier toutes les informations importantes de votre établissement.'),
(2, 2, 1, '1. Sur la page d\'accueil, cliquez sur le bouton \"Modifier\" en bas à droite.'),
(3, 3, 1, '2. Vous arriverez sur un formulaire où vous pourrez modifier vos informations.'),
(4, 4, 1, '3. Modifiez les informations que vous souhaitez.'),
(5, 5, 1, '4. Appuyez sur le bouton \"Modifier\" quand vous aurez fini.'),
(6, 1, 2, 'Cette fonctionnalité vous permet de personnaliser l\'apparence textuelle de votre carte numérique. Vous pouvez choisir parmi différentes polices de caractères pour donner un style unique à votre présentation.'),
(7, 2, 2, '1. Cliquez sur le bordereau blanc.'),
(8, 3, 2, '2. Sélectionnez une police parmi celles affichées.'),
(9, 4, 2, '3. Cliquez ensuite sur le bouton \"Modifier\", et le tour est joué !'),
(10, 1, 3, 'Cette option vous permet de modifier les éléments textuels principaux de votre carte. Vous pouvez ajuster votre titre pour qu\'il soit plus accrocheur et adapter votre description pour mieux présenter votre activité.'),
(11, 2, 3, '1. Cliquez sur le bouton \"Ajouter / Modifier Titre et Description\".'),
(12, 3, 3, '2. Vous arriverez sur un formulaire où vous pourrez ajouter ou modifier vos informations.'),
(13, 4, 3, '3. Cliquez ensuite sur le bouton \"Enregistrer\".'),
(14, 1, 4, 'Cette fonction est essentielle pour maintenir vos horaires d\'ouverture à jour. Le système permet une modification simple et rapide des horaires jour par jour, avec une interface intuitive utilisant des icônes d\'horloge.'),
(15, 2, 4, '1. Cliquez sur l\'horaire à modifier ou sur l\'horloge à droite du jour souhaité.'),
(16, 3, 4, '2. Cliquez ensuite sur le bouton \"Enregistrer\" en bas à droite.'),
(17, 1, 5, 'Cette option de personnalisation permet d\'adapter l\'apparence de votre QR Code à votre charte graphique. Vous pouvez modifier deux couleurs différentes pour créer un QR Code unique et en harmonie avec votre identité visuelle.'),
(18, 2, 5, '1. Cliquez sur l\'une des deux couleurs que vous souhaitez modifier en dessous du QR Code.'),
(19, 3, 5, '2. Sélectionnez ensuite la couleur souhaitée.'),
(20, 4, 5, '3. Cliquez en dehors de la sélection de couleur.'),
(21, 5, 5, '4. Cliquez sur le bouton \"Enregistrer\".'),
(22, 1, 6, 'Cette fonction offre deux options de téléchargement de votre QR Code Entreprise : en couleur ou en noir et blanc. Cela vous permet d\'utiliser le format le plus adapté à vos besoins de communication.'),
(23, 2, 6, '- Pour télécharger le QR Code de l\'entreprise en couleur, cliquez sur le bouton ci-dessous.'),
(24, 3, 6, '- Pour télécharger le QR Code de l\'entreprise en noir et blanc, cliquez sur le bouton ci-dessous.'),
(25, 1, 7, 'Cette fonction offre deux options de téléchargement de votre QR Code PDF : en couleur ou en noir et blanc. Cela vous permet d\'utiliser le format le plus adapté à vos besoins de communication.'),
(26, 2, 7, '- Pour télécharger le QR Code de votre PDF préalablement enregistré en couleur, cliquez sur le bouton ci-dessous.'),
(27, 3, 7, '- Pour télécharger le QR Code de votre PDF préalablement enregistré en noir et blanc, cliquez sur le bouton ci-dessous.'),
(28, 1, 8, 'Cette fonction vous permet de modifier l\'apparence générale de votre carte numérique en sélectionnant différents thèmes visuels, permettant ainsi de personnaliser davantage votre présentation.'),
(29, 2, 8, 'Cliquez sur le petit bouton au-dessus du thème désiré.'),
(30, 1, 9, 'Cette fonction permet de filtrer vos données statistiques par année. Via une liste déroulante simple, vous pouvez sélectionner l\'année dont vous souhaitez consulter les statistiques, ce qui permet d\'analyser l\'évolution de votre activité sur différentes périodes.'),
(31, 2, 9, '1. Cliquez sur la liste déroulante.'),
(32, 3, 9, '2. Choisissez l\'année que vous souhaitez.'),
(33, 1, 10, 'Cette fonctionnalité offre une navigation intuitive entre les différentes semaines de l\'année sélectionnée. Grâce aux flèches directionnelles, vous pouvez facilement parcourir les données semaine par semaine pour une analyse plus précise.'),
(34, 2, 10, '1. Cliquez sur les flèches à droite ou à gauche pour naviguer entre les semaines.'),
(35, 1, 11, 'Cette fonction affiche les statistiques individuelles de consultation pour chaque employé. Elle permet de suivre la performance de chaque membre de l\'équipe à travers le nombre de scans de leurs QR Codes personnels. Si aucune donnée n\'est visible, cela indique soit l\'absence d\'employés enregistrés, soit qu\'aucun scan n\'a encore été effectué.'),
(36, 1, 12, 'Cette métrique importante présente le total des consultations pour l\'année sélectionnée. C\'est un indicateur clé qui permet d\'avoir une vue d\'ensemble de la visibilité de votre entreprise sur une période annuelle.'),
(37, 1, 13, 'Cette fonction offre une analyse plus granulaire en affichant le nombre de consultations pour la semaine sélectionnée. Elle permet de suivre les tendances à court terme et d\'identifier les périodes de forte ou faible activité.'),
(38, 1, 14, 'Cette fonction permet d\'intégrer vos réseaux sociaux existants à votre profil. Le processus est simple : vous sélectionnez d\'abord le réseau social désiré (en vérifiant bien l\'icône correspondante), puis vous ajoutez l\'URL de votre page. L\'activation'),
(39, 2, 14, '1. Choisissez le réseau que vous voulez ajouter en faisant attention à l\'icône en haut à gauche.'),
(40, 3, 14, '2. Entrez votre URL menant vers votre page là où il est écrit \"Lien du réseau social\".'),
(41, 4, 14, '3. Cochez ensuite la case \"Activer\" en dessous.'),
(42, 5, 14, '4. Appuyez sur le bouton \"Mettre à jour\".'),
(43, 1, 15, 'Cette fonctionnalité permet de modifier facilement les liens de vos réseaux sociaux existants. Il suffit de remplacer l\'ancien lien par le nouveau et de confirmer avec le bouton de mise à jour. C\'est particulièrement utile si vous changez de nom d\'utilisateur.'),
(44, 2, 15, '1. Entrez votre nouvelle URL à la place de l\'ancienne.'),
(45, 3, 15, '2. Appuyez sur le bouton \"Mettre à jour\" en dessous.'),
(46, 1, 16, 'Cette fonction offre une gestion flexible de la visibilité de vos réseaux sociaux :'),
(47, 1, 17, 'Cette option permet d\'ajouter des réseaux sociaux qui ne sont pas listés par défaut. C\'est particulièrement utile pour les réseaux sociaux moins courants ou spécifiques à votre secteur d\'activité.'),
(48, 2, 17, '1. En bas de la page, appuyez sur le petit \"+\".'),
(49, 3, 17, '2. Entrez le nom de votre réseau social et le lien.'),
(50, 4, 17, '3. Cliquez ensuite sur \"Ajouter\".'),
(51, 1, 18, 'Cette fonction offre une recherche flexible et multiparamètres des employés. Vous pouvez retrouver rapidement un employé en utilisant différents critères (nom, prénom, fonction, numéro de téléphone ou email) via la barre de recherche. C\'est particulièrement utile lorsque vous gérez une équipe importante.'),
(52, 1, 19, 'Cette fonctionnalité permet de mettre à jour les informations d\'un employé existant.'),
(53, 2, 19, '1. Cliquez sur le bouton \"Modifier\" de l\'employé que vous souhaitez modifier.'),
(54, 3, 19, '2. Modifiez ensuite les informations que vous voulez changer.'),
(55, 4, 19, '3. Cliquez sur le bouton \"Modifier\" en bas de la page.'),
(56, 5, 19, '⚠️ Après avoir modifié les informations de l\'employé, veuillez rafraîchir le QR Code pour que les modifications soient prises en compte.'),
(57, 1, 20, 'Cette fonction permet de retirer un employé du système de manière simple et directe.'),
(58, 2, 20, 'Cliquez sur le bouton \"Supprimer\" de l\'employé que vous souhaitez supprimer.'),
(59, 3, 20, '⚠️ Il est important de l\'utiliser avec précaution car l\'action est définitive.'),
(60, 1, 21, 'Cette fonction est cruciale pour la synchronisation des informations. En cliquant sur l\'icône adjacente au QR Code, vous vous assurez que toutes les modifications récentes sont bien intégrées dans le QR Code de l\'employé.'),
(61, 2, 21, 'Cliquez sur l\'icône à droite du QR Code de l\'employé concerné.'),
(62, 1, 22, 'Cette fonction permet d\'intégrer un nouvel employé au système.'),
(63, 2, 22, '1. Cliquez sur le bouton \"Ajouter un employé\" en haut à droite de la page.'),
(64, 3, 22, '2. Entrez ensuite les informations de l\'employé.'),
(65, 4, 22, '3. Cliquez sur le bouton \"Inscription\" en bas de la page.'),
(66, 1, 23, 'Cette fonction permet de gérer l\'identité visuelle principale de votre carte.'),
(67, 2, 23, 'Pour Ajouter un Logo'),
(68, 3, 23, '1. Cliquez sur \"Choisir un fichier\".'),
(69, 4, 23, '2. Sélectionnez un logo dans votre explorateur de fichiers.'),
(70, 5, 23, '3. Cliquez ensuite sur \"Enregistrer\".'),
(71, 6, 23, 'Pour Supprimer un Logo'),
(72, 7, 23, 'Cliquez sur le bouton \"Supprimer\".'),
(73, 8, 23, '⚠️ Vous ne pouvez ajouter qu\'un seul logo (formats acceptés : SVG, PNG, JPG, JPEG).'),
(74, 1, 24, 'Cette fonctionnalité permet d\'intégrer un document PDF à votre carte.'),
(75, 2, 24, 'Ajouter un PDF'),
(76, 3, 24, '1. Cliquez sur \"Choisir un fichier\".'),
(77, 4, 24, '2. Sélectionnez un PDF dans votre explorateur de fichiers.'),
(78, 5, 24, '3. Nommez votre PDF (le nom choisi apparaîtra dans votre Wisikard).'),
(79, 6, 24, '4. Cliquez ensuite sur \"Enregistrer\".'),
(80, 7, 24, 'Supprimer un PDF'),
(81, 8, 24, 'Cliquez sur le bouton \"Supprimer\".'),
(82, 9, 24, '⚠️ Vous ne pouvez ajouter qu\'un seul PDF.'),
(83, 1, 25, 'Cette fonction permet d\'intégrer des contenus vidéo YouTube à votre carte.'),
(84, 2, 25, 'Ajouter une Vidéo YouTube'),
(85, 3, 25, '1. Allez sur YouTube et cliquez sur la vidéo que vous avez choisie.'),
(86, 4, 25, '2. Copiez l\'URL de la vidéo.'),
(87, 5, 25, '3. Allez ensuite sur la page \"Contenu\" et collez le lien de la vidéo YouTube.'),
(88, 6, 25, '4. Cliquez ensuite sur le bouton \"Enregistrer\".'),
(89, 7, 25, 'Supprimer une Vidéo YouTube'),
(90, 8, 25, 'Cliquez sur le bouton \"Supprimer\".'),
(91, 9, 25, '⚠️ Vous ne pouvez ajouter que des vidéos YouTube.'),
(92, 1, 26, 'Cette fonction permet d\'intégrer vos avis Google, augmentant ainsi la crédibilité de votre entreprise.'),
(93, 2, 26, 'Ajouter un Lien d\'Avis Google'),
(94, 3, 26, '1. Copiez l\'URL de votre page d\'avis Google.'),
(95, 4, 26, '2. Allez ensuite sur la page \"Contenu\" et collez le lien.'),
(96, 5, 26, '3. Cliquez ensuite sur le bouton \"Enregistrer\".'),
(97, 6, 26, 'Supprimer un Lien d\'Avis Google'),
(98, 7, 26, 'Cliquez sur le bouton \"Supprimer\".'),
(99, 8, 26, '⚠️ Vous ne pouvez ajouter qu\'un seul lien d\'avis Google.'),
(100, 1, 27, 'Cette fonction permet d\'intégrer votre système de réservation en ligne.'),
(101, 2, 27, 'Ajouter une URL de Prise de Rendez-vous'),
(102, 3, 27, '1. Allez sur votre site de prise de rendez-vous.'),
(103, 4, 27, '2. Copiez le lien menant à votre entreprise.'),
(104, 5, 27, '3. Allez ensuite sur la page \"Contenu\" et collez le lien.'),
(105, 6, 27, '4. Cliquez ensuite sur le bouton \"Enregistrer\".'),
(106, 7, 27, 'Supprimer une URL de Prise de Rendez-vous'),
(107, 8, 27, 'Cliquez sur le bouton \"Supprimer\".'),
(108, 9, 27, '⚠️ Vous ne pouvez ajouter qu\'une seule URL de prise de rendez-vous.'),
(109, 1, 28, 'Cette fonction offre une gestion flexible de vos visuels.'),
(110, 2, 28, 'Ajouter des Photos'),
(111, 3, 28, '1. Cliquez sur \"Choisir un fichier\".'),
(112, 4, 28, '2. Sélectionnez une ou plusieurs images dans votre explorateur de fichiers (maintenez la touche Ctrl pour sélectionner plusieurs images).'),
(113, 5, 28, '3. Cliquez ensuite sur \"Enregistrer\".'),
(114, 6, 28, 'Supprimer une Photo'),
(115, 7, 28, 'Cliquez sur la petite croix en haut à droite de l\'image/photo que vous souhaitez supprimer.'),
(116, 8, 28, '⚠️ Vous pouvez ajouter jusqu\'à 10 images/photos.'),
(117, 2, 16, 'Pour l\'activation : la case cochée en bleu rend le réseau social visible sur votre profil'),
(118, 3, 16, 'Pour la désactivation : la case décochée en gris masque temporairement le réseau social sans supprimer les informations'),
(119, 1, 29, 'Ce QR Code mène à la Wisikard de l\'employé, affichant ses informations.'),
(120, 2, 29, 'Pour télécharger le QR Code de l\'employé, appuyez sur l\'icône montrée ci-dessous.'),
(122, 1, 31, 'Cette fonctionnalité permet de lier votre site web principal à votre carte, créant ainsi un pont entre vos différentes présences numériques.'),
(123, 2, 31, 'Ajouter un Lien de votre site web'),
(124, 3, 31, '1. Copiez l\'URL de votre site web.'),
(125, 4, 31, '2. Allez ensuite sur la page \"Contenu\" et collez le lien.'),
(126, 5, 31, '3. Cliquez ensuite sur le bouton \"Enregistrer\".'),
(127, 6, 31, 'Supprimer un Lien de votre site web'),
(128, 7, 31, 'Cliquez sur le bouton \"Supprimer\".'),
(129, 8, 31, '⚠️ Vous ne pouvez ajouter qu\'un seul lien.'),
(137, 1, 30, 'Si vous avez oublié votre mot de passe, vous pouvez le réinitialiser en suivant ces étapes :'),
(138, 2, 30, '1. Accédez à la page de connexion : Allez sur la page de connexion de l\'application.'),
(139, 3, 30, '2. Cliquez sur \"Mot de passe oublié ?\" : Cliquez sur le lien \"Mot de passe oublié ?\" sous le champ de saisie du mot de passe.'),
(140, 4, 30, '3. Entrez votre adresse e-mail : Saisissez l\'adresse e-mail associée à votre compte.'),
(141, 5, 30, '4. Suivez les instructions : Vous recevrez un e-mail avec des instructions pour réinitialiser votre mot de passe. Suivez ces instructions pour créer un nouveau mot de passe.'),
(142, 1, 32, '1. Ouvrez Safari sur votre iPhone et accédez à votre Kard publique'),
(143, 2, 32, '2. Appuyez sur le bouton \"Partager\" en bas de l\'écran'),
(144, 3, 32, '3. Faites défiler le menu et appuyez sur \"Sur l\'écran d\'accueil\"'),
(145, 4, 32, '4. Personnalisez le nom si vous le souhaitez, puis appuyez sur \"Ajouter\"'),
(146, 5, 32, 'Une fois installée, votre carte sera accessible directement depuis votre écran d\'accueil comme une application native.'),
(147, 1, 33, '1. Appuyez sur le bouton “Fiche de contact”\r\nSur votre Kard, ou lorsque quelqu’un consulte votre profil via le QR Code, il suffit de cliquer sur le bouton “Fiche de contact”.'),
(148, 2, 33, '2. Téléchargement automatique du fichier\r\nUn fichier .vcf (format standard pour les contacts) va automatiquement se télécharger sur le smartphone.'),
(149, 3, 33, '3. Ouvrez le fichier téléchargé.\r\nFaites glisser la barre de notifications de votre téléphone (ou accèdez aux téléchargements), puis cliquez sur le fichier .vcf.'),
(150, 4, 33, '4. Le téléphone vous proposera d\'ajouter ce contact à votre carnet d\'adresses. Validez.\r\nToutes vos informations (nom, téléphone, e-mail, etc.) seront alors enregistrées automatiquement.'),
(151, 1, 34, 'Votre QR Code est bien plus qu’un simple visuel : c’est la passerelle directe vers toutes vos informations professionnelles. Utilisez-le stratégiquement pour booster votre visibilité.\r\n\r\nÉtapes simples pour le partager :\r\n1. Téléchargez votre QR Code\r\nDepuis votre interface Wisikard, récupérez votre QR Code ou votre lien public (ex. app.wisikard.fr/Kard/votreentreprise).'),
(152, 2, 34, '2. Intégrez-le à votre communication\r\nAffichez-le partout où votre audience peut le scanner :\r\nSignature mail\r\nCarte de visite physique\r\nFlyers, brochures, kakémonos\r\nBadges événementiels\r\nPrésentations PowerPoint\r\nRéseaux sociaux ou portfolio en ligne...'),
(153, 3, 34, '3. Rendez-le visible, toujours !\r\nPlus votre QR Code est accessible, plus vous facilitez la prise de contact. Pensez à le mettre en évidence sur vos supports les plus consultés.');

-- --------------------------------------------------------

--
-- Structure de la table `vue`
--

DROP TABLE IF EXISTS `vue`;
CREATE TABLE IF NOT EXISTS `vue` (
  `idVue` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `idCarte` int(11) NOT NULL,
  `idEmp` int(11) DEFAULT NULL,
  `ip_address` text DEFAULT NULL,
  PRIMARY KEY (`idVue`),
  KEY `vue_carte_FK` (`idCarte`),
  KEY `vue_employer_FK` (`idEmp`)
) ENGINE=InnoDB AUTO_INCREMENT=453 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

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
  ADD CONSTRAINT `fk_custom_link_carte` FOREIGN KEY (`idCarte`) REFERENCES `carte` (`idCarte`) ON DELETE CASCADE;

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
