<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Template Pêche</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100">
<div class="max-w-sm rounded-lg overflow-hidden shadow-lg bg-gradient-to-r from-purple-400 via-pink-500 to-red-500 text-white p-6">
    <div class="font-bold text-xl mb-2">{{ $carte->nomEntreprise }}</div>
    <p class="text-white text-base">{{ $carte->titre }}</p>
    <p class="text-white text-base">{{ $carte->tel }}</p>
    <p class="text-white text-base">{{ $carte->ville }}</p>
    <p class="text-white text-base">{{ $carte->descirptif }}</p>
    <p class="text-white text-base">{{ $compte->email }}</p>

    <div class="mt-4">
        <h3 class="font-bold text-lg">Réseaux Sociaux</h3>
        <ul class="flex space-x-4 mt-2">
            @foreach($social as $item)
                <li>
                    <a href="{{ $item->lien }}" class="text-blue-500">
                        {!! $logoSocial->where('idSocial', $item->idSocial)->first()->lienLogo !!}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="mt-4">
        <h3 class="font-bold text-lg">Vues</h3>
        <ul class="mt-2">
            @foreach($vue as $item)
                <li class="text-white">{{ $item->date }}</li>
            @endforeach
        </ul>
    </div>
</div>
</body>
</html>
