<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $carte['nomEntreprise'] ? $carte['nomEntreprise'] . ' - ' : '' }}Wisikard</title>
    <script src="https://cdn.lordicon.com/lordicon.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Montserrat:wght@400;700&family=Oswald:wght@400;700&family=Ubuntu:wght@400;700&family=Playfair+Display:wght@400;700&family=Work+Sans:wght@400;700&family=Bona+Nova:wght@400;700&family=Exo+2:wght@400;700&family=Pacifico&family=Gruppo&family=Rokkitt:wght@400;700&display=swap"
          rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        .fade-in {
            animation: fadeIn 0.3s ease-in;
        }

        .slide-in {
            animation: slideIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideIn {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .wk-button {
            @apply w-full py-4 px-6 mb-3 rounded-xl font-medium text-center 
                   transition-all duration-300 ease-in-out
                   bg-white text-gray-800 hover:scale-105 
                   flex items-center justify-between
                   shadow-sm hover:shadow-xl;
        }

        .link-hover-effect {
            @apply transform transition-transform duration-300 hover:scale-102 
                   hover:shadow-lg active:scale-98;
        }

        .gradient-background {
            background: linear-gradient(135deg, #1a1a1a 0%, #363636 100%);
        }
    </style>
</head>
<body class="bg-zinc-900 w-full" style="font-family: '{{ $carte['font'] }}';">


@php
    // Détection des différents types de fichiers
    $logoPath = '';
    $formats = ['svg', 'png', 'jpg', 'jpeg']; // Ajouter d'autres formats si nécessaire

    foreach ($formats as $format) {
        $path = public_path('entreprises/' . $carte->compte->idCompte . '/logos/logo.' . $format);
        if (file_exists($path)) {
            $logoPath = asset('entreprises/' . $carte->compte->idCompte . '/logos/logo.' . $format);
            break;
        }
    }
@endphp

<div class="profile-header mb-8 text-center">
    @if(!empty($logoPath))
        <div class="relative w-32 h-32 mx-auto mb-4 bg-white rounded-full p-2 shadow-xl">
            <div class="w-full h-full overflow-hidden rounded-full flex items-center justify-center">
                <img src="{{ $logoPath }}" 
                     alt="Logo" 
                     class="w-full h-full object-contain p-2">
            </div>
        </div>
    @else
        <h1 class="text-2xl font-bold text-white mb-2">{{ $carte['nomEntreprise'] }}</h1>
    @endif
    
    @if($carte['descriptif'])
        <p class="text-gray-300 text-sm">{{ $carte['descriptif'] }}</p>
    @endif
</div>
<div class="bg-white p-4 mt-4 mx-4 space-y-4 rounded-lg shadow-lg">
    @if($compte['email'])
        <div class="flex items-center justify-center">
            <a href="mailto:{{ $compte['email'] }}"
               class="w-full h-12 px-4 bg-gray-100 hover:bg-gray-200 text-zinc-800 font-medium rounded-lg border border-gray-300 flex items-center justify-center shadow-sm transition duration-200">
                <p>📧 Email: {{ $compte['email'] }}</p>
            </a>
        </div>
    @endif
    @if($carte['tel'])
        @php
            function formatPhone($phone) {
                $cleaned = preg_replace('/[^0-9]/', '', $phone);
                if (strlen($cleaned) === 10) {
                    return preg_replace('/(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})/', '$1 $2 $3 $4 $5', $cleaned);
                }
                return $phone;
            }
        @endphp

        <div class="flex items-center justify-center">
            <a href="tel:{{ $carte['tel'] }}"
               class="w-full h-12 px-4 bg-gray-100 hover:bg-gray-200 text-zinc-800 font-medium rounded-lg border border-gray-300 flex items-center justify-center shadow-sm transition duration-200">
                <p class="flex items-center gap-2">
                    📞 <span class="tracking-wide">Télephone: {{ formatPhone($carte['tel']) }}</span>
                </p>
            </a>
        </div>
    @endif
</div>
<div class="flex items-center justify-between mt-4 mx-4 gap-4">
    <div class="flex justify-center w-full">
        <a onclick="openQrModal()"
           class="cursor-pointer w-full rounded-lg px-6 h-10 font-semibold text-gray-800 text-center border border-gray-300 bg-white hover:text-white hover:bg-gray-800 hover:shadow-lg transition duration-300 ease-in-out flex items-center justify-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="25" height="25" fill="#000000">
                <path d="M0 80C0 53.5 21.5 32 48 32l96 0c26.5 0 48 21.5 48 48l0 96c0 26.5-21.5 48-48 48l-96 0c-26.5 0-48-21.5-48-48L0 80zM64 96l0 64 64 0 0-64L64 96zM0 336c0-26.5 21.5-48 48-48l96 0c26.5 0 48 21.5 48 48l0 96c0 26.5-21.5 48-48 48l-96 0c-26.5 0-48-21.5-48-48l0-96zm64 16l0 64 64 0 0-64-64 0zM304 32l96 0c26.5 0 48 21.5 48 48l0 96c0 26.5-21.5 48-48 48l-96 0c-26.5 0-48-21.5-48-48l0-96c0-26.5 21.5-48 48-48zm80 64l-64 0 0 64 64 0 0-64zM256 304c0-8.8 7.2-16 16-16l64 0c8.8 0 16 7.2 16 16s7.2 16 16 16l32 0c8.8 0 16-7.2 16-16s7.2-16 16-16s16 7.2 16 16l0 96c0 8.8-7.2 16-16 16l-64 0c-8.8 0-16-7.2-16-16s-7.2-16-16-16s-16 7.2-16 16l0 64c0 8.8-7.2 16-16 16l-32 0c-8.8 0-16-7.2-16-16l0-160zM368 480a16 16 0 1 1 0-32 16 16 0 1 1 0 32zm64 0a16 16 0 1 1 0-32 16 16 0 1 1 0 32z"/>
            </svg>
            QR Code
        </a>

        <div id="qrModal"
             class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center z-50">
            <div class="bg-white rounded-lg shadow-lg p-6 w-11/12 max-w-sm">
                <h2 class="text-xl font-semibold text-gray-800 text-center">Votre QR Code</h2>
                <div class="mt-4 text-center">
                    <img src="{{ $carte['lienQr'] }}" alt="QR Code" class="mx-auto max-h-64">
                </div>
                <div class="mt-6 text-center">
                    <button onclick="closeQrModal()"
                            class="px-4 py-2 bg-gray-800 text-white text-sm font-medium rounded-lg hover:bg-gray-900 transition duration-300 ease-in-out">
                        Fermer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="flex justify-center">
        <button onclick="openModal()"
                class="w-10 h-10 rounded-lg bg-white border border-gray-300 text-gray-800 flex items-center justify-center hover:text-white hover:bg-gray-800 hover:shadow-lg transition duration-300 ease-in-out">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="25" height="25" fill="#000000">
                <path d="M464 256A208 208 0 1 1 48 256a208 208 0 1 1 416 0zM0 256a256 256 0 1 0 512 0A256 256 0 1 0 0 256zM232 120l0 136c0 8 4 15.5 10.7 20l96 64c11 7.4 25.9 4.4 33.3-6.7s4.4-25.9-6.7-33.3L280 243.2 280 120c0-13.3-10.7-24-24-24s-24 10.7-24 24z"/>
            </svg>
        </button>

        <div id="horairesModal"
             class="hidden fixed inset-0 bg-zinc-900 bg-opacity-50 flex justify-center items-center z-50">
            <div class="bg-white w-11/12 max-w-lg rounded-lg shadow-lg p-6">
                <div class="flex justify-between items-center border-b border-zinc-700 pb-5">
                    <h3 class="text-2xl font-bold text-gray-800">Horaires de la semaine</h3>
                </div>

                <div class="mt-4">
                    <ul class="text-gray-700 list-disc list-inside">
                        @foreach($horaires as $jour => $horaire)
                            <li>
                                @php
                                    $jours = [
                                        0 => 'Lundi',
                                        1 => 'Mardi',
                                        2 => 'Mercredi',
                                        3 => 'Jeudi',
                                        4 => 'Vendredi',
                                        5 => 'Samedi',
                                        6 => 'Dimanche'
                                    ];
                                @endphp

                                <strong class="text-red-600">{{ $jours[$jour] ?? 'Jour inconnu' }} :</strong>

                                @if($horaire->ouverture_matin && $horaire->fermeture_matin && $horaire->ouverture_aprmidi && $horaire->fermeture_aprmidi)
                                    {{ date('H:i', strtotime($horaire->ouverture_matin)) }}
                                    - {{ date('H:i', strtotime($horaire->fermeture_matin)) }} /
                                    {{ date('H:i', strtotime($horaire->ouverture_aprmidi)) }}
                                    - {{ date('H:i', strtotime($horaire->fermeture_aprmidi)) }}
                                @else
                                    Fermé
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="mt-6 flex justify-end">
                    <button onclick="closeModal()"
                            class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                        Fermer
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="flex items-center justify-between mt-4 mx-4 gap-4">
    <div class="flex justify-center">
        <a href="{{ '/entreprises/'. $carte->compte->idCompte.'/VCF_Files/contact.vcf' }}"
                class="w-10 h-10 rounded-lg bg-white border border-gray-300 text-gray-800 flex items-center justify-center hover:text-white hover:bg-gray-800 hover:shadow-lg transition duration-300 ease-in-out">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="25" height="25" fill="#000000">
                <!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                <path d="M512 80c8.8 0 16 7.2 16 16l0 320c0 8.8-7.2 16-16 16L64 432c-8.8 0-16-7.2-16-16L48 96c0-8.8 7.2-16 16-16l448 0zM64 32C28.7 32 0 60.7 0 96L0 416c0 35.3 28.7 64 64 64l448 0c35.3 0 64-28.7 64-64l0-320c0-35.3-28.7-64-64-64L64 32zM208 256a64 64 0 1 0 0-128 64 64 0 1 0 0 128zm-32 32c-44.2 0-80 35.8-80 80c0 8.8 7.2 16 16 16l192 0c8.8 0 16-7.2 16-16c0-44.2-35.8-80-80-80l-64 0zM376 144c-13.3 0-24 10.7-24 24s10.7 24 24 24l80 0c13.3 0 24-10.7 24-24s-10.7-24-24-24l-80 0zm0 96c-13.3 0-24 10.7-24 24s10.7 24 24 24l80 0c13.3 0 24-10.7 24-24s-10.7-24-24-24l-80 0z"/>
            </svg>
        </a>
    </div>

    <div class="flex justify-center w-full">
        <a onclick="shareOrCopyLink()"
           class="cursor-pointer w-full rounded-lg px-6 h-10 font-semibold text-gray-800 text-center border border-gray-300 bg-white hover:text-white hover:bg-gray-800 hover:shadow-lg transition duration-300 ease-in-out flex items-center justify-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="25" height="25" fill="#000000">
                <path d="M400 255.4l0-15.4 0-32c0-8.8-7.2-16-16-16l-32 0-16 0-46.5 0c-50.9 0-93.9 33.5-108.3 79.6c-3.3-9.4-5.2-19.8-5.2-31.6c0-61.9 50.1-112 112-112l48 0 16 0 32 0c8.8 0 16-7.2 16-16l0-32 0-15.4L506 160 400 255.4zM336 240l16 0 0 48c0 17.7 14.3 32 32 32l3.7 0c7.9 0 15.5-2.9 21.4-8.2l139-125.1c7.6-6.8 11.9-16.5 11.9-26.7s-4.3-19.9-11.9-26.7L409.9 8.9C403.5 3.2 395.3 0 386.7 0C367.5 0 352 15.5 352 34.7L352 80l-16 0-32 0-16 0c-88.4 0-160 71.6-160 160c0 60.4 34.6 99.1 63.9 120.9c5.9 4.4 11.5 8.1 16.7 11.2c4.4 2.7 8.5 4.9 11.9 6.6c3.4 1.7 6.2 3 8.2 3.9c2.2 1 4.6 1.4 7.1 1.4l2.5 0c9.8 0 17.8-8 17.8-17.8c0-7.8-5.3-14.7-11.6-19.5c0 0 0 0 0 0c-.4-.3-.7-.5-1.1-.8c-1.7-1.1-3.4-2.5-5-4.1c-.8-.8-1.7-1.6-2.5-2.6s-1.6-1.9-2.4-2.9c-1.8-2.5-3.5-5.3-5-8.5c-2.6-6-4.3-13.3-4.3-22.4c0-36.1 29.3-65.5 65.5-65.5l14.5 0 32 0c0 13.3-10.7 24-24 24l0-64c0-35.3 21.5-48 48-48l0-336c0-35.3 10.7-24 24-24l64 0c13.3 0 24-10.7 24-24s-10.7-24-24-24l-80 0zM72 32C32.2 32 0 64.2 0 104L0 440c0 39.8 32.2 72 72 72l336 0c39.8 0 72-32.2 72-72l0-64c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 64c0 13.3-10.7 24-24 24L72 464c-13.3 0-24-10.7-24-24l0-336c0-13.3 10.7-24 24-24l64 0c13.3 0 24-10.7 24-24s-10.7-24-24-24l-80 0z"/>
            </svg>
            Partager
        </a>
    </div>
</div>

<div class="mt-4  {{ !$carte['lienAvis'] && !$carte['lienSiteWeb'] && !$carte['LienCommande'] ? 'mx-4' : 'flex items-center justify-between mx-4 gap-4' }}">
    <!-- Vérification : si aucun lien n'est disponible, on ajuste la carte -->
    @if(!$carte['lienAvis'] && !$carte['lienSiteWeb'] && !$carte['LienCommande'])
        @if(!empty($carte['ville']))
            <!-- Afficher la carte uniquement si la ville est définie -->
            <div id="map" class="w-full h-96 rounded-lg z-10"></div>
        @endif
    @else
        <div class="flex-cols justify-center space-y-4">
            <!-- Avis google -->
            @if($carte['lienAvis'])
                <div class="flex justify-center">
                    <a href="{{ $carte['lienAvis'] }}" target="_blank"
                       class="w-full rounded-lg px-6 h-10 font-semibold text-gray-800 text-center border border-gray-300 bg-white hover:text-white hover:bg-gray-800 hover:shadow-lg transition duration-300 ease-in-out flex items-center justify-start gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="25" height="25"
                             fill="#000000">
                            <path d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"/>
                        </svg>
                        Avis Google
                    </a>
                </div>
            @endif
            <!-- site -->
            @if($carte['lienSiteWeb'])
                <div class="flex justify-center">
                    <a href="{{ $carte['lienSiteWeb'] }}" target="_blank"
                       class="w-full rounded-lg px-6 h-10 font-semibold text-gray-800 text-center border border-gray-300 bg-white hover:text-white hover:bg-gray-800 hover:shadow-lg transition duration-300 ease-in-out flex items-center justify-start gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="25" height="25"
                             fill="#000000">
                            <path d="M352 256c0 22.2-1.2 43.6-3.3 64l-185.3 0c-2.2-20.4-3.3-41.8-3.3-64s1.2-43.6 3.3-64l185.3 0c2.2 20.4 3.3 41.8 3.3 64zm28.8-64l123.1 0c5.3 20.5 8.1 41.9 8.1 64s-2.8 43.5-8.1 64l-123.1 0c2.1-20.6 3.2-42 3.2-64s-1.1-43.4-3.2-64zm112.6-32l-116.7 0c-10-63.9-29.8-117.4-55.3-151.6c78.3 20.7 142 77.5 171.9 151.6zm-149.1 0l-176.6 0c6.1-36.4 15.5-68.6 27-94.7c10.5-23.6 22.2-40.7 33.5-51.5C239.4 3.2 248.7 0 256 0s16.6 3.2 27.8 13.8c11.3 10.8 23 27.9 33.5 51.5c11.6 26 20.9 58.2 27 94.7zm-209 0L18.6 160C48.6 85.9 112.2 29.1 190.6 8.4C165.1 42.6 145.3 96.1 135.3 160zM8.1 192l123.1 0c-2.1 20.6-3.2 42-3.2 64s1.1 43.4 3.2 64L8.1 320C2.8 299.5 0 278.1 0 256s2.8-43.5 8.1-64zM194.7 446.6c-11.6-26-20.9-58.2-27-94.6l176.6 0c-6.1 36.4-15.5 68.6-27 94.6c-10.5 23.6-22.2 40.7-33.5 51.5C272.6 508.8 263.3 512 256 512s-16.6-3.2-27.8-13.8c-11.3-10.8-23-27.9-33.5-51.5zM135.3 352c10 63.9 29.8 117.4 55.3 151.6C112.2 482.9 48.6 426.1 18.6 352l116.7 0zm358.1 0c-30 74.1-93.6 130.9-171.9 151.6c25.5-34.2 45.2-87.7 55.3-151.6l116.7 0z"/>
                        </svg>
                        Site Web
                    </a>
                </div>
            @endif


            <!-- rdv -->
            @if($carte['LienCommande'])
                <div class="flex justify-center">
                    <a href="{{ $carte['LienCommande'] }}" target="_blank"
                       class="w-full rounded-lg px-6 h-10 font-semibold text-gray-800 text-center border border-gray-300 bg-white hover:text-white hover:bg-gray-800 hover:shadow-lg transition duration-300 ease-in-out flex items-center justify-start gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="25" height="25"
                             fill="#000000">
                            <path d="M152 24c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 40L64 64C28.7 64 0 92.7 0 128l0 16 0 48L0 448c0 35.3 28.7 64 64 64l320 0c35.3 0 64-28.7 64-64l0-256 0-48 0-16c0-35.3-28.7-64-64-64l-40 0 0-40c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 40L152 64l0-40zM48 192l352 0 0 256c0 8.8-7.2 16-16 16L64 464c-8.8 0-16-7.2-16-16l0-256zm176 40c-13.3 0-24 10.7-24 24l0 48-48 0c-13.3 0-24 10.7-24 24s10.7 24 24 24l48 0 0 48c0 13.3 10.7 24 24 24s24-10.7 24-24l0-48 48 0c13.3 0 24-10.7 24-24s-10.7-24-24-24l-48 0 0-48c0-13.3-10.7-24-24-24z"/>
                        </svg>
                        Rendez-vous
                    </a>
                </div>
            @endif
        </div>

        @if(!empty($carte['ville']))
            <!-- Afficher la carte uniquement si la ville est définie -->
            <div id="map" class="w-40 h-60 rounded-lg z-10 "></div>
        @endif

    @endif
</div>

<!-- Galerie Photos -->
@php

    // Chemin vers le répertoire
    $sliderDirectory = public_path('entreprises/'.$carte->idCompte.'/slider');

    // Liste des fichiers existants
    $sliderImages = file_exists($sliderDirectory) ? array_values(array_diff(scandir($sliderDirectory), array('.', '..'))) : [];
@endphp

@if($sliderImages)
    <div class="flex-cols">
        <!-- Section affichant le bouton pour ouvrir la galerie -->
        <div class="w-full mt-4 text-center">
            <button onclick="openGallery()"
                    class="w-32 rounded-xl p-2 font-bold text-white text-center bg-zinc-800 border border-gray-200 cursor-pointer">
                Voir la Galerie
            </button>
        </div>

        <!-- Modale de la Galerie -->
        <div id="photoGallery"
             class="hidden fixed top-0 left-0 w-full h-full bg-zinc-800 bg-opacity-75 flex justify-center items-center z-50">
            <div class="relative bg-white p-6 rounded-lg w-11/12 md:w-2/3 lg:w-1/2">
                <!-- Bouton pour fermer la galerie -->
                <button onclick="closeGallery()" class="absolute top-2 right-2 p-2 font-bold text-xl text-gray-900">
                    &times;
                </button>

                <h2 class="text-center font-bold text-lg mb-4">Galerie de Photos</h2>

                <!-- Liste des images dans la galerie -->
                <div class="flex flex-wrap gap-4 justify-center items-center">
                    @foreach($sliderImages as $image)
                        <img src="{{ asset('entreprises/'.$carte->idCompte.'/slider/'.urlencode($image)) }}"
                             alt="Image thumbnail"
                             class="w-1/3 rounded-lg shadow-md cursor-pointer"
                             onclick="viewImage('{{ asset('entreprises/'.$carte->idCompte.'/slider/'.urlencode($image)) }}')">
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Vue agrandie d'image -->
        <div id="fullImage"
             class="hidden fixed top-0 left-0 w-full h-full bg-zinc-800 bg-opacity-90 flex justify-center items-center z-50">
            <div class="relative">
                <img id="fullImageContent" src="" alt="Image fullsize"
                     class="max-w-full max-h-full rounded-lg shadow-lg">

                <!-- Bouton pour fermer -->
                <button onclick="closeFullImage()" class="absolute top-2 right-2 text-red-800 text-2xl font-bold">
                    &times;
                </button>
            </div>
        </div>
    </div>
@endif

<script>// Fonction pour ouvrir la galerie
    function openGallery() {
        const photoGallery = document.getElementById('photoGallery');
        photoGallery.classList.remove('hidden');
    }

    // Fonction pour fermer la galerie
    function closeGallery() {
        const photoGallery = document.getElementById('photoGallery');
        photoGallery.classList.add('hidden');
    }

    // Fonction pour afficher une image en plein écran
    function viewImage(imageSrc) {
        const fullImage = document.getElementById('fullImage');
        const fullImageContent = document.getElementById('fullImageContent');

        fullImageContent.src = imageSrc;
        fullImage.classList.remove('hidden');
    }

    // Fonction pour fermer l'image agrandie
    function closeFullImage() {
        const fullImage = document.getElementById('fullImage');

        fullImage.classList.add('hidden');
    }</script>



<!-- Section PDF + Vidéo -->
<div class="flex justify-center mt-4 mx-4 gap-4">
    <!-- Bouton PDF -->
    @if($carte['pdf'])
        <div class="w-1/2">
            <a href="{{ asset($carte['pdf']) }}" download
               class="w-full rounded-lg px-6 h-12 font-semibold text-gray-800 text-center border border-gray-300 bg-white hover:text-white hover:bg-gray-800 hover:shadow-lg transition duration-300 ease-in-out flex items-center justify-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="25" height="25" fill="#000000">
                    <path d="M64 464l48 0 0 48-48 0c-35.3 0-64-28.7-64-64L0 64C0 28.7 28.7 0 64 0L229.5 0c17 0 33.3 6.7 45.3 18.7l90.5 90.5c12 12 18.7 28.3 18.7 45.3L384 304l-48 0 0-144-80 0c-17.7 0-32-14.3-32-32l0-80L64 48c-8.8 0-16 7.2-16 16l0 384c0 8.8 7.2 16 16 16zM176 352l32 0c30.9 0 56 25.1 56 56s-25.1 56-56 56l-16 0 0 32c0 8.8-7.2 16-16 16s-16-7.2-16-16l0-48 0-80c0-8.8 7.2-16 16-16zm32 80c13.3 0 24-10.7 24-24s-10.7-24-24-24l-16 0 0 48 16 0zm96-80l32 0c26.5 0 48 21.5 48 48l0 64c0 26.5-21.5 48-48 48l-32 0c-8.8 0-16-7.2-16-16l0-128c0-8.8 7.2-16 16-16zm32 128c8.8 0 16-7.2 16-16l0-64c0-8.8-7.2-16-16-16l-16 0 0 96 16 0zm80-112c0-8.8 7.2-16 16-16l48 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-32 0 0 32 32 0c8.8 0 16 7.2 16 16s-7.2 16-16 16l-32 0 0 48c0 8.8-7.2 16-16 16s-16-7.2-16-16l0-64 0-64z"/>
                </svg>
                <span>{{ $carte['nomBtnPdf'] ?? 'Télécharger le PDF' }}</span>
            </a>
        </div>
    @endif

    @if($youtubeUrls)
        <!-- Bouton Vidéo -->
        <div class="w-1/2">
            <button onclick="openVideoGallery()"
                    class="w-full rounded-lg px-6 h-12 font-semibold text-gray-800 text-center border border-gray-300 bg-white hover:text-white hover:bg-gray-800 hover:shadow-lg transition duration-300 ease-in-out flex items-center justify-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="25" height="25" fill="#000000">
                    <path d="M0 128C0 92.7 28.7 64 64 64l256 0c35.3 0 64 28.7 64 64l0 256c0 35.3-28.7 64-64 64L64 448c-35.3 0-64-28.7-64-64L0 128zM559.1 99.8c10.4 5.6 16.9 16.4 16.9 28.2l0 256c0 11.8-6.5 22.6-16.9 28.2s-23 5-32.9-1.6l-96-64L416 337.1l0-17.1 0-128 0-17.1 14.2-9.5 96-64c9.8-6.5 22.4-7.2 32.9-1.6z"/>
                </svg>
                <span>Voir les Vidéos</span>
            </button>
        </div>


        <!-- Modale Galerie Vidéos -->
        <div id="videoGallery"
             class="hidden fixed top-0 left-0 w-full h-full bg-black bg-opacity-75 flex justify-center items-center z-50">
            <div class="bg-white p-6 rounded-lg shadow-lg w-11/12 md:w-2/3 lg:w-1/2 relative flex flex-col">
                <!-- Titre -->
                <h3 class="text-center font-bold text-lg text-gray-800 mb-4">Galerie de Vidéos</h3>

                <!-- Liste des vidéos -->
                <div class="flex flex-wrap gap-4 justify-center items-center mb-4">
                    @foreach($youtubeUrls as $url)
                        @php
                            // Extraire l'ID de la vidéo YouTube
                            $videoId = preg_replace('/^.*?v=([\w\-]+).*$/', '$1', $url);
                        @endphp
                        <a href="{{ $url }}" target="_blank" rel="noopener noreferrer"
                           class="w-1/3 sm:w-1/4 lg:w-1/5 relative rounded-lg overflow-hidden">
                            <img src="https://img.youtube.com/vi/{{ $videoId }}/mqdefault.jpg"
                                 alt="Thumbnail"
                                 class="w-full rounded-lg hover:opacity-80 transition-opacity duration-200">
                        </a>
                    @endforeach
                </div>

                <!-- Bouton Fermer -->
                <div class="flex justify-center">
                    <button onclick="closeVideoGallery()"
                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300 ease-in-out">
                        Fermer
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

@if($mergedSocial && count($mergedSocial) > 0)
    <!-- Link social -->
    <div class="flex flex-wrap justify-center gap-4 mt-4 mx-4 bg-white rounded-lg p-2">
        @foreach($mergedSocial as $so)
            <a href="{{ $so['lien'] }}" target="_blank" rel="noopener noreferrer"
               class="w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-white flex items-center justify-center border border-gray-300 transition-all duration-300 ease-in-out hover:bg-gray-800 hover:border-gray-800 hover:shadow-lg group">
                <!-- Icône à l'intérieur d'un cercle -->
                <div class=" group-hover:text-white fill-current flex items-center justify-center">
                    {!! $so['logo'] !!}
                </div>
            </a>
        @endforeach
    </div>
@endif

@if($custom && count($custom) > 0)
    <!-- Section des cartes avec hauteur uniforme -->
    <div class="mx-4 mt-4 bg-gray-100 rounded-lg shadow-md p-1">
        <!-- Titre de la section -->
        <p class="text-xl font-semibold text-gray-800 m-2">Liens personnalisés</p>
        <!-- Liens -->
        <div class="bg-white m-1 border border-gray-300 rounded-lg">
            @foreach ($custom as $link)
                <a href="{{ $link['lien'] ?? '#' }}" target="_blank"
                   class="flex items-center p-2 space-x-2 hover:bg-gray-100 transition-colors duration-200 ease-in-out">
                    <!-- Icône -->
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" width="25" height="25"
                         fill="currentColor" class="text-gray-700">
                        <path d="M579.8 267.7c56.5-56.5 56.5-148 0-204.5c-50-50-128.8-56.5-186.3-15.4l-1.6 1.1c-14.4 10.3-17.7 30.3-7.4 44.6s30.3 17.7 44.6 7.4l1.6-1.1c32.1-22.9 76-19.3 103.8 8.6c31.5 31.5 31.5 82.5 0 114L422.3 334.8c-31.5 31.5-82.5 31.5-114 0c-27.9-27.9-31.5-71.8-8.6-103.8l1.1-1.6c10.3-14.4 6.9-34.4-7.4-44.6s-34.4-6.9-44.6 7.4l-1.1 1.6C206.5 251.2 213 330 263 380c56.5 56.5 148 56.5 204.5 0L579.8 267.7zM60.2 244.3c-56.5 56.5-56.5 148 0 204.5c50 50 128.8 56.5 186.3 15.4l1.6-1.1c14.4-10.3 17.7-30.3 7.4-44.6s-30.3-17.7-44.6-7.4l-1.6 1.1c-32.1 22.9-76 19.3-103.8-8.6C74 372 74 321 105.5 289.5L217.7 177.2c31.5-31.5 82.5-31.5 114 0c27.9 27.9 31.5 71.8 8.6 103.9l-1.1 1.6c-10.3 14.4-6.9 34.4 7.4 44.6s34.4 6.9 44.6-7.4l1.1-1.6C433.5 260.8 427 182 377 132c-56.5-56.5-148-56.5-204.5 0L60.2 244.3z"/>
                    </svg>
                    <!-- Texte -->
                    <p class="text-gray-700">{{ $link['nom'] ?? 'Lien personnalisé' }}</p>
                </a>
            @endforeach
        </div>
    </div>
@endif

<footer class=" text-center p-4 text-white text-sm mt-6">
    © {{ date('Y') }} - Un service proposé par
    <a href="https://sendix.fr" class="text-blue-400 hover:underline">SENDIX</a> -
    <a href="https://wisikard.fr" class="text-blue-400 hover:underline">Wisikard</a>
</footer>

<script>
    // **Script pour le Modal QR Code**
    function openQrModal() {
        const qrModal = document.getElementById('qrModal');
        qrModal.classList.remove('hidden');
        qrModal.classList.add('flex');
    }

    function closeQrModal() {
        const qrModal = document.getElementById('qrModal');
        qrModal.classList.add('hidden');
        qrModal.classList.remove('flex');
    }

    // **Script pour le Modal Horaires**
    function openModal() {
        const modal = document.getElementById('horairesModal');
        modal.classList.remove('hidden');
    }

    function closeModal() {
        const modal = document.getElementById('horairesModal');
        modal.classList.add('hidden');
    }

    // **Script pour la copie du lien**
    function shareOrCopyLink() {
        const linkToShare = "{{ url()->current().'?idCompte='.$carte->compte->idCompte }}";

        if (navigator.share) {
            // Partage natif si disponible
            navigator.share({
                title: document.title,
                text: "Découvrez cette entreprise sur Wisikard !",
                url: linkToShare
            }).then(() => {
                console.log("Lien partagé avec succès !");
            }).catch(err => {
                console.error("Erreur lors du partage :", err);
            });
        } else {
            // Fallback : copie dans le presse-papier
            navigator.clipboard.writeText(linkToShare).then(() => {
                alert("Lien copié dans le presse-papier !");
            }).catch(err => {
                console.error("Erreur lors de la copie :", err);
            });
        }
    }

    var map = L.map('map').setView([48.8566, 2.3522], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    async function rechercherEntreprise() {
        const nom = "{{ $carte['nomEntreprise'] }}";
        const ville = "{{ $carte['ville'] }}";
        console.log(nom, ville);
        try {
            // Étape 1 : Recherche avec nom + ville
            const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${nom},${ville}`);
            const data = await response.json();
            if (data.length > 0) {
                // Si une localisation est trouvée
                const location = data[0];
                const lat = location.lat;
                const lon = location.lon;
                map.setView([lat, lon], 15);
                L.marker([lat, lon]).addTo(map);
            } else {
                console.log("Aucune entreprise trouvée avec le nom, recherche uniquement avec la ville.");
                // Étape 2 : Si la recherche échoue, faire une recherche uniquement avec la ville
                const responseVille = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${ville}`);
                const dataVille = await responseVille.json();
                if (dataVille.length > 0) {
                    const location = dataVille[0];
                    const lat = location.lat;
                    const lon = location.lon;
                    map.setView([lat, lon], 13); // Zoom différent pour une localisation de ville
                    L.marker([lat, lon]).addTo(map);
                    console.log("Localisation trouvée pour la ville.");
                } else {
                    console.log("Aucune information trouvée pour cette ville.");
                }
            }
        } catch (error) {
            console.error("Erreur lors de la recherche d'entreprise ou d'adresse :", error);
        }
    }

    // Appel de la fonction de recherche au chargement de la page
    rechercherEntreprise();


    function openVideoGallery() {
        document.getElementById('videoGallery').classList.remove('hidden');
    }

    function closeVideoGallery() {
        document.getElementById('videoGallery').classList.add('hidden');
    }

    document.querySelectorAll('.wk-button').forEach(button => {
        button.addEventListener('mouseenter', () => {
            button.style.transform = 'translateY(-2px)';
        });
        
        button.addEventListener('mouseleave', () => {
            button.style.transform = 'translateY(0)';
        });
    });
</script>

</body>
</html>
