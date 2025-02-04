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
    <!-- Carte de Contact -->
    <a href="{{ '/entreprises/'. $carte->compte->idCompte.'_'.$carte->nomEntreprise.'/VCF_Files/contact.vcf' }}"
       class="w-48 rounded-xl p-2 font-bold text-white text-center border border-gray-200 bg-zinc-800">
        Fiche de Contact
    </a>

    <!-- QR Codes -->
    <a href="{{ $carte['LienQr'] }}"
       class="w-36 rounded-xl p-2 font-bold text-white text-center border border-gray-200 bg-zinc-800" >
        QR Code
    </a>
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
                        colors="primary:#000000,secondary:{{ $carte['couleur1'] }}"
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
                        colors="primary:#000000,secondary:{{ $carte['couleur1'] }}"
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
                        colors="primary:#000000,secondary:{{ $carte['couleur1'] }}"
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
                        colors="primary:#000000,secondary:{{ $carte['couleur1'] }}"
                        class="mr-2">
                </lord-icon>
                Email
            </a>
        </div>
    @endif

    <!-- PDF -->
    @if($carte['pdf'])
        <div class="w-full h-full flex justify-center items-center mt-2">
            <a href="{{ $carte['PDF'] }}"
               class="w-full h-12 mx-2 px-2 text-center bg-white font-bold rounded-lg border border-gray-200 text-gray-800 flex items-center">
                <lord-icon
                        src="https://cdn.lordicon.com/wzwygmng.json"
                        trigger="loop"
                        delay="1000"
                        colors="primary:#000000,secondary:{{ $carte['couleur1'] }}"
                        class="mr-2">
                </lord-icon>
                {{$carte['nomBtnPdf']}} (PDF)
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
                        colors="primary:#000000,secondary:{{ $carte['couleur1'] }}"
                        class="mr-2">
                </lord-icon>
                Prendre un rendez-vous
            </a>
        </div>
    @endif

    <!-- partage -->
    <div class="w-full h-full flex justify-center items-center mt-2">
        <!-- url de partage (actuel + les parametres) -->
        <a href=" {{ url()->current().'?idCompte='.$carte->compte->idCompte }}"
           class="w-full h-12 mx-2 px-2 text-center bg-white font-bold rounded-lg border border-gray-200 text-gray-800 flex items-center">
            <lord-icon
                    src="https://cdn.lordicon.com/udwhdpod.json"
                    trigger="loop"
                    delay="1000"
                    colors="primary:#000000,secondary:{{ $carte['couleur1'] }}"
                    class="mr-2">
            </lord-icon>
            Partager
        </a>
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

<!-- Custom Links -->
<div class="w-full mt-4 flex flex-wrap items-center justify-center gap-4">
    @foreach($custom as $link)
        <a href="{{ $link['lien'] }}"
           class="w-36 h-20 flex flex-col items-center justify-center bg-white font-bold rounded-lg text-gray-800 text-center p-3 border border-gray-200 transition-shadow duraion-300">
            <lord-icon
                    src="https://cdn.lordicon.com/gsjfryhc.json"
                    trigger="loop"
                    delay="1000"
                    colors="primary:#000000,secondary:{{ $carte['couleur1'] }}"
                    class="w-8 h-8 mb-2">
            </lord-icon>
            <span class="text-sm">{{ $link['nom'] }}</span>
        </a>
    @endforeach
</div>


</body>
</html>