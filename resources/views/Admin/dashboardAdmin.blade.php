<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Admin</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet"/>
    <style>
        /* Styles pour le conteneur du QR code */
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

        /* Styles pour l'image du QR code */
        .qr-code-container img {
            max-width: 100%; /* Assure que l'image s'adapte au conteneur */
            max-height: 100%;
        }

        /* Styles responsive pour les écrans de taille inférieure à 768px */
        @media (max-width: 768px) {
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
    </style>
</head>
<body class="align-items-center bg-grey-600">

<div class="flex flex-col md:flex-row">
    @include('menu.menuAdmin') <!-- Inclure le menu admin -->
    <div class="flex-1 md:ml-24 content">
        @if($messages->isNotEmpty())
            @foreach($messages as $message)
                <div class="bg-zinc-400/45 border border-zinc-400 text-zinc-700 px-4 py-3 rounded relative mb-2" 
                     role="alert">
                    <strong class="font-bold">Information :</strong>
                    <span class="block sm:inline">{{ $message->message }}</span>
                </div>
            @endforeach
        @endif

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Succès!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        @if ($errors->any())
            <div class="text-red-500 text-sm mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="w-full p-4">
            <div class="flex flex-col md:flex-row justify-between items-center pb-4">
                <!-- Barre de recherche -->
                <form method="GET" action="{{ route('dashboardAdmin') }}"
                      class="flex items-center relative w-full md:w-64 mb-4 md:mb-0">
                    <div class="absolute left-2 top-1/2 transform -translate-y-1/2">
                        <svg class="w-6 h-6 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                             xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search..."
                           class="p-2 pl-10 border border-gray-900 rounded-lg text-sm flex-grow">
                </form>
                <!-- Lien vers le formulaire d'inscription -->
                <div class="flex items-center w-full md:w-auto">
                    <a href="{{ route('inscription') }}"
                       class="w-full md:w-auto px-4 py-2 border border-gray-900 rounded-lg text-sm flex items-center justify-center hover:bg-gray-900 hover:text-white">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                             xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 4v16m8-8H4"></path>
                        </svg>
                        Ajouter une entreprise
                    </a>
                </div>
            </div>

            <!-- Grille des entreprises -->
            <div class="grid grid-cols-4 gap-4 mr-4">
                @foreach($entreprises as $entreprise)
                    <div class="bg-white rounded-lg shadow-lg p-4 flex flex-col">
                        <!-- Titre de l'entreprise -->
                        <div class="flex justify-between">
                            <!-- Nom et email de l'entreprise -->
                            <div class="flex flex-col">
                                <div class="mb-4">
                                    <p class="text-xl font-semibold">{{ $entreprise->nomEntreprise }}</p>
                                </div>
                                <div class="mb-4">
                                    <p class="text-lg text-gray-600">{{ $entreprise->compte_email }}</p>
                                </div>
                                <!-- Numéro de téléphone -->
                                <div>
                                    <p class="text-sm text-gray-600">{{ $entreprise->formattedTel }}</p>
                                </div>
                            </div>

                            <div class="flex flex-col">
                                <!-- QR Code -->
                                <div class="justify-center mb-2">
                                    <div class="qr-code-container">
                                        <img src="{{ $entreprise->lienQr }}" alt="QR Code"
                                             class="max-w-full max-h-full">
                                    </div>
                                </div>
                                <!-- Lien pour rafraîchir le QR Code -->
                                <div class="flex justify-end ">
                                    <a href="{{ route('refreshQrCode', $entreprise->idCompte) }}" class="ml-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                             viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2"
                                             stroke-linecap="round" stroke-linejoin="round"
                                             class="feather feather-repeat mr-4">
                                            <polyline points="17 1 21 5 17 9"></polyline>
                                            <path d="M3 11V9a4 4 0 0 1 4-4h14"></path>
                                            <polyline points="7 23 3 19 7 15"></polyline>
                                            <path d="M21 13v2a4 4 0 0 1-4 4H3"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Badge de rôle -->
                        @if($entreprise->compte_role == 'starter')
                            <div class="pt-4">
                                <div class="bg-blue-500/65 border-solid border border-blue-500 rounded-full w-28 h-7 flex items-center justify-center">
                                    <p class="text-slate-50 text-base">Starter</p>
                                </div>
                            </div>
                        @elseif($entreprise->compte_role == 'advanced')
                            <div class="pt-4">
                                <div class="bg-violet-500/65 border-solid border border-violet-500 rounded-full w-28 h-7 flex items-center justify-center">
                                    <p class="text-slate-50 text-base">Advanced</p>
                                </div>
                            </div>
                        @endif

                        <div class="pt-4">
                            <a href="{{ url('/Kard/' . $entreprise->nomEntreprise .'?idCompte='. $entreprise->idCompte) }}"
                            target="_blank"
                            class="bg-indigo-500 text-white px-4 py-2 rounded-full mr-2">Voir la carte</a>
                        </div>
                        
                        <!-- Boutons d'action -->
                        <div class="flex flex-row-reverse mt-auto pt-4">
                            <!-- Bouton pour supprimer l'entreprise -->
                            <form action="{{ route('entreprise.destroy', $entreprise->idCarte) }}" method="POST"
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette entreprise ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-full">Supprimer
                                </button>
                            </form>
                            <!-- Lien pour modifier le mot de passe -->
                            <a href="{{ route('modifierMdp', $entreprise->idCompte) }}" class="bg-indigo-500 text-white px-4 py-2 rounded-full mr-2">Modifier MDP</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

</body>
</html>
