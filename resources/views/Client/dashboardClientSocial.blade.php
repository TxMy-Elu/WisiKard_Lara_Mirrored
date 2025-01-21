<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Client</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
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
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($allSocial as $reseau)
                    <div class="bg-white rounded-lg shadow-lg p-4 flex flex-col">
                        <div class="flex items-center mb-4">
                            {!! $reseau->lienLogo !!}
                            <p class="text-lg font-semibold ml-2">{{ $reseau->nom }}</p>
                        </div>
                        <form action="{{ route('client.updateSocialLink') }}" method="POST" class="flex flex-col">
                            @csrf
                            <input type="hidden" name="idSocial" value="{{ $reseau->idSocial }}">
                            <input type="hidden" name="idCarte" value="{{ $idCarte }}">
                            <input type="text" name="lien"
                                   value="{{ isset($activatedSocial[$reseau->idSocial]) ? $activatedSocial[$reseau->idSocial]['lien'] : '' }}"
                                   class="border border-gray-300 p-2 rounded mb-2 w-full"
                                   placeholder="Lien du réseau social">
                            <div class="flex justify-between ">
                                <div class="flex items-center mb-2">
                                    <label class="toggle-switch">
                                        <input type="checkbox" id="{{ $reseau->idSocial }}"
                                               name="activer" {{ isset($activatedSocial[$reseau->idSocial]) && $activatedSocial[$reseau->idSocial]['activer'] ? 'checked' : '' }}>
                                        <span class="slider"></span>
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

            <div class="bg-blue-950 rounded-lg shadow-lg p-4 my-6 flex items-center justify-center text-center">
                <h2 class="text-xl text-white font-semibold">Ajouter un réseau social</h2>
            </div>


            {{-- line / svg + --}}
            <div class="flex items-center justify-center mb-4">
                <!-- Ligne à gauche -->
                <div class="flex-grow h-px bg-gray-300"></div>

                <!-- Rond avec un plus -->
                <div id="toggleButton"
                     class="mx-4 flex items-center justify-center w-8 h-8 bg-indigo-500 text-white rounded-full cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                         xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>

                <!-- Ligne à droite -->
                <div class="flex-grow h-px bg-gray-300"></div>
            </div>

            <div class="flex flex-col items-center mb-4">

                <!-- Formulaire caché -->

                <form action="{{ route('dashboardClientCustomLink') }}" method="POST" class="flex flex-col" id="hiddenForm">
                    <div class="bg-white rounded-lg shadow-lg p-4 my-6">
                        @csrf
                        @method('POST')
                        <div class="flex flex-col mb-4">
                            <label for="nom" class="text-lg mb-2">Nom du réseau social:</label>
                            <input type="text" name="nom" id="nom" class="border border-gray-300 p-2 rounded w-full"
                                   placeholder="Nom du réseau social">
                        </div>
                        <div class="flex flex-col mb-4">
                            <label for="lien" class="text-lg mb-2">Lien:</label>
                            <input type="text" name="lien" id="lien"
                                   class="border border-gray-300 p-2 rounded w-full" placeholder="Lien du reseau">
                        </div>
                        <button type="submit" class="bg-indigo-500 text-white text-base rounded px-10 py-2 ml-auto">
                            Ajouter
                        </button>
                    </div>
                </form>
            </div>


            <script>
                // Sélectionner le bouton et le formulaire
                const toggleButton = document.getElementById('toggleButton');
                const hiddenForm = document.getElementById('hiddenForm');

                // Écouter le clic sur le bouton
                toggleButton.addEventListener('click', () => {
                    // Basculer l'affichage du formulaire
                    hiddenForm.classList.toggle('hidden');
                });
            </script>


            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($custom as $link)
                    <div class="bg-white rounded-lg shadow-lg p-4 flex flex-col">
                        <div class="flex items-center mb-4">

                            <p class="text-lg font-semibold ml-2"> {{$link->nom }}</p>
                        </div>
                        <form action="#" method="POST" class="flex flex-col">
                            @csrf
                            <input type="text" name="lien" value="{{ $link->lien }}"
                                   class="border border-gray-300 p-2 rounded mb-2 w-full"
                                   placeholder="Lien du réseau social">
                            <div class="flex justify-between ">
                                <div class="flex items-center mb-2">
                                    <label class="toggle-switch">
                                        <input type="checkbox" id="" name="activer">
                                        <span class="slider"></span>
                                    </label>
                                    <label for="" class="text-sm ml-2">Activer</label>
                                </div>
                                <button type="submit" class="bg-indigo-500 text-white p-2 rounded">Mettre à jour
                                </button>
                            </div>
                        </form>
                    </div>
                @endforeach
            </div>


        </div>
    </div>
</div>

</body>
</html>