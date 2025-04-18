<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
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
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Montserrat:wght@400;700&family=Oswald:wght@400;700&family=Ubuntu:wght@400;700&family=Playfair+Display:wght@400;700&family=Work+Sans:wght@400;700&family=Bona+Nova:wght@400;700&family=Exo+2:wght@400;700&family=Pacifico&family=Gruppo&family=Rokkitt:wght@400;700&display=swap"
          rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="manifest" href="{{ '/entreprises/'. $carte->compte->idCompte.'/manifest.json' }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    
    <style>
        body {
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

        .utility-actions {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin: 1rem 0;
        }

        .utility-button {
            display: flex;
            flex-direction: column;
            align-items: center;
            color: #86868B;
            font-size: 0.875rem;
            transition: all 0.2s;
            padding: 0.5rem;
            border-radius: 8px;
        }

        .utility-button:hover {
            background: #F5F5F7;
            color: #1D1D1F;
        }
    </style>
</head>

<body class="h-100%" style="font-family: {{ $carte['font'] === 'défaut' ? 'SF Pro Display, -apple-system, BlinkMacSystemFont, sans-serif' : $carte['font'] }};">
    <div class="main-container">
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
            <p class="subtitle">{!! nl2br(e($carte['descriptif'])) !!}</p>
        </header>

        <!-- Utility Actions -->
        <div class="utility-actions">
            <button onclick="showQrCode()" class="utility-button">
                <lord-icon src="https://cdn.lordicon.com/avcjklpr.json" trigger="loop" colors="primary:#86868B,secondary:#0077ED" style="width:48px;height:48px"></lord-icon>
                <span>QR Code</span>
            </button>

            <a href="{{ '/entreprises/'. $carte->compte->idCompte.'/VCF_Files/contact.vcf' }}" download="{{ $carte['nomEntreprise'] }}.vcf" class="utility-button">
                <lord-icon src="https://cdn.lordicon.com/kdduutaw.json" trigger="loop" colors="primary:#86868B,secondary:#0077ED" style="width:48px;height:48px"></lord-icon>
                <span>Contact</span>
            </a>

            <button onclick="shareOrCopyLink()" class="utility-button">
                <lord-icon src="https://cdn.lordicon.com/udwhdpod.json" trigger="loop" colors="primary:#86868B,secondary:#0077ED" style="width:48px;height:48px"></lord-icon>
                <span>Partager</span>
            </button>
        </div>

        <!-- Primary Actions -->
        <div class="flex flex-wrap justify-center gap-4 my-8">
            @if($carte['tel'])
            <a href="tel:{{ $carte['tel'] }}" class="action-button">
                <lord-icon src="https://cdn.lordicon.com/qtykvslf.json" trigger="loop" colors="primary:#FFFFFF,secondary:#494949" style="width:24px;height:24px"></lord-icon>
                Appeler
            </a>
            @endif
            
            
            @if($compte['email'] && $carte->afficher_email)
            <a href="mailto:{{ $compte->email }}" class="action-button">
                <lord-icon src="https://cdn.lordicon.com/aycieyht.json" trigger="loop" colors="primary:#FFFFFF,secondary:#494949" style="width:24px;height:24px"></lord-icon>
                Contacter
            </a>
            @endif

            @if($carte['LienCommande'])
                <a href="{{ $carte['LienCommande'] }}" class="action-button" target="_blank" rel="noopener">
                    <lord-icon src="https://cdn.lordicon.com/jdgfsfzr.json" trigger="loop" colors="primary:#FFFFFF,secondary:#494949" style="width:24px;height:24px"></lord-icon>
                    Rendez-vous
                </a>
            @endif

            @if($carte['lienSite'])
            <a href="{{ $carte['lienSite'] }}" target="_blank" rel="noopener noreferrer" class="action-button">
                <lord-icon src="https://cdn.lordicon.com/pbbsmkso.json" trigger="loop" colors="primary:#FFFFFF,secondary:#494949" style="width:24px;height:24px"></lord-icon>
                Site Web
            </a>
            @endif

            @if($carte['ville'])
            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($carte['nomEntreprise'] . ' ' . $carte['ville']) }}" target="_blank" rel="noopener noreferrer" class="action-button">
                <lord-icon src="https://cdn.lordicon.com/surcxhka.json" trigger="loop" colors="primary:#FFFFFF,secondary:#494949" style="width:24px;height:24px"></lord-icon>
                Plan d'accès
            </a>
            @endif

            @if($carte['lienSiteWeb'])
            <a href="{{ $carte['lienSiteWeb'] }}" target="_blank" rel="noopener noreferrer" class="action-button">
                <lord-icon src="https://cdn.lordicon.com/pbbsmkso.json" trigger="loop" colors="primary:#FFFFFF,secondary:#494949" style="width:24px;height:24px"></lord-icon>
                Site Web
            </a>
            @endif

            @if($carte['lienAvis'])
            <a href="{{ $carte['lienAvis'] }}" target="_blank" rel="noopener noreferrer" class="action-button">
                <lord-icon src="https://cdn.lordicon.com/fozsorqm.json" trigger="loop" colors="primary:#FFFFFF,secondary:#494949" style="width:24px;height:24px"></lord-icon>
                Avis Google
            </a>
            @endif

            @if($carte['pdf'])
            <a href="{{ asset($carte['pdf']) }}" target="_blank" rel="noopener noreferrer" class="action-button">
                <lord-icon src="https://cdn.lordicon.com/wzwygmng.json" trigger="loop" colors="primary:#FFFFFF,secondary:#494949" style="width:24px;height:24px"></lord-icon>
                {{ $carte['nomBtnPdf'] ?? 'Voir PDF' }}
            </a>
            @endif
        </div>

        <!-- Liens personnalisés -->
        @if($custom && count($custom) > 0)
        <section class="section">
            <div class="flex flex-wrap justify-center gap-4">
                @foreach ($custom as $link)
                    <a href="{{ $link['lien'] }}" target="_blank" rel="noopener noreferrer" class="action-button">
                        <lord-icon
                            src="https://cdn.lordicon.com/bjxtqill.json"
                            trigger="loop"
                            colors="primary:#FFFFFF,secondary:#494949,secondary:#494949"
                            style="width:24px;height:24px">
                        </lord-icon>
                        {{ $link['nom'] }}
                    </a>
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

        <!-- YouTube Videos Section -->
        @if(!empty($youtubeUrls))
            <section class="section">
                <h2 class="text-2xl font-semibold mb-4">Vidéos</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($youtubeUrls as $url)
                        @php
                            $videoId = preg_replace('/^.*?v=([\w\-]+).*$/', '$1', $url);
                        @endphp
                        <div class="aspect-video relative rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition-shadow">
                            <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" 
                               class="block w-full h-full">
                                <img src="https://img.youtube.com/vi/{{ $videoId }}/maxresdefault.jpg"
                                     alt="Miniature YouTube"
                                     class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-black bg-opacity-20 flex items-center justify-center">
                                    <lord-icon
                                        src="https://cdn.lordicon.com/snxksidl.json"
                                        trigger="loop"
                                        colors="primary:#86868B,secondary:#0077ED"
                                        style="width:42px;height:42px">
                                    </lord-icon>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        <!-- Install Prompt pour PWA -->
        <div id="installPrompt" class="hidden fixed bottom-4 left-1/4 transform bg-white rounded-lg shadow-xl p-4 w-8/12 max-w-sm">
            <div class="flex items-center justify-between">
                <button id="installButton" class="flex items-center space-x-2 text-gray-800 hover:text-gray-600">
                    <lord-icon
                        src="https://cdn.lordicon.com/dxnllioo.json"
                        trigger="loop"
                        delay="1000"
                        colors="primary:#86868B,secondary:#0077ED"
                        style="width:32px;height:32px">
                    </lord-icon>
                    <span class="font-medium">Installer l'application</span>
                </button>
                <button onclick="dismissInstallPrompt()" class="text-gray-500 hover:text-gray-700">
                    &times;
                </button>
            </div>
        </div>

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


        // **Script pour la copie du lien**

        function shareOrCopyLink() {
                const linkToShare = "{{ url()->current() }}";

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

        // PWA Installation
        let deferredPrompt;
        
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            
            if (!localStorage.getItem('installPromptDismissed')) {
                setTimeout(() => {
                    document.getElementById('installPrompt').classList.remove('hidden');
                }, 2000);
            }
        });

        document.getElementById('installButton').addEventListener('click', async () => {
            if (deferredPrompt) {
                deferredPrompt.prompt();
                const { outcome } = await deferredPrompt.userChoice;
                if (outcome === 'accepted') {
                    console.log('Application installée');
                    document.getElementById('installPrompt').classList.add('hidden');
                }
                deferredPrompt = null;
            }
        });

        function dismissInstallPrompt() {
            document.getElementById('installPrompt').classList.add('hidden');
            localStorage.setItem('installPromptDismissed', 'true');
        }

        window.addEventListener('appinstalled', () => {
            document.getElementById('installPrompt').classList.add('hidden');
            localStorage.setItem('installPromptDismissed', 'true');
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