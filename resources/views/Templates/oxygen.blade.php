<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title>{{ $carte['nomEntreprise'] ? $carte['nomEntreprise'] . ' - ' : '' }}Wisikard</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#FF0000">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="WisiKard">
    <script src="https://cdn.lordicon.com/lordicon.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Oxygen:wght@300;400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Montserrat:wght@400;700&family=Oswald:wght@400;700&family=Ubuntu:wght@400;700&family=Playfair+Display:wght@400;700&family=Work+Sans:wght@400;700&family=Bona+Nova:wght@400;700&family=Exo+2:wght@400;700&family=Pacifico&family=Gruppo&family=Rokkitt:wght@400;700&display=swap"
          rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="manifest" href="{{ '/entreprises/'. $carte->compte->idCompte.'/manifest.json' }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    <style>
        body {
            background-size: 300% 300% !important;
            animation: gradient 10s ease infinite;
            height: 100vh;
            margin: 0;
            max-width: 100%;
        }

        @keyframes gradient {
            0% {
                background-position: 0% 0%;
            }
            33% {
                background-position: 100% 50%;
            }
            66% {
                background-position: 50% 100%;
            }
            100% {
                background-position: 0% 0%;
            }
        }


        .card-container {
            display: flex;
            text-align: center;
            justify-content: center;
        }

        .card-container h1 {
            margin: 5px 0 0 0;
            font-size: 2rem;
            font-weight: bold;
        }

        .card-container h2 {
            margin: 0;
            font-size: large;
            font-weight: 300;
        }

        .card-container p {
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
            z-index: 1055;
            height: 100%;
            display: flex;
            align-items: center;
            flex-direction: column;
        }


        .modal {
            text-align: center;
            justify-content: center;
            position: fixed;
            width: 100%;
            top: 0%;
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
            padding: 20px;
            padding-bottom: 0px;
            padding-right: 0px;
        }

        .modalTitleTxt {
            color:black;
            font-size: 1.5rem;
            font-weight: bold;
            flex:auto;
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

        #installPrompt {
            display: none;
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

        .title-description-container {
            background-color: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 1rem;
            margin: 1rem;
        }

        .title-description-container p {
            font-size: 1.10rem;
        }

        .horaires-container {
            font-size: 1.2rem;
        }

        .employee-card {
            padding: 1.5rem;
            margin: 1rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .employee-name {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
            color: white;
        }

        .employee-function {
            font-size: 1.1rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 1rem;
            font-style: italic;
        }

        .employee-contact {
            display: flex;
            gap: 0.75rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .employee-contact-btn {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            transition: all 0.3s ease;
            color: white;
        }

        .employee-contact-btn:hover {
            background-color: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }

        .employee-contact-btn lord-icon {
            width: 24px;
            height: 24px;
        }
    </style>
</head>

<body class="h-100%" style="font-family: {{ $carte['font'] === 'défaut' ? 'Oxygen' : $carte['font'] }};background: linear-gradient(to top left, {{ $carte->couleur1 }}, {{ $carte->couleur2}}); min-height: fit-content;">

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

<div class="card-container flex flex-col items-center w-full fade-in">
    <div class='flex justify-center items-center mt-2 p-8'>
        @if(!empty($logoPath))
        <img class='w-4/5 max-w-xl'
             src="{{ $logoPath}}"
             alt='Logo'>
        @endif
    </div>

    <div class="title-description-container">
        <div class='flex justify-center items-center mt-1'>
            <h1 class='font-bold'>{{ $carte['titre'] }}</h1>
        </div>
        @if($employe != null)
            <div class="employee-card">
                <div class="employee-name">{{ $employe->nom }} {{ $employe->prenom }}</div>
                <div class="employee-function">{{ $employe->fonction }}</div>
                <div class="employee-contact">
                    <a href="mailto:{{ $employe['mail'] }}" class="employee-contact-btn">
                        <lord-icon
                            src="https://cdn.lordicon.com/aycieyht.json"
                            trigger="hover"
                            colors="primary:#ffffff,secondary:{{ $carte['couleur1'] }}">
                        </lord-icon>
                        {{ $employe['mail'] }}
                    </a>
                    <a href="tel:{{ $employe['telephone'] }}" class="employee-contact-btn">
                        <lord-icon
                            src="https://cdn.lordicon.com/qtykvslf.json"
                            trigger="hover"
                            colors="primary:#ffffff,secondary:{{ $carte['couleur1'] }}">
                        </lord-icon>
                        {{ $employe['telephone'] }}
                    </a>
                </div>
            </div>
        @endif

        <div class='flex justify-center items-center mt-1'>
            <p class='text-center'>{{ $carte->descriptif }}</p>
        </div>
    </div>

    <div class='flex justify-center items-center flex-wrap mt-3 mx-5'>
        <!-- Bouton pour afficher le QR Code -->
        <button onclick="showQrCode()"
                class='m-1 p-1 bg-white bg-opacity-20 backdrop-filter backdrop-blur-md rounded-xl flex items-center justify-center'>
            <lord-icon src="https://cdn.lordicon.com/avcjklpr.json"
                       trigger="loop"
                       delay="1000"
                       colors="primary:#F5F5F5,secondary:{{ $carte['couleur1'] }}">
            </lord-icon>
            QRCode
        </button>
        <!-- Modal pour le QR Code -->
        <div id='qrCodeModal' class="modal">
            <div class="modalBody  bg-opacity-80 backdrop-blur-lg">
                <div class="modalTitle">
                    <div class="modalTitleTxt">
                        QR Code de {{ $carte['nomEntreprise'] }}
                    </div>
                    <div class="modalCloseBtn" onclick="hideQrCode()">
                        ✖
                    </div>
                </div>
                <div class="modalContent">
                    <img src="{{ $carte['lienQr'] }}" alt="QR Code" class="modalQR">
                </div>
            </div>
        </div>

        <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($carte['nomEntreprise'] . ' ' . $carte['ville']) }}" target="_blank" rel="noopener noreferrer"
           class='m-1 p-1 bg-white bg-opacity-20 backdrop-filter backdrop-blur-md rounded-xl flex items-center justify-center'>
            <lord-icon
                    src="https://cdn.lordicon.com/surcxhka.json"
                    trigger="loop"
                    delay="1000"
                    colors="primary:#F5F5F5,secondary:{{ $carte['couleur1'] }}">
            </lord-icon>
            Maps
        </a>

        <a href="{{ $carte['lienSiteWeb'] }}" target="_blank" rel="noopener noreferrer"
           class='m-1 p-1 bg-white bg-opacity-20 backdrop-filter backdrop-blur-md rounded-xl flex items-center justify-center'>
            <lord-icon
                    src="https://cdn.lordicon.com/pbbsmkso.json"
                    trigger="loop"
                    delay="1000"
                    colors="primary:#F5F5F5,secondary:{{ $carte['couleur1'] }}">
            </lord-icon>
            Site
        </a>

        <a href="tel:{{ $carte['tel'] }}"
           class='m-1 p-1 bg-white bg-opacity-20 backdrop-filter backdrop-blur-md rounded-xl flex items-center justify-center'>
            <lord-icon
                    src="https://cdn.lordicon.com/qtykvslf.json"
                    trigger="loop"
                    delay="1000"
                    colors="primary:#F5F5F5,secondary:{{ $carte['couleur1'] }}">
            </lord-icon>
            Téléphone
        </a>

        <a href="mailto:{{ $compte->email }}"
           class='m-1 p-1 bg-white bg-opacity-20 backdrop-filter backdrop-blur-md rounded-xl flex items-center justify-center'>
            <lord-icon
                    src="https://cdn.lordicon.com/aycieyht.json"
                    trigger="loop"
                    delay="1000" state="in-assembly"
                    colors="primary:#F5F5F5,secondary:{{ $carte['couleur1'] }}">
            </lord-icon>
            Mail
        </a>

        <!--PDF-->
        @if($carte['pdf'])
            <a href="{{ asset($carte['pdf']) }}" target="_blank" rel="noopener noreferrer"
               class='m-1 p-1 bg-white bg-opacity-20 backdrop-filter backdrop-blur-md rounded-xl flex items-center justify-center'>
                <lord-icon
                        src="https://cdn.lordicon.com/wzwygmng.json"
                        trigger="loop"
                        delay="1000"
                        colors="primary:#F5F5F5,secondary:{{ $carte['couleur1'] }}">
                </lord-icon>
                {{ $carte['nomBtnPdf'] ?? 'Voir PDF' }}
            </a>
        @endif

        <!--RDV-->
        @if($carte['LienCommande'])
        <a href="{{ $carte['LienCommande'] }}" target="_blank" rel="noopener noreferrer"
           class='m-1 p-1 bg-white bg-opacity-20 backdrop-filter backdrop-blur-md rounded-xl flex items-center justify-center'>
            <lord-icon
                    src="https://cdn.lordicon.com/odavpkmb.json"
                    trigger="loop"
                    delay="1000"
                    colors="primary:#F5F5F5,secondary:{{ $carte['couleur1'] }}">
            </lord-icon>Prendre un rendez-vous
        </a>
        @endif

        <!--Vcard-->
        <a href="{{ '/entreprises/'. $carte->compte->idCompte.'/VCF_Files/contact.vcf' }}"
           download="Contact-Wisikard.vcf"
           class='m-1 p-1 bg-white bg-opacity-20 backdrop-filter backdrop-blur-md rounded-xl flex items-center justify-center'>
            <lord-icon src="https://cdn.lordicon.com/rehjpyyh.json"
                       trigger="loop"
                       delay="1000"
                       state="hover-portrait"
                       colors="primary:#F5F5F5,secondary:{{ $carte['couleur1'] }}">
            </lord-icon>
            Fiche contact
        </a>

        <!--Installer-->
        <button id="installButton"
           class='m-1 p-1 bg-white bg-opacity-20 backdrop-filter backdrop-blur-md rounded-xl flex items-center justify-center'>
            <lord-icon src="https://cdn.lordicon.com/dxnllioo.json"
                       trigger="loop"
                       delay="1000"
                       state="loop-slide"
                       colors="primary:#F5F5F5,secondary:{{ $carte['couleur1'] }}">
            </lord-icon>
            Installer
        </button>

        <!--Partager-->
        <button onclick="shareOrCopyLink()" class='m-1 p-1 bg-white bg-opacity-20 backdrop-filter backdrop-blur-md rounded-xl flex items-center justify-center'>
            <lord-icon src="https://cdn.lordicon.com/udwhdpod.json"
                       trigger="loop"
                       delay="1000"
                       colors="primary:#F5F5F5,secondary:{{ $carte['couleur1'] }}">
            </lord-icon>
            Partager
        </button>

        <!--Horaire-->
        <!-- Bouton pour afficher les horaires dans un modal -->
        <button onclick="showHoraires()"
                class='m-1 p-1 bg-white bg-opacity-20 backdrop-filter backdrop-blur-md rounded-xl flex items-center justify-center'>
                <lord-icon
                    src="https://cdn.lordicon.com/warimioc.json"
                    trigger="loop"
                    state="loop-oscillate"
                       colors="primary:#F5F5F5,secondary:{{ $carte['couleur1'] }}">
            </lord-icon>
            Horaires
        </button>
    </div>

    <div class="flex justify-center flex-wrap mx-5 my-4 bg-[#342d29] bg-opacity-80 backdrop-blur-lg rounded-lg p-4">
        @foreach($mergedSocial as $so)
            <a href="{{ $so['lien'] }}" target="_blank" rel="noopener noreferrer"
               class="p-3">
                <div class="flex items-center justify-center">
                    <div class="w-12 h-12 flex items-center justify-center">
                        <div class="text-white fill-white">
                            {!! $so['logo'] !!}
                        </div>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</div>


<div class="w-auto p-3 relative flex justify-center items-center">
    @php
        $sliderDirectory = public_path('entreprises/'.$carte->idCompte.'_'.$carte->nomEntreprise.'/slider');
        $sliderImages = file_exists($sliderDirectory) ? array_diff(scandir($sliderDirectory), array('.', '..')) : [];
    @endphp
    @if(!empty($sliderImages))
        <div class="relative w-48 h-48">
            <div class="carousel-container w-full h-full overflow-hidden">
                <div class="carousel-track flex transition-transform ease-out duration-500">
                    @foreach($sliderImages as $image)
                        <div class="carousel-slide min-w-full">
                            <img src="{{ asset('entreprises/'.$carte->idCompte.'_'.$carte->nomEntreprise.'/slider/'. $image) }}"
                                 alt="Image"
                                 class="w-full h-full object-cover cursor-pointer hover:opacity-80">
                        </div>
                    @endforeach
                </div>
            </div>
            <!-- Navigation buttons -->
            <button class="carousel-button-prev absolute top-1/2 left-0 transform -translate-y-1/2 bg-gray-800 text-white p-2 rounded-full" onclick="prevSlide()">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </button>
            <button class="carousel-button-next absolute top-1/2 right-0 transform -translate-y-1/2 bg-gray-800 text-white p-2 rounded-full" onclick="nextSlide()">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </button>
        </div>
    @endif
</div>

<!-- Galerie Photos -->
@php
    $sliderDirectory = public_path('entreprises/'.$carte->compte->idCompte.'/slider');
    $sliderImages = file_exists($sliderDirectory) ? array_values(array_diff(scandir($sliderDirectory), array('.', '..'))) : [];
@endphp

@if($sliderImages)
    <div class="flex-cols">
        <div class="w-full mt-4 text-center">
            <button onclick="openGallery()" class="w-32 rounded-xl p-2 font-bold text-white text-center bg-zinc-800 border border-gray-200 cursor-pointer hover-effect">
                Voir la Galerie
            </button>
        </div>

        <div id="photoGallery" class="hidden fixed top-0 left-0 w-full h-full bg-zinc-800 bg-opacity-75 flex justify-center items-center z-50">
            <div class="relative bg-white p-6 rounded-lg w-11/12 md:w-2/3 lg:w-1/2">
                <button onclick="closeGallery()" class="absolute top-2 right-2 p-2 font-bold text-xl text-gray-900">&times;</button>
                <h2 class="text-center font-bold text-lg mb-4">Galerie de Photos</h2>
                <div class="flex flex-wrap gap-4 justify-center items-center">
                    @foreach($sliderImages as $image)
                        <img src="{{ asset('entreprises/'.$carte->compte->idCompte.'/slider/'.urlencode($image)) }}"
                             alt="Image thumbnail"
                             class="w-1/3 rounded-lg shadow-md cursor-pointer hover-effect"
                             onclick="viewImage('{{ asset('entreprises/'.$carte->compte->idCompte.'/slider/'.urlencode($image)) }}')">
                    @endforeach
                </div>
            </div>
        </div>

        <div id="fullImage" class="hidden fixed top-0 left-0 w-full h-full bg-zinc-800 bg-opacity-90 flex justify-center items-center z-50">
            <div class="relative">
                <img id="fullImageContent" src="" alt="Image fullsize" class="max-w-full max-h-full rounded-lg shadow-lg">
                <button onclick="closeFullImage()" class="absolute top-2 right-2 text-red-800 text-2xl font-bold">&times;</button>
            </div>
        </div>
    </div>
@endif

<!-- Vidéos YouTube -->
@if($youtubeUrls)
    <div class="flex-cols">
        <div class="w-full mt-4 text-center">
            <button onclick="openVideoGallery()" class="w-32 rounded-xl p-2 font-bold text-white text-center bg-zinc-800 border border-gray-200 cursor-pointer hover-effect">
                Voir les Vidéos
            </button>
        </div>

        <div id="videoGallery" class="hidden fixed top-0 left-0 w-full h-full bg-zinc-800 bg-opacity-75 flex justify-center items-center z-50">
            <div class="relative bg-white p-6 rounded-lg w-11/12 md:w-2/3 lg:w-1/2">
                <button onclick="closeVideoGallery()" class="absolute top-2 right-2 p-2 text-red-500 font-bold text-xl">&times;</button>
                <h2 class="text-center font-bold text-lg mb-4">Galerie de Vidéos</h2>
                <div class="flex flex-wrap gap-4 justify-center items-center">
                    @foreach($youtubeUrls as $url)
                        @php
                            $videoId = preg_replace('/^.*?v=([\w\-]+).*$/', '$1', $url);
                        @endphp
                        <a href="{{ $url }}" target="_blank" rel="noopener noreferrer"
                           class="w-1/3 sm:w-1/4 lg:w-1/5 aspect-video relative rounded-lg overflow-hidden block hover-effect">
                            <img src="https://img.youtube.com/vi/{{ $videoId }}/mqdefault.jpg"
                                 alt="Thumbnail"
                                 class="w-full h-full object-cover">
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif

<!-- Modal pour les horaires -->
<div id='horairesModal' class="modal">
    <div class="modalBody bg-opacity-80 backdrop-blur-lg">
        <div class="modalTitle">
            <div class="modalTitleTxt">
                Horaires de {{ $carte['nomEntreprise'] }}
            </div>
            <div class="modalCloseBtn" onclick="hideHoraires()">
                ✖
            </div>
        </div>
        <div class="modalContent">
            <div class='horaires-container mx-2 my-2 rounded-lg p-2'>
                @php
                    $days = $horaires->chunk(2);
                    $lastColumn = $days->pop();
                @endphp
                @foreach($days as $chunk)
                    <div class="horaires-column">
                        @foreach($chunk as $horaire)
                            <div class="horaires-item flex items-center justify-center p-2">
                                <div class="day text-white p-1 fill-white">
                                    {{ $horaire->jour }}
                                </div>
                                <div class="hours text-center mt-1">
                                    @if($horaire->ouverture_matin && $horaire->fermeture_matin && $horaire->ouverture_aprmidi && $horaire->fermeture_aprmidi)
                                        <p> {{ date('H:i', strtotime($horaire->ouverture_matin)) }} - {{ date('H:i', strtotime($horaire->fermeture_matin)) }}</p>
                                        <p> {{ date('H:i', strtotime($horaire->ouverture_aprmidi)) }} - {{ date('H:i', strtotime($horaire->fermeture_aprmidi)) }}</p>
                                    @else
                                        <p>Fermé</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
                <div class="horaires-column">
                    @foreach($lastColumn as $horaire)
                        <div class="horaires-item flex items-center justify-center p-2">
                            <div class="day text-white p-1 fill-white">
                                {{ $horaire->jour }}
                            </div>
                            <div class="hours text-center mt-1">
                                @if($horaire->ouverture_matin && $horaire->fermeture_matin && $horaire->ouverture_aprmidi && $horaire->fermeture_aprmidi)
                                    <p> {{ date('H:i', strtotime($horaire->ouverture_matin)) }} - {{ date('H:i', strtotime($horaire->fermeture_matin)) }}</p>
                                    <p> {{ date('H:i', strtotime($horaire->ouverture_aprmidi)) }} - {{ date('H:i', strtotime($horaire->fermeture_aprmidi)) }}</p>
                                @else
                                    <p>Fermé</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="text-center p-2">
    Un service proposé par <a href="https://sendix.fr" class="text-blue-500">SENDIX</a> - <a href="https://wisikard.fr"
                                                                                           class="text-blue-500">Wisikard</a>
</footer>

<script>
    function showQrCode() {
        document.getElementById('qrCodeModal').style.display = 'block';
    }

    function hideQrCode() {
        document.getElementById('qrCodeModal').style.display = 'none';
    }

    function showHoraires() {
        document.getElementById('horairesModal').style.display = 'block';
    }

    function hideHoraires() {
        document.getElementById('horairesModal').style.display = 'none';
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

    const carouselTrack = document.querySelector('.carousel-track');
    const slides = Array.from(carouselTrack.children);
    const slideWidth = slides[0].getBoundingClientRect().width;
    let currentIndex = 0;

    const setSlidePosition = (slide, index) => {
        slide.style.left = slideWidth * index + 'px';
    };

    slides.forEach(setSlidePosition);

    const moveToSlide = (carouselTrack, currentSlide, targetSlide) => {
        carouselTrack.style.transform = 'translateX(-' + targetSlide.style.left + ')';
        currentSlide.classList.remove('current-slide');
        targetSlide.classList.add('current-slide');
    };

    const prevSlide = () => {
        const currentSlide = slides[currentIndex];
        const prevSlide = slides[currentIndex - 1];
        moveToSlide(carouselTrack, currentSlide, prevSlide);
        currentIndex -= 1;
    };

    const nextSlide = () => {
        const currentSlide = slides[currentIndex];
        const nextSlide = slides[currentIndex + 1];
        moveToSlide(carouselTrack, currentSlide, nextSlide);
        currentIndex += 1;
    };

    document.querySelector('.carousel-button-prev').addEventListener('click', () => {
        if (currentIndex > 0) {
            prevSlide();
        }
    });

    document.querySelector('.carousel-button-next').addEventListener('click', () => {
        if (currentIndex < slides.length - 1) {
            nextSlide();
        }
    });

    // Galerie Photos
    function openGallery() {
        document.getElementById('photoGallery').classList.remove('hidden');
    }

    function closeGallery() {
        document.getElementById('photoGallery').classList.add('hidden');
    }

    function viewImage(imageSrc) {
        const fullImage = document.getElementById('fullImage');
        const fullImageContent = document.getElementById('fullImageContent');
        fullImageContent.src = imageSrc;
        fullImage.classList.remove('hidden');
    }

    function closeFullImage() {
        document.getElementById('fullImage').classList.add('hidden');
    }

    // Galerie Vidéos
    function openVideoGallery() {
        document.getElementById('videoGallery').classList.remove('hidden');
    }

    function closeVideoGallery() {
        document.getElementById('videoGallery').classList.add('hidden');
    }
</script>

    <!-- PWA -->
    
    <script>
        let deferredPrompt;
        
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            
            // Vérifier si l'utilisateur a déjà refusé l'installation
            if (!localStorage.getItem('installPromptDismissed')) {
                document.getElementById('installPrompt').style.display = 'block';
            }
        });

        document.getElementById('installButton').addEventListener('click', async () => {
            if (deferredPrompt) {
                deferredPrompt.prompt();
                const { outcome } = await deferredPrompt.userChoice;
                if (outcome === 'accepted') {
                    console.log('Application installée');
                }
                deferredPrompt = null;
                document.getElementById('installPrompt').style.display = 'none';
            }
        });

        // Vérifier si l'app est déjà installée
        window.addEventListener('appinstalled', () => {
            document.getElementById('installPrompt').style.display = 'none';
            localStorage.setItem('installPromptDismissed', 'true');
        });
    </script>

    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(registration => {
                        console.log('Enregistrement du ServiceWorker complété');
                    })
                    .catch(err => {
                        console.log('Enregistrement du ServiceWorker échoué: ', err);
                    });
            });
        }
    </script>
</body>

</html>
