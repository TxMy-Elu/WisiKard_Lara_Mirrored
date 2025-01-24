<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Client PDF</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <style>
        .carousel-item {
            display: none;
        }
        .carousel-item.active {
            display: block;
        }
        .square-card {
            width: 300px;
            height: 300px;
            position: relative;
        }
        .square-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .delete-button {
            position: absolute;
            bottom: 10px;
            right: 10px;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">

<div class="flex flex-col md:flex-row">
    @include('menu.menuClient')

    <div class="flex-1 md:ml-24 p-4">
        <h1 class="text-2xl font-bold mb-4">Télécharger des fichiers</h1>

        <!-- Message Erreur -->
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

        <!-- Formulaire logo -->
        <div class="bg-white w-3/6 p-6 rounded-lg shadow-md mb-6">
            <form action="{{ route('dashboardClientPDF.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div class="mb-4">
                    <label for="logo" class="block text-sm font-medium text-gray-700">Sélectionner un logo :</label>
                    <input type="file" id="logo" name="logo" class="mt-1 block w-full" accept=".jpg,.jpeg,.png">
                    <div class="flex p-4">
                       <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg">Enregistrer</button>
                    </div>
                </div>
            </form>
            <h2 class="text-xl font-bold mb-2">Logo</h2>
            <!-- Card pour le logo -->
            @if(File::exists(public_path("entreprises/{$idCompte}_{$carte->nomEntreprise}/logos/logo.jpg")) ||
               File::exists(public_path("entreprises/{$idCompte}_{$carte->nomEntreprise}/logos/logo.jpeg")) ||
               File::exists(public_path("entreprises/{$idCompte}_{$carte->nomEntreprise}/logos/logo.png")))
                <div class="mt-4">
                    <div class="grid grid-cols-1 w-96 ">
                        <div class="bg-white p-8 rounded-lg shadow-md relative">
                            <div class="text-center mb-2">
                                @if(File::exists(public_path("entreprises/{$idCompte}_{$carte->nomEntreprise}/logos/logo.jpg")))
                                    <img src="{{ asset("entreprises/{$idCompte}_{$carte->nomEntreprise}/logos/logo.jpg") }}" alt="Logo" class="w-auto h-auto mx-auto max-w-xs max-h-xs">
                                @elseif(File::exists(public_path("entreprises/{$idCompte}_{$carte->nomEntreprise}/logos/logo.jpeg")))
                                    <img src="{{ asset("entreprises/{$idCompte}_{$carte->nomEntreprise}/logos/logo.jpeg") }}" alt="Logo" class="w-auto h-auto mx-auto max-w-xs max-h-xs">
                                @elseif(File::exists(public_path("entreprises/{$idCompte}_{$carte->nomEntreprise}/logos/logo.png")))
                                    <img src="{{ asset("entreprises/{$idCompte}_{$carte->nomEntreprise}/logos/logo.png") }}" alt="Logo" class="w-auto h-auto mx-auto max-w-xs max-h-xs">
                                @endif
                            </div>
                            <form action="{{ route('dashboardClientPDF.deleteLogo') }}" method="POST" class="absolute bottom-2 right-2" id="deleteLogoForm">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="bg-red-500 text-white px-2 py-1 rounded-lg" onclick="confirmDelete('deleteLogoForm')">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Formulaire IMG -->
        <div class="bg-white w-3/6 p-6 rounded-lg shadow-md mb-6">
            <form action="{{ route('dashboardClientPDF.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div class="mb-4">
                    <label for="file" class="block text-sm font-medium text-gray-700">Sélectionner une image :</label>
                    <input type="file" id="file" name="file" class="mt-1 block w-full" accept=".mp4,.jpg,.jpeg,.png">
                    <div class="flex p-4">
                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg">Enregistrer</button>
                    </div>
                </div>
            </form>
            <h2 class="text-xl font-bold mb-2">Images téléchargées</h2>
            <!-- Card pour les images -->
            @if(File::exists(public_path("entreprises/{$idCompte}_{$carte->nomEntreprise}/images")))
                <div class="mt-4">
                    <div class="grid grid-cols-1 md:grid-cols-3">
                        @foreach(File::files(public_path("entreprises/{$idCompte}_{$carte->nomEntreprise}/images")) as $file)
                            <div class="bg-white w-max h-max p-4 rounded-lg shadow-md relative">
                                <div class="text-center mb-3">
                                    <h3 class="text-lg font-bold">{{ $file->getFilename() }}</h3>
                                </div>
                                <img src="{{ asset("entreprises/{$idCompte}_{$carte->nomEntreprise}/images/" . $file->getFilename()) }}" alt="{{ $file->getFilename() }}" class="w-auto h-auto mx-auto max-w-xs max-h-xs">
                                <form action="{{ route('dashboardClientPDF.deleteImage', ['filename' => $file->getFilename()]) }}" method="POST" class="mt-2 absolute bottom-2 right-2" id="deleteImageForm_{{ $file->getFilename() }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="bg-red-500 text-white px-2 py-1 rounded-lg" onclick="confirmDelete('deleteImageForm_{{ $file->getFilename() }}')">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

    <!-- Formulaire PDF -->
           <div class="bg-white p-6 w-3/6 rounded-lg shadow-md mb-6">
               <form action="{{ route('dashboardClientPDF.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                   @csrf
                   <div class="mb-4">
                       <label for="file" class="block text-sm font-medium text-gray-700">Sélectionner un fichier :</label>
                       <input type="file" id="file" name="file" class="mt-1 block w-full" accept=".pdf">
                       <div class="flex p-4">
                           <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg">Enregistrer</button>
                       </div>
                    </div>
               </form>
               <h2 class="text-xl font-bold mb-2">Fichiers PDF téléchargés</h2>
               <!-- Card pour les PDF -->
              @if(File::exists(public_path("entreprises/{$idCompte}_{$carte->nomEntreprise}/pdf")))
                  <div class="mt-4">
                      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                          @foreach(File::files(public_path("entreprises/{$idCompte}_{$carte->nomEntreprise}/pdf")) as $file)
                              <div class="bg-white w-max p-4 rounded-lg shadow-md relative">
                                  <div class="text-center mb-2">
                                      <h3 class="text-lg font-bold">{{ $file->getFilename() }}</h3>
                                  </div>
                                  <form action="{{ route('dashboardClientPDF.deletePDF' , ['filename' => $file->getFilename()]) }}" method="POST" class="absolute bottom-2 right-2" id="deletePDFForm">
                                      @csrf
                                      @method('DELETE')
                                      <button type="button" class="bg-red-500 text-white px-2 py-1 rounded-lg" onclick="confirmDelete('deletePDFForm')">
                                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                          </svg>
                                      </button>
                                  </form>
                                  <div class="w-96 h-80 overflow-auto border rounded-lg" onclick="openRenameModal('{{ $file->getFilename() }}', {{ $carte->idCarte }})">
                                      <iframe src="{{ asset("entreprises/{$idCompte}_{$carte->nomEntreprise}/pdf/" . $file->getFilename()) }}" width="100%" height="100%" style="border: none;"></iframe>
                                  </div>
                                  <button class="bg-blue-500 text-white px-2 py-1 rounded-lg mt-2" onclick="openRenameModal('{{ $file->getFilename() }}', {{ $carte->idCarte }})">Renommer</button>
                              </div>
                          @endforeach
                      </div>
                  </div>
              @endif
           </div>

    <!-- Formulaire YouTube -->
     <div class="bg-white p-6 w-3/6 rounded-lg shadow-md mb-6">
         <form action="{{ route('dashboardClientPDF.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
             @csrf
             <div class="mb-4">
                 <label for="youtube_url" class="block text-sm font-medium text-gray-700">URL YouTube :</label>
                 <input type="url" id="youtube_url" name="youtube_url" class="mt-1 block w-full" placeholder="https://www.youtube.com/watch?v=...">
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
                             <div class="video-container w-80 h-auto ">
                                 <iframe width="100%" height="200" src="{{ str_replace('watch?v=', 'embed/', $youtubeUrl) }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                             </div>
                             <form action="{{ route('dashboardClientPDF.deleteVideo', ['index' => $index]) }}" method="POST" class="absolute bottom-0 right-2 mb-2" id="deleteVideoForm_{{ $index }}">
                                 @csrf
                                 @method('DELETE')
                                 <button type="button" class="bg-red-500 text-white px-2 py-1 rounded-lg" onclick="confirmDelete('deleteVideoForm_{{ $index }}')">
                                     <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                     </svg>
                                 </button>
                             </form>
                         </div>
                     @endforeach
                 </div>
             </div>
         @endif
     </div>
<div class="bg-white p-6 w-3/6 rounded-lg shadow-md mb-6">
    <form action="{{ route('dashboardClientPDF.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div class="mb-4">
            <label for="rdv_url" class="block text-sm font-medium text-gray-700">URL de prise de rendez-vous :</label>
            <input type="url" id="rdv_url" name="rdv_url" class="mt-1 block w-full" placeholder="https://www.exemple.com/rdv...">
        </div>
        <div class="flex p-4">
            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg">Enregistrer</button>
        </div>
    </form>
    <h2 class="text-xl font-bold mb-2">Lien de RDV enregistré</h2>
    <!-- Card pour l'URL de RDV -->
    @if($carte->lienCommande)
        <div class="mt-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white w-96 p-4 rounded-lg shadow-md relative">
                    <div class="text-center mb-2">
                        <h3 class="text-lg font-bold">{{ $carte->lienCommande }}</h3>
                    </div>
                    <div class="video-container w-80 h-auto ">
                        <iframe width="100%" height="200" src="{{ $carte->lienCommande }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Formulaire slider -->
<div class="bg-white p-6 w-auto h-max rounded-lg shadow-md mb-6">
    <form action="{{ route('dashboardClientPDF.uploadSlider') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div class="mb-4">
            <label for="slider_image" class="block text-sm font-medium text-gray-700">Sélectionner des images pour le slider :</label>
            <input type="file" id="slider_image" name="slider_images[]" class="mt-1 block w-full" accept=".jpg,.jpeg,.png" multiple>
            <div class="flex p-4">
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg">Enregistrer</button>
             </div>
    </form>
    <h2 class="text-xl font-bold mb-2">Images pour slider téléchargées</h2>
    <!-- Card pour le slider -->
    @if(File::exists(public_path("entreprises/{$idCompte}_{$carte->nomEntreprise}/slider")))
        <div class="mt-4">
            <h2 class="text-xl font-bold mb-2">Slider</h2>
            <div class="bg-white w-80 p-4 rounded-lg shadow-md relative square-card">
                <div class="box-border h-auto w-auto p-4 border-4 ">
                    <div class="carousel">
                        @foreach(File::files(public_path("entreprises/{$idCompte}_{$carte->nomEntreprise}/slider")) as $index => $file)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }} relative">
                                <img src="{{ asset("entreprises/{$idCompte}_{$carte->nomEntreprise}/slider/" . $file->getFilename()) }}" alt="{{ $file->getFilename() }}" class="w-full h-full object-cover">
                            </div>
                        @endforeach
                    <button class="absolute left-2 top-1/2 transform -translate-y-1/2 bg-gray-200 p-2 rounded-full" onclick="prevSlide()">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    <button class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-gray-200 p-2 rounded-full" onclick="nextSlide()">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                    <div class="box-border h-auto w-auto p-4 border-4 ">
                        <button class="absolute bottom-2 -2 bg-green-500 text-white px-2 py-1 rounded-lg" onclick="toggleUploadForm()">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </button>
                      <button class="absolute bottom-2 right-2 bg-red-500 text-white px-2 py-1 rounded-lg" onclick="openDeleteModal(event)">
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                          </svg>
                      </button>

                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
<!-- The Modal -->
<div id="deleteModal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-lg relative">
        <span class="absolute top-2 right-2 text-3xl text-gray-500 hover:text-gray-700 cursor-pointer" onclick="closeDeleteModal()">&times;</span>
        <h2 class="text-xl font-bold mb-4">Sélectionnez les images à supprimer</h2>
        <form id="deleteForm" action="{{ route('dashboardClientPDF.deleteSliderImage') }}" method="POST">
            @csrf
            @method('DELETE')
            <input type="hidden" id="selectedFilenames" name="filenames">
            <div class="mb-4 flex flex-wrap">
                @foreach(File::files(public_path("entreprises/{$idCompte}_{$carte->nomEntreprise}/slider")) as $file)
                    <div class="flex-shrink-0 mr-4 mb-4 flex flex-col items-center">
                        <img src="{{ asset("entreprises/{$idCompte}_{$carte->nomEntreprise}/slider/" . $file->getFilename()) }}" alt="{{ $file->getFilename() }}" class="w-24 h-24 object-cover mb-2">
                        <label for="image_{{ $file->getFilename() }}" class="text-sm mb-2">{{ $file->getFilename() }}</label>
                        <input type="checkbox" id="image_{{ $file->getFilename() }}" name="selectedImages[]" value="{{ $file->getFilename() }}" onclick="updateSelectedFilenames()" class="mr-2">
                    </div>
                @endforeach
            </div>
            <button type="button" class="bg-red-500 text-white px-4 py-2 rounded-lg" onclick="submitDeleteForm()">Supprimer</button>
        </form>
    </div>
</div>

            <!-- Formulaire de téléchargement d'image pour le slider -->
            <div id="uploadForm" class="hidden mt-4 bg-white p-4 rounded-lg shadow-md">
                <form action="{{ route('dashboardClientPDF.uploadSlider') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div class="mb-4">
                        <label for="slider_image" class="block text-sm font-medium text-gray-700">Sélectionner une image pour le slider :</label>
                        <input type="file" id="slider_image" name="slider_images[]" class="mt-1 block w-full" accept=".jpg,.jpeg,.png">
                    </div>
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg">Enregistrer</button>
                </form>
            </div>

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
                        <label for="newFilename" class="block text-sm font-medium text-gray-700">Nouveau nom de fichier :</label>
                        <input type="text" id="newFilename" name="newFilename" class="mt-1 block w-full" required>
                    </div>
                    <button type="button" class="bg-blue-500 text-white px-4 py-2 rounded-lg" onclick="submitRenameForm()">Renommer</button>
                </form>
            </div>
        </div>

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
           event.preventDefault(); // Empêcher le comportement par défaut du bouton
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
