<!-- resources/views/client/dashboardClientPDF.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Client PDF</title>
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
                <div>
                    <label for="file" class="block text-sm font-medium text-gray-700">Sélectionner un fichier :</label>
                    <input type="file" id="file" name="file" class="mt-1 block w-full" accept=".pdf,.jpg,.jpeg,.png">
                </div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Télécharger</button>
            </form>

            @if(File::exists(public_path("entreprises/{$carte->nomEntreprise}/pdf")))
                <div class="mt-4">
                    <h2 class="text-xl font-bold mb-2">Fichiers PDF téléchargés</h2>
                    <ul>
                        @foreach(File::files(public_path("entreprises/{$carte->nomEntreprise}/pdf")) as $file)
                            <li><a href="{{ asset("entreprises/{$carte->nomEntreprise}/pdf/" . $file->getFilename()) }}" target="_blank">{{ $file->getFilename() }}</a></li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(File::exists(public_path("entreprises/{$carte->nomEntreprise}/images")))
                <div class="mt-4">
                    <h2 class="text-xl font-bold mb-2">Images téléchargées</h2>
                    <ul>
                        @foreach(File::files(public_path("entreprises/{$carte->nomEntreprise}/images")) as $file)
                            <li><a href="{{ asset("entreprises/{$carte->nomEntreprise}/images/" . $file->getFilename()) }}" target="_blank">{{ $file->getFilename() }}</a></li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
</div>

</body>
</html>
