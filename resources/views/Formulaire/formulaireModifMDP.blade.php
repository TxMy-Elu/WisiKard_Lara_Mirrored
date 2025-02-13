<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modifier Mot de Passe</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet"/>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('form');
            const mdp1 = document.getElementById('mdp1');
            const mdp2 = document.getElementById('mdp2');
            const errorMessage = document.getElementById('error-message');

            form.addEventListener('submit', function (event) {
                if (mdp1.value !== mdp2.value) {
                    event.preventDefault(); // Prevent form submission
                    errorMessage.textContent = 'Les mots de passe ne correspondent pas.';
                    errorMessage.style.display = 'block';
                } else {
                    errorMessage.style.display = 'none';
                }
            });
        });
    </script>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal min-h-screen flex items-center justify-center">
<!-- Container principal -->
<div class="container mx-auto px-4">
    <div class="max-w-lg mx-auto bg-white rounded-lg shadow-md overflow-hidden">

        <!-- En-tête -->
        <div class="px-6 py-4 bg-red-800 text-white text-center">
            <h1 class="text-xl font-semibold">Modifier votre mot de passe</h1>
            <p class="text-sm">Assurez-vous d'utiliser un mot de passe sécurisé.</p>
        </div>

        <!-- Messages de succès/erreur -->
        <div class="p-4">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <strong>Succès!</strong> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <strong>Erreur!</strong> {{ session('error') }}
                </div>
            @endif
        </div>

        <!-- Formulaire -->
        <form action="{{ route('updateMDP', ['id' => $compte->idCompte]) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <!-- Champ Nouveau mot de passe -->
            <div class="mb-4">
                <label for="mdp1" class="block text-gray-700 text-sm font-semibold mb-2">Nouveau mot de passe :</label>
                <input type="password" id="mdp1" name="mdp1" required
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-red-800">
            </div>

            <!-- Champ Confirmation mot de passe -->
            <div class="mb-4">
                <label for="mdp2" class="block text-gray-700 text-sm font-semibold mb-2">Confirmez le mot de passe
                    :</label>
                <input type="password" id="mdp2" name="mdp2" required
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-red-800">
            </div>

            <!-- Message d'erreur pour mots de passe différents -->
            <div id="error-message" class="text-red-500 text-sm mb-4" style="display: none;">Les mots de passe ne
                correspondent pas.
            </div>

            <!-- Boutons -->
            <div class="flex items-center justify-between">
                <!-- Bouton Soumettre -->
                <button type="submit"
                        class="bg-red-800 hover:bg-red-900 text-white font-bold py-2 px-4 rounded focus:outline-none transform hover:scale-105 duration-300">
                    Modifier
                </button>

                <!-- Bouton Retour -->
                <a href="{{ route('dashboardAdmin') }}"
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none transform hover:scale-105 duration-300">
                    Retour
                </a>
            </div>
        </form>
    </div>
</div>
</body>
</html>