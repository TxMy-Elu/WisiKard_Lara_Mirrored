<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Wisikard - Aide</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet"/>
    <style>
        .qr-code-container {
            width: 100px; /* Taille fixe pour le conteneur du QR code */
            height: 100px;
            background-color: #f1f1f1; /* Couleur de fond pour le conteneur */
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden; /* Couper le contenu qui dépasse */
            border-radius: 8px; /* Coins arrondis */
        }

        .qr-code-container img {
            max-width: 100%; /* Assure que l'image s'adapte au conteneur */
            max-height: 100%;
        }

        @media (max-width: 768px) {
            .desktop-only {
                display: none;
            }

            .mobile-only {
                display: block;
            }

            .qr-code-container {
                width: 80px; /* Taille fixe pour le conteneur du QR code sur mobile */
                height: 80px;
            }

            .grid-cols-4 {
                grid-template-columns: 1fr;
            }

            .md\:flex-row {
                flex-direction: column;
            }

            .md\:ml-24 {
                margin-left: 0;
            }

            .md\:w-64 {
                width: 100%;
            }

            .md\:w-auto {
                width: 100%;
            }

            .content {
                padding: 1rem;
            }
        }

        @media (min-width: 769px) {
            .mobile-only {
                display: none;
            }
        }
    </style>
</head>
<body class="bg-gray-50 flex flex-col md:flex-row min-h-screen">
    @include('menu.menuClient')
    <div class="flex-1 md:ml-24 p-6 mt-4">
        <!-- Barre de recherche -->
        <div class="mb-6 flex justify-center">


        <!-- Affichage des cartes -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

            <!-- Accueil -->
            <div class="bg-white rounded-lg shadow-lg p-6 flex flex-col items-center space-y-4">
                <p class="text-2xl font-bold text-red-800 border-b-2 border-red-300 pb-2 mb-8">Accueil</p>
                <a href="{{ route('dashboardClientDescription', ['id_guide' => 1]) }}" class="text-gray-700 hover:text-red-500 text-lg leading-relaxed">Modifier les Informations de l'Entreprise</a>
                <a href="{{ route('dashboardClientDescription', ['id_guide' => 2]) }}" class="text-gray-700 hover:text-red-500 text-lg leading-relaxed">Changer la Police</a>
                <a href="{{ route('dashboardClientDescription', ['id_guide' => 3]) }}" class="text-gray-700 hover:text-red-500 text-lg leading-relaxed">Changer le Titre et la Description</a>
                <a href="{{ route('dashboardClientDescription', ['id_guide' => 4]) }}" class="text-gray-700 hover:text-red-500 text-lg leading-relaxed">Gestion des Horaires</a>
                <a href="{{ route('dashboardClientDescription', ['id_guide' => 5]) }}" class="text-gray-700 hover:text-red-500 text-lg leading-relaxed">Changer la Couleur du QR Code</a>
                <a href="{{ route('dashboardClientDescription', ['id_guide' => 6]) }}" class="text-gray-700 hover:text-red-500 text-lg leading-relaxed">Télécharger le QR Code de l'Entreprise</a>
                <a href="{{ route('dashboardClientDescription', ['id_guide' => 7]) }}" class="text-gray-700 hover:text-red-500 text-lg leading-relaxed">Télécharger le QR Code du PDF</a>
                <a href="{{ route('dashboardClientDescription', ['id_guide' => 8]) }}" class="text-gray-700 hover:text-red-500 text-lg leading-relaxed">Changer le Thème de la Carte</a>
                <a href="{{ route('dashboardClientDescription', ['id_guide' => 30]) }}" class="text-gray-700 hover:text-red-500 text-lg leading-relaxed">Mot de passe oublié ?</a>
            </div>

            <!-- Statistiques -->
            <div class="bg-white rounded-lg shadow-lg p-6 flex flex-col items-center space-y-4">
                <p class="text-2xl font-bold text-red-800 border-b-2 border-red-300 pb-2 mb-8">Statistiques</p>
                <a href="{{ route('dashboardClientDescription', ['id_guide' => 9]) }}" class="text-gray-700 hover:text-red-500 text-lg leading-relaxed">Choisir l'Année</a>
                <a href="{{ route('dashboardClientDescription', ['id_guide' => 10]) }}" class="text-gray-700 hover:text-red-500 text-lg leading-relaxed">Choisir la Semaine</a>
                <a href="{{ route('dashboardClientDescription', ['id_guide' => 11]) }}" class="text-gray-700 hover:text-red-500 text-lg leading-relaxed">Nombre de Vues par Employé</a>
                <a href="{{ route('dashboardClientDescription', ['id_guide' => 12]) }}" class="text-gray-700 hover:text-red-500 text-lg leading-relaxed">Nombre Global de Vues</a>
                <a href="{{ route('dashboardClientDescription', ['id_guide' => 13]) }}" class="text-gray-700 hover:text-red-500 text-lg leading-relaxed">Nombre de Vues par Semaine</a>
            </div>

            <!-- Réseaux Sociaux -->
            <div class="bg-white rounded-lg shadow-lg p-6 flex flex-col items-center space-y-4">
                <p class="text-2xl font-bold text-red-800 border-b-2 border-red-300 pb-2 mb-8">Réseaux Sociaux</p>
                <a href="{{ route('dashboardClientDescription', ['id_guide' => 14]) }}" class="text-gray-700 hover:text-red-500 text-lg leading-relaxed">Ajouter un Réseau Social</a>
                <a href="{{ route('dashboardClientDescription', ['id_guide' => 15]) }}" class="text-gray-700 hover:text-red-500 text-lg leading-relaxed">Mettre à jour un Réseau Social</a>
                <a href="{{ route('dashboardClientDescription', ['id_guide' => 16]) }}" class="text-gray-700 hover:text-red-500 text-lg leading-relaxed">Activer/Désactiver un Réseau Social</a>
                <a href="{{ route('dashboardClientDescription', ['id_guide' => 17]) }}" class="text-gray-700 hover:text-red-500 text-lg leading-relaxed">Ajouter un Autre Réseau Social</a>
            </div>

            <!-- Employé -->
            <div class="bg-white rounded-lg shadow-lg p-6 flex flex-col items-center space-y-4">
                <p class="text-2xl font-bold text-red-800 border-b-2 border-red-300 pb-2 mb-8">Employé</p>
                <a href="{{ route('dashboardClientDescription', ['id_guide' => 18]) }}" class="text-gray-700 hover:text-red-500 text-lg leading-relaxed">Recherche</a>
                <a href="{{ route('dashboardClientDescription', ['id_guide' => 19]) }}" class="text-gray-700 hover:text-red-500 text-lg leading-relaxed">Modifier les Informations de l'Employé</a>
                <a href="{{ route('dashboardClientDescription', ['id_guide' => 20]) }}" class="text-gray-700 hover:text-red-500 text-lg leading-relaxed">Supprimer un Employé</a>
                <a href="{{ route('dashboardClientDescription', ['id_guide' => 21]) }}" class="text-gray-700 hover:text-red-500 text-lg leading-relaxed">Rafraîchir le QR Code de l'Employé</a>
                <a href="{{ route('dashboardClientDescription', ['id_guide' => 29]) }}" class="text-gray-700 hover:text-red-500 text-lg leading-relaxed">Télécharger le QR Code de l'Employé</a>
                <a href="{{ route('dashboardClientDescription', ['id_guide' => 22]) }}" class="text-gray-700 hover:text-red-500 text-lg leading-relaxed">Ajouter un Employé</a>
            </div>

            <!-- Contenu -->
            <div class="bg-white rounded-lg shadow-lg p-6 flex flex-col items-center space-y-4">
                <p class="text-2xl font-bold text-red-800 border-b-2 border-red-300 pb-2 mb-8">Contenu</p>
                <a href="{{ route('dashboardClientDescription', ['id_guide' => 23]) }}" class="text-gray-700 hover:text-red-500 text-lg leading-relaxed">Ajouter/Supprimer un Logo</a>
                <a href="{{ route('dashboardClientDescription', ['id_guide' => 24]) }}" class="text-gray-700 hover:text-red-500 text-lg leading-relaxed">Ajouter/Supprimer un PDF</a>
                <a href="{{ route('dashboardClientDescription', ['id_guide' => 25]) }}" class="text-gray-700 hover:text-red-500 text-lg leading-relaxed">Ajouter/Supprimer des Vidéos YouTube</a>
                <a href="{{ route('dashboardClientDescription', ['id_guide' => 26]) }}" class="text-gray-700 hover:text-red-500 text-lg leading-relaxed">Ajouter/Supprimer un Lien d'Avis Google</a>
                <a href="{{ route('dashboardClientDescription', ['id_guide' => 31]) }}" class="text-gray-700 hover:text-red-500 text-lg leading-relaxed">Ajouter/Supprimer un lien vers un Site Web</a>
                <a href="{{ route('dashboardClientDescription', ['id_guide' => 27]) }}" class="text-gray-700 hover:text-red-500 text-lg leading-relaxed">Ajouter/Supprimer une URL de Prise de Rendez-vous</a>
                <a href="{{ route('dashboardClientDescription', ['id_guide' => 28]) }}" class="text-gray-700 hover:text-red-500 text-lg leading-relaxed">Ajouter/Supprimer une Galerie Photo</a>
            </div>
            
            <!-- Kard -->
            <div class="bg-white rounded-lg shadow-lg p-6 flex flex-col items-center space-y-4">
                <p class="text-2xl font-bold text-red-800 border-b-2 border-red-300 pb-2 mb-8">Kard</p>
                <a href="{{ route('dashboardClientDescription', ['id_guide' => 33]) }}" class="text-gray-700 hover:text-red-500 text-lg leading-relaxed">Utiliser la fonction "Fiche de contact"</a>
                <a href="{{ route('dashboardClientDescription', ['id_guide' => 34]) }}" class="text-gray-700 hover:text-red-500 text-lg leading-relaxed">Partager son QR Code efficacement</a>
                <a href="{{ route('dashboardClientDescription', ['id_guide' => 32]) }}" class="text-gray-700 hover:text-red-500 text-lg leading-relaxed">Installer l'application sur l'écran d'accueil depuis un Iphone</a>
            </div>
        </div>
    </div>
</body>
</html>
