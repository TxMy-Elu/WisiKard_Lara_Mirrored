<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Description - {{ $titre }}</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet"/>
    <style>
        .small-image {
            width: 30%; /* Ajustez cette valeur pour modifier la taille de l'image */
            height: auto; /* Maintenez le ratio d'aspect */
        }
    </style>
</head>
<body class="bg-gray-100 p-6">
    <h1 class="text-2xl font-bold text-center mb-6">{{ $titre }}</h1>

    <div class="bg-white p-4 rounded-lg shadow-md space-y-4">
        @foreach($txts as $txt)
            <p class="text-gray-800">{{ $txt->txt }}</p>
            @if($txt->num_txt == 1 && $img0)
                <img src="{{ asset(str_replace('public/', '', $img0->chemin)) }}" alt="Image Description" class="mt-4 small-image">
            @elseif($txt->num_txt == 2 && $img1)
                <img src="{{ asset(str_replace('public/', '', $img1->chemin)) }}" alt="Image Description" class="mt-4 small-image">
            @elseif($txt->num_txt == 3 && $img2)
                <img src="{{ asset(str_replace('public/', '', $img2->chemin)) }}" alt="Image Description" class="mt-4 small-image">
            @elseif($txt->num_txt == 4 && $img3)
                <img src="{{ asset(str_replace('public/', '', $img3->chemin)) }}" alt="Image Description" class="mt-4 small-image">
            @elseif($txt->num_txt == 5 && $img4)
                <img src="{{ asset(str_replace('public/', '', $img4->chemin)) }}" alt="Image Description" class="mt-4 small-image">
            @endif
        @endforeach
    </div>

    <div class="text-center mt-6">
        <a href="{{ route('dashboardClientAide') }}" class="text-blue-500 hover:text-blue-700">← Retour à l'Aide</a>
    </div>
</body>
</html>
