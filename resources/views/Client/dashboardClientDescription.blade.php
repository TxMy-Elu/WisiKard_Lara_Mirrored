<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Wisikard - Section aide - {{ $titre }}</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet"/>
    <style>
        .small-image {
            width: 30%;
            height: auto; 
            transition: transform 0.3s ease-in-out;
        }
        .small-image:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen p-6">
    <div class="bg-white p-8 rounded-xl shadow-xl w-full max-w-2xl text-center space-y-6 mx-4">
        <h1 class="text-3xl font-bold text-red-800 mb-6">{{ $titre }}</h1>

        <div class="space-y-6">
            @foreach($txts as $txt)
                <p class="text-gray-700 text-base leading-relaxed">{{ $txt->txt }}</p>
                @php
                    $imgVar = 'img' . $txt->num_txt;
                @endphp
                @if(isset($$imgVar))
                    <img src="{{ asset(str_replace('public/', '', $$imgVar->chemin)) }}" alt="Image Description" class="mx-auto mt-4 small-image rounded-lg shadow-lg">
                @endif
            @endforeach
        </div>

        <div class="mt-8">
            <a href="{{ route('dashboardClientAide') }}" class="text-red-600 hover:text-red-800 transition duration-300">Retour à l'aide</a>
        </div>
    </div>
</div>

</body>
</html>
