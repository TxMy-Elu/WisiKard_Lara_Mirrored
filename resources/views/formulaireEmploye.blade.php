<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inscription Employé</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="align-items-center bg-gray-100 w-100">

<div class="flex flex-col md:flex-row">
    <div class="flex-1 md:ml-24 content">
        <div class="min-h-screen p-4">
            <div class="flex flex-col md:flex-row justify-between items-center pb-4">
                <h1 class="text-2xl font-bold">Inscription Employé</h1>
            </div>

            <form method="POST" action="{{ route('validationFormulaireInscriptionEmploye') }}" class="bg-white p-4 rounded-lg shadow-lg">
                @csrf
                <input type="hidden" name="idCompte" value="{{ session('connexion') }}"> <!-- Champ caché pour l'ID du compte -->
                <div class="mb-4">
                    <label for="nom" class="block text-gray-700 text-sm font-bold mb-2">Nom:</label>
                    <input type="text" id="nom" name="nom" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div class="mb-4">
                    <label for="prenom" class="block text-gray-700 text-sm font-bold mb-2">Prénom:</label>
                    <input type="text" id="prenom" name="prenom" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div class="mb-4">
                    <label for="fonction" class="block text-gray-700 text-sm font-bold mb-2">Fonction:</label>
                    <input type="text" id="fonction" name="fonction" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div class="mb-4">
                    <label for="motDePasse1" class="block text-gray-700 text-sm font-bold mb-2">Mot de passe:</label>
                    <input type="password" id="motDePasse1" name="motDePasse1" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div class="flex items-center justify-between">
                    <button type="submit" name="boutonInscriptionEmploye" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Inscrire</button>
                </div>
            </form>

            @if(isset($messageSucces))
                <div class="mt-4 p-4 bg-green-500 text-white rounded-lg shadow-lg">
                    {{ $messageSucces }}
                </div>
            @endif

            @if(isset($messagesErreur))
                <div class="mt-4 p-4 bg-red-500 text-white rounded-lg shadow-lg">
                    @foreach($messagesErreur as $message)
                        <p>{{ $message }}</p>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

</body>
</html>
