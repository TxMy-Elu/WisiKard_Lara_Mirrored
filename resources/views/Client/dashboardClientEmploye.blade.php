<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Wisikard - Employés</title>
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
<body class="align-items-center bg-gray-100">
<div class="flex flex-col md:flex-row">
    @include('menu.menuClient') <!-- Inclure le menu client -->
    <div class="relative flex-1 md:ml-24 content mt-4">
        @if($compte->role == 'starter')
            <!-- Message abonnement, centré au-dessus du blur -->
            <div class="relative z-50 flex flex-col items-center justify-center">
                <a href="https://wisikard.fr/produit/mise-a-niveau-wisikard-advanced/"
                   target="_blank"
                   class="bg-red-500 border-solid border border-red-500 hover:bg-red-900 hover:border-red-900 rounded-xl w-48 h-7 flex items-center justify-center space-x-4">
                    <p class="text-white text-base">Mettre à niveau</p>
                    <!-- Icône de curseur -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                         viewBox="0 0 24 24"
                         fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round"
                         stroke-linejoin="round" class="feather feather-mouse-pointer">
                        <path d="M3 3l7.07 16.97 2.51-7.39 7.39-2.51L3 3z"></path>
                        <path d="M13 13l6 6"></path>
                    </svg>
                </a>
            </div>
        @endif

        <div class="@if($compte->role == 'starter') blur-[3px] pointer-events-none opacity-50 @endif">
            @if(session('success'))
                <!-- Afficher un message de succès si disponible -->
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                     role="alert">
                    <strong class="font-bold">Succès!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <!-- Afficher un message d'erreur si disponible -->
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Erreur!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            @if(isset($error))
                <!-- Afficher un message d'erreur si disponible -->
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Erreur!</strong>
                    <span class="block sm:inline">{{ $error }}</span>
                </div>
            @endif

                <div class="p-4">
                    <div class="flex flex-col md:flex-row justify-between items-center pb-4">
                        <!-- Formulaire de recherche -->
                        <form method="GET" action="{{ route('dashboardClientEmploye') }}"
                              class="flex items-center relative w-full md:w-64 mb-4 md:mb-0">
                            <div class="relative w-full">
                                <svg class="w-6 h-6 text-gray-900 absolute left-3 top-1/2 transform -translate-y-1/2"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Rechercher..."
                                       class="p-2 pl-10 border border-gray-900 rounded-lg text-sm w-full">
                            </div>
                        </form>

                        <!-- Bouton d'ajout d'employé -->
                        <div class="flex items-center w-full md:w-auto mt-4 md:mt-0">
                            <a href="{{ route('afficherFormInsEmploye') }}"
                               class="w-full md:w-auto px-4 py-2 border border-gray-900 rounded-lg text-sm flex items-center justify-center hover:bg-gray-900 hover:text-white">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 4v16m8-8H4"></path>
                                </svg>
                                Ajouter un employé
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Version Desktop -->
            <div class="grid grid-cols-4 gap-4 p-4 desktop-only">
                @foreach($employes as $employe)
                    <div class="bg-white rounded-lg shadow-lg p-4 flex flex-col">
                        <div class="flex justify-between">
                            <div class="flex flex-col">
                                <div class="mb-4">
                                    <p class="text-xl font-semibold text-gray-800">{{ $employe->nom }}</p>
                                </div>
                                <div class="mb-4">
                                    <p class="text-lg text-gray-600">{{ $employe->prenom }}</p>
                                </div>
                                <div class="mb-4">
                                    <p class="text-lg text-gray-500">{{ $employe->fonction }}</p>
                                </div>
                                <div class="mb-4">
                                    <p class="text-sm text-gray-500">{{ $employe->telephone }}</p>
                                </div>
                                <div class="mb-4">
                                    <p class="text-sm text-gray-500">{{ $employe->mail }}</p>
                                </div>
                            </div>
                            <div class="flex flex-col items-center mb-4">
                                <!-- QR Code -->
                                <div class="w-24 h-24 bg-gray-200 flex justify-center items-center rounded-lg overflow-hidden">
                                    <img src="{{ asset("entreprises/{$employe->carte->idCompte}/QR_Codes/QR_Code_{$employe->idEmp}.svg") }}"
                                         alt="QR Code" class="max-w-full max-h-full">
                                </div>

                                <!-- Icônes sous le QR Code -->
                                <div class="flex mt-2 space-x-4 justify-end">
                                    <!-- Icône Rafraîchir -->
                                    <a href="{{ route('refreshQrCodeEmp', ['id' => $employe->carte->idCompte, 'empId' => $employe->idEmp]) }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                             viewBox="0 0 24 24" fill="none" stroke="#000"
                                             stroke-width="2"
                                             stroke-linecap="round" stroke-linejoin="round"
                                             class="feather feather-repeat">
                                            <polyline points="17 1 21 5 17 9"></polyline>
                                            <path d="M3 11V9a4 4 0 0 1 4-4h14"></path>
                                            <polyline points="7 23 3 19 7 15"></polyline>
                                            <path d="M21 13v2a4 4 0 0 1-4 4H3"></path>
                                        </svg>
                                    </a>

                                    <!-- Icône Télécharger -->
                                    <a href="{{ route('downloadQrCodeEmp', ['id' => $employe->carte->idCompte, 'empId' => $employe->idEmp]) }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                             viewBox="0 0 24 24" fill="none" stroke="#000"
                                             stroke-width="2"
                                             stroke-linecap="round" stroke-linejoin="round"
                                             class="feather feather-download">
                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                            <polyline points="7 10 12 15 17 10"></polyline>
                                            <line x1="12" y1="15" x2="12" y2="3"></line>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-row-reverse mt-auto pt-4">
                            <form action="{{ route('employe.destroy', $employe->idEmp) }}" method="POST"
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet employé ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-full">Supprimer
                                </button>
                            </form>
                            <a href="{{ route('employe.edit', $employe->idEmp) }}"
                               class="bg-indigo-500 text-white px-4 py-2 rounded-full mr-2">Modifier</a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Version Mobile -->
            <div class="grid grid-cols-1 gap-4 p-4 mobile-only mb-4">
                @foreach($employes as $employe)
                    <div class="bg-white rounded-lg shadow-lg p-4 flex flex-col mb-4">
                        <div class="flex justify-between items-center mb-4">
                            <div class="flex flex-col">
                                <p class="text-xl font-semibold text-gray-800">{{ $employe->nom }}</p>
                                <p class="text-lg text-gray-600">{{ $employe->prenom }}</p>
                                <p class="text-lg text-gray-500">{{ $employe->fonction }}</p>
                                <p class="text-sm text-gray-500">{{ $employe->telephone }}</p>
                                <p class="text-sm text-gray-500">{{ $employe->mail }}</p>
                            </div>
                            <div class="flex flex-col items-center mb-4">
                                <!-- QR Code -->
                                <div class="w-24 h-24 bg-gray-200 flex justify-center items-center rounded-lg overflow-hidden">
                                    <img src="{{ asset("entreprises/{$employe->carte->idCompte}/QR_Codes/QR_Code_{$employe->idEmp}.svg") }}"
                                         alt="QR Code" class="max-w-full max-h-full">
                                </div>

                                <!-- Icônes sous le QR Code -->
                                <div class="mt-2 flex space-x-4 items-center">
                                    <!-- Icône Rafraîchir -->
                                    <a href="{{ route('refreshQrCodeEmp', ['id' => $employe->carte->idCompte, 'empId' => $employe->idEmp]) }}" class="flex">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                             viewBox="0 0 24 24" fill="none" stroke="#000"
                                             stroke-width="2"
                                             stroke-linecap="round" stroke-linejoin="round"
                                             class="feather feather-repeat">
                                            <polyline points="17 1 21 5 17 9"></polyline>
                                            <path d="M3 11V9a4 4 0 0 1 4-4h14"></path>
                                            <polyline points="7 23 3 19 7 15"></polyline>
                                            <path d="M21 13v2a4 4 0 0 1-4 4H3"></path>
                                        </svg>
                                    </a>

                                    <!-- Icône Télécharger -->
                                    <a href="{{ asset("entreprises/{$employe->carte->idCompte}/QR_Codes/QR_Code_{$employe->idEmp}.svg") }}" download class="flex">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                             viewBox="0 0 24 24" fill="none" stroke="#000"
                                             stroke-width="2"
                                             stroke-linecap="round" stroke-linejoin="round"
                                             class="feather feather-download">
                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                            <polyline points="7 10 12 15 17 10"></polyline>
                                            <line x1="12" y1="15" x2="12" y2="3"></line>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-row-reverse mt-auto pt-4">
                            <form action="{{ route('employe.destroy', $employe->idEmp) }}" method="POST"
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet employé ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-full">Supprimer
                                </button>
                            </form>
                            <a href="{{ route('employe.edit', $employe->idEmp) }}"
                               class="bg-indigo-500 text-white px-4 py-2 rounded-full mr-2">Modifier</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
</body>
</html>
