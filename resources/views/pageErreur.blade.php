<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <title>Erreur</title>
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100">

<main class="w-full max-w-md px-4">
    <form method="POST" action="{{ route('validationChangementMotDePasse') }}" class="bg-white shadow-md rounded-lg p-6">
        @csrf
        <div class="text-center">
            <h1 class="text-2xl font-bold text-gray-800 mb-4">
                Erreur
            </h1>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-left" role="alert">
                <b>{{ $messageErreur }}</b>
            </div>
            <div>
                <a href="{{ route('connexion') }}" class="text-blue-500 hover:underline">
                    Se connecter
                </a>
            </div>
        </div>
    </form>
</main>
</body>
</html>