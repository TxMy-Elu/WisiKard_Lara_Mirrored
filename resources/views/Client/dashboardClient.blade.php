<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Client</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">

<div class="flex flex-col md:flex-row">
    @include('menu.menuClient')

    <div class="flex-1 md:ml-24 content"> <!-- Adjusted margin-left to match the new menu width -->
        @if($messageContent != "Aucun message disponible" || empty($messageContent))
            <div class="bg-zinc-400 bg-opacity-45 border border-zinc-400 text-zin-700 px-4 py-3 rounded relative"
                 role="alert">
                <strong class="font-bold">Information :</strong>
                <span class="block sm:inline">{{ $messageContent }}</span>
            </div>
        @endif
        <div class="min-h-screen p-4">
        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <h1 class="text-2xl font-bold mb-4">Informations du Compte</h1>
            <p class="mb-2"><strong>Email:</strong> {{ $compte->email }}</p>
            <p class="mb-2"><strong>Rôle:</strong> {{ $compte->role }}</p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <h2 class="text-xl font-bold mb-4">Informations de l'Entreprise</h2>
            <p class="mb-2"><strong>Nom de l'entreprise:</strong> {{ $carte->nomEntreprise }}</p>
            <p class="mb-2"><strong>Téléphone:</strong> {{ $carte->tel }}</p>
            <p class="mb-2"><strong>Ville:</strong> {{ $carte->ville }}</p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <h2 class="text-xl font-bold mb-4">Cartes</h2>
            @foreach($cartes as $carte)
                <div class="border p-4 mb-4 rounded-lg">
                    <p class="mb-2"><strong>Nom de l'entreprise:</strong> {{ $carte->nomEntreprise }}</p>
                    <p class="mb-2"><strong>Titre:</strong> {{ $carte->titre }}</p>
                    <p class="mb-2"><strong>Téléphone:</strong> {{ $carte->tel }}</p>
                    <p class="mb-2"><strong>Ville:</strong> {{ $carte->ville }}</p>
                </div>
            @endforeach
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-bold mb-4">Employés</h2>
            @foreach($employes as $employe)
                <div class="border p-4 mb-4 rounded-lg">
                    <p class="mb-2"><strong>Nom:</strong> {{ $employe->nom }}</p>
                    <p class="mb-2"><strong>Prénom:</strong> {{ $employe->prenom }}</p>
                    <p class="mb-2"><strong>Fonction:</strong> {{ $employe->fonction }}</p>
                    <p class="mb-2"><strong>Téléphone:</strong> {{ $employe->telephone }}</p>
                </div>
            @endforeach
        </div>
    </div>
</div>

</body>
</html>
