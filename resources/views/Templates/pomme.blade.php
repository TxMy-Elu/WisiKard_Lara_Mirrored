<!DOCTYPE html>
<html lang="fr">
<head>
    <title>{{ $carte['nomEntreprise'] ? $carte['nomEntreprise'] . ' - ' : '' }}Wisikard</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#FFFFFF">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="WisiKard">
    <script src="https://cdn.lordicon.com/lordicon.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=SF+Pro+Display:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="manifest" href="{{ '/entreprises/'. $carte->compte->idCompte.'/manifest.json' }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    
    <style>
        body {
            font-family: 'SF Pro Display', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #FFFFFF;
            color: #1D1D1F;
        }

        .main-container {
            max-width: 680px;
            margin: 0 auto;
            padding: 20px;
        }

        .logo-container {
            padding: 2rem 0;
        }

        .logo {
            max-width: 180px;
            height: auto;
        }

        .title {
            font-size: 40px;
            font-weight: 600;
            letter-spacing: -0.5px;
            line-height: 1.1;
            margin: 1rem 0;
        }

        .subtitle {
            font-size: 21px;
            font-weight: 400;
            color: #86868B;
            line-height: 1.4;
            margin-bottom: 2rem;
        }

        .action-button {
            background: #0071E3;
            color: white;
            padding: 12px 24px;
            border-radius: 980px;
            font-size: 17px;
            font-weight: 400;
            transition: all 0.2s;
            margin: 0.5rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .action-button:hover {
            background: #0077ED;
        }

        .section {
            margin: 4rem 0;
            padding: 2rem 0;
            border-bottom: 1px solid #D2D2D7;
        }

        .gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin: 2rem 0;
        }

        .gallery img {
            width: 100%;
            border-radius: 12px;
            transition: transform 0.3s ease;
        }

        .gallery img:hover {
            transform: scale(1.02);
        }

        .modal {
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(10px);
        }

        .modal-content {
            background: #FFFFFF;
            border-radius: 14px;
            padding: 2rem;
        }

        .social-links {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin: 2rem 0;
        }

        .social-links a {
            padding: 12px;
            border-radius: 50%;
            background: #F5F5F7;
            transition: all 0.2s;
        }

        .social-links a:hover {
            background: #E8E8ED;
        }

        .hours-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin: 2rem 0;
        }

        .hours-item {
            padding: 1rem;
            background: #F5F5F7;
            border-radius: 12px;
        }
    </style>
</head>

<body>
    <div class="main-container">
        <h1 style="color:#ff0000;text-align:center">Modèle en cours de finalisation</h1>
        <!-- Logo Section -->
        <div class="logo-container flex justify-center">
            @php
                $logoPath = '';
                $formats = ['svg', 'png', 'jpg', 'jpeg'];
                foreach ($formats as $format) {
                    $path = public_path('entreprises/' . $carte->compte->idCompte . '/logos/logo.' . $format);
                    if (file_exists($path)) {
                        $logoPath = asset('entreprises/' . $carte->compte->idCompte . '/logos/logo.' . $format);
                        break;
                    }
                }
            @endphp
            @if(!empty($logoPath))
                <img class="logo" src="{{ $logoPath }}" alt="Logo {{ $carte['nomEntreprise'] }}">
            @endif
        </div>

        <!-- Header Section -->
        <header class="text-center">
            <h1 class="title">{{ $carte['titre'] }}</h1>
            <p class="subtitle">{{ $carte->descriptif }}</p>
        </header>

        <!-- Primary Actions -->
        <div class="flex flex-wrap justify-center gap-4 my-8">
            <a href="tel:{{ $carte['tel'] }}" class="action-button">
                <lord-icon src="https://cdn.lordicon.com/qtykvslf.json" trigger="hover" colors="primary:#FFFFFF" style="width:24px;height:24px"></lord-icon>
                Appeler
            </a>
            
            <a href="mailto:{{ $compte->email }}" class="action-button">
                <lord-icon src="https://cdn.lordicon.com/aycieyht.json" trigger="hover" colors="primary:#FFFFFF" style="width:24px;height:24px"></lord-icon>
                Contacter
            </a>

            @if($carte['LienCommande'])
                <a href="{{ $carte['LienCommande'] }}" class="action-button" target="_blank" rel="noopener">
                    <lord-icon src="https://cdn.lordicon.com/odavpkmb.json" trigger="hover" colors="primary:#FFFFFF" style="width:24px;height:24px"></lord-icon>
                    Réserver
                </a>
            @endif

            @if($carte['lienSite'])
            <a href="{{ $carte['lienSite'] }}" target="_blank" rel="noopener noreferrer" class="action-button">
                <lord-icon src="https://cdn.lordicon.com/pbbsmkso.json" trigger="hover" colors="primary:#FFFFFF" style="width:24px;height:24px"></lord-icon>
                Site Web
            </a>
            @endif

            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($carte['nomEntreprise'] . ' ' . $carte['ville']) }}" target="_blank" rel="noopener noreferrer" class="action-button">
                <lord-icon src="https://cdn.lordicon.com/surcxhka.json" trigger="hover" colors="primary:#FFFFFF" style="width:24px;height:24px"></lord-icon>
                Plan d'accès
            </a>

            <button onclick="showQrCode()" class="action-button">
                <lord-icon src="https://cdn.lordicon.com/avcjklpr.json" trigger="hover" colors="primary:#FFFFFF" style="width:24px;height:24px"></lord-icon>
                QR Code
            </button>

            <a href="{{ '/entreprises/'. $carte->compte->idCompte.'/VCF_Files/contact.vcf' }}" download="Contact-Wisikard.vcf" class="action-button">
                <lord-icon src="https://cdn.lordicon.com/rehjpyyh.json" trigger="hover" colors="primary:#FFFFFF" style="width:24px;height:24px"></lord-icon>
                Fiche contact
            </a>

            <button onclick="share()" class="action-button">
                <lord-icon src="https://cdn.lordicon.com/udwhdpod.json" trigger="hover" colors="primary:#FFFFFF" style="width:24px;height:24px"></lord-icon>
                Partager
            </button>

            @if($carte['pdf'])
            <a href="{{ asset($carte['pdf']) }}" target="_blank" rel="noopener noreferrer" class="action-button">
                <lord-icon src="https://cdn.lordicon.com/wzwygmng.json" trigger="hover" colors="primary:#FFFFFF" style="width:24px;height:24px"></lord-icon>
                {{ $carte['nomBtnPdf'] ?? 'Voir PDF' }}
            </a>
            @endif
        </div>

        <!-- Additional Info Sections -->
        @if($horaires->count() > 0)
            <section class="section">
                <h2 class="text-2xl font-semibold mb-4">Horaires d'ouverture</h2>
                <div class="hours-grid">
                    @foreach($horaires as $horaire)
                        <div class="hours-item">
                            <div class="font-medium">{{ $horaire->jour }}</div>
                            @if($horaire->ouverture_matin && $horaire->fermeture_matin)
                                <div>{{ date('H:i', strtotime($horaire->ouverture_matin)) }} - {{ date('H:i', strtotime($horaire->fermeture_matin)) }}</div>
                            @endif
                            @if($horaire->ouverture_aprmidi && $horaire->fermeture_aprmidi)
                                <div>{{ date('H:i', strtotime($horaire->ouverture_aprmidi)) }} - {{ date('H:i', strtotime($horaire->fermeture_aprmidi)) }}</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        <!-- Gallery Section -->
        @php
            $sliderDirectory = public_path('entreprises/'.$carte->compte->idCompte.'/slider');
            $sliderImages = file_exists($sliderDirectory) ? array_diff(scandir($sliderDirectory), array('.', '..')) : [];
        @endphp
        @if(!empty($sliderImages))
            <section class="section">
                <h2 class="text-2xl font-semibold mb-4">Galerie</h2>
                <div class="gallery">
                    @foreach($sliderImages as $image)
                        <img src="{{ asset('entreprises/'.$carte->compte->idCompte.'/slider/'.$image) }}" 
                             alt="Image galerie"
                             onclick="openGalleryView(this.src)"
                             class="cursor-pointer">
                    @endforeach
                </div>
            </section>
        @endif

        <!-- Social Links -->
        @if(count($mergedSocial) > 0)
            <section class="section">
                <div class="social-links">
                    @foreach($mergedSocial as $so)
                        <a href="{{ $so['lien'] }}" target="_blank" rel="noopener noreferrer">
                            <div class="w-6 h-6 fill-current text-gray-800">
                                {!! $so['logo'] !!}
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        <!-- Footer -->
        <footer class="text-center text-gray-500 text-sm mt-12">
            Un service proposé par <a href="https://sendix.fr" class="text-blue-500">SENDIX</a> - 
            <a href="https://wisikard.fr" class="text-blue-500">Wisikard</a>
        </footer>
    </div>

    <!-- Gallery Modal -->
    <div id="galleryModal" class="fixed inset-0 modal hidden flex items-center justify-center z-50">
        <div class="modal-content max-w-4xl w-full mx-4">
            <img id="modalImage" src="" alt="Image en plein écran" class="w-full h-auto rounded-lg">
            <button onclick="closeGalleryView()" class="absolute top-4 right-4 text-white text-xl">&times;</button>
        </div>
    </div>

    <!-- QR Code Modal -->
    <div id="qrCodeModal" class="fixed inset-0 modal hidden flex items-center justify-center z-50">
        <div class="modal-content max-w-sm w-full mx-4">
            <h3 class="text-xl font-semibold mb-4">QR Code de {{ $carte['nomEntreprise'] }}</h3>
            <img src="{{ $carte['lienQr'] }}" alt="QR Code" class="w-full h-auto">
            <button onclick="hideQrCode()" class="absolute top-4 right-4 text-gray-600 text-xl">&times;</button>
        </div>
    </div>

    <script>
        function openGalleryView(src) {
            document.getElementById('modalImage').src = src;
            document.getElementById('galleryModal').classList.remove('hidden');
        }

        function closeGalleryView() {
            document.getElementById('galleryModal').classList.add('hidden');
        }

        function showQrCode() {
            document.getElementById('qrCodeModal').classList.remove('hidden');
        }

        function hideQrCode() {
            document.getElementById('qrCodeModal').classList.add('hidden');
        }

        async function share() {
            const shareData = {
                title: '{{ $carte['nomEntreprise'] }}',
                text: '{{ $carte['titre'] }}',
                url: window.location.href
            };

            if (navigator.share) {
                try {
                    await navigator.share(shareData);
                } catch (err) {
                    console.log('Error sharing:', err);
                }
            } else {
                // Fallback: copy to clipboard
                navigator.clipboard.writeText(window.location.href).then(() => {
                    alert('Lien copié dans le presse-papier !');
                });
            }
        }

        // PWA Installation
        let deferredPrompt;
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
        });

        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(registration => {
                        console.log('ServiceWorker enregistré avec succès');
                    })
                    .catch(err => {
                        console.log('Erreur d\'enregistrement du ServiceWorker:', err);
                    });
            });
        }
    </script>
</body>
</html>