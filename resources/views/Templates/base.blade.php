<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $carte['nomEntreprise'] ? $carte['nomEntreprise'] . ' - ' : '' }}Wisikard</title>

    <script src="https://cdn.lordicon.com/lordicon.js"></script>

    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Montserrat:wght@400;700&family=Oswald:wght@400;700&family=Ubuntu:wght@400;700&family=Playfair+Display:wght@400;700&family=Work+Sans:wght@400;700&family=Bona+Nova:wght@400;700&family=Exo+2:wght@400;700&family=Pacifico&family=Gruppo&family=Rokkitt:wght@400;700&display=swap"
          rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="h-100%" style="font-family: '{{ $carte['font'] }}';">

<!-- Presentation entreprise -->
<div class=" w-full h-52 text-white p-4 bg-gradient-to-tl from-red-800 to-zinc-900 rounded-sm">
    <!-- Logo -->
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

    @if(!empty($logoPath))
        <div class="">
            <img src="{{ $logoPath }}" alt="Logo de l'entreprise" class="h-24 w-24 mx-auto ">
        </div>
    @endif

    <!-- Nom de l'entreprise -->
    @if($carte['nomEntreprise'])
        <div class="mt-2">
            <h1 class="text-white text-3xl text-center">{{ $carte['nomEntreprise'] }}</h1>
        </div>
    @endif

    <!-- description de l'entreprise -->
    @if($carte['descriptif'])
        <div>
            <p class="text-white text-center text-lg">{{ $carte['descriptif'] }}</p>
        </div>
    @endif
</div>

<!-- VCard / Qr Code -->
<div class="flex items-center justify-center w-full mt-4 gap-4">


    <!-- Bouton QR Code -->
    <a onclick="openQrModal()"
       class="cursor-pointer w-36 rounded-xl p-2 font-bold text-white text-center border border-gray-200 bg-zinc-800">
        QR Code
    </a>

    <!-- Modal QR Code -->
    <div id="qrModal"
         class="hidden fixed inset-0 bg-zinc-800 bg-opacity-50 backdrop-blur-sm flex justify-center items-center z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-11/12 max-w-sm">
            <!-- Titre -->
            <h2 class="text-2xl font-bold text-gray-800 text-center">Votre QR Code</h2>

            <!-- Contenu QR Code -->
            <div class="mt-4 text-center">
                <img src="{{ $carte['lienQr'] }}" alt="QR Code" class="mx-auto max-h-64">
            </div>

            <!-- Bouton de fermeture -->
            <div class="mt-6 flex justify-end">
                <button onclick="closeQrModal()"
                        class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                    Fermer
                </button>
            </div>
        </div>
    </div>

    <!-- horaires -->
    <!-- Bouton pour ouvrir le modal -->
    <div class="flex justify-center">
        <btn onclick="openModal()"
             class="w-12 h-12 flex items-center justify-center rounded-xl p-2 font-bold text-white text-center border border-gray-200 cursor-pointer">
            <lord-icon
                    src="https://cdn.lordicon.com/warimioc.json"
                    trigger="loop"
                    delay="1000"
                    colors="primary:#000000,secondary:#9f0712">
            </lord-icon>
        </btn>

    </div>

    <!-- Modal (invisible par défaut) -->
    <div id="horairesModal"
         class="hidden fixed inset-0 bg-zinc-800 bg-opacity-50 flex items-center justify-center z-50">
        <!-- Contenu du modal -->
        <div class="bg-white w-11/12 max-w-lg rounded-lg shadow-lg p-6">
            <!-- Titre -->
            <div class="flex justify-between items-center border-b border-zinc-700 pb-5">
                <h3 class="text-2xl font-bold text-gray-800">Horaires de la semaine</h3>
            </div>

            <!-- Contenu des horaires -->
            <div class="mt-4">
                <ul class="text-gray-700 list-disc list-inside">
                    @foreach($horaires as $jour => $horaire)
                        <li>
                            @php
                                $jours = [
                                    0 => 'Lundi',
                                    1 => 'Mardi',
                                    2 => 'Mercredi',
                                    3 => 'Jeudi',
                                    4 => 'Vendredi',
                                    5 => 'Samedi',
                                    6 => 'Dimanche'
                                ];
                            @endphp

                            <strong class="text-red-600">{{ $jours[$jour] ?? 'Jour inconnu' }} :</strong>

                            @if($horaire->ouverture_matin && $horaire->fermeture_matin && $horaire->ouverture_aprmidi && $horaire->fermeture_aprmidi)
                                {{ date('H:i', strtotime($horaire->ouverture_matin)) }}
                                - {{ date('H:i', strtotime($horaire->fermeture_matin)) }} /
                                {{ date('H:i', strtotime($horaire->ouverture_aprmidi)) }}
                                - {{ date('H:i', strtotime($horaire->fermeture_aprmidi)) }}
                            @else
                                Fermé
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Bouton de fermeture -->
            <div class="mt-6 flex justify-end">
                <button onclick="closeModal()"
                        class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                    Fermer
                </button>
            </div>
        </div>
    </div>

    <!-- partage -->
    <!-- Bouton de partage -->
    <button
            onclick="shareOrCopyLink()"
            class="w-36 rounded-xl p-2 font-bold text-white text-center border border-gray-200 bg-zinc-800 hover:bg-zinc-700 transition">
        Partager
    </button>
    {{--
    <button
            onclick="copyLink()"
            class="w-36 rounded-xl p-2 font-bold text-white text-center border border-gray-200 bg-zinc-800 hover:bg-zinc-700 transition">
        Partager
    </button>

    <!-- Notification avec barre de progression -->
    <div id="copyNotification"
         class="hidden fixed bottom-5 right-5 bg-zinc-800 text-white text-lg px-3 py-2 rounded-lg shadow-lg w-48">
        Lien copié !
        <!-- Barre de progression -->
        <div class="w-full bg-zinc-950 h-1 mt-2">
            <div id="progressBar" class="bg-white h-full w-full"></div>
        </div>
    </div>
    --}}

</div>

<!-- Informations -->
<div class="w-full mt-4">
    <!-- Map -->
    @if($carte['ville'])
        <div class="w-full h-full flex justify-center items-center">
            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($carte['nomEntreprise'] . ' ' . $carte['ville']) }}"
               class="w-full h-12 mx-2 px-2 text-center bg-white font-bold rounded-lg border border-gray-200 text-gray-800 flex items-center ">
                <lord-icon
                        src="https://cdn.lordicon.com/surcxhka.json"
                        trigger="loop"
                        delay="1000"
                        colors="primary:#000000,secondary:#9f0712"
                        class="mr-2">
                </lord-icon>
                Maps
            </a>
        </div>
    @endif

    <!-- site web -->
    @if($carte['LienCommande'])
        <div class="w-full h-full flex justify-center items-center mt-2">
            <a href="{{ $carte['LienCommande'] }}"
               class="w-full h-12 mx-2 px-2 text-center bg-white font-bold rounded-lg border border-gray-200 text-gray-800 flex items-center">
                <lord-icon
                        src="https://cdn.lordicon.com/pbbsmkso.json"
                        trigger="loop"
                        delay="1000"
                        colors="primary:#000000,secondary:#9f0712"
                        class="mr-2">
                </lord-icon>
                Site web
            </a>
        </div>
    @endif

    <!-- telephones -->
    @if($carte['tel'])
        <div class="w-full h-full flex justify-center items-center mt-2">
            <a href="tel:{{ $carte['tel'] }}"
               class="w-full h-12 mx-2 px-2 text-center bg-white font-bold rounded-lg border border-gray-200 text-gray-800 flex items-center">
                <lord-icon
                        src="https://cdn.lordicon.com/qtykvslf.json"
                        trigger="loop"
                        delay="1000"
                        colors="primary:#000000,secondary:#9f0712"
                        class="mr-2">
                </lord-icon>
                Téléphone
            </a>
        </div>
    @endif

    <!-- Email -->
    @if($compte['email'])
        <div class="w-full h-full flex justify-center items-center mt-2">
            <a href="mailto:{{ $compte['email'] }}"
               class="w-full h-12 mx-2 px-2 text-center bg-white font-bold rounded-lg border border-gray-200 text-gray-800 flex items-center">
                <lord-icon
                        src="https://cdn.lordicon.com/aycieyht.json"
                        trigger="loop"
                        delay="1000"
                        colors="primary:#000000,secondary:#9f0712"
                        class="mr-2">
                </lord-icon>
                Email
            </a>
        </div>
    @endif

    <!-- PDF -->
    @if($carte['pdf'])
        <div class="w-full h-full flex justify-center items-center mt-2">
            <a href="{{ $carte['pdf'] }}" download
               class="w-full h-12 mx-2 px-4 text-center bg-white font-bold rounded-lg border border-gray-300 text-gray-800 flex items-center hover:bg-gray-100 transition duration-200">
                <lord-icon
                        src="https://cdn.lordicon.com/wzwygmng.json"
                        trigger="loop"
                        delay="1000"
                        colors="primary:#000000,secondary:#9f0712"
                        class="mr-2 w-6 h-6">
                </lord-icon>
                {{$carte['nomBtnPdf']}}
            </a>
        </div>
    @endif

    <!-- Rdv -->
    @if($carte['LienCommande'])
        <div class="w-full h-full flex justify-center items-center mt-2">
            <a href="{{ $carte['LienRdv'] }}"
               class="w-full h-12 mx-2 px-2 text-center bg-white font-bold rounded-lg border border-gray-200 text-gray-800 flex items-center">
                <lord-icon
                        src="https://cdn.lordicon.com/jdgfsfzr.json"
                        trigger="loop"
                        delay="1000"
                        colors="primary:#000000,secondary:#9f0712"
                        class="mr-2">
                </lord-icon>
                Prendre un rendez-vous
            </a>
        </div>
    @endif

    <!-- fiche de contacte -->
    <div class="w-full h-full flex justify-center items-center mt-2">
        <a href="{{ '/entreprises/'. $carte->compte->idCompte.'_'.$carte->nomEntreprise.'/VCF_Files/contact.vcf' }}"
           class="w-full h-12 mx-2 px-2 text-center bg-white font-bold rounded-lg border border-gray-200 text-gray-800 flex items-center">
            <lord-icon
                    src="https://cdn.lordicon.com/kdduutaw.json"
                    trigger="loop"
                    delay="1000"
                    colors="primary:#000000,secondary:#9f0712"
                    class="mr-2">
            </lord-icon>
            Fiche de contact
        </a>
    </div>

    <!-- Liens Avis -->
    @if($carte['lienAvis'])
        <div class="w-full h-full flex justify-center items-center mt-2">
            <a href="{{ $carte['lienAvis'] }}"
               class="w-full h-12 mx-2 px-2 text-center bg-white font-bold rounded-lg border border-gray-200 text-gray-800 flex items-center">
                <lord-icon
                        src="https://cdn.lordicon.com/fozsorqm.json"
                        trigger="loop"
                        delay="1000"
                        colors="primary:#000000,secondary:#9f0712"
                        class="mr-2">
                </lord-icon>
                Avis
            </a>
        </div>
    @endif

    <!-- Container principal -->
    <div class="w-full h-full flex justify-center items-start mt-2 relative">
        <!-- Bouton principal -->
        <button id="toggleLinksButton"
                onclick="toggleLinksDropdown()"
                class="w-full h-12 mx-2 px-2 bg-white font-bold rounded-lg border border-gray-200 text-gray-800 flex items-center">
            <lord-icon
                    src="https://cdn.lordicon.com/lcvlsnre.json"
                    trigger="loop"
                    delay="1000"
                    colors="primary:#000000,secondary:#9f0712"
                    class="mr-2">
            </lord-icon>
            Liens personnalisés
        </button>

        <!-- Liste déroulante personnalisée (cachée par défaut) -->
        <div id="customLinksDropdown"
             class="hidden absolute z-50 w-full bg-gray-100 rounded-lg border border-gray-300 shadow-lg mt-14 mx-2">
            <ul class="divide-y divide-gray-300">
                <!-- Boucle Laravel pour générer les liens -->
                @foreach ($custom as $link)
                    <li class="flex items-center h-12 px-2 ">
                        <!-- Icône gauche -->
                        <lord-icon
                                src="https://cdn.lordicon.com/exymduqj.json"
                                trigger="hover"
                                delay="1000"
                                colors="primary:#000000,secondary:#9f0712"
                                class="w-6 h-6">
                        </lord-icon>
                        <!-- Texte du lien -->
                        <a href="{{ $link['lien'] }}"
                           target="_blank"
                           class="ml-3 text-gray-800 font-bold text-sm flex-1">
                            {{ $link['nom'] }}
                        </a>
                        <!-- Icône droite -->
                        <svg xmlns="http://www.w3.org/2000/svg"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke-width="2"
                             stroke="currentColor"
                             class="w-5 h-5 text-gray-400">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

</div>

<div class="flex items-center justify-center space-x-6">
    <div class="flex-cols">
        <!-- Gelerie -->
        <div class="w-full mt-4">
            <div class="flex flex-wrap items-center justify-center gap-4">
                <!-- Bouton pour afficher les photos -->
                <button onclick="openGallery()"
                        class="w-24 flex items-center justify-center rounded-xl p-4 font-bold text-white text-center border border-gray-200">
                    <lord-icon
                            src="https://cdn.lordicon.com/rszslpey.json"
                            trigger="loop"
                            delay="1000"
                            colors="primary:#000000,secondary:#9f0712">
                    </lord-icon>
                </button>
            </div>
        </div>

        <!-- Galerie Modale -->
        <!-- PHP : Liste des images extraites du dossier "slider" -->
        @php
            $sliderDirectory = public_path('entreprises/'.$carte->idCompte.'_'.$carte->nomEntreprise.'/slider');
            $sliderImages = file_exists($sliderDirectory) ? array_values(array_diff(scandir($sliderDirectory), array('.', '..'))) : [];
        @endphp

                <!-- Section pour afficher un bouton qui ouvre la galerie -->
        <div class="w-full mt-4 text-center">
            <button onclick="openGallery()"
                    class="w-32 rounded-xl p-2 font-bold text-white text-center bg-zinc-800 border border-gray-200">
                Voir la Galerie
            </button>
        </div>

        <!-- Galerie (Lightbox) -->
        <div id="photoGallery"
             class="hidden fixed top-0 left-0 w-full h-full bg-zinc-800 bg-opacity-75 flex justify-center items-center z-50">
            <div class="relative bg-white p-6 rounded-lg w-11/12 md:w-2/3 lg:w-1/2">
                <!-- Bouton pour fermer la galerie -->
                <button onclick="closeGallery()" class="absolute top-2 right-2 p-2 text-red-500 font-bold text-xl">
                    &times;
                </button>

                <h2 class="text-center font-bold text-lg mb-4">Galerie de Photos</h2>

                <!-- Liste d'images -->
                <div class="flex flex-wrap gap-4 justify-center items-center">
                    @foreach($sliderImages as $image)
                        <img src="{{ asset('entreprises/'.$carte->idCompte.'_'.$carte->nomEntreprise.'/slider/'.$image) }}"
                             alt="Image slider"
                             class="w-1/3 rounded-lg shadow-md cursor-pointer"
                             onclick="viewImage('{{ asset('entreprises/'.$carte->idCompte.'_'.$carte->nomEntreprise.'/slider/'.$image) }}')">
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Affichage d'une image en plein écran -->
        <div id="fullImage"
             class="hidden fixed top-0 left-0 w-full h-full bg-zinc-800 bg-opacity-90 flex justify-center items-center z-50">
            <div class="relative">
                <!-- Image en taille réelle -->
                <img id="fullImageContent" src="" alt="Image en grand"
                     class="max-w-full max-h-full rounded-lg shadow-lg">

                <!-- Bouton pour fermer -->
                <button onclick="closeFullImage()" class="absolute top-2 right-2 text-red-800 text-2xl font-bold">
                    &times;
                </button>
            </div>
        </div>
    </div>

    <div class="flex-cols">
        <!-- Section Vidéos -->
        <div class="w-full mt-4">
            <div class="flex flex-wrap items-center justify-center gap-4">
                <!-- Bouton principal pour ouvrir la galerie de vidéos -->
                <button onclick="openVideoGallery()"
                        class="w-24 flex items-center justify-center rounded-xl p-4 font-bold text-white text-center border border-gray-200">
                    <lord-icon
                            src="https://cdn.lordicon.com/bomiazxt.json"
                            trigger="loop"
                            delay="1000"
                            colors="primary:#000000,secondary:#9f0712">
                    </lord-icon>
                </button>
            </div>
        </div>

        <!-- Section pour afficher un bouton individuel -->
        <div class="w-full mt-4 text-center">
            <button onclick="openVideoGallery()"
                    class="w-32 rounded-xl p-2 font-bold text-white text-center bg-zinc-800 border border-gray-200">
                Voir les Vidéos
            </button>
        </div>

        <!-- Galerie Vidéos Modale -->
        <div id="videoGallery"
             class="hidden fixed top-0 left-0 w-full h-full bg-zinc-800 bg-opacity-75 flex justify-center items-center z-50">
            <div class="relative bg-white p-6 rounded-lg w-11/12 md:w-2/3 lg:w-1/2">
                <!-- Bouton pour fermer la galerie -->
                <button onclick="closeVideoGallery()" class="absolute top-2 right-2 p-2 text-red-500 font-bold text-xl">
                    &times;
                </button>

                <h2 class="text-center font-bold text-lg mb-4">Galerie de Vidéos</h2>

                <!-- Liste des vidéos YouTube -->
                <div class="flex flex-wrap gap-4 justify-center items-center">
                    @foreach($youtubeUrls as $url)
                        @php
                            // Extraire l'ID vidéo YouTube
                            $videoId = preg_replace('/^.*?v=([\w\-]+).*$/', '$1', $url);
                        @endphp
                                <!-- Miniature de vidéo cliquable -->
                        <a href="{{ $url }}" target="_blank" rel="noopener noreferrer"
                           class="w-1/3 sm:w-1/4 lg:w-1/5 aspect-video relative rounded-lg overflow-hidden block">
                            <img src="https://img.youtube.com/vi/{{ $videoId }}/mqdefault.jpg"
                                 alt="Thumbnail"
                                 class="w-full h-full object-cover">
                            <div class="absolute inset-0 flex justify-center items-center">
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Réseaux sociaux -->
<div class="flex flex-wrap items-center justify-center w-full mt-4 gap-4">
    @foreach($mergedSocial as $so)
        <a href="{{ $so['lien'] }}" target="_blank" rel="noopener noreferrer"
           class="p-3">
            <div class="flex items-center justify-center">
                <div class="w-12 h-12 flex items-center justify-center">
                    <!-- Apporter la couleur blanche aux logos -->
                    <div class="text-white fill-red-800 hover:fill-black">
                        {!! $so['logo'] !!}
                    </div>
                </div>
            </div>
        </a>
    @endforeach
</div>

<script>
    // **Script pour le Modal QR Code**
    function openQrModal() {
        const qrModal = document.getElementById('qrModal');
        qrModal.classList.remove('hidden');
        qrModal.classList.add('flex');
    }

    function closeQrModal() {
        const qrModal = document.getElementById('qrModal');
        qrModal.classList.add('hidden');
        qrModal.classList.remove('flex');
    }

    // **Script pour le Modal Horaires**
    function openModal() {
        const modal = document.getElementById('horairesModal');
        modal.classList.remove('hidden');
    }

    function closeModal() {
        const modal = document.getElementById('horairesModal');
        modal.classList.add('hidden');
    }

    // **Script pour la copie du lien**
    {{--
    function copyLink() {
        const linkToCopy = "{{ url()->current().'?idCompte='.$carte->compte->idCompte }}";

        navigator.clipboard.writeText(linkToCopy).then(() => {
            const notification = document.getElementById('copyNotification');
            const progressBar = document.getElementById('progressBar');

            notification.classList.remove('hidden');

            progressBar.style.width = '100%';

            setTimeout(() => {
                progressBar.style.transition = 'width 3s linear';
                progressBar.style.width = '0%';
            }, 10);

            setTimeout(() => {
                notification.classList.add('hidden');
                progressBar.style.transition = 'none';
                progressBar.style.width = '100%';
            }, 3100);
        }).catch(err => {
            console.error("Erreur lors de la copie du lien :", err);
        });
    }--}}

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

    // **Scripts pour les galeries (photos et vidéos)**

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

    function toggleLinksDropdown() {
        const dropdown = document.getElementById('customLinksDropdown');

        // Alterne entre afficher (supprimer `hidden`) et cacher (ajouter `hidden`)
        dropdown.classList.toggle('hidden');
    }

    // Fermer le menu si l'utilisateur clique ailleurs
    document.addEventListener('click', function (event) {
        const dropdown = document.getElementById('customLinksDropdown');
        const button = document.getElementById('toggleLinksButton');

        if (!dropdown.contains(event.target) && !button.contains(event.target)) {
            // Ajoute la classe `hidden` si clic en dehors
            dropdown.classList.add('hidden');
        }
    });
</script>

<footer class="bg-zinc-900 text-center p-4 text-gray-200 text-sm bottom-0 w-full">
    © {{ date('Y') }} - Un service proposé par
    <a href="https://sendix.fr" class="text-blue-400 hover:underline">SENDIX</a> -
    <a href="https://wisikard.fr" class="text-blue-400 hover:underline">Wisikard</a>
</footer>

</body>
</html>