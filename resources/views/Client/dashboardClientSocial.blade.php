<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Client</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body class="align-items-center bg-gray-100 w-100">

<div class="flex flex-col md:flex-row">
    @include('menu.menuClient')
    <div class="flex-1 md:ml-24 content">
        <div class="flex flex-wrap justify-center bg-gray-100 p-10">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @foreach($allSocial as $reseau)
                <div class="custom-width bg-white rounded-lg shadow-lg p-4 flex flex-col">
                    <div class="flex items-center mb-2">
                        {!! html_entity_decode($reseau->lienLogo) !!}
                        <p class="text-lg font-semibold ml-2">{{ $reseau->nom }}</p>
                    </div>
                    <form action="{{ route('client.updateSocialLink') }}" method="POST" class="flex items-center">
                        @csrf
                        <input type="hidden" name="idSocial" value="{{ $reseau->idSocial }}">
                        <input type="hidden" name="idCarte" value="{{ $idCarte }}">
                        <input type="text" name="lien" value="{{ isset($activatedSocial[$reseau->idSocial]) ? $activatedSocial[$reseau->idSocial]['lien'] : '' }}" class="border p-2 flex-1" placeholder="Lien du réseau social">
                        <input type="hidden" name="activer" value="{{ isset($activatedSocial[$reseau->idSocial]) && $activatedSocial[$reseau->idSocial]['activer'] ? 1 : 0 }}">
                        <button type="submit" class="bg-blue-500 text-white p-2 ml-2 rounded">Mettre à jour</button>
                    </form>
                    <div class="flex items-center mt-2">
                        <input type="checkbox" id="{{ $reseau->idSocial }}" class="toggle toggle-success" {{ isset($activatedSocial[$reseau->idSocial]) && $activatedSocial[$reseau->idSocial]['activer'] ? 'checked' : '' }}>
                        <label for="{{ $reseau->idSocial }}" class="ml-2">Activer</label>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

</body>
</html>
