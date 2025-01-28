<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Client PDF</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet"/>
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">

<div class="flex flex-col md:flex-row">
    @include('menu.menuClient')

    <div class="flex-1 md:ml-24 p-4">


        <!-- Message Erreur -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                 role="alert">
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

        <div class="grid grid-cols-4 grid-rows-9 gap-1">

            <!-- Formulaire logo div1 -->
            <div class="bg-white rounded-lg shadow-md col-span-2 row-span-2 p-6 h-96 flex flex-col">
                <h2 class="text-3xl font-semibold text-gray-800 mb-4 text-center">Logo</h2>
                <div class="flex flex-wrap md:flex-nowrap justify-between items-center space-y-6 md:space-y-0 md:space-x-12 grow">
                    <!-- Formulaire d'upload -->
                    <form action="{{ route('dashboardClientPDF.uploadLogo') }}" method="POST"
                          enctype="multipart/form-data"
                          class="space-y-4 w-full md:w-1/2 flex flex-col justify-between">
                        @csrf
                        <div>
                            <label for="logo" class="block text-sm font-medium text-gray-600 mb-2">
                                Sélectionner un logo :
                            </label>
                            <input type="file" id="logo" name="logo"
                                   class="block w-full p-3 border border-gray-300 rounded-lg focus:ring focus:ring-blue-400 focus:outline-none text-sm"
                                   accept=".jpg,.jpeg,.png,.svg">
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                    class="w-full md:w-auto px-6 py-2 bg-indigo-500 text-white text-sm font-medium rounded-lg shadow-md transform transition-transform hover:scale-105 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                Enregistrer
                            </button>
                        </div>
                    </form>

                    <!-- Affichage du logo -->
                    <div class="w-full md:w-1/2 flex flex-col items-center justify-center">
                        <img class="w-32 h-32 object-contain border border-gray-200 rounded-md shadow-lg"
                             src="{{ $logoPath ? $logoPath : asset('images/default-logo.png') }}"
                             alt="Logo">
                        <p class="text-sm text-gray-500 mt-2">Aperçu du logo actuel</p>
                    </div>
                </div>
            </div>

            <!-- Formulaire PDF div2 -->
            <div class="bg-white rounded-lg shadow-md col-span-2 row-span-2 p-6 h-96 flex flex-col">
                <h2 class="text-3xl font-semibold text-gray-800 mb-4 text-center">PDF</h2>
                <div class="flex flex-wrap md:flex-nowrap justify-between items-center space-y-6 md:space-y-0 md:space-x-12 grow">
                    <!-- Formulaire d'upload -->
                    <form action="{{ route('dashboardClientPDF.upload') }}" method="POST" enctype="multipart/form-data"
                          class="space-y-4 w-full md:w-1/2 flex flex-col justify-between">
                        @csrf
                        <div>
                            <label for="pdf" class="block text-sm font-medium text-gray-600 mb-2">
                                Sélectionner un PDF :
                            </label>
                            <input type="file" id="pdf" name="pdf"
                                   class="block w-full p-3 border border-gray-300 rounded-lg focus:ring focus:ring-blue-400 focus:outline-none text-sm"
                                   accept=".pdf">
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                    class="w-full md:w-auto px-6 py-2 bg-indigo-500 text-white text-sm font-medium rounded-lg shadow-md transform transition-transform hover:scale-105 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                Enregistrer
                            </button>
                        </div>
                    </form>

                    <!-- Affichage du PDF -->
                    <div class="w-full md:w-1/2 flex flex-col items-center justify-center">
                        @if(!empty($carte->pdf) && file_exists(public_path($carte->pdf)))
                            <a href="{{ asset($carte->pdf) }}" target="_blank" class="text-blue-600 underline">
                                Télécharger le PDF
                            </a>
                        @else
                            <p class="text-gray-500 italic border-2 p-10">Aucun PDF disponible.</p>
                        @endif
                        <p class="text-sm text-gray-500 mt-2">Aperçu du PDF actuel</p>
                    </div>
                </div>
            </div>

            <!-- Formulaire YouTube div3 -->
            <div class="bg-white rounded-lg shadow-md col-span-3 row-span-3 p-6 h-auto flex flex-col">
                <h2 class="text-3xl font-semibold text-gray-800 mb-4 text-center">Vidéos YouTube</h2>
                <form action="{{ route('dashboardClientPDF.uploadYouTubeVideo') }}" method="POST"
                      enctype="multipart/form-data"
                      class="space-y-4">
                    @csrf
                    <div>
                        <label for="youtube_url" class="block text-sm font-medium text-gray-600 mb-2">
                            URL YouTube :
                        </label>
                        <input type="url" id="youtube_url" name="youtube_url"
                               class="block w-full p-3 border border-gray-300 rounded-lg focus:ring focus:ring-blue-400 focus:outline-none text-sm"
                               placeholder="https://www.youtube.com/watch?v=...">
                    </div>
                    <div class="flex justify-end">
                        <button type="submit"
                                class="w-full md:w-auto px-6 py-2 bg-indigo-500 text-white text-sm font-medium rounded-lg shadow-md transform transition-transform hover:scale-105 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Enregistrer
                        </button>
                    </div>
                </form>
                <h2 class="text-xl font-bold mb-2">Vidéos YouTube enregistrées</h2>
                <!-- Card pour les vidéos YouTube -->
                @if(!empty($youtubeUrls))
                    <div class="my-4 grow">
                        <div class="flex flex-nowrap gap-4 overflow-x-auto">
                            @foreach($youtubeUrls as $index => $youtubeUrl)
                                <div class="bg-white p-6 rounded-lg shadow-md flex flex-col items-center h-auto w-[300px]">
                                    <!-- Conteneur de l'iframe (ajusté à la carte) -->
                                    <div class="w-full flex justify-center items-center">
                                        <iframe
                                                src="{{ str_replace('watch?v=', 'embed/', $youtubeUrl) }}"
                                                frameborder="0"
                                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                allowfullscreen>
                                        </iframe>
                                    </div>

                                    <!-- Formulaire de suppression -->
                                    <form action="{{ route('dashboardClientPDF.deleteVideo', ['index' => $index]) }}"
                                          method="POST" class="mt-4 w-full flex justify-end">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg">
                                            Supprimer
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <p class="text-gray-500 italic text-center border-2 p-32">Aucune vidéo enregistrée.</p>
                @endif
            </div>

            <!-- div4 -->
            <div class="bg-white rounded-lg shadow-md col-span-1 row-span-3 p-6">
                <h2 class="text-3xl font-semibold text-gray-800 mb-4 text-center">URL de prise de rendez-vous</h2>
                <form action="{{ route('dashboardClientPDF.urlsrdv') }}" method="POST" enctype="multipart/form-data"
                      class="space-y-4">
                    @csrf
                    <div>
                        <label for="rdv_url" class="block text-sm font-medium text-gray-600 mb-2">
                            URL de prise de rendez-vous :
                        </label>
                        <input type="url" id="rdv_url" name="rdv_url"
                               class="block w-full p-3 border border-gray-300 rounded-lg focus:ring focus:ring-blue-400 focus:outline-none text-sm"
                               placeholder="https://www.exemple.com/rdv...">
                    </div>
                    <div class="flex justify-end">
                        <button type="submit"
                                class="w-full md:w-auto px-6 py-2 bg-indigo-500 text-white text-sm font-medium rounded-lg shadow-md transform transition-transform hover:scale-105 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Enregistrer
                        </button>
                    </div>
                </form>
                <h2 class="text-xl font-bold mb-2">Lien de RDV enregistré</h2>
                <!-- Afficher l'URL de RDV sous le bouton "Enregistrer" -->
                @if($carte->LienCommande)
                    <div class="mt-4 w-auto h-auto">
                        <div class="bg-white p-4 rounded-lg shadow-md relative w-auto h-auto">
                            <div class="video-container w-auto h-auto">
                                <a href="{{ $carte->LienCommande }}" class="text-blue-500 underline"
                                   target="_blank">{{ $carte->LienCommande }}</a>
                            </div>
                        </div>
                    </div>
                @endif


            </div>

            <!-- div5 galerie photo -->
            <div class="bg-white rounded-lg shadow-md col-span-4 row-span-4 p-6">

                <h2 class="text-3xl font-semibold text-gray-800 mb-4 text-center">Galerie photo</h2>
                <div class="flex flex-wrap md:flex-nowrap justify-between items-center space-y-6 md:space-y-0 md:space-x-12 grow">
                    <!-- Formulaire d'upload -->
                    <form action="{{ route('dashboardClientPDF.uploadSlider') }}" method="POST"
                          enctype="multipart/form-data"
                          class="space-y-4 w-full md:w-1/2 flex flex-col justify-between">
                        @csrf
                        @method('POST')
                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-600 mb-2">
                                Sélectionner une image :
                            </label>
                            <input type="file" id="image" name="image"
                                   class="block w-full p-3 border border-gray-300 rounded-lg focus:ring focus:ring-blue-400 focus:outline-none text-sm"
                                   accept=".jpg,.jpeg,.png">
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                    class="w-full md:w-auto px-6 py-2 bg-indigo-500 text-white text-sm font-medium rounded-lg shadow-md transform transition-transform hover:scale-105 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                Enregistrer
                            </button>
                        </div>
                    </form>

                    <!-- Affichage des images route public/entreprises/1_lidl/slider/...-->
                    <div class="w-full md:w-1/2 flex flex-col items-center justify-center">
                        @php
                            $sliderDirectory = public_path('entreprises/'.$carte->idCompte.'_'.$carte->nomEntreprise.'/slider');
                            $sliderImages = file_exists($sliderDirectory) ? array_diff(scandir($sliderDirectory), array('.', '..')) : [];
                        @endphp

                        @if(!empty($sliderImages))
                            <!-- Galerie photo -->
                            <div class="flex flex-wrap gap-4">
                                @foreach($sliderImages as $image)
                                    <div class="relative">
                                        <!-- Miniature -->
                                        <img src="{{ asset('entreprises/'.$carte->idCompte.'_'.$carte->nomEntreprise.'/slider/'. $image) }}"
                                             alt="Image"
                                             class="w-32 h-32 object-cover cursor-pointer hover:opacity-80"
                                             onclick="openModal('{{ asset('entreprises/'.$carte->idCompte.'_'.$carte->nomEntreprise.'/slider/'. $image) }}')">

                                        <!-- Formulaire de suppression -->
                                        <form action="{{ route('dashboardClientPDF.deleteSliderImage') }}" method="POST"
                                              class="absolute top-2 right-2">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="filename" value="{{ $image }}">
                                            <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded-lg">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                     viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                          d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-9a1 1 0 00-2 0v5a1 1 0 102 0v-5zm-2-3a1 1 0 011-1h2a1 1 0 110 2h-2a1 1 0 01-1-1z"
                                                          clip-rule="evenodd"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Modal pour afficher les images en grand -->
                            <div id="imageModal"
                                 class="fixed inset-0 flex items-center justify-center bg-zinc-950/99 hidden z-50">
                                <div class="relative">
                                    <button onclick="closeModal()"
                                            class="absolute top-4 right-4 text-white text-3xl font-bold">&times;
                                    </button>
                                    <img id="modalImage" src="" alt="Agrandissement de l'image"
                                         class="object-contain w-96 h-[90%] rounded-lg">
                                </div>
                            </div>
                        @else
                            <p class="text-gray-500 italic border-2 p-10">Aucune image disponible.</p>
                        @endif
                    </div>

                    <script>
                        function openModal(imageUrl) {
                            const modal = document.getElementById('imageModal');
                            const modalImage = document.getElementById('modalImage');

                            modalImage.src = imageUrl; // Met à jour l'URL de l'image dans le modal
                            modal.classList.remove('hidden'); // Affiche le modal
                        }

                        function closeModal() {
                            const modal = document.getElementById('imageModal');
                            modal.classList.add('hidden'); // Cache le modal
                        }

                    </script>


                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
