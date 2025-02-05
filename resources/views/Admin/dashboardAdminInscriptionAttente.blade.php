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
    </style>
</head>
<body class="align-items-center bg-grey-600">

            <div class="grid grid-cols-4 gap-4 mr-4">
                @foreach($entreprises as $entreprise)
                    <div class="bg-white rounded-lg shadow-lg p-4 flex flex-col">
                        <!-- Title -->
                        <div class="flex justify-between">
                            <!-- Company and email -->
                            <div class="flex flex-col">
                                <div class="mb-4">
                                    <p class="text-xl font-semibold">{{ $entreprise->nom_entre }}</p>
                                </div>
                                <div class="mb-4">
                                    <p class="text-lg text-gray-600">{{ $entreprise->mail }}</p>
                                </div>
                          <div id="toggleButton"
                                                           class="mx-4 flex items-center justify-center w-8 h-8 bg-indigo-500 text-white rounded-full cursor-pointer">
                                                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                               xmlns="http://www.w3.org/2000/svg">
                                                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M12 4v16m8-8H4"></path>
                                                          </svg>
                                                      </div>
                            </div>

                        @if($entreprise->compte_role == 'starter')
                            <div class="pt-4">
                                <div class="bg-blue-500/65 border-solid border border-blue-500 rounded-full w-28 h-7 flex items-center justify-center">
                                    <p class="text-slate-50 text-base">Starter</p>
                                </div>
                            </div>
                        @elseif($entreprise->compte_role == 'advanced')
                            <div class="pt-4">
                                <div class="bg-violet-500/65 border-solid border border-violet-500 rounded-full w-28 h-7 flex items-center justify-center">
                                    <p class="text-slate-50 text-base">Advanced</p>
                                </div>
                            </div>
                        @endif

                        <!-- Buttons -->
                        <div class="flex flex-row-reverse mt-auto pt-4">
                            <form action="{{ route('entreprise.destroy', $entreprise->idCarte) }}" method="POST"
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette entreprise ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-full">Supprimer
                                </button>
                            </form>
                            <a href="{{ route('modifierMdp', $entreprise->idCompte) }}" class="bg-indigo-500 text-white px-4 py-2 rounded-full mr-2">Modifier MDP</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

</body>
</html>
