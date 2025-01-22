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


    <div class='flex justify-center items-center w-24'>
        <img class='w-4/5 max-w-xl mt-6'  src="{{ '/entreprises/'. $carte->compte->idCompte.'_'.$carte->nomEntreprise.'/logos/logo.png' }}" alt='Logo'>
    </div>



    <div class='flex justify-center items-center'>
        <h1 class='mt-1'>{{ $carte['titre'] }}</h1>
    </div>



    <div class='flex justify-center items-center'>
        <h2 class='text-lg font-light'>{{ $employe['nom'] }} {{ $employe['prenom'] }} - {{ $employe['fonction'] }}</h2>
    </div>


        <div class='flex justify-center items-center flex-wrap'>
                <a href='mailto:{{ $employe['mail'] }}' class='m-2 p-2 bg-white bg-opacity-20 backdrop-filter backdrop-blur-md rounded-md'>{{ $employe['mail'] }}</a>
        <a href='tel:{{ $employe['telephone'] }}' class='m-2 p-2 bg-white bg-opacity-20 backdrop-filter backdrop-blur-md rounded-md'>{{ $employe['telephone'] }}</a>

        </div>

    <div class='flex justify-center items-center'>
        <p class='text-base mt-1.5'>{{ $carte->descriptif }}</p>
    </div>


<div class='flex justify-evenly flex-wrap mx-11 mb-0'>
    <a onclick="showQr()" class='m-2.5 p-2 bg-white bg-opacity-20 backdrop-filter backdrop-blur-md rounded-xl flex items-center justify-center'>
        <lord-icon src="https://cdn.lordicon.com/avcjklpr.json"
                   trigger="loop"
                   delay="1000"
                   colors="primary:#F5F5F5,secondary:{{ $carte['couleur1'] }}">
        </lord-icon>QRCode
    </a>

    @if($carte['nomEntreprise'] && $carte['ville'])
        <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($carte['nomEntreprise'] . ' ' . $carte['ville']) }}" class='m-2.5 p-2 bg-white bg-opacity-20 backdrop-filter backdrop-blur-md rounded-xl flex items-center justify-center'>
            <lord-icon
                    src="https://cdn.lordicon.com/surcxhka.json"
                    trigger="loop"
                    delay="1000"
                    colors="primary:#F5F5F5,secondary:{{ $carte['couleur1'] }}">
            </lord-icon>Maps
        </a>
    @endif

    @if($carte['lienSite'])
        <a href="{{ $carte['lienSite'] }}" class='m-2.5 p-2 bg-white bg-opacity-20 backdrop-filter backdrop-blur-md rounded-xl flex items-center justify-center'>
            <lord-icon
                    src="https://cdn.lordicon.com/pbbsmkso.json"
                    trigger="loop"
                    delay="1000"
                    colors="primary:#F5F5F5,secondary:{{ $carte['couleur1'] }}">
            </lord-icon>Site
        </a>
    @endif

    @if($carte['tel'])
        <a href="tel:{{ $carte['tel'] }}" class='m-2.5 p-2 bg-white bg-opacity-20 backdrop-filter backdrop-blur-md rounded-xl flex items-center justify-center'>
            <lord-icon
                    src="https://cdn.lordicon.com/qtykvslf.json"
                    trigger="loop"
                    delay="1000"
                    colors="primary:#F5F5F5,secondary:{{ $carte['couleur1'] }}">
            </lord-icon>Téléphone
        </a>
    @endif

    @if($carte['mailContact'])
        <a href="mailto:{{ $carte['mailContact'] }}" class='m-2.5 p-2 bg-white bg-opacity-20 backdrop-filter backdrop-blur-md rounded-xl flex items-center justify-center'>
            <lord-icon
                    src="https://cdn.lordicon.com/aycieyht.json"
                    trigger="loop"
                    delay="1000"
                    colors="primary:#F5F5F5,secondary:{{ $carte['couleur1'] }}">
            </lord-icon>Mail
        </a>
    @endif

    @if($carte['pdf'])
        <a href="{{ asset('storage/pdf/' . $carte['pdf']) }}" target="_blank" rel="noopener noreferrer" class='m-2.5 p-2 bg-white bg-opacity-20 backdrop-filter backdrop-blur-md rounded-xl flex items-center justify-center'>
            <lord-icon
                    src="https://cdn.lordicon.com/wzwygmng.json"
                    trigger="loop"
                    delay="1000"
                    colors="primary:#F5F5F5,secondary:{{ $carte['couleur1'] }}">
            </lord-icon>
            {{ $carte['nomBtnPdf'] ?? 'Voir PDF' }}
        </a>
    @endif

    @if($carte['LienCommande'] && $carte['nomButtonCommande'])
        <a href="{{ $carte['LienCommande'] }}" class='m-2.5 p-2 bg-white bg-opacity-20 backdrop-filter backdrop-blur-md rounded-xl flex items-center justify-center'>
            <lord-icon
                    src="https://cdn.lordicon.com/odavpkmb.json"
                    trigger="loop"
                    delay="1000"
                    colors="primary:#F5F5F5,secondary:{{ $carte['couleur1'] }}">
            </lord-icon>{{ $carte['nomButtonCommande'] }}
        </a>
    @endif

    <a href="{{ asset('storage/vcard/' . $carte['idCarte'] . '.vcf') }}" download="Contact-Wisikard.vcf" class='m-2.5 p-2 bg-white bg-opacity-20 backdrop-filter backdrop-blur-md rounded-xl flex items-center justify-center'>
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
    <div id="ios-prompt" style="display: none;">
        <p>Pour installer cette application, appuyez sur <strong>l'icône de partage</strong> puis sur <strong>Ajouter à l'écran d'accueil</strong>.</p>
    </div>

    <a onclick="share()" class='m-2.5 p-2 bg-white bg-opacity-20 backdrop-filter backdrop-blur-md rounded-xl flex items-center justify-center'>
        <lord-icon src="https://cdn.lordicon.com/udwhdpod.json"
                   trigger="loop"
                   delay="1000"
                   colors="primary:#F5F5F5,secondary:{{ $carte['couleur1'] }}">
        </lord-icon>Partager
    </a>
</div>

<div class='flex justify-evenly flex-wrap mx-5 my-2.5 bg-white bg-opacity-20 backdrop-filter backdrop-blur-md rounded-xl'>
    @foreach($logoSocial as $so)
        <a href="{{ $so['lien'] }}" class='p-2'>
            {!! $so['lienLogo'] !!}
        </a>
    @endforeach
</div>

@if($carte['imgPres'])
    <div class='flex justify-center items-center'>
        <img class='max-w-4/5 max-h-1/3' src="{{ asset('storage/image/' . $carte['imgPres']) }}" alt='Image'>
    </div>
@endif

@if($embedyoutube && $embedyoutube['option'])
    @php
        $video = "";
        if (str_contains($embedyoutube['option'], 'youtu.be')) {
            $x = explode('/', $embedyoutube['option']);
            $x = end($x);
            $video = explode('?', $x)[0];
        } else {
            $video = explode('&', (explode('?v=', $embedyoutube['option']))[1])[0];
        }
    @endphp
    <div class='flex justify-center items-center'>
        <iframe id='embedyoutube' class='w-4/5 my-5' type='text/html' frameborder='0' src='https://www.youtube.com/embed/{{ $video }}'></iframe>
    </div>
@endif

@if(!$nopub)
    @include('ads')
@endif

<div id='qrModal' class="modal">
    <div class="modalBody">
        <div class="modalTitle">
            <div class="modalTitleTxt">
                QR code de {{ $carte['nomEntreprise'] }}
            </div>
            <div class="modalCloseBtn" onclick="hideQr()">
                ✖
            </div>
        </div>
        <div class="modalContent">
            <img class="modalQR" src="{{ asset('storage/qr/' . ($employe ? $carte['idCarte'].'-'.$employe['idEmp'].'.png' : $carte['idCarte'].'.png')) }}" alt="">
        </div>
    </div>
</div>

<footer class="text-center p-4">
    Un service proposé par <a href="https://sendix.fr" class="text-blue-500">SENDIX</a> - <a href="https://wisikard.fr" class="text-blue-500">Wisikard</a>
</footer>
</body>

</html>
