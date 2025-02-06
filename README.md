# Manuel d'utilisation Wisikard

## Table des Matières

1. [Introduction](#introduction)
2. [Inscription et Connexion](#inscription-et-connexion)
3. [Tableau de Bord Admin](#tableau-de-bord-admin)
   - [Gestion des Comptes](#gestion-des-comptes)
   - [Statistiques](#statistiques)
   - [Messages](#messages)
   - [Gestion des Inscriptions en Attente](#gestion-des-inscriptions-en-attente)
4. [Tableau de Bord Client](#tableau-de-bord-client)
   - [Informations de l'Entreprise](#informations-de-lentreprise)
   - [Gestion des Liens](#gestion-des-liens)
   - [Gestion des Employés](#gestion-des-employés)
   - [Gestion des Templates](#gestion-des-templates)
   - [Gestion des Réseaux Sociaux](#gestion-des-réseaux-sociaux)
   - [Gestion des PDF et Images](#gestion-des-pdf-et-images)
   - [Gestion des Horaires](#gestion-des-horaires)
5. [Mot de passe oublié ?](#mot-de-passe-oublié-)
6. [Support et Assistance](#support-et-assistance)
7. [Architecture du projet](#architecture-du-projet)

## Introduction

Wisikard est une plateforme conçue pour aider les entreprises à créer et gérer des cartes de visite numériques grâce à des QR Codes. Ces cartes peuvent inclure des informations sur l'entreprise, des liens vers des réseaux sociaux, des PDF, des images, et bien plus encore.

## Inscription et Connexion

Pour commencer, inscrivez-vous en fournissant les informations nécessaires telles que votre adresse e-mail, un mot de passe sécurisé, et les détails de votre entreprise.

![Texte alternatif](/public/images/inscriptionclient.png "Inscrire")

Une fois inscrit, connectez-vous à l'application en utilisant votre adresse e-mail et votre mot de passe.

![Texte alternatif](/public/images/Connexion.png "Connexion")

## Tableau de Bord Admin

### Gestion des Comptes

En tant qu'**administrateur**, vous pouvez voir et gérer tous les comptes utilisateurs. Vous pouvez activer, désactiver ou supprimer des comptes.

### Statistiques

Accédez aux statistiques de l'application, comme le nombre de vues par mois.

### Messages

Vous pouvez afficher et gérer les messages destinés aux utilisateurs.

### Gestion des Inscriptions en Attente

#### Voir les Inscriptions

Consultez la liste des inscriptions en attente.

#### Approuver une Inscription

Approuvez les inscriptions pour créer de nouveaux comptes utilisateurs.

#### Supprimer une Inscription

Supprimez les inscriptions qui ne sont plus nécessaires.

## Tableau de Bord Client

### Informations de l'Entreprise

Mettez à jour les informations de votre entreprise, telles que le nom, le numéro de téléphone, et l'adresse e-mail.

### Gestion des Liens

Ajoutez des liens personnalisés et des liens vers des réseaux sociaux.

### Gestion des Employés

#### Ajouter un Employé

Ajoutez de nouveaux employés à votre entreprise en fournissant leurs informations de contact.

#### Modifier un Employé

Mettez à jour les informations des employés existants.

#### Supprimer un Employé

Supprimez les employés qui ne font plus partie de votre entreprise.

### Gestion des Templates

#### Choisir un Template

Sélectionnez un template pour votre carte de visite parmi les options disponibles.

#### Personnaliser le Template

Personnalisez le template choisi en ajoutant des couleurs, des images, et des liens.

### Gestion des Réseaux Sociaux

#### Ajouter des Liens

Ajoutez des liens vers vos profils sur les réseaux sociaux.

#### Activer/Désactiver des Liens

Activez ou désactivez les liens vers les réseaux sociaux selon vos besoins.

### Gestion des PDF et Images

#### Télécharger des Images

Téléchargez des images pour personnaliser votre carte de visite.

#### Télécharger des PDF

Téléchargez des fichiers PDF pour les inclure dans votre carte de visite.

#### Gérer les Fichiers

Supprimez ou mettez à jour les fichiers PDF et images téléchargés.

### Gestion des Horaires

#### Ajouter des Horaires

Définissez les horaires d'ouverture et de fermeture de votre entreprise.

#### Modifier des Horaires

Mettez à jour les horaires existants en fonction des changements de votre emploi du temps.

## Mot de passe oublié ?

Si vous avez oublié votre mot de passe, vous pouvez le réinitialiser en suivant ces étapes :

1. **Accédez à la page de connexion** : Allez sur la page de connexion de l'application.
2. **Cliquez sur "Mot de passe oublié ?"** : Cliquez sur le lien "Mot de passe oublié ?" sous le champ de saisie du mot de passe.
3. **Entrez votre adresse e-mail** : Saisissez l'adresse e-mail associée à votre compte.
4. **Suivez les instructions** : Vous recevrez un e-mail avec des instructions pour réinitialiser votre mot de passe. Suivez ces instructions pour créer un nouveau mot de passe.

## Support et Assistance

### FAQ

Consultez la section FAQ pour obtenir des réponses aux questions courantes.

### Contact

Si vous avez besoin d'aide, n'hésitez pas à contacter notre équipe de support via l'adresse e-mail de support fournie dans l'application.

En suivant ce guide, vous devriez être en mesure de naviguer et d'utiliser efficacement l'application Wisikard pour créer et gérer vos cartes de visite numériques.

[Retour à la table des matières](#table-des-matières)

## Architecture du projet

```txt
Directory structure:
└── txmy-elu-wisikard_lara/
    ├── README.md
    ├── artisan
    ├── bdd.sql
    ├── composer.json
    ├── composer.lock
    ├── package.json
    ├── phpunit.xml
    ├── script.sql
    ├── server.php
    ├── webpack.mix.js
    ├── .env.example
    ├── .styleci.yml
    ├── app/
    │   ├── Console/
    │   │   └── Kernel.php
    │   ├── Exceptions/
    │   │   └── Handler.php
    │   ├── Http/
    │   │   ├── Kernel.php
    │   │   ├── Controllers/
    │   │   │   ├── Connexion.php
    │   │   │   ├── Controller.php
    │   │   │   ├── DashboardAdmin.php
    │   │   │   ├── DashboardClient.php
    │   │   │   ├── Email.php
    │   │   │   ├── Employe.php
    │   │   │   ├── Entreprise.php
    │   │   │   ├── Inscription.php
    │   │   │   ├── Profil.php
    │   │   │   ├── RecuperationCompte.php
    │   │   │   └── Templates.php
    │   │   └── Middleware/
    │   │       ├── AdminMiddleware.php
    │   │       ├── Authenticate.php
    │   │       ├── Authentification.php
    │   │       ├── EncryptCookies.php
    │   │       ├── NonAuthentifie.php
    │   │       ├── PreventRequestsDuringMaintenance.php
    │   │       ├── RedirectIfAuthenticated.php
    │   │       ├── TrimStrings.php
    │   │       ├── TrustHosts.php
    │   │       ├── TrustProxies.php
    │   │       ├── ValidateSignature.php
    │   │       └── VerifyCsrfToken.php
    │   ├── Models/
    │   │   ├── Carte.php
    │   │   ├── Compte.php
    │   │   ├── Custom_Link.php
    │   │   ├── Employer.php
    │   │   ├── Logs.php
    │   │   ├── Message.php
    │   │   ├── Reactivation.php
    │   │   ├── Recuperation.php
    │   │   ├── Rediriger.php
    │   │   ├── Social.php
    │   │   ├── Template.php
    │   │   └── Vue.php
    │   └── Providers/
    │       ├── AppServiceProvider.php
    │       ├── AuthServiceProvider.php
    │       ├── BroadcastServiceProvider.php
    │       ├── EventServiceProvider.php
    │       └── RouteServiceProvider.php
    ├── bootstrap/
    │   ├── app.php
    │   └── cache/
    │       ├── packages.php
    │       └── services.php
    ├── config/
    │   ├── app.php
    │   ├── auth.php
    │   ├── broadcasting.php
    │   ├── cache.php
    │   ├── cors.php
    │   ├── database.php
    │   ├── filesystems.php
    │   ├── hashing.php
    │   ├── logging.php
    │   ├── mail.php
    │   ├── queue.php
    │   ├── sanctum.php
    │   ├── services.php
    │   ├── session.php
    │   └── view.php
    ├── database/
    │   ├── factories/
    │   │   └── UserFactory.php
    │   ├── migrations/
    │   │   ├── 2014_10_12_000000_create_users_table.php
    │   │   ├── 2014_10_12_100000_create_password_resets_table.php
    │   │   ├── 2019_08_19_000000_create_failed_jobs_table.php
    │   │   └── 2019_12_14_000001_create_personal_access_tokens_table.php
    │   └── seeders/
    │       └── DatabaseSeeder.php
    ├── public/
    │   ├── index.php
    │   ├── robots.txt
    │   ├── css/
    │   │   └── styles.css
    │   ├── entreprises/
    │   │   ├── 14_nomEntreprise/
    │   │   │   └── QR_Codes/
    │   │   ├── 14_nomentreprise/
    │   │   │   └── images/
    │   │   ├── 1_lidl/
    │   │   │   ├── QR_Codes/
    │   │   │   ├── images/
    │   │   │   ├── logos/
    │   │   │   ├── slider/
    │   │   │   └── videos/
    │   │   │       └── videos.json
    │   │   ├── 23_OUIOUI/
    │   │   │   └── QR_Codes/
    │   │   ├── 2_Entreprise2/
    │   │   │   └── QR_Codes/
    │   │   ├── 3_Entreprise3/
    │   │   │   └── QR_Codes/
    │   │   ├── 4_Entreprise4/
    │   │   │   └── QR_Codes/
    │   │   └── 6_1/
    │   │       └── QR_Codes/
    │   ├── icons/
    │   └── images/
    ├── resources/
    │   ├── css/
    │   │   └── app.css
    │   ├── js/
    │   │   ├── app.js
    │   │   └── bootstrap.js
    │   ├── lang/
    │   │   └── en/
    │   │       ├── auth.php
    │   │       ├── pagination.php
    │   │       ├── passwords.php
    │   │       └── validation.php
    │   └── views/
    │       ├── confirmationInscription.blade.php
    │       ├── messageErreur.blade.php
    │       ├── pageErreur.blade.php
    │       ├── profil.blade.php
    │       ├── welcome.blade.php
    │       ├── Admin/
    │       │   ├── dashboardAdmin.blade.php
    │       │   ├── dashboardAdminMessage.blade.php
    │       │   └── dashboardAdminStatistique.blade.php
    │       ├── Client/
    │       │   ├── dashboardClient.blade.php
    │       │   ├── dashboardClientEmploye.blade.php
    │       │   ├── dashboardClientPDF.blade.php
    │       │   ├── dashboardClientSocial.blade.php
    │       │   └── dashboardClientStatistique.blade.php
    │       ├── Formulaire/
    │       │   ├── formulaireChangementMotDePasse.blade.php
    │       │   ├── formulaireConnexion.blade.php
    │       │   ├── formulaireEmploye.blade.php
    │       │   ├── formulaireEntreprise.blade.php
    │       │   ├── formulaireInscription.blade.php
    │       │   ├── formulaireInscriptionEmploye.blade.php
    │       │   ├── formulaireModifEmploye.blade.php
    │       │   └── formulaireRecuperation.blade.php
    │       ├── Templates/
    │       │   └── Iframe/
    │       │       ├── fraise.blade.php
    │       │       ├── peche.blade.php
    │       │       └── pomme.blade.php
    │       ├── menu/
    │       │   ├── menuAdmin.blade.php
    │       │   └── menuClient.blade.php
    │       └── templates/
    │           ├── fraise.blade.php
    │           ├── peche.blade.php
    │           └── pomme.blade.php
    ├── routes/
    │   ├── api.php
    │   ├── channels.php
    │   ├── console.php
    │   └── web.php
    ├── storage/
    │   └── logs/
    └── tests/
        ├── CreatesApplication.php
        ├── TestCase.php
        ├── Feature/
        │   └── ExampleTest.php
        └── Unit/
            └── ExampleTest.php
