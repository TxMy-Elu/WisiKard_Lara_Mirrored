<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Wisikard - Accueil Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Montserrat:wght@400;700&family=Oswald:wght@400;700&family=Ubuntu:wght@400;700&family=Playfair+Display:wght@400;700&family=Work+Sans:wght@400;700&family=Bona+Nova:wght@400;700&family=Exo+2:wght@400;700&family=Pacifico&family=Gruppo&family=Rokkitt:wght@400;700&display=swap"
          rel="stylesheet">
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

            .grid-cols-5 {
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
<body class="bg-slate-100 flex flex-col min-h-screen">
<div class="flex flex-col md:flex-row">
    @include('menu.menuClient')
    <div class="flex-1 md:ml-24">
        @if($messageContent != "Aucun message disponible" || empty($messageContent))
            <div class="bg-zinc-400/45 border border-zinc-400 text-zinc-700 px-4 py-3 rounded relative"
                 role="alert">
                <strong class="font-bold">Information :</strong>
                <span class="block sm:inline">{{ $messageContent }}</span>
            </div>
        @endif
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

        <!-- Desktop Version -->
        <div class="grid grid-cols-5 gap-5 p-4 desktop-only">
            <!-- Carte (div1) -->
            <div class="col-span-2 row-span-1 bg-white rounded-lg shadow-lg p-4 flex flex-col justify-between">
                <div class="flex justify-between">
                    <!-- Title and other information -->
                    <div class="flex flex-col">
                        <div class="mb-4">
                            <p class="text-xl font-semibold text-gray-800">{{ $carte->nomEntreprise }}</p>
                        </div>
                        <div class="mb-4">
                            <p class="text-lg text-gray-600">{{ $carte->compte->email }}</p>
                        </div>
                        <!-- Phone number -->
                        <div class="mb-4">
                            <p class="text-sm text-gray-600">{{ $carte->formattedTel }}</p>
                        </div>
                        <!-- Address -->
                        <div>
                            <p class="text-sm text-gray-600">{{ $carte->ville }}</p>
                        </div>
                        @if($carte->compte->role == 'starter')
                            <div class="pt-4">
                                <div class="bg-blue-500/65 border-solid border border-blue-500 rounded-full w-28 h-7 flex items-center justify-center space-x-2 mb-2">
                                    <p class="text-slate-50 text-base">Starter</p>
                                </div>
                                <!-- svg upagrede -->
                                <a href="https://wisikard.fr/produit/mise-a-niveau-wisikard-advanced/" target="_blank"
                                   class="bg-red-500/65 border-solid border border-red-500 hover:bg-red-900 hover:border-red-900 rounded-full w-48 h-7 flex items-center justify-center space-x-4">
                                    <p class="text-slate-50 text-base">Mettre à niveau</p>
                                    <!-- svg cursor mouse -->
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                         fill="none" stroke="#ffff" stroke-width="2" stroke-linecap="round"
                                         stroke-linejoin="round" class="feather feather-mouse-pointer">
                                        <path d="M3 3l7.07 16.97 2.51-7.39 7.39-2.51L3 3z"></path>
                                        <path d="M13 13l6 6"></path>
                                    </svg>
                                </a>
                            </div>
                        @elseif($carte->compte->role == 'advanced')
                            <div class="pt-4">
                                <div class="bg-violet-500/65 border-solid border border-violet-500 rounded-full w-28 h-7 flex items-center justify-center">
                                    <p class="text-slate-50 text-base">Advanced</p>
                                </div>
                            </div>
                        @endif
                    </div>
                    @php
                        // Détection des différents types de fichiers
                        $logoPath = '';
                        $formats = ['svg', 'png', 'jpg', 'jpeg']; // Ajouter d'autres formats si nécessaire

                        foreach ($formats as $format) {
                            $path = public_path('entreprises/' . $carte->compte->idCompte . '/logos/logo.' . $format);
                            if (file_exists($path)) {
                                $logoPath = asset('entreprises/' . $carte->compte->idCompte . '/logos/logo.' . $format);
                                break;
                            }
                        }
                    @endphp
                            <!-- Logo -->
                    <div class="justify-center mb-2">
                        <div class="w-28">
                            <div class="w-full md:w-1/2 flex flex-col items-center justify-center">
                                @if (!empty($logoPath))
                                    <img class="w-32 h-32 object-contain border border-gray-200 rounded-md shadow-lg"
                                         src="{{ $logoPath }}"
                                         alt="Logo">
                                @else
                                    <p class="text-gray-500 italic border-2 p-10">Aucun logo disponible</p>
                                @endif
                                <p class="text-sm text-gray-500 mt-2">Aperçu du logo</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Buttons -->
                <div class="flex flex-row-reverse mt-auto pt-4">
                    <a href="{{ route('formulaireEntreprise') }}"
                       class="bg-indigo-500 text-white px-4 py-2 rounded-full mr-2">Modifier</a>

                    <a href="{{ url('/Kard/' . $carte->nomEntreprise .'?idCompte='. $carte->compte->idCompte) }}"
                       target="_blank"
                       class="bg-indigo-500 text-white px-4 py-2 rounded-full mr-2">Voir ma carte</a>
                </div>
            </div>
            <!-- Font (div5) -->
            <div class="relative col-span-2 row-span-1 ">
                <!-- La zone floutée -->
                <div class="bg-white rounded-lg shadow-lg p-4 @if($compte->role == 'starter') blur-[3px] pointer-events-none opacity-50 @endif mb-2">
                    <form action="{{ 'updateFont' }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="flex flex-col">
                            <label for="font" class="text-lg font-semibold text-gray-800">Police</label>
                            @php
                                $fonts = [
                                        'roboto',
                                        'montserrat',
                                        'oswald',
                                        'ubuntu',
                                        'playfair',
                                        'work-sans',
                                        'playwrite-india',
                                        'bona-nova',
                                        'exo-2',
                                        'pacifico',
                                        'gruppo',
                                        'rokkitt'
                                    ];
                            @endphp
                            <select name="font" id="font" class="w-full p-2 border border-gray-300 rounded-lg">
                                @foreach ($fonts as $font)
                                    <option value="{{ $font }}" @if($carte->font == $font) selected
                                            @endif style="font-family: '{{ $font }}';">
                                        {{ $font }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit"
                                    class="bg-indigo-500 text-white px-4 py-2 rounded-full w-48 mt-4 items-center">
                                Enregistrer
                            </button>
                        </div>
                    </form>
                </div>


                <!-- Section contenant le titre, descriptif et le bouton -->
                <div class="bg-white rounded-lg shadow-lg p-4 flex flex-col relative">
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex flex-col">
                            @if($carte->titre)
                                <p class="text-xl font-semibold text-gray-800">Titre: {{$carte->titre}}</p>
                            @else
                                <p class="text-xl font-semibold text-gray-800">Titre: Non défini</p>
                            @endif

                            @if($carte->descriptif)
                                <p class="text-lg text-gray-600">Description: {{$carte->descriptif}}</p>
                            @else
                                <p class="text-lg text-gray-600">Description: Non défini</p>
                            @endif
                        </div>
                    </div>
                    <div class="flex justify-end mt-4">
                        <!-- Bouton pour ouvrir le modal -->
                        <button class="bg-indigo-500 text-white p-2 rounded-full text-sm"
                                onclick="document.getElementById('modalForm').classList.remove('hidden')">
                            Ajouter / Modifier Titre et Description
                        </button>

                    </div>
                </div>

                <!-- Modal -->
                <div id="modalForm"
                     class="fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center hidden z-50">
                    <div class="bg-white rounded-lg shadow-xl p-6 w-1/3 relative">
                        <!-- Bouton de fermeture du modal (croix) -->
                        <button class="absolute top-2 right-2 text-gray-600 hover:text-gray-800 focus:outline-none"
                                onclick="document.getElementById('modalForm').classList.add('hidden')">
                            ✕
                        </button>

                        <h2 class="text-xl font-bold mb-4">Modifier le titre et la description</h2>
                        <form action="{{ route('dashboardClientInfo') }}" method="POST">
                            @csrf
                            <!-- Champ pour le titre -->
                            <div class="mb-4">
                                <label for="titre" class="block text-gray-700 font-semibold">Titre</label>
                                <input type="text" id="titre" name="titre"
                                       class="w-full border border-gray-300 rounded px-3 py-2"
                                       placeholder="Entrez le titre"
                                       value="{{$carte->titre}}">
                            </div>
                            <!-- Champ pour la description -->
                            <div class="mb-4">
                                <label for="descriptif" class="block text-gray-700 font-semibold">Description</label>
                                <textarea id="descriptif" name="descriptif"
                                          class="w-full border border-gray-300 rounded px-3 py-2"
                                          placeholder="Entrez la description">{{$carte->descriptif}}</textarea>
                            </div>
                            <!-- Boutons du formulaire -->
                            <div class="flex justify-end">
                                <button type="button"
                                        class="bg-gray-300 text-gray-700 px-3 py-2 rounded mr-2"
                                        onclick="document.getElementById('modalForm').classList.add('hidden')">
                                    Annuler
                                </button>
                                <button type="submit" class="bg-indigo-500 text-white px-4 py-2 rounded">
                                    Enregistrer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- QR Code (div2) -->
            <div class="col-span-1 row-span-2 bg-white rounded-lg shadow-lg p-4 flex flex-col">
                <!-- QR Code Image -->
                <label for="font" class="text-lg font-semibold text-gray-800">Votre WisiCode</label>
                <div class="mb-4 flex flex-col items-center">
                    <img src="{{ $carte->lienQr }}"
                         alt="QR Code" class="w-full max-w-xs rounded-2xl">
                </div>
                <div class="mb-4 @if($compte->role == 'starter') blur-[3px] pointer-events-none opacity-50 @endif">
                    <!-- Form for Color Selection -->
                    <form action="{{ route('dashboardClientColor') }}" method="POST"
                          class="flex flex-col items-center w-full">
                        @csrf
                        <div class="flex flex-wrap justify-center">
                            <div class="flex flex-col w-full md:w-1/2 mb-4">
                                <label for="color1"
                                       class="w-full text-center mb-0.5 font-bold text-gray-800">Pixel</label>
                                <input type="color" name="couleur1" id="color1" class="w-40 mx-auto bg-white"
                                       value="{{ $couleur1 }}">
                            </div>
                            <div class="flex flex-col w-full md:w-1/2 mb-4">
                                <label for="color2"
                                       class="w-full text-center mb-0.5 font-bold text-gray-800">Fond</label>
                                <input type="color" name="couleur2" id="color2" class="w-40 mx-auto bg-white"
                                       value="{{ $couleur2 }}">
                            </div>
                        </div>
                        <button type="submit" class="bg-indigo-500 text-white px-4 py-2 rounded-full w-full">
                            Enregistrer
                        </button>
                    </form>
                </div>
                <div class="flex justify-center items-center text-center bg-white mx-auto my-2 w-full p-2 mt-4  border-t-2 border-gray-200 ">
                    <p class="font-bold text-xl text-gray-800">Télécharger QR Codes</p>
                </div>
                <!-- Download Buttons -->
                <div class=" border-b-2 border-b-gray-200">
                    <div class="flex justify-center space-x-4 mt-4 mb-4">
                        <a href="{{ route('downloadQrCodesColor') }}"
                           class="flex items-center justify-center px-4 py-2 bg-indigo-500 text-white rounded-lg text-sm hover:bg-indigo-600 @if($compte->role == 'starter') blur-[3px] pointer-events-none opacity-50 @endif">
                            Couleur
                            <!-- Espace entre le texte et le SVG -->
                            <span class="ml-2"></span>
                            <!-- Download svg -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                 class="feather feather-download">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="7 10 12 15 17 10"></polyline>
                                <line x1="12" y1="15" x2="12" y2="3"></line>
                            </svg>
                        </a>
                        <a href="{{ route('downloadQrCodes') }}"
                           class="flex items-center justify-center px-4 py-2 border border-gray-900 text-gray-900 rounded-lg text-sm hover:bg-gray-100 ">
                            Noir / Blanc
                            <!-- Espace entre le texte et le SVG -->
                            <span class="ml-2"></span>
                            <!-- Download svg -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                 class="feather feather-download">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="7 10 12 15 17 10"></polyline>
                                <line x1="12" y1="15" x2="12" y2="3"></line>
                            </svg>
                        </a>
                    </div>
                </div>
                @if($carte->lienPdf)
                <div class="@if($compte->role == 'starter') blur-[3px] pointer-events-none opacity-50 @endif">
                    <div class="flex justify-center items-center text-center bg-white mx-auto my-2 w-full p-2 mt-4">
                        <p class="font-bold text-xl text-gray-800">Télécharger QR PDF</p>
                    </div>
                    <!-- Download Buttons -->
                    <div class="flex justify-center space-x-4 mt-4 mb-4">
                        <a href="{{ route('download.qrcode.pdf.color') }}"
                           class="flex items-center justify-center px-4 py-2 bg-indigo-500 text-white rounded-lg text-sm hover:bg-indigo-600">
                            Couleur
                            <!-- Espace entre le texte et le SVG -->
                            <span class="ml-2"></span>
                            <!-- Download svg -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                 class="feather feather-download">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="7 10 12 15 17 10"></polyline>
                                <line x1="12" y1="15" x2="12" y2="3"></line>
                            </svg>
                        </a>
                        <a href="{{ route('download.qrcode.pdf') }}"
                           class="flex items-center justify-center px-4 py-2 border border-gray-900 text-gray-900 rounded-lg text-sm hover:bg-gray-100">
                            Noir / Blanc
                            <!-- Espace entre le texte et le SVG -->
                            <span class="ml-2"></span>
                            <!-- Download svg -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                 class="feather feather-download">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="7 10 12 15 17 10"></polyline>
                                <line x1="12" y1="15" x2="12" y2="3"></line>
                            </svg>
                        </a>
                    </div>
                </div>
                @endif
            </div>
            <!-- Horaires d'ouverture (div6) -->
            <div class="relative col-span-4 row-span-1 ">
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
                <div class="col-span-4 row-span-1 bg-white rounded-lg shadow-lg p-4 @if($compte->role == 'starter') blur-[3px] pointer-events-none opacity-50 @endif">
                    <form action="{{ route('updateHoraires') }}" method="POST" class="p-6">
                        @csrf
                        <div class="flex flex-col space-y-6">
                            <h2 class="text-lg font-semibold text-gray-800">Horaires d'ouverture</h2>

                            <!-- Grille pour les autres jours -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Première colonne : Lundi à Mercredi -->
                                <div class="flex flex-col space-y-6">
                                    @foreach(['lundi', 'mardi', 'mercredi' ,'jeudi'] as $jour)
                                        <div class="flex flex-col md:flex-row items-center justify-between pb-4 mb-4 border-b-2 border-gray-200">
                                            <label for="{{ $jour }}_ouverture_matin"
                                                   class="w-full md:w-1/4 text-gray-700 font-medium text-sm capitalize">
                                                {{ ucfirst($jour) }}
                                            </label>
                                            <div class="flex flex-col md:flex-row items-center space-y-2 md:space-y-0 md:space-x-4 w-full md:w-3/4">
                                                <div class="flex items-center space-x-2">
                                                    <input
                                                            type="time"
                                                            name="{{ $jour }}_ouverture_matin"
                                                            id="{{ $jour }}_ouverture_matin"
                                                            class="w-full md:w-auto p-2 border border-gray-300 rounded-lg focus:outline-red-500 text-gray-700 "
                                                            value="{{ $horaires->where('jour', $jour)->first()->ouverture_matin ?? '' }}"
                                                    />
                                                    <p class="text-sm text-gray-500">à</p>
                                                    <input
                                                            type="time"
                                                            name="{{ $jour }}_fermeture_matin"
                                                            id="{{ $jour }}_fermeture_matin"
                                                            class="w-full md:w-auto p-2 border border-gray-300 rounded-lg focus:outline-red-500 text-gray-700"
                                                            value="{{ $horaires->where('jour', $jour)->first()->fermeture_matin ?? '' }}"
                                                    />
                                                </div>
                                                <p class="font-semibold text-gray-600">/</p>
                                                <div class="flex items-center space-x-2">
                                                    <input
                                                            type="time"
                                                            name="{{ $jour }}_ouverture_aprmidi"
                                                            id="{{ $jour }}_ouverture_aprmidi"
                                                            class="w-full md:w-auto p-2 border border-gray-300 rounded-lg focus:outline-red-500 text-gray-700"
                                                            value="{{ $horaires->where('jour', $jour)->first()->ouverture_aprmidi ?? '' }}"
                                                    />
                                                    <p class="text-sm text-gray-500">à</p>
                                                    <input
                                                            type="time"
                                                            name="{{ $jour }}_fermeture_aprmidi"
                                                            id="{{ $jour }}_fermeture_aprmidi"
                                                            class="w-full md:w-auto p-2 border border-gray-300 rounded-lg focus:outline-red-500 text-gray-700"
                                                            value="{{ $horaires->where('jour', $jour)->first()->fermeture_aprmidi ?? '' }}"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Deuxième colonne : Jeudi à Samedi -->
                                <div class="flex flex-col space-y-6">
                                    @foreach(['vendredi', 'samedi','dimanche'] as $jour)
                                        <div class="flex flex-col md:flex-row items-center justify-between pb-4 mb-4 border-b-2 border-gray-200">
                                            <label for="{{ $jour }}_ouverture_matin"
                                                   class="w-full md:w-1/4 text-gray-700 font-medium text-sm capitalize">
                                                {{ ucfirst($jour) }}
                                            </label>
                                            <div class="flex flex-col md:flex-row items-center space-y-2 md:space-y-0 md:space-x-4 w-full md:w-3/4 ">
                                                <div class="flex items-center space-x-2">
                                                    <input
                                                            type="time"
                                                            name="{{ $jour }}_ouverture_matin"
                                                            id="{{ $jour }}_ouverture_matin"
                                                            class="w-full md:w-auto p-2 border border-gray-300 rounded-lg focus:outline-red-500 text-gray-700"
                                                            value="{{ $horaires->where('jour', $jour)->first()->ouverture_matin ?? '' }}"
                                                    />
                                                    <p class="text-sm text-gray-500">à</p>
                                                    <input
                                                            type="time"
                                                            name="{{ $jour }}_fermeture_matin"
                                                            id="{{ $jour }}_fermeture_matin"
                                                            class="w-full md:w-auto p-2 border border-gray-300 rounded-lg focus:outline-red-500 text-gray-700"
                                                            value="{{ $horaires->where('jour', $jour)->first()->fermeture_matin ?? '' }}"
                                                    />
                                                </div>
                                                <p class="font-semibold text-gray-600">/</p>
                                                <div class="flex items-center space-x-2">
                                                    <input
                                                            type="time"
                                                            name="{{ $jour }}_ouverture_aprmidi"
                                                            id="{{ $jour }}_ouverture_aprmidi"
                                                            class="w-full md:w-auto p-2 border border-gray-300 rounded-lg focus:outline-red-500 text-gray-700"
                                                            value="{{ $horaires->where('jour', $jour)->first()->ouverture_aprmidi ?? '' }}"
                                                    />
                                                    <p class="text-sm text-gray-500">à</p>
                                                    <input
                                                            type="time"
                                                            name="{{ $jour }}_fermeture_aprmidi"
                                                            id="{{ $jour }}_fermeture_aprmidi"
                                                            class="w-full md:w-auto p-2 border border-gray-300 rounded-lg focus:outline-red-500 text-gray-700"
                                                            value="{{ $horaires->where('jour', $jour)->first()->fermeture_aprmidi ?? '' }}"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <button
                                        type="submit"
                                        class="mt-6 w-48 bg-indigo-500 hover:bg-indigo-600 text-white font-semibold py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-1">
                                    Enregistrer
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Template Selection (div4) -->
            <div class="col-span-5  row-span-2 bg-white rounded-lg shadow-lg p-4">
                <form id="templateForm" action="{{ route('updateTemplate') }}" method="POST">
                    @csrf
                    <div class="flex flex-col">
                        <h1 id="template" class="text-lg font-semibold text-gray-800">Template</h1>
                        <!-- radio button x4 (div4) -->
                        <div class="flex justify-center items-center space-x-4 mt-4">
                            <div class="flex flex-col items-center">
                                <input type="radio" name="idTemplate" id="template1" value="1"
                                       @if($idTemplate == 1) checked @endif class="mb-2"
                                       onchange="submitTemplateForm()">
                                <label for="template1"></label>
                                <!-- template gradient  -->
                                <iframe src="https://app.wisikard.fr/iframe?idTemplate=1"
                                        onerror="this.src='https://app.wisikard.fr/iframe?idTemplate=1'"
                                        class="w-96 h-[750px] rounded-lg"></iframe>
                            </div>
                            <div class="relative items-center justify-center ">
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
                                <!-- La zone floutée -->
                                <div class="@if($compte->role == 'starter') blur-[3px] pointer-events-none opacity-50 @endif flex space-x-4">
                                    <!-- Contenu des templates -->
                                    <div class="flex flex-col items-center">
                                        <input type="radio" name="idTemplate" id="template2" value="2"
                                               @if($idTemplate == 2) checked @endif class="mb-2"
                                               onchange="submitTemplateForm()">
                                        <label for="template2"></label>
                                        <!-- template gradient -->
                                        <iframe src="https://app.wisikard.fr/iframe?idTemplate=2"
                                                onerror="this.src='https://app.wisikard.fr/iframe?idTemplate=2'"
                                                class="w-96 h-[750px] rounded-lg border border-gray-200"></iframe>
                                    </div>
                                    <div class="flex flex-col items-center">
                                        <input type="radio" name="idTemplate" id="template3" value="3"
                                               @if($idTemplate == 3) checked @endif class="mb-2"
                                               onchange="submitTemplateForm()">
                                        <label for="template3"></label>
                                        <iframe src="https://app.wisikard.fr/iframe?idTemplate=3"
                                                onerror="this.src='https://app.wisikard.fr/iframe?idTemplate=3'"
                                                class="w-96 h-[750px] rounded-lg"></iframe>
                                    </div>
                                    <div class="flex flex-col items-center">
                                        <input type="radio" name="idTemplate" id="template4" value="4"
                                               @if($idTemplate == 4) checked @endif class="mb-2"
                                               onchange="submitTemplateForm()">
                                        <label for="template4"></label>
                                        <!-- template gradient -->
                                        <iframe src="https://app.wisikard.fr/iframe?idTemplate=4"
                                                onerror="this.src='https://app.wisikard.fr/iframe?idTemplate=4'"
                                                class="w-96 h-[750px] rounded-lg"></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <script>
                function submitTemplateForm() {
                    document.getElementById('templateForm').submit();
                }
            </script>
        </div>

        <!-- Mobile Version -->
        <div class="grid grid-cols-1 gap-5 p-4 mobile-only">
            <!-- Carte (div1) -->
            <div class="bg-white rounded-lg shadow-lg p-4 flex flex-col mb-4">
                <div class="flex justify-between items-center mb-4">
                    <div class="flex flex-col">
                        <p class="text-xl font-semibold text-gray-800">{{ $carte->nomEntreprise }}</p>
                        <p class="text-lg text-gray-600">{{ $carte->compte->email }}</p>
                        <p class="text-sm text-gray-600">{{ $carte->formattedTel }}</p>
                        <p class="text-sm text-gray-600">{{ $carte->ville }}</p>
                    </div>
                    @php
                        // Détection des différents types de fichiers
                        $logoPath = '';
                        $formats = ['svg', 'png', 'jpg', 'jpeg']; // Ajouter d'autres formats si nécessaire
                        foreach ($formats as $format) {
                            $path = public_path('entreprises/' . $carte->compte->idCompte . '/logos/logo.' . $format);
                            if (file_exists($path)) {
                                $logoPath = asset('entreprises/' . $carte->compte->idCompte . '/logos/logo.' . $format);
                                break;
                            }
                        }
                    @endphp
                    <div class="flex justify-center mb-2">
                        <img src="{{ $logoPath ? $logoPath : asset('images/default-logo.png') }}" alt="Logo"
                             class="w-28">
                    </div>
                </div>
                @if($carte->compte->role == 'starter')
                    <div class="bg-blue-500/65 border-solid border border-blue-500 rounded-full w-28 h-7 flex items-center justify-center space-x-2 mb-2">
                        <p class="text-slate-50 text-base">Starter</p>
                    </div>
                    <a href="https://wisikard.fr/produit/mise-a-niveau-wisikard-advanced/" target="_blank"
                       class="bg-red-500/65 border-solid border border-red-500 hover:bg-red-900 hover:border-red-900 rounded-full w-48 h-7 flex items-center justify-center space-x-4 mb-4">
                        <p class="text-slate-50 text-base">Mettre à niveau</p>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                             fill="none" stroke="#ffff" stroke-width="2" stroke-linecap="round"
                             stroke-linejoin="round" class="feather feather-mouse-pointer">
                            <path d="M3 3l7.07 16.97 2.51-7.39 7.39-2.51L3 3z"></path>
                            <path d="M13 13l6 6"></path>
                        </svg>
                    </a>
                @elseif($carte->compte->role == 'advanced')
                    <div class="bg-violet-500/65 border-solid border border-violet-500 rounded-full w-28 h-7 flex items-center justify-center mb-4">
                        <p class="text-slate-50 text-base">Advanced</p>
                    </div>
                @endif

                <a href="{{ route('formulaireEntreprise') }}"
                   class="bg-indigo-500 text-white px-4 py-2 rounded-full w-full text-center">Modifier</a>
                <a href="{{ url('/Kard/' . $carte->nomEntreprise .'?idCompte='. $carte->compte->idCompte) }}"
                   target="_blank"
                   class="bg-indigo-500 text-white px-4 py-2 rounded-full w-full text-center mt-2">Voir ma carte</a>
            </div>

            <!-- Font (div5) -->
            <div class="bg-white rounded-lg shadow-lg p-4 @if($compte->role == 'starter') blur-[3px] pointer-events-none opacity-50 @endif mb-4">
                <form action="{{ 'updateFont' }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="flex flex-col">
                        <label for="font" class="text-lg font-semibold text-gray-800">Police</label>
                        @php
                            $fonts = [
                                    'roboto',
                                    'montserrat',
                                    'oswald',
                                    'ubuntu',
                                    'playfair',
                                    'work-sans',
                                    'playwrite-india',
                                    'bona-nova',
                                    'exo-2',
                                    'pacifico',
                                    'gruppo',
                                    'rokkitt'
                                ];
                        @endphp
                        <select name="font" id="font" class="w-full p-2 border border-gray-300 rounded-lg">
                            @foreach ($fonts as $font)
                                <option value="{{ $font }}" @if($carte->font == $font) selected
                                        @endif style="font-family: '{{ $font }}';">
                                    {{ $font }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex justify-end mt-4">
                        <button type="submit"
                                class="bg-indigo-500 text-white px-4 py-2 rounded-full w-full">
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>

            <!-- Section mobile contenant le titre, descriptif et le bouton -->
            <div class="bg-white rounded-lg shadow-lg p-4 flex flex-col mb-4 relative">
                <div class="flex justify-between items-center mb-4">
                    <div class="flex flex-col">
                        <!-- Affichage conditionnel pour le Titre -->
                        @if($carte->titre)
                            <p class="text-xl font-semibold text-gray-800">Titre: {{$carte->titre}}</p>
                        @else
                            <p class="text-xl font-semibold text-gray-800">Titre: Non défini</p>
                        @endif

                        <!-- Affichage conditionnel pour la Description -->
                        @if($carte->descriptif)
                            <p class="text-lg text-gray-600">Description: {{$carte->descriptif}}</p>
                        @else
                            <p class="text-lg text-gray-600">Description: Non défini</p>
                        @endif
                    </div>
                </div>
                <div class="flex justify-end mt-4">
                    <!-- Bouton pour ouvrir le modal -->
                    <button class="bg-indigo-500 text-white p-2 rounded-full text-sm w-full"
                            onclick="document.getElementById('modalFormMobile').classList.remove('hidden')">
                        Ajouter / Modifier Titre et Description
                    </button>
                </div>
            </div>

            <!-- Modal mobile -->
            <div id="modalFormMobile"
                 class="fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center hidden z-50">
                <div class="bg-white rounded-lg shadow-xl p-6 w-11/12 max-w-md relative">
                    <!-- Bouton de fermeture du modal (croix) -->
                    <button class="absolute top-2 right-2 text-gray-600 hover:text-gray-800 focus:outline-none"
                            onclick="document.getElementById('modalFormMobile').classList.add('hidden')">
                        ✕
                    </button>

                    <h2 class="text-xl font-bold mb-4">Modifier le titre et la description</h2>
                    <form action="{{ route('dashboardClientInfo') }}" method="POST">
                        @csrf
                        <!-- Champ pour le titre -->
                        <div class="mb-4">
                            <label for="titreMobile" class="block text-gray-700 font-semibold">Titre</label>
                            <input type="text" id="titreMobile" name="titre"
                                   class="w-full border border-gray-300 rounded px-3 py-2"
                                   placeholder="Entrez le titre"
                                   value="{{$carte->titre}}">
                        </div>
                        <!-- Champ pour la description -->
                        <div class="mb-4">
                            <label for="descriptifMobile" class="block text-gray-700 font-semibold">Description</label>
                            <textarea id="descriptifMobile" name="descriptif"
                                      class="w-full border border-gray-300 rounded px-3 py-2"
                                      placeholder="Entrez la description">{{$carte->descriptif}}</textarea>
                        </div>
                        <!-- Boutons du formulaire -->
                        <div class="flex justify-end">
                            <button type="button"
                                    class="bg-gray-300 text-gray-700 px-3 py-2 rounded mr-2"
                                    onclick="document.getElementById('modalFormMobile').classList.add('hidden')">
                                Annuler
                            </button>
                            <button type="submit" class="bg-indigo-500 text-white px-4 py-2 rounded">
                                Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- QR Code (div2) -->
            <div class="bg-white rounded-lg shadow-lg p-4 flex flex-col mb-4">
                <label for="font" class="text-lg font-semibold text-gray-800">Votre WisiCode</label>
                <div class="mb-4 flex justify-center">
                    <img src="{{ $carte->lienQr }}"
                         alt="QR Code" class="w-full max-w-xs rounded-2xl">
                </div>
                <div class="mb-4 @if($compte->role == 'starter') blur-[3px] pointer-events-none opacity-50 @endif">
                    <form action="{{ route('dashboardClientColor') }}" method="POST"
                          class="flex flex-col items-center w-full">
                        @csrf
                        <div class="flex flex-wrap justify-center">
                            <div class="flex flex-col w-full mb-4">
                                <label for="color1"
                                       class="w-full text-center mb-0.5 font-bold text-gray-800">Pixel</label>
                                <input type="color" name="couleur1" id="color1" class="w-40 mx-auto bg-white"
                                       value="{{ $couleur1 }}">
                            </div>
                            <div class="flex flex-col w-full mb-4">
                                <label for="color2"
                                       class="w-full text-center mb-0.5 font-bold text-gray-800">Fond</label>
                                <input type="color" name="couleur2" id="color2" class="w-40 mx-auto bg-white"
                                       value="{{ $couleur2 }}">
                            </div>
                        </div>
                        <button type="submit" class="bg-indigo-500 text-white px-4 py-2 rounded-full w-full">
                            Enregistrer
                        </button>
                    </form>
                </div>
                <div class="flex justify-center items-center text-center bg-white mx-auto my-2 w-full p-2 mt-4 border-t-2 border-gray-200">
                    <p class="font-bold text-xl text-gray-800">Télécharger QR Codes</p>
                </div>
                <div class="flex justify-center space-x-4 mt-4 mb-4">
                    <a href="{{ route('downloadQrCodesColor') }}"
                       class="flex items-center justify-center px-4 py-2 bg-indigo-500 text-white rounded-lg text-sm hover:bg-indigo-600 @if($compte->role == 'starter') blur-[3px] pointer-events-none opacity-50 @endif">
                        Couleur
                        <span class="ml-2"></span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-download">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="7 10 12 15 17 10"></polyline>
                            <line x1="12" y1="15" x2="12" y2="3"></line>
                        </svg>
                    </a>
                    <a href="{{ route('downloadQrCodes') }}"
                       class="flex items-center justify-center px-4 py-2 border border-gray-900 text-gray-900 rounded-lg text-sm hover:bg-gray-100">
                        Noir / Blanc
                        <span class="ml-2"></span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-download">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="7 10 12 15 17 10"></polyline>
                            <line x1="12" y1="15" x2="12" y2="3"></line>
                        </svg>
                    </a>
                </div>
                <div class="@if($compte->role == 'starter') blur-[3px] pointer-events-none opacity-50 @endif">
                    <div class="flex justify-center items-center text-center bg-white mx-auto my-2 w-full p-2 mt-4">
                        <p class="font-bold text-xl text-gray-800">Télécharger QR PDF</p>
                    </div>
                    <div class="flex justify-center space-x-4 mt-4 mb-4">
                        <a href="{{ route('download.qrcode.pdf.color') }}"
                           class="flex items-center justify-center px-4 py-2 bg-indigo-500 text-white rounded-lg text-sm hover:bg-indigo-600">
                            Couleur
                            <span class="ml-2"></span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                 class="feather feather-download">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="7 10 12 15 17 10"></polyline>
                                <line x1="12" y1="15" x2="12" y2="3"></line>
                            </svg>
                        </a>
                        <a href="{{ route('download.qrcode.pdf') }}"
                           class="flex items-center justify-center px-4 py-2 border border-gray-900 text-gray-900 rounded-lg text-sm hover:bg-gray-100">
                            Noir / Blanc
                            <span class="ml-2"></span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                 class="feather feather-download">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="7 10 12 15 17 10"></polyline>
                                <line x1="12" y1="15" x2="12" y2="3"></line>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Horaires d'ouverture (div6) -->
            <div class="bg-white rounded-lg shadow-lg p-4 @if($compte->role == 'starter') blur-[3px] pointer-events-none opacity-50 @endif mb-4">
                <form action="{{ route('updateHoraires') }}" method="POST" class="p-6">
                    @csrf
                    <div class="flex flex-col space-y-6">
                        <h2 class="text-lg font-semibold text-gray-800">Horaires d'ouverture</h2>
                        <div class="flex flex-col space-y-6">
                            @foreach(['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'] as $jour)
                                <div class="flex flex-col mb-4">
                                    <label for="{{ $jour }}_ouverture_matin"
                                           class="text-gray-700 font-medium text-sm capitalize">
                                        {{ ucfirst($jour) }}
                                    </label>
                                    <div class="flex flex-col space-y-2">
                                        <div class="flex items-center space-x-2">
                                            <input
                                                    type="time"
                                                    name="{{ $jour }}_ouverture_matin"
                                                    id="{{ $jour }}_ouverture_matin"
                                                    class="w-full p-2 border border-gray-300 rounded-lg focus:outline-red-500 text-gray-700"
                                                    value="{{ $horaires->where('jour', $jour)->first()->ouverture_matin ?? '' }}"
                                            />
                                            <p class="text-sm text-gray-500">à</p>
                                            <input
                                                    type="time"
                                                    name="{{ $jour }}_fermeture_matin"
                                                    id="{{ $jour }}_fermeture_matin"
                                                    class="w-full p-2 border border-gray-300 rounded-lg focus:outline-red-500 text-gray-700"
                                                    value="{{ $horaires->where('jour', $jour)->first()->fermeture_matin ?? '' }}"
                                            />
                                        </div>
                                        <p class="font-semibold text-gray-600">/</p>
                                        <div class="flex items-center space-x-2">
                                            <input
                                                    type="time"
                                                    name="{{ $jour }}_ouverture_aprmidi"
                                                    id="{{ $jour }}_ouverture_aprmidi"
                                                    class="w-full p-2 border border-gray-300 rounded-lg focus:outline-red-500 text-gray-700"
                                                    value="{{ $horaires->where('jour', $jour)->first()->ouverture_aprmidi ?? '' }}"
                                            />
                                            <p class="text-sm text-gray-500">à</p>
                                            <input
                                                    type="time"
                                                    name="{{ $jour }}_fermeture_aprmidi"
                                                    id="{{ $jour }}_fermeture_aprmidi"
                                                    class="w-full p-2 border border-gray-300 rounded-lg focus:outline-red-500 text-gray-700"
                                                    value="{{ $horaires->where('jour', $jour)->first()->fermeture_aprmidi ?? '' }}"
                                            />
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="flex justify-end">
                            <button
                                    type="submit"
                                    class="mt-6 w-full bg-indigo-500 hover:bg-indigo-600 text-white font-semibold py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-1">
                                Enregistrer
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Template Selection (div4) -->
            <div class="bg-white rounded-lg shadow-lg p-4">
                <form id="templateForm" action="{{ route('updateTemplate') }}" method="POST">
                    @csrf
                    <div class="flex flex-col">
                        <h1 id="template" class="text-lg font-semibold text-gray-800">Template</h1>
                        <div class="flex flex-col space-y-4 mt-4">
                            <div class="flex flex-col items-center">
                                <input type="radio" name="idTemplate" id="template1" value="1"
                                       @if($idTemplate == 1) checked @endif class="mb-2"
                                       onchange="submitTemplateForm()">
                                <label for="template1"></label>
                                <iframe src="https://app.wisikard.fr/iframe?idTemplate=1"
                                        onerror="this.src='https://app.wisikard.fr/iframe?idTemplate=1'"
                                        class="w-full h-[300px] rounded-lg"></iframe>
                            </div>
                            @if($compte->role == 'starter')
                                <div class="relative z-50 flex flex-col items-center justify-center mb-4">
                                    <a href="https://wisikard.fr/produit/mise-a-niveau-wisikard-advanced/"
                                       target="_blank"
                                       class="bg-red-500 border-solid border border-red-500 hover:bg-red-900 hover:border-red-900 rounded-xl w-full h-7 flex items-center justify-center space-x-4">
                                        <p class="text-white text-base">Mettre à niveau</p>
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
                            <div class="flex flex-col items-center @if($compte->role == 'starter') blur-[3px] pointer-events-none opacity-50 @endif">
                                <input type="radio" name="idTemplate" id="template2" value="2"
                                       @if($idTemplate == 2) checked @endif class="mb-2"
                                       onchange="submitTemplateForm()">
                                <label for="template2"></label>
                                <iframe
                                        src="https://app.wisikard.fr/iframe?idTemplate=3"
                                        onerror="this.src='https://app.wisikard.fr/iframe?idTemplate=3'"
                                        class="w-full h-[300px] rounded-lg border border-gray-200">
                                </iframe>
                            </div>

                            <div class="flex flex-col items-center @if($compte->role == 'starter') blur-[3px] pointer-events-none opacity-50 @endif">
                                <input type="radio" name="idTemplate" id="template3" value="3"
                                       @if($idTemplate == 3) checked @endif class="mb-2"
                                       onchange="submitTemplateForm()">
                                <label for="template3"></label>
                                <iframe
                                        src="https://app.wisikard.fr/iframe?idTemplate=4"
                                        onerror="this.src='https://app.wisikard.fr/iframe?idTemplate=4'"
                                        class="w-full h-[300px] rounded-lg">
                                </iframe>
                            </div>

                            <div class="flex flex-col items-center @if($compte->role == 'starter') blur-[3px] pointer-events-none opacity-50 @endif">
                                <input type="radio" name="idTemplate" id="template4" value="4"
                                       @if($idTemplate == 4) checked @endif class="mb-2"
                                       onchange="submitTemplateForm()">
                                <label for="template4"></label>
                                <iframe
                                        src="https://app.wisikard.fr/iframe?idTemplate=5"
                                        onerror="this.src='https://app.wisikard.fr/iframe?idTemplate=5'"
                                        class="w-full h-[300px] rounded-lg">
                                </iframe>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <script>
                function submitTemplateForm() {
                    document.getElementById('templateForm').submit();
                }
            </script>
        </div>
    </div>
</div>
</body>
</html>
