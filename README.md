Directory structure:

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


```
