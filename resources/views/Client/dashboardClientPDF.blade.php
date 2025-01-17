<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Client Employe</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">

<div class="flex flex-col md:flex-row">
    @include('menu.menuClient')
    <div class="flex-1 md:ml-24 p-4">
        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <h1 class="text-2xl font-bold mb-4">Télécharger des fichiers</h1>
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

           <form action="{{ route('dashboardClientPDF.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
               @csrf
               <div class="mb-4">
                   <label for="file" class="block text-sm font-medium text-gray-700">Sélectionner un fichier :</label>
                   <input type="file" id="file" name="file" class="mt-1 block w-full" accept=".mp4,.pdf,.jpg,.jpeg,.png">
               </div>
               <div class="mb-4">
                   <label for="youtube_url" class="block text-sm font-medium text-gray-700">URL YouTube :</label>
                   <input type="url" id="youtube_url" name="youtube_url" class="mt-1 block w-full" placeholder="https://www.youtube.com/watch?v=...">
               </div>
               <div class="mb-4">
                   <label for="logo" class="block text-sm font-medium text-gray-700">Sélectionner un logo :</label>
                   <input type="file" id="logo" name="logo" class="mt-1 block w-full" accept=".jpg,.jpeg,.png">
               </div>
               <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg">Télécharger</button>
          <form action="{{ route('dashboardClientPDF.uploadSlider') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
              @csrf
              <div class="mb-4">
                  <label for="slider_image" class="block text-sm font-medium text-gray-700">Sélectionner une image pour le slider :</label>
                  <input type="file" id="slider_image" name="slider_image" class="mt-1 block w-full" accept=".jpg,.jpeg,.png">
              </div>
              <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg">Télécharger</button>
          </form>

           </form>
        </div>
       <!-- Card pour le logo -->
       @if(File::exists(public_path("entreprises/{$idCompte}_{$carte->nomEntreprise}/logos/logo.jpg")) ||
          File::exists(public_path("entreprises/{$idCompte}_{$carte->nomEntreprise}/logos/logo.jpeg")) ||
          File::exists(public_path("entreprises/{$idCompte}_{$carte->nomEntreprise}/logos/logo.png")))
           <div class="mt-4">
               <br><br>
               <h2 class="text-xl font-bold mb-2">Logo</h2>
               <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                   <div class="bg-white p-8 rounded-lg shadow-md relative">
                       <div class="text-center mb-2">
                           @if(File::exists(public_path("entreprises/{$idCompte}_{$carte->nomEntreprise}/logos/logo.jpg")))
                               <img src="{{ asset("entreprises/{$idCompte}_{$carte->nomEntreprise}/logos/logo.jpg") }}" alt="Logo" class="w-24 h-24 mx-auto">
                           @elseif(File::exists(public_path("entreprises/{$idCompte}_{$carte->nomEntreprise}/logos/logo.jpeg")))
                               <img src="{{ asset("entreprises/{$idCompte}_{$carte->nomEntreprise}/logos/logo.jpeg") }}" alt="Logo" class="w-24 h-24 mx-auto">
                           @elseif(File::exists(public_path("entreprises/{$idCompte}_{$carte->nomEntreprise}/logos/logo.png")))
                               <img src="{{ asset("entreprises/{$idCompte}_{$carte->nomEntreprise}/logos/logo.png") }}" alt="Logo" class="w-24 h-24 mx-auto">
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

    <!-- Card pour les images -->
    @if(File::exists(public_path("entreprises/{$idCompte}_{$carte->nomEntreprise}/images")))
        <div class="mt-4">
            <br><br>
            <h2 class="text-xl font-bold mb-2">Images téléchargées</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @foreach(File::files(public_path("entreprises/{$idCompte}_{$carte->nomEntreprise}/images")) as $file)
                    <div class="bg-white p-4 rounded-lg shadow-md relative">
                        <div class="text-center mb-3">
                            <h3 class="text-lg font-bold">{{ $file->getFilename() }}</h3>
                        </div>
                        <img src="{{ asset("entreprises/{$idCompte}_{$carte->nomEntreprise}/images/" . $file->getFilename()) }}" alt="{{ $file->getFilename() }}" class="w-24 h-24 mx-auto">
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


        <!-- Card pour les PDF -->
        @if(File::exists(public_path("entreprises/{$idCompte}_{$carte->nomEntreprise}/pdf")))
            <div class="mt-4">
                <br><br>
                <h2 class="text-xl font-bold mb-2">Fichiers PDF téléchargés</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach(File::files(public_path("entreprises/{$idCompte}_{$carte->nomEntreprise}/pdf")) as $file)
                        <div class="bg-white p-4 rounded-lg shadow-md">
                            <div class="text-center mb-2">
                                <h3 class="text-lg font-bold">{{ $file->getFilename() }}</h3>
                            </div>
                            <div class="h-64 overflow-auto border rounded-lg">
                                <iframe src="{{ asset("entreprises/{$idCompte}_{$carte->nomEntreprise}/pdf/" . $file->getFilename()) }}" width="100%" height="100%" style="border: none;"></iframe>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
        <!-- Card pour les vidéos YouTube -->
        @if(!empty($youtubeUrls))
            <div class="mt-4">
                <br><br>
                <h2 class="text-xl font-bold mb-2">Vidéos YouTube enregistrées</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($youtubeUrls as $index => $youtubeUrl)
                        <div class="bg-white p-4 rounded-lg shadow-md relative">
                            <div class="text-center mb-2">
                                <h3 class="text-lg font-bold">{{ $youtubeUrl }}</h3>
                            </div>
                            <div class="video-container"><br/>
                                <iframe width="100%" height="200" src="{{ str_replace('watch?v=', 'embed/', $youtubeUrl) }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            </div><br/>
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

<!-- Card pour le slider -->
@if(isset($sliderImages) && !empty($sliderImages))
    <div class="mt-4">
        <br><br>
        <h2 class="text-xl font-bold mb-2 slider-title">Slider</h2>
        <div class="bg-white p-4 rounded-lg shadow-md">
            <div class="flex space-x-4 overflow-x-auto">
                @foreach($sliderImages as $file)
                    <div class="w-24 h-24 bg-gray-100 rounded-lg shadow-md flex items-center justify-center relative">
                        <img src="{{ asset("entreprises/{$idCompte}_{$carte->nomEntreprise}/slider/" . $file->getFilename()) }}" alt="{{ $file->getFilename() }}" class="w-full h-full object-cover">
                        <form action="{{ route('dashboardClientPDF.deleteSliderImage', ['filename' => $file->getFilename()]) }}" method="POST" class="absolute bottom-0 right-0 mb-2 mr-2">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="bg-red-500 text-white px-2 py-1 rounded-lg" onclick="confirmDelete('deleteSliderForm_{{ $file->getFilename() }}')">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif




</div>
<script>
    function confirmDelete(formId) {
        if (confirm('Êtes-vous sûr de vouloir supprimer cet élément ?')) {
            document.getElementById(formId).submit();
        }
    }
</script>

</body>
</html>
