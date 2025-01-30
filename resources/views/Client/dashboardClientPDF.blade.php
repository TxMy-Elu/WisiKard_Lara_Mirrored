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

        <div class="grid grid-cols-4 grid-rows-8 gap-5">

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
                        @if (!empty($logoPath))
                            <img class="w-32 h-32 object-contain border border-gray-200 rounded-md shadow-lg"
                                 src="{{ $logoPath }}"
                                 alt="Logo">
                        @else
                            <p class="text-gray-500 italic border-2 p-10">Aucun logo disponible</p>
                        @endif
                        <p class="text-sm text-gray-500 mt-2">Aperçu du logo actuel</p>
                    </div>
                </div>
                @if($carte->logo)
                    <!-- delete du logo -->
                    <form action="{{ route('dashboardClientPDF.deleteLogo') }}" method="POST"
                          class="mt-4 w-full flex justify-end">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg">
                            Supprimer
                        </button>
                    </form>
                @endif
            </div>

            <!-- Formulaire PDF div2 -->
            <div class="bg-white rounded-lg shadow-md col-span-2 row-span-2 p-6 h-96 flex flex-col">
                <h2 class="text-3xl font-semibold text-gray-800 mb-4 text-center">PDF</h2>
                <div class="flex flex-wrap md:flex-nowrap justify-between items-center grow">
                    <!-- Formulaire principal -->
                    <form id="uploadForm" action="{{ route('dashboardClientPDF.uploadPdf') }}"
                          method="POST"
                          enctype="multipart/form-data" class="space-y-4 w-full md:w-1/2 flex flex-col justify-between">
                        @csrf <!-- Obligatoire pour sécuriser la requête -->

                        <div>
                            <label for="pdf" class="block text-sm font-medium text-gray-600 mb-2">
                                Sélectionner un PDF :
                            </label>
                            <input type="file" id="pdf" name="pdf"
                                   class="block w-full p-3 border border-gray-300 rounded-lg focus:ring focus:ring-blue-400 focus:outline-none text-sm"
                                   accept=".pdf" required>
                        </div>

                        <div class="flex justify-end">
                            <button type="button"
                                    onclick="openModalPdf()"
                                    class="w-full md:w-auto px-6 py-2 bg-indigo-500 text-white text-sm font-medium rounded-lg shadow-md transform transition-transform hover:scale-105 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                Enregistrer
                            </button>
                        </div>

                    </form>

                    <!-- Modale pour demander le nouveau nom -->
                    <div id="nameModal"
                         class="hidden fixed inset-0 flex items-center justify-center bg-gray-900/80 z-50">
                        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-sm">
                            <h2 class="text-lg font-medium text-gray-800 mb-4">Renommez le fichier</h2>
                            <p class="text-sm text-gray-600">Entrez un nouveau nom pour le fichier PDF :</p>
                            <input type="text" id="newName" name="new_name"
                                   class="block w-full p-3 border border-gray-300 rounded-lg focus:ring focus:ring-blue-400 focus:outline-none text-sm mt-4"
                                   placeholder="Exemple : Mon_Nouveau_Fichier" required>
                            <div class="flex justify-end space-x-3 mt-4">
                                <button type="button" onclick="closeModalPdf()"
                                        class="px-4 py-2 bg-gray-300 text-gray-800 text-sm rounded-lg">
                                    Annuler
                                </button>
                                <button type="button" onclick="saveAndSubmit()"
                                        class="px-4 py-2 bg-indigo-500 text-white text-sm rounded-lg">
                                    Enregistrer
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Affichage du PDF -->
                    <div class="w-full md:w-1/2 flex flex-col items-center justify-center">
                        @if(!empty($carte->pdf) && file_exists(public_path($carte->pdf)))
                            <iframe src="{{ asset($carte->pdf) }}" class="w-86 h-40" frameborder="0"></iframe>
                        @else
                            <p class="text-gray-500 italic border-2 p-10">Aucun PDF disponible.</p>
                        @endif
                        <p class="text-sm text-gray-500 mt-2">Aperçu du PDF actuel</p>
                    </div>
                </div>

                <!-- delete du PDF -->

                @if($carte->pdf)
                    <form action="{{ route('dashboardClientPDF.deletePdf') }}" method="POST"
                          class="mt-4 w-full flex justify-end">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="filename" value="{{ $carte->nomBtnPdf }}">
                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg">
                            Supprimer
                        </button>
                    </form>
                @endif


            </div>

            <!-- Formulaire YouTube div3 -->
            <div class="relative  col-span-2 row-span-3">
                @if($compte->role == 'starter')
                    <!-- Message abonnement, centré au-dessus du blur -->
                    <div class="relative z-50 flex flex-col items-center justify-center">
                        <a href="https://wisikard.fr/produit/mise-a-niveau-wisikard-advanced/"
                           target="_blank"
                           class="bg-red-500 border-solid border border-red-500 hover:bg-red-900 hover:border-red-900 rounded-xl w-48 h-7 flex items-center justify-center space-x-4">
                            <p class="text-white text-base">Mettre à niveau</p>
                            <!-- svg cursor mouse -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                 viewBox="0 0 24 24"
                                 fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="feather feather-mouse-pointer">
                                <path d="M3 3l7.07 16.97 2.51-7.39 7.39-2.51L3 3z"></path>
                                <path d="M13 13l6 6"></path>
                            </svg>
                        </a>
                    </div>
                @endif
                <div class="bg-white rounded-lg shadow-md p-6 h-auto flex flex-col
            @if($compte->role == 'starter') blur-sm pointer-events-none opacity-50 @endif">
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
                                    <div class="bg-gray-100 p-6 rounded-md shadow-lg mb-2 flex flex-col items-center h-auto w-[300px] ">
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
            </div>

            <!-- div 6 -->
            <!-- input lien avis google -->
            <div class="bg-white rounded-lg shadow-md col-span-1 row-span-3 p-6">
                <h2 class="text-3xl font-semibold text-gray-800 mb-4 text-center">Lien Avis Google</h2>
                <form action="{{ route('dashboardClientPDF.uploadAvis') }}" method="POST" enctype="multipart/form-data"
                      class="space-y-4">
                    @csrf
                    <div>
                        <label for="avis_google" class="block text-sm font-medium text-gray-600 mb-2">
                            URL de l'avis Google :
                        </label>
                        <input type="url" id="avis_google" name="avis_google"
                               class="block w-full p-3 border border-gray-300 rounded-lg focus:ring focus:ring-blue-400 focus:outline-none text-sm"
                               placeholder="https://www.google.com/...">
                    </div>
                    <div class="flex justify-end">
                        <button type="submit"
                                class="w-full md:w-auto px-6 py-2 bg-indigo-500 text-white text-sm font-medium rounded-lg shadow-md transform transition-transform hover:scale-105 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Enregistrer
                        </button>
                    </div>
                </form>
                <h2 class="text-xl font-bold mb-2 text-gray-800">Lien Avis Google enregistré</h2>
                <!-- Afficher l'URL de l'avis Google sous le bouton "Enregistrer" -->

                @if($carte->lienAvis)
                    <div class="mt-4 w-auto h-auto">
                        <div class="bg-white p-4 rounded-lg shadow
                        -md relative w-auto h-auto">
                            <div class="video-container w-auto h-auto">
                                <a href="{{$carte->lienAvis}}" class="text-blue-500 underline"
                                   target="_blank">Lien Google Avis</a>
                            </div>
                        </div>
                    </div>
                @else
                    <p class="text-gray-500 italic text-center border-2 p-32">Aucun lien d'avis Google enregistré.</p>
                @endif

                @if($carte->lienAvis)
                    <!-- btn sup lien -->
                    <form action="{{ route('dashboardClientPDF.deleteAvis') }}" method="POST"
                          class="mt-4 w-full flex justify-end">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg">
                            Supprimer
                        </button>
                    </form>
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
                <h2 class="text-xl font-bold mb-2 text-gray-800">Lien de RDV enregistré</h2>
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
                @else
                    <p class="text-gray-500 italic text-center border-2 p-32">Aucun lien de RDV enregistré.</p>
                @endif

                @if($carte->LienCommande)
                    <!-- btn sup lien -->
                    <form action="{{ route('dashboardClientPDF.deleteRDV') }}" method="POST"
                          class="mt-4 w-full flex justify-end">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg">
                            Supprimer
                        </button>
                    </form>
                @endif

            </div>

            <!-- div5 galerie photo -->
            <div class="relative col-span-4 row-span-4">
                @if($compte->role == 'starter')
                    <!-- Message abonnement, centré au-dessus du blur -->
                    <div class="relative z-50 flex flex-col items-center justify-center">
                        <a href="https://wisikard.fr/produit/mise-a-niveau-wisikard-advanced/"
                           target="_blank"
                           class="bg-red-500 border-solid border border-red-500 hover:bg-red-900 hover:border-red-900 rounded-xl w-48 h-7 flex items-center justify-center space-x-4">
                            <p class="text-white text-base">Mettre à niveau</p>
                            <!-- svg cursor mouse -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                 viewBox="0 0 24 24"
                                 fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="feather feather-mouse-pointer">
                                <path d="M3 3l7.07 16.97 2.51-7.39 7.39-2.51L3 3z"></path>
                                <path d="M13 13l6 6"></path>
                            </svg>
                        </a>
                    </div>
                @endif
                <div class="bg-white rounded-lg shadow-md p-6 @if($compte->role == 'starter') blur-sm pointer-events-none opacity-50 @endif">

                    <h2 class="text-3xl font-semibold text-gray-800 mb-4 text-center">Galerie photo</h2>
                    <div class="flex flex-wrap md:flex-nowrap justify-between items-center space-y-6 md:space-y-0 md:space-x-12 grow">
                        <!-- Formulaire d'upload -->
                        <form action="{{ route('dashboardClientPDF.uploadSlider') }}" method="POST"
                              enctype="multipart/form-data"
                              class="space-y-4 w-full md:w-1/3 flex flex-col justify-between">
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

                        <!-- Affichage des images dans la galerie -->
                        <div class="w-full flex flex-col justify-center rounded-2xl p-6 bg-indigo-200">
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
                                            <form action="{{ route('dashboardClientPDF.deleteSliderImage') }}"
                                                  method="POST"
                                                  class="absolute top-2 right-2">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="filename" value="{{ $image }}">
                                                <button type="submit"
                                                        class="bg-red-500 text-white px-2 py-1 rounded-lg">
                                                    <!-- svg poubelle -->
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                         viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              stroke-width="2"
                                                              d="M6 18L18 6M6 6l12 12"/>
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
                    </div>
                </div>
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

                /* pdf */
                // Fonction pour ouvrir la modale
                function openModalPdf() {
                    const modal = document.getElementById('nameModal'); // Cible la modale par son ID
                    modal.classList.remove('hidden'); // Affiche la modale en supprimant la classe 'hidden'
                }

                // Fonction pour fermer la modale
                function closeModalPdf() {
                    const modal = document.getElementById('nameModal'); // Cible la modale par son ID
                    modal.classList.add('hidden'); // Cache la modale en ajoutant la classe 'hidden'
                }

                // Fonction pour valider et soumettre le formulaire avec le nouveau nom
                function saveAndSubmit() {
                    // Récupérer les valeurs du nouveau nom de fichier
                    const newName = document.getElementById('newName').value;

                    // Vérifier que le champ "new_name" n'est pas vide
                    if (!newName) {
                        alert('Veuillez renseigner un nom pour le fichier.');
                        return;
                    }

                    // Ajouter la valeur du nouveau nom au formulaire principal
                    const uploadForm = document.getElementById('uploadForm');
                    const inputNewName = document.createElement('input');
                    inputNewName.type = 'hidden';
                    inputNewName.name = 'new_name';
                    inputNewName.value = newName;
                    uploadForm.appendChild(inputNewName);

                    // Soumettre le formulaire
                    uploadForm.submit();
                }


            </script>


        </div>
    </div>
</div>
</div>
</div>

</body>
</html>
