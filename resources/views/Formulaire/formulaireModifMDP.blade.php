<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modifier Mot de Passe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('form');
            const mdp1 = document.getElementById('mdp1');
            const mdp2 = document.getElementById('mdp2');
            const errorMessage = document.getElementById('error-message');

            form.addEventListener('submit', function (event) {
                if (mdp1.value !== mdp2.value) {
                    event.preventDefault(); // Empêche la soumission du formulaire
                    errorMessage.textContent = 'Les mots de passe ne correspondent pas.';
                    errorMessage.style.display = 'block';
                } else {
                    errorMessage.style.display = 'none';
                }
            });
        });
    </script>
</head>
<body class="align-items-center bg-gray-100 w-100">
<div class="flex flex-col md:flex-row">
    @include('menu.menuClient')
    <div class="flex-1 md:ml-24 content"> <!-- Adjusted margin-left to match the new menu width -->
        <div class="flex justify-between items-center mb-4">
            <!-- Bouton Retour -->
            <a href="{{ route('dashboardAdmin') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Retour</a>
        </div>
        <div class="min-h-screen p-4 flex justify-center items-center">

            <!-- Messages de succès ou d'erreur -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Succès!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Erreur!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Carte du formulaire -->
            <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
                <div class="flex justify-between items-center pb-4">
                    <h1 class="text-center text-lg md:text-xl lg:text-2xl font-bold">Modifier mot de passe</h1>
                </div>
                <form action="{{ route('updateMDP', $compte->idCompte) }}" method="POST" class="bg-white justify-between p-4 rounded-lg shadow-lg">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label for="mdp1" class="block text-gray-700 text-sm font-bold mb-2">Nouveau mot de passe:</label>
                        <input type="password" id="mdp1" name="mdp1" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>

                    <div class="mb-4">
                        <label for="mdp2" class="block text-gray-700 text-sm font-bold mb-2">Retapez le mot de passe:</label>
                        <input type="password" id="mdp2" name="mdp2" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>

                    <div id="error-message" class="text-red-500 text-sm mb-4" style="display: none;"></div>

                    <div class="flex items-center justify-between">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Modifier</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
