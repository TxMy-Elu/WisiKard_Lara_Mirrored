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
                    <input type="file" id="file" name="file" class="mt-1 block w-full" accept=".pdf,.jpg,.jpeg,.png">
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
                    <div class="bg-white p-8 rounded-lg shadow-md">
                        <div class="text-center mb-2">
                            @if(File::exists(public_path("entreprises/{$idCompte}_{$carte->nomEntreprise}/logos/logo.jpg")))
                                <img src="{{ asset("entreprises/{$idCompte}_{$carte->nomEntreprise}/logos/logo.jpg") }}" alt="Logo" class="w-24 h-24 mx-auto">
                            @elseif(File::exists(public_path("entreprises/{$idCompte}_{$carte->nomEntreprise}/logos/logo.jpeg")))
                                <img src="{{ asset("entreprises/{$idCompte}_{$carte->nomEntreprise}/logos/logo.jpeg") }}" alt="Logo" class="w-24 h-24 mx-auto">
                            @elseif(File::exists(public_path("entreprises/{$idCompte}_{$carte->nomEntreprise}/logos/logo.png")))
                                <img src="{{ asset("entreprises/{$idCompte}_{$carte->nomEntreprise}/logos/logo.png") }}" alt="Logo" class="w-24 h-24 mx-auto">
                            @endif
                        </div>
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
                        <div class="bg-white p-4 rounded-lg shadow-md">
                            <div class="text-center mb-3">
                                <h3 class="text-lg font-bold">{{ $file->getFilename() }}</h3>
                            </div>
                            <img src="{{ asset("entreprises/{$idCompte}_{$carte->nomEntreprise}/images/" . $file->getFilename()) }}" alt="{{ $file->getFilename() }}" class="w-24 h-24 mx-auto">
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
                    @foreach($youtubeUrls as $youtubeUrl)
                        <div class="bg-white p-4 rounded-lg shadow-md">
                            <div class="text-center mb-2">
                                <h3 class="text-lg font-bold">{{ $youtubeUrl }}</h3>
                            </div>
                            <div class="video-container">
                                <iframe width="100%" height="200" src="{{ str_replace('watch?v=', 'embed/', $youtubeUrl) }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Card pour le slider -->
        @if(File::exists(public_path("entreprises/{$idCompte}_{$carte->nomEntreprise}/slider")))
            <div class="mt-4">
                <br><br>
                <h2 class="text-xl font-bold mb-2 slider-title">Slider</h2>
                <div class="bg-white p-4 rounded-lg shadow-md">
                    <div class="flex space-x-4 overflow-x-auto">
                        @foreach(File::files(public_path("entreprises/{$idCompte}_{$carte->nomEntreprise}/slider")) as $file)
                            <div class="w-24 h-24 bg-gray-100 rounded-lg shadow-md flex items-center justify-center">
                                <img src="{{ asset("entreprises/{$idCompte}_{$carte->nomEntreprise}/slider/" . $file->getFilename()) }}" alt="{{ $file->getFilename() }}" class="w-full h-full object-cover">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

</body>
</html>
