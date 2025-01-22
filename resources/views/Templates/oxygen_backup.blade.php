<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $site['nomEntreprise'] ? $site['nomEntreprise'] . ' - ' : '' }} - Wisikard</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i%7CQuicksand:300,400,500,700" rel="stylesheet">
    <style>
        *:not(.material-icons, .fa-brands) {
            font-family: 'Open Sans'!important;
            color:whitesmoke;
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


        .container {
            display: flex;
            text-align: center;
            justify-content: center;
        }

        .container h1 {
            margin: 5px 0 0 0;
        }

        .container h2 {
            margin: 0;
            font-size: large;
            font-weight: 300;
        }

        .container p {
            margin: 6px 0;
            font-size: medium;
        }

        .logo {
            margin-top: 25px;    
            width: 80%;
            max-width: 600px;
        }

        .socials {
            margin:10px 5px 20px;
            backdrop-filter: blur(10px);
            background-color : rgba(0, 0, 0, 0.2);
            border-radius: 15px;
            display: flex;
            justify-content: space-evenly;
            flex-wrap: wrap;
        }

        .socials a {
            padding: 8px;
        }

        .socials svg{
            max-height: 30px;
            width: 100%;
        }

        .contacts {
            display: flex;
            justify-content: space-evenly;
            flex-wrap: wrap;
            margin: 30px 45px 0 45px;
        }


        lord-icon{
            height: 50px;
            width: 50px;
        }

        .contacts a {
            backdrop-filter: blur(10px);
            background-color : rgba(255, 255, 255, 0.2);
            border-radius: 5px;
            height: 55px;
            width: 55px;
            margin: 5px 2px 30px 2px;
            font-size:small;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
        }

        #prompt lord-icon {
            transform: rotate(180deg);
        }

        #embedyoutube {
            max-width:80%;
            margin:20px 0;
        }

        .container .affiche {
            max-width: 90%;
            max-height: 30%;
        }

        .modalBody {
            background-color: white;
            z-index: 1055;
            display: flex;
            flex-direction: column;
        }


        .modal {
            /* display: flex; */
            text-align: center;
            justify-content: center;
            position: fixed;
            width: 100%;
            top: 20%;
            z-index: 1055;
            display: none;
            outline: 0;
            transition: 500ms;
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
            /* padding: 5rem 1rem; */
            padding: 20px;
            padding-bottom: 0px;
            padding-right: 0px;
        }

        .modalTitleTxt {
            color:black;
        }

        .modalCloseBtn {
            color:black;
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

        .contactEmploye a{
            text-decoration: none;
            backdrop-filter: blur(10px);
            background-color : rgba(255, 255, 255, 0.2);
            border-radius: 5px;
            padding: 8px;
            margin: 8px;
        }

        .contactEmploye {
            flex-wrap: wrap;
        }
    </style>
    <link rel="shortcut icon" href="{{ asset('assets/img/favicon.png') }}" type="image/x-icon">
    <script src="https://cdn.lordicon.com/lordicon.js"></script>

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/icons/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/icons/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/icons/favicon-16x16.png') }}">
    <link rel="mask-icon" href="{{ asset('assets/icons/safari-pinned-tab.svg') }}" color="#ff0000">
    <link rel="shortcut icon" href="{{ asset('assets/icons/favicon.ico') }}">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="msapplication-config" content="{{ asset('assets/icons/browserconfig.xml') }}">
    <meta name="theme-color" content="#ffffff">

    <link rel="manifest" href="{{ asset('storage/manifest/' . $site['idSite'] . '-manifest.json') }}">

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-080RS8FYWX"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
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

    @if($site['logo'])
        <div class='container'>
            <img class='logo' src="{{ asset('storage/logo/' . $site['logo']) }}" alt='Logo'>
        </div>
    @endif

    @if($site['titre'])
        <div class='container'>
            <h1>{{ $site['titre'] }}</h1>
        </div>
    @endif

    @if($employe)
        <div class='container'>
            <h2>{{ $employe['nom'] }} {{ $employe['prenom'] }} - {{ $employe['fonction'] }}</h2>
        </div>

        @if($employe['email'] || $employe['telephone'])
            <div class='container contactEmploye'>
                @if($employe['email'])
                    <a href='mailto:{{ $employe['email'] }}'>{{ $employe['email'] }}</a>
                @endif

                @if($employe['telephone'])
                    <a href='tel:{{ $employe['telephone'] }}'>{{ $employe['telephone'] }}</a>
                @endif
            </div>
        @endif
    @endif

    @if($site['descriptif'])
        <div class='container'>
            <p>{{ $site['descriptif'] }}</p>
        </div>
    @endif

    <div class='contacts'>
        <a onclick="showQr()">
            <lord-icon src="https://cdn.lordicon.com/avcjklpr.json"
                trigger="loop"
                delay="1000"
                colors="primary:#F5F5F5,secondary:{{ $site['couleur1'] }}">
            </lord-icon>QRCode
        </a>

        @if($site['nomEntreprise'] && $site['ville'])
            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($site['nomEntreprise'] . ' ' . $site['ville']) }}">
                <lord-icon
                    src="https://cdn.lordicon.com/surcxhka.json"
                    trigger="loop"
                    delay="1000"
                    colors="primary:#F5F5F5,secondary:{{ $site['couleur1'] }}">
                </lord-icon>Maps
            </a>
        @endif

        @if($site['lienSite'])
            <a href="{{ $site['lienSite'] }}">
                <lord-icon
                    src="https://cdn.lordicon.com/pbbsmkso.json"
                    trigger="loop"
                    delay="1000"
                    colors="primary:#F5F5F5,secondary:{{ $site['couleur1'] }}">
                </lord-icon>Site
            </a>
        @endif

        @if($site['tel'])
            <a href="tel:{{ $site['tel'] }}">
                <lord-icon
                    src="https://cdn.lordicon.com/qtykvslf.json"
                    trigger="loop"
                    delay="1000"
                    colors="primary:#F5F5F5,secondary:{{ $site['couleur1'] }}">
                </lord-icon>Téléphone
            </a>
        @endif

        @if($site['mailContact'])
            <a href="mailto:{{ $site['mailContact'] }}">
                <lord-icon
                    src="https://cdn.lordicon.com/aycieyht.json"
                    trigger="loop"
                    delay="1000"
                    colors="primary:#F5F5F5,secondary:{{ $site['couleur1'] }}">
                </lord-icon>Mail
            </a>
        @endif

        @if($site['pdf'])
            <a href="{{ asset('storage/pdf/' . $site['pdf']) }}" target="_blank" rel="noopener noreferrer">
                <lord-icon
                    src="https://cdn.lordicon.com/wzwygmng.json"
                    trigger="loop"
                    delay="1000"
                    colors="primary:#F5F5F5,secondary:{{ $site['couleur1'] }}">
                </lord-icon>
                {{ $site['nomButtonPdf'] ?? 'Voir PDF' }}
            </a>
        @endif

        @if($site['lienCommande'] && $site['nomButtonCommande'])
            <a href="{{ $site['lienCommande'] }}">
                <lord-icon
                    src="https://cdn.lordicon.com/odavpkmb.json"
                    trigger="loop"
                    delay="1000"
                    colors="primary:#F5F5F5,secondary:{{ $site['couleur1'] }}">
                </lord-icon>{{ $site['nomButtonCommande'] }}
            </a>
        @endif

        <a href="{{ asset('storage/vcard/' . $site['idSite'] . '.vcf') }}" download="Contact-Wisikard.vcf">
            <lord-icon src="https://cdn.lordicon.com/rehjpyyh.json"
                trigger="loop"
                delay="1000"
                state="hover-portrait"
                colors="primary:#F5F5F5,secondary:{{ $site['couleur1'] }}">
            </lord-icon>Vcard
        </a>

        <a id="prompt">
            <lord-icon src="https://cdn.lordicon.com/dxnllioo.json"
                trigger="loop"
                delay="1000"
                state="loop-slide"
                colors="primary:#F5F5F5,secondary:{{ $site['couleur1'] }}">
            </lord-icon>Installer
        </a>
        <div id="ios-prompt" style="display: none;">
            <p>Pour installer cette application, appuyez sur <strong>l'icône de partage</strong> puis sur <strong>Ajouter à l'écran d'accueil</strong>.</p>
        </div>

        <a onclick="share()">
            <lord-icon src="https://cdn.lordicon.com/udwhdpod.json"
                trigger="loop"
                delay="1000"
                colors="primary:#F5F5F5,secondary:{{ $site['couleur1'] }}">
            </lord-icon>Partager
        </a>
    </div>

    <div class='socials'>
        @foreach($socials as $so)
            <a href="{{ $so['lien'] }}">{!! $so['lienLogo'] !!}</a>
        @endforeach
    </div>

    @if($site['image'])
        <div class='container'>
            <img class='affiche' src="{{ asset('storage/image/' . $site['image']) }}" alt='Image'>
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
        <div class='container'>
            <iframe id='embedyoutube' type='text/html' frameborder='0' src='https://www.youtube.com/embed/{{ $video }}'></iframe>
        </div>
    @endif

    @if(!$nopub)
        @include('ads')
    @endif

    <div id='qrModal' class="modal">
        <div class="modalBody">
            <div class="modalTitle">
                <div class="modalTitleTxt">
                    QR code de {{ $site['nomEntreprise'] }}
                </div>
                <div class="modalCloseBtn" onclick="hideQr()">
                    ✖
                </div>
            </div>
            <div class="modalContent">
                <img class="modalQR" src="{{ asset('storage/qr/' . ($employe ? $site['idSite'].'-'.$employe['idEmploye'].'.png' : $site['idSite'].'.png')) }}" alt="">
            </div>
        </div>
    </div>

    <footer>
        Un service proposé par <a href="https://sendix.fr">SENDIX</a> - <a href="https://wisikard.fr">Wisikard</a>
    </footer>
</body>

</html>