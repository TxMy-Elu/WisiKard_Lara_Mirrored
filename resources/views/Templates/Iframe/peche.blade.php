<!-- resources/views/carte.blade.php -->
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
    <div class="font-bold text-xl mb-2">Nom de l'Entreprise</div>
    <p class="text-white text-base">Titre</p>
    <p class="text-white text-base">Téléphone</p>
    <p class="text-white text-base">Ville</p>
    <p class="text-white text-base">Description</p>
    <p class="text-white text-base">email@example.com</p>

    <div class="mt-4">
        <h3 class="font-bold text-lg">Réseaux Sociaux</h3>
        <ul class="flex space-x-4 mt-2">
            <li>
                <a href="https://facebook.com" class="text-blue-500">
                    <img src="https://via.placeholder.com/30" alt="Facebook">
                </a>
            </li>
            <li>
                <a href="https://twitter.com" class="text-blue-500">
                    <img src="https://via.placeholder.com/30" alt="Twitter">
                </a>
            </li>
            <li>
                <a href="https://instagram.com" class="text-blue-500">
                    <img src="https://via.placeholder.com/30" alt="Instagram">
                </a>
            </li>
        </ul>
    </div>

    <div class="mt-4">
        <h3 class="font-bold text-lg">Vues</h3>
        <ul class="mt-2">
            <li class="text-white">2023-01-01</li>
            <li class="text-white">2023-02-01</li>
            <li class="text-white">2023-03-01</li>
        </ul>
    </div>
</div>
</body>
</html>
