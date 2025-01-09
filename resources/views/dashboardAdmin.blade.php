<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>dashboard Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .custom-width {
            width: 100%; /* Adjusted to be responsive */
        }
    </style>
</head>
<body class="align-items-center bg-gray-100 w-100">

<div class="flex flex-col md:flex-row">
    @include('menuAdmin')
    <div class="flex-1 md:ml-40"> <!-- Ajout de la marge gauche pour éviter le chevauchement -->
        <div class="min-h-screen p-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                @foreach($entreprises as $entreprise)
                    <div class="custom-width bg-white rounded-lg shadow-lg p-4 flex flex-col">
                        <!-- Titre -->
                        <div class="flex justify-between">
                            <!-- Entreprise et email -->
                            <div class="flex flex-col">
                                <div class="mb-4">
                                    <p class="text-xl font-semibold">{{ $entreprise->nomEntreprise }}</p>
                                </div>
                                <div class="mb-4">
                                    <p class="text-lg text-gray-600">{{ $entreprise->compte->email }}</p>
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
                        <div>
                            <p class="text-sm text-gray-600">{{ $entreprise->formattedTel }}</p>
                        </div>

                        @if($entreprise->compte->role == 'starter')
                            <div class="pt-4">
                                <div class="bg-blue-500 bg-opacity-65 border-solid border border-blue-500 rounded-full w-28 h-7 flex items-center justify-center">
                                    <p class="text-slate-50 text-base">Starter</p>
                                </div>
                            </div>
                        @elseif($entreprise->compte->role == 'advanced')
                            <div class="pt-4">
                                <div class="bg-violet-500 bg-opacity-65 border-solid border border-violet-500 rounded-full w-28 h-7 flex items-center justify-center">
                                    <p class="text-slate-50 text-base">Advanced</p>
                                </div>
                            </div>
                        @endif

                        <!-- Boutons -->
                        <div class="flex flex-row-reverse mt-auto pt-4">
                            <button class="bg-red-500 text-white px-4 py-2 rounded-full">Supprimer</button>
                            <a href="#" class="bg-indigo-500 text-white px-4 py-2 rounded-full mr-2">Modifier</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

</body>
</html>
