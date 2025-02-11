<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Admin Inscription Attente</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet"/>
    <style>
        .qr-code-container {
            width: 100px; /* Taille fixe pour le conteneur du QR code */
            height: 100px;
            background-color: #f1f1f1; /* Couleur de fond pour le conteneur */
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden; /* Couper le contenu qui dépasse */
            border-radius: 8px; /* Coins arrondis */
        }

        .qr-code-container img {
            max-width: 100%; /* Assure que l'image s'adapte au conteneur */
            max-height: 100%;
        }

        @media (max-width: 768px) {
            .qr-code-container {
                width: 80px; /* Taille fixe pour le conteneur du QR code sur mobile */
                height: 80px;
            }

            .grid-cols-4 {
                grid-template-columns: 1fr;
            }

            .md\:flex-row {
                flex-direction: column;
            }

            .md\:ml-24 {
                margin-left: 0;
            }

            .md\:w-64 {
                width: 100%;
            }

            .md\:w-auto {
                width: 100%;
            }

            .content {
                padding: 1rem;
            }
        }
    </style>
</head>
<body class="align-items-center bg-grey-600">

<div class="flex flex-col md:flex-row">
    @include('menu.menuAdmin')
    <div class="flex-1 md:ml-24 content"> <!-- Adjusted margin-left to match the new menu width -->
        @if($messageContent != "Aucun message disponible" || empty($messageContent))
            <div class="bg-zinc-400/45 border border-zinc-400 text-zin-700 px-4 py-3 rounded relative"
                 role="alert">
                <strong class="font-bold">Information :</strong>
                <span class="block sm:inline">{{ $messageContent }}</span>
            </div>
        @endif
        <div class="grid grid-cols-4 gap-4 mr-4">
            @foreach($inscriptions as $inscription)
                <div class="bg-white rounded-lg shadow-lg p-4 flex flex-col">
                    <!-- Title -->
                    <div class="flex justify-between items-center mb-4">
                        <!-- Company and email -->
                        <div class="flex flex-col">
                            <p class="text-xl font-semibold">{{ $inscription->nom_entre }}</p>
                            <p class="text-lg text-gray-600">{{ $inscription->mail }}</p>
                        </div>

                        @if($inscription->role == 'starter')
                            <div class="bg-blue-500/65 border-solid border border-blue-500 rounded-full w-28 h-7 flex items-center justify-center">
                                <p class="text-slate-50 text-base">Starter</p>
                            </div>
                        @elseif($inscription->role == 'advanced')
                            <div class="bg-violet-500/65 border-solid border border-violet-500 rounded-full w-28 h-7 flex items-center justify-center">
                                <p class="text-slate-50 text-base">Advanced</p>
                            </div>
                        @elseif($inscription->role == 'admin')
                            <div class="bg-red-500/65 border-solid border border-violet-500 rounded-full w-28 h-7 flex items-center justify-center">
                                <p class="text-slate-50 text-base">Admin</p>
                            </div>
                        @endif
                    </div>

                    <div class="mb-4">
                        <p class="text-lg text-gray-600">{{ $inscription->date_inscription }}</p>
                    </div>

                    <!-- Buttons -->
                    <div class="flex flex-row justify-between">
                        <form action="{{ route('inscription.ajout', $inscription->id_inscripAttente) }}" method="POST"
                              onsubmit="return confirm('Êtes-vous sûr de vouloir accepter cette inscription ?');">
                            @csrf
                            @method('POST')
                            <button type="submit" name="ajout" onclick="boutonInscriptionClient()"
                                    class="bg-indigo-500 text-white px-4 py-2 rounded-full">Ajouter
                            </button>
                        </form>
                        <form action="{{ route('inscription.destroy', $inscription->id_inscripAttente) }}" method="POST"
                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette inscription ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-full">Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
</body>
</html>
