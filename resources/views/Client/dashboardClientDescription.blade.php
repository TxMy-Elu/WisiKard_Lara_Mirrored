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
                @if($txt->num_txt == 1 &&$img1)
                    <img src="{{ asset(str_replace('public/', '', $img1->chemin)) }}" alt="Image Description" class="mx-auto mt-4 small-image rounded-lg shadow-lg">
                @elseif($txt->num_txt == 2 && $img2)
                    <img src="{{ asset(str_replace('public/', '', $img2->chemin)) }}" alt="Image Description" class="mx-auto mt-4 small-image rounded-lg shadow-lg">
                @elseif($txt->num_txt == 3 && $img3)
                    <img src="{{ asset(str_replace('public/', '', $img3->chemin)) }}" alt="Image Description" class="mx-auto mt-4 small-image rounded-lg shadow-lg">
                @elseif($txt->num_txt == 4 && $img4)
                    <img src="{{ asset(str_replace('public/', '', $img4->chemin)) }}" alt="Image Description" class="mx-auto mt-4 small-image rounded-lg shadow-lg">
                @elseif($txt->num_txt == 5 && $img5)
                    <img src="{{ asset(str_replace('public/', '', $img5->chemin)) }}" alt="Image Description" class="mx-auto mt-4 small-image rounded-lg shadow-lg">
                @elseif($txt->num_txt == 6 && $img6)
                    <img src="{{ asset(str_replace('public/', '', $img6->chemin)) }}" alt="Image Description" class="mx-auto mt-4 small-image rounded-lg shadow-lg">
                @elseif($txt->num_txt == 7 && $img7)
                    <img src="{{ asset(str_replace('public/', '', $img7->chemin)) }}" alt="Image Description" class="mx-auto mt-4 small-image rounded-lg shadow-lg">
                @elseif($txt->num_txt == 8 && $img8)
                    <img src="{{ asset(str_replace('public/', '', $img8->chemin)) }}" alt="Image Description" class="mx-auto mt-4 small-image rounded-lg shadow-lg">
                @endif
            @endforeach
        </div>

        <div class="mt-8">
            <a href="{{ route('dashboardClientAide') }}" class="text-red-600 hover:text-red-800 transitio
