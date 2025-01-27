<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Admin</title>
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
    </style>
</head>
<body class="align-items-center bg-grey-600">

<div class="flex flex-col md:flex-row">
    @include('menu.menuAdmin')
    <div class="flex-1 md:ml-24 content"> <!-- Adjusted margin-left to match the new menu width -->
        @if($messageContent != "Aucun message disponible" || empty($messageContent))
            <div class="bg-zinc-400/45 border border-zinc-400 text-zin-700 px-4 py-3 rounded relative"
                 role="alert">
                <strong class="font-bold">Information :</strong>
                <span class="block sm:inline">{{ $messageContent }}</span>
            </div>
        @endif
        <div class="w-full p-4">
            <div class="flex flex-col md:flex-row justify-between items-center pb-4">
                <!-- Search bar -->
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
                    <!-- Adjusted padding-left to pl-10 -->
                </form>
                <!-- Link to the registration form -->
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

            <div class="grid grid-cols-4 gap-4 mr-4">
                @foreach($entreprises as $entreprise)
                    <div class="bg-white rounded-lg shadow-lg p-4 flex flex-col">
                        <!-- Title -->
                        <div class="flex justify-between">
                            <!-- Company and email -->
                            <div class="flex flex-col">
                                <div class="mb-4">
                                    <p class="text-xl font-semibold">{{ $entreprise->nomEntreprise }}</p>
                                </div>
                                <div class="mb-4">
                                    <p class="text-lg text-gray-600">{{ $entreprise->compte->email }}</p>
                                </div>
                                <!-- Phone number -->
                                <div>
                                    <p class="text-sm text-gray-600">{{ $entreprise->formattedTel }}</p>
                                </div>
                            </div>

                            <div class="flex flex-col">
                                <!-- QR Code (you can replace with an actual QR code image) -->
                                <div class="justify-center mb-2">
                                    <div class="qr-code-container">
                                        <img src="{{ $entreprise->lienQr }}" alt="QR Code"
                                             class="max-w-full max-h-full">
                                    </div>
                                </div>
                                <div class="flex justify-end ">
                                    <a href="{{ route('refreshQrCode', $entreprise->compte->idCompte) }}" class="ml-2">
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

                        @if($entreprise->compte->role == 'starter')
                            <div class="pt-4">
                                <div class="bg-blue-500/65 border-solid border border-blue-500 rounded-full w-28 h-7 flex items-center justify-center">
                                    <p class="text-slate-50 text-base">Starter</p>
                                </div>
                            </div>
                        @elseif($entreprise->compte->role == 'advanced')
                            <div class="pt-4">
                                <div class="bg-violet-500/65 border-solid border border-violet-500 rounded-full w-28 h-7 flex items-center justify-center">
                                    <p class="text-slate-50 text-base">Advanced</p>
                                </div>
                            </div>
                        @endif

                        <!-- Buttons -->
                        <div class="flex flex-row-reverse mt-auto pt-4">
                            <form action="{{ route('entreprise.destroy', $entreprise->idCarte) }}" method="POST"
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette entreprise ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-full">Supprimer
                                </button>
                            </form>
                            <a href="#" class="bg-indigo-500 text-white px-4 py-2 rounded-full mr-2">Modifier</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

</body>
</html>
