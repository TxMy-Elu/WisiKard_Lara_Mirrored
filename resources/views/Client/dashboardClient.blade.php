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

        <!-- 1 form with picker color x2 -->
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


    </div>
</div>

</body>
</html>
