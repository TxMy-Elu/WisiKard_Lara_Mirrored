<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Client</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <style>
        /* Styles pour la version mobile */
        @media (max-width: 768px) {
            .grid {
                grid-template-columns: 1fr;
                grid-template-rows: auto;
            }
            .col-span-2 {
                grid-column: span 1;
            }
            .row-span-2 {
                grid-row: span 1;
            }
            .row-span-3 {
                grid-row: span 1;
            }
            .col-span-4 {
                grid-column: span 1;
            }
            .row-span-4 {
                grid-row: span 1;
            }
            .md\:flex-nowrap {
                flex-wrap: wrap;
            }
            .md\:space-x-12 {
                margin-bottom: 1rem;
            }
            .md\:w-1\/2 {
                width: 100%;
            }
            .md\:w-1\/3 {
                width: 100%;
            }
            .md\:ml-24 {
                margin-left: 0;
            }
            .flex-1 {
                flex: 1 1 100%;
            }
            .h-96 {
                height: auto;
            }
            .w-86 {
                width: 100%;
            }
            .h-40 {
                height: 200px;
            }
            .w-32 {
                width: 100%;
            }
            .h-32 {
                height: auto;
            }
            .p-32 {
                padding: 2rem;
            }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

<div class="flex flex-col md:flex-row flex-1">
    @include('menu.menuClient')
    <div class="flex-1 md:ml-24 p-6">
        <div class="p-6">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                     role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    {{ session('error') }}
                </div>
            @endif
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-2">
                @foreach($allSocial as $reseau)
                    <div class="bg-white rounded-lg shadow-lg p-4 flex flex-col">
                        <div class="flex items-center mb-4">
                            {!! $reseau->lienLogo !!}
                            <p class="text-lg font-semibold ml-2 text-gray-800">{{ $reseau->nom }}</p>
                        </div>
                        <form action="{{ route('client.updateSocialLink') }}" method="POST" class="flex flex-col">
                            @csrf
                            <input type="hidden" name="idSocial" value="{{ $reseau->idSocial }}">
                            <input type="hidden" name="idCarte" value="{{ $idCarte }}">
                            <input type="text" name="lien"
                                   value="{{ isset($activatedSocial[$reseau->idSocial]) ? $activatedSocial[$reseau->idSocial]['lien'] : '' }}"
                                   class="border border-gray-300 p-2 rounded mb-2 w-full"
                                   placeholder="Lien du réseau social">
                            <div class="flex justify-between">
                                <div class="flex items-center mb-2">
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" id="{{ $reseau->idSocial }}"
                                               name="activer"
                                               {{ isset($activatedSocial[$reseau->idSocial]) && $activatedSocial[$reseau->idSocial]['activer'] ? 'checked' : '' }}
                                               class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                                    </label>
                                    <label for="{{ $reseau->idSocial }}" class="text-sm ml-2">Activer</label>
                                </div>
                                <button type="submit" class="bg-indigo-500 text-white p-2 rounded">Mettre à jour
                                </button>
                            </div>
                        </form>
                    </div>
                @endforeach
            </div>

            @if($compte->role == 'starter')
                <div class="relative">
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
                    <div class="blur-[3px] pointer-events-none opacity-50">
                        <div class="bg-blue-950 rounded-lg shadow-lg p-4 my-6 flex items-center justify-center text-center">
                            <h2 class="text-xl text-white font-semibold">Ajouter un réseau social</h2>
                        </div>

                        <div class="flex items-center justify-center mb-4">
                            <div class="flex-grow h-px bg-gray-300"></div>
                            <div id="toggleButton"
                                 class="mx-4 flex items-center justify-center w-8 h-8 bg-indigo-500 text-white rounded-full cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 4v16m8-8H4"></path>
                                </svg>
                            </div>
                            <div class="flex-grow h-px bg-gray-300"></div>
                        </div>

                        <div class="flex flex-col items-center mb-4">
                            <div class="flex flex-col hidden">
                                <div class="bg-white rounded-lg shadow-lg p-4 my-6">
                                    <div class="flex flex-col mb-4">
                                        <label for="nom" class="text-lg mb-2 text-gray-800">Nom du réseau
                                            social:</label>
                                        <input type="text" name="nom" id="nom"
                                               class="border border-gray-300 p-2 rounded w-full"
                                               placeholder="Nom du réseau social">
                                    </div>
                                    <div class="flex flex-col mb-4">
                                        <label for="lien" class="text-lg mb-2">Lien:</label>
                                        <input type="text" name="lien" id="lien"
                                               class="border border-gray-300 p-2 rounded w-full"
                                               placeholder="Lien du reseau">
                                    </div>
                                    <button type="submit"
                                            class="bg-indigo-500 text-white text-base rounded px-10 py-2 ml-auto">
                                        Ajouter
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-blue-950 rounded-lg shadow-lg p-4 my-6 flex items-center justify-center text-center">
                    <h2 class="text-xl text-white font-semibold">Ajouter un réseau social</h2>
                </div>

                <div class="flex items-center justify-center mb-4">
                    <div class="flex-grow h-px bg-gray-300"></div>
                    <div id="toggleButton"
                         class="mx-4 flex items-center justify-center w-8 h-8 bg-indigo-500 text-white rounded-full cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                             xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 4v16m8-8H4"></path>
                        </svg>
                    </div>
                    <div class="flex-grow h-px bg-gray-300"></div>
                </div>

                <div class="flex flex-col items-center mb-4">
                    <form action="{{ route('dashboardClientCustomLink') }}" method="POST"
                          class="flex flex-col hidden"
                          id="hiddenForm">
                        <div class="bg-white rounded-lg shadow-lg p-4 my-6">
                            @csrf
                            @method('POST')
                            <div class="flex flex-col mb-4">
                                <label for="nom" class="text-lg mb-2 text-gray-800">Nom du réseau social:</label>
                                <input type="text" name="nom" id="nom"
                                       class="border border-gray-300 p-2 rounded w-full"
                                       placeholder="Nom du réseau social">
                            </div>
                            <div class="flex flex-col mb-4">
                                <label for="lien" class="text-lg mb-2">Lien:</label>
                                <input type="text" name="lien" id="lien"
                                       class="border border-gray-300 p-2 rounded w-full"
                                       placeholder="Lien du reseau">
                            </div>
                            <button type="submit"
                                    class="bg-indigo-500 text-white text-base rounded px-10 py-2 ml-auto">
                                Ajouter
                            </button>
                        </div>
                    </form>
                </div>

                <script>
                    const toggleButton = document.getElementById('toggleButton');
                    const hiddenForm = document.getElementById('hiddenForm');

                    toggleButton.addEventListener('click', () => {
                        hiddenForm.classList.toggle('hidden');
                    });
                </script>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($custom as $link)
                        <div class="bg-white rounded-lg shadow-lg p-4 flex flex-col">
                            <div class="flex items-center mb-4">
                                <p class="text-lg font-semibold ml-2 text-gray-800">{{ $link->nom }}</p>
                            </div>
                            <form action="{{ route('activeSocialLink') }}" method="POST" class="flex flex-col">
                                @csrf
                                <input type="hidden" name="id_link" value="{{ $link->id_link }}">
                                <input type="text" name="lien" value="{{ $link->lien }}"
                                       class="border border-gray-300 p-2 rounded mb-2 w-full"
                                       placeholder="Lien du réseau social">
                                <div class="flex justify-between">
                                    <div class="flex items-center mb-2">
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" id="{{ $link->id_link }}" name="activer"
                                                   {{ isset($activatedCustomLinks[$link->id_link]) && $activatedCustomLinks[$link->id_link]['activer'] ? 'checked' : '' }}
                                                   class="sr-only peer">
                                            <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">

                                            </div>
                                        </label>
                                        <label for="activer-{{ $link->id }}" class="text-sm ml-2">Activer</label>
                                    </div>
                                    <button type="submit" class="bg-indigo-500 text-white p-2 rounded">Mettre à jour
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
</body>
</html>
