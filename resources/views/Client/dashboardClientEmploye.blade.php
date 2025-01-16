<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Client Employe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body class="align-items-center bg-gray-100 w-100">

<div class="flex flex-col md:flex-row">

    @include('menu.menuClient')
    <div class="flex-1 md:ml-24 content"><br/>
        <div class="min-h-screen p-4">
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

            @if(isset($error))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Erreur!</strong>
                    <span class="block sm:inline">{{ $error }}</span>
                </div>
            @endif

            <div class="flex flex-col md:flex-row justify-between items-center pb-4">
                <form method="GET" action="{{ route('dashboardClientEmploye') }}"
                      class="flex items-center relative w-full md:w-64 mb-4 md:mb-0">
                    <div class="search-icon pl-2">
                        <svg class="w-6 h-6 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                             xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search..."
                           class="p-2 pl-10 border border-gray-900 rounded-lg text-sm flex-grow">
                </form>

                <div class="flex items-center w-full md:w-auto">
                    <a href="{{ route('afficherFormulaireInscEmpl') }}"
                       class="w-full md:w-auto px-4 py-2 border border-gray-900 rounded-lg text-sm flex items-center justify-center hover:bg-gray-900 hover:text-white">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                             xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 4v16m8-8H4"></path>
                        </svg>
                        Ajouter un employe
                    </a>
                </div>
            </div><br/>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                @foreach($employes as $employe)
                    <div class="custom-width bg-white rounded-lg shadow-lg p-4 flex flex-col">
                        <div class="flex justify-between">
                            <div class="flex flex-col">
                                <div class="mb-4">
                                    <p class="text-xl font-semibold">{{ $employe->nom }}</p>
                                </div>
                                <div class="mb-4">
                                    <p class="text-lg text-gray-600">{{ $employe->prenom }}</p>
                                </div>
                                <div class="mb-4">
                                    <p class="text-lg text-gray-500">{{ $employe->fonction }}</p>
                                </div>
                                <div class="mb-4">
                                    <p class="text-sm text-gray-500">{{ $employe->telephone }}</p>
                                </div>
                                <div class="mb-4">
                                    <p class="text-sm text-gray-500">{{ $employe->mail }}</p>
                                </div>
                            </div>
                            <div class="flex justify-center mb-4">
                                <img src="{{ $employe->qrCode }}" alt="QR Code" class="w-32 h-32">
                            </div>
                        </div>
                        <div class="flex flex-row-reverse mt-auto pt-4">
                            <form action="{{ route('employe.destroy', $employe->idEmp) }}" method="POST"
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet employé ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-full">Supprimer</button>
                            </form>
                            <a href="{{ route('employe.modifier', $employe->idEmp) }}" class="bg-indigo-500 text-white px-4 py-2 rounded-full mr-2">Modifier</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

</body>
</html>
