<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $carte['nomEntreprise'] ? $carte['nomEntreprise'] . ' - ' : '' }} - Wisikard</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i%7CQuicksand:300,300i,400,400i,600,600i,700,700i&display=swap"
          rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        *:not(.material-icons, .fa-brands) {
            font-family: 'Open Sans' !important;
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
            padding: 10px;
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
            font-size: 20px;
        }

        .modalContent {
            margin-bottom: 0%;
        }

        .modalQR {
            max-width: 100%;
            max-height: 100%;
        }

        .horaires-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            margin: 10px 0;
        }

        .horaires-column {
            flex: 1;
            min-width: 22%;
            margin: 5px;
        }

        .horaires-item {
            font-size: 0.7rem;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
        }

        .horaires-item .day {
            margin-right: 10px;
            width: 60px;
            text-align: right;
        }

        .horaires-item .hours {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .card-container {
            max-width: 90%;
            margin: 0 auto;
            padding: 10px;
        }

        .card-container h1 {
            font-size: 1.2rem;
        }

        .card-container p {
            font-size: 0.8rem;
        }

        .card-container a {
            font-size: 0.7rem;
            padding: 3px 8px;
        }

        .card-container button {
            font-size: 0.7rem;
            padding: 3px 8px;
        }

        @media (max-width: 600px) {
            .horaires-column {
                min-width: 45%;
            }

            .horaires-item {
                flex-direction: column;
                align-items: flex-start;
            }

            .horaires-item .day {
                margin-right: 0;
                width: auto;
                text-align: left;
                margin-bottom: 5px;
            }

            .horaires-item .hours {
                align-items: flex-start;
            }

            .card-container h1 {
                font-size: 1rem;
            }

            .card-container p {
                font-size: 0.7rem;
            }

            .card-container a {
                font-size: 0.6rem;
                padding: 2px 6px;
            }

            .card-container button {
                font-size: 0.6rem;
                padding: 2px 6px;
            }
        }
    </style>
    <link rel="shortcut icon" href="{{ asset('assets/img/favicon.png') }}" type="image-x-icon">
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

<div class="card-container flex flex-col items-center w-full">
    <div class='flex justify-center items-center w-24 mt-2'>
        <img class='w-4/5 max-w-xl'
             src="{{ $logoPath ? $logoPath : asset('images/default-logo.png') }}"
             alt='Logo'>
    </div>

    <div class='flex justify-center items-center mt-1'>
        <h1 class='font-bold'>{{ $carte['titre'] }}</h1>
    </div>
    @if($employe != null)
        <div class='flex justify-center items-center flex-wrap mt-2'>
            <a href='mailto:{{ $employe['mail'] }}'
               class='m-1 p-1 bg-white bg-opacity-20 backdrop-filter backdrop-blur-md rounded-md text-center'>
                {{ $employe['mail'] }}
            </a>
            <a href='tel:{{ $employe['telephone'] }}'
               class='m-1 p-1 bg-white bg-opacity-20 backdrop-filter backdrop-blur-md rounded-md text-center'>
                {{ $employe['telephone'] }}
            </a>
        </div>
    @endif

    <div class='flex justify-center items-center mt-1'>
        <p class='text-center'>{{ $carte->descriptif }}</p>
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
        <div class="modalBody">
            <div class="modalTitle">
                <div class="modalTitleTxt">
                    QR Code de {{ $carte['nomEntreprise'] }}
                </div>
                <div class="modalCloseBtn" onclick="hideQrCode()">
                    ✖
                </div>
            </div>
            <div class="modalContent">
                <img src="{{ $carte['qrCode'] }}" alt="QR Code" class="modalQR">
            </div>
        </div>
    </div>

        <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($carte['nomEntreprise'] . ' ' . $carte['ville']) }}"
           class='m-1 p-1 bg-white bg-opacity-20 backdrop-filter backdrop-blur-md rounded-xl flex items-center justify-center'>
            <lord-icon
                    src="https://cdn.lordicon.com/surcxhka.json"
                    trigger="loop"
                    delay="1000"
                    colors="primary:#F5F5F5,secondary:{{ $carte['couleur1'] }}">
            </lord-icon>
            Maps
        </a>

        <a href="{{ $carte['lienSite'] }}"
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

        <a href="mailto:{{ $carte['mailContact'] }}"
           class='m-1 p-1 bg-white bg-opacity-20 backdrop-filter backdrop-blur-md rounded-xl flex items-center justify-center'>
            <lord-icon
                    src="https://cdn.lordicon.com/aycieyht.json"
                    trigger="loop"
                    delay="1000"
                    colors="primary:#F5F5F5,secondary:{{ $carte['couleur1'] }}">
            </lord-icon>
            Mail
        </a>

         <!--PDF-->
        @if($carte['pdf'])
            <a href="{{ $carte['pdf'] }}" target="_blank" rel="noopener noreferrer"
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
        <a href="{{$carte['lienCommande']}}"
           class='m-1 p-1 bg-white bg-opacity-20 backdrop-filter backdrop-blur-md rounded-xl flex items-center justify-center'>
            <lord-icon
                    src="https://cdn.lordicon.com/odavpkmb.json"
                    trigger="loop"
                    delay="1000"
                    colors="primary:#F5F5F5,secondary:{{ $carte['couleur1'] }}">
            </lord-icon>{{ $carte['nomButtonCommande'] }}
        </a>

        <!--Vcard-->
        <a href="{{ '/entreprises/'. $carte->compte->idCompte.'_'.$carte->nomEntreprise.'/VCF_Files/contact.vcf' }}"
           download="Contact-Wisikard.vcf"
           class='m-1 p-1 bg-white bg-opacity-20 backdrop-filter backdrop-blur-md rounded-xl flex items-center justify-center'>
            <lord-icon src="https://cdn.lordicon.com/rehjpyyh.json"
                       trigger="loop"
                       delay="1000"
                       state="hover-portrait"
                       colors="primary:#F5F5F5,secondary:{{ $carte['couleur1'] }}">
            </lord-icon>
            Vcard
        </a>

        <!--Installer-->
        <a id="prompt"
           class='m-1 p-1 bg-white bg-opacity-20 backdrop-filter backdrop-blur-md rounded-xl flex items-center justify-center'>
            <lord-icon src="https://cdn.lordicon.com/dxnllioo.json"
                       trigger="loop"
                       delay="1000"
                       state="loop-slide"
                       colors="primary:#F5F5F5,secondary:{{ $carte['couleur1'] }}">
            </lord-icon>
            Installer
        </a>

        <!--Partager-->
        <button class='m-1 p-1 bg-white bg-opacity-20 backdrop-filter backdrop-blur-md rounded-xl flex items-center justify-center'>
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
            <lord-icon src="https://cdn.lordicon.com/lupuorrc.json"
                       trigger="loop"
                       delay="1000"
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

        <!-- Modal pour les horaires -->
        <div id='horairesModal' class="modal">
            <div class="modalBody">
                <div class="modalTitle">
                    <div class="modalTitleTxt">
                        Horaires de {{ $carte['nomEntreprise'] }}
                    </div>
                    <div class="modalCloseBtn" onclick="hideHoraires()">
                        ✖
                    </div>
                </div>
                <div class="modalContent">
                    <div class='horaires-container mx-2 my-2 bg-[#342d29] bg-opacity-80 backdrop-blur-lg rounded-lg p-2'>
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
</script>

</body>

</html>
