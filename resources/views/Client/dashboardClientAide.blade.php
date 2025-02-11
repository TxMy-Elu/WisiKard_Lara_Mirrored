<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Client Aide</title>
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
<body class="bg-gray-100 flex flex-col md:flex-row">
    @include('menu.menuClient') <!-- Inclure le menu client -->
    <div class="flex-1 md:ml-24 p-4 mt-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Accueil -->
            <div class="bg-white rounded-lg shadow-md p-6 flex flex-col items-center space-y-2">
                <p class="text-xl font-semibold text-red-800">Accueil</p>
                <p class="text-gray-800">Modifier les Informations de l'Entreprise</p>
                <p class="text-gray-800">Changer la Police</p>
                <p class="text-gray-800">Changer le Titre et la Description</p>
                <p class="text-gray-800">Gestion des Horaires</p>
                <p class="text-gray-800">Changer la Couleur du QR Code</p>
                <p class="text-gray-800">Télécharger le QR Code de l'Entreprise</p>
                <p class="text-gray-800">Télécharger le QR Code du PDF</p>
                <p class="text-gray-800">Changer le Thème de la Carte</p>
            </div>

            <!-- Choisir l'Année -->
            <div class="bg-white rounded-lg shadow-md p-6 flex flex-col items-center space-y-2">
                <p class="text-xl font-semibold text-red-800">Choisir l'Année</p>
                <p class="text-gray-800">Choisir la Semaine</p>
                <p class="text-gray-800">Nombre de Vues par Employé</p>
                <p class="text-gray-800">Nombre Global de Vues</p>
                <p class="text-gray-800">Nombre de Vues par Semaine</p>
            </div>

            <!-- Statistiques -->
            <div class="bg-white rounded-lg shadow-md p-6 flex flex-col items-center space-y-2">
                <p class="text-xl font-semibold text-red-800">Statistiques</p>
                <p class="text-gray-800">Choisir l'Année</p>
                <p class="text-gray-800">Choisir la Semaine</p>
                <p class="text-gray-800">Nombre de Vues par Employé</p>
                <p class="text-gray-800">Nombre Global de Vues</p>
                <p class="text-gray-800">Nombre de Vues par Semaine</p>
            </div>

            <!-- Réseaux Sociaux -->
            <div class="bg-white rounded-lg shadow-md p-6 flex flex-col items-center space-y-2">
                <p class="text-xl font-semibold text-red-800">Réseaux Sociaux</p>
                <p class="text-gray-800">Ajouter un Réseau Social</p>
                <p class="text-gray-800">Mettre à jour un Réseau Social</p>
                <p class="text-gray-800">Activer/Désactiver un Réseau Social</p>
                <p class="text-gray-800">Ajouter un Autre Réseau Social</p>
            </div>

            <!-- Employé -->
            <div class="bg-white rounded-lg shadow-md p-6 flex flex-col items-center space-y-2">
                <p class="text-xl font-semibold text-red-800">Employé</p>
                <p class="text-gray-800">Recherche</p>
                <p class="text-gray-800">Modifier les Informations de l'Employé</p>
                <p class="text-gray-800">Supprimer un Employé</p>
                <p class="text-gray-800">Rafraîchir le QR Code de l'Employé</p>
                <p class="text-gray-800">Ajouter un Employé</p>
            </div>
        </div>
    </div>
</body>
</html>
