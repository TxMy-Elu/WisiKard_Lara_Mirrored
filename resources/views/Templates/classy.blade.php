<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $carte['nomEntreprise'] ? $carte['nomEntreprise'] . ' - ' : '' }}Wisikard</title>
    <script src="https://cdn.lordicon.com/lordicon.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Montserrat:wght@400;700&family=Oswald:wght@400;700&family=Ubuntu:wght@400;700&family=Playfair+Display:wght@400;700&family=Work+Sans:wght@400;700&family=Bona+Nova:wght@400;700&family=Exo+2:wght@400;700&family=Pacifico&family=Gruppo&family=Rokkitt:wght@400;700&display=swap"
          rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="bg-zinc-900 w-full" style="font-family: '{{ $carte['font'] }}';">
<div class="flex gap-10 items-center mt-4 ml-4">
    <div class="bg-white w-48 h-32 flex justify-center items-center overflow-hidden p-2 rounded-lg">
        @php
            $logoPath = '';
            $formats = ['svg', 'png', 'jpg', 'jpeg'];
            foreach ($formats as $format) {
                $path = public_path('entreprises/' . $carte->compte->idCompte . '_' . $carte->nomEntreprise . '/logos/logo.' . $format);
                if (file_exists($path)) {
                    $logoPath = asset('entreprises/' . $carte->compte->idCompte . '_' . $carte->nomEntreprise . '/logos/logo.' . $format);
                    break;
                }
            }
        @endphp

        @if(!empty($logoPath))
            <div class="w-full h-full">
                <img src="{{ $logoPath }}" alt="Logo de l'entreprise" class="w-full h-full object-contain">
            </div>
        @endif
    </div>
    <div class="text-white space-y-2 ">
        @if($carte['nomEntreprise'])
            <div>
                <h1 class="text-white text-2xl font-bold">{{ $carte['nomEntreprise'] }}</h1>
            </div>
        @endif

        @if($carte['titre'])
            <div>
                <h2 class="text-slate-200 text-lg">{{ $carte['titre'] }}</h2>
            </div>
        @endif
    </div>
</div>

@if($carte['descriptif'])
    <div class="bg-white p-2 mt-4 mx-4 rounded-lg shadow-sm">
        <p class="text-zinc-500 text-center text-sm">{{ $carte['descriptif'] }}</p>
    </div>
@endif

<div class="bg-white p-4 mt-4 mx-4 space-y-4 rounded-lg shadow-lg">
    @if($compte['email'])
        <div class="flex items-center justify-center">
            <a href="mailto:{{ $compte['email'] }}"
               class="w-full h-12 px-4 bg-gray-100 hover:bg-gray-200 text-zinc-800 font-medium rounded-lg border border-gray-300 flex items-center justify-center shadow-sm transition duration-200">
                <p>📧 Email: {{ $compte['email'] }}</p>
            </a>
        </div>
    @endif
    @if($carte['tel'])
        @php
            function formatPhone($phone) {
                $cleaned = preg_replace('/[^0-9]/', '', $phone);
                if (strlen($cleaned) === 10) {
                    return preg_replace('/(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})/', '$1 $2 $3 $4 $5', $cleaned);
                }
                return $phone;
            }
        @endphp

        <div class="flex items-center justify-center">
            <a href="tel:{{ $carte['tel'] }}"
               class="w-full h-12 px-4 bg-gray-100 hover:bg-gray-200 text-zinc-800 font-medium rounded-lg border border-gray-300 flex items-center justify-center shadow-sm transition duration-200">
                <p class="flex items-center gap-2">
                    📞 <span class="tracking-wide">Télephone: {{ formatPhone($carte['tel']) }}</span>
                </p>
            </a>
        </div>
    @endif
</div>
<div class="flex items-center justify-between mt-4 mx-4 gap-4">
    <div class="flex justify-center w-full">
        <a onclick="openQrModal()"
           class="cursor-pointer w-full rounded-lg px-6 h-10 font-semibold text-gray-800 text-center border border-gray-300 bg-white hover:text-white hover:bg-gray-800 hover:shadow-lg transition duration-300 ease-in-out flex items-center justify-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="25" height="25" fill="#9f0712">
                <path d="M0 80C0 53.5 21.5 32 48 32l96 0c26.5 0 48 21.5 48 48l0 96c0 26.5-21.5 48-48 48l-96 0c-26.5 0-48-21.5-48-48L0 80zM64 96l0 64 64 0 0-64L64 96zM0 336c0-26.5 21.5-48 48-48l96 0c26.5 0 48 21.5 48 48l0 96c0 26.5-21.5 48-48 48l-96 0c-26.5 0-48-21.5-48-48l0-96zm64 16l0 64 64 0 0-64-64 0zM304 32l96 0c26.5 0 48 21.5 48 48l0 96c0 26.5-21.5 48-48 48l-96 0c-26.5 0-48-21.5-48-48l0-96c0-26.5 21.5-48 48-48zm80 64l-64 0 0 64 64 0 0-64zM256 304c0-8.8 7.2-16 16-16l64 0c8.8 0 16 7.2 16 16s7.2 16 16 16l32 0c8.8 0 16-7.2 16-16s7.2-16 16-16s16 7.2 16 16l0 96c0 8.8-7.2 16-16 16l-64 0c-8.8 0-16-7.2-16-16s-7.2-16-16-16s-16 7.2-16 16l0 64c0 8.8-7.2 16-16 16l-32 0c-8.8 0-16-7.2-16-16l0-160zM368 480a16 16 0 1 1 0-32 16 16 0 1 1 0 32zm64 0a16 16 0 1 1 0-32 16 16 0 1 1 0 32z"/>
            </svg>
            QR Code
        </a>

        <div id="qrModal"
             class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center z-50">
            <div class="bg-white rounded-lg shadow-lg p-6 w-11/12 max-w-sm">
                <h2 class="text-xl font-semibold text-gray-800 text-center">Votre QR Code</h2>
                <div class="mt-4 text-center">
                    <img src="{{ $carte['lienQr'] }}" alt="QR Code" class="mx-auto max-h-64">
                </div>
                <div class="mt-6 text-center">
                    <button onclick="closeQrModal()"
                            class="px-4 py-2 bg-gray-800 text-white text-sm font-medium rounded-lg hover:bg-gray-900 transition duration-300 ease-in-out">
                        Fermer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="flex justify-center">
        <button onclick="openModal()"
                class="w-10 h-10 rounded-lg bg-white border border-gray-300 text-gray-800 flex items-center justify-center hover:text-white hover:bg-gray-800 hover:shadow-lg transition duration-300 ease-in-out">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="25" height="25" fill="#9f0712">
                <path d="M464 256A208 208 0 1 1 48 256a208 208 0 1 1 416 0zM0 256a256 256 0 1 0 512 0A256 256 0 1 0 0 256zM232 120l0 136c0 8 4 15.5 10.7 20l96 64c11 7.4 25.9 4.4 33.3-6.7s4.4-25.9-6.7-33.3L280 243.2 280 120c0-13.3-10.7-24-24-24s-24 10.7-24 24z"/>
            </svg>
        </button>

        <div id="horairesModal"
             class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center z-50">
            <div class="bg-white w-11/12 max-w-lg rounded-lg shadow-lg p-6">
                <div class="flex justify-between items-center border-b border-zinc-700 pb-5">
                    <h3 class="text-2xl font-bold text-gray-800">Horaires de la semaine</h3>
                </div>

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

                <div class="mt-6 flex justify-end">
                    <button onclick="closeModal()"
                            class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                        Fermer
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="flex items-center justify-between mt-4 mx-4 gap-4">
    <div class="flex justify-center">
        <button onclick=""
                class="w-10 h-10 rounded-lg bg-white border border-gray-300 text-gray-800 flex items-center justify-center hover:text-white hover:bg-gray-800 hover:shadow-lg transition duration-300 ease-in-out">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="25" height="25" fill="#9f0712">
                <!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                <path d="M512 80c8.8 0 16 7.2 16 16l0 320c0 8.8-7.2 16-16 16L64 432c-8.8 0-16-7.2-16-16L48 96c0-8.8 7.2-16 16-16l448 0zM64 32C28.7 32 0 60.7 0 96L0 416c0 35.3 28.7 64 64 64l448 0c35.3 0 64-28.7 64-64l0-320c0-35.3-28.7-64-64-64L64 32zM208 256a64 64 0 1 0 0-128 64 64 0 1 0 0 128zm-32 32c-44.2 0-80 35.8-80 80c0 8.8 7.2 16 16 16l192 0c8.8 0 16-7.2 16-16c0-44.2-35.8-80-80-80l-64 0zM376 144c-13.3 0-24 10.7-24 24s10.7 24 24 24l80 0c13.3 0 24-10.7 24-24s-10.7-24-24-24l-80 0zm0 96c-13.3 0-24 10.7-24 24s10.7 24 24 24l80 0c13.3 0 24-10.7 24-24s-10.7-24-24-24l-80 0z"/>
            </svg>
        </button>
    </div>

    <div class="flex justify-center w-full">
        <a onclick="shareOrCopyLink()"
           class="cursor-pointer w-full rounded-lg px-6 h-10 font-semibold text-gray-800 text-center border border-gray-300 bg-white hover:text-white hover:bg-gray-800 hover:shadow-lg transition duration-300 ease-in-out flex items-center justify-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="25" height="25" fill="#9f0712">
                <path d="M400 255.4l0-15.4 0-32c0-8.8-7.2-16-16-16l-32 0-16 0-46.5 0c-50.9 0-93.9 33.5-108.3 79.6c-3.3-9.4-5.2-19.8-5.2-31.6c0-61.9 50.1-112 112-112l48 0 16 0 32 0c8.8 0 16-7.2 16-16l0-32 0-15.4L506 160 400 255.4zM336 240l16 0 0 48c0 17.7 14.3 32 32 32l3.7 0c7.9 0 15.5-2.9 21.4-8.2l139-125.1c7.6-6.8 11.9-16.5 11.9-26.7s-4.3-19.9-11.9-26.7L409.9 8.9C403.5 3.2 395.3 0 386.7 0C367.5 0 352 15.5 352 34.7L352 80l-16 0-32 0-16 0c-88.4 0-160 71.6-160 160c0 60.4 34.6 99.1 63.9 120.9c5.9 4.4 11.5 8.1 16.7 11.2c4.4 2.7 8.5 4.9 11.9 6.6c3.4 1.7 6.2 3 8.2 3.9c2.2 1 4.6 1.4 7.1 1.4l2.5 0c9.8 0 17.8-8 17.8-17.8c0-7.8-5.3-14.7-11.6-19.5c0 0 0 0 0 0c-.4-.3-.7-.5-1.1-.8c-1.7-1.1-3.4-2.5-5-4.1c-.8-.8-1.7-1.6-2.5-2.6s-1.6-1.9-2.4-2.9c-1.8-2.5-3.5-5.3-5-8.5c-2.6-6-4.3-13.3-4.3-22.4c0-36.1 29.3-65.5 65.5-65.5l14.5 0 32 0c0 13.3-10.7 24-24 24l0-64c0-35.3 21.5-48 48-48l0-336c0-35.3 10.7-24 24-24l64 0c13.3 0 24-10.7 24-24s-10.7-24-24-24l-80 0zM72 32C32.2 32 0 64.2 0 104L0 440c0 39.8 32.2 72 72 72l336 0c39.8 0 72-32.2 72-72l0-64c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 64c0 13.3-10.7 24-24 24L72 464c-13.3 0-24-10.7-24-24l0-336c0-13.3 10.7-24 24-24l64 0c13.3 0 24-10.7 24-24s-10.7-24-24-24l-80 0z"/>
            </svg>
            Partager
        </a>
    </div>
</div>


<div class="flex items-center justify-between mt-4 mx-4 gap-4">
    <div class="flex-cols justify-center space-y-4">
        <!-- Avis google -->
        @if($carte['lienAvis'])
            <div class="flex justify-center">
                <a href="{{ $carte['lienAvis'] }}"
                   class="w-full rounded-lg px-6 h-10 font-semibold text-gray-800 text-center border border-gray-300 bg-white hover:text-white hover:bg-gray-800 hover:shadow-lg transition duration-300 ease-in-out flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="25" height="25" fill="#9f0712">
                        <!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                        <path d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z"/>
                    </svg>

                    Avis Google
                </a>
            </div>
        @endif
        <!-- Site Web -->
        <div></div>
        <!-- Rdv -->
        @if($carte['LienCommande'])
            <div class="flex justify-center">
                <a href="{{ $carte['LienCommande'] }}"
                   class="w-full rounded-lg px-6 py-6 h-10 font-semibold text-gray-800 text-center border border-gray-300 bg-white hover:text-white hover:bg-gray-800 hover:shadow-lg transition duration-300 ease-in-out flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="25" height="25" fill="#9f0712">
                        <!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                        <path d="M152 24c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 40L64 64C28.7 64 0 92.7 0 128l0 16 0 48L0 448c0 35.3 28.7 64 64 64l320 0c35.3 0 64-28.7 64-64l0-256 0-48 0-16c0-35.3-28.7-64-64-64l-40 0 0-40c0-13.3-10.7-24-24-24s-24 10.7-24 24l0 40L152 64l0-40zM48 192l352 0 0 256c0 8.8-7.2 16-16 16L64 464c-8.8 0-16-7.2-16-16l0-256zm176 40c-13.3 0-24 10.7-24 24l0 48-48 0c-13.3 0-24 10.7-24 24s10.7 24 24 24l48 0 0 48c0 13.3 10.7 24 24 24s24-10.7 24-24l0-48 48 0c13.3 0 24-10.7 24-24s-10.7-24-24-24l-48 0 0-48c0-13.3-10.7-24-24-24z"/>
                    </svg>
                    Rendez-vous
                </a>
            </div>
        @endif
    </div>
    <div id="map" class="w-40 h-60 rounded-lg"></div>
</div>


<script>
    var map = L.map('map').setView([48.8566, 2.3522], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    async function rechercherEntreprise() {
        const nom = "{{ $carte['nomEntreprise'] }}";
        const ville = "{{ $carte['ville'] }}";

        console.log(nom, ville);

        try {
            const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${nom},${ville}`);
            const data = await response.json();

            if (data.length > 0) {
                const location = data[0];
                const lat = location.lat;
                const lon = location.lon;

                map.setView([lat, lon], 15);
                L.marker([lat, lon]).addTo(map);
            } else {
                alert("Aucune entreprise ou adresse trouvée.");
            }
        } catch (error) {
            console.error("Erreur lors de la recherche d'entreprise ou d'adresse:", error);
        }
    }

    // Appel de la fonction de recherche au chargement de la page
    rechercherEntreprise();
</script>

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

</script>

</body>
</html>
