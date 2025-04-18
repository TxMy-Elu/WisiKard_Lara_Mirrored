<!DOCTYPE html>
<html lang="fr">
<head>
    <title>{{ $carte['nomEntreprise'] ? $carte['nomEntreprise'] . ' - ' : '' }}Wisikard</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#000000">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="WisiKard">
    <script src="https://cdn.lordicon.com/lordicon.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@300;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="manifest" href="{{ '/entreprises/'. $carte->compte->idCompte.'/manifest.json' }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    
    <style>
        :root {
            --neon-border-color: {{ $carte->couleur1 }};
            --neon-text-color: {{ $carte->couleur2 }};
        }

        body {
            font-family: 'Rajdhani', sans-serif;
            background: #000000;
            color: #ffffff;
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                125deg,
                #ff0000 0%,
                #00ff00 25%,
                #0000ff 50%,
                #ff00ff 75%,
                #ff0000 100%
            );
            opacity: 0.15;
            animation: rgb-shift 10s linear infinite;
            z-index: -1;
        }

        @keyframes rgb-shift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .neon-container {
            background: rgba(0, 0, 0, 0.8);
            border: 2px solid var(--neon-border-color);
            border-radius: 15px;
            padding: 20px;
            margin: 15px;
            box-shadow: 0 0 10px var(--neon-border-color),
                        inset 0 0 10px var(--neon-border-color);
            animation: neon-pulse 2s infinite;
        }

        @keyframes neon-pulse {
            0% { box-shadow: 0 0 10px var(--neon-border-color),
                            inset 0 0 10px var(--neon-border-color); }
            50% { box-shadow: 0 0 20px var(--neon-border-color),
                            inset 0 0 20px var(--neon-border-color); }
            100% { box-shadow: 0 0 10px var(--neon-border-color),
                            inset 0 0 10px var(--neon-border-color); }
        }

        .neon-title {
            color: var(--neon-text-color);
            text-shadow: 0 0 5px #fff,
                       0 0 10px #fff,
                       0 0 20px var(--neon-text-color),
                       0 0 40px var(--neon-text-color);
            animation: neon-text-pulse 1.5s infinite alternate;
        }

        @keyframes neon-text-pulse {
            from { text-shadow: 0 0 5px #fff,
                              0 0 10px #fff,
                              0 0 20px var(--neon-text-color),
                              0 0 40px var(--neon-text-color); }
            to { text-shadow: 0 0 5px #fff,
                            0 0 15px #fff,
                            0 0 25px var(--neon-text-color),
                            0 0 45px var(--neon-text-color); }
        }

        .cyber-button {
            background: transparent;
            border: 2px solid var(--neon-border-color);
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            text-transform: uppercase;
            text-align: center;
            letter-spacing: 2px;
            position: relative;
            overflow: hidden;
            transition: all 0.3s;
            z-index: 1;
        }

        .cyber-button::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background: var(--neon-border-color);
            top: 0;
            left: -100%;
            transition: all 0.3s;
            z-index: -1;
        }

        .cyber-button:hover::before {
            left: 0;
        }

        .cyber-button:hover {
            color: #000;
            box-shadow: 0 0 20px var(--neon-border-color);
        }

        .social-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        .social-icon {
            transition: transform 0.3s, filter 0.3s;
            filter: drop-shadow(0 0 5px var(--neon-border-color));
        }

        .social-icon:hover {
            transform: scale(1.1);
            filter: drop-shadow(0 0 10px var(--neon-border-color));
        }
        
        .modal {
            background: rgba(0, 0, 0, 0.85);
            backdrop-filter: blur(10px);
        }

        .modal-content {
            background: rgba(0, 0, 0, 0.9);
            border: 2px solid var(--neon-border-color);
            box-shadow: 0 0 20px var(--neon-border-color);
        }
    </style>
</head>

<body>
    <div class="container mx-auto px-4 py-8">
        <h1 style="color:#ff0000;text-align:center">Modèle en cours de création</h1>
        <!-- Logo et titre -->
        <div class="neon-container text-center mb-8">
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
                <img src="{{ $logoPath }}" alt="Logo" class="mx-auto mb-4 max-w-[600px] w-full">
            @endif
            <h1 class="neon-title text-4xl mb-4">{{ $carte['titre'] }}</h1>
            <p class="text-xl text-gray-300">{!! nl2br(e($carte['descriptif'])) !!}</p>
        </div>

        <!-- Actions principales -->
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 my-8">
            <!-- QR Code -->
            <button onclick="showQrCode()" class="cyber-button">
                <lord-icon src="https://cdn.lordicon.com/avcjklpr.json" trigger="loop" colors="primary:#ffffff"></lord-icon>
                QR Code
            </button>

            <!-- Autres boutons d'action -->
            @if($carte['tel'])
                <a href="tel:{{ $carte['tel'] }}" class="cyber-button">
                    <lord-icon src="https://cdn.lordicon.com/qtykvslf.json" trigger="loop" colors="primary:#ffffff"></lord-icon>
                    Téléphone
                </a>
            @endif
            
            @if($compte['email'] && $carte->afficher_email)
                <a href="mailto:{{ $carte['email'] }}" class="cyber-button">
                    <lord-icon src="https://cdn.lordicon.com/aycieyht.json" trigger="loop" colors="primary:#ffffff"></lord-icon>
                    Mail
                </a>
            @endif
            
            @if($compte['lienSiteWeb'])
                <a href="{{ $carte['lienSiteWeb'] }}" target="_blank" class="cyber-button">
                    <lord-icon src="https://cdn.lordicon.com/pbbsmkso.json" trigger="loop" colors="primary:#ffffff"></lord-icon>
                    Mail
                </a>
            @endif

            @if($carte['LienCommande'])
                <a href="{{ $carte['LienCommande'] }}" target="_blank" class="cyber-button">
                    <lord-icon src="https://cdn.lordicon.com/jdgfsfzr.json" trigger="loop" colors="primary:#ffffff"></lord-icon>
                    Rendez-vous
                </a>
            @endif
            
            @if($carte['lienAvis'])
                <a href="{{ $carte['lienAvis'] }}" target="_blank" class="cyber-button">
                    <lord-icon src="https://cdn.lordicon.com/fozsorqm.json" trigger="loop" colors="primary:#ffffff"></lord-icon>
                    Avis Google
                </a>
            @endif
            
            <!-- Map -->
            @if($carte['ville'])
                <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($carte['nomEntreprise'] . ' ' . $carte['ville']) }}" target="_blank" class="cyber-button">
                    <lord-icon src="https://cdn.lordicon.com/surcxhka.json" trigger="loop" colors="primary:#ffffff"></lord-icon>
                    Plan d'accès
                </a>
            @endif
            
            <!-- PDF -->
            @if($carte['pdf'])
                <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($carte['nomEntreprise'] . ' ' . $carte['ville']) }}" target="_blank" class="cyber-button">
                    <lord-icon src="https://cdn.lordicon.com/wzwygmng.json" trigger="loop" colors="primary:#ffffff"></lord-icon>
                    {{$carte['nomBtnPdf'] ?? 'Voir PDF'}}
                </a>
            @endif

            <!-- Vcard -->
            <a href="{{ '/entreprises/'. $carte->compte->idCompte.'/VCF_Files/contact.vcf' }}"
               download="{{ $carte['nomEntreprise'] }}.vcf" class="cyber-button">
                <lord-icon src="https://cdn.lordicon.com/kdduutaw.json" trigger="loop" colors="primary:#ffffff"></lord-icon>
                Fiche contact
            </a>

            <!-- Partage -->
            <button onclick="shareOrCopyLink()" class="cyber-button">
                <lord-icon src="https://cdn.lordicon.com/udwhdpod.json" trigger="loop" colors="primary:#ffffff"></lord-icon>
                Partager
            </button>

            <!-- Installation PWA -->
            <button id="installButton" class="cyber-button">
                <lord-icon src="https://cdn.lordicon.com/dxnllioo.json" trigger="loop" colors="primary:#ffffff"></lord-icon>
                Installer
            </button>
        </div>
        
        <!-- Réseaux sociaux -->
        @if(count($mergedSocial) > 0)
            <div class="neon-container">
                <div class="social-grid">
                    @foreach($mergedSocial as $so)
                        <a href="{{ $so['lien'] }}" target="_blank" rel="noopener noreferrer"
                           class="social-icon flex justify-center items-center">
                            <div class="w-12 h-12 fill-current text-white">
                                {!! $so['logo'] !!}
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Galerie -->
        @php
            $sliderDirectory = public_path('entreprises/'.$carte->compte->idCompte.'/slider');
            $sliderImages = file_exists($sliderDirectory) ? array_values(array_diff(scandir($sliderDirectory), array('.', '..'))) : [];
        @endphp
        @if(!empty($sliderImages))
            <div class="neon-container">
                <h2 class="neon-title text-2xl mb-4">Galerie</h2>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($sliderImages as $image)
                        <img src="{{ asset('entreprises/'.$carte->compte->idCompte.'/slider/'.urlencode($image)) }}"
                             alt="Image galerie"
                             class="w-full h-48 object-cover rounded cursor-pointer hover:opacity-80 transition-opacity"
                             onclick="openImageModal('{{ asset('entreprises/'.$carte->compte->idCompte.'/slider/'.urlencode($image)) }}')">
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Vidéos YouTube -->
        @if(!empty($youtubeUrls))
            <div class="neon-container mt-4">
                <h2 class="neon-title text-2xl mb-4">Vidéos</h2>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($youtubeUrls as $url)
                        @php
                            $videoId = preg_replace('/^.*?v=([\w\-]+).*$/', '$1', $url);
                        @endphp
                        <div class="aspect-video relative cursor-pointer hover:opacity-80 transition-opacity"
                             onclick="openVideoModal('{{ $videoId }}')">
                            <img src="https://img.youtube.com/vi/{{ $videoId }}/mqdefault.jpg"
                                 alt="Thumbnail YouTube"
                                 class="w-full h-full object-cover rounded">
                            <div class="absolute inset-0 flex items-center justify-center">
                                <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 5v14l11-7z"/>
                                </svg>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- modal QR Code -->
    <div id="qrCodeModal" class="hidden fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex justify-center items-center z-50">
        <div class="bg-black rounded-lg p-6 border-2" style="border-color: {{ $carte->couleur1 }}">
            <h2 class="text-2xl font-bold text-center text-white mb-4">Votre QR Code</h2>
            <div class="flex justify-center">
                <img src="{{ $carte['lienQr'] }}" alt="QR Code" class="max-w-xs">
            </div>
            <div class="mt-6 flex justify-end">
                <button onclick="closeQrCode()" class="px-4 py-2 text-white rounded-lg" style="background-color: {{ $carte->couleur1 }}">
                    Fermer
                </button>
            </div>
        </div>
    </div>
    
    <!-- Modal Image -->
    <div id="imageModal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-90 flex items-center justify-center">
        <div class="relative max-w-4xl w-full mx-4">
            <img id="modalImage" src="" alt="Image en grand" class="w-full h-auto">
            <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white text-xl font-bold">&times;</button>
        </div>
    </div>

    <!-- Modal Vidéo -->
    <div id="videoModal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-90 flex items-center justify-center">
        <div class="relative max-w-4xl w-full mx-4 aspect-video">
            <iframe id="modalVideo" class="w-full h-full" frameborder="0" allowfullscreen></iframe>
            <button onclick="closeVideoModal()" class="absolute top-4 right-4 text-white text-xl font-bold">&times;</button>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Fonctions pour le modal QR Code
        function showQrCode() {
            document.getElementById('qrCodeModal').classList.remove('hidden');
        }

        function closeQrCode() {
            document.getElementById('qrCodeModal').classList.add('hidden');
        }

        // PWA Installation
        let deferredPrompt;
        
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            if (!localStorage.getItem('installPromptDismissed')) {
                document.getElementById('installButton').style.display = 'flex';
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
                document.getElementById('installButton').style.display = 'none';
            }
        });

        // Fonction de partage
        function shareOrCopyLink() {
            const linkToShare = "{{ url()->current() }}";
            if (navigator.share) {
                navigator.share({
                    title: document.title,
                    text: "Découvrez ce profil sur Wisikard !",
                    url: linkToShare
                }).catch(console.error);
            } else {
                navigator.clipboard.writeText(linkToShare).then(() => {
                    alert("Lien copié !");
                }).catch(console.error);
            }
        }

        // Fonctions pour le modal Image
        function openImageModal(imageSrc) {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            modalImage.src = imageSrc;
            modal.classList.remove('hidden');
        }

        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.add('hidden');
        }

        // Fonctions pour le modal Vidéo
        function openVideoModal(videoId) {
            const modal = document.getElementById('videoModal');
            const modalVideo = document.getElementById('modalVideo');
            modalVideo.src = `https://www.youtube.com/embed/${videoId}?autoplay=1`;
            modal.classList.remove('hidden');
        }

        function closeVideoModal() {
            const modal = document.getElementById('videoModal');
            const modalVideo = document.getElementById('modalVideo');
            modalVideo.src = '';
            modal.classList.add('hidden');
        }

        // Fermer les modals avec la touche Echap
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeImageModal();
                closeVideoModal();
            }
        });
    </script>
</body>
</html>