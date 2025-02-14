<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Changement du mot de passe</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100">

<main class="w-full max-w-md">
    <form method="POST" action="{{ route('validationChangementMotDePasse') }}" class="bg-white shadow-md rounded-lg p-6 mx-auto">
        @csrf
        <div class="text-center">
            <h1 class="text-2xl font-bold text-gray-800 mb-4">
                Changement du mot de passe
            </h1>
            @include('messageErreur')
            <div class="mb-3 text-left">
                <i class="text-gray-600">Renseignez un nouveau mot de passe pour votre compte utilisateur :</i>
            </div>
            <div class="mb-4">
                <label for="motDePasse1" class="block text-sm text-gray-700">Nouveau mot de passe :</label>
                <input type="password" id="motDePasse1" name="motDePasse1" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Mot de passe..." required>
                <span class="text-sm text-gray-500 mt-1 block">Minimum 13 caractères</span>
            </div>
            <div class="mb-4">
                <label for="motDePasse2" class="block text-sm text-gray-700">Confirmer le mot de passe :</label>
                <input type="password" id="motDePasse2" name="motDePasse2" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Confirmer le mot de passe..." required>
            </div>
            <div>
                <button class="w-full bg-blue-500 text-white py-2 px-4 rounded-md font-bold hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition" type="submit" value="{{ $codeRecuperation }}" name="boutonChangerMotDePasse">
                    Valider
                </button>
            </div>
        </div>
    </form>
</main>

</body>
</html>