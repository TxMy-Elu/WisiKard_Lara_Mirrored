<!-- resources/views/client/dashboardClient.blade.php -->
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
    <div class="flex-1 md:ml-24 p-4">
        @if($messageContent != "Aucun message disponible" || empty($messageContent))
            <div class="bg-zinc-400 bg-opacity-45 border border-zinc-400 text-zin-700 px-4 py-3 rounded relative"
                 role="alert">
                <strong class="font-bold">Information :</strong>
                <span class="block sm:inline">{{ $messageContent }}</span>
            </div>
        @endif
        {{--

                <form action="{{ route('dashboardClientColor') }}" method="POST" class="flex flex-col md:flex-row items-center">
                    @csrf
                    <div class="flex flex-col md:flex-row items-center w-full md:w-1/2">
                        <label for="color1" class="w-full md:w-1/2">Couleur 1 :</label>
                        <input type="color" name="couleur1" id="color1" class="w-full md:w-1/2" value="{{ $couleur1 }}">
                    </div>
                    <div class="flex flex-col md:flex-row items-center w-full md:w-1/2">
                        <label for="color2" class="w-full md:w-1/2">Couleur 2 :</label>
                        <input type="color" name="couleur2" id="color2" class="w-full md:w-1/2" value="{{ $couleur2 }}">
                    </div>
                    <button type="submit"
                            class="w-full md:w-auto px-4 py-2 border border-gray-900 rounded-lg text-sm flex items-center justify-center hover:bg-gray-900 hover:text-white mt-4 md:mt-0">
                        Enregistrer
                    </button>
                </form>


                <a href="{{ route('downloadQrCodes') }}"
                   class="w-full md:w-auto px-4 py-2 border border-gray-900 rounded-lg text-sm flex items-center justify-center hover:bg-gray-900 hover:text-white mt-4 md:mt-0">
                    Télécharger les QR Codes (noir et blanc)
                </a>


                <a href="{{ route('downloadQrCodesColor') }}"
                   class="w-full md:w-auto px-4 py-2 border border-gray-900 rounded-lg text-sm flex items-center justify-center hover:bg-gray-900 hover:text-white mt-4 md:mt-0">
                    Télécharger les QR Codes (couleur)
                </a>
                    --}}

            <div class="flex flex-wrap -mx-4">
                <div class="w-full sm:w-1/2 md:w-1/2 lg:w-1/3 xl:w-1/4 px-4 mb-4">
                    <div class="w-[600px] h-[350px] bg-white rounded-lg shadow-lg p-4 flex flex-col justify-between">
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
                            <a href="#" class="bg-indigo-500 text-white px-4 py-2 rounded-full mr-2">Modifier</a>
                        </div>
                    </div>
                </div>

                <!-- QR Code -->
                <div class="flex flex-col w-[400px] bg-white rounded-lg shadow-lg mx-auto p-4">
                    <!-- QR Code Image -->
                    <div class="mb-4 flex flex-col items-center">
                        <img src="{{ '/entreprises/'. $carte->compte->idCompte.'_'.$carte->nomEntreprise.'/QR_Codes/QR_Code.svg' }}" alt="QR Code" class="w-full max-w-xs">
                    </div>

                    <!-- Form for Color Selection -->
                    <form action="{{ route('dashboardClientColor') }}" method="POST" class="flex flex-col items-center w-full">
                        @csrf
                        <div class="flex">
                        <div class="flex flex-col w-full mb-4">
                            <label for="color1" class="w-full text-center mb-2">Pixel :</label>
                            <input type="color" name="couleur1" id="color1" class="w-40 mx-auto" value="{{ $couleur1 }}">
                        </div>
                        <div class="flex flex-col w-full mb-4">
                            <label for="color2" class="w-full text-center mb-2">Fond :</label>
                            <input type="color" name="couleur2" id="color2" class="w-40 mx-auto" value="{{ $couleur2 }}">
                        </div>
                        </div>
                        <button type="submit" class="w-full px-4 py-2 border border-gray-900 rounded-lg text-sm flex items-center justify-center hover:bg-gray-900 hover:text-white">
                            Enregistrer
                        </button>
                    </form>

                    <!-- Download Buttons -->
                    <div class="flex flex-col items-center w-full mt-4 space-y-4">
                        <a href="{{ route('downloadQrCodes') }}" class="w-full px-4 py-2 border border-gray-900 rounded-lg text-sm flex items-center justify-center hover:bg-gray-900 hover:text-white">
                            Télécharger les QR Codes (noir et blanc)
                        </a>
                        <a href="{{ route('downloadQrCodesColor') }}" class="w-full px-4 py-2 border border-gray-900 rounded-lg text-sm flex items-center justify-center hover:bg-gray-900 hover:text-white">
                            Télécharger les QR Codes (couleur)
                        </a>
                    </div>
                </div>



            </div>



    </div>
</div>

</body>
</html>
