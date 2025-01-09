<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>dashboard Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .custom-width {
            width: 30rem; /* Vous pouvez ajuster cette valeur selon vos besoins */
        }
    </style>
</head>
<body class="align-items-center w-100">

<div class="flex">
    @include('menuAdmin')
    <div class="flex-1 ml-40"> <!-- Ajout de la marge gauche pour éviter le chevauchement -->
        <div class="flex items-center justify-center min-h-screen bg-gray-100">
            <div class="custom-width h-80 bg-white rounded-lg shadow-lg p-4 flex flex-col justify-between">
                <!-- Titre -->
                <div class="flex justify-between">
                    <!-- Entreprise et email -->
                    <div class="flex flex-col">
                        <div class="mb-4">
                            <p class="text-xl font-semibold">Entreprise xxxxx</p>
                        </div>
                        <div class="mb-4">
                            <p class="text-lg text-gray-600">to.doguet@gmail.com</p>
                        </div>
                    </div>
                    <!-- QR Code (vous pouvez remplacer par une image réelle de QR code) -->
                    <div class="flex justify-center mb-4">
                        <div class="w-16 h-16 bg-gray-200 flex items-center justify-center rounded">
                            QR Code
                        </div>
                    </div>
                </div>

                <!-- numero de telephone -->
                <div class="">
                    <p class="text-sm text-gray-600">XXXXX.XXXXX.XXXXX</p>
                </div>

                <!-- Boutons -->
                <div class="flex justify-between">
                    <button class="bg-blue-500 text-white px-4 py-2 rounded-full">Starter</button>
                    <button class="bg-indigo-500 text-white px-4 py-2 rounded-full">Modifier</button>
                    <button class="bg-red-500 text-white px-4 py-2 rounded-full">Supprimer</button>
                </div>
            </div>
            @foreach($entreprises as $entreprise)

            @endforeach
        </div>
    </div>
</div>

</body>
</html>