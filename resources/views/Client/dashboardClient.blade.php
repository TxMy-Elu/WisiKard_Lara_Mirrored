<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Client</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">

<div class="flex flex-col md:flex-row">
    @include('menu.menuClient')
    <div class="flex-1 md:ml-24">
        @if($messageContent != "Aucun message disponible" || empty($messageContent))
            <div class="bg-zinc-400 bg-opacity-45 border border-zinc-400 text-zin-700 px-4 py-3 rounded relative"
                 role="alert">
                <strong class="font-bold">Information :</strong>
                <span class="block sm:inline">{{ $messageContent }}</span>
            </div>
        @endif
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

        <div class="parent  p-4">
            <!-- Carte (div1) -->
            <div class="div1 bg-white rounded-lg shadow-lg p-4 flex flex-col justify-between">
                <div class="flex justify-between">
                    <!-- Title and other information -->
                    <div class="flex flex-col">
                        <div class="mb-4">
                            <p class="text-xl font-semibold">{{ $carte->nomEntreprise }}</p>
                        </div>
                        <div class="mb-4">
                            <p class="text-lg text-gray-600">{{ $carte->compte->email }}</p>
                        </div>
                        <!-- Phone number -->
                        <div class="mb-4">
                            <p class="text-sm text-gray-600">{{ $carte->formattedTel }}</p>
                        </div>
                        <!-- Address -->
                        <div>
                            <p class="text-sm text-gray-600">{{ $carte->ville }}</p>
                        </div>
                        @if($carte->compte->role == 'starter')
                            <div class="pt-4">
                                <div class="bg-blue-500 bg-opacity-65 border-solid border border-blue-500 rounded-full w-28 h-7 flex items-center justify-center">
                                    <p class="text-slate-50 text-base">Starter</p>
                                </div>
                            </div>
                        @elseif($carte->compte->role == 'advanced')
                            <div class="pt-4">
                                <div class="bg-violet-500 bg-opacity-65 border-solid border border-violet-500 rounded-full w-28 h-7 flex items-center justify-center">
                                    <p class="text-slate-50 text-base">Advanced</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Logo -->
                    <div class="justify-center mb-2">
                        <div class="w-28">
                            <img src="{{ '/entreprises/'. $carte->compte->idCompte.'_'.$carte->nomEntreprise.'/logos/logo.jpg' }}"
                                 alt="Logo"
                                 class="w-28">
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex flex-row-reverse mt-auto pt-4">
                    <a href="{{ route('formulaireEntreprise') }}" class="bg-indigo-500 text-white px-4 py-2 rounded-full mr-2">Modifier</a>
                </div>
            </div>

            <!-- QR Code (div2) -->
            <div class="div2 bg-white rounded-lg shadow-lg p-4 flex flex-col">
                <!-- QR Code Image -->
                <div class="mb-4 flex flex-col items-center">
                    <img src="{{ $carte->lienQr }}"
                         alt="QR Code" class="w-full max-w-xs">
                </div>

                <!-- Form for Color Selection -->
                <form action="{{ route('dashboardClientColor') }}" method="POST"
                      class="flex flex-col items-center w-full">
                    @csrf
                    <div class="flex flex-wrap justify-center">
                        <div class="flex flex-col w-full md:w-1/2 mb-4">
                            <label for="color1" class="w-full text-center mb-0.5 font-bold">Pixel</label>
                            <input type="color" name="couleur1" id="color1" class="w-40 mx-auto bg-white"
                                   value="{{ $couleur1 }}">
                        </div>
                        <div class="flex flex-col w-full md:w-1/2 mb-4">
                            <label for="color2" class="w-full text-center mb-0.5 font-bold">Fond</label>
                            <input type="color" name="couleur2" id="color2" class="w-40 mx-auto bg-white"
                                   value="{{ $couleur2 }}">
                        </div>
                    </div>
                    <button type="submit" class="bg-indigo-500 text-white px-4 py-2 rounded-full w-full">
                        Enregistrer
                    </button>
                </form>

                <div class="flex justify-center items-center text-center bg-white rounded-lg shadow-lg mx-auto my-2 w-full p-2 mt-4 border border-gray-1200">
                    <p class="font-bold text-xl">Télécharger</p>
                </div>

                <!-- Download Buttons -->
                <div class="flex justify-center space-x-4 mt-4">
                    <a href="{{ route('downloadQrCodesColor') }}"
                       class="flex items-center justify-center px-4 py-2 bg-indigo-500 text-white rounded-lg text-sm hover:bg-indigo-600">
                        Couleur
                        <!-- Espace entre le texte et le SVG -->
                        <span class="ml-2"></span>
                        <!-- Download svg -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-download">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="7 10 12 15 17 10"></polyline>
                            <line x1="12" y1="15" x2="12" y2="3"></line>
                        </svg>
                    </a>
                    <a href="{{ route('downloadQrCodes') }}"
                       class="flex items-center justify-center px-4 py-2 border border-gray-900 text-gray-900 rounded-lg text-sm hover:bg-gray-100">
                        Noir / Blanc
                        <!-- Espace entre le texte et le SVG -->
                        <span class="ml-2"></span>
                        <!-- Download svg -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-download">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="7 10 12 15 17 10"></polyline>
                            <line x1="12" y1="15" x2="12" y2="3"></line>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Title / Description Form (div3) -->
            <div class="div3 bg-white rounded-lg shadow-lg p-4">
                <form action="{{ route('dashboardClientInfo') }}" method="POST">
                    @csrf
                    <div class="flex flex-col">
                        <label for="title" class="text-lg font-semibold">Titre</label>
                        <input type="text" name="titre" id="title" class="w-full p-2 border border-gray-300 rounded-lg"
                               value="{{ $titre }}">
                    </div>
                    <div class="flex flex-col mt-4">
                        <label for="descirptif" class="text-lg font-semibold">Description</label>
                        <textarea name="descirptif" id="descirptif"
                                  class="w-full p-2 border border-gray-300 rounded-lg">{{ $description }}</textarea>
                    </div>
                    <button type="submit"
                            class="bg-indigo-500 text-white px-4 py-2 rounded-full w-full mt-4">Enregistrer
                    </button>
                </form>
            </div>

            <!-- sheck box template (div4) -->
            <div class="div4 bg-white rounded-lg shadow-lg p-4">
                <form id="templateForm" action="{{ route('updateTemplate') }}" method="POST">
                    @csrf
                    <div class="flex flex-col">
                        <label for="template" class="text-lg font-semibold">Template</label>
                        <!-- radio button x3 (div4) -->
                        <div class="flex justify-center items-center space-x-10 mt-4">
                            <div class="flex flex-col items-center">
                                <input type="radio" name="idTemplate" id="template1" value="1" @if($idTemplate == 1) checked @endif class="mb-2" onchange="submitTemplateForm()">
                                <label for="template1"></label>
                                <!-- template gradient  -->
                                <div class="w-80 h-96 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-lg"></div>
                            </div>
                            <div class="flex flex-col items-center">
                                <input type="radio" name="idTemplate" id="template2" value="2" @if($idTemplate == 2) checked @endif class="mb-2" onchange="submitTemplateForm()">
                                <label for="template2"></label>
                                <!-- template gradient  -->
                                <div class="w-80 h-96 bg-gradient-to-r from-green-500 to-blue-500 rounded-lg"></div>
                            </div>
                            <div class="flex flex-col items-center">
                                <input type="radio" name="idTemplate" id="template3" value="3" @if($idTemplate == 3) checked @endif class="mb-2" onchange="submitTemplateForm()">
                                <label for="template3"></label>
                                <!-- template gradient  -->
                                <div class="w-80 h-96 bg-gradient-to-r from-purple-500 to-red-500 rounded-lg"></div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <script>
                function submitTemplateForm() {
                    document.getElementById('templateForm').submit();
                }
            </script>
        </div>
    </div>
</div>

</body>
</html>
