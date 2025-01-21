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
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    {{ session('success') }}
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
                            <input type="text" name="lien" value="{{ isset($activatedSocial[$reseau->idSocial]) ? $activatedSocial[$reseau->idSocial]['lien'] : '' }}" class="border border-gray-300 p-2 rounded mb-2 w-full" placeholder="Lien du réseau social">
                            <div class="flex items-center mb-2">
                                <label class="toggle-switch">
                                    <input type="checkbox" id="{{ $reseau->idSocial }}" name="activer" {{ isset($activatedSocial[$reseau->idSocial]) && $activatedSocial[$reseau->idSocial]['activer'] ? 'checked' : '' }}>
                                    <span class="slider"></span>
                                </label>
                                <label for="{{ $reseau->idSocial }}" class="text-sm ml-2">Activer</label>
                            </div>
                            <button type="submit" class="bg-indigo-500 text-white p-2 rounded">Mettre à jour</button>
                        </form>
                    </div>
                @endforeach
            </div>

            <div class="bg-white rounded-lg shadow-lg p-4 my-6">
                <h2 class="text-lg font-semibold mb-4">Ajouter un réseau social</h2>
                <form action="#" method="POST" class="flex flex-col">
                    @csrf
                    <input type="hidden" name="idCarte" value="{{ $idCarte }}">
                    <input type="text" name="nomSocial" id="social" class="border border-gray-300 p-2 rounded mb-2 w-full" placeholder="Nom">
                    <input type="text" name="lien" class="border border-gray-300 p-2 rounded mb-2 w-full" placeholder="Lien du réseau social">
                    <button type="submit" class="bg-indigo-500 text-white p-2 rounded">Ajouter</button>
                </form>
            </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                  @foreach($custom as $link)
                        <div class="bg-white rounded-lg shadow-lg p-4 flex flex-col">
                            <div class="flex items-center mb-4">

                                <p class="text-lg font-semibold ml-2"> {{$link->nom }}</p>
                            </div>
                            <form action="#" method="POST" class="flex flex-col">
                                @csrf
                                <input type="text" name="lien" value="{{ $link->lien }}" class="border border-gray-300 p-2 rounded mb-2 w-full" placeholder="Lien du réseau social">
                                <div class="flex items-center mb-2">
                                    <label class="toggle-switch">
                                        <input type="checkbox" id="" name="activer">
                                        <span class="slider"></span>
                                    </label>
                                    <label for="" class="text-sm ml-2">Activer</label>
                                </div>
                                <button type="submit" class="bg-indigo-500 text-white p-2 rounded">Mettre à jour</button>
                            </form>
                        </div>
                    @endforeach
                </div>




        </div>
    </div>
</div>

</body>
</html>