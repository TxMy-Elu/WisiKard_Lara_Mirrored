<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $carte['nomEntreprise'] ? $carte['nomEntreprise'] . ' - ' : '' }} - Wisikard</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i%7CQuicksand:300,400,500,700" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: {{ $carte['couleur1'] }};
            --secondary-color: {{ $carte['couleur2'] }};
            --background-color: #ffffff;
            --text-color: #1d1d1f;
            --accent-color: #06c;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'SF Pro Display', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--background-color);
            color: var(--text-color);
            line-height: 1.47059;
            font-weight: 400;
            letter-spacing: -.022em;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            max-width: 980px;
            width: 90%;
            margin: 2rem auto;
            text-align: center;
        }

        .logo {
            max-width: 120px;
            margin-bottom: 2rem;
        }

        h1 {
            font-size: 56px;
            line-height: 1.07143;
            font-weight: 600;
            letter-spacing: -.005em;
            margin-bottom: 1rem;
            color: var(--primary-color);
        }

        h2 {
            font-size: 28px;
            line-height: 1.10722;
            font-weight: 400;
            letter-spacing: .004em;
            margin-bottom: 1rem;
        }

        p {
            font-size: 17px;
            line-height: 1.47059;
            font-weight: 400;
            letter-spacing: -.022em;
            margin-bottom: 2rem;
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
            align-items: center;
            text-decoration: none;
            color: var(--accent-color);
            font-size: 17px;
            line-height: 1.23536;
            font-weight: 400;
            letter-spacing: -.022em;
            padding: 8px 16px;
            border-radius: 980px;
            background-color: rgba(0, 0, 0, 0.05);
            transition: background-color 0.3s ease;
        }

        .contacts a:hover {
            background-color: rgba(0, 0, 0, 0.1);
        }

        .contacts lord-icon {
            width: 24px;
            height: 24px;
            margin-right: 8px;
        }

        .socials {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .socials svg {
            width: 24px;
            height: 24px;
            fill: var(--text-color);
            transition: fill 0.3s ease;
        }

        .socials svg:hover {
            fill: var(--accent-color);
        }

        #embedyoutube {
            width: 100%;
            max-width: 560px;
            aspect-ratio: 16 / 9;
            margin-bottom: 2rem;
            border-radius: 18px;
            overflow: hidden;
        }

        .affiche {
            max-width: 100%;
            height: auto;
            border-radius: 18px;
            margin-bottom: 2rem;
        }

        footer {
            margin-top: auto;
            padding: 1rem;
            text-align: center;
            font-size: 12px;
            line-height: 1.33337;
            font-weight: 400;
            letter-spacing: -.01em;
            color: #86868b;
        }

        footer a {
            color: var(--accent-color);
            text-decoration: none;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.6s ease-out;
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
            background-color: rgba(0,0,0,0.5);
            backdrop-filter: blur(10px);
        }

        .modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px;
            border-radius: 18px;
            max-width: 300px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        .close {
            color: #86868b;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .close:hover,
        .close:focus {
            color: var(--text-color);
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
               class='m-2 p-2 bg-indigo-500/50 backdrop-filter backdrop-blur-md rounded-md text-center'>
                {{ $employe['mail'] }}
            </a>
            <a href='tel:{{ $employe['telephone'] }}'
               class='m-2 p-2 bg-indigo-500/50 backdrop-filter backdrop-blur-md rounded-md text-center'>
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
                class='m-2.5 p-2 bg-indigo-500/50 backdrop-filter backdrop-blur-md rounded-xl flex items-center justify-center'>
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
                        class="absolute bg-indigo-500/50 top-2 right-2 bg-red-500 text-white px-2 py-1 rounded-full">✖
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
           class='m-2.5 p-2 bg-indigo-500/50 backdrop-filter backdrop-blur-md rounded-xl flex items-center justify-center'>
            <lord-icon
                    src="https://cdn.lordicon.com/surcxhka.json"
                    trigger="loop"
                    delay="1000"
                    colors="primary:#F5F5F5,secondary:{{ $carte['couleur1'] }}">
            </lord-icon>
            Maps
        </a>

        <a href="{{ $carte['lienSite'] }}"
           class='m-2.5 p-2 bg-indigo-500/50 backdrop-filter backdrop-blur-md rounded-xl flex items-center justify-center'>
            <lord-icon
                    src="https://cdn.lordicon.com/pbbsmkso.json"
                    trigger="loop"
                    delay="1000"
                    colors="primary:#F5F5F5,secondary:{{ $carte['couleur1'] }}">
            </lord-icon>
            Site
        </a>

        <a href="tel:{{ $carte['tel'] }}"
           class='m-2.5 p-2 bg-indigo-500/50 backdrop-filter backdrop-blur-md rounded-xl flex items-center justify-center'>
            <lord-icon
                    src="https://cdn.lordicon.com/qtykvslf.json"
                    trigger="loop"
                    delay="1000"
                    colors="primary:#F5F5F5,secondary:{{ $carte['couleur1'] }}">
            </lord-icon>
            Téléphone
        </a>

        <a href="mailto:{{ $carte['mailContact'] }}"
           class='m-2.5 p-2 bg-indigo-500/50 backdrop-filter backdrop-blur-md rounded-xl flex items-center justify-center'>
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
               class='m-2.5 p-2 bg-indigo-500/50 backdrop-filter backdrop-blur-md rounded-xl flex items-center justify-center'>
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
           class='m-2.5 p-2 bg-indigo-500/50 backdrop-filter backdrop-blur-md rounded-xl flex items-center justify-center'>
            <lord-icon
                    src="https://cdn.lordicon.com/odavpkmb.json"
                    trigger="loop"
                    delay="1000"
                    colors="primary:#F5F5F5,secondary:{{ $carte['couleur1'] }}">
            </lord-icon>{{ $carte['nomButtonCommande'] }}
        </a>

        <a href="{{ '/entreprises/'. $carte->compte->idCompte.'_'.$carte->nomEntreprise.'/VCF_Files/contact.vcf' }}"
           download="Contact-Wisikard.vcf"
           class='m-2.5 p-2 bg-indigo-500/50 backdrop-filter backdrop-blur-md rounded-xl flex items-center justify-center'>
            <lord-icon src="https://cdn.lordicon.com/rehjpyyh.json"
                       trigger="loop"
                       delay="1000"
                       state="hover-portrait"
                       colors="primary:#F5F5F5,secondary:{{ $carte['couleur1'] }}">
            </lord-icon>
            Vcard
        </a>

        <a id="prompt"
           class='m-2.5 p-2 bg-indigo-500/50 backdrop-filter backdrop-blur-md rounded-xl flex items-center justify-center'>
            <lord-icon src="https://cdn.lordicon.com/dxnllioo.json"
                       trigger="loop"
                       delay="1000"
                       state="loop-slide"
                       colors="primary:#F5F5F5,secondary:{{ $carte['couleur1'] }}">
            </lord-icon>
            Installer
        </a>

        <button class='m-2.5 p-2 bg-indigo-500/50 backdrop-filter backdrop-blur-md rounded-xl flex items-center justify-center'>
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
</div>

<footer class="text-center p-4">
    Un service proposé par <a href="https://sendix.fr" class="text-blue-500">SENDIX</a> - <a href="https://wisikard.fr" class="text-blue-500">Wisikard</a>
</footer>
</body>
</html>
