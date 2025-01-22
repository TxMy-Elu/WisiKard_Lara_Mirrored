<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $carte['nomEntreprise'] ? $carte['nomEntreprise'] . ' - ' : '' }} - Wisikard</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i%7CQuicksand:300,400,500,700" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        *:not(.material-icons, .fa-brands) {
            font-family: 'Open Sans'!important;
            color: whitesmoke;
        }

        body {
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
            height: 100vh;
            margin: 0;
            max-width: 100%;
        }

        @keyframes gradient {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }

        .modal {
            position: fixed;
            width: 100%;
            top: 20%;
            z-index: 1055;
            display: none;
            outline: 0;
            transition: 500ms;
        }

        .modalBody {
            background-color: white;
            z-index: 1055;
            display: flex;
            flex-direction: column;
        }

        .modalTitle {
            max-height: 50px;
            max-width: 80%;
            height: 50px;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            padding: 20px;
            padding-bottom: 0px;
            padding-right: 0px;
        }

        .modalTitleTxt {
            color: black;
        }

        .modalCloseBtn {
            color: black;
            cursor: default;
            position: absolute;
            right: 8%;
            font-size: 25px;
        }

        .modalContent {
            margin-bottom: 0%;
        }

        .modalQR {
            max-width: 100%;
            max-height: 100%;
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
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-080RS8FYWX');
    </script>
</head>

<body class="bg-[#9b8a7f]">
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


<div class="flex flex-col items-center w-full">
    <div class='flex justify-center items-center w-24 mt-6'>
        <img class='w-4/5 max-w-xl' src="{{ '/entreprises/'. $carte->compte->idCompte.'_'.$carte->nomEntreprise.'/logos/logo.png' }}" alt='Logo'>
    </div>

    <div class='flex justify-center items-center mt-2'>
        <h1 class='text-2xl font-bold'>{{ $carte['titre'] }}</h1>
    </div>

    <div class='flex justify-center items-center mt-1'>
        <h2 class='text-lg font-light'>{{ $employe['nom'] }} {{ $employe['prenom'] }} - {{ $employe['fonction'] }}</h2>
    </div>

    <div class='flex justify-center items-center flex-wrap mt-4'>
        <a href='mailto:{{ $employe['mail'] }}' class='m-2 p-2 bg-white bg-opacity-20 backdrop-filter backdrop-blur-md rounded-md text-center'>
            {{ $employe['mail'] }}
        </a>
        <a href='tel:{{ $employe['telephone'] }}' class='m-2 p-2 bg-white bg-opacity-20 backdrop-filter backdrop-blur-md rounded-md text-center'>
            {{ $employe['telephone'] }}
        </a>
    </div>

    <div class='flex justify-center items-center mt-2.5'>
        <p class='text-base text-center'>{{ $carte->descriptif }}</p>
    </div>

    <div class='flex justify-center items-center flex-wrap mt-4 mx-11'>
        <button class='m-2.5 p-2 bg-white bg-opacity-20 backdrop-filter backdrop-blur-md rounded-xl flex items-center justify-center'>
            <lord-icon src="https://cdn.lordicon.com/avcjklpr.json"
                       trigger="loop"
                       delay="1000"
                       colors="primary:#F5F5F5,secondary:{{ $carte['couleur1'] }}">
            </lord-icon>QRCode
        </button>

        <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($carte['nomEntreprise'] . ' ' . $carte['ville']) }}"
           class='m-2.5 p-2 bg-white bg-opacity-20 backdrop-filter backdrop-blur-md rounded-xl flex items-center justify-center'>
            <lord-icon
                    src="https://cdn.lordicon.com/surcxhka.json"
                    trigger="loop"
                    delay="1000"
                    colors="primary:#F5F5F5,secondary:{{ $carte['couleur1'] }}">
            </lord-icon>Maps
        </a>

        <a href="{{ $carte['lienSite'] }}" class='m-2.5 p-2 bg-white bg-opacity-20 backdrop-filter backdrop-blur-md rounded-xl flex items-center justify-center'>
            <lord-icon
                    src="https://cdn.lordicon.com/pbbsmkso.json"
                    trigger="loop"
                    delay="1000"
                    colors="primary:#F5F5F5,secondary:{{ $carte['couleur1'] }}">
            </lord-icon>Site
        </a>

        <a href="tel:{{ $carte['tel'] }}" class='m-2.5 p-2 bg-white bg-opacity-20 backdrop-filter backdrop-blur-md rounded-xl flex items-center justify-center'>
            <lord-icon
                    src="https://cdn.lordicon.com/qtykvslf.json"
                    trigger="loop"
                    delay="1000"
                    colors="primary:#F5F5F5,secondary:{{ $carte['couleur1'] }}">
            </lord-icon>Téléphone
        </a>

        <a href="mailto:{{ $carte['mailContact'] }}" class='m-2.5 p-2 bg-white bg-opacity-20 backdrop-filter backdrop-blur-md rounded-xl flex items-center justify-center'>
            <lord-icon
                    src="https://cdn.lordicon.com/aycieyht.json"
                    trigger="loop"
                    delay="1000"
                    colors="primary:#F5F5F5,secondary:{{ $carte['couleur1'] }}">
            </lord-icon>Mail
        </a>

        @if($carte['pdf'])
            <a href="#" target="_blank" rel="noopener noreferrer" class='m-2.5 p-2 bg-white bg-opacity-20 backdrop-filter backdrop-blur-md rounded-xl flex items-center justify-center'>
                <lord-icon
                        src="https://cdn.lordicon.com/wzwygmng.json"
                        trigger="loop"
                        delay="1000"
                        colors="primary:#F5F5F5,secondary:{{ $carte['couleur1'] }}">
                </lord-icon>
                {{ $carte['nomBtnPdf'] ?? 'Voir PDF' }}
            </a>
        @endif

        <a href="#" class='m-2.5 p-2 bg-white bg-opacity-20 backdrop-filter backdrop-blur-md rounded-xl flex items-center justify-center'>
            <lord-icon
                    src="https://cdn.lordicon.com/odavpkmb.json"
                    trigger="loop"
                    delay="1000"
                    colors="primary:#F5F5F5,secondary:{{ $carte['couleur1'] }}">
            </lord-icon>{{ $carte['nomButtonCommande'] }}
        </a>

        <a href="#" download="Contact-Wisikard.vcf" class='m-2.5 p-2 bg-white bg-opacity-20 backdrop-filter backdrop-blur-md rounded-xl flex items-center justify-center'>
            <lord-icon src="https://cdn.lordicon.com/rehjpyyh.json"
                       trigger="loop"
                       delay="1000"
                       state="hover-portrait"
                       colors="primary:#F5F5F5,secondary:{{ $carte['couleur1'] }}">
            </lord-icon>Vcard
        </a>

        <a id="prompt" class='m-2.5 p-2 bg-white bg-opacity-20 backdrop-filter backdrop-blur-md rounded-xl flex items-center justify-center'>
            <lord-icon src="https://cdn.lordicon.com/dxnllioo.json"
                       trigger="loop"
                       delay="1000"
                       state="loop-slide"
                       colors="primary:#F5F5F5,secondary:{{ $carte['couleur1'] }}">
            </lord-icon>Installer
        </a>

        <button class='m-2.5 p-2 bg-white bg-opacity-20 backdrop-filter backdrop-blur-md rounded-xl flex items-center justify-center'>
            <lord-icon src="https://cdn.lordicon.com/udwhdpod.json"
                       trigger="loop"
                       delay="1000"
                       colors="primary:#F5F5F5,secondary:{{ $carte['couleur1'] }}">
            </lord-icon>Partager
        </button>
    </div>

    <div class="flex justify-center flex-wrap mx-5 my-4 bg-[#342d29] bg-opacity-80 backdrop-blur-lg rounded-lg p-4">
        @foreach($mergedSocial as $so)
            <a href="{{ $so['lien'] }}" target="_blank" rel="noopener noreferrer" class="p-3 hover:scale-110 transform transition duration-200">
                <div class="flex items-center justify-center">
                    <div class="w-12 h-12 flex items-center justify-center ">
                        {!! $so['logo'] !!}
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</div>

<footer class="text-center p-4">
    Un service proposé par <a href="https://sendix.fr" class="text-blue-500">SENDIX</a> - <a href="https://wisikard.fr" class="text-blue-500">Wisikard</a>
</footer>
</body>

</html>
