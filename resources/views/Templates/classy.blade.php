<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $carte['nomEntreprise'] ? $carte['nomEntreprise'] . ' - ' : '' }} - Wisikard</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i%7CQuicksand:300,400,500,700"
          rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: {{ $carte["couleur1"] }};
            --secondary-color: {{ $carte["couleur2"] }};
            --background-color: #1a1a1a;
            --text-color: #ffffff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--background-color);
            color: var(--text-color);
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            animation: fadeIn 1s ease-in-out;
        }

        .container {
            max-width: 1200px;
            width: 90%;
            margin: 2rem auto;
            text-align: center;
            background: rgba(255, 255, 255, 0.1);
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .logo {
            max-width: 150px;
            margin-bottom: 2rem;
        }

        h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: white; /* Mettre le titre en blanc */
        }

        h2 {
            font-size: 2rem;
            font-weight: 400;
            margin-bottom: 1rem;
        }

        p {
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .contacts {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .contacts a {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            color: var(--text-color);
            background-color: rgba(255, 255, 255, 0.1);
            padding: 1rem;
            border-radius: 10px;
            transition: transform 0.3s ease, background-color 0.3s ease;
        }

        .contacts a:hover {
            transform: translateY(-5px);
            background-color: rgba(255, 255, 255, 0.2);
        }

        .socials {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .socials svg {
            width: 30px;
            height: 30px;
            fill: var(--primary-color);
        }

        #embedyoutube {
            width: 100%;
            max-width: 560px;
            aspect-ratio: 16 / 9;
            margin-bottom: 2rem;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .affiche {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            margin-bottom: 2rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        footer {
            margin-top: auto;
            padding: 1rem;
            text-align: center;
            background: rgba(255, 255, 255, 0.1);
            width: 100%;
        }

        footer a {
            color: var(--primary-color);
            text-decoration: none;
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
            }
            100% {
                opacity: 1;
            }
        }

        /* Styles pour le modal QR code */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border-radius: 10px;
            max-width: 300px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }

        .hidden {
            display: none;
        }

        #galleryModal img.active {
            display: block;
        }

    </style>
    <link rel="shortcut icon" href="{{ asset('assets/img/favicon.png') }}" type="image/x-icon">
    <script src="https://cdn.lordicon.com/lordicon.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/icons/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/icons/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/icons/favicon-16x16.png') }}">
    <link rel="mask-icon" href="{{ asset('assets/icons/safari-pinned-tab.svg') }}" color="#ff0000">
    <link rel="shortcut icon" href="{{ asset('assets/icons/favicon.ico') }}">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="msapplication-config" content="{{ asset('assets/icons/browserconfig.xml') }}">
    <meta name="theme-color" content="#ffffff">

    <link rel="manifest" href="{{ asset('storage/manifest/' . $carte['idCarte'] . '-manifest.json') }}">

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-080RS8FYWX"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());
        gtag('config', 'G-080RS8FYWX');


    </script>
</head>
<body>
@php
    $nopub = false;
    $embedyoutube = null;
    foreach ($fonctions as $f) {
        if ($f['nom'] == 'nopub') {
            $nopub = true;
        }
        if ($f['nom'] == 'embedyoutube') {
            $embedyoutube = $f;
        }
    }
@endphp

@php
    // Détection des différents types de fichiers
    $logoPath = '';
    $formats = ['svg', 'png', 'jpg', 'jpeg']; // Ajouter d'autres formats si nécessaire
    foreach ($formats as $format) {
        $path = public_path('entreprises/' . $carte->compte->idCompte . '_' . $carte->nomEntreprise . '/logos/logo.' . $format);
        if (file_exists($path)) {
            $logoPath = asset('entreprises/' . $carte->compte->idCompte . '_' . $carte->nomEntreprise . '/logos/logo.' . $format);
            break;
        }
    }
@endphp

<div class="flex flex-col items-center w-full">
    <div class='flex justify-center items-center w-24 mt-6'>
        <img class='w-4/5 max-w-xl'
             src="{{ $logoPath ? $logoPath : asset('images/default-logo.png') }}"
             alt='Logo'>
    </div>

    <div class='flex justify-center items-center mt-2'>
        <h1 class='text-2xl font-bold'>{{ $carte['titre'] }}</h1>
    </div>
    @if($employe != null)
        <div class='flex justify-center items-center flex-wrap mt-4'>
            <a href='mailto:{{ $employe['mail'] }}'
               class='m-2 p-2 bg-white bg-opacity-20 backdrop-filter backdrop-blur-md rounded-md text-center'>
                {{ $employe['mail'] }}
            </a>
            <a href='tel:{{ $employe['telephone'] }}'
               class='m-2 p-2 bg-white bg-opacity-20 backdrop-filter backdrop-blur-md rounded-md text-center'>
                {{ $employe['telephone'] }}
            </a>
        </div>
    @endif

    <div class='flex justify-center items-center mt-2.5'>
        <p class='text-base text-center'>{{ $carte->descriptif }}</p>
    </div>

    <div class='flex justify-center items-center flex-wrap mt-4 mx-11'>
        <!-- Bouton pour afficher le QR Code -->
        <button onclick="showQrCode()"
                class='m-2.5 p-2 bg-white bg-opacity-20 backdrop-filter backdrop-blur-md rounded-xl flex items-center justify-center'>
            <lord-icon src="https://cdn.lordicon.com/avcjklpr.json"
                       trigger="loop"
                       delay="1000"
                       colors="primary:#F5F5F5,secondary:{{ $carte['couleur1'] }}">
            </lord-icon>
            QRCode
        </button>

        <!-- Modal pour afficher le QR Code -->
        <div id="qrCodeModal"
             class="fixed top-0 left-0 w-full h-full bg-black bg-opacity-70 flex justify-center items-center z-50 hidden">
            <div class="bg-white p-6 rounded-lg shadow-lg text-center relative">
                <h3 class="text-xl font-bold mb-4 text-zinc-900">{{ $carte['nomEntreprise'] }} - QR Code</h3>
                <img src="{{ $carte->lienQr }}" alt="QR Code" class="w-48 h-48 mx-auto">
                <button onclick="closeQrCode()"
                        class="absolute top-2 right-2 bg-red-500 text-white px-2 py-1 rounded-full">✖
                </button>
            </div>
        </div>

        <!-- Script JavaScript -->
        <script>
            // Fonction pour afficher la modal
            function showQrCode() {
                document.getElementById('qrCodeModal').classList.remove('hidden');
            }

            // Fonction pour cacher la modal
            function closeQrCode() {
                document.getElementById('qrCodeModal').classList.add('hidden');
            }
        </script>

        <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($carte['nomEntreprise'] . ' ' . $carte['ville']) }}"
           class='m-2.5 p-2 bg-white bg-opacity-20 backdrop-filter backdrop-blur-md rounded-xl flex items-center justify-center'>
            <lord-icon
                    src="https://cdn.lordicon.com/surcxhka.json"
                    trigger="loop"
                    delay="1000"
                    colors="primary:#F5F5F5,secondary:{{ $carte['couleur1'] }}">
            </lord-icon>
            Maps
        </a>

        <a href="{{ $carte['lienSite'] }}"
           class='m-2.5 p-2 bg-white bg-opacity-20 backdrop-filter backdrop-blur-md rounded-xl flex items-center justify-center'>
            <lord-icon
                    src="https://cdn.lordicon.com/pbbsmkso.json"
                    trigger="loop"
                    delay="1000"
                    colors="primary:#F5F5F5,secondary:{{ $carte['couleur1'] }}">
            </lord-icon>
            Site
        </a>

        <a href="tel:{{ $carte['tel'] }}"
           class='m-2.5 p-2 bg-white bg-opacity-20 backdrop-filter backdrop-blur-md rounded-xl flex items-center justify-center'>
            <lord-icon
                    src="https://cdn.lordicon.com/qtykvslf.json"
                    trigger="loop"
                    delay="1000"
                    colors="primary:#F5F5F5,secondary:{{ $carte['couleur1'] }}">
            </lord-icon>
            Téléphone
        </a>

        <a href="mailto:{{ $carte['mailContact'] }}"
           class='m-2.5 p-2 bg-white bg-opacity-20 backdrop-filter backdrop-blur-md rounded-xl flex items-center justify-center'>
            <lord-icon
                    src="https://cdn.lordicon.com/aycieyht.json"
                    trigger="loop"
                    delay="1000"
                    colors="primary:#F5F5F5,secondary:{{ $carte['couleur1'] }}">
            </lord-icon>
            Mail
        </a>

        @if($carte['pdf'])
            <a href="{{ $carte['pdf'] }}" target="_blank" rel="noopener noreferrer"
               class='m-2.5 p-2 bg-white bg-opacity-20 backdrop-filter backdrop-blur-md rounded-xl flex items-center justify-center'>
                <lord-icon
                        src="https://cdn.lordicon.com/wzwygmng.json"
                        trigger="loop"
                        delay="1000"
                        colors="primary:#F5F5F5,secondary:{{ $carte['couleur1'] }}">
                </lord-icon>
                {{ $carte['nomBtnPdf'] ?? 'Voir PDF' }}
            </a>
        @endif

        <a href="{{$carte['lienCommande']}}"
           class='m-2.5 p-2 bg-white bg-opacity-20 backdrop-filter backdrop-blur-md rounded-xl flex items-center justify-center'>
            <lord-icon
                    src="https://cdn.lordicon.com/odavpkmb.json"
                    trigger="loop"
                    delay="1000"
                    colors="primary:#F5F5F5,secondary:{{ $carte['couleur1'] }}">
            </lord-icon>{{ $carte['nomButtonCommande'] }}
        </a>

        <a href="{{ '/entreprises/'. $carte->compte->idCompte.'_'.$carte->nomEntreprise.'/VCF_Files/contact.vcf' }}"
           download="Contact-Wisikard.vcf"
           class='m-2.5 p-2 bg-white bg-opacity-20 backdrop-filter backdrop-blur-md rounded-xl flex items-center justify-center'>
            <lord-icon src="https://cdn.lordicon.com/rehjpyyh.json"
                       trigger="loop"
                       delay="1000"
                       state="hover-portrait"
                       colors="primary:#F5F5F5,secondary:{{ $carte['couleur1'] }}">
            </lord-icon>
            Vcard
        </a>

        <a id="prompt"
           class='m-2.5 p-2 bg-white bg-opacity-20 backdrop-filter backdrop-blur-md rounded-xl flex items-center justify-center'>
            <lord-icon src="https://cdn.lordicon.com/dxnllioo.json"
                       trigger="loop"
                       delay="1000"
                       state="loop-slide"
                       colors="primary:#F5F5F5,secondary:{{ $carte['couleur1'] }}">
            </lord-icon>
            Installer
        </a>

        <button class='m-2.5 p-2 bg-white bg-opacity-20 backdrop-filter backdrop-blur-md rounded-xl flex items-center justify-center'>
            <lord-icon src="https://cdn.lordicon.com/udwhdpod.json"
                       trigger="loop"
                       delay="1000"
                       colors="primary:#F5F5F5,secondary:{{ $carte['couleur1'] }}">
            </lord-icon>
            Partager
        </button>
    </div>

    <div class="flex justify-center flex-wrap mx-5 my-4 bg-[#342d29] bg-opacity-80 backdrop-blur-lg rounded-lg p-4">
        @foreach($mergedSocial as $so)
            <a href="{{ $so['lien'] }}" target="_blank" rel="noopener noreferrer"
               class="p-3 hover:scale-110 transform transition duration-200">
                <div class="flex items-center justify-center">
                    <div class="w-12 h-12 flex items-center justify-center">
                        <!-- Apporter la couleur blanche aux logos -->
                        <div class="text-white fill-white">
                            {!! $so['logo'] !!}
                        </div>
                    </div>
                </div>
            </a>
        @endforeach
    </div>

    <!-- Affichage des images dans la galerie -->
    @php
        $sliderDirectory = public_path('entreprises/'.$carte->idCompte.'_'.$carte->nomEntreprise.'/slider');
        $sliderImages = file_exists($sliderDirectory) ? array_values(array_diff(scandir($sliderDirectory), array('.', '..'))) : [];
    @endphp


    @if(!empty($sliderImages))
        <!-- Présentation de l'album -->
        <div class="bg-slate-100 rounded-lg relative">
            <!-- Carré de quatre photos -->
            <div class="p-2 flex flex-col items-center bg-slate-100 rounded-lg" onClick="openAlbum()">
                <div class="grid grid-cols-2 gap-2">
                    @for($i = 0; $i < 4; $i++)
                        @if(isset($sliderImages[$i]))
                            <div class="relative w-24 h-24">
                                <img src="{{ asset('entreprises/'.$carte->idCompte.'_'.$carte->nomEntreprise.'/slider/'.$sliderImages[$i]) }}"
                                     class="w-full h-full object-cover rounded-md shadow-md"
                                     alt="Photo de l'album">
                            </div>
                        @else
                            <!-- Bloc fond gris s'il manque des images -->
                            <div class="relative w-24 h-24 bg-gray-400 rounded-md shadow-md flex items-center justify-center">
                            </div>
                        @endif
                    @endfor
                </div>
            </div>
            @if(count($sliderImages) > 4)
                <!-- Badge pour indiquer le surplus d'images -->
                <span class="absolute bottom-2 right-2 bg-gray-800 text-white text-xs font-semibold px-2 py-1 rounded-full">
                +{{ count($sliderImages) - 4 }}
            </span>
            @endif
        </div>

        <!-- Modale pour afficher toutes les photos -->
        <div id="albumModal" class="fixed inset-0 bg-black bg-opacity-80 z-50 hidden">
            <!-- Contenu de l'album -->
            <div class="p-6 flex flex-wrap gap-4 justify-center">
                @foreach($sliderImages as $image)
                    <div class="relative max-w-xs w-full">
                        <img src="{{ asset('entreprises/'.$carte->idCompte.'_'.$carte->nomEntreprise.'/slider/'.$image) }}"
                             class="w-full h-auto rounded-lg shadow-md"
                             alt="Image de l'album">
                    </div>
                @endforeach
            </div>

            <!-- Bouton de fermeture -->
            <button onclick="closeAlbum()"
                    class="absolute top-5 right-5 bg-red-500 text-white text-xl px-4 py-2 rounded-full">
                ✖
            </button>
        </div>
    @endif
</div>

<footer class="text-center p-4">
    Un service proposé par <a href="https://sendix.fr" class="text-blue-500">SENDIX</a> - <a
            href="https://wisikard.fr"
            class="text-blue-500">Wisikard</a>
</footer>
</body>
<script>
    function openAlbum() {
        document.getElementById('albumModal').classList.remove('hidden');
    }

    function closeAlbum() {
        document.getElementById('albumModal').classList.add('hidden');
    }
</script>

</html>
