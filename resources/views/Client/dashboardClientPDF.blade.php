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
            <div class="bg-red-500 col-span-1 row-span-3">
                <!-- Contenu div4 -->
            </div>

            <!-- div5 -->
            <div class="bg-purple-500 col-span-4 row-span-4">
                <!-- Contenu div5 -->
            </div>


        </div>


        {{--


                    <!-- Formulaire YouTube div3 -->
                    <div class="bg-white p-6 rounded-lg shadow-md col-span-2 row-span-1">
                        <form action="{{ route('dashboardClientPDF.uploadYouTubeVideo') }}" method="POST"
                              enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <div class="mb-4">
                                <label for="youtube_url" class="block text-sm font-medium text-gray-700">URL YouTube :</label>
                                <input type="url" id="youtube_url" name="youtube_url" class="mt-1 block w-full"
                                       placeholder="https://www.youtube.com/watch?v=...">
                            </div>
                            <div class="flex p-4">
                                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg">Enregistrer</button>
                            </div>
                        </form>
                        <h2 class="text-xl font-bold mb-2">Vidéos YouTube enregistrées</h2>
                        <!-- Card pour les vidéos YouTube -->
                        @if(!empty($youtubeUrls))
                            <div class="my-4">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    @foreach($youtubeUrls as $index => $youtubeUrl)
                                        <div class="bg-white w-96 p-4 rounded-lg shadow-md relative">
                                            <div class="text-center mb-2">
                                                <h3 class="text-lg font-bold">{{ $youtubeUrl }}</h3>
                                            </div>
                                            <div class="video-container w-80 h-auto">
                                                <iframe width="100%" height="200"
                                                        src="{{ str_replace('watch?v=', 'embed/', $youtubeUrl) }}"
                                                        frameborder="0"
                                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                        allowfullscreen></iframe>
                                            </div>
                                            <form action="{{ route('dashboardClientPDF.deleteVideo', ['index' => $index]) }}"
                                                  method="POST" class="absolute bottom-0 right-2 mb-2"
                                                  id="deleteVideoForm_{{ $index }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="bg-red-500 text-white px-2 py-1 rounded-lg"
                                                        onclick="confirmDelete('deleteVideoForm_{{ $index }}')">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                         xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Formulaire pour l'URL de prise de rendez-vous div4 -->
                    <div class="bg-white p-6 rounded-lg shadow-md col-span-2 row-span-1">
                        <form action="{{ route('dashboardClientPDF.urlsrdv') }}" method="POST" enctype="multipart/form-data"
                              class="space-y-4">
                            @csrf
                            <div class="mb-4">
                                <label for="rdv_url" class="block text-sm font-medium text-gray-700">URL de prise de rendez-vous
                                    :</label>
                                <input type="url" id="rdv_url" name="rdv_url" class="mt-1 block w-full"
                                       placeholder="https://www.exemple.com/rdv...">
                            </div>
                            <div class="flex p-4">
                                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg">Enregistrer</button>
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

                    <!-- Formulaire slider  div5-->
                    <div class="bg-white p-6 rounded-lg shadow-md col-span-4 row-span-3">
                        <form action="{{ route('dashboardClientPDF.uploadSlider') }}" method="POST"
                              enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <div class="mb-4">
                                <label for="slider_image" class="block text-sm font-medium text-gray-700">Sélectionner des
                                    images pour le slider :</label>
                                <input type="file" id="slider_image" name="slider_images[]" class="mt-1 block w-full"
                                       accept=".jpg,.jpeg,.png" multiple>
                                <div class="flex p-4">
                                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg">Enregistrer
                                    </button>
                                </div>
                            </div>
                        </form>
                        <h2 class="text-xl font-bold mb-2">Images pour slider téléchargées</h2>
                        <!-- Card pour le slider -->
                        @if(File::exists(public_path("entreprises/{$idCompte}_{$carte->nomEntreprise}/slider")))
                            <div class="mt-4">
                                <h2 class="text-xl font-bold mb-2">Slider</h2>
                                <div class="bg-white w-80 p-4 rounded-lg shadow-md relative square-card">
                                    <div class="box-border h-auto w-auto p-4 border-4">
                                        <div class="carousel">
                                            @foreach(File::files(public_path("entreprises/{$idCompte}_{$carte->nomEntreprise}/slider")) as $index => $file)
                                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }} relative">
                                                    <img src="{{ asset("entreprises/{$idCompte}_{$carte->nomEntreprise}/slider/" . $file->getFilename()) }}"
                                                         alt="{{ $file->getFilename() }}" class="w-full h-full object-cover">
                                                </div>
                                            @endforeach
                                            <button class="absolute left-2 top-1/2 transform -translate-y-1/2 bg-gray-200 p-2 rounded-full"
                                                    onclick="prevSlide()">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                     xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M15 19l-7-7 7-7"></path>
                                                </svg>
                                            </button>
                                            <button class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-gray-200 p-2 rounded-full"
                                                    onclick="nextSlide()">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                     xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </button>
                                            <div class="box-border h-auto w-auto p-4 border-4">
                                                <button class="absolute bottom-2 -2 bg-green-500 text-white px-2 py-1 rounded-lg"
                                                        onclick="toggleUploadForm()">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                         xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                    </svg>
                                                </button>
                                                <button class="absolute bottom-2 right-2 bg-red-500 text-white px-2 py-1 rounded-lg"
                                                        onclick="openDeleteModal(event)">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                         xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                    </div>

                    <!-- The Modal -->
                    <div id="deleteModal"
                         class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 hidden">
                        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-lg relative">
                            <span class="absolute top-2 right-2 text-3xl text-gray-500 hover:text-gray-700 cursor-pointer"
                                  onclick="closeDeleteModal()">&times;</span>
                            <h2 class="text-xl font-bold mb-4">Sélectionnez les images à supprimer</h2>
                            <form id="deleteForm" action="{{ route('dashboardClientPDF.deleteSliderImage') }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" id="selectedFilenames" name="filenames">
                                <div class="mb-4 flex flex-wrap">
                                    @foreach(File::files(public_path("entreprises/{$idCompte}_{$carte->nomEntreprise}/slider")) as $file)
                                        <div class="flex-shrink-0 mr-4 mb-4 flex flex-col items-center">
                                            <img src="{{ asset("entreprises/{$idCompte}_{$carte->nomEntreprise}/slider/" . $file->getFilename()) }}"
                                                 alt="{{ $file->getFilename() }}" class="w-24 h-24 object-cover mb-2">
                                            <label for="image_{{ $file->getFilename() }}"
                                                   class="text-sm mb-2">{{ $file->getFilename() }}</label>
                                            <input type="checkbox" id="image_{{ $file->getFilename() }}" name="selectedImages[]"
                                                   value="{{ $file->getFilename() }}" onclick="updateSelectedFilenames()"
                                                   class="mr-2">
                                        </div>
                                    @endforeach
                                </div>
                                <button type="button" class="bg-red-500 text-white px-4 py-2 rounded-lg"
                                        onclick="submitDeleteForm()">Supprimer
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- The Modal Rename-->
                    <div id="renameModal" class="modal">
                        <div class="modal-content">
                            <span class="close" onclick="closeRenameModal()">&times;</span>
                            <h2 class="text-xl font-bold mb-2">Renommer le fichier PDF</h2>
                            <form id="renameForm" action="{{ route('dashboardClientPDF.renamePdf') }}" method="POST">
                                @csrf
                                <input type="hidden" id="currentFilename" name="currentFilename">
                                <input type="hidden" id="idCarte" name="idCarte">
                                <div class="mb-4">
                                    <label for="newFilename" class="block text-sm font-medium text-gray-700">Nouveau nom de
                                        fichier :</label>
                                    <input type="text" id="newFilename" name="newFilename" class="mt-1 block w-full" required>
                                </div>
                                <button type="button" class="bg-blue-500 text-white px-4 py-2 rounded-lg"
                                        onclick="submitRenameForm()">Renommer
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        --}}

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const form = document.querySelector('form');
                form.addEventListener('submit', function (event) {
                    const rdvUrlInput = document.getElementById('rdv_url');
                    const rdvUrl = rdvUrlInput.value;
                    const urlPattern = /^(https?:\/\/)/;

                    if (rdvUrl && !urlPattern.test(rdvUrl)) {
                        event.preventDefault();
                        alert('L\'URL de rendez-vous doit commencer par http ou https.');
                        rdvUrlInput.focus();
                    }
                });
            });

            let currentSlide = 0;

            function showSlide(index) {
                const slides = document.querySelectorAll('.carousel-item');
                slides.forEach((slide, i) => {
                    slide.classList.toggle('active', i === index);
                });
            }

            function prevSlide() {
                const slides = document.querySelectorAll('.carousel-item');
                currentSlide = (currentSlide - 1 + slides.length) % slides.length;
                showSlide(currentSlide);
            }

            function nextSlide() {
                const slides = document.querySelectorAll('.carousel-item');
                currentSlide = (currentSlide + 1) % slides.length;
                showSlide(currentSlide);
            }

            function confirmDelete(formId) {
                if (confirm('Êtes-vous sûr de vouloir supprimer cet élément ?')) {
                    document.getElementById(formId).submit();
                }
            }

            function openRenameModal(currentFilename, idCarte) {
                document.getElementById('currentFilename').value = currentFilename;
                document.getElementById('idCarte').value = idCarte;
                document.getElementById('renameModal').style.display = 'block';
            }

            function closeRenameModal() {
                document.getElementById('renameModal').style.display = 'none';
            }

            function submitRenameForm() {
                document.getElementById('renameForm').submit();
            }

            function selectImage(filename) {
                const radio = document.getElementById('image_' + filename);
                radio.checked = true;
                updateSelectedFilenames();
            }

            function updateSelectedFilenames() {
                const checkboxes = document.querySelectorAll('input[name="selectedImages[]"]:checked');
                const filenames = Array.from(checkboxes).map(checkbox => checkbox.value);
                document.getElementById('selectedFilenames').value = JSON.stringify(filenames);
            }

            function submitDeleteForm() {
                document.getElementById('deleteForm').submit();
            }

            function openDeleteModal(event) {
                event.preventDefault();
                document.getElementById('deleteModal').classList.remove('hidden');
            }

            function closeDeleteModal() {
                document.getElementById('deleteModal').classList.add('hidden');
            }

            function toggleUploadForm() {
                const uploadForm = document.getElementById('uploadForm');
                uploadForm.classList.toggle('hidden');
            }
        </script>

</body>
</html>
