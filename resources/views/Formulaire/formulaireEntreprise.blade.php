<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modifier Entreprise</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <script>
        // Fonction pour vérifier avant la soumission
        function confirmEntrepriseModification(event) {
            const form = event.target;
            const nomActuel = "{{ $carte->nomEntreprise }}"; // Nom actuel de l'entreprise
            const nomEntreprisInput = document.getElementById('nomEntreprise').value;

            if (nomEntreprisInput !== nomActuel) {
                const confirmation = confirm(
                    'Attention : La modification du nom de l\'entreprise entraînera l\'invalidation de tous les anciens QR codes. Vous devrez régénérer tous les QR codes manuellement pour qu\'ils soient de nouveau utilisables. Voulez-vous continuer ?'
                );
                if (!confirmation) {
                    event.preventDefault(); // Annule la soumission si l'utilisateur refuse
                }
            }
        }
    </script>
</head>
<body class="align-items-center bg-gray-100 w-100">
<div class="flex flex-col md:flex-row">
    @include('menu.menuClient')
    <div class="flex-1 md:ml-24 content"> <!-- Adjusted margin-left to match the new menu width -->
        <div class="min-h-screen p-4">
            <!-- Messages de succès ou d'erreur -->
            <div class="flex flex-col md:flex-row justify-between items-center pb-4">
                <h1 class="text-2xl font-semibold">Modifier Entreprise</h1>
            </div>
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
            <form action="{{ route('updateEntreprise') }}" method="POST" class="bg-white p-4 rounded-lg shadow-lg" onsubmit="confirmEntrepriseModification(event)">
                @csrf
                @method('POST')
                <div class="mb-4">
                    <label for="nomEntreprise" class="block text-gray-700 text-sm font-bold mb-2">Nom Entreprise:</label>
                    <input type="text" id="nomEntreprise" name="nomEntreprise" value="{{ $carte->nomEntreprise }}"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label for="mail" class="block text-gray-700 text-sm font-bold mb-2">Adresse mail</label>
                    <input type="email" id="mail" name="mail" value="{{ $carte->compte->email }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label for="tel" class="block text-gray-700 text-sm font-bold mb-2">Téléphone</label>
                    <input type="text" id="tel" name="tel" value="{{ $carte->tel }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label for="adresse" class="block text-gray-700 text-sm font-bold mb-2">Adresse</label>
                    <input type="text" id="adresse" name="adresse" value="{{ $carte->ville }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Modifier</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>