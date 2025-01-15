<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body class="align-items-center bg-gray-100 w-100 ">

<div class="flex flex-col md:flex-row">
    @include('menu.menuAdmin')


    <div class="flex-1 md:ml-24 content"> <!-- Adjusted margin-left to match the new menu width -->
        @if($messageContent != "Aucun message disponible" || empty($messageContent))
            <div class="bg-zinc-400 bg-opacity-45 border border-zinc-400 text-zin-700 px-4 py-3 rounded relative"
                 role="alert">
                <strong class="font-bold">Information :</strong>
                <span class="block sm:inline">{{ $messageContent }}</span>
            </div>
        @endif
        <div class="min-h-screen p-4">
            <div class="flex flex-col md:flex-row justify-between items-center pb-4">
                <!-- Search bar -->
                <form method="GET" action="{{ route('dashboardAdmin') }}"
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
                    <!-- Adjusted padding-left to pl-10 -->
                </form>
                <!-- Link to the registration form -->
                <div class="flex items-center w-full md:w-auto">
                    <a href="{{ route('inscription') }}"
                       class="w-full md:w-auto px-4 py-2 border border-gray-900 rounded-lg text-sm flex items-center justify-center hover:bg-gray-900 hover:text-white">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                             xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 4v16m8-8H4"></path>
                        </svg>
                        Ajouter une entreprise
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                @foreach($entreprises as $entreprise)
                    <div class="custom-width bg-white rounded-lg shadow-lg p-4 flex flex-col">
                        <!-- Title -->
                        <div class="flex justify-between">
                            <!-- Company and email -->
                            <div class="flex flex-col">
                                <div class="mb-4">
                                    <p class="text-xl font-semibold">{{ $entreprise->nomEntreprise }}</p>
                                </div>
                                <div class="mb-4">
                                    <p class="text-lg text-gray-600">{{ $entreprise->compte->email }}</p>
                                </div>
                            </div>
                            <!-- QR Code (you can replace with an actual QR code image) -->
                            <div class="flex justify-center mb-4">
                                <div class="w-16 h-16 bg-gray-200 flex items-center justify-center rounded">
                                    <div class="bg-zinc-900 flex justify-center items-center">
                                        <img src="{{ $entreprise->lienQr }}" alt="QR Code">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Phone number -->
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

                        <!-- Buttons -->
                        <div class="flex flex-row-reverse mt-auto pt-4">
                            <form action="{{ route('entreprise.destroy', $entreprise->idCarte) }}" method="POST"
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette entreprise ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-full">Supprimer
                                </button>
                            </form>
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