<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Client Employé</title>
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
    @include('menu.menuClient')
    <div class="relative flex-1 md:ml-24 content mt-4">
        @if($compte->role == 'starter')
            <!-- Message abonnement, centré au-dessus du blur -->
            <div class="relative z-50 flex flex-col items-center justify-center">
                <a href="https://wisikard.fr/produit/mise-a-niveau-wisikard-advanced/"
                   target="_blank"
                   class="bg-red-500 border-solid border border-red-500 hover:bg-red-900 hover:border-red-900 rounded-xl w-48 h-7 flex items-center justify-center space-x-4">
                    <p class="text-white text-base">Mettre à niveau</p>
                    <!-- svg cursor mouse -->
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
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                     role="alert">
                    <strong class="font-bold">Succès!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Erreur!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            @if(isset($error))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Erreur!</strong>
                    <span class="block sm:inline">{{ $error }}</span>
                </div>
            @endif

            <!-- Desktop Version -->
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
                            <div class="flex justify-center mb-4">
                                <div class="w-24 h-24 bg-gray-200 flex justify-center items-center rounded-lg overflow-hidden">
                                    <img src="{{ asset("entreprises/{$employe->carte->idCompte}_{$employe->carte->nomEntreprise}/QR_Codes/QR_Code_{$employe->idEmp}.svg") }}"
                                         alt="QR Code" class="max-w-full max-h-full">
                                </div>
                                <div class="flex justify-end">
                                    <a href="{{ route('refreshQrCodeEmp', ['id' => $employe->carte->idCompte, 'empId' => $employe->idEmp]) }}"
                                       class="ml-2">
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

            <!-- Mobile Version -->
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
                            <div class="flex justify-center mb-4">
                                <div class="w-24 h-24 bg-gray-200 flex justify-center items-center rounded-lg overflow-hidden">
                                    <img src="{{ asset("entreprises/{$employe->carte->idCompte}_{$employe->carte->nomEntreprise}/QR_Codes/QR_Code_{$employe->idEmp}.svg") }}"
                                         alt="QR Code" class="max-w-full max-h-full">
                                </div>
                                <div class="flex justify-end">
                                    <a href="{{ route('refreshQrCodeEmp', ['id' => $employe->carte->idCompte, 'empId' => $employe->idEmp]) }}"
                                       class="ml-2">
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
