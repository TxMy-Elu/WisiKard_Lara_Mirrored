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
                <a href="{{ route('dashboardClientDescription', ['id_guide' => 1]) }}" class="text-gray-800 hover:text-red-500">Modifier les Informations de l'Entreprise</a>
                <a href="{{ route('dashboardClientDescription', ['id_guide' => 2]) }}" class="text-gray-800 hover:text-red-500">Changer la Police</a>
                <a href="{{ route('dashboardClientDescription', ['id_guide' => 3]) }}" class="text-gray-800 hover:text-red-500">Changer le Titre et la Description</a>
                <a href="{{ route('dashboardClientDescription', ['id_guide' => 4]) }}" class="text-gray-800 hover:text-red-500">Gestion des Horaires</a>
                <a href="{{ route('dashboardClientDescription', ['id_guide' => 5]) }}" class="text-gray-800 hover:text-red-500">Changer la Couleur du QR Code</a>
                <a href="{{ route('dashboardClientDescription', ['id_guide' => 6]) }}" class="text-gray-800 hover:text-red-500">Télécharger le QR Code de l'Entreprise</a>
                <a href="{{ route('dashboardClientDescription', ['id_guide' => 7]) }}" class="text-gray-800 hover:text-red-500">Télécharger le QR Code du PDF</a>
                <a href="{{ route('dashboardClientDescription', ['id_guide' => 8]) }}" class="text-gray-800 hover:text-red-500">Changer le Thème de la Carte</a>
                <a href="{{ route('dashboardClientDescription', ['id_guide' => 30]) }}" class="text-gray-800 hover:text-red-500">Mot de passe oublié ?</a>

            </div>
  <!-- Statistiques -->
        <div class="bg-white rounded-lg shadow-md p-6 flex flex-col items-center space-y-2">
            <p class="text-xl font-semibold text-red-800">Statistiques</p>
            <a href="{{ route('dashboardClientDescription', ['id_guide' =>9]) }}"class="text-gray-800 hover:text-red-500">Choisir l'Année</a>
            <a href="{{ route('dashboardClientDescription', ['id_guide' => 10]) }}" class="text-gray-800 hover:text-red-500">Choisir la Semaine</a>
            <a href="{{ route('dashboardClientDescription', ['id_guide' => 11]) }}" class="text-gray-800 hover:text-red-500">Nombre de Vues par Employé</a>
            <a href="{{ route('dashboardClientDescription', ['id_guide' => 12]) }}"class="text-gray-800 hover:text-red-500">Nombre Global de Vues</a>
            <a href="{{ route('dashboardClientDescription', ['id_guide' => 13]) }}"class="text-gray-800 hover:text-red-500">Nombre de Vues par Semaine</a>
        </div>

            <!-- Réseaux Sociaux -->
          <div class="bg-white rounded-lg shadow-md p-6 flex flex-col items-center space-y-2">
              <p class="text-xl font-semibold text-red-800">Réseaux Sociaux</p>
              <a href="{{ route('dashboardClientDescription', ['id_guide' => 14]) }}" class="text-gray-800 hover:text-red-500">Ajouter un Réseau Social</a>
              <a href="{{ route('dashboardClientDescription', ['id_guide' => 15]) }}"class="text-gray-800 hover:text-red-500">Mettre à jour un Réseau Social</a>
              <a href="{{ route('dashboardClientDescription', ['id_guide' => 16]) }}"class="text-gray-800 hover:text-red-500">Activer/Désactiver un Réseau Social</a>
              <a href="{{ route('dashboardClientDescription', ['id_guide' => 17]) }}" class="text-gray-800 hover:text-red-500">Ajouter un Autre Réseau Social</a>
          </div>


           <!-- Employé -->
           <div class="bg-white rounded-lg shadow-md p-6 flex flex-col items-center space-y-2">
               <p class="text-xl font-semibold text-red-800">Employé</p>

               <a href="{{ route('dashboardClientDescription', ['id_guide' => 18]) }}" class="text-gray-800 hover:text-red-500">Recherche</a>
               <a href="{{ route('dashboardClientDescription', ['id_guide' => 19]) }}" class="text-gray-800 hover:text-red-500">Modifier les Informations de l'Employé</a>
               <a href="{{ route('dashboardClientDescription', ['id_guide' => 20]) }}" class="text-gray-800 hover:text-red-500">Supprimer un Employé</a>
               <a href="{{ route('dashboardClientDescription', ['id_guide' => 21]) }}" class="text-gray-800 hover:text-red-500">Rafraîchir le QR Code de l'Employé</a>
               <a href="{{ route('dashboardClientDescription', ['id_guide' => 29]) }}" class="text-gray-800 hover:text-red-500">Télécharger le Qr Code de l'employer</a>
               <a href="{{ route('dashboardClientDescription', ['id_guide' => 22]) }}" class="text-gray-800 hover:text-red-500">Ajouter un Employé</a>
           </div>

           <!-- Contenu -->
           <div class="bg-white rounded-lg shadow-md p-6 flex flex-col items-center space-y-2">
               <p class="text-xl font-semibold text-red-800">Contenu</p>

               <a href="{{ route('dashboardClientDescription', ['id_guide' => 23, 'categorie' => 'Ajouter/Supprimer un Logo']) }}" class="text-gray-800 hover:text-red-500">Ajouter/Supprimer un Logo</a>
               <a href="{{ route('dashboardClientDescription', ['id_guide' => 24, 'categorie' => 'Ajouter/Supprimer un PDF']) }}" class="text-gray-800 hover:text-red-500">Ajouter/Supprimer un PDF</a>
               <a href="{{ route('dashboardClientDescription', ['id_guide' => 25, 'categorie' => 'Ajouter/Supprimer des Vidéos YouTube']) }}" class="text-gray-800 hover:text-red-500">Ajouter/Supprimer des Vidéos YouTube</a>
               <a href="{{ route('dashboardClientDescription', ['id_guide' => 26, 'categorie' => 'Ajouter/Supprimer un Lien d\'Avis Google']) }}" class="text-gray-800 hover:text-red-500">Ajouter/Supprimer un Lien d'Avis Google</a>
               <a href="{{ route('dashboardClientDescription', ['id_guide' => 27, 'categorie' => 'Ajouter/Supprimer une Galerie Photo']) }}" class="text-gray-800 hover:text-red-500">Ajouter/Supprimer une Galerie Photo</a>
               <a href="{{ route('dashboardClientDescription', ['id_guide' => 28, 'categorie' => 'Ajouter/Supprimer une URL de Prise de Rendez-vous']) }}" class="text-gray-800 hover:text-red-500">Ajouter/Supprimer une URL de Prise de Rendez-vous</a>

           </div>
        </div>
    </div>
</body>
</html>
